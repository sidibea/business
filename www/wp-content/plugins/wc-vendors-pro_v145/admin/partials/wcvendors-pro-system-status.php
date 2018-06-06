<?php
/**
 * Provide a meta box view for the settings page
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
			<th colspan="3" data-export-label="WC Vendors Pro"><?php _e( 'WC Vendors Pro', 'wcvendors-pro' ); ?><?php echo ' <a href="#" class="help_tip" data-tip="' . esc_attr__( 'This section shows information about WC Vendors Pro requirements.', 'wcvendors-pro' ) . '"></a>'; ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Theme Compatability"><?php _e( 'Theme Compatability', 'wcvendors-pro' ); ?>:</td>
			<td class="help">&nbsp;</td>
			<td>
			<?php
				if ( '' !== $woocommerce_override ) {
					echo '<mark class="error">&#10005; ' . sprintf( __( 'Your theme is not 100%% WooCommerce compatible and will not display vendor stores properly. Please show this page ( <a href="%s">https://docs.woothemes.com/document/third-party-custom-theme-compatibility/</a> ) to your theme author and instruct them to provide full WooCommerce compatibility, not the limited WooCommerce compatibility they currently provide by using woocommerce.php instead of full templates.', 'wcvendors-pro' ) , 'https://docs.woothemes.com/document/third-party-custom-theme-compatibility/' ). '</mark>';
				} else {
					echo '-';
				}
			?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Pro Dashboard Page"><a href="admin.php?page=wc_prd_vendor&tab=pro"><?php _e( 'Pro dasboard page', 'wcvendors-pro' ); ?>:</a></td>
			<td class="help">&nbsp;</td>
			<td>
			<?php
				if ( ! $pro_dashboard_page ) {
					echo '<mark class="error">&#10005; -' . sprintf( __( 'WC Vendors Pro WILL NOT FUNCTION without this set. <a href="%s">Click here to set the page</a>.', 'wcvendors-pro' ), 'admin.php?page=wc_prd_vendor&tab=pro' ). '</mark>';
				} else {
					if ( $pro_dashboard_page == $free_dashboard_page ) { 
						echo '<mark class="error">&#10005; -' . sprintf( __( 'Your pro dashboard page cannot be set to your free dashboard page. <a href="%s">Click here to change</a>.', 'wcvendors-pro' ), 'admin.php?page=wc_prd_vendor&tab=pro' ). '</mark>';

					} else { 
						echo '<mark class="yes">&#10004; - #'. $pro_dashboard_page .'</mark>';
					}	
				}
			?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Feedback form page"><a href="admin.php?page=wc_prd_vendor&tab=vendor-ratings"><?php _e( 'Feedback form page', 'wcvendors-pro' ); ?>:</a></td>
			<td class="help">&nbsp;</td>
			<td><?php
				if ( ! $feedback_form_page ) {
					echo '<mark class="error">&#10005;  ' . sprintf( __( 'Vendor ratings will not work without this page set. <a href="%s">Click here to set the page</a>.', 'wcvendors-pro' ), 'admin.php?page=wc_prd_vendor&tab=vendor-ratings' ). '</mark>';
				} else {
					echo '<mark class="yes">&#10004; - #'. $feedback_form_page .'</mark>';
				}
			?></td>
		</tr>
		<tr>
			<td data-export-label="Vendor Shop Permalink"><a href="admin.php?page=wc_prd_vendor&tab=pro"><?php _e( 'Vendor Shop Permalink', 'wcvendors-pro' ); ?>:</a></td>
			<td class="help">&nbsp;</td>
			<td><?php 
				if ( $vendor_shop_permalink == '' ) {
					echo '<mark class="error">&#10005; -' . sprintf( __( 'You need to set a vendor store permalink. <a href="%s">Click here to set the slug</a>.', 'wcvendors-pro' ), 'admin.php?page=wc_prd_vendor&tab=general' ). '</mark>';
				} else { 
					echo '<mark class="yes">&#10004; - '. $vendor_shop_permalink .'</mark>';
				}
			?></td>
		</tr>
	</tbody>
</table>