<?php
/**
 * Plugin Name: Mavvy Tracking
 * Description: Mavvy Tracking is a plugin that has the possibility of tracking visits and sales using technologies behind Mavvy LLC.
 * Author: Digitalya OPS
 * Author URI: http://www.digitalya.ro
 * Version: 1.0.3
 * 
 * Requires at least: 4.1
 * Tested up to: 4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'MavvyTracking' ) ) :

class MavvyTracking {


    public $version = '1.0.0';

    public $query = null;

    public $embed_instance = false;

    public $notice;
    public $engine;

    protected static $instance = null;

    public function __construct(){
        $this->includes();
        $this->define_constants();

        if( $this->is_request('admin')){
            $this->notice = new MV_Notice();
            $this->notice->validation();
        }

        $this->engine = new MV_Engine();
        $this->engine->set_embed_on_frontend();
    }

    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function define_constants(){

        $this->define( 'MAVVY_PLUGIN_FILE', __FILE__ );
        $this->define( 'MAVVY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
        $this->define( 'MAVVY_PLUGIN_PATH', __DIR__ );
        $this->define( 'MV_VERSION', $this->version );
        $this->define( 'MAVVY_VERSION', $this->version );
    }

    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    public function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined( 'DOING_AJAX' );
            case 'cron' :
                return defined( 'DOING_CRON' );
            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

    public function includes(){

        if ( $this->is_request( 'admin' ) ) {
            include_once( 'includes/admin/class-mv-notice.php' );
            include_once( 'includes/admin/class-mv-admin.php' );
        }
        include_once( 'includes/admin/class-mv-engine.php' );

    }
}

add_action( 'plugins_loaded', array( 'MavvyTracking', 'get_instance' ), 0 );

endif;
