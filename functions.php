<?php

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/assets/dist/main.bundle.js', array(), '1.0.0', true );
    wp_enqueue_style( 'main', get_stylesheet_directory_uri() . '/assets/dist/main.css', array(), '1.0.0', 'all' );
} );

// dump( scandir( get_template_directory() . '/routes' ) );

// for each file inside the routes folder, check if it is a PHP file. If it is, remove the extension and add rewrite rule

function rewrite_rule() {

    foreach ( scandir( get_template_directory() . '/routes' ) as $file ) {

        if ( strpos( $file, '.php' ) !== false ) {
            $file = str_replace( '.php', '', $file );
            add_rewrite_rule( $file, 'index.php?pagename=' . $file, 'top' );
        }

    }

}

function query_vars( $query_vars ) {

    foreach ( scandir( get_template_directory() . '/routes' ) as $file ) {

        if ( strpos( $file, '.php' ) !== false ) {
            $file         = str_replace( '.php', '', $file );
            $query_vars[] = $file;
        }

    }

    return $query_vars;

}

function theme_template_include() {

    $template_name = get_query_var( 'pagename' );
    $template_path = get_template_directory() . '/routes/' . $template_name . '.php';

    return $template_path;

// $template_file = file_exists( $template_path ) ? $template_path : false;

// $template_data = $template_file ? Database::get_template_data( $template_name ) : false;

// if ( $template_file && is_array( $template_data ) ) {

//     echo Frontend::render_data( $template_data, get_the_ID(), $template_name, true );

// } else {

//     echo '<h1>' . esc_html__( '404 - Whoops, that page is gone', 'commerce-theme' ) . '</h1>';
    // }

}

add_action( 'init', 'rewrite_rule' );
add_filter( 'query_vars', 'query_vars' );
add_action( 'template_include', 'theme_template_include' );

add_action( 'init', function () {
    add_rewrite_rule( 'character/([a-z]+)[/]?$', 'index.php?character=$matches[1]', 'top' );
} );

add_filter( 'query_vars', function ( $query_vars ) {
    $query_vars[] = 'character';
    return $query_vars;
} );

add_action( 'template_include', function ( $template ) {

    if ( get_query_var( 'character' ) == false || get_query_var( 'character' ) == '' ) {
        return $template;
    }

    return get_template_directory() . '/character.php';
} );