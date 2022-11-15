#!/bin/bash
sudo dnf module enable -y mariadb:10.9
sudo dnf module install -y mariadb/server
# sudo mysqld_safe --skip-grant-tables --skip-networking &
cp /vagrant/enable_utf8.preset /etc/my.cnf.d/
sudo systemctl enable --now mariadb
sleep 10
sudo mysql -sfu root < /vagrant/secure_mysql.sql

# sudo killall mysqld_safe
