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

$bg_color = ! empty( get_option( 'wps_etmfw_ticket_bg_color', '' ) ) ? get_option( 'wps_etmfw_ticket_bg_color' ) : "#FFE6EA" ;
$text_color = ! empty( get_option( 'wps_etmfw_ticket_text_color', '' ) ) ? get_option( 'wps_etmfw_ticket_text_color' ) : "#ffffff" ;
// Inline style used for sending in email.
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
  <table cellspacing="0" cellpadding="0" style="width: 900px; background-color: <?php echo esc_attr( $bg_color ); ?>; margin: 0 auto;">
    <tbody>
      <tr>
        <td style="width: 25%; padding: 15px; box-sizing: border-box;">[LOGO]</td>
        <td style="width: 75%; box-sizing: border-box; background-color: #502343;">
				<table style="width: 100%; background-image: url(http://localhost:10044/wp-content/plugins/event-tickets-manager-for-woocommerce/admin/src/images/ticket-bg.png); background-size: cover;">
					<tr>
						<td>
							<table style="width: 80%; padding: 15px 15px; border-right: dashed 2px #ffffff; margin: 0 auto">
								<tr>
									<td colspan="2">
										<h1 style="margin: 0; font-size: 24px; color: <?php echo esc_attr( $text_color ); ?>; text-align: center;">[EVENTNAME]</h1>
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<p style="margin: 0; margin-top: 10px; margin-bottom: 10px; color: #FFFFFF; font-size: 16px; text-align: center;">Venue- <span style="color:<?php echo esc_attr( $text_color ); ?>">[VENUE]</span></p>
									</td>
								</tr>
								<tr>
									<td style="border-right: solid 1px #FFFFF; padding-left: 25px">
										<table>
											<tr>
												<td colspan="2" style="padding-top: 10px;">
													<p style="margin: 0; font-size: 12px; color: <?php echo esc_attr( $text_color ); ?>;">From</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 10px; color: <?php echo esc_attr( $text_color ); ?>;">[STARTDATE]</td>
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
													<p style="margin: 0; font-size: 12px; color: <?php echo esc_attr( $text_color ); ?>;">To</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 10px; color: <?php echo esc_attr( $text_color ); ?>;">[ENDDATE]</td>
											</tr>
											<!-- <tr><td style="padding-bottom: 10px; color: #FFFFFF;">12:00 am</td></tr> -->
										</table>
									</td>
								</tr>					
								<tr>
								<?php if( true == $wps_is_qr_is_enable ){?>
									<td colspan="2">
										<p style="margin: 0; margin-top: 10px; color:<?php echo esc_attr( $text_color ); ?>; font-size: 16px; text-align: center;">Ticket- <span style="color:<?php echo esc_attr( $text_color ); ?>;">[TICKET1]</span></p>
									</td>
									<?php  } ?>
								</tr>
							</table>
						</td>
						<td style="width: 25%; padding: 0; vertical-align: top; text-align: center; box-sizing: border-box;">
							<table style="100%; box-sizing: border-box; padding: 15px 20px;">
								<tr>
									<td style="background-color: #FFFFFF;padding: 5px 15px;border-radius: 5px;text-align: center;color: #7A2B7E; background-color: #FFFFFF;">Your Ticket</td>
								</tr>
								<tr>
									<td style="padding-top: 20px; width: 190px;text-align: center;color:<?php echo esc_attr( $text_color ); ?>;">
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

		<div style="width: 870px; padding: 15px; background-color: #FFE6EA; margin: 0 auto; margin-top: 20px; box-sizing: border-box;">
			<?php
				$body = get_option( 'wps_etmfw_email_body_content', '' );
				if ( '' != $body ) {
			?>
			[ADDITIONALINFO]
			<h4 style="margin-top: 0px; margin-bottom: 10px; font-size: 24px; color: #000000;">Note</h4>
			<div style="width:auto;text-align:left;vertical-align: top;">
			[EMAILBODYCONTENT]
			</div>
			<?php } ?>
		</div>
</body>
</html>
