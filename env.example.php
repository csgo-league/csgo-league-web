<?php

$variables = [
    'DB_HOST' => '', // Your database ip address or host name.
    'DB_USERNAME' => '', // Your database username.
    'DB_PASSWORD' => '', // Your database password.
    'DB_NAME' => '', // Your database name.
    'STEAM_API_KEY' => '', // Your steam API key.
    'BASE_TITLE' => 'B3none', // This is the base title for your site.
    'MATCHES_PAGE_LIMIT' => 10, // Page limit for matches page.
    'PLAYERS_PAGE_LIMIT' => 12, // Page limit for players page.

    // This is a crude hack to store an array in the env.
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
    ]
];

foreach ($variables as $key => $value) {
    if (is_array($value)) {
        $value = implode(',', $value);
    }

    putenv("$key=$value");
}