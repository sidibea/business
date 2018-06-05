<?php
/**
 * The template for displaying the dashboard quicklinks
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/
 *
 * @package    WCVendors_Pro
 * @version    1.1.5
 */
?>

<?php foreach( $quick_links as $link => $details) : ?>
	<a href="<?php echo $details['url']; ?>" class="button <?php echo $link ?>"><?php echo $details['label']; ?></a>
<?php endforeach; ?> 