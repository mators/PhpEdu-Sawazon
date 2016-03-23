#!/bin/bash

# MySQL and Apache
sudo debconf-set-selections <<< 'mysql-server-5.6 mysql-server/root_password password root'
sudo debconf-set-selections <<< 'mysql-server-5.6 mysql-server/root_password_again password root'
sudo apt-get update
sudo apt-get -y install mysql-server-5.6 apache2

# PHP 5.6
sudo apt-get update
sudo apt-get install python-software-properties
sudo add-apt-repository ppa:ondrej/php5-5.6 -y
sudo apt-get update
sudo apt-get -y install php5 php5-mysql php5-gd php5-xdebug

# Composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin && mv /usr/local/bin/composer.phar /usr/local/bin/composer

# Fill database
if [ ! -f /var/log/databasesetup ]
then
    echo "CREATE DATABASE sawazon_db" | mysql -uroot -proot
    mysql -uroot -proot < /var/www/html/sawazon_db.sql

    touch /var/log/databasesetup
fi

# Configure apache
if [ ! -f /var/log/apacheconfig ]
then
	insert='\n\n\t<Directory "/var/www/html">\n\t\tAllowOverride All\n\t</Directory>'
	after='DocumentRoot /var/www/html'
	sudo sed -i "s#${after}#${after}${insert}#" /etc/apache2/sites-available/000-default.conf

	service apache2 restart
	a2enmod rewrite
	touch /var/log/apacheconfig
fi
