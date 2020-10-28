FROM php:7.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev
RUN docker-php-ext-install pgsql pdo_pgsql mysqli pdo_mysql 

RUN apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxpm-dev \
    libwebp-dev \
    libxslt1-dev \
    libmemcached-dev \
    libxml2-dev \
    zlib1g-dev \
    libzip-dev

RUN docker-php-ext-configure gd \ 
    --with-freetype-dir \
    --with-jpeg-dir \
    --with-png-dir \
    --with-xpm-dir \
    --enable-gd-jis-conv \
    --with-gd \
    --with-webp-dir \
    --with-zlib-dir 

RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install gd mbstring zip

RUN set -xe \
    && buildDeps=" \
    $PHP_EXTRA_BUILD_DEPS \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libxpm-dev \
    libpng-dev \
    libxslt1-dev \
    libmemcached-dev \
    libxml2-dev \
    " \
    && apt-get update -q -y && apt-get install -q -y --no-install-recommends $buildDeps && rm -rf /var/lib/apt/lists/* \
    #Extract php source and install missing extensions
    && docker-php-source extract 
RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/ --with-xpm-dir=/usr/include/ --enable-gd-jis-conv
RUN docker-php-ext-install exif gd mbstring intl xsl pgsql pdo_pgsql pdo_mysql mysqli zip

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

RUN apt-get update && apt-get install -y cron

# Create the log file
RUN touch /var/log/schedule.log
RUN chmod 0777 /var/log/schedule.log

# Add crontab file in the cron directory
ADD scheduler /etc/cron.d/scheduler

# Run the cron
RUN crontab /etc/cron.d/scheduler

# Add files
ADD entrypoint.sh /entrypoint.sh
 
RUN chmod +x /entrypoint.sh

RUN apt-get update && apt-get install -y nano

CMD [ "sh", "/entrypoint.sh" ]
# CMD [ "sh", "-c", "cron -f & docker-php-entrypoint php-fpm" ]