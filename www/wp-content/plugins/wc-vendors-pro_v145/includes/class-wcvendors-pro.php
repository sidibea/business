<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/includes
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WCVendors_Pro_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $wcvendors_pro    The string used to uniquely identify this plugin.
	 */
	protected $wcvendors_pro;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Is the plugin base directory 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $base_dir  string path for the plugin directory 
	 */
	private $base_dir;

	/**
	 * Is the plugin in debug mode 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      bool    $debug    plugin is in debug mode 
	 */
	private $debug;

	static $wcvendors_id = 'wc_prd_vendor'; 

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->wcvendors_pro = 'wcvendors-pro';
		$this->version = WCV_PRO_VERSION;
		$this->debug = false;

		$this->load_dependencies();
		$this->set_locale();

		// Admin Objects 
		$this->wcvendors_pro_admin = new WCVendors_Pro_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_debug() ); 
		$this->wcvendors_pro_commission_controller = new WCVendors_Pro_Commission_Controller( $this->get_plugin_name(), $this->get_version(), $this->get_debug() ); 
		$this->wcvendors_pro_shipping_controller = new WCVendors_Pro_Shipping_Controller( $this->get_plugin_name(), $this->get_version(), $this->get_debug() ); 
		$this->wcvendors_pro_admin_vendor_controller = new WCVendors_Pro_Admin_Vendor_Controller( $this->get_plugin_name(), $this->get_version(), $this->get_debug() ); 

		// Public Objects 
		$this->wcvendors_pro_public = new WCVendors_Pro_Public( $this->get_plugin_name(), $this->get_version(), $this->get_debug() );
		$this->wcvendors_pro_dashboard = new WCVendors_Pro_Dashboard( $this->get_plugin_name(), $this->get_version(), $this->get_debug() );
		$this->wcvendors_pro_product_controller = new WCVendors_Pro_Product_Controller( $this->get_plugin_name(), $this->get_version(), $this->get_debug() );
		$this->wcvendors_pro_order_controller = new WCVendors_Pro_Order_Controller( $this->get_plugin_name(), $this->get_version(), $this->get_debug() );
		$this->wcvendors_pro_shop_coupon_controller = new WCVendors_Pro_Shop_Coupon_Controller( $this->get_plugin_name(), $this->get_version(), $this->get_debug() );
		$this->wcvendors_pro_report_controller = new WCVendors_Pro_Reports_Controller( $this->wcvendors_pro, $this->version, $this->get_debug()  ); 
		$this->wcvendors_pro_vendor_controller = new WCVendors_Pro_Vendor_Controller( $this->wcvendors_pro, $this->version, $this->get_debug()  ); 
		$this->wcvendors_pro_product_form = new WCVendors_Pro_Product_Form( $this->wcvendors_pro, $this->version, $this->get_debug() );  
		$this->wcvendors_pro_store_form = new WCVendors_Pro_Store_Form( $this->wcvendors_pro, $this->version, $this->get_debug() );  

		// Shared Objects
		$this->wcvendors_pro_ratings_controller = new WCVendors_Pro_Ratings_Controller( $this->get_plugin_name(), $this->get_version(), $this->get_debug() ); 

		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shared_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WCVendors_Pro_Loader. Orchestrates the hooks of the plugin.
	 * - WCVendors_Pro_i18n. Defines internationalization functionality.
	 * - WCVendors_Pro_Admin. Defines all hooks for the dashboard.
	 * - WCVendors_Pro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wcvendors-pro-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wcvendors-pro-i18n.php';

		/**
		 *  A utility class for use throughout the plugin 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wcvendors-pro-utils.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wcvendors-pro-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wcvendors-pro-commission-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wcvendors-pro-shipping-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wcvendors-pro-admin-vendor-controller.php';

		/**
		 *  The classes that are shared between both admin and public 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wcvendors-pro-ratings-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wcvendors-pro-product-dropdown-walker.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wcvendors-pro-product-category-checklist.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wcvendors-pro-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wcvendors-pro-form-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wcvendors-pro-table-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wcvendors-pro-dashboard.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wcvendors-pro-product-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wcvendors-pro-order-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wcvendors-pro-vendor-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wcvendors-pro-shop-coupon-controller.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wcvendors-pro-reports-controller.php';

		/**
		 *   All forms for the public facing side 
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/forms/class-wcvendors-pro-store-form.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/forms/class-wcvendors-pro-tracking-number-form.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/forms/class-wcvendors-pro-coupon-form.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/forms/class-wcvendors-pro-product-form.php';

		$this->loader = new WCVendors_Pro_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WCVendors_Pro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WCVendors_Pro_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->wcvendors_pro . '.php' );

		$wc_prd_vendor_options 	= get_option( 'wc_prd_vendor_options' ); 
		$shipping_disabled		= ( isset( $wc_prd_vendor_options[ 'shipping_management_cap' ] ) ) ? $wc_prd_vendor_options[ 'shipping_management_cap' ] : true;

		// Register admin actions 
		$this->loader->add_action( 'admin_enqueue_scripts',					$this->wcvendors_pro_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'wcvendors_option_updates',				$this->wcvendors_pro_admin, 'options_updated', 10, 2 );
		
		$this->loader->add_action( 'init', 									$this->wcvendors_pro_admin, 'admin_lockout' );	
		$this->loader->add_action( 'woocommerce_system_status_report', 		$this->wcvendors_pro_admin, 'wcvendors_pro_system_status' );	
		$this->loader->add_action( 'woocommerce_system_status_report', 		$this->wcvendors_pro_admin, 'wcvendors_pro_template_status' );	

		$this->loader->add_filter( 'woocommerce_debug_tools',				$this->wcvendors_pro_admin, 'wc_pro_tools' );
	
		// @todo replace this with the plugin_basename once work out how to correct the path to wcvendors-pro instead of wc-vendors-pro
		$this->loader->add_action( 'plugin_action_links_'. $plugin_basename, 	$this->wcvendors_pro_admin, 	'add_action_links' );

		$this->loader->add_filter( 'wcv_commission_rate', 					$this->wcvendors_pro_commission_controller, 'process_commission', 10, 5 );
		// $this->loader->add_action( 'wcvendors_shipping_due' , 				$this->wcvendors_pro_commission_controller, 'get_shipping_due', 10, 4 );			
		$this->loader->add_action( 'wcvendors_shipping_due' , 				$this->wcvendors_pro_commission_controller, 'get_shipping_due_from_order', 10, 4 );			

		// Product Meta Commission Tab 
		// disable free commission tabs
		$this->loader->add_filter( 'wcv_product_commission_tab' , 			$this->wcvendors_pro_commission_controller, 	'update_product_meta' );	
		$this->loader->add_action( 'woocommerce_product_write_panel_tabs', 	$this->wcvendors_pro_commission_controller,  	'add_commission_tab' ); 
		$this->loader->add_action( 'woocommerce_product_data_panels', 		$this->wcvendors_pro_commission_controller,  	'add_commission_panel' ); 
		$this->loader->add_action( 'woocommerce_process_product_meta', 		$this->wcvendors_pro_commission_controller,  	'save_commission_panel' );

		// Vendor Commission Overrides 
		$this->loader->add_action( 'show_user_profile', 					$this->wcvendors_pro_commission_controller,  	'store_commission_meta_fields', 11 ); 
		$this->loader->add_action( 'edit_user_profile', 					$this->wcvendors_pro_commission_controller,  	'store_commission_meta_fields', 11 ); 
		$this->loader->add_action( 'personal_options_update', 				$this->wcvendors_pro_commission_controller,  	'store_commission_meta_fields_save', 11 ); 
		$this->loader->add_action( 'edit_user_profile_update', 				$this->wcvendors_pro_commission_controller,  	'store_commission_meta_fields_save', 11 ); 

		// Vendor Controller 
		$this->loader->add_action( 'edit_user_profile', 					$this->wcvendors_pro_admin_vendor_controller, 	'add_pro_vendor_meta_fields', 11 );
		$this->loader->add_action( 'show_user_profile', 					$this->wcvendors_pro_admin_vendor_controller, 	'add_pro_vendor_meta_fields', 11 );

		$this->loader->add_action( 'personal_options_update', 				$this->wcvendors_pro_admin_vendor_controller, 	'save_pro_vendor_meta_fields' );
		$this->loader->add_action( 'edit_user_profile_update', 				$this->wcvendors_pro_admin_vendor_controller, 	'save_pro_vendor_meta_fields' );
		$this->loader->add_action( 'restrict_manage_posts', 				$this->wcvendors_pro_admin_vendor_controller, 	'restrict_manage_posts', 12 );
		$this->loader->add_filter( 'parse_query', 							$this->wcvendors_pro_admin_vendor_controller, 	'vendor_filter_query' );

		// Check shipping capability. 
		if ( ! $shipping_disabled ) { 

			// Shipping calculator 
			$this->loader->add_action( 'woocommerce_shipping_init',		$this->wcvendors_pro_admin, 'wcvendors_pro_shipping_init' );
			$this->loader->add_filter( 'woocommerce_shipping_methods',	$this->wcvendors_pro_admin, 'wcvendors_pro_shipping_method' );

			// Shipping Controller 
			$this->loader->add_action( 'woocommerce_product_tabs', 				$this->wcvendors_pro_shipping_controller, 	'shipping_panel_tab', 11, 2 );
			
			
			// Store Shipping Override for User Meta
			$this->loader->add_action( 'personal_options_update', 				$this->wcvendors_pro_shipping_controller,  	'save_vendor_shipping_user', 11 ); 
			$this->loader->add_action( 'edit_user_profile_update', 				$this->wcvendors_pro_shipping_controller,  	'save_vendor_shipping_user', 11 );
			$this->loader->add_action( 'edit_user_profile', 					$this->wcvendors_pro_shipping_controller, 	'add_pro_vendor_meta_fields', 11 );
			$this->loader->add_action( 'show_user_profile', 					$this->wcvendors_pro_shipping_controller, 	'add_pro_vendor_meta_fields', 11 ); 
			$this->loader->add_action( 'wcv_admin_after_shipping_flat_rate', 	$this->wcvendors_pro_shipping_controller,  	'add_pro_vendor_country_rate_fields', 11 );

			// Shipping Product edit 
			$this->loader->add_action( 'woocommerce_product_options_shipping', 	$this->wcvendors_pro_shipping_controller,  	'product_vendor_shipping_panel' ); 
			$this->loader->add_action( 'woocommerce_process_product_meta', 		$this->wcvendors_pro_shipping_controller, 	'save_vendor_shipping_product' );

			// Cart and checkout 
			$this->loader->add_filter( 'woocommerce_cart_shipping_packages', 			$this->wcvendors_pro_shipping_controller,	'vendor_split_woocommerce_cart_shipping_packages' );
			$this->loader->add_filter( 'woocommerce_shipping_package_name', 			$this->wcvendors_pro_shipping_controller,	'rename_vendor_shipping_package', 10, 3 ); 
			$this->loader->add_filter( 'woocommerce_cart_shipping_method_full_label', 	$this->wcvendors_pro_shipping_controller, 	'rename_vendor_shipping_method_label', 10, 2 );

		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$wc_prd_vendor_options 	= get_option( 'wc_prd_vendor_options' ); 
		$shipping_disabled		= ( isset( $wc_prd_vendor_options[ 'shipping_management_cap' ] ) ) ? $wc_prd_vendor_options[ 'shipping_management_cap' ] : true;
		$pro_store_header		= ( isset( $wc_prd_vendor_options[ 'vendor_store_header_type' ] ) ) ? $wc_prd_vendor_options[ 'vendor_store_header_type' ] : ''; 
		$shop_store_header		= ( isset( $wc_prd_vendor_options[ 'store_shop_headers' ] ) && $wc_prd_vendor_options[ 'store_shop_headers' ] ) ? true : false; 
		$single_store_header	= ( isset( $wc_prd_vendor_options[ 'store_single_headers' ] ) && $wc_prd_vendor_options[ 'store_single_headers' ] ) ? true : false; 
		$single_product_tools	= ( isset( $wc_prd_vendor_options[ 'single_product_tools' ] ) ) ? $wc_prd_vendor_options[ 'single_product_tools' ] : false;
		
		// Public Class 
		$this->loader->add_action( 'wp_enqueue_scripts', 	$this->wcvendors_pro_public, 		'enqueue_styles' 	);
		$this->loader->add_action( 'wp_enqueue_scripts', 	$this->wcvendors_pro_public, 		'enqueue_scripts' 	);
		$this->loader->add_filter( 'body_class', 			$this->wcvendors_pro_public, 		'body_class' 		);

		// WCVendors Pro Dashboard 
		$this->loader->add_action( 'template_redirect', 			$this->wcvendors_pro_dashboard, 	'check_permission' );
		$this->loader->add_action( 'wcv_pro_after_dashboard_nav', 	$this->wcvendors_pro_dashboard, 	'lock_new_products_notice' );

		// Dashboard Rewrite rule filters 
		$this->loader->add_filter( 'query_vars', 				$this->wcvendors_pro_dashboard, 'add_query_vars' );
		$this->loader->add_filter( 'rewrite_rules_array', 		$this->wcvendors_pro_dashboard, 'rewrite_rules' );
		$this->loader->add_shortcode( 'wcv_pro_dashboard', 		$this->wcvendors_pro_dashboard, 'load_dashboard' );
		$this->loader->add_shortcode( 'wcv_pro_dashboard_nav',  $this->wcvendors_pro_dashboard, 'load_dashboard_nav' );

		// Product controller 
		$this->loader->add_action( 'init', 										$this->wcvendors_pro_product_controller, 	'process_submit' );
		$this->loader->add_action( 'template_redirect', 						$this->wcvendors_pro_product_controller, 	'process_delete' );
		$this->loader->add_action( 'template_redirect', 						$this->wcvendors_pro_product_controller, 	'process_duplicate' );
		$this->loader->add_action( 'template_redirect', 						$this->wcvendors_pro_product_form, 	'init' );
		$this->loader->add_filter( 'wcv_product_gallery_options', 				$this->wcvendors_pro_product_form, 	'product_max_gallery_count' );

		// Product Display table 
		$this->loader->add_filter( 'wcvendors_pro_table_columns_product', 		$this->wcvendors_pro_product_controller, 'table_columns' );
		$this->loader->add_filter( 'wcvendors_pro_table_rows_product',	 		$this->wcvendors_pro_product_controller, 'table_rows', 10, 2 );
		$this->loader->add_filter( 'wcvendors_pro_table_action_column_product',	$this->wcvendors_pro_product_controller, 'table_action_column' );
		$this->loader->add_filter( 'wcvendors_pro_table_before_product',		$this->wcvendors_pro_product_controller, 'table_actions' );
		$this->loader->add_filter( 'wcvendors_pro_table_after_product',			$this->wcvendors_pro_product_controller, 'table_actions' );
		$this->loader->add_filter( 'wcvendors_pro_table_post_per_page_product',	$this->wcvendors_pro_product_controller, 'table_posts_per_page' );
		$this->loader->add_filter( 'wcvendors_pro_table_no_data_notice_product',$this->wcvendors_pro_product_controller, 'table_no_data_notice' );

		// Product AJAX calls 
		$this->loader->add_action( 'wp_ajax_wcv_json_search_products', 				$this->wcvendors_pro_product_controller, 'json_search_products' );
		$this->loader->add_action( 'wp_ajax_wcv_json_search_tags', 					$this->wcvendors_pro_product_controller, 'json_search_product_tags' );
		$this->loader->add_action( 'wp_ajax_wcv_json_add_attribute', 				$this->wcvendors_pro_product_controller, 'json_add_attribute' );
		$this->loader->add_action( 'wp_ajax_wcv_json_add_new_attribute', 			$this->wcvendors_pro_product_controller, 'json_add_new_attribute' );
		$this->loader->add_action( 'wp_ajax_wcv_json_default_variation_attributes', $this->wcvendors_pro_product_controller, 'json_default_variation_attributes' );
		$this->loader->add_action( 'wp_ajax_wcv_json_load_variation', 				$this->wcvendors_pro_product_controller, 'json_load_variations' );
		$this->loader->add_action( 'wp_ajax_wcv_json_add_variation', 				$this->wcvendors_pro_product_controller, 'json_add_variation' );
		$this->loader->add_action( 'wp_ajax_wcv_json_link_all_variations', 			$this->wcvendors_pro_product_controller, 'json_link_all_variations' );

		// Orders controller 
		$this->loader->add_filter( 'wcvendors_pro_table_action_column_order',		$this->wcvendors_pro_order_controller, 	'table_action_column' );
		$this->loader->add_filter( 'wcvendors_pro_table_before_order',				$this->wcvendors_pro_order_controller, 	'table_actions' );
		$this->loader->add_filter( 'wcvendors_pro_table_no_data_notice_order', 	 	$this->wcvendors_pro_order_controller,  'table_no_data_notice' );
		$this->loader->add_action( 'template_redirect', 							$this->wcvendors_pro_order_controller, 	'process_submit' );
		$this->loader->add_action( 'template_redirect', 							$this, 	'wc_filter_address_hook' );
	
		// Shop Coupon controller 	
		$this->loader->add_action( 'template_redirect',								$this->wcvendors_pro_shop_coupon_controller, 	'process_submit' );
		$this->loader->add_action( 'template_redirect', 							$this->wcvendors_pro_shop_coupon_controller, 	'process_delete' );
		
		// Shop coupon table 
		$this->loader->add_filter( 'wcvendors_pro_table_columns_shop_coupon', 		$this->wcvendors_pro_shop_coupon_controller, 	'table_columns' );
		$this->loader->add_filter( 'wcvendors_pro_table_rows_shop_coupon',	 		$this->wcvendors_pro_shop_coupon_controller, 	'table_rows', 10, 2 );
		$this->loader->add_filter( 'wcvendors_pro_table_actions_shop_coupon',	 	$this->wcvendors_pro_shop_coupon_controller, 	'table_row_actions' );
		$this->loader->add_filter( 'wcvendors_pro_table_action_column_shop_coupon',	$this->wcvendors_pro_shop_coupon_controller, 	'table_action_column' );	
		$this->loader->add_filter( 'wcvendors_pro_table_before_shop_coupon',		$this->wcvendors_pro_shop_coupon_controller, 	'table_actions' );
		$this->loader->add_filter( 'wcvendors_pro_table_after_shop_coupon',			$this->wcvendors_pro_shop_coupon_controller, 	'table_actions' );
		$this->loader->add_filter( 'wcvendors_pro_table_post_per_page_shop_coupon',	$this->wcvendors_pro_shop_coupon_controller, 	'table_posts_per_page' );
		$this->loader->add_filter( 'wcvendors_pro_table_no_data_notice_shop_coupon',$this->wcvendors_pro_shop_coupon_controller, 	'table_no_data_notice' );

		$this->loader->add_filter( 'manage_shop_coupon_posts_columns', 				$this->wcvendors_pro_shop_coupon_controller, 	'display_vendor_store_column', 15 );
		$this->loader->add_action( 'manage_shop_coupon_posts_custom_column', 		$this->wcvendors_pro_shop_coupon_controller, 	'display_vendor_store_custom_column', 2, 99 );

		// Reports 
		$this->loader->add_action( 'template_redirect', 								$this->wcvendors_pro_report_controller, 	'process_submit' );
		$this->loader->add_filter( 'wcvendors_pro_table_no_data_notice_recent_product',	$this->wcvendors_pro_report_controller, 	'product_table_no_data_notice' );
		$this->loader->add_filter( 'wcvendors_pro_table_no_data_notice_recent_order',	$this->wcvendors_pro_report_controller, 	'order_table_no_data_notice' );

		// Vendor Controller 
		$this->loader->add_action( 'woocommerce_created_customer', 				$this->wcvendors_pro_vendor_controller, 	'apply_vendor_redirect', 10, 2 );
		$this->loader->add_action( 'template_redirect', 						$this->wcvendors_pro_vendor_controller, 	'process_submit' );
		$this->loader->add_action( 'woocommerce_before_my_account', 			$this->wcvendors_pro_vendor_controller, 	'pro_dashboard_link_myaccount' );
		$this->loader->add_shortcode( 'wcv_pro_vendorslist', 					$this->wcvendors_pro_vendor_controller, 	'vendors_list' );
		$this->loader->add_action( 'wp_enqueue_scripts', 						$this->wcvendors_pro_vendor_controller, 	'wcvendors_list_scripts' );

		if ( 'pro' === $pro_store_header  ) { 

			$this->loader->add_action( 'init', 									$this->wcvendors_pro_vendor_controller, 	'remove_free_headers' );

			if ( $shop_store_header ){ 
				
				$this->loader->add_action( 'woocommerce_before_main_content',		$this->wcvendors_pro_vendor_controller, 	'store_main_content_header', 30 );
				$this->loader->add_action( 'wcv_after_vendor_store_header',			$this->wcvendors_pro_vendor_controller, 	'vacation_mode' );

				if ( $single_store_header ) { 
					$this->loader->add_action( 'woocommerce_before_single_product',		$this->wcvendors_pro_vendor_controller, 	'store_single_header');
				} else { 
					$this->loader->add_action( 'woocommerce_before_single_product',		$this->wcvendors_pro_vendor_controller, 	'vacation_mode');
				}	
			} else { 
				$this->loader->add_action( 'woocommerce_before_main_content',			$this->wcvendors_pro_vendor_controller, 	'vacation_mode', 30);
			}
		}

		
		if ( ! $shipping_disabled ){ 
			$this->loader->add_action( 'woocommerce_product_meta_start', 		$this->wcvendors_pro_vendor_controller, 	'product_ships_from', 9 );
		}

		// Single product page vendor tools
		if ( $single_product_tools ){ 
			$this->loader->add_action( 'woocommerce_product_meta_start', 		$this->wcvendors_pro_vendor_controller, 	'enable_vendor_tools', 8 );
		}
		
		// Settings and signup form
		$this->loader->add_action( 'template_redirect', 						$this->wcvendors_pro_store_form, 	'init' );

	}
	

	/**
	 * Register all of the hooks related to shared functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shared_hooks() {

		// Settings 
		$this->loader->add_filter( 'wc_prd_vendor_options', $this, 'load_settings' 	); 
		// Get the settings directly from the database as WC_Vendors::$pv_options->get_option() isn't loaded yet. 
		$wc_prd_vendor_options 	= get_option( 'wc_prd_vendor_options' ); 
		$ratings_disabled		= ( isset( $wc_prd_vendor_options[ 'ratings_management_cap' ] ) ) ? $wc_prd_vendor_options[ 'ratings_management_cap' ] : true;

		$pro_store_header		= ( isset( $wc_prd_vendor_options[ 'vendor_store_header_type' ] ) && $wc_prd_vendor_options[ 'vendor_store_header_type' ] ) ? true : false; 

		// Filter all uploads to include an md5 of the guid. 
		$this->loader->add_filter( 'wp_update_attachment_metadata', 'WCVendors_Pro', 'add_md5_to_attachment', 10, 2); 


		if ( !$ratings_disabled ) { 

			// ADMIN 
			$this->loader->add_action( 'admin_menu', 			$this->wcvendors_pro_ratings_controller, 	'admin_page_setup' );
			$this->loader->add_filter( 'init', 					$this->wcvendors_pro_ratings_controller, 	'process_form_submission' );
			$this->loader->add_filter( 'rewrite_rules_array', 	$this->wcvendors_pro_ratings_controller, 	'add_rewrite_rules' );
			$this->loader->add_filter( 'query_vars', 			$this->wcvendors_pro_ratings_controller, 	'add_query_vars' );
			$this->loader->add_action( 'admin_enqueue_scripts', $this->wcvendors_pro_ratings_controller, 	'enqueue_scripts' );
			$this->loader->add_action( 'admin_enqueue_scripts', $this->wcvendors_pro_ratings_controller, 	'enqueue_styles' );

			// PUBLIC 
			$this->loader->add_filter( 'wcvendors_pro_table_columns_rating', 		$this->wcvendors_pro_ratings_controller, 'table_columns' );
			$this->loader->add_filter( 'wcvendors_pro_table_rows_rating',	 		$this->wcvendors_pro_ratings_controller, 'table_rows' );
			$this->loader->add_filter( 'wcvendors_pro_table_action_column_rating',	$this->wcvendors_pro_ratings_controller, 'table_action_column' );
			$this->loader->add_filter( 'wcvendors_pro_table_no_data_notice_rating', $this->wcvendors_pro_ratings_controller, 'table_no_data_notice' );
			$this->loader->add_action( 'template_redirect', 						$this->wcvendors_pro_ratings_controller, 'display_vendor_ratings' );
			$this->loader->add_action( 'woocommerce_product_tabs', 					$this->wcvendors_pro_ratings_controller, 'vendor_ratings_panel_tab' );
			$this->loader->add_shortcode( 'wcv_feedback', 							$this->wcvendors_pro_ratings_controller, 'wcv_feedback' );

			//  Display the link to view the ratings in both headers 
			if ( ! $pro_store_header ) {
				$this->loader->add_action( 'wcv_after_main_header', $this->wcvendors_pro_ratings_controller, 	'ratings_link' );
				$this->loader->add_action( 'wcv_after_mini_header', $this->wcvendors_pro_ratings_controller, 	'ratings_link' );
			} 

			$this->loader->add_filter( 'woocommerce_my_account_my_orders_actions', 		$this->wcvendors_pro_ratings_controller, 	'feedback_link_action', 10, 3 );
			$this->loader->add_shortcode( 'wcv_feedback_form', 		$this->wcvendors_pro_ratings_controller, 	'feedback_form' );
		} 
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->wcvendors_pro;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WCVendors_Pro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the debug status of the plugin.
	 *
	 * @since     1.0.0
	 * @return    bool    The debug status of the plugin.
	 */
	public function get_debug() {
		return $this->debug;
	}

	/**
	 * Load the Pro settings into the existing settings system. 
	 *
	 * @since     1.0.0
	 * @return    bool    The debug status of the plugin.
	*/
	public function load_settings( $options ) {

		$wc_prd_vendor_options 	= get_option( 'wc_prd_vendor_options' ); 
		$existing_commission 	= ( array_key_exists( 'default_commission', $wc_prd_vendor_options ) ) ? $wc_prd_vendor_options[ 'default_commission' ] : 0; 
		$dashboard_page_id 	 	= get_option( 'wcv_dashboard_page_id' ); 
		$feedback_page_id 	 	= get_option( 'wcv_feedback_page_id' ); 
		$options[ ] 			= array( 'name' => __( 'Pro', 'wcvendors-pro' ), 'type' => 'heading' );
		$shipping_disabled		= ( isset( $wc_prd_vendor_options[ 'shipping_management_cap' ] ) ) ? $wc_prd_vendor_options[ 'shipping_management_cap' ] : true;
		$desc 					= $shipping_disabled ? '' : sprintf('<a href="%s">' .__('Click to view vendor shipping settings here.', 'wcvendors-pro') .'</a>', 'admin.php?page=wc-settings&tab=shipping&section=wcvendors_pro_shipping_method' ); 

		$options[ ] = array( 'name' => __( 'General options', 'wcvendors-pro' ), 'type' => 'title', 'desc' => $desc );

		$options[ ] = array(
			'name'    => __( 'Pro Dashboard Page', 'wcvendors-pro' ),
			'desc'    => __( 'Page requires the [wcv_pro_dashboard] shortcode. <strong>DO NOT USE THE WC VENDORS FREE DASHBOARD PAGE HERE - and do NOT delete the Free Dashboard pages either, you will break your site if you do.</strong>', 'wcvendors-pro' ),
			'id'      => 'dashboard_page_id',
			'type'    => 'single_select_page',
			'select2' => true,
			'std'	  => $dashboard_page_id 
		);

		$options[ ] = array(
			'name'     => __( 'Store Header', 'wcvendors-pro' ),
			'desc'     => __( 'Which store header to use. Store headers need to be enabled for this option to work.', 'wcvendors-pro' ),
			'id'       => 'vendor_store_header_type',
			'type'     => 'select',
			'options' => array(
					'free'		=> __( 'Free',  'wcvendors-pro' ),
					'pro'		=> __( 'Pro', 'wcvendors-pro' ), 
			), 
			'std'	=> 'pro'
		);

		$options[ ] = array(
			'name' => __( 'Store Shop Header', 'wcvendors-pro' ),
			'desc' => __( 'Enable Store Headers on Shop Pages.', 'wcvendors-pro' ),
			'tip'  => __( 'Check to enable the entire header on the /vendors/username/ page.', 'wcvendors-pro' ),
			'id'   => 'store_shop_headers',
			'type' => 'checkbox',
			'std'  => true,
		);

		$options[ ] = array(
			'name' => __( 'Store Single Product Header', 'wcvendors-pro' ),
			'desc' => __( 'Enable Store Headers on Single Product Pages.', 'wcvendors-pro' ),
			'tip'  => __( 'Check to enable the entire header on /shop/product-category/product-name/', 'wcvendors-pro' ),
			'id'   => 'store_single_headers',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name' => __( 'WordPress Dashboard', 'wcvendors-pro' ),
			'desc' => __( 'Only administrators can access the /wp-admin/ dashboard. ', 'wcvendors-pro' ),
			'tip'  => __( 'Lock vendors out of the /wp-admin/ area.', 'wcvendors-pro' ),
			'id'   => 'disable_wp_admin_vendors',
			'type' => 'checkbox',
			'std'  => false,
		);


		$options[ ] = array(
			'name' => __( 'Vendor Dashboard Notice', 'wcvendors-pro' ),
			'desc' => __( 'Display a message to vendors on all dashboard pages below the dashboard menu.', 'wcvendors-pro' ),
			'id'   => 'vendor_dashboard_notice',
			'type' => 'text',
		);

		$options[ ] = array(
			'name' => __( 'Allow HTML in Inputs', 'wcvendors-pro' ),
			'desc' => __( 'Allow vendors to add html source to the inputs and text areas on forms.', 'wcvendors-pro' ),
			'tip'  => __( 'This will allow vendors the ability to add html source code to their inputs and text areas.', 'wcvendors-pro' ),
			'id'   => 'allow_form_markup',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name' => __( 'Single Product Tools', 'wcvendors-pro' ),
			'desc' => __( 'Display product actions on the single product page for vendor.', 'wcvendors-pro' ),
			'tip'  => __( 'Diplay the enabled actions for edit/duplicate/delete on the single product page to the vendor.', 'wcvendors-pro' ),
			'id'   => 'single_product_tools',
			'type' => 'checkbox',
			'std'  => false,
		);


		// Dashboard Options 
		$options[ ] = array( 'name' => __( 'Pro Features', 'wcvendors-pro' ), 'type' => 'title', 'desc' => '' );

		$options[ ] = array(
			'name' => __( 'Product Management', 'wcvendors-pro' ),
			'desc' => __( 'Disable product management in pro dashboard. ', 'wcvendors-pro' ),
			'tip'  => __( 'Check to remove the product management from the pro dashboard.', 'wcvendors-pro' ),
			'id'   => 'product_management_cap',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name' => __( 'Order Management', 'wcvendors-pro' ),
			'desc' => __( 'Disable order management in pro dashboard. ', 'wcvendors-pro' ),
			'tip'  => __( 'Check to remove the order management from the pro dashboard.', 'wcvendors-pro' ),
			'id'   => 'order_management_cap',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name' => __( 'Coupon Management', 'wcvendors-pro' ),
			'desc' => __( 'Disable coupon management in pro dashboard. ', 'wcvendors-pro' ),
			'tip'  => __( 'Check to remove the coupon management from the pro dashboard.', 'wcvendors-pro' ),
			'id'   => 'shop_coupon_management_cap',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name' => __( 'Settings Management', 'wcvendors-pro' ),
			'desc' => __( 'Disable store settings management in pro dashboard. ', 'wcvendors-pro' ),
			'tip'  => __( 'Check to remove the store settings management from the pro dashboard.', 'wcvendors-pro' ),
			'id'   => 'settings_management_cap',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name' => __( 'Ratings', 'wcvendors-pro' ),
			'desc' => __( 'Disable the ratings system completely. ', 'wcvendors-pro' ),
			'tip'  => __( 'Check to remove the ratings system from the front end completely.', 'wcvendors-pro' ),
			'id'   => 'ratings_management_cap',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name' => __( 'Vendor Shipping', 'wcvendors-pro' ),
			'desc' => __( 'Disable the vendor shipping system completely. ', 'wcvendors-pro' ),
			'tip'  => __( 'Check to remove the vendor shipping system from the front end completely.', 'wcvendors-pro' ),
			'id'   => 'shipping_management_cap',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name' => __( 'View Store', 'wcvendors-pro' ),
			'desc' => __( 'Disable the view store button on the pro dashboard. ', 'wcvendors-pro' ),
			'tip'  => __( 'Check to remove the view store button from the navigation.', 'wcvendors-pro' ),
			'id'   => 'view_store_cap',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name' => __( 'Delete Product', 'wcvendors-pro' ),
			'desc' => __( 'Disable the delete option on the product form. ', 'wcvendors-pro' ),
			'tip'  => __( 'Check to remove the delete button from the product table.', 'wcvendors-pro' ),
			'id'   => 'delete_product_cap',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name' => __( 'Duplicate Product', 'wcvendors-pro' ),
			'desc' => __( 'Disable the duplicate option on the product form. ', 'wcvendors-pro' ),
			'tip'  => __( 'Check to remove the duplicate button from the product table.', 'wcvendors-pro' ),
			'id'   => 'duplicate_product_cap',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name' => __( 'Edit Approved Products', 'wcvendors-pro' ),
			'desc' => __( 'Publish edits to approved products. ( New products will still have to be approved )', 'wcvendors-pro' ),
			'tip'  => __( 'Allow vendors to edit products that have already been approved.', 'wcvendors-pro' ),
			'id'   => 'can_edit_approved_products',
			'type' => 'checkbox',
			'std'  => false,
		);


		$options[ ] = array(
			'name'     => __( 'Dashboard Date Range', 'wcvendors-pro' ),
			'id'       => 'dashboard_date_range',
			'tip'  => __( 'Define the dashboard default date range.', 'wcvendors-pro' ),
			'options'  => array(
				'annually' 		=> __( 'Annually', 'wcvendors-pro' ),
				'quarterly' 	=> __( 'Quarterly', 'wcvendors-pro' ),
				'monthly'		=> __( 'Monthly', 'wcvendors-pro' ),
				'weekly'		=> __( 'Weekly', 'wcvendors-pro' ),
			),

			'type'     => 'radio',
			'multiple' => true,
			'std'	   => 'monthly'
		);


		$options[ ] = array(
			'name'     => __( 'Orders Page Ranges', 'wcvendors-pro' ),
			'id'       => 'orders_sales_range',
			'tip'  => __( 'Define the orders sales page date range.', 'wcvendors-pro' ),
			'options'  => array(
				'annually' 		=> __( 'Annually', 'wcvendors-pro' ),
				'quarterly' 	=> __( 'Quarterly', 'wcvendors-pro' ),
				'monthly'		=> __( 'Monthly', 'wcvendors-pro' ),
				'weekly'		=> __( 'Weekly', 'wcvendors-pro' ),
			),

			'type'     => 'radio',
			'multiple' => true,
			'std'	   => 'monthly'
		);

		$options[ ] = array(
			'name' => __( 'Products per page', 'wcvendors-pro' ),
			'desc' => __( 'How many products to display per page', 'wcvendors-pro' ),
			'id'   => 'products_per_page',
			'type' => 'number',
			'std'  => 20, 
		);

		$options[ ] = array(
			'name' => __( 'Coupons per page', 'wcvendors-pro' ),
			'desc' => __( 'How many coupons to display per page', 'wcvendors-pro' ),
			'id'   => 'coupons_per_page',
			'type' => 'number',
			'std'  => 20, 
		);

		// Trash Options
		$options[ ] = array( 'name' => __( 'Permissions', 'wcvendors-pro' ), 'type' => 'title', 'desc' => __( 'Configure what to hide from all vendors on the front end.', 'wcvendors-pro' ) );


		$options[ ] = array(
			'name' => __( 'Orders Table & Details', 'wcvendors-pro' ),
			'desc' => __( 'Customer Name', 'wcvendors-pro' ),
			'tip'  => __( 'Hide the customers name from the vendor on the orders dashboard page.', 'wcvendors-pro' ),
			'id'   => 'hide_order_customer_name',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'desc' => __( 'Customer Shipping Address', 'wcvendors-pro' ),
			'tip'  => __( 'Hide the customers address from the vendor on the orders dashboard page.', 'wcvendors-pro' ),
			'id'   => 'hide_order_customer_shipping_address',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'desc' => __( 'Customer Billing Address', 'wcvendors-pro' ),
			'tip'  => __( 'Hide the customers address from the vendor on the orders dashboard page.', 'wcvendors-pro' ),
			'id'   => 'hide_order_customer_billing_address',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'desc' => __( 'Customer Phone', 'wcvendors-pro' ),
			'tip'  => __( 'Hide the customers phone number from the vendor on the orders dashboard page.', 'wcvendors-pro' ),
			'id'   => 'hide_order_customer_phone',
			'type' => 'checkbox',
			'std'  => true,
		);

		$options[ ] = array(
			'name' => __( 'Orders Table Actions', 'wcvendors-pro' ),
			'desc' => __( 'View order details', 'wcvendors-pro' ),
			'tip'  => __( 'Hide the view details action from the orders table.', 'wcvendors-pro' ),
			'id'   => 'hide_order_view_details',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'desc' => __( 'Shipping label', 'wcvendors-pro' ),
			'tip'  => __( 'Hide the shipping label action from the orders table.', 'wcvendors-pro' ),
			'id'   => 'hide_order_shipping_label',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'desc' => __( 'Order note', 'wcvendors-pro' ),
			'tip'  => __( 'Hide the order note action from the orders table.', 'wcvendors-pro' ),
			'id'   => 'hide_order_order_note',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'desc' => __( 'Tracking number', 'wcvendors-pro' ),
			'tip'  => __( 'Hide the tracking number action from the orders table.', 'wcvendors-pro' ),
			'id'   => 'hide_order_tracking_number',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'desc' => __( 'Mark shipped', 'wcvendors-pro' ),
			'tip'  => __( 'Hide the mark shipped action from the orders table.', 'wcvendors-pro' ),
			'id'   => 'hide_order_mark_shipped',
			'type' => 'checkbox',
			'std'  => false,
		);

		// Trash Options
		$options[ ] = array( 'name' => __( 'Trash', 'wcvendors-pro' ), 'type' => 'title', 'desc' => '' );

		$options[ ] = array(
			'name' => __( 'Product Delete', 'wcvendors-pro' ),
			'desc' => __( 'Delete vendor products permanently. ', 'wcvendors-pro' ),
			'tip'  => __( 'Bypass the trash when a vendor deletes a product and delete permanently.', 'wcvendors-pro' ),
			'id'   => 'vendor_product_trash',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name' => __( 'Coupon Delete', 'wcvendors-pro' ),
			'desc' => __( 'Delete vendor coupons permanently. ', 'wcvendors-pro' ),
			'tip'  => __( 'Bypass the trash when a vendor deletes a coupon and delete permanently.', 'wcvendors-pro' ),
			'id'   => 'vendor_coupon_trash',
			'type' => 'checkbox',
			'std'  => false,
		);

		// Default branding options 
		$options[ ] = array( 'name' => __( 'Default Branding', 'wcvendors-pro' ), 'type' => 'title', 'desc' => '' );

		$options[ ] = array(
			'name' => __( 'Default Store Banner', 'wcvendors-pro' ),
			'desc' => __( 'Select an image for the default store banner', 'wcvendors-pro' ),
			'id'   => 'default_store_banner_src',
			'type' => 'image',
			'class' => 'wcv-img-id button', 
			'std'  => plugin_dir_url( dirname( __FILE__ ) ) . 'includes/assets/images/wcvendors_default_banner.jpg', 
		);

		// Verified Vendor 
		$options[ ] = array( 'name' => __( 'Verified Vendor', 'wcvendors-pro' ), 'type' => 'title', 'desc' => '' );

		$options[ ] = array(
			'name' => __( 'Verified Vendor Label', 'wcvendors-pro' ),
			'desc' => __( 'Text to output on the verified vendor badge.', 'wcvendors-pro' ),
			'id'   => 'verified_vendor_label',
			'type' => 'text',
			'std'  => __( 'Verified Vendor', 'wcvendors-pro' ), 
		);

		// Utils 
		$options[ ] = array( 'name' => __( 'Utils', 'wcvendors-pro' ), 'type' => 'title', 'desc' => '' );

		$options[ ] = array(
			'name' => __( 'Disable Select2', 'wcvendors-pro' ),
			'desc' => __( 'Disable select2 from loading with pro.', 'wcvendors-pro' ),
			'tip'  => __( 'This will disable the included select2 scripts from loading', 'wcvendors-pro' ),
			'id'   => 'disable_select2',
			'type' => 'checkbox',
			'std'  => false,
		);

		// Vendor Rating System
		$options[ ] = array( 'name' => __( 'Vendor Ratings', 'wcvendors-pro' ), 'type' => 'heading' );
		$options[ ] = array( 'name' => __( 'Vendor Ratings System.', 'wcvendors-pro' ), 'type' => 'title', 'desc' => '');

		
		$options[ ] = array(
			'name'    => __( 'Feedback Form Page', 'wcvendors-pro' ),
			'desc'    => __( 'The page to display the feedback from this will have the [wcv_feedback_form] shortcode.', 'wcvendors-pro' ),
			'id'      => 'feedback_page_id',
			'type'    => 'single_select_page',
			'select2' => true,
			'std'	  => $feedback_page_id, 
		);

		$options[ ] = array(
			'name' => __( 'Vendor Ratings Label', 'wcvendors-pro' ),
			'desc' => __( 'The vendor ratings tab title on the single product page.', 'wcvendors-pro' ),
			'id'   => 'vendor_ratings_label',
			'type' => 'text',
			'std'  => __( 'Product Ratings', 'wcvendors-pro' ),
		);

		$options[ ] = array(
			'name' => __( 'Feedback System', 'wcvendors-pro' ),
			'desc' => __( 'Start all vendors at a 5 star rating until they receive their first feedback score', 'wcvendors-pro' ),
			'tip'  => __( 'Reverse the feedback system.', 'wcvendors-pro' ),
			'id'   => 'feedback_system',
			'type' => 'checkbox',
			'std'  => true,
		);

		$options[ ] = array(
			'name' => __( 'Feedback Display', 'wcvendors-pro' ),
			'desc' => __( 'Disable feedback on the single product pages.', 'wcvendors-pro' ),
			'tip'  => __( 'Only show feedback at the store level.', 'wcvendors-pro' ),
			'id'   => 'feedback_display',
			'type' => 'checkbox',
			'std'  => false,
		);

		$options[ ] = array(
			'name'     => __( 'Feedback Sort', 'wcvendors-pro' ),
			'desc'     => __( 'What order to display the feedback in.', 'wcvendors-pro' ),
			'id'       => 'feedback_sort_order',
			'type'     => 'select',
			'options' => array(
					'desc'		=> __( 'Newest',  'wcvendors-pro' ),
					'asc'		=> __( 'Oldest', 'wcvendors-pro' ), 
			), 
			'std'	=> 'desc'
		);

		$options[ ] = array(
			'name'     => __( 'Order Status', 'wcvendors-pro' ),
			'desc'     => __( 'The order status required before feedback can be left.', 'wcvendors-pro' ),
			'id'       => 'feedback_order_status',
			'type'     => 'select',
			'options' => array(
					'processing'	=> __( 'Processing',  'wcvendors-pro' ),
					'completed'		=> __( 'Completed', 'wcvendors-pro' ), 
			), 
			'std'	=> 'processing'
		);


		// Vendor Rating System
		$options[ ] = array( 'name' => __( 'Commissions', 'wcvendors-pro' ), 'type' => 'heading' );
		$options[ ] = array( 'name' => __( 'Commission settings for payouts.', 'wcvendors-pro' ), 'type' => 'title', 'desc' => '');

		$options[ ] = array(
			'name'     => __( 'Coupon Action', 'wcvendors-pro' ),
			'desc'     => __( 'Process the commission before or after the coupon has been applied to the price.', 'wcvendors-pro' ),
			'id'       => 'commission_coupon_action',
			'type'     => 'select',
			'options' => array(
					'yes'	=> __( 'After',  'wcvendors-pro' ),
					'no'	=> __( 'Before', 'wcvendors-pro' ), 
			), 
			'std'	=> 'yes'
		);

		$options[ ] = array(
			'name'     => __( 'Global Commission Type', 'wcvendors-pro' ),
			'desc'     => __( 'This is the default commission type for all vendors. <strong>This overrides default commission in WC Vendors Free.  You may edit a vendors user account to override the global rate for that vendor.  You may edit an individual product to override the commission rate only for that product.</strong>', 'wcvendors-pro' ),
			'id'       => 'commission_type',
			'type'     => 'select',
			'options' => WCVendors_Pro_Commission_Controller::commission_types(), 
			'std'	=> 'percent'
		);

		$options[ ] = array(
			'name'     => __( 'Commission %', 'wcvendors-pro' ),
			'desc'     => __( 'The percent of commission you give the vendors.', 'wcvendors-pro' ),
			'id'       => 'commission_percent',
			'type'     => 'number',
			'restrict' => array(
				'min' => 0,
				'max' => 100
			), 
			'std'	   => $existing_commission, 
		);

		$options[ ] = array(
			'name'     => __( 'Commission amount', 'wcvendors-pro' ),
			'desc'     => __( 'The fixed amount of commission you give the vendors.', 'wcvendors-pro' ),
			'id'       => 'commission_amount',
			'type'     => 'number',
		);

		$options[ ] = array(
			'name'     => __( 'Commission fee', 'wcvendors-pro' ),
			'desc'     => __( 'This is the fee deducted from the commission amount.', 'wcvendors-pro' ),
			'id'       => 'commission_fee',
			'type'     => 'number',
		);

		// Product Form 
		$options[ ] = array( 'name' => __( 'Product Form', 'wcvendors-pro' ), 'type' => 'heading' );

		$options[ ] = array( 'name' => __( 'Show / Hide', 'wcvendors-pro' ), 'type' => 'title', 'desc' => __( 'Configure what to hide from all vendors when adding a product on the front end.', 'wcvendors-pro' ) );

		$options[ ] = array(
			'name'     => __( 'Default Product Form', 'wcvendors-pro' ),
			'desc'     => __( 'Which product form to use.', 'wcvendors-pro' ),
			'id'       => 'product_form_template',
			'type'     => 'select',
			'options' => WCVendors_Pro_Product_Controller::product_templates(), 
			'std'	=> 'select'
		);

		$options[ ] = array(
			'name'     => __( 'Basic', 'wcvendors-pro' ),
			'id'       => 'hide_product_basic',
			'options'  => array(
				'description' 				=> __( 'Description', 'wcvendors-pro' ),
				'short_description' => __( 'Short Description', 'wcvendors-pro' ),
				'categories'		=> __( 'Categories', 'wcvendors-pro' ),
				'tags'				=> __( 'Tags', 'wcvendors-pro' ),
				'attributes'		=> __( 'Attributes', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'		=> false
		);

		$options[ ] = array(
			'name'     => __( 'Media', 'wcvendors-pro' ),
			'id'       => 'hide_product_media',
			'options'  => array(
				'featured' 		=> __( 'Featured Image (also disables the gallery)', 'wcvendors-pro' ),
				'gallery' 		=> __( 'Gallery', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false
		);


		$options[ ] = array(
			'name'     => __( 'General', 'wcvendors-pro' ),
			'id'       => 'hide_product_general',
			'options'  => array(
				'sku' 				=> __( 'SKU', 'wcvendors-pro' ),
				'private_listing' 	=> __( 'Private listing', 'wcvendors-pro' ),
				'external_url'		=> __( 'External URL', 'wcvendors-pro' ),
				'button_text'		=> __( 'Button text for external url', 'wcvendors-pro' ),
				'price'				=> __( 'Price (disables sale price) ', 'wcvendors-pro' ),
				'sale_price'		=> __( 'Sale price', 'wcvendors-pro' ),
				'tax'				=> __( 'Tax', 'wcvendors-pro' ),
				'download_files'	=> __( 'Download files (also disables all download fields)', 'wcvendors-pro' ),
				'download_file_url'	=> __( 'Disable vendors ability to change file URL to prevent remote file URLs', 'wcvendors-pro' ),
				'download_limit'	=> __( 'Download limit', 'wcvendors-pro' ),
				'download_expiry'	=> __( 'Download expiry', 'wcvendors-pro' ),
				'download_type'		=> __( 'Download type', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false
		);

		$options[ ] = array(
			'name'     => __( 'Inventory', 'wcvendors-pro' ),
			'id'       => 'hide_product_inventory',
			'options'  => array(
				'manage_inventory' 	=> __( 'Manage Inventory (also disables all inventory fields)', 'wcvendors-pro' ),
				'stock_qty' 		=> __( 'Stock Qty', 'wcvendors-pro' ),
				'backorders'		=> __( 'Backorders', 'wcvendors-pro' ),
				'stock_status'		=> __( 'Stock status', 'wcvendors-pro' ),
				'sold_individually'	=> __( 'Sold individually', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'		=> false
		);

		$options[ ] = array(
			'name'     => __( 'Shipping', 'wcvendors-pro' ),
			'id'       => 'hide_product_shipping',
			'options'  => array(
				'weight' 				=> __( 'Weight', 'wcvendors-pro' ),
				'handling_fee' 			=> __( 'Product handling fee', 'wcvendors-pro' ),
				'max_charge' 			=> __( 'Maximum shipping charge', 'wcvendors-pro' ),
				'free_shipping_order' 	=> __( 'Free Shipping Order', 'wcvendors-pro' ),
				'free_shipping_product' => __( 'Free Shipping Product', 'wcvendors-pro' ),
				'dimensions'			=> __( 'Dimensions', 'wcvendors-pro' ),
				'shipping_class'		=> __( 'Shipping class', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false
		);

		$options[ ] = array(
			'name'     => __( 'Upsells / Grouping', 'wcvendors-pro' ),
			'id'       => 'hide_product_upsells',
			'options'  => array(
				'up_sells' 			=> __( 'Up sells', 'wcvendors-pro' ),
				'crosssells' 		=> __( 'Cross sells', 'wcvendors-pro' ),
				'grouped_products' 	=> __( 'Grouped Products', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);


		$options[ ] = array(
			'name'     => __( 'Variations', 'wcvendors-pro' ),
			'id'       => 'hide_product_variations',
			'options'  => array(
				'featured' 			=> __( 'Featured Image', 'wcvendors-pro' ),
				'sku' 				=> __( 'SKU', 'wcvendors-pro' ),
				'enabled'			=> __( 'Enabled', 'wcvendors-pro' ),
				'downloadable'		=> __( 'Downloadable', 'wcvendors-pro' ),
				'virtual'			=> __( 'Virtual', 'wcvendors-pro' ),
				'manage_stock'		=> __( 'Manage Stock', 'wcvendors-pro' ),
				'sale_price'		=> __( 'Sale Price', 'wcvendors-pro' ),
				'stock_qty'			=> __( 'Stock QTY', 'wcvendors-pro' ),
				'allow_backorders'	=> __( 'Allow backorders', 'wcvendors-pro' ),
				'stock_status'		=> __( 'Stock Status', 'wcvendors-pro' ),
				'weight'			=> __( 'Weight', 'wcvendors-pro' ),
				'dimensions'		=> __( 'Dimensions', 'wcvendors-pro' ),
				'shipping_class'	=> __( 'Shipping Class', 'wcvendors-pro' ),
				'tax_class'			=> __( 'Tax Class', 'wcvendors-pro' ),
				'description'		=> __( 'Description', 'wcvendors-pro' ),
				'download_files'	=> __( 'Download files ( disables all download fields on variations )', 'wcvendors-pro' ),
				'download_limit'	=> __( 'Download limit', 'wcvendors-pro' ),
				'download_expiry'	=> __( 'Download expiry', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false
		);


		// $options[ ] = array( 'name' => __( 'Required Fields', 'wcvendors-pro' ), 'type' => 'title', 'desc' => __( 'Configure what fields are required on the product form.', 'wcvendors-pro' ) );

		// $options[ ] = array(
		// 	'name'     => __( 'Basic', 'wcvendors-pro' ),
		// 	'id'       => 'required_product_basic',
		// 	'options'  => array(
		// 		'description' 		=> __( 'Description', 'wcvendors-pro' ),
		// 		'short_description' => __( 'Short Description', 'wcvendors-pro' ),
		// 		'categories'		=> __( 'Categories', 'wcvendors-pro' ),
		// 		'tags'				=> __( 'Tags', 'wcvendors-pro' ),
		// 		'attributes'		=> __( 'Attributes', 'wcvendors-pro' ),
		// 	),
		// 	'type'     => 'checkbox',
		// 	'multiple' => true,
		// 	'std'		=> false
		// );

		// $options[ ] = array(
		// 	'name'     => __( 'Media', 'wcvendors-pro' ),
		// 	'id'       => 'required_product_media',
		// 	'options'  => array(
		// 		'featured' 		=> __( 'Featured Image', 'wcvendors-pro' ),
		// 		'gallery' 		=> __( 'Gallery', 'wcvendors-pro' ),
		// 	),
		// 	'type'     => 'checkbox',
		// 	'multiple' => true,
		// 	'std'	   => false
		// );


		// $options[ ] = array(
		// 	'name'     => __( 'General', 'wcvendors-pro' ),
		// 	'id'       => 'required_product_general',
		// 	'options'  => array(
		// 		'sku' 				=> __( 'SKU', 'wcvendors-pro' ),
		// 		'private_listing' 	=> __( 'Private listing', 'wcvendors-pro' ),
		// 		'external_url'		=> __( 'External URL', 'wcvendors-pro' ),
		// 		'button_text'		=> __( 'Button text for external url', 'wcvendors-pro' ),
		// 		'price'				=> __( 'Price', 'wcvendors-pro' ),
		// 		'sale_price'		=> __( 'Sale price', 'wcvendors-pro' ),
		// 		'tax'				=> __( 'Tax', 'wcvendors-pro' ),
		// 		'download_files'	=> __( 'Download files', 'wcvendors-pro' ),
		// 		'download_limit'	=> __( 'Download limit', 'wcvendors-pro' ),
		// 		'download_expiry'	=> __( 'Download expiry', 'wcvendors-pro' ),
		// 		'download_type'		=> __( 'Download type', 'wcvendors-pro' ),
		// 	),
		// 	'type'     => 'checkbox',
		// 	'multiple' => true,
		// 	'std'	   => false
		// );

		// $options[ ] = array(
		// 	'name'     => __( 'Inventory', 'wcvendors-pro' ),
		// 	'id'       => 'required_product_inventory',
		// 	'options'  => array(
		// 		'manage_inventory' 	=> __( 'Manage Inventory', 'wcvendors-pro' ),
		// 		'stock_qty' 		=> __( 'Stock Qty', 'wcvendors-pro' ),
		// 		'backorders'		=> __( 'Backorders', 'wcvendors-pro' ),
		// 		'stock_status'		=> __( 'Stock status', 'wcvendors-pro' ),
		// 		'sold_individually'	=> __( 'Sold individually', 'wcvendors-pro' ),
		// 	),
		// 	'type'     => 'checkbox',
		// 	'multiple' => true,
		// 	'std'		=> false
		// );

		// $options[ ] = array(
		// 	'name'     => __( 'Shipping', 'wcvendors-pro' ),
		// 	'id'       => 'required_product_shipping',
		// 	'options'  => array(
		// 		'weight' 				=> __( 'Weight', 'wcvendors-pro' ),
		// 		'handling_fee' 			=> __( 'Product handling fee', 'wcvendors-pro' ),
		// 		'max_charge' 			=> __( 'Maximum shipping charge', 'wcvendors-pro' ),
		// 		'free_shipping_product' => __( 'Free Shipping Product', 'wcvendors-pro' ),
		// 		'dimensions'			=> __( 'Dimensions', 'wcvendors-pro' ),
		// 		'shipping_class'		=> __( 'Shipping class', 'wcvendors-pro' ),
		// 	),
		// 	'type'     => 'checkbox',
		// 	'multiple' => true,
		// 	'std'	   => false
		// );

		// $options[ ] = array(
		// 	'name'     => __( 'Upsells / Grouping', 'wcvendors-pro' ),
		// 	'id'       => 'required_product_upsells',
		// 	'options'  => array(
		// 		'up_sells' 			=> __( 'Up sells', 'wcvendors-pro' ),
		// 		'crosssells' 		=> __( 'Cross sells', 'wcvendors-pro' ),
		// 	),
		// 	'type'     => 'checkbox',
		// 	'multiple' => true,
		// 	'std'	   => false 
		// );


		$options[ ] = array( 'name' => __( 'Options', 'wcvendors-pro' ), 'type' => 'title', 'desc' => __( 'Configure the product edit form options. ', 'wcvendors-pro' ) );

		$options[ ] = array(
			'name'     => __( 'Capabilities', 'wcvendors-pro' ),
			'id'       => 'product_form_cap',
			'options'  => array(
				'attribute_cap' 			=> __( 'Allow vendors to add attribute terms. ( This does not allow vendors to add attributes )', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => 0
		);

		$options[ ] = array(
			'name'     => __( 'Category Display', 'wcvendors-pro' ),
			'desc'     => __( 'What kind of category selection.', 'wcvendors-pro' ),
			'id'       => 'category_display',
			'type'     => 'select',
			'options' => array(
					'select'	=> __( 'Multi select',  'wcvendors-pro' ),
					'checklist'	=> __( 'Check list', 'wcvendors-pro' ), 
			), 
			'std'	=> 'select'
		);

		$options[ ] = array(
			'name'     => __( 'Hide Categories', 'wcvendors-pro' ),
			'desc'     => __( 'A comma separated list of categories to hide from the vendor product form. ', 'wcvendors-pro' ),
			'id'       => 'hide_categories_list',
			'type'     => 'text',
		);

		$options[ ] = array(
			'name'     => __( 'Categories limit', 'wcvendors-pro' ),
			'desc'     => __( 'Limit the number of categories a vendor can select. ', 'wcvendors-pro' ),
			'id'       => 'category_limit',
			'type'     => 'text',
			'std'	   => ''
		);

		$options[ ] = array(
			'name'     => __( 'Tag Display', 'wcvendors-pro' ),
			'desc'     => __( 'What kind of tag selection.', 'wcvendors-pro' ),
			'id'       => 'tag_display',
			'type'     => 'select',
			'options' => array(
					'select'			=> __( 'Multi select',  'wcvendors-pro' ),
					'select_limited'	=> __( 'Multi select limited', 'wcvendors-pro' ), 
			), 
			'std'	=> 'select'
		);

		$options[ ] = array(
			'name'     => __( 'Tag Separator', 'wcvendors-pro' ),
			'desc'     => __( 'What kind of tag separator.', 'wcvendors-pro' ),
			'id'       => 'tag_separator',
			'type'     => 'select',
			'options' => array(
					'both'			=> __( 'Comma (,) and space ( )',  'wcvendors-pro' ),
					'space'			=> __( 'Space only ( )', 'wcvendors-pro' ), 
					'comma'			=> __( 'Comma only (,)', 'wcvendors-pro' ), 
			), 
			'std'	=> 'select'
		);

		$options[ ] = array(
			'name'     => __( 'File Display', 'wcvendors-pro' ),
			'desc'     => __( 'The format to display on the file uploader.', 'wcvendors-pro' ),
			'id'       => 'file_display',
			'type'     => 'select',
			'options' => array(
					'file_url'		=> __( 'File URL',  'wcvendors-pro' ),
					'file_name'		=> __( 'File name', 'wcvendors-pro' ), 
			), 
			'std'	=> 'file_url'
		);

		$options[ ] = array(
			'name'     => __( 'Hide Attributes', 'wcvendors-pro' ),
			'desc'     => __( 'A comma separated list of attributes to hide from the vendor product form. ', 'wcvendors-pro' ),
			'id'       => 'hide_attributes_list',
			'type'     => 'text',
		);

		$options[ ] = array(
			'name'     => __( 'Max Gallery Images', 'wcvendors-pro' ),
			'desc'     => __( 'The maximum number of images that can be uploaded to the gallery. ', 'wcvendors-pro' ),
			'id'       => 'product_max_gallery_count',
			'type'     => 'number',
			'std'	   => 4, 
		);

		// Settings Form 
		$options[ ] = array( 'name' => __( 'Settings Form', 'wcvendors-pro' ), 'type' => 'heading' );

		$options[ ] = array( 'name' => __( 'Settings form.', 'wcvendors-pro' ), 'type' => 'title', 'desc' => __( 'Configure what to hide on the vendor settings page.', 'wcvendors-pro' ) );

		$options[ ] = array(
			'name'     => __( 'Settings Form Tabs', 'wcvendors-pro' ),
			'id'       => 'hide_settings_general',
			'options'  => array(
				'payment' 	=> __( 'Payment', 'wcvendors-pro' ),
				'branding'	=> __( 'Branding', 'wcvendors-pro' ),
				'shipping'	=> __( 'Shipping', 'wcvendors-pro' ),
				'social'	=> __( 'Social', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);

		$options[ ] = array(
			'name'     => __( 'Store', 'wcvendors-pro' ),
			'id'       => 'hide_settings_store',
			'options'  => array(
				'pv_shop_description' 	=> __( 'Store description', 'wcvendors-pro' ),
				'pv_seller_info' 		=> __( 'Seller info', 'wcvendors-pro' ),
				'_wcv_company_url' 		=> __( 'Company / blog URL', 'wcvendors-pro' ),
				'_wcv_store_phone'		=> __( 'Store phone', 'wcvendors-pro' ),
				'store_address'			=> __( 'Store address ( if you use pro shipping this option will not work. )', 'wcvendors-pro' ),
				'vacation_mode'			=> __( 'Vacation Mode ( Allow vendors to create a message to show on their stores. )', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);

		$options[ ] = array(
			'name'     => __( 'Payment', 'wcvendors-pro' ),
			'id'       => 'hide_settings_payment',
			'options'  => array(
				'paypal' 		=> __( 'Paypal email', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);

		$options[ ] = array(
			'name'     => __( 'Branding', 'wcvendors-pro' ),
			'id'       => 'hide_settings_branding',
			'options'  => array(
				'store_banner' 		=> __( 'Store banner', 'wcvendors-pro' ),
				'store_icon' 		=> __( 'Store icon', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);

		$options[ ] = array(
			'name'     => __( 'Shipping', 'wcvendors-pro' ),
			'id'       => 'hide_settings_shipping',
			'options'  => array(
				'handling_fee' 			=> __( 'Product handling fee', 'wcvendors-pro' ),
				'min_charge' 			=> __( 'Minimum order charge', 'wcvendors-pro' ),
				'max_charge' 			=> __( 'Maximum order charge', 'wcvendors-pro' ),
				'max_charge_product' 	=> __( 'Maximum product charge', 'wcvendors-pro' ),
				'free_shipping_order' 	=> __( 'Free Shipping Order', 'wcvendors-pro' ),
				'free_shipping_product' => __( 'Free Shipping Product', 'wcvendors-pro' ),
				'shipping_policy' 		=> __( 'Shipping policy', 'wcvendors-pro' ),
				'return_policy' 		=> __( 'Return policy', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);

		$options[ ] = array(
			'name'     => __( 'Social', 'wcvendors-pro' ),
			'id'       => 'hide_settings_social',
			'options'  => array(
				'twitter' 		=> __( 'Twitter', 'wcvendors-pro' ),
				'instagram' 	=> __( 'Instagram', 'wcvendors-pro' ),
				'facebook'		=> __( 'Facebook', 'wcvendors-pro' ),
				'linkedin'		=> __( 'Linkedin', 'wcvendors-pro' ),
				'youtube'		=> __( 'Youtube', 'wcvendors-pro' ),
				'pinterest' 	=> __( 'Pinterest', 'wcvendors-pro' ),
				'google_plus' 	=> __( 'Google+', 'wcvendors-pro' ),
				'snapchat' 		=> __( 'Snapchat', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);

		// Signup Form 
		$options[ ] = array( 'name' => __( 'Signup Form', 'wcvendors-pro' ), 'type' => 'heading' );
		$options[ ] = array( 'name' => __( 'Signup form.', 'wcvendors-pro' ), 'type' => 'title', 'desc' => __( 'Configure what to hide on the signup form.', 'wcvendors-pro' ) );

		$options[ ] = array(
			'name'     => __( 'Signup Form Tabs', 'wcvendors-pro' ),
			'id'       => 'hide_signup_general',
			'options'  => array(
				'payment' 	=> __( 'Payment', 'wcvendors-pro' ),
				'branding'	=> __( 'Branding', 'wcvendors-pro' ),
				'shipping'	=> __( 'Shipping', 'wcvendors-pro' ),
				'social'	=> __( 'Social', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);

		$options[ ] = array(
			'name'     => __( 'Store', 'wcvendors-pro' ),
			'id'       => 'hide_signup_store',
			'options'  => array(
				'pv_shop_description' 	=> __( 'Store description', 'wcvendors-pro' ),
				'pv_seller_info' 		=> __( 'Seller info', 'wcvendors-pro' ),
				'_wcv_company_url' 		=> __( 'Company / blog URL', 'wcvendors-pro' ),
				'_wcv_store_phone'		=> __( 'Store phone', 'wcvendors-pro' ),
				'store_address'			=> __( 'Store address ( if you use pro shipping this option will not work. )', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);

		$options[ ] = array(
			'name'     => __( 'Payment', 'wcvendors-pro' ),
			'id'       => 'hide_signup_payment',
			'options'  => array(
				'paypal' 		=> __( 'Paypal Email', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);

		$options[ ] = array(
			'name'     => __( 'Branding', 'wcvendors-pro' ),
			'id'       => 'hide_signup_branding',
			'options'  => array(
				'store_banner' 		=> __( 'Store banner', 'wcvendors-pro' ),
				'store_icon' 		=> __( 'Store icon', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);

		$options[ ] = array(
			'name'     => __( 'Shipping', 'wcvendors-pro' ),
			'id'       => 'hide_signup_shipping',
			'options'  => array(
				'handling_fee' 		=> __( 'Product handling fee', 'wcvendors-pro' ),
				'min_charge' 			=> __( 'Minimum order charge', 'wcvendors-pro' ),
				'max_charge' 			=> __( 'Maximum order charge', 'wcvendors-pro' ),
				'max_charge_product' 	=> __( 'Maximum product charge', 'wcvendors-pro' ),
				'free_shipping_order' 	=> __( 'Free Shipping Order', 'wcvendors-pro' ),
				'free_shipping_product' => __( 'Free Shipping Product', 'wcvendors-pro' ),
				'shipping_policy' 	=> __( 'Shipping policy', 'wcvendors-pro' ),
				'return_policy' 	=> __( 'Return policy', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);

		$options[ ] = array(
			'name'     => __( 'Social', 'wcvendors-pro' ),
			'id'       => 'hide_signup_social',
			'options'  => array(
				'twitter' 		=> __( 'Twitter', 'wcvendors-pro' ),
				'instagram' 	=> __( 'Instagram', 'wcvendors-pro' ),
				'facebook'		=> __( 'Facebook', 'wcvendors-pro' ),
				'linkedin'		=> __( 'Linkedin', 'wcvendors-pro' ),
				'youtube'		=> __( 'Youtube', 'wcvendors-pro' ),
				'pinterest' 	=> __( 'Pinterest', 'wcvendors-pro' ),
				'google_plus' 	=> __( 'Google+', 'wcvendors-pro' ),
			),
			'type'     => 'checkbox',
			'multiple' => true,
			'std'	   => false 
		);

		// Vendor Signup Options
		$options[ ] = array( 'name' => __( 'Signup Messages', 'wcvendors-pro' ), 'type' => 'title', 'desc' => __( 'These options allow you to provide messages to vendors signing up to your market place.', 'wcvendors-pro' ) );

		$options[ ] = array(
			'name' => __( 'Vendor signup notice', 'wcvendors-pro' ),
			'desc' => __( 'Display a message to vendors on signup page, this could include store specific instructions.', 'wcvendors-pro' ),
			'id'   => 'vendor_signup_notice',
			'type' => 'wysiwyg',
		);

		$options[ ] = array(
			'name' => __( 'Pending vendor message', 'wcvendors-pro' ),
			'desc' => __( 'Display a message to pending vendors after they have applied.', 'wcvendors-pro' ),
			'id'   => 'vendor_pending_notice',
			'type' => 'textarea',
			'std' => __( 'Your application has been received. You will be notified by email the results of your application.', 'wcvendors-pro' ), 
		);

		$options[ ] = array(
			'name' => __( 'Approved vendor message', 'wcvendors-pro' ),
			'desc' => __( 'Display a message on the dashboard for approved vendors.' , 'wcvendors-pro' ),
			'id'   => 'vendor_approved_notice',
			'type' => 'textarea',
			'std' => __( 'Congratulations! You are now a vendor. Be sure to configure your store settings before adding products.', 'wcvendors-pro' ), 
		);
	
		return $options; 
	}

	/**
	 * Get Option wrapper for WC Vendors calls
	 *
	 * @since     1.0.0
	 * @return    mixed    The option requested from the main options system. 
	*/
	public static function get_option( $option = '' ) { 
	
		if ( class_exists( 'WC_Vendors') ) { 
			return WC_Vendors::$pv_options->get_option( $option );
		} else { 
			return '';
		}

	} // get_option()


	/**
	 * Get the plugin path 
	 *
	 * @since     1.0.0
	 * @return    string    The path to the plugin dir
	*/
	public static function get_path( ){ 
		
		return plugin_dir_path( dirname( __FILE__ ) ); 

	} // get_path()

	/**
	 * Class logger so that we can keep our debug and logging information cleaner 
	 *
	 * @since 1.4.0 
	 * @access public
	 * 
	 * @param mixed - $data the data to go to the error log could be string, array or object
	 */
	public static function log( $data = '' ){ 

		$trace 	= debug_backtrace( false, 2 ); 
		$caller = ( isset( $trace[ 1 ] ) ) ? $trace[ 1 ][ 'class' ] : ''; 

		if ( is_array( $data ) || is_object( $data ) ) { 
			error_log( $caller . ' : ' . print_r( $data, true ) ); 
		} else { 
			error_log( $caller  . ' : ' . $data );
		}

	} // log() 

	/**
	 * Filter the WooCommerce shipping and billing addresses on the pro dashboard to show and hide options 
	 * 
	 * @since 1.3.6 
	 * @access public 
	 */
	public function wc_filter_address_hook() { 

		$wc_prd_vendor_options 	= get_option( 'wc_prd_vendor_options' ); 
		$dashboard_page_id 		= ( isset( $wc_prd_vendor_options[ 'dashboard_page_id' ] ) ) ? $wc_prd_vendor_options[ 'dashboard_page_id' ] : '';

		if ( isset( $dashboard_page_id ) ) { 
			// Dashboard page or the shipping label page 
			if ( is_page( $dashboard_page_id ) || ( isset( $_GET['wcv_shipping_label' ] ) ) ){ 
				add_filter( 'woocommerce_order_formatted_shipping_address',	array( $this->wcvendors_pro_order_controller, 	'filter_formatted_shipping_address' ) ); 
				add_filter( 'woocommerce_order_formatted_billing_address',	array( $this->wcvendors_pro_order_controller, 	'filter_formatted_billing_address' ) ); 
			}
		}

	} // wc_shipping_address_hook() 

	/**
	*--------------------------------------------------------------------------
	* WC Vendors Pro settings 
	*--------------------------------------------------------------------------
	* 
	* These methods return which front end components are enabled for WC Vendors Pro 
	* 
	*/


	/**
	 *  Check if the vendor shipping method is enabled in woocommerce settings 
	 * 
	 * @since 1.4.0 
	 * @access public 
	 */
	public function is_vendor_shipping_method_enabled(){ 

		global $woocommerce; 

		$shipping_methods 			= $woocommerce->shipping->load_shipping_methods();
		$shipping_method_enabled	= ( array_key_exists('wcv_pro_vendor_shipping', $shipping_methods ) && $shipping_methods['wcv_pro_vendor_shipping']->enabled == 'yes' ) ? true : false; 

		return $shipping_method_enabled; 

	} // is_vendor_shipping_method_enabled() 

	/**
	 * This function fires when an attachment is uploaded in wp-admin and will generate an md5 of the post GUID. 
	 * 
	 * @since 1.3.9
	 * @access public 
	 */	
	public static function add_md5_to_attachment( $meta_data, $post_id ){  

		WCVendors_Pro::md5_attachment_url( $post_id ); 

		// Return original Meta data 
		return $meta_data; 

	} // add_md5_to_attachment() 

	/**
	 * This function will add an md5 hash of the file url ( post GUID ) on attachment post types. 
	 * 
	 * @since 1.3.9
	 * @access public 
	 */
	public static function md5_attachment_url( $post_id ){ 

		// Add an MD5 of the GUID for later queries. 
		if ( !$attachment_post = get_post( $post_id ) )
			return false; 
		
		update_post_meta( $attachment_post->ID, '_md5_guid', md5( $attachment_post->guid ) ); 

	} // md5_upload_attachment 

	/**
	 * This function will return the md5 hash of an attachment post if the id is 
	 * 
	 * @since 1.3.9
	 * @access public 
	 * @return int $attachment_id 
	 */
	public static function get_attachment_id( $md5_guid ){ 

		global $wpdb;
		// Get the attachment_id from the database
		$attachment_id = $wpdb->get_var( "select post_id from $wpdb->postmeta where meta_key = '_md5_guid' AND meta_value ='$md5_guid'" );

		return $attachment_id; 

	} // get_attachment_id 

} // WCVendors_Pro