<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return response()->json([
            'defaultCurrency' => 'EUR',
            'currencies' => ['EUR', 'USD'],
            'env' => config('app.env'),
            'stripe' => [
                'publishable_key' => config('payments.drivers.stripe.publishable_key')
            ]
        ]);
        // ...
    }
}
