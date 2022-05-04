<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to show dynamic fields at the frontend.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/templates/frontend/
 */

?>
<div class="wps_etmfw_product_wrapper">
	<div class="wps_etmfw_event_info_section">
		<?php do_action( 'wps_etmfw_before_event_general_info', $product_id ); ?>
		<input type="hidden" name="wps_etmfw_event_start" value=<?php echo esc_html( $start_date ); ?>>
		<input type="hidden" name="wps_etwmfw_atc_nonce" value=<?php echo esc_html( wp_create_nonce( 'wps_etwmfw_atc_nonce' ) ); ?>>

		<input type="hidden" name="wps_etmfw_event_finish" value=<?php echo esc_html( $end_date ); ?>>
		<div id="wps_etmwf_event_date" class="wps_etmfw_event_general_info">
			<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/calendar_icone.svg' ); ?>" height="20px" width="20px">
			<span class="wps_etmfw_date_label"><?php echo esc_html( wps_etmfw_get_only_date_format( $start_date ), 'event-tickets-manager-for-woocommerce' ); ?><span><?php echo esc_html( ' - ' ); ?></span><?php echo esc_html( wps_etmfw_get_only_date_format( $end_date ), 'event-tickets-manager-for-woocommerce' ); ?></span>
		</div>
		<div id="wps_etmwf_event_time" class="wps_etmfw_event_general_info">
			<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/clock.svg' ); ?>" height="20px" width="20px">
			<span class="wps_etmfw_date_label"><?php echo esc_html( wps_etmfw_get_only_time_format( $start_date ), 'event-tickets-manager-for-woocommerce' ); ?><span><?php echo esc_html( ' - ' ); ?></span><?php echo esc_html( wps_etmfw_get_only_time_format( $end_date ), 'event-tickets-manager-for-woocommerce' ); ?></span>
		</div>
		<div id="wps_etmwf_event_venue" class="wps_etmfw_event_general_info">
			<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/map_pin.svg' ); ?>" height="20px" width="20px">
			<span><?php echo esc_html( $event_venue, 'event-tickets-manager-for-woocommerce' ); ?></span>
			<input type="hidden" name="wps_etmfw_event_venue" value=<?php echo esc_html( $event_venue ); ?>>
		</div>
		<?php do_action( 'wps_etmfw_before_event_general_info', $product_id ); ?>
	</div>
	<?php
	$display_map = isset( $wps_etmfw_product_array['etmfw_display_map'] ) ? $wps_etmfw_product_array['etmfw_display_map'] : 'no';
	$location_site = get_option( 'wps_etmfw_enabe_location_site', 'off' );
	$map_api_key = get_option( 'wps_etmfw_google_maps_api_key', '' );
	if ( 'yes' === $display_map && 'on' === $location_site && '' !== $map_api_key ) {
		?>
		<div class="wps_etmfw_event_map_wrapper">
			
			<iframe width="640" height="480" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.it/maps?q=<?php echo esc_html( $event_venue ); ?>&output=embed"></iframe>
		</div>
		<?php
	}
	?>
	<div class="wps_etmfw_addition_info_section">
		<?php do_action( 'wps_etmfw_before_more_info', $product_id ); ?>
		<?php $this->wps_etmfw_generate_addional_fields( $product_id, $event_field_array ); ?>
		<?php do_action( 'wps_etmfw_after_more_info', $product_id ); ?>
	</div>
</div>
