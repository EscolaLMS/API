#!/bin/bash

# disable paritcular supervisor job by deleting jobs files 

if [ "$DISBALE_PHP_FPM" == 'true' ]
then
    rm -f /etc/supervisor/conf.d/php-fpm.conf
    echo php-fpm.conf disabled
fi

if [ "$DISBALE_HORIZON" == 'true' ]
then
    rm -f /etc/supervisor/custom.d/horizon.conf
    echo horizon.conf disabled
fi

if [ "$DISBALE_CADDY" == 'true' ]
then
    rm -f /etc/supervisor/custom.d/caddy.conf
    echo caddy.conf disabled
fi

if [ "$DISBALE_SCHEDULER" == 'true' ]
then
    rm -f /etc/supervisor/custom.d/scheduler.conf
    echo scheduler.conf disabled
fi

# set env from `LARAVEL_` prefixed env vars
php docker/envs/envs.php

# if binded by k8s or docker those folders might need to be recreated
mkdir storage
mkdir storage/framework
mkdir storage/framework/sessions
mkdir storage/framework/views
mkdir storage/framework/cache
mkdir storage/app
mkdir storage/logs

# run all laravel related tasks 

php artisan config:cache 
php artisan key:generate --force --no-interaction
php artisan passport:keys --force --no-interaction 
php artisan migrate --force
php artisan passport:client --personal --no-interaction
php artisan db:seed --class=PermissionsSeeder --force --no-interaction
php artisan storage:link --force --no-interaction
php artisan h5p:storage-link
