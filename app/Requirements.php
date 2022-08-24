<?php

namespace CommerceTheme\App;

class Requirements {

    public static function check() {
        add_action( 'after_setup_theme', array( self::class, 'check_requirements' ) );
    }

    public static function check_requirements() {

        $permalink_structure = get_option( 'permalink_structure' );

        if ( $permalink_structure !== '/%year%/%monthnum%/%day%/%postname%/' ) {
            self::print_permalinks_notice();
            return;
        }

    }

    public static function print_permalinks_notice() {
        ?>

    <div style="background: black; padding: 1rem; width: max-content; font-family: monospace; margin: 2rem;">
        <h2 style="color: red; font-size: 3rem">Missing requirement</h2>
        <p style="color: greenyellow; font-size: 1rem">The permalink structure must be set to "Day and name" in order to use this theme.</p>
    </div>


    <?php
}

}
