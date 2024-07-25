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
	 *
	 * This variable stores the type of the product. In this case, it represents an event ticket manager.
	 *
	 * @var string
	 */
	public $product_type = 'event_ticket_manager';

	/**
	 * Initialize simple product.
	 *
	 * @param mixed $product product.
	 */
	public function __construct( $product ) {
		parent::__construct( $product );
	}
}
