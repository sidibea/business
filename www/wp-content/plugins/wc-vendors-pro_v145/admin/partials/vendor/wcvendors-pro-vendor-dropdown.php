<?php 
/**
 * Output the vendor metabox 
 *
 * 
 *
 * @link       http://www.wcvendors.com
 * @since      1.3.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials
 */

$output = "<select style='width:150px;' name='vendor_id' id='vendor_id'>\n";
$output .= "\t<option value=''>".__('Select a Vendor', 'wcvendors-pro')."</option>\n";

foreach ( (array) $users as $user ) {

	$select = selected( $user->ID, $wp_query->query_vars[ 'author' ], false );
	$output .= "\t<option value='$user->ID' $select>$user->user_login</option>\n";
}

$output .= "</select>";