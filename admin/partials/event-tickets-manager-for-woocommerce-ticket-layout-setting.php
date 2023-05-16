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
if ( isset( $_POST['wps_etmfw_new_layout_setting_save'] ) ) {

	$wps_verify_nonce_form = isset( $_POST['wps_layout_value'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_layout_value'] ) ) : '1';
	if ( wp_verify_nonce( $wps_verify_nonce_form, 'wps_layout_nonce_verify' ) ) {
		$wps_selected_pdf_ticket_template = isset( $_POST['wps_etmfw_ticket_template'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_ticket_template'] ) ) : '1';
		update_option( 'wps_etmfw_ticket_template', $wps_selected_pdf_ticket_template );
	}
}
if ( isset( $_POST['wps_etmfw_new_layout_setting_save_2'] ) ) {
	$wps_etmfw_border_type_template = isset( $_POST['wps_etmfw_border_type'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_border_type'] ) ) : 'none';
	$wps_etmfw_pdf_border_color = isset( $_POST['wps_etmfw_pdf_border_color'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_pdf_border_color'] ) ) : 'black';
	$wps_etmfw_pdf_background_color = isset( $_POST['wps_etmfw_pdf_background_color'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_pdf_background_color'] ) ) : '';
	update_option( 'wps_etmfw_border_type', $wps_etmfw_border_type_template );
	update_option( 'wps_etmfw_pdf_border_color', $wps_etmfw_pdf_border_color );
	update_option( 'wps_etmfw_pdf_background_color', $wps_etmfw_pdf_background_color );

	$wps_etmfw_pdf_text_color = isset( $_POST['wps_etmfw_pdf_text_color'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_pdf_text_color'] ) ) : '';
	$wps_etmfw_logo_size = isset( $_POST['wps_etmfw_logo_size'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_logo_size'] ) ) : '';
	$wps_etmfw_qr_size = isset( $_POST['wps_etmfw_qr_size'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_qr_size'] ) ) : '';
	$wps_etmfw_background_image = isset( $_POST['wps_etmfw_background_image'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_background_image'] ) ) : '';

	update_option( 'wps_etmfw_pdf_text_color', $wps_etmfw_pdf_text_color );
	update_option( 'wps_etmfw_logo_size', $wps_etmfw_logo_size );
	update_option( 'wps_etmfw_qr_size', $wps_etmfw_qr_size );
	update_option( 'wps_etmfw_background_image', $wps_etmfw_background_image );

}


$wps_plugin_list = get_option( 'active_plugins' );
$wps_is_pro_active = false;
$wps_plugin = 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php';
if ( in_array( $wps_plugin, $wps_plugin_list ) ) {
	$wps_is_pro_active = true;
}
$allowed_html = array(
	'span' => array(
		'class'    => array(),
		'data-tip' => array(),
	),
);
?>
<form action="" method="POST">

	<!-- Nav starts. -->
	<nav class="nav-tab-wrapper wps-etmfw-appearance-nav-tab">
		<a class="nav-tab wps-etmfw-appearance-template nav-tab-active" href="javascript:void(0);"><?php esc_html_e( 'Template', 'upsell-order-bump-offer-for-woocommerce' ); ?></a>
		<a class="nav-tab wps-etmfw-appearance-design" href="javascript:void(0);"><?php esc_html_e( 'Design', 'upsell-order-bump-offer-for-woocommerce' ); ?></a>
	</nav>
	<!-- Nav ends. -->
<div class="wps_etmfw_ticket_layout_div_wrapper" >
<input type="hidden" name="wps_layout_value" value="<?php echo esc_html( wp_create_nonce( 'wps_layout_nonce_verify' ) ); ?>"/>

				<!-- Template start -->
				<div class="wps-etmfw-template-section" >
					<?php $wps_ubo_selected_template = get_option( 'wps_etmfw_ticket_template' ); ?>
					<!-- Image wrapper -->
					<div id="available_tab" class="wps_etmfw_temp_class wps_etmfw_template_select-wrapper" >
						<!-- Template one. -->
						<div class="wps_etmfw_template_select <?php echo esc_html( 1 === (int) $wps_ubo_selected_template ? 'wps_etmfw_selected_class' : '' ); ?>">
							<input type="hidden" id="wps_etmfw_ticket_template" name='wps_etmfw_ticket_template' />
							<p class="wps_etmfw_template_name" ><strong><?php esc_html_e( 'Zenith', 'upsell-order-bump-offer-for-woocommerce' ); ?></strong></p>
							<a href="javascript:void" class="wps_etmfw_template_link" data_link = '1' >
								<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/template-1.png' ); ?>">
							</a>
						</div>

						<!-- Template two. -->
						<div id ="wps_ubo_premium_popup_1_template" class="wps_etmfw_template_select <?php echo esc_html( 2 === (int) $wps_ubo_selected_template ? 'wps_etmfw_selected_class' : '' ); ?>">
						<?php
						if ( true != $wps_is_pro_active ) {
							?>
							<span class="wps_etmfw_premium_strip"><?php esc_html_e( 'Pro', 'upsell-order-bump-offer-for-woocommerce' ); } ?></span>	
						<p class="wps_etmfw_template_name" ><strong><?php esc_html_e( 'Elixir', 'upsell-order-bump-offer-for-woocommerce' ); ?></strong></p>
							<a href="javascript:void" class=" 
							<?php
							if ( true == $wps_is_pro_active ) {
								?>
								  <?php
									echo 'wps_etmfw_template_link';
							} else {
								echo 'wps_etmfw_template_link_pro'; }
							?>
																" data_link = '2' >
								<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/template-2.png' ); ?>">
							</a>
						</div>

						<!-- Template three. -->
						<div id ="wps_ubo_premium_popup_2_template"  class="wps_etmfw_template_select <?php echo esc_html( 3 === (int) $wps_ubo_selected_template ? 'wps_etmfw_selected_class' : '' ); ?>">
						<?php
						if ( true != $wps_is_pro_active ) {
							?>
							<span class="wps_etmfw_premium_strip"><?php esc_html_e( 'Pro', 'upsell-order-bump-offer-for-woocommerce' ); } ?></span>	
						<p class="wps_etmfw_template_name" ><strong><?php esc_html_e( 'Demure', 'upsell-order-bump-offer-for-woocommerce' ); ?></strong></p>
							<a href="javascript:void" class=" 
							<?php
							if ( true == $wps_is_pro_active ) {
								?>
								  <?php
									echo 'wps_etmfw_template_link';
							} else {
								echo 'wps_etmfw_template_link_pro'; }
							?>
																" data_link = '3' >
								<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/template-3.png' ); ?>">
							</a>
						</div>
						
						<!-- Template four. -->
						<div id ="wps_ubo_premium_popup_3_template" class="wps_etmfw_template_select <?php echo esc_html( 4 === (int) $wps_ubo_selected_template ? 'wps_etmfw_selected_class' : '' ); ?>">
						<?php
						if ( true != $wps_is_pro_active ) {
							?>
							<span class="wps_etmfw_premium_strip"><?php esc_html_e( 'Pro', 'upsell-order-bump-offer-for-woocommerce' ); } ?></span>	
						<p class="wps_etmfw_template_name" ><strong><?php esc_html_e( 'Mellifluous', 'upsell-order-bump-offer-for-woocommerce' ); ?></strong></p>
							<a href="javascript:void" class=" 
							<?php
							if ( true == $wps_is_pro_active ) {
								?>
								  <?php
									echo 'wps_etmfw_template_link';
							} else {
								echo 'wps_etmfw_template_link_pro'; }
							?>
							" data_link = '4' >
								<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/template-4.png' ); ?>">
							</a>
						</div>
					</div>
				</div>

				<!-- Design Section For Template. -->
				<!--Major div wrapper start here-->
				<div class="wps_etmfw_table_column_wrapper wps-etmfw-appearance-section-hidden">

				<!--2nd Section for the design setting.-->
				<div class="wps_etmfw_table wps_etmfw_table--border wps_etmfw_custom_template_settings ">
				<table class="form-table wps_etmfw_creation_setting">
				<tbody>
				<!-- Border style start. -->
				<tr valign="top">
					<th>Border Type</th>
					<td>
					<?php
					$attribute_description = esc_html__( 'Select among different border types for PDF Ticket.', 'upsell-order-bump-offer-for-woocommerce' );
					$wps_etmfw_border_type = ! empty( get_option( 'wps_etmfw_border_type' ) ) ? get_option( 'wps_etmfw_border_type' ) : '';
					// wps_ubo_lite_help_tip( $attribute_description );.
					echo wp_kses( wc_help_tip( $attribute_description ), $allowed_html );
					?>
				<label>

				<!-- Select options for border. -->
				<select name="wps_etmfw_border_type" class="wps_etmfw_preview_select_border_type" >

				<?php
				$border_type_array = array(
					'none' => esc_html__( 'No Border', 'upsell-order-bump-offer-for-woocommerce' ),
					'solid' => esc_html__( 'Solid', 'upsell-order-bump-offer-for-woocommerce' ),
					'dashed' => esc_html__( 'Dashed', 'upsell-order-bump-offer-for-woocommerce' ),
					'double' => esc_html__( 'Double', 'upsell-order-bump-offer-for-woocommerce' ),
					'dotted' => esc_html__( 'Dotted', 'upsell-order-bump-offer-for-woocommerce' ),
				);

				?>
				<option value="" ><?php esc_html_e( '----Select Border Type----', 'upsell-order-bump-offer-for-woocommerce' ); ?></option>
				<?php foreach ( $border_type_array as $value => $name ) : ?>
						<option <?php echo esc_html( $wps_etmfw_border_type === $value ? 'selected' : '' ); ?> value="<?php echo esc_html( $value ); ?>" ><?php echo esc_html( $name ); ?></option>
							<?php endforeach; ?>
							</select>

					</label>
				   </td>
				</tr>

				<tr valign="top">
					<th>Border Color</th>
					<td>
					<?php
					$attribute_description = esc_html__( 'Select color of different border types for PDF Ticket.', 'upsell-order-bump-offer-for-woocommerce' );
					$wps_etmfw_border_color = ! empty( get_option( 'wps_etmfw_pdf_border_color' ) ) ? get_option( 'wps_etmfw_pdf_border_color' ) : '';
					// wps_ubo_lite_help_tip( $attribute_description );.
					echo wp_kses( wc_help_tip( $attribute_description ), $allowed_html );
					?>
					<input type="text" name="wps_etmfw_pdf_border_color" class="wps_etmfw_colorpicker wps_etmfw_select_ticket_border_color" value="<?php echo esc_attr( $wps_etmfw_border_color ); ?>">
					</td>
				</tr>

				<tr valign="top">
					<th>Background Color</th>
					<td>
					<?php
					$attribute_description = esc_html__( 'Select among different border types for PDF Ticket.', 'upsell-order-bump-offer-for-woocommerce' );
					$wps_etmfw_background_color = ! empty( get_option( 'wps_etmfw_pdf_background_color' ) ) ? get_option( 'wps_etmfw_pdf_background_color' ) : '';
					// wps_ubo_lite_help_tip( $attribute_description );.
					echo wp_kses( wc_help_tip( $attribute_description ), $allowed_html );
					?>
					<input type="text" name="wps_etmfw_pdf_background_color" class="wps_etmfw_colorpicker wps_etmfw_select_ticket_background" value="<?php echo esc_attr( $wps_etmfw_background_color ); ?>">
					</td>
				</tr>

				<tr valign="top">
					<th>Text Color</th>
					<td>
					<?php
					$attribute_description = esc_html__( 'Select among different border types for PDF Ticket.', 'upsell-order-bump-offer-for-woocommerce' );
					$wps_etmfw_pdf_text_color = ! empty( get_option( 'wps_etmfw_pdf_text_color' ) ) ? get_option( 'wps_etmfw_pdf_text_color' ) : '';
					// wps_ubo_lite_help_tip( $attribute_description );.
					echo wp_kses( wc_help_tip( $attribute_description ), $allowed_html );
					?>
					<input type="text" name="wps_etmfw_pdf_text_color" class="wps_etmfw_colorpicker wps_etmfw_pdf_text_color" value="<?php echo esc_attr( $wps_etmfw_pdf_text_color ); ?>">
					</td>
				</tr>

				<tr valign="top">
					<th>Logo Size</th>
					<td>
					<?php
					$attribute_description = esc_html__( 'Select among different border types for PDF Ticket.', 'upsell-order-bump-offer-for-woocommerce' );
					$wps_etmfw_logo_size = ! empty( get_option( 'wps_etmfw_logo_size' ) ) ? get_option( 'wps_etmfw_logo_size' ) : '';
					// wps_ubo_lite_help_tip( $attribute_description );.
					echo wp_kses( wc_help_tip( $attribute_description ), $allowed_html );
					?>
					<input type="range" min="100" value="<?php echo esc_attr( $wps_etmfw_logo_size ); ?>"  max="200" value="" name='wps_etmfw_logo_size' class="wps_etmfw_logo_size_slider" />
					<span class="wps_etmfw_logo_size_slider_span" ><?php echo esc_attr( $wps_etmfw_logo_size . 'px' ); ?></span>
					</td>
				</tr>

				<tr valign="top">
					<th>QR Size</th>
					<td>
					<?php
					$attribute_description = esc_html__( 'Select among different border types for PDF Ticket.', 'upsell-order-bump-offer-for-woocommerce' );
					$wps_etmfw_qr_size = ! empty( get_option( 'wps_etmfw_qr_size' ) ) ? get_option( 'wps_etmfw_qr_size' ) : '';
					// wps_ubo_lite_help_tip( $attribute_description );.
					echo wp_kses( wc_help_tip( $attribute_description ), false );
					// echo '<span class="woocommerce-help-tip" data-tip="' . wc_sanitize_tooltip( $attribute_description ) . '"></span>';.
					?>
					<input type="range" min="100" value="<?php echo esc_attr( $wps_etmfw_qr_size );  // echo esc_html( $wps_upsell_bumps_list[ $wps_upsell_bump_id ]['design_css']['top_vertical_spacing'] );. ?>"  max="220" value="" name='wps_etmfw_qr_size' class="wps_etmfw_qr_size_slider" />
					<span class="wps_etmfw_qr_size_slider_span" ><?php echo esc_attr( $wps_etmfw_qr_size . 'px' ); ?></span>
					</td>
				</tr>

				<tr class="wps_etmfw_hide_setting" valign="top">
					<th>Background Image</th>
					<td>
					<?php
					$attribute_description = esc_html__( 'Select among different border types for PDF Ticket.', 'upsell-order-bump-offer-for-woocommerce' );
					$wps_etmfw_background_image = ! empty( get_option( 'wps_etmfw_background_image' ) ) ? get_option( 'wps_etmfw_background_image' ) : '';
					// wps_ubo_lite_help_tip( $attribute_description );.
					echo wp_kses( wc_help_tip( $attribute_description ), $allowed_html );
					?>
					<?php
					if ( ! empty( $wps_etmfw_background_image ) ) {

						$image_attributes = wp_get_attachment_image_src( $wps_etmfw_background_image, 'thumbnail' );
						?>
					<div class="wps_wocuf_saved_custom_image">
					<a href="#" class="wps_etmfw_upload_image_button button"><img src="<?php echo esc_url( $image_attributes[0] ); ?>" style="max-width:150px;display:block;"></a>
					<input type="hidden" name="wps_etmfw_background_image" id="wps_etmfw_background_image_1" value="<?php echo esc_attr( $image_post_id ); ?>">
					<a href="#" class="wps_etmfw_remove_image_button button" style="display:inline-block;margin-top: 10px;display:inline-block;"><?php esc_html_e( 'Remove Image', 'upsell-order-bump-offer-for-woocommerce-pro' ); ?></a>
				</div>
				<?php } else { ?>
						<div class="wps_wocuf_saved_custom_image"> 
						<a href="#" class="wps_etmfw_upload_image_button button"><?php esc_html_e( 'Upload image', 'upsell-order-bump-offer-for-woocommerce-pro' ); ?></a>
						<input type="hidden" name="wps_etmfw_background_image" id="wps_etmfw_background_image" value="<?php echo esc_attr( get_option( 'm1' ) ); ?>">
						<a href="#" class="wps_etmfw_remove_image_button button" style="display:inline-block;margin-top: 10px;display:none;"><?php esc_html_e( 'Remove Image', 'upsell-order-bump-offer-for-woocommerce-pro' ); ?></a>
						</div>
						<?php } ?>
					</td>
				</tr>

				</tbody>
				</table>
				</div>
				</div>
				<!--Major div wrapper end here.-->

				<!-- Preview start -->
				<div class="wps_etmfw_offer_preview" >
				<div class="wps_etmfw_offer_preview_in" >
					<?php $wps_ubo_selected_template = ! empty( get_option( 'wps_etmfw_ticket_template' ) ) ? get_option( 'wps_etmfw_ticket_template' ) : '1'; ?>
					<h3 class="wps_ubo_offer_preview_heading"><?php esc_html_e( 'PDF Ticket Preview', 'upsell-order-bump-offer-for-woocommerce' ); ?></h3>
					<?php if ( 1 === (int) $wps_ubo_selected_template ) { ?>
					<?php include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'Demo/wps-etmfw-mail-html-content.php'; } // Zenith. ?>
					<?php if ( 2 === (int) $wps_ubo_selected_template ) { ?>
					<?php include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'Demo/wps-etmfw-mail-html-content-1.php'; } // // Elixir. ?>
					<?php if ( 3 === (int) $wps_ubo_selected_template ) { ?>
					<?php include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'Demo/wps-etmfw-mail-html-content-2.php'; } // Demure. ?>
					<?php if ( 4 === (int) $wps_ubo_selected_template ) { ?>
					<?php include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'Demo/wps-etmfw-mail-html-content-3.php'; } // Mellifluous. ?>			
				</div>
				</div>
				<!-- Preview end -->
			</div>
				<div class="wps-form-group wps_center_save_changes" >
							<div class="wps-form-group__control">
								<button class="mdc-button mdc-button--raised" name= "wps_etmfw_new_layout_setting_save_2" id = 'wps_etmfw_new_layout_setting_save_2'><span class="mdc-button__ripple"></span>
									<span class="mdc-button__label"><?php echo 'Save'; ?></span>
								</button>
								<button class="mdc-button mdc-button--raised" name= "reset_wps" id = 'wps_etmfw_new_layout_setting_save_3'><span class="mdc-button__ripple"></span>
									<span class="mdc-button__label"><?php echo 'Reset'; ?></span>
								</button>
							</div>
						</div>

				<p class="submit" class="wps_hide_save_button" >
			<input type="submit" class="wps_hide_save_button" value="<?php esc_html_e( 'Save Changes', 'upsell-order-bump-offer-for-woocommerce' ); ?>" class="button-primary woocommerce-save-button" name="wps_etmfw_new_layout_setting_save" id="wps_etmfw_new_layout_setting_save" >
		</p>
</form>

<div class="wps_etmfw_animation_loader">
<img src="images/spinner-2x.gif">
</div>

<!-- Skin Change Popup -->
<div class="wps_etmfw_skin_popup_wrapper">
	<div class="wps_etmfw_skin_popup_inner">
		<!-- Popup icon -->
		<div class="wps_etmfw_skin_popup_head">
			<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/icons/warning.png' ); ?>">
		</div>
		<!-- Popup body. -->
		<div class="wps_etmfw_skin_popup_content">
			<div class="wps_etmfw_skin_popup_ques">
				<h5><strong><?php esc_html_e( 'Do you really want to change PDF Ticket layout ?', 'upsell-order-bump-offer-for-woocommerce' ); ?></strong></h5>
			</div>
			<div class="wps_etmfw_skin_popup_option">
				<!-- Yes button. -->
				<a href="javascript:void(0);" class="wps_ubo_template_layout_yes"><?php esc_html_e( 'Yes', 'upsell-order-bump-offer-for-woocommerce' ); ?></a>
				<!-- No button. -->
				<a href="javascript:void(0);" class="wps_ubo_template_layout_no"><?php esc_html_e( 'No', 'upsell-order-bump-offer-for-woocommerce' ); ?></a>
			</div>
		</div>
	</div>
</div>
<?php
if ( isset( $_POST['reset_wps'] ) ) {
	$wps_ubo_selected_template = get_option( 'wps_etmfw_ticket_template', '1' );
	if ( 1 === (int) $wps_ubo_selected_template ) {

		 update_option( 'wps_etmfw_logo_size', '133' );
		 update_option( 'wps_etmfw_qr_size', '133' );
		 update_option( 'wps_etmfw_pdf_background_color', '#2196f3' );
		 update_option( 'wps_etmfw_pdf_text_color', '#ffffff' );
		 update_option( 'wps_etmfw_border_type', 'solid' );
		 update_option( 'wps_etmfw_pdf_border_color', 'black' );
		// update_option('wps_etmfw_background_image','#ffffff');.
	}


	if ( 2 === (int) $wps_ubo_selected_template ) {

		update_option( 'wps_etmfw_logo_size', '133' );
		update_option( 'wps_etmfw_qr_size', '133' );
		update_option( 'wps_etmfw_pdf_background_color', '#f5ebeb' );
		update_option( 'wps_etmfw_pdf_text_color', '#000000' );
		update_option( 'wps_etmfw_border_type', 'none' );
		update_option( 'wps_etmfw_pdf_border_color', 'black' );
	}
	// include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'Demo/wps-etmfw-mail-html-content-1.php'; } // // Elixir.


	if ( 3 === (int) $wps_ubo_selected_template ) {

		update_option( 'wps_etmfw_logo_size', '133' );
		update_option( 'wps_etmfw_qr_size', '133' );
		update_option( 'wps_etmfw_pdf_background_color', '#D77565' );
		update_option( 'wps_etmfw_pdf_text_color', '#000000' );
		update_option( 'wps_etmfw_border_type', 'none' );
		update_option( 'wps_etmfw_pdf_border_color', 'black' );
	} // Demure.


	if ( 4 === (int) $wps_ubo_selected_template ) {

		update_option( 'wps_etmfw_logo_size', '133' );
		update_option( 'wps_etmfw_qr_size', '133' );
		update_option( 'wps_etmfw_pdf_background_color', '#FFE6EA' );
		update_option( 'wps_etmfw_pdf_text_color', '#ffffff' );
		update_option( 'wps_etmfw_border_type', 'none' );
		update_option( 'wps_etmfw_pdf_border_color', 'black' );
	} // Mellifluous.
}
