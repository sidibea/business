<?php

/**
 * Form Helper Class
 *
 * Defines relevant static methods for generating form elements for public facing forms. 
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public
 * @author     Jamie Madden <support@wcvendors.com>
 */
class WCVendors_Pro_Form_Helper {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wcvendors_pro    The ID of this plugin.
	 */
	private $wcvendors_pro;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $wcvendors_pro     	The name of the plugin.
	 * @param    string    $version    			The version of this plugin.
	 */
	public function __construct( $wcvendors_pro, $version ) {

		$this->wcvendors_pro = $wcvendors_pro;
		$this->version = $version;

	}


	/**
	 * Create an input field with label 
	 *
	 * @since      1.0.0
	 * @param      array     $field      Array defining all field attributes 
	 * @Todo add filters to allow the field to be hooked into this should not echo html but return it. 
	 */
	public static function input( $field ) {

		$allow_markup 					= WC_Vendors::$pv_options->get_option( 'allow_form_markup' ); 

		$post_id 						= isset( $field['post_id'] ) ? $field[ 'post_id' ] : 0; 
		$field['placeholder']   		= isset( $field['placeholder'] ) ? $field['placeholder'] : '';
		$field['class']         		= isset( $field['class'] ) ? $field['class'] : '';
		$field['style']         		= isset( $field['style'] ) ? $field['style'] : '';
		$field['label']         		= isset( $field['label'] ) ? $field['label'] : '&nbsp;';
		$field['wrapper_class'] 		= isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		$field['wrapper_start']			= isset( $field['wrapper_start'] ) ? $field['wrapper_start'] : '';
		$field['wrapper_end']			= isset( $field['wrapper_end'] ) ? $field['wrapper_end'] : '';
		$field['value']        			= isset( $field['value'] ) ? $field['value'] : get_post_meta( $post_id, $field['id'], true );
		$field['cbvalue']       		= isset( $field['cbvalue'] ) ? $field['cbvalue'] : 'yes';
		$field['name']         			= isset( $field['name'] ) ? $field['name'] : $field['id'];
		$field['type']         			= isset( $field['type'] ) ? $field['type'] : 'text';	
		$field[ 'show_label']			= isset( $field['show_label'] ) ? $field['show_label'] : true; 
		$data_type             			= empty( $field['data_type'] ) ? '' : $field['data_type'];
		$html 							= ''; 

		// Strip tags 
		$field['value'] 				= ( $allow_markup ) ? $field['value'] : wp_strip_all_tags( $field['value'] ); 

		// Custom attribute handling
		$custom_attributes = array();

		if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

			foreach ( $field['custom_attributes'] as $attribute => $value ){
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
			}
		}

		// Container wrapper start if defined start & end required otherwise no output is shown 
		if (! empty($field['wrapper_start'] ) && ! empty($field['wrapper_end'] ) ) { 
			echo $field['wrapper_start']; 
		}


		if ( !empty( $field[ 'wrapper_class' ] ) ) { 
				echo '<div class="' . esc_attr( $field['wrapper_class'] ) . '">'; 
		}

		if ( 'hidden' !== $field['type'] ) { 
			echo '<div class="control-group">';
		}
		

		// Change the output slightly for check boxes 
		if ('checkbox' === $field['type'] ) { 	

			echo '<ul class="control unstyled inline" style="padding:0; margin:0;">';
			echo '<li><input type="checkbox" class="' . esc_attr( $field['class'] ) . ' '. esc_attr( $field['wrapper_class'] ) .'" style="' . esc_attr( $field['style'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['cbvalue'] ) . '" ' . checked( $field['value'], $field['cbvalue'], false ) . '  ' . implode( ' ', $custom_attributes ) . '/><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ).'</label></li>';
			echo '</ul>';

		} else { 


				if ( $field['show_label'] ) echo '<label for="' . esc_attr( $field['id'] ) . '" class="' . esc_attr( $field['wrapper_class'] ) . '">' . wp_kses_post( $field['label'] ); echo '</label>';

				$html .= apply_filters('wcv_wp_input_start_' . $field['id'] , $html); 

				echo '<div class="control">';

				echo '<input type="' . esc_attr( $field['type'] ) . '" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" ' . implode( ' ', $custom_attributes ) . ' /> ';
				
				echo '</div>';

				if ( ! empty( $field['description'] ) ) {
						if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
								echo '<p class="tip">'. $field['description'] . '</p>'; 
						}
				}

				$html .= apply_filters('wcv_wp_input_end_' . $field['id'], $html); 

				echo $html; 
										
		}

		if (! empty( $field[ 'wrapper_class' ] ) ) { 
				echo '</div>';
		}

		if ( 'hidden' !== $field['type'] ) { 
			echo '</div>';
		}
		
		

		// container wrapper end if defined 
		if (! empty($field['wrapper_start'] ) && ! empty($field['wrapper_end'] ) ) { 
			echo $field['wrapper_end']; 
		}

	}


	/**
	 * Create select with label 
	 *
	 * @since      1.0.0
	 * @param      array     $field      Array defining all field attributes 
	 * @todo       add filters to allow the field to be hooked into this should not echo html but return it. 
	 */
	public static function select( $field ) {

		$post_id 						= isset( $field['post_id'] ) ? $field[ 'post_id' ] : 0; 
		$field[ 'class' ]         		= isset( $field['class'] ) ? $field['class'] : 'select2';
		$field[ 'style' ]        		= isset( $field['style'] ) ? $field['style'] : '';
		$field[ 'wrapper_class' ] 		= isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		$field[ 'wrapper_start' ]		= isset( $field['wrapper_start'] ) ? $field['wrapper_start'] : '';
		$field[ 'wrapper_end' ]			= isset( $field['wrapper_end'] ) ? $field['wrapper_end'] : '';
		$field[ 'value' ]         		= isset( $field['value'] ) ? $field['value'] : get_post_meta( $post_id, $field['id'], true );
		$field[ 'show_option_none' ]	= isset( $field['show_option_none'] ) ? $field[ 'show_option_none' ] : ''; 
		$field[ 'options' ]				= isset( $field['options'] ) ? $field['options'] : array(); 
		$field[ 'taxonomy_field' ]		= isset( $field[ 'taxonomy_field' ] ) ? $field[ 'taxonomy_field' ] : 'slug'; 
		$field[ 'show_label']			= isset( $field['show_label'] ) ? $field['show_label'] : true; 


		// Custom attribute handling
		$custom_attributes = array();

		if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

			foreach ( $field['custom_attributes'] as $attribute => $value ){

				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
			}
		}

		// Taxonomy drop down 
		// @todo Support nested parent/child attributes 
		if ( isset( $field[ 'taxonomy' ] ) && ( isset( $field[ 'taxonomy_args'] ) && is_array( $field[ 'taxonomy_args' ] ) ) ) { 

			// Default terms args 
			$defaults = array(
			    'orderby'           => 'name', 
			    'order'             => 'ASC',
			    'hide_empty'        => true, 
			    'exclude'           => array(), 
			    'exclude_tree'      => array(), 
			    'include'           => array(),
			    'number'            => '', 
			    'fields'            => 'all', 
			    'slug'              => '',
			    'parent'            => '',
			    'hierarchical'      => true, 
			    'child_of'          => 0, 
			    'get'               => '', 
			    'name__like'        => '',
			    'description__like' => '',
			    'pad_counts'        => false, 
			    'offset'            => '', 
			    'search'            => '', 
			    'cache_domain'      => 'core'
			); 

			// Merge args 
			$args = wp_parse_args( $field[ 'taxonomy_args' ], $defaults ); 

			if ( $args[ 'orderby' ] 	== 'order' ) {
				$args[ 'menu_order' ] 	= 'asc';
				$args[ 'orderby' ]    	= 'name';
			}

			// Get terms for taxonomy 
			$terms = get_terms( $field[ 'taxonomy' ], $args );  

			$options = array(); 

			foreach ( $terms as $term ) {

				$options[ $term->term_id ] = $term->name; 
				
			}

			$field['options'] = $options; 
		}

		// Container wrapper start if defined start & end required otherwise no output is shown 
		if (! empty($field['wrapper_start'] ) && ! empty($field['wrapper_end'] ) ) { 
			echo $field['wrapper_start']; 
		}

		echo '<div class="control-group">';

		if ( $field['show_label'] )  echo '<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ).  '</label>';
			
		echo '<div class="control select">';

		echo '<select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" ' . implode( ' ', $custom_attributes ) . '>';

		if (! empty( $field[ 'show_option_none' ] ) ) { 
			echo '<option value>' . esc_html( $field[ 'show_option_none' ] ) . '</option>';
		}

		foreach ( $field['options'] as $key => $value ) {
			echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
		}

		echo '</select> ';

		if ( ! empty( $field['description'] ) ) {
				if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
						echo '<p class="tip">'. $field['description'] . '</p>'; 
				}
		}

		echo '</div>'; //control 
		echo '</div>'; //control-group

		// container wrapper end if defined 
		if (! empty($field['wrapper_start'] ) && ! empty($field['wrapper_end'] ) ) { 
			echo $field['wrapper_end']; 
		}

	}


	/**
	 * Create select2 with label 
	 *
	 * @since      1.0.0
	 * @param      array     $field      Array defining all field attributes 
	 * @todo       add filters to allow the field to be hooked into this should not echo html but return it. 
	 */
	public static function select2( $field ) {

		$post_id 						= isset( $field['post_id'] ) ? $field[ 'post_id' ] : 0; 
		$field[ 'class' ]         		= isset( $field['class'] ) ? $field['class'] : 'select2';
		$field[ 'style' ]        		= isset( $field['style'] ) ? $field['style'] : '';
		$field[ 'wrapper_class' ] 		= isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		$field[ 'wrapper_start' ]		= isset( $field['wrapper_start'] ) ? $field['wrapper_start'] : '';
		$field[ 'wrapper_end' ]			= isset( $field['wrapper_end'] ) ? $field['wrapper_end'] : '';
		$field[ 'value' ]         		= isset( $field['value'] ) ? $field['value'] : get_post_meta( $post_id, $field['id'], true );
		$field[ 'show_option_none' ]	= isset( $field['show_option_none'] ) ? $field[ 'show_option_none' ] : ''; 
		$field[ 'options' ]				= isset( $field['options'] ) ? $field['options'] : array(); 

		// Custom attribute handling
		$custom_attributes = array();

		if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

			foreach ( $field['custom_attributes'] as $attribute => $value ){

				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
			}
		}

		// Taxonomy drop down 
		if ( isset( $field[ 'taxonomy' ] ) && ( isset( $field[ 'taxonomy_args'] ) && is_array( $field[ 'taxonomy_args' ] ) ) ) { 

			$existing_terms = wp_get_post_terms( $post_id, $field[ 'taxonomy' ], array( 'fields' => 'all') );

			$selected = array(); 
			if ( !empty( $existing_terms ) ) { 
				foreach ($existing_terms as $existing_term) {
					$selected[] = $existing_term->term_id; 
				}
			}

			// Default terms args 
			$defaults            = apply_filters( 'wcv_select2_args_' . $field[ 'taxonomy' ], array(
				'pad_counts'         => 1,
				'show_count'       	 => 0,
				'hierarchical'       => 1,
				'hide_empty'         => 1,
				'fields'			 => 'all', 
				'show_uncategorized' => 1,
				'orderby'            => 'name',
				'selected'           => $selected,
				'menu_order'         => false, 
				'value'				 => 'id'
			) );

			// Merge args 
			$args = wp_parse_args( $field[ 'taxonomy_args' ], $defaults ); 

			if ( $args['orderby'] == 'order' ) {
				$args['menu_order'] = 'asc';
				$args['orderby']    = 'name';
			}

			// Get terms for taxonomy 
			$terms = get_terms( $field[ 'taxonomy' ], $args );  

			if ( ! $terms ) {
				return;
			}

			$field['options'] = wcv_walk_category_dropdown_tree( $terms, 0, $args );

		}

		// Container wrapper start if defined start & end required otherwise no output is shown 
		if ( !empty($field['wrapper_start'] ) && ! empty($field['wrapper_end'] ) ) { 
			echo $field['wrapper_start']; 
		}

		echo '<div class="control-group">';

		echo '<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

		echo '<div class="control select">';

				if ( ! empty( $field['description'] ) ) {
						if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
								echo '<span data-tooltip data-tip="' . esc_attr( $field['description'] ) . '" aria-haspopup="true" class="has-tip right" title="'.__( esc_attr( $field['description'] ) , 'wcvendors-pro' ).'"><i class="fa fa-info-circle"></i></span>';
						}
				}

		echo '<select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" ' . implode( ' ', $custom_attributes ) . '>';

		if (! empty( $field[ 'show_option_none' ] ) ) { 
			echo '<option value>' . esc_html( $field[ 'show_option_none' ] ) . '</option>';
		}

		// If taxonomy provided then display the custom walked drop down, otherwise iterate over provided options 
		if ( isset( $field[ 'taxonomy' ] ) && ( isset( $field[ 'taxonomy_args'] ) && is_array( $field[ 'taxonomy_args' ] ) ) ) { 
			echo $field[ 'options' ]; 
		} else { 
			foreach ( $field[ 'options' ] as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';
			}
		} 

		echo '</select> ';

		echo '</div>'; //control 
		echo '</div>'; //control-group
		

		// container wrapper end if defined 
		if (! empty($field['wrapper_start'] ) && ! empty($field['wrapper_end'] ) ) { 
			echo $field['wrapper_end']; 
		}

	}


	/**
	 * Create select2 with label 
	 *
	 * @since      1.0.0
	 * @param      array     $field      Array defining all field attributes 
	 * @todo       add filters to allow the field to be hooked into this should not echo html but return it. 
	 */
	public static function nested_select( $field ) {

		$post_id 						= isset( $field['post_id'] ) ? $field[ 'post_id' ] : 0; 
		$field[ 'class' ]         		= isset( $field['class'] ) ? $field['class'] : 'select2';
		$field[ 'style' ]        		= isset( $field['style'] ) ? $field['style'] : '';
		$field[ 'wrapper_class' ] 		= isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		$field[ 'wrapper_start' ]		= isset( $field['wrapper_start'] ) ? $field['wrapper_start'] : '';
		$field[ 'wrapper_end' ]			= isset( $field['wrapper_end'] ) ? $field['wrapper_end'] : '';
		$field[ 'value' ]         		= isset( $field['value'] ) ? $field['value'] : get_post_meta( $post_id, $field['id'], true );
		$field[ 'show_option_none' ]	= isset( $field['show_option_none'] ) ? $field[ 'show_option_none' ] : ''; 
		$field[ 'options' ]				= isset( $field['options'] ) ? $field['options'] : array(); 
		$field[ 'value_type' ]			= isset( $field['value_type'] ) ? $field['value_type'] : 'value'; 

		// Custom attribute handling
		$custom_attributes = array();

		if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

			foreach ( $field['custom_attributes'] as $attribute => $value ){

				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
			}
		}

		// Container wrapper start if defined start & end required otherwise no output is shown 
		if ( !empty($field['wrapper_start'] ) && ! empty($field['wrapper_end'] ) ) { 
			echo $field['wrapper_start']; 
		}

		echo '<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] );

				if ( ! empty( $field['description'] ) ) {
						if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
								echo '<span data-tooltip data-tip="' . esc_attr( $field['description'] ) . '" aria-haspopup="true" class="has-tip right" title="'.__( esc_attr( $field['description'] ) , 'wcvendors-pro' ).'"><i class="fa fa-info-circle"></i></span>';
						}
				}

		echo '<select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" ' . implode( ' ', $custom_attributes ) . '>';

		if (! empty( $field[ 'show_option_none' ] ) ) { 
			echo '<option value>' . esc_html( $field[ 'show_option_none' ] ) . '</option>';
		}

		foreach ( $field['options'] as $option_group => $option ) {

			echo '<optgroup label="' . $option_group . '">';
			
			foreach ( $option as $key => $value ) {
				$output = ( $field[ 'value_type' ] == 'value' ) ? $value : $key; 
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $field[ 'value' ] ), esc_attr( $key ), false ) . '>' . esc_html( $output ) . '</option>';
			} 

			echo '</optgroup>'; 

		} 
	
		echo '</select> ';
		echo '</label>';

		// container wrapper end if defined 
		if (! empty($field['wrapper_start'] ) && ! empty($field['wrapper_end'] ) ) { 
			echo $field['wrapper_end']; 
		}

	}

	/**
	 * Create a textarea with label 
	 *
	 * @since      1.0.0
	 * @param      array     $field      Array defining all field attributes 
	 * @todo       add filters to allow the field to be hooked into this should not echo html but return it. 
	 */
	public static function textarea( $field ) {

		$allow_markup 					= WC_Vendors::$pv_options->get_option( 'allow_form_markup' ); 
		$post_id 						= isset( $field['post_id'] ) ? $field[ 'post_id' ] : 0; 
		$field['placeholder']   		= isset( $field['placeholder'] ) ? $field['placeholder'] : '';
		$field[ 'class' ]         		= isset( $field['class'] ) ? $field['class'] : 'select short';
		$field[ 'rows' ]         		= isset( $field['rows'] ) ? $field['rows'] : 5;
		$field[ 'cols' ]         		= isset( $field['cols'] ) ? $field['cols'] : 5;
		$field[ 'style' ]        		= isset( $field['style'] ) ? $field['style'] : '';
		$field[ 'wrapper_start' ]		= isset( $field['wrapper_start'] ) ? $field['wrapper_start'] : '';
		$field[ 'wrapper_end' ]			= isset( $field['wrapper_end'] ) ? $field['wrapper_end'] : '';
		$field[ 'value' ]         		= isset( $field['value'] ) ? $field['value'] : get_post_meta( $post_id, $field['id'], true );

		// Strip tags 
		$field['value'] 				= ( $allow_markup ) ? $field['value'] : wp_strip_all_tags( $field['value'] ); 

		// Custom attribute handling
		$custom_attributes = array();

		if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

			foreach ( $field['custom_attributes'] as $attribute => $value ){
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
			}
		}

		// Container wrapper start if defined start & end required otherwise no output is shown 
		if (! empty($field['wrapper_start'] ) && ! empty($field['wrapper_end'] ) ) { 
			echo $field['wrapper_start']; 
		}
		
		echo '<div class="control-group">';
		echo '<div class="control">';

		echo '<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

		echo '<textarea class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '"  name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" rows="'.$field['rows'].'" cols="'.$field['cols'].'" ' . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $field['value'] ) . '</textarea> ';
		
		echo '</div>';

		if ( ! empty( $field['description'] ) ) {
				if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
						echo '<p class="tip">'. $field['description'] . '</p>'; 
				}
		}

		echo '</div>';

		// container wrapper end if defined 
		if (! empty($field['wrapper_start'] ) && ! empty($field['wrapper_end'] ) ) { 
			echo $field['wrapper_end']; 
		}
	}


	/**
	 * Output a woocommerce attribute select 
	 *
	 * @since      1.0.0
	 * @param      array     $field      Array defining all field attributes 
	 * @todo       add filters to allow the field to be hooked into this should not echo html but return it. 
	 */
	public static function attribute( $post_id ) { 

		$basic_options 		= (array) WC_Vendors::$pv_options->get_option( 'hide_product_basic' ); 

		if ( ! $basic_options[ 'attributes' ] ) { 

			// Array of defined attribute taxonomies
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			// If there are any defined attributes display them 
			if ( !empty( $attribute_taxonomies ) ) { 

				$i = 0; 
				// Get any set attributes for the product 
				$attributes  = maybe_unserialize( get_post_meta( $post_id, '_product_attributes', true ) );

				foreach ($attribute_taxonomies as $product_attribute) {

					$current_attribute = '';
					$is_variation = 'no';

					// If the attributes aren't empty, extract the attribute value for the current product 
					// Does not support multi select at this time
					// TODO:  Support select2 and multiple attributes 
					if ( ! empty( $attributes ) && array_key_exists( wc_attribute_taxonomy_name( $product_attribute->attribute_name ), $attributes ) ) { 
						// get all terms 
						$current_attribute = wp_get_post_terms( $post_id, wc_attribute_taxonomy_name( $product_attribute->attribute_name ) );
						$is_variation = $attributes[ wc_attribute_taxonomy_name($product_attribute->attribute_name) ]['is_variation'] ? 'yes' : 'no' ; 
						$current_attribute = reset ( $current_attribute ); 
						$current_attribute = $current_attribute->slug; 

					}
					
					// Output attribute select 
					self::select( array( 
						'id' 				=> 'attribute_values[' . $i . '][]', 
						'post_id'			=> $post_id, 
						'label' 			=> ucfirst( wc_attribute_taxonomy_name( $product_attribute->attribute_name ) ),
						'value' 			=> $current_attribute, 
						'show_option_none' => __( 'Select a ', 'wcvendors-pro' ) . ucfirst( $product_attribute->attribute_name ),
						'taxonomy'			=> wc_attribute_taxonomy_name( $product_attribute->attribute_name ), 
						'taxonomy_args'		=> array( 
												'hide_empty'	=> 0, 
												'orderby'		=> $product_attribute->attribute_orderby,
											), 
						)
					);

					// Output attribute name hidden 
					self::input( array( 
										'post_id'				=> $post_id, 
										'id' 					=> 'attribute_names['.$i.']', 
										'type' 					=> 'hidden', 
										'show_label'			=> false, 
										'value'					=> wc_attribute_taxonomy_name( $product_attribute->attribute_name ), 
										)	
					);
					$i++; 
				}
			}

			// Support other plugins hooking into attributes 
			// Not sure if this will work ? 
			do_action( 'woocommerce_product_options_attributes' );

		} 

	} //attribute()


	/**
	 * Output a the product images and hook into media uploader on front end
	 *
	 * @since      1.1.3
	 * @param      int     $post_id      the post id for the files being uploaded 
	 * @todo       add filters to allow the field to be hooked into this should not echo html but return it. 
	 */
	public static function product_media_uploader( $post_id ) { 

		$media_options 		= (array) WC_Vendors::$pv_options->get_option( 'hide_product_media' ); 

		if ( ! $media_options[ 'featured' ]  ) {

			echo '<div class="all-33 small-100 tiny-100">'; 

			echo '<h6>'.__('Featured Image', 'wcvendors-pro').'</h6>';
			$post_thumb 	= has_post_thumbnail( $post_id ); 

		  	echo '<div class="wcv-featuredimg" data-title="'.__('Select or Upload a Feature Image', 'wcvendors-pro').'" data-button_text="'.__('Set Product Feature Image', 'wcvendors-pro').'">'; 
		    if ( $post_thumb ) { 
		    	$image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id), array(150,150) ); 
		    	echo '<img src="'.$image_attributes[0].'" width="'.$image_attributes[1].'" height="'.$image_attributes[2].'">'; 
		    }
		    echo '</div>'; 
		   	echo '<input type="hidden" id="_featured_image_id" name="_featured_image_id" value="'.( $post_thumb ? get_post_thumbnail_id( $post_id) : '' ). '" />'; 


		  	echo '<a class="wcv-media-uploader-featured-add ' . ( $post_thumb ? 'hidden' : '' ) . '" href="#" >'.__('Set featured image', 'wcvendors-pro').'</a><br />'; 
		  	echo '<a class="wcv-media-uploader-featured-delete ' . ( !$post_thumb ? 'hidden' : '' )  . '" href="#" >'.__('Remove featured image', 'wcvendors-pro').'</a><br />'; 

		  	echo '<span class="wcv_featured_image_msg"></span>'; 

		  	echo '</div>'; 

		  	if ( ! $media_options[ 'gallery' ]  ) {

		  		if ( metadata_exists( 'post', $post_id, '_product_image_gallery' ) ) {
					$product_image_gallery = get_post_meta( $post_id, '_product_image_gallery', true );
				} else {
					// Backwards compat
					$attachment_ids = get_posts( 'post_parent=' . $post_id . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&meta_key=_woocommerce_exclude_image&meta_value=0' );
					$attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
					$product_image_gallery = implode( ',', $attachment_ids );
				}

				// Output the image gallery if there are any images. 
			 	$attachment_ids = array_filter( explode( ',', $product_image_gallery ) );
			 	$gallery_options = apply_filters( 'wcv_product_gallery_options', array( 
			 			'max_upload' => 4, 
			 			'notice' => __( 'You have reached the maximum number of gallery images.', 'wcvendors-pro' )
			 		)
			 	); 

			 	echo '<div class="all-66 small-100 tiny-100" >'; 

			 	echo '<h6>'.__('Gallery', 'wcvendors-pro').'</h6>';
			   	
			   	echo '<div id="product_images_container" data-gallery_max_upload="'. $gallery_options[ 'max_upload' ] .'" data-gallery_max_notice="'.$gallery_options[ 'notice' ].'">'; 
			   	echo '<ul class="product_images inline">';
			   	
			    if ( sizeof( $attachment_ids ) > 0 ) {   	

					foreach( $attachment_ids as $attachment_id ) {

						echo '<li class="wcv-gallery-image" data-attachment_id="' . $attachment_id . '">'; 
						echo wp_get_attachment_image( $attachment_id, array(150,150) );
						echo '<ul class="actions">'; 
						echo '<li><a href="#" class="delete" title="delete"><i class="fa fa-times"></i></a></li>';
						echo '</ul>';
						echo '</li>'; 

					}

				}
				echo '</ul>'; 
				echo '<input type="hidden" id="product_image_gallery" name="product_image_gallery" value="'. ( ( sizeof( $attachment_ids ) > 0 ) ? $product_image_gallery : '' ). '">'; 
				echo '</div>'; 
				echo '<p class="wcv-media-uploader-gallery"><a href="#" data-choose="' .__( 'Add Images to Product Gallery', 'wcvendors-pro'). '" data-update="' .__( 'Add to gallery', 'wcvendors-pro'). '" data-delete="Delete image" data-text="Delete">' .__( 'Add product gallery images', 'wcvendors-pro'). '</a></p>'; 

				echo '<span class="wcv_gallery_msg"></span>'; 

				echo '</div>'; 

			} 

			echo '<div class="all-100"></div>'; 

		} 

	} // media_uploader () 

	/**
	 * Output a file upload link 
	 *
	 * @since      1.0.0
	 * @param      array     $field      file uploader arguments 
	 * @todo       add filters to allow the field to be hooked into this should not echo html but return it. 
	 */
	public static function file_uploader( $field ) { 

		$field[ 'header_text' ]			= isset( $field[ 'header_text' ] ) 		? $field[ 'header_text' ]	 	: __('Image', 'wcvendors-pro' ); 
		$field[ 'add_text' ] 			= isset( $field[ 'add_text' ] ) 		? $field[ 'add_text' ] 			: __('Add Image', 'wcvendors-pro' ); 
		$field[ 'remove_text' ]			= isset( $field[ 'remove_text' ] ) 		? $field[ 'remove_text' ] 		: __('Remove Image', 'wcvendors-pro' ); 
		$field[ 'image_meta_key' ] 		= isset( $field[ 'image_meta_key' ] ) 	? $field[ 'image_meta_key' ] 	: '_wcv_image_id';  
		$field[ 'save_button' ]			= isset( $field[ 'save_button' ] ) 		? $field[ 'save_button' ] 		: __('Add Image', 'wcvendors-pro' ); 
		$field[ 'window_title' ]		= isset( $field[ 'window_title' ] ) 	? $field[ 'window_title' ] 		: __('Select an Image', 'wcvendors-pro' ); 
		$field[ 'value' ]				= isset( $field[ 'value' ] ) 			? $field[ 'value' ] 			: 0; 
		$field[ 'size' ]				= isset( $field[ 'size' ] ) 			? $field[ 'size' ] 				: 'full'; 
		$field[ 'class' ]         		= isset( $field[ 'class'] ) 			? $field[ 'class' ] 			: ''; 
		$field[ 'wrapper_start' ]		= isset( $field[ 'wrapper_start' ] ) 	? $field[ 'wrapper_start' ] 	: '';
		$field[ 'wrapper_end' ]			= isset( $field[ 'wrapper_end' ] ) 		? $field[ 'wrapper_end' ] 		: '';

		// Get the image src
		$image_src = wp_get_attachment_image_src( $field[ 'value' ], $field[ 'size' ] );

		// see if the array is valid
		$has_image = is_array( $image_src );

		// Container wrapper start if defined start & end required otherwise no output is shown 
		if (! empty($field['wrapper_start'] ) && ! empty($field['wrapper_end'] ) ) { 
			echo $field['wrapper_start']; 
		}
 
		echo '<div class="wcv-file-uploader'. $field[ 'image_meta_key' ] .' '. $field[ 'class' ] .'">'; 

		if ( $has_image ) {  
			echo '<img src="'. $image_src[0].'" alt="" style="max-width:100%;" />'; 
		}
		
		echo '</div>';

		echo '<a class="wcv-file-uploader-add'. $field[ 'image_meta_key' ] . ' ' . ( $has_image ? 'hidden' : '' ) . '" href="#">'.$field[ 'add_text' ].'</a><br />'; 
		echo '<a class="wcv-file-uploader-delete' . $field[ 'image_meta_key' ] .' ' . ( !$has_image ? 'hidden' : '' )  . '" href="#" >'.$field[ 'remove_text' ].'</a><br />'; 
		echo '<input class="wcv-img-id" name="'. $field[ 'image_meta_key'] .'" id="'. $field[ 'image_meta_key'] .'" type="hidden" value="'. esc_attr( $field[ 'value' ] ) .'" data-image_meta_key="'. $field[ 'image_meta_key' ] .'" data-save_button="'. $field[ 'save_button' ] .'" data-window_title="'. $field[ 'window_title' ] .'" />';

		// container wrapper end if defined 
		if (! empty($field['wrapper_start'] ) && ! empty($field['wrapper_end'] ) ) { 
			echo $field['wrapper_end']; 
		}

	} // file_uploader()



	/**
	 * Output a submit button 
	 *
	 * @since      1.0.0
	 * @param      array     $array    the text for the submit button 
	 */
	public static function submit( $args ) { 

		$args[ 'id' ]			= isset( $args[ 'id' ] ) 		? $args[ 'id' ] 	 : '';
		$args[ 'value' ]		= isset( $args[ 'value' ] ) 	? $args[ 'value' ] 	 : 'Submit'; 
		$args[ 'class' ]        = isset( $args[ 'class'] ) 		? $args[ 'class' ] 	 : ''; 

		// Container wrapper start if defined start & end required otherwise no output is shown 
		if (! empty( $args[ 'wrapper_start' ] ) && ! empty( $args[ 'wrapper_end' ] ) ) { 
			echo $args['wrapper_start']; 
		}

		// echo '<div class="control-group">';

		// echo '<div class="control">';
		echo '<input type="submit" value="'. $args[ 'value' ] .'" class="wcv-button '. $args[ 'class' ] .'" name="'. $args[ 'id' ] .'" id="'. $args[ 'id' ] .'">'; 
		// echo '</div>'; 

		// echo '</div>'; 

		// container wrapper end if defined 
		if (! empty( $args[ 'wrapper_start' ] ) && ! empty( $args[ 'wrapper_end' ] ) ) { 
			echo $args[ 'wrapper_end' ]; 
		}

	}  // submit() 


	/**
	 * Output a selec2 country selector
	 *
	 * @since      1.0.0
	 * @param      array     $field      country select arguments
	 */
	public static function country_select2( $field ) { 

		$field[ 'id' ]					= isset( $field[ 'id' ] ) 				? $field[ 'id' ] 				: '';
		$field[ 'label' ]				= isset( $field[ 'label' ] ) 			? $field[ 'label' ] 			: '';
		$field[ 'value' ]				= isset( $field[ 'value' ] ) 			? $field[ 'value' ] 			: ''; 
		$field[ 'class' ]         		= isset( $field[ 'class'] ) 			? $field[ 'class' ] 			: ''; 
		$field[ 'wrapper_start' ]		= isset( $field[ 'wrapper_start' ] ) 	? $field[ 'wrapper_start' ] 	: '';
		$field[ 'wrapper_end' ]			= isset( $field[ 'wrapper_end' ] ) 		? $field[ 'wrapper_end' ] 		: '';
		$field[ 'show_option_none' ]	= isset( $field['show_option_none'] ) 	? $field[ 'show_option_none' ] 	: ''; 
		$field[ 'options' ]				= isset( $field['options'] ) 			? $field['options'] 			: ( WC()->countries->get_allowed_countries() ) ? WC()->countries->get_allowed_countries() : WC()->countries->get_shipping_countries(); 

 
		if ( $field[ 'value' ] == '' ) $field[ 'value' ] = WC()->countries->get_base_country();

		WCVendors_Pro_Form_Helper::select( apply_filters( 'wcv_country_select2', array(
			'id' 					=> $field[ 'id' ], 
			'label' 				=> $field[ 'label' ], 
			'value' 				=> $field[ 'value' ], 
			'class'					=> 'select2 country_to_state country_select '. $field[ 'class' ], 
			'options' 				=> $field[ 'options' ], 
			'wrapper_start'			=> $field[ 'wrapper_start' ], 
			'wrapper_end'			=> $field[ 'wrapper_end' ], 
			) )
		);
	}

	/**
	 * Countries array
	 *
	 * @since      1.0.0
	 */
	public static function countries( ) { 

		return $countries = apply_filters( 'wcv_countries_list', array(
				'AF' => __( 'Afghanistan', 'wcvendors-pro' ),
				'AX' => __( '&#197;land Islands', 'wcvendors-pro' ),
				'AL' => __( 'Albania', 'wcvendors-pro' ),
				'DZ' => __( 'Algeria', 'wcvendors-pro' ),
				'AS' => __( 'American Samoa', 'wcvendors-pro' ),
				'AD' => __( 'Andorra', 'wcvendors-pro' ),
				'AO' => __( 'Angola', 'wcvendors-pro' ),
				'AI' => __( 'Anguilla', 'wcvendors-pro' ),
				'AQ' => __( 'Antarctica', 'wcvendors-pro' ),
				'AG' => __( 'Antigua and Barbuda', 'wcvendors-pro' ),
				'AR' => __( 'Argentina', 'wcvendors-pro' ),
				'AM' => __( 'Armenia', 'wcvendors-pro' ),
				'AW' => __( 'Aruba', 'wcvendors-pro' ),
				'AU' => __( 'Australia', 'wcvendors-pro' ),
				'AT' => __( 'Austria', 'wcvendors-pro' ),
				'AZ' => __( 'Azerbaijan', 'wcvendors-pro' ),
				'BS' => __( 'Bahamas', 'wcvendors-pro' ),
				'BH' => __( 'Bahrain', 'wcvendors-pro' ),
				'BD' => __( 'Bangladesh', 'wcvendors-pro' ),
				'BB' => __( 'Barbados', 'wcvendors-pro' ),
				'BY' => __( 'Belarus', 'wcvendors-pro' ),
				'BE' => __( 'Belgium', 'wcvendors-pro' ),
				'PW' => __( 'Belau', 'wcvendors-pro' ),
				'BZ' => __( 'Belize', 'wcvendors-pro' ),
				'BJ' => __( 'Benin', 'wcvendors-pro' ),
				'BM' => __( 'Bermuda', 'wcvendors-pro' ),
				'BT' => __( 'Bhutan', 'wcvendors-pro' ),
				'BO' => __( 'Bolivia', 'wcvendors-pro' ),
				'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'wcvendors-pro' ),
				'BA' => __( 'Bosnia and Herzegovina', 'wcvendors-pro' ),
				'BW' => __( 'Botswana', 'wcvendors-pro' ),
				'BV' => __( 'Bouvet Island', 'wcvendors-pro' ),
				'BR' => __( 'Brazil', 'wcvendors-pro' ),
				'IO' => __( 'British Indian Ocean Territory', 'wcvendors-pro' ),
				'VG' => __( 'British Virgin Islands', 'wcvendors-pro' ),
				'BN' => __( 'Brunei', 'wcvendors-pro' ),
				'BG' => __( 'Bulgaria', 'wcvendors-pro' ),
				'BF' => __( 'Burkina Faso', 'wcvendors-pro' ),
				'BI' => __( 'Burundi', 'wcvendors-pro' ),
				'KH' => __( 'Cambodia', 'wcvendors-pro' ),
				'CM' => __( 'Cameroon', 'wcvendors-pro' ),
				'CA' => __( 'Canada', 'wcvendors-pro' ),
				'CV' => __( 'Cape Verde', 'wcvendors-pro' ),
				'KY' => __( 'Cayman Islands', 'wcvendors-pro' ),
				'CF' => __( 'Central African Republic', 'wcvendors-pro' ),
				'TD' => __( 'Chad', 'wcvendors-pro' ),
				'CL' => __( 'Chile', 'wcvendors-pro' ),
				'CN' => __( 'China', 'wcvendors-pro' ),
				'CX' => __( 'Christmas Island', 'wcvendors-pro' ),
				'CC' => __( 'Cocos (Keeling) Islands', 'wcvendors-pro' ),
				'CO' => __( 'Colombia', 'wcvendors-pro' ),
				'KM' => __( 'Comoros', 'wcvendors-pro' ),
				'CG' => __( 'Congo (Brazzaville)', 'wcvendors-pro' ),
				'CD' => __( 'Congo (Kinshasa)', 'wcvendors-pro' ),
				'CK' => __( 'Cook Islands', 'wcvendors-pro' ),
				'CR' => __( 'Costa Rica', 'wcvendors-pro' ),
				'HR' => __( 'Croatia', 'wcvendors-pro' ),
				'CU' => __( 'Cuba', 'wcvendors-pro' ),
				'CW' => __( 'Cura&ccedil;ao', 'wcvendors-pro' ),
				'CY' => __( 'Cyprus', 'wcvendors-pro' ),
				'CZ' => __( 'Czech Republic', 'wcvendors-pro' ),
				'DK' => __( 'Denmark', 'wcvendors-pro' ),
				'DJ' => __( 'Djibouti', 'wcvendors-pro' ),
				'DM' => __( 'Dominica', 'wcvendors-pro' ),
				'DO' => __( 'Dominican Republic', 'wcvendors-pro' ),
				'EC' => __( 'Ecuador', 'wcvendors-pro' ),
				'EG' => __( 'Egypt', 'wcvendors-pro' ),
				'SV' => __( 'El Salvador', 'wcvendors-pro' ),
				'GQ' => __( 'Equatorial Guinea', 'wcvendors-pro' ),
				'ER' => __( 'Eritrea', 'wcvendors-pro' ),
				'EE' => __( 'Estonia', 'wcvendors-pro' ),
				'ET' => __( 'Ethiopia', 'wcvendors-pro' ),
				'FK' => __( 'Falkland Islands', 'wcvendors-pro' ),
				'FO' => __( 'Faroe Islands', 'wcvendors-pro' ),
				'FJ' => __( 'Fiji', 'wcvendors-pro' ),
				'FI' => __( 'Finland', 'wcvendors-pro' ),
				'FR' => __( 'France', 'wcvendors-pro' ),
				'GF' => __( 'French Guiana', 'wcvendors-pro' ),
				'PF' => __( 'French Polynesia', 'wcvendors-pro' ),
				'TF' => __( 'French Southern Territories', 'wcvendors-pro' ),
				'GA' => __( 'Gabon', 'wcvendors-pro' ),
				'GM' => __( 'Gambia', 'wcvendors-pro' ),
				'GE' => __( 'Georgia', 'wcvendors-pro' ),
				'DE' => __( 'Germany', 'wcvendors-pro' ),
				'GH' => __( 'Ghana', 'wcvendors-pro' ),
				'GI' => __( 'Gibraltar', 'wcvendors-pro' ),
				'GR' => __( 'Greece', 'wcvendors-pro' ),
				'GL' => __( 'Greenland', 'wcvendors-pro' ),
				'GD' => __( 'Grenada', 'wcvendors-pro' ),
				'GP' => __( 'Guadeloupe', 'wcvendors-pro' ),
				'GU' => __( 'Guam', 'wcvendors-pro' ),
				'GT' => __( 'Guatemala', 'wcvendors-pro' ),
				'GG' => __( 'Guernsey', 'wcvendors-pro' ),
				'GN' => __( 'Guinea', 'wcvendors-pro' ),
				'GW' => __( 'Guinea-Bissau', 'wcvendors-pro' ),
				'GY' => __( 'Guyana', 'wcvendors-pro' ),
				'HT' => __( 'Haiti', 'wcvendors-pro' ),
				'HM' => __( 'Heard Island and McDonald Islands', 'wcvendors-pro' ),
				'HN' => __( 'Honduras', 'wcvendors-pro' ),
				'HK' => __( 'Hong Kong', 'wcvendors-pro' ),
				'HU' => __( 'Hungary', 'wcvendors-pro' ),
				'IS' => __( 'Iceland', 'wcvendors-pro' ),
				'IN' => __( 'India', 'wcvendors-pro' ),
				'ID' => __( 'Indonesia', 'wcvendors-pro' ),
				'IR' => __( 'Iran', 'wcvendors-pro' ),
				'IQ' => __( 'Iraq', 'wcvendors-pro' ),
				'IE' => __( 'Republic of Ireland', 'wcvendors-pro' ),
				'IM' => __( 'Isle of Man', 'wcvendors-pro' ),
				'IL' => __( 'Israel', 'wcvendors-pro' ),
				'IT' => __( 'Italy', 'wcvendors-pro' ),
				'CI' => __( 'Ivory Coast', 'wcvendors-pro' ),
				'JM' => __( 'Jamaica', 'wcvendors-pro' ),
				'JP' => __( 'Japan', 'wcvendors-pro' ),
				'JE' => __( 'Jersey', 'wcvendors-pro' ),
				'JO' => __( 'Jordan', 'wcvendors-pro' ),
				'KZ' => __( 'Kazakhstan', 'wcvendors-pro' ),
				'KE' => __( 'Kenya', 'wcvendors-pro' ),
				'KI' => __( 'Kiribati', 'wcvendors-pro' ),
				'KW' => __( 'Kuwait', 'wcvendors-pro' ),
				'KG' => __( 'Kyrgyzstan', 'wcvendors-pro' ),
				'LA' => __( 'Laos', 'wcvendors-pro' ),
				'LV' => __( 'Latvia', 'wcvendors-pro' ),
				'LB' => __( 'Lebanon', 'wcvendors-pro' ),
				'LS' => __( 'Lesotho', 'wcvendors-pro' ),
				'LR' => __( 'Liberia', 'wcvendors-pro' ),
				'LY' => __( 'Libya', 'wcvendors-pro' ),
				'LI' => __( 'Liechtenstein', 'wcvendors-pro' ),
				'LT' => __( 'Lithuania', 'wcvendors-pro' ),
				'LU' => __( 'Luxembourg', 'wcvendors-pro' ),
				'MO' => __( 'Macao S.A.R., China', 'wcvendors-pro' ),
				'MK' => __( 'Macedonia', 'wcvendors-pro' ),
				'MG' => __( 'Madagascar', 'wcvendors-pro' ),
				'MW' => __( 'Malawi', 'wcvendors-pro' ),
				'MY' => __( 'Malaysia', 'wcvendors-pro' ),
				'MV' => __( 'Maldives', 'wcvendors-pro' ),
				'ML' => __( 'Mali', 'wcvendors-pro' ),
				'MT' => __( 'Malta', 'wcvendors-pro' ),
				'MH' => __( 'Marshall Islands', 'wcvendors-pro' ),
				'MQ' => __( 'Martinique', 'wcvendors-pro' ),
				'MR' => __( 'Mauritania', 'wcvendors-pro' ),
				'MU' => __( 'Mauritius', 'wcvendors-pro' ),
				'YT' => __( 'Mayotte', 'wcvendors-pro' ),
				'MX' => __( 'Mexico', 'wcvendors-pro' ),
				'FM' => __( 'Micronesia', 'wcvendors-pro' ),
				'MD' => __( 'Moldova', 'wcvendors-pro' ),
				'MC' => __( 'Monaco', 'wcvendors-pro' ),
				'MN' => __( 'Mongolia', 'wcvendors-pro' ),
				'ME' => __( 'Montenegro', 'wcvendors-pro' ),
				'MS' => __( 'Montserrat', 'wcvendors-pro' ),
				'MA' => __( 'Morocco', 'wcvendors-pro' ),
				'MZ' => __( 'Mozambique', 'wcvendors-pro' ),
				'MM' => __( 'Myanmar', 'wcvendors-pro' ),
				'NA' => __( 'Namibia', 'wcvendors-pro' ),
				'NR' => __( 'Nauru', 'wcvendors-pro' ),
				'NP' => __( 'Nepal', 'wcvendors-pro' ),
				'NL' => __( 'Netherlands', 'wcvendors-pro' ),
				'NC' => __( 'New Caledonia', 'wcvendors-pro' ),
				'NZ' => __( 'New Zealand', 'wcvendors-pro' ),
				'NI' => __( 'Nicaragua', 'wcvendors-pro' ),
				'NE' => __( 'Niger', 'wcvendors-pro' ),
				'NG' => __( 'Nigeria', 'wcvendors-pro' ),
				'NU' => __( 'Niue', 'wcvendors-pro' ),
				'NF' => __( 'Norfolk Island', 'wcvendors-pro' ),
				'MP' => __( 'Northern Mariana Islands', 'wcvendors-pro' ),
				'KP' => __( 'North Korea', 'wcvendors-pro' ),
				'NO' => __( 'Norway', 'wcvendors-pro' ),
				'OM' => __( 'Oman', 'wcvendors-pro' ),
				'PK' => __( 'Pakistan', 'wcvendors-pro' ),
				'PS' => __( 'Palestinian Territory', 'wcvendors-pro' ),
				'PA' => __( 'Panama', 'wcvendors-pro' ),
				'PG' => __( 'Papua New Guinea', 'wcvendors-pro' ),
				'PY' => __( 'Paraguay', 'wcvendors-pro' ),
				'PE' => __( 'Peru', 'wcvendors-pro' ),
				'PH' => __( 'Philippines', 'wcvendors-pro' ),
				'PN' => __( 'Pitcairn', 'wcvendors-pro' ),
				'PL' => __( 'Poland', 'wcvendors-pro' ),
				'PT' => __( 'Portugal', 'wcvendors-pro' ),
				'PR' => __( 'Puerto Rico', 'wcvendors-pro' ),
				'QA' => __( 'Qatar', 'wcvendors-pro' ),
				'RE' => __( 'Reunion', 'wcvendors-pro' ),
				'RO' => __( 'Romania', 'wcvendors-pro' ),
				'RU' => __( 'Russia', 'wcvendors-pro' ),
				'RW' => __( 'Rwanda', 'wcvendors-pro' ),
				'BL' => __( 'Saint Barth&eacute;lemy', 'wcvendors-pro' ),
				'SH' => __( 'Saint Helena', 'wcvendors-pro' ),
				'KN' => __( 'Saint Kitts and Nevis', 'wcvendors-pro' ),
				'LC' => __( 'Saint Lucia', 'wcvendors-pro' ),
				'MF' => __( 'Saint Martin (French part)', 'wcvendors-pro' ),
				'SX' => __( 'Saint Martin (Dutch part)', 'wcvendors-pro' ),
				'PM' => __( 'Saint Pierre and Miquelon', 'wcvendors-pro' ),
				'VC' => __( 'Saint Vincent and the Grenadines', 'wcvendors-pro' ),
				'SM' => __( 'San Marino', 'wcvendors-pro' ),
				'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'wcvendors-pro' ),
				'SA' => __( 'Saudi Arabia', 'wcvendors-pro' ),
				'SN' => __( 'Senegal', 'wcvendors-pro' ),
				'RS' => __( 'Serbia', 'wcvendors-pro' ),
				'SC' => __( 'Seychelles', 'wcvendors-pro' ),
				'SL' => __( 'Sierra Leone', 'wcvendors-pro' ),
				'SG' => __( 'Singapore', 'wcvendors-pro' ),
				'SK' => __( 'Slovakia', 'wcvendors-pro' ),
				'SI' => __( 'Slovenia', 'wcvendors-pro' ),
				'SB' => __( 'Solomon Islands', 'wcvendors-pro' ),
				'SO' => __( 'Somalia', 'wcvendors-pro' ),
				'ZA' => __( 'South Africa', 'wcvendors-pro' ),
				'GS' => __( 'South Georgia/Sandwich Islands', 'wcvendors-pro' ),
				'KR' => __( 'South Korea', 'wcvendors-pro' ),
				'SS' => __( 'South Sudan', 'wcvendors-pro' ),
				'ES' => __( 'Spain', 'wcvendors-pro' ),
				'LK' => __( 'Sri Lanka', 'wcvendors-pro' ),
				'SD' => __( 'Sudan', 'wcvendors-pro' ),
				'SR' => __( 'Suriname', 'wcvendors-pro' ),
				'SJ' => __( 'Svalbard and Jan Mayen', 'wcvendors-pro' ),
				'SZ' => __( 'Swaziland', 'wcvendors-pro' ),
				'SE' => __( 'Sweden', 'wcvendors-pro' ),
				'CH' => __( 'Switzerland', 'wcvendors-pro' ),
				'SY' => __( 'Syria', 'wcvendors-pro' ),
				'TW' => __( 'Taiwan', 'wcvendors-pro' ),
				'TJ' => __( 'Tajikistan', 'wcvendors-pro' ),
				'TZ' => __( 'Tanzania', 'wcvendors-pro' ),
				'TH' => __( 'Thailand', 'wcvendors-pro' ),
				'TL' => __( 'Timor-Leste', 'wcvendors-pro' ),
				'TG' => __( 'Togo', 'wcvendors-pro' ),
				'TK' => __( 'Tokelau', 'wcvendors-pro' ),
				'TO' => __( 'Tonga', 'wcvendors-pro' ),
				'TT' => __( 'Trinidad and Tobago', 'wcvendors-pro' ),
				'TN' => __( 'Tunisia', 'wcvendors-pro' ),
				'TR' => __( 'Turkey', 'wcvendors-pro' ),
				'TM' => __( 'Turkmenistan', 'wcvendors-pro' ),
				'TC' => __( 'Turks and Caicos Islands', 'wcvendors-pro' ),
				'TV' => __( 'Tuvalu', 'wcvendors-pro' ),
				'UG' => __( 'Uganda', 'wcvendors-pro' ),
				'UA' => __( 'Ukraine', 'wcvendors-pro' ),
				'AE' => __( 'United Arab Emirates', 'wcvendors-pro' ),
				'GB' => __( 'United Kingdom (UK)', 'wcvendors-pro' ),
				'US' => __( 'United States (US)', 'wcvendors-pro' ),
				'UM' => __( 'United States (US) Minor Outlying Islands', 'wcvendors-pro' ),
				'VI' => __( 'United States (US) Virgin Islands', 'wcvendors-pro' ),
				'UY' => __( 'Uruguay', 'wcvendors-pro' ),
				'UZ' => __( 'Uzbekistan', 'wcvendors-pro' ),
				'VU' => __( 'Vanuatu', 'wcvendors-pro' ),
				'VA' => __( 'Vatican', 'wcvendors-pro' ),
				'VE' => __( 'Venezuela', 'wcvendors-pro' ),
				'VN' => __( 'Vietnam', 'wcvendors-pro' ),
				'WF' => __( 'Wallis and Futuna', 'wcvendors-pro' ),
				'EH' => __( 'Western Sahara', 'wcvendors-pro' ),
				'WS' => __( 'Samoa', 'wcvendors-pro' ),
				'YE' => __( 'Yemen', 'wcvendors-pro' ),
				'ZM' => __( 'Zambia', 'wcvendors-pro' ),
				'ZW' => __( 'Zimbabwe', 'wcvendors-pro' ),
			) );
	}

	/**
	 * Generate a check list for categories on the front end 
	 * 
	 * @since 1.2.0
	 */
	public static function wcv_terms_checklist( $post_id = 0, $args = array(), $field = array() ) {


		$field[ 'class' ]         		= isset( $field[ 'class'] ) 			? $field[ 'class' ] 			: ''; 

	 	$defaults = array(
			'descendants_and_self' => 0,
			'selected_cats' => false,
			'walker' => null,
			'taxonomy' => 'category',
			'checked_ontop' => false,
			'echo' => true,
		);

		$params = apply_filters( 'wp_terms_checklist_args', $args, $post_id );

		$r = wp_parse_args( $params, $defaults );

		if ( empty( $r['walker'] ) || ! ( $r['walker'] instanceof Walker ) ) {
			$walker = new WCV_Walker_Category_Checklist;
		} else {
			$walker = $r['walker'];
		}

		$taxonomy = $r['taxonomy'];

		$descendants_and_self = (int) $r['descendants_and_self'];

		$args = array( 'taxonomy' => $taxonomy );

		$tax = get_taxonomy( $taxonomy );

		$args['disabled'] = ! current_user_can( $tax->cap->assign_terms );

		$args['list_only'] = ! empty( $r['list_only'] );

		if ( is_array( $r['selected_cats'] ) ) {
			$args['selected_cats'] = $r['selected_cats'];
		} elseif ( $post_id ) {
			$args['selected_cats'] = wp_get_object_terms( $post_id, $taxonomy, array_merge( $args, array( 'fields' => 'ids' ) ) );
		} else {
			$args['selected_cats'] = array();
		}
		
		if ( $descendants_and_self ) {
			$categories = (array) get_terms( $taxonomy, array(
				'child_of' => $descendants_and_self,
				'hierarchical' => 0,
				'hide_empty' => 0,
				'exclude' => $r['exclude'], 
			) );
			$self = get_term( $descendants_and_self, $taxonomy );
			array_unshift( $categories, $self );
		} else {
			$categories = (array) get_terms( $taxonomy, array( 'get' => 'all', 'exclude' => $r['exclude'] ) );
		}

		$output = '';

		// Then the rest of them
		$output .= call_user_func_array( array( $walker, 'walk' ), array( $categories, 0, $args ) );

		if ( $r['echo'] ) {

			echo '<div class="control-group">';
			echo '<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ).  '</label>';
			echo '<div class="wcv_terms_checklist_container">';

			echo '<ul class="control unstyled wcv_terms_checklist ' . $field[ 'class' ] . '">'; 
			echo $output;
			echo '</ul>'; 

			echo '</div>'; 
			echo '<span class="wcv_required_form_msg wcv_check_list_msg"></span>'; 
			echo '</div>'; 
		}

		return $output;

	} // wcv_terms_checklist()


	/**
	 * Output a custom form input based on an included file 
	 */
	public static function custom_field( $options ){ 

		extract( $options ); 

		echo '<div class="control-group">';

		if ( isset( $wrapper_class ) ) { 
			echo '<div class="' . $wrapper_class .'">'; 
		}

		include_once( $path ); 

		if ( isset( $wrapper_class ) ) { 
			echo '</div>';
		} 

		echo '</div>'; 


	} // custom_field() 


	/**
	 * This function will output form fields based on the options parsed 
	 * 
	 * @since 1.4.0 
	 * @param array $options field options 
	 * @param int $post_id the post id 
	 */
	public static function generate_form_part( $options, $post_id = 0 ){ 

		extract( $options ); 

		if ( isset( $wrapper_class ) ) { 
			echo '<div class="' . $wrapper_class .'" id="' .$wrapper_id. '">'; 
		}

		foreach ( $fields as $field_id => $field ) {

			$field[ 'id' ]			= $field_id; 
			$field[ 'post_id' ]		= $post_id; 

			switch ( $field[ 'field_type' ] ) {
				case 'input': 
					WCVendors_Pro_Form_Helper::input( $field ); 
					break;
				case 'select': 
					WCVendors_Pro_Form_Helper::select( $field ); 
					break; 
				case 'select2': 
					WCVendors_Pro_Form_Helper::select2( $field ); 
					break; 
				case 'custom_field': 
					WCVendors_Pro_Form_Helper::custom_field( $field ); 
					break; 
				default:
					break;
			}
		
		}


		if ( isset( $wrapper_class ) ) { 
			echo '</div>';
		} 


	} // generate_form() 

}