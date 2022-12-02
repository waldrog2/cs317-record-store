#!/bin/bash
sudo dnf install -y nginx
sudo rm -R /usr/share/nginx
sudo ln -s /var/www /usr/share/nginx
sed -i 's/enforcing/disabled/'  /etc/selinux/config
sudo setenforce 0
cp /vagrant/nginx.conf /etc/nginx/nginx.conf
cp /vagrant/localhost.conf /etc/nginx/conf.d/
# chcon -Rt httpd_sys_content_t /usr/share/nginx/html
# chcon -Rt httpd_sys_content_t /usr/share/nginx/images
