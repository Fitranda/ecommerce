# Local Installation Guide

Step-by-step guide to set up the Multi-Tenant eCommerce Platform on a new Windows machine.

## Prerequisites

| Requirement   | Version  | Notes                                                       |
| ------------- | -------- | ----------------------------------------------------------- |
| PHP           | 8.2+     | With extensions: pdo_mysql, mbstring, openssl, tokenizer    |
| Composer      | 2.x      | PHP dependency manager                                      |
| Node.js       | 18+      | With npm                                                    |
| MySQL         | 8.0+     | Root user must have `CREATE DATABASE` privilege              |
| Nginx         | Any      | Reverse proxy for subdomain routing                         |

> **Recommended:** Install [Laragon Full](https://laragon.org/download/) — it bundles PHP 8.2+, MySQL 8, Nginx, Composer, and Node.js out of the box.

---

## Step 1 — Clone & Install Dependencies

```bash
cd D:\laragon\www
git clone <repo-url> "Ahmed Project"
cd "Ahmed Project"

composer install
npm install
```

## Step 2 — Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and set:

```dotenv
APP_NAME="Multi-Tenant eCommerce"
APP_URL=http://ecommerce.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_central
DB_USERNAME=root
DB_PASSWORD=

SESSION_DOMAIN=.ecommerce.test

TENANCY_CENTRAL_DOMAINS=ecommerce.test
```

## Step 3 — Create Database & Run Migrations

```sql
CREATE DATABASE ecommerce_central CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

> **Important:** The MySQL user must have `CREATE DATABASE` privilege. Each new tenant automatically creates its own database (e.g., `tenant_alkes`, `tenant_demo`).

```bash
php artisan migrate
php artisan storage:link
```

## Step 4 — Setup Hosts File Permission

Right-click `setup-hosts-permission.bat` → **Run as administrator**.

This script will:
1. Grant write permission on `C:\Windows\System32\drivers\etc\hosts`
2. Add `127.0.0.1 ecommerce.test` to the hosts file
3. Flush DNS cache

> This is required so the application can automatically register new tenant subdomains in the hosts file when a store is created.

## Step 5 — Configure Nginx (Laragon)

Create `D:\laragon\etc\nginx\sites-enabled\ecommerce.test.conf`:

```nginx
server {
    listen 80;
    server_name ecommerce.test *.ecommerce.test;
    root "D:/laragon/www/Ahmed Project/public";

    index index.html index.htm index.php;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
        autoindex on;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass php_upstream;
    }

    charset utf-8;

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    location ~ /\.ht {
        deny all;
    }
}
```

Then reload Nginx in Laragon: **Menu → Nginx → Reload**.

## Step 6 — Build & Run

**Development (with hot reload):**

```bash
php artisan serve
npm run dev
```

Open: http://ecommerce.test

**Production build:**

```bash
npm run build
```

---

## Docker Alternative

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running
- No other services using ports **8080**, **3307**, or **5173**

### Architecture

| Container          | Service               | Port              |
| ------------------ | --------------------- | ----------------- |
| `ecommerce-nginx`  | Nginx reverse proxy   | `localhost:8080`  |
| `ecommerce-app`    | PHP 8.2-FPM (Laravel) | internal :9000    |
| `ecommerce-mysql`  | MySQL 8.0             | `localhost:3307`  |
| `ecommerce-node`   | Vite asset builder    | (exits on finish) |

> **Note:** The `.env` file stays configured for local (Laragon) development. Docker-specific values (DB_HOST, DB_PASSWORD, etc.) are overridden via `environment:` in `docker-compose.yml`, so switching between local and Docker won't break either setup.

### Step 1 — Build & Start

```bash
docker compose up -d --build
```

Wait for the `ecommerce-node` container to finish building assets (it will exit after `npm run build`). Check progress:

```bash
docker compose logs node --follow
```

### Step 2 — Install Dependencies & Setup

```bash
# Fix vendor permissions (needed for the volume mount)
docker compose exec --user root app chown -R appuser:www-data /var/www/vendor

# Install PHP dependencies
docker compose exec app composer install --no-interaction

# Generate app key
docker compose exec app php artisan key:generate --force

# Run migrations & seed central database
docker compose exec app php artisan migrate --seed --force

# Create storage symlink
docker compose exec app php artisan storage:link --force
```

### Step 3 — Access the Application

Open in browser: **http://localhost:8080**

To access via `ecommerce.test:8080`, add to your hosts file:
```
127.0.0.1 ecommerce.test
```

### Docker — Useful Commands

```bash
# View container status
docker compose ps

# View application logs
docker compose logs app --tail 50
docker compose logs nginx --tail 50

# Enter the PHP container shell
docker compose exec app bash

# Run artisan commands
docker compose exec app php artisan <command>

# Run tests inside Docker
docker compose exec app php artisan test

# Rebuild assets after frontend changes
docker compose restart node

# Stop all containers
docker compose down

# Stop and remove all data (databases, volumes)
docker compose down -v
```

### Docker — Troubleshooting

#### Blank page (no styles/scripts)

Assets haven't been built yet. Check if the node container finished:
```bash
docker compose logs node --tail 5
```
If needed, rebuild:
```bash
docker compose restart node
```

#### "Class not found" errors

Vendor volume may be empty. Run:
```bash
docker compose exec --user root app chown -R appuser:www-data /var/www/vendor
docker compose exec app composer install --no-interaction
```

#### Cannot connect to MySQL

Ensure the MySQL container is healthy:
```bash
docker compose ps
```
If unhealthy, restart:
```bash
docker compose restart mysql
```

#### Port conflict

If port 8080 or 3307 is in use, edit `docker-compose.yml` ports section. For example change `"8080:80"` to `"8081:80"`.

---

## URL Structure

```
ecommerce.test               → Central domain (landing page, store registration)
├── /register                 → New store registration form
│
{subdomain}.ecommerce.test    → Tenant domain (per store)
├── /                         → Storefront home
├── /products                 → Product listing
├── /products/{slug}          → Product detail
├── /cart                     → Shopping cart
├── /checkout                 → Checkout (auth required)
├── /orders                   → Order history (auth required)
├── /login                    → Customer / admin login
├── /register                 → Customer registration
│
├── /admin/dashboard          → Admin dashboard (admin only)
├── /admin/products           → Product CRUD
├── /admin/categories         → Category CRUD
└── /admin/orders             → Order management
```

## How Multi-Tenancy Works

1. A user registers a new store at `ecommerce.test/register`
2. The system automatically:
   - Creates a record in the `tenants` table (central DB)
   - Creates a record in the `domains` table
   - Creates a new database: `tenant_{subdomain}`
   - Runs migrations and seeders on the tenant database
   - Adds an entry to the Windows hosts file
3. The user is redirected to `{subdomain}.ecommerce.test/login`
4. Each tenant has completely **isolated** users, products, orders, and data

## Default Accounts (per Tenant)

Each new store gets an admin account from the registration data, plus a sample customer:

| Role     | Email               | Password            |
| -------- | ------------------- | ------------------- |
| Admin    | (from registration) | (from registration) |
| Customer | customer@store.test | password            |

## Useful Artisan Commands

```bash
# List all tenants
php artisan tinker --execute="echo json_encode(App\Models\Tenant::all(['id','name','email'])->toArray(), JSON_PRETTY_PRINT);"

# Re-migrate a specific tenant
php artisan tenants:rollback --tenants=tenant_name
php artisan tenants:migrate --tenants=tenant_name
php artisan tenants:seed --tenants=tenant_name

# Migrate all tenants
php artisan tenants:migrate

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Run tests
php artisan test
```

## Troubleshooting

### "This site can't be reached" when opening a tenant subdomain

The subdomain is not registered in Windows hosts file.

1. Make sure you ran `setup-hosts-permission.bat` as Administrator (Step 4)
2. Or manually add to `C:\Windows\System32\drivers\etc\hosts`:
   ```
   127.0.0.1 storename.ecommerce.test
   ```

### Nginx shows Laragon default page

The virtual host file is missing. Create `ecommerce.test.conf` in `laragon\etc\nginx\sites-enabled\` (see Step 5).

### "Column not found" or database errors

Tenant migrations are out of sync. Run:
```bash
php artisan tenants:rollback --tenants=tenant_name
php artisan tenants:migrate --tenants=tenant_name
php artisan tenants:seed --tenants=tenant_name
```

### "Call to undefined method" errors

Run `php artisan config:clear` after modifying any configuration files.
