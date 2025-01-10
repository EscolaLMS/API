FROM escolalms/php:8.3-bookworm
WORKDIR /var/www/html
EXPOSE 9000
COPY / /var/www/html
RUN \
  cp docker/conf/supervisor/supervisord.conf /etc/supervisord.conf \
  && cp docker/conf/php/escolalms-custom-php.ini /usr/local/etc/php/conf.d/escolalms-custom-php.ini \  
  && cp docker/conf/php/php-fpm-custom.conf /usr/local/etc/php-fpm.d/php-fpm-custom.conf
RUN composer install --no-scripts

CMD /var/www/html/init.sh

HEALTHCHECK --interval=30s --timeout=30s --start-period=10s --retries=5 CMD [ "php", "artisan", "health:check" ]