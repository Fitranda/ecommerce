# Multi-Tenant eCommerce Platform

A full-featured multi-tenant eCommerce platform built with **Laravel 12**, **Vue.js 3** (via Inertia.js), and **Tailwind CSS**. Each tenant (store) gets its own isolated database, ensuring complete data separation.

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Vue.js 3 + Inertia.js v2
- **Styling:** Tailwind CSS
- **Multi-tenancy:** stancl/tenancy v3.9
- **Database:** MySQL (separate database per tenant)
- **Build Tool:** Vite

## Architecture

```
Central App (ecommerce.test)
├── Tenant Registration
└── Landing Page

Tenant App ({store}.ecommerce.test)
├── Storefront (public)
│   ├── Home page with featured products
│   ├── Product listing with filters
│   ├── Product detail with add-to-cart
│   ├── Shopping cart (guest + authenticated)
│   └── Checkout & order tracking
├── Authentication
│   ├── Login / Register
│   └── Role-based access (admin/customer)
└── Admin Panel
    ├── Dashboard with stats
    ├── Product CRUD
    ├── Category CRUD
    └── Order management
```

## Features

### Core Features
- **Multi-tenancy with separate databases** — each store gets a dedicated MySQL database
- **Subdomain-based tenant identification** — `{store}.ecommerce.test`
- **Automatic database creation** — on tenant registration, DB + migrations + seeding run automatically
- **User authentication** — login, register, logout with role-based access (admin/customer)
- **Product management** — full CRUD with categories, images, stock tracking
- **Shopping cart** — works for guests (session-based) and merges on login
- **Checkout & orders** — order placement with shipping info, stock decrement, order tracking
- **Admin dashboard** — revenue stats, recent orders, low stock alerts

### Bonus Features
- **Caching** — dashboard stats, category lists, featured products, product detail pages
- **Security** — form request validation, CSRF protection, role-based middleware, XSS prevention via Vue
- **Rate limiting** — Laravel built-in throttling on auth routes

## Setup Instructions

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ / npm
- MySQL 8.0+
- Laragon (recommended for Windows) or any local dev server

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd "Ahmed Project"

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Create central database
mysql -u root -e "CREATE DATABASE ecommerce_central"

# Run central migrations
php artisan migrate

# (Optional) Seed demo tenant
php artisan db:seed
```

### Local Domain Setup

Add these to your `hosts` file (`C:\Windows\System32\drivers\etc\hosts`):

```
127.0.0.1   ecommerce.test
127.0.0.1   demo.ecommerce.test
```

If using Laragon, add a virtual host for `ecommerce.test` pointing to the `public/` directory.

### Running the Application

```bash
# Start Vite dev server
npm run dev

# Start Laravel server (if not using Laragon)
php artisan serve --host=ecommerce.test --port=80
```

Visit:
- **Central app:** http://ecommerce.test
- **Demo store:** http://demo.ecommerce.test

### Demo Credentials (after seeding)

| Role     | Email               | Password |
|----------|---------------------|----------|
| Admin    | admin@store.test    | password |
| Customer | customer@store.test | password |

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
│   │   ├── Central/           # Central domain controllers
│   │   └── Tenant/            # Tenant controllers
│   │       ├── Admin/         # Admin panel
│   │       ├── Auth/          # Authentication
│   │       └── ...            # Storefront, Cart, etc.
│   ├── Middleware/
│   │   ├── EnsureUserIsAdmin.php
│   │   └── HandleInertiaRequests.php
│   └── Requests/Tenant/       # Form request validation
├── Models/
│   ├── Tenant.php             # Central tenant model
│   └── Tenant/                # Tenant-scoped models
│       ├── User.php
│       ├── Product.php
│       ├── Category.php
│       ├── Order.php
│       ├── Cart.php
│       └── ...
├── Providers/
│   └── TenancyServiceProvider.php
database/
├── migrations/
│   └── tenant/                # Tenant-specific migrations
├── seeders/
│   ├── DatabaseSeeder.php     # Central seeder
│   └── TenantDatabaseSeeder.php
resources/js/
├── Layouts/
│   ├── CentralLayout.vue
│   ├── StorefrontLayout.vue
│   └── AdminLayout.vue
├── Pages/
│   ├── Central/
│   ├── Tenant/
│   │   ├── Auth/
│   │   ├── Storefront/
│   │   └── Admin/
routes/
├── web.php                    # Central routes
└── tenant.php                 # Tenant routes
tests/
├── Feature/
│   ├── DataIsolationTest.php
│   ├── AuthTest.php
│   ├── ProductTest.php
│   └── CartTest.php
└── TenantTestCase.php         # Base test case for tenant tests
```

## How Multi-Tenancy Works

1. **Tenant Registration** — user fills form on central domain
2. **Tenant::create()** triggers automatic:
   - Database creation (`tenant_{id}`)
   - Schema migration
   - Database seeding (admin user + sample data)
   - Domain creation (`{subdomain}.ecommerce.test`)
3. **Request lifecycle** — subdomain middleware identifies tenant, switches DB connection
4. **Complete isolation** — each tenant's data lives in a separate database

## License

This project is built as a technical assessment. MIT License.
