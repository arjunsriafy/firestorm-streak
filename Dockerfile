FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy your code to the container
COPY . /var/www/html/

# Set correct working dir and permissions
WORKDIR /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Enable .htaccess override
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
