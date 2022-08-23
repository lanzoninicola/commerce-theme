<?php

namespace CommerceTheme\App;

class ThemeRoutes {

    /**
     * Object that own of the structure of the "routes" folder.
     *
     * @var Routes
     */
    private Routes $routes;

    /**
     * Instance of the class.
     *
     * @var ThemeRoutes|null
     */
    public static $instance = null;

    // singletone call
    public static function singletone( Routes $routes ) {

        if ( self::$instance === null ) {
            self::$instance = new ThemeRoutes( $routes );
        }

        return self::$instance;
    }

    public function __construct( Routes $routes ) {
        $this->routes = $routes;
    }

    /**
     * Here we create the rules that WordPress will use to match the requests.
     *
     * add_rewrite_rule( $regex, $query_vars, $flags );
     */
    public function rewrite_rule() {

        foreach ( $this->routes->get_routes() as $route_php_file => $route_full_path ) {

            if ( is_dir( $route_full_path ) ) {

                $route_files = scandir( $route_full_path );

                foreach ( $route_files as $route_file ) {

                    if ( $route_file === '.' || $route_file === '..' ) {
                        continue;
                    }

                    $route_file_path = $route_full_path . '/' . $route_file;

                    if ( is_file( $route_file_path ) ) {
                        $route_file_name = str_replace( '.php', '', $route_file );
                        add_rewrite_rule(
                            $route_php_file . '/' . $route_file_name . '/?$',
                            "$route_php_file/$route_file_name",
                            'top'
                        );
                    }

                }

            } else {
                $route_file_name = str_replace( '.php', '', $route_php_file );

                add_rewrite_rule(
                    $route_file_name . '/?$',
                    $route_php_file,
                    'top'
                );

            }

        }

    }

    public function include_template() {
        $route_name       = get_query_var( 'pagename' );
        $routes_root_path = $this->routes->get_routes_root_dir_path();

        /** It is the website home-page. Get the template via wordpress standard way.*/

        if ( $route_name === '' ) {
            return;
        }

        /** Browser points to a route folder. If the index.php file exists, use it otherwise returns 404. */

        if ( is_dir( "$routes_root_path/$route_name" ) ) {

            if ( file_exists( "$routes_root_path/$route_name/index.php" ) ) {
                return "$routes_root_path/$route_name/index.php";
            }

            return get_404_template();
        }

        /** Browser point to a subroute */

        if ( strpos( $route_name, '-' ) > 0 ) {

            $path       = explode( '-', $route_name );
            $route_path = $routes_root_path . '/' . $path[0] . '/' . $path[1] . '.php';

            if ( file_exists( $route_path ) ) {
                return $route_path;
            }

            return get_404_template();
        }

        return "$routes_root_path/$route_name.php";

    }

    public function run() {

        add_action( 'init', array( $this, 'rewrite_rule' ) );
        add_action( 'template_include', array( $this, 'include_template' ) );

    }

}
