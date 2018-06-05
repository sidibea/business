<?php

/**
 * Variation default attributes loaded via ajax
 *
 * This file is used to load the product variations  
 *
 * @link       http://www.wcvendors.com
 * @since      1.3.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/forms/partials
 */ 
?>

<div class="variations-defaults">
	<div class="wcv-cols-group">
		<div class="all-20">
			<strong><?php _e( 'Default Form Values', 'wcvendors-pro' ); ?>: <?php __( 'These are the attributes that will be pre-selected on the frontend.', 'wcvendors-pro' ); ?></strong>
		</div>
		<div class="variation_default_values all-80">
		<?php

			$attributes = WCVendors_Pro_Utils::array_sort( $attributes, 'position' ); 

			foreach ( $attributes as $attribute ) {

				echo '<select data-taxonomy="'.sanitize_title( $attribute['name'] ).'" name="default_attribute_' . sanitize_title( $attribute['name'] ) . '" data-current="" class="default_attribute '.sanitize_title( $attribute['name'] ).'"><option value="">' . __( 'No default', 'wcvendors-pro' ) . ' ' . esc_html( wc_attribute_label( $attribute['name'] ) ) . '&hellip;</option>';

				foreach ( $attribute[ 'values' ] as $key => $value ) {
								echo '<option value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>';

				}

				echo '</select>';
			}
		?>
		</div>
		<div class="all-100"> 
			<p class="tip" style="float: left"><?php _e( 'These are the attributes that will be pre-selected on the frontend.', 'wcvendors-pro' ); ?></p>
		</div>
	</div> 
</div>