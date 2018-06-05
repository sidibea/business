<?php 
/**
 * The Template for displaying a vendor in the vendor list shortcode 
 *
 * Override this template by copying it to yourtheme/wc-vendors/front
 *
 * @package    WCVendors_Pro
 * @version    1.2.3
 */

$store_icon_src 	= wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_icon_id', true ), array( 100, 100 ) ); 
$store_icon 		= ''; 
$store_banner_src 	= wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_banner_id', true ), 'full'); 
$store_banner 		= ''; 

// see if the array is valid
if ( is_array( $store_icon_src ) ) { 
	$store_icon 	= '<img src="'. $store_icon_src[0].'" alt="" class="store-icon" />'; 
} 

if ( is_array( $store_banner_src ) ) { 
	$store_banner	= '<img src="'. $store_banner_src[0].'" alt="" class="store-banner" style="max-height: 200px;"/>'; 
} else { 
	//  Getting default banner 
	$default_banner_src = WCVendors_Pro::get_option( 'default_store_banner_src' ); 
	$store_banner	= '<img src="'. $default_banner_src.'" alt="" class="store-banner" style="max-height: 200px;"/>'; 
}

?>

<div class="wcv-pro-vendorlist"> 

	<div class="wcv-store-grid"> 

		<div class="wcv-banner-wrapper"> 
			
				<?php echo $store_banner; ?>

				<div class="wcv-inner-details">
					<a href="<?php echo $shop_link; ?>">
						<div class="wcv-store-grid__col wcv-store-grid__col--1-of-3  wcv-icon-container">
							<?php echo $store_icon; ?>
						</div>

						<div class="wcv-store-grid__col wcv-store-grid__col--2-of-3 store-info wcv-shop-details"> 
							<h4><?php echo $shop_name; ?></h4>
							<p><?php echo $vendor_meta[ 'pv_shop_description' ]; ?>
							
							</p>
						</div>

					</a>
				</div>		
		</div>	

	</div><!-- close wcv-store-grid -->

</div>