<?php

$variables = [
    'DB_HOST' => 'denial.network',
    'DB_USERNAME' => 'denialnetwork',
    'DB_PASSWORD' => 'cQcXAdZk78',
    'DB_NAME' => 'denialnetwork_matches',
    'SITE_NAME' => 'Redline', // Name of Site
    'WEBSITE' => 'https://redlinecs.net', // Name of Site
    'PAGE_TITLE' => 'Redline | Matches', // Page title in browser.
    'LIMIT' => 10, // Page Limit for match cards.
    'MAPS' => [
        // 'Path/To/Image' => 'full_map_name',
        'assets/img/maps/austria.jpg' => 'de_austria',
        'assets/img/maps/cache.jpg' => 'de_cache',
        'assets/img/maps/canals.jpg' => 'de_canals',
        'assets/img/maps/cbble.jpg' => 'de_cbble',
        'assets/img/maps/dust.png' => 'de_dust',
        'assets/img/maps/dust.png' => 'de_shortdust',
        'assets/img/maps/dust2.jpg' => 'de_dust2',
        'assets/img/maps/mirage.jpg' => 'de_mirage',
        'assets/img/maps/nuke.jpg' => 'de_nuke',
        'assets/img/maps/nuke.jpg' => 'de_shortnuke',
        'assets/img/maps/overpass.jpg' => 'de_overpass',
        'assets/img/maps/train.jpg' => 'de_train',
        'assets/img/maps/inferno.jpg' => 'de_inferno'
    ]
];

foreach ($variables as $key => $value) {
    if (is_array($value)) {
        $value = implode(',', $value);
    }

    putenv("$key=$value");
}