<?php

/**
 * Output the vendor tools on the single product page 
 *
 * This file outputs any enabled actions for the product to the product owner. Styles are loaded inline to reduce the requirement for loading two files for almost no code. 
 *
 * @link       http://www.wcvendors.com
 * @since      1.3.6
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/product
 */ 

?>

<script type="text/javascript">
		jQuery( function( $ ){
			$('.confirm_delete').on( 'click', function(e) { 
				if ( ! confirm( $( this ).data('confirm_text') ) ) e.preventDefault(); 
			}); 
		}); 
</script>

<style type="text/css">
	.wcv_single_product_actions a {  padding-right: 5px; }
</style>

<div class="wcv_single_product_actions"> 

<span><?php echo $tools_label; ?></span>
<?php 
	
	foreach ( $actions as $action => $details ) { 

		if ( !empty( $details ) ) { 

			if ( empty( $details['url'] ) ) { 
				if ( $action == 'view' ) { 
					$action_url = get_permalink( $object_id ); 
				} else { 
					$action_url = WCVendors_Pro_Dashboard::get_dashboard_page_url( $this->post_type . '/' . $action . '/' . $object_id );
				} 
			} else { 
				$action_url = $details[ 'url' ];
			} 
		} 

		( ! empty( $details[ 'class' ] ) ) ? $class='class="' .$details[ 'class' ]. '"' : $class = '';
		( ! empty( $details[ 'id' ] ) ) ? $id='id="' .$details[ 'id' ]. '"' : $id = ''; 
		( ! empty( $details[ 'target' ] ) ) ? $target='target="' .$details[ 'target' ]. '"' : $target = ''; 
		$custom = ''; 
		if ( ! empty( $details[ 'custom' ] ) ) { 
			foreach ($details['custom'] as $attr => $value ) {
				$custom .= $attr. '="'. $value .'" '; 
			}

		}

		echo '<a href="'. $action_url.'" '.$id.' '.$class.' '.$target.' '.$custom.' >'. $details[ 'label' ].'</a>'; 
	} 
?>
</div>