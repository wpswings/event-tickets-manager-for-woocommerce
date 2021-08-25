<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to show dynamic fields at the frontend.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/templates/frontend/
 */

switch ( $html_value['type'] ) {
	case 'hidden':
	case 'number':
	case 'email':
	case 'text':
	case 'date':
		?>
		<input type="hidden" value="<?php echo esc_attr( $html_value_label ); ?>">
		<div class="mwb-edit-form-group" data-id="<?php echo esc_attr( $html_value_label ); ?>">
			<div class="mwb-edit-form-group__label">
				<label class="mwb_etmfe_input_label" for="<?php echo esc_attr( $html_value_label ); ?>"><?php echo esc_html( $html_value['label'] ); ?></label>
				<?php if ( $mandatory ) : ?>
					<span class="mwb_etmfw_mandatory_fields">
						<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
					</span>
				<?php endif; ?>
			</div>
			<div class="mwb-edit-form-group__control">
				<input type="<?php echo esc_html( $html_value['type'] ); ?>" value="<?php echo esc_html( $user_data_value ); ?>" id="mwb_etmfw_<?php echo esc_html( $html_value_label ); ?>" <?php echo esc_html( $required ); ?>>
			</div>
		</div>
		<?php
		break;

	case 'textarea':
		?>
		<div class="mwb-edit-form-group" data-id="<?php echo esc_attr( $html_value_label ); ?>">
			<label class="mwb_etmfe_input_label" for="<?php echo esc_attr( $html_value_label ); ?>"><?php echo esc_html( $html_value['label'] ); ?></label>
			<?php if ( $mandatory ) : ?>
					<span class="mwb_etmfw_mandatory_fields">
						<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
					</span>
				<?php endif; ?>
			<textarea class="" rows="2" cols="25" id="mwb_etmfw_<?php echo esc_attr( $html_value_label ); ?>" id="<?php echo esc_attr( $html_value_label ); ?>" <?php echo esc_html( $required ); ?>><?php echo esc_textarea( $user_data_value ); // WPCS: XSS ok. ?></textarea>
		</div>
		<?php
		break;
	case 'yes-no':
		?>
		<div class="mwb-edit-form-group" data-id="<?php echo esc_attr( $html_value_label ); ?>">
			<label class="mwb_etmfe_input_label" for="<?php echo esc_attr( $html_value_label ); ?>"><?php echo esc_html( $html_value['label'] ); ?></label>
			<?php if ( $mandatory ) : ?>
					<span class="mwb_etmfw_mandatory_fields">
						<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
					</span>
				<?php endif; ?>
			<div>
				<input type="radio" id="mwb_etmfw_<?php echo esc_attr( $html_value_label ); ?>" name="mwb_etmfw_<?php echo esc_attr( $html_value_label ); ?>" value="yes" <?php echo esc_html( 'yes' === $user_data_value ) ? 'checked' : ''; ?> <?php echo esc_html( $required ); ?>>
				<label for="yes"><?php esc_html_e( 'Yes', 'event-tickets-manager-for-woocommerce' ); ?></label>
			</div>
			<div>
				<input type="radio" id="mwb_etmfw_<?php echo esc_attr( $html_value_label ); ?>" name="mwb_etmfw_<?php echo esc_attr( $html_value_label ); ?>" value="no" <?php echo esc_html( 'no' === $user_data_value ) ? 'checked' : ''; ?>>
				<label for="no"><?php esc_html_e( 'No', 'event-tickets-manager-for-woocommerce' ); ?></label>
			</div>
		</div>
		<?php
		break;

		do_action( 'mwb_etmfw_edit_ticket_info_field', $html_value, $user_data_value );

	default:
		break;
}