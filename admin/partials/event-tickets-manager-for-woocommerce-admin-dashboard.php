<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}

global $etmfw_mwb_etmfw_obj, $error_notice;
$etmfw_active_tab   = isset( $_GET['etmfw_tab'] ) ? sanitize_key( $_GET['etmfw_tab'] ) : 'event-tickets-manager-for-woocommerce-general';
$etmfw_default_tabs = $etmfw_mwb_etmfw_obj->mwb_etmfw_plug_default_tabs();
?>

<header>
<tr class="plugin-update-tr active notice-warning notice-alt">
	<td colspan="4" class="plugin-update colspanchange">
		<div class="notice notice-success inline update-message notice-alt">
			<div class='wps-notice-title wps-notice-section'>
				<p><strong>IMPORTANT NOTICE:</strong></p>
			</div>
			<div class='wps-notice-content wps-notice-section'>
				<p>From this update [here] onwards, the plugin and its support will be handled by <strong>WP Swings</strong>.</p><p><strong>WP Swings</strong> is just our improvised and rebranded version with all quality solutions and help being the same, so no worries at your end.
				Please connect with us for all setup, support, and update related queries without hesitation.</p>
			</div>
		</div>
	</td>
</tr>
<style>
	.wps-notice-section > p:before {
		content: none;
	}
</style>
	<div class="mwb-header-container mwb-bg-white mwb-r-8">
		<h1 class="mwb-header-title"><?php echo esc_attr( strtoupper( str_replace( '-', ' ', $etmfw_mwb_etmfw_obj->etmfw_get_plugin_name() ) ) ); ?></h1>

		<a href="https://docs.makewebbetter.com/event-tickets-manager-for-woocommerce/?utm_source=MWB-event-org&utm_medium=MWB-org-backend&utm_campaign=MWB-event-doc" target="_blank" class="mwb-link"><?php esc_html_e( 'Documentation', 'event-tickets-manager-for-woocommerce' ); ?></a>
		<span>|</span>
		<a href="https://makewebbetter.com/submit-query/?utm_source=MWB-event-org&utm_medium=MWB-org-backend&utm_campaign=MWB-event-support" target="_blank" class="mwb-link"><?php esc_html_e( 'Support', 'event-tickets-manager-for-woocommerce' ); ?></a>
	</div>
</header>

<?php
do_action( 'mwb_etmfw_licensed_tab_section' );
if ( ! $error_notice ) {
	$mwb_etmfw_error_text = esc_html__( 'Settings saved !', 'event-tickets-manager-for-woocommerce' );
	$etmfw_mwb_etmfw_obj->mwb_etmfw_plug_admin_notice( $mwb_etmfw_error_text, 'success' );
}
?>
<main class="mwb-main mwb-bg-white mwb-r-8">
	<nav class="mwb-navbar">
		<ul class="mwb-navbar__items">
			<?php
			if ( is_array( $etmfw_default_tabs ) && ! empty( $etmfw_default_tabs ) ) {

				foreach ( $etmfw_default_tabs as $etmfw_tab_key => $etmfw_default_tabs ) {

					$etmfw_tab_classes = 'mwb-link ';

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

	<section class="mwb-section">
		<div>
			<?php
				do_action( 'mwb_etmfw_before_general_settings_form' );
				// if submenu is directly clicked on woocommerce.
			if ( empty( $etmfw_active_tab ) ) {
				$etmfw_active_tab = 'mwb_etmfw_plug_general';
			}
					// look for the path based on the tab id in the admin templates.
					$etmfw_tab_content_path = 'admin/partials/' . $etmfw_active_tab . '.php';

					$etmfw_tab_content_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/' . $etmfw_active_tab . '.php';
					$etmfw_mwb_etmfw_obj->mwb_etmfw_plug_load_template( $etmfw_tab_content_path, $etmfw_active_tab );

				do_action( 'mwb_etmfw_after_general_settings_form' );
			?>
		</div>
	</section>
