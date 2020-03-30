[![HitCount](http://hits.dwyl.io/csgo-league/csgo-league-web.svg)](http://hits.dwyl.io/csgo-league/csgo-league-web)
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
4. PHP 7.1 or newer
5. Gulp
6. Node 10 or newer.
7. Zip and Unzip
8. MySQL 5.7

`sudo apt install apache2 composer php php-mysql php-json php-simplexml mysql-server zip unzip -y`


### Installing NodeJS 10
`curl -sL https://deb.nodesource.com/setup_10.x -o nodesource_setup.sh`
`sudo bash nodesource_setup.sh`
`sudo apt-get install nodejs`

### Installing the Web Interface
1. CD into `/var/www/`
2. Remove the html directory with `rm -rf html/`
3. `git clone https://github.com/csgo-league/csgo-league-web`
4. `cd csgo-league-web/`
5. `composer install`
6. `npm i`
7. `sudo npm i -g gulp`
8. `gulp build`

# Database Setup
1. We need to secure our MySQL installation, to do this run the command `mysql_secure_installation`.
2. Follow the steps and make sure to disable remote root login and disable the default database.

### Setting up our user
1. Login to MYSQL with the command `mysql -u root -p`.
2. Now we need to make our database and a user that can connect to it.
```
CREATE USER 'league'@'%' IDENTIFIED BY '{password}';
CREATE DATABASE panel;
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

Once finished Mirgrate your DB with `./vendor/bin/phpmig migrate`

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
        FallbackResource /index.php
    </Directory>
</VirtualHost>
```
5. `sudo a2ensite csgo-league-web.conf`

Finally make sure to `chown www-data:www-data app` in the `/csgo-league-web` directory.

You should be all set!

### Debugging
1. if you get `too many redirects` error try change in `env.php` `'WEBSITE' => '/home'` to `'WEBSITE' => ''`
