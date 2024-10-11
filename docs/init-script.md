# init script Entrypoint

Each time The API start its entrypoint is [init.sh](../init.sh) script

The flow goes as follows

## `init.sh`

This Bash script is part of a deployment or startup process, specifically for a Laravel application running in a containerized environment (possibly Docker with Kubernetes). Below is a point-by-point explanation of what the script does.

This script automates the setup and configuration for a Laravel application, handling environment variables, file permissions, database setup, key generation, and the startup of necessary services using Supervisor.

### 1. Deletes the inited file if it exists:

```bash
rm inited
```

This could be a marker file to indicate whether the initialization process has run before.

you can run `./wait.sh` script to see if the process is finished

### 2. Check for Multiple Domains

Checks if the `MULTI_DOMAINS` environment variable is set:

```bash
if [ -n "$MULTI_DOMAINS" ]; then
  ./init_multidomains.sh
  exit 0;
fi
```

If set, it runs `init_multidomains.sh` to handle multiple domain configurations and exits the script.

### 3. Disable PHP-FPM (FastCGI Process Manager)

Disables PHP-FPM if `DISABLE_PHP_FPM` is set to 'true':

```bash
if [ "$DISABLE_PHP_FPM" == 'true' ]; then
  rm -f /etc/supervisor/conf.d/php-fpm.conf
  echo php-fpm.conf disabled
else
  echo php-fpm.conf enabled
fi
```

This is needed only for API that is exposed to internet with load balancer, for API Endpoints. It can be set to `true` for queue and scheduler

4. Disable Horizon
   Disables Laravel Horizon if DISABLE_HORIZON is set to 'true':

```bash
if [ "$DISABLE_HORIZON" == 'true' ]; then
  rm -f /etc/supervisor/custom.d/horizon.conf
  echo horizon.conf disabled
else
  echo horizon.conf enabled
fi
```

Deletes [`/etc/supervisor/custom.d/horizon.conf`](../docker/conf/supervisor/services/horizon.conf) so [Laravel Horizon](https://laravel.com/docs/11.x/horizon) won't start. This is needed only for only API Endpoints service. It should not be set to `true` for queue and scheduler
This is needed only for only API Endpoints service. It should not be set to `true` for queue and scheduler

### 5. Disable the Scheduler

Disables the Laravel Scheduler if DISABLE_SCHEDULER is set to 'true':

```bash
if [ "$DISABLE_SCHEDULER" == 'true' ]; then
  rm -f /etc/supervisor/custom.d/scheduler.conf
  echo scheduler.conf disabled
else
  echo scheduler.conf enabled
fi
```

Deletes [`/etc/supervisor/custom.d/scheduler.conf`](../docker/conf/supervisor/services/scheduler.conf) so [Laravel Scheduler](https://laravel.com/docs/11.x/scheduling#running-the-scheduler) won't start. This is needed only for only API Endpoints service. It should not be set to `true` for queue and scheduler.

### 6. Set Environment Variables from `LARAVEL_` Prefix

Runs a PHP script that sets environment variables:

````bash
php docker/envs/envs.php
```

It sets values for variables prefixed with `LARAVEL_`, such as domain-specific settings, convert all env variables with `LARAVEL_` prefix to convert them into variable set for `.env` file for [Laravel Environment Configuration](https://laravel.com/docs/11.x/configuration#environment-configuration). Note that `.env` is ephemeral and created each time service restarts.

7. Create Necessary Storage Directories
Creates the required directories under storage:
```bash
mkdir storage
mkdir storage/framework
mkdir storage/framework/sessions
mkdir storage/framework/views
mkdir storage/framework/cache
mkdir storage/app
mkdir storage/logs
````

### 8. Set Permissions for the storage Directory

Ensures proper permissions on the storage directory and its contents:

```bash
chmod -R 0766 storage
```

### 9. Store RSA Keys for JWT (JSON Web Token) Generation

If the JWT keys are provided in base64 format, they are decoded and stored:

```bash
if [ -n "$JWT_PUBLIC_KEY_BASE64" ]; then
  echo "Storing public RSA key for JWT generation - storage/oauth-public.key"
  echo ${JWT_PUBLIC_KEY_BASE64} | base64 -d > storage/oauth-public.key
fi

if [ -n "$JWT_PRIVATE_KEY_BASE64" ]; then
  echo "Storing private RSA key for JWT generation - storage/oauth-private.key"
  echo ${JWT_PRIVATE_KEY_BASE64} | base64 -d > storage/oauth-private.key
fi
```

### 10. Check if Database Migrations Should be Skipped

If database migrations are not disabled, it runs php `artisan migrate --force`

If `DISABLE_DB_MIGRATE=true` skip migration, other wise run `php artisan migrate --force`. You can skip migration on some services, but at least one should not have this set to `true`

```bash
if [ "$DISABLE_DB_MIGRATE" == 'true' ]; then
  echo "Disable db migrate"
else
  php artisan migrate --force
fi
```

You can skip migration on some services, but at least one should not have this set to `true`

### 11. Generate Laravel Passport Keys if They Don’t Exist

If the private Passport key file does not exist, generate it:

```bash
FILE=storage/oauth-private.key
if [ -f "$FILE" ]; then
  echo "$FILE exists."
else
  echo "$FILE does not exist. Generating app keys, passport keys and passport client"
  php artisan key:generate --force --no-interaction
  php artisan passport:keys --force --no-interaction
  php artisan passport:client --personal --no-interaction
fi
```

### 12. Check if Permission Database Seeding Should be Skipped

If database seeding is not disabled, it seeds the database:

```bash
if [ "$DISABLE_DB_SEED" == 'true' ]; then
  echo "Disable db:seed"
else
  php artisan db:seed --class=PermissionsSeeder --force --no-interaction
fi
```

You can skip migration on some services, but at least one should not have this set to `true`

Also Permission Seeder creates a new Admin if user database is empty, env var `INITIAL_USER_PASSWORD` is used

### 13. Create H5P Storage Symlink

Runs php artisan h5p:storage-link to create necessary h5p js/css assets into publicly available storage

```bash
php artisan h5p:storage-link
```

### 14. Mark Initialization as Complete

Creates the inited file to mark that initialization has been completed:

```bash
touch inited
```

### 15. Change Ownership of the storage Directory

Changes the owner of the storage directory to `devilbox`:

```bash
chown -R devilbox:devilbox /var/www/html/storage
```

### 16. Start Supervisor Process Manager

Starts the Supervisor daemon to manage the processes:

```bash
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf
```

## `init_multidomain.sh`

This script is designed to initialize a Laravel application with support for multiple domains. It handles configuring PHP-FPM, the queue worker, and the scheduler

Here’s a detailed, step-by-step breakdown of the provided Bash script:

### 1. Echo a start message

```bash
echo "Wellms multidomains init script!"
```

Displays a message indicating the start of the multi-domain initialization script for the Wellms application.

### 2. Enable or Disable PHP-FPM

```bash
if [ "$DISABLE_PHP_FPM" == 'true' ]; then
    rm -f /etc/supervisor/conf.d/php-fpm.conf
    echo php-fpm.conf disabled
else
    cp docker/conf/supervisor/services/php-fpm.conf /etc/supervisor/conf.d/php-fpm.conf
    echo php-fpm.conf enabled
fi
```

If DISABLE_PHP_FPM is set to 'true', the PHP-FPM configuration file is deleted, disabling the service.
If it is not set or set to any other value, the PHP-FPM configuration is copied from a predefined location, enabling the service.

### 3. Enable or Disable the Queue Worker

```bash
if [ "$DISABLE_QUEUE" == 'true' ]; then
    rm -f /etc/supervisor/conf.d/multidomain_queue.conf
    echo multidomain_queue.conf disabled
else
    cp docker/conf/supervisor/services/multidomain_queue.conf /etc/supervisor/conf.d/multidomain_queue.conf
    echo multidomain_queue.conf enabled
fi
```

If `DISABLE_QUEUE` is set to 'true', the queue worker configuration is removed, disabling it.
If it is not set, the configuration for the queue worker is enabled by copying the relevant file.

### 4. Remove Default Horizon Configuration

```bash
rm -f /etc/supervisor/custom.d/horizon.conf
```

The script removes the default Horizon configuration, assuming a different configuration is required for multi-domain support.

### 5. (Commented out) Option to Remove Scheduler Configuration

```bash
# rm -f /etc/supervisor/custom.d/scheduler.conf
```

The script has a commented-out section that would remove the scheduler configuration if uncommented. This is likely because it's being managed per domain in the later steps.
###6. Ensure Laravel Storage Directories Exist

```bash
if [ ! -d "storage" ]; then mkdir storage; fi
if [ ! -d "storage/framework" ]; then mkdir storage/framework; fi
if [ ! -d "storage/framework/sessions" ]; then mkdir storage/framework/sessions; fi
if [ ! -d "storage/framework/views" ]; then mkdir storage/framework/views; fi
if [ ! -d "storage/framework/cache" ]; then mkdir storage/framework/cache; fi
if [ ! -d "storage/app" ]; then mkdir storage/app; fi
if [ ! -d "storage/logs" ]; then mkdir storage/logs; fi
```

This section ensures that the necessary storage directories for Laravel exist. If not, it creates them.

### 7. Generate the General .env File

```bash
echo "Generating general .env file for next specific domain files"
php docker/envs/envs.php
```

A PHP script is run to generate a general .env file, which will later be copied or used for specific domain environment files.

### 8. Re-create the storage Directories

```bash
mkdir storage
mkdir storage/framework
mkdir storage/framework/sessions
mkdir storage/framework/views
mkdir storage/framework/cache
mkdir storage/app
mkdir storage/logs
```

The storage directories are recreated, possibly because they were cleaned up in the previous step.

### 9. Set Permissions on the storage Directory

```bash
chmod -R 0766 storage
```

Permissions are set for the storage directory to ensure that it is accessible and writable by the Laravel application.

### 10. Process Multi-Domain Configuration

```bash
if [ -n "$MULTI_DOMAINS" ]; then
  IFS=',' read -ra domains <<< "$MULTI_DOMAINS"

  for domain in "${domains[@]}"; do
    echo "Setup $domain"
    php artisan domain:add $domain
```

If the MULTI_DOMAINS environment variable is set (contains domain names), it splits this variable by commas and iterates over the list of domains.
For each domain, the script runs php artisan domain:add $domain to set up Laravel for the specific domain.

### 11. Enable or Disable the Scheduler Per Domain

```bash
if [ -z "$DISABLE_SCHEDULER" ] || [ "$DISABLE_SCHEDULER" != "true" ]; then
    cp "docker/conf/supervisor/example/scheduler.conf.example" "/etc/supervisor/custom.d/scheduler.$domain.conf"
    sed "s/\$SCHEDULER_DOMAIN/$domain/g" "docker/conf/supervisor/example/scheduler.conf.example" > "/etc/supervisor/custom.d/scheduler.$domain.conf"
    echo "Schedule enabled"
else
    echo "Schedule disabled"
fi
```

If `DISABLE_SCHEDULER` is not set to 'true', the script copies and modifies the scheduler configuration for the specific domain and enables it.
Otherwise, it logs that the scheduler is disabled for the domain.

### 12. Set Up Environment Variables Per Domain

```bash
DOMAIN_KEY=$(echo "$domain" | tr '[:lower:]' '[:upper:]')
DOMAIN_KEY=$(echo "$DOMAIN_KEY" | tr '.-' '__')
DB_NAME_KEY="${DOMAIN_KEY}_DB_DATABASE"
DB_NAME_VALUE=${!DB_NAME_KEY}
php docker/envs/envs_multidomains.php $domain $DOMAIN_KEY
```

Converts the domain name into uppercase and replaces dots (.) and hyphens (-) with underscores to form environment variable keys.
Runs a script to set up environment variables for each specific domain.

### 13. Store RSA JWT Keys (Global and Specific to Domains)

```bash
if [ -n "$JWT_PUBLIC_KEY_BASE64" ]; then
    echo ${JWT_PUBLIC_KEY_BASE64} | base64 -d > /var/www/html/storage/${STORAGE_DIRECTORY}/oauth-public.key
fi
if [ -n "${!SPECIFIC_JWT_PUBLIC_KEY_BASE64}" ]; then
    echo ${!SPECIFIC_JWT_PUBLIC_KEY_BASE64} | base64 -d > /var/www/html/storage/${STORAGE_DIRECTORY}/oauth-public.key
fi
```

Decodes base64-encoded RSA keys (global and specific per domain) and stores them in the appropriate storage directory for JWT token generation.

### 14. Run Database Migrations Per Domain

```bash
if [ "$DISABLE_DB_MIGRATE" == 'true' ]; then
    echo "Disable db migrate"
else
    php artisan migrate --force --domain=$domain
fi
```

If DISABLE_DB_MIGRATE is set to 'true', the database migrations are skipped.
Otherwise, the script runs php artisan migrate to apply migrations for the specific domain.

### 15. Generate Passport Keys If Needed

```bash
FILE=storage/${STORAGE_DIRECTORY}/oauth-private.key
if [ -f "$FILE" ]; then
    echo "key file $FILE exists."
else
    php artisan key:generate --force --no-interaction --domain=$domain
    php artisan passport:keys --force --no-interaction --domain=$domain
    php artisan passport:client --personal --no-interaction --domain=$domain
fi
```

If the private Passport key file exists, it skips key generation. If it doesn't exist, it generates the application keys and Passport keys for the domain.

### 16. Run Database Seeding Per Domain

```bash
if [ "$DISABLE_DB_SEED" == 'true' ]; then
    echo "Disable db:seed"
else
    php artisan db:seed --domain=$domain --class=PermissionsSeeder --force --no-interaction
fi
```

If `DISABLE_DB_SEED` is set to 'true', the seeding process is skipped.
Otherwise, it seeds the database for the domain using the PermissionsSeeder class.

17. Create H5P Storage Link Per Domain

```bash
php artisan h5p:storage-link --domain=$domain
```

This command sets up storage with h5p default js/css for the specific domain.

18. Handle Case When `MULTI_DOMAINS` is Empty

```bash
else
  echo "Environment variable MULTI_DOMAINS is empty."
fi
```

If the `MULTI_DOMAINS` variable is empty, it logs that no domains were provided.

### 19. Mark Initialization as Complete

```bash
touch inited
```

Creates the inited file to indicate that the initialization process has completed.

### 20. Change Ownership of the Storage Directory

```bash
chown -R devilbox:devilbox /var/www/html/storage
```

Ensures that the storage directory and its contents are owned by the appropriate user (devilbox).

### 21. Start Supervisor Process Manager

```bash
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf
Finally, the script starts Supervisor, a process control system that manages the services configured in the system.
```
