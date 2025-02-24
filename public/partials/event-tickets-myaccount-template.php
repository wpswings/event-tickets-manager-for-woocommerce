<?php
/**
 * Provide a public area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/public/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
</div>
    <div class="wps_etmfw_input_ticket_section">
        <label> <?php esc_html_e( 'Ticket Number *', 'event-tickets-manager-for-woocommerce' ); ?> </label>
        <input type="text" name="wps_etmfw_imput_ticket" id="wps_etmfw_imput_ticket">
    </div>
    <div class="wps_etmfw_input_ticket_section">
        <label> <?php esc_html_e( 'Enter Email *', 'event-tickets-manager-for-woocommerce' ); ?> </label>
        <input type="email" name="wps_etmfw_chckin_email" id="wps_etmfw_chckin_email">
    </div>


    <div class="wps_etmfw--loader-btn-wrapper">
        <div class="wps_etmfw_checkin_button_section">
            <input type="submit" name="wps_etmfw_checkin_button" id="wps_etmfwp_event_transfer_button" value="<?php esc_html_e( 'Transfer', 'event-tickets-manager-for-woocommerce' ); ?> ">
        </div>
        <div class="wps_etmfw_loader" id="wps_etmfw_checkin_loader">
            <img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/loading.gif' ); // phpcs:ignore. ?>">
        </div>
    </div>
    </form>
</div>
