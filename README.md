# XWMS Package
Secure, advanced login and authentication APIs for businesses

## Overview
XWMS Package is a PHP library providing secure and sophisticated login and authentication APIs designed for enterprise environments. Our APIs support complex identity management workflows, integration with existing systems, and comply with stringent security standards.
This package is built for businesses requiring robust authentication mechanisms beyond simple login solutions — ensuring reliability, security, and scalability.

## Features
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
// --------- XWMS LOGIN API
// ------------------------------------------------------

Route::get('/xwms/info', [XwmsApiHelper::class, 'info'])->name('xwms.api.info');
Route::get('/xwms/auth', [XwmsApiHelper::class, 'auth'])->name('xwms.api.auth');
Route::get('/xwms/validateToken', [XwmsApiHelper::class, 'authValidate'])->name('xwms.api.validateToken');

```
✅ That’s it! You’re now ready to use the XWMS authentication APIs in your Laravel app.

```



```

## Installation (ANY AI CHATBOT)
Use this prompt to ask any AI chatbot (like ChatGPT, Gemini, Copilot, Claude, etc.) how to work with the XWMS authentication API in your preferred programming language.

What you need to do:
Paste the full prompt below into an AI chat.

Replace [your programming language] with the language you want to use (e.g. Python, JavaScript, C#, etc.).

Let the AI generate the example for you — with the correct endpoints, headers, and request structure.
You do not need to use Laravel or PHP — this works with any language.

```bash
You are an AI assistant for developers integrating the XWMS Authentication API.

I want you to generate example code in [your programming language] that interacts with the following 3 API endpoints:

1. `GET https://xwms.nl/api/info` – Fetch basic service info.
2. `POST https://xwms.nl/api/sign-token` – Start an authentication request.
3. `POST https://xwms.nl/api/sign-token-verify` – Validate a token after redirection.

These endpoints require custom headers:

- `X-Client-Id`: your client ID (e.g., "your_client_id")
- `X-Client-Secret`: your client secret (e.g., "your_secret")
- `Accept`: application/json

For `sign-token`, you must include `redirect_url` in the JSON body.

For `sign-token-verify`, you must include `token` in the JSON body.

---

Please generate example code (e.g., using `fetch`, `axios`, `requests`, `http.client`, etc. depending on the language) that does the following:

- Send a `GET` request to `/info`
- Send a `POST` request to `/sign-token` with a `redirect_url`
- Send a `POST` request to `/sign-token-verify` with a `token`

Make sure you include all headers and body structures as needed.

Use the following placeholder values:
- client ID: `your_client_id`
- client secret: `your_secret`
- redirect_url: `http://example.com/validateToken`
- token: `example-token-value`

Output only the code in [your language], nothing else. If you need to set variables or install a package (like `axios` or `requests`), include it too.
if the user did NOT replace [your programming language] please ask the user to provide an programming language first.
```

✅ That’s it! You’re now ready to use the XWMS authentication APIs in your Laravel app.



