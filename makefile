include .env
#export $(shell sed 's/=.*//' envfile)
export POSTGRES_DB=postgresql://$(DB_USERNAME):$(DB_PASSWORD)@127.0.0.1:$(DB_PORT)/$(DB_DATABASE)
export NOW_DB_PREFIX=$(date +\%Y-\%m-\%d-\%H:\%M:\%S)

test-phpunit:
	- docker compose exec api bash -c "./vendor/bin/phpunit"

bash:
	- docker compose exec api bash

bash-sudo:
	- docker compose exec api bash

migrate-fresh-quick:
	- docker compose exec api bash -c "XDEBUG_MODE=off php artisan migrate:fresh --seed"
	- docker compose exec api bash -c "XDEBUG_MODE=off php artisan passport:keys --force"
	- docker compose exec api bash -c "XDEBUG_MODE=off php artisan passport:client --personal --no-interaction"
	- docker compose exec api bash -c "cp storage/oauth-private.key vendor/orchestra/testbench-core/laravel/storage/oauth-private.key"

composer-update:
	- docker compose up -d api
	- docker compose exec api bash -c "XDEBUG_MODE=off composer self-update"
	- docker compose exec api bash -c "XDEBUG_MODE=off composer update"
## supervisd must be restarted, horizon & scheduler must fetch new code
	- docker compose restart escola_lms_queue_cron

restart_queue_cron:
	- docker compose restart escola_lms_queue_cron

update-composer-to-git:
	- git checkout develop
	- git pull
	- docker compose up -d api
	- docker compose exec api bash -c "XDEBUG_MODE=off composer update --no-scripts"
	- git add composer.lock
	- git commit -m "updating dependencies"
	- git push origin develop


swagger-generate:
	- docker compose exec api bash -c "XDEBUG_MODE=off php artisan l5-swagger:generate"

h5p-seed:
	- docker compose exec api bash -c "XDEBUG_MODE=off php artisan db:seed --class=H5PLibrarySeeder"
	- docker compose exec api bash -c "XDEBUG_MODE=off php artisan db:seed --class=H5PContentSeeder"
	- docker compose exec api bash -c "XDEBUG_MODE=off php artisan db:seed --class=H5PContentCoursesSeeder"

node-packages:
# install globally at /usr/bin/mjml
	- docker compose exec api bash -c "npm install -g mjml"

tinker:
# use CTRL+C to quit and CTRL+D to refresh tinker
	- docker compose exec api bash -c "while true; do php artisan tinker; done"

migrate-fresh: migrate-fresh-quick h5p-seed



refresh: composer-update migrate-fresh h5p-seed

docker-up:
	- docker compose up -d

switch-to-postgres:
	- cp docker/envs/.env.postgres.example .env
	- docker compose exec api bash -c "php artisan config:cache"

migrate-postgres: switch-to-postgres migrate-fresh-quick

test-phpunit-postgres: switch-to-postgres test-phpunit

test-fresh: migrate-fresh-quick test-phpunit

# creates a backup file into `data` folder
backup-postgres:
	- docker compose exec postgres bash -c "pg_dump --clean --dbname=$(POSTGRES_DB) -f /var/lib/postgresql/backups/backup-$(NOW_DB_PREFIX).sql"
	- docker compose exec postgres bash -c "cp /var/lib/postgresql/backups/backup-$(NOW_DB_PREFIX).sql  /var/lib/postgresql/backups/backup-latest.sql"

# imports database backup from data folder
# make import BACKUP_FILE=backup-2020-09-15-14:49:22.sql
# or
# make import BACKUP_FILE=backup-latest.sql
#import-postgres: backup-postgres

import-postgres:
	- docker compose exec postgres bash -c "psql --dbname=$(POSTGRES_DB) < /var/lib/postgresql/backups/$(BACKUP_FILE)"

init: docker-up switch-to-postgres composer-update migrate-fresh-quick

wait:
	- docker compose exec api bash -c "./wait.sh"
