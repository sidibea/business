<?php
/**
 * Active plugins 
 */
if ( ! function_exists( 'get_active_plugins') ){ 
	function get_active_plugins(){ 
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		return $active_plugins; 
	}
}

/**
 * WooCommerce Detection
 */
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	function is_woocommerce_active() {
		$active_plugins = get_active_plugins(); 		
		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}
}

/**
 * WC Vendors Detection 
 */
if ( ! function_exists( 'is_wcvendors_active' ) ) {
	function is_wcvendors_active() {
		$active_plugins = get_active_plugins(); 		
		return in_array( 'wc-vendors/class-wc-vendors.php', $active_plugins ) || array_key_exists( 'wc-vendors/class-wc-vendors.php', $active_plugins );
	}
}

/**
 * WooCommerce Required Notice 
 */
if ( ! function_exists( 'woocommerce_required_notice' ) ) {
	function woocommerce_required_notice() {
		echo '<div class="error"><p>' . __( '<b>WooCommerce not found.</b>. WC Vendors Pro requires a minimum of WooCommerce v2.6.0.', 'wcvendors-pro' ) . '</p></div>';	
	}
}

/**
 * WooCommerce Required Notice 
 */
if ( ! function_exists( 'wcvendors_required_notice' ) ) {
	function wcvendors_required_notice() {
		echo '<div class="error"><p>' . __( '<b>WC Vendors not found.</b>. WC Vendors Pro requires a minimum of WC Vendors v1.9.8.', 'wcvendors-pro' ) . '</p></div>';
	}
}