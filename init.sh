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

# MULTI_DOMAINS
if [ -n "$MULTI_DOMAINS" ]; then
  IFS=',' read -ra domains <<< "$MULTI_DOMAINS"

  for domain in "${domains[@]}"; do
    echo "$domain"

    # horizon
    if [ -z "$DISBALE_HORIZON" ] || [ "$DISBALE_HORIZON" != "true" ];
    then
      cp "docker/conf/supervisor/example/horizon.conf.example" "/etc/supervisor/custom.d/horizon.$domain.conf"
      sed "s/\$HORIZON_DOMAIN/$domain/g" "docker/conf/supervisor/example/horizon.conf.example" > "/etc/supervisor/custom.d/horizon.$domain.conf"
    else
      echo "Horizon disabled"
    fi
    # scheduler
    if [ -z "$DISBALE_SCHEDULER" ] || [ "$DISBALE_SCHEDULER" != "true" ];
    then
      cp "docker/conf/supervisor/example/scheduler.conf.example" "/etc/supervisor/custom.d/scheduler.$domain.conf"
      sed "s/\$SCHEDULER_DOMAIN/$domain/g" "docker/conf/supervisor/example/scheduler.conf.example" > "/etc/supervisor/custom.d/scheduler.$domain.conf"
    else
      echo "Schedule disabled"
    fi

    DOMAIN_KEY=$(echo "$domain" | tr '[:lower:]' '[:upper:]')
    DOMAIN_KEY=$(echo "$DOMAIN_KEY" | tr '.-' '__')

    DB_NAME_KEY="${DOMAIN_KEY}_DB_DATABASE"
    DB_NAME_VALUE=${!DB_NAME_KEY}

    # create db if not exists
    if PGPASSWORD="$POSTGRES_PASSWORD" psql -h "$LARAVEL_DB_HOST" -U "$POSTGRES_USER" -d "$DB_NAME_VALUE" -c ";" >/dev/null 2>&1; then
      echo "DB $DB_NAME_VALUE already exists"
    else
      PGPASSWORD="$POSTGRES_PASSWORD" psql -h "$LARAVEL_DB_HOST" -U "$POSTGRES_USER" -c "CREATE DATABASE $DB_NAME_VALUE;"
      echo "DB $DB_NAME_VALUE has been created"
    fi

    # db migrate
    if [ "$DISBALE_DB_MIGRATE" == 'true' ]
    then
        echo "Disable db migrate"
    else
        php artisan migrate --force --domain=$domain
    fi

    # db seed
    if [ "$DISBALE_DB_SEED" == 'true' ]
    then
        echo "Disable db:seed"
    else
        php artisan db:seed --domain=$domain --class=PermissionsSeeder --force --no-interaction
    fi

    # storage
    STORAGE_DIRECTORY=$(echo "$domain" | tr '[:upper:]' '[:lower:]' | tr '.' '_')
    STORAGE_PUBLIC_KEY="${DOMAIN_KEY}_APP_PUBLIC_STORAGE"
    STORAGE_PUBLIC_NAME=${!STORAGE_PUBLIC_KEY}
    if [ -n "$STORAGE_PUBLIC_NAME" ]; then
      ln -s /var/www/html/storage/${STORAGE_DIRECTORY}/app/public public/storage${STORAGE_PUBLIC_NAME}
    fi

    php artisan optimize:clear --domain=$domain

  done
else
  echo "Environment variable MULTI_DOMAINS is empty."
fi
