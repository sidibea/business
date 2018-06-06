<?php
/**
 * Output the template status
 *
 * @link       http://www.wcvendors.com
 * @since      1.2.3
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/admin/partials
 */

?>

<table class="wc_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Templates"><?php _e( 'WC Vendors Pro Templates', 'wcvendors-pro' ); ?><?php echo ' <a href="#" class="help_tip" data-tip="' . esc_attr__( 'This section shows any files that are overriding the default WC Vendors Pro template pages.', 'wcvendors-pro' ) . '"></a>'; ?></th>
		</tr>
	</thead>
	<tbody>
		<?php

			$template_paths     = apply_filters( 'wcv_template_overrides_scan_paths', array( 'wc-vendors', $this->plugin_base_dir . '/templates/' ) );
			$scanned_files      = array();
			$found_files        = array();
			$outdated_templates = false;

			foreach ( $template_paths as $plugin_name => $template_path ) {

				$scanned_files = WC_Admin_Status::scan_template_files( $template_path );

				foreach ( $scanned_files as $file ) {
					if ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
						$theme_file = get_stylesheet_directory() . '/' . $file;
					} elseif ( file_exists( get_stylesheet_directory() . '/wc-vendors/' . $file ) ) {
						$theme_file = get_stylesheet_directory() . '/wc-vendors/' . $file;
					} elseif ( file_exists( get_template_directory() . '/' . $file ) ) {
						$theme_file = get_template_directory() . '/' . $file;
					} elseif( file_exists( get_template_directory() . '/wc-vendors/' . $file ) ) {
						$theme_file = get_template_directory() . '/wc-vendors/' . $file;
					} else {
						$theme_file = false;
					}

					// Need to check file extension is only .php otherwise ignore 

					if ( ! empty( $theme_file ) ) {
						$core_version  = WC_Admin_Status::get_file_version( $template_path . $file );
						$theme_version = WC_Admin_Status::get_file_version( $theme_file );

						if ( $core_version && ( empty( $theme_version ) || version_compare( $theme_version, $core_version, '<' ) ) ) {
							if ( ! $outdated_templates ) {
								$outdated_templates = true;
							}
							$found_files[ $plugin_name ][] = sprintf( __( '<code>%s</code> version <strong style="color:red">%s</strong> is out of date. The core version is %s', 'wcvendors-pro' ), str_replace( WP_CONTENT_DIR . '/themes/', '', $theme_file ), $theme_version ? $theme_version : '-', $core_version );
						} else {
							$found_files[ $plugin_name ][] = sprintf( '<code>%s</code>', str_replace( WP_CONTENT_DIR . '/themes/', '', $theme_file ) );
						}
					}
				}
			}

			if ( $found_files ) {
				foreach ( $found_files as $plugin_name => $found_plugin_files ) {
					?>
					<tr>
						<td data-export-label="Overrides"><?php _e( 'Overrides', 'wcvendors-pro' ); ?> (<?php echo $plugin_name; ?>):</td>
						<td class="help">&nbsp;</td>
						<td><?php echo implode( ', <br/>', $found_plugin_files ); ?></td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr>
					<td data-export-label="Overrides"><?php _e( 'Overrides', 'wcvendors-pro' ); ?>:</td>
					<td class="help">&nbsp;</td>
					<td>&ndash;</td>
				</tr>
				<?php
			}

			if ( true === $outdated_templates ) {
				?>
				<tr>
					<td>&nbsp;</td>
					<td class="help">&nbsp;</td>
					<td><a href="https://www.wcvendors.com/kb/fix-outdated-templates/" target="_blank"><?php _e( 'Learn how to update outdated templates', 'wcvendors-pro' ) ?></a></td>
				</tr>
				<?php
			}
		?>
	</tbody>
</table>