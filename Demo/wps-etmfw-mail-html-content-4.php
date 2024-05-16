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

$wps_etmfw_border_type = ! empty( get_option( 'wps_etmfw_border_type' ) ) ? get_option( 'wps_etmfw_border_type' ) : 'none';
$wps_etmfw_border_color = ! empty( get_option( 'wps_etmfw_pdf_border_color' ) ) ? get_option( 'wps_etmfw_pdf_border_color' ) : '#000000';
$wps_etmfw_logo_size = ! empty( get_option( 'wps_etmfw_logo_size', true ) ) ? get_option( 'wps_etmfw_logo_size', true ) : '180';
$wps_etmfw_qr_size = ! empty( get_option( 'wps_etmfw_qr_size' ) ) ? get_option( 'wps_etmfw_qr_size' ) : '180';
$wps_etmfw_qr_code_is_enable = ! empty( get_option( 'wps_etmfwp_include_qr' ) ) ? get_option( 'wps_etmfwp_include_qr' ) : '';

$wps_etmfw_text_color = ! empty( get_option( 'wps_etmfw_pdf_text_color' ) ) ? get_option( 'wps_etmfw_pdf_text_color' ) : '#ffffff';
$wps_etmfw_background_color = ! empty( get_option( 'wps_etmfw_pdf_background_color' ) ) ? get_option( 'wps_etmfw_pdf_background_color' ) : '#FFE6EA';
$wps_etmfw_header_background_color = ! empty( get_option( 'wps_etmfw_pdf_header_background_color' ) ) ? get_option( 'wps_etmfw_pdf_header_background_color' ) : '#81d742';
$wps_etmfw_logo_url = ! empty( get_option( 'wps_etmfw_mail_setting_upload_logo' ) ) ? get_option( 'wps_etmfw_mail_setting_upload_logo' ) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .ticket-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .ticket-header {
            background-color: #4CAF50;
            /* color: #fff; */
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #388E3C;
        }

        .ticket-header h1 {
            margin: 0;
            font-size: 28px;
        }

        .ticket-info {
            padding: 20px;
            text-align: left;
            display: flex;
            align-items: center;
        }

        .ticket-info p {
            margin: 10px 0;
            font-size: 18px;
            /* color: #333; */
        }

        .logo {
            width: 120px;
            height: auto;
            margin-right: 20px;
        }

        .ticket-qrcode {
            text-align: center;
            padding: 20px;
        }

        .ticket-qrcode img {
            max-width: 200px;
            height: auto;
        }

        .participant-details {
            padding: 20px;
            border-top: 2px solid #ddd;
            text-align: left;
            overflow-y: auto;
        }

        .participant-details p {
            margin: 10px 0;
            font-size: 14px;
            /* color: #666; */
        }

        .participant-details p strong {
            margin-right: 5px;
        }

        .ticket-footer {
            padding: 20px;
            text-align: center;
            background-color: #f4f4f4;
            border-top: 2px solid #ddd;
        }

        .ticket-footer p {
            margin: 10px 0;
            font-size: 14px;
            /* color: #666; */
        }
        .ticket-code {
    flex: 1;
    padding: 20px;
    text-align: center;
    /* background-color: #f4f4f4; */
    border: 2px dashed #ddd;
}

.ticket-code p {
    font-size: 24px;
    /* color: #333; */
    margin: 0;
    padding: 10px;
}

    </style>
</head>
<body>
    <div class="ticket-container wps_etmfw_border_color wps_etmfw_ticket_body wps_etmfw_pdf_text_colour" style="background-color:  <?php echo esc_attr( $wps_etmfw_background_color ); ?>;border:2px <?php echo esc_attr( $wps_etmfw_border_type . ' ' . $wps_etmfw_border_color ); ?>;color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;" id = "wps_event_border_type">
        <div class="ticket-header" style="background-color:  <?php echo esc_attr( $wps_etmfw_header_background_color ); ?>;">  <!-- Add different color section -->
            <h1 class="wps_etmfw_pdf_text_colour" style="color: <?php echo esc_attr( $wps_etmfw_text_color ); ?>;">Event Ticket</h1>
        </div>
        <div class="ticket-info">
            <img class="logo" id="wps_wem_logo_id" style="width:<?php echo esc_attr( $wps_etmfw_logo_size . 'px' ); ?>;" src= <?php echo $wps_etmfw_logo_url; ?> alt="Event Logo">
            <div>
                <p><strong>Event Name:</strong> Lorem Ipsum Event</p>
                <p><strong>Date:</strong> May 15, 2024</p>
                <p><strong>Time:</strong> 7:00 PM - 10:00 PM</p>
                <p><strong>Venue:</strong> Lorem Ipsum Hall</p>
            </div>
        </div>
        <?php  if ( 'on' == $wps_etmfw_qr_code_is_enable ) { ?>
        <div class="ticket-qrcode">
            <img id="wps_qr_image" style="width:<?php echo esc_attr( $wps_etmfw_qr_size . 'px' ); ?>;height:<?php echo esc_attr( $wps_etmfw_qr_size . 'px' ); ?>" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=EventTicketData">
        </div>
        <?php } elseif ( 'on' == get_option( 'wps_etmfwp_include_barcode' ) ) { ?>
            <div class="ticket-qrcode">
            <img id="wps_qr_image" style="width:<?php echo esc_attr( $wps_etmfw_qr_size . 'px' ); ?>;height:<?php echo esc_attr( $wps_etmfw_qr_size . 'px' ); ?>" src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/resources/offer-templates/barcode.png' ); ?>">
        </div>
        <?php } else { ?>
        <div class="ticket-code wps_etmfw_pdf_text_colour">
            <p>Ticket Code: ABC123</p>
        </div>

        <?php  }  ?>
        <div class="participant-details">
            <p><strong>Name:</strong> John Doe</p>
            <p><strong>Email:</strong> johndoe@example.com</p>
            <p><strong>Phone:</strong> +1 (123) 456-7890</p>
            <p><strong>Address:</strong> 123 Main Street, City, Country</p>
            <!-- Add more participant details here if needed -->
        </div>
        <div class="ticket-footer wps_etmfw_ticket_body" style="background-color:  <?php echo esc_attr( $wps_etmfw_background_color ); ?>;">
            <p>This ticket is valid for one entry only. Please keep it safe.</p>
        </div>
    </div>
</body>
</html>
