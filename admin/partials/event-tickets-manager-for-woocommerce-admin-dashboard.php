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
$secure_nonce      = wp_create_nonce( 'wps-event-auth-nonce' );
$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-event-auth-nonce' );

if ( ! $id_nonce_verified ) {
	wp_die( esc_html__( 'Nonce Not verified', 'event-tickets-manager-for-woocommerce' ) );
}
$etmfw_default_tabs = $etmfw_wps_etmfw_obj->wps_etmfw_plug_default_tabs();
$plugin_path = 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php';
$wps_pro_is_active = false;
$tab_meta_label     = 'v' . $etmfw_wps_etmfw_obj->etmfw_get_version() . ( $wps_pro_is_active ? ' Pro' : '' );
// Check if the plugin is active.
if ( is_plugin_active( $plugin_path ) ) {
	$wps_pro_is_active = true;
	$tab_meta_label    = 'v' . $etmfw_wps_etmfw_obj->etmfw_get_version() . ' Pro';
}
?>
<div class="wps-etmfw-ui-shell">
	<?php Event_Tickets_Manager_For_Woocommerce_Admin_Layout::render_header( Event_Tickets_Manager_For_Woocommerce_Admin_UI::get_header_config() ); ?>

	<?php
	do_action( 'wps_etmfw_licensed_tab_section' );
	if ( ! $error_notice ) {
		$wps_etmfw_error_text = esc_html__( 'Settings saved !', 'event-tickets-manager-for-woocommerce' );
		$etmfw_wps_etmfw_obj->wps_etmfw_plug_admin_notice( $wps_etmfw_error_text, 'success' );
	}
	?>

	<?php Event_Tickets_Manager_For_Woocommerce_Admin_Layout::render_tabs( $etmfw_default_tabs, $etmfw_active_tab, 'event_tickets_manager_for_woocommerce_menu', $tab_meta_label ); ?>

	<?php Event_Tickets_Manager_For_Woocommerce_Admin_Layout::open_page_grid(); ?>

	<?php
	do_action( 'wps_etmfw_settings_saved' );
	do_action( 'wps_etmfw_before_general_settings_form' );
	if ( empty( $etmfw_active_tab ) ) {
		$etmfw_active_tab = 'event-tickets-manager-for-woocommerce-general';
	}
	?>
	<div class="wps-etmfw-tab-panels" data-active-tab="<?php echo esc_attr( $etmfw_active_tab ); ?>">
		<?php
		$initial_active_tab = $etmfw_active_tab;
		foreach ( $etmfw_default_tabs as $tab_key => $tab ) {
			$tab_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/' . $tab_key . '.php';
			$panel_active = $initial_active_tab === $tab_key;
			$etmfw_active_tab = $tab_key;
			$GLOBALS['etmfw_active_tab'] = $tab_key;
			ob_start();
			$etmfw_wps_etmfw_obj->wps_etmfw_plug_load_template( $tab_path, $tab_key );
			$tab_content = ob_get_clean();
			?>
			<div
				class="wps-etmfw-tab-panel<?php echo $panel_active ? ' is-active' : ''; ?>"
				data-tab-key="<?php echo esc_attr( $tab_key ); ?>"
				role="tabpanel"
				aria-hidden="<?php echo $panel_active ? 'false' : 'true'; ?>"
				<?php echo $panel_active ? '' : 'hidden'; ?>
			>
				<?php echo $tab_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<?php
		}
		$etmfw_active_tab = $initial_active_tab;
		$GLOBALS['etmfw_active_tab'] = $initial_active_tab;
		?>
	</div>
	<?php
	do_action( 'wps_etmfw_after_general_settings_form' );
	?>

	<?php Event_Tickets_Manager_For_Woocommerce_Admin_Layout::close_page_grid( Event_Tickets_Manager_For_Woocommerce_Admin_UI::get_sidebar_config() ); ?>
</div>
