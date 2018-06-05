<?php

/**
 * The feedback edit form 
 *
 * This file is used to display the feedback edit form on the backend. 
 *
 * @link       http://www.wcvendors.com
 * @since      1.0.0
 * @version    1.3.3
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/includes/partials/ratings
 */

$user = get_userdata( stripslashes($feedback->vendor_id) );
$vendor_id = '<a href="' . admin_url( 'user-edit.php?user_id=' . stripslashes($feedback->vendor_id) ) . '">' . WCV_Vendors::get_vendor_shop_name( stripslashes($feedback->vendor_id) ) . '</a>';
$product_link  = '<a href="' . admin_url( 'post.php?post=' . stripslashes($feedback->product_id) . '&action=edit' ) . '">' . get_the_title( stripslashes($feedback->product_id) ) . '</a>';  
$order_link = '<a href="' . admin_url( 'post.php?post=' . stripslashes($feedback->order_id) . '&action=edit' ) . '">' . stripslashes($feedback->order_id) . '</a>';
$user = get_userdata( stripslashes( $feedback->customer_id ) );
$customer = '<a href="' . admin_url( 'user-edit.php?user_id=' . stripslashes( $feedback->customer_id) ) . '">' . $user->display_name . '</a>'; 
$postdate = date_i18n( get_option( 'date_format' ), strtotime( $feedback->postdate ) ); 


?> 

<form action="" method="post">
<input type="hidden" name="rating_id" value="<?php echo $feedback->id; ?>" />
<input type="hidden" name="action" value="save" />

<h3><?php echo __( 'Edit Vendor Rating', 'wcvendors-pro' ); ?></h3>
<h4><?php echo __( 'Rating Details', 'wcvendors-pro' ); ?></h4>
<p><strong>Order #: <?php echo $order_link; ?></strong>| Posted by : <?php echo $customer; ?> for <?php echo $product_link; ?> on 
<?php echo $postdate; ?>
</p>

<table class="form-table wcv-form-table">
    <tbody>
    	<tr>
		    <th scope="row">
		        <label for="rating"><?php echo __( 'Feedback Rating', 'wcvendors-pro' ); ?></label>
		    </th>
		 
		    <td>
		    <?php 
		        $rating = ''; 
		      	for ($i = 1; $i<=stripslashes( $feedback->rating ); $i++) { $rating .= "<i class='fa fa-star'></i>"; } 
				for ($i = stripslashes( $feedback->rating ); $i<5; $i++) { $rating .=  "<i class='fa fa-star-o'></i>"; }
				echo $rating; 
			?>
		    </td>
		</tr>
        <tr>
		    <th scope="row">
		        <label for="rating_title"><?php echo __( 'Feedback Title', 'wcvendors-pro' ); ?></label>
		    </th>
		 
		    <td>
		        <input type="text" value="<?php echo $feedback->rating_title; ?>" id="rating_title" name="rating_title">
		        <br>
		        <span class="description"><?php echo __( 'The feedback title.', 'wcvendors-pro' ); ?></span>
		    </td>
		</tr>
		<tr>
		    <th scope="row">
		        <label for="comments"><?php echo __( 'Feedback Comment', 'wcvendors-pro' ); ?></label>
		    </th>
		 
		    <td>
		        <textarea id="rating_comments" name="rating_comments"><?php echo $feedback->comments; ?></textarea>
		        <br>
		        <span class="description"><?php echo __( 'The feedback comment.', 'wcvendors-pro' ); ?></span>
		    </td>
		</tr>
    </tbody>
</table>
    <p class="submit"><input type="submit" value="Save Changes" class="button-primary" name="Submit"></p>
</form>			