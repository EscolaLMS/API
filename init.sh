#!/bin/bash

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

if [ "$DISBALE_NGINX" == 'true' ]
then
    rm -f /etc/supervisor/custom.d/nginx.conf
    echo nginx.conf disabled
fi

if [ "$DISBALE_SCHEDULER" == 'true' ]
then
    rm -f /etc/supervisor/custom.d/scheduler.conf
    echo scheduler.conf disabled
fi

php docker/envs/envs.php
mkdir storage/framework/{sessions,views,cache}
php artisan config:cache 
php artisan key:generate --force --no-interaction
php artisan passport:keys --force --no-interaction 
php artisan migrate --force
php artisan passport:client --personal --no-interaction
php artisan db:seed --class=PermissionsSeeder --force --no-interaction
php artisan storage:link --force --no-interaction
php artisan h5p:storage-link
