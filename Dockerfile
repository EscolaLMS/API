ARG PHP_IMAGE=php:8.4-fpm-alpine
# Set BASE_IMAGE to a published base image to skip building the php-base stage.
ARG BASE_IMAGE=php-base

FROM ${PHP_IMAGE} AS php-base

COPY --from=mlocati/php-extension-installer:2.12.3 /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer:2.10.3 /usr/bin/composer /usr/local/bin/composer

RUN apk add --no-cache \
    bash \
    supervisor \
    ffmpeg \
    unzip \
    jpegoptim optipng pngquant gifsicle libwebp \
  && install-php-extensions \
    apcu \
    bcmath \
    gd \
    intl \
    opcache \
    pcntl \
    pdo_mysql \
    pdo_pgsql \
    redis \
    zip

COPY docker/docker-entrypoint.sh /docker-entrypoint.sh

ENV COMPOSER_ALLOW_SUPERUSER=1
WORKDIR /var/www/html
EXPOSE 9000
ENTRYPOINT ["/docker-entrypoint.sh"]

FROM ${BASE_IMAGE} AS app
WORKDIR /var/www/html
COPY / /var/www/html
RUN \
  cp docker/conf/supervisor/supervisord.conf /etc/supervisord.conf \
  && cp docker/conf/php/escolalms-custom-php.ini /usr/local/etc/php/conf.d/escolalms-custom-php.ini \
  && cp docker/conf/php/php-fpm-custom.conf /usr/local/etc/php-fpm.d/zz-escolalms.conf
RUN composer install --no-scripts --no-dev --no-interaction --prefer-dist

CMD /var/www/html/init.sh

HEALTHCHECK --interval=30s --timeout=30s --start-period=10s --retries=5 CMD [ "php", "artisan", "health:check" ]

FROM app AS develop
RUN install-php-extensions excimer

# Default target - keep last.
FROM app AS production
