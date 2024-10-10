#!/bin/bash
if [ -n "$MULTI_DOMAINS" ]; then
  #TODO make them in random order !!!
  IFS=',' read -ra domains <<< "$MULTI_DOMAINS"

  while [ true ]
  do
    for domain in "${domains[@]}"; do
      # supervisor is set for stdout so it just make fuzz
      #echo "queue work for $domain"      
      php /var/www/html/artisan queue:work --max-jobs=20 --stop-when-empty --domain=$domain
    done
  done
else
  echo "Environment variable MULTI_DOMAINS is empty."
fi