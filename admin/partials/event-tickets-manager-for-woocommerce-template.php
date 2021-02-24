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
$etmfw_template_settings = apply_filters( 'etmfw_template_settings_array', array() );
?>
<!--  template file for admin settings. -->
<div class="etmfw-section-wrap">
	<?php
		$etmfw_template_html = $etmfw_mwb_etmfw_obj->mwb_etmfw_plug_generate_html( $etmfw_template_settings );
		echo esc_html( $etmfw_template_html );
	?>
</div>
