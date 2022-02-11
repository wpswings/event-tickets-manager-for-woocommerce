<?php
/**
 * Fired during plugin activation
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Event_Tickets_Manager_For_Woocommerce_Api_Process' ) ) {

	/**
	 * The plugin API class.
	 *
	 * This is used to define the functions and data manipulation for custom endpoints.
	 *
	 * @since      1.0.0
	 * @package    Event_Tickets_Manager_For_Woocommerce
	 * @subpackage Event_Tickets_Manager_For_Woocommerce/includes
	 * @author     WPSwings <WPSwings.com>
	 */
	class Event_Tickets_Manager_For_Woocommerce_Api_Process {

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {

		}

		/**
		 * Define the function to process data for custom endpoint.
		 *
		 * @since    1.0.0
		 * @param   Array $etmfw_request  data of requesting headers and other information.
		 * @return  Array $wps_etmfw_rest_response    returns processed data and status of operations.
		 */
		public function wps_etmfw_default_process( $etmfw_request ) {
			$wps_etmfw_rest_response = array();

			// Write your custom code here.

			$wps_etmfw_rest_response['status'] = 200;
			$wps_etmfw_rest_response['data'] = $etmfw_request->get_headers();
			return $wps_etmfw_rest_response;
		}
	}
}
