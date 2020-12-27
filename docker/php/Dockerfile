FROM php:7.4-fpm

RUN docker-php-ext-install pdo_mysql

RUN apt-get update && \
apt-get install -y \
libzip-dev

RUN docker-php-ext-install zip

RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
        && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
        && php composer-setup.php && rm composer-setup.php* \
        && chmod +x composer.phar && mv composer.phar /usr/bin/composer

WORKDIR /usr/src/app

COPY --chown=1000:1000 . /usr/src/app

RUN PATH=$PATH:/usr/src/app/vendor/bin:bin

RUN composer install --no-scripts --prefer-dist \
  && rm -rf "$(composer config cache-dir)" "$(composer config data-dir)"