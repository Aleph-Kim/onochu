# PHP 7.4와 Apache 이미지 사용
FROM php:7.4-apache

# 필요한 PHP 확장 모듈 설치
RUN docker-php-ext-install mysqli pdo pdo_mysql

# mod_rewrite 모듈 활성화
RUN a2enmod rewrite

# 커스텀 Apache 설정 파일 복사
COPY apache.conf /etc/apache2/conf-available/custom.conf

# 커스텀 Apache 설정 활성화
RUN a2enconf custom

# 애플리케이션 파일 복사
COPY . /var/www/html/

# Apache 서비스 시작
CMD ["apache2-foreground"]