# Use a base image that includes PHP and other necessary extensions
FROM php:7.4-fpm

# Install mlocati/php-extension-installer for installing PHP extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

# Install xdebug extension
RUN install-php-extensions xdebug

# Configure xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey = stagingkey" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Set PHP_IDE_CONFIG environment variable
ENV PHP_IDE_CONFIG='serverName=staging'

