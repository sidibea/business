<?php

/**
 * Table template 
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.wcvendors.com
 * @since      1.0.0
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/public/partials/helpers/table
 */
?>

<?php if( $this->container_wrap ): ?> 
<div class="wcv-cols-group wcv-horizontal-gutters">
<div class="all-100">
<?php endif; ?>

<table role="grid" class="wcvendors-table wcvendors-table-<?php echo $this->id; ?> wcv-table">

<?php $this->display_columns(); ?>
<?php $this->display_rows(); ?>

</table>

<?php if( $this->container_wrap ): ?> 
</div>
</div>
<?php endif; ?>