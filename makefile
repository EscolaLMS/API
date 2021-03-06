fix-style:
	- docker-compose exec escola_lms_app bash -c "./vendor/bin/php-cs-fixer fix ."

test-phpunit:
	- docker-compose exec escola_lms_app bash -c "./vendor/bin/phpunit"

test-behat:
	- docker-compose exec escola_lms_app bash -c "./vendor/bin/behat --colors"

bash:
	- docker-compose exec escola_lms_app bash

ide-helper:
	- docker-compose exec escola_lms_app bash -c "php artisan ide-helper:generate && mv _ide_helper.php .phan/stubs/_ide_helper.php"

migrate-fresh-quick:
	- docker-compose exec escola_lms_app bash -c "php artisan migrate:fresh --seed"
	- docker-compose exec escola_lms_app bash -c "php artisan passport:keys --force"
	- docker-compose exec escola_lms_app bash -c "php artisan passport:client --personal --no-interaction"
	- docker-compose exec escola_lms_app bash -c "cp storage/oauth-private.key vendor/orchestra/testbench-core/laravel/storage/oauth-private.key"

composer-update:
	- docker-compose exec escola_lms_app bash -c "composer update"

swagger-generate:
	- docker-compose exec escola_lms_app bash -c "php artisan l5-swagger:generate"

h5p-seed:
	- docker-compose exec escola_lms_app bash -c "php artisan db:seed --class=H5PLibrarySeeder"
	- docker-compose exec escola_lms_app bash -c "php artisan db:seed --class=H5PContentSeeder"
	- docker-compose exec escola_lms_app bash -c "php artisan db:seed --class=H5PContentCoursesSeeder"

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

test-behat-postgres: switch-to-postgres test-behat

test-phpunit-mysql: switch-to-mysql test-phpunit

test-behat-mysql: switch-to-mysql test-behat

test-fresh: migrate-fresh-quick test-phpunit

init: docker-up switch-to-postgres composer-update migrate-fresh-quick

init-mysql: docker-up switch-to-mysql composer-update migrate-fresh-quick

init-postgres: docker-up switch-to-postgres composer-update migrate-fresh-quick
