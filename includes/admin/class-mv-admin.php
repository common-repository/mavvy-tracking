<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * MV_Admin class.
 */
class MV_Admin {

    public function __construct() {
        add_action( 'init', array( $this, 'includes' ) );
    }
    
    public function includes(){
        include_once('class-mv-template.php');
        include_once('class-mv-admin-menus.php');
    }

}

return new MV_Admin();