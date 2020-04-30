FROM php:7.4-cli
RUN pecl install xdebug-2.7.2 \
    && docker-php-ext-enable xdebug
COPY . /code
WORKDIR /code
