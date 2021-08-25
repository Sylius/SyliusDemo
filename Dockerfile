FROM php:7.4-alpine as php

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN set -eux; \
    apk add --no-cache \
    coreutils \
    freetype-dev \
    icu-dev \
    libpng-dev \
    libzip-dev \
    libtool \
    libwebp-dev \
    libzip-dev \
    mariadb-dev \
    zlib-dev \
    ;

RUN docker-php-ext-install -j$(nproc) \
    exif \
    gd \
    intl \
    pdo_mysql \
    zip \
    ;

RUN mkdir app
COPY . /app
RUN cd app && composer install

FROM nginx:alpine AS nginx

COPY docker/nginx/default.conf /etc/nginx/conf.d/

WORKDIR /srv/sylius

COPY --from=php /app/public public/

EXPOSE 80
#COPY --from=node /srv/sylius/public public/
