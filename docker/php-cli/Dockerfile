# Pull php-fpm image
FROM php:8.2-cli-alpine

# Install container dependencies
RUN apk add zsh

# Install php extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install xdebug if a XDEBUG_PORT is set on the environment
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
ARG XDEBUG_PORT

# https://forums.docker.com/t/how-to-install-xdebug-on-an-php-alpine-docker-image/136231
RUN if [ "${XDEBUG_PORT}" ] ; then \
    apk add --no-cache linux-headers; \
	apk add --no-cache --update --virtual .build-dependencies $PHPIZE_DEPS; \
    pecl install xdebug; \
	docker-php-ext-enable xdebug; \
    pecl clear-cache; \
    apk del .build-dependencies; \
    echo "xdebug.client_port=${XDEBUG_PORT}" >> /usr/local/etc/php/conf.d/xdebug.ini; \
else \
    rm /usr/local/etc/php/conf.d/xdebug.ini; \
fi

# Install and run composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add a non-root user to prevent files being created with root permissions on host machine.
RUN addgroup -S appgroup && \
    adduser -S appuser -G appgroup

# Set default work directory
WORKDIR /var/www
