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
	$wps_etmfw_header_background_color = ! empty( get_option( 'wps_etmfw_pdf_header_background_color' ) ) ? get_option( 'wps_etmfw_pdf_header_background_color' ) : '#81d742';
} else {
	$wps_etmfw_background_color = ! empty( get_option( 'wps_etmfw_ticket_bg_color', '' ) ) ? get_option( 'wps_etmfw_ticket_bg_color' ) : '#f5ebeb';
	$wps_etmfw_text_color = ! empty( get_option( 'wps_etmfw_ticket_text_color', '' ) ) ? get_option( 'wps_etmfw_ticket_text_color', '' ) : '#f5ebeb';
	$wps_etmfw_header_background_color = ! empty( get_option( 'wps_etmfw_ticket_bg_color' ) ) ? get_option( 'wps_etmfw_ticket_bg_color' ) : '#81d742';
}


$wps_etmfw_border_type = ! empty( get_option( 'wps_etmfw_border_type' ) ) ? get_option( 'wps_etmfw_border_type' ) : 'none';
$wps_etmfw_border_color = ! empty( get_option( 'wps_etmfw_pdf_border_color' ) ) ? get_option( 'wps_etmfw_pdf_border_color' ) : '#000000';
$wps_etmfw_logo_size = ! empty( get_option( 'wps_etmfw_logo_size', true ) ) ? get_option( 'wps_etmfw_logo_size', true ) : '180';
$wps_etmfw_qr_size = ! empty( get_option( 'wps_etmfw_qr_size' ) ) ? get_option( 'wps_etmfw_qr_size' ) : '180';
$wps_etmfw_qr_code_is_enable = ! empty( get_option( 'wps_etmfwp_include_qr' ) ) ? get_option( 'wps_etmfwp_include_qr' ) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Event Ticket</title>
</head>
<body>
	<div class="ticket-container wps_etmfw_border_color wps_etmfw_ticket_body wps_etmfw_pdf_text_colour" style="background-color:  <?php echo esc_attr( $wps_etmfw_background_color ); ?>; border: 2px <?php echo esc_attr( $wps_etmfw_border_type . ' ' . $wps_etmfw_border_color ); ?>; color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;" id="wps_event_border_type">
		<div class="ticket-header" style="background-color:  <?php echo esc_attr( $wps_etmfw_header_background_color ); ?>;">  <!-- Add different color section -->
			<h1 class="wps_etmfw_pdf_text_colour" style="text-align: center;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">[EVENTNAME]</h1>
		</div>
		<div class="ticket-info" style="padding: 20px; text-align: left; display: flex;">
			<img class="logo" id="wps_wem_logo_id" style="width: <?php echo esc_attr( $wps_etmfw_logo_size . 'px' ); ?>; height: auto; margin-right: 20px;" src="http://localhost:10009/wp-content/uploads/2024/04/Vivamor-Image-1-1-150x150.png" alt="Event Logo">
			<div style="text-align: center;">
				<p><strong>Event Name:</strong>[EVENTNAME]</p>
				<p><strong>Start Date:</strong> [STARTDATE]</p>
				<p><strong>End Date:</strong> [ENDDATE]</p>
				<p><strong>Venue:</strong> [VENUE]</p>
			</div>
		</div>
		<?php if ( 'on' == $wps_etmfw_qr_code_is_enable ) { ?>
			<div class="ticket-qrcode" style="text-align: center;">
			[TICKET]
			</div>
		<?php } elseif ( 'on' == get_option( 'wps_etmfwp_include_barcode' ) ) { ?>
			<div class="ticket-qrcode" style="text-align: center; padding: 20px;">
				<img id="wps_qr_image" style="max-width: 200px; height: auto; width: <?php echo esc_attr( $wps_etmfw_qr_size . 'px' ); ?>; height: <?php echo esc_attr( $wps_etmfw_qr_size . 'px' ); ?>;" src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/barcode.png' ); ?>">
			</div>
		<?php } else { ?>
			<div class="ticket-code wps_etmfw_pdf_text_colour" style="flex: 1; padding: 20px; text-align: center; border: 2px dashed #ddd;">
				<p style="font-size: 24px; margin: 0; padding: 10px;">Ticket Code: [TICKET]</p>
			</div>
		<?php } ?>
		<div class="participant-details" style="padding: 20px; border-top: 2px solid #ddd; text-align: left; overflow-y: auto;">
		[ADDITIONALINFO]
			<!-- Add more participant details here if needed -->
		</div>
		<div class="ticket-footer wps_etmfw_ticket_body" style="padding: 20px;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>; text-align: center; background-color: <?php echo esc_attr( $wps_etmfw_background_color ); ?>; border-top: 2px solid #ddd;">
			<p style="margin: 10px 0; font-size: 14px; color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>!important;">[EMAILBODYCONTENT]</p>
		</div>
	</div>
</body>
</html>
