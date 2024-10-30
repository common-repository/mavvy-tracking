<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class MV_Notice {

    public function validation(){
        $this->set_wc_notice();
    }

    public function set_wc_notice(){
        add_action( 'admin_notices', array( $this, 'show_wc_notice' ) );
    }

    public function show_wc_notice(){
        if( !class_exists( 'WC_API_Client' ) || !defined( 'WOOCOMMERCE_VERSION' ) ) {
            echo '<div class="error"><p>' . sprintf( 'Mavvy Tracking depends on the %s to work!','<b>WooCommerce plugin</b>') . '</p></div>';
        }
    }
}
