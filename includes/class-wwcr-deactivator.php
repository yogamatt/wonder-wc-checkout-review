<?php
/**
 * Wonder WC Checkout Review
 * slug: wonder-wc-checkout-review
 * @author Matt Ediger
 * @version 1.0
 */

/**
 * Fired during plugin deactivation
*/
class WWCR_Deactivator {

	public static function deactivate() {
		flush_rewrite_rules();
	}
}