[![CircleCI](https://circleci.com/gh/csgo-league/csgo-league-web/tree/develop.svg?style=svg)](https://circleci.com/gh/csgo-league/csgo-league-web/tree/develop)
[![HitCount](http://hits.dwyl.io/csgo-league/csgo-league-web.svg)](http://hits.dwyl.io/csgo-league/csgo-league-web)

# CS:GO League Web
A web application for PUG statistics.

# Author
Alex Blackham - Developer and Maintainer - [B3none](https://github.com/b3none/)

## Watch for releases

So as to keep the latest version of the plugin I recommend watching the repository

![Watch releases](https://github.com/b3none/gdprconsent/raw/development/.github/README_ASSETS/watch_releases.png)

## Share the love

If you appreciate the project then please take the time to star our repository.

![Star us](https://github.com/b3none/gdprconsent/raw/development/.github/README_ASSETS/star_us.png)

## Recommendations
The steps below are all written with the presumption that you're using Ubuntu.

## Prerequisites
1. Apache2
2. Composer
3. NPM
4. PHP 7.1 or newer
5. Gulp


## Installation

### Settings
1. `git clone https://github.com/b3none/csgo-league-web`
2. `composer install`
3. `npm i`
4. `npm i -g gulp`
5. `gulp build`

### DB Migration
1. Create Database
2. Change `env.example.php` to `env.php`
3. Edit `env.php` for your Database
4. Migrate your DB with `./vendor/bin/phpmig migrate`


## Serving

### Locally
1. `cd web`
2. `php -S localhost:5000`

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
