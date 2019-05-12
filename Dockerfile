FROM php:7.1-cli
RUN pecl install xdebug-2.7.2 \
    && docker-php-ext-enable xdebug
COPY . /code
WORKDIR /code
