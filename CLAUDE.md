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

### API Structure

- **API Authentication**: Laravel Sanctum for token-based authentication
- **API Routes**: Defined in `routes/api.php` (currently minimal, mainly `/api/user` endpoint)
- **API Middleware**: `auth:sanctum` for protected endpoints
- API responses follow standard Laravel JSON response format

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
- **User System**: Includes referral tracking with hierarchical user relationships (parent-child structure)

### Frontend/Backend Asset Separation

Static assets are physically separated in `/asset/`:
- `/asset/admin/` - Admin panel assets
- `/asset/frontend/` - User-facing assets
- `/asset/images/`, `/asset/webfonts/` - Shared resources

### Multi-language Support

- Language files in `resources/lang/`
- Dynamic language management via `Language` model
- JSON-based translations in `resources/views/all.json` and `sections.json`

### Push Notifications & Messaging

The system includes multiple notification channels:

- **Firebase Cloud Messaging (FCM)**: Push notifications to web/mobile clients
  - Configuration in `config/firebase.php`
  - Service worker: `public/firebase-messaging-sw.js`
  - User FCM tokens stored in `users.fcm_token` column
  - Helper function: `sendFCMNotification($user, $title, $body, $data, $url)` in `app/Http/Helpers/helpers.php:499`
- **WhatsApp Integration**: SMS/WhatsApp messaging via helper function
  - Helper function: `sendWhatsApp($phoneNumber, $message)` in `app/Http/Helpers/helpers.php:438`
- **SMS Providers**: Multiple SMS gateway integrations
  - Nexmo (Vonage) - configured via `nexmo/laravel` package
  - Infobip - configured via `pnlinh/laravel-infobip-sms` package
- **Email Notifications**: Template-based email system
  - Managed via `EmailTemplate` model and admin panel
  - Helper functions: `sendMail($key, $data, $user)` and `sendGeneralMail($data)`
- **Notification Order**: FCM notifications are sent before WhatsApp with a 5-second delay (as per recent commits)

**Notification Flow:**
1. User registers/updates preferences → FCM token saved via `/save-fcm-token` route
2. Admin triggers notification → `sendFCMNotification()` helper called
3. System sends FCM push first, then WhatsApp after delay
4. Notifications logged in Laravel notification system (`app/Notifications/`, `app/Listeners/`)

### Key Helper Functions

Global helper functions in `app/Http/Helpers/helpers.php` (auto-loaded):

- **File Management**:
  - `uploadImage($file, $location, $size, $old, $thumb)` - Upload and resize images (uses Intervention Image)
  - `makeDirectory($path)` - Create directories with proper permissions
  - `removeFile($path)` - Delete files safely
  - `getFile($folder_name, $filename)` - Retrieve file URLs
  - `filePath($folder_name)` - Get file storage paths

- **Content & Data**:
  - `content($key)` - Retrieve dynamic content from database
  - `element($key, $take)` - Get content elements with limit
  - `frontendFormatter($key)` - Format frontend content keys
  - `convertHtml($content)` - HTML purification (uses Mews Purifier)
  - `replaceBaseUrl($content)` - Update URLs in content
  - `variableReplacer($code, $value, $template)` - Template variable replacement

- **User & Referral System**:
  - `getUserWithChildren($userId)` - Get user hierarchy (referral tree)
  - `referMoney($user, $amount)` - Process referral commissions

- **UI & Navigation**:
  - `getMenus($type)` - Retrieve header/footer menus
  - `menuActive($routeName)` - Highlight active menu items

- **Utilities**:
  - `verificationCode($length)` - Generate verification codes
  - `gatewayImagePath()` - Get payment gateway image paths
  - `nonEditableRender($file)` - Render non-editable templates

### Social Media & Sharing

- Uses `jorenvanhocht/laravel-share` package for social media sharing
- Cookie consent managed via `spatie/laravel-cookie-consent`

## Environment Setup

**Requirements:**
- PHP ^8.2 (as specified in composer.json)
- MySQL/MariaDB database
- Node.js and NPM for asset compilation

**Configuration:**

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
- **Migrations**: Primary database schema is in `/install/database.sql`. Some feature-specific migrations exist in `/core/database/migrations/` (e.g., FCM token support). Run both installation SQL and `php artisan migrate` for full setup
- **Models**: No `Visa` model file exists; visa data is handled through other models (`VisaFileField`, `VisaStatusLog`) and database queries
- **Image Processing**: Uses `intervention/image` package for image uploads, resizing, and thumbnail generation. All image operations go through the `uploadImage()` helper
- **Security**: HTML content is sanitized using `mews/purifier` package via the `convertHtml()` helper to prevent XSS attacks
- **QR Codes**: Two-factor authentication QR codes generated using `bacon/bacon-qr-code` package
