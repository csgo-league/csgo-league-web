<?php

$variables = [
    'DB_HOST' => '', // Your database ip address or host name.
    'DB_USERNAME' => '', // Your database username.
    'DB_PASSWORD' => '', // Your database password.
    'DB_PORT' => '', // Your database port.
    'DB_NAME' => '', // Your database name.
    'STEAM_API_KEY' => '', // Your steam API key.
    'MATCHES_PAGE_LIMIT' => 10, // Page limit for matches page.
    'PLAYERS_PAGE_LIMIT' => 12, // Page limit for players page.
    'WEBSITE' => '/home', // Path to your main website
    'URL' => '', // The URL to the league panel
    'DISCORD' => '', // A permanent invite link to your Discord server

    'BASE_TITLE' => 'B3none', // This is the base title for your site.
    'DESCRIPTION' => 'Top quality PUGs and 10 mans.', // Meta description

    // This is a crude hack to store a keyed array in the env.
    // map_name,/path/to/map/from/web
    'MAPS' => [
        'de_austria,/img/maps/austria.jpg',
        'de_cache,/img/maps/cache.jpg',
        'de_canals,/img/maps/canals.jpg',
        'de_cbble,/img/maps/cbble.jpg',
        'de_dust,/img/maps/dust.png',
        'de_shortdust,/img/maps/dust.png',
        'de_dust2,/img/maps/dust2.jpg',
        'de_mirage,/img/maps/mirage.jpg',
        'de_nuke,/img/maps/nuke.jpg',
        'de_shortnuke,/img/maps/nuke.jpg',
        'de_overpass,/img/maps/overpass.jpg',
        'de_train,/img/maps/train.jpg',
        'de_inferno,/img/maps/inferno.jpg',
    ],

    'RCON' => '', // Servers RCON password. (Must be the same on every server)
    'SERVERS' => [
        // Servers (this cannot be a URL)
        // 'ip:port'
    ],

    // You should also randomly generate an API key
    // Here's a link which will generate one for you
    // I'd suggest giving each API key a comment saying what it is
    // https://www.random.org/strings/?num=1&len=20&digits=on&upperalpha=on&loweralpha=on&unique=on&format=plain
    'API_KEYS' => [
        // '', // Discord bot
        // '', // CS:GO Server
    ],
];

foreach ($variables as $key => $value) {
    if (is_array($value)) {
        $value = implode(',', $value);
    }

    putenv("$key=$value");
}
