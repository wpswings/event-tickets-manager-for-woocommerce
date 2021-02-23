<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Events_For_Woocommerce
 * @subpackage Events_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $efw_mwb_efw_obj;
$efw_genaral_settings = apply_filters( 'efw_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="mwb-efw-gen-section-form">
	<div class="efw-secion-wrap">
		<?php
		$efw_general_html = $efw_mwb_efw_obj->mwb_efw_plug_generate_html( $efw_genaral_settings );
		echo esc_html( $efw_general_html );
		?>
	</div>
</form>