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

$bg_color = ! empty( get_option( 'wps_etmfw_ticket_bg_color', '' ) ) ? get_option( 'wps_etmfw_ticket_bg_color' ) : '#FFE6EA';
$text_color = ! empty( get_option( 'wps_etmfw_ticket_text_color', '' ) ) ? get_option( 'wps_etmfw_ticket_text_color' ) : '#ffffff';
// Inline style used for sending in email.

$image_attributes = wp_get_attachment_image_src( get_option( 'wps_etmfw_background_image' ), 'thumbnail' );
$wps_etmfw_logo_size = ! empty( get_option( 'wps_etmfw_logo_size', true ) ) ? get_option( 'wps_etmfw_logo_size', true ) : '180';
$wps_etmfw_qr_size = ! empty( get_option( 'wps_etmfw_qr_size' ) ) ? get_option( 'wps_etmfw_qr_size' ) : '180';
$wps_etmfw_background_color = ! empty( get_option( 'wps_etmfw_pdf_background_color' ) ) ? get_option( 'wps_etmfw_pdf_background_color' ) : '#FFE6EA';
$wps_etmfw_text_color = ! empty( get_option( 'wps_etmfw_pdf_text_color' ) ) ? get_option( 'wps_etmfw_pdf_text_color' ) : '#ffffff';

$wps_etmfw_border_type = ! empty( get_option( 'wps_etmfw_border_type' ) ) ? get_option( 'wps_etmfw_border_type' ) : 'none';
$wps_etmfw_border_color = ! empty( get_option( 'wps_etmfw_pdf_border_color' ) ) ? get_option( 'wps_etmfw_pdf_border_color' ) : '#000000';
?>
<!DOCTYPE html>
<html>
<head>
<style>
	@import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;300;500;700;800;900&display=swap');

		body , html {
			font-family: 'Inter', sans-serif;
		}
		* {
			box-sizing: border-box;
		}
	</style>
	
</head>

<body style="background-color: #ffffff; margin: 0; box-sizing: border-box;">
  <table class="wps_etmfw_border_color wps_etmfw_ticket_body" id = "wps_etmfw_parent_wrapper" cellspacing="0" cellpadding="0" style="width: 900px; background-color: <?php echo esc_attr( $wps_etmfw_background_color ); ?>; margin: 0 auto;border:2px <?php echo esc_attr( $wps_etmfw_border_type . ' ' . $wps_etmfw_border_color ); ?>">
	<tbody>
	  <tr>
		<td style="width: 25%; padding: 15px; box-sizing: border-box;">[LOGO]</td>
		<td style="width: 75%; box-sizing: border-box; background-color: #81426e;">
				<table style="width: 100%; background-image: url(<?php echo esc_url( $image_attributes[0] ); ?>); background-size: cover;">
					<tr>
						<td>
							<table style="width: 80%; padding: 15px 15px; border-right: dashed 2px #ffffff; margin: 0 auto">
								<tr>
									<td colspan="2">
										<h1 class="wps_etmfw_pdf_text_colour" style="margin: 0; font-size: 24px; color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>; text-align: center;">[EVENTNAME]</h1>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<p class="wps_etmfw_pdf_text_colour" style="margin: 0; margin-top: 10px; margin-bottom: 10px; color:<?php echo esc_attr( $wps_etmfw_text_color ); ?>; font-size: 16px; text-align: center;">Venue- <span style="color:<?php echo esc_attr( $wps_etmfw_text_color ); ?>">[VENUE]</span></p>
									</td>
								</tr>
								<tr>
									<td style="border-right: solid 1px #FFFFF; padding-left: 25px">
										<table>
											<tr>
												<td colspan="2" style="padding-top: 10px;">
													<p class="wps_etmfw_pdf_text_colour" style="margin: 0; font-size: 12px; color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">From</p>
												</td>
											</tr>
											<tr>
												<td class="wps_etmfw_pdf_text_colour" style="padding-bottom: 10px; color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">[STARTDATE]</td>
											</tr>
											<tr>
												<!-- <td style="padding-bottom: 10px; color: #FFFFFF;">02:23 pm</td> -->
											</tr>
										</table>
									</td>
									<td style="padding-left: 25px">
										<table style="">
											<tr>
												<td colspan="2">
													<p class="wps_etmfw_pdf_text_colour" style="margin: 0; font-size: 12px; color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">To</p>
												</td>
											</tr>
											<tr>
												<td class="wps_etmfw_pdf_text_colour" style="padding-bottom: 10px; color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">[ENDDATE]</td>
											</tr>
											<!-- <tr><td style="padding-bottom: 10px; color: #FFFFFF;">12:00 am</td></tr> -->
										</table>
									</td>
								</tr>					
								<tr>
								<?php if ( 'on' == get_option( 'wps_etmfwp_include_qr' ) ) { ?>
									<td colspan="2">
										<p class="wps_etmfw_pdf_text_colour" style="margin: 0; margin-top: 10px; color:<?php echo esc_attr( $wps_etmfw_text_color ); ?>; font-size: 16px; text-align: center;">Ticket- <span style="color:<?php echo esc_attr( $wps_etmfw_text_color ); ?>;">[TICKET1]</span></p>
									</td>
									<?php } ?>
								</tr>
							</table>
						</td>
						<td style="width: 25%; padding: 0; vertical-align: top; text-align: center; box-sizing: border-box;">
							<table style="100%; box-sizing: border-box; padding: 15px 20px;">
								<tr>
									<td style="background-color: #FFFFFF;padding: 5px 15px;border-radius: 5px;text-align: center;color: #7A2B7E; background-color: #FFFFFF;color:<?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Your Ticket</td>
								</tr>
								<tr>
									<td style="padding-top: 20px; width: 190px;text-align: center;color:<?php echo esc_attr( $wps_etmfw_text_color ); ?>;">
										[TICKET]
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
		</td>
	  </tr>
	</tbody>
  </table>

		<div class="wps_etmfw_border_color wps_etmfw_ticket_body" id = "wps_etmfw_parent_wrapper_2" style="width: 870px; padding: 15px; background-color:<?php echo esc_attr( $wps_etmfw_background_color ); ?>; margin: 0 auto; margin-top: 20px; box-sizing: border-box;border:2px <?php echo esc_attr( $wps_etmfw_border_type . ' ' . $wps_etmfw_border_color ); ?>">
			<?php
				$body = get_option( 'wps_etmfw_email_body_content', '' );
			if ( '' != $body ) {
				?>
			[ADDITIONALINFO]
			<h4 style="margin-top: 0px; margin-bottom: 10px; font-size: 24px; color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Note</h4>
			<div style="width:auto;text-align:left;vertical-align: top;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?> ! important; ">
			[EMAILBODYCONTENT]
			</div>
			<?php } ?>
		</div>
</body>
</html>
