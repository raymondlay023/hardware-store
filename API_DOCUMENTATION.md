# BangunanPro API Documentation

> **Note**: BangunanPro is primarily a web application using Livewire for real-time interactions. This document covers the available HTTP routes for integration purposes.

## Authentication

### Session-Based (Web)
- Uses Laravel Breeze session authentication
- CSRF protection required for all POST/PUT/DELETE requests
- Login: `/login` (POST)
- Logout: `/logout` (POST)
- Register: `/register` (POST)

### Default Test Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@bangunanpro.com | password |
| Manager | budi@bangunanpro.com | password |
| Cashier | siti@bangunanpro.com | password |

---

## Web Routes

### Public Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/health` | Health check endpoint |
| GET | `/receipt/{sale}/{token}` | Digital receipt (token-authenticated) |

### Protected Routes (Require Authentication)

#### Dashboard

| Method | Route | Description | Roles |
|--------|-------|-------------|-------|
| GET | `/dashboard` | Main dashboard | All |

#### Products

| Method | Route | Description | Roles |
|--------|-------|-------------|-------|
| GET | `/products` | Product list | All |
| GET | `/products/create` | Create product page | Admin, Manager |

#### Sales

| Method | Route | Description | Roles |
|--------|-------|-------------|-------|
| GET | `/sales` | Sales list | All |
| GET | `/sales/create` | Create sale (POS) | All |
| GET | `/sales/reports` | Sales reports | Admin, Manager |

#### Purchases

| Method | Route | Description | Roles |
|--------|-------|-------------|-------|
| GET | `/purchases` | Purchase orders list | Admin, Manager |

#### Inventory

| Method | Route | Description | Roles |
|--------|-------|-------------|-------|
| GET | `/inventory/adjust` | Stock adjustment | Admin, Manager |
| GET | `/inventory/movements` | Stock movement history | Admin, Manager |

#### Customers

| Method | Route | Description | Roles |
|--------|-------|-------------|-------|
| GET | `/customers` | Customer list | All |
| GET | `/customers/create` | Create customer | All |
| GET | `/customers/{customer}/edit` | Edit customer | Admin, Manager |

#### Suppliers

| Method | Route | Description | Roles |
|--------|-------|-------------|-------|
| GET | `/suppliers` | Supplier list | Admin, Manager |
| GET | `/suppliers/create` | Create supplier | Admin, Manager |
| GET | `/suppliers/{supplier}/edit` | Edit supplier | Admin, Manager |

#### Admin

| Method | Route | Description | Roles |
|--------|-------|-------------|-------|
| GET | `/admin/activity-logs` | Activity log viewer | Admin |
| GET | `/health/status` | Protected health check | Admin |

#### Reports

| Method | Route | Description | Roles |
|--------|-------|-------------|-------|
| GET | `/reports` | Reports dashboard | Admin, Manager |
| GET | `/reports/sales` | Sales report | Admin, Manager |
| GET | `/reports/inventory` | Inventory report | Admin, Manager |
| GET | `/reports/customers` | Customer report | Admin, Manager |
| GET | `/reports/financial` | Financial report (P&L) | Admin, Manager |

#### PDF Generation

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/pdf/sale/{sale}/receipt` | Download sale receipt PDF |
| GET | `/pdf/sale/{sale}/receipt/view` | View sale receipt PDF |
| GET | `/pdf/sale/{sale}/invoice` | Download invoice PDF |
| GET | `/pdf/purchase/{purchase}/order` | Download purchase order PDF |
| GET | `/pdf/purchase/{purchase}/order/view` | View purchase order PDF |

---

## Livewire Component Actions

> These are internal Livewire methods called via wire:click or form submissions.

### Sales (CreateSale Component)

```php
// Add item to cart
$wire.addItem()

// Remove item from cart
$wire.removeItem($index)

// Update item quantity
$wire.updateQuantity($index, $value)

// Apply quick discount
$wire.applyQuickDiscount($percentage)

// Clear discount
$wire.clearDiscount()

// Save sale
$wire.save()

// Cancel sale
$wire.cancel()
```

### Products (ProductList Component)

```php
// Save product
$wire.saveProduct()

// Delete product
$wire.deleteProduct($id)

// Start edit
$wire.editProduct($id)

// Trigger auto-reorder
$wire.triggerAutoReorder($id)
```

### Purchases (PurchaseList Component)

```php
// Create purchase
$wire.save()

// Receive purchase (update stock)
$wire.receivePurchase($id)

// Delete purchase
$wire.deletePurchase($id)
```

---

## Error Handling

### HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 302 | Redirect (after form submission) |
| 401 | Unauthorized (not logged in) |
| 403 | Forbidden (insufficient permissions) |
| 404 | Not found |
| 422 | Validation error |
| 500 | Server error |

### Custom Exceptions

```php
// Thrown when attempting to sell more than available stock
App\Exceptions\InsufficientStockException

// Thrown for business rule violations
App\Exceptions\BusinessLogicException
```

---

## Digital Receipt Token

### Generation
```php
$token = hash_hmac('sha256', $sale->id . $sale->created_at, config('app.key'));
```

### URL Format
```
/receipt/{sale_id}/{token}?expires={timestamp}
```

### Expiration
- 30 days from sale creation
- Returns 403 if expired or invalid token

---

## Rate Limiting

Currently no API rate limiting is configured. For production:
- Consider adding `throttle:60,1` middleware for API routes
- Implement per-user rate limits for sensitive operations

---

## Future REST API (Planned)

For future REST API implementation:

```
/api/v1/products       - Product CRUD
/api/v1/sales          - Sales CRUD
/api/v1/purchases      - Purchase orders
/api/v1/inventory      - Stock adjustments
/api/v1/customers      - Customer management
/api/v1/suppliers      - Supplier management
/api/v1/reports        - Report data
```

Authentication: Bearer token (Laravel Sanctum)

---

*Last Updated: January 2026*
