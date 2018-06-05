<?php
/**
 * The template for displaying the order note form this is displayed in the modal pop up.
 *
 * Override this template by copying it to yourtheme/wc-vendors/dashboard/order
 *
 * @package    WCVendors_Pro
 * @version    1.2.3
 */
?>

<div class="wcv-shade wcv-fade">
	<div id="order-note-modal-<?php echo $order_id; ?>" class="wcv-modal wcv-fade" data-trigger="#open-order-note-modal-<?php echo $order_id; ?>" data-width="80%" data-height="80%"  data-reveal aria-labelledby="modalTitle-<?php echo $order_id; ?>" aria-hidden="true" role="dialog">

		<div class="modal-header">
            <button class="modal-close wcv-dismiss"></button>
            <h3 id="modal-title"><?php _e('Order Notes', 'wcvendors-pro'); ?></h3>
        </div>

        <div class="modal-body" id="modalContent">
		
			<div class="order_details">
				<?php if ($notes != '' ) : ?> 
					<?php echo $notes; ?>
				<?php endif; ?>

				<form method="post" name="add_note_<?php echo $order_id; ?>" id="add-comment_<?php echo $order_id; ?>" class="order_note_form wcv-form">

					<?php wp_nonce_field( 'wcv-add-note', 'wcv_add_note' ); ?>

					<textarea name="wcv_comment_text" class="wcv_order_note"></textarea>

					<input type="hidden" name="wcv_order_id" value="<?php echo $order_id; ?>">
					
					<br /><br />

					<input class="btn btn-large btn-block" type="submit" name="add_order_note" value="<?php _e( 'Add Order Note', 'wcvendors-pro' ); ?>">
					
				</form>
			</div>

		</div>
	</div>
</div>