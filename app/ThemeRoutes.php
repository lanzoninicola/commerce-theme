<?php

namespace CommerceTheme\App;

class ThemeRoutes {

    public static function rewrite_rule() {

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

    public static function query_vars( $query_vars ) {
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

    public static function include_template() {
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

    public static function run() {

        add_action( 'init', array( __CLASS__, 'rewrite_rule' ) );
        add_filter( 'query_vars', array( __CLASS__, 'query_vars' ) );
        add_action( 'template_include', array( __CLASS__, 'include_template' ) );

    }

}
