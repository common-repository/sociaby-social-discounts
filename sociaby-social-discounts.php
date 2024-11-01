<?php
/*
Plugin Name: Sociaby Social Discounts
Plugin URI: http://sociaby.com
Description: Adds Sociaby Social widget to the checkout page. Requires WP-Ecommerce (GetShopped).
Version: 1.0
Author: Sociaby
Author URI: http://sociaby.com
License: GPL2
*/

/*
 This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, version 2.
 This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 */

function sociaby_plugins_loaded() {

    if ( class_exists ( 'WP_eCommerce' ) )
        require_once ( 'sociaby-social-discounts-wpec.php' );

    if ( class_exists('Woocommerce') )
        require_once ( 'sociaby-social-discounts-woo.php' );

/* ------- Jigoshop is not yet supported ------- */
//    if ( defined ( 'JIGOSHOP_VERSION' ) )
//        require_once ( 'sociaby-social-discounts-jigoshop.php' );

/* ------- SHOPP Support is in beta and has not been tested.  Uncomment and use this code at your own risk ------- */
//    if ( defined ( 'SHOPP_VERSION' ) )
//        require_once ( 'sociaby-social-discounts-shopp.php' );

}
add_action ( 'plugins_loaded', 'sociaby_plugins_loaded');




function sociaby_output_js($sb_id, $sociaby_products) {

	$code = '
			<script type="text/javascript" id="sociaby_' . $sb_id . '">
				var sociabyProductsObjectArray_' . $sb_id . ' = ' . json_encode($sociaby_products) . ';';

	$code .=<<<EOF
					(function() {
					function async_load(){
						var sprod = sociabyProductsObjectArray_$sb_id || 'na';
						var s = document.createElement('script'); s.type = 'text/javascript'; var tot = 0; s.async = true;
						s.src = '//sociaby.com/js/sociaby.js?cb=$sb_id&foo='+Math.random()*10000000000000000;
						if (typeof sprod == 'object') { if (sprod.prices.length && sprod.prices) {
							for (var i = 0; i < sprod.prices.length; i++) {tot = tot + sprod.prices[i];}s.src = s.src + '&amt=' + Math.round(tot*100)/100;}}
						var x = document.getElementsByTagName('script')[0];
						x.parentNode.insertBefore(s, x);}
					if (window.attachEvent) window.attachEvent('onload', async_load);
					else window.addEventListener('load', async_load, false);
				})();
			</script>
EOF;

	return $code;
}


/*------  ADMIN FUNCTIONS  ------*/

if ( is_admin() ){
	add_action('admin_menu', 'sociaby_admin_menu');
	add_action( 'admin_init', 'sociaby_register_settings' );
}

function sociaby_admin_options() { ?>
	<div class="wrap"> 
		<?php screen_icon(); ?>
		<h2>Sociaby Social Discounts</h2>
		<form method="post" action="options.php"> 
		
		<?php settings_fields( 'sociaby-options' ); ?>
		<?php do_settings_sections( 'sociaby-options' ); ?>
		<p>Enter your Sociaby Unique ID.  This can be found in your Sociaby account under the Configure menu.
		<table class="form-table">
			<tr valign="top">
			<th scope="row">Sociaby Vendor ID</th>
			<td><input type="text" name="sociaby-vendor-id" maxlength="16" value="<?php echo get_option('sociaby-vendor-id'); ?>" /></td>
			</tr>
		</table>
		
		<?php submit_button(); ?>

		</form>
	</div>
<?php 
}

function sociaby_admin_menu() {
	add_options_page( 'Sociaby Options', 'Sociaby Social Discounts', 'manage_options', 'sociaby-social-discounts', 'sociaby_admin_options' );
}

function sociaby_register_settings() {
	register_setting( 'sociaby-options', 'sociaby-vendor-id' );
}

function sociaby_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=sociaby-social-discounts">Settings</a>';
  	array_unshift( $links, $settings_link );
  	return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'sociaby_add_settings_link' );
?>