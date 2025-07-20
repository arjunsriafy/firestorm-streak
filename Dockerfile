# Use PHP with Apache
FROM php:8.2-apache

# Copy application files
COPY . /var/www/html/

# Enable Apache rewrite module
RUN a2enmod rewrite

# Update Apache to listen on the Cloud Run expected port
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/:80/:8080/' /etc/apache2/sites-available/000-default.conf

# Set proper PHP settings: suppress deprecated warnings and hide errors
RUN echo "error_reporting = E_ALL & ~E_DEPRECATED & ~E_WARNING" > /usr/local/etc/php/conf.d/error-override.ini && \
    echo "display_errors = Off" >> /usr/local/etc/php/conf.d/error-override.ini && \
    echo "log_errors = On" >> /usr/local/etc/php/conf.d/error-override.ini

# Environment variable for Cloud Run
ENV PORT 8080

# Expose the port Cloud Run expects
EXPOSE 8080

# Start Apache in foreground
CMD ["apache2-foreground"]
