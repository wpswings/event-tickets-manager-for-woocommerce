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

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/includes
 * @author     WP Swings <webmaster@wpswings.com>
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
		self::upgrade_wp_etmfw_postmeta();
		self::upgrade_wp_etmfw_options();
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function upgrade_wp_etmfw_postmeta() {

		$post_meta_keys = array(
			'mwb_etmfw_product_array',
			'mwb_etmfw_order_meta_updated',
			'mwb_etmfw_generated_tickets',
		);

		foreach ( $post_meta_keys as $key => $meta_keys ) {
			$products = get_posts(
				array(
					'numberposts' => -1,
					'post_status' => array( 'publish', 'draft', 'trash' ),
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

				}
			}
		}

		foreach ( $post_meta_keys as $key => $meta_keys ) {
			$products = get_posts(
				array(
					'numberposts' => -1,
					'post_status' => array( 'publish', 'draft', 'trash' ),
					'fields'      => 'ids', // return only ids.
					'meta_key'    => $meta_keys, //phpcs:ignore
					'post_type'   => 'event',
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

				}
			}
		}

		foreach ( $post_meta_keys as $key => $meta_keys ) {
			$products = get_posts(
				array(
					'numberposts' => -1,
					'post_status' => array( 'wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-cancelled', 'wc-refunded', 'wc-failed' ),
					'fields'      => 'ids', // return only ids.
					'meta_key'    => $meta_keys, //phpcs:ignore
					'post_type'   => 'shop_order',
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
	public static function upgrade_wp_etmfw_options() {
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
			'mwb_etmfw_display_duration'         => '',
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

		}
	}


	/**
	 * Function for shortcode migration.
	 *
	 * @return void
	 */
	public static function wpg_etmfw_replace_mwb_to_wps_in_shortcodes() {
		$all_product_ids = get_posts(
			array(
				'post_type' => 'product',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'fields' => 'ids',
			)
		);
		$all_post_ids = get_posts(
			array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'fields' => 'ids',
			)
		);
		$all_page_ids = get_posts(
			array(
				'post_type' => 'page',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'fields' => 'ids',
			)
		);
		$all_ids = array_merge( $all_product_ids, $all_post_ids, $all_page_ids );
		foreach ( $all_ids as $id ) {
			$post = get_post( $id );
			$content = $post->post_content;

			$array = explode( ' ', $content );

			foreach ( $array as $key => $val ) {

				$content = str_replace( 'MWB_', 'WPS_', $content );
				$my_post = array(
					'ID'           => $id,
					'post_content' => $content,
				);
				wp_update_post( $my_post );
			}
		}
	}

}
