<?php 

if( !class_exists('WP_List_Table') ){ require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ); }

/**
 * The WCVendors Pro Rating Admin Table Class
 *
 * This class outputs the ratings table in the admin dashboard
 *
 *
 * @since      1.0.0
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/includes
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Ratings_Admin_Table extends WP_List_Table {

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
	public $table_name; 

    public function __construct( $wcvendors_pro, $version, $debug, $table_name ) {

    	global $wpdb; 

       	parent::__construct( array(
      		'singular'	=> 'vendor_rating', //Singular label
      		'plural' 	=> 'vendor_ratings', //plural label, also this well be one of the table css class
      		'ajax'   	=> false //We won't support Ajax for this table
      	) );

      	$this->wcvendors_pro 	= $wcvendors_pro;
		$this->version 			= $version;
		$this->debug 			= $debug; 
		$this->base_dir			= plugin_dir_path( dirname( __FILE__ ) ); 
		$this->table_name 		= $wpdb->prefix . "wcv_feedback";		

       	add_filter( 'init', array( $this, 'vendor_rating_actions' ) );
    }

    function get_columns() {

	   return $columns = array(
	   	'cb'         	=> '<input type="checkbox" />',
		'order_id'		=>__( 'Order #', 	'wcvendors-pro' ),
		'rating_title'	=>__( 'Title', 		'wcvendors-pro' ),		
		'vendor_id'		=>__( 'Vendor', 	'wcvendors-pro' ),
		'product_id'	=>__( 'Product', 	'wcvendors-pro' ),
		'customer_id'	=>__( 'Customer', 	'wcvendors-pro' ),		
		'comments'		=>__( 'Comments', 	'wcvendors-pro' ),
		'rating'		=>__( 'Rating', 	'wcvendors-pro' ), 
		'postdate'		=>__( 'Date', 		'wcvendors-pro' ), 
	   );
	} 

	public function get_sortable_columns() {

		return $sortable_columns = array(
			'vendor_id' 	=> array( 'vendor_id', false ),
		    'product_id'	=> array( 'product_id', false ),
		    'order_id'		=> array( 'order_id', false ), 
			'postdate' 		=> array( 'postdate', false )
		);
	}

	/**
	 * Return the column check box 
	 *
	 * @param array $item
	 *
	 * @return mixed
	 */
	public function column_cb( $item )
	{
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			/*$1%s*/
			'wcv_vendor_rating_id',
			/*$2%s*/
			$item->id
		);
	}

	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) { 

		switch ( $column_name ) {
			case 'id': 
				return $item->id; 
			case 'vendor_id' 	: 
		      	$user = get_userdata( stripslashes($item->vendor_id) );
				return $link = '<a href="' . admin_url( 'user-edit.php?user_id=' . stripslashes($item->vendor_id) ) . '">' . WCV_Vendors::get_vendor_shop_name( stripslashes( $item->vendor_id ) ) . '</a>';
	      	case 'product_id' : 
		      	return $link = '<a href="' . admin_url( 'post.php?post=' . stripslashes($item->product_id) . '&action=edit' ) . '">' . get_the_title( stripslashes($item->product_id) ) . '</a>';  
	      	case 'order_id' : 
		      	return $link = '<a href="' . admin_url( 'post.php?post=' . stripslashes($item->order_id) . '&action=edit' ) . '">' . stripslashes($item->order_id) . '</a>';
	      	case 'customer_id' :
		      	$user = get_userdata( stripslashes( $item->customer_id ) );
				return $link = '<a href="' . admin_url( 'user-edit.php?user_id=' . stripslashes( $item->customer_id) ) . '">' . $user->display_name . '</a>'; 
	      	case 'rating_title' : 
		      	return stripslashes( $item->rating_title );   
	      	case 'comments' : 
		      	return stripslashes( $item->comments );   
		      	break;
	      	case 'rating'	: 
	      		$rating = ''; 
		      	for ($i = 1; $i<=stripslashes( $item->rating ); $i++) { $rating .= "<i class='fa fa-star'></i>"; } 
				for ($i = stripslashes( $item->rating ); $i<5; $i++) { $rating .=  "<i class='fa fa-star-o'></i>"; }
				return $rating; 
	      	case 'postdate' : 
		      	return date_i18n( get_option( 'date_format' ), strtotime( $item->postdate ) ); 
		
	     }

	}

	/**
	 * Get the bulk actions 
	 *
	 * @since 1.0.0
	 *
	 * @return array $actions available bulk actions 
	 */
	function get_bulk_actions()
	{
		$actions = array(
			'delete_ratings' => __('Delete',  'wcvendors-pro' ),
		);

		return $actions;
	}

	/**
	 * Process the bulk actions for the table 
	 *
	 * @since 1.0.0
	 * 
	 *
	 */
	public function process_bulk_action() {
        
	    if ( 'delete_ratings' === $this->current_action() ) {
	       
	    	$id = $_REQUEST[ 'wcv_vendor_rating_id' ]; 

	    	$result = $this->delete_ratings($id); 

	    	if ( $result ) { 
	    		echo '<div class="updated"><p>' . __( 'Vendor rating(s) deleted.', 'wcvendors-pro' ) . '</p></div>';
	    	}
	    }

    }


    /**
	 * Display the feedback edit form 
	 *
	 * @param array $feedback the feedback to edit
	 * @since 1.0.0
	 *
	 */
 	public function display_edit_form( $feedback ) { 

		include( apply_filters( 'wcvendors_pro_display_edit_form_path', 'partials/ratings/admin/wcvendors-pro-ratings-feedback-form.php' ) ); 

	} // display_edit_form() 


	/**
	 * Add the screen options to the table 
	 *
	 * @since 1.0.0
	 *
	 */
	public static function add_options() {

	  $option = 'per_page';

	  $args = array(
	         'label' 	=> __('Vendor Ratings' , 'wcvendors-pro' ), 
	         'default' 	=> 20,
	         'option' 	=> 'ratings_per_page'
	         );

	  add_screen_option( $option, $args );

	} // add_options() 


	/**
	 *   column rating_title actions single edit/delete functions 
	 */
	public function column_rating_title( $item ) {
	  $actions = array(
	            'edit'      => sprintf('<a href="?page=%s&action=%s&wcv_vendor_rating_id=%s">%s</a>',$_REQUEST[ 'page' ],'edit', $item->id, __( 'Edit', 'wcvendors-pro') ),
	            'delete'    => sprintf('<a href="?page=%s&action=%s&wcv_vendor_rating_id=%s">%s</a>',$_REQUEST[ 'page' ],'delete',$item->id, __( 'Delete', 'wcvendors-pro') ),
	        );

	  return sprintf('%1$s %2$s', $item->rating_title, $this->row_actions( $actions ) );
	}

	/**
	 * Prepare the items for display 
	 *
	 * @since 1.0.0
	 *
	 */
	public function prepare_items() {

		$screen 							= get_current_screen();
		$hidden 							= array();
		$sortable 							= $this->get_sortable_columns();
		$columns 							= $this->get_columns();
		$this->_column_headers 				= array( $columns, $hidden, $sortable );
		$_wp_column_headers[ $screen->id ] 	= $columns;

		/** Process bulk action */
		$this->process_bulk_action();
		
		$per_page     = $this->get_items_per_page( 'ratings_per_page', 10 );
		$current_page = $this->get_pagenum(); 
		$total_items  = self::count_feedback();

		$this->set_pagination_args( array( 
			'total_items' => $total_items, 
			'per_page'    => $per_page, 
			) 
		);

		$this->items = self::get_feedback( $per_page, $current_page );
		
	} // prepare_items() 

	/**
	 * Retrieve feedback data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 * @since 1.0.0
	 *
	 * @return mixed
	 */
	public function get_feedback( $per_page = 5, $page_number = 1 ) { 

		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}wcv_feedback";

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";

		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		
		$result = $wpdb->get_results( $sql );
		
		return $result;

	}

	/**
	 * Returns the count of feedback in the database.
	 *
	 * @return null|string
	 */
	public static function count_feedback() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}wcv_feedback";
		
		return $wpdb->get_var( $sql );

	} // count_feedback() 


	/**
	 * Update a specific rating 
	 *
	 * @param array $feedback the feedback post array 
	 *
	 * @return mixed $result result of the action 
	 */
	public function update_rating( $feedback ) { 

		global $wpdb; 

		$result = $wpdb->update(
				$this->table_name,
				array(
					'comments'    		=> esc_html( $feedback[ 'rating_comments' ] ),
					'rating_title'    	=> esc_html( $feedback[ 'rating_title' ] ),
				),
				array( 'id' => $feedback[ 'id' ] ), 
				array(
					'%s',
					'%s'
					)
				);

		return $result; 

	} // update_rating() 

	/**
	 * Delete the ratings by id's 
	 *
	 * @param array $ids the feedback to delete 
	 *
	 * @return mixed $result result of the action 
	 */
	public function delete_ratings( $ids ) { 

		global $wpdb;

		// Convert to an array to process multiple results of required 
		$ids = ( is_array( $ids ) ) ? $ids  : array( $ids ); 

        foreach ( $ids as $id ) {
            $id = absint( $id );
            $result = $wpdb->query( "DELETE FROM $this->table_name WHERE id = $id" );
        }

        return $result; 
	} // delete_ratings() 

	/**
	 * Display if no ratings found
	 *
	 * @param array $ids the feedback to delete 
	 *
	 * @return mixed $result result of the action 
	 */
	public function no_items() {
  		_e( 'No vendor ratings found.', 'wcvendors-pro' );
	} // no_items()


} //end WCVendors_Pro_Ratings_Admin_Table