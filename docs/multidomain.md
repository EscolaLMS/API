# Wellms Single or Multi Domain API Mode.

Multi-domain is controlled by environmental variables and usage of [gecche/laravel-multidomain](https://github.com/gecche/laravel-multidomain)

## How it works. Example

There is no better documentation than example, aka "specification by example"

Launch Wellms api as usual with, eg `docker compose up -d` with `docker-compose.yml` package from this repository- now single domain wellms is working, as you can check under http://api.localhost

1. Launch application with default setting

```bash
docker compose up -d
```

Url [http://api.localhost/api/name](http://api.localhost/api/name) should return "Application Name: Wellms" and URL [http://api.localhost/api/courses](http://api.localhost/api/courses) JSON with courses.

Lets test it with Admin panel

// todo test here

Enter docker bash with `make bash` now do

```bash
cd multidomain-tool
composer install
```

Then you need to get to know some variables

// list variables

now add some domains with the following helper

```bash
#!/bin/bash
AWS_ROOT_ACCESS_KEY_ID=masoud \
AWS_ROOT_SECRET_ACCESS_KEY=tg9t712TG1Odn17fisxXM9y01YrD \
AWS_ENDPOINT=http://minio:9000 \
AWS_API_ENDPOINT=http://minio:9001 \
AWS_URL_PREFIX=http://storage.localhost:1001 \
DB_ROOT_HOST=postgres \
DB_ROOT_PORT=5432 \
DB_ROOT_USERNAME=default \
DB_ROOT_PASSWORD=secret \
 ./multidomain add api$RANDOM.localhost,api$RANDOM.localhost,api$RANDOM.localhost,api$RANDOM.localhost
```

output should be similar to

```
Use those values for adding new domain
Pass them into Docker Container Enviroment Variables
-------------
API6388_LOCALHOST_AWS_ACCESS_KEY_ID=api6388localhost
API6388_LOCALHOST_AWS_SECRET_ACCESS_KEY=19df28aae054ed64
API6388_LOCALHOST_AWS_BUCKET=api6388localhost
API6388_LOCALHOST_AWS_URL=http://storage.localhost:1001/api6388localhost
API6388_LOCALHOST_DB_DATABASE="api6388_localhost"
API6388_LOCALHOST_DB_USERNAME="api6388_localhost"
API6388_LOCALHOST_DB_PASSWORD=c87c65d817805b3c
API15686_LOCALHOST_AWS_ACCESS_KEY_ID=api15686localhost
API15686_LOCALHOST_AWS_SECRET_ACCESS_KEY=7e131a0e989beb39
API15686_LOCALHOST_AWS_BUCKET=api15686localhost
API15686_LOCALHOST_AWS_URL=http://storage.localhost:1001/api15686localhost
API15686_LOCALHOST_DB_DATABASE="api15686_localhost"
API15686_LOCALHOST_DB_USERNAME="api15686_localhost"
API15686_LOCALHOST_DB_PASSWORD=e5a2e2ce0ca23f8f
API7001_LOCALHOST_AWS_ACCESS_KEY_ID=api7001localhost
API7001_LOCALHOST_AWS_SECRET_ACCESS_KEY=4677a0d7a74268af
API7001_LOCALHOST_AWS_BUCKET=api7001localhost
API7001_LOCALHOST_AWS_URL=http://storage.localhost:1001/api7001localhost
API7001_LOCALHOST_DB_DATABASE="api7001_localhost"
API7001_LOCALHOST_DB_USERNAME="api7001_localhost"
API7001_LOCALHOST_DB_PASSWORD=c6820a9139e1edfa
API26938_LOCALHOST_AWS_ACCESS_KEY_ID=api26938localhost
API26938_LOCALHOST_AWS_SECRET_ACCESS_KEY=6a421e2906375708
API26938_LOCALHOST_AWS_BUCKET=api26938localhost
API26938_LOCALHOST_AWS_URL=http://storage.localhost:1001/api26938localhost
API26938_LOCALHOST_DB_DATABASE="api26938_localhost"
API26938_LOCALHOST_DB_USERNAME="api26938_localhost"
API26938_LOCALHOST_DB_PASSWORD=5a6cf4966db76c7b
MULTI_DOMAINS=api6388.localhost,api15686.localhost,api7001.localhost,api26938.localhost
If you forgot to copy those above, don't worry they are saved to /var/www/html/multidomain-tool/build/api6388.localhost,api15686.localhost,api7001.localhost,api26938.localhost.env
```

New lets add those values into docker yaml setup, create a file `docker-compose.saas.yml`

```yaml
services:
  escola_lms_app:
    command: "./init.sh"
    environment:
      - MULTI_DOMAINS=api1104.localhost,api6999.localhost,api7438.localhost
      - API1104_LOCALHOST_APP_NAME="app one"
      - API1104_LOCALHOST_AWS_ACCESS_KEY_ID=api1104localhost
      - API1104_LOCALHOST_AWS_SECRET_ACCESS_KEY=0ac782e7cf1b248b
      - API1104_LOCALHOST_AWS_BUCKET=api1104localhost
      - API1104_LOCALHOST_AWS_URL=http://storage.localhost:1001/api1104localhost
      - API1104_LOCALHOST_DB_DATABASE="api1104_localhost"
      - API1104_LOCALHOST_DB_USERNAME="api1104_localhost"
      - API1104_LOCALHOST_DB_PASSWORD=1df9cb51bb5ae4c6
      - API6999_LOCALHOST_APP_NAME="App two"
      - API6999_LOCALHOST_AWS_ACCESS_KEY_ID=api6999localhost
      - API6999_LOCALHOST_AWS_SECRET_ACCESS_KEY=c9dd1b15eff0cf83
      - API6999_LOCALHOST_AWS_BUCKET=api6999localhost
      - API6999_LOCALHOST_AWS_URL=http://storage.localhost:1001/api6999localhost
      - API6999_LOCALHOST_DB_DATABASE="api6999_localhost"
      - API6999_LOCALHOST_DB_USERNAME="api6999_localhost"
      - API6999_LOCALHOST_DB_PASSWORD=056b5feb99c618a7
      - API7438_LOCALHOST_APP_NAME="app three"
      - API7438_LOCALHOST_AWS_ACCESS_KEY_ID=api7438localhost
      - API7438_LOCALHOST_AWS_SECRET_ACCESS_KEY=a20a3e4cd6c94e87
      - API7438_LOCALHOST_AWS_BUCKET=api7438localhost
      - API7438_LOCALHOST_AWS_URL=http://storage.localhost:1001/api7438localhost
      - API7438_LOCALHOST_DB_DATABASE="api7438_localhost"
      - API7438_LOCALHOST_DB_USERNAME="api7438_localhost"
      - API7438_LOCALHOST_DB_PASSWORD=a1a439f4d6428663
```

Now run the following command

```bash
docker compose -f docker-compose.yml -f docker-compose.saas.yml up -d
```

Lets add admin config for those new api endpoints

// todo add php file and attach
// todo, what about generating admin user ?
//
