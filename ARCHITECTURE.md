# BangunanPro Architecture

## Overview

BangunanPro is built using a **layered architecture** pattern that separates concerns and promotes testability, maintainability, and scalability. This document explains the architectural decisions and patterns used throughout the application.

## Technology Stack

### Backend
- **Framework:** Laravel 12.x
- **Language:** PHP 8.2+
- **Database:** MySQL 8.0+ / MariaDB 10.5+
- **Cache:** Redis (production) / Database (development)
- **Queue:** Database driver (can scale to Redis/SQS)

### Frontend
- **UI Framework:** Livewire 3.x (TALL stack)
- **JavaScript:** Alpine.js 3.x
- **CSS:** TailwindCSS 3.x
- **Build Tool:** Vite 4.x

### Development Tools
- **Testing:** Pest PHP
- **Code Quality:** Laravel Pint, PHPStan (Larastan)
- **Package Management:** Composer, NPM

## Architectural Layers

```
┌─────────────────────────────────────────────────────┐
│              UI Layer (Presentation)                │
│                                                     │
│  Livewire Components │ Blade Templates             │
│  - Forms         │ - Views                       │
│  - Tables        │ - Layouts                     │
│  - Modals        │ - Components                  │
└──────────────────┬──────────────────────────────────┘
                   │
┌──────────────────▼──────────────────────────────────┐
│            Service Layer (Business Logic)            │
│                                                     │
│  - ProductService    - SaleService                  │
│  - StockService      - PurchaseService              │
│  - Business rules    - Calculations                 │
│  - Validations       - Transformations              │
└──────────────────┬──────────────────────────────────┘
                   │
┌──────────────────▼──────────────────────────────────┐
│        Repository Layer (Data Access)                │
│                                                     │
│  - ProductRepository  - SaleRepository              │
│  - Query abstraction  - Data persistence            │
│  - Complex queries    - Caching logic               │
└──────────────────┬──────────────────────────────────┘
                   │
┌──────────────────▼──────────────────────────────────┐
│            Model Layer (Domain)                      │
│                                                     │
│  - Eloquent Models   - Relationships                │
│  - Attributes        - Scopes                       │
│  - Mutators/Accessors - Observers                  │
└──────────────────┬──────────────────────────────────┘
                   │
┌──────────────────▼──────────────────────────────────┐
│              Database Layer                          │
│                                                     │
│  - MySQL / MariaDB                                  │
│  - Migrations   - Seeders   - Factories              │
└─────────────────────────────────────────────────────┘
```

## Design Patterns

### 1. Repository Pattern

**Purpose:** Abstract data access logic from business logic.

**Location:** `app/Repositories/`

**Example:**
```php
// Interface
interface ProductRepositoryInterface
{
    public function findWithLowStock(int $threshold): Collection;
    public function search(string $query): Collection;
}

// Implementation
class ProductRepository implements ProductRepositoryInterface
{
    public function findWithLowStock(int $threshold): Collection
    {
        return Product::where('current_stock', '<=', 'low_stock_threshold')
            ->with(['category', 'supplier'])
            ->get();
    }
}
```

**Benefits:**
- Testable (mock repositories in tests)
- Reusable queries
- Clean separation of concerns

---

### 2. Service Layer Pattern

**Purpose:** Encapsulate business logic separate from controllers/Livewire.

**Location:** `app/Services/`

**Example:**
```php
class SaleService
{
    public function __construct(
        private ProductRepository $productRepo,
        private StockService $stockService
    ) {}
    
    public function createSale(array $data): Sale
    {
        DB::transaction(function () use ($data) {
            // 1. Create sale
            $sale = Sale::create([...]);
            
            // 2. Create sale items
            foreach ($data['items'] as $item) {
                $saleItem = $sale->items()->create($item);
                
                // 3. Update stock
                $this->stockService->decreaseStock(
                    $item['product_id'],
                    $item['quantity']
                );
            }
            
            // 4. Record audit log
            AuditLog::create([...]);
            
            return $sale;
        });
    }
}
```

**Benefits:**
- Reusable across Livewire, API, Artisan commands
- Transaction management
- Complex business logic isolation

---

### 3. Observer Pattern

**Purpose:** React to model events (created, updated, deleted).

**Location:** `app/Observers/`

**Example:**
```php
class ProductObserver
{
    public function updated(Product $product)
    {
        // Track price changes
        if ($product->isDirty('price')) {
            ProductPriceHistory::create([
                'product_id' => $product->id,
                'old_price' => $product->getOriginal('price'),
                'new_price' => $product->price,
            ]);
        }
    }
}
```

**Benefits:**
- Automatic actions on model changes
- Clean model classes
- Centralized side-effect logic

---

### 4. Event-Listener Pattern

**Purpose:** Decouple actions from their triggers.

**Location:** `app/Events/`, `app/Listeners/`

**Example:**
```php
// Event
class StockBelowThreshold
{
    public function __construct(public Product $product) {}
}

// Listener
class SendLowStockAlert
{
    public function handle(StockBelowThreshold $event)
    {
        // Send email/notification
        Mail::to('admin@bangunanpro.com')->send(
            new LowStockAlert($event->product)
        );
    }
}
```

**Benefits:**
- Async processing (queued listeners)
- Multiple listeners per event
- Easy to add new behaviors

---

## Directory Structure

```
app/
├── Console/
│   └── Commands/           # Artisan commands
├── Events/                 # Domain events
├── Exceptions/             # Custom exceptions
├── Exports/                # Excel exports
├── Http/
│   ├── Controllers/        # HTTP controllers (minimal, mostly API)
│   ├── Middleware/         # Request middleware
│   └── Requests/           # Form request validation
├── Imports/                # Excel imports
├── Listeners/              # Event listeners
├── Livewire/              # Livewire components (UI)
│   ├── Products/
│   ├── Sales/
│   ├── Customers/
│   └── Dashboard/
├── Models/                 # Eloquent models
├── Observers/              # Model observers
├── Providers/              # Service providers
├── Repositories/          # Data access layer
│   ├── Contracts/          # Repository interfaces
│   └── Eloquent/           # Eloquent implementations
├── Services/               # Business logic layer
└── View/
    └── Components/         # Blade components

database/
├── factories/              # Model factories (test data)
├── migrations/             # Database schema
└── seeders/                # Data seeders

resources/
├── css/                    # Tailwind CSS
├── js/                     # Alpine.js, app.js
└── views/
    ├── components/         # Blade components
    ├── layouts/            # Page layouts
    └── livewire/           # Livewire views

tests/
├── Feature/                # Feature tests (full stack)
└── Unit/                   # Unit tests (isolated)
```

## Data Flow

### Typical Request Flow

```
User Action (Browser)
    ↓
Livewire Component
    ↓
Service Layer (Business Logic)
    ↓
Repository (if complex query)
    ↓
Model (Eloquent ORM)
    ↓
Database
    ↓
← Response back through layers ←
    ↓
Livewire re-renders
    ↓
Updated UI
```

### Example: Creating a Sale

1. **UI:** User fills out sale form in `Livewire/Sales/CreateSale.php`
2. **Validation:** Form validated using Form Request
3. **Service:** `SaleService::createSale()` called
4. **Transaction:** Database transaction started
5. **Business Logic:**
   - Create `Sale` record
   - Create `SaleItem` records
   - Update product stock via `StockService`
   - Record stock movements
   - Create audit log
6. **Events:** `SaleCreated` event fired
7. **Listeners:** 
   - Generate PDF receipt (queued)
   - Send notification (queued)
8. **Response:** Redirect to sale details page

## Database Schema

### Key Relationships

```
users ─────┐
           ├─ sales (created_by)
roles ─────┘

categories ── products ── stock_movements
            /      │   \
suppliers ─        │    └─ product_price_history
                   │
                   ├─ sale_items ── sales ── customers
                   │
                   └─ purchase_items ── purchases ── suppliers
```

### Tables

**Core Entities:**
- `users` - System users (admin, manager, cashier)
- `roles` - User roles
- `permissions` - Fine-grained permissions (planned)

**Product Management:**
- `products` - Product catalog
- `categories` - Product categories
- `product_aliases` - Alternative product names
- `product_price_history` - Price change tracking

**Sales:**
- `sales` - Sale transactions
- `sale_items` - Line items per sale
- `customers` - Customer database

**Purchasing:**
- `purchases` - Purchase orders
- `purchase_items` - Line items per purchase
- `suppliers` - Supplier database

**Inventory:**
- `stock_movements` - All stock changes (audit trail)

**System:**
- `audit_logs` - User action logging
- `notifications` - System notifications (Laravel default)
- `jobs` - Queue jobs (Laravel default)

## Security Architecture

### Authentication & Authorization

```
User Login
    ↓
Laravel Breeze (Authentication)
    ↓
Session Management
    ↓
Role Check (Middleware)
    ↓
Permission Check (Gates/Policies)
    ↓
Authorized Action
    ↓
Audit Log
```

### Security Layers

1. **Network:** HTTPS, Firewall
2. **Application:** CSRF, XSS protection, Input validation
3. **Database:** Parameterized queries (Eloquent), Encryption
4. **Access Control:** Roles + Permissions
5. **Audit:** Action logging, Error tracking

## Scalability Considerations

### Current Architecture (0-100 customers)

- Single server
- Database driver for cache/queue
- Suitable for MVP and early growth

### Future Scaling Path (100-1000 customers)

1. **Separate Services:**
   - App server(s)
   - Database server
   - Redis for cache/sessions
   - Queue workers

2. **Database Optimization:**
   - Read replicas
   - Query optimization
   - Proper indexing

3. **Caching Strategy:**
   - Redis/Memcached
   - CDN for static assets
   - API response caching

4. **Queue Workers:**
   - Background job processing
   - Email sending
   - Report generation
   - Data imports/exports

5. **Load Balancing:**
   - Multiple app servers
   - HAProxy/Nginx load balancer
   - Sticky sessions for Livewire

## Multi-Tenancy (SaaS Architecture)

### Tenant Isolation Strategy

**Option 1: Column-Based (Current)**
```php
// Add tenant_id to all tenant-specific tables
class Product extends Model
{
    protected static function booted()
    {
        static::addGlobalScope('tenant', function ($query) {
            $query->where('tenant_id', auth()->user()->tenant_id);
        });
    }
}
```

**Option 2: Database-Per-Tenant (Future)**
```php
// Dynamic database connection
config(['database.connections.tenant' => [
    'database' => "tenant_{$tenantId}",
    // ... other config
]]);
```

### Tenant Configuration

- Subdomain routing: `{tenant}.bangunanpro.com`
- Tenant middleware for request isolation
- Shared tables: `users`, `tenants`, `plans`
- Isolated tables: `products`, `sales`, `customers`, etc.

## Performance Optimization

### Implemented

- Eager loading relationships
- Database indexes on foreign keys
- Livewire lazy loading
- Asset optimization (Vite)

### Planned

- Query result caching
- Redis for sessions/cache
- CDN for static assets
- Database query optimization
- Image optimization (lazy loading, WebP)

## Testing Strategy

### Unit Tests
- Services (business logic)
- Repositories (query logic)
- Models (methods, scopes)

### Feature Tests
- API endpoints
- Livewire components
- Authentication flows
- Authorization rules

### Integration Tests
- Complete user flows
- Multi-step processes
- Third-party integrations

## Deployment Architecture

See [DEPLOYMENT.md](DEPLOYMENT.md) for detailed deployment strategy and infrastructure recommendations.

## Future Enhancements

1. **API Layer:** RESTful API for third-party integrations
2. **Mobile App:** React Native / Flutter app
3. **WebSocket:** Real-time updates (Laravel Echo + Pusher)
4. **Microservices:** Separate services for reporting, analytics
5. **ElasticSearch:** Advanced search capabilities
6. **GraphQL:** Alternative API approach

## Questions?

For architecture-related questions, please:
- Open a GitHub discussion
- Review existing architecture decisions in issues
- Contact the development team

---

**Last Updated:** January 2026
**Version:** 0.1.0
