FROM php:8.2-cli

RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

COPY . /usr/src/myapp
WORKDIR /usr/src/myapp

EXPOSE 8000

CMD [ "php", "-S", "0.0.0.0:8000" ]