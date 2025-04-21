# PHP 7.4와 Apache 이미지 사용
FROM php:7.4-apache

# 필요한 PHP 확장 모듈 설치
RUN docker-php-ext-install mysqli pdo pdo_mysql

# mod_rewrite 모듈 활성화
RUN a2enmod rewrite

# mod_remoteip 모듈 활성화
RUN a2enmod remoteip

# 커스텀 Apache 설정 파일 복사
COPY apache.conf /etc/apache2/conf-available/custom.conf

# 커스텀 Apache 설정 활성화
RUN a2enconf custom

# Composer 설치에 필요한 패키지 설치 (curl, unzip, git)
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git

# Composer 설치
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 애플리케이션 파일 복사
COPY . /var/www/html/

# composer 라이브러리 설치
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Apache 서비스 시작
CMD ["apache2-foreground"]
