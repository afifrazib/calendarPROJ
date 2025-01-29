FROM yiisoftware/yii2-php:8.4-apache

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/src|g' /etc/apache2/sites-available/000-default.conf
RUN apt-get update && apt-get install -y bash

# Installing mariadb-client
RUN apt-get install mariadb-client -y bash
# Installing mysqli
RUN docker-php-ext-install mysqli

#RUN chown www-data:www-data /src -R

# Add php-imap module
#RUN apt-get update && apt-get install -y libc-client-dev libkrb5-dev && rm -r /var/lib/apt/lists/*
#RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install -j$(nproc) imap
