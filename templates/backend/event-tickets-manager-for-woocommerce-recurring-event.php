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
$wps_is_product_is_recurring = get_post_meta( $product_id, 'is_recurring_' . $product_id, '' );

$wps_plugin_list = get_option( 'active_plugins' );
$wps_is_pro_active = false;
$wps_plugin = 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php';
if ( in_array( $wps_plugin, $wps_plugin_list ) ) {
    $wps_is_pro_active = true;
}

if ( is_array( $wps_etmfw_product_array ) && ! empty( $wps_etmfw_product_array ) && empty( $wps_is_product_is_recurring ) ) {

	$wps_event_recurring_type = isset( $wps_etmfw_product_array['wps_event_recurring_type'] ) ? $wps_etmfw_product_array['wps_event_recurring_type'] : '';
	$wps_event_recurring_value = isset( $wps_etmfw_product_array['wps_event_recurring_value'] ) ? $wps_etmfw_product_array['wps_event_recurring_value'] : '';
	$wps_recurring_event_enable = isset( $wps_etmfw_product_array['etmfwp_recurring_event_enable'] ) ? $wps_etmfw_product_array['etmfwp_recurring_event_enable'] : '';

	/* Timing For Event Daily Start And End */
	$wps_event_recurring_daily_start_time = isset( $wps_etmfw_product_array['wps_event_recurring_daily_start_time'] ) ? $wps_etmfw_product_array['wps_event_recurring_daily_start_time'] : '';
	$wps_event_recurring_daily_end_time = isset( $wps_etmfw_product_array['wps_event_recurring_daily_end_time'] ) ? $wps_etmfw_product_array['wps_event_recurring_daily_end_time'] : '';

	$wps_event_set = false;

	if ( ! empty( $wps_etmfw_product_array ) ) {
		$wps_event_start_date = ! empty( $wps_etmfw_product_array['event_start_date_time'] ) ? strtotime( $wps_etmfw_product_array['event_start_date_time'] ) : '';
		$wps_event_end_date = ! empty( $wps_etmfw_product_array['event_end_date_time'] ) ? strtotime( $wps_etmfw_product_array['event_end_date_time'] ) : '';

		if ( ! empty( $wps_event_start_dat ) && ! empty( $wps_event_end_date ) ) {
			$wps_event_set = true;
		}
	}
	if ( is_array( $wps_etmfw_product_array ) && ! empty( $wps_etmfw_product_array ) ) {
		?>
		<div class="wps_main_recurring_wrapper" id="wps_main_recurring_wrapper_id">
			<div class="wps_main_header">
				<h2><strong><?php esc_html_e( 'Recurring Event Settings', 'event-tickets-manager-for-woocommerce' ); ?></strong></h2>
			</div>
			<?php if ( ! $wps_event_set ) { ?>
				<div class="wps_apply_recurrence_message wps_main_recurring_common">
					<i> * <?php esc_html_e( 'Any Changes in date or venue after creating the recurring event will not be reflect in already create recurring event , you have to delete those event and create new recurring events.', 'event-tickets-manager-for-woocommerce' ); ?> </i>
					<i> * <?php esc_html_e( 'Current Recurrence Pattern: Every', 'event-tickets-manager-for-woocommerce' ); ?> <?php echo esc_attr( $wps_event_recurring_value ); ?> <?php echo esc_attr( str_replace( 'i', 'y', rtrim( $wps_event_recurring_type, 'ly' ) ) ); ?> <?php esc_html_e( 'within', 'event-tickets-manager-for-woocommerce' ); ?> <?php echo esc_attr( gmdate( 'F j, Y g:i A', $wps_event_start_date ) ); ?>, <?php esc_html_e( 'to', 'event-tickets-manager-for-woocommerce' ); ?> <?php echo esc_attr( gmdate( 'F j, Y g:i A', $wps_event_end_date ) ); ?> .</i><?php } ?>
				</div>
				<div class="wps_recurring_description_section wps_main_recurring_common"><i>* <?php esc_html_e( 'Within the specified date range, an one-day event will be generated for each recurring date of the recurring event.', 'event-tickets-manager-for-woocommerce' ); ?></i></div>

				<div class="wps_recurring_setting wps_main_recurring_common">
					<label for="wps_recurring_value_id"><?php esc_html_e( 'The event recurrs every', 'event-tickets-manager-for-woocommerce' ); ?></label>
					<input type="number" min = 0 name="wps_recurring_value" id="wps_recurring_value_id" value="<?php echo esc_attr( $wps_event_recurring_value ); ?>" />
					<select id="wps_recurring_type" name="wps_recurring_type">
						<option
						<?php if ( 'daily' == $wps_event_recurring_type ) { echo 'selected="true"'; } ?> value="daily"><?php esc_html_e( 'Daily', 'event-tickets-manager-for-woocommerce' ); ?>
                        </option>
							
                        <option
                        <?php if ( 'weekly' == $wps_event_recurring_type ) { echo 'selected="true"'; } ?> value="weekly" <?php if ( ! $wps_is_pro_active ) { echo 'disabled'; } ?> ><?php esc_html_e( 'Weekly', 'event-tickets-manager-for-woocommerce' ); ?>
                        </option>
                        
                        <option
                        <?php if ( 'monthly' == $wps_event_recurring_type ) { echo 'selected="true"'; } ?> value="monthly" <?php if ( ! $wps_is_pro_active ) { echo 'disabled'; } ?> ><?php esc_html_e( 'Monthly', 'event-tickets-manager-for-woocommerce' ); ?>
                        </option>
                    </select>
				</div>
				<div class="wps_event_daily_duration_wrap">

				<span class="wps_event_daily_start_time" >
				<span><?php esc_html_e( 'Start Time', 'event-tickets-manager-for-woocommerce' ); ?></span>
				<input type="time" value="<?php echo esc_attr( $wps_event_recurring_daily_start_time ); ?>" class="wps_event_daily_start_time"  name="wps_event_daily_start_time_val"/>
				</span>

				<span class="wps_event_daily_end_time" >
				<span><?php esc_html_e( 'End Time', 'event-tickets-manager-for-woocommerce' ); ?></span>
				<input type="time" value="<?php echo esc_attr( $wps_event_recurring_daily_end_time ); ?>" class="wps_event_daily_end_time"  name="wps_event_daily_end_time_val"/>
				</span>
				</div>

				<?php if ( ! empty( $wps_event_recurring_type ) && ! empty( $wps_event_recurring_value ) && 'yes' == $wps_recurring_event_enable ) { ?>
					<div class="wps_recurring_start_button">
						<button id="wps_etmfw_create_recurring_id" class="button" type="submit" value=<?php echo esc_attr( $product_id ); ?>><?php esc_html_e( 'Create Recurring Event', 'event-tickets-manager-for-woocommerce' ); ?></button>
						<button id="wps_etmfw_delete_create_recurring_id" class="button" type="submit" value=<?php echo esc_attr( $product_id ); ?>><?php esc_html_e( 'Delete All Attached Recurring Event', 'event-tickets-manager-for-woocommerce' ); ?></button>
						<span><img id ='wps_recurring_loader' class="wps_recurring_loader_main" src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/loading.gif' ); ?>" alt="Girl in a jacket" width="" height="30px"></span>
					</div>
				<?php } ?>
		</div>
		<?php
	}
}
