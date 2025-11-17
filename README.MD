# Laravel reCAPTCHA v3 SDK

A lightweight and easy-to-use Laravel package for verifying **Google reCAPTCHA v3** requests.  
This SDK provides:

- ✔️ RecaptchaService (simple server-side verification)
- ✔️ Middleware (`recaptcha.v3`)
- ✔️ Config publish (`config/recaptcha.php`)
- ✔️ Laravel 5.8 / PHP 7.3 support

Perfect for login, registration, purchase flows, and bot detection.

---

## 🚀 Installation

### 1. Install the package

If your repo is private (Git URL):

```bash
composer require yourname/laravel-recaptcha-sdk
```

If using Packagist:
```bash
composer require yourname/laravel-recaptcha-sdk
```

Laravel will auto-discover the service provider.

Publish Config
```bash
php artisan vendor:publish --tag=config
```

After publishing, you’ll get:
```bash
config/recaptcha.php
```

🔧Environment Variables

In your .env file, add:
```bash
RECAPTCHA_ENABLE=TRUE|FALSE
RECAPTCHA_SECRET=your_secret_key
```

🧩 Usage
1. Add Middleware to a Route

Your middleware is pre-registered as:
```bash
recaptcha.v3
```

Use it like this:
```bash
Route::post('/login', 'Auth\LoginController@login')->middleware('recaptcha.v3');
```

Or for any protected form submission:
```bash
Route::post('/submit', 'FormController@submit')->middleware('recaptcha.v3');
```