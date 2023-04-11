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

$bg_color = ! empty( get_option( 'wps_etmfw_ticket_bg_color', '' ) ) ? get_option( 'wps_etmfw_ticket_bg_color' ) : "#D77565" ;
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
					<table style="width: 100%; padding: 15px 15px; border-left: dashed 2px #B3B9C2;">
						<tr>
							<td colspan="2">
								<h1 style="margin: 0; font-size: 24px; color: <?php echo esc_attr( $text_color ); ?>;">[EVENTNAME]</h1>
							</td>
						</tr>
						<tr>
							<td>
								<p style="margin: 0; margin-top: 10px; margin-bottom: 10px; color: <?php echo esc_attr( $text_color ); ?>; font-size: 16px;">Venue- <br><span style="color:<?php echo esc_attr( $text_color ); ?>">[VENUE]</span></p>
							</td>
                            <?php  if(true == $wps_is_qr_is_enable){?>
							<td>
								<p style="margin: 0; margin-top: 10px; color: <?php echo esc_attr( $text_color ); ?>; font-size: 16px;">Ticket- <br><span style="color:<?php echo esc_attr( $text_color ); ?>">[TICKET1]</span></p>
							</td>
                            <?php  } ?>
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
					</table>
        </td>
                    <td style="width: 25%; padding: 0; vertical-align: top; text-align: center; box-sizing: border-box; background-image: url(http://localhost:10044/wp-content/plugins/event-tickets-manager-for-woocommerce/admin/src/images/qr-bg.png); background-size: cover;">
					<table style="100%; box-sizing: border-box; padding: 15px 20px;">
						<tr>
							<td style="background-color: #65C7D7;padding: 5px 15px;border-radius: 5px;text-align: center;color: <?php echo esc_attr( $text_color ); ?>; background-color: #ffffff;">Your Ticket</td>
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

		<div style="width: 870px; padding: 15px; background-color:<?php echo esc_attr( $bg_color ); ?>; margin: 0 auto; margin-top: 20px; box-sizing: border-box;">
			<?php
				$body = get_option( 'wps_etmfw_email_body_content', '' );
				if ( '' != $body ) {
			?>
			[ADDITIONALINFO]
			<h4 style="margin-top: 0px; margin-bottom: 0px; font-size: 24px; color:#000000;">Note</h4>
			<div style="width:auto;text-align:left;vertical-align: top;">
			[EMAILBODYCONTENT]
			</div>
			<?php } ?>
		</div>
</body>
</html>
