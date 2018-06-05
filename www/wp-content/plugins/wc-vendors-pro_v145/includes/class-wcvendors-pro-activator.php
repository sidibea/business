<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/includes
 * @author     Jamie Madden <support@wcvendors.com>
 * @link       http://www.wcvendors.com
 */
class WCVendors_Pro_Activator {


	/**
	 * The vendor feedback tablename 
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WCVendors_Pro_Activator    $feedback_tbl_name    Vendor Feedback table name
	 */
	public static $feedback_tbl_name; 

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 * @todo 	 check which version of WC Vendors is installed. 
	 */
	public static function activate( $plugin_file ) {

		$php_version = phpversion();

		/**
		 *  Requires PHP 5.4.0 to function
		 */
		if ( version_compare( $php_version, '5.4', '<' ) ) {
			deactivate_plugins( $plugin_file );
			wp_die( __( 'WC Vendors Pro requires PHP 5.4 or newer to function.  Please call your webhosting company and have them upgrade your hosting account to a version of PHP 5.4 or newer.', 'wcvendors-pro' ) );
		}

		/**
		 *  Requires woocommerce to be installed and active 
		 */
		if ( !class_exists( 'WooCommerce' ) ) { 
			deactivate_plugins( $plugin_file );
			wp_die( __( 'WC Vendors Pro requires WooCommerce to run. Please install WooCommerce and activate before attempting to activate again.', 'wcvendors-pro' ) );
		}

		/**
		 *  Requires WC Vendors to be installed and active 
		 */
		if ( !class_exists( 'WC_Vendors' ) ) { 
			deactivate_plugins( $plugin_file );
			wp_die( __( 'WC Vendors Pro requires WC Vendors to run. Please install WC Vendors and activate before attempting to activate again.', 'wcvendors-pro' ) );
		}

		$db_version = get_option( 'wcvendors_pro_db_version', '0.1' ); 	
		self::$feedback_tbl_name  = 'wcv_feedback'; 

		// Initial Install 
		if ( version_compare( $db_version, '1.0', '<' ) ) {

			self::create_feedback_table(); 
			self::create_pages(); 
			
		} elseif ( version_compare( $db_version, '1.1', '<' ) ) {

			self::update_to( '1.1' ); 	

		} elseif ( version_compare( $db_version, '1.2', '<' ) ) {

			self::update_to( '1.2' ); 	
		} 

		// Make permalinks flush after adding pages 
		update_option( WC_Vendors::$id . '_flush_rules', true );

	}

	/**
	 * Create the vendor ratings table
	 *
	 * Stores relevant vendor ratings feedback from customer orders. 
	 *
	 * @since    1.0.0
	 */
	public static function create_feedback_table()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . self::$feedback_tbl_name;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			rating bigint(20) NOT NULL,
			order_id bigint(20) NOT NULL,
			vendor_id bigint(20) NOT NULL,
			product_id bigint(20) NOT NULL, 
			customer_id bigint(20) NOT NULL,
			rating_title varchar(255),
			comments varchar(255),
			postdate timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		add_option( 'wcvendors_pro_db_version', '1.0' );

		self::update_to( '1.1' ); 
	}

	/**
	 * Output a message if WC Vendors isn't active 
	 *
	 * Stores relevant vendor ratings feedback from customer orders. 
	 *
	 * @since    1.0.0
	 */
	public static function installation_fail(){ 
		echo '<div class="error"><p>' . __( '<b>WC Vendors Pro is disabled</b>. WC Vendors Pro requires WC Vendors v1.7.0 or higher to operate.', 'wcvendors-pro' ) . '</p></div>';
	}

	/**
	 *  Create the new pages for pro 
	 * 
	 * @since    1.0.0
	 */
	public static function create_pages(){ 

		// Create the Pro dashboard page and populate it with the shortcode
		self::create_page( 'dashboard', __( 'Pro Dashboard', 'wcvendors-pro' ), '[wcv_pro_dashboard]' );

		// Nest the feedback page under my-account 
		$my_account_page = wc_get_page_id( 'myaccount' ); 
		self::create_page( 'feedback', __( 'Feedback', 'wcvendors-pro' ), '[wcv_feedback_form]', $my_account_page );

	} // create_pages()
	
	/**
	 *  Create a new page 
	 * 
	 * @since    1.0.0
	 *
	 * @param mixed  $slug         Slug for the new page
	 * @param mixed  $option       Option name to store the page's ID
	 * @param string $page_title   (optional) (default: '') Title for the new page
	 * @param string $page_content (optional) (default: '') Content for the new page
	 * @param int    $post_parent  (optional) (default: 0) Parent for the new page
	 * @return int 	 $page_id  	    new or existing page id
	 */
	public static function create_page( $slug, $page_title = '', $page_content = '', $post_parent = 0 ) {

		global $wpdb;

		$page_id = WC_Vendors::$pv_options->get_option( $slug . '_page_id' );

		if ( $page_id > 0 && get_post( $page_id ) ) {
			update_option( 'wcv_' . $slug . '_page_id', $page_id ); 
			return $page_id;
		}

		$page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_name = %s LIMIT 1;", $slug ) );

		if ( $page_found ) {
			if ( !$page_id ) {
				WC_Vendors::$pv_options->update_option( $slug . '_page_id', $page_found );
				update_option( 'wcv_' . $slug . '_page_id', $page_found ); 
				return $page_found;
			}

			return $page_id;
		}

		$page_data = array(
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => 1,
			'post_name'      => $slug,
			'post_title'     => $page_title,
			'post_content'   => $page_content,
			'post_parent'    => $post_parent,
			'comment_status' => 'closed'
		);

		$page_id = wp_insert_post( $page_data );
		
		add_option( 'wcv_' . $slug . '_page_id', $page_id ); 

		WC_Vendors::$pv_options->update_option( $slug . '_page_id', $page_id );

		return $page_id;

	} //create_page()


	/**
	 * Update To
	 *
	 * Update method to allow version specific code to fire  
	 *
	 * @since    1.1.4
	 */
	public static function update_to( $version ) {
		
		global $wpdb;

		switch ( $version ) {

			case '1.1':
				// Alter existing installs 
				$table_name = $wpdb->prefix . self::$feedback_tbl_name;
				$alter_sql = "ALTER TABLE $table_name CHANGE `comments` `comments` LONGTEXT NULL DEFAULT NULL"; 
				$wpdb->query( $alter_sql );
				update_option( 'wcvendors_pro_db_version', '1.1' ); 
				break;	
			case '1.2': 
				// Remove Vendor Stores 
				self::remove_vendor_stores(); 
				update_option( 'wcvendors_pro_db_version', '1.2' ); 
				break; 
			default:
				# code...
				break;
		}
		
	} // update_to() 


	/**
	 * Remove Vendor Stores
	 *
	 * Remove the vendor stores and post type from the system. 
	 *
	 * @since    1.2.0
	 */
	public static function remove_vendor_stores(){ 

		$vendors        	= get_users(  array(  'role' => 'vendor',  'fields'	=> 'ID' ) );

		if ( post_type_exists( 'vendor_store' ) ) { 

			foreach ( $vendors as $vendor_id ) {

				$post_args = array( 
						'post_type'  		=> 'vendor_store', 
						'author'	 		=> $vendor_id, 
						'order_by' 	 		=> 'date', 
						'posts_per_page' 	=> 1,
				); 

				$store = get_posts( $post_args ); 
				$vendor_store = reset( $store ); 

				$store_shipping 	= get_post_meta( $vendor_store->ID, '_wcv_shipping', true ); 
				$vendor_shipping 	= get_user_meta( $vendor_id, '_wcv_shipping', true ); 
				
				if ( ! is_array( $vendor_shipping ) ) { 
					update_user_meta( $vendor_id, '_wcv_shipping', 	$store_shipping ); 	
				}

				// Fix migration script for shipping details 
				$store_shipping_rates 	= get_post_meta( $vendor_store->ID, '_wcv_shipping_rates', true ); 
				$vendor_shipping_rates	= get_user_meta( $vendor_id, '_wcv_shipping_rates', true ); 

				if ( ! is_array( $vendor_shipping ) ) { 
					update_user_meta( $vendor_id, '_wcv_shipping_rates',  $store_shipping_rates  );
				}
				
				$store_icon_id = get_post_meta( $vendor_store->ID, '_wcv_store_icon_id', 	true );  	
				$vendor_icon_id = get_user_meta( $vendor_id, '_wcv_store_icon_id', true );  	

				// Store Icon 
				if ( '' !== $store_icon_id && '' === $vendor_icon_id ) { 
					update_user_meta( $vendor_id, '_wcv_store_icon_id', 	$store_icon_id );  	
				}

				// Company URL  
				$store_company_url = get_post_meta( $vendor_store->ID, '_wcv_company_url', 	true );  	
				$vendor_company_url = get_user_meta( $vendor_id, '_wcv_company_url', true );  

				if ( '' !== $store_company_url && '' === $vendor_company_url ) { 
					update_user_meta( $vendor_id, '_wcv_company_url', 	$store_company_url ); 
				} 

				// Store Address1 
				$address1 = get_post_meta( $vendor_store->ID, '_wcv_store_address1', 	true );  	
				$vendor_address1 = get_user_meta( $vendor_id, '_wcv_store_address1', true );  

				if ( '' !== $address1 && '' === $vendor_address1 ) { 
					update_user_meta( $vendor_id, '_wcv_store_address1', 	$address1 ); 
				} 

				$address2 = get_post_meta( $vendor_store->ID, '_wcv_store_address2', 	true );  	
				$vendor_address2 = get_user_meta( $vendor_id, '_wcv_store_address2', true );  

				// Store Address2 
				if ( '' !== $address2 && '' === $vendor_address2 ) { 
					update_user_meta( $vendor_id, '_wcv_store_address2', 	$address2 ); 
				} 

				// Store City 
				$city = get_post_meta( $vendor_store->ID, '_wcv_store_city', 	true );  	
				$vendor_city = get_user_meta( $vendor_id, '_wcv_store_city', true ); 

				if ( '' !== $city && '' === $vendor_city ) { 
					update_user_meta( $vendor_id, '_wcv_store_city', 	$city ); 
				} 

				// Store State 
				$state = get_post_meta( $vendor_store->ID, '_wcv_store_state', 	true );  	
				$vendor_state = get_user_meta( $vendor_id, '_wcv_store_state', true ); 

				if ( '' !== $state && '' === $vendor_state ) { 
					update_user_meta( $vendor_id, '_wcv_store_state', 	$state ); 
				}

				// Store Country 
				$country = get_post_meta( $vendor_store->ID, '_wcv_store_country', 	true );  	
				$vendor_country = get_user_meta( $vendor_id, '_wcv_store_country', true ); 

				if ( '' !== $country && '' === $country ) { 
					update_user_meta( $vendor_id, '_wcv_store_country', 	$country ); 
				} 

				// Store post code 
				$postcode = get_post_meta( $vendor_store->ID, '_wcv_store_postcode', true );  	
				$vendor_postcode = get_user_meta( $vendor_id, '_wcv_store_postcode', true ); 

				if ( '' !== $postcode && '' === $vendor_postcode ) { 
					update_user_meta( $vendor_id, '_wcv_store_postcode', 	$postcode ); 
				} 

				// Store Phone
				$store_phone = get_post_meta( $vendor_store->ID, '_wcv_store_phone', true );  	
				$vendor_phone = get_user_meta( $vendor_id, '_wcv_store_phone', true ); 

				if ( '' !== $store_phone && '' === $vendor_phone ) { 
					update_user_meta( $vendor_id, '_wcv_store_phone', 	$store_phone ); 
				}
			
				// Twitter Username
				$twitter_username = get_post_meta( $vendor_store->ID, '_wcv_twitter_username', true );  	
				$vendor_twitter_username = get_user_meta( $vendor_id, '_wcv_twitter_username', true ); 

				if ( '' !== $twitter_username && '' === $vendor_twitter_username ) { 
					update_user_meta( $vendor_id, '_wcv_twitter_username', 	$twitter_username ); 
				} 
				
				//Instagram Username 
				$instagram_username = get_post_meta( $vendor_store->ID, '_wcv_instagram_username', true );  	
				$vendor_instagram_username = get_user_meta( $vendor_id, '_wcv_instagram_username', true ); 

				if ( '' !== $instagram_username && '' === $vendor_instagram_username ) { 
					update_user_meta( $vendor_id, '_wcv_instagram_username', 	$instagram_username ); 
				}

				// Facebook URL
				$facebook_url = get_post_meta( $vendor_store->ID, '_wcv_facebook_url', true );  	
				$vendor_facebook_url = get_user_meta( $vendor_id, '_wcv_facebook_url', true ); 

				if ( '' !== $facebook_url && '' === $vendor_facebook_url ) { 
					update_user_meta( $vendor_id, '_wcv_facebook_url', 	$facebook_url ); 
				} 
				
				// LinkedIn URL
				$linkedin_url = get_post_meta( $vendor_store->ID, '_wcv_linkedin_url', true );  	
				$vendor_linkedin_url = get_user_meta( $vendor_id, '_wcv_linkedin_url', true ); 

				if ( '' !== $linkedin_url && '' === $vendor_linkedin_url ) { 
					update_user_meta( $vendor_id, '_wcv_linkedin_url', 	$linkedin_url ); 
				}

				// YouTube URL
				$youtube_url = get_post_meta( $vendor_store->ID, '_wcv_youtube_url', true );  	
				$vendor_youtube_url = get_user_meta( $vendor_id, '_wcv_youtube_url', true ); 

				if ( '' !== $youtube_url && '' === $vendor_youtube_url ) { 
					update_user_meta( $vendor_id, '_wcv_youtube_url', 	$youtube_url ); 
				}

				// Pinterest URL
				$pinterest_url = get_post_meta( $vendor_store->ID, '_wcv_pinterest_url', true );  	
				$vendor_pinterest_url = get_user_meta( $vendor_id, '_wcv_pinterest_url', true ); 

				if ( '' !== $pinterest_url && '' === $vendor_pinterest_url ) { 
					update_user_meta( $vendor_id, '_wcv_pinterest_url', 	$pinterest_url ); 
				} 

				// Google+ URL
				$googleplus_url = get_post_meta( $vendor_store->ID, '_wcv_googleplus_url', true );  	
				$vendor_googleplus_url = get_user_meta( $vendor_id, '_wcv_googleplus_url', true ); 

				if ( '' !== $googleplus_url && '' === $vendor_googleplus_url ) { 
					update_user_meta( $vendor_id, '_wcv_googleplus_url', 	$googleplus_url ); 
				}

				// Commission 
				$store_store_commission_type 	= get_post_meta( $vendor_store->ID, 'wcv_commission_type', 	 true ); 
				$vendor_store_commission_type 	= get_user_meta( $vendor_id, '_wcv_commission_type', 	 true ); 

				if ( '' !== $store_store_commission_type && '' === $vendor_store_commission_type ) { 
					update_user_meta( $vendor_id, '_wcv_commission_type', 	 $store_store_commission_type ); 
				}

				$store_commission_percent 		= get_post_meta( $vendor_store->ID, 'wcv_commission_percent',  true );  
				$vendor_commission_percent 		= get_user_meta( $vendor_id, '_wcv_commission_percent',  true ); 

				if ( '' !== $store_commission_percent && '' === $vendor_commission_percent ){ 
					update_user_meta( $vendor_id, '_wcv_commission_percent', 	 $store_commission_percent ); 
				}

				$store_commission_amount 		= get_post_meta( $vendor_store->ID, 'wcv_commission_amount',	 true ); 
				$vendor_commission_amount 		= get_user_meta( $vendor_id, '_wcv_commission_amount',	 true ); 

				if ( '' !== $store_commission_amount && '' === $vendor_commission_amount ){ 
					update_user_meta( $vendor_id, '_wcv_commission_amount', 	 $store_commission_amount ); 
				}

				$store_commission_fee			= get_post_meta( $vendor_store->ID, 'wcv_commission_fee', 	 true ); 
				$vendor_commission_fee			= get_user_meta( $vendor_id, '_wcv_commission_fee', 	 true ); 

				if ( '' !== $store_commission_fee && '' === $vendor_commission_fee ){ 
					update_user_meta( $vendor_id, '_wcv_commission_fee', 	 $store_commission_fee ); 
				}

				// Featured image 
				if ( get_post_thumbnail_id( $vendor_store->ID ) ) { 
					update_user_meta( $vendor_id, '_wcv_store_banner_id', get_post_thumbnail_id( $vendor_store->ID ) ); 
				}

				// Custom metas 
				$custom_metas = array_intersect_key( $store_meta, array_flip( preg_grep('/^_wcv_custom_settings_/', array_keys( $store_meta ) ) ) );

				foreach ( $custom_metas as $key => $value ){

					$store_post_meta 	= get_post_meta( $vendor_store->ID, $key, true ); 
					$user_post_meta		= get_user_meta( $vendor_id, $key,  true ); 

					if ( '' !== $store_post_meta && '' === $user_post_meta ) { 
						update_user_meta( $vendor_id, $key, $store_post_meta ); 
					}
				}

			} 

		}

	} // remove_vendor_stores()
}