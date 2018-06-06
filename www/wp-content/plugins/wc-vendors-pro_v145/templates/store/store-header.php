<?php 
/**
 * The Template for displaying a store header
 *
 * Override this template by copying it to yourtheme/wc-vendors/store
 *
 * @package    WCVendors_Pro
 * @version    1.3.5
 */

$store_icon_src 	= wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_icon_id', true ), array( 150, 150 ) ); 
$store_icon 		= ''; 
$store_banner_src 	= wp_get_attachment_image_src( get_user_meta( $vendor_id, '_wcv_store_banner_id', true ), 'full'); 
$store_banner 		= ''; 

// see if the array is valid
if ( is_array( $store_icon_src ) ) { 
	$store_icon 	= '<img src="'. $store_icon_src[0].'" alt="" class="store-icon" />'; 
} 

if ( is_array( $store_banner_src ) ) { 
	$store_banner	= '<img src="'. $store_banner_src[0].'" alt="" class="store-banner" />'; 
} else { 
	//  Getting default banner 
	$default_banner_src = WCVendors_Pro::get_option( 'default_store_banner_src' ); 
	$store_banner	= '<img src="'. $default_banner_src.'" alt="" class="wcv-store-banner" style="max-height: 200px;"/>'; 
}

// Verified vendor 
$verified_vendor 			= ( array_key_exists( '_wcv_verified_vendor', $vendor_meta ) ) ? $vendor_meta[ '_wcv_verified_vendor' ] : false; 
$verified_vendor_label 		= WCVendors_Pro::get_option( 'verified_vendor_label' ); 
// $verified_vendor_icon_src 	= WCVendors_Pro::get_option( 'verified_vendor_icon_src' ); 

// Store title 
$store_title 		=  ( is_product() ) ? '<a href="'. WCV_Vendors::get_vendor_shop_page( $post->post_author ).'">'. $vendor_meta['pv_shop_name'] . '</a>' : $vendor_meta['pv_shop_name']; 

// Get store details including social, adddresses and phone number 
$twitter_username 	= get_user_meta( $vendor_id , '_wcv_twitter_username', true ); 
$instagram_username = get_user_meta( $vendor_id , '_wcv_instagram_username', true ); 
$facebook_url 		= get_user_meta( $vendor_id , '_wcv_facebook_url', true ); 
$linkedin_url 		= get_user_meta( $vendor_id , '_wcv_linkedin_url', true ); 
$youtube_url 		= get_user_meta( $vendor_id , '_wcv_youtube_url', true ); 
$googleplus_url 	= get_user_meta( $vendor_id , '_wcv_googleplus_url', true ); 
$pinterest_url 		= get_user_meta( $vendor_id , '_wcv_pinterest_url', true ); 
$snapchat_username 	= get_user_meta( $vendor_id , '_wcv_snapchat_username', true ); 

// Migrate to store address array 
$address1 			= ( array_key_exists( '_wcv_store_address1', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_address1' ] : ''; 
$address2 			= ( array_key_exists( '_wcv_store_address2', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_address2' ] : '';
$city	 			= ( array_key_exists( '_wcv_store_city', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_city' ]  : '';
$state	 			= ( array_key_exists( '_wcv_store_state', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_state' ] : '';
$phone				= ( array_key_exists( '_wcv_store_phone', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_phone' ]  : '';
$store_postcode		= ( array_key_exists( '_wcv_store_postcode', $vendor_meta ) ) ? $vendor_meta[ '_wcv_store_postcode' ]  : '';

$address 			= ( $address1 != '') ? $address1 .', ' . $city .', '. $state .', '. $store_postcode : '';   

$social_icons = empty( $twitter_username ) && empty( $instagram_username ) && empty( $facebook_url ) && empty( $linkedin_url ) && empty( $youtube_url ) && empty( $googleplus_url ) && empty( $pinterst_url ) && empty( $snapchat_username ) ? false : true; 

// This is where you would load your own custom meta fields if you stored any in the settings page for the dashboard

?>

<?php do_action( 'wcv_before_vendor_store_header' ); ?>

<div class="wcv-header-container">

	<div class="wcv-store-grid wcv-store-header"> 

		<div id="banner-wrap">

			<?php echo $store_banner; ?>

			<div id="inner-element">

				<?php if ( ! empty( $store_icon ) ) : ?> 

		  		<div class="wcv-store-grid__col wcv-store-grid__col--1-of-3  store-brand">	  
			   		<?php echo $store_icon; ?>
			   		<?php if ( $social_icons ) : ?> 				   		   			
					   	<ul class="social-icons"> 
				   			<?php if ( $facebook_url != '') { ?><li><a href="<?php echo $facebook_url; ?>" target="_blank"><i class="fa fa-facebook-square"></i></a></li><?php } ?>
				   			<?php if ( $instagram_username != '') { ?><li><a href="//instagram.com/<?php echo $instagram_username; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li><?php } ?>
				   			<?php if ( $twitter_username != '') { ?><li><a href="//twitter.com/<?php echo $twitter_username; ?>" target="_blank"><i class="fa fa-twitter-square"></i></a></li><?php } ?>
				   			<?php if ( $googleplus_url != '') { ?><li><a href="<?php echo $googleplus_url; ?>" target="_blank"><i class="fa fa-google-plus-square"></i></a></li><?php } ?>
				   			<?php if ( $pinterest_url != '') { ?><li><a href="<?php echo $pinterest_url; ?>" target="_blank"><i class="fa fa-pinterest-square"></i></a></li><?php } ?>
				   			<?php if ( $youtube_url != '') { ?><li><a href="<?php echo $youtube_url; ?>" target="_blank"><i class="fa fa-youtube-square"></i></a></li><?php } ?>
				   			<?php if ( $linkedin_url != '') { ?><li><a href="<?php echo $linkedin_url; ?>" target="_blank"><i class="fa fa-linkedin-square"></i></a></li><?php } ?>
				   			<?php if ( $snapchat_username != '') { ?><li><a href="//www.snapchat.com/add/<?php echo $snapchat_username; ?>" target="_blank"><i class="fa fa-snapchat" aria-hidden="true"></i></a></li><?php } ?>
					   	</ul>
					<?php endif; ?>
			   	</div>
			   	
			   	<?php endif; ?>

			   	<?php if ( ! empty( $store_icon ) ) : ?> 
			   	<div class="wcv-store-grid__col wcv-store-grid__col--2-of-3 store-info">
			    <?php else: ?>
			    <div class="wcv-store-grid__col wcv-store-grid__col--3-of-3 store-info">
			    <?php endif; ?>
			   		<?php do_action( 'wcv_before_vendor_store_title' ); ?>
			   		<h3><?php echo $store_title; ?></h3>	   	
			   		<?php do_action( 'wcv_after_vendor_store_title' ); ?>
			   		<?php do_action( 'wcv_before_vendor_store_rating' ); ?>
				   	<?php if ( ! WCVendors_Pro::get_option( 'ratings_management_cap' ) ) echo WCVendors_Pro_Ratings_Controller::ratings_link( $vendor_id, true ); ?>
					<?php do_action( 'wcv_after_vendor_store_rating' ); ?>	
					<?php do_action( 'wcv_before_vendor_store_description' ); ?>	
					<?php if ( $verified_vendor ) : ?>	   			
						<div class="wcv-verified-vendor">
							<i class="fa fa-check-circle-o fa-lg" aria-hidden="true"></i> &nbsp; <?php echo $verified_vendor_label; ?>
						</div>
					<?php endif; ?> 
				   	<p><?php echo $vendor_meta['pv_shop_description']; ?></p>
				   	<?php do_action( 'wcv_after_vendor_store_description' ); ?>	

				   	<?php if ( empty( $store_icon ) ) : ?> 
						<?php if ( $social_icons ) : ?> 				   		   			
						   	<ul class="social-icons"> 
					   			<?php if ( $facebook_url != '') { ?><li><a href="<?php echo $facebook_url; ?>" target="_blank"><i class="fa fa-facebook-square"></i></a></li><?php } ?>
					   			<?php if ( $instagram_username != '') { ?><li><a href="//instagram.com/<?php echo $instagram_username; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li><?php } ?>
					   			<?php if ( $twitter_username != '') { ?><li><a href="//twitter.com/<?php echo $twitter_username; ?>" target="_blank"><i class="fa fa-twitter-square"></i></a></li><?php } ?>
					   			<?php if ( $googleplus_url != '') { ?><li><a href="<?php echo $googleplus_url; ?>" target="_blank"><i class="fa fa-google-plus-square"></i></a></li><?php } ?>
					   			<?php if ( $pinterest_url != '') { ?><li><a href="<?php echo $pinterest_url; ?>" target="_blank"><i class="fa fa-pinterest-square"></i></a></li><?php } ?>
					   			<?php if ( $youtube_url != '') { ?><li><a href="<?php echo $youtube_url; ?>" target="_blank"><i class="fa fa-youtube-square"></i></a></li><?php } ?>
					   			<?php if ( $linkedin_url != '') { ?><li><a href="<?php echo $linkedin_url; ?>" target="_blank"><i class="fa fa-linkedin-square"></i></a></li><?php } ?>
					   			<?php if ( $snapchat_username != '') { ?><li><a href="//www.snapchat.com/add/<?php echo $snapchat_username; ?>" target="_blank"><i class="fa fa-snapchat" aria-hidden="true"></i></a></li><?php } ?>
						   	</ul>
						<?php endif; ?>
				   	<?php endif; ?>
			   </div>
					   
			</div>
		</div>
	</div>
</div>

<div class="wcv-store-address-container wcv-store-grid ">
	
	<div class="wcv-store-grid__col wcv-store-grid__col--1-of-2 store-address">	  
			<?php if ( $address != '' ) {  ?><a href="http://maps.google.com/maps?&q=<?php echo $address; ?>"><address><i class="fa fa-location-arrow"></i><?php echo $address; ?></address></a><?php } ?>
	</div>
	<div class="wcv-store-grid__col wcv-store-grid__col--1-of-2 store-phone">	  
			<?php if ($phone != '')  { ?><a href="tel:<?php echo $phone; ?>"><i class="fa fa-phone"></i><?php echo $phone; ?></a><?php } ?>
	</div> 
</div>

<?php do_action( 'wcv_after_vendor_store_header' ); ?>

	