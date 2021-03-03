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

init: docker-up composer-update migrate-fresh-quick
