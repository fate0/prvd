FROM php:7.2-apache
MAINTAINER fate0 <fate0@fatezero.org>


# 注释为中国区域使用的源
# RUN echo 'deb http://mirrors.aliyun.com/debian/ stretch main non-free contrib' > /etc/apt/sources.list

RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && \
    curl -sOSL https://github.com/fate0/xmark/archive/master.zip && unzip -q master.zip && rm master.zip && \
    curl -sOSL https://github.com/fate0/prvd/archive/master.zip && unzip -q master.zip && rm master.zip && \
    mkdir -p /data && \
    mv xmark-master /data/xmark && \
    mv prvd-master /data/prvd && \
    curl --silent --show-error https://getcomposer.org/installer | php && \
#    COMPOSER_ALLOW_SUPERUSER=1 php composer.phar config repo.packagist composer https://packagist.phpcomposer.com -d /data/prvd/ && \
    COMPOSER_ALLOW_SUPERUSER=1 php composer.phar install -d /data/prvd/ --no-dev && \
    rm composer.phar \
    && \
    mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install iconv gd mysqli pdo pdo_mysql && \
    docker-php-ext-configure /data/xmark --enable-xmark && \
    docker-php-ext-install /data/xmark && \
    rm -rf /data/xmark \
    && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*


COPY prvd.ini $PHP_INI_DIR/conf.d/prvd.ini


EXPOSE 80


COPY entrypoint.php /
ENTRYPOINT ["php", "-n", "/entrypoint.php"]