<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to show loader and error message at backend.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/templates/backend/
 */

?>
<div id="wps_etmfw_location_loader">
		<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/loading.gif' ); ?>">
	</div>
	<div class="wps_etmfw_error_message_div">
		<div id="wps_etmfw_error_msg"></div>
</div>
