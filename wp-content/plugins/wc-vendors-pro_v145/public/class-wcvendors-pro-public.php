<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Public {

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
	 * Script suffix for debugging 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $suffix    script suffix for including minified file versions 
	 */
	private $suffix;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wcvendors_pro       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 * @param      bool      $debug    Plugin is in debug mode 
	 */
	public function __construct( $wcvendors_pro, $version, $debug ) {

		$this->wcvendors_pro 	= $wcvendors_pro;
		$this->version 			= $version;
		$this->debug 			= $debug; 
		$this->base_dir			= plugin_dir_url( __FILE__ ); 
		$this->suffix		 	= $this->debug ? '' : '.min';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 * @todo 	 check if any of the styles are already loaded before enqueing them 
	 */
	public function enqueue_styles() {
		
		global $post; 

		$current_page_id = get_the_ID(); 

		$dashboard_page_id 	= WCVendors_Pro::get_option( 'dashboard_page_id' ); 
		$feedback_page_id 	= WCVendors_Pro::get_option( 'feedback_page_id' );  
		$disable_select2 	= WCVendors_Pro::get_option( 'disable_select2' );  
		$view_dashboard		= apply_filters( 'wcv_view_dashboard', 	$current_page_id == $dashboard_page_id ? true : false );
 		$view_feedback		= apply_filters( 'wcv_view_feedback', 	$current_page_id == $feedback_page_id ? true : false );

		// Store Style 
		if ( is_shop() || is_product() ) { 
			wp_enqueue_style( 'wcv-pro-store-style', apply_filters( 'wcv_pro_store_style', $this->base_dir . 'assets/css/store' . $this->suffix . '.css' ), false, $this->version );			
		} 

		// Dashboard styles 
		if ( ( $view_dashboard ) || ( $view_feedback ) || ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'wcv_pro_dashboard_nav') ) )  { 

			// Ink system 
			wp_enqueue_style( 'wcv-ink', 	apply_filters( 'wcv_pro_ink_style', $this->base_dir . 'assets/lib/ink-3.1.10/dist/css/ink.min.css' ), array(), '3.1.10', 'all' );

			if ( ! $disable_select2 ) {  
				//Select2 3.5.4
				wp_enqueue_style( 'select2-css', 	$this->base_dir . '../includes/assets/css/select2' . $this->suffix . '.css', array(), '3.5.2', 'all' );
			} 

			if ( is_user_logged_in() ) {

				// Dashboard Style
				wp_enqueue_style( 'wcv-pro-dashboard', apply_filters( 'wcv_pro_dashboard_style' , $this->base_dir . 'assets/css/dashboard' . $this->suffix . '.css' ), false, $this->version );

			} 

			// Load jquery-ui-datepicker style
    		wp_enqueue_style( 'wcv-datepicker-style', $this->base_dir . 'assets/lib/jquery-ui/jquery-ui' . $this->suffix . '.css');

    		// load jquery-ui-datetimepicker style 
    		wp_enqueue_style( 'wcv-datetimepicker-style', $this->base_dir . 'assets/lib/jquery-ui/jquery-ui-timepicker.css');

		} 

		//font awesome 
		wp_enqueue_style( 'font-awesome', 	$this->base_dir . '../includes/assets/lib/font-awesome-4.6.3/css/font-awesome.min.css', array(), '4.6.3', 'all' );


	}

	/**
	 * Add custom wcvendors pro css classes 
	 *
	 * @since    1.0.0
	 * @access public 
	 * 
	 * @param array $classes - body css classes 
	 * @return array $classes - body css classes 
	 */
	public function body_class( $classes ){ 

		$dashboard_page_id 	= WCVendors_Pro::get_option( 'dashboard_page_id' ); 
		$feedback_page_id 	= WCVendors_Pro::get_option( 'feedback_page_id' );  

		if ( is_page( $dashboard_page_id ) ){ 
			$classes[] = 'wcvendors wcvendors-pro wcv-pro-dashboard'; 
		}

		if ( is_page( $feedback_page_id ) ){ 
			$classes[] = 'wcvendors wcvendors-pro wcv-ratings-page'; 
		}

		return $classes; 


	} // body_class() 

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$current_page_id 			= get_the_ID(); 
		$dashboard_page_id 			= WCVendors_Pro::get_option( 'dashboard_page_id' );
		$file_display 				= WCVendors_Pro::get_option( 'file_display' );
		$tag_separator 				= WCVendors_Pro::get_option( 'tag_separator' ) ? WCVendors_Pro::get_option( 'tag_separator' ) : 'both';
		$disable_select2 			= WCVendors_Pro::get_option( 'disable_select2' );  
		$view_dashboard				= apply_filters( 'wcv_view_dashboard', 	$current_page_id == $dashboard_page_id ? true : false ); 
		$category_limit 			= WCVendors_Pro::get_option( 'category_limit' );
		$tag_limit 					= WCVendors_Pro::get_option( 'tag_limit' );
		// $required_basic_options 	= (array) WC_Vendors::$pv_options->get_option( 'required_product_basic' );
		// $required_media_options 	= (array) WC_Vendors::$pv_options->get_option( 'required_product_media' );
		// $required_general_options 	= (array) WC_Vendors::$pv_options->get_option( 'required_product_general' );
		// $required_upsell_options 	= (array) WC_Vendors::$pv_options->get_option( 'required_product_upsells' );  
	
		if ( $view_dashboard ) { 
	
			if ( is_user_logged_in() ) {
	
				wp_enqueue_media();

				$localize_search_args = array(
					'i18n_matches_1'            => __( 'One result is available, press enter to select it.', 'wcvendors-pro' ),
					'i18n_matches_n'            => __( '%qty% results are available, use up and down arrow keys to navigate.', 'wcvendors-pro' ),
					'i18n_no_matches'           => __( 'No matches found', 'wcvendors-pro' ),
					'i18n_ajax_error'           => __( 'Loading failed', 'wcvendors-pro' ),
					'i18n_input_too_short_1'    => __( 'Please enter 1 or more characters', 'wcvendors-pro' ),
					'i18n_input_too_short_n'    => __( 'Please enter %qty% or more characters', 'wcvendors-pro' ),
					'i18n_input_too_long_1'     => __( 'Please delete 1 character', 'wcvendors-pro' ),
					'i18n_input_too_long_n'     => __( 'Please delete %qty% characters', 'wcvendors-pro' ),
					'i18n_selection_too_long_1' => __( 'You can only select 1 category', 'wcvendors-pro' ),
					'i18n_selection_too_long_n' => __( 'You can only select %qty% items', 'wcvendors-pro' ),
					'i18n_load_more'            => __( 'Loading more results&hellip;', 'wcvendors-pro' ),
					'i18n_searching'            => sprintf( __( 'Searching %s', 'wcvendors-pro' ), '&hellip;'),
					'ajax_url'                  => admin_url( 'admin-ajax.php' ),
					'nonce'						=> wp_create_nonce('wcv-search'), 
				); 

				// ChartJS 1.0.2
				wp_register_script( 'chartjs', 				$this->base_dir . 'assets/lib/chartjs/Chart' . $this->suffix	 . '.js', array( 'jquery' ), '1.0.2', true );
				wp_enqueue_script( 'chartjs'); 

				// WCV chart init 
				wp_register_script( 'wcvendors-pro-charts', $this->base_dir . 'assets/js/wcvendors-pro-charts'.$this->suffix.'.js', array( 'chartjs' ), $this->version, true );
				wp_enqueue_script( 'wcvendors-pro-charts'); 
				
				if ( ! $disable_select2 ) {  
				
					// Select 2 (3.5.2 branch)
					wp_register_script( 'select2', 				$this->base_dir . '../includes/assets/js/select2' . $this->suffix	 . '.js', array( 'jquery' ), '3.5.2', true );
					wp_enqueue_script( 'select2'); 

				} 

				$ink_vars = array( 'test_var' => 'this is a test' ); 

				// Ink js 
				wp_register_script( 'ink-js', 				$this->base_dir . 'assets/lib/ink-3.1.10/dist/js/ink-all' . $this->suffix	 . '.js', array(), '1.11.4', true );
				wp_localize_script( 'ink-js', 'wcv_ink_vars', $ink_vars ); 
				wp_enqueue_script( 'ink-js'); 
				
				// Ink autoloader 
				wp_register_script( 'ink-autoloader-js', 	$this->base_dir . 'assets/lib/ink-3.1.10/dist/js/autoload' . $this->suffix	 . '.js', array( 'jquery' ), '1.11.4', true );
				wp_enqueue_script( 'ink-autoloader-js'); 

				// Accounting 
				wp_register_script( 'accounting', 			$this->base_dir . 'assets/lib/accounting/accounting' . $this->suffix	 . '.js', array( 'jquery' ), '0.4.2', true );
				wp_localize_script( 'accounting', 			'accounting_params', array( 'mon_decimal_point' => wc_get_price_decimal_separator() ) );

				// Product search
				wp_register_script( 'wcv-product-search', 	$this->base_dir . 'assets/js/select' . $this->suffix . '.js', array( 'jquery' ), '3.5.2', true );
				$localize_search_args[ 'nonce' ] 		= wp_create_nonce( 'wcv-search-products' ); 
				$localize_search_args[ 'separator' ] 	= apply_filters( 'wcv_product_search_args_separator', array( ',', ' ' ) ); 
				wp_localize_script( 'wcv-product-search', 'wcv_product_select_params', $localize_search_args );
				wp_enqueue_script( 'wcv-product-search' );

				// Tag search 
				wp_register_script( 'wcv-tag-search', 	$this->base_dir . 'assets/js/tags' . $this->suffix . '.js', array( 'jquery' ), WCV_PRO_VERSION, true );
				$localize_search_args['nonce'] = wp_create_nonce( 'wcv-search-product-tags' ); 
				$localize_search_args[ 'separator' ] 	= apply_filters( 'wcv_tag_search_args_separator', $this->select2_separator( $tag_separator ) ); 
				wp_localize_script( 'wcv-tag-search', 'wcv_tag_search_params', $localize_search_args ); 
				wp_enqueue_script( 'wcv-tag-search' );

				// Product Edit 
				$product_params = array( 
					'ajax_url'                      		=> admin_url( 'admin-ajax.php' ),
					'product_types' 						=> array_map( 'sanitize_title', get_terms( 'product_type', array( 'hide_empty' => false, 'fields' => 'names' ) ) ),
					'wcv_add_attribute_nonce'       		=> wp_create_nonce( 'wcv-add-attribute' ),
					'wcv_add_new_attribute_nonce'   		=> wp_create_nonce( 'wcv-add-new-attribute' ),
					'remove_attribute'              		=> __( 'Remove this attribute?', 'wcvendors-pro' ),
					'name_label'                    		=> __( 'Name', 'wcvendors-pro' ),
					'remove_label'                  		=> __( 'Remove', 'wcvendors-pro' ),
					'click_to_toggle'               		=> __( 'Click to toggle', 'wcvendors-pro' ),
					'values_label'                  		=> __( 'Value(s)', 'wcvendors-pro' ),
					'text_attribute_tip'            		=> __( 'Enter some text, or some attributes by pipe (|) separating values.', 'wcvendors-pro' ),
					'visible_label'                 		=> __( 'Visible on the product page', 'wcvendors-pro' ),
					'used_for_variations_label'     		=> __( 'Used for variations', 'wcvendors-pro' ),
					'new_attribute_prompt'          		=> __( 'Enter a name for the new attribute term:', 'wcvendors-pro' ),
					'wc_deliminator'						=> WC_DELIMITER, 
					'wcv_file_display'						=> $file_display, 
					'category_limit'						=> $category_limit, 
					'tag_limit'								=> $tag_limit, 
					'category_limit_msg'					=> apply_filters( 'wcv_category_limit_msg', sprintf( __( 'You can only select %s categories', 'wcvendors-pro' ), $category_limit ) ), 
					// 'require_featured_image'				=> $required_media_options[ 'featured' ], 
					// 'require_featured_image_msg'			=> apply_filters( 'wcv_require_featured_image_msg', __( 'Featured image is required.', 'wcvendors-pro' ) ), 
					// 'require_gallery_image'					=> $required_media_options[ 'gallery' ], 
					// 'require_gallery_image_msg'				=> apply_filters( 'wcv_require_featured_image_msg', __( 'A gallery image is required.', 'wcvendors-pro' ) ), 
					// 'require_category'						=> $required_basic_options[ 'categories' ], 
					// 'require_category_msg'					=> apply_filters( 'wcv_require_category_msg', __( 'A category is required.', 'wcvendors-pro' ) ), 
					// 'require_tag'							=> $required_basic_options[ 'tags' ], 
					// 'require_tag_msg'						=> apply_filters( 'wcv_require_tag_msg', __( 'A tag is required.', 'wcvendors-pro' ) ), 
					// 'require_crosssells'					=> $required_upsell_options[ 'crosssells' ], 
					// 'require_crosssells_msg'				=> apply_filters( 'wcv_require_crosssells_msg', __( 'A cross sell is required.', 'wcvendors-pro' ) ), 
					// 'require_up_sells'						=> $required_upsell_options[ 'up_sells' ], 
					// 'require_up_sells_msg'					=> apply_filters( 'wcv_require_up_sells_msg', __( 'An up sell is required.', 'wcvendors-pro' ) ), 
					// 'require_download_file'					=> $required_general_options[ 'download_files' ], 
					// 'require_download_file_msg'				=> apply_filters( 'wcv_require_download_file_msg', __( 'A download file is required.', 'wcvendors-pro' ) ), 
					// 'require_attributes'					=> $required_basic_options[ 'attributes' ], 
					// 'require_attributes_msg'				=> apply_filters( 'wcv_require_attributes_msg', __( 'An attribute is required.', 'wcvendors-pro' ) ), 

				); 	

				wp_register_script( 'wcv-frontend-product', $this->base_dir . 'assets/js/product' . $this->suffix	 . '.js', array('jquery-ui-core' ), WCV_PRO_VERSION, true ); 				
				wp_localize_script( 'wcv-frontend-product', 'wcv_frontend_product',  $product_params );
				wp_enqueue_script( 'wcv-frontend-product' ); 

				// Product Variation 
				$product_variation_params = array(
					'ajax_url'                      		=> admin_url( 'admin-ajax.php' ),
					'wcv_add_variation_nonce'               => wp_create_nonce( 'wcv-add-variation' ),
					'wcv_link_variation_nonce'              => wp_create_nonce( 'wcv-link-variations' ),
					'wcv_delete_variations_nonce'           => wp_create_nonce( 'wcv-delete-variations' ),
					'wcv_json_link_all_variations_nonce'	=> wp_create_nonce( 'wcv-link-all-variations'), 
					'wcv_load_variations_nonce'             => wp_create_nonce( 'wcv-load-variations' ),
					'wcv_bulk_edit_variations_nonce'        => wp_create_nonce( 'wcv-bulk-edit-variations' ),
					'wcv_woocommerce_placeholder_img_src'   => wc_placeholder_img_src(),
					'wc_deliminator'						=> WC_DELIMITER, 
					'i18n_link_all_variations'            	=> esc_js( __( 'Are you sure you want to link all variations? This will create a new variation for each and every possible combination of variation attributes (max 50 per run).', 'wcvendors-pro' ) ),	
					'i18n_enter_a_value'                  	=> esc_js( __( 'Enter a value', 'wcvendors-pro' ) ),
					'i18n_enter_menu_order'               	=> esc_js( __( 'Variation menu order (determines position in the list of variations)', 'wcvendors-pro' ) ),
					'i18n_enter_a_value_fixed_or_percent' 	=> esc_js( __( 'Enter a value (fixed or %)', 'wcvendors-pro' ) ),
					'i18n_delete_all_variations'          	=> esc_js( __( 'Are you sure you want to delete all variations? This cannot be undone.', 'wcvendors-pro' ) ),
					'i18n_last_warning'                   	=> esc_js( __( 'Last warning, are you sure?', 'wcvendors-pro' ) ),
					'i18n_choose_image'                   	=> esc_js( __( 'Choose an image', 'wcvendors-pro' ) ),
					'i18n_set_image'                      	=> esc_js( __( 'Set variation image', 'wcvendors-pro' ) ),
					'i18n_variation_added'                	=> esc_js( __( "variation added", 'wcvendors-pro' ) ),
					'i18n_variations_added'               	=> esc_js( __( "variations added", 'wcvendors-pro' ) ),
					'i18n_no_variations_added'            	=> esc_js( __( "No variations added", 'wcvendors-pro' ) ),
					'i18n_remove_variation'               	=> esc_js( __( 'Are you sure you want to remove this variation?', 'wcvendors-pro' ) ),
					'i18n_scheduled_sale_start'           	=> esc_js( __( 'Sale start date (YYYY-MM-DD format or leave blank)', 'wcvendors-pro' ) ),
					'i18n_scheduled_sale_end'             	=> esc_js( __( 'Sale end date (YYYY-MM-DD format or leave blank)', 'wcvendors-pro' ) ),
					'i18n_edited_variations'              	=> esc_js( __( 'Save changes before changing page?', 'wcvendors-pro' ) ),
					'i18n_variation_count_single'         	=> esc_js( __( '%qty% variation', 'wcvendors-pro' ) ),
					'i18n_variation_count_plural'         	=> esc_js( __( '%qty% variations', 'wcvendors-pro' ) ),
					'i18n_any_label' 						=> esc_js( __( 'Any', 'wcvendors-pro' ) ),
					'variations_per_page'                 	=> absint( apply_filters( 'woocommerce_admin_meta_boxes_variations_per_page', 15 ) )
				); 

				wp_register_script( 'wcv-frontend-product-variation', $this->base_dir . 'assets/js/product-variation' . $this->suffix	 . '.js', array( 'jquery', 'jquery-ui-core', 'accounting' ), WCV_PRO_VERSION, true ); 		
				wp_localize_script( 'wcv-frontend-product-variation', 'wcv_frontend_product_variation',  $product_variation_params );
				wp_enqueue_script( 'wcv-frontend-product-variation' ); 

				// Order 
				wp_register_script( 'wcv-frontend-order', $this->base_dir . 'assets/js/order' . $this->suffix	 . '.js', array( 'jquery' ), WCV_PRO_VERSION, true ); 				
				wp_enqueue_script( 'wcv-frontend-order' );
		
				$general_settings_params = array( 
					'date_format' => apply_filters( 'wcv-datepicker-dateformat', 'yy-mm-dd' ), 
				); 

				// Country select 
				$country_select_args = array(
					'countries'                 => json_encode( array_merge( WC()->countries->get_allowed_country_states(), WC()->countries->get_shipping_country_states() ) ),
					'i18n_select_state_text'    => esc_attr__( 'Select an option&hellip;', 'wcvendors-pro' ),
					'i18n_matches_1'            => _x( 'One result is available, press enter to select it.', 'enhanced select', 'wcvendors-pro' ),
					'i18n_matches_n'            => _x( '%qty% results are available, use up and down arrow keys to navigate.', 'enhanced select', 'wcvendors-pro' ),
					'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', 'wcvendors-pro' ),
					'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', 'wcvendors-pro' ),
					'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', 'wcvendors-pro' ),
					'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', 'wcvendors-pro' ),
					'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', 'wcvendors-pro' ),
					'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', 'wcvendors-pro' ),
					'i18n_selection_too_long_1' => _x( 'You can only select 1 item', 'enhanced select', 'wcvendors-pro' ),
					'i18n_selection_too_long_n' => _x( 'You can only select %qty% items', 'enhanced select', 'wcvendors-pro' ),
					'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'wcvendors-pro' ),
					'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'wcvendors-pro' ),
				);

				wp_register_script( 'wcv-country-select', $this->base_dir . '../includes/assets/js/country-select' . $this->suffix	 . '.js', array( 'jquery' ), WCV_PRO_VERSION, true ); 				
				wp_localize_script( 'wcv-country-select', 'wcv_country_select_params',  $country_select_args );
				wp_enqueue_script( 'wcv-country-select' );

				// General settings 
				wp_register_script( 'wcv-frontend-general', $this->base_dir . 'assets/js/general' . $this->suffix	 . '.js', array( 'jquery', 'select2' ), WCV_PRO_VERSION, true ); 				
				wp_localize_script( 'wcv-frontend-general', 'wcv_frontend_general',  $general_settings_params );
				wp_enqueue_script( 'wcv-frontend-general' );

				// Jquery-ui datepicker 
	    		wp_enqueue_script( 'jquery-ui-datepicker' );
	    		wp_enqueue_script( 'jquery-ui-slider' );

	    		// Load jquery-datepicker-timepicker 
	    		wp_enqueue_script( 'wcv-timepicker',  $this->base_dir . 'assets/lib/jquery-ui/jquery-ui-timepicker.js', array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-datepicker' ), '1.6.3', true );	



			}  // user logged in check 
		
		} // on dashboard page 

	}

	/**
	 * Select 2 seperator options for tag search 
	 * 
	 * @since 1.3.6
	 * @access public 
	 * @return array separator types 
	 */
	public function select2_separator( $option ){ 

		switch ( $option ) {
			case 'space':
				return array( ' ' ); 
				break;
			case 'comma':
				return array( ',' ); 
				break;
			default:
			 	return apply_filters( 'wcv_tag_separator_defaults', array( ',', ' ' ) ); 
				break;
		}

	} // select2_separator() 

}