ARG PHP_VERSION=8.1
ARG NGINX_VERSION=1.23

# Tool for easier installation of PHP deps
FROM mlocati/php-extension-installer:latest AS php_extension_installer

FROM php:${PHP_VERSION}-fpm-alpine AS vetelib_php

COPY --from=php_extension_installer /usr/bin/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
	;

RUN set -eux; \
    install-php-extensions \
    	apcu \
    	intl \
		  opcache \
    	zip \
			pdo_mysql \
    ;


COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY ./docker/php/conf.d/prod.ini $PHP_INI_DIR/conf.d/api.ini

RUN set -eux; \
composer global config --no-plugins allow-plugins.symfony/flex true; \
composer global require "symfony/flex" --prefer-dist --no-progress --classmap-authoritative; \
composer clear-cache

ENV PATH="${PATH}:/root/.composer/vendor/bin"

# Copy files to container
WORKDIR /srv/api

ENV APP_ENV=prod

COPY composer.json ./
COPY composer.lock ./
COPY symfony.lock ./

RUN set -eux; \
    composer install --prefer-dist --no-dev --no-scripts --no-progress; \
    composer clear-cache

COPY .env .env
RUN composer dump-env prod

COPY bin ./bin/
COPY config ./config/
COPY migrations ./migrations/
COPY public ./public/
COPY src ./src/
COPY templates ./templates/

RUN find config migrations public src templates -type d -exec chmod a+rx {} \;
RUN find config migrations public src templates -type f -exec chmod a+r {} \;

RUN set -eux; \
  mkdir -p var/cache var/log; \
  composer dump-autoload --classmap-authoritative --no-dev; \
  composer run-script --no-dev post-install-cmd; \
  chmod +x bin/console; sync

COPY ./docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

FROM nginx:${NGINX_VERSION}-alpine AS vetelib_nginx

COPY ./docker/nginx/conf.d/default.conf /etc/nginx/conf.d/

WORKDIR /srv/api/public

COPY --from=VETELIB_PHP /srv/api/public ./