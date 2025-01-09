#!/bin/bash

# disable paritcular supervisor job by deleting jobs files 

rm inited

mkdir -p /etc/supervisor/custom.d 
mkdir -p /etc/supervisor/conf.d 

if [ -n "$MULTI_DOMAINS" ]
then
  ./init_multidomains.sh
  exit 0;
fi

if [ "$DISABLE_PHP_FPM" == 'true' ]
then
    rm -f /etc/supervisor/conf.d/php-fpm.conf
    echo php-fpm.conf disabled
else 
    echo php-fpm.conf enabled
    cp docker/conf/supervisor/services/php-fpm.conf /etc/supervisor/conf.d/php-fpm.conf
fi

if [ "$DISABLE_HORIZON" == 'true' ]
then
    rm -f /etc/supervisor/custom.d/horizon.conf
    echo horizon.conf disabled
else 
    cp docker/conf/supervisor/services/horizon.conf /etc/supervisor/custom.d/horizon.conf
    echo horizon.conf enabled
fi


if [ "$DISABLE_SCHEDULER" == 'true' ]
then
    rm -f /etc/supervisor/custom.d/scheduler.conf
    echo scheduler.conf disabled
else 
    cp docker/conf/supervisor/services/scheduler.conf /etc/supervisor/custom.d/scheduler.conf
    echo scheduler.conf enabled
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

chmod -R 0766 storage

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
    echo ${JWT_PRIVATE_KEY_BASE64} | base64 -d > storage/oauth-private.key
fi




if [ "$DISABLE_DB_MIGRATE" == 'true' ]
then
    echo "Disable db migrate"
else 
    php artisan migrate --force
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

if [ "$DISABLE_DB_SEED" == 'true' ]
then
    echo "Disable db:seed"
else 
    php artisan db:seed --class=PermissionsSeeder --force --no-interaction
fi

if [ "$DISABLE_H5P_STORAGE_LINK" == 'true' ]
then
    echo "Disable h5p:storage-link"
else 
    php artisan h5p:storage-link --overwrite
fi

touch inited

# TODO: Fixme
# This is required so far as docker compose run this script as root 
#chown -R devilbox:devilbox /var/www/html/storage

/usr/bin/supervisord -c /etc/supervisord.conf


