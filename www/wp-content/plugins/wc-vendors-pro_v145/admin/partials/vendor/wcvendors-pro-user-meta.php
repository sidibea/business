<?php

/**
 * The extended user meta fields
 *
 * This file is used to display the pro user meta fields on the edit screen. 
 *
 * @link       http://www.wcvendors.com
 * @since      1.2.3
 * @version    1.3.6
 *
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/admin/partials/vendors
 */ 


// Store Meta fields 
foreach ( $fields as $fieldkey => $fieldset ) : ?>

<?php $class = isset( $fieldset[ 'field_class' ] ) ? 'wcv-'. $fieldkey . ' ' . $fieldset[ 'field_class' ] : 'wcv-'. $fieldkey; ?>

<?php do_action( 'wcv_admin_before_' . $fieldkey, $user ); ?>

<div class="<?php echo $class; ?>">
<h3><?php echo $fieldset['title']; ?></h3>
<table class="form-table">
	<?php foreach ( $fieldset['fields'] as $key => $field ) : ?>

		<?php $value = isset( $field[ 'value' ] ) ? $field[ 'value' ] : esc_attr( get_user_meta( $user->ID, $key, true ) ); ?>

		<tr>
			<th><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
			<td>
				<?php if ( ! empty( $field['type'] ) && 'select' == $field['type'] ) : ?>
					<select name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" class="<?php echo ( ! empty( $field['class'] ) ? $field['class'] : '' ); ?>" style="width: 25em;">
						<?php
							$selected = isset( $field[ 'value'] ) ? $field[ 'value'] : esc_attr( get_user_meta( $user->ID, $key, true ) );
							foreach ( $field['options'] as $option_key => $option_value ) : ?>
							<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $selected, $option_key, true ); ?>><?php echo esc_attr( $option_value ); ?></option>
						<?php endforeach; ?>
					</select>
					<br />
					<span class="description"><?php echo wp_kses_post( $field['description'] ); ?></span>
				<?php elseif ( ! empty( $field['type'] ) && 'checkbox' == $field['type'] ) : ?>
					<label for="<?php echo esc_attr( $key ); ?>">
						<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" <?php checked( 'yes', $value , true ); ?> />
						<?php echo esc_html( $field['description'] ); ?>
					</label>
				<?php elseif ( ! empty( $field['type'] ) && 'textarea' == $field['type'] ) : ?>
					<?php $value = isset( $field[ 'value' ] ) ? esc_attr( $field[ 'value' ] ) : esc_attr( get_user_meta( $user->ID, $key, true ) ); ?>
					<textarea name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>"><?php echo $value; ?></textarea>
				<?php else : ?>
					<?php $value = isset( $field[ 'value' ] ) ? esc_attr( $field[ 'value' ] ) : esc_attr( get_user_meta( $user->ID, $key, true ) ); ?>
					<input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="<?php echo $value; ?>" class="<?php echo ( ! empty( $field['class'] ) ? $field['class'] : 'regular-text' ); ?>" <?php if ( isset( $field[ 'placeholder' ] ) ) { ?> placeholder="<?php echo $field[ 'placeholder' ]; ?>" <?php } ?> /><br />
					  <span class="description"><?php echo wp_kses_post( $field['description'] ); ?></span>
				<?php endif; ?>
				<br/>
				
			</td>
		</tr>
		<?php endforeach; ?>
</table>
</div>

<?php do_action( 'wcv_admin_after_' . $fieldkey, $user ); ?>

<?php endforeach; ?>

