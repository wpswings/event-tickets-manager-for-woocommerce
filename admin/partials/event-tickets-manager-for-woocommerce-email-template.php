<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for email template tab.
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
$etmfw_email_template_settings = apply_filters( 'wps_etmfw_email_template_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="wps-etmfw-email-template-section-form">
<input type="hidden" name="wps_event_nonce" value="<?php echo esc_html( wp_create_nonce( 'wps_event_nonce' ) ); ?>">

	<div class="etmfw-secion-wrap">
		<?php
		$etmfw_email_template_html = $etmfw_wps_etmfw_obj->wps_etmfw_plug_generate_html( $etmfw_email_template_settings );
		echo esc_html( $etmfw_email_template_html );
		wp_nonce_field( 'wps-etmfw-email-template-nonce', 'wps-etmfw-email-template-nonce-field' );
		?>
	</div>
</form>
