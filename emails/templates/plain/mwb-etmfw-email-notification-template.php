<?php
/**
 * Points and rewards email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/mwb-wpr-email-notification-template.php.
 *
 * @package    points-and-rewards-for-wooCommerce
 * @author  makewebbetter<ticket@makewebbetter.com>
 * @since      1.0.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$template = '<table class="mwb-wuc__email-template" style=" border: 1px solid #000000 ;width: 100%!important; max-width: 600px; text-align: left; font-size: 20px;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0" align="center">
	<tbody>
		<tr>
			<td style="background: #fff;">
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tbody>
						<tr style="background-color: #000000;">
							<th colspan="4">
								<h2 style="color: #ffffff; font-size: 30px; margin: 20px 0; text-align: center;">[EventName]</h2>
							</th>
						</tr>
					</tbody>
				</table>
				<table style="width: 100%; padding: 15px;" border="0" cellspacing="0" cellpadding="0">
					<tbody>
						<tr style="font-size: 16px;">
							<th style="width: 25%;">Ticket#</th>
							<th style="width: 25%;">Purchaser</th>
						</tr>						
						<tr style="font-size: 16px;">
							<td>[TICKET]</td>
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
								<p style="font-size: 16px;">[VENUE]</p>							
								<p style="font-size: 16px;">[TIME]</p>
							</td>
						</tr>						
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>';
$template = str_replace( '[EVENTNAME]',  $email_content['event'] , $template );
$template = str_replace( '[TICKET]',  $email_content['ticket'] , $template );
$template = str_replace( '[PURCHASER]',  $email_content['purchaser'] , $template );
$template = str_replace( '[VENUE]',  $email_content['venue'] , $template );
$template = str_replace( '[TIME]',  $email_content['time'] , $template );
$template = str_replace( '[FEATUREDIMAGE]',  $email_content['featuredimage'] , $template );

echo wp_kses_post( html_entity_decode( $template ) ); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped


echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped
