<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'MV_Admin_Menus' ) ) :

class MV_Admin_Menus {

    private $template;

    public function __construct() {
        $this->notice = new MV_Notice();
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );

        add_action( 'admin_bar_menu', array( $this, 'admin_bar_menus' ), 31 );

        $this->template = new MV_Template_Admin();
    }

    public function admin_menu() {
        global $menu;

        $menu[57] = array('','read',"separator-mavvysi",'','wp-menu-separator');

        add_menu_page( 'Mavvy', 'Mavvy', 'manage_options', 'mavvysi', array($this, 'get_sales_template'), null, '57.5' );
    }

    public function get_sales_template(){
        $this->template->output('sales');
    }

    public function admin_bar_menus( $wp_admin_bar ) {
        if ( ! is_admin() || ! is_user_logged_in() ) {
            return;
        }

        if ( ! is_user_member_of_blog() && ! is_super_admin() ) {
            return;
        }

        $wp_admin_bar->add_node( array(
            'parent' => 'site-name',
            'id'     => 'view-mavvy-app',
            'title'  => 'Visit Mavvy App',
            'href'   => 'http://app.mavvy.co/',
            'meta'   => array(
                'target' => 'blank'
            )
        ) );
    }
}

endif;

return new MV_Admin_Menus();