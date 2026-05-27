FROM php:8.5-apache

# Installeer systeem afhankelijkheden en PHP extensies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite

# Wijzig de Apache Document Root naar /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Installeer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Vertrouw de projectmap binnen Git om ownership waarschuwingen te voorkomen
RUN git config --global --add safe.directory /var/www/html

WORKDIR /var/www/html
