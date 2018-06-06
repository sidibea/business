<?php
/**
 * The WCVendors Pro Coupon Form class
 *
 * This is the order form class
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/forms
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Coupon_Form {

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
	public static function form_data( $button_text, $coupon_id ) {

		wp_nonce_field( 'wcv-save-coupon', 'wcv_save_coupon' );	

		//  Coupon ID if it already exists 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_post_id', array( 
				'post_id'		=> $coupon_id, 
				'type'			=> 'hidden', 
				'id' 			=> '_wcv_coupon_post_id', 
				'value'			=> $coupon_id
				) )
			);

		self::save_button( $button_text ); 
	} 

	/**
	 *  Output coupon title
	 * 
	 * @since    1.0.0
	 */
	public static function coupon_code( $coupon_code ) {

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $coupon_code = array_key_exists( '_wcv_coupon_post_title', $_POST ) ? $_POST[ '_wcv_coupon_post_title' ] : '';

		// Coupon Code 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_post_title', array(  
			'id' 				=> '_wcv_coupon_post_title', 
			'label' 			=> __( 'Coupon Code', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'Coupon Code', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $coupon_code
			)
		) );

	} // coupon_code()

	/**
	 *  Output coupon description
	 * 
	 * @since    1.0.0
	 */
	public static function coupon_description( $coupon_description ) {

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $coupon_description = array_key_exists( '_wcv_coupon_post_excerpt', $_POST ) ? $_POST[ '_wcv_coupon_post_excerpt' ] : '';

		// Coupon Description
		WCVendors_Pro_Form_Helper::textarea( apply_filters( 'wcv_coupon_post_content', array(  
			'id' 				=> '_wcv_coupon_post_excerpt', 
			'label' 			=> __( 'Coupon Description', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'Coupon Description', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $coupon_description
			)
		) );

	} // coupon_description()

	/**
	 *  Output discount type drop down 
	 * 
	 * @since    1.0.0
	 */
	public static function discount_type( $discount_type ) { 

		if ( is_array( $discount_type ) ) { 
			$discount_type = reset( $discount_type ); 
		} else { 
			$discount_type = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $discount_type = array_key_exists( '_wcv_coupon_post_meta_discount_type', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_discount_type' ] : '';
		
		// Discount Type
		WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_coupon_discount_type', array( 
			'id' 				=> '_wcv_coupon_post_meta_discount_type', 
			'label'	 			=> __( 'Discount Type', 'wcvendors-pro' ), 
			'value'				=> $discount_type, 
			'placeholder'		=> __( 'Discount Type', 'wcvendors-pro' ), 
			'options' 			=> array(
					'fixed_product' 	=> __( 'Product discount', 'wcvendors-pro' ),
					'percent_product'   => sprintf( __( 'Product %s discount', 'wcvendors-pro' ), '%'),
				)	 
			) )
		);

	} // discount_type() 

	/**
	 *  Output appl to all products checkbox
	 * 
	 * @since    1.0.0
	 */
	public static function apply_to_all_products( $all_products ) {

		if ( is_array( $all_products ) ) { 
			$all_products = ( reset( $all_products ) ); 
		} else { 
			$all_products = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $all_products = array_key_exists( '_wcv_coupon_post_meta_apply_to_all_products', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_apply_to_all_products' ] : '';

		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_apply_to_all_products', array( 
						'id' 				=> '_wcv_coupon_post_meta_apply_to_all_products', 
						'label' 			=> __( 'Apply to all my products', 'wcvendors-pro' ), 
						'type' 				=> 'checkbox',
						'value' 			=> $all_products, 
						'desc_tip'			=> true, 
						'description'		=> __('Check this box if the coupon applies to all your products.', 'wcvendors-pro'), 
						) )
					);


	} // apply_to_all_products()

	/**
	 *  Output coupon amount
	 * 
	 * @since    1.0.0
	 */
	public static function coupon_amount( $coupon_amount ) {

		if ( is_array( $coupon_amount ) ) { 
			$coupon_amount = reset( $coupon_amount ); 
		} else { 
			$coupon_amount = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $coupon_amount = array_key_exists( '_wcv_coupon_post_meta_coupon_amount', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_coupon_amount' ] : '';

		// Coupon Amount
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_amount', array(  
			'id' 				=> '_wcv_coupon_post_meta_coupon_amount', 
			'label' 			=> __( 'Coupon Amount', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'Coupon Amount', 'wcvendors-pro' ), 
			'type' 				=> 'decimal', 
			'value'				=> $coupon_amount, 
			)
		) );

	} // coupon_code()


	/**
	 *  Output Allow free shipping checkbox
	 * 
	 * @since    1.0.0
	 */
	public static function free_shipping( $free_shipping ) {

		if ( is_array( $free_shipping ) ) { 
			$free_shipping = ( reset( $free_shipping ) ); 
		} else { 
			$free_shipping = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $free_shipping = array_key_exists( '_wcv_coupon_post_meta_free_shipping', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_free_shipping' ] : '';

		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_free_shipping', array( 
						'id' 				=> '_wcv_coupon_post_meta_free_shipping', 
						'label' 			=> __( 'Allow Free Shipping', 'wcvendors-pro' ), 
						'type' 				=> 'checkbox',
						'value' 			=> $free_shipping, 
						'desc_tip'			=> true, 
						'description'		=> __('Check this box if the coupon grants free shipping. The free shipping method must be enabled and be set to require "a valid free shipping coupon" (see the "Free Shipping Requires" setting).', 'wcvendors-pro'), 
						) )
					);


	} // free_shipping()


	/**
	 *  Output coupon expiry date 
	 * 
	 * @since    1.0.0
	 */
	public static function expiry_date( $expiry_date ) {

		if ( is_array( $expiry_date ) ) { 
			$expiry_date = reset( $expiry_date ); 
		} else { 
			$expiry_date = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $expiry_date = array_key_exists( '_wcv_coupon_post_meta_expiry_date', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_expiry_date' ] : '';

		// Coupon sale date 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_sale_price_date_from', array( 
			'id' 			=> '_wcv_coupon_post_meta_expiry_date', 
			'label' 		=> __( 'Coupon Expiry Date', 'wcvendors-pro' ), 
			'class'			=> 'wcv-datepicker', 
			'value' 		=> $expiry_date, 
			'placeholder'	=> 'YYYY-MM-DD',  
			'custom_attributes' => array(
				'maxlenth' 	=> '10', 
				'pattern' 	=> '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])'
				),
			) )
		);

	} // expiry_date() 


	/**
	 *  Output min spend
	 * 
	 * @since    1.0.0
	 */
	public static function minimum_amount( $minimum_amount ) {

		if ( is_array( $minimum_amount ) ) { 
			$minimum_amount = reset( $minimum_amount ); 
		} else { 
			$minimum_amount = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $minimum_amount = array_key_exists( '_wcv_coupon_post_meta_minimum_amount', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_minimum_amount' ] : '';

		// Minimum Spend
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_minimum_amount', array(  
			'id' 				=> '_wcv_coupon_post_meta_minimum_amount', 
			'label' 			=> __( 'Minimum Spend', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'No Minimum', 'wcvendors-pro' ), 
			'type' 				=> 'number', 
			'value'				=> $minimum_amount
			)
		) );

	} // minimum_amount()

	/**
	 *  Output max spend
	 * 
	 * @since    1.0.0
	 */
	public static function maximum_amount( $maximum_amount ) {

		if ( is_array( $maximum_amount ) ) { 
			$maximum_amount = reset( $maximum_amount ); 
		} else { 
			$maximum_amount = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $maximum_amount = array_key_exists( '_wcv_coupon_post_meta_maximum_amount', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_maximum_amount' ] : '';

		// Minimum Spend
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_maximum_amount', array(  
			'id' 				=> '_wcv_coupon_post_meta_maximum_amount', 
			'label' 			=> __( 'Maximum Spend', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'No Maximum', 'wcvendors-pro' ), 
			'type' 				=> 'number', 
			'value'				=> $maximum_amount
			)
		) );

	} // maximum_amount()


	/**
	 *  Output Individual use only
	 * 
	 * @since    1.0.0
	 */
	public static function individual_use( $individual_use ) {

		if ( is_array( $individual_use ) ) { 
			$individual_use = reset( $individual_use ); 
		} else { 
			$individual_use = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $individual_use = array_key_exists( '_wcv_coupon_post_meta_individual_use', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_individual_use' ] : '';

		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_individual_use', array( 
						'id' 				=> '_wcv_coupon_post_meta_individual_use', 
						'label' 			=> __( 'Individual use only', 'wcvendors-pro' ), 
						'type' 				=> 'checkbox', 
						'value'				=> $individual_use, 
						'wrapper_start' 	=> '<div class="all-50">',
						'wrapper_end' 		=> '</div>', 
						'desc_tip'			=> true, 
						'description'		=> __('Check this box if the coupon cannot be used in conjunction with other coupons.', 'wcvendors-pro'), 
						) )
					);


	} // individual_use()

	/**
	 *  Output exclude sale items
	 * 
	 * @since    1.0.0
	 */
	public static function exclude_sale_items( $exclude_sale_items ) {

		if ( is_array( $exclude_sale_items ) ) { 
			$exclude_sale_items = reset( $exclude_sale_items ); 
		} else { 
			$exclude_sale_items = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $exclude_sale_items = array_key_exists( '_wcv_coupon_post_meta_exclude_sale_items', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_exclude_sale_items' ] : '';

		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_exclude_sale_items', array( 
						'id' 				=> '_wcv_coupon_post_meta_exclude_sale_items', 
						'label' 			=> __( 'Exclude sale items', 'wcvendors-pro' ), 
						'type' 				=> 'checkbox', 
						'value'				=> $exclude_sale_items, 
						'wrapper_start' 	=> '<div class="all-50">',
						'wrapper_end' 		=> '</div>', 
						'desc_tip'			=> true, 
						'description'		=> __('Check this box if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are no sale items in the cart.', 'wcvendors-pro'), 
						) )
					);


	} // exclude_sale_items()



	/**
	 *  Output included products 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function products( $product_ids ) {
		
		$return_products_ids    = array();

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) { 
			$product_ids = array_key_exists( '_wcv_coupon_post_meta_product_ids', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_product_ids' ] : '';
		} 

		if ( is_array( $product_ids ) ) { 
			$product_ids = array_filter( array_map( 'absint', explode( ',', reset( $product_ids ) ) ) );

			if ( !empty($product_ids ) ) { 
				foreach ( $product_ids as $product_id ) {
					$product = wc_get_product( $product_id );
					if ( is_object( $product ) ) {
						$return_products_ids[ $product_id ] = wp_kses_post( html_entity_decode( $product->get_formatted_name() ) );
					}
				}
			} 

		} 

		// Output product searcher 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_product_ids', array( 
				'id' 					=> '_wcv_coupon_post_meta_product_ids', 
				'label' 				=> __( 'Products', 'wcvendors-pro' ), 
				'value' 				=> implode( ',', array_keys( $return_products_ids ) ), 
				'style'					=> 'width: 100%;', 
				'class'					=> 'wc-product-search', 
				'type'					=> 'hidden', 
				'show_label'			=> true, 
				'custom_attributes' 	=> array(
						'data-placeholder' 	=> __( 'Search for a product &hellip;', 'wcvendors-pro' ), 
						'data-action'		=> 'wcv_json_search_products', 
						'data-multiple' 	=> 'true', 
						'data-selected'		=> esc_attr( json_encode( $return_products_ids ) ) 
					),
			) )
		);

	}  // products() 


	/**
	 *  Output the exclude products field 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function exclude_products( $exclude_products ) {

		// Find what the meta key this is stored in. 
		$return_excluded_product_ids    = array();

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) { 
			$exclude_products = array_key_exists( '_wcv_coupon_post_meta_exclude_product_ids', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_exclude_product_ids' ] : '';
		} 

		if ( is_array( $exclude_products ) ) { 
			$exclude_products = array_filter( array_map( 'absint', explode( ',', reset( $exclude_products ) ) ) );

			if (!empty( $exclude_products ) ) { 
				foreach ( $exclude_products as $product_id ) {
					
					$product = wc_get_product( $product_id );

					if ( is_object( $product ) ) {
						$return_excluded_product_ids[ $product_id ] = wp_kses_post( html_entity_decode( $product->get_formatted_name() ) );
					}
				}
			} 

		}  

		// Output product searcher 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_exlude_product_ids', array( 
				'id' 					=> '_wcv_coupon_post_meta_exclude_product_ids', 
				'label' 				=> __( 'Exclude Products', 'wcvendors-pro' ), 
				'value' 				=> implode( ',', array_keys( $return_excluded_product_ids ) ), 
				'style'					=> 'width: 100%;', 
				'class'					=> 'wc-product-search', 
				'type'					=> 'hidden', 
				'show_label'			=> true, 
				'custom_attributes' 	=> array(
						'data-placeholder' 	=> __( 'Search for a product &hellip;', 'wcvendors-pro' ), 
						'data-action'		=> 'wcv_json_search_products', 
						'data-multiple' 	=> 'true', 
						'data-selected'		=> esc_attr( json_encode( $return_excluded_product_ids ) ) 
					),
			) )
		);

	}  // exclude_products() 


	/**
	 *  Output email addresses
	 * 
	 * @since    1.0.0
	 */
	public static function email_addresses( $email_addresses ) {

		if ( is_array( $email_addresses ) ) { 
			$email_addresses = reset( $email_addresses ); 
		} else { 
			$email_addresses = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $email_addresses = array_key_exists( '_wcv_coupon_post_meta_email_addresses', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_email_addresses' ] : '';

		// email addresses 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_email_addresses', array(  
			'id' 				=> '_wcv_coupon_post_meta_email_addresses', 
			'label' 			=> __( 'Email Restrictions', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'No restrictions', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $email_addresses, 
			'desc_tip'			=> true, 
			'description'		=> __('List of allowed emails to check against the customer&#039;s billing email when an order is placed. Separate email addresses with commas.', 'wcvendors-pro'), 
			)
		) );

	} // email_addresses()


	/**
	 *  Output usage_limit_per_user
	 * 
	 * @since    1.0.0
	 */
	public static function usage_limit( $usage_limit ) {

		if ( is_array( $usage_limit ) ) { 
			$usage_limit = reset( $usage_limit ); 
		} else { 
			$usage_limit = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $usage_limit = array_key_exists( '_wcv_coupon_post_meta_usage_limit', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_usage_limit' ] : '';

		// usage_limit
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_usage_limit', array(  
			'id' 				=> '_wcv_coupon_post_meta_usage_limit', 
			'label' 			=> __( 'Usage limit per coupon', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'Unlimited usage', 'wcvendors-pro' ), 
			'type' 				=> 'number', 
			'value'				=> $usage_limit, 
			)
		) );

	} // usage_limit()

	/**
	 *  Output usage_limit_per_user
	 * 
	 * @since    1.0.0
	 */
	public static function limit_usage_to_x_items( $limit_usage_to_x_items ) {

		if ( is_array( $limit_usage_to_x_items ) ) { 
			$limit_usage_to_x_items = reset( $limit_usage_to_x_items ); 
		} else { 
			$limit_usage_to_x_items = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $limit_usage_to_x_items = array_key_exists( '_wcv_coupon_post_meta_limit_usage_to_x_items', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_limit_usage_to_x_items' ] : '';

		// usage_limit
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_limit_usage_to_x_items', array(  
			'id' 				=> '_wcv_coupon_post_meta_limit_usage_to_x_items', 
			'label' 			=> __( 'Usage limit per coupon', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'Unlimited usage', 'wcvendors-pro' ), 
			'type' 				=> 'number', 
			'value'				=> $limit_usage_to_x_items, 
			)
		) );

	} // limit_usage_to_x_items()

	/**
	 *  Output usage_limit_per_user
	 * 
	 * @since    1.0.0
	 */
	public static function usage_limit_per_user( $usage_limit_per_user ) {

		if ( is_array( $usage_limit_per_user ) ) { 
			$usage_limit_per_user = reset( $usage_limit_per_user ); 
		} else { 
			$usage_limit_per_user = ''; 
		}

		if ( isset( $_POST[ 'wcv_save_coupon' ] ) ) $usage_limit_per_user = array_key_exists( '_wcv_coupon_post_meta_usage_limit_per_user', $_POST ) ? $_POST[ '_wcv_coupon_post_meta_usage_limit_per_user' ] : '';

		// usage_limit_per_user
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_coupon_usage_limit_per_user', array(  
			'id' 				=> '_wcv_coupon_post_meta_usage_limit_per_user', 
			'label' 			=> __( 'Usage limit per user', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'Unlimited usage', 'wcvendors-pro' ), 
			'type' 				=> 'number', 
			'value'				=> $usage_limit_per_user, 
			)
		) );

	} // usage_limit_per_user()

	/**
	 *  Output save button 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function save_button( $button_text ) {

		WCVendors_Pro_Form_helper::submit( apply_filters( 'wcv_coupon_save_button', array( 
		 	'id' 		=> 'save_button', 
		 	'value' 	=> $button_text, 
		 	'class'		=> ''
		 	) )
		 ); 

	} // save_button()



}