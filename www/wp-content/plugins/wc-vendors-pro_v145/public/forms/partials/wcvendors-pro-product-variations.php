<?php

/**
 * Product Variations  
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

<div id="wcv_variable_product_options" class="wcv-metaboxes-wrapper">

	<div id="variable_product_options_inner">
		<div class="wcv-cols-group wcv-horizontal-gutters variations_notice"> 
			<div class="all-100"> 
				<div id="wcv-attr-message" class="inline notice woocommerce-message">
					<?php _e( 'Before you can add a variation you need to add some variation attributes on the <strong>Attributes</strong> tab.', 'wcvendors-pro' ); ?>
				</div>
			</div>
		</div>
		<div class="wcv-cols-group wcv-horizontal-gutters variation_options variations-toolbar"> 
			<div class="all-75"> 
					<select id="field_to_edit" class="variation_actions">
						<option data-global="true" value="add_variation"><?php _e( 'Add variation', 'wcvendors-pro' ); ?></option>
						<option data-global="true" value="link_all_variations"><?php _e( 'Create variations from all attributes', 'wcvendors-pro' ); ?></option>
						<option value="delete_all"><?php _e( 'Delete all variations', 'wcvendors-pro' ); ?></option>
						<optgroup label="<?php esc_attr_e( 'Status', 'wcvendors-pro' ); ?>">
							<option value="toggle_variable_enabled"><?php _e( 'Toggle &quot;Enabled&quot;', 'wcvendors-pro' ); ?></option>
							<option value="toggle_variable_is_downloadable"><?php _e( 'Toggle &quot;Downloadable&quot;', 'wcvendors-pro' ); ?></option>
							<option value="toggle_variable_is_virtual"><?php _e( 'Toggle &quot;Virtual&quot;', 'wcvendors-pro' ); ?></option>
						</optgroup>
						<optgroup label="<?php esc_attr_e( 'Pricing', 'wcvendors-pro' ); ?>">
							<option value="variable_regular_price"><?php _e( 'Set regular prices', 'wcvendors-pro' ); ?></option>
							<option value="variable_regular_price_increase"><?php _e( 'Increase regular prices (fixed amount or percentage)', 'wcvendors-pro' ); ?></option>
							<option value="variable_regular_price_decrease"><?php _e( 'Decrease regular prices (fixed amount or percentage)', 'wcvendors-pro' ); ?></option>
							<option value="variable_sale_price"><?php _e( 'Set sale prices', 'wcvendors-pro' ); ?></option>
							<option value="variable_sale_price_increase"><?php _e( 'Increase sale prices (fixed amount or percentage)', 'wcvendors-pro' ); ?></option>
							<option value="variable_sale_price_decrease"><?php _e( 'Decrease sale prices (fixed amount or percentage)', 'wcvendors-pro' ); ?></option>
							<option value="variable_sale_schedule"><?php _e( 'Set scheduled sale dates', 'wcvendors-pro' ); ?></option>
						</optgroup>
						<optgroup label="<?php esc_attr_e( 'Inventory', 'wcvendors-pro' ); ?>">
							<option value="toggle_variable_manage_stock"><?php _e( 'Toggle &quot;Manage stock&quot;', 'wcvendors-pro' ); ?></option>
							<option value="variable_stock"><?php _e( 'Stock', 'wcvendors-pro' ); ?></option>
						</optgroup>
						<optgroup label="<?php esc_attr_e( 'Shipping', 'wcvendors-pro' ); ?>">
							<option value="variable_length"><?php _e( 'Length', 'wcvendors-pro' ); ?></option>
							<option value="variable_width"><?php _e( 'Width', 'wcvendors-pro' ); ?></option>
							<option value="variable_height"><?php _e( 'Height', 'wcvendors-pro' ); ?></option>
							<option value="variable_weight"><?php _e( 'Weight', 'wcvendors-pro' ); ?></option>
						</optgroup>
						<optgroup label="<?php esc_attr_e( 'Downloadable products', 'wcvendors-pro' ); ?>">
							<option value="variable_download_limit"><?php _e( 'Download limit', 'wcvendors-pro' ); ?></option>
							<option value="variable_download_expiry"><?php _e( 'Download expiry', 'wcvendors-pro' ); ?></option>
						</optgroup>
						<?php do_action( 'wcv_variable_product_bulk_edit_actions' ); ?>
					</select>
					<a class="button bulk_edit do_variation_action"><?php _e( 'Go', 'wcvendors-pro' ); ?></a>
			</div>

			<div class="all-25 align-right"> 
				<div class="variations-pagenav">
					<span class="displaying-num"><?php printf( _n( '%s item', '%s items', $variations_count, 'wcvendors-pro' ), $variations_count ); ?></span>
					<span class="expand-close">
						(<a href="#" class="expand_all"><?php _e( 'Expand', 'wcvendors-pro' ); ?></a> / <a href="#" class="close_all"><?php _e( 'Close', 'wcvendors-pro' ); ?></a>)
					</span>
				</div>
			</div>
		</div>

		<div class="wcv-cols-group wcv-horizontal-gutters"> 
			<div class="all-100"> 
				<div class="toolbar toolbar-variations-defaults">	
					<div class="variations-defaults">
						<?php if ( $variation_attribute_found ) : ?>
							<div class="wcv-cols-group">
								<div class="all-20">
									<strong><?php _e( 'Default Form Values', 'wcvendors-pro' ); ?>:</strong>
								</div>
								<div class="variation_default_values all-80">
								<?php

									$attributes = WCVendors_Pro_Utils::array_sort( $attributes, 'position' ); 
									$default_attributes = maybe_unserialize( get_post_meta( $post_id, '_default_attributes', true ) );

									foreach ( $attributes as $attribute ) {

										// Only deal with attributes that are variations
										if ( ! $attribute['is_variation'] ) {
											continue;
										}

										// Get current value for variation (if set)
										$variation_selected_value = isset( $default_attributes[ sanitize_title( $attribute['name'] ) ] ) ? $default_attributes[ sanitize_title( $attribute['name'] ) ] : '';

										// Name will be something like attribute_pa_color
										echo '<select data-taxonomy="'.sanitize_title( $attribute['name'] ).'" name="default_attribute_' . sanitize_title( $attribute['name'] ) . '" class="default_attribute ' . sanitize_title( $attribute['name'] ) . '" data-current="' . esc_attr( $variation_selected_value ) . '"><option value="">' . __( 'No default', 'wcvendors-pro' ) . ' ' . esc_html( wc_attribute_label( $attribute['name'] ) ) . '&hellip;</option>';

										// Get terms for attribute taxonomy or value if its a custom attribute
										if ( $attribute['is_taxonomy'] ) {
											$post_terms = wp_get_post_terms( $post_id, $attribute['name'] );

											foreach ( $post_terms as $term ) {
												echo '<option ' . selected( $variation_selected_value, $term->slug, false ) . ' value="' . esc_attr( $term->slug ) . '">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) . '</option>';
											}

										} else {
											$options = wc_get_text_attributes( $attribute['value'] );

											foreach ( $options as $option ) {
												$selected = sanitize_title( $variation_selected_value ) === $variation_selected_value ? selected( $variation_selected_value, sanitize_title( $option ), false ) : selected( $variation_selected_value, $option, false );
												echo '<option ' . $selected . ' value="' . esc_attr( $option ) . '">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) )  . '</option>';
											}

										}

										echo '</select>';
									}
								?>
								</div>
							<div class="all-100"> 
								 <p class="tip"><?php _e( 'These are the attributes that will be pre-selected on the frontend.', 'wcvendors-pro' ); ?></p>
							</div>
							</div>
						<?php endif; ?>
					</div> 
				</div>
			</div>
		</div>				

		<div class="wcv-cols-group wcv-horizontal-gutters"> 
			<div class="all-100"> 
				<div class="wcv_variations wcv-metaboxes" data-attributes="<?php
					// esc_attr does not double encode - htmlspecialchars does
					echo htmlspecialchars( json_encode( $attributes ) );
				?>" data-total="<?php echo $variations_count; ?>" data-page="1" data-edited="false">
				<?php 
					if ( !empty( $post_id ) ) { 
						WCVendors_Pro_Product_Controller::load_variations( $post_id ); 
					}
				?>
				</div>
			</div>
		</div>
		<div class="wcv-cols-group wcv-horizontal-gutters variations-toolbar"> 
			<div class="all-100 align-right">
				<div class="toolbar">
					<div class="variations-pagenav">
						<span class="displaying-num"><?php printf( _n( '%s item', '%s items', $variations_count, 'wcvendors-pro' ), $variations_count ); ?></span>
						<span class="expand-close">
							(<a href="#" class="expand_all"><?php _e( 'Expand', 'wcvendors-pro' ); ?></a> / <a href="#" class="close_all"><?php _e( 'Close', 'wcvendors-pro' ); ?></a>)
						</span>
					</div>
				</div> <!-- end .toolbar --> 
			</div>
		</div> 
		<input type="hidden" id="wcv_parent_object" value="" />
		<input type="hidden" id="wcv_deleted_variations" name="wcv_deleted_variations" value="" data-variations="" />
	</div>
</div>