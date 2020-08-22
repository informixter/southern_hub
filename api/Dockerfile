FROM php:7.3-fpm-stretch
RUN apt-get update && apt-get install -y bash git libtool libpng-dev libpq-dev librabbitmq-dev zlib1g-dev libicu-dev libxslt-dev nano libzip-dev apt-transport-https wget
RUN docker-php-ext-install -j$(nproc) intl gd bcmath zip pdo_pgsql pgsql sockets xsl soap
ENV COMPOSER_HOME /composer
ENV PATH ./vendor/bin:/composer/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
RUN groupadd -g 242 app
RUN useradd -m -d /var/www/html -g 242 -u 242 -s /bin/bash app
RUN apt-get clean
RUN chown -R app:app /composer
WORKDIR /var/www/html/
