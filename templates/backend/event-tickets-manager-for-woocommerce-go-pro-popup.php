<?php
/**
 * Provide the "Go Pro" popup shown when a locked Pro field/tab is clicked.
 *
 * This file is used to markup the go-pro popup for the plugin admin screens.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/templates/backend/
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}
?>
<div class="wps-rma__popup-for-pro-wrap">
	<div class="wps-rma__popup-for-pro-shadow"></div>
	<div class="wps-rma__popup-for-pro">
		<span class="wps-rma__popup-for-pro-close">+</span>
		<h3 class="wps-rma__popup-for-pro-title"><?php esc_html_e( 'Want More ?? Go Pro !!', 'event-tickets-manager-for-woocommerce' ); ?></h3>
		<p class="wps-rma__popup-for-pro-content"><i><?php echo esc_html__( 'The Pro Version will unlock all of the feature', 'event-tickets-manager-for-woocommerce' ) . '<br/>' . esc_html__( 'This will easily process event tickets, allow sharing of tickets, resend tickets, and QRCode generation, twilio integration, and email notifications feature making it the perfect event management system', 'event-tickets-manager-for-woocommerce' ); ?></i></p>
		<div class="wps-rma__popup-for-pro-link-wrap">
			<a target="_blank" href="https://wpswings.com/product/event-tickets-manager-for-woocommerce-pro/?utm_source=wpswings-events-pro&utm_medium=events-org-backend&utm_campaign=go-pro" class="wps-rma__popup-for-pro-link"><?php esc_html_e( 'Go pro now', 'event-tickets-manager-for-woocommerce' ); ?></a>
		</div>
	</div>
</div>
