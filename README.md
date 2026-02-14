# Multi-Tenant eCommerce Platform

A full-featured multi-tenant eCommerce platform built with **Laravel 12**, **Vue.js 3** (via Inertia.js), and **Vuetify 3** (Material Design). Each tenant (store) gets its own isolated database, ensuring complete data separation.

## Tech Stack

| Layer        | Technology                                    |
| ------------ | --------------------------------------------- |
| Backend      | Laravel 12, PHP 8.2+                          |
| Frontend     | Vue.js 3 + Inertia.js v2                      |
| UI Framework | Vuetify 3 (Material Design) + MDI Icons       |
| Multi-Tenant | stancl/tenancy v3.9 (separate DB per tenant)  |
| Database     | MySQL 8.0+                                    |
| Build Tool   | Vite 7                                        |

## Architecture

```
Central App (ecommerce.test)
├── Landing Page — platform introduction
└── Store Registration — create a new tenant

Tenant App ({store}.ecommerce.test)
├── Storefront (public)
│   ├── Home page with featured products
│   ├── Product listing with search & filters
│   ├── Product detail with add-to-cart
│   ├── Shopping cart (guest + authenticated)
│   └── Checkout & order tracking
├── Authentication
│   ├── Login / Register
│   └── Role-based access (admin / customer)
└── Admin Panel
    ├── Dashboard with revenue stats & alerts
    ├── Product CRUD with image upload
    ├── Category CRUD
    └── Order management with status updates
```

## Features

### Core Features
- **Multi-tenancy with separate databases** — each store gets a dedicated MySQL database
- **Subdomain-based tenant identification** — `{store}.ecommerce.test`
- **Automatic provisioning** — on registration: DB creation + migrations + seeding + hosts entry
- **User authentication** — login, register, logout with role-based access (admin / customer)
- **Product management** — full CRUD with categories, images, and stock tracking
- **Shopping cart** — session-based for guests, merges on login
- **Checkout & orders** — order placement with shipping info, stock decrement, order history
- **Admin dashboard** — revenue stats, recent orders, low stock alerts

### Bonus Features
- **Caching** — dashboard stats, category lists, featured products, product detail pages
- **Security** — form request validation, CSRF protection, role-based middleware, XSS prevention
- **Rate limiting** — Laravel built-in throttling on auth routes
- **Auto hosts registration** — tenant subdomains auto-added to Windows hosts file

## Quick Start

```bash
# Clone & install
git clone <repository-url>
cd "Ahmed Project"
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Create central database
mysql -u root -e "CREATE DATABASE ecommerce_central"

# Run migrations
php artisan migrate
php artisan storage:link

# Setup hosts file (run as Administrator)
# Right-click setup-hosts-permission.bat > Run as administrator

# Start development servers
php artisan serve
npm run dev
```

> **For detailed installation instructions, see [INSTALL.md](INSTALL.md).**

### Nginx Virtual Host

Create `ecommerce.test.conf` in your Nginx sites-enabled directory:

```nginx
server {
    listen 80;
    server_name ecommerce.test *.ecommerce.test;
    root "D:/laragon/www/Ahmed Project/public";

    index index.html index.htm index.php;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass php_upstream;
    }
}
```

### Docker Alternative

```bash
docker-compose up -d --build
```

Access: http://localhost:8080

### Demo Credentials (per Tenant)

| Role     | Email               | Password            |
| -------- | ------------------- | ------------------- |
| Admin    | (from registration) | (from registration) |
| Customer | customer@store.test | password            |

Visit:
- **Central app:** http://ecommerce.test
- **Tenant store:** http://{subdomain}.ecommerce.test

## Running Tests

```bash
php artisan test
```

Test coverage includes:
- **DataIsolationTest** — verifies tenants get separate databases
- **AuthTest** — login, register, logout, role-based redirects
- **ProductTest** — CRUD operations, authorization, validation
- **CartTest** — guest/user cart, checkout, stock validation

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Central/                # Central domain controllers
│   │   └── Tenant/                 # Tenant controllers
│   │       ├── Admin/              # Admin panel
│   │       ├── Auth/               # Authentication
│   │       └── ...                 # Storefront, Cart, etc.
│   ├── Middleware/
│   │   ├── EnsureUserIsAdmin.php
│   │   └── HandleInertiaRequests.php
│   └── Requests/Tenant/            # Form request validation
├── Models/
│   ├── Tenant.php                  # Central tenant model
│   └── Tenant/                     # Tenant-scoped models
│       ├── User.php
│       ├── Product.php
│       ├── Category.php
│       ├── Order.php
│       ├── Cart.php
│       └── ...
├── Providers/
│   └── TenancyServiceProvider.php
config/
│   └── tenancy.php                 # Tenancy configuration
database/
├── migrations/
│   └── tenant/                     # Tenant-specific migrations
├── seeders/
│   ├── DatabaseSeeder.php          # Central seeder
│   └── TenantDatabaseSeeder.php    # Tenant seeder (admin + sample data)
resources/js/
├── vuetify.js                      # Vuetify 3 plugin + theme config
├── Layouts/
│   ├── CentralLayout.vue           # Central domain layout
│   ├── StorefrontLayout.vue        # Tenant storefront layout
│   └── AdminLayout.vue             # Admin panel layout
├── Pages/
│   ├── Central/                    # Welcome, Register
│   ├── Tenant/
│   │   ├── Auth/                   # Login, Register
│   │   ├── Storefront/             # Home, Products, Cart, Checkout, Orders
│   │   └── Admin/                  # Dashboard, Products, Categories, Orders
routes/
├── web.php                         # Central routes
└── tenant.php                      # Tenant routes
tests/
├── Feature/
│   ├── DataIsolationTest.php
│   ├── AuthTest.php
│   ├── ProductTest.php
│   └── CartTest.php
└── TenantTestCase.php              # Base test case for tenant tests
```

## How Multi-Tenancy Works

1. **Registration** — user fills the store registration form on the central domain
2. **Tenant::create()** triggers automatic:
   - Database creation (`tenant_{subdomain}`)
   - Schema migration (products, categories, orders, carts, users)
   - Database seeding (admin user from registration + sample customer + demo data)
   - Domain creation (`{subdomain}.ecommerce.test`)
   - Hosts file entry (Windows — `127.0.0.1 {subdomain}.ecommerce.test`)
3. **Request lifecycle** — subdomain middleware identifies tenant, switches DB connection
4. **Complete isolation** — each tenant's users, products, orders, and sessions are fully separated

## License

MIT License.
