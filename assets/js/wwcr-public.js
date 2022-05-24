/* global inlineObj */
(function($){
	/**
	 * Wonder WC Checkout Review
	 * slug: wonder-wc-checkout-review
	 * @author Matt Ediger
	 * @version 1.0
	 */

	// inlineObj is required to continue, ensure the object exists
	if ( typeof inlineObj === 'undefined' ) {
		return false;
	}

	var WonderWooCommerceCheckoutReview = {
		$content_wrapper: $( inlineObj.content_wrapper_selector ),
		$checkout_form: $( 'form.woocommerce-checkout' ),
		init: function(){
			
			/**
			 * Trigger handler to maybe interrupt checkout
			 * 
			 * Runs during wc_checkout_form.submit()
			 */
			this.$checkout_form.on( 'checkout_place_order', this.maybe_interrupt_checkout );

			// Ajax
			$( document.body ).on( 'ajax_loading', this.loading );
			$( document.body ).on( 'ajax_loaded', this.loaded );

			// Return/reset checkout form
			$( '.return-checkout-link' ).on( 'click', this.return_checkout );

			// Review page place order button
			$( '#wc-review-page #place_order' ).on( 'click', this.run_checkout );
		},
		maybe_interrupt_checkout: function(e){
			var $form = WonderWooCommerceCheckoutReview.$checkout_form;

			if ( ! $( '#wc-review-page' ).length ) {
				WonderWooCommerceCheckoutReview.show_order_review_page();
				return false;
			}
		},
		show_order_review_page: function(){
			var $content_wrapper = WonderWooCommerceCheckoutReview.$content_wrapper,
				inner_div = $content_wrapper.children( '.woocommerce' ),
				checkout_fields = inlineObj.checkout_fields;

			// Start ajax loading animations/blocks here
			$( document.body ).trigger( 'ajax_loading' );

			// Getting input/select values
			if ( checkout_fields ) {
				
				// Adding {split} and then splitting by it
				var billing_fields = checkout_fields['billing'].replace( /}/g, '}{split}' ).split( '{split}' ),
					order_fields = checkout_fields['order'].replace( /}/g, '}{split}' ).split( '{split}' ),
					values = { 'needs_shipping': checkout_fields['needs_shipping'] };

				// Billing fields
				if ( billing_fields.length ) {
					var billing_results = {};
					$.each( billing_fields, function(i){
						if (
							'{newline}' == billing_fields[i] ||
							'{space}' == billing_fields[i] ||
							'{comma}' == billing_fields[i] ||
							'{period}' == billing_fields[i]
						) {
							billing_results[i] = billing_fields[i];
						} else {
							var thisFieldName = billing_fields[i].replace( '{', '' ).replace( '}', '' );

							if ( thisFieldName.length ) {
								if ( thisFieldName.indexOf( '|' ) !== -1 ) {
									var fieldNames = thisFieldName.split( '|' );

									if ( fieldNames.length ) {
										$.each( fieldNames, function(k){
											var thisField = $( '#' + fieldNames[k] );

											if ( thisField.length && thisField.val() ) {
												billing_results[i] = thisField.val();
												return false; // break
											}
										});
									}
								} else {
									var thisField = $( '#' + thisFieldName );

									if ( thisField.length && thisField.val() ) {
										billing_results[i] = thisField.val();
									}
								}
							}
						}
					});
					values['billing'] = billing_results;
				}

				// Order fields
				if ( order_fields.length ) {
					var order_results = {};
					$.each( order_fields, function(i){
						if (
							'{newline}' == order_fields[i] ||
							'{space}' == order_fields[i] ||
							'{comma}' == order_fields[i] ||
							'{period}' == order_fields[i]
						) {
							order_results[i] = order_fields[i];
						} else {
							var thisFieldName = order_fields[i].replace( '{', '' ).replace( '}', '' );

							if ( thisFieldName.length ) {
								if ( thisFieldName.indexOf( '|' ) !== -1 ) {
									var fieldNames = thisFieldName.split( '|' );

									if ( fieldNames.length ) {
										$.each( fieldNames, function(k){
											var thisField = $( '#' + fieldNames[k] );

											if ( thisField.length && thisField.val() ) {
												order_results[i] = thisField.val();
												return false; // break
											}
										});
									}
								} else {
									var thisField = $( '#' + thisFieldName );

									if ( thisField.length && thisField.val() ) {
										order_results[i] = thisField.val();
									}
								}
							}
						}	
					});
					values['order'] = order_results;
				}

				// Show the shipping?
				var show_shipping = false,
					ship_to_diff = $( 'input#ship-to-different-address-checkbox' );

				if ( ship_to_diff.length ) {
					if ( ship_to_diff.is( ':checked' ) ) {
						show_shipping = true;
					}
				}

				// Shipping fields
				if ( checkout_fields['needs_shipping'] && true == checkout_fields['needs_shipping'] && show_shipping ) {

					var shipping_fields = checkout_fields['shipping'].replace( /}/g, '}{split}' ).split( '{split}' );

					if ( shipping_fields.length ) {
						var shipping_results = {};
						$.each( shipping_fields, function(i){
							if (
								'{newline}' == shipping_fields[i] ||
								'{space}' == shipping_fields[i] ||
								'{comma}' == shipping_fields[i] ||
								'{period}' == shipping_fields[i]
							) {
								shipping_results[i] = shipping_fields[i];
							} else {
								var thisFieldName = shipping_fields[i].replace( '{', '' ).replace( '}', '' );

								if ( thisFieldName.length ) {
									if ( thisFieldName.indexOf( '|' ) !== -1 ) {
										var fieldNames = thisFieldName.split( '|' );

										if ( fieldNames.length ) {
											$.each( fieldNames, function(k){
												var thisField = $( '#' + fieldNames[k] );

												if ( thisField.length && thisField.val() ) {
													shipping_results[i] = thisField.val();
													return false; // break
												}
											});
										}
									} else {
										var thisField = $( '#' + thisFieldName );

										if ( thisField.length && thisField.val() ) {
											shipping_results[i] = thisField.val();
										}
									}
								}
							}
						});
						values['shipping'] = shipping_results;
					}
				}
			}
			
			$.ajax({
				type: 'post',
				url: inlineObj.ajaxURL,
				data: {
					'action' : 'wwcr_show_review_order_page_ajax',
					'values' : values
				},
				dataType: 'json',
				beforeSend: function(response){
				},
				complete: function(response){
					$( document.body ).trigger( 'ajax_loaded' );
				},
				success: function(response){
					if ( ! $( '#wc-review-page' ).length ) {
						// Scroll to top of review page
						$( window ).scrollTop( $content_wrapper.offset().top );
						$content_wrapper.prepend( response.html );
						WonderWooCommerceCheckoutReview.$checkout_form.addClass( 'checkout-is-reviewed' );
					}
					
					inner_div.addClass( 'clip-hide' );
				}
			});
		},
		loading: function(){
			$( document.body ).addClass( 'ajaxing' );
			WonderWooCommerceCheckoutReview.$content_wrapper.block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				} 
			});
		},
		loaded: function(){
			$( document.body ).removeClass( 'ajaxing' );
			WonderWooCommerceCheckoutReview.$content_wrapper.unblock();
		},
		return_checkout: function(e){
			var $content_wrapper = WonderWooCommerceCheckoutReview.$content_wrapper,
				inner_div = $content_wrapper.children( '.woocommerce' );

			e.preventDefault();

			$content_wrapper.find( '#wc-review-page' ).remove();
			inner_div.removeClass( 'clip-hide' );

			WonderWooCommerceCheckoutReview.$checkout_form.removeClass( 'checkout-is-reviewed' );
		},
		run_checkout: function(){
			WonderWooCommerceCheckoutReview.$checkout_form.submit();
		}	
	};
	WonderWooCommerceCheckoutReview.init();
	$( document ).ajaxComplete(function(){
		WonderWooCommerceCheckoutReview.init();
	});

})(jQuery)