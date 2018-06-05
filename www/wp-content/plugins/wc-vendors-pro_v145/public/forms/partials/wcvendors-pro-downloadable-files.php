<?php

/**
 * Downloadable files template
 *
 * This file is used to load the download files data 
 *
 * @link       http://www.wcvendors.com
 * @since      1.0.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/product
 */ 
?>

<div class="show_if_downloadable">
	<div class="form-field downloadable_files">
		<table class="download_file_table">
			<thead>
				<tr>
					<th class="sort">&nbsp;</th>
					<th><?php _e( 'Name', 'wcvendors-pro' ); ?> <span class="tips" data-tip="<?php _e( 'This is the name of the download shown to the customer.', 'wcvendors-pro' ); ?>"></span></th>
					<th colspan="2"><?php _e( 'File ', 'wcvendors-pro' ); ?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php $downloadable_files = $post_id ? get_post_meta( $post_id, '_downloadable_files', true ) : false; ?>

				<?php if ( $downloadable_files ) : ?>

					<?php foreach ( $downloadable_files as $key => $file ) : ?>

						<?php $file_id = WCVendors_Pro::get_attachment_id( $key ); ?>
						<?php $file_display = ( $file_display_type == 'file_url' ) ? $file[ 'file' ] : basename( $file['file'] ); ?> 

					<tr>
						<td class="sort"><i class="fa fa-sort"></i></td>
						<td class="file_name"><div class="control-group"><div class="control">
						<input type="text" class="input_text" placeholder="<?php _e( 'File Name', 'wcvendors-pro' ); ?>" name="_wc_file_names[]" value="<?php echo esc_attr( $file['name'] ); ?>" /></div></div></td>
						<td class="file_url">
							<input type="text" class="input_text file_display" placeholder="<?php _e( "http://", 'wcvendors-pro' ); ?>" name="_wc_file_display[]" value="<?php echo esc_attr( $file_display ); ?>" />
							<input type="hidden" class="file_url" name="_wc_file_urls[]" value="<?php echo esc_attr( $file['file'] ); ?>" />
							<input type="hidden" class="file_id" name="_wc_file_ids[]" value="<?php echo $file_id; ?>" />
						</td>
						<td class="file_url_choose" width="1%"><a href="#" class="button upload_file_button" data-choose="<?php _e( 'Choose file', 'wcvendors-pro' ); ?>" data-update="<?php _e( 'Insert file URL', 'wcvendors-pro' ); ?>"><?php echo str_replace( ' ', '&nbsp;', __( 'Choose file', 'wcvendors-pro' ) ); ?></a></td>
						<td width="1%"><a href="#" class="delete"><i class="fa fa-times"></i></a></td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="5">
						<a href="#" class="button insert" data-row="
						<?php
							$file = array(
								'file' => '',
								'name' => ''
							);
							$file_data_row = '<tr class="download_file"><td class="sort"><i class="fa fa-sort"></i></td><td class="file_name"><div class="control-group"><div class="control"><input type="text" class="input_text" placeholder="'. __( "File Name", 'wcvendors-pro' ).'" name="_wc_file_names[]" value="'. esc_attr( $file['name'] ). '" /></div></div></td>
	<td class="file_url"><div class="control-group"><div class="control"><input type="text" class="input_text file_display" placeholder="' .__( "http://", 'wcvendors-pro' ) .'" name="_wc_file_display[]" value="" /><input type="hidden" class="file_id" name="_wc_file_ids[]" value="" /><input type="hidden" class="file_url" name="_wc_file_urls[]" value="" /></div></div></td>
	<td class="file_url_choose" width="1%"><a href="#" class="button upload_file_button" data-choose="' . __( 'Choose file', 'wcvendors-pro' ). '" data-update="'. __( 'Insert file URL', 'wcvendors-pro' ). '">' . str_replace( ' ', '&nbsp;', __( 'Choose file', 'wcvendors-pro' ) ). '</a></td>
	<td width="1%"><a href="#" class="delete"><i class="fa fa-times"></i></a></td></tr>';

							echo esc_attr( $file_data_row );
						?>"><?php _e( 'Add File', 'wcvendors-pro' ); ?></a>
					</th>
				</tr>
			</tfoot>
		</table>
	</div>

	
	<?php do_action( 'wcv_product_options_downloads' ); ?>

</div>