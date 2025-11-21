FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    git curl zip unzip libonig-dev libxml2-dev libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev

RUN a2enmod rewrite

RUN printf "<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n" \
> /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

RUN docker-php-ext-install pdo pdo_mysql mbstring zip xml gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
