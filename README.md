# Escola LMS

Laravel Headless LMS REST API.

## Installation

```sh
docker-compose build
docker-compose up -d

docker exec -it kibble_app bash
composer update
php artisan key:generate
php artisan migrate:fresh --seed
php artisan passport:keys --force
php artisan passport:client --personal --no-interaction
```

## Demo & Credentials

| Role       | Email ID                  | Password |
| ---------- | ------------------------- | -------- |
| Admin      | admin@escola-lms.com      | secret   |
| Instructor | instructor@escola-lms.com | secret   |
| Student    | student@escola-lms.com    | secret   |

## SWAGGER Docoumentation

[http://localhost:1000/api/documentation](http://localhost:1000/api/documentation)

```
php artisan l5-swagger:generate
```
