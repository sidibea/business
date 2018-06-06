<?php

/**
 * The product flat rate shipping panels
 *
 * This file is used to display vendor shipping flat rate on the product edit page
 *
 * @link       http://www.wcvendors.com
 * @since      1.3.4
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/admin/partials/product
 */ 

if ( empty( $shipping_details ) ){ 

	$shipping_details = array( 
		'national' 						=> '', 
		'national_qty_override' 		=> '', 
		'national_free' 				=> '', 
		'national_disable' 				=> '', 
		'international' 				=> '', 
		'international_qty_override' 	=> '', 
		'international_free' 			=> '', 
		'international_disable' 		=> '', 
	); 

}

?>

<!-- National Rates -->
<div class="options_group">
	<p><strong><?php _e( 'National Rates', 'wcvendors-pro' ); ?></strong></p>

	<p class="form-field">
		<label for="_shipping_fee_national"><?php _e( 'National shipping fee', 'wcvendors-pro'); ?></label>
		<input type="text" class="short wc_input_decimal" style="" name="_shipping_fee_national" id="_shipping_fee_national" value="<?php echo $shipping_details[ 'national' ]; ?>" placeholder="0">
	</p>
	<p class="form-field" style="display: block;">
		<label for="_shipping_fee_national_qty"><?php _e( 'Charge Once', 'wcvendors-pro' ); ?></label>
		<?php $checked ?>
		<input type="checkbox" class="checkbox" style="" name="_shipping_fee_national_qty" id="_shipping_fee_national_qty" <?php checked( $shipping_details[ 'national_qty_override' ], 'yes' ); ?> />
		<span class="description"><?php _e( ' Charge once per product for national shipping, even if more than one is purchased.', 'wcvendors-pro' );  ?></span>
	</p>
	<p class="form-field" style="display: block;">
		<label for="_shipping_fee_national_free"><?php _e( 'Free national shipping', 'wcvendors-pro' ); ?></label>
		<input type="checkbox" class="checkbox" style="" name="_shipping_fee_national_free" id="_shipping_fee_national_free"  <?php checked( $shipping_details[ 'national_free' ], 'yes' ); ?> />
	</p>
	
	<p class="form-field" style="display: block;">
		<label for="_shipping_fee_national_disable"><?php _e( 'Disable national shipping', 'wcvendors-pro' ); ?></label>
		<input type="checkbox" class="checkbox" style="" name="_shipping_fee_national_disable" id="_shipping_fee_national_disable" <?php checked( $shipping_details[ 'national_disable' ], 'yes' ); ?> />
	</p>

</div>

<!-- International Rates -->
<div class="options_group">

	<p><strong><?php _e( 'International Rates', 'wcvendors-pro' ); ?></strong></p>
	<p class="form-field">
		<label for="_shipping_fee_international"><?php _e( 'International shipping fee', 'wcvendors-pro'); ?></label>
		<input type="text" class="short wc_input_decimal" style="" name="_shipping_fee_international" id="_shipping_fee_international" value="<?php echo $shipping_details[ 'international' ]; ?>" placeholder="0">
	</p>
	<p class="form-field" style="display: block;">
		<label for="_shipping_fee_international_qty"><?php _e( 'Charge Once', 'wcvendors-pro' ); ?></label>
		<input type="checkbox" class="checkbox" style="" name="_shipping_fee_international_qty" id="_shipping_fee_international_qty" <?php checked( $shipping_details[ 'international_qty_override' ], 'yes' ); ?> />
		<span class="description"><?php _e( ' Charge once per product for international shipping, even if more than one is purchased.', 'wcvendors-pro' );  ?></span>
	</p>
	<p class="form-field" style="display: block;">
		<label for="_shipping_fee_international_free"><?php _e( 'Free international shipping', 'wcvendors-pro' ); ?></label>
		<input type="checkbox" class="checkbox" style="" name="_shipping_fee_international_free" id="_shipping_fee_international_free" <?php checked( $shipping_details[ 'international_free' ], 'yes' ); ?> />
	</p>

	<p class="form-field" style="display: block;">
		<label for="_shipping_fee_international_disable"><?php _e( 'Disable international shipping', 'wcvendors-pro' ); ?></label>
		<input type="checkbox" class="checkbox" style="" name="_shipping_fee_international_disable" id="_shipping_fee_international_disable" <?php checked( $shipping_details[ 'international_disable' ], 'yes' ); ?> />
	</p>

</div>




