<?php

return [
    'secret' => env('RECAPTCHA_SECRET'),
    'enable' => env('RECAPTCHA_ENABLE'),
    'min_score' => 0.5,
];