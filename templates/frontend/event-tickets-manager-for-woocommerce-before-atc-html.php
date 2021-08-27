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

?>
<div class="mwb_etmfw_product_wrapper">
	<div class="mwb_etmfw_event_info_section">
		<?php do_action( 'mwb_etmfw_before_event_general_info', $product_id ); ?>
		<input type="hidden" name="mwb_etmfw_event_start" value=<?php echo esc_html( $start_date ); ?>>
		<input type="hidden" name="mwb_etmfw_event_finish" value=<?php echo esc_html( $end_date ); ?>>
		<div id="mwb_etmwf_event_date" class="mwb_etmfw_event_general_info">
			<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/calendar_icone.svg' ); ?>" height="20px" width="20px">
			<span class="mwb_etmfw_date_label"><?php echo esc_html( mwb_etmfw_get_only_date_format( $start_date ), 'event-tickets-manager-for-woocommerce' ); ?><span><?php echo esc_html( ' - ' ); ?></span><?php echo esc_html( mwb_etmfw_get_only_date_format( $end_date ), 'event-tickets-manager-for-woocommerce' ); ?></span>
		</div>
		<div id="mwb_etmwf_event_time" class="mwb_etmfw_event_general_info">
			<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/clock.svg' ); ?>" height="20px" width="20px">
			<span class="mwb_etmfw_date_label"><?php echo esc_html( mwb_etmfw_get_only_time_format( $start_date ), 'event-tickets-manager-for-woocommerce' ); ?><span><?php echo esc_html( ' - ' ); ?></span><?php echo esc_html( mwb_etmfw_get_only_time_format( $end_date ), 'event-tickets-manager-for-woocommerce' ); ?></span>
		</div>
		<div id="mwb_etmwf_event_venue" class="mwb_etmfw_event_general_info">
			<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/map_pin.svg' ); ?>" height="20px" width="20px">
			<span><?php echo esc_html( $event_venue, 'event-tickets-manager-for-woocommerce' ); ?></span>
			<input type="hidden" name="mwb_etmfw_event_venue" value=<?php echo esc_html( $event_venue ); ?>>
		</div>
		<?php do_action( 'mwb_etmfw_before_event_general_info', $product_id ); ?>
	</div>
	<?php
	$display_map = isset( $mwb_etmfw_product_array['etmfw_display_map'] ) ? $mwb_etmfw_product_array['etmfw_display_map'] : 'no';
	$location_site = get_option( 'mwb_etmfw_enabe_location_site', 'off' );
	$map_api_key = get_option( 'mwb_etmfw_google_maps_api_key', '' );
	if ( 'yes' === $display_map && 'on' === $location_site && '' !== $map_api_key ) {
		?>
		<div class="mwb_etmfw_event_map_wrapper">
			<?php
			$event_lat = isset( $mwb_etmfw_product_array['etmfw_event_venue_lat'] ) ? $mwb_etmfw_product_array['etmfw_event_venue_lat'] : '';
			$event_lng = isset( $mwb_etmfw_product_array['etmfw_event_venue_lng'] ) ? $mwb_etmfw_product_array['etmfw_event_venue_lng'] : '';
			?>
			<input type="hidden" id="etmfw_event_lat" value="<?php echo esc_attr( $event_lat ); ?>">
			<input type="hidden" id="etmfw_event_lng" value="<?php echo esc_attr( $event_lng ); ?>">
			<?php // script used for display map. ?>
			<script>
			function initMap() {
					let event_lat = parseInt( document.getElementById('etmfw_event_lat').value );
				let event_lng = parseInt( document.getElementById('etmfw_event_lng').value );
				const myLatLng = { lat: event_lat, lng: event_lng };
					const map = new google.maps.Map(document.getElementById("mwb_etmfw_event_map"), {
					zoom: 4,
					center: myLatLng,
					});
					new google.maps.Marker({
					position: myLatLng,
					map,
					title: "Event!",
				});
			}
			</script>
			<div id="mwb_etmfw_event_map"></div>
		</div>
		<?php
	}
	?>
	<div class="mwb_etmfw_addition_info_section">
		<?php do_action( 'mwb_etmfw_before_more_info', $product_id ); ?>
		<?php $this->mwb_etmfw_generate_addional_fields( $product_id, $event_field_array ); ?>
		<?php do_action( 'mwb_etmfw_after_more_info', $product_id ); ?>
	</div>
</div>
