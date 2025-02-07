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

	// Get the Details For the Dynamic Form Start Here.
	$wps_etmfw_product_array = get_post_meta( get_the_ID(), 'wps_etmfw_product_array', true );

if ( isset( $wps_etmfw_product_array ) && ! empty( $wps_etmfw_product_array ) && is_array( $wps_etmfw_product_array ) ) {
	$wps_etmfw_dyn_name = isset( $wps_etmfw_product_array['wps_etmfw_dyn_name'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_name'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_name'] : '';
	$wps_etmfw_dyn_mail = isset( $wps_etmfw_product_array['wps_etmfw_dyn_mail'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_mail'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_mail'] : '';
	$wps_etmfw_dyn_contact = isset( $wps_etmfw_product_array['wps_etmfw_dyn_contact'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_contact'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_contact'] : '';
	$wps_etmfw_dyn_date = isset( $wps_etmfw_product_array['wps_etmfw_dyn_date'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_date'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_date'] : '';
	$wps_etmfw_dyn_address = isset( $wps_etmfw_product_array['wps_etmfw_dyn_address'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_address'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_address'] : '';
	// Get the Details For the Dynamic Form End Here.
}

$wps_plugin_list = get_option( 'active_plugins' );
$wps_is_pro_active = false;
$wps_plugin = 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php';
if ( in_array( $wps_plugin, $wps_plugin_list ) ) {
	$wps_is_pro_active = true;
}
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
			<input type="hidden" id="wps_etmfw_event_venue" name="wps_etmfw_event_venue" value="<?php echo esc_html( $event_venue ); ?>">
			<input type="hidden" id="wps_etmfw_event_venue_lat" value="<?php echo esc_html( $etmfw_event_venue_lat ); ?>">
			<input type="hidden" id="wps_etmfw_event_venue_lng" value="<?php echo esc_html( $etmfw_event_venue_lng ); ?>">
		</div>
	</div>
	<?php
	$display_map = isset( $wps_etmfw_product_array['etmfw_display_map'] ) ? $wps_etmfw_product_array['etmfw_display_map'] : 'no';
	$location_site = get_option( 'wps_etmfw_enabe_location_site', 'off' );
	$map_api_key = get_option( 'wps_etmfw_google_maps_api_key', '' );

	$wps_etmfw_field_user_type_price_data = isset( $wps_etmfw_product_array['wps_etmfw_field_user_type_price_data'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_field_user_type_price_data'] ) ? $wps_etmfw_product_array['wps_etmfw_field_user_type_price_data'] : array();
	if ( ! empty( $wps_etmfw_field_user_type_price_data ) && is_array( $wps_etmfw_field_user_type_price_data ) ) {
		$product = wc_get_product( $product_id );
		$current_product_price = 0;
		?>
		<p>
		<div class ='wps_user_type_label wps-form-group__label' ><strong><?php esc_html_e( 'Select Ticket User Type', 'event-tickets-manager-for-woocommerce' ); ?></strong></div>
		<div class='wps-form-group__control'>
		<select name='user_type' id='wps_select_user' class='wps-select-user-type' style='width:200px;'>
		<option value='<?php echo esc_attr( $current_product_price ); ?>' selected><?php esc_html_e( 'Select User Type', 'event-tickets-manager-for-woocommerce' ); ?></option>
					<?php foreach ( $wps_etmfw_field_user_type_price_data as $key => $value ) { ?>
			<option value = <?php echo esc_attr( $value['price'] ); ?> ><?php echo esc_html( $value['label'] ); ?></option>
			<?php } ?>
		</select>
		</div>
		</p>
		<?php
	}
	?>
	<div class="wps_etmfw_addition_info_section">
		<?php do_action( 'wps_etmfw_before_more_info', $product_id ); ?>
		<?php if ( '' == $wps_etmfw_dyn_name && '' == $wps_etmfw_dyn_mail && '' == $wps_etmfw_dyn_contact && '' == $wps_etmfw_dyn_date && '' == $wps_etmfw_dyn_address ) { ?>
				<?php $this->wps_etmfw_generate_addional_fields( $product_id, $event_field_array );
				do_action( 'wps_etmfw_after_more_info', $product_id ); ?>
		<?php } ?>
		<?php if ( true == $wps_is_pro_active ) { ?>
			<?php if ( '' != $wps_etmfw_dyn_name || '' != $wps_etmfw_dyn_mail || '' != $wps_etmfw_dyn_contact || '' != $wps_etmfw_dyn_date || '' != $wps_etmfw_dyn_address ) { ?>
				<div id = 'wps_etmfw_dynamic_form_fr_<?php echo esc_attr( $product_id ); ?>'></div>
				<div id = 'wps_etmfw_total_member' class='wps_class_etmfw_total_member'><span id='wps_total_member'></span></div>
				<div id = 'wps_etmfw_total_price' class='wps_class_etmfw_total_price'><span id='wps_total_price'></span></div>
				<div id = 'wps_add_more_people_wrapper' class = 'wps_class_add_more_people'>
					<span id = 'wps_add_more_people' class="button"><?php esc_html_e( ' Add Participants', 'event-tickets-manager-for-woocommerce' ); ?></span>
				</div>
				<?php
			}
		}
		?>
	</div>

	<?php
	do_action( 'wps_etmfw_after_event_general_info_end', $product_id );

	if ( 'yes' === $display_map && 'on' === $location_site && '' !== $map_api_key ) {
		?>
		<p>
		<div class="wps_etmfw_event_map_wrapper">
			
			<iframe id="wps_etmfw_event_map" width="640" height="480" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.it/maps?q=<?php echo esc_html( $event_venue ); ?>&output=embed"></iframe>
		</div>
		</p>
		<?php
	}
	?>
</div>
<?php if ( true == $wps_is_pro_active ) { ?>
	<?php if ( '' != $wps_etmfw_dyn_name || '' != $wps_etmfw_dyn_mail || '' != $wps_etmfw_dyn_contact || '' != $wps_etmfw_dyn_date || '' != $wps_etmfw_dyn_address ) { ?>
<style>
 .single_add_to_cart_button{
	opacity: 0.5;
	cursor: none;
	display: none;
 }
	</style>
	 <?php }
} ?>
