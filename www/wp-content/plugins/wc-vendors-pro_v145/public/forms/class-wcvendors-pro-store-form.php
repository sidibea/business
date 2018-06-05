<?php
/**
 * The WCVendors Pro Order Form Class
 *
 * This is the order form class
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/forms
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Store_Form {

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
	 * What form type is it settings or sign up
	 *
	 * @since    1.2.0
	 * @access   public
	 * @var      bool    $form_type  bool true for sign up form otherwise its the settings form for vendors 
	*/
	public static $form_type; 

	/**
	 * Settings General Options
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $settings_general  array with options from admin
	 */
	private static $settings_general;

	/**
	 * Settings Store Options
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $settings_store  array with options from admin
	 */
	private static $settings_store;

	/**
	 * Settings Payment Options
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $settings_payment  array with options from admin
	 */
	private static $settings_payment;

	/**
	 * Settings Branding Options
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $settings_branding  array with options from admin
	 */
	private static $settings_branding;

	/**
	 * Settings Shipping Options
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $settings_shipping  array with options from admin
	 */
	private static $settings_shipping;

	/**
	 * Settings Social Options
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $settings_social  array with options from admin
	 */
	private static $settings_social;


	/**
	 * Signup General Options
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $signup_general  array with options from admin
	 */
	private static $signup_general;

	/**
	 * Signup Store Options
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $signup_store  array with options from admin
	 */
	private static $signup_store;

	/**
	 * Signup Payment Options
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $signup_payment  array with options from admin
	 */
	private static $signup_payment;

	/**
	 * Signup Branding Options
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $signup_branding  array with options from admin
	 */
	private static $signup_branding;

	/**
	 * Signup Shipping Options
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $signup_shipping  array with options from admin
	 */
	private static $signup_shipping;

	/**
	 * Signup Social Options
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $signup_social  array with options from admin
	 */
	private static $signup_social;

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
	 *  Init variables for use in this class
	 * 
	 * @since    1.2.0
	 */
	public function init(){ 

		// Settings page options
		self::$settings_general		= (array) WC_Vendors::$pv_options->get_option( 'hide_settings_general' );
		self::$settings_store 		= (array) WC_Vendors::$pv_options->get_option( 'hide_settings_store' );
		self::$settings_payment 	= (array) WC_Vendors::$pv_options->get_option( 'hide_settings_payment' );
		self::$settings_branding 	= (array) WC_Vendors::$pv_options->get_option( 'hide_settings_branding' );
		self::$settings_shipping 	= (array) WC_Vendors::$pv_options->get_option( 'hide_settings_shipping' );
		self::$settings_social 		= (array) WC_Vendors::$pv_options->get_option( 'hide_settings_social' );

		// Signup page options
		self::$signup_general		= (array) WC_Vendors::$pv_options->get_option( 'hide_signup_general' );
		self::$signup_store 		= (array) WC_Vendors::$pv_options->get_option( 'hide_signup_store' );
		self::$signup_payment 		= (array) WC_Vendors::$pv_options->get_option( 'hide_signup_payment' );
		self::$signup_branding 		= (array) WC_Vendors::$pv_options->get_option( 'hide_signup_branding' );
		self::$signup_shipping 		= (array) WC_Vendors::$pv_options->get_option( 'hide_signup_shipping' );
		self::$signup_social 		= (array) WC_Vendors::$pv_options->get_option( 'hide_signup_social' );
	}

	/**
	 *  Output required form data 
	 * 
	 * @since    1.2.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function form_data( $signup = false ) {

		self::$form_type = $signup; 

		wp_nonce_field( 'wcv-save_store_settings', '_wcv-save_store_settings' );	

	} //form_data()


	/**
	 *  Output required sign up form data 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function sign_up_form_data( ) {

		self::form_data( true ); 

		// Needed for processing the signup form 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_application_id', array( 
			'type'			=> 'hidden', 
			'id' 			=> '_wcv_vendor_application_id', 
			'value'			=> get_current_user_id() 
			) )
		);

	} //sign_up_form_data() 


	/**
	 *  Output the tabs for the settings or signup form. 
	 * 
	 * @since    1.2.0
	 */
	public static function store_form_tabs(){ 

		global $woocommerce; 

		$hide_tabs = ( self::$form_type ) ? self::$signup_general : self::$settings_general; 
		$store 	   = ( self::$form_type ) ? self::$signup_store : self::$settings_store; 
		$payment   = ( self::$form_type ) ? self::$signup_payment : self::$settings_payment; 
		$branding  = ( self::$form_type ) ? self::$signup_branding : self::$settings_branding; 
		$shipping  = ( self::$form_type ) ? self::$signup_branding : self::$settings_branding; 
		$social    = ( self::$form_type ) ? self::$signup_social : self::$settings_social; 

		$settings_social 	= (array) WC_Vendors::$pv_options->get_option( 'hide_settings_social' );
		$social_total 		= count( $settings_social ); 
		$social_count 		= 0; 
		foreach ( $social as $value) { if ( 1 == $value ) $social_count +=1;  }


		$shipping_disabled			= WCVendors_Pro::get_option( 'shipping_management_cap' ); 
		$shipping_methods 			= $woocommerce->shipping->load_shipping_methods();
		$shipping_method_enabled	= ( array_key_exists('wcv_pro_vendor_shipping', $shipping_methods ) && $shipping_methods['wcv_pro_vendor_shipping']->enabled == 'yes' ) ? true : false; 
		$css_classes				= apply_filters( 'wcv_store_tabs_class', array( 'tabs-nav' ) ); 

		$store_tabs = apply_filters( 'wcv_store_tabs', array(
			'store' 			=> array(
				'label'  => __( 'Store', 'wcvendors-pro'), 
				'target' => 'store',
				'class'  => array(),
			), 
			'payment' 		=> array( 
				'label'  => __( 'Payment', 'wcvendors-pro'), 
				'target' => 'payment',
				'class'  => array(),
			), 
			'branding' 			=> array( 
				'label'  => __( 'Branding', 'wcvendors-pro'), 
				'target' => 'branding',
				'class'  => array(),
			), 
			'shipping'	=> array( 
				'label'  => __( 'Shipping', 'wcvendors-pro'), 
				'target' => 'shipping',
				'class'  => array(),
			), 
			'social'	=> array( 
				'label'  => __( 'Social', 'wcvendors-pro'), 
				'target' => 'social',
				'class'  => array(),
			), 
		)); 

		foreach ( $hide_tabs as $panel => $value ) {

			if ( array_key_exists( $panel, $store_tabs ) ) { 
				if ( $value ) unset( $store_tabs[ $panel ] ); 
			}

		}

		if ( $social_count == $social_total ) { unset( $store_tabs[ 'social' ] ); }

		$css_class = implode(' ', $css_classes ); 

		if ( $shipping_disabled || ! $shipping_method_enabled ) { unset( $store_tabs[ 'shipping' ] );  }

		include( apply_filters( 'wcvendors_pro_store_form_store_tabs_path', 'partials/wcvendors-pro-store-tabs.php' ) );

	} // form_tabs()


	/**
	 *  Output save button 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function save_button( $button_text ) {

		WCVendors_Pro_Form_helper::submit( apply_filters( 'wcv_store_save_button', array( 
		 	'id' 		=> 'save_button', 
		 	'value' 	=> $button_text, 
		 	'class'		=> ''
		 	) )
		 ); 

	} // save_button()



	/**
	 *  Output store banner uploader  
	 * 
	 * @since    1.2.0
	 */
	public static function store_banner( ) {

		$branding  = ( self::$form_type ) ? self::$signup_branding : self::$settings_branding; 

		if ( ! $branding[ 'store_banner' ] ) { 

			$value = get_user_meta( get_current_user_id(), '_wcv_store_banner_id', true ); 

			echo '<h6>'. __( 'Store Banner', 'wcvendors-pro'). '</h6>'; 

			if ( self::$form_type ){ 

				echo '<p>' . __( 'Once you become a vendor you can upload your banner here.', 'wcvendors-pro' ). '</p>'; 

			} else { 

				// Store Banner Image
				WCVendors_Pro_Form_Helper::file_uploader( apply_filters( 'wcv_vendor_store_banner', array(  
					'header_text'		=> __('Store Banner', 'wcvendors-pro' ), 
					'add_text' 			=> __('Add Store Banner', 'wcvendors-pro' ), 
					'remove_text'		=> __('Remove Store Banner', 'wcvendors-pro' ), 
					'image_meta_key' 	=> '_wcv_store_banner_id', 
					'save_button'		=> __('Add Store Banner', 'wcvendors-pro' ), 
					'window_title'		=> __('Select an Image', 'wcvendors-pro' ), 
					'value'				=> $value
					)
				) );

			}

		} 

	} // store_banner()


	/**
	 *  Output store icon uploader  
	 * 
	 * @since    1.2.0
	 * @todo 	 dimension limits 
	 */
	public static function store_icon( ) {

		$branding  = ( self::$form_type ) ? self::$signup_branding : self::$settings_branding; 

		if ( ! $branding[ 'store_icon' ] ) { 

			$value = get_user_meta( get_current_user_id(),  '_wcv_store_icon_id', true ); 

			echo '<h6>'. __( 'Store Icon', 'wcvendors-pro'). '</h6>';

			if ( self::$form_type ){ 

				echo '<p>' . __( 'Once you become a vendor you can upload your store icon here.', 'wcvendors-pro' ). '</p>'; 

			} else { 

				// Store Icon
				WCVendors_Pro_Form_Helper::file_uploader( apply_filters( 'wcv_vendor_store_icon', array(  
					'header_text'		=> __('Store Icon', 'wcvendors-pro' ), 
					'add_text' 			=> __('Add Store Icon', 'wcvendors-pro' ), 
					'remove_text'		=> __('Remove Store Icon', 'wcvendors-pro' ), 
					'image_meta_key' 	=> '_wcv_store_icon_id', 
					'save_button'		=> __('Add Store Icon', 'wcvendors-pro' ), 
					'window_title'		=> __('Select an Image', 'wcvendors-pro' ), 
					'value'				=> $value, 
					'size'				=> 'thumbnail', 
					'class'				=> 'wcv-store-icon'
					)
				) );

			} 

		} 

	} // store_icon()

	/**
	 *  Output paypal address
	 * 
	 * @since    1.2.0
	 */
	public static function paypal_address(  ) {

		$payment   = ( self::$form_type ) ? self::$signup_payment : self::$settings_payment; 

		if ( !$payment[ 'paypal' ] ) { 

			$value = get_user_meta( get_current_user_id(), 'pv_paypal', true ); 

			// Paypal address
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_paypal_address', array(  
				'id' 				=> '_wcv_paypal_address', 
				'label' 			=> __( 'PayPal Address', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'yourpaypaladdress@goeshere.com', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Your PayPal address is used to send you your commission.', 'wcvendors-pro' ), 
				'type' 				=> 'email', 
				'value'				=> $value
				)
			) );
		} 

	} // paypal_address()

	/**
	 *  Output store name
	 * 
	 * @since    1.2.0
	 */
	public static function store_name( $store_name ) {

		if ( '' == $store_name ) { 
			$user_data = get_userdata( get_current_user_id() ); 
			$store_name = apply_filters( 'wcv_default_store_name' , ucfirst( $user_data->display_name ) . __( ' Store', 'wcvendors-pro' ), $user_data ); 
		} 

		// Store Name
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_store_name', array(  
			'id' 				=> '_wcv_store_name', 
			'label' 			=> __( 'Store Name <small>Required</small>', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'Your Store Name', 'wcvendors-pro' ), 
			'desc_tip' 			=> 'true', 
			'description' 		=> __( 'Your shop name is public and must be unique.', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $store_name,
			'custom_attributes' => array(
				'data-rules' 	=> 'required', 
				'data-error' => __( 'This field is required.', 'wcvendors-pro' )
				),
			)
		) );

	} // store_name()


	/**
	 *  Output store name
	 * 
	 * @since    1.2.0
	 */
	public static function store_phone( ) {

		$store 	   = ( self::$form_type ) ? self::$signup_store : self::$settings_store; 
		
		if ( !$store[ '_wcv_store_phone' ] ) { 

			$value = get_user_meta( get_current_user_id(), '_wcv_store_phone', true ); 

			// Store Name
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_store_phone', array(  
				'id' 				=> '_wcv_store_phone', 
				'label' 			=> __( 'Store Phone', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'Your store phone number', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'This is your store contact number', 'wcvendors-pro' ), 
				'type' 				=> 'text', 
				'value'				=> $value
				)
			) );

		} 

	} // store_name()

	/**
	 *  Output store info
	 * 
	 * @since    1.2.0
	 */
	public static function seller_info( ) {

		$store 	   = ( self::$form_type ) ? self::$signup_store : self::$settings_store; 

		if ( ! $store[ 'pv_seller_info' ] ) { 

			$user_id			= get_current_user_id(); 
			$value 				= get_user_meta( $user_id, 'pv_seller_info', true ); 
			$vendor_store_html  = get_user_meta( $user_id, 'pv_shop_html_enabled', true );
			$store_wide_html 	= WC_Vendors::$pv_options->get_option( 'shop_html_enabled' );


			// If html in info is allowed then display the tinyMCE otherwise just display a text box.
			if ( $vendor_store_html || $store_wide_html ) {

				$settings = apply_filters('wcv_vendor_seller_info_editor_settings', array( 
					'editor_height' => 200, 
					'media_buttons' => false,
					'teeny'			=> true,
					)
				);

				echo '<label>'. __( 'Seller Info', 'wcvendors-pro'). '</label>';

				wp_editor( $value , 'pv_seller_info', $settings );

			} else {


				WCVendors_Pro_Form_Helper::textarea( apply_filters( 'wcv_vendor_seller_info', array( 
			 		'id' 		=> 'pv_seller_info', 
			 		'label'	 	=> __( 'Seller Info', 'wcvendors-pro' ), 
			 		'value' 	=> $value  
			 		) )
			 	);

			 } 
		} 
		 
	} // description()


	/**
	 *  Output store description
	 * 
	 * @since    1.2.0
	 */
	public static function store_description( ) {

		$store 	   = ( self::$form_type ) ? self::$signup_store : self::$settings_store; 

		if ( ! $store[ 'pv_shop_description' ] ) { 

			$user_id			= get_current_user_id(); 
			$vendor_store_html  = get_user_meta( $user_id, 'pv_shop_html_enabled', true );
			$store_wide_html 	= WC_Vendors::$pv_options->get_option( 'shop_html_enabled' );

			$value 		= get_user_meta( get_current_user_id(), 'pv_shop_description', true ); 

			// If html in info is allowed then display the tinyMCE otherwise just display a text box.
			if ( $vendor_store_html || $store_wide_html ) {

				$settings = apply_filters('wcv_vendor_store_description_editor_settings', array( 
					'editor_height' => 200, 
					'media_buttons' => false,
					'teeny'			=> true,
					)
				);

				echo '<label>'. __( 'Store Description', 'wcvendors-pro'). '</label>'; 

				wp_editor( $value, 'pv_shop_description', $settings );
				
			} else {
			
				WCVendors_Pro_Form_Helper::textarea( apply_filters( 'wcv_vendor_store_description', array( 
			 		'id' 		=> 'pv_shop_description', 
			 		'label'	 	=> __( 'Store Description', 'wcvendors-pro'),  
			 		'value' 	=> $value 
			 		) )
			 	);
			 } 
		} 
		 
	} // description()

	/**
	 * DEPRICATED - Output a formatted store address
	 *
	 * @since      1.2.0
	 * @param      int     $post_id      the post id for the files being uploaded 
	 */
	public static function store_address( ) { 

		$store 	   = ( self::$form_type ) ? self::$signup_store : self::$settings_store; 

		if ( ! $store[ 'store_address' ] ) { 

			$address1 	= get_user_meta( get_current_user_id(), '_wcv_store_address1', 	true ); 
			$address2 	= get_user_meta( get_current_user_id(), '_wcv_store_address2', 	true ); 
			$city	 	= get_user_meta( get_current_user_id(), '_wcv_store_city', 		true ); 
			$state	 	= get_user_meta( get_current_user_id(), '_wcv_store_state',		true ); 
			$country	= get_user_meta( get_current_user_id(), '_wcv_store_country', 	true ); 
			$postcode	= get_user_meta( get_current_user_id(), '_wcv_store_postcode', 	true ); 

			include( apply_filters( 'wcvendors_pro_store_form_store_address_path', 'wcvendors-pro-address.php' ) );
		} 

	} // store_address()

	/**
	 * Output a formatted store address country 
	 *
	 * @since      1.2.0
	 * @param      int     $post_id      the post id for the files being uploaded 
	 */
	public static function store_address_country( ) { 

		$store 	   = ( self::$form_type ) ? self::$signup_store : self::$settings_store; 

		if ( ! $store[ 'store_address' ] ) { 

			$country	= get_user_meta( get_current_user_id(), '_wcv_store_country', 	true ); 

			WCVendors_Pro_Form_Helper::country_select2( apply_filters( 'wcv_vendor_store_country', array(  
				'id' 				=> '_wcv_store_country', 
				'label' 			=> __( 'Store Country', 'wcvendors-pro' ), 
				'type' 				=> 'text', 
				'value'				=> $country
				)
			) );
		} 

	} //store_address_

	/**
	 * Output a formatted store address1 
	 *
	 * @since      1.2.0
	 * @param      int     $post_id      the post id for the files being uploaded 
	 */
	public static function store_address1( ) { 

		$store 	   = ( self::$form_type ) ? self::$signup_store : self::$settings_store; 

		if ( ! $store[ 'store_address' ] ) { 

			$address1 	= get_user_meta( get_current_user_id(), '_wcv_store_address1', 	true ); 

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_store_address1', array(  
				'id' 				=> '_wcv_store_address1', 
				'label' 			=> __( 'Store Address', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'Street Address', 'wcvendors-pro' ), 
				'type' 				=> 'text', 
				'value'				=> $address1
				)
			) );
		} 

	} //store_address1()

	/**
	 * Output a formatted store address2
	 *
	 * @since      1.2.0
	 * @param      int     $post_id      the post id for the files being uploaded 
	 */
	public static function store_address2( ) { 

		$store 	   = ( self::$form_type ) ? self::$signup_store : self::$settings_store; 

		if ( ! $store[ 'store_address' ] ) { 

			$address2 	= get_user_meta( get_current_user_id(), '_wcv_store_address2', 	true ); 

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_store_address2', array(  
				'id' 				=> '_wcv_store_address2', 
				'placeholder' 		=> __( 'Apartment, unit, suite etc. ', 'wcvendors-pro' ),  
				'type' 				=> 'text', 
				'value'				=> $address2
				)
			) );
		} 

	} //store_address2()


	/**
	 * Output a formatted store address city
	 *
	 * @since      1.2.0
	 * @param      int     $post_id      the post id for the files being uploaded 
	 */
	public static function store_address_city( ) { 

		$store 	   = ( self::$form_type ) ? self::$signup_store : self::$settings_store; 

		if ( ! $store[ 'store_address' ] ) { 

			$city	 	= get_user_meta( get_current_user_id(), '_wcv_store_city', 		true ); 

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_store_city', array(  
				'id' 				=> '_wcv_store_city', 
				'label' 			=> __( 'City / Town', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'City / Town', 'wcvendors-pro' ),  
				'type' 				=> 'text', 
				'value'				=> $city
				)
			) );

		} 

	} //store_address_city()


	/**
	 * Output a formatted store address state
	 *
	 * @since      1.2.0
	 * @param      int     $post_id      the post id for the files being uploaded 
	 */
	public static function store_address_state( ) { 

		$store 	   = ( self::$form_type ) ? self::$signup_store : self::$settings_store; 

		if ( ! $store[ 'store_address' ] ) { 

			$state	 	= get_user_meta( get_current_user_id(), '_wcv_store_state',		true ); 

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_store_state', array( 
				'id' 			=> '_wcv_store_state', 
				'label' 		=> __( 'State / County', 'wcvendors-pro' ), 
				'placeholder'	=> __( 'State / County', 'wcvendors-pro' ),  
				'value' 		=> $state, 
				'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">',
				'wrapper_end' 	=> '</div>', 
				) )
			);

		} 

	} //store_address_state()

	/**
	 * Output a formatted store address postcode
	 *
	 * @since      1.2.0
	 * @param      int     $post_id      the post id for the files being uploaded 
	 */
	public static function store_address_postcode( ) { 

		$store 	   = ( self::$form_type ) ? self::$signup_store : self::$settings_store; 

		if ( ! $store[ 'store_address' ] ) { 

			$postcode	= get_user_meta( get_current_user_id(), '_wcv_store_postcode', 	true ); 

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_store_postcode', array( 
				'id' 				=> '_wcv_store_postcode', 
				'label' 			=> __( 'Postcode / Zip', 'wcvendors-pro' ), 	
				'placeholder'		=> __( 'Postcode / Zip', 'wcvendors-pro' ), 
				'value' 			=> $postcode, 
				'wrapper_start' => '<div class="all-50 small-100">',
				'wrapper_end' 		=> '</div></div>', 
				) )
			);

		} 

	} //store_address_state()

	/**
	 *  Output company url field
	 * 
	 * @since    1.2.0
	 */
	public static function company_url(  ) {

		$store 	   = ( self::$form_type ) ? self::$signup_store : self::$settings_store; 

		if ( ! $store[ '_wcv_company_url' ] ) { 

			$value 		= get_user_meta( get_current_user_id(), '_wcv_company_url', true ); 

			// Company URL
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_company_url', array(  
				'id' 				=> '_wcv_company_url', 
				'label' 			=> __( 'Store Website / Blog URL', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'https://yourcompany-blogurl.com/here', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Your <a href="https://yourcompany-blogurl.com/here">Company / Blog </a> url.', 'wcvendors-pro' ), 
				'type' 				=> 'text', 
				'value'				=> $value
				)
			) );
		} 

	} // facebook_url()

	/**
	 *  Output twitter username field
	 * 
	 * @since    1.2.0
	 */
	public static function twitter_username(  ) {

		$social    = ( self::$form_type ) ? self::$signup_social : self::$settings_social; 

		if ( ! $social['twitter'] ) { 

			$value = get_user_meta( get_current_user_id(), '_wcv_twitter_username', true ); 

			// Twitter Username
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_twitter_username', array(  
				'id' 				=> '_wcv_twitter_username', 
				'label' 			=> __( 'Twitter Username', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'YourTwitterUserHere', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Your <a href="https://twitter.com/">Twitter</a> username without the url.', 'wcvendors-pro' ), 
				'type' 				=> 'text', 
				'value'				=> $value
				)
			) );

		}

	} // twitter_username()

	/**
	 *  Output instagram username field
	 * 
	 * @since    1.2.0
	 */
	public static function instagram_username(  ) {

		$social    = ( self::$form_type ) ? self::$signup_social : self::$settings_social; 

		if ( ! $social[ 'instagram' ] ) { 

			$value = get_user_meta( get_current_user_id(), '_wcv_instagram_username', true ); 

			// Instagram Username
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_instagram_username', array(  
				'id' 				=> '_wcv_instagram_username', 
				'label' 			=> __( 'Instagram Username', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'YourInstagramUsername', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Your <a href="https://instagram.com/">Instagram</a> username without the url.', 'wcvendors-pro' ), 
				'type' 				=> 'text', 
				'value'				=> $value
				)
			) );

		}

	} // instagram_username()

	/**
	 *  Output facebook url field
	 * 
	 * @since    1.2.0
	 */
	public static function facebook_url(  ) {

		$social    = ( self::$form_type ) ? self::$signup_social : self::$settings_social; 

		if ( ! $social[ 'facebook' ] ) { 

			$value 		= get_user_meta( get_current_user_id(), '_wcv_facebook_url', true ); 

			// Facebook URL
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_facebook_url', array(  
				'id' 				=> '_wcv_facebook_url', 
				'label' 			=> __( 'Facebook URL', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'http://yourfacebookurl/here', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Your <a href="https://facebook.com/">Facebook</a> url.', 'wcvendors-pro' ), 
				'type' 				=> 'text', 
				'value'				=> $value
				)
			) );

		} 

	} // facebook_url()

	/**
	 *  Output LinkedIn url field
	 * 
	 * @since    1.2.0
	 */
	public static function linkedin_url(  ) {

		$social    = ( self::$form_type ) ? self::$signup_social : self::$settings_social; 

		if ( ! $social[ 'linkedin' ] ) { 

			$value 		= get_user_meta( get_current_user_id(), '_wcv_linkedin_url', true ); 

			// Facebook URL
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_linkedin_url', array(  
				'id' 				=> '_wcv_linkedin_url', 
				'label' 			=> __( 'LinkedIn URL', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'http://linkedinurl.com/here', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Your <a href="https://linkedin.com/">LinkedIn</a> url.', 'wcvendors-pro' ), 
				'type' 				=> 'url', 
				'value'				=> $value
				)
			) );

		} 

	} // linkedin_url()

	/**
	 *  Output youtube url field
	 * 
	 * @since    1.2.0
	 */
	public static function youtube_url(  ) {

		$social    = ( self::$form_type ) ? self::$signup_social : self::$settings_social; 

		if ( ! $social[ 'youtube' ] ) { 

			$value 		= get_user_meta( get_current_user_id(), '_wcv_youtube_url', true ); 

			// Youtube URL
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_youtube_url', array(  
				'id' 				=> '_wcv_youtube_url', 
				'label' 			=> __( 'YouTube URL', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'http://youtube.com/here', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Your <a href="https://youtube.com/">Youtube</a> url.', 'wcvendors-pro' ), 
				'type' 				=> 'url', 
				'value'				=> $value
				)
			) );

		} 

	} // youtube_url()

	/**
	 *  Output youtube url field
	 * 
	 * @since    1.2.0
	 */
	public static function googleplus_url(  ) {

		$social    = ( self::$form_type ) ? self::$signup_social : self::$settings_social; 

		if ( ! $social[ 'google_plus' ] ) { 

			$value 		= get_user_meta( get_current_user_id(), '_wcv_googleplus_url', true ); 

			// Facebook URL
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_googleplus_url', array(  
				'id' 				=> '_wcv_googleplus_url', 
				'label' 			=> __( 'Google+ URL', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'https://plus.google.com/yourname', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Your <a href="https://plus.google.com">Google+</a> url.', 'wcvendors-pro' ), 
				'type' 				=> 'url', 
				'value'				=> $value
				)
			) );

		}

	} // googleplus_url()


	/**
	 *  Output pintrest url field
	 * 
	 * @since    1.2.0
	 */
	public static function pinterest_url(  ) {

		$social    = ( self::$form_type ) ? self::$signup_social : self::$settings_social; 

		if ( ! $social[ 'pinterest' ] ) { 

			$value 		= get_user_meta( get_current_user_id(), '_wcv_pinterest_url', true ); 

			// Pinterest URL
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_pinterest_url', array(  
				'id' 				=> '_wcv_pinterest_url', 
				'label' 			=> __( 'Pinterest URL', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'https://www.pinterest.com/username/', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Your <a href="https://www.pinterest.com/">Pinterest</a> url.', 'wcvendors-pro' ), 
				'type' 				=> 'url', 
				'value'				=> $value
				)
			) );

		} 

	} // pinterest_url()

	/**
	 *  Output snapchat username field
	 * 
	 * @since    1.2.0
	 */
	public static function snapchat_username(  ) {

		$social    = ( self::$form_type ) ? self::$signup_social : self::$settings_social; 

		if ( ! $social[ 'snapchat' ] ) { 

			$value = get_user_meta( get_current_user_id(), '_wcv_snapchat_username', true ); 

			// Instagram Username
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_snapchat_username', array(  
				'id' 				=> '_wcv_snapchat_username', 
				'label' 			=> __( 'Snapchat Username', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'snapchatUsername', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Your snapchat username.', 'wcvendors-pro' ), 
				'type' 				=> 'text', 
				'value'				=> $value
				)
			) );

		}

	} // snapchat_username()


	/**
	 * 
	 *	Shipping Information 
	 *
	 */


	/**
	 *  Output default national shipping fee field 
	 * 
	 * @since    1.2.0
	 */
	public static function shipping_fee_national( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'national', $shipping_details ) ) ? $shipping_details[ 'national' ] : ''; 

		// Shipping Fee 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_national_fee', array(  
			'id' 				=> '_wcv_shipping_fee_national', 
			'label' 			=> __( 'Default National Shipping Fee', 'wcvendors-pro' ), 
			'placeholder' 		=> __( '0', 'wcvendors-pro' ), 
			'desc_tip' 			=> 'true', 
			'description' 		=> __( 'The default shipping fee within your country, this can be overridden on a per product basis.', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'class' 			=> 'wcv-disable-national-input',
			'wrapper_start' 	=> '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">', 
			'wrapper_end' 		=>  '</div>', 
			'value'				=> $value, 
			'custom_attributes' => array( 
		 			'data-rules' => 'decimal', 
		 			'data-error' => __( 'This should be a number.', 'wcvendors-pro' )

		 		)
			)
		) );

	} // shipping_fee_national()

	/**
	 *  Output default national shipping qty override field 
	 * 
	 * @since    1.2.0
	 */
	public static function shipping_fee_national_qty( $shipping_details ) {

		$qty_value = ( is_array( $shipping_details ) && array_key_exists( 'national_qty_override', $shipping_details ) ) ? $shipping_details[ 'national_qty_override' ] : 0; 

		// QTY Override 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_national_qty', array( 
					'id' 				=> '_wcv_shipping_fee_national_qty', 
					'label' 			=> __( 'Charge once per product for national shipping, even if more than one is purchased.', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox', 
					'class' 			=> 'wcv-disable-national-input',
					'wrapper_start' 	=> '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">', 
					'wrapper_end' 		=>  '</div>', 
					'value'				=> $qty_value
					) 
		) );

	} // shipping_fee_national_qty() 

	/**
	 *  Output default national shipping qty override field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_national_free( $shipping_details ) {

		$free = ( is_array( $shipping_details ) && array_key_exists( 'national_free', $shipping_details ) ) ? $shipping_details[ 'national_free' ] : 0; 

		// QTY Override 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_national_free', array( 
					'id' 				=> '_wcv_shipping_fee_national_free', 
					'label' 			=> __( 'Free national shipping.', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox', 
					'class' 			=> 'wcv-disable-national-input',
					'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">', 
					'wrapper_end' 	=>  '</div>', 
					'value'				=> $free
					) 
		) );

	} // shipping_fee_national_qty() 

	/**
	 *  Output default national shipping qty override field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_national_disable( $shipping_details ) {

		$disable = ( is_array( $shipping_details ) && array_key_exists( 'national_disable', $shipping_details ) ) ? $shipping_details[ 'national_disable' ] : 0; 

		// QTY Override 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_national_disable', array( 
					'id' 				=> '_wcv_shipping_fee_national_disable', 
					'label' 			=> __( 'Disable national shipping.', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox', 
					'wrapper_start' 	=> '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">', 
					'wrapper_end' 		=> '</div>', 
					'value'				=> $disable
					) 
		) );

	} // shipping_fee_national_qty() 

	/**
	 *  Output default international shipping fee field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_international( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'international', $shipping_details ) ) ? $shipping_details[ 'international' ] : ''; 

		// Shipping Fee 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_international_fee', array(  
			'id' 				=> '_wcv_shipping_fee_international', 
			'label' 			=> __( 'Default International Shipping Fee', 'wcvendors-pro' ), 
			'placeholder' 		=> __( '0', 'wcvendors-pro' ), 
			'desc_tip' 			=> 'true', 
			'description' 		=> __( 'The default shipping fee outside your country, this can be overridden on a per product basis. ', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'class' 			=> 'wcv-disable-international-input',
			'wrapper_start' 	=> '<div class="all-50 small-100">', 
			'wrapper_end' 		=> '</div></div>', 
			'value'				=> $value, 
			'custom_attributes' => array( 
		 			'data-rules' => 'decimal', 
		 			'data-error' => __( 'This should be a number.', 'wcvendors-pro' )

		 		)
			)
		) );


	

	} // shipping_fee_international()

	/**
	 *  Output default international shipping fee field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_international_qty( $shipping_details ) {

		$qty_value = ( is_array( $shipping_details ) && array_key_exists( 'international_qty_override', $shipping_details ) ) ? $shipping_details[ 'international_qty_override' ] : 0; 

		// QTY Override 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_international_qty', array( 
					'id' 				=> '_wcv_shipping_fee_international_qty', 
					'label' 			=> __( 'Charge once per product for international shipping, even if more than one is purchased.', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox', 
					'class' 			=> 'wcv-disable-international-input',
					'wrapper_start' 	=> '<div class="all-50 small-100">', 
					'wrapper_end' 		=> '</div></div>', 
					'value'				=> $qty_value
					) 
		) );


	} // shipping_fee_international_qty() 


	/**
	 *  Output default international shipping free field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_international_free( $shipping_details ) {

		$free = ( is_array( $shipping_details ) && array_key_exists( 'international_free', $shipping_details ) ) ? $shipping_details[ 'international_free' ] : 0; 

		// QTY Override 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_international_free', array( 
					'id' 				=> '_wcv_shipping_fee_international_free', 
					'label' 			=> __( 'Free international shipping.', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox', 
					'class' 			=> 'wcv-disable-international-input',
					'wrapper_start' 	=> '<div class="all-50 small-100">', 
					'wrapper_end' 		=>  '</div></div>', 
					'value'				=> $free
					) 
		) );


	} // shipping_fee_international_free() 


	/**
	 *  Output default international shipping free field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_international_disable( $shipping_details ) {

		$disable = ( is_array( $shipping_details ) && array_key_exists( 'international_disable', $shipping_details ) ) ? $shipping_details[ 'international_disable' ] : 0; 

		// QTY Override 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_international_disable', array( 
					'id' 				=> '_wcv_shipping_fee_international_disable', 
					'label' 			=> __( 'Disable international shipping.', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox', 
					'wrapper_start' 	=> '<div class="all-50 small-100">', 
					'wrapper_end' 		=>  '</div></div>', 
					'value'				=> $disable
					) 
		) );


	} // shipping_fee_international_free() 



	/**
	 *  Output the shipping rate depending on the admin settings 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_rates( ){ 

		$shipping_settings 		= get_option( 'woocommerce_wcv_pro_vendor_shipping_settings' ); 
		$store_shipping_type	= get_user_meta( get_current_user_id(), '_wcv_shipping_type', true ); 
		$shipping_type 			= ( $store_shipping_type != '' ) ? $store_shipping_type : $shipping_settings[ 'shipping_system' ]; 
		$shipping_details 		= get_user_meta( get_current_user_id(), '_wcv_shipping', true );


		if ( $shipping_type == 'flat' ){ 

			self::shipping_fee_national( $shipping_details );
			self::shipping_fee_international( $shipping_details );
			self::shipping_fee_national_free( $shipping_details );
			self::shipping_fee_international_free( $shipping_details );
			self::shipping_fee_national_qty( $shipping_details );
			self::shipping_fee_international_qty( $shipping_details );
			self::shipping_fee_national_disable( $shipping_details );
			self::shipping_fee_international_disable( $shipping_details );

		} else { 

			self::shipping_rate_table(); 

		}

		// Backwards compatability 
		// This has been moved into the store-settings template for 1.3.7 and above. 
		if ( version_compare( WCV_PRO_VERSION, '1.3.7', '<' ) ){ 		

			self::product_handling_fee( $shipping_details );
			self::shipping_policy( $shipping_details );
			self::return_policy( $shipping_details );
			self::shipping_from( $shipping_details );
			self::shipping_address( $shipping_details );
			
		}

		
	} // shipping_rates() 

	/**
	 *  Output default product handling fee field 
	 * 
	 * @since    1.0.0
	 */
	public static function product_handling_fee( $shipping_details ) {

		$shipping  = ( self::$form_type ) ? self::$signup_shipping : self::$settings_shipping; 

		if ( ! $shipping[ 'handling_fee' ] ) { 

			$value = ( is_array( $shipping_details ) && array_key_exists( 'product_handling_fee', $shipping_details ) ) ? $shipping_details[ 'product_handling_fee' ] : ''; 

			// Product handling Fee 
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_product_fee', array(  
				'id' 				=> '_wcv_shipping_product_handling_fee', 
				'label' 			=> __( 'Product handling fee', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'Leave empty to disable', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'The product handling fee, this can be overridden on a per product basis. Amount (5.00) or Percentage (5%).', 'wcvendors-pro' ), 
				'type' 				=> 'text', 
				'value'				=> $value
				)
			) );

		} 

	} // product_handling_fee()

	/**
	 *  Output default order handling fee field 
	 * 
	 * @since    1.0.0
	 */
	public static function order_handling_fee( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'order_handling_fee', $shipping_details ) ) ? $shipping_details[ 'order_handling_fee' ] : ''; 

		// Order handling fee 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_order_fee', array(  
			'id' 				=> '_wcv_shipping_order_handling_fee', 
			'label' 			=> __( 'Order handling fee', 'wcvendors-pro' ), 
			'placeholder' 		=> __( '0', 'wcvendors-pro' ), 
			'desc_tip' 			=> 'true', 
			'description' 		=> __( 'The order handling fee for all products in the order. Amount (5.00) or Percentage (5%)', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $value, 
			'custom_attributes' => array( 
		 			'data-rules' => 'decimal', 
		 			'data-error' => __( 'This should be a number.', 'wcvendors-pro' )

		 		)
			)
		) );

	} // order_handling_fee()

	/**
	 *  Output the max order spend for shipping 
	 * 
	 * @since    1.0.0
	 */
	public static function order_min_charge( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'min_charge', $shipping_details ) ) ? $shipping_details[ 'min_charge' ] : ''; 

		// Order min Spend
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_min_charge', array(  
			'id' 				=> '_wcv_shipping_min_charge', 
			'label' 			=> __( 'Minimum shipping charged per order', 'wcvendors-pro' ), 
			'placeholder' 		=> __( '0', 'wcvendors-pro' ), 
			'desc_tip' 			=> 'true', 
			'description' 		=> __( 'The minimum shipping fee charged for an order.', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $value, 
			'wrapper_start' 	=> '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">', 
			'wrapper_end' 		=> '</div>', 
			'custom_attributes' => array( 
		 			'data-rules' => 'decimal', 
		 			'data-error' => __( 'This should be a number.', 'wcvendors-pro' )

		 		)
			)
		) );

	} // min_charge()

	/**
	 *  Output the max order spend for shipping 
	 * 
	 * @since    1.0.0
	 */
	public static function order_max_charge( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'max_charge', $shipping_details ) ) ? $shipping_details[ 'max_charge' ] : ''; 

		// Order min spend
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_max_charge', array(  
			'id' 				=> '_wcv_shipping_max_charge', 
			'label' 			=> __( 'Maximum shipping charged per order', 'wcvendors-pro' ), 
			'placeholder' 		=> __( '0', 'wcvendors-pro' ), 
			'desc_tip' 			=> 'true', 
			'description' 		=> __( 'The maximum shipping fee charged for an order.', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $value, 
			'wrapper_start' => '<div class="all-50 small-100">', 
			'wrapper_end' 	=>  '</div></div>', 
			'custom_attributes' => array( 
		 			'data-rules' => 'decimal', 
		 			'data-error' => __( 'This should be a number.', 'wcvendors-pro' )

		 		)
			)
		) );

	} // order_max_spend()



	/**
	 *  Output the max order spend for shipping 
	 * 
	 * @since    1.0.0
	 */
	public static function free_shipping_order( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'free_shipping_order', $shipping_details ) ) ? $shipping_details[ 'free_shipping_order' ] : ''; 

		// Order min Spend
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_free_shipping_order', array(  
			'id' 				=> '_wcv_shipping_free_shipping_order', 
			'label' 			=> __( 'Free shipping order', 'wcvendors-pro' ), 
			'placeholder' 		=> __( '0', 'wcvendors-pro' ), 
			'desc_tip' 			=> 'true', 
			'description' 		=> __( 'Free shipping for order spends over this amount. This will override the max shipping charge above.', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $value
			)
		) );

	} // free_shipping_order()

	/**
	 *  Output the max order spend for shipping 
	 * 
	 * @since    1.0.0
	 */
	public static function free_shipping_product( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'free_shipping_product', $shipping_details ) ) ? $shipping_details[ 'free_shipping_product' ] : ''; 

		// Order min Spend
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_free_shipping_product', array(  
			'id' 				=> '_wcv_shipping_free_shipping_product', 
			'label' 			=> __( 'Free shipping product', 'wcvendors-pro' ), 
			'placeholder' 		=> __( '0', 'wcvendors-pro' ), 
			'desc_tip' 			=> 'true', 
			'description' 		=> __( 'Free shipping if the spend per product is over this amount. This will override the max shipping charge above.', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $value
			)
		) );

	} // free_shipping_product()


	/**
	 *  Output the max order spend for shipping 
	 * 
	 * @since    1.0.0
	 */
	public static function product_max_charge( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'max_charge_product', $shipping_details ) ) ? $shipping_details[ 'max_charge_product' ] : ''; 

		// Order min spend
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_shipping_max_charge_product', array(  
			'id' 				=> '_wcv_shipping_max_charge_product', 
			'label' 			=> __( 'Maximum product charge', 'wcvendors-pro' ), 
			'placeholder' 		=> __( '0', 'wcvendors-pro' ), 
			'desc_tip' 			=> 'true', 
			'description' 		=> __( 'The maximum shipping charged per product no matter the quantity.', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $value, 
			'custom_attributes' => array( 
		 			'data-rules' => 'decimal', 
		 			'data-error' => __( 'This should be a number.', 'wcvendors-pro' )

		 		)
			)
		) );

	} // order_max_spend()


	/**
	 *  Output shipping policy field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_policy( $shipping_details ) {

		$shipping  = ( self::$form_type ) ? self::$signup_shipping : self::$settings_shipping; 

		if ( ! $shipping[ 'shipping_policy' ] ) { 

			$value = ( is_array( $shipping_details ) && array_key_exists( 'shipping_policy', $shipping_details ) ) ? $shipping_details[ 'shipping_policy' ] : ''; 

			// Shipping Policy
			WCVendors_Pro_Form_Helper::textarea( apply_filters( 'wcv_vendor_shipping_policy', array( 
			 		'id' 		=> '_wcv_shipping_policy', 
			 		'label'	 	=> __( 'Shipping Policy', 'wcvendors-pro' ), 
			 		'value' 	=> $value  
			 		) )
			 	);
		} 

	} // shipping_policy()

	/**
	 *  Output shipping policy field 
	 * 
	 * @since    1.0.0
	 */
	public static function return_policy( $shipping_details ) {

		$shipping  = ( self::$form_type ) ? self::$signup_shipping : self::$settings_shipping; 

		if ( ! $shipping[ 'return_policy' ] ) { 

			$value = ( is_array( $shipping_details ) && array_key_exists( 'return_policy', $shipping_details ) ) ? $shipping_details[ 'return_policy' ] : ''; 

			// Return Policy
			WCVendors_Pro_Form_Helper::textarea( apply_filters( 'wcv_vendor_shipping_return_policy', array( 
			 		'id' 		=> '_wcv_shipping_return_policy', 
			 		'label'	 	=> __( 'Return Policy', 'wcvendors-pro' ), 
			 		'value' 	=> $value  
			 		) )
			 	);

		} 

	} // return_policy()

	/**
	 *  Output shipping type
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function shipping_from( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'shipping_from', $shipping_details ) ) ? $shipping_details[ 'shipping_from' ] : ''; 

		// shipping from
		WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_vendor_shipping_from', array( 
			'id' 				=> '_wcv_shipping_from', 
			'class'				=> 'select2',
			'label'	 			=> __( 'Shipping from', 'wcvendors-pro' ), 
			'desc_tip' 			=> 'true', 
			'description' 		=> __( 'Where products will be shipped from.', 'wcvendors-pro' ), 
			'wrapper_start' 	=> '<div class="all-100">',
			'wrapper_end' 		=> '</div>', 
			'value'				=> $value, 
			'options' 			=> array(
				'store_address' => __( 'Store Address', 'wcvendors-pro' ),
				'other'       	=> __( 'Other', 'wcvendors-pro' ),
				)	 
			) )
		);

	} // shipping_type()

	/**
	 * Output a formatted store address
	 *
	 * @since      1.0.0
	 * @param      array     $shipping_details the shipping details meta 
	 */
	public static function shipping_address( $shipping_details ) { 

		$value = ( is_array( $shipping_details ) && array_key_exists( 'shipping_address', $shipping_details ) ) ? $shipping_details[ 'shipping_address' ] : ''; 

		$address1 	= ( is_array( $value ) && array_key_exists( 'address1', $value ) ) ? $value[ 'address1' ] : ''; 
		$address2 	= ( is_array( $value ) && array_key_exists( 'address2', $value ) ) ? $value[ 'address2' ] : '';  
		$city	 	= ( is_array( $value ) && array_key_exists( 'city', $value ) ) ? $value[ 'city' ] : ''; 
		$state	 	= ( is_array( $value ) && array_key_exists( 'state', $value ) ) ? $value[ 'state' ] : ''; 
		$country	= ( is_array( $value ) && array_key_exists( 'country', $value ) ) ? $value[ 'country' ] : ''; 
		$postcode	= ( is_array( $value ) && array_key_exists( 'postcode', $value ) ) ? $value[ 'postcode' ] : ''; 

		include( apply_filters( 'wcvendors_pro_store_form_shipping_address_path', 'wcvendors-pro-shipping-address.php' ) );

	} 

	/**
	 *  Output shipping rate table
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_rate_table(  ) {

		$helper_text = apply_filters( 'wcv_shipping_rate_table_msg', __( 'Countries must use the international standard for two letter country codes. eg. AU for Australia.', 'wcvendors-pro' ) );

		$shipping_rates = get_user_meta( get_current_user_id(), '_wcv_shipping_rates', true ); 

		include_once( apply_filters( 'wcvendors_pro_store_form_shipping_rate_table_path', 'partials/wcvendors-pro-shipping-table.php' ) );

	} // download_files()


	/**
	 *  Output vacation mode 
	 * 
	 * @since    1.3.0
	 */
	public static function vacation_mode( ) { 

		if ( ! self::$settings_store[ 'vacation_mode' ] ) { 

			$vacation_mode 	= get_user_meta( get_current_user_id(), '_wcv_vacation_mode', 	true ); 
			$vacation_msg 	= get_user_meta( get_current_user_id(), '_wcv_vacation_mode_msg', 	true ); 

			// Vacation Mode 
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vacation_mode', array( 
						'id' 				=> '_wcv_vacation_mode', 
						'label' 			=> __( 'Enable Vacation Mode', 'wcvendors-pro' ), 
						'type' 				=> 'checkbox', 
						'class' 			=> 'wcv-vacaction-mode',
						'wrapper_start' 	=> '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100">', 
						'wrapper_end' 		=> '</div>', 
						'value'				=> $vacation_mode
						) 
			) );


			WCVendors_Pro_Form_Helper::textarea( apply_filters( 'wcv_vacation_mode_msg', array( 
				 		'id' 				=> '_wcv_vacation_mode_msg', 
				 		'label'	 			=> __( 'Vacation Message', 'wcvendors-pro' ), 
				 		'class' 			=> 'wcv-vacaction-mode-msg',
				 		'wrapper_start' 	=> '<div class="all-100 wcv-vacation-mode-msg-wrapper">', 
						'wrapper_end' 		=> '</div></div>', 
				 		'value' 	=> $vacation_msg  
				 		) )
				 	);
		} 


	} // vaction_mode()


	/**
	 * Output Vendor terms on the signup page 
	 * @since 1.3.2 
	*/
	public static function vendor_terms( ) { 

		$terms_page = WC_Vendors::$pv_options->get_option( 'terms_to_apply_page' ); 

		if ( ( $terms_page )  &&  ( ! isset( $_GET['terms'] ) ) ){ 

			// Vendor Terms checkbox 
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_vendor_terms_args', array( 
						'id' 				=> '_wcv_agree_to_terms', 
						'label' 			=> sprintf( __( 'I have read and accepted the <a href="%s" target="_blank">terms and conditions</a>', 'wcvendors-pro' ), get_permalink( $terms_page ) ), 
						'type' 				=> 'checkbox', 
						'class' 			=> '',
						'wrapper_start' 	=> '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-100">', 
						'wrapper_end' 		=>  '</div>', 
						'value'				=> 1, 
						'custom_attributes' => array(
							'data-rules' 	=> 'required', 
							'data-error'	=> __('You must agree to the terms and conditions to apply to be a vendor.', 'wcvendors-pro' ), 
							),
						)
					) 
			);

		}

	} // vendor_terms() 
	

}