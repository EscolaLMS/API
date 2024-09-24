#!/bin/bash

# disable paritcular supervisor job by deleting jobs files 

if [ -n "$MULTI_DOMAINS" ]
then
  ./init_multidomains.sh
  exit 0;
fi

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
# this also setup MULTI_DOMAINS eg 
# when MULTI_DOMAINS: "api-sprawnymarketing.escolalms.com,api-gest.escolalms.com" 
# then API_SPRAWNYMARKETING_ESCOLALMS_COM_APP_NAME: '"Sprawny Marketing"'

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
# klucze mozna trzymac jako zmienne srodowiskowe wiec .... 
# https://github.com/gecche/laravel-multidomain/issues/51
# create keys from env base64 variables 
if [ -n "$JWT_PUBLIC_KEY_BASE64" ]; then
    echo "Storing public RSA key for JWT generation - storage/oauth-public.key"
    echo ${JWT_PUBLIC_KEY_BASE64} | base64 -d > storage/oauth-public.key
fi

if [ -n "$JWT_PRIVATE_KEY_BASE64" ]; then
    echo "Storing private RSA key for JWT generation - storage/oauth-private.key"
    mkdir -d /var/www/config/jwt/
    echo ${JWT_PRIVATE_KEY_BASE64} | base64 -d > storage/oauth-private.key
fi

# generate passport keys only if storage/oauth-private.key is not set

FILE=storage/oauth-private.key
if [ -f "$FILE" ]; then
    echo "$FILE exists."     
else 
    echo "$FILE does not exist. Generating app keys, passport keys and passport client"
    php artisan key:generate --force --no-interaction
    php artisan passport:keys --force --no-interaction 
    php artisan passport:client --personal --no-interaction
fi

# FIX me, do we nee to clear cache ? 
#php artisan config:cache 

if [ "$DISBALE_DB_MIGRATE" == 'true' ]
then
    echo "Disable db migrate"
else 
    php artisan migrate --force
fi

if [ "$DISBALE_DB_SEED" == 'true' ]
then
    echo "Disable db:seed"
else 
    php artisan db:seed --class=PermissionsSeeder --force --no-interaction
fi

php artisan storage:link --force --no-interaction
php artisan h5p:storage-link


