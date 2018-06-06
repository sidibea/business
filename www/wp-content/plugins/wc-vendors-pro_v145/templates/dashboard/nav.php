<?php
/**
 * The template for displaying the Pro Dashboard navigation 
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/
 *
 * @var 	   $page_url  		The permalink to the page 
 * @var 	   $page_label 		The page label for the menu item 
 * @package    WCVendors_Pro
 * @version    1.0.3
 */
?>
<li id="dashboard-menu-item-<?php echo $page['slug']; ?>" class="<?php echo $class; ?>"><a href="<?php echo $page_url; ?>"><?php echo $page_label; ?></a></li>