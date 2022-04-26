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
// Inline style used for sending in email.
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
	<table border="1" cellspacing="0" cellpadding="0" style="padding: 20px;table-layout: auto; width: 100%;">
		<tbody>
			<tr>
				<td style="padding: 20px;">
					<table border="0" cellspacing="0" cellpadding="0" style="table-layout: auto; width: 100%;">
						<tbody>
							<tr style="width: 100%;">
								<td style="width: 20%;background: #000000;">
									[LOGO]
								</td>
								<td style="width: 60%;background: #2196f3;">
									<table style="padding: 20px; table-layout: auto; width: 100%;">
										<tbody>
											<tr>
												<td style="text-align: center;">
													<h1 style="margin: 0 0 15px;font-size: 32px;color: #ffffff;">[EVENTNAME]</h1>
												</td>
											</tr>
											<tr>
												<td style="color: #ffffff;padding: 10px 0;">
													<h3 style="margin: 0;color: #ffffff;">Venue - [VENUE]</h3>
												</td>
											</tr>
											<tr>
												<td style="color: #ffffff;padding: 10px 0;">									
													<h3 style="margin: 0;color: #ffffff;">Date - [STARTDATE] To [ENDDATE]</h3>						
												</td>
											</tr>
										</tbody>
									</table>
								</td>
								<td style="background: #000000;">
									<table style="table-layout: auto; width: 100%;">
										<tbody>
											<tr>
												<td style="text-align: center;color: #ffffff;">
													<h3>Your Ticket</h3>
												</td>
											</tr>
											<tr>
												<td>
													<h3 style="margin: 0;text-align: center;color: #ffffff;">[TICKET]</h3>
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
	<div  style="margin-right:20px;margin-left:20px;border: 1px solid black">
	<?php $body = get_option( 'wps_etmfw_email_body_content', '' );
								if( '' != $body ) {?>
									<h4 style="padding: 10px;">Note</h4>
									<div style="padding: 20px;width:auto;text-align:left;vertical-align: middle;">
										[EMAILBODYCONTENT]
									</div>
									<?php } ?>
	</div>
</body>
</html>
