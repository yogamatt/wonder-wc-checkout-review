<?php
/**
 * Wonder WC Checkout Review
 * slug: wonder-wc-checkout-review
 * @author Matt Ediger
 * @version 1.0
 */

// No direct access
defined( 'ABSPATH' ) or die( 'No direct access!' );


/**
 * Show order review page
 * Ajax
 */
add_action( 'wp_ajax_wwcr_show_review_order_page_ajax', 'wwcr_show_review_order_page_ajax_callback' );
add_action( 'wp_ajax_nopriv_wwcr_show_review_order_page_ajax', 'wwcr_show_review_order_page_ajax_callback' );
function wwcr_show_review_order_page_ajax_callback() {
	// Exposes public to template
	global $wwcr_public;
	$wwcr_public = new WWCR_Public();

	ob_start();
	include WWCR_PATH . 'template-parts/checkout-review-page.php';
	$review_html = ob_get_clean();

	echo json_encode( array(
		'html' => $review_html
	));
	wp_die();
}


/**
 * Get section details html
 * 
 * Notes: Uses AJAX post request $_POST['values']
 */
function wwcr_get_section_html( $slug ) {
	global $wwcr_public;

	$html 	= '';

	if ( ! isset( $_POST['values'] ) ) {
		return $html;
	}

	$values = $_POST['values']; // Array is escaped below

	$details = ! empty( $values[ $slug ] ) ? $values[ $slug ] : '';

	if ( ! empty( $details ) ) {
		$title = $wwcr_public->get_section_title( $slug );
		$html .= '<h3>' . esc_html( $title ) . '</h3>';
		
		foreach ( $details as $key => $detail ) {
			$html .= str_replace( "{newline}", "<br />", esc_html( $detail ) );
			$html = str_replace( "{space}", " ", $html );
			$html = str_replace( "{comma}", ",", $html );
			$html = str_replace( "{period}", ".", $html );
		}

		$html .= wwcr_get_return_checkout_link( $title );

		// Remove double spaces
		$html = str_replace( "<br /><br />", "<br />", $html );
	}

	return $html;
}


/**
 * Get return checkout link
 */
function wwcr_get_return_checkout_link( $title ) {
	return '<a href="#" class="wc-return-to-checkout return-checkout-link" data-return="' . esc_attr( sanitize_title( $title ) ). '">' . __( 'Back to ' . esc_html( strtolower( $title ) ), 'woocommerce' ) . '</a>';
}


/**
 * Output submit details
 */
function wwcr_output_submit_details() {
	$wwcr = new WWCR();
	$text = ! empty( $wwcr ) ? $wwcr->get_terminology( 'place_order_text' ) : '';

	echo '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" data-value="' . esc_attr( $text ) . '">' . esc_html( __( $text, 'woocommerce' ) ) . '</button>';
}