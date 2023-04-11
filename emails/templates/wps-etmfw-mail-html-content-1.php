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
$bg_color = ! empty( get_option( 'wps_etmfw_ticket_bg_color', '' ) ) ? get_option( 'wps_etmfw_ticket_bg_color' ) : "#f5ebeb" ;
$text_color = ! empty( get_option( 'wps_etmfw_ticket_text_color', '' ) ) ? get_option( 'wps_etmfw_ticket_text_color' ) : "#000000" ;

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
        <td style="width: 50%; box-sizing: border-box;">
					<table style="width: 100%; padding: 15px 15px; border-right: dashed 2px #B3B9C2;">
						<tr>
							<td colspan="2">
								<h1 style="margin: 0; font-size: 24px; color: <?php echo esc_attr( $text_color ); ?>;">[EVENTNAME]</h1>
								<p style="margin: 0; margin-top: 10px; margin-bottom: 10px; color: <?php echo esc_attr( $text_color ); ?>; font-size: 16px;">Venue- <span style="color:<?php echo esc_attr( $text_color ); ?>">[VENUE]</span></p>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="border-top: #C4CDD5 solid 2px; padding-top: 10px;">
								<p style="margin: 0; font-size: 12px; color: <?php echo esc_attr( $text_color ); ?>;">From</p>
							</td>
						</tr>
						<tr>
							<td style="padding-bottom: 10px; color: <?php echo esc_attr( $text_color ); ?>;">[STARTDATE]</td>
							<!-- <td style="padding-bottom: 10px; color: #1E1E1E;">02:23 pm</td> -->
						</tr>
						<tr>
							<td colspan="2">
								<p style="margin: 0; font-size: 12px; color: <?php echo esc_attr( $text_color ); ?>;">To</p>
							</td>
						</tr>
						<tr>
							<td style="padding-bottom: 10px; color: <?php echo esc_attr( $text_color ); ?>;">[ENDDATE]</td>
							<!-- <td style="padding-bottom: 10px; color: #1E1E1E;">12:00 am</td> -->
						</tr>
						<tr>
						<?php if( true == $wps_is_qr_is_enable ){?>
							<td colspan="2" style="border-top: #C4CDD5 solid 2px; padding-top: 10px;">
								<p style="margin: 0; margin-top: 10px; color: <?php echo esc_attr( $text_color ); ?>; font-size: 16px;">Ticket- <span style="color:<?php echo esc_attr( $text_color ); ?>">[TICKET1]</span></p>
							</td>
							<?php } ?>
						</tr>
					</table>
        </td>
        <td style="width: 25%; padding: 15px 15px; vertical-align: top; text-align: center; box-sizing: border-box;">
					<table style="100%; box-sizing: border-box;">
						<tr>
							<td style="background-color: #483DE0;padding: 5px 15px;border-radius: 5px;text-align: center;color: <?php echo esc_attr( $text_color ); ?>;">Your Ticket</td>
						</tr>
						<tr>
							<td style="padding-top: 20px; width: 190px;text-align: center;">
								[TICKET]
							</td>
						</tr>
					</table>
        </td>
      </tr>
    </tbody>
  </table>

		<div style="width: 870px; padding: 15px; background-color: #f5ebeb; margin: 0 auto; margin-top: 20px; box-sizing: border-box;">
			<?php
				$body = get_option( 'wps_etmfw_email_body_content', '' );
				if ( '' != $body ) {
			?>
			[ADDITIONALINFO]
			<h4 style="margin-top: 15px; margin-bottom: 10px; font-size: 24px; color: #000000;">Note</h4>
			<div style="width:auto;text-align:left;vertical-align: top;">
			[EMAILBODYCONTENT]
			</div>
			<?php } ?>
		</div>
</body>
</html>
