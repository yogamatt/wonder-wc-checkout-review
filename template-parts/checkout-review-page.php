<?php
/**
 * Wonder WC Checkout Review
 * slug: wonder-wc-checkout-review
 * @author Matt Ediger
 * @version 1.0
 */

// No direct access
defined( 'ABSPATH' ) or die( 'No direct access!' );
$colcount = 0; ?>

<div id="wc-review-page" class="woocommerce wwcr-review-page">

	<div class="wwcr-review-upper wwcr-review-row col-set">
		<?php if ( ! empty( wwcr_get_section_html( 'billing' ) ) ) : $colcount++; ?>
			<div class="col-<?php echo esc_attr( $colcount ); ?> wwcr-billing-details">
				<div class="col-inner">
					<?php echo wp_kses_post( wwcr_get_section_html( 'billing' ) ); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( wwcr_get_section_html( 'shipping' ) ) ) : $colcount++; ?>
			<div class="col-<?php echo esc_attr( $colcount ); ?> wwcr-shipping-details">
				<div class="col-inner">
					<?php echo wp_kses_post( wwcr_get_section_html( 'shipping' ) ); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( wwcr_get_section_html( 'order' ) ) ) : $colcount++; ?>
			<div class="col-<?php echo esc_attr( $colcount ); ?> wwcr-order-details">
				<div class="col-inner">
					<?php echo wp_kses_post( wwcr_get_section_html( 'order' ) ); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<div class="wwcr-review-lower wwcr-review-row">
		<?php woocommerce_order_review(); ?>
		<a href="<?php echo wc_get_checkout_url(); ?>" id="reset-checkout-link" class="reset-checkout-link"><?php echo __( 'Reset checkout.', 'woocommerce' ); ?></a>
	</div>

	<div class="wwcr-submit-row wwcr-review-row">
		<?php wwcr_output_submit_details(); ?>
	</div>
</div>