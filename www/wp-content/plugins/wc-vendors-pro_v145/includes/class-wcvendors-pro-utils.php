<?php 
/**
 * The plugin utils class 
 *
 * This is used to define utility helpers for the plugin
 * 
 *
 *
 * @since      1.0.0
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/includes
 * @author     Jamie Madden <support@wcvendors.com>
 */

class WCVendors_Pro_Utils { 
	

	/**
	 *  Sort a multi dimensional array by nested array key 
	 * 
	 * @since    1.3.0
	 * @param 	 array 	$array the array to sort 
	 * @param 	 string $key the array key to sort on 
	 * @param 	 const 	$order the sort order 
	 * Source :  http://stackoverflow.com/a/16306693/977610
	 */
	public static function array_sort( $array, $key, $order=SORT_ASC ) {

	    $new_array = array();
	    $sortable_array = array();

	    if ( count( $array ) > 0 ) {
	        foreach ( $array as $k => $v ) {
	            if (is_array($v)) {
	                foreach ( $v as $k2 => $v2 ) {
	                    if ( $k2 == $key ) {
	                        $sortable_array[ $k ] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[ $k ] = $v;
	            }
	        }

	        switch ( $order ) {
	            case SORT_ASC:
	                asort( $sortable_array );
	                break;
	            case SORT_DESC:
	                arsort( $sortable_array );
	                break;
	        }

	        foreach ( $sortable_array as $k => $v ) {
	            $new_array[ $k ] = $array[ $k ];
	        }
	    }

	    return $new_array;

	} // array_sort() 


}