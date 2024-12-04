# Environment Variables

Application is designed to be stateless - it is controlled by environmental variables

## `LARAVEL_` prefix

Each Variable that that has `LARAVEL_` is converted nto variable set for `.env` file for [Laravel Environment Configuration](https://laravel.com/docs/11.x/configuration#environment-configuration). Note that `.env` is ephemeral and created each time service restarts.

Example

```bash
LARAVEL_APP_NAME=Wellms
LARAVEL_APP_ENV=local
```

will be saved into `.env` as

```bash
APP_NAME=Wellms
APP_ENV=local
```

## `MULTI_DOMAINS` List of domains and specic domain variable

`MULTI_DOMAINS` has comma separated list of domains that will be used for [init_multidomains.sh](init-script.md) script

Each domain can have a specific [Laravel Environment Configuration](https://laravel.com/docs/11.x/configuration#environment-configuration). Note that each domain `.env` is ephemeral and created each time service restarts.

Example

```bash
MULTI_DOMAINS=api17005.localhost,api16576.localhost,api22800.localhost
API17005_LOCALHOST_APP_NAME="App one"
API16576_LOCALHOST_APP_NAME="App two"
API22800_LOCALHOST_APP_NAME="App three"
API22800_LOCALHOST_INITIAL_USER_PASSWORD="password one"
API16576_LOCALHOST_INITIAL_USER_PASSWORD="password one"
API17005_LOCALHOST_INITIAL_USER_PASSWORD="password two"
```

Wil create three `.env` for each domain prefixed

```bash
#.env.api17005.localhost file
APP_NAME="App one"
INITIAL_USER_PASSWORD="password one"
```

```bash
#.env.api16576.localhost file
APP_NAME="App two"
INITIAL_USER_PASSWORD="password one"
```

```bash
#.env.api22800.localhost file
APP_NAME="App three"
INITIAL_USER_PASSWORD="password two"
```

## List of domains

Each

| Variable name                           | Description                                                      | Default             |
| --------------------------------------- | ---------------------------------------------------------------- | ------------------- |
| `LARAVEL_` prefix                       | main Laravel Environment Configuration                           |                     |
| `${DOMAIN_KEY}_` prefix                 | domain specific Laravel Environment Configuration                |                     |
| `MULTI_DOMAINS`                         | Comma separated list of multidomains                             |                     |
| `DISABLE_PHP_FPM`                       | Disable PHP FPM Supervisor process                               | false               |
| `DISABLE_HORIZON`                       | Disable Laravel Horizon Supervisor process                       | false               |
| `DISABLE_SCHEDULER`                     | Disable Laravel Scheduler Supervisor process                     | false               |
| `DISABLE_H5P_STORAGE_LINK`              | Disable h5p storage link                                         | false               |
| `JWT_PUBLIC_KEY_BASE64`                 | Base64 encoded `storage/oauth-public.key`                        |                     |
| `JWT_PRIVATE_KEY_BASE64`                | Base64 encoded `storage/oauth-private.key`                       |                     |
| `${DOMAIN_KEY}_JWT_PUBLIC_KEY_BASE64`   | Base64 encoded `storage/oauth-public.key` for domain             |                     |
| `${DOMAIN_KEY}_JWT_PRIVATE_KEY_BASE64`  | Base64 encoded `storage/oauth-private.key` for domain            |                     |
| `DISABLE_DB_MIGRATE`                    | Disable Laravel Database migration on startup                    | false               |
| `DISABLE_DB_SEED`                       | Disable Laravel Permissions Database Seed migration on startup   | false               |
| `DISABLE_QUEUE`                         | Disable Laravel Queue Supervisor process (only for Multidomains) | false               |
| `INITIAL_USER_PASSWORD`                 | Initial admin password                                           |                     |
| `INITIAL_USER_FIRST_NAME`               | Initial admin first name                                         | Root                |
| `INITIAL_USER_LAST_NAME`                | Initial admin last name                                          | Admin               |
| `INITIAL_USER_EMAIL`                    | Initial admin email                                              | admin@escolalms.com |
| `${DOMAIN_KEY}_INITIAL_USER_PASSWORD`   | Initial admin password for domain                                |                     |
| `${DOMAIN_KEY}_INITIAL_USER_FIRST_NAME` | Initial admin first name for domain                              | Root                |
| `${DOMAIN_KEY}_INITIAL_USER_LAST_NAME`  | Initial admin last name for domain                               | Admin               |
| `${DOMAIN_KEY}_INITIAL_USER_EMAIL`      | Initial admin email for domain                                   | admin@escolalms.com |
