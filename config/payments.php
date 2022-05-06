<?php

return [
    /**
     * Defaults settings
     */
    'default_gateway' => env('PAYMENTS_DEFAULT_GATEWAY', 'Stripe'),
    'default_currency' => env('PAYMENTS_DEFAULT_CURRENCY', 'USD'),

    /**
     * Driver specific settings
     */
    'drivers' => [
        'free' => [],
        'stripe' => [
            'enabled' => true,
            'secret_key' => env('PAYMENTS_STRIPE_SECRET_KEY'),
            'publishable_key' => env('PAYMENTS_STRIPE_PUBLISHABLE_KEY'),
            'allowed_payment_method_types' => ['card'],
        ],
        'przelewy24' => [
            'enabled' => true,
            'live' => env('PAYMENTS_PRZELEWY24_LIVE', false),
            'merchant_id' => env('PAYMENTS_PRZELEWY24_MERCHANT_ID'),
            'pos_id' => env('PAYMENTS_PRZELEWY24_POS_ID'),
            'api_key' => env('PAYMENTS_PRZELEWY24_API_KEY'),
            'crc' => env('PAYMENTS_PRZELEWY24_CRC'),
        ],
    ]
];
