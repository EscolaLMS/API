#!/bin/sh
set -eux

savedAptMark="$(apt-mark showmanual)"

apt-get update
apt-get install -y --no-install-recommends \
  libavif-dev \
  libfreetype-dev \
  libicu-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libpq-dev \
  libwebp-dev \
  libxpm-dev \
  libzip-dev

docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-avif --with-xpm
docker-php-ext-install -j"$(nproc)" bcmath exif gd intl opcache pcntl pdo_mysql pdo_pgsql zip

pecl install apcu redis "$@"
docker-php-ext-enable apcu redis "$@"

apt-mark auto '.*' > /dev/null
apt-mark manual $savedAptMark > /dev/null
find /usr/local -type f -executable -exec ldd '{}' ';' \
  | awk '/=>/ { so = $(NF-1); if (index(so, "/usr/local/") == 1) { next }; gsub("^/(usr/)?", "", so); printf "*%s\n", so }' \
  | sort -u \
  | xargs -r dpkg-query --search \
  | cut -d: -f1 \
  | sort -u \
  | xargs -r apt-mark manual
apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false
rm -rf /var/lib/apt/lists/* /tmp/pear
