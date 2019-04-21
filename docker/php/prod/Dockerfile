FROM php:7.3-apache

RUN apt-get update && apt-get -y install gnupg git zip unzip cron

RUN docker-php-ext-install pdo_mysql
RUN \
    apt-get update && \
    apt-get install libldap2-dev -y && \
    docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install ldap

#node js
RUN curl -sL https://deb.nodesource.com/setup_11.x | bash - && apt-get install -y nodejs

#composer
RUN cd /tmp && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php --install-dir=/usr/local/bin --filename=composer && php -r "unlink('composer-setup.php');"
ENV HOME=/home/www-data/
ENV COMPOSER_HOME=/home/www-data/.composer
RUN mkdir -p /home/www-data/.composer && chown -R www-data:www-data /home/www-data/

RUN crontab -l | { cat; echo "* * * * * /usr/local/bin/php /var/www/sran/bin/console app:cron:run:due >> /var/www/sran/var/log/cron.log 2>&1"; } | crontab -

WORKDIR /var/www/sran

CMD service cron start && apache2-foreground