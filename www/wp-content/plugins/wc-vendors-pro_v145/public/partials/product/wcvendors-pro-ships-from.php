<?php

/**
 * Output the ships from 
 *
 * This file outputs the ships from on the single product page meta
 *
 * @link       http://www.wcvendors.com
 * @since      1.2.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/product
 */ 
	
if ( isset( $ships_from[ 'wrapper_start' ] ) && $ships_from[ 'wrapper_start' ] != '' ) echo $ships_from[ 'wrapper_start' ]; 

echo '<span>'. $ships_from[ 'title' ] .' '.$ships_from[ 'store_country' ] . '</span>'; 

if ( isset( $ships_from[ 'wrapper_end' ] ) && $ships_from[ 'wrapper_end' ] != '' ) echo $ships_from[ 'wrapper_end' ]; 
	
?>