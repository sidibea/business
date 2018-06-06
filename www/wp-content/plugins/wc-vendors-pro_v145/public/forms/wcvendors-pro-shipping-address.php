<?php

/**
 * Shipping Address template 
 *
 * This file will output a formatted address fieldset. 
 *
 * @link       http://www.wcvendors.com
 * @since      1.1.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/helpers/forms
 */

?>
<!-- Country  -->

<div class="shipping_other"> 

<?php 
WCVendors_Pro_Form_Helper::country_select2( apply_filters( 'wcv_store_shipping_country', array(  
	'id' 				=> '_wcv_shipping_country', 
	'label' 			=> __( 'Shipping Country', 'wcvendors-pro' ), 
	'type' 				=> 'text', 
	'value'				=> $country
	)
) );
?>

<!-- Address 1 -->
<?php 
WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_store_shipping_address1', array(  
	'id' 				=> '_wcv_shipping_address1', 
	'label' 			=> __( 'Shipping Address', 'wcvendors-pro' ), 
	'placeholder' 		=> __( 'Shipping Address', 'wcvendors-pro' ), 
	'type' 				=> 'text', 
	'value'				=> $address1
	)
) );
?>

<!-- Address 2 -->
<?php 
WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_store_shipping_address2', array(  
	'id' 				=> '_wcv_shipping_address2', 
	'placeholder' 		=> __( 'Apartment, unit, suite etc. ', 'wcvendors-pro' ),  
	'type' 				=> 'text', 
	'value'				=> $address2
	)
) );
?>

<!-- Town / City  -->
<?php 
WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_store_shipping_city', array(  
	'id' 				=> '_wcv_shipping_city', 
	'label' 			=> __( 'City / Town', 'wcvendors-pro' ), 
	'placeholder' 		=> __( 'City / Town', 'wcvendors-pro' ),  
	'type' 				=> 'text', 
	'value'				=> $city
	)
) );
?>

<!-- State / County | Post Code  -->
<?php 
WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_store_state', array( 
	'id' 			=> '_wcv_shipping_state', 
	'label' 		=> __( 'State / County', 'wcvendors-pro' ), 
	'placeholder'	=> __( 'State / County', 'wcvendors-pro' ),  
	'value' 		=> $state, 
	'wrapper_start' => '<div class="wcv-column-group wcv-horizontal-gutters"><div class="all-50 small-100">',
	'wrapper_end' 	=> '</div>', 
	) )
);

WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_store_postcode', array( 
	'id' 				=> '_wcv_shipping_postcode', 
	'label' 			=> __( 'Postcode / Zip', 'wcvendors-pro' ), 	
	'placeholder'		=> __( 'Postcode / Zip', 'wcvendors-pro' ), 
	'value' 			=> $postcode, 
	'wrapper_start' => '<div class="all-50 small-100">',
	'wrapper_end' 		=> '</div></div>', 
	) )
);
?>

</div>
