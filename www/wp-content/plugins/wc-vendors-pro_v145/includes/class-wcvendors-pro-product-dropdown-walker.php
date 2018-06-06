<?php
/**
 * WCV_Product_Dropdown_Walker class.
 *
 * @extends 	Walker
 * @class 		WCV_Product_Dropdown_Walker
 * @version		1.1.4
 * @package     WCVendors_Pro
 * @subpackage  WCVendors_Pro/includes
 * @author      Jamie Madden <support@wcvendors.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WCV_Product_Dropdown_Walker extends Walker {

	public $tree_type = 'category';
	public $db_fields = array ('parent' => 'parent', 'id' => 'term_id', 'slug' => 'slug' );

	/**
	 * @see Walker::start_el()
	 * @since 2.1.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of category in reference to parents.
	 * @param integer $current_object_id
	 */
	public function start_el( &$output, $cat, $depth = 0, $args = array(), $current_object_id = 0 ) {

		if ( ! empty( $args['hierarchical'] ) )
			$pad = str_repeat('&nbsp;', $depth * 3);
		else
			$pad = '';

		$cat_name = apply_filters( 'list_product_cats', $cat->name, $cat );

		$value = isset( $args['value'] ) && $args['value'] == 'id' ? $cat->term_id : $cat->slug;

		$output .= "\t<option class=\"level-$depth\" value=\"" . $value . "\"";

		if ( $value == $args['selected'] || ( is_array( $args['selected'] ) && in_array( $value, $args['selected'] ) ) )
			$output .= ' selected="selected"';

		$output .= '>';

		$output .= $pad . __( $cat_name, 'wcvendors-pro' );

		if ( ! empty( $args['show_count'] ) )
			$output .= '&nbsp;(' . $cat->count . ')';

		$output .= "</option>\n";
	}

}

function wcv_walk_category_dropdown_tree() {
		
	$args = func_get_args();
	
	// the user's options are the third parameter
	if ( empty( $args[2]['walker']) || !is_a($args[2]['walker'], 'Walker' ) ) {
		$walker = new WCV_Product_Dropdown_Walker;
	} else {
		$walker = $args[2]['walker'];
	}

	return call_user_func_array( array( &$walker, 'walk' ), $args );

}
