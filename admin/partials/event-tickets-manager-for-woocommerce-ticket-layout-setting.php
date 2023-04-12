<?php 
if(isset($_POST['wps_etmfw_new_layout_setting_save'])) {
	$wps_selected_pdf_ticket_template = isset($_POST['wps_etmfw_ticket_template']) ? $_POST['wps_etmfw_ticket_template'] : '1';
    update_option( 'wps_etmfw_ticket_template', $wps_selected_pdf_ticket_template);
}
$pluginList = get_option( 'active_plugins' );
$wps_is_pro_active = false;
$plugin = 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php'; 
if ( in_array( $plugin , $pluginList ) ) {
   $wps_is_pro_active = true;
}
?>
<form action="" method="POST">
<div class="wps_etmfw_ticket_layout_div_wrapper" >
				<!-- Template start -->
				<div class="wps-etmfw-template-section" >
					<?php $wps_ubo_selected_template = get_option( 'wps_etmfw_ticket_template'); ?>
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
						<?php if(true != $wps_is_pro_active){?><span class="wps_etmfw_premium_strip"><?php esc_html_e( 'Pro', 'upsell-order-bump-offer-for-woocommerce' ); }?></span>	
						<p class="wps_etmfw_template_name" ><strong><?php esc_html_e( 'Elixir', 'upsell-order-bump-offer-for-woocommerce' ); ?></strong></p>
							<a href="javascript:void" class=" <?php if(true == $wps_is_pro_active){ ?>  <?php echo 'wps_etmfw_template_link'; } else { echo 'wps_etmfw_template_link_pro'; } ?>" data_link = '2' >
								<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/template-2.png' ); ?>">
							</a>
						</div>

						<!-- Template three. -->
						<div id ="wps_ubo_premium_popup_2_template"  class="wps_etmfw_template_select <?php echo esc_html( 3 === (int) $wps_ubo_selected_template ? 'wps_etmfw_selected_class' : '' ); ?>">
						<?php if(true != $wps_is_pro_active){?><span class="wps_etmfw_premium_strip"><?php esc_html_e( 'Pro', 'upsell-order-bump-offer-for-woocommerce' ); }?></span>	
						<p class="wps_etmfw_template_name" ><strong><?php esc_html_e( 'Demure', 'upsell-order-bump-offer-for-woocommerce' ); ?></strong></p>
							<a href="javascript:void" class=" <?php if(true == $wps_is_pro_active){ ?>  <?php echo 'wps_etmfw_template_link'; } else { echo 'wps_etmfw_template_link_pro'; } ?>" data_link = '3' >
								<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/template-3.png' ); ?>">
							</a>
						</div>
						
						<!-- Template four. -->
						<div id ="wps_ubo_premium_popup_3_template" class="wps_etmfw_template_select <?php echo esc_html( 4 === (int) $wps_ubo_selected_template ? 'wps_etmfw_selected_class' : '' ); ?>">
						<?php if(true != $wps_is_pro_active){?><span class="wps_etmfw_premium_strip"><?php esc_html_e( 'Pro', 'upsell-order-bump-offer-for-woocommerce' ); }?></span>	
						<p class="wps_etmfw_template_name" ><strong><?php esc_html_e( 'Mellifluous', 'upsell-order-bump-offer-for-woocommerce' ); ?></strong></p>
							<a href="javascript:void" class=" <?php if(true == $wps_is_pro_active){ ?>  <?php echo 'wps_etmfw_template_link'; } else { echo 'wps_etmfw_template_link_pro'; } ?>" data_link = '4' >
								<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/template-4.png' ); ?>">
							</a>
						</div>
					</div>
				</div>

				
				<div class="wps-form-group wps_center_save_changes" >
							<div class="wps-form-group__control">
								<button class="mdc-button mdc-button--raised" name= "" id = 'wps_etmfw_new_layout_setting_save_2'><span class="mdc-button__ripple"></span>
									<span class="mdc-button__label"><?php echo 'Save'; ?></span>
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
