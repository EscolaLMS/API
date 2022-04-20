fix-style:
	- docker-compose exec escola_lms_app bash -c "./vendor/bin/php-cs-fixer fix ."

test-phpunit:
	- docker-compose exec escola_lms_app bash -c "./vendor/bin/phpunit"

bash:
	- docker-compose exec escola_lms_app bash

ide-helper:
	- docker-compose exec escola_lms_app bash -c "php artisan ide-helper:generate && mv _ide_helper.php .phan/stubs/_ide_helper.php"

migrate-fresh-quick:
	- docker-compose exec escola_lms_app bash -c "XDEBUG_MODE=off php artisan migrate:fresh --seed"
	- docker-compose exec escola_lms_app bash -c "XDEBUG_MODE=off php artisan passport:keys --force"
	- docker-compose exec escola_lms_app bash -c "XDEBUG_MODE=off php artisan passport:client --personal --no-interaction"
	- docker-compose exec escola_lms_app bash -c "cp storage/oauth-private.key vendor/orchestra/testbench-core/laravel/storage/oauth-private.key"

composer-update:
	- docker-compose exec escola_lms_app bash -c "XDEBUG_MODE=off composer self-update"
	- docker-compose exec escola_lms_app bash -c "XDEBUG_MODE=off composer update"
## supervisd horizon must be restarted 
	- docker-compose restart escola_lms_app

swagger-generate:
	- docker-compose exec escola_lms_app bash -c "XDEBUG_MODE=off php artisan l5-swagger:generate"

h5p-seed:
	- docker-compose exec escola_lms_app bash -c "XDEBUG_MODE=off php artisan db:seed --class=H5PLibrarySeeder"
	- docker-compose exec escola_lms_app bash -c "XDEBUG_MODE=off php artisan db:seed --class=H5PContentSeeder"
	- docker-compose exec escola_lms_app bash -c "XDEBUG_MODE=off php artisan db:seed --class=H5PContentCoursesSeeder"

node-packages:
# install globally at /usr/bin/mjml
	- docker-compose exec escola_lms_app bash -c "npm install -g mjml"

tinker:
# use CTRL+C to quit and CTRL+D to refresh tinker
	- docker-compose exec escola_lms_app bash -c "while true; do php artisan tinker; done"


migrate-fresh: migrate-fresh-quick h5p-seed

# this one is called from composer inside docker
link:
	- mkdir -p "storage/app/public/h5p/libraries"
	- php artisan storage:link
	- cd public/assets/vendor/h5p && ln -sf ../../../../storage/app/public/h5p/libraries

refresh: composer-update migrate-fresh h5p-seed

docker-up:
	- docker-compose up -d

switch-to-postgres:
	- cp docker/envs/.env.postgres.example .env
	- docker-compose exec escola_lms_app bash -c "php artisan config:cache"

switch-to-mysql:
	- cp docker/envs/.env.mysql.example .env
	- docker-compose exec escola_lms_app bash -c "php artisan config:cache"

migrate-mysql: switch-to-mysql migrate-fresh-quick

migrate-postgres: switch-to-postgres migrate-fresh-quick

test-phpunit-postgres: switch-to-postgres test-phpunit

test-phpunit-mysql: switch-to-mysql test-phpunit

test-fresh: migrate-fresh-quick test-phpunit

init: docker-up node-packages switch-to-postgres composer-update migrate-fresh-quick init-cronjob

init-mysql: docker-up switch-to-mysql composer-update migrate-fresh-quick

init-postgres: docker-up switch-to-postgres composer-update migrate-fresh-quick

init-cronjob: 
	- docker-compose exec escola_lms_app bash -c "{ crontab -l; echo \"*/2 * * * * /usr/local/bin/php /var/www/html/artisan schedule:run >> /var/www/html/logfile.log\" } | crontab -"