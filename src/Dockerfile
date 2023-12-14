# Use the PHP 7.4 image with Apache as the base image
FROM php:7.4-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Copy the application files into the container
COPY . /var/www/html/

