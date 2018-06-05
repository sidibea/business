<?php

/**
 * Product Meta Tabs 
 *
 * This file is used to load the download files data 
 *
 * @link       http://www.wcvendors.com
 * @since      1.0.2
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/product
 */ 
?>

<ul class="<?php echo $css_class; ?>" style="padding:0; margin:0;">
	<?php foreach ( $product_meta_tabs as $tab ) : ?>
	    <li><a class="tabs-tab <?php echo implode( ' ' , $tab[ 'class' ] ); ?> <?php echo $tab[ 'target' ]; ?>" href="#<?php echo $tab[ 'target' ]; ?>"><?php echo $tab[ 'label' ]; ?></a></li>
	<?php endforeach; ?>        
</ul>
