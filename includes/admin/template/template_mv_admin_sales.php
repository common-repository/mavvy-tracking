<?php
$mavvysi = MavvyTracking::get_instance();

if($_POST['mavvysi_sales_hidden'] == 'yes') {
    $mavvysi_sales_consumer_key = $_POST['mavvysi_sales_consumer_key'];
    $mavvysi_sales_consumer_secret = $_POST['mavvysi_sales_consumer_secret'];

    $mavvysi->engine->set_consumer_key( $mavvysi_sales_consumer_key);
    $mavvysi->engine->set_consumer_secret( $mavvysi_sales_consumer_secret);

} else {
    $mavvysi_sales_consumer_key = $mavvysi->engine->get_consumer_key();
    $mavvysi_sales_consumer_secret = $mavvysi->engine->get_consumer_secret();
}


?>
<div class="wrap">
    <h2> <b>Mavvy Tracking </b> <small>// Sales tracking settings</small></h2>

    <form name="mavvysi_sales_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="mavvysi_sales_hidden" value="yes">
        <h4>Set WooCommerce Secret Keys</h4>
        <p>
            Consumer key
            <input type="text" name="mavvysi_sales_consumer_key" value="<?php echo $mavvysi_sales_consumer_key;?>" size="70">
        </p>
        <p>
            Consumer secret
            <input type="text" name="mavvysi_sales_consumer_secret" value="<?php echo $mavvysi_sales_consumer_secret;?>" size="70">
        </p>

        <p class="submit">
            <input type="submit" name="mavvysi_sales_submit" value="Update options" />
        </p>
    </form>
</div>