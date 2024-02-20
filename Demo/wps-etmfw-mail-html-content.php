<?php
/**
 * Exit if accessed directly
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/emails/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$image_attributes = wp_get_attachment_image_src( get_option( 'wps_etmfw_background_image' ), 'thumbnail' );
$wps_etmfw_logo_size = ! empty( get_option( 'wps_etmfw_logo_size', true ) ) ? get_option( 'wps_etmfw_logo_size', true ) : '180';
$wps_etmfw_qr_size = ! empty( get_option( 'wps_etmfw_qr_size' ) ) ? get_option( 'wps_etmfw_qr_size' ) : '180';
$wps_etmfw_background_color = ! empty( get_option( 'wps_etmfw_pdf_background_color' ) ) ? get_option( 'wps_etmfw_pdf_background_color' ) : '#006333';
$wps_etmfw_text_color = ! empty( get_option( 'wps_etmfw_pdf_text_color' ) ) ? get_option( 'wps_etmfw_pdf_text_color' ) : '#ffffff';
// Inline style used for sending in email.
$wps_etmfw_border_type = ! empty( get_option( 'wps_etmfw_border_type' ) ) ? get_option( 'wps_etmfw_border_type' ) : 'none';
$wps_etmfw_border_color = ! empty( get_option( 'wps_etmfw_pdf_border_color' ) ) ? get_option( 'wps_etmfw_pdf_border_color' ) : '#000000';
$wps_etmfw_logo_url = ! empty( get_option( 'wps_etmfw_mail_setting_upload_logo' ) ) ? get_option( 'wps_etmfw_mail_setting_upload_logo' ) : '';
$wps_etmfw_email_body_content = ! empty( get_option( 'wps_etmfw_email_body_content' ) ) ? get_option( 'wps_etmfw_email_body_content' ) : '';
$wps_etmfw_qr_code_is_enable = ! empty( get_option( 'wps_etmfwp_include_qr' ) ) ? get_option( 'wps_etmfwp_include_qr' ) : '';
?>
<!DOCTYPE html>
<html>
<head>
	<style>
		body , html {
			font-family: helvetica;
		}
	</style>	
</head>
<body>	
	<table cellspacing="0" class="wps_etmfw_border_color" id = "wps_etmfw_parent_wrapper" cellpadding="0" style="padding: 20px;table-layout: auto; width: 100%;border:2px <?php echo esc_attr( $wps_etmfw_border_type . ' ' . $wps_etmfw_border_color ); ?>"> 
		<tbody>
			<tr>
				<td style="padding: 20px;">
					<table cellspacing="0" cellpadding="0" style="table-layout: auto; width: 100%;">
						<tbody>
							<tr style="width: 100%;">
								<td style="width: 20%;background: #000000;">
								<img id="wps_wem_logo_id" class="wps_wem_logo" src="<?php echo esc_url( $wps_etmfw_logo_url ); ?>" style="width:<?php echo esc_attr( $wps_etmfw_logo_size . 'px' ); ?>;margin-left: 25px">
								</td>
								<?php
									  $bg_color = ! empty( get_option( 'wps_etmfw_ticket_bg_color', '' ) ) ? get_option( 'wps_etmfw_ticket_bg_color' ) : '#2196f3';
									  $text_color = ! empty( get_option( 'wps_etmfw_ticket_text_color', '' ) ) ? get_option( 'wps_etmfw_ticket_text_color' ) : '#ffffff';
								?>
								<td style="width: 60%;background: <?php echo esc_attr( $wps_etmfw_background_color ); ?>">
									<table class="wps_etmfw_ticket_body" style="padding: 20px; table-layout: auto; width: 100%;">
										<tbody>
											<tr>
												<td style="text-align: center;">
													<h1 class="wps_etmfw_pdf_text_colour" style="margin: 0 0 15px;font-size: 32px;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Album JIO</h1>
												</td>
											</tr>
											<tr>
												<td style="color: #ffffff;padding: 10px 0;">
													<h3 class="wps_etmfw_pdf_text_colour" style="margin: 0;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Venue - Delhi</h3>
												</td>
											</tr>
											<tr>
												<td style="color: #ffffff;padding: 10px 0;">									
													<h3 class="wps_etmfw_pdf_text_colour" style="margin: 0;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Date - April 12,2023 1:23pm To April 28,2023 12:00am</h3>						
												</td>
											</tr>
											<?php
											require_once ABSPATH . 'wp-admin/includes/plugin.php';
											$plug           = get_plugins();
											if ( isset( $plug['event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php'] ) ) {
												if ( is_plugin_active( 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php' ) ) {

													if ( ! version_compare( $plug['event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php']['Version'], '1.0.4', '<' ) ) {
														if ( 'on' == get_option( 'wps_etmfwp_include_qr' ) ) {

															?>
														<tr>
															<td style="color: <?php echo esc_attr( $wps_etmfw_background_color ); ?>;padding: 10px 0;">									
																<h3 class="wps_etmfw_pdf_text_colour" style="margin: 0;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Ticket - FENBI</h3>						
															</td>
														</tr>
															<?php
														}
													}
												}
											}
											?>
											
										</tbody>
									</table>
								</td>
								<?php if('on' == get_option( 'wps_etmfwp_include_barcode' )) {?>
								<td style="background: white;">
								<?php } else { ?>
								<td style="background: #000000;">
								<?php } ?>
									<table style="table-layout: auto; width: 100%;">
										<tbody>
											<tr>
											<?php if('on' == get_option( 'wps_etmfwp_include_barcode' )){ ?>
												<td style="text-align: center;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">
													<h3 class="wps_etmfw_pdf_text_colour"  style="color: <?php echo esc_attr( 'black' ); ?>;">Your Ticket</h3>
												</td>
												<?php } else { ?>

												<td style="text-align: center;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">
												<h3 class="wps_etmfw_pdf_text_colour"  style="color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Your Ticket</h3>
												</td>
												<?php }   ?>
											</tr>
											<tr>
												<td>
													<h3  style="margin: 0;text-align: center;color: #ffffff;">
													<?php if ( 'on' == get_option( 'wps_etmfwp_include_qr' )) { ?>
													<img id="wps_qr_image" src="data:image/png;base64,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" style="width:<?php echo esc_attr( $wps_etmfw_qr_size . 'px' ); ?>;height:<?php echo esc_attr( $wps_etmfw_qr_size . 'px' ); ?>">
													<?php } elseif('on' == get_option( 'wps_etmfwp_include_barcode' )){
														?>
														<img id="wps_qr_image" src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/barcode.png' ); ?>" style="width:<?php echo esc_attr(100 . 'px' ); ?>;height:<?php echo esc_attr( 100 . 'px' ); ?>">
														<?php
													} else { ?>
														<h3 class="wps_etmfw_pdf_text_colour" style="margin-left: 31px;margin-top: 39px; color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">FENBI</h3>
														<?php } ?>
												</h3>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<table border="0" class="wps_etmfw_pdf_text_colour" cellpadding="0" style="table-layout: auto; width: 100%;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;"><tbody><tr><td style="padding: 20px 0 10px;"><h2 class="wps_etmfw_pdf_text_colour" style="margin: 0;font-size: 24px; color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Details :-</h2></td></tr>
					<tr><td style="padding: 5px 0;">
					<p>Name - John Doe</p>
					<p>Mob No - 978xxxxxx</p>
					<p>Do You Have Tickets?-yes</p>
					</td></tr>
					</tbody></table>
				</td>
			</tr>
		</tbody>
	</table>
	<div id="wps_etmfw_parent_wrapper_2" class="wps_etmfw_border_color" style="margin-right:0px;margin-left:0px;border:2px <?php echo esc_attr( $wps_etmfw_border_type . ' ' . $wps_etmfw_border_color ); ?>">
	<?php
	$body = $wps_etmfw_email_body_content;
	if ( '' != $body ) {
		?>
									<h4 class="wps_etmfw_pdf_text_colour" style="padding: 10px;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Note</h4>
									<div class="wps_etmfw_pdf_text_colour" style="padding: 20px;width:auto;text-align:left;vertical-align: middle;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">
									Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
									Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
									</div>
									<?php } ?>
	</div>
</body>
</html>
