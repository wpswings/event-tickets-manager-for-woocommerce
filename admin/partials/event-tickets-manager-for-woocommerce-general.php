<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
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
$etmfw_genaral_settings = apply_filters( 'mwb_etmfw_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="mwb-etmfw-gen-section-form">
<input type="hidden" name="mwb_event_nonce" value="<?php echo esc_html( wp_create_nonce( 'mwb_event_nonce' ) ); ?>">
	<div class="etmfw-secion-wrap">
		<?php
		$etmfw_general_html = $etmfw_mwb_etmfw_obj->mwb_etmfw_plug_generate_html( $etmfw_genaral_settings );
		echo esc_html( $etmfw_general_html );
		?>
	</div>
</form>
