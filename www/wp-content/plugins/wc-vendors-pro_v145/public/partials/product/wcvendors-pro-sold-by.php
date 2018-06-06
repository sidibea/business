<?php

/**
 * Output the Sold By text in the loop. 
 *
 * This file outputs the Sold By in the product loop and single page
 *
 * @link       http://www.wcvendors.com
 * @since      1.0.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/product
 */ 
	
if ( isset( $sold_by[ 'wrapper_start' ] ) && $sold_by[ 'wrapper_start' ] != '' ) echo $sold_by[ 'wrapper_start' ]; 

if ( $sold_by[ 'vendor_id' ] != 1 ) { 
	echo $sold_by[ 'title' ] .' <a href="'.$sold_by[ 'shop_url' ].'">'. $sold_by[ 'shop_name' ] .'</a>'; 
} else { 
	echo $sold_by[ 'title' ] .' '.$sold_by[ 'shop_name' ]; 
}

if ( isset( $sold_by[ 'wrapper_end' ] ) && $sold_by[ 'wrapper_end' ] != '' ) echo $sold_by[ 'wrapper_end' ]; 
	
?>