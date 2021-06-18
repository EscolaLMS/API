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
            'key' => env('PAYMENTS_STRIPE_KEY', 'sk_test_51I6fE0FHAZ5Pnnlr2l21VJwGXrsnsUzUZ4om4l6fJmjriJ1ScZEBRzwFFw6stsn1h30ldDnpMfs1Gw7uE9N2uVGH00PcJYHZJ0'),
        ]
    ]
];
