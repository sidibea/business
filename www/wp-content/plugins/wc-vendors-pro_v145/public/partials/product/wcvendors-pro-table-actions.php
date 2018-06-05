<?php

/**
 * Product Table Main Actions 
 *
 * This file is used to add the table actions before and after a table
 *
 * @link       http://www.wcvendors.com
 * @since      1.2.4
 * @version    1.4.0 
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/product
 */ 



?>

<div class="wcv_dashboard_table_header wcv-cols-group wcv-search"> 

	<div class="all-70"> 
		<?php if ( strlen( $search ) > 0 ) : ?> 
			<span class="wcv_search_results"><?php printf( __( 'Search results for "%s" ...', 'wcvendors-pro' ), $search ); ?></span> 
		<?php endif; ?> 
	</div>

	<div class="all-30" style="float:right">
		<form class="wcv-form" method="post">
			<div class="column-group horizontal-gutters">
				<div class="control-group">
		            <div class="control append-button" role="search">
		                <span><input type="text" name="wcv-search" id="wcv-search" value="<?php echo $search; ?>"></span>
		                <button class="wcv-button"><?php echo __( 'Search', 'wcvendors-pro' ); ?></button>
		            </div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="wcv_actions wcv-cols-group"> 
	<div class="all-50">
	<?php foreach ( $template_overrides as $key => $template_data ) : ?>
		<a href="<?php echo $template_data[ 'url' ]; ?>" class="wcv-button button"><?php echo sprintf( __( 'Add %s ', 'wcvendors-pro' ), $template_data[ 'label' ] );  ?></a>
	<?php endforeach; ?>
	</div>

	<div class="all-50" style="float:right">
			<?php 
				echo $pagination_wrapper[ 'wrapper_start' ];
				echo paginate_links( apply_filters( 'wcv_product_pagination_args', array(  
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


	
