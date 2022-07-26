# note: 最新版だと未対応パッケージがあったため、16を指定している
#       https://qiita.com/yuzu_283879391/items/8e9acaea257d64231037
FROM node:16

# apt-getでのパッケージインストール
RUN apt-get update && \
    apt-get install -y \
    vim

RUN npm install -g node-gyp-build

WORKDIR /usr/src/app
