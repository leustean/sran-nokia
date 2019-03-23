#!/usr/bin/env bash

docker exec -u www-data sran-apache-server sh -c "composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader && composer dump-env prod && npm install && npm run compile-scss && php bin/console doctrine:migrations:migrate --no-interaction"