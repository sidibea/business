<?php

/**
 * Product Attributes 
 *
 * This file is used to load the overall product attributes
 *
 * @link       http://www.wcvendors.com
 * @since      1.3.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/product
 */ 
?>

<div class="wcv-column-group wcv-horizontal-gutters">
	<div class="all-80">
		<div class="control-group">
			<div class="all-100">
				<select name="attribute_taxonomy" class="attribute_taxonomy">
						<option value=""><?php echo apply_filters( 'wcv_custom_attribute_default_label', __( 'Select an attribute' , 'wcvendors-pro' ) ); ?></option>
						<?php
							global $wc_product_attributes;

							// Array of defined attribute taxonomies
							$attribute_taxonomies = wc_get_attribute_taxonomies();

							if ( $attribute_taxonomies ) {
								foreach ( $attribute_taxonomies as $tax ) {
									if ( in_array( $tax->attribute_id, explode( ',', self::$hide_attributes_list ) ) ) continue;  
									$attribute_taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
									$label = $tax->attribute_label ? $tax->attribute_label : $tax->attribute_name;
									echo '<option value="' . esc_attr( $attribute_taxonomy_name ) . '">' . esc_html( $label ) . '</option>';
								}
							}
						?>
				</select>

				<button type="button" class="button add_attribute"><?php _e( 'Add', 'wcvendors-pro' ); ?></button>
			</div>
		</div>
	</div>

	<div class="all-20 align-right"> 
		<span class="expand-close ">
			<a href="#" class="expand_all"><?php _e( 'Expand', 'wcvendors-pro' ); ?></a> / <a href="#" class="close_all"><?php _e( 'Close', 'wcvendors-pro' ); ?></a>
		</span>
	</div>
</div>

<div class="product_attributes">
	<?php
		// Product attributes - taxonomies and custom, ordered, with visibility and variation attributes set
		$attributes           = maybe_unserialize( get_post_meta( $post_id, '_product_attributes', true ) );

		// Output All Set Attributes
		if ( ! empty( $attributes ) ) {
			$attribute_keys  = array_keys( $attributes );
			$attribute_total = sizeof( $attribute_keys );

			for ( $i = 0; $i < $attribute_total; $i ++ ) {
				$attribute     = $attributes[ $attribute_keys[ $i ] ];
				$position      = empty( $attribute['position'] ) ? 0 : absint( $attribute['position'] );
				$taxonomy      = '';
				$metabox_class = array();

				if ( $attribute['is_taxonomy'] ) {
					$taxonomy = $attribute['name'];

					if ( ! taxonomy_exists( $taxonomy ) ) {
						continue;
					}

					$attribute_taxonomy = $wc_product_attributes[ $taxonomy ];
					$metabox_class[]    = 'taxonomy';
					$metabox_class[]    = $taxonomy;
					$attribute_label    = wc_attribute_label( $taxonomy );
				} else {
					$attribute_label    = apply_filters( 'woocommerce_attribute_label', $attribute['name'], $attribute['name'] );
				}
				
					include( 'wcvendors-pro-product-attribute.php' ); 
			} // end for 

		} // end if  
	?>
</div>


<div class="wcv-column-group wcv-horizontal-gutters">
	<div class="all-100 align-right">
		<span class="expand-close">
			<a href="#" class="expand_all"><?php _e( 'Expand', 'wcvendors-pro' ); ?></a> / <a href="#" class="close_all"><?php _e( 'Close', 'wcvendors-pro' ); ?></a>
		</span>
	</div>
</div>

<input type="hidden" id="wcv-variation-attributes" data-variation_attr="" />

<div class="clear"></div>