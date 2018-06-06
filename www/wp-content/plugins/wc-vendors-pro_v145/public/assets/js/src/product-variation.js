/* global wcv_frontend_product_variation / copyright WooCommerce 2016 
*/
jQuery( function( $ ) {

	var debug = false; 
	var parent_obj = {}; 
    var existing_attributes = []; 

	/**
	 *  Utilities for variations 
	*/
	var wcv_utils = { 

		// Create a variation attribute drop down 
		create_variation_drop_down: function( taxonomy, options, taxonomy_label, position ) {

			var name = 'attribute_' + taxonomy + '[' + position + ']'; 
			var css_class = 'variation_attribute ' + taxonomy; 

    		var taxonomy_dd = $( '<select></select>').attr( 'name', name ).attr('class', css_class ).data('taxonomy', taxonomy );

    		taxonomy_dd.append( '<option value="">' + wcv_frontend_product_variation.i18n_any_label + ' ' + taxonomy_label + '</option>');
    		
    		$.each( options, function ( value, text) {
        		taxonomy_dd.append( '<option value="' + value  +'">' + text + '</option>');
    		});

    		return taxonomy_dd;
   
		}, 

		// Create the variations default drop down 
		create_defaults_drop_down: function( taxonomy, options, taxonomy_label ) {

			var name = 'default_attribute_' + taxonomy; 
			var css_class = taxonomy; 

    		var taxonomy_dd = $( '<select></select>').attr( 'name', name ).attr('class', css_class ).data('current', '').data('taxonomy', taxonomy );;

    		taxonomy_dd.append( '<option value="">' + wcv_frontend_product_variation.i18n_any_label + ' ' + taxonomy_label + '</option>');
    		
    		$.each( options, function ( value, text) {
        		taxonomy_dd.append( '<option value="' + value  +'">' + text + '</option>');
    		});

    		return taxonomy_dd;
   
		}, 

		// Sort select terms alphabetically 
		sort_select_terms: function( select_element ){ 

			var $dd = select_element;

			if ( $dd.length > 0 ) { // make sure we found the select we were looking for

			    // save the selected value
			    var selectedVal = $dd.val();

			    // get the options and loop through them
			    var $options = $('option', $dd);
			    var arrVals = [];
			    $options.each(function(){
			        // push each option value and text into an array
			        arrVals.push({
			            val: $(this).val(),
			            text: $(this).text()
			        });
			    });

			    // sort the array by the value (change val to text to sort by text instead)
			   arrVals.sort(function(a, b){
				    if(a.val>b.val){
				        return 1;
				    }
				    else if (a.val==b.val){
				        return 0;
				    }
				    else {
				        return -1;
				    }
				});

			    // loop through the sorted array and set the text/values to the options
			    for (var i = 0, l = arrVals.length; i < l; i++) {
			        $($options[i]).val(arrVals[i].val).text(arrVals[i].text);
			    }

			    // set the selected value back
			    $dd.val(selectedVal);

			    return $dd; 
			}

		}, 

		update_variation_select_positions: function( ){ 

			var attributes = $( '#wcv-variation-attributes' ).data( 'variation_attr' ); 

			$( '.wcv_variation').each( function( ) { 
		
				var $selects = $( this ).find( 'select.variation_attribute' ); 

				$selects.sort( function( a, b ) {

					var an = attributes[ $( a ).data( 'taxonomy' ) ]['position']; 
					var bn = attributes[ $( b ).data( 'taxonomy' ) ]['position'];

					if ( an > bn ) {
						return 1;
					}
					if ( an < bn ) {
						return -1;
					}

					return 0;
				}); 

				$selects.detach().appendTo( $( this ).find( 'span.variations_wrapper') ); 

			}); 

		}, 

		update_default_select_positions: function( ){ 

			var attributes = $( '#wcv-variation-attributes' ).data( 'variation_attr' ); 

			var $selects = $( '.variation_default_values' ).find( 'select.default_attribute' ); 

			$selects.sort( function( a, b ) {

				var an = attributes[ $( a ).data( 'taxonomy' ) ]['position']; 
				var bn = attributes[ $( b ).data( 'taxonomy' ) ]['position'];

				if ( an > bn ) {
					return 1;
				}
				if ( an < bn ) {
					return -1;
				}

				return 0;
			}); 

			$selects.detach().appendTo( '.variation_default_values'); 


		}, 

	}; 

	/**
	 * Variations actions
	 */
	var wcv_product_variations_actions = {

		/**
		 * Initialize variations actions
		 */
		init: function() {
			$( '#wcv_variable_product_options' )
				.on( 'change', 'input.variable_is_downloadable', this.variable_is_downloadable )
				.on( 'change', 'input.variable_is_virtual', this.variable_is_virtual )
				.on( 'change', 'input.variable_manage_stock', this.variable_manage_stock )
				.on( 'click', 'button.notice-dismiss', this.notice_dismiss )
				.on( 'click', 'h5 .wcv-sort', this.set_menu_order )
				.on( 'reload', this.reload );

			$( 'input.variable_is_downloadable, input.variable_is_virtual, input.variable_manage_stock' ).change();
			$( document.body ).on( 'wcv_variations_added', this.variation_added );
		},

		/**
		 * Reload UI
		 *
		 * @param {Object} event
		 * @param {Int} qty
		 */
		reload: function() {
			wcv_product_variations_ajax.load_variations( 1 );
			this.variation_options; 
		},

		/**
		 * Check if variation is downloadable and show/hide elements
		 */
		variable_is_downloadable: function() {
			$( this ).closest( '.wcv_variation' ).find( '.show_if_variation_downloadable' ).hide();

			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( '.wcv_variation' ).find( '.show_if_variation_downloadable' ).show();
			}
		},

		/**
		 * Check if variation is virtual and show/hide elements
		 */
		variable_is_virtual: function() {
			$( this ).closest( '.wcv_variation' ).find( '.hide_if_variation_virtual' ).show();

			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( '.wcv_variation' ).find( '.hide_if_variation_virtual' ).hide();
			}
		},

		/**
		 * Check if variation manage stock and show/hide elements
		 */
		variable_manage_stock: function() {
			$( this ).closest( '.wcv_variation' ).find( '.show_if_variation_manage_stock' ).hide();

			if ( $( this ).is( ':checked' ) ) {
				$( this ).closest( '.wcv_variation' ).find( '.show_if_variation_manage_stock' ).show();
			}
		},

		/**
		 * Notice dismiss
		 */
		notice_dismiss: function() {
			$( this ).closest( 'div.notice' ).remove();
		},

		/* 
		* 	Update the input with provided value 
		*/ 
		update_input: function( value, field ){ 
			$( '.' + field ).val( value ); 
			return false; 
		}, 

		/** 
		*	 update price field up or down by number or percent 
		*/ 
		adjust_price: function( field, value, operator ){ 

			var somevalue = value; 

			$( '.wcv_variation .'+ field ).each( function( index, el ){ 

				var temp_value = 0; 

				var current_price 	= parseFloat( $( this ).attr('value') ); 
				var new_price 		= 0; 

				if ( current_price.length <= 0 || isNaN( current_price ) ) current_price = 0; 

				if ( value.indexOf( '%' ) >= 0 ) {
					temp_value = parseFloat( value.replace('%', '') ); 
					var percentage = ( temp_value / 100 ) * parseFloat( current_price ); 
					new_price = ( operator == '+' ) ? ( parseFloat( current_price ) + percentage ) : ( parseFloat( current_price ) - percentage ); 
				} else {
					temp_value = parseFloat( value ); 
					new_price = ( operator == '+' ) ? ( parseFloat( current_price ) + temp_value ) : ( parseFloat( current_price ) - temp_value ); 
				}

				if ( new_price < 0 || isNaN( new_price ) ) new_price = 0; 

				$( this ).attr( 'value', new_price ); 

			}); 

			return false; 
		}, 

		/**
		 * Run actions when variations is loaded
		 *
		 * @param {Object} event
		 * @param {Int} needsUpdate
		 */
		variations_loaded: function( event, needsUpdate ) {
			needsUpdate = needsUpdate || false;

			var wrapper = $( '#wcv_variable_product_options' ); 

			if ( ! needsUpdate ) {
				// Show/hide downloadable, virtual and stock fields
				$( 'input.variable_is_downloadable, input.variable_is_virtual, input.variable_manage_stock', wrapper ).change();

				// Open sale schedule fields when have some sale price date
				$( '.wcv_variation', wrapper ).each( function( index, el ) {
					var $el       = $( el ),
						date_from = $( '.sale_price_dates_from', $el ).val(),
						date_to   = $( '.sale_price_dates_to', $el ).val();

					if ( '' !== date_from || '' !== date_to ) {
						$( 'a.sale_schedule', $el ).click();
					}
				});
			}

			// Allow sorting
			$( '.wcv_variations', wrapper ).sortable({
				items:                '.wcv_variation',
				cursor:               'move',
				axis:                 'y',
				handle:               '.wcv-sort',
				scrollSensitivity:    40,
				forcePlaceholderSize: true,
				helper:               'clone',
				opacity:              0.65,
				stop:                 function() {
				    wcv_product_variations_actions.variation_row_indexes();
				}
			});

		},

		/**
		 * Run actions when added a variation
		 *
		 * @param {Object} event
		 * @param {Int} qty
		 */
		variation_added: function( event, qty ) {
			if ( 1 === qty ) {
				wcv_product_variations_actions.variations_loaded( null, true );
			}
		},

		/**
		 * Lets the user manually input menu order to move items around pages
		 */
		set_menu_order: function( event ) {
			event.preventDefault();
			var $menu_order  = $( this ).closest( '.wcv_variation' ).find('.variation_menu_order');
			var value        = window.prompt( wcv_frontend_product_variation.i18n_enter_menu_order, $menu_order.val() );

			if ( value != null ) {
				// Set value, save changes and reload view
				$menu_order.val( parseInt( value, 10 ) ).change();
				wcv_product_variations_ajax.save_variations();
			}
		},

		/**
		 * Set menu order
		 */
		variation_row_indexes: function() {
			var wrapper      = $( '#wcv_variable_product_options' ).find( '.wcv_variations' ),
				current_page = parseInt( wrapper.attr( 'data-page' ), 10 ),
				offset       = parseInt( ( current_page - 1 ) * wcv_frontend_product_variation.variations_per_page, 10 );

			$( '.wcv_variations .wcv_variation' ).each( function ( index, el ) {
				$( '.variation_menu_order', el ).val( parseInt( $( el ).index( '.wcv_variations .wcv_variation' ), 10 ) + 1 + offset ).change();
			});
		}
	};

	/**
	 * Variations media actions
	 */
	var wcv_product_variations_media = {

		/**
		 * Variation image object
		 *
		 * @type {Object}
		 */
		setting_variation_image: null,

		/**
		 * Initialize media actions
		 */
		init: function() {
			$( '#wcv_variable_product_options' ).on( 'click', '.upload_image_button', this.add_image );
		},

		/**
		 * Added new image
		 *
		 * @param {Object} event
		 */
		add_image: function( event  ) {
			var $button = $( this ),
				post_id = $button.attr( 'rel' ),
				$parent = $button.closest( '.upload_image' );
			var media_uploader, json;


			wcv_product_variations_media.setting_variation_image    = $parent;

			event.preventDefault(); 

			if ( $button.is( '.wcv_remove' ) ) {

				$( '.upload_image_id', wcv_product_variations_media.setting_variation_image ).val( '' ).change();
				wcv_product_variations_media.setting_variation_image.find( 'img' ).eq( 0 ).attr( 'src', wcv_frontend_product_variation.wcv_woocommerce_placeholder_img_src );
				wcv_product_variations_media.setting_variation_image.find( '.upload_image_button' ).removeClass( 'wcv_remove' );

			} else {

				if ( undefined !== media_uploader ) { 
					media_uploader.open(); 
					return; 
				}

			    media_uploader = wp.media({
	  				title: wcv_frontend_product_variation.i18n_choose_image,
					button: {
						text: wcv_frontend_product_variation.i18n_set_image
					},
					states: [
						new wp.media.controller.Library({
							title: wcv_frontend_product_variation.i18n_choose_image,
							filterable: 'all'
						})
					]
		    	});

			    media_uploader.on( 'select' , function(){
			    	json = media_uploader.state().get('selection').first().toJSON(); 

			    	if ( 0 > $.trim( json.url.length ) ) {
				        return;
				    }

					attachment_url = json.sizes.thumbnail ? json.sizes.thumbnail.url : json.url; 

					$( '.upload_image_id', wcv_product_variations_media.setting_variation_image ).val( json.id ).change();
					wcv_product_variations_media.setting_variation_image.find( '.upload_image_button' ).addClass( 'wcv_remove' );
					wcv_product_variations_media.setting_variation_image.find( 'img' ).eq( 0 ).attr( 'src', attachment_url );

			    });

				media_uploader.open();

			} 

		},

		/**
		 * Restore wp.media post ID.
		 */
		restore_wp_media_post_id: function() {
			wp.media.model.settings.post.id = $( '#post_id' ).val();
		}
	};

	/**
	 * Product variations metabox ajax methods
	 */
	var wcv_product_variations_ajax = {

		/**
		 * Initialize variations ajax methods
		 */
		init: function() {

			$( '#wcv_variable_product_options' )
				.on( 'click', '.remove_variation', this.remove_variation );

			$( document.body )
				.on( 'change', '#wcv_variable_product_options .wcv_variations :input', this.input_changed )
				.on( 'change', '.variations-defaults select', this.defaults_changed );

			$( '.wcv-metaboxes-wrapper' ).on( 'click', 'a.do_variation_action', this.do_variation_action );

			$( 'a.variations' ).on( 'click', this.check_for_attribute_changes ); 

			// populate after doc ready as attributes aren't loaded otherwise
			$( document).ready( function() {
				var attributes = $( '#wcv-variation-attributes' ).data( 'variation_attr' );
				if ( $.isEmptyObject( existing_attributes ) ) existing_attributes = $.extend( {}, attributes ); 
			}); 

			
		},

		/**
		*	Check for attributes and adjust UI accordingly 
		*/ 
		check_for_attribute_changes: function() { 

			var attributes = $( '#wcv-variation-attributes' ).data( 'variation_attr' );
			if ( $.isEmptyObject( existing_attributes ) ) existing_attributes = $.extend( {}, attributes ); 

			// Any changes 
			if ( JSON.stringify( existing_attributes ) !=  JSON.stringify( attributes ) ) { 
			
				if ( JSON.stringify( existing_attributes ).length == JSON.stringify( attributes ).length ){ 

					wcv_utils.update_variation_select_positions(); 
					wcv_utils.update_default_select_positions();

				} else if ( JSON.stringify( existing_attributes ).length > JSON.stringify( attributes ).length ){ 

					if ( Object.keys( existing_attributes ).length == Object.keys( attributes ).length ){ 						
						$.each( existing_attributes, function( taxonomy, data ) {
							var terms = _.omit( data[ 'values' ], Object.keys( attributes[ taxonomy ][ 'values' ] ) ); 
							if ( ! $.isEmptyObject( terms ) ) {
								wcv_product_variations_ajax.update_variations_ui( taxonomy, terms, 'term', '-'); 
							}							
						}); 
					} else { 
						var removed = _.omit( existing_attributes, Object.keys( attributes ) ); 
						$.each( removed, function( index, data ) {
							 wcv_product_variations_ajax.update_variations_ui( index, data, 'attribute', '-' ); 
							 wcv_product_variations_ajax.update_defaults_ui( index, data, 'attribute', '-' ); 
						});
					}

				} else if ( JSON.stringify( existing_attributes ).length < JSON.stringify( attributes ).length ) { 

					if ( Object.keys( existing_attributes ).length == Object.keys( attributes ).length ){ 
						$.each( attributes , function( taxonomy, data ) {
							var terms = _.omit( data[ 'values' ], Object.keys( existing_attributes[ taxonomy ][ 'values' ] ) ); 
							if ( ! $.isEmptyObject( terms ) ) {
								wcv_product_variations_ajax.update_variations_ui( taxonomy, terms, 'term', '+'); 
							}							
						}); 
					} else { 
						var added = _.omit( attributes, Object.keys( existing_attributes ) ); 
						$.each( added, function( index, data ) {
							wcv_product_variations_ajax.update_variations_ui( index, data, 'attribute', '+' ); 
							wcv_product_variations_ajax.update_defaults_ui( index, data, 'attribute', '+' ); 
						})
					}
				}

				// clone the new attributes to keep track
				existing_attributes = jQuery.extend( {}, attributes ); 

			} 

			// Show the toolbar or notice 
			if ( jQuery.isEmptyObject( attributes ) ) { 
				$( '.variations-toolbar' ).hide();
				$( '.variations_notice' ).show(); 
			} else { 
				$( '.variations-toolbar' ).show();
				$( '.variations_notice' ).hide(); 
			}

			// Update the variation count if required 
			wcv_product_variations_ajax.check_total_variations();
		
		}, 

		/**
		*	Update the Variations UI when a change has been detected 
		*/ 
		update_variations_ui: function( taxonomy, data, element_type, operator ){ 

			var attributes = $( '#wcv-variation-attributes' ).data( 'variation_attr' );

			wcv_product_variations_ajax.block();

			var sort_required; 

			$( '.wcv_variation').each( function( position, el ) { 

				switch( element_type ){ 
					case 'attribute':
						if ( operator == '-' ) { 
							$( this ).find( 'select.' + taxonomy ).remove(); 
						} else { 
							var drop_down = wcv_utils.create_variation_drop_down( taxonomy, data['values'], data['label'], position ); 
							$( this ).find( '.variation_title' ).append( drop_down ); 

							wcv_utils.update_variation_select_positions(); 
							wcv_utils.update_default_select_positions();
						}
						break; 
					case 'term' : 
						if ( operator == '-' ) { 
							var current_select = $( this ).find( 'select.' + taxonomy ); 
							$.each( data, function( value, text ) {
								current_select.find('[value="' + value + '"]').remove(); 
							}); 
						} else { 
							var current_select = $( this ).find( 'select.' + taxonomy ); 
							$.each( data, function( key, value ){ 
								current_select.append( $( '<option></option>' ).attr('value',key ).text( value ) );
							}); 
							current_select = wcv_utils.sort_select_terms( current_select ); 
						}
						break; 
					default: 
						break; 
				}

			});

			wcv_product_variations_ajax.unblock();

		}, 

		update_defaults_ui: function( taxonomy, data, element_type, operator ){ 

			switch( element_type ){ 
					case 'attribute':
						if ( operator == '-' ) { 
							$( '.variations-defaults' ).find( 'select.' + taxonomy ).remove(); 
						} else { 
							var drop_down = wcv_utils.create_defaults_drop_down( taxonomy, data['values'], data['label'] ); 
							$( '.variation_default_values' ).append( drop_down ); 
							wcv_utils.update_variation_select_positions(); 
							wcv_utils.update_default_select_positions();
						}
						break; 
					case 'term' : 
						if ( operator == '-' ) { 
							var current_select = $( this ).find( 'select.' + taxonomy ); 
							$.each( data, function( value, text ) {
								current_select.find('[value="' + value + '"]').remove(); 
							}); 
						} else { 
							var current_select = $( this ).find( 'select.' + taxonomy ); 
							$.each( data, function( key, value ){ 
								current_select.append( $( '<option></option>' ).attr('value',key ).text( value ) );
							}); 
							current_select = wcv_utils.sort_select_terms( current_select ); 
						}
						break; 
					default: 
						break; 
				}

		},  


		/** 
		*	check the total variation count 
		*/ 
		check_total_variations: function(){ 

			var total_variations = $( '.wcv_variation' ).length; 

			// Remove the defaults toolbar if there are no variations 
			if ( 0 == total_variations ){ 
				$( '.variations-defaults' ).remove(); 
			}

			return false; 

		}, 

		/**
		 * Block edit screen
		 */
		block: function() {
			$( '#wcv_variable_product_options' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
		},

		/**
		 * Unblock edit screen
		 */
		unblock: function() {
			$( '#wcv_variable_product_options' ).unblock();
		},

		/** 
		*    Load default attributes drop downs
		*
		*/ 
		load_default_attributes: function(){ 

			if ( $( '.wcv_variation' ).length == 0 ) { 

				var data = {
					action: 'wcv_json_default_variation_attributes',
					attributes: $( '#wcv-variation-attributes' ).data( 'variation_attr' ), 
					security: wcv_frontend_product_variation.wcv_add_variation_nonce
				};

				$.post( wcv_frontend_product_variation.ajax_url, data, function( response ) {
					var default_attributes = $( response );
					$( '.toolbar-variations-defaults' ).prepend( default_attributes );
					$( '.toolbar-variations-defaults' ).show(); 
				});

			}

		}, 

		/**
		 * Add variation
		 *
		 * @return {Bool}
		 */
		add_variation: function() {

			wcv_product_variations_ajax.block();

			var data = {
				action: 'wcv_json_add_variation',
				loop: $( '.wcv_variation' ).size(),
				parent_data: parent_obj,  
				attributes: $( '#wcv-variation-attributes' ).data( 'variation_attr' ), 
				security: wcv_frontend_product_variation.wcv_add_variation_nonce
			};

			$.post( wcv_frontend_product_variation.ajax_url, data, function( response ) {
				var variation = $( response );
				wcv_product_variations_ajax.load_default_attributes(); 
				$( '#wcv_variable_product_options' ).find( '.wcv_variations' ).prepend( variation );
				$( '#wcv_variable_product_options' ).trigger( 'wcv_variations_added', 1 );
				wcv_product_variations_ajax.unblock();
			});

			return false;
		},

		/**
		 * Remove variation
		 *
		 * @return {Bool}
		 */
		remove_variation: function() {

			if ( window.confirm( wcv_frontend_product_variation.i18n_remove_variation ) ) {
 
				var $parent = $( this ).parent().parent().parent().parent(); 
				var variation_id = $parent.attr( 'rel' ); 
				var loop = $parent.data( 'loop' ); 
				$parent.remove(); 
				wcv_product_variations_ajax.add_deleted_variation( variation_id, loop ); 
				wcv_product_variations_counts.update_variations_count( -1 ); 
				wcv_product_variations_ajax.check_total_variations();
				return false; 
			}

		},

		/**
		*	Add a variation_id to the deleted list
		*/ 
		add_deleted_variation: function( variation_id, loop ){ 

			// Only run this for variations that have come from the db 
			if ( variation_id != 0 ) { 

				var variation_ids = $( '#wcv_deleted_variations' ).data( 'variations' ); 
				var tempObj = {}; 

				if ( jQuery.isEmptyObject( variation_ids ) ) { 
					variation_ids = []; 
				} 
				tempObj['loop'] = loop; 
				tempObj['id'] = variation_id; 
				variation_ids.push( tempObj ); 
				$( '#wcv_deleted_variations' ).data( 'variations', variation_ids ); 
				$( '#wcv_deleted_variations' ).val( JSON.stringify( variation_ids ) );
				return false; 
			} 

		}, 

		/**
		*	Delete all variations from the UI and set the delete all for the backend
		*/ 	
		delete_all_variations: function() { 

			$( '.wcv_variation').each( function() { 
				var variation_id = $( this ).attr( 'rel' ); 
				var loop = $( this ).data( 'loop' ); 
				$( this ).remove(); 
				wcv_product_variations_ajax.add_deleted_variation( variation_id, loop ); 
				wcv_product_variations_counts.update_variations_count( -1 ); 
				wcv_product_variations_ajax.check_total_variations();
			}); 

			return false; 

		}, 

		/**
		 * Link all variations (or at least try :p)
		 *
		 * @return {Bool}
		 */
		link_all_variations: function() {
		
			if ( window.confirm( wcv_frontend_product_variation.i18n_link_all_variations ) ) {

				wcv_product_variations_ajax.block();
				var available_variations = []; 
				var existing_variations =  $( '.wcv_variation' ).size(); 
				
				// Get available variations set in the UI 
				$( '.wcv_variation' ).each( function( index, el ){ 

					var existing_variation = {}; 

					// check to see if ANY of the selects are missing a value 
					var missing_attribute = $( this ).find( '.variation_attribute' ).filter( function() {
	       	 			return this.value === "";
	    			});

					// Exit the loop 
	    			if ( missing_attribute.length ) { 
	    				return true; 
	    			}

					$( this ).find( '.variation_attribute' ).each( function() {
						existing_variation[ $( this ).attr( 'name' ).split('[')[0] ] = $( this ).val(); 
					});	

					available_variations.push( existing_variation ); 

				}); 

				// Load all variations via ajax 
				var data = { 
					action: 'wcv_json_link_all_variations',
					parent_data: parent_obj,
					loop: $( '.wcv_variation' ).size(), 
					attributes: $( '#wcv-variation-attributes' ).data( 'variation_attr' ), 
					available_variations: available_variations, 
					security: wcv_frontend_product_variation.wcv_json_link_all_variations_nonce
				}; 

				$.post( wcv_frontend_product_variation.ajax_url, data, function( response ) {

					var variations = $( response );
					wcv_product_variations_ajax.load_default_attributes(); 
					$( '#wcv_variable_product_options' ).find( '.wcv_variations' ).prepend( variations );
					var variations_count = parseInt( $( '.wcv_variation' ).length ) - parseInt( existing_variations ) ;

					// Display how many variations were added 
					if ( 1 === variations_count ) {
						window.alert( variations_count + ' ' + wcv_frontend_product_variation.i18n_variation_added );
					} else if ( 0 === variations_count || variations_count > 1 ) {
						window.alert( variations_count + ' ' + wcv_frontend_product_variation.i18n_variations_added );
					} else { 
						window.alert( wcv_frontend_product_variation.i18n_no_variations_added );
					}

					wcv_product_variations_counts.update_variations_count( variations_count );

					// $( '#wcv_variable_product_options' ).trigger( 'wcv_variations_added', variations_count );
					wcv_product_variations_ajax.unblock();
				});

				return false;
			} 
		},

		/**
		 * Add new class when have changes in some input
		 */
		input_changed: function() {
			$( this )
				.closest( '.wcv_variation' )
				.addClass( 'variation-needs-update' );

			$( 'button.cancel-variation-changes, button.save-variation-changes' ).removeAttr( 'disabled' );

			$( '#wcv_variable_product_options' ).trigger( 'wcv_variations_input_changed' );
		},

		/**
		*	Populate parent
		*/ 
		populate_parent: function() {

			parent_obj[ 'title' ] 		= $( '#post_title' ).val(); 
			parent_obj[ 'sku' ] 		= $( '#_sku' ).val(); 
			parent_obj[ 'weight' ]		= $( '#_weight' ).val(); 
			parent_obj[ 'length' ]		= $( '#_length' ).val(); 
			parent_obj[ 'width' ]		= $( '#_width' ).val(); 
			parent_obj[ 'height' ]		= $( '#_height' ).val(); 
			parent_obj[ 'tax_status' ]	= $( '#_tax_status' ).val(); 

		}, 

		/**
		 * Added new .variation-needs-update class when defaults is changed
		 */
		defaults_changed: function() {
			$( this )
				.closest( '#wcv_variable_product_options' )
				.find( '.wcv_variation:first' )
				.addClass( 'variation-needs-update' );

			$( '#wcv_variable_product_options' ).trigger( 'wcv_variations_defaults_changed' );
		},

		/**
		 * Actions
		 */
		do_variation_action: function() {

			var do_variation_action = $( 'select.variation_actions' ).val(),
				data       = {},
				changes    = 0,
				value;

			// populate the parent object 
			wcv_product_variations_ajax.populate_parent(); 

			switch ( do_variation_action ) {
				case 'add_variation' :
					wcv_product_variations_ajax.add_variation();
					return;
				case 'link_all_variations' :
					wcv_product_variations_ajax.link_all_variations();
					return;
				case 'delete_all' :
					if ( window.confirm( wcv_frontend_product_variation.i18n_delete_all_variations ) ) {
						if ( window.confirm( wcv_frontend_product_variation.i18n_last_warning ) ) {
							wcv_product_variations_ajax.delete_all_variations(); 
						}
					}
					break;
				case 'toggle_variable_enabled' : 
				case 'toggle_variable_is_downloadable' : 
				case 'toggle_variable_is_virtual' :
				case 'toggle_variable_manage_stock' : 
					var selector = do_variation_action.replace( /toggle_/, '' ); 
					$( '.' + selector ).prop("checked", ! $( '.' + selector ).prop("checked") );
					$( '.' + selector ).trigger('change');
					break; 
				case 'variable_regular_price_increase' :
				case 'variable_regular_price_decrease' :
				case 'variable_sale_price_increase' :
				case 'variable_sale_price_decrease' :
					if ( value = window.prompt( wcv_frontend_product_variation.i18n_enter_a_value_fixed_or_percent ) ){ 

						var operator = do_variation_action.indexOf( 'increase' ) > -1 ? '+' : '-'; 
						var field = do_variation_action.indexOf( 'increase' ) > -1 ? do_variation_action.substring( 0, do_variation_action.indexOf( '_increase' ) ) : do_variation_action.substring( 0, do_variation_action.indexOf( '_decrease' ) )
						
						if ( value != null ) {
							if ( value.indexOf( '%' ) >= 0 ) {
								value = accounting.unformat( value.replace( /\%/, '' ), wcv_frontend_product_variation.mon_decimal_point ) + '%';
							} else {
								value = accounting.unformat( value, wcv_frontend_product_variation.mon_decimal_point );
							}
						}
						wcv_product_variations_actions.adjust_price( field, String(value), operator ); 
					} 
					break;
				case 'variable_regular_price' :
					value = window.prompt( wcv_frontend_product_variation.i18n_enter_a_value ); 
					wcv_product_variations_actions.update_input( value, 'variable_regular_price'); 
					break; 
				case 'variable_sale_price' :
					value = window.prompt( wcv_frontend_product_variation.i18n_enter_a_value ); 
					wcv_product_variations_actions.update_input( value, 'variable_sale_price'); 
					break;
				case 'variable_stock' :
					value = window.prompt( wcv_frontend_product_variation.i18n_enter_a_value ); 
					wcv_product_variations_actions.update_input( value, 'variable_stock'); 
					break;
				case 'variable_weight' :
					value = window.prompt( wcv_frontend_product_variation.i18n_enter_a_value ); 
					wcv_product_variations_actions.update_input( value, 'variable_weight'); 
					break;
				case 'variable_length' :
					value = window.prompt( wcv_frontend_product_variation.i18n_enter_a_value ); 
					wcv_product_variations_actions.update_input( value, 'variable_length'); 
					break;
				case 'variable_width' :
					value = window.prompt( wcv_frontend_product_variation.i18n_enter_a_value ); 
					wcv_product_variations_actions.update_input( value, 'variable_width'); 
					break;
				case 'variable_height' :
					value = window.prompt( wcv_frontend_product_variation.i18n_enter_a_value ); 
					wcv_product_variations_actions.update_input( value, 'variable_height'); 
					break;
				case 'variable_download_limit' :
					value = window.prompt( wcv_frontend_product_variation.i18n_enter_a_value ); 
					wcv_product_variations_actions.update_input( value, 'variable_download_limit'); 
					break;
				case 'variable_download_expiry' :
					value = window.prompt( wcv_frontend_product_variation.i18n_enter_a_value ); 
					wcv_product_variations_actions.update_input( value, 'variable_download_expiry'); 
					break;
				case 'variable_sale_schedule' :
					date_from = window.prompt( wcv_frontend_product_variation.i18n_scheduled_sale_start );
					date_to   = window.prompt( wcv_frontend_product_variation.i18n_scheduled_sale_end );

					if ( null === date_from ) {
						date_from = false;
					}
					wcv_product_variations_actions.update_input( date_from, 'sale_price_dates_from'); 

					if ( null === date_to ) {
						date_to = false;
					}
					wcv_product_variations_actions.update_input( date_to, 'sale_price_dates_to'); 
					break;
				default :
					$( 'select.variation_actions' ).trigger( do_variation_action );
					data = $( 'select.variation_actions' ).triggerHandler( do_variation_action + '_ajax_data', data );
					break;
			}

		}
	};

	/**
	 * Product variations counts
	 */
	var wcv_product_variations_counts = {

		/**
		 * Initialize products variations meta box
		 */
		init: function() {
			$( document.body )
				.on( 'wcv_variations_added', this.update_single_quantity ); 
		},

		/**
		 * Set variations count
		 *
		 * @param {Int} qty
		 *
		 * @return {Int}
		 */
		update_variations_count: function( qty ) {
			var wrapper        = $( '#wcv_variable_product_options' ).find( '.wcv_variations' ),
				total          = parseInt( wrapper.attr( 'data-total' ), 10 ) + qty,
				displaying_num = $( '.variations-pagenav .displaying-num' );

			// Set the new total of variations
			wrapper.attr( 'data-total', total );

			if ( 1 === total ) {
				displaying_num.text( wcv_frontend_product_variation.i18n_variation_count_single.replace( '%qty%', total ) );
			} else {
				displaying_num.text( wcv_frontend_product_variation.i18n_variation_count_plural.replace( '%qty%', total ) );
			}

			return total;
		},

		/**
		 * Update variations quantity when add a new variation
		 *
		 * @param {Object} event
		 * @param {Int} qty
		 */
		update_single_quantity: function( event, qty ) {
			if ( 1 === qty ) {
				var page_nav = $( '.variations-pagenav' );

				wcv_product_variations_counts.update_variations_count( qty );

				if ( page_nav.is( ':hidden' ) ) {
					$( 'option, optgroup', '.variation_actions' ).show();
					$( '.variation_actions' ).val( 'add_variation' );
					$( '#wcv_variable_product_options' ).find( '.toolbar' ).show();
					page_nav.show();
					$( '.pagination-links', page_nav ).hide();
				}
			}
		},
		
	};

	// Meta-Boxes - Open/close
	$( '.wcv_product_variations' ).on( 'click', 'h5.variation_title', function( event ) {

		if ( $( event.target ).filter( ':input, option, .wcv-sort' ).length ) {
			return;
		}
		$( this ).parent().parent().parent().find( '.wcv-metabox-content' ).stop().slideToggle();

	})
	.on( 'click', '.expand_all', function() {
		$( this ).closest( '.wcv-metaboxes-wrapper' ).find( '.wcv-metabox > .wcv-metabox-content' ).show();
		return false;
	})
	.on( 'click', '.close_all', function() {
		$( this ).closest( '.wcv-metaboxes-wrapper' ).find( '.wcv-metabox > .wcv-metabox-content' ).hide();
		return false;
	});
	
	// File inputs
	$('.wcv_product_variations').on('click','.downloadable_files a.insert',function(){
		$(this).closest('.downloadable_files').find('tbody').append( $(this).data( 'row' ) );
		return false;
	});

	$('.wcv_product_variations').on('click','.downloadable_files a.delete',function(){
		$(this).closest('tr').remove();
		return false;
	});
 
	$( '.wcv_product_variations' ).on( 'click', '.sale_schedule', function() {
		$('.sale_price_dates_fields').show(); 
		$(this).hide(); 
		$('.cancel_sale_schedule').show(); 
		return false;
	});

	$( '.wcv_product_variations' ).on( 'click', '.cancel_sale_schedule', function() {
		$('.sale_price_dates_fields').hide();
		$(this).hide(); 
		$('.sale_schedule').show(); 
		return false;
	});

	wcv_product_variations_actions.init();
	wcv_product_variations_media.init();
	wcv_product_variations_ajax.init();
	wcv_product_variations_counts.init();



});
