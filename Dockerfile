# PHP 7.4와 Apache 이미지 사용
FROM php:7.4-apache

# 한국 시간대 설정
ENV TZ=Asia/Seoul
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

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

# ubuntu 패키지 설치
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    cron

# Composer 설치
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 애플리케이션 파일 복사
COPY . /var/www/html/

# 크론 파일 복사
COPY cronfile /etc/cron.d/cronfile

# 크론 파일 설정
RUN crontab /etc/cron.d/cronfile

# composer 라이브러리 설치
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# entrypoint 스크립트 복사
COPY entrypoint.sh /entrypoint.sh

# entrypoint 스크립트 실행 권한 부여
RUN chmod +x /entrypoint.sh

# entrypoint 스크립트 실행
ENTRYPOINT [ "/entrypoint.sh" ]