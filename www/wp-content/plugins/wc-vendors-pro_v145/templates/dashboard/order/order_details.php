<?php
/**
 * The template for displaying the order details
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/order
 *
 * @package    WCVendors_Pro
 * @version    1.4.0
 */
?>

<div class="wcv-shade wcv-fade">
	
	<div id="order-details-modal-<?php echo $order_id; ?>" class="wcv-modal wcv-fade" data-trigger="#open-order-details-modal-<?php echo $order_id; ?>" data-width="80%" data-height="90%"  data-reveal aria-labelledby="modalTitle-<?php echo $order_id; ?>" aria-hidden="true" role="dialog">

			<div class="modal-header">
		            <button class="modal-close wcv-dismiss"></button>
		            <h3 id="modal-title"><?php echo sprintf( __( 'Order #%d Details', 'wcvendors-pro'), $order->get_order_number() ); ?> - <?php echo date_i18n( wc_date_format(), strtotime( $order_date ) );  ?></h3>
		    </div>

		    <div class="modal-body wcv-order-details" id="modalContent">

				    <div class="wcv-order-customer-details wcv-cols-group wcv-horizontal-gutters">

				    	<div class="all-50">	
				    		<h4><?php _e( 'Billing Details', 'wcvendors-pro' ); ?></h4>
							<?php
								// Display values
								echo '<div class="wcv-order-address">';

									if ( $order->get_formatted_billing_address() ) {
										echo '<p><strong>' . __( 'Address', 'wcvendors-pro' ) . ':</strong>' . wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) ) . '</p>';
									} else {
										echo '<p class="none_set"><strong>' . __( 'Address', 'wcvendors-pro' ) . ':</strong> ' . __( 'No billing address set.', 'wcvendors-pro' ) . '</p>';
									}

									foreach ( $billing_fields as $key => $field ) {
										if ( isset( $field['show'] ) && false === $field['show'] ) {
											continue;
										}

										$field_name = 'billing_' . $key;
									}

								echo '</div>';
						?>
						</div>  <!-- // billing details  -->

						<div class="all-50">
							<h4><?php _e( 'Shipping Details', 'wcvendors-pro' ); ?></h4>
							<?php
								// Display values
								echo '<div class="wcv-order-address">';

									if ( $order->get_formatted_shipping_address() ) {
										echo '<p><strong>' . __( 'Address', 'wcvendors-pro' ) . ':</strong>' . wp_kses( $order->get_formatted_shipping_address(), array( 'br' => array() ) ) . '</p>';
									} else {
										echo '<p class="none_set"><strong>' . __( 'Address', 'wcvendors-pro' ) . ':</strong> ' . __( 'No shipping address set.', 'wcvendors-pro' ) . '</p>';
									}

									if ( ! empty( $shipping_fields ) ) {
										foreach ( $shipping_fields as $key => $field ) {
											if ( isset( $field['show'] ) && false === $field['show'] ) {
												continue;
											}

											$field_name = 'shipping_' . $key;

											if ( ! empty( $order->$field_name ) ) {
												echo '<p><strong>' . esc_html( $field['label'] ) . ':</strong> ' . make_clickable( esc_html( $order->$field_name ) ) . '</p>';
											}
										}
									}

								echo '</div>';
							?>
						</div> <!-- //shipping details  -->

				    </div>	

		    		<hr />

					<div class=" wcv-order-customer-details wcv-cols-group wcv-horizontal-gutters">

						<div class="all-100">

					    	<h4><?php _e( 'Order Items', 'wcvendors-pro'); ?></h4>

					    	<table cellpadding="0" cellspacing="0" class="wcv-table wcv-order-table">
								<thead>
									<tr>
										<th colspan="2"><?php _e( 'Item', 'wcvendors-pro' ); ?></th>
										<th><?php _e( 'Commission', 'wcvendors-pro' ); ?></th>
										<th><?php _e( 'Cost', 'wcvendors-pro' ); ?></th>
										<th><?php _e( 'Qty', 'wcvendors-pro' ); ?></th>
										<th><?php _e( 'Total', 'wcvendors-pro' ); ?></th>

										<?php
										if ( ! empty( $order_taxes ) ) :
											foreach ( $order_taxes as $tax_id => $tax_item ) :
												$tax_class      = wc_get_tax_class_by_tax_id( $tax_item['rate_id'] );
												$tax_class_name = isset( $classes_options[ $tax_class ] ) ? $classes_options[ $tax_class ] : __( 'Tax', 'wcvendors-pro' );
												$column_label   = ! empty( $tax_item['label'] ) ? $tax_item['label'] : __( 'Tax', 'wcvendors-pro' );
												?>
													<th class="line_tax tips" data-tip="<?php
															echo esc_attr( $tax_item['name'] . ' (' . $tax_class_name . ')' );
														?>">
														<?php echo esc_attr( $column_label ); ?>
														<input type="hidden" class="order-tax-id" name="order_taxes[<?php echo $tax_id; ?>]" value="<?php echo esc_attr( $tax_item['rate_id'] ); ?>">
														<a class="delete-order-tax" href="#" data-rate_id="<?php echo $tax_id; ?>"></a>
													</th>
												<?php
											endforeach;
										endif;
									?>
									</tr>
								</thead>

									<tbody id="order_line_items">
									<?php
										foreach ( $line_items as $item_id => $item ) {

											$product_id 		= !empty( $item['variation_id'] ) ? $item['variation_id'] : $item['product_id'];
											$_product  			= $order->get_product_from_item( $item );
											$item_qty 			= ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $item->qty : $item->get_quantity(); 
											$product_commision  = ( $item_qty > 1 ) ? $_order->product_commissions[ $product_id ] / $item_qty : $_order->product_commissions[ $product_id ]; 

											?>
											<tr class="item-id-<?php echo $item_id; ?>">
												<td class="wcv-order-thumb">
													<?php if ( $_product ) : ?>
														<?php echo $_product->get_image( 'shop_thumbnail', array( 'title' => '' ) ); ?>
													<?php else : ?>
														<?php echo wc_placeholder_img( 'shop_thumbnail' ); ?>
													<?php endif; ?>
												</td>
												<td class="name">

													<?php echo ( $_product && $_product->get_sku() ) ? esc_html( $_product->get_sku() ) . ' &ndash; ' : ''; ?>

													<?php echo esc_html( $item['name'] ); ?>
													
													<div class="view">
														<?php

															if ( ! empty( $item[ 'item_meta_array' ] ) ) { 

																foreach ( $item[ 'item_meta_array' ] as $meta ) {

																	// Skip hidden core fields
																	if ( in_array( $meta->key, apply_filters( 'woocommerce_hidden_order_itemmeta', array(
																		'_qty',
																		'_tax_class',
																		'_product_id',
																		'_variation_id',
																		'_line_subtotal',
																		'_line_subtotal_tax',
																		'_line_total',
																		'_line_tax',
																		'method_id', 
																		'cost', 
																		'_commission_total', 
																		WC_Vendors::$pv_options->get_option( 'sold_by_label' ), 
																	) ) ) ) {
																		continue;
																	}

																	// Skip serialised meta
																	if ( is_serialized( $meta->value ) ) {
																		continue;
																	}

																	// Get attribute data
																	if ( taxonomy_exists( wc_sanitize_taxonomy_name( $meta->key ) ) ) {
																		$term           = get_term_by( 'slug', $meta->value, wc_sanitize_taxonomy_name( $meta->key ) );
																		$meta->key  	= wc_attribute_label( wc_sanitize_taxonomy_name( $meta->key ) );
																		$meta->value 	= isset( $term->name ) ? $term->name : $meta->value;
																	} else {
																		$meta->key   	= apply_filters( 'woocommerce_attribute_label', wc_attribute_label( $meta->key, $_product ), $meta->key );
																	}

																	echo '<strong>' . wp_kses_post( rawurldecode( $meta->key ) ) . '</strong> : ' . wp_kses_post( rawurldecode( $meta->value ) );
																}
															}
														?>
													</div>
												</td>

												<td class="item_cost" width="1%">
													<div class="view">
														<?php echo wc_price( $product_commision, array( 'currency' => $order_currency ) ); ?>
													</div>
												</td>

												<td class="item_cost" width="1%">
													<div class="view">
														<?php
															if ( isset( $item['line_total'] ) ) {
																if ( isset( $item['line_subtotal'] ) && $item['line_subtotal'] != $item['line_total'] ) {
																	echo '<del>' . wc_price( $order->get_item_subtotal( $item, false, true ), array( 'currency' => $order_currency ) ) . '</del> ';
																}
																echo wc_price( $order->get_item_total( $item, false, true ), array( 'currency' => $order_currency ) );
															}
														?>
													</div>
												</td>

												<td class="quantity" width="1%">
													<div class="view">
														<?php echo ( isset( $item['qty'] ) ) ? esc_html( $item['qty'] ) : '';  ?>
													</div>
												</td>

												<td class="line_cost" width="1%" data-sort-value="<?php echo esc_attr( isset( $item['line_total'] ) ? $item['line_total'] : '' ); ?>">
													<div class="view">
														<?php
															if ( isset( $item['line_total'] ) ) {
																if ( isset( $item['line_subtotal'] ) && $item['line_subtotal'] != $item['line_total'] ) {
																	echo '<del>' . wc_price( $item['line_subtotal'], array( 'currency' => $order_currency ) ) . '</del> ';
																}
																echo wc_price( $item['line_total'], array( 'currency' => $order_currency ) );
															}
														?>
													</div>

												</td>

												<?php
													if ( wc_tax_enabled() ) :
														$line_tax_data = isset( $item['line_tax_data'] ) ? $item['line_tax_data'] : '';
														$tax_data      = maybe_unserialize( $line_tax_data );

														foreach ( $order_taxes as $tax_item ) :
															$tax_item_id       = $tax_item['rate_id'];
															$tax_item_total    = isset( $tax_data['total'][ $tax_item_id ] ) ? $tax_data['total'][ $tax_item_id ] : '';
															$tax_item_subtotal = isset( $tax_data['subtotal'][ $tax_item_id ] ) ? $tax_data['subtotal'][ $tax_item_id ] : '';

															?>
																<td class="line_tax" width="1%">
																	<div class="view">
																		<?php
																			if ( '' != $tax_item_total ) {
																				if ( isset( $tax_item_subtotal ) && $tax_item_subtotal != $tax_item_total ) {
																					echo '<del>' . wc_price( wc_round_tax_total( $tax_item_subtotal ), array( 'currency' => $order_currency ) ) . '</del> ';
																				}

																				echo wc_price( wc_round_tax_total( $tax_item_total ), array( 'currency' => $order_currency ) );
																			} else {
																				echo '&ndash;';
																			}
																		?>
																	</div>
																</td>
															<?php
														endforeach;
													endif;
												?>
											</tr>
									<?php  } ?>
									</tbody>

									<tbody class="wcv-order-totals">
									<tr>
										<td class="wcv-order-totals-label" colspan="5"><?php _e( 'Shipping', 'wcvendors-pro' ); ?>:</td>
										<td class="total"><?php echo wc_price( $_order->total_shipping, array( 'currency' => $order_currency ) ); ?></td>
									</tr>

										<?php if ( wc_tax_enabled() ) : ?>
											<?php foreach ( $order->get_tax_totals() as $code => $tax ) : ?>
												<tr>
													<td class="wcv-order-totals-label" colspan="5"><?php echo $tax->label; ?>:</td>
													<td class="total"><?php
														if ( ( $refunded = $order->get_total_tax_refunded_by_rate_id( $tax->rate_id ) ) > 0 ) {
															echo '<del>' . strip_tags( $tax->formatted_amount ) . '</del> <ins>' . wc_price( WC_Tax::round( $tax->amount, wc_get_price_decimals() ) - WC_Tax::round( $refunded, wc_get_price_decimals() ), array( 'currency' => $order_currency ) ) . '</ins>';
														} else {
															echo $tax->formatted_amount;
														}
													?></td>
												</tr>
											<?php endforeach; ?>
										<?php endif; ?>

										<tr>
											<td class="wcv-order-totals-label" colspan="5"><?php _e( 'Commission Total', 'wcvendors-pro' ); ?>:</td>
											<td class="total"><div class="view"><?php echo wc_price( $_order->commission_total, array( 'currency' => $order_currency ) ); ?></div></td>
										</tr>
										<tr>
											<td class="wcv-order-totals-label" colspan="5"><?php _e( 'Order Total', 'wcvendors-pro' ); ?>:</td>
											<td class="total"><div class="view"><?php echo wc_price( $_order->total, array( 'currency' => $order_currency ) ); ?></div></td>
										</tr>
									
										</tbody>
							</table>

						</div>

					</div>

					<div class="wcv-cols-group wcv-horizontal-gutters">

						    	<div class="all-100">
							    	<h4><?php _e( 'Customer Note', 'wcvendors-pro' ); ?></h4>

							    	<?php 
							    		$customer_note 		 = ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->customer_note : $order->get_customer_note(); 
							    		if ( $customer_note ) { 
							    			echo '<p>'. wp_kses( $order->get_customer_note(), array( 'br' => array() ) ) . '</p>';
							    		} else { 
							    			echo '<p>'. _e( 'No customer notes.', 'wcvendors-pro') . '</p>';
							    		}
							    	?>

						    	</div>

					</div>

			</div>

	</div>

</div>