<?php
/**
 * Registering custom product type.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/includes
 */

	/**
	 * Set the giftcard product type.
	 *
	 * @since 1.0.0
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://wpswings.com/
	 */
class WC_Product_Event_Ticket_Manager extends WC_Product {

	/**
	 * Initialize simple product.
	 *
	 * @param mixed $product product.
	 */
	public function __construct( $product ) {
		$this->product_type = 'event_ticket_manager';
		parent::__construct( $product );
	}
}
