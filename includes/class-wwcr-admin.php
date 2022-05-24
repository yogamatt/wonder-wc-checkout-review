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
 * WWCR_Admin
 * 
 * This class is initated within class-wwcr.php.
 */
class WWCR_Admin {

	/**
	 * Construct
	 */
	public function __construct( $plugin_name, $version ) {
		global $wwcr;

		$wwcr = new WWCR();
	}


	/**
	 * Init
	 */
	public function init() {

		// WWCR Admin Menu
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		// WWCR Admin Page
		add_action( 'admin_head', array( $this, 'add_admin_head_css' ) );
		add_action( 'wwcr_admin_before_main_content', array( $this, 'admin_process_options' ) );
		add_action( 'wwcr_admin_main_content', array( $this, 'admin_options_content' ) );
	}


	/**
	 * Add Admin Menu
	 * 
	 * Add the menu as a subitem of the main WooCommerce admin link.
	 */
	public function add_admin_menu() {
		add_submenu_page(
			'woocommerce',
			'Checkout review page',
			'Checkout review',
			'manage_options',
			'wwcr_admin_menu',
			array( $this, 'show_wwcr_admin_page' ),
			9
		);
	}


	/**
	 * Show WWCR Page
	 * 
	 * Show the WWCR admin page.
	 */
	public function show_wwcr_admin_page() {
		do_action( 'wwcr_admin_before_main_content' );
		?>
		<div class="wrap about-wrap">
			<h1><?php echo get_admin_page_title(); ?></h1>
			<?php do_action( 'wwcr_admin_main_content' ); ?>
		</div><!-- .wrap -->
		<?php
		do_action( 'wwcr_admin_after_main_content' );
	}


	/**
	 * Add Admin Head CSS
	 * 
	 * Add css in the admin header.
	 */
	public function add_admin_head_css() {
		?>
		<style>
			.has-small-font-size,
			p.has-small-font-size {
				font-size: 0.775rem;
			}
			pre.has-small-font-size {
				margin: 0.5rem 0;
				padding: 0.25rem;
				background: rgba(0,0,0,0.125);
				max-width: 50rem;
				overflow: auto;
				opacity: 0.75;
				transition: all 0.2s ease-out;
			}
			pre.has-small-font-size:hover {
				opacity: 1;
			}
			a.reset {
				display: block;
			}
			th .field-description {
				font-weight: 300;
				font-size: 0.7rem;
				color: #999;
			}
			table.form-table + hr {
				margin: 3rem 0;
			}
			table.form-table + hr + h2 {
				margin-top: 0;
			}
			.about-wrap h2 {
				display: inline-block;
				border-bottom: 3px solid #a9a9a9;
				font-weight: normal;
				font-size: 1.8em;
				text-align: left;
			}
			.wwcr-settings td input,
			.wwcr-settings td select {
				width: 100%;
				max-width: 16rem;
			}
		</style>
		<?php
	}


	/**
	 * Admin Process Options
	 * 
	 * Process the plugin options.
	 */
	public function admin_process_options() {
		// Enable checkout review page
		if ( isset( $_POST['wwcr_enable_checkout_review_page'] ) ) {
			if ( get_option( 'wwcr_enable_checkout_review_page' ) !== sanitize_text_field( $_POST['wwcr_enable_checkout_review_page'] ) ) {
				update_option( 'wwcr_enable_checkout_review_page', sanitize_text_field( $_POST['wwcr_enable_checkout_review_page'] ) );
			}
		}

		// Public CSS Whereshow
		if ( isset( $_POST['wwcr_public_css_whereshow'] ) ) {
			if ( get_option( 'wwcr_public_css_whereshow' ) !== sanitize_text_field( $_POST['wwcr_public_css_whereshow'] ) ) {
				update_option( 'wwcr_public_css_whereshow', sanitize_text_field( $_POST['wwcr_public_css_whereshow'] ) );
			}
		}

		// Content wrapper selector
		if ( isset( $_POST['wwcr_content_wrapper_selector'] ) ) {
			if ( get_option( 'wwcr_content_wrapper_selector' ) !== sanitize_text_field( $_POST['wwcr_content_wrapper_selector'] ) ) {
				update_option( 'wwcr_content_wrapper_selector', sanitize_text_field( $_POST['wwcr_content_wrapper_selector'] ) );
			}
		}

		// Review order button text
		if ( isset( $_POST['wwcr_terminology_review_order_text'] ) ) {
			if ( get_option( 'wwcr_terminology_review_order_text' ) !== sanitize_text_field( $_POST['wwcr_terminology_review_order_text'] ) ) {
				update_option( 'wwcr_terminology_review_order_text', sanitize_text_field( $_POST['wwcr_terminology_review_order_text'] ) );
			}
		}

		// Place order button text
		if ( isset( $_POST['wwcr_terminology_place_order_text'] ) ) {
			if ( get_option( 'wwcr_terminology_place_order_text' ) !== sanitize_text_field( $_POST['wwcr_terminology_place_order_text'] ) ) {
				update_option( 'wwcr_terminology_place_order_text', sanitize_text_field( $_POST['wwcr_terminology_place_order_text'] ) );
			}
		}

		// Billing section title
		if ( isset( $_POST['wwcr_billing_section_title'] ) ) {
			if ( get_option( 'wwcr_billing_section_title' ) !== sanitize_text_field( $_POST['wwcr_billing_section_title'] ) ) {
				update_option( 'wwcr_billing_section_title', sanitize_text_field( $_POST['wwcr_billing_section_title'] ) );
			}
		}

		// Billing section format
		if ( isset( $_POST['wwcr_billing_section_format'] ) ) {
			update_option( 'wwcr_billing_section_format', sanitize_textarea_field( str_replace( "\r\n", "{newline}", str_replace( " ", "{space}", str_replace( ",", "{comma}", str_replace( ".", "{period}", $_POST['wwcr_billing_section_format'] ) ) ) ) ) );
		}

		// Shipping section title
		if ( isset( $_POST['wwcr_shipping_section_title'] ) ) {
			if ( get_option( 'wwcr_shipping_section_title' ) !== sanitize_text_field( $_POST['wwcr_shipping_section_title'] ) ) {
				update_option( 'wwcr_shipping_section_title', sanitize_text_field( $_POST['wwcr_shipping_section_title'] ) );
			}
		}

		// Shipping post section format
		if ( isset( $_POST['wwcr_shipping_section_format'] ) ) {
			update_option( 'wwcr_shipping_section_format', sanitize_textarea_field( str_replace( "\r\n", "{newline}", str_replace( " ", "{space}", str_replace( ",", "{comma}", str_replace( ".", "{period}", $_POST['wwcr_shipping_section_format'] ) ) ) ) ) );
		}

		// Order section title
		if ( isset( $_POST['wwcr_order_section_title'] ) ) {
			if ( get_option( 'wwcr_order_section_title' ) !== sanitize_text_field( $_POST['wwcr_order_section_title'] ) ) {
				update_option( 'wwcr_order_section_title', sanitize_text_field( $_POST['wwcr_order_section_title'] ) );
			}
		}

		// Order post section format
		if ( isset( $_POST['wwcr_order_section_format'] ) ) {
			update_option( 'wwcr_order_section_format', sanitize_textarea_field( str_replace( "\r\n", "{newline}", str_replace( " ", "{space}", str_replace( ",", "{comma}", str_replace( ".", "{period}", $_POST['wwcr_order_section_format'] ) ) ) ) ) );
		}
	}


	/** 
	 * Admin Main Content
	 * 
	 * Main content for admin area.
	 */
	public function admin_options_content() {
		global $wwcr;
		?> 
		<form class="options-form wwcr-settings" method="post">
			<h2><?php echo __( 'General options', 'woocommerce' ); ?></h2>
			<table class="form-table">
				<tr>
					<th align="left" valign="top">
						<label for="wwcr_enable_checkout_review_page"><?php echo __( 'Enable checkout review page?', 'woocommerce' ); ?></label>
					</th>
					<td>
						<select name="wwcr_enable_checkout_review_page" id="wwcr_enable_checkout_review_page">
							<option value="1" <?php selected( '1', get_option( 'wwcr_enable_checkout_review_page', '' ) ); ?>><?php echo __( 'Yes', 'woocommerce' ); ?></option>
							<option value="0" <?php selected( '0', get_option( 'wwcr_enable_checkout_review_page', '' ) ); ?>><?php echo __( 'No', 'woocommerce' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th align="left" valign="top">
						<label for="wwcr_public_css_whereshow"><?php echo __( 'Where should the public css be outputted?', 'woocommerce' ); ?></label>
					</th>
					<td>
						<select name="wwcr_public_css_whereshow" id="wwcr_public_css_whereshow">
							<option value="header" <?php selected( 'header', get_option( 'wwcr_public_css_whereshow', '' ) ); ?>><?php echo __( 'Header', 'woocommerce' ); ?></option>
							<option value="enqueue" <?php selected( 'enqueue', get_option( 'wwcr_public_css_whereshow', '' ) ); ?>><?php echo __( 'Enqueue', 'woocommerce' ); ?></option>
							<option value="none" <?php selected( 'none', get_option( 'wwcr_public_css_whereshow', '' ) ); ?>><?php echo __( 'None', 'woocommerce' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th align="left" valign="top">
						<label for="wwcr_content_wrapper_selector"><?php echo __( 'Content Wrapper Selector', 'woocommerce' ); ?></label>
						<p class="field-description"><?php echo __( 'This is the target element used to display the AJAX response. Adjust to your theme\'s html structure or leave alone.', 'woocommerce' ); ?>
					</th>
					<td>
						<input type="text" name="wwcr_content_wrapper_selector" id="wwcr_content_wrapper_selector" value="<?php echo ! empty( get_option( 'wwcr_content_wrapper_selector', '' ) ) ? wp_kses_post( get_option( 'wwcr_content_wrapper_selector', '' ) ) : 'article > .entry-content'; ?>" />
					</td>
				</tr>
				<tr>
					<th align="left" valign="top">
						<label for="wwcr_terminology_review_order_text"><?php echo __( '"Review order" button text', 'woocommerce' ); ?></label>
					</th>
					<td>
						<input type="text" name="wwcr_terminology_review_order_text" id="wwcr_terminology_review_order_text" value="<?php echo ! empty( get_option( 'wwcr_terminology_review_order_text', '' ) ) ? esc_attr( get_option( 'wwcr_terminology_review_order_text', '' ) ) : __( 'Review order', 'woocommerce' ); ?>" />
					</td>
				</tr>
				<tr>
					<th align="left" valign="top">
						<label for="wwcr_terminology_place_order_text"><?php echo __( '"Place order" button text', 'woocommerce' ); ?></label>
					</th>
					<td>
						<input type="text" name="wwcr_terminology_place_order_text" id="wwcr_terminology_place_order_text" value="<?php echo ! empty( get_option( 'wwcr_terminology_place_order_text', '' ) ) ? esc_attr( get_option( 'wwcr_terminology_place_order_text', '' ) ) : __( 'Place order', 'woocommerce' ); ?>" />
					</td>
				</tr>
			</table>
			<hr />
			<?php $checkout_fields = WC()->checkout->checkout_fields; ?>
			<h2><?php echo __( 'Billing section', 'woocommerce' ); ?></h2>
			<table class="form-table">
				<tr>
					<th align="left" valign="top">
						<label for="wwcr_billing_section_title"><?php echo __( 'Billing section title', 'woocommerce' ); ?></label>
					</th>
					<td>
						<input type="text" name="wwcr_billing_section_title" id="wwcr_billing_section_title" value="<?php echo ! empty( get_option( 'wwcr_billing_section_title', '' ) ) ? esc_attr( get_option( 'wwcr_billing_section_title', '' ) ) : __( 'Billing details', 'woocommerce' ); ?>" />
					</td>
				</tr>
				<tr>
					<th align="left" valign="top">
						<label for="wwcr_billing_section_format"><?php echo __( 'Billing section format', 'woocommerce' ); ?></label>
					</th>
					<td>
						<textarea name="wwcr_billing_section_format" id="wwcr_billing_section_format" rows="10" cols="100"><?php
							$billing = $checkout_fields['billing'];
							$html = '';
							$c = 0;
							foreach ( $billing as $key => $billing_field ) {
								$c++;

								$html .= '{' . $key . '}';

								if ( strpos( $key, 'city' ) !== false ) {
									$html .= ', ';
								} elseif ( $c < count( $billing ) ) {
									$html .= '\r\n';
								}
							}

							if ( ! empty( $wwcr->get_billing_section_format() ) ) {
								echo wp_kses_post( str_replace( "{newline}", "\r\n", str_replace( "{space}", " ", str_replace( "{comma}", ",", str_replace( "{period}", ".", $wwcr->get_billing_section_format() ) ) ) ) );
							} else {
								echo wp_kses_post( str_replace( '\r\n', "\r\n", $html ) );
							}
						?></textarea>
						<?php echo '<pre class="has-small-font-size">'; var_dump( $wwcr->get_billing_section_format() ); echo '</pre>'; ?>
						<a href="#" id="reset-wwcr_billing_section_format" class="reset"><?php echo __( 'Reset', 'woocommerce' ); ?>
						<script>
							(function($){
								$( '#reset-wwcr_billing_section_format' ).click(function(e){
									e.preventDefault();
									$( '#wwcr_billing_section_format' ).val( '' ).val( "<?php echo wp_kses_post( $html ); ?>" );
								});
							})(jQuery);
						</script>
					</td>
				</tr>
			</table>
			<hr />

			<h2><?php echo __( 'Shipping section', 'woocommerce' ); ?></h2>
			<table class="form-table">
				<tr>
					<th align="left" valign="top">
						<label for="wwcr_shipping_section_title"><?php echo __( 'Shipping section title', 'woocommerce' ); ?></label>
					</th>
					<td>
						<input type="text" name="wwcr_shipping_section_title" id="wwcr_shipping_section_title" value="<?php echo ! empty( get_option( 'wwcr_shipping_section_title', '' ) ) ? esc_attr( get_option( 'wwcr_shipping_section_title', '' ) ) : __( 'Shipping details', 'woocommerce' ); ?>" />
					</td>
				</tr>
				<tr>
					<th align="left" valign="top">
						<label for="wwcr_shipping_section_format"><?php echo __( 'Shipping section format', 'woocommerce' ); ?></label>
					</th>
					<td>
						<textarea name="wwcr_shipping_section_format" id="wwcr_shipping_section_format" rows="10" cols="100"><?php
							$shipping = $checkout_fields['shipping'];
							$html = '';
							$c = 0;
							foreach ( $shipping as $key => $shipping_field ) {
								$c++;

								$html .= '{' . $key . '}';

								if ( strpos( $key, 'city' ) !== false ) {
									$html .= ', ';
								} elseif ( $c < count( $shipping ) ) {
									$html .= '\r\n';
								}
							}

							if ( ! empty( $wwcr->get_shipping_section_format() ) ) {
								echo wp_kses_post( str_replace( "{newline}", "\r\n", str_replace( "{space}", " ", str_replace( "{comma}", ",", str_replace( "{period}", ".", $wwcr->get_shipping_section_format() ) ) ) ) );
							} else {
								echo wp_kses_post( str_replace( '\r\n', "\r\n", $html ) );
							}
						?></textarea>
						<?php echo '<pre class="has-small-font-size">'; var_dump( $wwcr->get_shipping_section_format() ); echo '</pre>'; ?>
						<a href="#" id="reset-wwcr_shipping_section_format" class="reset"><?php echo __( 'Reset', 'woocommerce' ); ?>
						<script>
							(function($){
								$( '#reset-wwcr_shipping_section_format' ).click(function(e){
									e.preventDefault();
									$( '#wwcr_shipping_section_format' ).val( '' ).val( "<?php echo wp_kses_post( $html ); ?>" );
								});
							})(jQuery);
						</script>
					</td>
				</tr>
			</table>
			<hr />

			<h2><?php echo __( 'Additional information section', 'woocommerce' ); ?></h2>
			<table class="form-table">
				<tr>
					<th align="left" valign="top">
						<label for="wwcr_order_section_title"><?php echo __( 'Additional information section title', 'woocommerce' ); ?></label>
					</th>
					<td>
						<input type="text" name="wwcr_order_section_title" id="wwcr_order_section_title" value="<?php echo ! empty( get_option( 'wwcr_order_section_title', '' ) ) ? esc_attr( get_option( 'wwcr_order_section_title', '' ) ) : __( 'Additional information', 'woocommerce' ); ?>" />
					</td>
				</tr>
				<tr>
					<th align="left" valign="top">
						<label for="wwcr_order_section_format"><?php echo __( 'Additional information section format', 'woocommerce' ); ?></label>
					</th>
					<td>
						<textarea name="wwcr_order_section_format" id="wwcr_order_section_format" rows="10" cols="100"><?php
							$order = $checkout_fields['order'];
							$html = '';
							$c = 0;
							foreach ( $order as $key => $order_field ) {
								$c++;

								$html .= '{' . $key . '}';

								if ( strpos( $key, 'city' ) !== false ) {
									$html .= ', ';
								} elseif ( $c < count( $order ) ) {
									$html .= '\r\n';
								}
							}

							if ( ! empty( $wwcr->get_order_section_format() ) ) {
								echo wp_kses_post( str_replace( "{newline}", "\r\n", str_replace( "{space}", " ", str_replace( "{comma}", ",", str_replace( "{period}", ".", $wwcr->get_order_section_format() ) ) ) ) );
							} else {
								echo wp_kses_post( str_replace( '\r\n', "\r\n", $html ) );
							}
						?></textarea>
						<?php echo '<pre class="has-small-font-size">'; var_dump( $wwcr->get_order_section_format() ); echo '</pre>'; ?>
						<a href="#" id="reset-wwcr_order_section_format" class="reset"><?php echo __( 'Reset', 'woocommerce' ); ?>
						<script>
							(function($){
								$( '#reset-wwcr_order_section_format' ).click(function(e){
									e.preventDefault();
									$( '#wwcr_order_section_format' ).val( '' ).val( "<?php echo wp_kses_post( $html ); ?>" );
								});
							})(jQuery);
						</script>
					</td>
				</tr>
			</table>
			<div class="submit-row">
				<input type="submit" class="button" value="Submit" />
			</div>
		</form>
		<?php 
	}
}