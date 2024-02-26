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

if ( is_plugin_active( 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php' ) ) { 
	$wps_etmfw_background_color = ! empty( get_option( 'wps_etmfw_pdf_background_color' ) ) ? get_option( 'wps_etmfw_pdf_background_color' ) : '#006333';
	$wps_etmfw_text_color = ! empty( get_option( 'wps_etmfw_pdf_text_color' ) ) ? get_option( 'wps_etmfw_pdf_text_color' ) : '#ffffff';
} else {
	$wps_etmfw_background_color = ! empty( get_option( 'wps_etmfw_ticket_bg_color', '' ) ) ? get_option( 'wps_etmfw_ticket_bg_color' ) : '#f5ebeb';
	$wps_etmfw_text_color = ! empty( get_option( 'wps_etmfw_ticket_text_color', '' ) ) ? get_option( 'wps_etmfw_ticket_text_color', '' ) : '#f5ebeb';

}

$image_attributes = wp_get_attachment_image_src( get_option( 'wps_etmfw_background_image' ), 'thumbnail' );
$wps_etmfw_logo_size = ! empty( get_option( 'wps_etmfw_logo_size', true ) ) ? get_option( 'wps_etmfw_logo_size', true ) : '180';
$wps_etmfw_qr_size = ! empty( get_option( 'wps_etmfw_qr_size' ) ) ? get_option( 'wps_etmfw_qr_size' ) : '180';
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
								<img id="wps_wem_logo_id" class="wps_wem_logo" src="<?php echo esc_url( get_option( 'wps_etmfw_mail_setting_upload_logo', '' ) ); ?>" style="width:<?php echo esc_attr( $wps_etmfw_logo_size . 'px' ); ?>;margin-left: 25px">
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
													<h1 class="wps_etmfw_pdf_text_colour" style="margin: 0 0 15px;font-size: 32px;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">[EVENTNAME]</h1>
												</td>
											</tr>
											<tr>
												<td style="color: #ffffff;padding: 10px 0;">
													<h3 class="wps_etmfw_pdf_text_colour" style="margin: 0;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Venue - [VENUE]</h3>
												</td>
											</tr>
											<tr>
												<td style="color: #ffffff;padding: 10px 0;">									
													<h3 class="wps_etmfw_pdf_text_colour" style="margin: 0;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Date - [STARTDATE] To [ENDDATE]</h3>						
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
																<h3 class="wps_etmfw_pdf_text_colour" style="margin: 0;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Ticket - <span style="color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">[TICKET1]</span></h3>						
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
								<?php if('on' == get_option( 'wps_etmfwp_include_barcode' )){ ?>
								<td style="background: white;">
								<?php } else { ?>
								<td style="background: #000000">
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
												<?php } ?>
											</tr>
											<tr>
												<td>
													<h3  style="margin: 0;text-align: center;color:<?php echo esc_attr( $wps_etmfw_text_color ); ?>;"> [TICKET]</h3>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					[ADDITIONALINFO]
				</td>
			</tr>
		</tbody>
	</table>
	<div id="wps_etmfw_parent_wrapper_2" class="wps_etmfw_border_color" style="margin-right:0px;margin-left:0px;border:2px <?php echo esc_attr( $wps_etmfw_border_type . ' ' . $wps_etmfw_border_color ); ?>">
	<?php
	$body = get_option( 'wps_etmfw_email_body_content', '' );
	if ( '' != $body ) {
		?>
									<h4 style="padding: 10px;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>">Note</h4>
									<div style="padding: 20px;width:auto;text-align:left;vertical-align: middle;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?> ! important; ">
									[EMAILBODYCONTENT]
									</div>
									<?php } ?>
	</div>
</body>
</html>
