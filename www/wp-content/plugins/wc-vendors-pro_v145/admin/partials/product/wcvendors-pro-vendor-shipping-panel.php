<?php

/**
 * The vendor shipping panel 
 *
 * This file is used to display the Vendor's shipping panel in the product edit screen
 *
 * @link       http://www.wcvendors.com
 * @since      1.3.3
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/admin/partials/store
 */ 
?>

<div class="options_group wcv_vendor_shipping">

<?php if ( $shipping_type == 'flat' ) : ?> 

	<?php include( apply_filters( 'wcv_partial_path_pro_product_shipping_flat_rate' , $this->base_dir . 'admin/partials/product/wcvendors-pro-product-meta-shipping-flat-rate.php' ) ); ?> 

<?php elseif ( $shipping_type =='country' ) :?> 

	<?php include( apply_filters( 'wcv_partial_path_pro_user_shipping_country_rate' , $this->base_dir . 'admin/partials/vendor/wcvendors-pro-user-meta-shipping-country-rate.php' ) ); ?> 

<?php endif; ?>
	
</div>

<div class="options_group">
	<p class="form-field _weight_field ">
		<label for="_handling_fee"><?php _e( 'Product handling fee', 'wcvendors-pro'); ?></label>
		<input type="text" class="short wc_input_decimal" style="" name="_handling_fee" id="_handling_fee" value="<?php echo $handling_fee; ?>" placeholder="0">
	</p>
</div>