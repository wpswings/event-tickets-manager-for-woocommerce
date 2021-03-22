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

global $etmfw_mwb_etmfw_obj;
$etmfw_active_tab   = isset( $_GET['etmfw_tab'] ) ? sanitize_key( $_GET['etmfw_tab'] ) : 'event-tickets-manager-for-woocommerce-general';
$etmfw_default_tabs = $etmfw_mwb_etmfw_obj->mwb_etmfw_plug_default_tabs();
?>
<header>
	<div class="mwb-header-container mwb-bg-white mwb-r-8">
		<h1 class="mwb-header-title"><?php echo esc_attr( strtoupper( str_replace( '-', ' ', $etmfw_mwb_etmfw_obj->etmfw_get_plugin_name() ) ) ); ?></h1>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=event_tickets_manager_for_woocommerce_menu' ) . '&etmfw_tab=' . esc_attr( 'event-tickets-manager-for-woocommerce-support' ) ); ?>" class="mwb-link"><?php esc_html_e( 'Support', 'event-tickets-manager-for-woocommerce' ); ?></a>
	</div>
</header>

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

				$etmfw_mwb_etmfw_obj->mwb_etmfw_plug_load_template( $etmfw_tab_content_path );

				do_action( 'mwb_etmfw_after_general_settings_form' );
			?>
		</div>
	</section>
