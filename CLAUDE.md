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
  - `Admin/` - Backend admin controllers:
    - `HomeController` - Admin dashboard
    - `ManageUserController` - User management
    - `ManageGatewayController` - Payment gateway configuration
    - `GeneralSettingController` - Site settings, SEO, analytics
    - `VisaApplyController` - Backend visa application management
    - `PlanController`, `CountryController` - Configuration management
    - `Auth/` - Admin authentication
  - `Auth/` - User authentication (login, register, social auth)
  - `Gateway/` - Payment gateway integrations (15+ gateways, each in its own subdirectory with `ProcessController.php`)
  - Root level - Frontend controllers:
    - `VisaController` - Display visa information
    - `VisaApplyController` - Handle visa applications
    - `UserController` - User profile and account management
    - `PaymentController` - Process payments
    - `TicketController` - Support ticket system
- **`app/Models/`** - Key models include:
  - `VisaFileField`, `VisaStatusLog` - Visa management (Note: no `Visa` model exists)
  - `User`, `Admin` - User management
  - `Payment`, `Checkout`, `CheckoutLog` - Payment processing
  - `Gateway`, `Deposit`, `Transaction` - Payment gateways
  - `Country`, `Plan` - Configuration data
  - `Ticket`, `TicketReply` - Support system
  - `Language`, `EmailTemplate`, `GeneralSetting` - Configuration
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

### Testing

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test suite
./vendor/bin/phpunit --testsuite=Feature
./vendor/bin/phpunit --testsuite=Unit
```

Note: Test directory structure exists (configured in `phpunit.xml`) but no test files are currently present.

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

The system supports 15+ payment gateways organized by directory:
- Stripe, PayPal, Razorpay, Mollie
- Cryptocurrency: `coinpayments/`, `nowpayments/`, `crypto/`
- Regional: `paytm/` (India), `paystack/`, `flutterwave/` (Africa), `paghiper/` (Brazil)
- Manual payment processing: `manual/`

Each gateway has its own controller directory in `app/Http/Controllers/Gateway/` with a `ProcessController.php` that handles payment flow.

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

1. Copy `.env.example` to `.env` in the `/core/` directory
2. Configure database credentials:
   - `DB_DATABASE`
   - `DB_USERNAME`
   - `DB_PASSWORD`
   - `DB_HOST` (default: 127.0.0.1)
3. Configure OAuth providers (optional):
   - Google: `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_CALLBACK_URL`
   - Facebook: `FACEBOOK_CLIENT_ID`, `FACEBOOK_CLIENT_SECRET`, `FACEBOOK_CALLBACK_URL`
4. Configure payment gateways (as needed):
   - Stripe: `STRIPE_PUBLISHABLE_KEY`, `STRIPE_SECRET_KEY`
   - Each gateway has similar configuration variables
5. Configure email/SMTP settings:
   - `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`
6. Run installation wizard at `/install/index.php` if fresh install
7. Installation creates `/install/installed` flag file to prevent re-installation

## Important Notes

- **Working Directory**: Laravel app is in `/core/`, not root. All Laravel/Artisan/Composer/NPM commands must be run from the `/core/` directory
- **Entry Point**: Root `/index.php` bootstraps the Laravel app from `/core/bootstrap/app.php`
- **Asset Compilation**: Laravel Mix configured in `/core/webpack.mix.js`
- **Custom Helpers**: Global functions in `app/Http/Helpers/helpers.php` (auto-loaded via composer.json)
- **Installation Check**: Root `index.php` redirects to installer if `/install/installed` doesn't exist
- **Demo Mode**: Admin routes are protected by `demo` middleware to prevent modifications in demo environments
- **Middleware**: Key custom middleware includes:
  - `demo` - Protects admin actions in demo mode
  - `google2fa` - Enforces two-factor authentication
  - `verified` - Email verification check
  - `LanguageManager` - Handles multi-language support
  - `CheckStatus` - Validates user account status
- **No Migrations**: Database schema is in `/install/database.sql`. Use seeders in `/core/database/seeders/` for data population
- **Models**: No `Visa` model file exists; visa data is handled through other models (`VisaFileField`, `VisaStatusLog`) and database queries
