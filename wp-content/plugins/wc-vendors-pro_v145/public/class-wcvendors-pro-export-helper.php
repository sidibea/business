<?php
/**
 * The WCVendors Export Helper Class
 *
 * This is the this is the helper class to help exporting data for vendors 
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Export_Helper {

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
	 * Send the CSV to the browser for download
	 *
	 * @since   1.0.0
	 * @param 	array  $orders
	 * @return 	array  $orders  formatted 
	 */
	public function format_orders_export( $all_orders ) { 

		$rows = array(); 

		if ( !empty( $all_orders ) ) { 

			foreach ( $all_orders as $_order ) { 

				$order 			= $_order->order; 
				$products		= ''; 
				$needs_shipping = false; 
				$needs_to_ship 	= false; 
				$downloadable 	= false; 

				foreach ( $_order->order_items as $key => $item ) { 
		
					$product_id = !empty( $item['variation_id'] ) ? $item['variation_id'] : $item['product_id'];
					$_product = new WC_Product( $product_id );

					$needs_shipping 	= $_product->is_virtual(); 
					if ( !$needs_shipping ) $needs_shipping = 0; 

					$downloadable 		= ( $_product->is_downloadable( 'yes' ) ) ?  true: false; 
					if ( $downloadable == null )  $downloadable = 0; 
					$item_qty 		= $item['qty']; 
					$item_name		= $item['name']; 
					$products 		.= "$item_qty x $item_name \r"; 
					$needs_to_ship 	= ( $needs_shipping || !$downloadable ) ? true : false; 
				}

				$order_id 		= ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->id : $order->get_id();  	
				$shippers 		= (array) get_post_meta( $order_id, 'wc_pv_shipped', true ); 
				$has_shipped 	= in_array( get_current_user_id() , $shippers ) ? __( 'Yes', 'wcvendors-pro' )  : __( 'No', 'wcvendors-pro' ); 
				$shipped 		= ( $needs_to_ship ) ? $has_shipped : __( 'NA', 'wcvendors-pro' ); 
				$order_date 	= ( version_compare( WC_VERSION, '2.7', '<' ) ) ? $order->order_date : $order->get_date_created();  

				$new_row = array(); 

				$new_row[' order_number' ]		= $order->get_order_number(); 
				$new_row[' customer' ]			= str_replace('<br/>', ', ', $order->get_formatted_shipping_address() );
				$new_row[' products' ] 			= $products;
				$new_row[' total' ] 			= $_order->total;
				$new_row[' status' ] 			= $shipped;
				$new_row[' order_date' ]		= date_i18n( 'Y-m-d', strtotime( $order_date ) ); 

				$rows[] = $new_row; 

			} 
		} // check for orders 

		return $rows; 

	} // prepare_orders_export()

	/**
	 * Send the CSV to the browser for download
	 *
	 * @since   1.0.0
	 * @param 	array  $headers
	 * @param 	array  $body
	 * @param 	string $filename
	 */
	public function download_csv( $headers, $body, $filename ) {

		// Clear browser output before this point
		if ( ob_get_contents() ) ob_end_clean(); 

		// Output headers so that the file is downloaded rather than displayed
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename . '.csv' );

		// Create a file pointer connected to the output stream
		$csv_output = fopen( 'php://output', 'w' );

		// Output the column headings
		fputcsv( $csv_output, $headers );

		// Body
		foreach ( $body as $data ) { 
			fputcsv( $csv_output, $data );
		}

		die();

	} // download_csv() 


}