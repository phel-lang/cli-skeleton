FROM php:8.5-cli

RUN apt-get update && \
    apt-get install -y --no-install-recommends git unzip libzip-dev && \
    docker-php-ext-install zip && \
    rm -rf /var/lib/apt/lists/*

RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.enable_cli=1'; \
        echo 'opcache.jit=tracing'; \
        echo 'opcache.jit_buffer_size=64M'; \
        echo 'opcache.validate_timestamps=1'; \
    } > /usr/local/etc/php/conf.d/zz-opcache.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN useradd -m dev && \
    mkdir -p /srv/phel-cli-skeleton && \
    chown dev:dev /srv/phel-cli-skeleton

WORKDIR /srv/phel-cli-skeleton
USER dev
