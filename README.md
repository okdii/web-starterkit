# Web Starter Kit - Multi-Tenant SaaS Architecture

A production-ready SaaS starter kit built with Laravel, featuring multi-tenancy, comprehensive ACL system, and modern frontend stack. This starter kit provides everything you need to jumpstart your SaaS application development.

## 🚀 Key Features

### Multi-Tenancy Architecture

-   **Domain-based tenant isolation** using Stancl/Tenancy package
-   Separate databases per tenant for complete data isolation
-   Central admin panel for managing all tenants
-   Automatic tenant database creation and migration
-   Domain management for each tenant

### Access Control & Permissions

-   **Role-Based Access Control (RBAC)** with Spatie Laravel Permission
-   Module-based permission system
-   Dynamic permission assignment to roles
-   Hierarchical menu system with permission-based visibility
-   Separate ACL for central admin and tenant spaces

### User Management

-   Complete user authentication system (Login, Register, Password Reset)
-   User status management (Active, Inactive, etc.)
-   Profile management with customizable settings
-   Email verification support
-   Audit logging for all user actions

### Admin Features

-   **Central Admin Dashboard** for super administrators
-   Tenant management (Create, Edit, Suspend, Delete)
-   User management across all tenants
-   Role and permission management
-   Dynamic menu builder
-   System-wide configuration management

### Developer Experience

-   **Modern Frontend Stack** with Vue 3 + Inertia.js
-   PrimeVue UI components for rich user interfaces
-   Tailwind CSS for utility-first styling
-   Form validation with VeeValidate, Yup, and Zod
-   Hot Module Replacement (HMR) for rapid development
-   TypeScript-ready configuration

## 🛠️ Technology Stack

### Backend

-   **Laravel 12.x** - Latest PHP framework
-   **PHP 8.2+** - Modern PHP features
-   **MySQL/PostgreSQL** - Database support
-   **Redis** - Caching and session management
-   **Stancl/Tenancy 3.9** - Multi-tenancy package
-   **Spatie Permission 6.x** - ACL management
-   **Laravel Auditing** - Activity logging

### Frontend

-   **Vue 3** - Progressive JavaScript framework
-   **Inertia.js 2.0** - Modern monolith architecture
-   **PrimeVue 4.x** - Comprehensive UI component library
-   **Tailwind CSS 3.x** - Utility-first CSS framework
-   **Vite 6.x** - Next-generation frontend tooling
-   **Axios** - HTTP client

### Development Tools

-   **Laravel Breeze** - Authentication scaffolding
-   **Laravel Debugbar** - Debug toolbar
-   **Laravel Pint** - Code style fixer
-   **Laravel Sail** - Docker development environment
-   **Concurrently** - Run multiple commands simultaneously

## 📋 Requirements

-   PHP >= 8.2
-   Composer >= 2.0
-   Node.js >= 18.0
-   NPM >= 9.0
-   MySQL >= 8.0 or PostgreSQL >= 13
-   Redis (optional, for caching)

## 🔧 Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd web-starterkit
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
cp .env.example .env
```

Edit the `.env` file and configure your database and other settings:

```env
APP_NAME='Your SaaS Name'
APP_URL=http://your-domain.local

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Redis Configuration (optional)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Run database migrations

```bash
# Migrate central database
php artisan migrate

# Seed initial data (roles, permissions, admin user)
php artisan db:seed
```

### 7. Build frontend assets

```bash
npm run build
```

### 8. Start development servers

```bash
# Using the custom dev script (runs all services concurrently)
composer dev

# Or run services individually:
php artisan serve
npm run dev
php artisan queue:listen
```

## 📁 Project Structure

```
web-starterkit/
├── app/
│   ├── Console/           # Console commands
│   ├── Enums/            # Enum definitions (UserStatus, TenantStatus)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Central/  # Central admin controllers
│   │   │   └── Tenant/   # Tenant-specific controllers
│   │   ├── Middleware/   # Custom middleware
│   │   ├── Requests/     # Form request validation
│   │   └── Traits/       # Reusable traits
│   ├── Models/           # Eloquent models
│   ├── Providers/        # Service providers
│   └── Services/         # Business logic services
├── config/               # Configuration files
│   ├── tenancy.php      # Multi-tenancy configuration
│   ├── permission.php   # ACL configuration
│   └── ...
├── database/
│   ├── migrations/      # Central database migrations
│   │   └── tenant/      # Tenant database migrations
│   └── seeders/
│       ├── Central/     # Central database seeders
│       └── Tenant/      # Tenant database seeders
├── resources/
│   ├── js/
│   │   ├── Components/  # Reusable Vue components
│   │   ├── Layouts/     # Application layouts
│   │   └── Pages/
│   │       ├── Central/ # Central admin pages
│   │       └── Tenant/  # Tenant pages
│   └── css/            # Stylesheets
├── routes/
│   ├── central/        # Central admin routes
│   └── tenant/         # Tenant routes
└── storage/            # File storage
```

## 🎯 Core Modules

### 1. Authentication & Authorization

-   Login/Register/Password Reset flows
-   Email verification
-   Two separate authentication contexts (Central/Tenant)
-   Session-based authentication with database driver

### 2. Tenant Management

-   Create and manage multiple tenants
-   Automatic database provisioning
-   Domain assignment and management
-   Tenant status control (Active/Suspended/Inactive)
-   Soft delete support

### 3. User Management

-   CRUD operations for users
-   Role assignment
-   Status management
-   Activity tracking with audit logs
-   Profile management

### 4. Access Control Lists (ACL)

-   **Modules**: Logical grouping of features
-   **Permissions**: Granular access control
-   **Roles**: Collection of permissions
-   **Role-Module Mapping**: Associate modules with roles
-   Dynamic permission checking in menus and UI

### 5. Menu System

-   Hierarchical menu structure
-   Permission-based visibility
-   Icon support (PrimeIcons)
-   Drag-and-drop reordering
-   Cached for performance

### 6. Audit Logging

-   Track all database changes
-   User activity monitoring
-   IP address tracking
-   Before/after value comparison
-   Searchable audit trail

## 🔐 Security Features

-   CSRF protection
-   XSS prevention
-   SQL injection protection via Eloquent ORM
-   Password hashing with Bcrypt
-   Rate limiting on authentication endpoints
-   Secure session management
-   Environment-based configuration

## 🚢 Deployment

### Production Optimization

```bash
# Install production dependencies only
composer install --no-dev --optimize-autoloader

# Build and optimize frontend assets
npm run build

# Cache configuration and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force
```

### Environment Variables

Ensure all production environment variables are properly set:

-   Set `APP_ENV=production`
-   Set `APP_DEBUG=false`
-   Configure proper database credentials
-   Set up mail configuration
-   Configure queue driver (redis/database)

## 🧪 Testing

```bash
# Run PHP tests
php artisan test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

## 📚 Documentation

### Creating a New Tenant

```php
use App\Models\Tenant;

$tenant = Tenant::create([
    'id' => 'tenant1',
    'name' => 'Tenant Name',
    'status' => 'active'
]);

$tenant->domains()->create([
    'domain' => 'tenant1.yourdomain.com'
]);
```

### Adding New Permissions

1. Create migration for new permissions
2. Add permission records to the database
3. Associate permissions with modules
4. Assign modules to roles
5. Update menu items with permission requirements

### Customizing the Frontend

-   Vue components are in `resources/js/Components/`
-   Page components are in `resources/js/Pages/`
-   Layouts are in `resources/js/Layouts/`
-   Use PrimeVue components for consistency

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

For issues, questions, or suggestions, please open an issue in the GitHub repository.

## 🙏 Credits

Built with:

-   [Laravel](https://laravel.com) - The PHP Framework for Web Artisans
-   [Vue.js](https://vuejs.org) - The Progressive JavaScript Framework
-   [Inertia.js](https://inertiajs.com) - The Modern Monolith
-   [Stancl/Tenancy](https://tenancyforlaravel.com) - Multi-tenancy for Laravel
-   [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) - Associate users with roles and permissions
-   [PrimeVue](https://primevue.org) - Next Generation Vue UI Component Library

---

**Note**: This is a starter kit intended as a foundation for your SaaS application. Customize and extend it according to your specific requirements.
