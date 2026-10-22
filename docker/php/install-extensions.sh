#!/bin/sh
set -eux

apk add --no-cache --virtual .build-deps \
  $PHPIZE_DEPS \
  linux-headers \
  freetype-dev \
  icu-dev \
  libavif-dev \
  libjpeg-turbo-dev \
  libpng-dev \
  libwebp-dev \
  libxpm-dev \
  libzip-dev \
  postgresql-dev

docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-avif --with-xpm

exts="bcmath gd intl pcntl pdo_mysql pdo_pgsql zip"
php -m | grep -q 'Zend OPcache' || exts="$exts opcache"
docker-php-ext-install -j"$(nproc)" $exts

pecl install apcu redis "$@"
docker-php-ext-enable apcu redis "$@"

runDeps="$(
  scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
    | tr ',' '\n' \
    | sort -u \
    | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }'
)"
apk add --no-cache $runDeps
apk del --no-network .build-deps
rm -rf /tmp/pear
