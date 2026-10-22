ARG PHP_IMAGE=php:8.4-fpm-alpine
ARG BASE_IMAGE=php-base

FROM ${PHP_IMAGE} AS php-base

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/php/install-extensions.sh /usr/local/bin/install-extensions.sh

RUN apk add --no-cache \
    bash \
    supervisor \
    ffmpeg \
    unzip \
    jpegoptim optipng pngquant gifsicle libwebp \
  && install-extensions.sh

COPY docker/docker-entrypoint.sh /docker-entrypoint.sh
COPY docker/conf/supervisor/supervisord.conf /etc/supervisord.conf
COPY docker/conf/php/escolalms-custom-php.ini /usr/local/etc/php/conf.d/escolalms-custom-php.ini
COPY docker/conf/php/php-fpm-custom.conf /usr/local/etc/php-fpm.d/zz-escolalms.conf

ENV COMPOSER_ALLOW_SUPERUSER=1
WORKDIR /var/www/html
EXPOSE 9000
ENTRYPOINT ["/docker-entrypoint.sh"]

FROM ${BASE_IMAGE} AS app
WORKDIR /var/www/html
COPY / /var/www/html
RUN composer install --no-scripts --no-dev --no-interaction --prefer-dist

CMD /var/www/html/init.sh

HEALTHCHECK --interval=30s --timeout=30s --start-period=10s --retries=5 CMD [ "php", "artisan", "health:check" ]

FROM app AS develop
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers \
  && pecl install excimer \
  && docker-php-ext-enable excimer \
  && apk del --no-network .build-deps \
  && rm -rf /tmp/pear

FROM app AS production
