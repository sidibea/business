<?php

/**
 * Order Table Main Actions 
 *
 * This file is used to add the table actions before and after a table
 *
 * @link       http://www.wcvendors.com
 * @since      1.3.7
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/product
 */ 
?>

<div class="wcv_dashboard_table_header wcv_actions wcv-cols-group horizontal-gutters wcv-order-header"> 
	<div class="all-50">
		<form method="post" action="" class="wcv-form"> 
		     <?php 
		    

				// Start Date 
				WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_order_start_date_input', array( 
					'id' 			=> '_wcv_order_start_date_input', 
					'label' 		=> __( 'Start Date', 'wcvendors-pro' ), 
					'class'			=> 'wcv-datepicker no_limit', 
					'value' 		=> date("Y-m-d", $this->start_date), 
					'placeholder'	=> 'YYYY-MM-DD',  
					'wrapper_start' 	=> '<div class="all-66 tiny-50"><div class="wcv-cols-group wcv-horizontal-gutters"><div class="all-50 tiny-100">',
					'wrapper_end' 		=> '</div>', 
					'custom_attributes' => array(
						'maxlenth' 	=> '10', 
						'pattern' 	=> '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])'
						),
					) )
				);

				// End Date 
				WCVendors_Pro_Form_Helper::input( apply_filters( 'wcv_order_end_date_input', array( 
					'id' 			=> '_wcv_order_end_date_input', 
					'label' 		=> __( 'End Date', 'wcvendors-pro' ), 
					'class'			=> 'wcv-datepicker no_limit', 
					'value' 		=> date("Y-m-d", $this->end_date ), 
					'placeholder'	=> 'YYYY-MM-DD',  
					'wrapper_start' 	=> '<div class="all-50 tiny-100">',
					'wrapper_end' 		=> '</div></div></div>', 
					'custom_attributes' => array(
						'maxlenth' 	=> '10', 
						'pattern' 	=> '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])'
						),
					) )
				);

				// Update Button 
				WCVendors_Pro_Form_helper::submit( apply_filters( 'wcv_order_update_button', array( 
				 	'id' 		=> 'update_button', 
				 	'value' 	=> __( 'Update', 'wcvendors-pro' ), 
				 	'class'		=> 'expand', 
				 	'wrapper_start' 	=> '<div class="all-33"><div class="control-group"><div class="control"><label>&nbsp;&nbsp;</label>',
					'wrapper_end' 		=> '</div></div></div>', 
				 	) )
				 ); 

				wp_nonce_field( 'wcv-order-date-update', 'wcv_order_date_update' );	
			?>
		</form>
	</div>

	<?php if ( $can_export_csv ) : ?>

	<?php $export_btn_class = apply_filters( 'wcv_order_export_btn_class', '' ); ?>

	<div class="all-50 align-right">
		<br />
		<a href="<?php echo $add_url; ?>" class="wcv-button button <?php echo $export_btn_class; ?>"><?php echo __( 'Export Orders', 'wcvendors-pro' ); ?></a>
	</div>

	<?php endif; ?>
	
</div>