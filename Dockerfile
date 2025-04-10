FROM php:8.2-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_sqlite

COPY . /var/www/html

RUN mkdir -p /var/www/html/temp \
    /var/www/html/log \
    /var/www/html/data \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/temp \
    && chmod -R 775 /var/www/html/log \
    && chmod -R 775 /var/www/html/data

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/temp


EXPOSE 8000
USER www-data
CMD ["php", "-S", "0.0.0.0:8000", "-t", "www"]