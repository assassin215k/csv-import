FROM php:8.1-fpm

RUN apt update \
    && apt install -y libxml2-dev libonig-dev libpq-dev \
            libzip-dev \
            libfreetype6-dev \
            libjpeg62-turbo-dev \
            libpng-dev \
            git \
    && docker-php-ext-install mbstring pdo pdo_pgsql pdo_mysql pgsql bcmath gd intl opcache zip soap sockets\
    && docker-php-ext-enable mbstring pdo pdo_pgsql pdo_mysql pgsql bcmath gd intl opcache zip soap sockets

RUN echo 'memory_limit=-1' >> /usr/local/etc/php/conf.d/docker-php-memory_limit.ini;
RUN echo 'error_reporting=-1' >> /usr/local/etc/php/conf.d/docker-php-error_reporting.ini;
RUN echo 'log_errors_max_len=0' >> /usr/local/etc/php/conf.d/docker-php-log_errors_max_len.ini;
RUN echo 'zend.assertions=1' >> /usr/local/etc/php/conf.d/docker-php-zend.assertions.ini;
RUN echo 'assert.exception=1' >> /usr/local/etc/php/conf.d/docker-php-assert.exception.ini;
RUN echo 'xdebug.show_exception_trace=0' >> /usr/local/etc/php/conf.d/docker-php-xdebug.show_exception_trace.ini;
RUN echo 'suhosin.executor.include.whitelist = phar' >> /usr/local/etc/php/conf.d/docker-php-suhosin.executor.include.whitelist.ini;

# Download using curl
RUN curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar && chmod +x ./phpcs.phar && mv ./phpcs.phar /usr/local/bin/phpcs \
    && curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar && chmod +x ./phpcbf.phar && mv ./phpcbf.phar /usr/local/bin/phpcbf

# latest composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD [ "php-fpm" ]
