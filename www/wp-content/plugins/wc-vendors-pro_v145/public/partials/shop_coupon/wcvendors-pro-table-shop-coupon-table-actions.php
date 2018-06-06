<?php

/**
 * Shop Coupon Table Main Action 
 *
 * This file is used to add the table actions before and after a table
 *
 * @link       http://www.wcvendors.com
 * @since      1.2.4
 * @version    1.3.2
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/shop_coupon
 */ 
?>

<div class="wcv_dashboard_table_header wcv_actions wcv-cols-group"> 
	<div class="all-50">
		<a href="<?php echo $add_url; ?>" class="wcv-button button"><?php echo __('Add Coupon', 'wcvendors-pro' ); ?></a>
	</div>
	<div class="all-50">	
	<?php 
		echo $pagination_wrapper[ 'wrapper_start' ];

		echo paginate_links( apply_filters( 'wcv_shop_coupon_pagination_args', array(  
			    'base' 			=> get_pagenum_link( ) . '%_%',  
			    'format' 		=> 'page/%#%/',  
			    'current' 		=> ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,  
			    'total' 		=> $this->max_num_pages,  
			    'prev_next'    	=> true,  
			    'type'         	=> 'list',  
				), ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1, $this->max_num_pages
			) );

		echo $pagination_wrapper[ 'wrapper_end' ];
		?>
	</div>
</div>