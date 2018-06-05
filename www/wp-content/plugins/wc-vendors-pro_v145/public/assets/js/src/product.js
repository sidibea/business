/* Front end product meta boxes */
jQuery( function( $ ){

	var debug = false; 

	// PRODUCT TYPE SPECIFIC OPTIONS
	$( 'select#product-type' ).change( function () {

		// Get value
		var select_val = $( this ).val();

		if ( 'variable' === select_val ) {
			$( 'input#_manage_stock' ).change();
			$( 'input#_downloadable' ).prop( 'checked', false );
			$( 'input#_virtual' ).removeAttr( 'checked' );
		} else if ( 'grouped' === select_val ) {
			$( 'input#_downloadable' ).prop( 'checked', false );
			$( 'input#_virtual' ).removeAttr( 'checked' );
		} else if ( 'external' === select_val ) {
			$( 'input#_downloadable' ).prop( 'checked', false );
			$( 'input#_virtual' ).removeAttr( 'checked' );
		}

		show_and_hide_panels();

		$( 'ul.wc-tabs li:visible' ).eq(0).find( 'a' ).click();

		$( 'body' ).trigger( 'woocommerce-product-type-change', select_val, $( this ) );

	}).change();

	$('input#_downloadable, input#_virtual').change(function(){
		show_and_hide_panels();
	});

	// Sale price schedule
	$('.sale_price_dates_fields').each( function() { 

		var sale_schedule_set = false; 

		$('.sale_price_dates_fields').find('input').each(function(){
			if ( $(this).val() != '' )
				sale_schedule_set = true;
		});

		if ( sale_schedule_set ) {

			$('.sale_schedule').hide();
			$('.sale_price_dates_fields').show();

		} else {

			$('.sale_schedule').show();
			$('.sale_price_dates_fields').hide();

		}
	}); 

	$('.sale_schedule').on( 'click', function() {
		$('.sale_price_dates_fields').show(); 
		$(this).hide(); 
		$('.cancel_sale_schedule').show(); 
		return false;
	});

	$('.cancel_sale_schedule').on( 'click', function() {
		$('.sale_price_dates_fields').hide();
		$(this).hide(); 
		$('.sale_schedule').show(); 
		return false;
	});


	function show_and_hide_panels() {
		var product_type    = $('#product-type').val();
		var is_virtual      = $('#_virtual').is(':checkbox') ? $('input#_virtual:checked').size() : $('#_virtual').val();
		var is_downloadable = $('#_downloadable').is(':checkbox') ? $('input#_downloadable:checked').size() : $('#_downloadable').val();

		// Hide/Show all with rules
		var hide_classes = '.hide_if_downloadable, .hide_if_virtual';
		var show_classes = '.show_if_downloadable, .show_if_virtual, .show_if_external';

		$.each( wcv_frontend_product.product_types, function( index, value ) {
			hide_classes = hide_classes + ', .hide_if_' + value;
			show_classes = show_classes + ', .show_if_' + value;
		} );

		$( hide_classes ).show().removeClass( 'is_hidden' );
		$( show_classes ).hide().addClass( 'is_hidden' );

		// Shows rules
		if ( is_downloadable ) {
			$('.show_if_downloadable').show().removeClass( 'is_hidden' );
		}
		if ( is_virtual ) {
			$('.show_if_virtual').show().removeClass( 'is_hidden' );
		}

        $('.show_if_' + product_type).show().removeClass( 'is_hidden' );

		// Hide rules
		if ( is_downloadable ) {
			$('.hide_if_downloadable').hide().addClass( 'is_hidden' );
		}
		if ( is_virtual ) {
			$('.hide_if_virtual').hide().addClass( 'is_hidden' );
		}

		if ( product_type == "grouped" ) {
	
			Ink.requireModules( ['Ink.Dom.Selector_1','Ink.UI.Tabs_1'], function( Selector, Tabs ){
        		var tabsObj = new Tabs('#wcv-tabs');
        		tabsObj.changeTab('#linked_product'); 
    		});
		}

		$('.hide_if_' + product_type).hide().addClass( 'is_hidden' );

		$('input#_manage_stock').change();
	}

	
	// STOCK OPTIONS
	$('input#_manage_stock').change(function(){
		if ( $(this).is(':checked') ) {
			$('div.stock_fields').show().removeClass( 'is_hidden' );
		} else {
			$('div.stock_fields').hide().addClass( 'is_hidden' );
		}
	}).change();


	// FEATURED IMAGE 
	// Setting uploader type to true allows multiple selections as required by gallery 
	// todo make translatable 
	function featured_image_uploader() {

		var media_uploader, json;

		var title 			= $( '.wcv-featuredimg' ).data( 'title' ); 
		var button_text 	= $( '.wcv-featuredimg' ).data( 'button_text' ); 

		if (undefined !== media_uploader ) { 
			media_uploader.open(); 
			return; 
		}

	    media_uploader = wp.media({
      		title: title,
      		button: {
        		text: button_text
      		},
      		multiple: false  // Set to true to allow multiple files to be selected
    	});

	    media_uploader.on( 'select' , function(){
	    	json = media_uploader.state().get('selection').first().toJSON(); 

	    	if ( 0 > $.trim( json.url.length ) ) {
		        return;
		    }

		    attachment_image_url = json.sizes.thumbnail ? json.sizes.thumbnail.url : json.url;

		    $( '.wcv-featuredimg' )
		    	.append( '<img src="'+ attachment_image_url + '" alt="' + json.caption + '" title="' + json.title +'" style="max-width: 100%;" />'); 
		    $('#_featured_image_id').val(json.id); 
		    $( '.wcv_featured_image_msg' ).html( '' ); 
		    $( '.wcv-featuredimg').css( 'background-color', '#e1e1e1' ); 

		    $('.wcv-media-uploader-featured-add').addClass('hidden'); 
		    $('.wcv-media-uploader-featured-delete').removeClass('hidden'); 


	    });

	    media_uploader.open();
	}


	$('.wcv-media-uploader-featured-delete').on('click', function(e) { 
		e.preventDefault(); 
		// reset the data so that it can be removed and saved. 
		$('.wcv-featuredimg').html(''); 
		$('#_featured_image_id').val( '' ); 
		$('.wcv-media-uploader-featured-delete').addClass('hidden'); 
		$('.wcv-media-uploader-featured-add').removeClass('hidden'); 

	});

	// Handle Remove Featured Image 
	$('.wcv-media-uploader-featured-add').on( 'click', function(e) { 
		e.preventDefault(); 
		featured_image_uploader(); 
		return false; 
	}); 




	// PRODUCT IMAGE GALLERY 

	/* 
		Product Gallery Uploader 
	*/
	function product_gallery_uploader() { 

		var media_uploader, json;
		var $image_gallery_ids = $('#product_image_gallery');
		var $product_images = $('#product_images_container ul.product_images');
		var attachment_ids = $image_gallery_ids.val();
		var $el = $('.wcv-media-uploader-gallery a'); 

		if (undefined !== media_uploader ) { 
			media_uploader.open(); 
			return; 
		}

	    // Create the media frame.
		media_uploader = wp.media.frames.product_gallery = wp.media({
			// Set the title of the modal.
			title: $el.data('choose'),
			button: {
				text: $el.data('update'),
			},
			states : [
				new wp.media.controller.Library({
					title: $el.data('choose'),
					filterable :	'all', 
					multiple:  true
				})
			]
		});

	    media_uploader.on( 'select' , function( ) { 
	    	
	    	var selection = media_uploader.state().get('selection');

			selection.map( function( attachment ) {

				attachment = attachment.toJSON();

				if ( attachment.id ) {
					attachment_ids   = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;
					attachment_image = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

					$product_images.append('\<li class="wcv-gallery-image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '"><i class="fa fa-times"></i></a></li></ul></li>');

					$( '.wcv_gallery_msg' ).html(''); 
				}

			});

			$image_gallery_ids.val( attachment_ids );

	    });

	    if ( check_gallery_count() ){ 
	    	var gallery_max_msg = $( '#product_images_container' ).data( 'gallery_max_notice' ); 
	    	alert( gallery_max_msg ); 
	    } else { 
	    	// Open the modal 
	   		media_uploader.open();
	    }

	}

	// Remove images
	$('#product_images_container').on( 'click', 'a.delete', function(e) {

		var $image_gallery_ids = $('#product_image_gallery');

		e.preventDefault(); 
		$(this).closest('li.wcv-gallery-image').remove();

		var attachment_ids = '';

		$('#product_images_container ul li.wcv-gallery-image').css('cursor','default').each(function() {
			var attachment_id = jQuery(this).attr( 'data-attachment_id' );
			attachment_ids = attachment_ids + attachment_id + ',';
		});

		$image_gallery_ids.val( attachment_ids );

		// remove any lingering tooltips
		$( '#tiptip_holder' ).removeAttr( 'style' );
		$( '#tiptip_arrow' ).removeAttr( 'style' );

		return false;
	});

	$('.wcv-media-uploader-gallery').on( 'click' , function(e) { 
		e.preventDefault(); 
		product_gallery_uploader(); 
		return false; 
	}); 


	function check_gallery_count(){ 

		var gallery_count 	= $( '.wcv-gallery-image' ).length; 
		var gallery_max 	= $( '#product_images_container' ).data( 'gallery_max_upload' ) -1; 
		
		return ( gallery_count > gallery_max ) ? true : false; 

	}

	// 
	//  File downloads 
	// 
	

	// File inputs
	$('#files_download').on('click','.downloadable_files a.insert',function(){
		$(this).closest('.downloadable_files').find('tbody').append( $(this).data( 'row' ) );
		return false;
	});

	$('#files_download').on('click','.downloadable_files a.delete',function(){
		$(this).closest('tr').remove();
		return false;
	});

	// Uploading files
	var downloadable_file_frame;
	var file_path_field;

	$(document).on( 'click', '.upload_file_button', function( event ){

		var $el = $(this);

		file_path_field = $el.closest('tr').find('.file_url');
		file_id_field = $el.closest('tr').find('.file_id');
		file_display_field = $el.closest('tr').find('.file_display');

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( downloadable_file_frame ) {
			downloadable_file_frame.open();
			return;
		}

		var downloadable_file_states = [
			// Main states.
			new wp.media.controller.Library({
				library:   wp.media.query(),
				multiple:  true,
				title:     $el.data('choose'),
				priority:  20,
				filterable: 'uploaded',
			})
		];

		// Create the media frame.
		downloadable_file_frame = wp.media.frames.downloadable_file = wp.media({
			// Set the title of the modal.
			title: $el.data('choose'),
			library: {
				type: ''
			},
			button: {
				text: $el.data('update'),
			},
			multiple: true,
			states: downloadable_file_states,
		});

		// When an image is selected, run a callback.
		downloadable_file_frame.on( 'select', function() {

			var file_path = '';
			var file_display = ''; 
			var file_id = 0; 
			var selection = downloadable_file_frame.state().get('selection');

			selection.map( function( attachment ) {

				if ( wcv_frontend_product.wcv_file_display == 'file_url' ){ 
					file_display = attachment.attributes.url; 
				} else { 
					file_display = attachment.attributes.filename;  
				}

				attachment = attachment.toJSON();

				if ( attachment.url )
					file_path = attachment.url

				if ( attachment.id )
					file_id = attachment.id 

			} );

			file_path_field.val( file_path );
			file_display_field.val( file_display );
			file_id_field.val( file_id ); 

		});

		// Set post to 0 and set our custom type
		downloadable_file_frame.on( 'ready', function() {
			downloadable_file_frame.uploader.options.uploader.params = {
				type: 'downloadable_product'
			};
		});

		// Finally, open the modal.
		downloadable_file_frame.open();
	});

	// Download ordering
	$('.downloadable_files tbody').sortable({
		items:'tr',
		cursor:'move',
		axis:'y',
		handle: 'td.sort',
		scrollSensitivity:40,
		forcePlaceholderSize: true,
		helper: 'clone',
		opacity: 0.65,
	});

	// 
	//  Shipping Rates 
	// 

	// Flat Rates 
	function enable_disable( disable_input, toggle_inputs ){ 

		if ( $( disable_input ).is(':checked') ) {
			toggle_inputs.prop( 'disabled', true ); 
			
			toggle_inputs.each(function() {
			  if ( $(this).is(':checkbox') ) { 
			  	$(this).removeAttr('checked');
			  } else { 
			  	$(this).val(''); 
			  }
			});

		} else {
			toggle_inputs.prop( 'disabled', false ); 
		}
	}

	// Disable national shipping 
	$( '#_shipping_fee_national_disable' ).change(function() { enable_disable( $( this ), $( '.wcv-disable-national-input' ) ); } ); 
	// Toggle Free shipping 
	$( '#_shipping_fee_national_free' ).change(function() { enable_disable( $( this ), $( '#_shipping_fee_national' ) ); } ); 

	// International 
	// Disable international shipping 
	$( '#_shipping_fee_international_disable' ).change(function() { enable_disable( $( this ), $( '.wcv-disable-international-input' ) ); } ); 
	// Free shipping 
	$( '#_shipping_fee_international_free' ).change(function() { enable_disable( $( this ), $( '#_shipping_fee_international' ) ); } ); 

	// Country Rates 
	$('#shipping').on('click','.wcv_shipping_rates a.insert',function(){
		var select2_args = {
					placeholderOption: 'first',
					width: '100%'
				}
		$(this).closest('.wcv_shipping_rates').find('tbody').append( $(this).data( 'row' ) ).find( 'select' ).select2( select2_args );
		return false;
	});

	$('#shipping').on('click','.wcv_shipping_rates a.delete',function(){
		$(this).closest('tr').remove();
		return false;
	});

	// shipping rate ordering
	$('.wcv_shipping_rates tbody').sortable({
		items:'tr',
		cursor:'move',
		axis:'y',
		handle: 'td.sort',
		scrollSensitivity:40,
		forcePlaceholderSize: true,
		helper: 'clone',
		opacity: 0.65,
	});

	
	// Enable selects to use the select2 enhanced select
	$('.select2').select2();  
	// Categories 
	$('.category-select2').select2( { maximumSelectionSize: wcv_frontend_product.category_limit } ); 

	$( '.wcv_category_check').on( 'change', function() {
		if ( wcv_frontend_product.category_limit > 0 ){ 
			terms_list_limit( $( this ) ); 
		}
	}); 

	function terms_list_limit( el ){ 

		var category_limit = wcv_frontend_product.category_limit; 
		var category_count = $( ".wcv_category_check:checked" ).length;

		if ( category_count > category_limit) {
         	$( el ).prop( 'checked', '' );
        	$('.wcv_check_list_msg') .html( wcv_frontend_product.category_limit_msg ); 
        	$( '.wcv_terms_checklist_container').css( 'background-color', 'rgba(150,8,12,.55)' ); 
     	} else { 
     		$('.wcv_check_list_msg') .html( '' ); 
     		$( '.wcv_terms_checklist_container').css( 'background-color', '' ); 
     	}
	}


	show_and_hide_panels();

	// On load shipping enable and disable 
	enable_disable( $( '#_shipping_fee_national_free' ),  $( '#_shipping_fee_national' ) ); 
	enable_disable( $( '#_shipping_fee_international_free' ),  $( '#_shipping_fee_international' ) ); 
	enable_disable( $( '#_shipping_fee_national_disable' ),  $( '.wcv-disable-national-input' )  ); 
	enable_disable( $( '#_shipping_fee_international_disable' ), $( '.wcv-disable-international-input' ) );

	//  Product Confirm Delete 
	$('.confirm_delete').on( 'click', function(e) { 
		if ( ! confirm( $( this ).data('confirm_text') ) ) e.preventDefault(); 
	}); 

	// ATTRIBUTE TABLES

	// Initial order
	var woocommerce_attribute_items = $('.product_attributes').find( '.woocommerce_attribute' ).get();

	woocommerce_attribute_items.sort(function(a, b) {
	   var compA = parseInt( $( a ).attr( 'rel' ), 10 );
	   var compB = parseInt( $( b ).attr( 'rel' ), 10 );
	   return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
	});

	$( woocommerce_attribute_items ).each( function( idx, itm ) {
		$( '.product_attributes' ).append(itm);
	});

	$( '.product_attributes .woocommerce_attribute' ).each( function( index, el ) {
		if ( $( el ).css( 'display' ) !== 'none' && $( el ).is( '.taxonomy' ) ) {
			$( 'select.attribute_taxonomy' ).find( 'option[value="' + $( el ).data( 'taxonomy' ) + '"]' ).attr( 'disabled', 'disabled' );
		}
	});

	// Close  / Expand 
	$( '.wcv_product_attributes' ).on( 'click', '.wcv-metabox h5', function( event ) {

		if ( $( event.target ).filter( ':input, option, .sort' ).length ) {
			return;
		}

		$( this ).next( '.wcv-metabox-content' ).stop().slideToggle();
	})
	.on( 'click', '.expand_all', function() {
		$( this ).closest( '.wcv_product_attributes' ).find( '.wcv-metabox > .wcv-metabox-content' ).show();
		return false;
	})
	.on( 'click', '.close_all', function() {
		$( this ).closest( '.wcv_product_attributes' ).find( '.wcv-metabox > .wcv-metabox-content' ).hide();
		return false;
	});
	$( '.wcv-metabox.closed' ).each( function() {
		$( this ).find( '.wcv-metabox-content' ).hide();
	});

	$( function( e ) { 

		// Add rows
		$( 'button.add_attribute' ).on( 'click', function() {

			var size         = $( '.product_attributes .woocommerce_attribute' ).size();
			var attribute    = $( 'select.attribute_taxonomy' ).val();

			if ( attribute ) { 
				var $attributes  = $( '.product_attributes '); 
				var product_type = $( '#product-type' ).val();
				var data         = {
					action:   'wcv_json_add_attribute',
					taxonomy: attribute,
					i:        size,
					security: wcv_frontend_product.wcv_add_attribute_nonce
				};

				$.post( wcv_frontend_product.ajax_url, data, function( response ) {

					if ( response.error ) {
						// Error
						window.alert( response.error );
					} else { 

						$attributes.append( response );

						$('select.attribute_values.select2').select2( 'destroy' ).select2();

						if ( product_type !== 'variable' ) {
							$attributes.find( '.enable_variation' ).hide();
						}

						$( document.body ).trigger( 'wc-enhanced-select-init' );
						attribute_row_indexes();

						$( document.body ).trigger( 'woocommerce_added_attribute' );

					}
					
				});

				if ( attribute ) {
					$( 'select.attribute_taxonomy' ).find( 'option[value="' + attribute + '"]' ).attr( 'disabled','disabled' );
					$( 'select.attribute_taxonomy' ).val( '' );
				}

				return false;
			}
		});

		//  Select all terms 
		$( '.product_attributes' ).on( 'click', 'button.select_all_attributes', function() {

			var index_value = $( this ).parent().data( 'index_value' ); 

			$( '#attribute_values_' + index_value + ' > option' ).prop( 'selected', 'selected' ); 
			$( '#attribute_values_' + index_value ).trigger( 'change' ); 

			return false;
		});

		// Unselect all terms 
		$( '.product_attributes' ).on( 'click', 'button.select_no_attributes', function() {

			var index_value = $( this ).parent().data( 'index_value' ); 
			var taxonomy = $( this ).parent().data( 'taxonomy' ); 

			$( '#attribute_values_' + index_value + ' > option' ).removeAttr( 'selected' ); 
			$( '#attribute_values_' + index_value ).trigger( 'change' ); 

			if ( $( '#attribute_variation_' + index_value ).is(':checked') ) { 
				remove_attribute_variations( taxonomy );  
			}

			return false;
		});

		// Add attribute variations based on the selected attribute values for this attribute 
		function add_attribute_variation( values, taxonomy, position, label ){ 

			// get existing variation attributes if there is any 
			var wcv_variation_attributes = $( '#wcv-variation-attributes' ).data( 'variation_attr' ); 
			// create the empty object first if required
			if ( jQuery.isEmptyObject( wcv_variation_attributes ) ) wcv_variation_attributes = {}; 
			
			var attr_var = {}; 

			attr_var[ 'values' ] 	= values; 
			attr_var[ 'position' ] 	= position; 
			attr_var[ 'name']		= taxonomy; 
			attr_var[ 'label' ]		= label; 

			wcv_variation_attributes[ taxonomy ] = attr_var; 

			$( '#wcv-variation-attributes' ).data( 'variation_attr', wcv_variation_attributes ); 

			if ( debug ) console.log( $( '#wcv-variation-attributes' ).data( 'variation_attr' ) ); 

			return false;

		} // add_attribute_variation() 

		// Remove the attribute variations 
		function remove_attribute_variations( taxonomy ){ 

			var wcv_variation_attributes = $( '#wcv-variation-attributes' ).data( 'variation_attr' ); 

			if ( ! jQuery.isEmptyObject( wcv_variation_attributes ) ){ 

				// remove the attribute from the global object 
				delete wcv_variation_attributes[ taxonomy ]; 

				$( '#wcv-variation-attributes' ).data( 'variation_attr', wcv_variation_attributes ); 
				
				if ( debug ) console.log( $( '#wcv-variation-attributes' ).data( 'variation_attr' ) );
				
			}

			return false;

		} // remove_attribute_variations()

		// Toggle attributes available or not to variations 
		function toggle_attributes( el, position, label ) { 

			var attr_vals = [];
			var attr_obj = {};  
			var taxonomy = el.parent().data( 'taxonomy' ); 
			var index_value = el.parent().data( 'index_value' ); 

			if ( $( '#attribute_variation_' + index_value ).is(':checked') ) { 

				if( el.is( 'input' ) ) { 
				        
			        var data = el.val().split( wcv_frontend_product.wc_deliminator ); 

			        $.each( data, function( index, value ) { 
			        	if ( $.trim( value ).length > 0 ){ 
			        		attr_obj[ $.trim( value ).toLowerCase() ] = $.trim( value ); 
			        	}
			    	}); 

			    } else {

			    	var data = el.select2('data'); 

			    	$.each( data, function( index, value ) { 
		    			attr_obj[ value.id ] = value.text; 
			    	}); 
			    }
			} 

		    // Only fire if there are values to add
		    if ( ! jQuery.isEmptyObject( attr_obj ) ){ 
				add_attribute_variation( attr_obj, taxonomy, position, label ); 
			} else { 
				remove_attribute_variations( taxonomy ); 
			}

		} // toggle_attributes() 

		function load_attributes(){ 

			$( '.attribute_values' ).each( function( ) { 

				var index_value = $( this ).parent().data( 'index_value' ); 
				var position 	= $( '#attribute_position_' + index_value ).val(); 
				var label 		= $( this ).closest( '.woocommerce_attribute').data('label'); 

				toggle_attributes( $( this ), position, label ); 

			}); 

		} // load_attributes() 

		$( '.product_attributes' ).on( 'change', '.attribute_values', function() {

			var index_value = $( this ).parent().data( 'index_value' ); 
			var position 	= $( '#attribute_position_' + index_value ).val(); 
			var label 		= $( this ).closest( '.woocommerce_attribute').data('label'); 

			toggle_attributes( $( this ), position, label ); 
		}); 

		// fire if adding or removing this attribute from available variations 
		$( '.product_attributes' ).on( 'change', '.wcv_variation_checkbox', function() {

			var index_value = $( this ).parent().parent().parent().data( 'index_value' ); 
			var taxonomy 	= $( this ).closest( '.woocommerce_attribute').data('taxonomy'); 
			var label 		= $( this ).closest( '.woocommerce_attribute').data('label'); 
			var position 	= $( '#attribute_position_' + index_value ).val(); 

			var el = $('#attribute_values_' + index_value ); 

			if ( $( this ).is( ':checked' ) ) {
				toggle_attributes( el, position, label ); 
			} else {
				remove_attribute_variations( taxonomy );  
			}
		}); 

		$( '.product_attributes' ).on( 'click', '.remove_row', function() {

			if ( window.confirm( wcv_frontend_product.remove_attribute ) ) {

				var $parent = $( this ).parent().parent();

				if ( $parent.is( '.taxonomy' ) ) {
					$parent.find( 'select, input[type=text]' ).val('');
					$parent.hide();
					$( '.attribute_taxonomy' ).find( 'option[value="' + $parent.data( 'taxonomy' ) + '"]' ).removeAttr( 'disabled' );
				} else {
					$parent.find( 'select, input[type=text]' ).val('');
					$parent.hide();
					attribute_row_indexes();
				}

				remove_attribute_variations( $parent.data('taxonomy') ); 

			}
			return false;
		});

		// Attribute ordering
		$( '.product_attributes' ).sortable( {
			items: '.woocommerce_attribute',
			cursor: 'move',
			axis: 'y',
			handle: 'h5',
			scrollSensitivity: 40,
			forcePlaceholderSize: true,
			helper: 'clone',
			opacity: 0.65,
			placeholder: 'wcv-metabox-sortable-placeholder',
			start: function( event, ui ) {
				ui.item.css( 'background-color', '#f6f6f6' );
			},
			stop: function( event, ui ) {
				var index_value = ui.item.data( 'index_value' );
				ui.item.removeAttr( 'style' );
				attribute_row_indexes( index_value );
			}
		});

		// Adjust attribute row indexes then toggle the object storage 
		function attribute_row_indexes() {
			$( '.product_attributes .woocommerce_attribute' ).each( function( index, el ) {
				$( '.attribute_position', el ).val( parseInt( $( el ).index( '.product_attributes .woocommerce_attribute' ), 10 ) );
				// toggle after sorting to ensure positions are correct 
				var index_value = $( this ).data( 'index_value' ); 
				var el 			= $('#attribute_values_' + index_value ); 
				var position 	= $( '#attribute_position_' + index_value ).val(); 
				var label 		= $( this ).data('label'); 
				toggle_attributes( el, position, label ); 
			});			

		}
			
		attribute_row_indexes(); 
		load_attributes(); 

	}); // end function wrapper for attribute variation detection

	// 
	// Add a new attribute (via ajax)
	//  
	// 
	$( '.product_attributes' ).on( 'click', 'button.add_new_attribute', function() {

		$( '.product_attributes' ).block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });

		var index_value 		= $( this ).parent().data( 'index_value' ); 
		var attr_select 		= $( '#attribute_values_' + index_value ); 
		var taxonomy 			= $( this ).parent().data( 'taxonomy' ); 
		var new_attribute_name 	= window.prompt( wcv_frontend_product.new_attribute_prompt );

		if ( new_attribute_name ) {

			var data = {
				action:   'wcv_json_add_new_attribute',
				taxonomy: taxonomy,
				term:     new_attribute_name,
				security: wcv_frontend_product.wcv_add_attribute_nonce
			};

			$.post( wcv_frontend_product.ajax_url, data, function( response ) {

				if ( response.error ) {
					// Error
					window.alert( response.error );
				} else if ( response.slug ) {
					// Success
					attr_select.append( $( '<option />' ).attr( 'value', response.slug ).prop( 'selected', 'selected' ).text( response.name ) ); 
					attr_select.trigger( 'change' ); 
					$( attr_select ).select2( 'destroy' ).select2();
				}

				$( '.product_attributes' ).unblock();
			});

		} else {
			$( '.product_attributes' ).unblock();
		}

		return false;
	});

	// Check for required fields 
	$( '#wcv-product-edit' ).submit( function( e ) { 

		// Check if featured image is required 
		if ( wcv_frontend_product.require_featured_image == 1 ){ 

			if ( $('#_featured_image_id').val() === '' ){ 

				$( '.wcv_featured_image_msg' ).html( wcv_frontend_product.require_featured_image_msg ); 
				$( '.wcv-featuredimg').css( 'background-color', 'rgba(150,8,12,.55)' ); 
				e.preventDefault(); 

			} 

		}

		// Check if gallery images are required 
		if ( wcv_frontend_product.require_gallery_image == 1 ){ 

			if ( $('#product_image_gallery').val() === '' ){ 

				$( '.wcv_gallery_msg' ).html( wcv_frontend_product.require_gallery_image_msg ); 
				e.preventDefault(); 
			} 

		}

		// Check if categories is required 
		if ( wcv_frontend_product.require_category == 1 ){ 

			// Categories checklist 
			if ( $( '.wcv_category_check' ).length ) { 

				var category_count = $( '.wcv_category_check:checked' ).length;

				if ( category_count <= 0 ) {
		        	$('.wcv_check_list_msg') .html( wcv_frontend_product.require_category_msg ); 
		        	$( '.wcv_terms_checklist_container').css( 'background-color', 'rgba(150,8,12,.55)' ); 
		        	e.preventDefault(); 
		     	}

			} 

			// Category select2 
			var $category_select = $( '#s2id_product_cat\\[\\]' );

			// Check if the page has this element to begin with 
			if ( $category_select.length > 0 ){ 

				var category_select_count = $category_select.find( 'li.select2-search-choice' ).length;

				if ( category_select_count <= 0 ) {
					$category_select.append( '<span class="wcv_category_msg">' + wcv_frontend_product.require_category_msg + '</span>'); 
					e.preventDefault(); 

				} 

			}

		}

		// Check if a tag is required 
		if ( wcv_frontend_product.require_tag == 1 ){ 

			// Tag select2 
			var $tag_select = $( '#s2id_product_tags\\[\\]' );

			if ( $tag_select.length > 0 ){ 

				var tag_count = $tag_select.find( 'li.select2-search-choice' ).length;

				if ( tag_count <= 0 ) {
					$tag_select.append( '<span class="wcv_required_form_msg">' + wcv_frontend_product.require_tag_msg + '</span>'); 
					e.preventDefault(); 

				} 
			}
		}

		// Check if cross sell is required 
		if ( wcv_frontend_product.require_crosssells == 1 ){ 

			// Crosssell select2 
			var $crosssell_ids_select = $( '#s2id_crosssell_ids' );

			if ( $crosssell_ids_select.length > 0 ){ 

				var cross_sell_count = $crosssell_ids_select.find( 'li.select2-search-choice' ).length;

				if ( cross_sell_count <= 0 ) {
					$crosssell_ids_select.append( '<span class="wcv_required_form_msg">' + wcv_frontend_product.require_crosssells_msg + '</span>'); 
					e.preventDefault(); 

				} 
			}
		}

		// Check if upsell is required 
		if ( wcv_frontend_product.require_up_sells == 1 ){ 

			// Upsell select2 
			var $upsell_ids_select = $( '#s2id_upsell_ids' );

			if ( $upsell_ids_select.length > 0 ){ 

				var upsell_count = $upsell_ids_select.find( 'li.select2-search-choice' ).length;

				if ( upsell_count <= 0 ) {
					$upsell_ids_select.append( '<span class="wcv_required_form_msg">' + wcv_frontend_product.require_up_sells_msg + '</span>'); 
					e.preventDefault(); 

				} 
			}
		}

		// Download file is required 
		if ( wcv_frontend_product.require_download_file == 1 ){ 

			var $download_file_table = $( '.download_file_table' ); 
			var file_count =  $( '.download_file').length; 
			var validated = true 
			var parent = $( '.downloadable_files' ).parent(); 

			if ( ! $( '.downloadable_files' ).parent().hasClass( 'is_hidden' ) ){ 

				if ( file_count > 0 ){ 
				 $( '.download_file').each( function( index ) {

				 	if ( $( this ).find(' td.file_name').find( 'input' ).val() === '' || $( this ).find(' td.file_url').find( 'input' ).val() === ''  ){ 
				 		validated = false; 
				 	}

				 }); 
				} else { 
					validated = false; 
				}

				if ( ! validated ){ 
					$( '.downloadable_files').append( '<span class="wcv_required_form_msg">' + wcv_frontend_product.require_download_file_msg + '</span>'); 
					e.preventDefault(); 
				}

			}

		} 	// END:  Download file is required 


		// Attributes required 
		if ( wcv_frontend_product.require_attributes == 1 ){ 

			var attribute_container_count = $( '.woocommerce_attribute ').length;

			if ( attribute_container_count > 0 ) { 

				$( '.wcv_attribute_data').each( function( index ) {

					if ( $( this ).find( '.attribute_values .select2-choices ').length < 1  ){ 

						$( '#attributes').prepend( '<span class="wcv_required_form_msg">' + wcv_frontend_product.require_attributes_msg + '</span><br />'); 
						$( 'a.tabs-tab.attributes').css( 'background-color', 'rgba(150,8,12,.55)' ); 
						$( 'a.tabs-tab.attributes').css( 'color', 'rgba(255,255,255,.55)' ); 

						e.preventDefault(); 
					}

				}); 


			} else { 

				$( '#attributes').prepend( '<span class="wcv_required_form_msg">' + wcv_frontend_product.require_attributes_msg + '</span><br />'); 
				$( 'a.tabs-tab.attributes').css( 'background-color', 'rgba(150,8,12,.55)' ); 
				$( 'a.tabs-tab.attributes').css( 'color', 'rgba(255,255,255,.55)' ); 
				e.preventDefault(); 
			}

		} 

	}); 
	// END: Check for required fields 

	

});