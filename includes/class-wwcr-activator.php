<?php
/**
 * Wonder WC Checkout Review
 * slug: wonder-wc-checkout-review
 * @author Matt Ediger
 * @version 1.0
 */

/**
 * Fired during plugin activation
*/
class WWCR_Activator {

	public static function activate() {
		flush_rewrite_rules();
	}
}
