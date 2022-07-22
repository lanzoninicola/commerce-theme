<?php

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/assets/dist/main.bundle.js', array(), '1.0.0', true );
    wp_enqueue_style( 'main', get_stylesheet_directory_uri() . '/assets/dist/main.css', array(), '1.0.0', 'all' );
} );