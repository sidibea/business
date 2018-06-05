<tr>
	<td class="file_name">
		<div class="control">
			<input type="text" class="input_text" placeholder="<?php esc_attr_e( 'File Name', 'wcvendors-pro' ); ?>" name="_wc_variation_file_names[<?php echo $variation_id; ?>][]" value="<?php echo esc_attr( $file['name'] ); ?>" />
		</div>
	</td>
	<td class="file_url">
		<div class="control"> 
			<input type="hidden" class="file_url" name="_wc_variation_file_urls[<?php echo $variation_id; ?>][]" value="<?php echo esc_attr( $file['file'] ); ?>" />
			<input type="hidden" class="file_id" name="_wc_variation_file_ids[<?php echo $variation_id; ?>][]" value="<?php echo $file_id; ?>" />
			<input type="text" class="file_display" placeholder="<?php esc_attr_e( "http://", 'wcvendors-pro' ); ?>" name="_wc_variation_file_display[<?php echo $variation_id; ?>][]" value="<?php echo esc_attr( $file_display ); ?>" />
		</div>
	</td>

	<td class="file_url_choose" width="1%"><a href="#" class="button upload_file_button" data-choose="<?php esc_attr_e( 'Choose file', 'wcvendors-pro' ); ?>" data-update="<?php esc_attr_e( 'Insert file URL', 'wcvendors-pro' ); ?>"><?php echo str_replace( ' ', '&nbsp;', __( 'Choose file', 'wcvendors-pro' ) ); ?></a></td>
	<td width="1%"><a href="#" class="delete"><i class="fa fa-times"></i></a></td>
</tr>