<?php

function sociaby_wpec () {
	if(wpsc_uses_coupons()) {
		$sb_id = get_option('sociaby-vendor-id');
		if (!$sb_id) return false;
		else {
			while (wpsc_have_cart_items()) : wpsc_the_cart_item();
				$sociaby_products["items"][] = wpsc_cart_item_name();
				$sociaby_products["urls"][] = esc_url( wpsc_cart_item_url() );
				$sociaby_products["images"][] = wpsc_cart_item_image();
				$sociaby_products["prices"][] = wpsc_cart_item_price(false);
			endwhile;
			echo sociaby_output_js($sb_id, $sociaby_products);
		}
	}
}
add_action('wpsc_bottom_of_shopping_cart', 'sociaby_wpec');
?>