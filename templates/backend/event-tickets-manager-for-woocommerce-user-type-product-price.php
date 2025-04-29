<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to add dynamic fields at at backend.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/templates/backend/
 */

$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
$wps_etmfw_field_user_type_price_data = isset( $wps_etmfw_product_array['wps_etmfw_field_user_type_price_data'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_field_user_type_price_data'] ) ? $wps_etmfw_product_array['wps_etmfw_field_user_type_price_data'] : array();
$wps_etmfw_field_user_type_price_base_price_condition = isset( $wps_etmfw_product_array['wps_etmfw_field_user_type_price_data_baseprice'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_field_user_type_price_data_baseprice'] ) ? $wps_etmfw_product_array['wps_etmfw_field_user_type_price_data_baseprice'] : 'base_price';
$wps_etmfw_tip_1 = esc_attr__( 'Show the price by adding the set price with the regular/sale price of the product', 'event-tickets-manager-for-woocommerce' );
$wps_etmfw_tip_2 = esc_attr__( 'Show the price without adding the set price with the  regular/sale price of the product', 'event-tickets-manager-for-woocommerce' );
$wps_etmfw_tip_3 = esc_attr__( 'Create the event user type with specific price for the product', 'event-tickets-manager-for-woocommerce' );
$allowed_html = array(
	'span' => array(
		'class'    => array(),
		'data-tip' => array(),
	),
);
?>
<div class="wps_etmfw_dynamic_price_wrap" id="wps_etmfwppp_add_fields_wrapper">
	<div class="wps_etmfw_dynamic_pricing_base_price">
	<div class="wps_etmfw_price_cal_rule"><h4><?php esc_html_e( 'Price Calculation Rule', 'event-tickets-manager-for-woocommerce' ); ?></h4></div>
	<?php echo wp_kses( wc_help_tip( $wps_etmfw_tip_1, false ), $allowed_html ); ?><input type="radio" id="wps_etmfw_base_price" name="wps_base_price_cal" value="base_price" <?php checked( $wps_etmfw_field_user_type_price_base_price_condition, 'base_price' ); ?>><?php esc_html_e( 'With Base Price', 'event-tickets-manager-for-woocommerce' ); ?>
	<?php echo wp_kses( wc_help_tip( $wps_etmfw_tip_2, false ), $allowed_html ); ?><input type="radio" id="wps_etmfw_not_base_price" name="wps_base_price_cal" value="not_base_price" <?php checked( $wps_etmfw_field_user_type_price_base_price_condition, 'not_base_price' ); ?>><?php esc_html_e( 'Without Base Price', 'event-tickets-manager-for-woocommerce' ); ?>
	</div>
	<div class="wps_etmfwpp_add_fields_title">
		<h3>
			<strong class="etmfwp_attribute_name"><?php esc_html_e( 'Set Price For Specific User Type', 'event-tickets-manager-for-woocommerce' ); ?></strong>
			<?php echo wp_kses( wc_help_tip( $wps_etmfw_tip_3, false ), $allowed_html ); ?>
		</h3>
	</div>
	<div class="wps_etmfwppp_add_fields_data">
		<div class="wps_etmfwppp_fields_panel">
		<table class="field-options wp-list-table widefat wps_etmfwpp_user_field_table">
			<thead>
					<tr>
						<th></th>
						<th class="etmfwppp_field_label"><?php esc_html_e( 'User Type', 'event-tickets-manager-for-woocommerce' ); ?></th>
						<th class="etmfwppp_field_type"><?php esc_html_e( 'Price', 'event-tickets-manager-for-woocommerce' ); ?></th>
						<th class="etmfwppp_field_actions"><?php esc_html_e( 'Actions', 'event-tickets-manager-for-woocommerce' ); ?></th>
					</tr>
			</thead>
			<tbody class="wps_etmfwpp_user_field_body">
			<?php if ( 1 != 1 ) : ?> 
							<tr class="wps_etmfwpp_user_field_wrap" data-id="0">
								<td class="etmfwpp-drag-icon">
									<i class="dashicons dashicons-move"></i>
								</td>
								<td class="form-field wps_etmfwpp_label_fields">
									<input type="text" class="wps_etmfwpp_field_label"  name="etmfwppp_fields[0][_label]" id="label_fields_0" value="" placeholder="" min="0" required>
								</td>
								<td class="form-field wps_etmfwpp_price_fields">
									<input type="number" class="wps_etmfwpp_field_price" style="" name="etmfwppp_fields[0][_price]" id="price_fields_0" value="" placeholder="" min="0" required>
								</td>
								<td class="wps_etmfwpp_remove_row">
									<input type="button" name="wps_etmfwpp_remove_fields_button" class="wps_user_type_remove" value="Remove">
								</td>
							</tr>
							<?php
						else :

							foreach ( $wps_etmfw_field_user_type_price_data as $row_id => $row_value ) :
								?>
								<tr class="wps_etmfwpp_user_field_wrap" data-id="<?php echo esc_attr( $row_id ); ?>">
									<td class="etmfwpp-drag-icon">
										<i class="dashicons dashicons-move"></i>
									</td>
									<td class="form-field wps_etmfwpp_label_fields">
										<input type="text" class="wps_etmfwpp_field_label" style="" name="etmfwppp_fields[<?php echo esc_attr( $row_id ); ?>][_label]" id="label_fields_<?php echo esc_attr( $row_id ); ?>" value="<?php echo esc_attr( $row_value['label'] ); ?>" placeholder="" required>
									</td>
									<td class="form-field wps_etmfwpp_price_fields">
									<input type="number" class="wps_etmfwpp_field_price" style="" name="etmfwppp_fields[<?php echo esc_attr( $row_id ); ?>][_price]" id="price_fields_<?php echo esc_attr( $row_id ); ?>" value="<?php echo esc_attr( $row_value['price'] ); ?>" placeholder="" min="0" required>
									</td>
									<td class="wps_etmfwpp_remove_row">
										<input type="button" name="wps_etmfwpp_remove_fields_button" class="wps_user_type_remove" value="Remove">
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
						<tfoot>
						<tr>
							<td colspan="5">
								<input type="button" name="wps_etmfwppp_user_add_fields_button" class="button wps_etmfwppp_user_add_fields_button" value="<?php esc_attr_e( 'Add Price Rule', 'event-tickets-manager-for-woocommerce' ); ?>">
							</td>
						</tr>
					</tfoot>
			</tbody>
		</table>
</div>
</div>
</div>