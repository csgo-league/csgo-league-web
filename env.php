<?php

$variables = [
    'DB_HOST' => 'denial.network',
    'DB_USERNAME' => 'denialnetwork',
    'DB_PASSWORD' => 'cQcXAdZk78',
    'DB_NAME' => 'denialnetwork_matches',
    'SITE_NAME' => 'RedlineCS',
    'WEBSITE' => 'https://redlinecs.net',
    'PAGE_TITLE' => 'RedlineCS | Matches',
    'LIMIT' => 10, // Page Limit for matches page.
    'MAPS' => [
        'de_austria,img/maps/austria.jpg',
        'de_cache,img/maps/cache.jpg',
        'de_canals,img/maps/canals.jpg',
        'de_cbble,img/maps/cbble.jpg',
        'de_dust,img/maps/dust.png',
        'de_shortdust,img/maps/dust.png',
        'de_dust2,img/maps/dust2.jpg',
        'de_mirage,img/maps/mirage.jpg',
        'de_nuke,img/maps/nuke.jpg',
        'de_shortnuke,img/maps/nuke.jpg',
        'de_overpass,img/maps/overpass.jpg',
        'de_train,img/maps/train.jpg',
        'de_inferno,img/maps/inferno.jpg',
    ]
];

foreach ($variables as $key => $value) {
    if (is_array($value)) {
        $value = implode(',', $value);
    }

    putenv("$key=$value");
}