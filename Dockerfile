# PHP 7 이미지를 사용
FROM php:7.4-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Apache 서버 설정
COPY . /var/www/html/

# Apache 서비스 시작
CMD ["apache2-foreground"]