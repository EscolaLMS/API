<?php

return [
    /**
     * Defaults settings
     */
    'default_gateway' => env('PAYMENTS_DEFAULT_GATEWAY', 'Stripe'),
    'default_currency' => env('PAYMENTS_DEFAULT_CURRENCY', EscolaLms\Payments\Enums\Currency::USD),

    /**
     * Eloquent models settings
     */

    'payment_model' => env('PAYMENTS_PAYMENT_MODEL', EscolaLms\Payments\Models\Payment::class),
    'fallback_billable_model' => env('PAYMENTS_BILLABLE_MODEL', App\Models\User::class),

    /**
     * Urls settings
     */

    'url_redirect' => env('PAYMENTS_REDIRECT_URL', '/'),
    'url_notification' => null,

    /** 
     * Driver specific settings 
     */
    'drivers' => [
        'free' => [],
        'stripe' => [
            'key' => env('PAYMENTS_STRIPE_SECRET_KEY', 'sk_test_51Ig8icJ9tg9t712TG1Odn17fisxXM9y01YrDBxC4vd6FJMUsbB3bQvXYs8Oiz9U2GLH1mxwQ2BCjXcjc3gxEPKTT00tx6wtVco'),
            'publishable_key' => env('PAYMENTS_STRIPE_PUBLISHABLE_KEY', 'pk_test_51Ig8icJ9tg9t712TnCR6sKY9OXwWoFGWH4ERZXoxUVIemnZR0B6Ei0MzjjeuWgOzLYKjPNbT8NbG1ku1T2pGCP4B00GnY0uusI'),
        ]
    ]
];
