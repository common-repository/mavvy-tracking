<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'MV_Template_Admin' ) ) :

class MV_Template_Admin {

    public function output( $type){
        $ds = DIRECTORY_SEPARATOR;
        if( is_file(MAVVY_PLUGIN_PATH . $ds ."includes". $ds ."admin". $ds ."template". $ds ."template_mv_admin_$type.php")){
            require_once("template". $ds ."template_mv_admin_$type.php");
        } else {
            echo 'We are working on this page :)';
        }
    }
}

endif;