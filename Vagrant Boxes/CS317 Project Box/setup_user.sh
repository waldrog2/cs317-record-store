#!/bin/bash
sudo useradd developer
echo "developer:developer" | chpasswd
sudo chown -R developer:developer /var/www/html
echo "export APACHE_RUN_USER=developer\nexport APACHE_RUN_GROUP=developer" > /etc/httpd/envvars
echo "ServerTokens ProductOnly" > /etc/httpd/conf.d/security.conf
sudo systemctl restart httpd
ip a > /vagrant/interfaces.txt