#!/bin/bash
sudo dnf install -y php-common php-cli composer php-fpm php-pcre php-pdo php-mysqlnd
sudo rm -R /var/www/cgi-bin
sed -i 's/user = apache/user = nginx/g' /etc/php-fpm.d/www.conf
sed -i 's/group = apache/group = nginx/g' /etc/php-fpm.d/www.conf
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g' /etc/php.ini
sudo systemctl enable --now php-fpm