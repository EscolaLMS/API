FROM php:8.2-fpm-bookworm
WORKDIR /var/www/html
EXPOSE 80

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/php/install-extensions.sh /usr/local/bin/install-extensions.sh
RUN install-extensions.sh
RUN pecl install excimer && rm -rf /tmp/pear

RUN apt-get update && apt-get install -y --no-install-recommends \
    caddy \
    ffmpeg \
    git \
    gifsicle \
    jpegoptim \
    nodejs \
    npm \
    optipng \
    pngquant \
    procps \
    supervisor \
    unzip \
    webp \
  && npm install -g mjml@4 \
  && ln -sf /usr/local/bin/mjml /usr/bin/mjml \
  && rm -rf /var/lib/apt/lists/* /root/.npm

RUN groupadd -g 1000 devilbox \
  && useradd -u 1000 -g devilbox -m -s /bin/bash devilbox \
  && rm -f /usr/local/etc/php-fpm.d/* /etc/supervisor/conf.d/* \
  && mkdir -p /etc/supervisor/custom.d

COPY / /var/www/html
RUN cp docker/envs/.env.postgres.prod /var/www/html/.env \
  && cp docker/conf/supervisor/supervisord.conf /etc/supervisor/supervisord.conf \
  && cp docker/conf/supervisor/conf.d/php-fpm.conf /etc/supervisor/conf.d/php-fpm.conf \
  && cp -r docker/conf/supervisor/services/* /etc/supervisor/custom.d \
  && cp docker/conf/caddy/Caddyfile /etc/caddy/Caddyfile \
  && cp docker/conf/php/xxx-devilbox-default-php.ini /usr/local/etc/php/conf.d/xxx-devilbox-default-php.ini \
  && cp docker/conf/php/zz-cve-mitigation.ini /usr/local/etc/php/conf.d/zz-cve-mitigation.ini \
  && cp docker/conf/php/php-fpm.conf /usr/local/etc/php-fpm.conf \
  && cp docker/conf/php/php-fpm-custom.conf /usr/local/etc/php-fpm.d/php-fpm-custom.conf
RUN composer install --no-scripts
RUN ln -s /var/www/html/storage/app/public public/storage \
  && ln -s /var/www/html/storage/app/h5p public/h5p \
  && ln -s /var/www/html/vendor/h5p/h5p-core public/h5p-core \
  && ln -s /var/www/html/vendor/h5p/h5p-editor public/h5p-editor
RUN chown -R devilbox:devilbox /var/www/

CMD php docker/envs/envs.php && /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
