ARG PHP_EXTENSIONS="mysqli pdo_mysql gd pdo_pgsql pgsql intl"
FROM thecodingmachine/php:7.4-v4-apache

# docker image build -t abc . && docker run -p 5000:5000 abc

ENV APP_ENV=prod \
  APACHE_DOCUMENT_ROOT=public/ \
  ABSOLUTE_APACHE_DOCUMENT_ROOT=/var/www/html/public \
  PORT=5000 \
  PHP_EXTENSIONS="mysqli pdo_mysql gd pdo_pgsql pgsql intl" \
  PACKAGIST_URL=repo.packagist.org

EXPOSE 5000

COPY / /var/www/html/
COPY .env.kub /var/www/html/.env

USER root
RUN sudo sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf \
  && chown -R docker:docker /var/www \
  && chmod -R 777 /var/www/html/storage \
  && touch /var/www/html/storage/logs/laravel.log \
  && chmod 777 /var/www/html/storage/logs/laravel.log \
  && sudo apt update -y \
  && sudo apt install -y build-essential \
  && composer config --global github-protocols https \
  && composer config -g repo.packagist composer https://$PACKAGIST_URL \
  && COMPOSER_MEMORY_LIMIT=-1 composer install
