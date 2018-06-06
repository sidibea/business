<?php

/**
 * Output the vendor metabox 
 *
 * 
 *
 * @link       http://www.wcvendors.com
 * @since      1.0.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials
 */


$output = "<select style='width:200px;' name='$id' id='$id' class='$class' data-placeholder='$placeholder'>\n";
$output .= "\t<option value=''></option>\n";

foreach ( $roles as $role ) {

	$new_args           = $user_args;
	$new_args[ 'role' ] = $role;
	$users              = get_users( $new_args );

	if ( empty( $users ) ) continue;
	foreach ( (array) $users as $user ) {
		$select = selected( $user->ID, $selected, false );
		$output .= "\t<option value='$user->ID' $select>$user->user_login</option>\n";
	}

}
$output .= "</select>";

// Convert this selectbox with select2
$output .= '
<script type="text/javascript">jQuery(function() { jQuery("#' . $id . '").select2().focus(); } );</script>';		

?>