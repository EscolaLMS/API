#!/bin/bash
php docker/envs/envs.php
php artisan key:generate --force --no-interaction
php artisan passport:keys --force --no-interaction
php artisan migrate --force
php artisan passport:client --personal --no-interaction
php artisan db:seed --class=PermissionsSeeder --force --no-interaction
php artisan storage:link --force --no-interaction
php artisan h5p:storage-link