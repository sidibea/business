<?php

/**
 * Product Attribute 
 *
 * This file is used to load the product attribute 
 *
 * @link       http://www.wcvendors.com
 * @since      1.3.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials
 */ 
?>

<div data-index_value="<?php echo $i; ?>" data-label="<?php echo esc_html( $attribute_label ); ?>" data-taxonomy="<?php echo esc_attr( $taxonomy ); ?>" class="woocommerce_attribute wcv-metabox closed <?php echo esc_attr( implode( ' ', $metabox_class ) ); ?>" rel="<?php echo $position; ?>" >
		<h5>
			<strong class="attribute_name"><?php echo esc_html( $attribute_label ); ?></strong>
			<a href="#" class="remove_row delete" style="float:right;"><?php _e( 'Remove', 'wcvendors-pro' ); ?></a>
			<i class='fa fa-angle-down'></i>
		</h5>
		
		<div class="wcv_attribute_data wcv-metabox-content">

			<div class="wcv-column-group wcv-horizontal-gutters" style="border-bottom: 1px solid #ccc; margin-bottom: 5px;">
					<div class="all-30"> 
						<div class="control-group" data-index_value="<?php echo $i; ?>">
								<?php if ( $attribute['is_taxonomy'] ) : ?>
								<input type="hidden" name="attribute_names[<?php echo $i; ?>]" value="<?php echo esc_attr( $taxonomy ); ?>" />
							<?php else : ?>
								<input type="text" class="attribute_name" name="attribute_names[<?php echo $i; ?>]" value="<?php echo esc_attr( $attribute['name'] ); ?>" />
							<?php endif; ?>

							<input type="hidden" name="attribute_position[<?php echo $i; ?>]" class="attribute_position" value="<?php echo esc_attr( $position ); ?>" id="attribute_position_<?php echo $i; ?>" />
							<input type="hidden" name="attribute_is_taxonomy[<?php echo $i; ?>]" value="<?php echo $attribute['is_taxonomy'] ? 1 : 0; ?>" />
							
							<ul class="control unstyled inline">
								<li>
									<input type="checkbox" class="checkbox" <?php checked( $attribute['is_visible'], 1 ); ?> name="attribute_visibility[<?php echo $i; ?>]" value="1" /> 
									<label><?php _e( 'Visible on the product page', 'wcvendors-pro' ); ?></label>
								</li>
								<li class="enable_variation show_if_variable">
									<input type="checkbox" class="checkbox wcv_variation_checkbox" <?php checked( $attribute['is_variation'], 1 ); ?> id="attribute_variation_<?php echo $i; ?>" name="attribute_variation[<?php echo $i; ?>]" value="1" /> 
									<label><?php _e( 'Used for variations', 'wcvendors-pro' ); ?></label>
								</li>
							</ul>
						</div>
					</div>

				<div class="all-70">
					<div class="control-group">
						<label><?php _e( 'Value(s)', 'wcvendors-pro' ); ?>:</label>
						<div class="control" data-index_value="<?php echo $i; ?>" data-taxonomy="<?php echo $taxonomy; ?>" data-label="<?php echo esc_html( $attribute_label ) ?>">
							<?php if ( $attribute['is_taxonomy'] ) : ?>
										<?php if ( 'select' === $attribute_taxonomy->attribute_type ) : ?>

											<select multiple="multiple" id="attribute_values_<?php echo $i; ?>" data-placeholder="<?php esc_attr_e( 'Select terms', 'wcvendors-pro' ); ?>" class="attribute_values select2" name="attribute_values[<?php echo $i; ?>][]" style="width: 100%" >
												<?php
												$all_terms = get_terms( $taxonomy, 'orderby=name&hide_empty=0' );
												if ( $all_terms ) {
													foreach ( $all_terms as $term ) {
														echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( has_term( absint( $term->term_id ), $taxonomy, $post_id ), true, false ) . '>' . $term->name . '</option>';
													}
												}
												?>
											</select>
											<button class="button select_all_attributes"><?php _e( 'Select all', 'wcvendors-pro' ); ?></button>
											<button class="button select_no_attributes"><?php _e( 'Select none', 'wcvendors-pro' ); ?></button>

											<?php if ( $form_caps['attribute_cap'] ) : ?> 

											<button class="button add_new_attribute" data-selectid="attribute_values_<?php echo $i; ?>" style="float:right;"><?php _e( 'Add new', 'wcvendors-pro' ); ?></button>

											<?php endif; ?>

										<?php elseif ( 'text' == $attribute_taxonomy->attribute_type ) : ?>

											<input type="text" id="attribute_values_<?php echo $i; ?>" name="attribute_values[<?php echo $i; ?>]" value="<?php echo esc_attr( implode( ' ' . WC_DELIMITER . ' ', wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'names' ) ) ) );?>" placeholder="<?php echo esc_attr( sprintf( __( '"%s" separate terms', 'wcvendors-pro' ), WC_DELIMITER ) ); ?>" class="attribute_values" />

										<?php endif; ?>

							<?php endif; ?>
							</div>
					</div> <!-- end control group -->
				</div>

		
			</div>
			
		</div>
		<hr style="clear: both;" />
	</div>