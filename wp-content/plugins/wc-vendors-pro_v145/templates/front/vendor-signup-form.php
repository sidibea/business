<?php
/**
 * The template for displaying the vendor application form 
 *
 * Override this template by copying it to yourtheme/wc-vendors/front
 *
 * @package    WCVendors_Pro
 * @version    1.3.2
 */
?>
<form method="post" action="" class="wcv-form wcv-formvalidator"> 

	<?php WCVendors_Pro_Store_Form::sign_up_form_data(); ?>

	<h3><?php _e( 'Vendor Application', 'wcvendors-pro'); ?></h3>

	<div class="wcv-signupnotice"> 
		<?php echo $vendor_signup_notice; ?>
	</div>

	<br />

	<div class="wcv-tabs top" data-prevent-url-change="true">

		<?php WCVendors_Pro_Store_Form::store_form_tabs( ); ?>

		<!-- Store Settings Form -->
		<div class="tabs-content" id="store">

			<!-- Store Name -->
			<?php WCVendors_Pro_Store_Form::store_name( '' ); ?>

			<?php do_action( 'wcvendors_settings_after_shop_name' ); ?>

			<!-- Store Description -->
			<?php WCVendors_Pro_Store_Form::store_description( '' ); ?>	
			
			<?php do_action( 'wcvendors_settings_after_shop_description' ); ?>
			<br />

			<!-- Seller Info -->
			<?php WCVendors_Pro_Store_Form::seller_info( ); ?>	
			
			
			<?php do_action( 'wcvendors_settings_after_seller_info' ); ?>

			<br />

			<!-- Company URL -->
			<?php WCVendors_Pro_Store_Form::company_url( ); ?>

			<!-- Store Phone -->
			<?php WCVendors_Pro_Store_Form::store_phone( ); ?>

			<?php WCVendors_Pro_Store_Form::store_address( ); ?>

		</div>

		<div class="tabs-content" id="payment">
			<!-- Paypal address -->
			<?php WCVendors_Pro_Store_Form::paypal_address( ); ?>
		</div>

		
	<div class="tabs-content" id="branding">
		<?php WCVendors_Pro_Store_Form::store_banner( ); ?>	

		<!-- Store Icon -->
		<?php WCVendors_Pro_Store_Form::store_icon( ); ?>	
	</div>

	<div class="tabs-content" id="shipping">

		<?php do_action( 'wcvendors_settings_before_shipping' ); ?>

		<!-- Shipping Rates -->
		<?php WCVendors_Pro_Store_Form::shipping_rates( ); ?>
		
		<?php do_action( 'wcvendors_settings_after_shipping' ); ?>

	</div>

	<div class="tabs-content" id="social">
		<!-- Twitter -->
		<?php WCVendors_Pro_Store_Form::twitter_username( ); ?>
		<!-- Instagram -->
		<?php WCVendors_Pro_Store_Form::instagram_username( ); ?>
		<!-- Facebook -->
		<?php WCVendors_Pro_Store_Form::facebook_url( ); ?>
		<!-- Linked in -->
		<?php WCVendors_Pro_Store_Form::linkedin_url( ); ?>
		<!-- Youtube URL -->
		<?php WCVendors_Pro_Store_Form::youtube_url( ); ?>
		<!-- Pinterest URL -->
		<?php WCVendors_Pro_Store_Form::pinterest_url( ); ?>
		<!-- Google+ URL -->
		<?php WCVendors_Pro_Store_Form::googleplus_url( ); ?>
	</div>

	</div>

		<!-- Terms and Conditions -->
		<?php WCVendors_Pro_Store_Form::vendor_terms(); ?> 

		<!-- Submit Button -->
		<!-- DO NOT REMOVE THE FOLLOWING TWO LINES -->
		<?php WCVendors_Pro_Store_Form::save_button( __( 'Apply to be Vendor', 'wcvendors-pro') ); ?>

	</form>
