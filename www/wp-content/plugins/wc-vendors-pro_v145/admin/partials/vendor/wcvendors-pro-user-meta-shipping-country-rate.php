<?php

/**
 * The admin country rate shipping for user and product pages
 *
 * This file is used to display the shipping type override in the edit user screen
 *
 * @link       http://www.wcvendors.com
 * @since      1.3.3 
 * @version    1.4.0 
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/admin/partials/store
 */ 

$countries = ( WC()->countries->get_allowed_countries() ) ? WC()->countries->get_allowed_countries() : WC()->countries->get_shipping_countries(); 

?>

<!-- Country Rate Table -->
<?php if ( isset( $user ) ) do_action( 'wcv_admin_user_before_country_rate_shipping', $user ); ?>
<div class="wcv-country_rate_shipping wcv-shipping-rates wcv-shipping-country">
<?php if ( $screen->id == 'user-edit' ) : ?>
<h3><?php _e( 'Country Rate Shipping', 'wcvendors-pro' ); ?></h3>
<?php endif; ?>

<div id="shipping"> 
		<div class="form-field wcv_shipping_rates">
			<table>
				<thead>
					<tr>
						<th class="sort">&nbsp;</th>
						<th align="left"><?php _e( 'Country', 'wcvendors-pro' ); ?></th>
						<th align="left"><?php _e( 'State', 'wcvendors-pro' ); ?> </th>
						<th align="left"><?php _e( 'Postcode', 'wcvendors-pro' ); ?> </th>
						<th align="left"><?php _e( 'Fee', 'wcvendors-pro' ); ?></th>
						<th align="left"><?php _e( 'Override', 'wcvendors-pro' ); ?></th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>

					<?php if ( $shipping_rates ) : ?>
						<?php foreach ( $shipping_rates as $rate ) : ?>

							<!-- required for pro 1.4 and above -->
							<?php if ( ! array_key_exists( 'qty_override', 	$rate ) ) { $rate['qty_override'] 	= ''; } ?>
							<?php if ( ! array_key_exists( 'postcode', 		$rate ) ) { $rate['postcode'] 		= ''; } ?>

						<tr>
							<td class="sort"><i class="fa fa-sort"></i></td>
							<td class="country">
							<select name="_wcv_shipping_countries[]" id="_wcv_shipping_countries[]" class="country_to_state country_select">
								<option value=""><?php _e( 'Select a country&hellip;', 'wcvendors-pro' ); ?></option>

							<?php 
								foreach ( $countries as $ckey => $cvalue ) {
									echo '<option value="' . esc_attr( $ckey ) . '" ' . selected( esc_attr( $rate['country'] ), $ckey, false ) . '>' . $cvalue . '</option>';
								}
							?>

							</select>

							</td>
							<td class="state"><input type="text" placeholder="<?php _e( "State", 'wcvendors-pro' ); ?>" class="shipping_state" name="_wcv_shipping_states[]" value="<?php echo esc_attr( $rate['state'] ); ?>" /></td>
							<td class="postcode"><input type="text" placeholder="<?php _e( "Postcode", 'wcvendors-pro' ); ?>" name="_wcv_shipping_postcodes[]" value="<?php echo esc_attr( $rate['postcode'] ); ?>" /></td>
							<td class="fee"><input type="text" data-rules="decimal"  data-error="<?php _e( 'This should be a number.', 'wcvendors-pro' ); ?>" placeholder="<?php _e( "Fee", 'wcvendors-pro' ); ?>" name="_wcv_shipping_fees[]" value="<?php echo esc_attr( $rate['fee'] ); ?>" /></td>
							<td class="override"><input type="checkbox" name="_wcv_shipping_overrides[]"  <?php checked( $rate[ 'qty_override' ], 'yes' ); ?> /> <label><?php _e( 'QTY', 'wcvendors-pro'); ?></label></td>
							<td width="1%"><a href="#" class="delete"><i class="fa fa-times"></i></a></td>
						</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="7">
							<br />
							<a href="#" class="button insert" style="float: left;" data-row="
							<?php
								$rate = array(
									'country'		=> '',
									'state' 		=> '', 
									'postcode'		=> '', 
									'fee' 			=> '',
									'qty_override' 	=> '', 
								);
								$file_data_row = '<tr>
										<td class="sort"><i class="fa fa-sort"></i></td>
										 <td class="country">
										 <select name="_wcv_shipping_countries[]" id="_wcv_shipping_countries[]" class="country_to_state country_select">
											<option value="">'. __( 'Select a country&hellip;', 'wcvendors-pro' ). '</option>';
								
											foreach ( $countries as $ckey => $cvalue ) {
												$file_data_row .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( esc_attr( $rate['country'] ), $ckey, false ) . '>' . $cvalue . '</option>';
											}
									
									$file_data_row .= '</select></td>

									<td class="state"><input type="text" placeholder="'. __( "State", "wcvendors-pro" ). '" class="shipping_state" name="_wcv_shipping_states[]" value="'. esc_attr( $rate["state"] ) .'" /></td>
									<td class="postcode"><input type="text" placeholder="'. __( "Postcode", "wcvendors-pro" ) .'" name="_wcv_shipping_postcodes[]" value="'. esc_attr( $rate[ "postcode" ] ) . '" /></td>
									<td class="fee"><input type="text" data-error="'.__( "This should be a number.", "wcvendors-pro" ) .'" data-rules="decimal" placeholder="'. __( "Fee", "wcvendors-pro" ). '" name="_wcv_shipping_fees[]" value="'. esc_attr( $rate["fee"] ) .'" /></td>
									<td class="override"><input type="checkbox" name="_wcv_shipping_overrides[]" ' . checked( $rate[ 'qty_override' ], 'yes' ) .' /><label>' . __( "QTY", "wcvendors-pro" ) . '</label></td>
									<td width="1%"><a href="#" class="delete"><i class="fa fa-times"></i></a></td>
								</tr>';

								echo esc_attr( $file_data_row );
							?>">
						<?php _e( 'Add Rate', 'wcvendors-pro' ); ?></a><br /><br />
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php if ( isset( $user ) ) do_action( 'wcv_admin_user_after_country_rate_shipping', $user ); ?>