(function( $ ) {
	'use strict';

	// On load check which screen we're on based on element and adjust as necessary
	$( window ).load( function() {

		var commission_type, amount_row, percent_row,fee_row; 

		if ( $( '#wcv_commission_type' ).length ) { 
			commission_type = $( '#wcv_commission_type' ).val(); 
			amount_row 		= $( '.wcv_commission_amount_input'); 
			percent_row 	= $( '.wcv_commission_percent_input');
			fee_row 		= $( '.wcv_commission_fee_input'); 
			toggle_commission_fields( commission_type, amount_row, percent_row, fee_row ); 

		} else if ( $( '#_wcv_commission_type' ).length ) { 
			commission_type = $( '#_wcv_commission_type' ).val(); 
			amount_row 		= $( '._wcv_commission_amount_input'); 
			percent_row 	= $( '._wcv_commission_percent_input');
			fee_row 		= $( '._wcv_commission_fee_input'); 
			toggle_commission_fields( commission_type, amount_row, percent_row, fee_row ); 

		} else if ( $('#commission_type').length ) { 
			commission_type = $('#commission_type').val(); 
			amount_row 		= $('#commission_amount' ).closest('tr');
			percent_row 	= $('#commission_percent' ).closest('tr'); 
			fee_row 		= $('#commission_fee' ).closest('tr'); 
			toggle_commission_fields( commission_type, amount_row, percent_row, fee_row ); 
		}
	});

	// Product Commission fields 
	$('#_wcv_commission_type').on('change', function( ) { 

		var commission_type = $(this).val();
		var amount_row 		= $('._wcv_commission_amount_input'); 
		var percent_row 	= $('._wcv_commission_percent_input');
		var fee_row 		= $('._wcv_commission_fee_input'); 

		toggle_commission_fields( commission_type, amount_row, percent_row, fee_row ); 
		
	}); 

	// Product Commission fields 
	$('#wcv_commission_type').on('change', function( ) { 

		var commission_type = $(this).val();
		var amount_row 		= $('.wcv_commission_amount_input'); 
		var percent_row 	= $('.wcv_commission_percent_input');
		var fee_row 		= $('.wcv_commission_fee_input'); 

		toggle_commission_fields( commission_type, amount_row, percent_row, fee_row ); 
		
	}); 

	// Global settings fields 
	$('#commission_type').on('change', function( ) { 

		var commission_type = $(this).val(); 
		var amount_row 		= $('#commission_amount').closest('tr');
		var percent_row 	= $('#commission_percent').closest('tr'); 
		var fee_row 		= $('#commission_fee').closest('tr'); 

		toggle_commission_fields( commission_type, amount_row, percent_row, fee_row ); 

	}); 

	function toggle_commission_fields( commission_type, amount_row, percent_row, fee_row ) { 

		switch( commission_type ) {
		    case 'fixed':
		    	amount_row.show(); 
		        percent_row.hide(); 
		        fee_row.hide(); 
		        break;
		    case 'fixed_fee':
		    	amount_row.show(); 
		    	fee_row.show(); 
		    	percent_row.hide(); 
		        break;
		    case 'percent':
			    percent_row.show(); 
		    	amount_row.hide(); 
		        fee_row.hide(); 
		        break;    
		    case 'percent_fee':
			    percent_row.show(); 
			    fee_row.show(); 
		    	amount_row.hide(); 
		        break;    
		    default:
		    	amount_row.hide(); 
				percent_row.hide(); 
				fee_row.hide(); 
		}

	}


	if ( $( '.wcv-file-uploader_wcv_store_banner_id' ).find('img').length > 0 ){ 
		$('#_wcv_add_wcv_store_banner_id').hide(); 
	} else { 
		$('#_wcv_remove_wcv_store_banner_id').hide(); 
	}

	if ( $( '.wcv-file-uploader_wcv_store_icon_id' ).find('img').length > 0 ){ 
		$('#_wcv_add_wcv_store_icon_id').hide(); 
	} else { 
		$('#_wcv_remove_wcv_store_icon_id').hide(); 
	}

	// Handle Add banner
	$('#_wcv_add_wcv_store_banner_id').on( 'click', function(e) { 
		e.preventDefault(); 
		file_uploader( '_wcv_store_banner_id' ); 
		return false; 
	}); 

	$('#_wcv_remove_wcv_store_banner_id').on('click', function(e) { 
		e.preventDefault(); 
		// reset the data so that it can be removed and saved. 
		var upload_notice = $('#_wcv_store_banner_id').data('upload_notice'); 
		$( '.wcv-file-uploader_wcv_store_banner_id' ).html(''); 
		$( '.wcv-file-uploader_wcv_store_banner_id' ).append( upload_notice ); 
		$( '#_wcv_store_banner_id').val(''); 
		$('#_wcv_add_wcv_store_banner_id').show(); 
		$('#_wcv_remove_wcv_store_banner_id').hide(); 
	});

	// Handle Add banner
	$('#_wcv_add_wcv_store_icon_id').on( 'click', function(e) { 
		e.preventDefault(); 
		file_uploader( '_wcv_store_icon_id' ); 
		return false; 
	}); 

	$('#_wcv_remove_wcv_store_icon_id').on('click', function(e) { 
		e.preventDefault(); 
		// reset the data so that it can be removed and saved. 
		var upload_notice = $('#_wcv_store_icon_id').data('upload_notice'); 
		$( '.wcv-file-uploader_wcv_store_icon_id' ).html(''); 
		$( '.wcv-file-uploader_wcv_store_icon_id' ).append( upload_notice ); 
		$( '#_wcv_store_icon_id').val(''); 
		$('#_wcv_add_wcv_store_icon_id').show(); 
		$('#_wcv_remove_wcv_store_icon_id').hide(); 
	});



	function file_uploader( id )
	{

		var media_uploader, json, attachment_image_url;

		if ( undefined !== media_uploader ) { 
			media_uploader.open(); 
			return; 
		}

	    media_uploader = wp.media({
      		title: $( '#' + id ).data('window_title'), 
      		button: {
        		text: $( '#' + id ).data('save_button'), 
      		},
      		multiple: false  // Set to true to allow multiple files to be selected
    	});

	    media_uploader.on( 'select' , function(){
	    	json = media_uploader.state().get('selection').first().toJSON(); 

	    	if ( 0 > $.trim( json.url.length ) ) {
		        return;
		    }

		    attachment_image_url = json.sizes.thumbnail ? json.sizes.thumbnail.url : json.url;

		    $( '.wcv-file-uploader' + id )
		    	.html( '<img src="'+ attachment_image_url + '" alt="' + json.caption + '" title="' + json.title +'" style="max-width: 100%;" />' ); 
		    
		    $('#' + id ).val( json.id ); 

			$('#_wcv_add' + id ).hide(); 
			$('#_wcv_remove' + id ).show(); 

	    });

	    media_uploader.open();
	}

	// Show / Hide shipping types for the user edit screen 
	if ( wcv_admin.screen_id == 'user-edit' ){ 
		// Hide both shipping rates 
		$( '.wcv-shipping-rates').hide(); 
		// Show the global current one 
		$( '.wcv-shipping-' + wcv_admin.current_shipping_type ).show(); 

		// If there is a vendor shipping override change that here. 
		var previous = wcv_admin.current_shipping_type; 

		$('.wcv-shipping-type').on( 'focus', function(){ 
			if ( $( this ).val() !== '' ) previous = $( this ).val(); 
		}).change( function( ) { 
			var shipping_type = $( this ).val(); 
			$( '.wcv-shipping-' +  shipping_type ).show(); 
			$( '.wcv-shipping-' +  previous ).hide(); 
			if ( $( this ).val() === '' )  $( '.wcv-shipping-' + wcv_admin.global_shipping_type ).show(); 
			previous = shipping_type; 
		}); 
	}	

	// Show / Hide shipping types for the product edit screen 
	if ( wcv_admin.screen_id == 'product' ){
		$( '.wcv-shipping-' +  wcv_admin.current_shipping_type ).show(); 
	} 

	var select2_args = { placeholderOption: 'first', width: '100%' }

	// Country Rates 
	$('#shipping').on('click','.wcv_shipping_rates a.insert',function(){
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

	// Global shipping settings 
	$('.wcv-shipping-system').on( 'change', function() {
		
		if ( $( this ).val() == 'flat' ){ 

			$( '.wcv-flat-rate' ).each( function( index ){ 
				$( this ).closest( 'tr' ).show(); 
			});

			$( '.wcv_country_rate_table').hide(); 

		} else { 

			$( '.wcv-flat-rate' ).each( function( index ){ 
				$( this ).closest( 'tr' ).hide(); 
			}); 

			$( '.wcv_country_rate_table' ).show(); 

		}
	}); 

	if ( $('.wcv-shipping-system').val() == 'flat' ) { 
		
		$( '.wcv-flat-rate' ).each( function( index ){ 
			$( this ).closest( 'tr' ).show(); 
		});

		$( '.wcv_country_rate_table').hide(); 

	} else { 

		$( '.wcv-flat-rate' ).each( function( index ){ 
			$( this ).closest( 'tr' ).hide(); 
		}); 

	}

	$( window ).load(function(){ 
		$( '#shipping' ).find( 'select' ).each( function(){ 
			$( this ).select2( select2_args ); 
		}); 
	}); 

})( jQuery );
