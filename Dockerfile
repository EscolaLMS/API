FROM escolalms/php:8.3-alpine
WORKDIR /var/www/html
EXPOSE 9000
COPY / /var/www/html
# RUN \
#   # general supervisord settings
#   cp docker/conf/supervisor/supervisord.conf /etc/supervisor/supervisord.conf \
#   && cp docker/conf/php/xxx-devilbox-default-php.ini /usr/local/etc/php/conf.d/xxx-devilbox-default-php.ini \  
#   && cp docker/conf/php/php-fpm-custom.conf /usr/local/etc/php-fpm.d/php-fpm-custom.conf
RUN composer install --no-scripts

CMD /var/www/html/init.sh

HEALTHCHECK --interval=30s --timeout=30s --start-period=10s --retries=5 CMD [ "php", "artisan", "health:check" ]