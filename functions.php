<?php

use CommerceTheme\App\Requirements;
use CommerceTheme\App\Routes;
use CommerceTheme\App\ScriptsEnqueuer;
use CommerceTheme\App\ThemeRoutes;

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/** Check requirements */

Requirements::check();

/** Load the routes */

$routes_list = Routes::singletone();
$routes_list->scan();

// dump( $routes_list->get_routes() );

ThemeRoutes::singletone( $routes_list )->run();

/** Adds dynamic title */

/** Load the assets */

$enqueuer = new ScriptsEnqueuer();

$assets_dist = get_template_directory_uri() . '/assets/dist';

$enqueuer->add_public_style(
    'main',
    $assets_dist . '/style.css',
    '1.0.0',
);

$enqueuer->add_public_script(
    'app',
    $assets_dist . '/templates-editor/assets/index.js',
    '1.0.0',
);

/**
$enqueuer->add_public_script(
'app',
$assets_dist . '/app.bundle.js',
'1.0.0',
);

$enqueuer->add_public_script(
'index',
$assets_dist . '/index.bundle.js',
'1.0.0',
);
 */

$enqueuer->run();
