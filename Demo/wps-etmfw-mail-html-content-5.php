<?php

/**
 * Exit if accessed directly
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/emails/templates
 */

if (!defined('ABSPATH')) {
	exit;
}

if (is_plugin_active('event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php')) {
	$wps_etmfw_background_color = !empty(get_option('wps_etmfw_pdf_background_color')) ? get_option('wps_etmfw_pdf_background_color') : '#006333';
	$wps_etmfw_text_color = !empty(get_option('wps_etmfw_pdf_text_color')) ? get_option('wps_etmfw_pdf_text_color') : '#ffffff';
	$wps_etmfw_header_background_color = !empty(get_option('wps_etmfw_pdf_header_background_color')) ? get_option('wps_etmfw_pdf_header_background_color') : '#81d742';
} else {
	$wps_etmfw_background_color = !empty(get_option('wps_etmfw_ticket_bg_color', '')) ? get_option('wps_etmfw_ticket_bg_color') : '#f5ebeb';
	$wps_etmfw_text_color = !empty(get_option('wps_etmfw_ticket_text_color', '')) ? get_option('wps_etmfw_ticket_text_color', '') : '#f5ebeb';
	$wps_etmfw_header_background_color = !empty(get_option('wps_etmfw_ticket_bg_color')) ? get_option('wps_etmfw_ticket_bg_color') : '#81d742';
}

$wps_etmfw_border_type = !empty(get_option('wps_etmfw_border_type')) ? get_option('wps_etmfw_border_type') : 'none';
$wps_etmfw_border_color = !empty(get_option('wps_etmfw_pdf_border_color')) ? get_option('wps_etmfw_pdf_border_color') : '#000000';
$wps_etmfw_logo_size = !empty(get_option('wps_etmfw_logo_size', true)) ? get_option('wps_etmfw_logo_size', true) : '180';
$wps_etmfw_qr_size = !empty(get_option('wps_etmfw_qr_size')) ? get_option('wps_etmfw_qr_size') : '180';
$wps_etmfw_qr_code_is_enable = !empty(get_option('wps_etmfwp_include_qr')) ? get_option('wps_etmfwp_include_qr') : '';
$wps_etmfw_logo_url = !empty(get_option('wps_etmfw_mail_setting_upload_logo')) ? get_option('wps_etmfw_mail_setting_upload_logo') : '';
$wps_qr_image = esc_url(EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/qr_image.png');

$wps_etmfw_barcode_enable = !empty(get_option( 'wps_etmfwp_include_barcode' )) ? get_option( 'wps_etmfwp_include_barcode' ) : '';

$m = '';
$wps_etmfw_background_image = ! empty( get_option( 'wps_etmfw_background_image' ) ) ? get_option( 'wps_etmfw_background_image' ) : '';
if ( ! empty( $wps_etmfw_background_image ) ) {
	$image_attributes = wp_get_attachment_image_src( $wps_etmfw_background_image, 'thumbnail' );
	$m = $image_attributes[0];
} else {

	$m =  esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/bg-image.png' );
}

?>
<!-- Template Start -->

<table border='0' cellpadding='0' cellspacing='0' role='presentation' style='width: 100%;font-family:Arial, Helvetica, sans-serif;border:2px <?php echo esc_attr( $wps_etmfw_border_type . ' ' . $wps_etmfw_border_color ); ?>' id="wps_new_template_border">
	<tbody>
		<tr>
			<td style="width:70%;">
				<div style='color:#fff;border-radius: 15px;padding: 20px;background-image:url(<?php echo esc_url( $m ); ?>);background-size: 140% 120%;background-position:center center;background-repeat: no-repeat;overflow:hidden;'>
					<div style="background-image: url(<?php echo $wps_etmfw_logo_url; ?>); height: 40px; background-size: contain; background-position: left; background-repeat: no-repeat"></div>
					<div style='color:#fff;font-size:32px;font-weight:bold;margin:10px 0 12px;letter-spacing:0.5px;line-height:1.25;'>Anniversary Gala Concert</div>
					<h3 style='color:#FFC525;font-size:16px;font-weight:normal;margin:0 0 8px;letter-spacing:0.5px;'><strong style='color:#fff;'>From: </strong> September 15, 2024 | 09:00am</h3>
					<h3 style='color:#FFC525;font-size:16px;font-weight:normal;margin:0 0 8px;letter-spacing:0.5px;'><strong style='color:#fff;'>To: </strong> September 16, 2024 | 06:00pm</h3>
					<h3 style='color:#FFC525;font-size:16px;font-weight:normal;margin:0;letter-spacing:0.5px;'><strong style='color:#fff;'>Venue: </strong> Music Areana Auditorium, Los Vegas</h4>
				</div>
			</td>
			<td style="width:30%;">
				<div style='border-radius:0 15px 0 0;text-align:center;padding:20px;background: #fff;'>
					<h4 style='color:#000;font-size: 20px;font-weight:bold;line-height:20px;border-bottom:1px solid #f4f4f4;margin: 0 0 5px;padding:0 0 10px;text-align: center;letter-spacing:0.5px;'><?php echo __('Ticket Code' ,'event-tickets-manager-for-woocommerce'); ?></h4>
					
                    <?php if(('on' == $wps_etmfw_barcode_enable) && (('' == $wps_etmfw_qr_code_is_enable))) {?>
					<!-- BAR CODE START-->
					<div style="background-image: url(<?php echo esc_url(EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/barcode.png'); ?>); height: 40px; background-size: contain; background-position: center center; background-repeat: no-repeat"></div>
					<!-- BAR CODE END -->
                    <?php } ?>

                    <?php if (is_plugin_active('event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php') && ('on' == $wps_etmfw_qr_code_is_enable) && ('' == $wps_etmfw_barcode_enable)) { ?>
					<!-- QR CODE START -->
					<div style="background-image: url(<?php echo $wps_qr_image; ?>); height: 120px; background-size: contain; background-position: center center; background-repeat: no-repeat"></div>
					<!-- QR CODE END -->
                    <?php  } ?>

                    <?php if(('' == $wps_etmfw_barcode_enable) && (('' == $wps_etmfw_qr_code_is_enable))) {?>
					<h4 style='color:#000;font-size: 20px;font-weight:normal;line-height:1;border-top:1px solid #f4f4f4;margin: 5px 0 0;padding:10px 0 0;text-align: center;letter-spacing:0.5px;'>2QWRT500</h4>
                    <?php  } ?>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan='2' style='padding: 20px;background: #fff;'>
				<h4 style='color:#000;font-weight:bold;font-size:18px;margin:0 0 10px;letter-spacing:0.5px;line-height:1;'><?php echo __('Details','event-tickets-manager-for-woocommerce'); ?></h4>
				<p style='color:#000;font-size:14px;margin:0 0 2px;letter-spacing:0.5px;border-bottom:1px solid #FFC525;padding:5px 0;'><span style="margin:0 10px 0 0;"><strong>Name: </strong> Anvi Tiwari</span><span style="margin:0 10px 0 0;"><strong>Mob No.: </strong>+91 8004589657</span><span style="margin:0 10px 0 0;"><strong>Date: </strong>August 24, 2024</span><span style="margin:0 10px 0 0;"><strong>Email ID: </strong>anvi2015@gmail.com</span></p>
			</td>
		</tr>
		<tr>
			<td colspan='2'>
				<div style='border-radius:15px;padding: 20px;background: #cecece4f;'>
					<h4 style='color:#000;font-weight:bold;font-size:18px;margin:0 0 5px;letter-spacing:0.5px;line-height:1;'>Note:</h4>
					<p style='color:#1e1e1e;font-size:14px;letter-spacing:0.5px;line-height:1.5;'>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
				</div>
			</td>
		</tr>
	</tbody>
</table>
<!-- Template End -->