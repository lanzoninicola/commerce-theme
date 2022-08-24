<?php

namespace CommerceTheme\App;

class ScriptsEnqueuer {

    /**
     * Contains the data of all style to register.
     *
     * @var array
     */
    protected $public_styles = array();

    /**
     * Contains the data of all script to register.
     *
     * @var array
     */
    protected $public_scripts = array();

    /**
     * Add a new style to the collection to be registered with WordPress.
     *
     * @param [type] $handle
     * @param [type] $src
     * @param array $deps
     * @param boolean $ver
     * @param string $media
     * @return void
     */
    public function add_public_style( $handle, $src, $ver = false, $deps = array(), $media = 'all' ) {
        $this->public_styles[$handle] = array(
            'handle'  => $handle,
            'src'     => $src,
            'deps'    => $deps,
            'version' => $ver,
            'media'   => $media,
        );
    }

    /**
     * Add a new ***public*** script to the collection to be registered with WordPress.
     *
     * @param [type] $handle
     * @param [type] $src
     * @param array $deps
     * @param boolean $ver
     * @param boolean $in_footer
     * @return void
     */
    public function add_public_script( $handle, $src, $ver = false, $deps = array(), $in_footer = true ) {
        $this->public_scripts[$handle] = array(
            'handle'    => $handle,
            'src'       => $src,
            'deps'      => $deps,
            'version'   => $ver,
            'in_footer' => $in_footer,
        );
    }

    /**
     * Register the stylesheets for the public side
     */
    public function enqueue_styles() {

        foreach ( $this->public_styles as $style ) {

            wp_enqueue_style(
                $style['handle'],
                $style['src'],
                $style['deps'],
                $style['version']
            );

        }

    }

    /**
     * Register the scripts
     */
    public function enqueue_scripts() {

        foreach ( $this->public_scripts as $script ) {

            wp_enqueue_script(
                $script['handle'],
                $script['src'],
                $script['deps'],
                $script['version'],
                $script['in_footer']
            );

        }

    }

    /**
     * Deregister the scripts
     */
    public function deregister_scripts() {

        foreach ( $this->deregister_scripts as $script ) {

            wp_deregister_script( $script );

        }

    }

    /**
     * Add the related actions to load the stylesheet and javascript.
     *
     * Currently the priority is set default to 10, but this might be implemented in the future.
     *
     * @return void
     */
    public function run() {

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

    }

}
