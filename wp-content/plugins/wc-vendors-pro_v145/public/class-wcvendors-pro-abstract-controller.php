<?php
/**
 * The WCVendors Pro Abstract Controller class
 *
 * This is the abstract controller class for all front end actions 
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Abstract_Controller {

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
 
	}

	/**
	 *  Process the form submission from the front end. 
	 *
	 * @since    1.0.0
	 */
	public function process_submit() { 

	} // process_submit() 

	/**
	 *  Process the delete action 
	 *
	 * @since    1.0.0
	 */
	public function process_delete( ) { 

	} // process_delete() 

	
	/**
	 *  Update Table Headers for display
	 * 
	 * @since    1.0.0
	 * @param 	 array 	$headers  array passed via filter 
	 */
	public function table_columns( $columns ) {

		$columns = array( ); 

		return $columns; 

	} // table_columns() 

	/**
	 *  Manipulate the table data 
	 * 
	 * @since    1.0.0
	 * @param 	 array 	$rows  		array of wp_post objects passed by the filter 
	 * @return   array  $new_rows   array of stdClass objects passed back to the filter 
	 */
	public function table_rows( $rows ) {

		$new_rows = array(); 

		foreach ($rows as $row ) {
			$new_row = new stdClass(); 
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

		$new_column = 'post_title'; 
		return $new_column; 

	}

}