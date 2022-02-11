<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to generate additional field html in ticket.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/templates/frontend/
 */

?>
<p>
	<?php
	switch ( $value['type'] ) {
		case 'text':
			?>
		<div class="wps-form-group">
			<div class="wps-form-group__label">
				<label class="wps_etmfw_field_label"><?php echo esc_html( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
					<?php if ( $mandatory ) : ?>
						<span class="wps_etmfw_mandatory_fields">
							<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
						</span>
					<?php endif; ?>
				</label>
			</div>
			<div class="wps-form-group__control">
				<input type="text" name="wps_etmfw_<?php echo esc_attr( $field_label ); ?>" <?php echo esc_html( $required ); ?>>
			</div>
		</div>

			<?php
			break;

		case 'email':
			?>
		<div class="wps-form-group">
			<div class="wps-form-group__label">
				<label class="wps_etmfw_field_label"><?php echo esc_html( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
					<?php if ( $mandatory ) : ?>
						<span class="wps_etmfw_mandatory_fields">
							<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
						</span>
					<?php endif; ?>
				</label>
			</div>
			<div class="wps-form-group__control">
				<input type="email" name="wps_etmfw_<?php echo esc_attr( $field_label ); ?>" <?php echo esc_html( $required ); ?> >
			</div>
		</div>
			<?php
			break;

		case 'textarea':
			?>
		<div class="wps-form-group">
			<div class="wps-form-group__label">
				<label class="wps_etmfw_field_label"><?php echo esc_html( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
					<?php if ( $mandatory ) : ?>
						<span class="wps_etmfw_mandatory_fields">
							<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
						</span>
					<?php endif; ?>
				</label>
			</div>
			<div class="wps-form-group__control">
				<textarea name="wps_etmfw_<?php echo esc_attr( $field_label ); ?>" <?php echo esc_html( $required ); ?>></textarea>
			</div>
		</div>


			<?php
			break;

		case 'number':
			?>

		<div class="wps-form-group">
			<div class="wps-form-group__label">
				<label class="wps_etmfw_field_label"><?php echo esc_html( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
					<?php if ( $mandatory ) : ?>
						<span class="wps_etmfw_mandatory_fields">
							<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
						</span>
					<?php endif; ?>
				</label>
			</div>
			<div class="wps-form-group__control">
				<input type="number" name="wps_etmfw_<?php echo esc_attr( $field_label ); ?>" <?php echo esc_html( $required ); ?>>
			</div>
		</div>

			<?php
			break;

		case 'date':
			?>
		<div class="wps-form-group">
			<div class="wps-form-group__label">
				<label class="wps_etmfw_field_label"><?php echo esc_html( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
					<?php if ( $mandatory ) : ?>
						<span class="wps_etmfw_mandatory_fields">
							<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
						</span>
					<?php endif; ?>
				</label>
			</div>
			<div class="wps-form-group__control">
				<input type="date" name="wps_etmfw_<?php echo esc_attr( $field_label ); ?>" <?php echo esc_html( $required ); ?>>
			</div>
		</div>


			<?php
			break;

		case 'yes-no':
			?>
		<div class="wps-form-group">
			<div class="wps-form-group__label">
				<label class="wps_etmfw_field_label"><?php echo esc_html( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
					<?php if ( $mandatory ) : ?>
						<span class="wps_etmfw_mandatory_fields">
							<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
						</span>
					<?php endif; ?>
				</label>
			</div>
			<div class="wps-form-group__control">
				<div>
					<input type="radio" id="wps_etmfw_<?php echo esc_attr( $field_label ); ?>" name="wps_etmfw_<?php echo esc_attr( $field_label ); ?>" value="yes" <?php echo esc_html( $required ); ?>>
					<label for="yes"><?php esc_html_e( 'Yes', 'event-tickets-manager-for-woocommerce' ); ?></label>
				</div>
				<div>
					<input type="radio" id="wps_etmfw_<?php echo esc_attr( $field_label ); ?>" name="wps_etmfw_<?php echo esc_attr( $field_label ); ?>" value="no">
					<label for="no"><?php esc_html_e( 'No', 'event-tickets-manager-for-woocommerce' ); ?></label>
				</div>
			</div>
		</div>
			<?php
			break;

		default:
			do_action( 'wps_etmfw_after_input_fields', $value, $required );
			break;
	}
	do_action( 'wps_etmfw_after_input_fields', $value );
	?>
</p>
