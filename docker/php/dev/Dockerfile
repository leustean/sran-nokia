FROM php:7.2.1-apache

RUN apt-get update && apt-get -y install gnupg git

RUN docker-php-ext-install pdo_mysql

#node js
RUN curl -sL https://deb.nodesource.com/setup_11.x | bash - && apt-get install -y nodejs

#composer
RUN cd /tmp && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php --install-dir=/usr/local/bin --filename=composer && php -r "unlink('composer-setup.php');"
ENV HOME=/home/www-data/
ENV COMPOSER_HOME=/home/www-data/.composer
ENV COMPOSER_ALLOW_XDEBUG=1
RUN mkdir -p /home/www-data/.composer && chown -R www-data:www-data /home/www-data/

#xdebug
RUN pecl install xdebug; \
    docker-php-ext-enable xdebug; \
    echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;



WORKDIR /var/www/sran