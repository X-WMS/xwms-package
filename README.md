XWMS Package
Secure, advanced login and authentication APIs for businesses

Overview
XWMS Package is a PHP library providing secure and sophisticated login and authentication APIs designed for enterprise environments. Our APIs support complex identity management workflows, integration with existing systems, and comply with stringent security standards.

This package is built for businesses requiring robust authentication mechanisms beyond simple login solutions — ensuring reliability, security, and scalability.

Features
Secure token-based authentication (JWT, OAuth2 compatible)

Support for multi-factor authentication (MFA)

Integration-ready with existing enterprise systems

Robust user verification workflows

Detailed error handling and logging

Compliance with GDPR and industry security standards

Lightweight and easy to integrate with PHP projects

Compatible with frameworks like Laravel, Symfony, or plain PHP

## Installation (Laravel)

Step 1: Install the package via Composer

```bash
composer require xwms/package
```

Step 2: Publish the config file

```bash
php artisan vendor:publish --tag=xwms-config
```

Step 3: Configure your `.env` file
Add your XWMS credentials and settings to `.env`:

```bash
XWMS_CLIENT_ID="your_client_id"
XWMS_CLIENT_SECRET="your_secret"
XWMS_REDIRECT_URI="http://example.com/xwms/validateToken"

```

Step 4: Add XWMS API routes
Add the following routes to `routes/web.php` or any other route file:

```bash
<?php

use Illuminate\Support\Facades\Route;
use XWMS\Package\Controllers\Api\XwmsApiHelper;

// ------------------------------------------------------
// --------- XWMS API
// ------------------------------------------------------

Route::get('/xwms/info', [XwmsApiHelper::class, 'info'])->name('xwms.api.info');
Route::get('/xwms/auth', [XwmsApiHelper::class, 'auth'])->name('xwms.api.auth');
Route::get('/xwms/validateToken', [XwmsApiHelper::class, 'authValidate'])->name('xwms.api.validateToken');

```

✅ That’s it! You’re now ready to use the XWMS authentication APIs in your Laravel app.

