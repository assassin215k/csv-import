FROM php:8.1-fpm

RUN apt update \
    && apt install -y libxml2-dev libonig-dev libpq-dev \
            libzip-dev \
            libfreetype6-dev \
            libjpeg62-turbo-dev \
            libpng-dev \
    && docker-php-ext-install mbstring pdo pdo_pgsql pdo_mysql pgsql bcmath gd intl opcache zip soap sockets \
    && docker-php-ext-enable mbstring pdo pdo_pgsql pdo_mysql pgsql bcmath gd intl opcache zip soap sockets

# Download using curl
RUN curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar && chmod +x ./phpcs.phar && mv ./phpcs.phar /usr/local/bin/phpcs \
    && curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar && chmod +x ./phpcbf.phar && mv ./phpcbf.phar /usr/local/bin/phpcbf

RUN echo 'memory_limit = 512M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;

# latest composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD [ "php-fpm" ]
