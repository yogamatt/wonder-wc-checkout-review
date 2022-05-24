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
 * WWCR_Public
 * 
 * This class is initated within class-wwcr.php.
 */
class WWCR_Public {

	/**
	 * Construct
	 */
	public function __construct() {
		global $wwcr;
		$wwcr = new WWCR();
	}


	/**
	 * Init
	 */
	public function init() {
		global $wwcr;

		if ( $wwcr->checkout_review_page_enabled() ) {
			// Public CSS
			$css_whereshow = $wwcr->public_css_whereshow();

			if ( 'header' === $css_whereshow ) {
				add_action( 'wp_head', array( $this, 'public_head_style' ) );
			} elseif ( 'enqueue' === $css_whereshow ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_style' ) );
			}		

			// Public JS
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ) );

			// WooCommerce actions
			add_filter( 'woocommerce_order_button_html', array( $this, 'review_order_button_html' ), 99999 );
		}
	}


	/**
	 * Enqueue Public Style
	 * 
	 * Enqueue public style
	 */
	public function enqueue_public_style() {
		global $wwcr;

		wp_enqueue_style( 'wwcr-public', $wwcr->get_plugin_url() . 'assets/css/wwcr-public.css', array(), '0.1', 'all' );
	}


	/**
	 * Enqueue Public Scripts
	 * 
	 * Enqueue public scripts.
	 */
	public function enqueue_public_scripts() {
		global $wwcr;

		$checkout_fields = array(
			'billing'		 => $wwcr->get_billing_section_format(),
			'order'			 => $wwcr->get_order_section_format(),
			'needs_shipping' => WC()->cart->needs_shipping_address()
		);

		if ( true === $checkout_fields['needs_shipping'] ) {
			$checkout_fields['shipping'] = $wwcr->get_shipping_section_format();
		}

		$varObj = array(
			'ajaxURL' 				   => admin_url( 'admin-ajax.php' ),
			'content_wrapper_selector' => $wwcr->get_content_wrapper_selector(),
			'checkout_fields' 		   => $checkout_fields
		);

		// WWCR Public Script
		wp_enqueue_script( 'wwcr-public', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/wwcr-public.js', array('jquery'), '', true );
		wp_add_inline_script( 'wwcr-public', 'const inlineObj = ' . json_encode( $varObj ), 'before' );
	}


	/**
	 * Public head style
	 */
	public function public_head_style() {
		?>
		<style>
			.clip-hide {
				position: absolute !important;
				left: -9999rem !important;
				height: 1px !important;
				width: 1px !important;
				clip: rect(1px, 1px, 1px, 1px) !important;
				overflow: hidden !important;
			}

			.wwcr-review-row:after {
				content: '';
				display: table;
				clear: both;
			}

			#wc-review-page > * {
				margin: 1rem 0;
			}

			#wc-review-page > *:first-child {
				margin-top: 0;
			}

			#wc-review-page > *:last-child {
				margin-bottom: 0;
			}

			#wc-review-page .col-set {
				width: auto;
			    display: -webkit-box;
			    display: -ms-flexbox;
			    display: flex;
			    -ms-flex-wrap: wrap;
			    flex-wrap: wrap;
			}

			#wc-review-page .col-set > * {
				margin: 0 0 2rem;
				width: 100%;
			}

			#wc-review-page h3 {
				margin-bottom: 0.5rem;
			}

			.return-checkout-link,
			.reset-checkout-link {
				display: block;
			}

			@media screen and (min-width: 768px) {
				#wc-review-page .col-set {
					margin-right: -1rem;
					margin-left: -1rem;
				}

				#wc-review-page .col-set > * {
					margin-right: 1rem;
					margin-left: 1rem;
					width: calc( 50% - 2rem );
				}

				form.woocommerce-checkout #payment button[type="submit"],
				#wc-review-page button[type="submit"] {
					margin: 0;
					float: right;
				}
			}
		</style>
		<?php
	}


	/**
	 * Review Order Button HTML
	 * 
	 * The markup for the order button. Sanitizes the title and replaces hyphens with underscores.
	 */
	public function review_order_button_html( $html ) {
		global $wwcr;

		$review_order_text = $wwcr->get_terminology( 'review_order_text' );

		$html = str_replace( 'place_order', esc_attr( str_replace( '-', '_', sanitize_title( $review_order_text ) ) ), $html );
		$html = str_replace( 'Place order', esc_html( $review_order_text ), $html );

		return $html;
	}


	/**
	 * Get section title
	 */
	public function get_section_title( $slug ) {
		global $wwcr;

		return call_user_func( array( $wwcr, 'get_' . $slug . '_section_title' ) );
	}

}