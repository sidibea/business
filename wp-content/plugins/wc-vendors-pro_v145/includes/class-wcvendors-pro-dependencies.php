<?php

/**
 * Dependencies required for the plugin. 
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/includes
 * @author     Jamie Madden <support@wcvendors.com>
 * @link       http://www.wcvendors.com
 */
class WCVendors_Pro_Dependencies {


	private static $active_plugins;

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		self::$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}
	}

	/**
	 * Check if WooCommerce is active.  
	 *
	 * Check if Woocommerce is active otherwise deactivate. 
	 *
	 * @since    1.0.0
	 */
	public static function woocommerce_active_check()
	{
		if ( !self::$active_plugins ) self::init();

		foreach ( self::$active_plugins as $plugin ) {
			if ( strpos( $plugin, '/woocommerce.php' ) ) return true;
		}

		return false;
	}


	/**
	 * Check if WooCommerce is active.  
	 *
	 * Check if Woocommerce is active otherwise deactivate. 
	 *
	 * @since    1.0.0
	 */
	public static function wcvendors_active_check()
	{
		if ( !self::$active_plugins ) self::init();

		foreach ( self::$active_plugins as $plugin ) {
			if ( strpos( $plugin, '/class-wc-vendors.php' ) ) return true;
		}

		return false;
	}

}