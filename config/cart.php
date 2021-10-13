<?php

return [
    'cart_owner_model' => env('CART_OWNER_MODEL', class_exists(App\Models\User::class) ? App\Models\User::class : EscolaLms\Cart\Models\User::class),
];
