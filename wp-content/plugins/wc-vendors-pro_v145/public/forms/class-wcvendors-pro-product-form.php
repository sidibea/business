<?php
/**
 * The WCVendors Pro Product Form class
 *
 * This is the order form class
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/forms
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Product_Form {

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
	 * Product basic options
	 *
	 * @since    1.1.5
	 * @access   private
	 * @var      array    $basic_options  array with options from admin
	 */
	private static $basic_options;

	/**
	 *  Product Media options
	 *
	 * @since    1.1.5
	 * @access   private
	 * @var      array    $media_options  array with options from admin
	 */
	private static $media_options;

	/**
	 * Product General options
	 *
	 * @since    1.1.5
	 * @access   private
	 * @var      array    $general_options  array with options from admin
	 */
	private static $general_options;

	/**
	 * Product Inventory options
	 *
	 * @since    1.1.5
	 * @access   private
	 * @var      array    $inventory_options  array with options from admin
	 */
	private static $inventory_options;

	/**
	 * Product Shipping options
	 *
	 * @since    1.1.5
	 * @access   private
	 * @var      array    $shipping_options  array with options from admin
	 */
	private static $shipping_options;	

	/**
	 * Product Upsell options
	 *
	 * @since    1.1.5
	 * @access   private
	 * @var      array    $upsell_options  array with options from admin
	 */
	private static $upsell_options;


	/**
	 * Product grouped options
	 *
	 * @since    1.1.5
	 * @access   private
	 * @var      array    $upsell_options  array with options from admin
	 */
	private static $grouped_products;


	/**
	 * Product basic required options
	 *
	 * @since    1.4.0
	 * @access   private
	 * @var      array    $required_basic_options  array with options from admin
	 */
	private static $required_basic_options;

	/**
	 *  Product Media required options
	 *
	 * @since    1.4.0
	 * @access   private
	 * @var      array    $media_options  array with options from admin
	 */
	private static $required_media_options;

	/**
	 * Product General options
	 *
	 * @since    1.1.5
	 * @access   private
	 * @var      array    $general_options  array with options from admin
	 */
	private static $required_general_options;

	/**
	 * Product Inventory required options
	 *
	 * @since    1.4.0
	 * @access   private
	 * @var      array    $required_inventory_options  array with options from admin
	 */
	private static $required_inventory_options;

	/**
	 * Product Shipping required options
	 *
	 * @since    1.4.0
	 * @access   private
	 * @var      array    $required_shipping_options  array with options from admin
	 */
	private static $required_shipping_options;	

	/**
	 * Product Upsell required options
	 *
	 * @since    1.4.0
	 * @access   private
	 * @var      array    $required_upsell_options  array with options from admin
	 */
	private static $required_upsell_options;


	/**
	 * Product grouped required options
	 *
	 * @since    1.4.0
	 * @access   private
	 * @var      array    $required_grouped_products  array with options from admin
	 */
	private static $required_grouped_products;

	/**
	 * Max gallery upload limit
	 *
	 * @since    1.1.5
	 * @access   private
	 * @var      array    $shipping_options  array with options from admin
	 */
	private static $product_max_gallery_count;

	/**
	 * Hide product categories list
	 *
	 * @since    1.1.5
	 * @access   private
	 * @var      array    $hide_categories_list  text csv of categories to hide from the front end.
	 */
	private static $hide_categories_list;

	/**
	 * Hide product attributes list
	 *
	 * @since    1.1.5
	 * @access   private
	 * @var      array    $hide_categories_list  text csv of attributes to hide from the front end.
	 */
	private static $hide_attributes_list;

	/**
	 * Category display 
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $hide_categories_list  text csv of attributes to hide from the front end.
	 */
	private static $category_display;

	/**
	 * Tag display 
	 *
	 * @since    1.2.5
	 * @access   private
	 */
	private static $tag_display;


	/**
	 * File display 
	 *
	 * @since    1.3.0
	 * @access   private
	 */
	private static $file_display; 

	/**
	 * Product Form capabilities
	 *
	 * @since    1.2.5
	 * @access   private
	 */
	private static $form_caps;

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
	 * @since    1.1.5
	 */
	public function init() { 

		// Show / Hide  
		self::$basic_options 				= (array) WC_Vendors::$pv_options->get_option( 'hide_product_basic' );
		self::$media_options 				= (array) WC_Vendors::$pv_options->get_option( 'hide_product_media' );
		self::$general_options 				= (array) WC_Vendors::$pv_options->get_option( 'hide_product_general' );
		self::$inventory_options 			= (array) WC_Vendors::$pv_options->get_option( 'hide_product_inventory' );
		self::$shipping_options 			= (array) WC_Vendors::$pv_options->get_option( 'hide_product_shipping' );
		self::$upsell_options 				= (array) WC_Vendors::$pv_options->get_option( 'hide_product_upsells' );

		// Required fields 
		$required_basic_options_defaults 	= array(
				'description' 		=> __( 'Description', 'wcvendors-pro' ),
				'short_description' => __( 'Short Description', 'wcvendors-pro' ),
				'categories'		=> __( 'Categories', 'wcvendors-pro' ),
				'tags'				=> __( 'Tags', 'wcvendors-pro' ),
				'attributes'		=> __( 'Attributes', 'wcvendors-pro' ),
		); 
		self::$required_basic_options 		= wp_parse_args( $required_basic_options_defaults, (array) WC_Vendors::$pv_options->get_option( 'required_product_basic' ) );

		$required_media_options_defaults 	=  array(
				'featured' 		=> __( 'Featured Image', 'wcvendors-pro' ),
				'gallery' 		=> __( 'Gallery', 'wcvendors-pro' ),
		); 
		self::$required_media_options 		= wp_parse_args( $required_media_options_defaults, (array) WC_Vendors::$pv_options->get_option( 'required_product_media' ) );

		$required_product_general_defaults 	= array(
				'sku' 				=> __( 'SKU', 'wcvendors-pro' ),
				'private_listing' 	=> __( 'Private listing', 'wcvendors-pro' ),
				'external_url'		=> __( 'External URL', 'wcvendors-pro' ),
				'button_text'		=> __( 'Button text for external url', 'wcvendors-pro' ),
				'price'				=> __( 'Price', 'wcvendors-pro' ),
				'sale_price'		=> __( 'Sale price', 'wcvendors-pro' ),
				'tax'				=> __( 'Tax', 'wcvendors-pro' ),
				'download_files'	=> __( 'Download files', 'wcvendors-pro' ),
				'download_limit'	=> __( 'Download limit', 'wcvendors-pro' ),
				'download_expiry'	=> __( 'Download expiry', 'wcvendors-pro' ),
				'download_type'		=> __( 'Download type', 'wcvendors-pro' ),
		); 
		self::$required_general_options 	= wp_parse_args( $required_product_general_defaults, (array) WC_Vendors::$pv_options->get_option( 'required_product_general' ) );

		$required_inventory_options_defaults = array(
				'manage_inventory' 	=> __( 'Manage Inventory', 'wcvendors-pro' ),
				'stock_qty' 		=> __( 'Stock Qty', 'wcvendors-pro' ),
				'backorders'		=> __( 'Backorders', 'wcvendors-pro' ),
				'stock_status'		=> __( 'Stock status', 'wcvendors-pro' ),
				'sold_individually'	=> __( 'Sold individually', 'wcvendors-pro' ),
		); 
		self::$required_inventory_options 	= wp_parse_args( $required_inventory_options_defaults, (array) WC_Vendors::$pv_options->get_option( 'required_product_inventory' ) );
		
		$required_shipping_options_defaults = array(
				'weight' 				=> __( 'Weight', 'wcvendors-pro' ),
				'handling_fee' 			=> __( 'Product handling fee', 'wcvendors-pro' ),
				'max_charge' 			=> __( 'Maximum shipping charge', 'wcvendors-pro' ),
				'free_shipping_product' => __( 'Free Shipping Product', 'wcvendors-pro' ),
				'dimensions'			=> __( 'Dimensions', 'wcvendors-pro' ),
				'shipping_class'		=> __( 'Shipping class', 'wcvendors-pro' ),
		); 	
		self::$required_shipping_options 	= wp_parse_args( $required_shipping_options_defaults, (array) WC_Vendors::$pv_options->get_option( 'required_product_shipping' ) );
		
		$required_upsell_options_defaults 	= array(
				'up_sells' 			=> __( 'Up sells', 'wcvendors-pro' ),
				'crosssells' 		=> __( 'Cross sells', 'wcvendors-pro' ),
		); 
		self::$required_upsell_options 		= wp_parse_args( $required_upsell_options_defaults, (array) WC_Vendors::$pv_options->get_option( 'required_product_upsells' ) );  

		self::$product_max_gallery_count 	= (int) WC_Vendors::$pv_options->get_option( 'product_max_gallery_count' );
		self::$hide_categories_list 		= WC_Vendors::$pv_options->get_option( 'hide_categories_list' );
		self::$hide_attributes_list 		= WC_Vendors::$pv_options->get_option( 'hide_attributes_list' );
		self::$category_display 			= WC_Vendors::$pv_options->get_option( 'category_display' );
		self::$tag_display 					= WC_Vendors::$pv_options->get_option( 'tag_display' );
		self::$file_display 				= WC_Vendors::$pv_options->get_option( 'file_display' );
		self::$form_caps 					= (array) WC_Vendors::$pv_options->get_option( 'product_form_cap' );

	} // init() 


	/**
	 *  Set gallery image upload limit options
	 * 
	 * @since    1.1.5
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public function product_max_gallery_count( $gallery_options ){
    
    	$gallery_options[ 'max_upload' ] = self::$product_max_gallery_count; // Change 1 to whatever you want it to be, default in Pro is 4.
    	return $gallery_options;

	} // product_max_gallery_count()


	/**
	 *  Output required form data 
	 * 
	 * @since    1.0.0
	 * @version  1.4.0 
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function form_data( $post_id, $post_status, $template = '' ) {

		if ( $post_id != null ) { 

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_post_id', array( 
				'post_id'		=> $post_id, 
				'type'			=> 'hidden', 
				'id' 			=> 'post_id', 
				'value'			=> $post_id
				) )
			);

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_post_status', array( 
				'post_id'		=> $post_id, 
				'type'			=> 'hidden', 
				'id' 			=> 'post_status', 
				'value'			=> $post_status
				) )
			);
		} 

		// If the template variable has been defined then save this with the product 
		if ( !empty( $template ) ){ 

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_form_template', array( 
				'post_id'		=> $post_id, 
				'type'			=> 'hidden', 
				'id' 			=> '_wcv_product_form_template', 
				'value'			=> $template
				) )
			);

		}

		wp_nonce_field( 'wcv-save_product', '_wcv-save_product' );	

	} 

	/**
	 *  Output product title 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function title( $post_id, $product_title ) {

		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_title', array( 
		 	'post_id' 			=> $post_id, 
		 	'id'	 			=> 'post_title', 
		 	'label' 			=> __( 'Product Name', 'wcvendors-pro' ),
		 	'value' 			=> $product_title, 
		 	'custom_attributes' => array( 
		 			'data-rules' => 'required|max_length[100]', 
		 			'data-error' => __( 'Product name is required or is too long.', 'wcvendors-pro' ), 
		 			'data-label' => __( 'Product Name', 'wcvendors-pro' ),

		 		)
		 	) )
		);

	} // title()

	/**
	 *  Output product description  
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function description( $post_id, $product_description ) {

		if ( ! self::$basic_options[ 'description' ] ) { 

			// $custom_attributes = ( self::$required_basic_options[ 'description' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Product description is required.', 'wcvendors-pro' ) ) : array();  

			WCVendors_Pro_Form_Helper::textarea( apply_filters( 'wcv_product_description', array( 
			 	'post_id'	=> $post_id, 
			 	'id' 		=> 'post_content', 
			 	'label'	 	=> __( 'Product Description', 'wcvendors-pro' ), 
			 	'value' 	=> $product_description, 
			 	'placeholder' 		=> __( 'Please add a full description of your product here', 'wcvendors-pro' ), 
			 	// 'custom_attributes' => $custom_attributes
			 	) )
			 );
		} 

	} // description()


	/**
	 *  Output product short_description  
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function short_description( $post_id, $product_short_description ) {

		if ( ! self::$basic_options[ 'short_description' ] ) { 

			// $custom_attributes = ( self::$required_basic_options[ 'short_description' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Product short description is required.', 'wcvendors-pro' ) ) : array();  

			 WCVendors_Pro_Form_Helper::textarea( apply_filters( 'wcv_product_short_description', array( 
			 	'post_id'			=> $post_id, 
			 	'id' 				=> 'post_excerpt', 
			 	'label'	 			=> __( 'Product Short Description', 'wcvendors-pro' ), 
			 	'placeholder' 		=> __( 'Please add a brief description of your product here', 'wcvendors-pro' ), 
			 	'value' 	=> $product_short_description, 
			 	// 'custom_attributes' => $custom_attributes
			 	) )
			 );
		} 

	} // short_description()


	/**
	 *  Output save button 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function save_button( $button_text ) {

		$can_edit 			= WC_Vendors::$pv_options->get_option( 'can_edit_published_products');
		$can_submit_live 	= WC_Vendors::$pv_options->get_option( 'can_submit_live_products' ); 

		if ( ! $can_submit_live && ! $can_edit ) $button_text = __( 'Save Pending', 'wcvendors-pro' ); 

		WCVendors_Pro_Form_helper::submit( apply_filters( 'wcv_product_save_button', array( 
		 	'id' 		=> 'save_button', 
		 	'value' 	=> $button_text, 
		 	'class'		=> ''
		 	) )
		 ); 

	} // save_button()

	/**
	 *  Output save button 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function draft_button( $button_text ) {

		WCVendors_Pro_Form_helper::submit( apply_filters( 'wcv_product_draft_button', array( 
		 	'id' 		=> 'draft_button', 
		 	'value' 	=> $button_text, 
		 	'class'		=> ''
		 	) )
		 ); 

	} // save_button()

	/**
	 *  Output product categories  
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 * @param 	 bool 	$multiple allow mupltiple selection 
	 */
	public static function categories( $post_id, $multiple = false ) {

		if ( ! self::$basic_options[ 'categories' ] ) { 


			if ( self::$category_display == 'select' ) { 
				self::categories_dropdown( $post_id, $multiple ); 
			} else { 
				self::categories_checklist( $post_id ); 
			}
		} 

	} // categories()

	/**
	 *  Output product categories drop down
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 * @param 	 bool 	$multiple allow mupltiple selection 
	 */
	public static function categories_dropdown( $post_id, $multiple = false ) {

		if ( ! self::$basic_options[ 'categories' ] ) { 

			$custom_attributes 	= ( $multiple ) ? array( 'multiple' => 'multiple' ) : array(); 
			$show_option_none 	= ( $multiple ) ? '' : __( 'Select a Category', 'wcvendors-pro' ); 
			$exclude = array(); 

			if ( !empty( self::$hide_categories_list ) ) { 
				$exclude = explode(',', str_replace( ' ', '', self::$hide_categories_list ) ); 
			}

			// Product Category Drop down 
			WCVendors_Pro_Form_Helper::select2( apply_filters( 'wcv_product_categories', 
				array( 
					'post_id'			=> $post_id, 
					'id' 				=> 'product_cat[]', 
					'taxonomy'			=> 'product_cat', 
					'class'				=> 'category-select2',
					'show_option_none'	=> $show_option_none,
					'taxonomy_args'		=> array( 
											'hide_empty'	=> 0, 
											'orderby'		=> 'order', 					
											'exclude'		=> $exclude, 
										), 	
					'label'	 			=> ( $multiple ) ? __( 'Categories', 'wcvendors-pro' ) : __( 'Category', 'wcvendors-pro' ), 
					'custom_attributes' => $custom_attributes, 
					) 
				)
			);
		} 

	} // categories()


	/**
	 *  Output product categories check list
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function categories_checklist( $post_id ) {

		if ( ! self::$basic_options[ 'categories' ] ) { 

			$exclude = array(); 

			if ( !empty( self::$hide_categories_list ) ) { 
				$exclude = explode(',', str_replace( ' ', '', self::$hide_categories_list ) ); 
			}

			$args = array( 
				'taxonomy' => 'product_cat', 
				'exclude'  => $exclude, 
			); 

			$field = array( 
				'id'  	=> 'product_cat_list',
				'label' => __( 'Categories', 'wcvendors-pro' ), 
				'class'	=> 'product_cat_checklist'
			); 

			WCVendors_Pro_Form_Helper::wcv_terms_checklist( $post_id, $args, $field ); 
		} 

	} // categories_checklist() 


	/**
	 * DEPRECATED This function has been replaced - Output a woocommerce attribute selects 
	 *
	 * @since      1.0.0
	 * @param      array     $field      Array defining all field attributes 
	 * @todo       add filters to allow the field to be hooked into this should not echo html but return it. 
	 */
	public static function attributes( $post_id, $multiple = false ) { 

		if ( ! self::$basic_options[ 'attributes' ] ) { 

			// Array of defined attribute taxonomies
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			// If there are any defined attributes display them 
			if ( !empty( $attribute_taxonomies ) ) { 

				$i = 0; 
				// Get any set attributes for the product 
				$attributes  = maybe_unserialize( get_post_meta( $post_id, '_product_attributes', true ) );

				foreach ( $attribute_taxonomies as $product_attribute ) {

					if ( in_array( $product_attribute->attribute_id, explode( ',', self::$hide_attributes_list ) ) ) continue;  

					$current_attribute = '';
					$is_variation = 'no';
					// $custom_attributes 	= ( $multiple ) ? array( 'multiple' => 'multiple' ) : array(); 

					// If the attributes aren't empty, extract the attribute value for the current product 
					if ( ! empty( $attributes ) && array_key_exists( wc_attribute_taxonomy_name( $product_attribute->attribute_name ), $attributes ) ) { 
						// get all terms 
						$current_attribute = wp_get_post_terms( $post_id, wc_attribute_taxonomy_name( $product_attribute->attribute_name ) );
						$is_variation = $attributes[ wc_attribute_taxonomy_name($product_attribute->attribute_name) ]['is_variation'] ? 'yes' : 'no' ; 
						$current_attribute = reset ( $current_attribute ); 
						$current_attribute = $current_attribute->slug;
					}

					// Output attribute select 
					WCVendors_Pro_Form_Helper::select( array( 
						'id' 				=> 'attribute_values[' . $i . '][]', 
						'post_id'			=> $post_id, 
						'label' 			=> ucfirst( $product_attribute->attribute_label ),
						'value' 			=> $current_attribute, 
						'show_option_none'  => __( 'Select a ', 'wcvendors-pro' ) . ucfirst( $product_attribute->attribute_label ),
						'taxonomy'			=> wc_attribute_taxonomy_name( $product_attribute->attribute_name ), 
						'is_attribute'		=> true, 
						'taxonomy_args'		=> array( 
												'hide_empty'	=> 0, 
												'orderby'		=> 'order' 
											), 
						// 'custom_attributes' => $custom_attributes, 
						)
					);

					// Output attribute name hidden 
					WCVendors_Pro_Form_Helper::input( array( 
										'post_id'				=> $post_id, 
										'id' 					=> 'attribute_names['.$i.']', 
										'type' 					=> 'hidden', 
										'show_label'			=> false, 
										'value'					=> wc_attribute_taxonomy_name( $product_attribute->attribute_name ), 
										)	
					);
					$i++; 
				}
			}

			// Support other plugins hooking into attributes 
			// Not sure if this will work ? 
			do_action( 'wcv_product_options_attributes' );

		} 

	} //attribute()


	/**
	 *  Output product tags  
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 * @param 	 bool 	$multiple allow mupltiple selection 
	 */
	public static function tags( $post_id, $multiple = false ) {

		if ( ! self::$basic_options[ 'tags' ] ) { 

			if ( self::$tag_display == 'select_limited' ) { 
				self::tags_select_limited( $post_id, $multiple ); 
			} else { 
				self::tags_select( $post_id, $multiple );
			}
		} 

	} // tags()


	/**
	 *  Output product tags multi select
	 * 
	 * @since    1.3.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 * @param 	 bool 	$multiple allow mupltiple selection 
	 */
	public static function tags_select( $post_id, $multiple = false ) {

		$tags 		= wp_get_post_terms( $post_id, 'product_tag' ); 
		$tag_ids    = array();

		foreach ( $tags as $tag ) {
				$tag_ids[ $tag->term_id ] = wp_kses_post( html_entity_decode( $tag->name ) );
		} 

		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_tags', array( 
				'id' 					=> 'product_tags', 
				'label' 				=> __( 'Tags', 'wcvendors-pro' ), 
				'value' 				=> implode( ',', array_keys( $tag_ids ) ), 
				'style'					=> 'width: 100%;', 
				'class'					=> 'wcv-tag-search tag-select2', 
				'type'					=> 'hidden', 
				'show_label'			=> 'true', 
				'custom_attributes' 	=> array(
						'data-placeholder' 	=> __( 'Search or add a tag&hellip;', 'wcvendors-pro' ), 
						'data-action'		=> 'wcv_json_search_tags', 
						'data-multiple' 	=> 'true', 
						'data-tags'			=> 'true', 
						'data-selected'		=> esc_attr( json_encode( $tag_ids ) ) 
					),
			) )
		);

	} // tags_select()


	/**
	 *  Output product tags multi select limited to defined tags
	 * 
	 * @since    1.3.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 * @param 	 bool 	$multiple allow mupltiple selection 
	 */
	public static function tags_select_limited( $post_id, $multiple = false ) {


		$custom_attributes 	= ( $multiple ) ? array( 'multiple' => 'multiple' ) : array(); 
		$show_option_none 	= ( $multiple ) ? '' : __( 'Select a Tag', 'wcvendors-pro' ); 

		// Product Tag Drop down 
		WCVendors_Pro_Form_Helper::select2( apply_filters( 'wcv_product_tags_dropdown', 
			array( 
				'post_id'			=> $post_id, 
				'id' 				=> 'product_tags[]', 
				'taxonomy'			=> 'product_tag', 
				'class'				=> 'tag-select2 select2', 
				'show_option_none'	=> $show_option_none,
				'taxonomy_args'		=> array( 
										'hide_empty'	=> 0, 
										'orderby'		=> 'order', 					
									), 	
				'label'	 			=> ( $multiple ) ? __( 'Tags', 'wcvendors-pro' ) : __( 'Tag', 'wcvendors-pro' ), 
				'custom_attributes' => $custom_attributes, 
				) 
			)
		);
		
	} // tags_select_limited()


	/**
	 *  Output product type 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 * @todo  	 remove all echo statements and html 
	 */
	public static function product_type( $post_id ) {

		$product = 	( is_numeric( $post_id ) ) ? wc_get_product( $post_id ) : null;

		if ( $product != null ) { 
			if ( $terms = wp_get_object_terms( $post_id, 'product_type' ) ) {
				$product_type = sanitize_title( current( $terms )->name );
			} else {
				$product_type = apply_filters( 'wcv_default_product_type', 'simple' );
			}
		} else { 
			$product_type = apply_filters( 'wcv_default_product_type', 'simple' );
		}

		$product_type_selector = apply_filters( 'wcv_product_type_selector', array(
			'simple'   => __( 'Simple product', 'wcvendors-pro' ),
			'grouped'  => __( 'Grouped product', 'wcvendors-pro' ),
			'external' => __( 'External/Affiliate product', 'wcvendors-pro' ),
			'variable' => __( 'Variable product', 'wcvendors-pro' )
		), $product_type );

		// Disable capabitilies based on settings
		$product_type_settings = (array) WC_Vendors::$pv_options->get_option( 'hide_product_types' );

		foreach ( $product_type_settings as $product_type_setting => $value ) {

			if ( array_key_exists( $product_type_setting, $product_type_selector ) ) { 
				if ( $value ) unset( $product_type_selector[ $product_type_setting ] ); 
			}
		}		

		$type_box = '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">';      	
		$type_box .= '<div class="control-group">'; 
		$type_box .= '<label>'.__('Product Type', 'wcvendors-pro').'</label>'; 
		$type_box .= '<div class="control select">'; 
		$type_box .= '<select id="product-type" name="product-type" class="select2">';

		foreach ( $product_type_selector as $value => $label ) {
			$type_box .= '<option value="' . esc_attr( $value ) . '" ' . selected( $product_type, $value, false ) .'>' . esc_html( $label ) . '</option>';
		}

		$type_box .= '</select>';
		$type_box .= '</div>'; //control 
		$type_box .= '</div>'; //control-group 
		$type_box .= '</div>'; // grid

		$product_type_options = apply_filters( 'product_type_options', array(
			'virtual' => array(
				'id'            => '_virtual',
				'wrapper_class' => 'show_if_simple',
				'label'         => __( 'Virtual', 'wcvendors-pro' ),
				'description'   => __( 'Virtual products are intangible and aren\'t shipped.', 'wcvendors-pro' ),
				'default'       => 'no'
			),
			'downloadable' => array(
				'id'            => '_downloadable',
				'wrapper_class' => 'show_if_simple',
				'label'         => __( 'Downloadable', 'wcvendors-pro' ),
				'description'   => __( 'Downloadable products give access to a file upon purchase.', 'wcvendors-pro' ),
				'default'       => 'no'
			)
		) );

		// Disable capabitilies based on settings
		$product_type_options_settings = (array) WC_Vendors::$pv_options->get_option( 'hide_product_type_options' );

		foreach ( $product_type_options_settings as $product_type_options_setting => $value ) {

			if ( array_key_exists( $product_type_options_setting, $product_type_options ) ) { 
				if ( $value ) unset( $product_type_options[ $product_type_options_setting ] ); 
			}
		}

		$type_box .= '<div class="all-50 small-100">';     

		$type_box .= '<div class="control-group"> <br />';	


		$type_box .= '<ul class="control unstyled inline" style="padding: 0; margin:0;">'; 			

		foreach ( $product_type_options as $key => $option ) {

			$selected_value = ( is_numeric( $post_id ) ) ? get_post_meta( $post_id, '_' . $key, true ) : '';

			if ( '' == $selected_value && isset( $option['default'] ) ) {
				$selected_value = $option['default'];
			}

			$type_box .= '<li class="'. esc_attr( $option['wrapper_class'] ) . ' "><input type="checkbox" name="' . esc_attr( $option['id'] ) . '" id="' . esc_attr( $option['id'] ) . '" ' . checked( $selected_value, 'yes', false ) .' /><label for="' . esc_attr( $option['id'] ) . '" class="'. esc_attr( $option['wrapper_class'] ) . ' " data-tip="' . esc_attr( $option['description'] ) . '">' . esc_html( $option['label'] ) . '</label></li>';
		}

		$type_box .= '</ul>';

		$type_box .= '</div>';  // control 
		$type_box .= '</div>';  // control-group
		$type_box .= '</div>';  // grid

		echo $type_box; 

	} 

	/**
	 *  Output a hidden product type input for use with different templates 
	 * 
	 * @since    1.4.0
	 * @param 	 int 		$post_id  post_id for this meta if any 
	 * @param 	 string 	$product_type  product_type to set 
	 */
	public static function product_type_hidden( $post_id, $product_type = 'simple' ){ 

		//Product Type 
		WCVendors_Pro_Form_Helper::input( array( 
						'post_id'		=> $post_id, 
						'type'			=> 'hidden', 
						'id' 			=> 'product-type', 
						'value'			=> $product_type, 
						'show_label'	=> false
						)
		);

	} // hidden_product_type()

	/**
	 *  Output a hidden virtual product input for use with different templates 
	 * 
	 * @since    1.4.0
	 * @param 	 int 		$post_id  post_id for this meta if any 
	 */
	public static function virtual_product_hidden( $post_id ){ 

		// Virtual Product 
		WCVendors_Pro_Form_Helper::input( array( 
						'post_id'		=> $post_id, 
						'type'			=> 'hidden', 
						'id' 			  => '_virtual', 
						'value'			=> 'yes', 
						'show_label'	=> false
						)
		);

	} // virtual_product() 

	/**
	 *  Output a hidden downloadable product input for use with different templates 
	 * 
	 * @since    1.4.0
	 * @param 	 int 		$post_id  post_id for this meta if any 
	 */
	public static function downloadable_product_hidden( $post_id ){ 

		// Downloadable Product 
		WCVendors_Pro_Form_Helper::input( array( 
						'post_id'		=> $post_id, 
						'type'			=> 'hidden', 
						'id' 			  => '_downloadable', 
						'value'			=> 'yes', 
						'show_label'	=> false
						)
		);

	} // downloadable_product() 

	/**
	 *  Output product price 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function price( $post_id ) {

		if ( ! self::$general_options[ 'price' ] ) { 

			$custom_attributes = // ( self::$required_general_options[ 'price' ] ) ? array(  'data-rules' => 'decimal|required', 'data-error' => __( 'Price is required and must be a number.', 'wcvendors-pro' ) ) : 
					array( 'data-rules' => 'decimal', 'data-error' => __( 'Price should be a number.', 'wcvendors-pro' ) );  

			$wrapper_start 	= ! self::$general_options[ 'sale_price' ] ? '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">' : '<div class="all-100">'; 

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_price', array( 
				'post_id'		=> $post_id, 
				'id' 			=> '_regular_price', 
				'label' 		=> __( 'Regular Price', 'wcvendors-pro' ) . ' (' . get_woocommerce_currency_symbol() . ')', 
				'data_type' 	=> 'price', 
				'wrapper_start' => $wrapper_start, 
				'wrapper_end' 	=> '</div>', 
				'custom_attributes' => $custom_attributes 
				) )
			);
		} 
	} 

	/**
	 *  Output product sale price 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function sale_price( $post_id ) {

		if ( ! self::$general_options[ 'price' ] && ! self::$general_options[ 'sale_price' ] ) {

			$custom_attributes = // ( self::$required_general_options[ 'sale_price' ] ) ? array(  'data-rules' => 'decimal|required', 'data-error' => __( 'Sale price is required and must be a number.', 'wcvendors-pro' ) ) : 
					array( 'data-rules' => 'decimal', 'data-error' => __( 'Sale price should be a number.', 'wcvendors-pro' ) );  

			// Special Price - ends columns and row 
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_sale_price', array( 
				'post_id'		=> $post_id, 
				'id' 			=> '_sale_price', 
				'data_type' 	=> 'price', 
				'label' 		=> __( 'Sale Price', 'wcvendors-pro' ) . ' ('.get_woocommerce_currency_symbol().')', 
				'desc_tip' 		=> 'true', 
				'description' 	=> '<a href="#" class="sale_schedule right">' . __( 'Schedule', 'wcvendors-pro' ) . '</a>', 
				'wrapper_start' => '<div class="all-50 small-100">', 
				'wrapper_end' 	=>  '</div></div>', 
				'custom_attributes' => $custom_attributes 
				) )
			);

			// Special Price date range
			$sale_price_dates_from = $post_id ? ( ( $date = get_post_meta( $post_id, '_sale_price_dates_from', true ) ) ? date_i18n( 'Y-m-d', $date ) : '' ) : '';
			$sale_price_dates_to   = $post_id ? ( ( $date = get_post_meta( $post_id, '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '' ) : '';	
		
			// From Sale Date 
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_sale_price_date_from', array( 
				'post_id'		=> $post_id, 
				'id' 			=> '_sale_price_dates_from', 
				'label' 		=> __( 'From', 'wcvendors-pro' ), 
				'class'			=> 'wcv-datepicker', 
				'value' 		=> esc_attr( $sale_price_dates_from ), 
				'placeholder'	=> ( '' == $sale_price_dates_from ) ? __( 'From&hellip;', 'placeholder', 'wcvendors-pro' ). ' YYYY-MM-DD' : '',  
				'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100 sale_price_dates_fields">',
				'wrapper_end' 	=> '</div>', 
				'custom_attributes' => array(
					'data-close-text' => __( 'Close', 'wcvendors-pro' ), 
					'data-clean-text' => __( 'Clear', 'wcvendors-pro' ), 
					'data-of-text' => __( ' of ', 'wcvendors-pro' ), 
					),
				) )
			);

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_sale_price_date_to', array( 
				'post_id'			=> $post_id, 
				'id' 				=> '_sale_price_dates_to', 
				'label' 			=> __( 'To', 'wcvendors-pro' ), 
				'class'				=> 'wcv-datepicker', 
				'placeholder'		=> ( '' == $sale_price_dates_to ) ? __( 'To&hellip;', 'placeholder', 'wcvendors-pro' ). ' YYYY-MM-DD' : '', 
				'wrapper_start' 	=> '<div class="all-50 small-100 sale_price_dates_fields">',
				'wrapper_end' 		=> '</div></div>', 
				'value' 			=> esc_attr( $sale_price_dates_to ), 
				'desc_tip'			=> true, 
				'description'		=> __( 'The sale will end at the beginning of the set date.', 'wcvendors-pro' ) . '<a href="#" class="cancel_sale_schedule right">'. __( 'Cancel', 'wcvendors-pro' ) .'</a>', 
				'custom_attributes' => array(
					'data-start-date' => '', 
					'data-close-text' => __( 'Close', 'wcvendors-pro' ), 
					'data-clean-text' => __( 'Clear', 'wcvendors-pro' ), 
					'data-of-text' => __( ' of ', 'wcvendors-pro' ), 
					),
				) )
			);
		} 

	} // sale_price()

	/**
	 *  Output product price and sale price 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function prices( $post_id ) {

		self::price( $post_id ); 
		self::sale_price( $post_id ); 

	} 


	/**
	 *  Output downloadable files fields
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function download_files( $post_id ) {

		if ( ! self::$general_options[ 'download_files' ] ) { 

			$readonly 			= ( self::$general_options[ 'download_file_url' ] ) ? 'readonly' : ''; 
			$file_display_type 	= WCVendors_Pro::get_option( 'file_display' );

			include_once( apply_filters( 'wcvendors_pro_product_form_download_files_path', 'partials/wcvendors-pro-downloadable-files.php' ) );
		} 

	} // download_files()


	/**
	 *  Output downloadable files fields
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function product_attributes( $post_id ) {

		if ( ! self::$basic_options[ 'attributes' ] ) { 

			$form_caps = (array) WC_Vendors::$pv_options->get_option( 'product_form_cap' );

			include_once( apply_filters( 'wcvendors_pro_product_form_product_attributes_path', 'partials/wcvendors-pro-attributes.php' ) );
		} 

	} // download_files()


	/**
	 *  Output product download limit
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function download_limit( $post_id ) {

		if ( ! self::$general_options[ 'download_files' ] && ! self::$general_options[ 'download_limit' ] ) { 

			// $custom_attributes = ( self::$required_general_options[ 'download_limit' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Download limit is required.', 'wcvendors-pro' ) ) : array();  

			$wrapper_start 	= self::$general_options[ 'download_expiry' ] ? '<div class="all-100">' : '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">'; 

			// Download Limit
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_dowlnoad_limit', array( 
				'post_id'			=> $post_id, 
				'id' 				=> '_download_limit', 
				'label' 			=> __( 'Download Limit', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'Unlimited', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Leave blank for unlimited re-downloads.', 'wcvendors-pro' ), 
				'type' 				=> 'number', 
				'wrapper_start' 	=> $wrapper_start, 
				'wrapper_end' 		=> '</div>', 
				// 'custom_attributes' => $custom_attributes, 
				) )
			);
		} 

	} // download_limit()


	/**
	 *  Output product download expiry
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function download_expiry( $post_id ) {

		if ( ! self::$general_options[ 'download_files' ] && ! self::$general_options[ 'download_expiry' ] ) { 

			// $custom_attributes = ( self::$required_general_options[ 'download_expiry' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Download expiry is required.', 'wcvendors-pro' ) ) : array();  

			$wrapper_start 	= ! self::$general_options[ 'download_limit' ] ? '<div class="all-50 small-100">' : '<div class="all-100">'; 
			$wrapper_end 	= ! self::$general_options[ 'download_limit' ] ? '</div></div>' : '</div>'; 

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_download_expiry', array( 
				'post_id'			=> $post_id, 
				'id' 				=> '_download_expiry', 
				'label' 			=> __( 'Download Expiry', 'wcvendors-pro' ), 
				'placeholder' 		=> __( 'Never', 'wcvendors-pro' ),
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Enter the number of days before a download link expires, or leave blank.', 'wcvendors-pro' ), 
				'type' 				=> 'number', 
				'wrapper_start' 	=> $wrapper_start,
				'wrapper_end' 		=> $wrapper_end, 
				// 'custom_attributes' => $custom_attributes, 
				) )
			);
		}

	} // download_expiry()


	/**
	 *  Output product download type
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function download_type( $post_id ) {


		if ( ! self::$general_options[ 'download_files' ] && ! self::$general_options[ 'download_type' ] ) { 

			// $custom_attributes = ( self::$required_general_options[ 'download_type' ] ) ? array(  'required' => 'required', 'data-rules' => 'required', 'data-error' => __( 'Download type is required.', 'wcvendors-pro' ) ) : array();  

			// Download Type
			WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_product_download_type', array( 
				'post_id'			=> $post_id, 
				'id' 				=> '_download_type', 
				'class'				=> 'select',
				'label'	 			=> __( 'Download Type', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> sprintf( __( 'Choose a download type - this controls the <a href="%s">http://schema.org</a>.', 'wcvendors-pro' ), 'http://schema.org/' ), 
				'wrapper_start' 	=> '<div class="all-100">',
				'wrapper_end' 		=> '</div>', 
				'options' 			=> array(
					''            	=> __( 'Standard Product', 'wcvendors-pro' ),
					'application' 	=> __( 'Application/Software', 'wcvendors-pro' ),
					'music'       	=> __( 'Music', 'wcvendors-pro' )
				), 	 
				// 'custom_attributes' => $custom_attributes, 
				) )
			);
		} 

	} // download_type()

	/**
	 *  Output product sku
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function sku( $post_id ) {

		$product_misc   = (array) WC_Vendors::$pv_options->get_option( 'hide_product_misc' );
		$sku_disabled 	= false; 

		if ( array_key_exists( 'sku', $product_misc ) ){ 
			$sku_disabled = $product_misc['sku']; 
		}

		if ( !$sku_disabled && ! self::$general_options[ 'sku' ]  ) { 

			if ( wc_product_sku_enabled() ) {

				// $custom_attributes = ( self::$required_general_options[ 'sku' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'SKU is required.', 'wcvendors-pro' ) ) : array();  

				WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_sku', array( 
					'post_id'		=> $post_id, 
					'id' 			=> '_sku', 
					'label' 		=> '<abbr title="'. __( 'Stock Keeping Unit', 'wcvendors-pro' ) .'">' . __( 'SKU', 'wcvendors-pro' ) . '</abbr>', 
					'desc_tip' 		=> 'true', 
					'description' 	=> __( 'SKU refers to a Stock-keeping unit, a unique identifier for each distinct product and service that can be purchased.', 'wcvendors-pro' ), 
					// 'custom_attributes' => $custom_attributes 
					) )
				);
			} else {

				WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_sku', array( 
					'post_id'		=> $post_id, 
					'type'			=> 'hidden', 
					'id' 			=> '_sku', 
					'value'			=> esc_attr( get_post_meta( $post_id, '_sku', true ) )
					) )
				);
			}
		} 

		do_action( 'wcv_product_options_sku' );

	} // sku()

	/**
	 *  Output private listing checkbox
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function private_listing( $post_id ) {

		if ( ! self::$general_options[ 'private_listing' ]  ) {

			// $custom_attributes = ( self::$required_general_options[ 'private_listing' ] ) ? array(  'data-rules' => 'required', 'data-label' => __( 'Private listing is required.', 'wcvendors-pro' ) ) : array();  

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_private_listing', array( 
				'post_id'			=> $post_id, 
				'id' 				=> '_private_listing', 
				'wrapper_class' 	=> '', 
				'label' 			=> __( 'Private Listing, hide this product from the catalog.', 'wcvendors-pro' ), 
				'type' 				=> 'checkbox', 
				// 'custom_attributes' => $custom_attributes  
				) )
			);
		} 

	} // private_listing()


	/**
	 *  Output external url for external products
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function external_url( $post_id ) {

		if ( ! self::$general_options[ 'external_url' ]  ) {

			// $custom_attributes = ( self::$required_general_options[ 'external_url' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'External URL is required.', 'wcvendors-pro' ) ) : array();  

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_product_url', array( 
				'post_id'		=> $post_id, 
				'id' 			=> '_product_url', 
				'label' 		=> __( 'Product URL', 'wcvendors-pro' ), 
				'placeholder' 	=> 'http://', 
				'desc_tip' 		=> 'true', 
				'description' 	=> __( 'Enter the external URL to the product.', 'wcvendors-pro' ), 
				// 'custom_attributes' => $custom_attributes  
				) ) 
			);
		} 
		
	} // external_url()


	/**
	 *  Output button text for external products
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function button_text( $post_id ) {

		if ( ! self::$general_options[ 'button_text' ]  ) {

			// $custom_attributes = ( self::$required_general_options[ 'button_text' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Button text is required.', 'wcvendors-pro' ) ) : array();  
		
			// Button text
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_button_text', array( 
				'post_id'		=> $post_id, 
				'id' 			=> '_button_text', 
				'label' 		=> __( 'Button text', 'wcvendors-pro' ), 
				'placeholder' 	=> _x('', 'placeholder', 'wcvendors-pro'), 
				'desc_tip' 		=> 'true', 
				'description' 	=> __( 'This text will be shown on the button linking to the external product.', 'wcvendors-pro' ), 
				// 'custom_attributes' => $custom_attributes   
				) )
			);
		} 

	} // button_text()

	/**
	 *  Output tax information 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function tax( $post_id ) {

		if ( ! self::$general_options[ 'tax' ]  ) {

			if ( wc_tax_enabled() ) {

				// $custom_attributes = ( self::$required_general_options[ 'tax' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Tax is required.', 'wcvendors-pro' ) ) : array();  

				// Tax
				WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_product_tax_status', array( 
					'post_id'			=> $post_id, 
					'id' 				=> '_tax_status', 
					'label' 			=> __( 'Tax Status', 'wcvendors-pro' ), 
					'wrapper_start' 	=> '<div class="all-100">',
					'wrapper_end' 		=> '</div>', 
					// 'custom_attributes' => $custom_attributes,    
					'options' 			=> array(
						'taxable' 		=> __( 'Taxable', 'wcvendors-pro' ),
						'shipping' 		=> __( 'Shipping only', 'wcvendors-pro' ),
						'none' 			=> _x( 'None', 'Tax status', 'wcvendors-pro' )
						) 
					) )
				);

				$tax_classes         = WC_Tax::get_tax_classes();
				$classes_options     = array();
				$classes_options[''] = __( 'Standard', 'wcvendors-pro' );

				if ( $tax_classes ) {

					foreach ( $tax_classes as $class ) {
						$classes_options[ sanitize_title( $class ) ] = esc_html( $class );
					}
				}

				WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_product_tax_class', array( 
					'post_id'			=> $post_id, 
					'id' 				=> '_tax_class', 
					'label' 			=> __( 'Tax Class', 'wcvendors-pro' ), 
					'options' 			=> $classes_options , 
					'wrapper_start' 	=> '<div class="all-100">', 
					'wrapper_end' 		=> '</div>' 
					) )
				);

				do_action( 'wcv_product_options_tax' );

			} 
		} 

	} // tax()


	/**
	 *  Output enable reviews 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function reviews( $post_id ) {

		$product = 	( is_numeric( $post_id ) ) ? wc_get_product( $post_id ) : null;
		$comment_status = ( $product != null ) ? esc_attr( $product->comment_status ) : 0; 

		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_reviews', array( 
					'post_id'			=> $post_id, 
					'id' 				=> 'comment_status', 
					'label' 			=> __( 'Enable reviews', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox' 
					) )
				);

		do_action( 'wcv_product_options_reviews' );

	} // reviews()


	/**
	 *  Output manage stock 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function manage_stock( $post_id ) {

		if ( ! self::$inventory_options[ 'manage_inventory' ]  ) {

			// $custom_attributes = ( self::$required_inventory_options[ 'manage_inventory' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Manage stock is required.', 'wcvendors-pro' ) ) : array();  

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_manage_stock', array( 
				'post_id'			=> $post_id, 
				'id' 				=> '_manage_stock', 
				'wrapper_class' 	=> 'show_if_simple show_if_variable', 
				'label' 			=> __( 'Manage stock?', 'wcvendors-pro' ), 
				'description' 		=> __( 'Enable stock management at product level', 'wcvendors-pro' ), 
				'type' 				=> 'checkbox', 
				// 'custom_attributes' => $custom_attributes   
				) )
			);

		}  

	} // manage_stock()


	/**
	 *  Output stock qty
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function stock_qty( $post_id ) {

		if ( ! self::$inventory_options[ 'manage_inventory' ] && ! self::$inventory_options[ 'stock_qty' ]  ) {

			// $custom_attributes = ( self::$required_inventory_options[ 'stock_qty' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Stock QTY is required.', 'wcvendors-pro' ) ) : array();  

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_stock_qty', array(
				'post_id'			=> $post_id, 
				'id'                => '_stock',
				'label'             => __( 'Stock Qty', 'wcvendors-pro' ),
				'wrapper_start'		=> '<div class="all-100">',
				'wrapper_end' 		=> '</div>', 
				'desc_tip'          => true,
				'description'       => __( 'Stock quantity.', 'wcvendors-pro' ),
				'type'              => 'number',
				'data_type'         => 'stock', 
				// 'custom_attributes' => $custom_attributes   
				) )
			);
		} 

	} // stock_qty() 

	/**
	 *  Output backorder select
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function backorders( $post_id ) {

		if ( ! self::$inventory_options[ 'manage_inventory' ] && ! self::$inventory_options[ 'backorders' ]  ) {

			// $custom_attributes = ( self::$required_inventory_options[ 'backorders' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Allow backorders is required.', 'wcvendors-pro' ) ) : array();  

			// Backorders?
			WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_product_backorders', array( 
				'post_id'			=> $post_id, 
				'id' 				=> '_backorders', 'label' => __( 'Allow Backorders?', 'wcvendors-pro' ), 
				'wrapper_start' 	=> '<div class="all-100">',
				'wrapper_end' 		=> '</div>', 
				'desc_tip' 			=> true, 
				'description' 		=> __( 'If managing stock, this controls whether or not backorders are allowed. If enabled, stock quantity can go below 0.', 'wcvendors-pro' ), 
				// 'custom_attributes' => $custom_attributes,   
				'options' 			=> array(
					'no'     		=> __( 'Do not allow', 'wcvendors-pro' ),
					'notify' 		=> __( 'Allow, but notify customer', 'wcvendors-pro' ),
					'yes'    		=> __( 'Allow', 'wcvendors-pro' )
					),  
				) )
			);
		} 
	} 

	/**
	 *  Output stock status
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function stock_status( $post_id ) {

		if ( ! self::$inventory_options[ 'manage_inventory' ] && ! self::$inventory_options[ 'stock_status' ]  ) {

			// $custom_attributes = ( self::$required_inventory_options[ 'stock_status' ] ) ? array(  'required' => 'required', 'data-rules' => 'required', 'data-error' => __( 'Stock status is required.', 'wcvendors-pro' ) ) : array();  

			WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_product_stock_status', array( 
				'post_id'			=> $post_id, 
				'id' 				=> '_stock_status', 
				'class'				=> 'select',
				'wrapper_class' 	=> 'hide_if_variable', 
				'label' 			=> __( 'Stock status', 'wcvendors-pro' ), 
				'wrapper_start' 	=> '<div class="all-100 hide_if_variable">',
				'wrapper_end' 		=> '</div>', 
				'desc_tip' 			=> true, 
				'description'		=> __( 'Controls whether or not the product is listed as "in stock" or "out of stock" on the frontend.', 'wcvendors-pro' ), 
				// 'custom_attributes' => $custom_attributes,   
				'options' 			=> array(
					'instock' 		=> __( 'In stock', 'wcvendors-pro' ),
					'outofstock' 	=> __( 'Out of stock', 'wcvendors-pro' )
					)
				) )
			);
		} 
	}

	/**
	 *  Output sold individually checkbox
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function sold_individually( $post_id ) {

		if ( ! self::$inventory_options[ 'manage_inventory' ] && ! self::$inventory_options[ 'sold_individually' ]  ) {

			// $custom_attributes = ( self::$required_inventory_options[ 'sold_individually' ] ) ? array(  'data-rules' => 'required', 'data-label' => __( 'Sold individually is required.', 'wcvendors-pro' ) ) : array();  

			// Individual product
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_sold_individually', array( 
				'post_id'			=> $post_id, 
				'id' 				=> '_sold_individually', 
				'wrapper_class' 	=> 'show_if_simple show_if_variable', 
				'label' 			=> __( 'Sold Individually', 'wcvendors-pro' ), 
				'desc_tip'			=> true, 
				'description' 		=> __( 'Enable this to only allow one of this item to be bought in a single order', 'wcvendors-pro' ),
				'type' 				=> 'checkbox', 
				// 'custom_attributes' => $custom_attributes,
				) )
			);
		} 
	} 


	/**
	 *  Output weight input
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function weight( $post_id ) {

		if ( ! self::$shipping_options[ 'weight' ]  ) {
 
			if ( wc_product_weight_enabled() ) {

				// $custom_attributes = ( self::$required_shipping_options[ 'weight' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Weight is required.', 'wcvendors-pro' ) ) : array();  

				WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_weight', array( 
					'post_id'			=> $post_id, 
					'id' 					=> '_weight', 
					'label' 				=> __( 'Weight', 'wcvendors-pro' ) . ' (' . get_option( 'woocommerce_weight_unit' ) . ')', 
					'placeholder' 			=> wc_format_localized_decimal( 0 ), 
					'desc_tip' 				=> 'true', 
					'description' 			=> __( 'Weight in decimal form', 'wcvendors-pro' ), 
					'type' 					=> 'text', 
					'data_type' 			=> 'decimal', 
					// 'custom_attributes' 	=> $custom_attributes, 
					) )
				);
			}
		} 

	}


	/**
	 *  Output dimensions inputs
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function dimensions( $post_id ) {

		if ( ! self::$shipping_options[ 'dimensions' ]  ) {

			if ( wc_product_dimensions_enabled() ) {

				// $length_custom_attributes = ( self::$required_shipping_options[ 'dimensions' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Length is required.', 'wcvendors-pro' ) ) : array();  

				WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_length', array( 
					'post_id'				=> $post_id, 
					'id' 					=> '_length', 
					'label' 				=> __( 'Dimensions', 'wcvendors-pro' ) . ' (' . get_option( 'woocommerce_dimension_unit' ) . ')', 
					'placeholder' 			=> __( 'Length', 'wcvendors-pro' ), 
					'type' 					=> 'text', 
					'data_type'				=> 'decimal', 
					'wrapper_start' 		=> '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-33">',
					'wrapper_end' 			=> '</div>',  
					// 'custom_attributes' 	=> $length_custom_attributes, 
					) )
				);

				// $width_custom_attributes = ( self::$required_shipping_options[ 'dimensions' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Width is required.', 'wcvendors-pro' ) ) : array();  

				WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_width', array( 
					'post_id'				=> $post_id, 
					'id' 					=> '_width', 
					'placeholder' 			=> __( 'Width', 'wcvendors-pro' ), 
					'type' 					=> 'text', 
					'data_type'				=> 'decimal', 
					'wrapper_start' 		=> '<div class="all-33">',
					'wrapper_end' 			=> '</div>',  
					// 'custom_attributes' 	=> $width_custom_attributes, 
					) )
				);

				// $height_custom_attributes = ( self::$required_shipping_options[ 'dimensions' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Height is required.', 'wcvendors-pro' ) ) : array();  

				WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_height', array( 
					'post_id'				=> $post_id, 
					'id' 					=> '_height', 
					'placeholder' 			=> __( 'Height', 'wcvendors-pro' ), 
					'type' 					=> 'text', 
					'data_type'				=> 'decimal', 
					'wrapper_start' 		=> '<div class="all-33">',
					'wrapper_end' 			=> '</div></div>',  
					'desc_tip'				=> true, 
					'description' 			=> __( 'Dimensions in decimal form.', 'wcvendors-pro' ),
					// 'custom_attributes' 	=> $height_custom_attributes, 
					) ) 
				);
			}
		} 

	} //dimensions() 

	/**
	 *  Output shipping class details
	 * 
	 * @since    1.0.0
	 * @version  1.4.0 
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function shipping_class( $post_id ) {

		if ( ! self::$shipping_options[ 'shipping_class' ]  ) {

			// $custom_attributes = ( self::$required_shipping_options[ 'shipping_class' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Shipping class is required.', 'wcvendors-pro' ) ) : array();  

			$classes = ($post_id) ? get_the_terms( $post_id, 'product_shipping_class' ) : '';

			if ( $classes && ! is_wp_error( $classes ) ) {
				$current_shipping_class = current( $classes )->term_id;
			} else {
				$current_shipping_class = '';
			}

			WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_product_shipping_classes', array( 
				'post_id'				=> $post_id, 
				'class'					=> 'select', 
				'id' 					=> 'product_shipping_class', 
				'label' 				=> __( 'Shipping class', 'wcvendors-pro' ), 
				'show_option_none' 		=> __( 'No shipping class', 'wcvendors-pro' ),
				'value' 				=> $current_shipping_class, 
				'taxonomy'				=> 'product_shipping_class', 
				'taxonomy_field'		=> 'term_id', 
				'desc_tip' 				=> true, 
				'description' 			=> __( 'Shipping classes are used by certain shipping methods to group similar products.', 'wcvendors-pro' ), 
				// 'custom_attributes' 	=> $custom_attributes, 
				'taxonomy_args'			=> array( 
					'hide_empty'		=> 0, 
					), 	
				) ) 
			);

		} 

	}  //shipping_class()

	/**
	 *  Output upsell select2
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function up_sells( $post_id ) {

		if ( ! self::$upsell_options[ 'up_sells' ]  ) {


			$product_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post_id, '_upsell_ids', true ) ) );
			$upsell_ids    = array();
			foreach ( $product_ids as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( is_object( $product ) ) {
					$upsell_ids[ $product_id ] = wp_kses_post( html_entity_decode( $product->get_formatted_name() ) );
				}
			}

			$custom_attributes = //( self::$required_upsell_options[ 'up_sells' ] ) ? 
				// array(  
				// 	'data-rules' 		=> 'required',
				// 	'data-error' 		=> __( 'Upsells is required.', 'wcvendors-pro' ),
				// 	'data-placeholder'	=> __( 'Search for a product&hellip;', 'wcvendors-pro' ), 
				// 	'data-action'		=> 'wcv_json_search_products', 
				// 	'data-multiple' 	=> 'true', 
				// 	'data-selected'		=> esc_attr( json_encode( $upsell_ids ) ) ) : 
				array(
						'data-placeholder' 	=> __( 'Search for a product&hellip;', 'wcvendors-pro' ), 
						'data-action'		=> 'wcv_json_search_products', 
						'data-multiple' 	=> 'true', 
						'data-selected'		=> esc_attr( json_encode( $upsell_ids ) ) 
					);  


			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_upsells', array( 
					'id' 					=> 'upsell_ids', 
					'label' 				=> __( 'Up-Sells', 'wcvendors-pro' ), 
					'value' 				=> implode( ',', array_keys( $upsell_ids ) ), 
					'style'					=> 'width: 100%;', 
					'class'					=> 'wc-product-search', 
					'type'					=> 'hidden', 
					'desc_tip' 				=> false, // tool tip messes with styling of drop down 
					'description' 			=> __( 'Up-sells are products which you recommend instead of the currently viewed product, for example, products that are more profitable or better quality or more expensive.', 'wcvendors-pro' ), 
					'custom_attributes' 	=> $custom_attributes, 
				) )
			);
		} 

	} //up_sells()


	/**
	 *  Output crosssell select2
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function crosssells( $post_id ) {

		if ( ! self::$upsell_options[ 'crosssells' ]  ) {

			$product_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post_id, '_crosssell_ids', true ) ) );
			$crosssell_ids    = array();

			foreach ( $product_ids as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( is_object( $product ) ) {
					$crosssell_ids[ $product_id ] = wp_kses_post( html_entity_decode( $product->get_formatted_name() ) );
				}
			}

			$custom_attributes = // ( self::$required_upsell_options[ 'crosssells' ] ) ? 
				// array(  
				// 	'data-rules' 		=> 'required',
				// 	'data-error' 		=> __( 'Crossels is required.', 'wcvendors-pro' ),
				// 	'data-placeholder'	=> __( 'Search for a product&hellip;', 'wcvendors-pro' ), 
				// 	'data-action'		=> 'wcv_json_search_products', 
				// 	'data-multiple' 	=> 'true', 
				// 	'data-selected'		=> esc_attr( json_encode( $crosssell_ids ) ) ) : 
				array(
					'data-placeholder' 	=> __( 'Search for a product&hellip;', 'wcvendors-pro' ), 
					'data-action'		=> 'wcv_json_search_products', 
					'data-multiple' 	=> 'true', 
					'data-selected'		=> esc_attr( json_encode( $crosssell_ids ) ) 
				);
	

			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_crosssells', array( 
					'id' 					=> 'crosssell_ids', 
					'label' 				=> __( 'Cross-Sells', 'wcvendors-pro' ), 
					'value' 				=> implode( ',', array_keys( $crosssell_ids ) ), 
					'style'					=> 'width: 100%;', 
					'class'					=> 'wc-product-search', 
					'type'					=> 'hidden', 
					'desc_tip' 				=> false, // tool tip messes with styling of drop down 
					'description' 			=> __( 'Cross-sells are products which you promote in the cart, based on the current product.', 'wcvendors-pro' ), 
					// 'custom_attributes' 	=> $custom_attributes, 
				) )
			);
		}

	} // crosssells()

	/**
	 *  Output grouped_products select2
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function grouped_products( $post_id, $product = false ) {

		if ( ! self::$upsell_options[ 'grouped_products' ]  ) {

			$post 				= get_post( $post_id ); 
			$post_parents 		= array();
			$post_parents[''] 	= __( 'Choose a grouped product&hellip;', 'wcvendors-pro' );

			if ( $grouped_term = get_term_by( 'slug', 'grouped', 'product_type' ) ) {

				$posts_in = array_unique( (array) get_objects_in_term( $grouped_term->term_id, 'product_type' ) );

				if ( sizeof( $posts_in ) > 0 ) {

					$args = array(
						'post_type'        => 'product',
						'post_status'      => 'any',
						'numberposts'      => -1,
						'orderby'          => 'title',
						'order'            => 'asc',
						'post_parent'      => 0,
						'suppress_filters' => 0,
						'include'          => $posts_in,
					);

					$grouped_products_posts = get_posts( $args );

					if ( $grouped_products_posts ) {

						foreach ( $grouped_products_posts as $grouped_product ) {

							if ( $grouped_product->ID == $post_id ) {
								continue;
							}

							$post_parents[ $grouped_product->ID ] = $grouped_product->post_title;
						}
					}
				}

			}

			$post_parent_value = isset( $post ) ? absint( $post->post_parent ) : 0; 

			WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_product_grouping', array(
				'post_id'				=> $post_id, 
				'id' 					=> 'parent_id', 
				'label' 				=> __( 'Grouping', 'wcvendors-pro' ), 
				'value' 				=> $post_parent_value, 
				'options' 				=> $post_parents, 
				'desc_tip' 				=> true, 
				'description' 			=> __( 'Set this option to make this product part of a grouped product.', 'wcvendors-pro' ), 
				) )
			);

			// woocommerce_wp_hidden_input( array( 'id' => 'previous_parent_id', 'value' => $post_parent_value ) );

			WCVendors_Pro_Form_Helper::input( array( 
					'id' 				=> 'previous_parent_id', 
					'type'				=> 'hidden', 
					'value' 			=> $post_parent_value
				)
			); 

		} 

	} // grouped_product()

	/**
	 *  Output Product meta tab information 
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function product_meta_tabs( ) {

		$wcv_product_panel 			= (array) WC_Vendors::$pv_options->get_option( 'hide_product_panel' );
		$shipping_disabled			= WCVendors_Pro::get_option( 'shipping_management_cap' ); 
		$inventory_options			= WC_Vendors::$pv_options->get_option( 'hide_product_inventory' ); 
		$upsell_options 			= WC_Vendors::$pv_options->get_option( 'hide_product_upsells' ); 
		$css_classes				= apply_filters( 'wcv_product_meta_tabs_class', array( 'tabs-nav' ) ); 

		$product_meta_tabs = apply_filters( 'wcv_product_meta_tabs', array(
			'general' 			=> array(
				'label'  => __( 'General', 'wcvendors-pro'), 
				'target' => 'general',
				'class'  => array( 'hide_if_grouped' ),
			), 
			'inventory' 		=> array( 
				'label'  => __( 'Inventory', 'wcvendors-pro'), 
				'target' => 'inventory',
				'class'  => array( 'show_if_simple', 'show_if_variable', 'show_if_grouped' ),
			), 
			'shipping' 			=> array( 
				'label'  => __( 'Shipping', 'wcvendors-pro'), 
				'target' => 'shipping',
				'class'  => array( 'hide_if_virtual', 'hide_if_grouped', 'hide_if_external' ),
			), 
			'linked_product'	=> array( 
				'label'  => __( 'Linked Products', 'wcvendors-pro'), 
				'target' => 'linked_product',
				'class'  => array(),
			), 
			'attributes'	=> array( 
				'label'  => __( 'Attributes', 'wcvendors-pro'), 
				'target' => 'attributes',
				'class'  => array(),
			), 
			'variations'	=> array( 
				'label'  => __( 'Variations', 'wcvendors-pro'), 
				'target' => 'variations',
				'class'  => array('show_if_variable'),
			), 
		)); 

		foreach ( $wcv_product_panel as $panel => $value ) {

			if ( array_key_exists( $panel, $product_meta_tabs ) ) { 
				if ( $value ) unset( $product_meta_tabs[ $panel ] ); 
			}

		}
		
		if ( $inventory_options[ 'manage_inventory' ] ) { unset( $product_meta_tabs[ 'inventory' ] ); }
		if ( $shipping_disabled ) { unset( $product_meta_tabs[ 'shipping' ] );  }
		if ( $upsell_options[ 'up_sells' ] && $upsell_options[ 'crosssells' ] ) { unset( $product_meta_tabs[ 'linked_product' ] ); }
		if ( self::$basic_options[ 'attributes' ] ) { unset( $product_meta_tabs[ 'attributes' ] ); }


		$css_class = implode(' ', $css_classes ); 

		include( apply_filters( 'wcvendors_pro_product_form_product_meta_tabs_path', 'partials/wcvendors-pro-product-meta-tabs.php' ) );

	} //product_meta_tabs 


	/**
	 *  Output national shipping fee field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_rates( $post_id ){ 

		global $wcvendors_pro; 

		$shipping_settings 		= get_option( 'woocommerce_wcv_pro_vendor_shipping_settings' ); 
		$store_shipping_type	= get_user_meta( get_current_user_id(), '_wcv_shipping_type', true ); 
		$shipping_type 			= ( $store_shipping_type != '' ) ? $store_shipping_type : $shipping_settings[ 'shipping_system' ]; 

		$shipping_details 		= get_post_meta( $post_id, '_wcv_shipping_details', true );


		if ( $wcvendors_pro->is_vendor_shipping_method_enabled() ) { 

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

				self::shipping_rate_table( $post_id ); 

			}

			self::handling_fee( $shipping_details );
			self::max_charge( $shipping_details );
			self::free_shipping_product( $shipping_details ); 

		}

	} //shipping_rates()


	/**
	 *  Output national shipping fee field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_national( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'national', $shipping_details ) ) ? $shipping_details[ 'national' ] : ''; 

		// Shipping Fee 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_shipping_fee_national', array(  
			'id' 				=> '_shipping_fee_national', 
			'label' 			=> __( 'National shipping fee', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'Change to override store defaults.', 'wcvendors-pro' ), 
			'desc_tip' 			=> 'true', 
			'description' 		=> __( 'The cost to ship this product within your country.', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $value, 
			'class' 			=> 'wcv-disable-national-input',
			'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">', 
			'wrapper_end' 	=>  '</div>', 
			'custom_attributes' => array( 
		 			'data-rules' => 'decimal', 
		 			'data-error' => __( 'This should be a number.', 'wcvendors-pro' )

		 		)
			)
		) );

		


	} // shipping_fee_national()

	/**
	 *  Output national shipping fee qty override field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_national_qty( $shipping_details ) {

		$qty_value = ( is_array( $shipping_details ) && array_key_exists( 'national_qty_override', $shipping_details ) ) ? $shipping_details[ 'national_qty_override' ] : 0; 


		// QTY Override 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_shipping_fee_national_qty', array( 
					'id' 				=> '_shipping_fee_national_qty', 
					'label' 			=> __( 'Charge once per product for national shipping, even if more than one is purchased.', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox', 
					'value'				=> $qty_value, 
					'class' 			=> 'wcv-disable-national-input',
					'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">', 
					'wrapper_end' 	=>  '</div>'
					) )
		);


	} // shipping_fee_national_qty() 

	/**
	 *  Output national shipping fee disable field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_national_disable( $shipping_details ) {

		$disabled = ( is_array( $shipping_details ) && array_key_exists( 'national_disable', $shipping_details ) ) ? $shipping_details[ 'national_disable' ] : 0; 

		// QTY Override 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_shipping_fee_national_disable', array( 
					'id' 				=> '_shipping_fee_national_disable', 
					'label' 			=> __( 'Disable national shipping for this product.', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox', 
					'value'				=> $disabled, 
					'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">', 
					'wrapper_end' 	=>  '</div>'
					) )
		);


	} // shipping_fee_national_disable() 

	/**
	 *  Output national shipping fee free shipping field
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_national_free( $shipping_details ) {

		$free = ( is_array( $shipping_details ) && array_key_exists( 'national_free', $shipping_details ) ) ? $shipping_details[ 'national_free' ] : 0; 

		// QTY Override 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_shipping_fee_national_free', array( 
					'id' 				=> '_shipping_fee_national_free', 
					'label' 			=> __( 'Free national shipping', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox',
					'class' 			=> 'wcv-disable-national-input',
					'value'				=> $free, 
					'wrapper_start' => '<div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 small-100">', 
					'wrapper_end' 	=>  '</div>'
					) )
		);


	} // shipping_fee_national_free() 

	/**
	 *  Output international shipping fee field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_international( $shipping_details ) {

		$value = ( is_array( $shipping_details ) && array_key_exists( 'international', $shipping_details ) ) ? $shipping_details[ 'international' ] : ''; 

		// Shipping international Fee 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_shipping_fee_international', array(  
			'id' 				=> '_shipping_fee_international', 
			'label' 			=> __( 'International shipping fee', 'wcvendors-pro' ), 
			'placeholder' 		=> __( 'Change to override store defaults.', 'wcvendors-pro' ), 
			'desc_tip' 			=> 'true', 
			'description' 		=> __( 'The cost to ship this product outside your country.', 'wcvendors-pro' ), 
			'type' 				=> 'text', 
			'value'				=> $value, 
			'class' 			=> 'wcv-disable-international-input',
			'wrapper_start' => '<div class="all-50 small-100">', 
			'wrapper_end' 	=>  '</div></div>', 
			'custom_attributes' => array( 
		 			'data-rules' => 'decimal', 
		 			'data-error' => __( 'This should be a number.', 'wcvendors-pro' )

		 		)
			)
		) );

	} // shipping_fee_international()


	/**
	 *  Output international shipping fee qty override field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_international_qty( $shipping_details ) {

		$qty_value = ( is_array( $shipping_details ) && array_key_exists( 'international_qty_override', $shipping_details ) ) ? $shipping_details[ 'international_qty_override' ] : 0; 

		// QTY Override 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_shipping_fee_international_qty', array( 
					'id' 				=> '_shipping_fee_international_qty', 
					'label' 			=> __( 'Charge once per product for international shipping, even if more than one is purchased.', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox', 
					'value'				=> $qty_value, 
					'class' 			=> 'wcv-disable-international-input',
					'wrapper_start' => '<div class="all-50 small-100">', 
					'wrapper_end' 	=>  '</div></div>'
					) )
				);


	} // shipping_fee_international_qty() 

	/**
	 *  Output international shipping fee qty override field 
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_international_disable( $shipping_details ) {

		$disabled = ( is_array( $shipping_details ) && array_key_exists( 'international_disable', $shipping_details ) ) ? $shipping_details[ 'international_disable' ] : 0; 

		// QTY Override 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_shipping_fee_international_disable', array( 
					'id' 				=> '_shipping_fee_international_disable', 
					'label' 			=> __( 'Disable international shipping for this product.', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox', 
					'value'				=> $disabled, 
					'wrapper_start' => '<div class="all-50 small-100">', 
					'wrapper_end' 	=>  '</div></div>'
					) )
				);


	} // shipping_fee_international_qty() 


	/**
	 *  Output international shipping fee free shipping field
	 * 
	 * @since    1.0.0
	 */
	public static function shipping_fee_international_free( $shipping_details ) {

		$free = ( is_array( $shipping_details ) && array_key_exists( 'international_free', $shipping_details ) ) ? $shipping_details[ 'international_free' ] : 0; 

		// QTY Override 
		WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_shipping_fee_international_free', array( 
					'id' 				=> '_shipping_fee_international_free', 
					'label' 			=> __( 'Free international shipping', 'wcvendors-pro' ), 
					'type' 				=> 'checkbox', 
					'value'				=> $free, 
					'class' 			=> 'wcv-disable-international-input',
					'wrapper_start' => '<div class="all-50 small-100">', 
					'wrapper_end' 	=>  '</div></div>'
					) )
				);


	} // shipping_fee_international_qty() 


	/**
	 *  Output product handling fee field 
	 * 
	 * @since    1.0.0
	 */
	public static function handling_fee( $shipping_details ) {

		if ( ! self::$shipping_options[ 'handling_fee' ]  ) {

			$custom_attributes = ( self::$required_shipping_options[ 'handling_fee' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Product handling fee is required.', 'wcvendors-pro' ) ) : array();  

			$value = ( is_array( $shipping_details ) && array_key_exists( 'handling_fee', $shipping_details ) ) ? $shipping_details[ 'handling_fee' ] : ''; 

			// Product handling Fee 
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_handling_fee', array(  
				'id' 				=> '_handling_fee', 
				'label' 			=> __( 'Product handling fee', 'wcvendors-pro' ), 
				'placeholder' 		=> __( '0', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'The product handling fee. Amount (5.00) or Percentage (5%).', 'wcvendors-pro' ), 
				'type' 				=> 'text', 
				'value'				=> $value, 
				'custom_attributes'	=> $custom_attributes, 
				)
			) );

		} 

	} // product_handling_fee()

	/**
	 *  Output product max shipping charge field 
	 * 
	 * @since    1.0.0
	 */
	public static function max_charge( $shipping_details ) {

		if ( ! self::$shipping_options[ 'max_charge' ]  ) {

			$custom_attributes = ( self::$required_shipping_options[ 'max_charge' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Maximum shipping charge is required.', 'wcvendors-pro' ) ) : array();  

			$value = ( is_array( $shipping_details ) && array_key_exists( 'max_charge_product', $shipping_details ) ) ? $shipping_details[ 'max_charge_product' ] : ''; 

			// Product handling Fee 
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_max_charge', array(  
				'id' 				=> '_max_charge_product', 
				'label' 			=> __( 'Maximum shipping charge', 'wcvendors-pro' ), 
				'placeholder' 		=> __( '0', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'The maximum shipping charged per product no matter the quantity.', 'wcvendors-pro' ), 
				'type' 				=> 'text', 
				'value'				=> $value, 
				'custom_attributes'	=> $custom_attributes, 
				)
			) );

		} 

	} // max_charge()


	/**
	 *  Output product max shipping charge field 
	 * 
	 * @since    1.0.0
	 */
	public static function free_shipping_product( $shipping_details ) {

		if ( ! self::$shipping_options[ 'free_shipping_product' ]  ) {

			$custom_attributes = ( self::$required_shipping_options[ 'free_shipping_product' ] ) ? array(  'data-rules' => 'required', 'data-error' => __( 'Free shipping product is required.', 'wcvendors-pro' ) ) : array();  

			$value = ( is_array( $shipping_details ) && array_key_exists( 'free_shipping_product', $shipping_details ) ) ? $shipping_details[ 'free_shipping_product' ] : ''; 

			// Product handling Fee 
			WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_product_free_shipping_product', array(  
				'id' 				=> '_free_shipping_product', 
				'label' 			=> __( 'Free Shipping Product', 'wcvendors-pro' ), 
				'placeholder' 		=> __( '0', 'wcvendors-pro' ), 
				'desc_tip' 			=> 'true', 
				'description' 		=> __( 'Free shipping if the spend per product is over this amount. This will override the max shipping charge above.', 'wcvendors-pro' ), 
				'type' 				=> 'text', 
				'value'				=> $value, 
				'custom_attributes'	=> $custom_attributes, 
				)
			) );
		} 

	} // max_charge()


	/**
	 *  Output shipping rate table
	 * 
	 * @since    1.0.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function shipping_rate_table( $post_id ) {

		$helper_text = apply_filters( 'wcv_store_shipping_rate_table_msg', __( 'Countries must use the international standard for two letter country codes. eg. AU for Australia.', 'wcvendors-pro' ) );

		$shipping_rates = get_post_meta( $post_id, '_wcv_shipping_rates', true ); 

		include_once( apply_filters( 'wcvendors_pro_product_form_shipping_rate_table_path', 'partials/wcvendors-pro-shipping-table.php' ) );

	} // download_files()


	/**
	 *  Output product variations 
	 * 
	 * @since    1.2.0
	 * @param 	 int 	$post_id  post_id for this meta if any 
	 */
	public static function product_variations( $post_id ){ 

		global $wpdb;

		// Get attributes
		$attributes = maybe_unserialize( get_post_meta( $post_id, '_product_attributes', true ) );

		$basic_options 			= (array) WC_Vendors::$pv_options->get_option( 'hide_product_basic' );
		$media_options 			= (array) WC_Vendors::$pv_options->get_option( 'hide_product_media' );
		$general_options 		= (array) WC_Vendors::$pv_options->get_option( 'hide_product_general' );
		$inventory_options 		= (array) WC_Vendors::$pv_options->get_option( 'hide_product_inventory' );
		$shipping_options 		= (array) WC_Vendors::$pv_options->get_option( 'hide_product_shipping' );
		$upsell_options 		= (array) WC_Vendors::$pv_options->get_option( 'hide_product_upsells' );

		// See if any are set
		$variation_attribute_found = false;

		if ( $attributes ) {
			foreach ( $attributes as $attribute ) {
				if ( ! empty( $attribute['is_variation'] ) ) {
					$variation_attribute_found = true;
					break;
				}
			}
		}

		$variations_count       = absint( $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM $wpdb->posts WHERE post_parent = %d AND post_type = 'product_variation' AND post_status IN ('publish', 'private')", $post_id ) ) );
		
		include_once( apply_filters( 'wcvendors_pro_product_form_product_variations_path', 'partials/wcvendors-pro-product-variations.php' ) ); 
		
	} // product_variations() 


}