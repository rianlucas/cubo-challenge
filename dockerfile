FROM php:8.4

WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    nodejs \
    npm \
    default-libmysqlclient-dev

RUN docker-php-ext-install pdo pdo_mysql

COPY composer.* /var/www/html
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --prefer-dist --no-scripts --no-autoloader

COPY . /var/www/html
RUN composer dump-autoload

EXPOSE 8000

# Start the Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
