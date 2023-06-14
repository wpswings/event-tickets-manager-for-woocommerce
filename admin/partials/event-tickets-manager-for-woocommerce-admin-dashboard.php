<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}

global $etmfw_wps_etmfw_obj, $error_notice;
$etmfw_active_tab   = isset( $_GET['etmfw_tab'] ) ? sanitize_key( $_GET['etmfw_tab'] ) : 'event-tickets-manager-for-woocommerce-general';
$etmfw_default_tabs = $etmfw_wps_etmfw_obj->wps_etmfw_plug_default_tabs();
?>

<header>
	<div class="wps-header-container wps-bg-white wps-r-8">
		<h1 class="wps-header-title"><?php echo esc_attr( strtoupper( str_replace( '-', ' ', $etmfw_wps_etmfw_obj->etmfw_get_plugin_name() ) ) ); ?></h1>

		<a href="https://docs.wpswings.com/event-tickets-manager-for-woocommerce/?utm_source=wpswings-events-doc&utm_medium=events-org-backend&utm_campaign=documentation" target="_blank" class="wps-link"><?php esc_html_e( 'Documentation', 'event-tickets-manager-for-woocommerce' ); ?></a>
		<span>|</span>
		<a href="https://www.youtube.com/embed/9KyB4qpal6M" target="_blank" class="wps-link"><?php esc_html_e( 'Video', 'event-tickets-manager-for-woocommerce' ); ?></a>
		<span>|</span>
		<a href="https://wpswings.com/submit-query/?utm_source=wpswings-events-support&utm_medium=events-org-backend&utm_campaign=support" target="_blank" class="wps-link"><?php esc_html_e( 'Support', 'event-tickets-manager-for-woocommerce' ); ?></a>
	</div>
</header>

<?php
do_action( 'wps_etmfw_licensed_tab_section' );
if ( ! $error_notice ) {
	$wps_etmfw_error_text = esc_html__( 'Settings saved !', 'event-tickets-manager-for-woocommerce' );
	$etmfw_wps_etmfw_obj->wps_etmfw_plug_admin_notice( $wps_etmfw_error_text, 'success' );
}
?>
<main class="wps-main wps-bg-white wps-r-8">
	<nav class="wps-navbar">
		<ul class="wps-navbar__items">
			<?php
			if ( is_array( $etmfw_default_tabs ) && ! empty( $etmfw_default_tabs ) ) {

				foreach ( $etmfw_default_tabs as $etmfw_tab_key => $etmfw_default_tabs ) {

					$etmfw_tab_classes = 'wps-link ';

					if ( ! empty( $etmfw_active_tab ) && $etmfw_active_tab === $etmfw_tab_key ) {
						$etmfw_tab_classes .= 'active';
					}
					?>
					<li>
						<a id="<?php echo esc_attr( $etmfw_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=event_tickets_manager_for_woocommerce_menu' ) . '&etmfw_tab=' . esc_attr( $etmfw_tab_key ) ); ?>" class="<?php echo esc_attr( $etmfw_tab_classes ); ?>"><?php echo esc_html( $etmfw_default_tabs['title'] ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>

	<section class="wps-section">
	<div class="wps-rma__popup-for-pro-wrap">
			<div class="wps-rma__popup-for-pro-shadow"></div>
			<div class="wps-rma__popup-for-pro">
				<span class="wps-rma__popup-for-pro-close">+</span>
				<h2 class="wps-rma__popup-for-pro-title"><?php esc_html_e( 'Want More ?? Go Pro !!', 'event-tickets-manager-for-woocommerce' ); ?></h2>
				<p class="wps-rma__popup-for-pro-content"><i><?php echo esc_html__( 'The Pro Version will unlock all of the feature', 'event-tickets-manager-for-woocommerce' ) . '<br/>' . esc_html__( 'This will easily process event tickets, allow sharing of tickets, resend tickets, and QRCode generation, twilio integration, and email notifications feature making it the perfect event management system', 'event-tickets-manager-for-woocommerce' ); ?></i></p>
				<div class="wps-rma__popup-for-pro-link-wrap">
					<a target="_blank" href="https://wpswings.com/product/event-tickets-manager-for-woocommerce-pro/?utm_source=wpswings-events-pro&utm_medium=events-org-backend&utm_campaign=go-pro" class="wps-rma__popup-for-pro-link"><?php esc_html_e( 'Go pro now', 'event-tickets-manager-for-woocommerce' ); ?></a>
				</div>
			</div>
		</div>

		<div>
			<?php
				do_action( 'wps_etmfw_before_general_settings_form' );
				// if submenu is directly clicked on woocommerce.
			if ( empty( $etmfw_active_tab ) ) {
				$etmfw_active_tab = 'wps_etmfw_plug_general';
			}
					// look for the path based on the tab id in the admin templates.
					$etmfw_tab_content_path = 'admin/partials/' . $etmfw_active_tab . '.php';

					$etmfw_tab_content_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/' . $etmfw_active_tab . '.php';
					$etmfw_wps_etmfw_obj->wps_etmfw_plug_load_template( $etmfw_tab_content_path, $etmfw_active_tab );

				do_action( 'wps_etmfw_after_general_settings_form' );
			?>
		</div>
	</section>
