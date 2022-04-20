#!/bin/sh
crontab -l | { cat; echo "* * * * * /usr/local/bin/php /var/www/html/artisan schedule:run "; } | crontab -
