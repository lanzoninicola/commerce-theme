<?php

namespace CommerceTheme\App;

class Routes {

    /**
     * Path of the routes directory.
     *
     * @var string
     */
    private string $root_dir_path = '';

    /**
     * List of files/folder that represents the routes
     *
     * @var array
     */
    private array $routes = array();

    /**
     * Instance of the class.
     *
     * @var Routes|null
     */
    public static $instance = null;

    // singletone call
    public static function singletone() {

        if ( self::$instance === null ) {
            self::$instance = new Routes();
        }

        return self::$instance;
    }

    public function __construct() {
        $this->root_dir_path = $this->get_routes_root_dir_path();
    }

    /**
     * Get the full path of the root routes directory.
     *
     * @return string
     */
    public function get_routes_root_dir_path() {
        return get_template_directory() . '/routes';
    }

    /**
     * List files and directories inside the routes directory.
     *
     * @return void
     */
    public function scan() {
        $list = scandir( $this->root_dir_path );

        foreach ( $list as $route_file_dir ) {

            if ( $route_file_dir === '.' || $route_file_dir === '..' ) {
                continue;
            }

            $route_filename_path = $this->root_dir_path . '/' . $route_file_dir;

            $this->routes[$route_file_dir] = $route_filename_path;

        }

    }

    /**
     * Get the list of the first-level file/directories inside the "routes" folder.
     *
     * Example:
    -routes: array:5 [▼
    "account.php"   => ".../wp-content/themes/commerce-theme/routes/account.php"
    "bar.php"       => ".../wp-content/themes/commerce-theme/routes/bar.php"
    "foo"           => ".../wp-content/themes/commerce-theme/routes/foo"
    "index.php"     => ".../wp-content/themes/commerce-theme/routes/index.php"
    ]
     *
     * @return array
     */
    public function get_routes() {
        return $this->routes;
    }

}
