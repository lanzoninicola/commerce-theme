<?php

namespace CommerceTheme\App;

class Helpers {

    /**
     * Get the current page visited.
     *
     * @var string
     */
    public static function current_page() {
        $url   = $_SERVER['REQUEST_URI'];
        $parts = explode( '/', $url );
        $last  = end( $parts );

        /** if the last part is empty, it means that the url ends with a slash.
         * So it is the home page */

        if ( $last === '' ) {
            return 'index';
        }

        return $last;
    }

}
