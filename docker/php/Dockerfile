FROM php:8.4-fpm-alpine AS prod

ARG USER_UID=1000
ARG GROUP_GID=1000
ARG UGNAME=app

RUN addgroup -g ${GROUP_GID} ${UGNAME} \
    && adduser -D -u ${USER_UID} -G ${UGNAME} -h /home/${UGNAME} ${UGNAME}

RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
        postgresql-dev \
        bzip2-dev \
        libxml2-dev \
        gmp-dev \
    && apk add --no-cache \
        curl \
        git \
        unzip \
        netcat-openbsd \
        icu \
        libzip \
        postgresql-libs \
        bzip2 \
        libxml2 \
        gmp \
    \
    && docker-php-ext-install -j$(nproc) \
        intl \
        zip \
        bz2 \
        pcntl \
        pdo \
        pdo_pgsql \
        pgsql \
        gmp \
    \
    && apk del .build-deps \
    && rm -rf /tmp/* /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY ./wait-for-it.sh /usr/bin/wait-for-it
RUN chmod +x /usr/bin/wait-for-it

WORKDIR /var/www/app

RUN chown -R ${UGNAME}:${UGNAME} /var/www

FROM prod AS dev

RUN apk add --update linux-headers
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps \
    && rm -rf /tmp/*

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/xdebug.ini

USER ${UGNAME}
