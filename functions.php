<?php

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/assets/dist/main.bundle.js', array(), '1.0.0', true );
    wp_enqueue_style( 'main', get_stylesheet_directory_uri() . '/assets/dist/main.css', array(), '1.0.0', 'all' );
} );

function rewrite_rule() {
    $routes = scandir( get_template_directory() . '/routes' );

    foreach ( $routes as $route ) {

        if ( $route === '.' || $route === '..' ) {
            continue;
        }

        $routes_path = get_template_directory() . '/routes/' . $route;

        if ( is_dir( $routes_path ) ) {

            $route_files = scandir( $routes_path );

            foreach ( $route_files as $route_file ) {

                if ( $route_file === '.' || $route_file === '..' ) {
                    continue;
                }

                $route_file_path = $routes_path . '/' . $route_file;

                if ( is_file( $route_file_path ) ) {
                    $route_file_name = str_replace( '.php', '', $route_file );
                    add_rewrite_rule(
                        $route . '/' . $route_file_name . '/?$',
                        "index.php?pagename=$route-$route_file_name",
                        'top'
                    );
                }

            }

        } else {
            $route_file_name = str_replace( '.php', '', $route );

            add_rewrite_rule(
                $route . '/?$',
                'index.php?pagename=' . $route_file_name,
                'top'
            );

        }

    }

}

function query_vars( $query_vars ) {

    $routes = scandir( get_template_directory() . '/routes' );

    foreach ( $routes as $route ) {

        if ( $route === '.' || $route === '..' ) {
            continue;
        }

        $routes_path = get_template_directory() . '/routes/' . $route;

        if ( is_dir( $routes_path ) ) {

            $route_files = scandir( $routes_path );

            foreach ( $route_files as $route_file ) {

                if ( $route_file === '.' || $route_file === '..' ) {
                    continue;
                }

                $route_file_name = str_replace( '.php', '', $route_file );
                $query_vars[]    = "$route/$route_file_name";

            }

        } else {
            $route_name   = str_replace( '.php', '', $route );
            $query_vars[] = $route_name;
        }

    }

    return $query_vars;

}

function theme_template_include() {

    $template_name = get_query_var( 'pagename' );
    $routes_path   = get_template_directory() . '/routes';

    /** It is the website home-page. Get the template via wordpress standard way.*/

    if ( $template_name === '' ) {
        return;
    }

    /** Browser points to a route folder. If the index.php file exists, use it otherwise returns 404. */

    if ( is_dir( "$routes_path/$template_name" ) ) {

        if ( file_exists( "$routes_path/$template_name/index.php" ) ) {
            return "$routes_path/$template_name/index.php";
        }

        return get_404_template();
    }

    /** Browser point to a subroute */

    if ( strpos( $template_name, '-' ) > 0 ) {
        $path       = explode( '-', $template_name );
        $route_path = $routes_path . '/' . $path[0] . '/' . $path[1] . '.php';

        if ( file_exists( $route_path ) ) {
            return $route_path;
        }

        return get_404_template();
    }

    return "$routes_path/$template_name.php";

}

add_action( 'init', 'rewrite_rule' );
add_filter( 'query_vars', 'query_vars' );
add_action( 'template_include', 'theme_template_include' );
