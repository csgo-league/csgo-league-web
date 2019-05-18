# CS:GO League Web
A web application for PUG statistics.

## Recommendations
The steps below are all written with the presumption that you're using Ubuntu.

## Prerequisites
1. Apache2
2. Composer
3. NPM
4. PHP 7.1 or newer
5. Gulp

## Installation
1. `git clone https://github.com/b3none/csgo-league-web`
2. `composer install`
3. `npm i`
4. `npm i -g gulp`
5. `gulp build`

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