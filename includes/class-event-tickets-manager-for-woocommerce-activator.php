<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Event_Tickets_Manager_For_Woocommerce_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function event_tickets_manager_for_woocommerce_activate() {
		self::upgrade_wp_postmeta();
		self::upgrade_wp_options();
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function upgrade_wp_postmeta() {

		$post_meta_keys = array(
			'mwb_etmfw_product_array',
			'mwb_etmfw_order_meta_updated',
			'mwb_etmfw_generated_tickets',
		);

		foreach ( $post_meta_keys as $key => $meta_keys ) {
			$products = get_posts(
				array(
					'numberposts' => -1,
					'post_status' => 'publish',
					'fields'      => 'ids', // return only ids.
					'meta_key'    => $meta_keys, //phpcs:ignore
					'post_type'   => 'product',
					'order'       => 'ASC',
				)
			);

			if ( ! empty( $products ) && is_array( $products ) ) {
				foreach ( $products as $k => $product_id ) {
					$value   = get_post_meta( $product_id, $meta_keys, true );
					$new_key = str_replace( 'mwb_', 'wps_', $meta_keys );

					if ( ! empty( get_post_meta( $product_id, $new_key, true ) ) ) {
						continue;
					}

					update_post_meta( $product_id, $new_key, $value );
					// delete_post_meta( $product_id, $new_key );
				}
			}
		}
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function upgrade_wp_options() {
		$wp_options = array(
			'mwb_all_plugins_active'             => '',
			'mwb_etmfw_email_body_content'       => '',
			'mwb_etmfw_email_subject'            => '',
			'mwb_etmfw_enabe_location_site'      => '',
			'mwb_etmfw_enable_plugin'            => '',
			'mwb_etmfw_google_maps_api_key'      => '',
			'mwb_etmfw_mail_setting_upload_logo' => '',
			'mwb_etmfw_onboarding_data_skipped'  => '',
			'mwb_etmfw_onboarding_data_sent'     => '',
			'mwb_etmfw_display_duration'		 => '',
			'mwb_etmfw_event_view'               => '',
			'mwb_etmfw_google_client_id'         => '',
			'mwb_etmfw_google_client_secret'     => '',
			'mwb_etmfw_google_redirect_url'      => '',
			'mwb_etmfw_radio_switch_demo'        => '',



			
		);

		foreach ( $wp_options as $key => $value ) {
			$new_key = str_replace( 'mwb_', 'wps_', $key );

			if ( ! empty( get_option( $new_key ) ) ) {
				continue;
			}

			$new_value = get_option( $key, $value );
			update_option( $new_key, $new_value );
			// delete_post_meta( $key );
		}
	}

}