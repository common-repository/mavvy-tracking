<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'MV_Admin_Menus' ) ) :

class MV_Engine {

    private $woocommerce_api;

    private $consumer_key;
    private $consumer_secret;

    public function __construct(){
        $this->includes();
        $this->prepare_secret_keys();
        $this->prepare_woocommerce();
    }

    /**
     * Sales tracking
     */

    private function includes(){
        include_once( 'lib'. DIRECTORY_SEPARATOR .'woocommerce-api.php' );
    }

    private function prepare_woocommerce(){
        if( $this->is_set_woocommerce() ){
            $consumer_key = $this->get_consumer_key();
            $consumer_secret = $this->get_consumer_secret();
            $options = array(
                'ssl_verify'      => false,
            );

            $this->woocommerce_api = new WC_API_Client( get_bloginfo('url'), $consumer_key, $consumer_secret, $options);
            add_action( 'wp_footer', array( $this, 'send_order_information' ), 99999 );
        }
        return null;
    }

    public function send_order_information(){
        if( is_order_received_page()) {

            if( isset($_GET) && isset($_GET['key']) && $this->is_set_woocommerce()){
                $order_key = $_GET['key'];
                $order_id = wc_get_order_id_by_order_key( $order_key);
                $order_all_details = $this->woocommerce_api->orders->get($order_id);
                $order_all_details_object = $order_all_details->order;
                $order_products = array();
                foreach( $order_all_details_object->line_items as $product){
                    $order_products[] = array(
                        'name' => $product->name,
                        'quantity' => $product->quantity,
                        'product_price' => $product->price,
                        'product_id' => $product->product_id,
                    );
                }

                $order_prepared_details = array(
                    'order_id' => $order_key,
                    'products' => $order_products
                );

                $content = $order_prepared_details;
                echo sprintf("<script>
                    if( typeof _mavvy !== 'undefined' && _mavvy ){
                        _mavvy.registerSale('%s', %s);
                    };
                    </script>", $content['order_id'], json_encode($content['products']));
            }
        }
    }

    private function is_set_woocommerce(){
        return $this->is_set_consumer_key() && $this->is_set_consumer_secret();
    }

    private function is_set_consumer_key(){
        return $this->consumer_key !== null && $this->consumer_key !== "";
    }

    private function is_set_consumer_secret(){
        return $this->consumer_secret !== null  && $this->consumer_secret !== "";
    }

    private function prepare_secret_keys(){
        // get consumer key
        if( $this->get_consumer_key() === null ){
            $consumer_key = get_option('mavvysi_sales_consumer_key');
            if( $consumer_key !== false || $consumer_key !== "" ){
                $this->consumer_key = $consumer_key;
            } else {
                $this->consumer_key = null;
            }
        }

        // get consumer secret
        if( $this->get_consumer_secret() === null){
            $consumer_secret = get_option('mavvysi_sales_consumer_secret');
            if( $consumer_secret !== false || $consumer_secret !== "" ){
                $this->consumer_secret = $consumer_secret;
            } else {
                $this->consumer_secret = null;
            }
        }
    }

    public function get_consumer_key(){
        return $this->consumer_key;
    }

    public function get_consumer_secret(){
        return $this->consumer_secret;
    }

    public function set_consumer_key( $consumer_key){
        if( $consumer_key !== null){
            $this->consumer_key = $consumer_key;
            update_option('mavvysi_sales_consumer_key', $consumer_key);
        }
    }

    public function set_consumer_secret( $consumer_secret){
        if( $consumer_secret !== null){
            $this->consumer_secret = $consumer_secret;
            update_option('mavvysi_sales_consumer_secret', $consumer_secret);
        }
    }

    /**
     * Visits tracking
     */

    public function set_embed_on_frontend(){
        add_action( 'wp_footer', array( 'MV_Engine', 'embed_visit_tracking' ) );
    }

    public function embed_visit_tracking(){
        echo '<script>
            var _mavvy=function(a){function b(a,b){var c=document.createElement("script");
            c.type="text/javascript",c.readyState?c.onreadystatechange=function(){
            "loaded"!=c.readyState&&"complete"!=c.readyState||(c.onreadystatechange=null,b());
            }:c.onload=function(){b()},c.src=a,document.getElementsByTagName("head")[0].appendChild(c);
            }return a.init=function(){b("https://app.mavvy.co/embed/tracking.min.js?_mavvy_cbid=1464102223124",function(){});
            },a.dealNumber=null,a.saleQueue=[],a.setDealNumber=function(b){
            a.dealNumber=b},a.registerSale=function(b,c){
            a.saleQueue.push({saleID:b,products:c})},a;
            }(window._mavvy||{});_mavvy.init();
        </script>';
    }

}

endif;