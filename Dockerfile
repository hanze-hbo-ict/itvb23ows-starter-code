FROM php:8.2-cli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
COPY . /usr/src/ows
WORKDIR /usr/src/ows
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000"]
