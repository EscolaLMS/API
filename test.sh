#!/bin/sh
php artisan key:generate
php artisan migrate:fresh --seed
php artisan passport:keys --force
php artisan passport:client --personal --no-interaction
cp storage/oauth-private.key vendor/orchestra/testbench-core/laravel/storage/oauth-private.key
./vendor/bin/phpunit
