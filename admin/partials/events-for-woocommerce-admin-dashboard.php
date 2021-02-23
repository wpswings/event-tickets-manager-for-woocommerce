<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Events_For_Woocommerce
 * @subpackage Events_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}

global $efw_mwb_efw_obj;
$efw_active_tab   = isset( $_GET['efw_tab'] ) ? sanitize_key( $_GET['efw_tab'] ) : 'events-for-woocommerce-general';
$efw_default_tabs = $efw_mwb_efw_obj->mwb_efw_plug_default_tabs();
?>
<header>
	<div class="mwb-header-container mwb-bg-white mwb-r-8">
		<h1 class="mwb-header-title"><?php echo esc_attr( strtoupper( str_replace( '-', ' ', $efw_mwb_efw_obj->efw_get_plugin_name() ) ) ); ?></h1>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=events_for_woocommerce_menu' ) . '&efw_tab=' . esc_attr( 'events-for-woocommerce-support' ) ); ?>" class="mwb-link"><?php esc_html_e( 'Support', 'events-for-woocommerce' ); ?></a>
	</div>
</header>

<main class="mwb-main mwb-bg-white mwb-r-8">
	<nav class="mwb-navbar">
		<ul class="mwb-navbar__items">
			<?php
			if ( is_array( $efw_default_tabs ) && ! empty( $efw_default_tabs ) ) {

				foreach ( $efw_default_tabs as $efw_tab_key => $efw_default_tabs ) {

					$efw_tab_classes = 'mwb-link ';

					if ( ! empty( $efw_active_tab ) && $efw_active_tab === $efw_tab_key ) {
						$efw_tab_classes .= 'active';
					}
					?>
					<li>
						<a id="<?php echo esc_attr( $efw_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=events_for_woocommerce_menu' ) . '&efw_tab=' . esc_attr( $efw_tab_key ) ); ?>" class="<?php echo esc_attr( $efw_tab_classes ); ?>"><?php echo esc_html( $efw_default_tabs['title'] ); ?></a>
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
				do_action( 'mwb_efw_before_general_settings_form' );
						// if submenu is directly clicked on woocommerce.
				if ( empty( $efw_active_tab ) ) {
					$efw_active_tab = 'mwb_efw_plug_general';
				}

						// look for the path based on the tab id in the admin templates.
				$efw_tab_content_path = 'admin/partials/' . $efw_active_tab . '.php';

				$efw_mwb_efw_obj->mwb_efw_plug_load_template( $efw_tab_content_path );

				do_action( 'mwb_efw_after_general_settings_form' ); 
			?>
		</div>
	</section>
