FROM ubuntu:22.04

# Install Apache
RUN apt-get -y update
RUN apt-get -y install apache2

# Install PHP
RUN apt-get -y install software-properties-common
RUN add-apt-repository ppa:ondrej/php && apt-get update
ENV DEBIAN_FRONTEND noninteractive
RUN apt-get -y install \
php8.1 php8.1-opcache php8.1-imap php8.1-dev php8.1-bcmath php8.1-ldap php8.1-pspell \
php8.1-pdo php8.1-xml php8.1-mbstring php8.1-zip php8.1 php8.1-mysqlnd php8.1-pgsql \
php8.1-dba php8.1-intl php8.1-apcu php8.1-mongodb php8.1-soap php8.1-common php8.1-fpm php8.1-odbc \
php8.1-gd php8.1-imagick libapache2-mod-php8.1 php8.1-curl wget composer sudo vim sudo php8.1-xdebug \
procps telnet iputils-ping
RUN apt-get clean

ARG USER_ID
ARG GROUP_ID

RUN echo "www-data  ALL=(ALL) NOPASSWD:ALL" | sudo tee /etc/sudoers.d/www-data

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

CMD composer install --ignore-platform-reqs --no-dev --optimize-autoloader --no-ansi --no-interaction --no-progress --no-plugins

RUN usermod --non-unique --uid $USER_ID www-data && groupmod --non-unique -g $GROUP_ID www-data
RUN mkdir -p /project/versions/current
RUN chown -R www-data:www-data /project
RUN chown -R www-data:www-data /var/www
RUN update-alternatives --set php /usr/bin/php8.1 \
    && a2enmod php8.1  \
    && a2enmod proxy_fcgi setenvif \
    && a2enconf php8.1-fpm \
    && a2enmod rewrite

COPY ./php/* /etc/php/8.1/fpm/conf.d/
COPY ./php/* /etc/php/8.1/cli/conf.d/
COPY ./php/* /etc/php/8.1/apache2/conf.d/

CMD ["sudo", "apachectl", "-D", "FOREGROUND"]

WORKDIR /project/versions/current

version: "3.9"
services:
  webapp:
    build:
      context: .
      dockerfile: ./Dockerfile
    ports:
      - "8000:80"
    volumes:
      - ./src:/var/www
    environment:
      - APP_KEY=SomeRandomStringToAddSecurity123
      - APP_ENV=development
      - APP_DEBUG=true
      - APACHE_RUN_USER=apache-www-volume
      - APACHE_RUN_GROUP=apache-www-volume