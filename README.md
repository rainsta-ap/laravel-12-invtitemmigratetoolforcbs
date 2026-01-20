# IMFC - Inventory Management with Livewire & Flux

A modern inventory management system built with Laravel 12, Livewire, and Flux. This application provides comprehensive tools for managing inventory items, barcodes, categories, units, packages, and stock tracking.

## Table of Contents

- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage Guide](#usage-guide)
- [Features](#features)
- [Project Structure](#project-structure)
- [Troubleshooting](#troubleshooting)

## System Requirements

### Required Versions

| Component | Version | Notes |
|-----------|---------|-------|
| **PHP** | `^8.2` | PHP 8.2 or higher (8.3 recommended) |
| **Laravel** | `^12.0` | Laravel 12 framework |
| **Node.js** | `^18.0` | For asset compilation (npm scripts) |
| **NPM** | `^9.0` | For dependency management |
| **Composer** | `^2.5` | PHP package manager |
| **Database** | MySQL 8.0+ / PostgreSQL 12+ | Recommended MySQL 8.0+ |

### Required PHP Extensions

- OpenSSL
- PDO (Database)
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON

### Recommended System Specifications

- **RAM**: 2GB minimum (4GB+ recommended)
- **Disk Space**: 500MB minimum
- **OS**: Windows 10+, macOS 10.14+, or Linux (Ubuntu 18.04+)

## Dependencies

### Backend (Composer)

```json
{
  "php": "^8.2",
  "laravel/framework": "^12.0",
  "laravel/fortify": "^1.30",
  "laravel/tinker": "^2.10.1",
  "livewire/flux": "^2.9.0"
}
```

### Frontend (NPM)

```json
{
  "@tailwindcss/vite": "^4.1.11",
  "tailwindcss": "^4.0.7",
  "vite": "^7.0.4",
  "laravel-vite-plugin": "^2.0"
}
```

### Development Dependencies

- **Testing**: PHPUnit ^11.5.3
- **Code Quality**: Laravel Pint ^1.24
- **Debugging**: Laravel Pail ^1.2.2
- **Container**: Laravel Sail ^1.41

## Installation

### Step 1: Clone or Setup Project

```bash
# Navigate to project directory
cd imfc
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies (Composer)
composer install

# Install JavaScript dependencies (NPM)
npm install
```

### Step 3: Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

Edit `.env` file and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=imfc_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 5: Run Migrations

```bash
# Create database tables
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed
```

### Step 6: Build Assets

```bash
# Development build
npm run dev

# Production build
npm run build
```

### Step 7: Start Development Server

```bash
# Option 1: Basic server
php artisan serve

# Option 2: Full development environment (requires concurrently)
composer run dev
```

The application will be available at `http://localhost:8000`

## Configuration

### Environment Variables

Key environment variables to configure:

```env
APP_NAME="IMFC"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=imfc_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_DRIVER=mailtrap
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Database Configuration

The project uses migrations for database schema. Key tables include:

- `users` - User accounts and authentication
- `invt_items` - Inventory items
- `invt_item_barcodes` - Barcode management
- `invt_item_categories` - Product categories
- `invt_item_units` - Measurement units
- `invt_item_packages` - Package information
- `invt_item_stocks` - Stock tracking
- `barang` - Product/goods master data

## 📚 Usage Guide

### Initial Setup Script

Run the quick setup command:

```bash
composer run setup
```

This will:
1. Install Composer dependencies
2. Copy `.env.example` to `.env`
3. Generate application key
4. Run database migrations
5. Install NPM dependencies
6. Build frontend assets

### Development Workflow

#### Start Development Server

```bash
# Terminal 1 - PHP Development Server
php artisan serve

# Terminal 2 - Asset Compilation (watch mode)
npm run dev

# Terminal 3 (Optional) - Queue Listener
php artisan queue:listen --tries=1
```

Or use the combined command:

```bash
composer run dev
```

#### Tinker - Interactive Shell

```bash
php artisan tinker
```

Tinker allows you to interact with your application in a command-line shell.

### Livewire Components

The project uses Livewire for reactive components. Key components:

- **BarangList** - Product listing and management
- **InvtItemMigrate** - Inventory item migration
- **SelectiveMigrate** - Selective data migration

Components are located in `app/Livewire/` directory.

### Authentication

The project uses Laravel Fortify for authentication:

- Registration available at `/register`
- Login available at `/login`
- Configured in `config/fortify.php`

## Features

### Inventory Management

- ✅ Comprehensive inventory tracking system
- ✅ Multi-warehouse support capability
- ✅ Barcode generation and management
- ✅ Category organization
- ✅ Unit and package management
- ✅ Real-time stock tracking

### Technical Features

- ✅ **Livewire Integration** - Reactive, real-time UI updates
- ✅ **Flux Components** - Modern, responsive UI toolkit
- ✅ **Tailwind CSS** - Utility-first CSS framework
- ✅ **Vite** - Lightning-fast asset bundling
- ✅ **Authentication** - Secure user authentication with Fortify
- ✅ **Database Migrations** - Version-controlled schema

## 📁 Project Structure

```
imfc/
├── app/
│   ├── Actions/          # Action classes (Fortify actions)
│   ├── Http/
│   │   └── Controllers/  # Route controllers
│   ├── Livewire/         # Livewire components
│   │   ├── BarangList.php
│   │   ├── InvtItemMigrate.php
│   │   └── SelectiveMigrate.php
│   ├── Models/           # Eloquent models
│   │   ├── Barang.php
│   │   ├── InvtItem.php
│   │   ├── InvtItemBarcode.php
│   │   ├── InvtItemCategory.php
│   │   ├── InvtItemPackge.php
│   │   ├── InvtItemStock.php
│   │   └── InvtItemUnit.php
│   └── Providers/        # Service providers
├── config/               # Configuration files
├── database/
│   ├── migrations/       # Database migrations
│   ├── factories/        # Model factories (testing)
│   └── seeders/          # Database seeders
├── resources/
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── views/            # Blade templates
├── routes/
│   ├── web.php           # Web routes
│   └── console.php       # Console commands
├── tests/                # Unit and feature tests
└── storage/              # Logs, cache, and uploads

```

## Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/YourTest.php

# Run with code coverage
php artisan test --coverage
```

## Code Quality

```bash
# Format code with Pint
composer pint

# Check code style
composer pint --check
```

## Troubleshooting

### Common Issues

#### 1. "Class not found" Error

```bash
# Clear and regenerate autoloader
composer dump-autoload

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

#### 2. Migration Fails

```bash
# Rollback migrations
php artisan migrate:rollback

# Reset database
php artisan migrate:reset

# Migrate fresh
php artisan migrate:fresh --seed
```

#### 3. Node/NPM Issues

```bash
# Clear npm cache
npm cache clean --force

# Reinstall dependencies
rm -rf node_modules
npm install
```

#### 4. Vite/Asset Compilation Issues

```bash
# Clear Vite cache
rm -rf node_modules/.vite

# Rebuild assets
npm run build
```

#### 5. Permission Errors (Linux/Mac)

```bash
# Fix storage directory permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Fix file permissions
chmod -R 644 storage/
chmod 755 storage/app/
```

### Getting Help

- Check Laravel Documentation: https://laravel.com/docs
- Livewire Documentation: https://livewire.laravel.com
- Flux Components: https://flux.laravel.com
- Create an issue in the repository

## Development Tips

### Hot Module Replacement

While running `npm run dev`, changes to assets are automatically reflected in the browser.

### Database Snapshot

```bash
# Create a fresh migration
php artisan make:migration create_table_name

# Create a fresh model with migration
php artisan make:model ModelName -m
```

### Debugging

Enable debug mode in `.env`:

```env
APP_DEBUG=true
```

Use Laravel Pail for real-time logs:

```bash
php artisan pail
```

## License

This project is open-sourced software licensed under the MIT license.

## Contributing

Contributions are welcome! Please ensure:

1. Code follows PSR-12 standard (checked with Pint)
2. Tests pass: `php artisan test`
3. Documentation is updated

## Support

For issues or questions, please:

1. Check existing documentation
2. Review error messages carefully
3. Check `.env` configuration
4. Review application logs in `storage/logs/`

---

**Last Updated**: January 2026  
**Laravel Version**: 12.0  
**PHP Version**: 8.2+
