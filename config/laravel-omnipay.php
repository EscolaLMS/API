<?php

return [

    // The default gateway to use
    'default' => 'paypal',

    // Add in each gateway here
    'gateways' => [
        'paypal' => [
            'driver'  => 'PayPal_Express',
            'options' => [
                'username'  => '',
                'password'  => '',
                'signature' => '',
                'solutionType' => '',
                'landingPage'    => '',
                'headerImageUrl' => '',
                'brandName' =>  'Your app name',
                'testMode' => true
            ]
        ]
    ]

];
