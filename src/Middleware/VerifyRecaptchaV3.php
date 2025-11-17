<?php

namespace Ruruchen2023\Recaptcha\Middleware;

use Closure;
use Ruruchen2023\Recaptcha\RecaptchaService;

class VerifyRecaptchaV3
{
    public function handle($request, Closure $next, $action = null, $min_score = null)
    {
        // Disable reCAPTCHA check when not enabled
        if (config('recaptcha.enable') !== true) {
            return $next($request);
        }

        $token = $request->input('recaptcha_token');

        if (!$token) {
            return response()->json(['message' => 'Missing reCAPTCHA token'], 400);
        }

        $result = app('recaptcha')->verify($token, $action, $request->ip());

        $min_score = $min_score ?? config('recaptcha.min_score');

        if (!data_get($result, 'success') || data_get($result, 'score') < $min_score) {
            return response()->json(['message' => 'Failed reCAPTCHA verification'], 400);
        }

        return $next($request);
    }
}