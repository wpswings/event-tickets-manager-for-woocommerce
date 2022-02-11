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

?>
<div id="wps_etmfw_add_fields_wrapper">
	<div class="wps_etmfw_add_fields_title">
		<h2>
			<strong class="attribute_name"><?php esc_html_e( 'Add custom fields on the tickets for this event', 'event-tickets-manager-for-woocommerce' ); ?></strong></h2>
		</div>
		<div class="wps_etmfw_add_fields_data">
			<div class="wps_etmfw_fields_panel">
				<table class="field-options wp-list-table widefat wps_etmfw_field_table">
					<thead>
						<tr>
							<th></th>
							<th class="etmfw_field_label"><?php esc_html_e( 'Label', 'event-tickets-manager-for-woocommerce' ); ?></th>
							<th class="etmfw_field_type"><?php esc_html_e( 'Type', 'event-tickets-manager-for-woocommerce' ); ?></th>
							<th class="etmfw_field_required"><?php esc_html_e( 'Required', 'event-tickets-manager-for-woocommerce' ); ?></th>
							<th class="etmfw_field_actions"><?php esc_html_e( 'Actions', 'event-tickets-manager-for-woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody class="wps_etmfw_field_body">
						<?php if ( empty( $wps_etmfw_field_data ) ) : ?>
							<tr class="wps_etmfw_field_wrap" data-id="0">
								<td class="drag-icon">
									<i class="dashicons dashicons-move"></i>
								</td>
								<td class="form-field wps_etmfw_label_fields">
									<input type="text" class="wps_etmfw_field_label" style="" name="etmfw_fields[0][_label]" id="label_fields_0" value="" placeholder="">
								</td>
								<td class="form-field wps_etmfw_type_fields">
									<select id="type_fields_0" name="etmfw_fields[0][_type]" class="wps_etmfw_field_type">
										<?php
										$wps_etmfw_field_array = $this->wps_etmfw_event_fields();
										foreach ( $wps_etmfw_field_array as $key => $value ) :
											?>
											<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
										<?php endforeach; ?> 
									</select>
								</td>
								<td class="form-field wps_etmfw_required_fields">
									<input type="checkbox" class="checkbox" style="" name="etmfw_fields[0][_required]" id="required_fields_0">
								</td>
								<td class="wps_etmfw_remove_row">
									<input type="button" name="wps_etmfw_remove_fields_button" class="wps_etmfw_remove_row_btn" value="Remove">
								</td>
							</tr>
							<?php
						else :
							foreach ( $wps_etmfw_field_data as $row_id => $row_value ) :
								if ( isset( $row_value['required'] ) && ( 'on' == $row_value['required'] ) ) {
									$wps_etmfw_required = 1;
								} else {
									$wps_etmfw_required = 0;
								}
								?>
								<tr class="wps_etmfw_field_wrap" data-id="<?php echo esc_attr( $row_id ); ?>">
									<td class="drag-icon">
										<i class="dashicons dashicons-move"></i>
									</td>
									<td class="form-field wps_etmfw_label_fields">
										<input type="text" class="wps_etmfw_field_label" style="" name="etmfw_fields[<?php echo esc_attr( $row_id ); ?>][_label]" id="label_fields_<?php echo esc_attr( $row_id ); ?>" value="<?php echo esc_attr( $row_value['label'] ); ?>" placeholder="">
									</td>
									<td class="form-field wps_etmfw_type_fields">
										<select id="type_fields_<?php echo esc_attr( $row_id ); ?>" name="etmfw_fields[<?php echo esc_attr( $row_id ); ?>][_type]" class="wps_etmfw_field_type">
											<?php
											$wps_etmfw_field_array = $this->wps_etmfw_event_fields();
											foreach ( $wps_etmfw_field_array as $key => $value ) :
												$typeselected = '';
												?>
												<?php
												if ( $key === $row_value['type'] ) :
													$typeselected = "selected='selected'";
												endif;
												?>
												?>
												<option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $typeselected ); ?>><?php echo esc_attr( $value ); ?></option>
											<?php endforeach; ?> 
										</select>
									</td>
									<td class="form-field wps_etmfw_required_fields">
										<input type="checkbox" class="checkbox" style="" name="etmfw_fields[<?php echo esc_attr( $row_id ); ?>][_required]" id="required_fields_<?php echo esc_attr( $row_id ); ?>" <?php checked( $wps_etmfw_required, 1 ); ?>>
									</td>
									<td class="wps_etmfw_remove_row">
										<input type="button" name="wps_etmfw_remove_fields_button" class="wps_etmfw_remove_row_btn" value="Remove">
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>				
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<input type="button" name="wps_etmfw_add_fields_button" class="button wps_etmfw_add_fields_button" value="<?php esc_attr_e( 'Add More', 'event-tickets-manager-for-woocommerce' ); ?>">
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
