# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 8-based visa application management system ("Estuairevisa") that handles visa applications, payments, user management, and administrative functions. The application supports multiple payment gateways and includes both frontend (user-facing) and backend (admin) interfaces.

## Project Structure

The codebase has a non-standard Laravel structure:

- **`/core/`** - Contains the Laravel application (not in root)
- **`/asset/`** - Static assets separated into `admin/` and `frontend/` subdirectories
- **`/install/`** - Installation wizard files
- **`/index.php`** - Entry point that routes to `/core/` Laravel app

### Core Laravel Structure (inside `/core/`)

- **`app/Http/Controllers/`** - Split into:
  - `Admin/` - Backend admin controllers (visa management, users, settings, gateways)
  - `Auth/` - Authentication controllers
  - `Gateway/` - Payment gateway integration controllers (Stripe, PayPal, Razorpay, Mollie, etc.)
  - Root level - Frontend controllers (VisaController, VisaApplyController, UserController)
- **`app/Models/`** - Key models include:
  - `Visa`, `VisaFileField`, `VisaStatusLog` - Visa management
  - `User`, `Admin` - User management
  - `Payment`, `Checkout`, `CheckoutLog` - Payment processing
  - `Gateway`, `Deposit`, `Transaction` - Payment gateways
  - `Country`, `Plan` - Configuration data
- **`app/Http/Helpers/helpers.php`** - Global helper functions (auto-loaded via composer.json)
- **`resources/views/`** - Blade templates organized into:
  - `backend/` - Admin panel views
  - `frontend/` - User-facing views
- **`routes/web.php`** - All web routes (admin routes prefixed with `admin.`)

## Common Development Commands

### Development Workflow

**Working directory:** All commands must be run from the `/core/` directory

```bash
cd core
```

### PHP/Laravel Commands

```bash
# Install PHP dependencies
composer install

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Generate application key
php artisan key:generate

# Start development server
php artisan serve
```

### Frontend Assets

```bash
# Install Node dependencies
npm install

# Development build
npm run dev

# Watch for changes
npm run watch

# Production build
npm run prod
```

## Key Architectural Patterns

### Route Organization

- Admin routes: Prefixed with `admin.`, middleware `['admin', 'demo']`
- User routes: Middleware `auth`, `verified`, `google2fa`
- Payment gateway routes: Organized by provider in `Gateway/` namespace

### Authentication & Authorization

- Multiple auth guards: `admin` and `web` (users)
- Two-factor authentication via Google2FA
- Role-based access using Spatie Laravel Permission package
- Social authentication via Laravel Socialite

### Payment Processing

The system supports 15+ payment gateways:
- Stripe, PayPal, Razorpay, Mollie
- Cryptocurrency: Coinpayments, Nowpayments
- Regional: Paytm (India), Paystack, Flutterwave (Africa), Paghiper (Brazil)

Each gateway has its own controller in `app/Http/Controllers/Gateway/`

### Database Architecture

- **Visa System**: `visas`, `visa_file_fields`, `visa_status_logs` tables track visa types, required documents, and application status
- **Payments**: `checkouts`, `checkout_logs`, `payments`, `transactions`, `deposits` track payment flows
- **Configuration**: `general_settings`, `countries`, `plans`, `gateways` store app configuration

### Frontend/Backend Asset Separation

Static assets are physically separated in `/asset/`:
- `/asset/admin/` - Admin panel assets
- `/asset/frontend/` - User-facing assets
- `/asset/images/`, `/asset/webfonts/` - Shared resources

### Multi-language Support

- Language files in `resources/lang/`
- Dynamic language management via `Language` model
- JSON-based translations in `resources/views/all.json` and `sections.json`

## Environment Setup

1. Copy `.env.example` to `.env` (if exists)
2. Configure database credentials:
   - `DB_DATABASE`
   - `DB_USERNAME`
   - `DB_PASSWORD`
   - `DB_HOST`
3. Run installation wizard at `/install/index.php` if fresh install
4. Installation creates `/install/installed` flag file

## Important Notes

- **Working Directory**: Laravel app is in `/core/`, not root
- **Artisan Commands**: Must be run from `/core/` directory
- **Asset Compilation**: Laravel Mix configured in `/core/webpack.mix.js`
- **Custom Helpers**: Global functions in `app/Http/Helpers/helpers.php` (auto-loaded)
- **Installation Check**: `index.php` redirects to installer if `/install/installed` doesn't exist
- **Demo Mode**: Protected by `demo` middleware on admin routes
