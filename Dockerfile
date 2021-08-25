FROM php:7.4-alpine as build
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN mkdir app
COPY . /app
RUN cd app && composer install
