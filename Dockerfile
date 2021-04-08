FROM jcsilkey/php:8.0.3-cli

COPY --chown=php-cli:php-cli composer.* /app

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --no-autoloader

COPY --chown=php-cli:php-cli . /app

RUN composer dumpautoload \
    --optimize \
    --apcu \
    --classmap-authoritative

CMD ["bin/app", "serve"]
