FROM php:8.3-fpm-alpine3.20

ENV PHP_IDE_CONFIG="serverName=docker"

RUN set -eux \
    && apk add --no-cache \
        icu-libs \
        libintl \
        make \
    && rm -rf /usr/local/etc/php/php.ini* /usr/local/etc/php-fpm.d/* /var/www/*

RUN set -eux \
    && apk add --no-cache --virtual .build-deps \
        autoconf \
        g++ \
        icu-dev \
        linux-headers \
        zlib-dev \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-configure pdo_mysql --with-zlib-dir=/usr \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && pecl install redis-6.1.0 \
    && docker-php-ext-enable redis \
    && pecl install xdebug-3.3.2 \
    && echo ";zend_extension=xdebug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && find "$(php-config --extension-dir)" -name "*.so" -type f -exec strip --strip-all {} \; \
    && docker-php-source delete \
    && apk del .build-deps \
    && rm -rf /tmp/* /usr/src/* /var/cache/apk/* /var/tmp/*

COPY conf.d/php.ini /usr/local/etc/php/php.ini
COPY conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY conf.d/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY conf.d/php-fpm.conf /usr/local/etc/php-fpm.conf

WORKDIR /app

CMD ["php-fpm"]