
FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli

# Allow Apache/PHP to read Render secret files
RUN usermod -a -G 1000 www-data

RUN a2enmod rewrite

ENV PORT=10000

RUN sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i 's/:80>/:10000>/' /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/

EXPOSE 10000

CMD ["apache2-foreground"]
