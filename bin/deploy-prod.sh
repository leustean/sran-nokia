#!/usr/bin/env bash

docker exec -u www-data sran-apache-server sh -c "git reset --hard origin/master 2>&1 ; npm ci 2>&1 ; npm run compile-scss 2>&1 ; php bin/console doctrine:migrations:migrate --no-interaction ; composer dump-env prod 2>&1 ; composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader 2>&1"