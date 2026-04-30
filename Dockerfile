FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock symfony.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

FROM php:8.4-fpm-alpine AS app

WORKDIR /var/www/html

RUN apk add --no-cache \
        bash \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        mariadb-connector-c-dev \
        tzdata \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j"$(nproc)" \
        intl \
        opcache \
        pdo_mysql \
        zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/conf.d/app.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

COPY . /var/www/html
COPY --from=vendor /app/vendor /var/www/html/vendor

# ✅ FIX: créer un user non-root uid 1000 (requis par PodSecurity restricted)
RUN addgroup -g 1000 appuser \
    && adduser -u 1000 -G appuser -s /bin/sh -D appuser \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p var/cache var/log \
    && chown -R appuser:appuser /var/www/html

# ✅ FIX: configurer php-fpm pour tourner avec appuser
RUN sed -i 's/user = www-data/user = appuser/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/group = www-data/group = appuser/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/;listen.owner = www-data/listen.owner = appuser/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/;listen.group = www-data/listen.group = appuser/g' /usr/local/etc/php-fpm.d/www.conf

ENV APP_ENV=prod
ENV APP_DEBUG=0

# ✅ FIX: switcher sur appuser avant de lancer le process
USER 1000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]