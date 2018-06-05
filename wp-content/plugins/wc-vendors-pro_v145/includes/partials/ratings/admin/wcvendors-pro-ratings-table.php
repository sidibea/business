<?php

/**
 *  The main table for the ratings admin 
 *
 * This file is used to display the ratings link 
 *
 * @link       http://www.wcvendors.com
 * @since      1.0.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/includes/partials/ratings
 */


$ratings_table->prepare_items();

echo '<form id="vendor_ratings-filter" method="get"><input type="hidden" name="page" value="'.$_REQUEST['page'].'" />';

$ratings_table->display(); 

echo '</form>'; 