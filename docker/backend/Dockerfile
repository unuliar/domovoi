# Базовый образ с nginx и php
FROM richarvey/nginx-php-fpm

# Добавляем наше веб приложение
ADD . /var/www/backend

# Удаляем конфиги сайтов которые там есть
RUN rm -Rf /etc/nginx/sites-enabled/*
RUN docker-php-ext-install pdo_pgsql

# Добавляем наш конфиг
ADD docker/backend/conf/nginx/site.conf /etc/nginx/sites-available/site.conf

# Включаем его
RUN ln -s /etc/nginx/sites-available/site.conf /etc/nginx/sites-enabled/site.conf