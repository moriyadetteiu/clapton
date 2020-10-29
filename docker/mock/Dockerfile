FROM node:latest

# apt-getでのパッケージインストール
RUN apt-get update && \
    apt-get install -y \
    vim

RUN npm install -g node-gyp-build

WORKDIR /usr/src/app
