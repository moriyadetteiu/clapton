FROM php:7.4-fpm

# TODO: #1 iniファイルのコピーを入れる

RUN apt-get update \
    && apt-get install -y vim zlib1g-dev libpq-dev zip unzip libzip-dev libonig-dev git libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis \
    && docker-php-ext-install mbstring pdo_mysql opcache mysqli bcmath gd zip \
    && docker-php-ext-enable pdo_mysql opcache mysqli bcmath gd zip \
    && docker-php-ext-install redis

# マルチステージビルドを利用してcomposerを入れる
# 参考： https://qiita.com/yatsubashi/items/02bbbebbfe7e5a5976bc
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# コンテナ内ではrootユーザでcomposerを叩くので、警告文を抑制する
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_NO_INTERACTION 1

WORKDIR /var/www/html
