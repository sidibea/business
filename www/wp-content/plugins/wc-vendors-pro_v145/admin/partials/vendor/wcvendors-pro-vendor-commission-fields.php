<?php

/**
 * The vendor store commission information
 *
 * This file is used to display the Vendor's commission panel in the user edit screen
 *
 * @link       http://www.wcvendors.com
 * @since      1.1.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/admin/partials/store
 */ 
?>

<?php do_action( '_wcv_admin_before_store_commission', $user ); ?>

<!-- Pro Commission -->
<h3><?php _e( 'Pro Commission', 'wcvendors' ); ?></h3>
<table class="form-table">
	<tbody>
	<tr class='form-field _wcv_commission_type_select'>
	<th><label for='_wcv_commission_type'><?php _e( 'Commission Type', 'wcvendors' ); ?></label></th>
	<td><select id="_wcv_commission_type" name="_wcv_commission_type">
			<option></option>
			<?php 
				foreach ( WCVendors_Pro_Commission_Controller::commission_types() as $option => $option_name ) {
					$selected = selected( $option, $commission_type, false );
					echo '<option value="' . $option . '" ' . $selected . '>' . $option_name . '</option>';
				}	
			 ?>
			</select></td>
	</tr>
	<tr class='form-field _wcv_commission_percent_input'>
	<th><label for="_wcv_commission_percent"><?php _e( 'Commission %', 'wcvendors' ); ?></label></th>
	<td><input type="text" id="_wcv_commission_percent" name="_wcv_commission_percent" style="width: 25em" value="<?php echo $commission_percent; ?>"></td>
	</tr>
	<tr class='form-field _wcv_commission_amount_input'>
	<th><label for="_wcv_commission_amount"><?php _e( 'Commission Amount', 'wcvendors' ); ?></label></th>
	<td><input type="text" id="_wcv_commission_amount" name="_wcv_commission_amount" style="width: 25em"  value="<?php echo $commission_amount; ?>"></td>
	</tr>
	<tr class='form-field _wcv_commission_fee_input'>
	<th><label for="_wcv_commission_fee"><?php _e( 'Commission Fee', 'wcvendors' ); ?></label></th>
	<td><input type="text" id="_wcv_commission_fee" name="_wcv_commission_fee" style="width: 25em" value="<?php echo $commission_fee; ?>"></td>
	</tr>
	</tbody>
</table>

<?php do_action( '_wcv_admin_after_store_commission', $user ); ?>