FROM php:8.2-cli
RUN apt-get update && apt-get install -y \
    zip \
    unzip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY ./Hive /usr/src/ows
WORKDIR /usr/src/ows
EXPOSE 8000
RUN composer install
CMD ["php", "-S", "0.0.0.0:8000"]
