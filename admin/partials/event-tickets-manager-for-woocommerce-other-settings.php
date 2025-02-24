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
global $etmfw_wps_etmfw_obj;
$etmfw_other_settings = apply_filters( 'wps_etmfw_other_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="wps-etmfw-other-section-form">
<input type="hidden" name="wps_event_nonce" value="<?php echo esc_html( wp_create_nonce( 'wps_event_nonce' ) ); ?>">
	<div class="etmfw-secion-wrap">
		<?php
		$etmfw_other_html = $etmfw_wps_etmfw_obj->wps_etmfw_plug_generate_html( $etmfw_other_settings );
		echo esc_html( $etmfw_other_html );
		?>
	</div>
</form>