<?php
/**
 * The template for displaying the order note
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/order
 *
 * @package    WCVendors_Pro
 * @version    1.0.3
 */
?>

<p>
<span><?php echo _e( $time_posted .' ago you wrote :', 'wcvendors-pro' ); ?></span> <br />
<?php echo $note_text; ?>
</p>