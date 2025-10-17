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

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copie du code
COPY vuln-app/ /var/www/html/

# Création du dossier uploads avec permissions correctes
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod 755 /var/www/html/uploads

# ajouter un flag lisible pour la démo XXE
RUN echo 'Encode_FLAG{eHhlX2Nob3BzaG9wXzIwMjU=}' > /flag_xxe.txt \
    && chown www-data:www-data /flag_xxe.txt \
    && chmod 644 /flag_xxe.txt

# Fix permissions sur le reste du code
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
