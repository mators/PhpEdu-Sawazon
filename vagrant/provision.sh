#!/bin/bash

# MySQL and Apache
sudo debconf-set-selections <<< 'mysql-server-5.6 mysql-server/root_password password root'
sudo debconf-set-selections <<< 'mysql-server-5.6 mysql-server/root_password_again password root'
sudo apt-get update
sudo apt-get -y install mysql-server-5.6 apache2

# PHP 5.6
sudo apt-get install python-software-properties
sudo add-apt-repository ppa:ondrej/php5-5.6 -y
sudo apt-get -y install php5 php5-mysql php5-gd

# Composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin && mv /usr/local/bin/composer.phar /usr/local/bin/composer

# Fill database
if [ ! -f /var/log/databasesetup ]
then
    mysql -uroot -proot < /var/www/html/sawazon_db.sql
    sudo touch /var/log/databasesetup
fi

# Configure apache, php.ini
if [ ! -f /var/log/apacheconfig ]
then
	sudo tee -a /etc/apache2/apache2.conf > /dev/null << EOF
<Directory "/var/www/html">
	AllowOverride All
</Directory>
EOF

	sudo rm /var/www/html/index.html
	sudo touch /var/log/apacheconfig
fi

sudo a2enmod rewrite
sudo service apache2 restart