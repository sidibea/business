<?php

/**
 * The WC Vendors Pro commission controller. 
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/admin
 * @author     Jamie Madden <support@wcvendors.com>
 */

class WCVendors_Pro_Commission_Controller {

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

		$this->wcvendors_pro 		= $wcvendors_pro;
		$this->version 				= $version;
		$this->debug 				= $debug; 
		$this->base_dir				= plugin_dir_path( dirname(__FILE__) ); 
		 
	}

	/**
	 *  Process the new commission structure 
	 *
	 * @since    1.0.0
	 */
	public function process_commission(  $commission, $product_id, $product_price, $order, $qty ) { 

		// Check if this is a variation and get the parent id, this ensures that the correct vendor id is retrieved 
		if ( get_post_type( $product_id ) === 'product_variation' ) { 
			$product_id = get_post_field( 'post_parent', $product_id );
		}

		// Product Commission 
		$product_commission_type 	= get_post_meta( $product_id, 'wcv_commission_type', true ); 
		$original_product_price 	= $product_price; 
		$free_product_commission 	= get_post_meta( $product_id, 'pv_commission_rate', true ); 

		// Store Commission 
		$vendor_id 					= get_post_field( 'post_author', $product_id );
	 	$store_commission_type 		= get_user_meta( $vendor_id, '_wcv_commission_type', true ); 
	 	$store_free_commission 		= get_user_meta( $vendor_id, 'pv_custom_commission_rate', true ); 			

	 	if ( '' != $product_commission_type ) { 
	 		$commission_type 	= $product_commission_type; 
			$commission_percent = get_post_meta( $product_id, 'wcv_commission_percent', true );  
			$commission_amount 	= get_post_meta( $product_id, 'wcv_commission_amount', 	true ); 
			$commission_fee		= get_post_meta( $product_id, 'wcv_commission_fee', 	true ); 
	 	} else if ( '' != $store_commission_type ) { 
	 		$commission_type 	= $store_commission_type; 
			$commission_percent = get_user_meta( $vendor_id, '_wcv_commission_percent', true );  
			$commission_amount 	= get_user_meta( $vendor_id, '_wcv_commission_amount',	 true ); 
			$commission_fee		= get_user_meta( $vendor_id, '_wcv_commission_fee', 	 true ); 
	 	} else { 
	 		// Global Commissions 
			$commission_type 	= WCVendors_Pro::get_option( 'commission_type' );  
			$commission_percent = WCVendors_Pro::get_option( 'commission_percent' );  
			$commission_amount 	= WCVendors_Pro::get_option( 'commission_amount' ); 
			$commission_fee		= WCVendors_Pro::get_option( 'commission_fee' ); 
	 	}

		// Assumption that coupon codes are unique, if created by vendors they are. 
	 	$coupons = $order->get_items( 'coupon' ); 

	 	$discount_amount = 0; 

	 	if ( !empty ($coupons ) ) { 

			foreach ( $coupons as $coupon ) {

				$coupon_obj 	= new WC_Coupon( $coupon[ 'name' ] ); 
				$coupon_owner	= WCVendors_Pro_Vendor_Controller::get_vendor_from_object( $coupon_obj->id );  
				$product_owner 	= WCVendors_Pro_Vendor_Controller::get_vendor_from_object( $product_id ); 

				$coupon_user	=  get_userdata( $coupon_owner ); 

				// This checks that the coupon is created by the product owner OR the site administrator 
				if ( $coupon_owner == $product_owner || in_array( 'administrator', $coupon_user->roles ) ) { 
					$discount_amount += $coupon_obj->get_discount_amount( $product_price ); 
				}

			}

	 	} 

	 	// Apply the coupon before the commission is taken out 
	 	if ( 'no' == WCVendors_Pro::get_option( 'commission_coupon_action' ) ) {
	 		$product_price = $product_price - $discount_amount; 
	 	}
	 	
		switch ( $commission_type ) {
			case 'fixed':
				$commission      = round( $commission_amount, 2 );
				break;
			case 'fixed_fee': 
				$commission      = round( $commission_amount - $commission_fee , 2 );
				break; 
			case 'percent': 
				$commission      = $product_price * ( $commission_percent / 100 );
				$commission      = round( $commission, 2 );
				break; 
			case 'percent_fee': 
				$commission      = $product_price * ( $commission_percent / 100 );
				$commission      = round( $commission - $commission_fee, 2 );
				break; 
			default:
				$commission      = round( $commission_amount, 2 );
				break;
		}

		// Apply the coupon after the commission is taken out 
	 	if ( 'yes' == WCVendors_Pro::get_option( 'commission_coupon_action' ) ) {
	 		$commission = $commission - $discount_amount; 
	 	}

	 	// If the coupon amount is higher than the commission amount then set it to 0 
	 	if ( $commission < 0 ) $commission = 0; 

		return $commission; 

	} // process_commission() 

	/**
	 *  Save the commission detail for the post object ( Product | Store )
	 *
	 * @param    int    $post_id       post_id being saved 
	 * @since    1.0.0
	 */
	public function save_commission_details( $post_id ){ 

		if ( isset( $_POST[ 'wcv_commission_type' ] ) && '' !== $_POST[ 'wcv_commission_type' ] ) {
			update_post_meta( $post_id, 'wcv_commission_type', $_POST[ 'wcv_commission_type' ] );

			// wcv_commission_percent
			if ( isset( $_POST[ 'wcv_commission_percent' ] ) && '' !== $_POST[ 'wcv_commission_percent' ] ) {
				update_post_meta( $post_id, 'wcv_commission_percent', ( float ) $_POST[ 'wcv_commission_percent' ] );	
			} else { 
				delete_post_meta( $post_id, 'wcv_commission_percent'); 
			}

			// wcv_commission_amount
			if ( isset( $_POST[ 'wcv_commission_amount' ] ) && '' !== $_POST[ 'wcv_commission_amount' ] ) {
				update_post_meta( $post_id, 'wcv_commission_amount', ( float ) $_POST[ 'wcv_commission_amount' ] );	
			} else { 
				delete_post_meta( $post_id, 'wcv_commission_amount'); 
			}

			// wcv_commission_fee
			if ( isset( $_POST[ 'wcv_commission_fee' ] ) && '' !== $_POST[ 'wcv_commission_fee' ] ) {
				update_post_meta( $post_id, 'wcv_commission_fee', ( float ) $_POST[ 'wcv_commission_fee' ] );	
			} else { 
				delete_post_meta( $post_id, 'wcv_commission_fee'); 
			}

		} else { 
			delete_post_meta( $post_id, 'wcv_commission_type'); 
 			delete_post_meta( $post_id, 'wcv_commission_percent'); 
 			delete_post_meta( $post_id, 'wcv_commission_amount'); 
 			delete_post_meta( $post_id, 'wcv_commission_fee'); 
		}

	} //save_commission_details()

	/**
	 *  Disable the product commission tab enabled by free 
	 *
	 * @since    1.0.0
	 */
	public function update_product_meta( ) { 
		return false; 
	} // update_product_meta()

	/**
	 *  Add the product commission tab
	 *
	 * @since    1.0.0
	 */
	public function add_commission_tab() {
		
		if ( !current_user_can( 'manage_woocommerce' ) ) return;

		include( apply_filters( 'wcvendors_pro_add_commission_tab_path', 'partials/product/wcvendors-pro-product-meta-tab.php' ) ); 

	} //add_commission_tab()

	/**
	 * Add the panel to the product commission tab 
	 *
	 * @since    1.0.0
	 */
	public function add_commission_panel() {

		if ( !current_user_can( 'manage_woocommerce' ) ) return;

		global $post; 

		$commission_type 	= get_post_meta( $post->ID, 'wcv_commission_type', 		true ); 
		$commission_percent = get_post_meta( $post->ID, 'wcv_commission_percent', 	true );  
		$commission_amount 	= get_post_meta( $post->ID, 'wcv_commission_amount', 	true ); 
		$commission_fee		= get_post_meta( $post->ID, 'wcv_commission_fee', 		true ); 

		include( apply_filters( 'wcvendors_pro_add_commission_panel_path', 'partials/product/wcvendors-pro-commission-panel.php' ) ); 

	} //add_commission_panel()

	/**
	 * Save the data for the product 
	 *
	 * @param    int    $post_id       post_id being saved 
	 * @since    1.0.0
	 */
	public function save_commission_panel( $post_id ) { 
		if ( !current_user_can( 'manage_woocommerce' ) ) return;

		$this->save_commission_details( $post_id ); 

	} // save_commission_panel() 


	/**
	 *  Add new commission interface to user edit screen
	 *
	 * @since    1.0.0
	 */
	public function store_commission_meta_fields( $user ) { 

		if ( !current_user_can( 'manage_woocommerce' ) ) { return; } 

		if ( ! WCV_Vendors::is_vendor( $user->ID ) ) { return; } 

		// Get the default commission rate 
		$free_override_commission = get_user_meta( $user->ID, 'pv_custom_commission_rate', true ); 

		$commission_type 	= get_user_meta( $user->ID, '_wcv_commission_type', 	true ); 
		$commission_percent = get_user_meta( $user->ID, '_wcv_commission_percent', 	true );  
		$commission_amount 	= get_user_meta( $user->ID, '_wcv_commission_amount', 	true ); 
		$commission_fee		= get_user_meta( $user->ID, '_wcv_commission_fee', 		true ); 

		include( apply_filters( 'wcvendors_pro_store_commission_meta_fields_path', 'partials/vendor/wcvendors-pro-vendor-commission-fields.php' ) ); 

	} // store_commission_meta_fields()

	/**
	 *  Save the store commission fields on the user edit screen. 
	 *
	 * @param    int    $post_id       post_id being saved 
	 * @since    1.0.0
	 */
	public function store_commission_meta_fields_save( $vendor_id ) { 

		if ( !current_user_can( 'manage_woocommerce' ) ) { return; } 

		if ( ! WCV_Vendors::is_vendor( $vendor_id ) ) { return; } 

		if ( isset( $_POST[ '_wcv_commission_type' ] ) && '' !== $_POST[ '_wcv_commission_type' ] ) {

			update_user_meta( $vendor_id, '_wcv_commission_type', wc_clean( $_POST[ '_wcv_commission_type' ] ) );

			// _wcv_commission_percent
			if ( isset( $_POST[ '_wcv_commission_percent' ] ) && '' !== $_POST[ '_wcv_commission_percent' ] ) {
				update_user_meta( $vendor_id, '_wcv_commission_percent', ( float ) $_POST[ '_wcv_commission_percent' ] );	
			} else { 
				delete_user_meta( $vendor_id, '_wcv_commission_percent'); 
			}

			// _wcv_commission_fee
			if ( isset( $_POST[ '_wcv_commission_fee' ] ) && '' !== $_POST[ '_wcv_commission_fee' ] ) {
				update_user_meta( $vendor_id, '_wcv_commission_fee', ( float ) $_POST[ '_wcv_commission_fee' ] );	
			} else { 
				delete_user_meta( $vendor_id, '_wcv_commission_fee'); 
			}

			// _wcv_commission_amount
			if ( isset( $_POST[ '_wcv_commission_amount' ] ) && '' !== $_POST[ '_wcv_commission_amount' ] ) {
				update_user_meta( $vendor_id, '_wcv_commission_amount', ( float ) $_POST[ '_wcv_commission_amount' ] );	
			} else { 
				delete_user_meta( $vendor_id, '_wcv_commission_amount'); 
			}

		} else { 
			delete_user_meta( $vendor_id, '_wcv_commission_type'); 
 			delete_user_meta( $vendor_id, '_wcv_commission_percent'); 
 			delete_user_meta( $vendor_id, '_wcv_commission_amount'); 
 			delete_user_meta( $vendor_id, '_wcv_commission_fee'); 
		}

	} //store_commission_meta_fields_save()

	/**
	 *  Commission types
	 *
	 * @param    int    $post_id       post_id being saved 
	 * @since    1.0.0
	 */
	public static function commission_types( ) {

		return apply_filters( 'wcv_commission_types', array( 
				'fixed'			=> __( 'Fixed', 			'wcvendors-pro' ),
				'fixed_fee'		=> __( 'Fixed + fee', 		'wcvendors-pro' ),
				'percent'		=> __( 'Percentage', 		'wcvendors-pro' ),
				'percent_fee'	=> __( 'Percentage + fee', 	'wcvendors-pro' ),
				) 
		); 

	} // commission_types()


	/**
	 *  DEPRECIATED as of v1.4.0 
	 *  
	 *  Get shipping due for vendor shipping 
	 *
	 * @param    int    $post_id       post_id being saved 
	 * @since    1.0.0
	 */
	public function get_shipping_due( $shipping_costs, $order_id, $product, $vendor_id ) { 

		if ( ! class_exists( 'WCVendors_Pro_Shipping_Method' ) ){ 
			include('class-wcvendors-pro-shipping.php'); 
		} 

		$item_shipping_cost = 0;
		$shipping_costs 	= array( 'amount' => 0, 'tax' => 0 ); 
		$rate 				= false; 
		$settings 			= get_option( 'woocommerce_wcv_pro_vendor_shipping_settings' ); 
		$taxable 			= $settings[ 'tax_status' ]; 
		$product_id 		= !empty( $product[ 'variation_id' ] ) ? $product[ 'variation_id' ] : $product[ 'product_id' ]; 
		$_product 			= wc_get_product( $product_id ); 
		$product_cost 		= 0; 

		if ( $_product && $_product->needs_shipping() && !$_product->is_downloadable() ) {

			$order = new WC_Order( $order_id );

			// Get Shipping methods. 
			$shipping_methods = $order->get_shipping_methods();
			// Get the items from the order, required to get the correct price for the shipping
			$order_items = $order->get_items(); 

			$shipping_items = $order->get_items( 'shipping' ); 

			foreach ( $shipping_methods as $shipping_method ) {

				if ( 'wcv_pro_vendor_shipping' === $shipping_method[ 'method_id' ] ) { 

					$shipping_country 	 = ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->shipping_country 	: $order->get_shipping_country();
					$shipping_state 	 = ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->shipping_state 		: $order->get_shipping_state();
					$shipping_postcode 	 = ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->shipping_postcode    : $order->get_shipping_postcode(); 

					$package[ 'destination' ][ 'country' ]  = $shipping_country;
					$package[ 'destination' ][ 'state' ]    = $shipping_state;
					$package[ 'destination' ][ 'postcode' ] = $shipping_postcode;

					$product_id = $product['product_id'];

					$rate = WCVendors_Pro_Shipping_Method::get_shipping_rate( $product_id, $vendor_id, $package, $settings ); 	
			
					if ( $rate ) {

						$qty = ( $rate->qty_override == 'yes' ) ? 1 : $product[ 'qty' ];

						$item_shipping_cost += $rate->fee * $qty; 
						$item_shipping_cost += $this->get_fee( $rate->product_fee, $item_shipping_cost ) * $qty;

						foreach ( $order_items as $item ) {
							if ( $item[ 'product_id'] == $product_id ){ 
								$product_cost = $item[ 'line_subtotal' ] * $item[ 'qty' ]; 
							}
						}

						// product level overrides 
						if ( $rate->max_charge_product > 0 && $item_shipping_cost > $rate->max_charge_product ) $item_shipping_cost = $rate->max_charge_product; 
						if ( $rate->free_shipping_product > 0 && $product_cost > $rate->free_shipping_product ) $item_shipping_cost = 0; 

					} else {

						return $shipping_costs; 
					}

					$shipping_costs[ 'amount' ] = $item_shipping_cost; 
					$shipping_costs[ 'tax' ] 	= ( 'taxable' === $taxable ) ? WCV_Shipping::calculate_shipping_tax( $item_shipping_cost, $order ) : 0; 

				}
			}
		} 

		return $shipping_costs; 

	} // get_shipping_due()


	/**
	 *  Get shipping due for vendor shipping this uses the new package stored vendor_costs in the order. No need for secondary calculations 
	 *
	 * @param    int    $shipping_costs      
	 * @since    1.4.0
	 */
	public function get_shipping_due_from_order( $shipping_costs, $order_id, $product, $vendor_id ){ 

		$item_shipping_cost = 0;
		$settings 			= get_option( 'woocommerce_wcv_pro_vendor_shipping_settings' ); 
		$taxable 			= $settings[ 'tax_status' ]; 
		$shipping_costs 	= array( 'amount' => 0, 'tax' => 0 ); 
		$product_id 		= !empty( $product[ 'variation_id' ] ) ? $product[ 'variation_id' ] : $product[ 'product_id' ]; 
		$order 				= new WC_Order( $order_id ); 
		$shipping_items 	= $order->get_items( 'shipping' ); 
		$_product 			= wc_get_product( $product_id ); 

		if ( $_product && $_product->needs_shipping() && !$_product->is_downloadable() ) {

			if ( !empty( $shipping_items ) ) { 

				foreach ( $shipping_items as $shipping_item ) {

					$shipping_meta_data = $shipping_item->get_meta_data(); 

					foreach ( $shipping_meta_data as $meta ) {
						
						if ( 'vendor_costs' === $meta->key ){ 

							$package_cost = $meta->value; 
							
							foreach ( $package_cost[ 'items' ] as $key => $shipping_details ) {

								if ( $product_id == $shipping_details[ 'product_id' ] ){ 
									$item_shipping_cost = $shipping_details[ 'shipping_cost']; 
								}
							}
						}

					}
				}

				$shipping_costs[ 'amount' ] = $item_shipping_cost; 
				$shipping_costs[ 'tax' ] 	= ( 'taxable' === $taxable ) ? WCV_Shipping::calculate_shipping_tax( $item_shipping_cost, $order ) : 0; 

			}

		} 

		return $shipping_costs; 

	} // get_shipping_due_from_order() 


	/**
	 * get_fee function from woocommerce 
	 *
	 * @param mixed $fee
	 * @param mixed $total
	 * @return float
	 */
	public function get_fee( $fee, $total ) {

		if ( strstr( $fee, '%' ) ) {
			$fee = ( $total / 100 ) * str_replace( '%', '', $fee );
		}

		return $fee;
	} // get_fee() 


	/**
	 * Import the commission overrides for vendors 
	 * 
	 * @since 1.3.6
	 * @access public 
	 * @todo delete the free meta keys 
	 */
	public static function import_vendor_commission_overrides(){ 

		$all_vendor_ids	= get_users(  array(  'role' => 'vendor',  'fields'	=> 'ID' ) );

		if ( isset( $all_vendor_ids ) ){ 

			foreach ( $all_vendor_ids as $vendor_id ) {
	
				$store_free_commission = get_user_meta( $vendor_id, 'pv_custom_commission_rate', true ); 			

				// There is a free commission override. Import it into pro
				if ( isset( $store_free_commission ) ) { 
					update_user_meta( $vendor_id, '_wcv_commission_type', 	'percent' );
					update_user_meta( $vendor_id, '_wcv_commission_percent', $store_free_commission );
				}

			}

			echo '<div class="updated inline"><p>' . __( 'Vendor commission overrides successfully imported.', 'wcvendors-pro' ) . '</p></div>';

		} 

	} // import_vendor_commission_overrides() 

	/**
	 * Import the commission overrides for products 
	 * 
	 * @since 1.3.6
	 * @access public 
	 * @todo delete the free meta keys 
	 */
	public static function import_product_commission_overrides(){ 

		$all_products = get_posts( array( 'post_type' => 'product' ) ); 

		if ( isset( $all_products ) ){ 

			foreach ( $all_products as $product ) {

				$free_product_override_commission = get_post_meta( $product->ID, 'pv_commission_rate', true ); 

				if ( isset( $free_product_override_commission ) ){ 
					update_post_meta( $product->ID, 'wcv_commission_type', 'percent' );
					update_post_meta( $product->ID, 'wcv_commission_percent', $free_product_override_commission );
				}

			}

			echo '<div class="updated inline"><p>' . __( 'Product commission overrides successfully imported.', 'wcvendors-pro' ) . '</p></div>';

		}

	} // import_product_commission_overrides() 

} 