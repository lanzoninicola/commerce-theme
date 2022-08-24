<?php

namespace CommerceTheme\App;

class ThemeRoutes {

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
     * When the REGEX matches, WordPress will execute a query to find content.
     *
     * For example, if we have a route like this:
     * ["account.php/?$"]=> string(26) "index.php?pagename=account"
     *
     * Wordpress search on DB if there is a post of type page with the name "account".
     *
     * For our custom routes, we need to create a custom query (see the function "query_vars" below).
     *
     * add_rewrite_rule( $regex, $query_vars, $flags );
     */
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

    /**
     * Query vars are the variables that WordPress will use to find the content.
     * We need to add our custom query vars here.
     *
     * @param [type] $query_vars
     * @return void
     */
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
