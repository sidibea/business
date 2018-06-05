<?php
/**
 * The WCVendors Pro Vendor Controller class
 *
 * This is the vendor controller class for all vendor related work
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Vendor_Controller {

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
		$this->base_url			= plugin_dir_url( __FILE__ ); 
		$this->suffix		 	= $this->debug ? '' : '.min';
	}

	/**
	 *  Get the store id of the vendor - DEPRECIATED 
	 * 
	 * @since    1.2.0
	 * @param 	 int 	$vendor_id  vendor id for store id
	 * @deprecated 1.2.0
	 */
	public static function get_vendor_store_id( $vendor_id ) {

		$args = array(
		    'author'        => $vendor_id, 
		    'orderby'       => 'post_date',
		    'post_type'		=> 'vendor_store', 
		    'post_status'	=> array( 'publish', 'draft' ),  
    	);

    	$stores = get_posts( $args ); 

    	if ( !empty( $stores ) ) { 
			// We have a store and we need to return it. 
			$store = reset( $stores ); 
			return $store->ID; 
		} else { 
			return null; 
		}
    	
	} //get_vendor_store_id()

	/**
	 *  Get the login_name of the vendor
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$vendor_id  vendor id for store id
	 * @param 	 string 	$meta_key   user meta key 
	 */
	public static function get_vendor_detail( $vendor_id, $meta_key ) {

		$vendor = get_userdata( $vendor_id ); 

		return $vendor->{$meta_key}; 

	}

	/**
	 *  Get all orders for a vendor 
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$vendor_id   vendor id for store id
	 * @param 	 array 		$date_range  date range to search for 
	 * @todo 	 Deprecate this function and update orders controller 
	 */
	public static function get_orders( $vendor_id, $date_range = null ) {

		$start_date = strtotime( date( 'Ymd', strtotime( date( 'Ym', current_time( 'timestamp' ) ) . '01' ) ) );
		$end_date	= strtotime( date( 'Ymd', current_time( 'timestamp' ) ) );

		global $wpdb; 

		$sql = "
			SELECT id, DISTINCT( order_id ), product_id, vendor_id, total_shipping, total_due, qty, tax, status, time
			FROM {$wpdb->prefix}pv_commission as order_items
			WHERE   vendor_id = {$vendor_id}"; 

		$sql .= " AND     status != 'reversed'"; 

		if ( $date_range != null ) { 

			$sql .= " 
			AND     time >= '" . $date_range[ 'after' ] . "'
			AND     time <= '" . $date_range[ 'before' ] . "' 
			";
		}

		$sql .= "
			ORDER BY time DESC;
		";

		$orders = $wpdb->get_results( $sql );

		$total_orders = array(); 

		if ( $orders ) { 

			foreach ( $orders as $order ) {
				
				$_order 					= new WC_Order( $order->order_id ); 
				$wcv_order 					= new stdClass();
				$wcv_order->order_id 		= $order->order_id; 
				$wcv_order->order			= $_order; 
				$wcv_order->total_due		= $order->total_due;
				$wcv_order->total 			= 0;
				$wcv_order->tax 			= 0; 
				$wcv_order->order_items		= array(); 
				$wcv_order->total_shipping	= $order->total_shipping;
				$wcv_order->status			= $order->status;
				$wcv_order->recorded_time	= $order->time;

				$order_items = $_order->get_items(); 

				foreach ( $order_items as $key => $order_item ) {

					if ( $order_item[ 'product_id' ] == $order->product_id || $order_item[ 'variation_id' ] == $order->product_id ) { 

						$wcv_order->order_items[] 	=  $order_item; 
						$wcv_order->total 			+= $order_item['line_total']; 
						$wcv_order->tax				+= $order_item['line_tax']; 
					}
				} 

				$total_orders[] = $wcv_order; 		

			}

		}

		return $total_orders; 
	
	} // get_orders() 


	/**
	 *  Get all orders for a vendor 
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$vendor_id   vendor id for store id
	 * @param 	 array 		$date_range  date range to search for 
	 * @return 	 array 		$wcv_orders an array of order objects with required information for the vendor 
	 */
	public static function get_orders2( $vendor_id, $date_range = null, $reports = true ) {

		global $wpdb; 

		$sql = "
			SELECT id, order_id, product_id, vendor_id, total_due, total_shipping, qty, tax, status, time
			FROM {$wpdb->prefix}pv_commission as order_items
			WHERE vendor_id = {$vendor_id} "; 

		if ( $reports ) { 
			$sql .= 
			" AND status != 'reversed'"; 
		}

		if ( $date_range != null ) { 

			$sql .= " 
			AND     time >= '" . $date_range[ 'after' ] . " 00:00:00'
			AND     time <= '" . $date_range[ 'before' ] . " 23:59:59' 
			";
		}

		$sql .= "
			ORDER BY time DESC;
		";

		$sql = apply_filters( 'wcv_get_orders_all_sql', $sql ); 

		// Get all orders for the vendor id supplied except for reversed commission
		$all_orders = $wpdb->get_results( $sql );

		$sql = "
			SELECT DISTINCT( order_id )
			FROM {$wpdb->prefix}pv_commission as unqiue_orders
			WHERE   vendor_id = {$vendor_id} "; 

		if ( $reports ) { 
			$sql .= 
			" AND     status != 'reversed'"; 
		}

		if ( $date_range != null ) { 

			$sql .= " 
			AND     time >= '" . $date_range[ 'after' ] . " 00:00:00'
			AND     time <= '" . $date_range[ 'before' ] . " 23:59:59' 
			";
		}

		$sql .= "
			ORDER BY time DESC;
		";

		$sql = apply_filters( 'wcv_get_orders_unqiue_sql', $sql ); 

		$unique_orders = $wpdb->get_results( $sql ); 

		$total_orders = array(); 

		if ( $unique_orders ) { 
		
			foreach ( $unique_orders as $order ) { 

				$wcv_order 						= new stdClass();
				$wcv_order->order_id 			= $order->order_id; 
				$_order 						= new WC_Order( $order->order_id ); 
				$wcv_order->order				= $_order; 
				$order_items 					= $_order->get_items(); 
				$wcv_order->product_commissions	= array(); 
				$wcv_order->total 				= 0;
				$wcv_order->commission_total 	= 0;
				$wcv_order->product_total 		= 0; 
				$wcv_order->total_due			= 0;
				$wcv_order->qty					= 0; 
				$wcv_order->total_tax			= 0;
				$wcv_order->total_shipping		= 0;

				$vendor_products = array_filter( $all_orders, function( $single_order ) use( &$order ) { return $single_order->order_id == $order->order_id; } ); 

				$wcv_order->vendor_products 	= $vendor_products; 

				foreach ( $vendor_products as $key => $vendor_product ) {

					$wcv_order->total_due			+= $vendor_product->total_due;
					$wcv_order->total_tax			+= $vendor_product->tax;
					$wcv_order->qty					+= $vendor_product->qty;
					$wcv_order->total_shipping		+= $vendor_product->total_shipping;
					$wcv_order->status				= $vendor_product->status;
					$wcv_order->recorded_time		= date('Y-m-d', strtotime( $vendor_product->time ) );

					// // Ensure that only the vendor products are in the order 
					foreach ( $order_items as $key => $order_item ) {

						if ( $order_item[ 'product_id' ] == $vendor_product->product_id || $order_item[ 'variation_id' ] == $vendor_product->product_id ) { 
							$item_id 														= ( $order_item[ 'variation_id' ] ) ? $order_item[ 'variation_id' ] : $order_item ['product_id' ]; 
							$wcv_order->product_commissions[ $order_item[ 'product_id' ] ]	= $vendor_product->total_due; 
							$wcv_order->order_items[ $item_id ] 							= $order_item; 
							$wcv_order->product_total 										+= $order_item[ 'line_total' ]; 
						} 
					}
				}				

				$wcv_order->total 				= $wcv_order->product_total + $wcv_order->total_shipping + $wcv_order->total_tax; 
				$wcv_order->commission_total  	= $wcv_order->total_due + $wcv_order->total_shipping + $wcv_order->total_tax; 
				

				$total_orders[] = $wcv_order; 		
			}

			
		} 

		return $total_orders; 
	
	} // get_orders2() 

	/**
	 *  Get the min and max dates for a vendors orders 
	 * 
	 * @since    1.2.3
	 * @param 	 int 		$vendor_id  vendor id
	 * @return 	 object 	$dates  the min and max dates 
	 * @todo     make this actually function as its supposed to. 
	 */
	public static function get_order_dates( $vendor_id, $range_type ){ 

		global $wpdb; 

		// Get the first and last order date for the vendor 
		$sql 			= "SELECT min(time) as start_date, max(time) as end_date FROM {$wpdb->prefix}pv_commission WHERE vendor_id = $vendor_id"; 
		$dates 			= $wpdb->get_row( $sql );

		// Get the start of the week option from Settings > General 
		// Convert the start day to the date interval format required by PHP
		$start_of_week 	= get_option( 'start_of_week' ); 
		$start_day 		= ( 0 == (int) $start_of_week ) ? 6 : (int) $start_of_week - 1; 

		$start 			= new DateTime( $dates->start_date );
		$end 			= new DateTime( $dates->end_date );
		$interval 		= new DateInterval('P1D');

		$date_range 	= new DatePeriod( $start, $interval, $end );

		$weekNumber 	= 1; 
		$monthNumber 	= 0; 
		$weeks 			= array();

		foreach ( $date_range as $date ) {

		    $weeks[ $weekNumber ][] = $date->format('Y-m-d');
		    
		    // Weekly 
		    if ( $date->format('w') == $start_day ) {
		        $weekNumber++;
		    }
		}

		$ranges = array_map( function( $week ) { return array( 'start_week' => array_shift( $week ),  'end_week' => array_pop( $week ) ); } , $weeks);

		return $dates; 

	} //get_order_dates

	/**
	 *  Get the vendors products by id only 
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$vendor_id  vendor id for store id
	 * @return 	 array 		$product_ids  All the vendors product ids, no matter their post status
	 */
	public static function get_products_by_id( $vendor_id ) {

		$args = array(
			'numberposts' => -1,
			'post_type'   => 'product',
			'author'      => $vendor_id,
			'post_status' => 'any',
		);

		$args = apply_filters( 'wcv_get_vendor_products_by_id_args', $args );

		$products = get_posts( $args ); 
		$product_ids = wp_list_pluck( $products, 'ID' ); 

		return $product_ids; 

	} //get_products_by_id()

	/**
	 *  Get the vendors products
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$vendor_id  vendor id for store id
	 * @param 	 string 	$meta_key   user meta key 
	 * @return 	 array 		$products  All vendors products in array of product objects 
	 */
	public static function get_products( $vendor_id ) {

		$all_product_ids = WCVendors_Pro_Vendor_Controller::get_products_by_id( $vendor_id ); 

		$products = array(); 

		foreach ( $all_product_ids as $product_id ) {
				
			$products[] = new WC_Product( $product_id ); 

		}
		return $products; 

	} //get_products()

	/**
	 *  Get the vendor id from the object id parsed 
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$object_id  search for the object id
	 * @return   int 		$vendor_id  author of the product 
	 */
	public static function get_vendor_from_object( $object_id )
	{
		// Make sure we are returning an author for products or product variations only or shop coupon 
		if ( 'product' === get_post_type( $object_id ) || 'product_variation' === get_post_type( $object_id ) ||  'shop_coupon' === get_post_type( $object_id ) ) { 
			$object = get_post( $object_id );
			$author = $object ? $object->post_author : 1;
		} else { 
			$author = -1; 
		}
		return $author;
	} //get_vendor_from_object()

	/**
	 *  Save the pending vendor 
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$vendor_id  the new vendor id
	 */
	public static function save_pending_vendor( $vendor_id ) { 

		global $woocommerce;

		// Stop admins from registering as vendor
		if ( user_can( get_current_user_id(), 'manage_options' ) ) {
			wc_add_notice( __( 'The Vendor Dashboard is only visible to Vendors. Due to WordPress capabilities and its limitations, Administrators can not view it. You should create a test vendor user account, with the role Vendor, and use that account to view and experience the Vendor Dashboard. ', 'wcvendors-pro' ), 'error' );
			wp_safe_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); 
			exit; 
		} 

		$manual = WC_Vendors::$pv_options->get_option( 'manual_vendor_registration' );
		$role   = apply_filters( 'wcvendors_pending_role', ( $manual ? 'pending_vendor' : 'vendor' ) );

		$wp_user_object = new WP_User( $vendor_id );
		$wp_user_object->set_role( $role );

		// Fire email off for new vendor 
		if ( $role == 'pending_vendor' ) {
			$status = __( 'pending', 'wcvendors-pro' );
		} elseif ( $role == 'vendor' ) {
			$status = __( 'approved', 'wcvendors-pro' );
		} 

		$mails = $woocommerce->mailer()->get_emails();

		if ( isset( $status ) && !empty( $mails ) ) {
			$mails[ 'WC_Email_Approve_Vendor' ]->trigger( get_current_user_id(), $status );
		}

		do_action( 'wcv_save_pending_vendor', $vendor_id ); 

	} //save_pending_vendor()

	/**
	 *  Is the user a pending vendor
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$vendor_id  the user id to test 
	 */
	public static function is_pending_vendor( $user_id ) { 

		$user = get_userdata( $user_id ); 

		if ( is_object( $user ) ) { 
			$is_pending_vendor = is_array( $user->roles ) ? in_array( 'pending_vendor', $user->roles ) : false;
		} else { 
			$is_pending_vendor = false; 
		}

		return apply_filters( 'wcv_is_pending_vendor', $is_pending_vendor, $user_id );

	} //is_pending_vendor()


	/**
	 *  Get the vendors store url 
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$vendor_id  the user id to test 
	 */
	public static function get_vendor_store_url( $vendor_id ) { 

		$vendor_store_url = WCV_Vendors::get_vendor_shop_page( $vendor_id ); 
		return apply_filters( 'wcv_vendor_store_url', $vendor_store_url, $vendor_id ); 

	} //get_vendor_store_url()

	/**
	 *  Redirect the applicant to the pro dashboard
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$user_id  the user id returned from the registration
	 * @todo 	 Fix how the loading happens so that we don't have to change the role of the vendor before firing. 
	 */
	public function apply_vendor_redirect( $user_id ) { 

		// If apply for vendor is selected, redirect to the pro dash board
		if ( isset( $_POST[ 'apply_for_vendor' ] ) ) {			
			add_filter( 'woocommerce_registration_redirect', array( $this, 'redirect_to_pro_dashboard' ), 11 );
		} 

	} //apply_vendor_redirect()

	/**
	 *  Output the pro dashboard
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$user_id  the user id returned from the registration
	 */
	public function redirect_to_pro_dashboard( $redirect ) { 

		$terms = isset( $_POST['agree_to_terms'] ) ? $_POST['agree_to_terms'] : ''; 

		$dashboard_url = WCVendors_Pro_Dashboard::get_dashboard_page_url() . '?terms='.$terms; 
		return apply_filters( 'wcv_vendor_signup_redirect', $dashboard_url );

	} // redirect_to_pro_dashboard()


	/**
	 *  Process the store settings submission from the front end, this applies to vendor dashboard and vendor application.
	 *
	 * @since    1.2.0
	 */
	public function process_submit() { 

		if ( ! isset( $_POST[ '_wcv-save_store_settings' ] ) || !wp_verify_nonce( $_POST[ '_wcv-save_store_settings' ], 'wcv-save_store_settings' ) || !is_user_logged_in() ) { 
			return; 
		}

		// error_log( print_r( $_POST, true ) ); 

		$vendor_status 	= ''; 
		$notice_text 	= ''; 
		$vendor_id 		= get_current_user_id(); 

		$this->allow_markup 	= WC_Vendors::$pv_options->get_option( 'allow_form_markup' ); 

		$settings_store 	= ( isset( $_POST[ '_wcv_vendor_application_id'] ) ) ? (array) WC_Vendors::$pv_options->get_option( 'hide_signup_store' ) : (array) WC_Vendors::$pv_options->get_option( 'hide_settings_store' );

		// Check if the Shop name is unique 
		$users = get_users( array( 'meta_key' => 'pv_shop_slug', 'meta_value' => sanitize_title( $_POST[ '_wcv_store_name' ] ) ) );

		if ( !empty( $users ) && $users[ 0 ]->ID != $vendor_id ) {		
			wc_add_notice( __( 'That store name is already taken. Your store name must be unique. <br /> Settings have not been saved.', 'wcvendors-pro' ), 'error' ); 
			return; 
		} 

		wc_add_notice( __( 'Store Settings Saved', 'wcvendors-pro' ), 'success' ); 

		// Maybe server side validation 
		$paypal_address		= ( isset( $_POST[ '_wcv_paypal_address' ] ) )		? sanitize_email( $_POST[ '_wcv_paypal_address' ] ) 		: ''; 
		$store_name 		= ( isset( $_POST[ '_wcv_store_name' ] ) )			? sanitize_text_field( trim( $_POST[ '_wcv_store_name' ] ) )	: ''; 
		$store_phone		= ( isset( $_POST[ '_wcv_store_phone' ] ) )			? sanitize_text_field( trim( $_POST[ '_wcv_store_phone' ] )	)	: '';
		$seller_info 		= ( isset( $_POST[ 'pv_seller_info' ] ) )			? trim( $_POST[ 'pv_seller_info' ] )			: ''; 
		$store_description 	= ( isset( $_POST[ 'pv_shop_description' ]) )		? trim( $_POST[ 'pv_shop_description' ] )		: ''; 
		$store_banner_id 	= ( isset( $_POST[ '_wcv_store_banner_id' ] ) ) 	? sanitize_text_field( $_POST[ '_wcv_store_banner_id' ] )		: ''; 
		$store_icon_id 		= ( isset( $_POST[ '_wcv_store_icon_id' ] ) ) 		? sanitize_text_field( $_POST[ '_wcv_store_icon_id' ] )			: ''; 
		$twitter_username 	= ( isset( $_POST[ '_wcv_twitter_username' ] ) ) 	? sanitize_text_field( $_POST[ '_wcv_twitter_username' ] )		: ''; 
		$instagram_username = ( isset( $_POST[ '_wcv_instagram_username' ] ) ) 	? sanitize_text_field( $_POST[ '_wcv_instagram_username' ] )	: ''; 
		$facebook_url 		= ( isset( $_POST[ '_wcv_facebook_url' ] ) ) 		? sanitize_text_field( $_POST[ '_wcv_facebook_url' ] )			: ''; 
		$linkedin_url 		= ( isset( $_POST[ '_wcv_linkedin_url' ] ) ) 		? sanitize_text_field( $_POST[ '_wcv_linkedin_url' ] )			: ''; 
		$youtube_url 		= ( isset( $_POST[ '_wcv_youtube_url' ] ) ) 		? sanitize_text_field( $_POST[ '_wcv_youtube_url' ] )			: ''; 
		$pinterest_url 		= ( isset( $_POST[ '_wcv_pinterest_url' ] ) ) 		? sanitize_text_field( $_POST[ '_wcv_pinterest_url' ] )		: ''; 
		$googleplus_url 	= ( isset( $_POST[ '_wcv_googleplus_url' ] ) ) 		? sanitize_text_field( $_POST[ '_wcv_googleplus_url' ] )		: ''; 
		$snapchat_username 	= ( isset( $_POST[ '_wcv_snapchat_username' ] ) ) 	? sanitize_text_field( $_POST[ '_wcv_snapchat_username' ] )		: ''; 
		$address1 			= ( isset( $_POST[ '_wcv_store_address1' ] ) ) 		? sanitize_text_field( $_POST[ '_wcv_store_address1' ] ) 		: '';
		$address2 			= ( isset( $_POST[ '_wcv_store_address2' ] ) ) 		? sanitize_text_field( $_POST[ '_wcv_store_address2' ] ) 		: '';
		$city	 			= ( isset( $_POST[ '_wcv_store_city' ] ) ) 			? sanitize_text_field( $_POST[ '_wcv_store_city' ] ) 			: '';
		$state	 			= ( isset( $_POST[ '_wcv_store_state' ]	 ) ) 		? sanitize_text_field( $_POST[ '_wcv_store_state' ] )	  		: '';
		$country			= ( isset( $_POST[ '_wcv_store_country' ] ) )  		? sanitize_text_field( $_POST[ '_wcv_store_country' ] )  		: '';
		$postcode			= ( isset( $_POST[ '_wcv_store_postcode' ] ) ) 	 	? sanitize_text_field( $_POST[ '_wcv_store_postcode' ] ) 		: '';
		$company_url		= ( isset( $_POST[ '_wcv_company_url' ] ) ) 	 	? sanitize_text_field( $_POST[ '_wcv_company_url' ] ) 			: '';
		$vacation_mode		= ( isset( $_POST[ '_wcv_vacation_mode' ] ) ) 	 	? sanitize_text_field( $_POST[ '_wcv_vacation_mode' ] ) 		: '';
		$vacation_msg		= ( isset( $_POST[ '_wcv_vacation_mode_msg' ] ) ) 	? sanitize_text_field( $_POST[ '_wcv_vacation_mode_msg' ] ) 	: '';


		$shipping_fee_national				= ( isset( $_POST[ '_wcv_shipping_fee_national' ] ) ) 				? sanitize_text_field( $_POST[ '_wcv_shipping_fee_national' ] ) 				: '';
		$shipping_fee_international			= ( isset( $_POST[ '_wcv_shipping_fee_international' ] ) ) 			? sanitize_text_field( $_POST[ '_wcv_shipping_fee_international' ] ) 			: '';
		$shipping_fee_national_qty			= ( isset( $_POST[ '_wcv_shipping_fee_national_qty' ] ) ) 			? 'yes' 	: '';
		$shipping_fee_international_qty		= ( isset( $_POST[ '_wcv_shipping_fee_international_qty' ] ) ) 		? 'yes' 	: '';
		$shipping_fee_national_free			= ( isset( $_POST[ '_wcv_shipping_fee_national_free' ] ) ) 			? 'yes'		: '';
		$shipping_fee_international_free	= ( isset( $_POST[ '_wcv_shipping_fee_international_free' ] ) ) 	? 'yes'		: '';
		$shipping_fee_national_disable		= ( isset( $_POST[ '_wcv_shipping_fee_national_disable' ] ) ) 		? 'yes'		: '';
		$shipping_fee_international_disable	= ( isset( $_POST[ '_wcv_shipping_fee_international_disable' ] ) ) 	? 'yes' 	: '';
		$product_handling_fee   			= ( isset( $_POST[ '_wcv_shipping_product_handling_fee' ] ) ) 		? sanitize_text_field( $_POST[ '_wcv_shipping_product_handling_fee' ] ) 		: '';
		$max_charge   						= ( isset( $_POST[ '_wcv_shipping_max_charge' ] ) ) 				? sanitize_text_field( $_POST[ '_wcv_shipping_max_charge' ] ) 					: '';
		$min_charge   						= ( isset( $_POST[ '_wcv_shipping_min_charge' ] ) ) 				? sanitize_text_field( $_POST[ '_wcv_shipping_min_charge' ] ) 					: '';
		$free_shipping_order 				= ( isset( $_POST[ '_wcv_shipping_free_shipping_order' ] ) ) 		? sanitize_text_field( $_POST[ '_wcv_shipping_free_shipping_order' ] ) 		: '';
		$free_shipping_product 				= ( isset( $_POST[ '_wcv_shipping_free_shipping_product' ] ) ) 		? sanitize_text_field( $_POST[ '_wcv_shipping_free_shipping_product' ] ) 		: '';
		$max_charge_product   				= ( isset( $_POST[ '_wcv_shipping_max_charge_product' ] ) ) 		? sanitize_text_field( $_POST[ '_wcv_shipping_max_charge_product' ] ) 			: '';
		$shipping_policy					= ( isset( $_POST[ '_wcv_shipping_policy' ] ) ) 					? sanitize_text_field( $_POST[ '_wcv_shipping_policy' ] ) 						: '';
		$return_policy						= ( isset( $_POST[ '_wcv_shipping_return_policy' ] ) ) 				? sanitize_text_field( $_POST[ '_wcv_shipping_return_policy' ] ) 				: '';
		$shipping_from						= ( isset( $_POST[ '_wcv_shipping_from' ] ) ) 						? sanitize_text_field( $_POST[ '_wcv_shipping_from' ] ) 						: '';
		$shipping_address1 					= ( isset( $_POST[ '_wcv_shipping_address1' ] ) ) 					? sanitize_text_field( $_POST[ '_wcv_shipping_address1' ] ) 		: '';
		$shipping_address2 					= ( isset( $_POST[ '_wcv_shipping_address2' ] ) ) 					? sanitize_text_field( $_POST[ '_wcv_shipping_address2' ] ) 		: '';
		$shipping_city	 					= ( isset( $_POST[ '_wcv_shipping_city' ] ) ) 						? sanitize_text_field( $_POST[ '_wcv_shipping_city' ] ) 			: '';
		$shipping_state	 					= ( isset( $_POST[ '_wcv_shipping_state' ]	 ) ) 					? sanitize_text_field( $_POST[ '_wcv_shipping_state' ]	)  			: '';
		$shipping_country					= ( isset( $_POST[ '_wcv_shipping_country' ] ) )  					? sanitize_text_field( $_POST[ '_wcv_shipping_country' ] ) 		: '';
		$shipping_postcode					= ( isset( $_POST[ '_wcv_shipping_postcode' ] ) ) 	 				? sanitize_text_field( $_POST[ '_wcv_shipping_postcode' ] ) 		: '';

		// Save free user meta 
		update_user_meta( $vendor_id, 'pv_paypal', 				$paypal_address );
		update_user_meta( $vendor_id, 'pv_shop_name', 			$store_name );
		update_user_meta( $vendor_id, 'pv_shop_slug', 			sanitize_title( $store_name ) );
		
		// Store description 
		if ( isset( $store_description ) && '' !== $store_description ) { 
			update_user_meta( $vendor_id, 'pv_shop_description', 	$this->allow_markup ? $store_description : wp_strip_all_tags( $store_description ) );
		} 

		// Seller info 
		if ( isset( $seller_info ) && '' !== $seller_info ) { 
			update_user_meta( $vendor_id, 'pv_seller_info', 		$this->allow_markup ? $seller_info : wp_strip_all_tags( $seller_info ) );
		} 

		// Store Banner
		if ( isset( $store_banner_id ) && '' !== $store_banner_id ) { 
			update_user_meta( $vendor_id, '_wcv_store_banner_id',  (int) $store_banner_id ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_store_banner_id' ); 
		}

		// Store Icon 
		if ( isset( $store_icon_id ) && '' !== $store_icon_id ) { 
			update_user_meta( $vendor_id, '_wcv_store_icon_id', 		$store_icon_id );  	
		} else { 
			delete_user_meta( $vendor_id, '_wcv_store_icon_id' );  	
		}

		// Company URL  
		if ( isset( $company_url ) && '' !== $company_url ) { 
			update_user_meta( $vendor_id, '_wcv_company_url', 	$company_url ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_company_url' ); 
		}

		// Store Address1 
		if ( isset( $address1 ) && '' !== $address1 ) { 
			update_user_meta( $vendor_id, '_wcv_store_address1', 	$address1 ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_store_address1' ); 
		}
		// Store Address2 
		if ( isset( $address2 ) && '' !== $address2 ) { 
			update_user_meta( $vendor_id, '_wcv_store_address2', 	$address2 ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_store_address2' ); 
		}
		// Store City 
		if ( isset( $city ) && '' !== $city ) { 
			update_user_meta( $vendor_id, '_wcv_store_city', 	$city ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_store_city' ); 
		}
		// Store State 
		if ( isset( $state ) && '' !== $state ) { 
			update_user_meta( $vendor_id, '_wcv_store_state', 	$state ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_store_state' ); 
		}
		// Store Country 
		if ( isset( $country ) && '' !== $country ) { 
			update_user_meta( $vendor_id, '_wcv_store_country', 	$country ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_store_country' ); 
		}
		// Store post code 
		if ( isset( $postcode ) && '' !== $postcode ) { 
			update_user_meta( $vendor_id, '_wcv_store_postcode', 	$postcode ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_store_postcode' ); 
		}
		// Store Phone
		if ( isset( $store_phone ) && '' !== $store_phone ) { 
			update_user_meta( $vendor_id, '_wcv_store_phone', 	$store_phone ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_store_phone' ); 
		}

		// Vacation Message 
		if ( isset( $vacation_mode ) && '' !== $vacation_mode ){ 
			update_user_meta( $vendor_id, '_wcv_vacation_mode', 	$vacation_mode ); 
			update_user_meta( $vendor_id, '_wcv_vacation_mode_msg', $vacation_msg ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_vacation_mode' ); 
			delete_user_meta( $vendor_id, '_wcv_vacation_mode_msg' ); 
		}
	
		// Twitter Username
		if ( isset( $twitter_username ) && '' !== $twitter_username ) { 
			update_user_meta( $vendor_id, '_wcv_twitter_username', 	$twitter_username ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_twitter_username' ); 
		}
		
		//Instagram Username 
		if ( isset( $instagram_username ) && '' !== $instagram_username ) { 
			update_user_meta( $vendor_id, '_wcv_instagram_username', 	$instagram_username ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_instagram_username' ); 
		}

		// Facebook URL
		if ( isset( $facebook_url ) && '' !== $facebook_url ) { 
			update_user_meta( $vendor_id, '_wcv_facebook_url', 	$facebook_url ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_facebook_url' ); 
		}
		
		// LinkedIn URL
		if ( isset( $linkedin_url ) && '' !== $linkedin_url ) { 
			update_user_meta( $vendor_id, '_wcv_linkedin_url', 	$linkedin_url ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_linkedin_url' ); 
		}

		// YouTube URL
		if ( isset( $youtube_url ) && '' !== $youtube_url ) { 
			update_user_meta( $vendor_id, '_wcv_youtube_url', 	$youtube_url ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_youtube_url' ); 
		}

		// Pinterest URL
		if ( isset( $pinterest_url ) && '' !== $pinterest_url ) { 
			update_user_meta( $vendor_id, '_wcv_pinterest_url', 	$pinterest_url ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_pinterest_url' ); 
		}

		// Google+ URL
		if ( isset( $googleplus_url ) && '' !== $googleplus_url ) { 
			update_user_meta( $vendor_id, '_wcv_googleplus_url', 	$googleplus_url ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_googleplus_url' ); 
		}

		// Snapchat Username 
		if ( isset( $snapchat_username ) && '' !== $snapchat_username ) { 
			update_user_meta( $vendor_id, '_wcv_snapchat_username', 	$snapchat_username ); 
		} else { 
			delete_user_meta( $vendor_id, '_wcv_snapchat_username' ); 
		}


		$wcvendors_shipping = array( 
			'national' 						=> $shipping_fee_national, 
			'national_qty_override'			=> $shipping_fee_national_qty,
			'national_free'					=> $shipping_fee_national_free,
			'national_disable'				=> $shipping_fee_national_disable, 
			'international' 				=> $shipping_fee_international,
			'international_qty_override' 	=> $shipping_fee_international_qty, 
			'international_free' 			=> $shipping_fee_international_free, 
			'international_disable' 		=> $shipping_fee_international_disable, 
			'product_handling_fee' 			=> $product_handling_fee, 
			'max_charge'					=> $max_charge, 
			'min_charge'					=> $min_charge, 
			'free_shipping_order'			=> $free_shipping_order, 
			'free_shipping_product'			=> $free_shipping_product, 
			'max_charge_product'			=> $max_charge_product, 
			'shipping_policy'				=> $this->allow_markup ? $shipping_policy : wp_strip_all_tags( $shipping_policy ), 
			'return_policy' 				=> $this->allow_markup ? $return_policy : wp_strip_all_tags( $return_policy ), 
			'shipping_from' 				=> $shipping_from, 
			'shipping_address'				=> '', 
		); 

		if ( $shipping_from && $shipping_from == 'other' ) { 

			$shipping_address = array(
				'address1' => 	$shipping_address1,
				'address2' => 	$shipping_address2,
				'city'	   =>	$shipping_city,
				'state'    =>	$shipping_state,
				'country'  => 	$shipping_country,
				'postcode' => 	$shipping_postcode,
			); 

			$wcvendors_shipping[ 'shipping_address' ] = $shipping_address; 
		} 

		update_user_meta( $vendor_id, '_wcv_shipping', 	$wcvendors_shipping ); 	

		// shipping rates 
		$shipping_rates = array();

		if ( isset( $_POST['_wcv_shipping_fees'] ) ) {
			$shipping_countries    	= isset( $_POST['_wcv_shipping_countries'] ) 	? $_POST['_wcv_shipping_countries'] : array(); 
			$shipping_states    	= isset( $_POST['_wcv_shipping_states'] ) 		? $_POST['_wcv_shipping_states'] : array();
			$shipping_postcodes    	= isset( $_POST['_wcv_shipping_postcodes'] ) 	? $_POST['_wcv_shipping_postcodes'] : array();
			$shipping_qty_overrides = isset( $_POST[ '_wcv_shipping_overrides'] ) 	? $_POST[ '_wcv_shipping_overrides'] : array();
			$shipping_fees     		= isset( $_POST['_wcv_shipping_fees'] )  		? $_POST['_wcv_shipping_fees'] : array();
			$shipping_fee_count 	= sizeof( $shipping_fees );

			for ( $i = 0; $i < $shipping_fee_count; $i ++ ) {

				if ( $shipping_fees[ $i ] != '' ) {
					$country       = wc_clean( $shipping_countries[ $i ] ); 
					$state         = wc_clean( $shipping_states[ $i ] );
					$postcode      = wc_clean( $shipping_postcodes[ $i ] );
					$qty_override  = ( isset( $shipping_qty_overrides[ $i ] ) && '' != $shipping_qty_overrides[ $i ] ) ? 'yes' : '';
					$fee           = wc_format_decimal( $shipping_fees[ $i ] );
					$shipping_rates[ $i ] = array(
						'country'		=> $country,
						'state' 		=> $state, 
						'postcode'		=> $postcode,
						'fee' 			=> $fee,
						'qty_override'	=> $qty_override, 
					);
				}
			}
			update_user_meta( $vendor_id, '_wcv_shipping_rates',  $shipping_rates  );
		} else { 
			delete_user_meta( $vendor_id, '_wcv_shipping_rates' );
		}

		// To be used to allow hidden custom meta keys 
		$wcv_hidden_custom_metas = array_intersect_key( $_POST, array_flip( preg_grep('/^_wcv_custom_settings_/', array_keys( $_POST ) ) ) );

		if ( !empty( $wcv_hidden_custom_metas ) ) { 

			foreach ( $wcv_hidden_custom_metas as $key => $value ) {
				update_user_meta( $vendor_id, $key, $value ); 	
			}

		}		

		// To be used to allow custom meta keys 
		$wcv_custom_metas = array_intersect_key( $_POST, array_flip( preg_grep('/^wcv_custom_settings_/', array_keys( $_POST ) ) ) );

		if ( !empty( $wcv_custom_metas ) ) { 

			foreach ( $wcv_custom_metas as $key => $value ) {
				update_user_meta( $vendor_id, $key, $value ); 	
			}

		}	

		// save the pending vendor 
		// TODO: If the vendor is denied then need to scrub database of meta's above
		if ( isset( $_POST[ '_wcv_vendor_application_id' ] ) ) {  

			$manual = WC_Vendors::$pv_options->get_option( 'manual_vendor_registration' );

			WCVendors_Pro_Vendor_Controller::save_pending_vendor( $vendor_id ); 
			wc_clear_notices(); 

			if ( $manual ) { 
				$vendor_pending_notice = WCVendors_Pro::get_option( 'vendor_pending_notice' );
				wc_add_notice( $vendor_pending_notice , 'success' );
				wp_safe_redirect( apply_filters( 'wcv_register_pending_vendor_url', get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) ); 
				exit; 
			} else { 
				$approved_vendor_notice = WCVendors_Pro::get_option( 'vendor_approved_notice' );
				wc_add_notice( $approved_vendor_notice , 'success' );
				$dashboard_page_id 	= WCVendors_Pro::get_option( 'dashboard_page_id' );
				wp_safe_redirect( apply_filters( 'wcv_register_vendor_url', get_permalink( $dashboard_page_id ) ) ); 
				exit; 
			}

			
		} 

		do_action( 'wcv_pro_store_settings_saved', $vendor_id );


	} // process_submit() 

	/**
	 *  Hook into the single product page to display the ships from 
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$product_id  the product to hook into 
	 */
	public function product_ships_from( $product_id ) { 

		global $post, $product;

		$shipping_disabled	= WCVendors_Pro::get_option( 'shipping_management_cap' );
		$post 				= get_post( $product->get_id() ); 	


		if ( $product->needs_shipping() && ! $shipping_disabled && WCV_Vendors::is_vendor( $post->post_author ) ) { 

			$vendor_id 	= WCV_Vendors::get_vendor_from_product( $product_id ); 
			$is_vendor 	= WCV_Vendors::is_vendor( $vendor_id ); 

			$store_rates		= (array) get_user_meta( $vendor_id, '_wcv_shipping', true ); 

			$store_country 		= ( $store_rates && array_key_exists('shipping_from', $store_rates ) && $store_rates['shipping_from'] == 'other' ) ? strtolower( $store_rates['shipping_address']['country'] ) : strtolower( get_user_meta( $vendor_id, '_wcv_store_country', true ) ); 
			$countries 			= WCVendors_Pro_Form_Helper::countries();

			if ( ! $store_country ) $store_country = WC()->countries->get_base_country(); 

			$ships_from 	= apply_filters( 'wcv_product_ships_from', 
				array( 
					'store_country'		=> $countries[ strtoupper( $store_country ) ], 
					'wrapper_start'	=> '<span class="wcvendors_ships_from"><br />', 
					'wrapper_end'	=> '</span><br />',  	
					'title'				=> __( 'Ships From: ', 'wcvendors-pro' )
				) ); 

			include( apply_filters( 'wcvendors_pro_vendor_product_ships_from_path', 'partials/product/wcvendors-pro-ships-from.php' ) ); 
		} 

	} // product_ships_from() 


	/**
	 *  Hook into the single product page to vendor tools 
	 * 
	 * @since    1.0.0
	 * @param 	 int 		$product_id  the product to hook into 
	 */
	public function enable_vendor_tools( $product_id ) { 

		global $post, $product;

		if ( get_current_user_id() == $post->post_author && WCV_Vendors::is_vendor( get_current_user_id() ) ) { 

			$can_edit 				= WC_Vendors::$pv_options->get_option( 'can_edit_published_products');
			$disable_delete 		= WC_Vendors::$pv_options->get_option( 'delete_product_cap');
			$disable_duplicate 		= WC_Vendors::$pv_options->get_option( 'duplicate_product_cap');
			$tools_label 			= apply_filters( 'wcv_product_tools_label', __( 'Tools: ', 'wcvendors-pro') ); 

			$actions = apply_filters( 'wcv_product_single_actions' , array( 
				'edit'  	=> 
						apply_filters( 'wcv_product_single_actions_edit', array(  
							'label' 	=> __( 'Edit', 	'wcvendors-pro' ), 
							'class'		=> '', 
							'url' 		=> WCVendors_Pro_Dashboard::get_dashboard_page_url( 'product/edit/' . $product->id ), 
						) ), 
				'duplicate'  	=> 
						apply_filters( 'wcv_product_single_actions_duplicate', array(  
							'label' 	=> __( 'Duplicate', 	'wcvendors-pro' ), 
							'class'		=> '', 
							'url' 		=> WCVendors_Pro_Dashboard::get_dashboard_page_url( 'product/duplicate/' . $product->id ), 
						) ), 
				'delete'  	=> 
						apply_filters( 'wcv_product_single_actions_delete', array( 
							'label' 	=> __( 'Delete', 'wcvendors-pro' ), 
							'class'		=> 'confirm_delete', 
							'custom'	=> array( 'data-confirm_text' => __( 'Delete product?', 'wcvendors-pro')  ), 
							'url' 		=> WCVendors_Pro_Dashboard::get_dashboard_page_url( 'product/delete/' . $product->id ), 
						) ), 
			) ); 

			// Abide by dashboard permissions 
			if ( !$can_edit ) {  unset( $actions[ 'edit' ] ); } 
			if ( $disable_delete ) unset( $actions[ 'delete' ] ); 
			if ( $disable_duplicate ) unset( $actions[ 'duplicate' ] ); 

			if ( ! empty( $actions ) ) { 
				include( apply_filters( 'wcvendors_pro_vendor_single_product_tools_path', 'partials/product/wcvendors-pro-single-product-tools.php' ) ); 
			}
		} 

	} // edit_product_link() 


	/**
	 * Output the Pro header on single product page
	 *
	 * @since    1.0.0
	 * @return html
	 */
	public function store_single_header( ) { 

		global $product; 

		$post = get_post( $product->get_id() ); 

		if ( WCV_Vendors::is_vendor_product_page( $post->post_author ) )  { 

			$vendor_id   		= $post->post_author; 
			$vendor_meta 		= array_map( function( $a ){ return $a[0]; }, get_user_meta( $vendor_id ) );

			do_action('wcv_before_main_header', $vendor_id); 

			wc_get_template( 'store-header.php', array( 
						'vendor_id' 	=> $vendor_id, 
						'vendor_meta'	=> $vendor_meta,
						'product' 		=> $product, 
						'post'			=> $post,
			), 'wc-vendors/store/', $this->base_dir . 'templates/store/' ); 


			do_action('wcv_after_main_header', $vendor_id); 

		} 

	}

	/**
	 * Remove the free headers and related headers
	 *
	 * @since    1.2.0
	 */
	public function remove_free_headers( ){ 

		remove_action( 'woocommerce_before_main_content',   array( 'WCV_Vendor_Shop', 'vendor_main_header'), 20 ); 
		remove_action( 'woocommerce_before_single_product', array( 'WCV_Vendor_Shop', 'vendor_mini_header') ); 
		remove_action( 'woocommerce_before_main_content',   array( 'WCV_Vendor_Shop', 'shop_description' ), 30 );
	
	} //remove_free_headers() 

	/**
	 * Add the new pro store header on the main page
	 *
	 * @since    1.2.0
	 */
	public function store_main_content_header() { 


		if ( WCV_Vendors::is_vendor_page() ) { 

			$vendor_shop 		= urldecode( get_query_var( 'vendor_shop' ) );
			$vendor_id   		= WCV_Vendors::get_vendor_id( $vendor_shop ); 
			$vendor_meta 		= array_map( function( $a ){ return $a[0]; }, get_user_meta( $vendor_id ) );

			do_action('wcv_before_main_header', $vendor_id); 

			wc_get_template( 'store-header.php', array( 
						'vendor_id' 	=> $vendor_id, 
						'vendor_meta'	=> $vendor_meta, 
			), 'wc-vendors/store/', $this->base_dir . 'templates/store/' ); 


			do_action('wcv_after_main_header', $vendor_id); 

		}

	} // store_main_content_header() 

	/**
	 * Add link to pro dashboard on my account page 
	 *
	 * @since    1.2.3
	 */
	public function pro_dashboard_link_myaccount() { 

		$user 				= get_user_by( 'id', get_current_user_id() ); 
		$dashboard_page_id 	= WCVendors_Pro::get_option( 'dashboard_page_id' );
		$dashboard_url 		= get_permalink( $dashboard_page_id ); 
		$my_account_msg 	= apply_filters( 'wcv_my_account_msg', __( '<p>To add or edit products, view sales and orders for your vendor account, or to configure your store, visit your <a href="%s">Vendor Dashboard</a>.</p>', 'wcvendors-pro' ) ); 

		if ( ! WCV_Vendors::is_vendor( $user->ID ) ) { return; } 
		
		echo sprintf( $my_account_msg, $dashboard_url );  

	} // pro_dashboard_link_myaccount ()


	/**
	*	vendors_with_products - Get vendors with products pubilc or private 
	*	@param array $query 	
	*/
	public function vendors_with_products( $query ) {

		global $wpdb; 

	    if ( isset( $query->query_vars['query_id'] ) && 'vendors_with_products' == $query->query_vars['query_id'] ) {  
	        $query->query_from = $query->query_from . ' LEFT OUTER JOIN (
	                SELECT post_author, COUNT(*) as post_count
	                FROM '.$wpdb->prefix.'posts
	                WHERE post_type = "product" AND (post_status = "publish" OR post_status = "private")
	                GROUP BY post_author
	            ) p ON ('.$wpdb->prefix.'users.ID = p.post_author)';
	        $query->query_where = $query->query_where . ' AND post_count  > 0 ' ;  
	    } 
	}


	/**
	 * Add a pro vendor list short code 
	 *
	 * @since    1.2.3
	 */
	public function vendors_list( $atts ) {

		$html = ''; 
		
	  	extract( shortcode_atts( array(
	  			'orderby' 		=> 'registered',
	  			'order'			=> 'ASC',
				'per_page'      => '12',
				'show_products'	=> 'yes' 
			), $atts ) );

	  	$paged      = ( get_query_var('paged') ) ? get_query_var('paged') : 1;   
	  	$offset     = ( $paged - 1 ) * $per_page;

	  	// Hook into the user query to modify the query to return users that have at least one product 
	  	if ( $show_products == 'yes') add_action( 'pre_user_query', array( $this, 'vendors_with_products') );

	  	// Get all vendors 
	  	$vendor_total_args = apply_filters( 'wcv_vendor_total_args', array( 
	  		'role' 				=> 'vendor', 
	  		'meta_key' 			=> 'pv_shop_slug', 
  			'meta_value'   		=> '',
			'meta_compare' 		=> '>',
			'orderby' 			=> $orderby,
  			'order'				=> $order,
	  	) );

	  	if ( $show_products == 'yes' ) $vendor_total_args['query_id'] = 'vendors_with_products'; 

	  	$vendor_query = New WP_User_Query( $vendor_total_args ); 
	  	$all_vendors =$vendor_query->get_results(); 

	  	// Get the paged vendors 
	  	$vendor_paged_args = apply_filters( 'wcv_vendor_paged_args', array( 
	  		'role' 				=> 'vendor', 
	  		'meta_key' 			=> 'pv_shop_slug', 
  			'meta_value'   		=> '',
			'meta_compare' 		=> '>',
			'orderby' 			=> $orderby,
  			'order'				=> $order,
	  		'offset' 			=> $offset, 
	  		'number' 			=> $per_page, 
	  	) );

	  	if ($show_products == 'yes' ) $vendor_paged_args[ 'query_id' ] = 'vendors_with_products'; 

	  	$vendor_paged_query = New WP_User_Query( $vendor_paged_args ); 
	  	$paged_vendors = $vendor_paged_query->get_results(); 

	  	// Pagination calcs 
		$total_vendors 			= count( $all_vendors );  
		$total_vendors_paged 	= count( $paged_vendors );  
		$total_pages 			= ceil( $total_vendors / $per_page );

	   	ob_start();

	   	do_action( 'wcv_before_vendorslist' ); 

	    // Loop through all vendors and output a simple link to their vendor pages
	    foreach ( $paged_vendors as $vendor ) {

	    	$vendor_meta = array_map( function( $a ){ return $a[0]; }, get_user_meta( $vendor->ID ) );

			wc_get_template( 'pro-vendor-list.php', array(
				'shop_link'			=> WCV_Vendors::get_vendor_shop_page( $vendor->ID ), 
				'shop_name'			=> $vendor->pv_shop_name, 
				'vendor_id' 		=> $vendor->ID, 
				'vendor_meta'		=> $vendor_meta, 
				), 
			'wc-vendors/front/', $this->base_dir . 'templates/front/' );

	    } // End foreach 
	   	
	   	$html .=  ob_get_clean() ;

	    if ( $total_vendors > $total_vendors_paged ) {  

			$html .= apply_filters( 'wcv_pagination_before', '<nav class="woocommerce-pagination">' );

			$current_page = max( 1, get_query_var('paged') );  

			$html .= paginate_links( apply_filters( 'wcv_pagination_args', array(  
			    'base' => get_pagenum_link( ) . '%_%',  
			    'format' => 'page/%#%/',  
			    'current' => $current_page,  
			    'total' => $total_pages,  
			    'prev_next'    => false,  
			    'type'         => 'list',  
				), $current_page, $total_pages 
			) );  

			$html .= apply_filters( 'wcv_pagination_after', '</nav>' ); 
		}

		do_action( 'wcv_after_vendorslist' ); 

	    return $html; 
	
	} //vendors_list()

	/**
	 * Add vacation mode message
	 *
	 * @since    1.2.3
	 */
	public function vacation_mode(  ) {

		if ( is_product() ){ 

			global $product; 

			$post = get_post( $product->get_id() ); 

			if ( is_object( $product ) && WCV_Vendors::is_vendor_product_page( $post->post_author ) )  { 
				$vendor_id   		= $post->post_author; 
			}

		} else { 
			$vendor_shop 		= urldecode( get_query_var( 'vendor_shop' ) );
			$vendor_id   		= WCV_Vendors::get_vendor_id( $vendor_shop ); 
		}

		if ( isset( $vendor_id ) ){ 
		
			$vacation_mode 		= get_user_meta( $vendor_id , '_wcv_vacation_mode', true ); 
			$vacation_msg 		= ( $vacation_mode ) ? get_user_meta( $vendor_id , '_wcv_vacation_mode_msg', true ) : ''; 

			wc_get_template( 'store-vacation-message.php', array(
					'vendor_id' 		=> $vendor_id, 
					'vacation_mode'		=> $vacation_mode, 
					'vacation_msg'		=> $vacation_msg, 
				), 'wc-vendors/store/', $this->base_dir . 'templates/store/' ); 
		}

	} // vacation_mode()

	/**
	 * Load the store styles only on the vendors list shortcode page 
	 *
	 * @since    1.3.1
	 */
	public function wcvendors_list_scripts() { 

		global $post;

		if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'wcv_pro_vendorslist') ) {
			wp_enqueue_style( 'wcv-pro-store-style', apply_filters( 'wcv_pro_store_style', $this->base_url . 'assets/css/store' . $this->suffix . '.css' ), false, $this->version );			
		}
	}
}