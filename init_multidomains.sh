#!/bin/bash

echo "multidomains" 


if [ "$DISBALE_PHP_FPM" == 'true' ]
then
    rm -f /etc/supervisor/conf.d/php-fpm.conf
    echo php-fpm.conf disabled
fi

# if [ "$DISBALE_HORIZON" == 'true' ]
# then
#     rm -f /etc/supervisor/custom.d/horizon.conf
#     echo horizon.conf disabled
# fi

if [ "$DISBALE_CADDY" == 'true' ]
then
    rm -f /etc/supervisor/custom.d/caddy.conf
    echo caddy.conf disabled
fi

# if [ "$DISBALE_SCHEDULER" == 'true' ]
# then
#     rm -f /etc/supervisor/custom.d/scheduler.conf
#     echo scheduler.conf disabled
# fi

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

# run all laravel related tasks 
# klucze mozna trzymac jako zmienne srodowiskowe wiec .... 
# https://github.com/gecche/laravel-multidomain/issues/51



# FIX me, do we nee to clear cache ? 
#php artisan config:cache 

# if [ "$DISBALE_DB_MIGRATE" == 'true' ]
# then
#     echo "Disable db migrate"
# else 
#     php artisan migrate --force
# fi

# if [ "$DISBALE_DB_SEED" == 'true' ]
# then
#     echo "Disable db:seed"
# else 
#     php artisan db:seed --class=PermissionsSeeder --force --no-interaction
# fi

# generate general .env file for next specific domain files 
# as `php artisan domain:add $domain` copy values from `.env`
echo "Generating general .env file for next specific domain files"
php docker/envs/envs.php 

# MULTI_DOMAINS
if [ -n "$MULTI_DOMAINS" ]; then
  IFS=',' read -ra domains <<< "$MULTI_DOMAINS"

  for domain in "${domains[@]}"; do
    echo "Setup $domain"

    php artisan domain:add $domain

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
        mkdir -d /var/www/config/jwt/
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

    # generate passport keys only if storage/oauth-private.key is not set

    FILE=storage/${STORAGE_DIRECTORY}/oauth-private.key
    if [ -f "$FILE" ]; then
        echo "key file $FILE exists. Using one from file or env"     
    else 
        echo "$FILE does not exist. Generating app keys, passport keys and passport client"
        php artisan key:generate --force --no-interaction --domain=$domain
        php artisan passport:keys --force --no-interaction --domain=$domain
        php artisan passport:client --personal --no-interaction --domain=$domain
    fi

    # default .env is created not we fetch values from it for admin db access
    source .env

    # Variables
    DB_USER=$STORAGE_DIRECTORY
    DB_NAME=$STORAGE_DIRECTORY
    DB_SUPERUSER=$POSTGRES_USER  # The PostgreSQL superuser or a user with sufficient privileges

    # Generate a random password (12-character alphanumeric password)
    DB_RND_PASSWORD=$(openssl rand -base64 12)

    # Check if the user already exists
    USER_EXISTS=$(PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USERNAME" -tAc "SELECT 1 FROM pg_roles WHERE rolname='$DB_USER'")

    if [ "$USER_EXISTS" = "1" ]; then
        echo "User '$DB_USER' already exists."
        # assuming 
    else
        echo "User '$DB_USER' dont exists."        
        PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USERNAME" -c "CREATE USER $DB_USER WITH PASSWORD '$DB_RND_PASSWORD';"
        echo "User '$DB_USER' created with password: $DB_RND_PASSWORD"

        # Create new PostgreSQL database
    #     sudo -u $DB_SUPERUSER psql -c "CREATE DATABASE $DB_NAME;"
    #     echo "Database '$DB_NAME' created."

        php artisan domain:update_env $domain --domain_values='{"DB_USERNAME":"'$DB_USER'"}' 
        php artisan domain:update_env $domain --domain_values='{"DB_PASSWORD":"'$DB_RND_PASSWORD'"}' 

    fi

    # Check if the database already exists
    DB_EXISTS=$(PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USERNAME" -tAc "SELECT 1 FROM pg_database WHERE datname='$DB_NAME'")

    if [ "$DB_EXISTS" = "1" ]; then
        echo "Database '$DB_NAME' already exists."
    else
        # Create new PostgreSQL database
        PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USERNAME" -c "CREATE DATABASE $DB_NAME;"
        echo "Database '$DB_NAME' created."
         php artisan domain:update_env $domain --domain_values='{"DB_DATABASE":"'$DB_NAME'"}' 
    fi

    # Grant privileges to the user on the database
    PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USERNAME" -c "GRANT ALL PRIVILEGES ON DATABASE $DB_NAME TO $DB_USER;"
    echo "Privileges granted to user '$DB_USER' on database '$DB_NAME'."

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


    # ////
    # # create db if not exists
    # if PGPASSWORD="$POSTGRES_PASSWORD" psql -h "$LARAVEL_DB_HOST" -U "$POSTGRES_USER" -d "$DB_NAME_VALUE" -c ";" >/dev/null 2>&1; then
    #   echo "DB $DB_NAME_VALUE already exists"
    # else
    #   PGPASSWORD="$POSTGRES_PASSWORD" psql -h "$LARAVEL_DB_HOST" -U "$POSTGRES_USER" -c "CREATE DATABASE $DB_NAME_VALUE;"
    #   echo "DB $DB_NAME_VALUE has been created"
    # fi

    # # db migrate
    # if [ "$DISBALE_DB_MIGRATE" == 'true' ]
    # then
    #     echo "Disable db migrate"
    # else
    #     php artisan migrate --force --domain=$domain
    # fi

    # # db seed
    # if [ "$DISBALE_DB_SEED" == 'true' ]
    # then
    #     echo "Disable db:seed"
    # else
    #     php artisan db:seed --domain=$domain --class=PermissionsSeeder --force --no-interaction
    # fi

    # # storage
    # STORAGE_DIRECTORY=$(echo "$domain" | tr '[:upper:]' '[:lower:]' | tr '.' '_')
    # STORAGE_PUBLIC_KEY="${DOMAIN_KEY}_APP_PUBLIC_STORAGE"
    # STORAGE_PUBLIC_NAME=${!STORAGE_PUBLIC_KEY}
    # # to nie bedzie potrzebne bo bedziemy korzystac z minio 
    # if [ -n "$STORAGE_PUBLIC_NAME" ]; then
    #   ln -s /var/www/html/storage/${STORAGE_DIRECTORY}/app/public public/storage${STORAGE_PUBLIC_NAME}
    #   ln -s /var/www/html/storage/${STORAGE_DIRECTORY}/app/h5p public/h5p${STORAGE_PUBLIC_NAME}
    # fi

    # php artisan optimize:clear --domain=$domain

  done
else
  echo "Environment variable MULTI_DOMAINS is empty."
fi


