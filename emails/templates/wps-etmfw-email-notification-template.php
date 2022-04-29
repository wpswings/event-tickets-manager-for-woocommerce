<?php
/**
 * Points and rewards email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/wps-wpr-email-notification-template.php.
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/emails/templates
 * @author     WPSwings<ticket@wpswings.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
* This hooks use for emaail header
 *
 * @hooked WC_Emails::email_header() Output the email header
 */
// Inline style used for sending in email.
$template = '<table class="wps-wuc__email-template" style=" border: 1px solid #000000 ;width: 100%!important; max-width: 600px; text-align: left; font-size: 20px;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0" align="center">
	<tbody>
		<tr>
			<td style="background: #fff;">
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tbody>
						<tr style="background-color: #000000;">
							<th colspan="4">
								<h2 style="color: #ffffff; font-size: 30px; margin: 20px 0; text-align: center;">[EVENTNAME]</h2>
							</th>
						</tr>
					</tbody>
				</table>
				<table style="width: 100%; padding: 15px;" border="0" cellspacing="0" cellpadding="0">
					<tbody>
						<tr style="font-size: 16px;">
							
							<th style="width: 25%;">Purchaser</th>
						</tr>						
						<tr style="font-size: 16px;">
							
							<td>[PURCHASER]</td>
						</tr>
					</tbody>
				</table>
				<table style="border-top: 2px solid #000000; padding: 10px 0; margin: 0 auto; width: 95%;" border="0" width="100%" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td style="text-align: center;">
								[FEATUREDIMAGE]
							</td>						
							<td>
								<h3 style="color: #000000; font-size: 26px; margin: 20px 0 0; text-align: left;">Check In For This Event</h3>
														
								<p style="font-size: 16px;">[TIME]</p>
								<p style="font-size: 16px;">[VENUE]</p>	
							</td>
							<td style="text-align: right;">
								[QRCODE]
							</td>
						</tr>						
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>';
if( array_key_exists( 'event', $email_content ) ) {

	$template = str_replace( '[EVENTNAME]', $email_content['event'], $template );
}
if( array_key_exists( 'ticket', $email_content ) ) {

	$template = str_replace( '[TICKET]', $email_content['ticket'], $template );
}
if( array_key_exists( 'purchaser', $email_content ) ) {

	$template = str_replace( '[PURCHASER]', $email_content['purchaser'], $template );
}
if( array_key_exists( 'email_body', $email_content ) ) {

	$template = str_replace( '[EMAIL_BODY]', $email_content['email_body'], $template );
}
if( array_key_exists( 'time', $email_content ) ) {

	$template = str_replace( '[TIME]', $email_content['time'], $template );
}
if( array_key_exists( 'featuredimage', $email_content ) ) {

	$template = str_replace( '[FEATUREDIMAGE]', $email_content['featuredimage'], $template );
}
if( array_key_exists( 'qrcode', $email_content ) ) {

	$qr_code  = isset( $email_content['qrcode'] ) ? $email_content['qrcode'] : '';
	$template = str_replace( '[QRCODE]', $qr_code, $template );
}


echo wp_kses_post( html_entity_decode( $template ) ); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped

/**
* This hooks use for emaail footer
 *
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
