[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg)](https://github.com/csgo-league/csgo-league-web/graphs/commit-activity)
[![GitHub release](https://img.shields.io/github/release/csgo-league/csgo-league-web.svg)](https://github.com/csgo-league/csgo-league-web/releases/)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)
[![Open Source Love svg3](https://badges.frapsoft.com/os/v3/open-source.svg?v=103)](https://github.com/csgo-league)

# CS:GO League Web
A web application for PUG statistics.

Our support discord can be found [here](https://discord.gg/b5MhANU).

# Author
[B3none](https://b3none.co.uk/) - Developer / Maintainer

## Watch for releases

So as to keep the latest version of the plugin I recommend watching the repository

![Watch releases](https://github.com/b3none/gdprconsent/raw/development/.github/README_ASSETS/watch_releases.png)

## Share the love

If you appreciate the project then please take the time to star our repository.

![Star us](https://github.com/b3none/gdprconsent/raw/development/.github/README_ASSETS/star_us.png)

## Recommendations
The steps below are all written with the presumption that you're using Ubuntu.

# Installation

### Prerequisites
1. Apache2
2. Composer
3. NPM
4. ~ PHP 7
5. Gulp
6. Node 10 or newer.
7. Zip and Unzip
8. MySQL 5.7 or MariaDB 10.4
9. OpenSSL

### Default Install
`sudo apt install apache2 composer openssl php php-mysql php-json php-simplexml mysql-server zip unzip -y`

### Ubuntu 18.04 Install
`sudo apt install apache2 composer openssl php7.2 php7.2-mysql php7.2-json php7.2-simplexml mariadb-server zip unzip -y`

### Debian 10.3 Install
`sudo apt install apache2 composer openssl php7.3 php7.3-mysql php7.3-json php7.3-simplexml mariadb-server zip unzip -y`

### Installing NodeJS 10
1. Download and install NodeJS repo: `curl -sL https://deb.nodesource.com/setup_10.x | sudo -E bash -`
2. Install NodeJS version 10: `sudo apt-get install nodejs`
3. Check installed version: `npm -v`
4. Check installed version: `node -v`

### Installing NodeJS Version Manager (https://github.com/nvm-sh/nvm)
1. Download and install NodeJS repo: `curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash`
2. Install NodeJS version 10: `nvm install 10.19`
3. Set NodeJS version to 10: `nvm use 10.19`
4. Check installed version: `npm -v`
5. Check installed version: `node -v`

### Installing the Web Interface
1. Change directory to web default `cd /var/www/`
2. Remove the html directory with `rm -rf html/`
3. Disable the default site `sudo a2dissite 000-default.conf`.
4. Enable mod_rewrite `sudo a2enmod rewrite`.
5. Clone repo: `git clone https://github.com/csgo-league/csgo-league-web`
6. Change directory to repo: `cd csgo-league-web/`
7. Install requirements: `composer install`
8. Install NodeJS requirements: `npm i`
9. Install gulp globally: `sudo npm i -g gulp`
10. Build: `gulp build`

# Database Setup
1. We need to secure our MySQL installation, to do this run the command `mysql_secure_installation`.
2. Follow the steps and make sure to disable remote root login and disable the default database.

### Setting up our user
1. Login to MYSQL with the command `mysql -u root -p`.
2. Now we need to make our database and a user that can connect to it.
```
CREATE USER 'league'@'%' IDENTIFIED BY '{password}';
CREATE DATABASE panel CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
GRANT ALL PRIVILEGES ON panel.* TO 'league'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
```
Then edit your MYSQL Conf to allow external connections to the database.
`nano /etc/mysql/mysql.conf.d/mysqld.cnf` and change the `bind-address` to `0.0.0.0`

Now restart the MySQL service with `sudo service mysql restart`

Next we'll configure the web panel to use our database and communicate with the bot and game servers.
```
cd /var/www/csgo-league-web
cp env.example.php env.php
nano env.php
```
Fill out all of the fields with your information like MySQL, Servers, RCON, and Unique API Key. You can generate your API Key with the link provided in the `env.php` file.

Once finished Migrate your DB with `./vendor/bin/phpmig migrate`

### Server
1. Point the `league` CNAME at your dedicated server.
2. `cd /etc/apache2/sites-available`
3. `nano csgo-league-web.conf`
4. Use the following config:
```apacheconfig
<VirtualHost *:80>
    ServerName league.your.domain

    DocumentRoot /var/www/csgo-league-web/web

    <Directory /var/www/csgo-league-web/web>
        Options -Indexes
        AllowOverride All
        FallbackResource /index.php
    </Directory>
</VirtualHost>
```
5. `sudo a2ensite csgo-league-web.conf`

Finally make sure to `chown -R www-data:www-data app` in the `/csgo-league-web` directory.

You should be all set!

### 429 (Too many requests) fix for projects with lots of website visits.
1. Download `https://github.com/Rob--W/cors-anywhere` or `git clone https://github.com/Rob--W/cors-anywhere.git`
2. Use `screen` or alternative to run this code in background
3. Launch it
```
cd cors-anywhere
node server.js
```
4. Edit `/var/www/csgo-league-web/assets/scripts/listeners/steam-profile.js`,  
change line 14 to  
```axios.get(`http://{YOUR IP}:8080/https://steamcommunity.com/profiles/${steam}?xml=true`)```  
Don't forget to change `{YOUR IP}` to IP of server.  
5. Build web side again  
```
cd /var/www/csgo-league-web/
gulp build
```
P.S.: Don't forget to clean cache of your web browser.

### Debugging
1. if you get `too many redirects` error try change in `env.php` `'WEBSITE' => '/home'` to `'WEBSITE' => ''`
