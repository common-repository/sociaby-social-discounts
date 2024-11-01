<?php

function sociaby_shopp ( $content ) {
	$sb_id = get_option('sociaby-vendor-id');
	if (!$sb_id) return false;
	else {
		if (is_cart_page() && shopp('cart','promos-available')) {
			$items = shopp_cart_items();
			foreach ( $items as $item ) {
				$sociaby_products["items"][] = shopp($item,'name');
				$sociaby_products["urls"][] = shopp($item,'url');
				$sociaby_products["images"][] = shopp($item,'coverimage');
				$sociaby_products["prices"][] = shopp($item,'total','currency=off');
			}
			$script = sociaby_output_js($sb_id, $sociaby_products);
			return $content . $script;
		}
	}
}

add_filter('shopp_cart_template', 'sociaby_shopp');

?>