<?php
/**
 * Wonder WooCommerce Checkout Review
 * slug: wonder-woocommerce-checkout-review
 * @author Matt Ediger
 * @version 1.0
 */

// No direct access
defined( 'ABSPATH' ) or die( 'No direct access!' );


/**
 * WWCR
 * 
 * This class is initiated within wonder-woocommerce-checkout-review.php.
 */
class WWCR {

	protected $plugin_name;
	protected $version;

	/**
	 * Construct
	 */
	public function __construct() {
		if ( defined( 'WWCR_VERSION' ) ) {
			$this->version = WWCR_VERSION;
		} else {
			$this->version = '1.0';
		}
		$this->plugin_name = 'wonder-wc-checkout-review';
	}


	/**
	 * Init
	 */
	public function init() {
		if ( is_admin() ) {
			$this->load_dependencies( true );
			$this->add_admin_hooks();
		} else {
			$this->load_dependencies();
			$this->add_public_hooks();
		}
	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 */
	private function load_dependencies( $admin = false ) {
		if ( true === $admin ) {
			require_once $this->get_plugin_path() . 'includes/class-wwcr-admin.php';
		}

		require_once $this->get_plugin_path() . 'includes/class-wwcr-public.php';
		require_once $this->get_plugin_path() . 'includes/wwcr-functions.php';
	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 */
	private function add_admin_hooks() {
		$admin = new WWCR_Admin( $this->get_plugin_name(), $this->get_version() );
		$admin->init();
	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 */
	private function add_public_hooks() {
		$public = $this->get_wwcr_public();
		$public->init();
	}


	/**
	 * Enable checkout review page
	 * 
	 * 
	 * Is the checkout review page enabled?
	 */
	public function checkout_review_page_enabled() {
		return ! empty( get_option( 'wwcr_enable_checkout_review_page', '' ) )  ? get_option( 'wwcr_enable_checkout_review_page', '' ) : 0;
	}


	/**
	 * Where to show the public css
	 */
	public function public_css_whereshow() {
		return ! empty( get_option( 'wwcr_public_css_whereshow', '' ) )  ? get_option( 'wwcr_public_css_whereshow', '' ) : '';
	}


	/**
	 * Content wrapper selector
	 */
	public function get_content_wrapper_selector() {
		return ! empty( get_option( 'wwcr_content_wrapper_selector', '' ) )  ? get_option( 'wwcr_content_wrapper_selector', '' ) : '';
	}


	/**
	 * Get billing section title
	 */
	public function get_billing_section_title() {
		return ! empty( get_option( 'wwcr_billing_section_title', '' ) ) ?get_option( 'wwcr_billing_section_title', '' ) : '';
	}


	/**
	 * Get billing section format
	 */
	public function get_billing_section_format() {
		return ! empty( get_option( 'wwcr_billing_section_format', '' ) ) ? get_option( 'wwcr_billing_section_format', '' ) : '';
	}


	/**
	 * Get shipping section title
	 */
	public function get_shipping_section_title() {
		return ! empty( get_option( 'wwcr_shipping_section_title', '' ) ) ? get_option( 'wwcr_shipping_section_title', '' ) : '';
	}


	/**
	 * Get shipping section format
	 */
	public function get_shipping_section_format() {
		return ! empty( get_option( 'wwcr_shipping_section_format', '' ) ) ? get_option( 'wwcr_shipping_section_format', '' ) : '';
	}


	/**
	 * Get order section title
	 */
	public function get_order_section_title() {
		return ! empty( get_option( 'wwcr_order_section_title', '' ) ) ? get_option( 'wwcr_order_section_title', '' ) : '';
	}


	/**
	 * Get order section format
	 */
	public function get_order_section_format() {
		return ! empty( get_option( 'wwcr_order_section_format', '' ) ) ? get_option( 'wwcr_order_section_format', '' ) : '';
	}


	/**
	 * Get Terminology
	 * 
	 * Get translated text.
	 */
	public function get_terminology( $slug ) {
		switch( $slug ) {
			case 'review_order_text':
				$return = ! empty( get_option( 'wwcr_terminology_review_order_text', '' ) ) ? get_option( 'wwcr_terminology_review_order_text', '' ) : __( 'Review order', 'woocommerce' );
				break;
			case 'place_order_text':
				$return = ! empty( get_option( 'wwcr_terminology_place_order_text', '' ) ) ? get_option( 'wwcr_terminology_place_order_text', '' ) : __( 'Place order', 'woocommerce' );
				break;
			default:
				$return = '';
		}

		return $return;
	}


	/**
	 * Get WWCR Public
	 */
	public function get_wwcr_public() {
		require_once $this->get_plugin_path() . 'includes/class-wwcr-public.php';
		return new WWCR_Public();
	}
	

	public function get_plugin_path() {
		return WWCR_PATH;
	}

	public function get_plugin_url() {
		return WWCR_URL;
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_version() {
		return $this->version;
	}
}