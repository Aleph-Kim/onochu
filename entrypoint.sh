#! /bin/bash

# cron 서비스 시작
service cron start

# Apache 서비스 시작
apache2-foreground
