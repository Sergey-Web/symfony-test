FROM php:8.3-fpm-alpine3.20

ENV XDEBUG_VERSION 3.3.1

RUN apk add --no-cache postgresql-dev fcgi git linux-headers \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && git clone --branch $XDEBUG_VERSION --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug \
    && docker-php-ext-configure xdebug --enable-xdebug-dev \
    && docker-php-ext-install pdo_pgsql xdebug \
    && apk del git linux-headers

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY files/php/php.ini /usr/local/etc/php/php.ini
COPY files/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY files/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY files/php-fpm.conf /usr/local/etc/php-fpm.conf

WORKDIR /app

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s \
    CMD REDIRECT_STATUS=true SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET \
    cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1