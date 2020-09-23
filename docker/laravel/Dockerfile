FROM php:7.4-fpm

# TODO: #1 iniファイルのコピーを入れる

RUN apt-get update \
    && apt-get install -y vim zlib1g-dev libpq-dev zip unzip libonig-dev \
    && docker-php-ext-install mbstring pdo_mysql opcache mysqli bcmath \
    && docker-php-ext-enable mysqli

# マルチステージビルドを利用してcomposerを入れる
# 参考： https://qiita.com/yatsubashi/items/02bbbebbfe7e5a5976bc
COPY --from=composer /usr/bin/composer /usr/bin/composer

# コンテナ内ではrootユーザでcomposerを叩くので、警告文を抑制する
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_NO_INTERACTION 1

WORKDIR /var/www/html
