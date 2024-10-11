FROM escolalms/php:8.2-prod
WORKDIR /var/www/html
EXPOSE 80
COPY / /var/www/html
RUN pecl install excimer
RUN \
  # general supervisord settings
  cp docker/conf/supervisor/supervisord.conf /etc/supervisor/supervisord.conf \
  #   supervisord services 
  # && cp -r docker/conf/supervisor/services/* /etc/supervisor/custom.d \
  # devilbox php.ini./ TODO this should be rather send to different custom file 
  && cp docker/conf/php/xxx-devilbox-default-php.ini /usr/local/etc/php/conf.d/xxx-devilbox-default-php.ini \
  # overwrite some php-fpm settings
  && cp docker/conf/php/php-fpm-custom.conf /usr/local/etc/php-fpm.d/php-fpm-custom.conf
RUN composer self-update && composer install --no-scripts
RUN chown -R devilbox:devilbox /var/www/

CMD /var/www/html/init.sh

HEALTHCHECK --interval=30s --timeout=30s --start-period=10s --retries=5 CMD [ "php", "artisan", "health:check" ]