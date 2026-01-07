# BangunanPro - Sistem ERP Toko Bangunan

![BangunanPro Logo](C:/Users/raymo/.gemini/antigravity/brain/d073fad1-b9ac-4786-b90d-aa13b6ed84fd/bangunanpro_final_logo_1767716903092.png)

**Sistem manajemen toko bangunan profesional berbasis web dengan arsitektur modern.**

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?logo=laravel)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-4E56A6?logo=livewire)](https://livewire.laravel.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC?logo=tailwind-css)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?logo=alpine.js)](https://alpinejs.dev)

---

## 🎯 Fitur Utama

### 📊 **Dashboard & Analitik**
- Real-time sales metrics
- Profit margin tracking
- Inventory valuation
- Low stock alerts
- Customer analytics

### 🛒 **Manajemen Penjualan**
- Point of Sale (POS) system
- Multi-item cart
- Discount management (percentage/fixed)
- Customer tracking
- Receipt printing
- Sale history & reports

### 📦 **Manajemen Stok**
- Real-time inventory tracking
- Stock movement history
- Low stock alerts
- Automatic reorder suggestions
- Bulk stock adjustments
- Physical inventory counting

### 🏗️ **Manajemen Produk**
- Product CRUD operations
- Price history tracking
- Cost & markup management
- Product categories
- Product aliases
- Barcode support

### 🤝 **Manajemen Pelanggan**
- Customer database
- Purchase history
- Credit limit management
- Customer segmentation (retail/wholesale/contractor)
- Customer analytics

### 🏭 **Manajemen Supplier**
- Supplier database
- Purchase order management
- Outstanding balance tracking
- Credit terms management

### 👥 **Multi-User & Roles**
- Role-based access control (Admin, Manager, Cashier)
- User activity logging
- Audit trails

---

## 🏗️ Arsitektur Modern

Dibangun dengan pola arsitektur profesional:

```
┌─────────────┐
│   Livewire  │  ← UI Layer (Presentation)
│ Components  │
└──────┬──────┘
       │
┌──────▼──────┐
│  Services   │  ← Business Logic Layer
│  Layer      │
└──────┬──────┘
       │
┌──────▼──────┐
│ Repository  │  ← Data Access Layer
│  Pattern    │
└──────┬──────┘
       │
┌──────▼──────┐
│   Models    │  ← Database Layer
└─────────────┘
```

**Keuntungan:**
- ✅ Highly testable
- ✅ Reusable business logic  
- ✅ Clean separation of concerns
- ✅ Easy to maintain and scale
- ✅ Type-safe with custom exceptions

---

## 🚀 Quick Start

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Git

### Installation

1. **Clone repository**
```bash
git clone <your-repo-url> bangunanpro
cd bangunanpro
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
copy .env.example .env
php artisan key:generate
```

4. **Configure database** 
Edit `.env` file:
```env
APP_NAME="BangunanPro"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bangunanpro
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations & seeders**
```bash
php artisan migrate --seed
```

6. **Build assets**
```bash
npm run build
# or for development
npm run dev
```

7. **Start development server**
```bash
php artisan serve
```

8. **Access the application**
- URL: `http://localhost:8000`
- Admin Email: `admin@bangunanpro.com`
- Password: `password`

---

## 🔐 Default Users

After running seeders, you'll have these test accounts:

| Role | Email | Password | Access Level |
|------|-------|----------|--------------|
| **Admin** | admin@bangunanpro.com | password | Full access |
| **Manager** | budi@bangunanpro.com | password | Sales, inventory, reports |
| **Cashier** | siti@bangunanpro.com | password | POS, basic sales |

---

## 📁 Project Structure

```
bangunanpro/
├── app/
│   ├── Events/              # Domain events
│   ├── Exceptions/          # Custom exceptions
│   ├── Livewire/            # UI components
│   ├── Models/              # Eloquent models
│   ├── Observers/           # Model observers
│   ├── Repositories/        # Data access layer
│   └── Services/            # Business logic layer
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/             # Sample data
├── resources/
│   ├── views/
│   │   ├── components/      # Blade components
│   │   └── livewire/        # Livewire views
│   └── css/                 # Tailwind styles
└── public/                  # Assets
```

---

## 🧪 Testing

Run the test suite:

```bash
php artisan test
```

Run specific test suites:
```bash
# Unit tests
php artisan test --testsuite=Unit

# Feature tests
php artisan test --testsuite=Feature
```

---

## 📝 Key Technologies

- **Backend**: Laravel 11.x
- **Frontend**: Livewire 3.x, Alpine.js 3.x, TailwindCSS 3.x
- **Database**: MySQL 8.x
- **Authentication**: Laravel Breeze
- **Real-time**: Livewire reactive components
- **Icons**: Font Awesome 6.x

---

## 🎨 Customization

### Changing App Name
Update `.env`:
```env
APP_NAME="Your Store Name"
```

### Theme Colors
Edit `tailwind.config.js`:
```js
colors: {
  primary: '#1565C0',
  secondary: '#FF9800',
}
```

### Logo
Replace logo in `resources/views/layouts/app.blade.php`

---

## 📊 Database Schema

### Key Tables
- `users` - User authentication & roles
- `products` - Product catalog with pricing
- `categories` - Product categories
- `suppliers` - Supplier management
- `customers` - Customer database
- `sales` - Sale transactions
- `sale_items` - Sale line items
- `purchases` - Purchase orders
- `stock_movements` - Inventory tracking
- `product_price_history` - Price change tracking

---

## 🔒 Security

- CSRF protection enabled
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade templating)
- Password hashing (bcrypt)
- Role-based authorization
- Environment variable protection

---

## 🐛 Troubleshooting

**Migration errors:**
```bash
php artisan migrate:fresh --seed
```

**Asset build issues:**
```bash
npm run build
```

**Cache clear:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## 📈 Roadmap

- [ ] Multi-warehouse support
- [ ] Barcode scanner integration
- [ ] Export to PDF/Excel
- [ ] WhatsApp notifications
- [ ] Mobile app (PWA)
- [ ] Multi-currency support
- [ ] Advanced reporting

---

## 🤝 Contributing

Contributions welcome! Please read [CONTRIBUTING.md](CONTRIBUTING.md) first.

---

## 📄 License

This project is licensed under the MIT License.

---

## 💬 Support

For support and questions:
- 📧 Email: support@bangunanpro.com
- 📱 WhatsApp: +62 xxx-xxxx-xxxx
- 📚 Documentation: [docs.bangunanpro.com](https://docs.bangunanpro.com)

---

**Built with ❤️ for Indonesian hardware stores**
