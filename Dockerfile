FROM php:7.1.5-fpm

COPY . /var/www/html

RUN docker-php-ext-install bcmath \
    && chown -R www-data:www-data /var/www/html \
    && chmod +x /var/www/html \
    && chmod 777 -R /var/www/html/var

WORKDIR /var/www/html/web

EXPOSE 9000
CMD ["php-fpm"]
