<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for integrations tab.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="mwb_etmfw_table_wrapper mwb_etmfw_overview-wrapper">
	<div class="mwb_etmfw_overview_content">
		<h4 class="mwb_etmfw_overview_heading"><?php esc_html_e( 'What is an Event Tickets Manager For WooCommerce?', 'event-tickets-manager-for-woocommerce' ); ?></h4>
		<p><?php esc_html_e( 'Events Tickets Manager For WooCommerce is a plugin that allows merchants to add events as products on your WooCommerce store. The best part is that you can sell the tickets of your offline events without paying a single to third-party marketplace services.', 'event-tickets-manager-for-woocommerce' ); ?></p>
	</div>
	<div class="mwb_etmfw_plugin_can_do">
		<h5 class="mwb_etmfw_overview_heading"><?php esc_html_e( 'With Our Event Tickets Manager, You Can:', 'event-tickets-manager-for-woocommerce' ); ?></h5>
		<ol type="1">
			<li><?php esc_html_e( 'Add event-based products to your website.', 'event-tickets-manager-for-woocommerce' ); ?></li>
			<li><?php esc_html_e( 'Create an event management and ticketing website.', 'event-tickets-manager-for-woocommerce' ); ?></li>
			<li><?php esc_html_e( 'Export list of attendees in CSV, or PDF format.', 'event-tickets-manager-for-woocommerce' ); ?></li>
			<li><?php esc_html_e( 'Show upcoming events through a calendar on your online store.', 'event-tickets-manager-for-woocommerce' ); ?></li>
			<li><?php esc_html_e( 'Manage the stock of tickets.', 'event-tickets-manager-for-woocommerce' ); ?></li>
			<?php do_action( 'mwb_etmfw_extent_plugin_feature_info' ); ?>
		</ol>
	</div>
	<div class="mwb_etmfw_video_wrapper">
		<iframe height="411" src="https://www.youtube.com/embed/YgPLO8HDGtc" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</div>
</div>
<?php
