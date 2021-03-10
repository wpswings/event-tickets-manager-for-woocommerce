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
global $etmfw_mwb_etmfw_obj;
$etmfw_integration_settings = apply_filters( 'mwb_etmfw_integration_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="mwb-etmfw-integration-section-form">
	<div class="etmfw-secion-wrap">
		<?php
		$etmfw_integration_html = $etmfw_mwb_etmfw_obj->mwb_etmfw_plug_generate_html( $etmfw_integration_settings );
		echo esc_html( $etmfw_integration_html );
		wp_nonce_field( 'mwb-etmfw-integration-nonce', 'mwb-etmfw-integration-nonce-field' );
		?>
	</div>
</form>