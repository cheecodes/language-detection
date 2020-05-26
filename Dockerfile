FROM php:7.3-cli

RUN apt update && apt install -yy -qq git curl zip unzip

WORKDIR /www

COPY . /www

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');"

RUN php composer.phar install --prefer-dist --no-progress

RUN ./vendor/bin/phpunit
