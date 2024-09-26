# Wellms Single or Multi Domain API Mode.

Multi-domain is controlled by environmental variables and usage of [gecche/laravel-multidomain](https://github.com/gecche/laravel-multidomain)

## How it works. Example

There is no better documentation than example, aka "specification by example"

Launch Wellms api as usual with, eg `docker compose up -d` with `docker-compose.yml` package from this repository- now single domain wellms is working, as you can check under http://api.localhost

1. Launch appliaction with default setting

```bash
docker compose up -d
```

Url [http://api.localhost/api/name](http://api.localhost/api/name) should return "Application Name: Wellms" and URL [http://api.localhost/api/courses](http://api.localhost/api/courses) JSON with courses.

Enter docker bash with `make bash` now do

```bash
cd multidomain-tool
composer install
```

Then you need to get to know some variables

docker compose -f docker-compose.yml -f docker-compose.saas.yml up -d
