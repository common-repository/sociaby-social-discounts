<?php
function sociaby_woo () {
	global $woocommerce;
	$sb_id = get_option('sociaby-vendor-id');
	$cart = $woocommerce->cart->cart_contents;
	if ( is_array( $cart ) && $woocommerce->cart->coupons_enabled() ) {
		if (!$sb_id) return false;
		else {
			foreach ($cart as $p) {
				$sociaby_products["items"][] = get_the_title( $p["product_id"] );
				$sociaby_products["urls"][] = esc_url( get_permalink( $p["product_id"] ) );
				$img = wp_get_attachment_image_src ( get_post_thumbnail_id ( $p["product_id"] ) );
				$sociaby_products["images"][] = $img[0];
				$sociaby_products["prices"][] = $p["line_total"];
			}
		}
	}
	echo sociaby_output_js($sb_id, $sociaby_products);
}
add_action('woocommerce_after_cart_table', 'sociaby_woo');
?>