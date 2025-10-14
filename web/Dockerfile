FROM php:8.2-apache

# Installer dépendances système nécessaires pour compiler les extensions
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install mysqli pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Activer mod_rewrite si besoin
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copier ton code (montage en dev peut remplacer ça)
COPY vuln-app/ /var/www/html/

# Fix permissions si nécessaire
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
