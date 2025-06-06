#!/bin/bash

echo "Wellms multidomains init script!" 

if [ "$DISABLE_PHP_FPM" == 'true' ]
then
    rm -f /etc/supervisor/conf.d/php-fpm.conf
    echo php-fpm.conf disabled
else 
    cp docker/conf/supervisor/services/php-fpm.conf /etc/supervisor/conf.d/php-fpm.conf
    echo php-fpm.conf enabled
fi

if [ "$DISABLE_QUEUE" == 'true' ]
then
    rm -f /etc/supervisor/custom.d/multidomain_queue.conf
    echo multidomain_queue.conf disabled
else 
    cp docker/conf/supervisor/services/multidomain_queue.conf /etc/supervisor/custom.d/multidomain_queue.conf
    echo multidomain_queue.conf enabled
fi


# removing default horizon for multidomain
rm -f /etc/supervisor/custom.d/horizon.conf


# removing default scheduler for multidomain
rm -f /etc/supervisor/custom.d/scheduler.conf

# set env from `LARAVEL_` prefixed env vars
# this also setup MULTI_DOMAINS eg 
# when MULTI_DOMAINS: "api-sprawnymarketing.escolalms.com,api-gest.escolalms.com" 
# then API_SPRAWNYMARKETING_ESCOLALMS_COM_APP_NAME: '"Sprawny Marketing"'

# if binded by k8s or docker those folders might need to be recreated
if [ ! -d "storage" ]; then mkdir storage; fi
if [ ! -d "storage/framework" ]; then mkdir storage/framework; fi
if [ ! -d "storage/framework/sessions" ]; then mkdir storage/framework/sessions; fi
if [ ! -d "storage/framework/views" ]; then mkdir storage/framework/views; fi
if [ ! -d "storage/framework/cache" ]; then mkdir storage/framework/cache; fi
if [ ! -d "storage/app" ]; then mkdir storage/app; fi
if [ ! -d "storage/logs" ]; then mkdir storage/logs; fi

# generate general .env file for next specific domain files 
# as `php artisan domain:add $domain` copy values from `.env`
echo "Generating general .env file for next specific domain files"
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

# MULTI_DOMAINS
if [ -n "$MULTI_DOMAINS" ]; then
  IFS=',' read -ra domains <<< "$MULTI_DOMAINS"

  for domain in "${domains[@]}"; do
    echo "Setup $domain"

    php artisan domain:add $domain

     # TODO it considers only global variable, what if you want to control which domain has disabled scheduler
    if [ -z "$DISABLE_SCHEDULER" ] || [ "$DISABLE_SCHEDULER" != "true" ];
    then
      cp "docker/conf/supervisor/example/scheduler.conf.example" "/etc/supervisor/custom.d/scheduler.$domain.conf"
      sed "s/\$SCHEDULER_DOMAIN/$domain/g" "docker/conf/supervisor/example/scheduler.conf.example" > "/etc/supervisor/custom.d/scheduler.$domain.conf"
      echo "Schedule enabled"
    else
      echo "Schedule disabled"
    fi
    # delare variables
    DOMAIN_KEY=$(echo "$domain" | tr '[:lower:]' '[:upper:]')
    DOMAIN_KEY=$(echo "$DOMAIN_KEY" | tr '.-' '__')
    DB_NAME_KEY="${DOMAIN_KEY}_DB_DATABASE"
    DB_NAME_VALUE=${!DB_NAME_KEY}
    SPECIFIC_JWT_PUBLIC_KEY_BASE64="${DOMAIN_KEY}_JWT_PUBLIC_KEY_BASE64"
    SPECIFIC_JWT_PRIVATE_KEY_BASE64="${DOMAIN_KEY}_JWT_PRIVATE_KEY_BASE64"
    STORAGE_DIRECTORY=$(echo "$domain" | tr '[:upper:]' '[:lower:]' | tr '.' '_')

    php docker/envs/envs_multidomains.php $domain $DOMAIN_KEY

    # create keys from env base64 variables 
    if [ -n "$JWT_PUBLIC_KEY_BASE64" ]; then
        echo "Storing public shared env JWT_PUBLIC_KEY_BASE64 RSA key for JWT generation -  /var/www/html/storage/${STORAGE_DIRECTORY}/oauth-public.key"
        echo ${JWT_PUBLIC_KEY_BASE64} | base64 -d > /var/www/html/storage/${STORAGE_DIRECTORY}/oauth-public.key
    fi

    if [ -n "$JWT_PRIVATE_KEY_BASE64" ]; then
        echo "Storing private shared env JWT_PUBLIC_KEY_BASE64 RSA key for JWT generation - /var/www/html/storage/${STORAGE_DIRECTORY}/oauth-private.key"
        echo ${JWT_PRIVATE_KEY_BASE64} | base64 -d > /var/www/html/storage/${STORAGE_DIRECTORY}/oauth-private.key
    fi

    if [ -n "${!SPECIFIC_JWT_PUBLIC_KEY_BASE64}" ]; then
        echo "Storing public specific env ${DOMAIN_KEY}_JWT_PUBLIC_KEY_BASE64 RSA key for JWT generation -  /var/www/html/storage/${STORAGE_DIRECTORY}/oauth-public.key"
        echo ${!SPECIFIC_JWT_PUBLIC_KEY_BASE64} | base64 -d > /var/www/html/storage/${STORAGE_DIRECTORY}/oauth-public.key
    fi

    if [ -n "${!SPECIFIC_JWT_PRIVATE_KEY_BASE64}" ]; then
        echo "Storing private specific env ${DOMAIN_KEY}_JWT_PRIVATE_KEY_BASE64 RSA key for JWT generation - /var/www/html/storage/${STORAGE_DIRECTORY}/oauth-private.key"
        mkdir -d /var/www/config/jwt/
        echo ${!SPECIFIC_JWT_PRIVATE_KEY_BASE64} | base64 -d > /var/www/html/storage/${STORAGE_DIRECTORY}/oauth-private.key
    fi

   
    
    # db migrate
    if [ "$DISABLE_DB_MIGRATE" == 'true' ]
    then
        echo "Disable db migrate"
    else
        php artisan migrate --force --domain=$domain
    fi

    # generate passport keys only if storage/oauth-private.key is not set
    # note that app:keys are generated here as well 
    FILE=storage/${STORAGE_DIRECTORY}/oauth-private.key
    if [ -f "$FILE" ]; then
        echo "key file $FILE exists. Using one from file or env"     
    else 
        echo "$FILE does not exist. Generating app keys, passport keys and passport client"
        php artisan key:generate --force --no-interaction --domain=$domain
        php artisan passport:keys --force --no-interaction --domain=$domain
        php artisan passport:client --personal --no-interaction --domain=$domain
    fi

    # db seed
    if [ "$DISABLE_DB_SEED" == 'true' ]
    then
        echo "Disable db:seed"
    else
        php artisan db:seed --domain=$domain --class=PermissionsSeeder --force --no-interaction
    fi

    if [ "$DISABLE_H5P_STORAGE_LINK" == 'true' ]
    then
        echo "Disable h5p:storage-link"
    else
        php artisan h5p:storage-link --overwrite --domain=$domain
    fi
    

  done
else
  echo "Environment variable MULTI_DOMAINS is empty."
fi

# Default setup 
echo "Default setup"
# This is required for create default laravel envs 
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
    #php artisan key:generate --force --no-interaction
    php artisan passport:keys --force --no-interaction 
    #php artisan passport:client --personal --no-interaction
fi

touch inited

# TODO: Fixme
# This is required so far as docker compose run this script as root 

chown -R www-data:www-data /var/www/html/storage

/usr/bin/supervisord -c /etc/supervisord.conf
