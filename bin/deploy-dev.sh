#!/usr/bin/env bash

docker exec -u www-data sran-apache-server sh -c "composer install && npm install && npm run compile-scss && php bin/console doctrine:migrations:migrate --no-interaction"