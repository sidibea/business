<?php
/**
 * The WCVendors Pro Tracking Number Form Class
 *
 * This is the tracking number form class
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/forms
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Tracking_Number_Form {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wcvendors_pro    The ID of this plugin.
	 */
	private $wcvendors_pro;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Is the plugin in debug mode 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool    $debug    plugin is in debug mode 
	 */
	private $debug;

	/**
	 * Is the plugin base directory 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $base_dir  string path for the plugin directory 
	 */
	private $base_dir;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wcvendors_pro       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wcvendors_pro, $version, $debug ) {

		$this->wcvendors_pro 	= $wcvendors_pro;
		$this->version 			= $version;
		$this->debug 			= $debug; 
		$this->base_dir			= plugin_dir_path( dirname(__FILE__) ); 
 
	}

	/**
	 *  Output required form data 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function form_data( $order_id, $button_text ) {

		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_tracking_number_order_id', array( 
			'type'			=> 'hidden', 
			'id' 			=> '_wcv_order_id', 
			'value'			=> $order_id
			) )
		);

		wp_nonce_field( 'wcv-add-tracking-number', 'wcv_add_tracking_number' );

		self::save_button( $button_text ); 

	} 

	/**
	 *  Output tracking number 
	 * 
	 * @since    1.0.0
	 */
	public static function tracking_number( $tracking_number, $order_id ) {

		// Tracking number 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_tracking_number', array(  
			'id' 				=> '_wcv_tracking_number_' . $order_id, 
			'label' 			=> __( 'Tracking Number', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'Tracking Number', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $tracking_number
			)
		) );

	} // tracking_number() 


	/**
	 *  Output date shipped date picker 
	 * 
	 * @since    1.0.0
	 */
	public static function date_shipped( $date_shipped, $order_id ) {

		// Date shipped 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_tracking_number_date_shipped', array( 
			'id' 			=> '_wcv_date_shipped_' . $order_id, 
			'label' 		=> __( 'Date Shipped', 'wcvendors-pro' ), 
			'class'			=> 'wcv-datepicker no_limit _wcv_date_shipped_' . $order_id, 
			'value' 		=> $date_shipped, 
			'placeholder'	=> 'YYYY-MM-DD',  
			'custom_attributes' => array(
				'maxlenth' 	=> '10', 
				'pattern' 	=> '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])'
				),
			) )
		);

	} // date_shipped() 


	/**
	 *  Output shipping providers
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function shipping_provider( $shipping_provider, $order_id ) {

		// Download Type
		WCVendors_Pro_Form_Helper::nested_select( apply_filters( 'wcv_tracking_number_shipping_provider', array( 
			'id' 				=> '_wcv_shipping_provider_' . $order_id, 
			'label'	 			=> __( 'Shipping Provider', 'wcvendors-pro' ), 
			'value'				=> $shipping_provider, 
			'class'				=> 'wcv_shipping_provider select2', 
			'value_type'		=> 'key', 
			'options' 			=> WCVendors_Pro_Order_Controller::shipping_providers(), 
			) )
		);
	} // shipping_provider()

	/**
	 *  Output add tracking number button 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function save_button( $button_text ) {

		WCVendors_Pro_Form_helper::submit( apply_filters( 'wcv_tracking_number_save_button', array( 
		 	'id' 		=> 'save_button', 
		 	'value' 	=> $button_text, 
		 	'class'		=> ''
		 	) )
		 ); 

	} // save_button()


}