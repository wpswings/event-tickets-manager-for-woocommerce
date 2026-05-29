<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$etmfw_genaral_settings = apply_filters( 'wps_etmfw_general_settings_array', array() );
$tab_context            = Event_Tickets_Manager_For_Woocommerce_Admin_UI::get_tab_context( 'event-tickets-manager-for-woocommerce-general' );
?>
<?php
Event_Tickets_Manager_For_Woocommerce_Admin_Layout::render_settings_card(
	array(
		'eyebrow'           => $tab_context['eyebrow'],
		'title'             => $tab_context['title'],
		'description'       => $tab_context['description'],
		'documentation_url' => $tab_context['documentation_url'],
		'form_class'        => 'wps-etmfw-gen-section-form',
		'fields'            => $etmfw_genaral_settings,
		'nonce_name'        => 'wps_event_nonce',
		'nonce_action'      => 'wps_event_nonce',
	)
);
?>
