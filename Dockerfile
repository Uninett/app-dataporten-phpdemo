FROM tutum/apache-php:latest
MAINTAINER Kasper Rynning-Tønnesen <kasper@kasperrt.no>

RUN apt-get update && apt-get install -yqq git && rm -rf /var/lib/apt/lists/*
RUN /usr/local/bin/composer self-update

ADD ./webapp /app

RUN composer install