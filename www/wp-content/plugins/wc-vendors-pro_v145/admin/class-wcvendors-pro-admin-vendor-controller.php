<?php

/**
 * The admin side vendor controller functions 
 *
 * This controller looks after all admin vendor features for pro. 
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/admin
 * @author     Jamie Madden <support@wcvendors.com>
 */

class WCVendors_Pro_Admin_Vendor_Controller {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      string    $wcvendors_pro    The ID of this plugin.
	 */
	private $wcvendors_pro;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Is the plugin in debug mode 
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      bool    $debug    plugin is in debug mode 
	 */
	private $debug;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.2.0
	 * @param    string    $wcvendors_pro   The name of this plugin.
	 * @param    string    $version    		The version of this plugin.
	 * @param    bool    $debug    			Plugin in debug mode
	 */
	public function __construct( $wcvendors_pro, $version, $debug ) {

		$this->wcvendors_pro 	= $wcvendors_pro;
		$this->version 			= $version;
		$this->debug 			= $debug; 
		$this->base_dir			= plugin_dir_url( __FILE__ ); 

	}

	/**
	 * Get all Custom pro user fields 
	 *
	 * @since    1.2.0
	 */
	public function get_pro_user_meta_fields( $user ){ 

		return $fields = apply_filters( 'wcv_custom_user_fields', array( 
			'store_general' => array( 
				'title'  	=> __( 'Store General' ), 	
				'fields'	=> array( 
					'_wcv_verified_vendor' => array(
						'label'       => __( 'Verified Vendor', 'wcvendors-pro' ),
						'description' => __( ' Check to publish that this vendor is verified by the store admin..', 'wcvendors-pro' ),
						'type'		  => 'checkbox', 
					),
					'_wcv_trusted_vendor' => array(
						'label'       => __( 'Trusted Vendor', 'wcvendors-pro' ),
						'description' => __( ' Check to allow this vendor to publish products immediately regardless of global publishing rules.', 'wcvendors-pro' ),
						'type'		  => 'checkbox', 
					),
					'_wcv_untrusted_vendor' => array(
						'label'       => __( 'Untrusted Vendor', 'wcvendors-pro' ),
						'description' => __( ' Check to require all products submitted to be reviewed, regardless of global publishing rules. This overrides the trusted vendor option.', 'wcvendors-pro' ),
						'type'		  => 'checkbox', 
					),
					'_wcv_lock_new_products_vendor' => array(
						'label'       => __( 'Lock New Products', 'wcvendors-pro' ),
						'description' => __( 'Lock vendor from creating any new products.', 'wcvendors-pro' ),
						'type'		  => 'checkbox', 
					),
					'_wcv_lock_new_products_vendor_msg' => array(
						'label'       => __( 'Lock New Products Message', 'wcvendors-pro' ),
						'value' 	  => __( 'Your access to create new products has been disabled.', 'wcvendors-pro' ), 
						'description' => '', 
						'type'		  => 'textarea'
					),
					'_wcv_lock_edit_products_vendor' => array(
						'label'       => __( 'Lock edit products', 'wcvendors-pro' ),
						'description' => __( 'Lock vendor from editing any existing products', 'wcvendors-pro' ),
						'type'		  => 'checkbox', 
					),
					'_wcv_lock_edit_products_vendor_msg' => array(
						'label'       => __( 'Lock Edit Products Message', 'wcvendors-pro' ),
						'value' 	  => __( 'Your access to edit products has been disabled.', 'wcvendors-pro' ), 
						'type'		  => 'textarea', 
						'description' => ''
					),
					'_wcv_company_url' => array(
						'label'       => __( 'Store Website / Blog URL', 'wcvendors-pro' ),
						'description' => ''
					),
				), 
			), 
			'store_address' => array( 
				'title'  	=> __( 'Store Address' ), 	
				'fields'	=> array( 
					'_wcv_store_address1' => array(
						'label'       => __( 'Address 1', 'wcvendors-pro' ),
						'description' => ''
					),
					'_wcv_store_address2' => array(
						'label'       => __( 'Address 2', 'wcvendors-pro' ),
						'description' => ''
					),
					'_wcv_store_city' => array(
						'label'       => __( 'City', 'wcvendors-pro' ),
						'description' => ''
					),
					'_wcv_store_postcode' => array(
						'label'       => __( 'Postcode', 'wcvendors-pro' ),
						'description' => ''
					),
					'_wcv_store_country' => array(
						'label'       => __( 'Country', 'wcvendors-pro' ),
						'description' => '',
						'class'       => 'js_field-country',
						'type'        => 'select',
						'options'     => array( '' => __( 'Select a country&hellip;', 'wcvendors-pro' ) ) + WC()->countries->get_allowed_countries()
					),
					'_wcv_store_state' => array(
						'label'       => __( 'State/County', 'wcvendors-pro' ),
						'description' => __( 'State/County or state code', 'wcvendors-pro' ),
						'class'       => 'js_field-state'
					),
					'_wcv_store_phone' => array(
						'label'       => __( 'Telephone', 'wcvendors-pro' ),
						'description' => ''
					),
				), 
			),
			'store_social' => array( 
				'title'  	=> __( 'Store Social' ), 	
				'fields'	=> array( 
					'_wcv_twitter_username' => array(
						'label'       => __( 'Twitter', 'wcvendors-pro' ),
						'description' => __( '<a href="https://twitter.com/">Twitter</a> username without the url.', 'wcvendors-pro' ), 
					), 
					'_wcv_instagram_username' => array(
						'label'       => __( 'Instagram', 'wcvendors-pro' ),
						'description' => __( '<a href="https://instagram.com/">Instagram</a> username without the url.', 'wcvendors-pro' ), 
					), 
					'_wcv_facebook_url' => array(
						'label'       => __( 'Facebook', 'wcvendors-pro' ),
						'description' => __( '<a href="https://facebook.com/">Facebook</a> url.', 'wcvendors-pro' ), 
					),
					'_wcv_linkedin_url' => array(
						'label'       => __( 'LinkedIn', 'wcvendors-pro' ),
						'description' => __( '<a href="https://linkedin.com/">LinkedIn</a> url.', 'wcvendors-pro' ), 
					),
					'_wcv_youtube_url' => array(
						'label'       => __( 'YouTube', 'wcvendors-pro' ),
						'description' => __( '<a href="https://youtube.com/">Youtube</a> url.', 'wcvendors-pro' ), 
					),
					'_wcv_googleplus_url' => array(
						'label'       => __( 'Google+', 'wcvendors-pro' ),
						'description' => __( '<a href="https://plus.google.com">Google+</a> url.', 'wcvendors-pro' ), 
					),
					'_wcv_pinterest_url' => array(
						'label'       => __( 'Pinterest', 'wcvendors-pro' ),
						'description' => __( '<a href=https://www.pinterest.com/">Pinterest</a> url.', 'wcvendors-pro' ), 
					),
					'_wcv_snapchat_username' => array(
						'label'       => __( 'Snapchat', 'wcvendors-pro' ),
						'description' => __( 'Snapchat username.', 'wcvendors-pro' ), 
					), 

				), 
			), 
		)); 

	} // get_pro_user_meta_fields()


	/**
	 * Show the Pro vendor store fields 
	 *
	 * @since    1.2.0
	 * @param WP_User $user
	 */
	public function add_pro_vendor_meta_fields( $user ) {

		if ( ! current_user_can( 'manage_woocommerce' ) ) { return; }

		if ( ! WCV_Vendors::is_vendor( $user->ID ) && ! WCV_Vendors::is_pending( $user->ID ) ) { return; } 

		$fields = $this->get_pro_user_meta_fields( $user );
		
		include( apply_filters( 'wcv_partial_path_pro_user_meta', 'partials/vendor/wcvendors-pro-user-meta.php' ) ); 
		include( apply_filters( 'wcv_partial_path_pro_user_meta_branding', 'partials/vendor/wcvendors-pro-user-meta-branding.php' ) ); 

	}

	/**
	 * Save the pro vendor store fields 
	 *
	 * @since    1.2.0
	 * @param WP_User $user
	 */
	public function save_pro_vendor_meta_fields( $vendor_id ) {

		if ( ! current_user_can( 'manage_woocommerce' ) ) { return; }

		$user = get_user_by( 'id', $vendor_id ); 

		if ( ! WCV_Vendors::is_pending( $vendor_id ) && ! WCV_Vendors::is_vendor(  $vendor_id ) ) { return; }
		
		$save_fields = $this->get_pro_user_meta_fields( $user );

		foreach ( $save_fields as $fieldset ) {

			foreach ( $fieldset['fields'] as $key => $field ) {

				if ( isset( $_POST[ $key ] ) ) {

					// Set the correct value for a check box
					if ( array_key_exists( 'type', $field ) && 'checkbox' == $field[ 'type' ] ) { 
						$value = 'yes'; 
					} else { 
						$value = $_POST[ $key ]; 
					}

					update_user_meta( $vendor_id, $key, wc_clean( $value ) );

				} else { 
					delete_user_meta( $vendor_id, $key );
				}
			}
		}

		// Banner 
		if ( isset( $_POST[ '_wcv_store_banner_id' ] ) ) {
			update_user_meta( $vendor_id, '_wcv_store_banner_id', wc_clean( $_POST[ '_wcv_store_banner_id' ] ) );
		} else { 
			delete_user_meta( $vendor_id, '_wcv_store_banner_id' );
		}

		// Icon 
		if ( isset( $_POST[ '_wcv_store_icon_id' ] ) ) {
			update_user_meta( $vendor_id, '_wcv_store_icon_id', wc_clean( $_POST[ '_wcv_store_icon_id' ] ) );
		} else { 
			delete_user_meta( $vendor_id, '_wcv_store_icon_id' );
		}

	} // save_pro_vendor_meta_fields()

	/**
	 * Output a vendor drop down to restrict the product type by
	 *
	 * @since    1.3.0
	 */
	public function restrict_manage_posts() {

		global $typenow, $wp_query;

		if ( 'product' == $typenow ) {
			$users 	= get_users( array( 
				'role'		=> 'vendor',
				'fields' 	=> array( 
					'ID', 'user_login', 
				) 
			) );
			include( apply_filters( 'wcvendors_pro_restrict_manage_posts_path', 'partials/vendor/wcvendors-pro-vendor-dropdown.php' ) ); 
			echo $output;
		}

	} //restrict_manage_posts()

	/**
	 * Filter wp query for the product post type 
	 *
	 * @since    1.3.0
	 */
	public function vendor_filter_query( $query ) {

		global $typenow, $wp_query;

		if ( 'product' == $typenow ) {

			if ( isset( $_GET[ 'vendor_id' ] ) ) { 

				$query->query_vars[ 'author' ] = $_GET[ 'vendor_id' ]; 

			}

		} 

	} // vendor_filter_query()

} 