<?php

/**
 * Table Helper Class
 *
 * Defines relevant methods for generating a display table for public facing pages. 
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Table_Helper {

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
	 * The table id.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $id    The table id 
	 */
	private $id;

	/**
	 * The tables header rows 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $columns    The table columns
	 */
	private $columns;

	/**
	 * The table rows 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $rows    The table rows
	 */
	private $rows;


	/**
	 * The tables row action 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $actions    The table row actions
	 */
	private $actions;

	/**
	 * The column to display action 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $action_column   The column key from $this->actions 
	 */
	private $action_column;


	/**
	 * The post_type for this table
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $post_type    The post type to display 
	 */
	private $post_type;


	/**
	 * The vendor id of the products for this table
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int    $vendor    The vendor id of the post type. 
	 */
	private $vendor_id;

	/**
	 * The max number of pages for the results
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int    $vendor    The vendor id of the post type. 
	 */
	public $max_num_pages;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $wcvendors_pro     	The name of the plugin.
	 * @param    string    $version    			The version of this plugin.
	 * @param    string    $id    				The table id used to reference the table
	 */
	public function __construct( $wcvendors_pro, $version, $id, $post_type, $vendor_id ) {

		$this->wcvendors_pro 	= $wcvendors_pro;
		$this->version 			= $version;
		$this->id 				= $id; 
		$this->post_type		= $post_type; 
		$this->vendor_id		= $vendor_id; 
		$this->container_wrap	= true; 

		$this->set_defaults(); 

	} 

	/**
	 *  Set the defaults for the table 
	 *  
	 *  This sets up the default values for the different aspects of the table. 
	 *
	 * @since    1.0.0
	 */
	public function set_defaults() { 

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$search = isset( $_POST[ 'wcv-search'] ) ?  $_POST[ 'wcv-search'] : ''; 

		// Default table rows 
		$args = array(
				'posts_per_page' => apply_filters( 'wcvendors_pro_table_post_per_page_' . $this->id, 20 ),
				'post_type'   => $this->post_type,
				'author' 	  => $this->vendor_id,
				'post_status' => 'any',
				'paged'		  => $paged, 
				's'			  => $search, 
		);

		$args = apply_filters( 'wcvendors_pro_table_row_args_' . $this->id, $args );
		$results = new WP_Query( $args ); 
		
		$this->rows = apply_filters( 'wcvendors_pro_table_rows_' . $this->id, $results->posts, $results ); 
				
		//  Default table columns 
		$this->columns = apply_filters( 'wcvendors_pro_table_columns_' . $this->id, 
				array( 
					'ID'  				=> __( 'ID', 			'wcvendors-pro' ), 
					'post_title'  		=> __( 'Title', 		'wcvendors-pro' ), 
					'post_content'  	=> __( 'Description', 	'wcvendors-pro' ), 
					'post_modified'  	=> __( 'Date Posted', 	'wcvendors-pro' ), 
				)
		); 

		// Default table actions 
		$this->actions = apply_filters( 'wcvendors_pro_table_actions_' . $this->id, 
				array( 
					'edit'  	=> array( 'label' => __( 'Edit', 	'wcvendors-pro' ), 'url' => '' ), 
					'delete'  	=> array( 'label' => __( 'Delete', 	'wcvendors-pro' ), 'url' => '' ), 
					'view'  	=> array( 'label' => __( 'View', 	'wcvendors-pro' ), 'url' => '' ), 
				)
		); 

		// Which column to display the actions in by default  
		$this->action_column = apply_filters( 'wcvendors_pro_table_action_column_' . $this->id, 'post_title' ); 
	}

	/**
	 *  Set the table columns
	 *  
	 *  Associative array in the format 'name' => __('Label', 'wcvendors-pro' )
	 *
	 * @since    1.0.0
	 * @param    array     $columns     	The table columns
	 */
	public function set_columns( $columns ) { 

		$this->columns = $columns; 

	} // set_columns() 

	/**
	 *  Get the table columns
	 *  
	 *  Associative array in the format 'name' => __('Label', 'wcvendors-pro' )
	 *
	 * @since    1.0.0
	 * @return   array     $columns     	The table columns
	 */
	public function get_columns( ) { 

		return $this->columns; 

	} // get_columns()

	/**
	 *  Set the table data
	 *
	 * @since    1.0.0
	 * @param    array     $rows     	The table data
	 */
	public function set_rows( $rows ) { 

		$this->rows = $rows; 

	} // set_rows()

		/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @return 	 array 	The table rows 
	 */
	public function get_rows( ) { 

		return $this->rows;

	}  // get_rows()

	/**
	 *  Set the table actions
	 *
	 * @since    1.0.0
	 * @param    array     $actions     The table row actions 
	 */
	public function set_actions( $actions = null ) { 
		
		$this->actions = $actions; 

	} // set_actions()


	/**
	 *  Get the table actions
	 *
	 * @since    1.0.0
	 * @return   array     $actions     The table row actions 
	 */
	public function get_actions( ) { 

		 return $this->actions; 

	} // get_actions()


	/**
	 *  Set the column the actions are displayed in 
	 *
	 * @since    1.0.0
	 * @param    string     $column   The column key actions will be displayed in 
	 */
	public function set_action_column( $action_column ) { 

		$this->action_column = $action_column; 

	} // set_action_column()

	/**
	 *  Set the column the actions are displayed in 
	 *
	 * @since    1.0.0
	 * @param    string     $column   The column key actions will be displayed in 
	 */
	public function get_action_column(  ) { 

		return $this->action_column; 

	} // get_action_column()

	public function get_action_url( $object_id ) { 

		return WCVendors_Pro_Dashboard::get_dashboard_page_url( $post_type .'/' . $object_id, 'label'); 
	}


	/**
	 *  Display the table 
	 *
	 * @since    1.0.0
	 */
	public function display() { 

		// Get the rows from the database 
		$this->get_rows(); 
		// Set the table columns
		$this->get_columns(); 
		// Set the row actions 
		$this->get_actions(); 
		// Set the action column
		$this->get_action_column(); 

		// display the table 

		do_action( 'wcvendors_pro_table_before_' . $this->id ); 

		$no_data_notice = apply_filters('wcvendors_pro_table_no_data_notice_' . $this->id, __( "No " . $this->id . "'s found") ); 

		if ( $this->has_rows() ) { 
			include( apply_filters( 'wcvendors_pro_table_path', 'partials/helpers/table/wcvendors-pro-table.php' ) ); 
		} else { 
			include( apply_filters( 'wcvendors_pro_table_no_data_path', 'partials/helpers/table/wcvendors-pro-table-nodata.php' ) ); 
		}

		do_action( 'wcvendors_pro_table_after_' . $this->id ); 

	} 

	/**
	 *  Display the table columns
	 *
	 * @since    1.0.0
	 */
	public function display_columns() { 

		include( apply_filters( 'wcvendors_pro_table_display_columns_path', 'partials/helpers/table/wcvendors-pro-table-columns.php' ) ); 
	} 

	public function display_rows() { 

		include( apply_filters( 'wcvendors_pro_table_display_rows_path', 'partials/helpers/table/wcvendors-pro-table-data.php' ) ); 
		
	}

	/**
	 *  Display the table columns
	 *
	 * @since    1.0.0
	 */
	public function display_actions( $object_id ) { 

		include( apply_filters( 'wcvendors_pro_table_display_actions_path', 'partials/helpers/table/wcvendors-pro-table-actions.php' ) ); 				
	} 


	/**
	 *  Does the table have any data 
	 *
	 * @since    1.0.0
	 * @return   bool   Returns a boolean indicating whether there is any table data. 
	 */
	public function has_rows() { 
		return array_filter( $this->rows ); 
	} 

} 