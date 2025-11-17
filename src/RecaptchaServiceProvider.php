<?php

namespace Ruruchen2023\Recaptcha;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Ruruchen2023\Recaptcha\Middleware\VerifyRecaptchaV3;

class RecaptchaServiceProvider extends ServiceProvider
{
    public function register()
    {
        // 合併設定檔
        $this->mergeConfigFrom(__DIR__ . '/../config/recaptcha.php', 'recaptcha');

        // 註冊服務
        $this->app->singleton('recaptcha', function ($app) {
            return new RecaptchaService(config('recaptcha'));
        });
    }

    public function boot(Router $router)
    {
        // 匯出設定檔
        $this->publishes([
            __DIR__ . '/../config/recaptcha.php' => config_path('recaptcha.php'),
        ], 'config');

        // 註冊 middleware
        $router->aliasMiddleware('recaptcha.v3', VerifyRecaptchaV3::class);
    }
}