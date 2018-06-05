<?php 
/**
 * The WCVendors Pro Rating Class 
 *
 * This class handles the Vendor ratings system 
 *
 *
 * @since      1.0.0
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/includes
 * @author     Jamie Madden <support@wcvendors.com>
 */

class WCVendors_Pro_Ratings_Controller {

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
	 * Is the ratings table name
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $table_name  name of the ratings table 
	 */
	public static $table_name =  "wcv_feedback"; 

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wcvendors_pro     The name of the plugin.
	 * @param      string    $version    		The version of this plugin.
	 * @param      bool 	 $debug    			If the plugin is currently in debug mode 
	 */
	public function __construct( $wcvendors_pro, $version, $debug )
	{
		global $wpdb; 

		$this->wcvendors_pro 	= $wcvendors_pro;
		$this->version 			= $version;
		$this->debug 			= $debug; 
		$this->base_dir			= plugin_dir_path( dirname(__FILE__) ); 
		$this->base_url			= plugin_dir_url( __FILE__ ); 

	}

	/**
	 * Load admin javascript  
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() { 

	} // enqueue_scripts()

	/**
	 * Add the styles
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() { 

		$screen = get_current_screen();

		if ( $screen->id == 'woocommerce_page_wcv_pro_vendor_feedback' ) { 
			//font awesome 
			wp_enqueue_style( 'font-awesome', 	$this->base_url . 'assets/lib/font-awesome-4.6.3/css/font-awesome.min.css', array(), '4.6.3', 'all' );
		}

	} // enqueue_styles()


	/**
	 *  Process the ratings submission  
	 *
	 * @since    1.0.0
	 */
	public function process_form_submission()
	{

		// Is the form submitted
		if ( ! isset( $_POST['wcv-order_id']) || ! isset( $_POST['_wcv-submit_feedback'] ) || ! wp_verify_nonce( $_POST['_wcv-submit_feedback'], 'wcv-submit_feedback' ) )  {
			return;
		} 

		global $wpdb;

		// Iterate over each line item to leave feedback 
		// TO-DO : find better way to validate data before entering during each loop
		//  Only allow posting once. 
		$err = true; 

		foreach ( $_POST[ 'wcv-feedback' ] as $feedback ) {

			if ( empty( $feedback['star-rating'] ) && $feedback[ 'rating_title' ] == '' && $feedback[ 'comments' ] == '' ) { 
				continue; 
			} 

			if ( empty( $feedback['star-rating'] ) ) {
				wc_add_notice( __( 'Please select a star rating.', 'wcvendors-pro' ) );
				return;
			}

			$update = array_key_exists( 'feedback_id', $feedback ) ? true : false; 

			if ( $update ) { 
				$res = $wpdb->update(
				$wpdb->prefix. self::$table_name,
				array(
					'rating'      		=> (int) $feedback[ 'star-rating' ],
					'order_id'    		=> (int) $_POST[ 'wcv-order_id' ],
					'vendor_id'   		=> (int) $feedback[ 'vendor_id' ],
					'product_id'  		=> (int) $feedback[ 'product_id' ], 
					'customer_id' 		=> (int) $feedback[ 'customer_id' ], 
					'comments'    		=> stripslashes( $feedback['comments'] ),
					'rating_title'    	=> stripslashes( $feedback['rating_title'] ),
				),
				array('id' => $feedback['feedback_id']), 
				array(
					'%d',
					'%d',
					'%d',
					'%d',
					'%s', 
					'%s',
					'%s'
					)
				);
			} else { 
				$res = $wpdb->insert(
					$wpdb->prefix. self::$table_name,
					array(
						'rating'      		=> (int) $feedback[ 'star-rating' ],
						'order_id'    		=> (int) $_POST[ 'wcv-order_id' ],
						'vendor_id'   		=> (int) $feedback[ 'vendor_id' ],
						'product_id'  		=> (int) $feedback[ 'product_id' ], 
						'customer_id' 		=> (int) $feedback[ 'customer_id' ], 
						'comments'    		=> stripslashes( $feedback[ 'comments' ] ),
						'rating_title'    	=> stripslashes( $feedback[ 'rating_title' ] ),
					), 
					array(
						'%d',
						'%d',
						'%d',
						'%d',
						'%s', 
						'%s',
						'%s'
					)
				);

			}

		}		

		if ( $err ) { 
			$notice = __( 'Your feedback has been saved.', 'wcvendors-pro' );
		} else { 
			$notice = __( 'There was an error posting your feedback.', 'wcvendors-pro' );
		}

		wc_add_notice( $notice, 'success' );

		$orders_endpoint = get_option( 'woocommerce_myaccount_orders_endpoint' ); 
		wp_safe_redirect( apply_filters( 'wcv_ratings_redirect', get_permalink( wc_get_page_id( 'myaccount' ) )  . $orders_endpoint ) );
		
  		exit; 

	} // process_form_submission() 

	/**
	 *  Display the feedback form
	 *
	 * @since    1.0.0
	 */
	public function feedback_form()
	{
		if ( ! class_exists( 'WC_Vendors' ) ) return;

		if ( ! isset( $_GET[ 'wcv_order_id' ] ) || ! is_user_logged_in() || ! isset( $_GET[ '_wpnonce' ] ) || ! wp_verify_nonce( $_GET[ '_wpnonce' ], 'wcv-leave_feedback' ) ) {
			echo sprintf( apply_filters( 'wcv_feedback_page_error_msg', __( '<p>This page should not be accessed directly. Please return to the <a href="%s">my account page</a> and select an order to leave feedback. </p>', 'wcvendors-pro' ) ), get_permalink( wc_get_page_id( 'myaccount' ) ) ); 
			return;
		}

		//  Template variables 
		$order_id 		= $_GET[ 'wcv_order_id' ]; 		
		$feedback 		= $this->get_order_feedback( $order_id ); 
		$order 			= new WC_Order( $order_id ); 
		$products		= $order->get_items(); 

		ob_start();
		wc_get_template( 'feedback-form.php', array(
				'order_id' 		=> $order_id, 
				'feedback'		=> $feedback, 
				'order'			=> $order, 
				'order_date' 	=> ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->order_date : $order->get_date_created(), 
				'products'	=> $products, 
			), 
			'wc-vendors/front/ratings/', $this->base_dir . 'templates/front/ratings/' );
		return ob_get_clean();

	} // feedback_form() 

	/**
	 * Feedback link action that hooks into the my account order page 
	 *
	 * @since    1.0.0
	 * @param    string    $actions  actions array 
	 * @param    object    $order the order object 
	 */
	public function feedback_link_action( $actions, $order ) { 

		$feedback_status = WCVendors_Pro::get_option( 'feedback_order_status' ); 

		if ( 'processing' == $feedback_status ) {  
			if ( ! $order || ! $order->has_status( 'processing' ) ) { 
				if ( ! $order || ! $order->has_status( 'completed' ) ) { 
					return $actions; 
				}
			} 
		} else { 

			if ( ! $order || ! $order->has_status( 'completed' ) ) { 
				return $actions; 
			} 
		}

		$order_id 			= ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->id : $order->get_id();  	
		$existing_feedback 	= $this->get_order_feedback( $order_id ); 
		$feedback_text 		= ( ! empty( $existing_feedback ) ) ? __( 'Revise Feedback',  'wcvendors-pro' ) : $feedback_text = __( 'Leave Feedback',  'wcvendors-pro' );
		$feedback_form_page = WCVendors_Pro::get_option( 'feedback_page_id' );  

		$actions[ 'leave_feedback' ] = array(
								'url'  => wp_nonce_url( add_query_arg( 'wcv_order_id', $order_id, get_permalink( $feedback_form_page ) ) , 'wcv-leave_feedback' ),
								'name' => apply_filters( 'wcv_feedback_btn_text',  $feedback_text )
							);
		return $actions; 

	} // feedback_link_action()

	/**
	 * Generate the ratings URL 
	 *
	 * @since    1.0.0
	 * @param    int    $vendor_id  the vendor id to generate
	 * @param    bool   $link  		output a link, otherwise just the rating
	 */
	public static function ratings_link( $vendor_id, $link = true, $link_text = '' ) { 

		$feedback_form_page =  		WCVendors_Pro::get_option( 'feedback_page_id' );  
		$feedback_system = 			WCVendors_Pro::get_option( 'feedback_system' ); 

		$url = apply_filters( 'wcv_ratings_link_url', WCVendors_Pro_Vendor_Controller::get_vendor_store_url( $vendor_id ) . 'ratings/' ); 
			
		if ( $feedback_form_page ) { 
			$ratings_count 		= 	self::get_ratings_count( $vendor_id ); 
			$ratings_average 	= 	self::get_ratings_average( $vendor_id ); 
			include( 'partials/ratings/public/wcvendors-pro-ratings-link.php'); 
		} 
	}

	/**
	 * Get the feedback for an order
	 *
	 * @since    1.0.0
	 * @param    int    $order_id  the order id to get 
	 */
	public function get_order_feedback( $order_id ) { 

		global $wpdb; 

		$order_id = (int) $order_id; 

		$table_name = $wpdb->prefix. self::$table_name; 
	
		$feedback = $wpdb->get_results( $wpdb->prepare(
		"
		SELECT * FROM $table_name
		WHERE order_id = %d
		",
		$order_id
		) );

		return $feedback; 

	} // get_order_feedback()

	/**
	 * Get the vendor feedback 
	 *
	 * @since    1.0.0
	 * @param    int    $vendor_id  the vendor id to get feedback for  
	 */
	public static function get_vendor_feedback( $vendor_id ) { 

		global $wpdb; 

		$vendor_id = (int) $vendor_id; 
		
		$table_name = $wpdb->prefix. self::$table_name; 

		$sort_order = WC_Vendors::$pv_options->get_option( 'feedback_sort_order' ); 

		$feedback = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT * FROM $table_name
			WHERE vendor_id = %d
			ORDER BY postdate $sort_order
			", 
			$vendor_id
		 ) ); 

		return $feedback; 

	} // get_vendor_feedback()


	/**
	 * Get the product feedback 
	 *
	 * @since    1.0.0
	 * @param    int    $vendor_id  the vendor id to get feedback for  
	 */
	public function get_product_feedback( $product_id ) { 

		global $wpdb; 
			
		$table_name = $wpdb->prefix. self::$table_name; 

		$sort_order = WC_Vendors::$pv_options->get_option( 'feedback_sort_order' ); 

		$feedback = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT * FROM $table_name
			WHERE product_id = %d
			ORDER BY postdate $sort_order
			", 
			$product_id
		) ); 

		return $feedback; 

	} // get_product_feedback()


	/**
	 * Get the feedback 
	 *
	 * @since    1.0.0
	 * @param    int    $feedback_id  the feedback id 
	 */
	public function get_feedback( $feedback_id ) { 

		global $wpdb; 

		$feedback_id = (int) $feedback_id; 

		$table_name = $wpdb->prefix. self::$table_name; 

		$sort_order = WC_Vendors::$pv_options->get_option( 'feedback_sort_order' ); 

		$feedback = $wpdb->get_row( $wpdb->prepare(
			"
			SELECT * FROM $table_name
			WHERE id = %d
			ORDER BY postdate $sort_order
			", 
			$feedback_id
		) ); 

		return $feedback; 

	} // get_feedback()

	/**
	 * Get the feedback 
	 *
	 * @since    1.0.0
	 * @param    int    $vendor_id  the vendor id to get the ratings average for 
	 */
	public static function get_ratings_average( $vendor_id ) { 

		global $wpdb; 

		$vendor_ratings = array(); 
		$average_rating = '';

		$vendor_id = (int) $vendor_id; 

		$count = self::get_ratings_count( $vendor_id ); 

		$table_name = $wpdb->prefix. self::$table_name; 

		$ratings = $wpdb->get_var( $wpdb->prepare(
				"
				SELECT SUM(rating) FROM $table_name
				WHERE vendor_id = %d
				",
				$vendor_id
		) ); 

		if ($count > 0) { 
			$average_rating = number_format( $ratings / $count, 1 );
		} else { 
			$average_rating = 0;
		}

		return $average_rating; 

	} // get_ratings_average()

	/**
	 * Get the feedback 
	 *
	 * @since    1.0.0
	 * @param    int    $vendor_id  the vendor id to get the ratings average for 
	 */
	public static function get_ratings_count( $vendor_id ) { 

		global $wpdb; 

		$table_name = $wpdb->prefix. self::$table_name; 

		$count = $wpdb->get_var( $wpdb->prepare(
				"
				SELECT count(rating) FROM $table_name
				WHERE vendor_id = %s
				", 
				$vendor_id
		) ); 

		return $count; 

	} // get_ratings_count() 

	/**
	 * The main admin page for the ratings 
	 *
	 * @since    1.0.0
	 */
	public function admin_page_setup() { 

		$hook = add_submenu_page(
			'woocommerce',
			__( 'Vendor Ratings', 'wcvendors-pro' ), __( 'Vendor Ratings', 'wcvendors-pro' ),
			'manage_woocommerce',
			'wcv_pro_vendor_feedback',
			array( $this, 'ratings_admin_page' )
		);

		include('class-wcvendors-pro-ratings-admin-table.php'); 
		
		add_filter( 'set-screen-option', array( $this, 'ratings_set_option') , 10, 3);

		add_action( "load-$hook", array( 'WCVendors_Pro_Ratings_Admin_Table' , 'add_options' ) );


	} // admin_page_setup()

	public function ratings_set_option( $status, $option, $value ) {
	  return $value;
	}

	/**
	 *  Load the admin ratings table in the wp-admin dashboard
	 *
	 * @since    1.0.0
	 */
	public function ratings_admin_page() { 

		include_once( 'class-wcvendors-pro-ratings-admin-table.php' ); 

		$ratings_table = new WCVendors_Pro_Ratings_Admin_Table( $this->wcvendors_pro, $this->version, $this->debug, self::$table_name ); 
		
		include( apply_filters( 'wcvendors_pro_ratings_admin_page_table_title_path', 'partials/ratings/admin/wcvendors-pro-ratings-table-title.php' ) ); 
		
		//  Display the edit form without the items table 
		if ( 'edit' === $ratings_table->current_action() ) { 
		
			$id 			= $_GET['wcv_vendor_rating_id']; 
			$feedback 		= $this->get_feedback( $id ); 
			$ratings_table->display_edit_form( $feedback ); 

		} else { 

			//  Process the single item actions 
			if (isset ($_POST['action']) && 'save' === $_POST['action']) { 

				$id 					= $_POST['rating_id']; 
				$comments 				= $_POST['rating_comments']; 
				$title 					= $_POST['rating_title']; 

				$feedback = array ( 
					'id' 				=> $id, 
					'rating_title' 		=> $title, 
					'rating_comments' 	=> $comments
				); 	

				$result = $ratings_table->update_rating( $feedback ); 

				if ( $result ) { 
	    			$message =  __( 'Vendor rating updated.', 'wcvendors-pro' ); 
	    	 		include( apply_filters( 'wcvendors_pro_ratings_admin_page_table_notice_path', 'partials/ratings/admin/wcvendors-pro-ratings-table-notice.php' ) ); 
	    		}

			} elseif ('delete' === $ratings_table->current_action()) { 

				$id = $_GET[ 'wcv_vendor_rating_id' ]; 

				if ( isset($id) ) { 

					$result = $ratings_table->delete_ratings($id); 

					if ( $result ) { 
		    			$message = __( 'Vendor rating deleted.', 'wcvendors-pro' ); 
		    			include( apply_filters( 'wcvendors_pro_ratings_admin_page_table_notice_path', 'partials/ratings/admin/wcvendors-pro-ratings-table-notice.php' ) ); 
		    		}
				}
			} 

			include( apply_filters( 'wcvendors_pro_ratings_admin_page_table_path', 'partials/ratings/admin/wcvendors-pro-ratings-table.php' ) ); 
	
		}

		include( apply_filters( 'wcvendors_pro_ratings_admin_page_table_end_path', 'partials/ratings/admin/wcvendors-pro-ratings-table-end.php' ) ); 

	} // ratings_admin_page() 



	/**
	 *  Add the vendor ratings tab on the front end
	 * 
	 * @since    1.0.0
	 */
	public function vendor_ratings_panel_tab( $tabs ) {

		global $product; 

		$feedback_display 	= WC_Vendors::$pv_options->get_option( 'feedback_display' ); 
		$post 				= get_post( $product->get_id() ); 

		if ( WCV_Vendors::is_vendor( $post->post_author ) && ! $feedback_display ){ 

			$vendor_ratings_label = WC_Vendors::$pv_options->get_option( 'vendor_ratings_label' ); 

			$tabs[ 'vendor_ratings_tab' ] = apply_filters( 'wcv_vendor_ratings_tab', array(
				'title' 	=> $vendor_ratings_label,
				'priority' 	=> 50,
				'callback' 	=> array( $this, 'vendor_ratings_panel' ) 
			) );
		} 

		return $tabs;

	} // vendor_ratings_panel_tab()

	/**
	 * 
	 */

	/**
	 *  Add the vendor ratings information for this product to the front end
	 * 
	 * @since    1.0.0
	 */
	public function vendor_ratings_panel() {

		global $product;

		$product_feedback 	= $this->get_product_feedback( $product->get_id() ); 
		$post 				= get_post( $product->get_id() ); 

		echo self::ratings_link( $post->post_author, true, __( 'View All Ratings <br /><br />', 'wcvendors-pro' ) ) ;

		if ( $product_feedback ) { 

			foreach ( $product_feedback as $pf ) {

				$customer 		= get_userdata( $pf->customer_id ); 
				$rating 		= $pf->rating; 
				$rating_title 	= $pf->rating_title; 
				$comment 		= $pf->comments;
				$post_date		= date_i18n( get_option( 'date_format' ), strtotime( $pf->postdate ) );  
				$customer_name 	= ucfirst( $customer->display_name ); 

				wc_get_template( 'ratings-display-panel.php', array( 
					'rating' 		=> $rating, 
					'rating_title' 	=> $rating_title,
					'comment' 		=> $comment, 
					'customer_name' => $customer_name, 
					'post_date' 	=> $post_date, 

				), 'wc-vendors/front/ratings/', $this->base_dir . 'templates/front/ratings/' );
			}

			
		} else { 

			echo __( 'No ratings have been submitted for this product yet.', 'wcvendors-pro' ); 
		}
		

	} // vendor_ratings_panel()

	/**
	 *  Update Table Headers for display of vendor ratings
	 * 
	 * @since    1.0.0
	 * @param 	 array 	$headers  array passed via filter 
	 */
	public function table_columns() {

		$columns = array(
				   	'ID'  			=> __( 'ID', 		'wcvendors-pro' ), 
					'order_id'		=> __( 'Order #', 	'wcvendors-pro' ),
					'feedback'		=> __( 'Feedback', 	'wcvendors-pro' ),		
					'product_id'	=> __( 'Product', 	'wcvendors-pro' ),
					'customer_id'	=> __( 'Customer', 	'wcvendors-pro' ),		
					'postdate'		=> __( 'Date', 		'wcvendors-pro' ), 
	   		);

		return $columns; 

	} // table_columns() 

	/**
	 *  Retrieve the vendor ratings data 
	 * 
	 * @since    1.0.0
	 * @return   array  $new_rows   array of stdClass objects passed back to the filter 
	 */
	public function table_rows( ) {

		$feedback = self::get_vendor_feedback( get_current_user_id() ); 

		$new_rows = array(); 

		foreach ( $feedback as $fb ) {
	
			$customer 					= get_userdata( $fb->customer_id ); 
			$order 						= new WC_Order( $fb->order_id ); 
			$order_items				= $order->get_items(); 

			$product_search = new RecursiveIteratorIterator( new RecursiveArrayIterator( $order_items ) );

			$product_title = ''; 

			foreach ( $product_search as $product_meta ) {

			    $subArray = $product_search->getSubIterator();

			    if ( array_key_exists( 'product_id', $subArray ) ) { 
				    if ( $subArray['product_id'] === $fb->product_id )  {
						$product_title = reset( $subArray ); 
				    }
				} 
			}

			$feedback 					= '';
			include('partials/ratings/admin/wcvendors-pro-ratings-feedback.php'); 

			$new_row = new stdClass();
			$new_row->ID 				= $fb->id; 
			$new_row->order_id			= $fb->order_id; 
			$new_row->feedback			= $feedback; 
			$new_row->product_id		= $product_title; 
			$new_row->customer_id		= ucfirst( $customer->display_name ); 
			$new_row->postdate			= date_i18n( get_option( 'date_format' ), strtotime( $fb->postdate ) );  

			$new_rows[] = $new_row; 
		}

		return $new_rows; 

	} // table_rows() 


	/**
	 *  Change the column that actions are displayed in 
	 * 
	 * @since    1.0.0
	 * @param 	 string $column  		column passed from filter 
	 * @return   string $new_column   	new column passed back to filter 
	 */
	public function table_action_column( $column ) {

		$new_column = ''; 

		return $new_column; 

	} // table_action_column()

	/**
	 *  Change the column that actions are displayed in 
	 * 
	 * @since    1.0.0
	 * @param 	 string $column  		column passed from filter 
	 * @return   string $new_column   	new column passed back to filter 
	 */
	public function table_no_data_notice( $notice ) {

		$notice = __( 'No ratings found.', 'wcvendors-pro' ); 
		return $notice; 

	}

	/**
	 * Display the custom order table 
	 *
	 * @since    1.0.0
	 */
	public function display() { 

		// Use the internal table generator to create object list 
		$ratings_table = new WCVendors_Pro_Table_Helper( $this->wcvendors_pro, $this->version, 'rating', null, get_current_user_id() ); 

		$ratings_table->set_columns( $this->table_columns() ); 
		$ratings_table->set_rows( $this->table_rows() ); 

		// display the table 
		$ratings_table->display(); 

	} // display() 


	/**
	 * Display the custom order table 
	 *
	 * @since    1.2.0
	 */
	public function display_vendor_ratings( ){ 
		
		if ( ! is_admin() ) { 

			if ( get_query_var( 'ratings' ) ) { 

				$vendor_shop 		= urldecode( get_query_var( 'vendor_shop' ) );
				$vendor_id   		= WCV_Vendors::get_vendor_id( $vendor_shop ); 
				$vendor_feedback 	= WCVendors_Pro_Ratings_Controller::get_vendor_feedback( $vendor_id );

				wc_get_template( 'store-ratings.php', array( 
					'vendor_shop' 		=> $vendor_shop, 
					'vendor_id' 		=> $vendor_id,
					'vendor_feedback' 	=> $vendor_feedback, 
				), 'wc-vendors/store/', $this->base_dir . 'templates/store/' );

				exit; 

			}

		}

	} // display_vendor_ratings() 


	/**
	 * Add the query vars 
	 *
	 * @since    1.2.0
	 */
	public function add_query_vars( $query_vars ){ 

		$query_vars[] = 'ratings'; 
		return $query_vars; 

	} // add_query_vars() 

	/**
	 * Add the ratings rewrite rule 
	 *
	 * @since    1.2.0
	 */
	public function add_rewrite_rules( $rules ){ 

		$permalink = untrailingslashit( WC_Vendors::$pv_options->get_option( 'vendor_shop_permalink' ) );

		// Remove beginning slash
		if ( substr( $permalink, 0, 1 ) == '/' ) {
			$permalink = substr( $permalink, 1, strlen( $permalink ) );
		}

		$ratings_rule = array(  $permalink . '/([^/]*)/ratings' => 'index.php?post_type=product&vendor_shop=$matches[1]&ratings=all' ); 
		$rules = $ratings_rule + $rules; 

		return $rules; 

	} //add_rewrite_rules() 

	/**
	 *  Output a vendor ratings link 
	 *
	 * @since    1.3.0
	 */
	public function wcv_feedback( $atts ) { 

		extract( shortcode_atts( array(
				'vendor' => '',
			), $atts ) );

		ob_start();

		if ( ! WCVendors_Pro::get_option( 'ratings_management_cap' ) ) echo WCVendors_Pro_Ratings_Controller::ratings_link( WCV_Shortcodes::get_vendor( $vendor ), true );

		return ob_get_clean(); 

	} // wcv_feedback() 


} // End WCVendors_Pro_Ratings