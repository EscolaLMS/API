# Escola LMS

Laravel Headless LMS REST API.

## Installation

To install defult docker enviroment.

### Postgres (default)

```sh
make init
```

### Mysql

```
make init-mysql
```

## Demo & Credentials

| Role       | Email ID                  | Password |
| ---------- | ------------------------- | -------- |
| Admin      | admin@escola-lms.com      | secret   |
| Instructor | instructor@escola-lms.com | secret   |
| Student    | student@escola-lms.com    | secret   |

## SWAGGER Docoumentation

[http://localhost:1000/api/documentation](http://localhost:1000/api/documentation)

## Tasks

See [makefile](makefile) for all availabe devops tasks

- `make fix-style`
- `make test-phpunit`
- `make test-behat`
- `make bash`
- `make ide-helper`
- `make migrate-fresh-quick`
- `make composer-update`
- `make swagger-generate`
- `make h5p-seed`
- `make migrate-fresh`
- `make refresh`
- `make docker-up`
- `make switch-to-postgres`
- `make switch-to-mysql`
- `make migrate-mysql`
- `make migrate-postgres`
- `make test-phpunit-postgres`
- `make test-behat-postgres`
- `make test-phpunit-mysql`
- `make test-behat-mysql`
- `make init`
- `make init-mysql`
- `make init-postgres`
