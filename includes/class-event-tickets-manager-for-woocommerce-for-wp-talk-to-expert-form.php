<?php
/**
 * Talk to an Expert HubSpot form handling.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'Event_Tickets_Manager_For_Woocommerce_For_Wp_Talk_To_Expert_Form' ) ) {
	return;
}

/**
 * Handle the Talk to an Expert form submission lifecycle.
 */
class Event_Tickets_Manager_For_Woocommerce_For_Wp_Talk_To_Expert_Form {

	/**
	 * Base URL for HubSpot submissions.
	 *
	 * @var string
	 */
	private $etmfw_base_url = 'https://api.hsforms.com/';

	/**
	 * HubSpot portal id.
	 *
	 * @var string
	 */
	private static $etmfw_talk_to_expert_portal_id = '25444144';

	/**
	 * HubSpot form id.
	 *
	 * @var string
	 */
	private static $etmfw_talk_to_expert_form_id = 'eab973a7-5c65-4264-a31d-3b1b10b82c82';

	/**
	 * Handle the growth consultation form submission.
	 *
	 * @return void
	 */
	public function wps_etmfw_submit_talk_to_expert() {
		check_ajax_referer( 'wps_etmfw_growth_lead_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'You are not allowed to submit this request.', 'event-tickets-manager-for-woocommerce' ),
				),
				403
			);
		}

		$raw_payload = isset( $_POST['form_data'] ) ? wp_unslash( $_POST['form_data'] ) : '';
		$form_data   = json_decode( $raw_payload, true );

		if ( empty( $form_data ) || ! is_array( $form_data ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'The request could not be processed. Please try again.', 'event-tickets-manager-for-woocommerce' ),
				),
				400
			);
		}

		$sanitized_data = $this->etmfw_sanitize_growth_lead_form_data( $form_data );

		if ( empty( $sanitized_data['email'] ) || ! is_email( $sanitized_data['email'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Please enter a valid email address.', 'event-tickets-manager-for-woocommerce' ),
				),
				400
			);
		}

		$hubspot_result = $this->etmfw_submit_growth_lead_to_hubspot( $sanitized_data );

		if ( ! $hubspot_result['success'] ) {
			wp_send_json_error(
				array(
					'message' => $hubspot_result['message'],
				),
				500
			);
		}

		wp_send_json_success(
			array(
				'message' => $hubspot_result['message'],
			)
		);
	}

	/**
	 * Sanitize the growth lead form payload.
	 *
	 * @param array $form_data Raw form data.
	 * @return array
	 */
	private function etmfw_sanitize_growth_lead_form_data( $form_data ) {
		$allowed_services = $this->etmfw_get_growth_lead_allowed_services();
		$allowed_budgets  = $this->etmfw_get_growth_lead_allowed_budgets();
		$services         = array();

		if ( ! empty( $form_data['what_services_do_you_need_help_with'] ) ) {
			$services = is_array( $form_data['what_services_do_you_need_help_with'] ) ? $form_data['what_services_do_you_need_help_with'] : array( $form_data['what_services_do_you_need_help_with'] );
			$services = array_values(
				array_intersect(
					array_map( 'sanitize_text_field', $services ),
					$allowed_services
				)
			);
		}

		$budget = isset( $form_data['budget'] ) ? sanitize_text_field( $form_data['budget'] ) : '';
		if ( ! in_array( $budget, $allowed_budgets, true ) ) {
			$budget = '';
		}

		return array(
			'firstname' => isset( $form_data['firstname'] ) ? sanitize_text_field( $form_data['firstname'] ) : '',
			'lastname'  => isset( $form_data['lastname'] ) ? sanitize_text_field( $form_data['lastname'] ) : '',
			'email'     => isset( $form_data['email'] ) ? sanitize_email( $form_data['email'] ) : '',
			'phone'     => isset( $form_data['phone'] ) ? sanitize_text_field( $form_data['phone'] ) : '',
			'what_services_do_you_need_help_with' => $services,
			'budget'    => $budget,
			'message'   => isset( $form_data['message'] ) ? sanitize_textarea_field( $form_data['message'] ) : '',
		);
	}

	/**
	 * Get the allowed growth lead services.
	 *
	 * @return array
	 */
	private function etmfw_get_growth_lead_allowed_services() {
		return array(
			'seo_services',
			'google_ads_setup_and_ga4_setup',
			'speed_optimization',
			'woocommerce_development_services',
		);
	}

	/**
	 * Get the allowed growth lead budgets.
	 *
	 * @return array
	 */
	private function etmfw_get_growth_lead_allowed_budgets() {
		return array(
			'',
			'500-1000',
			'1001-5000',
			'5001-10000',
			'10001-15000',
		);
	}

	/**
	 * Submit the growth lead to HubSpot.
	 *
	 * @param array $sanitized_data Sanitized form values.
	 * @return array
	 */
	private function etmfw_submit_growth_lead_to_hubspot( $sanitized_data ) {
		$request_body = array(
			'fields'  => $this->etmfw_build_growth_lead_fields( $sanitized_data ),
			'context' => array(
				'pageUri'   => $this->etmfw_get_growth_lead_page_uri(),
				'pageName'  => get_bloginfo( 'name' ),
				'ipAddress' => $this->etmfw_get_client_ip(),
			),
		);

		$portal_id = (string) apply_filters( 'wps_etmfw_growth_lead_hubspot_portal_id', self::$etmfw_talk_to_expert_portal_id );
		$form_id   = (string) apply_filters( 'wps_etmfw_growth_lead_hubspot_form_id', self::$etmfw_talk_to_expert_form_id );
		$endpoint  = $this->etmfw_base_url . 'submissions/v3/integration/submit/' . rawurlencode( $portal_id ) . '/' . rawurlencode( $form_id );

		$response = wp_remote_post(
			$endpoint,
			array(
				'method'      => 'POST',
				'timeout'     => 45,
				'blocking'    => true,
				'sslverify'   => false,
				'headers'     => array(
					'Content-Type' => 'application/json',
				),
				'body'        => wp_json_encode( $request_body ),
				'data_format' => 'body',
			)
		);

		if ( is_wp_error( $response ) ) {
			return array(
				'success' => false,
				'message' => __( 'The request could not be sent right now. Please try again shortly.', 'event-tickets-manager-for-woocommerce' ),
			);
		}

		$status_code   = (int) wp_remote_retrieve_response_code( $response );
		$response_body = (string) wp_remote_retrieve_body( $response );

		if ( $status_code >= 200 && $status_code < 300 ) {
			return array(
				'success' => true,
				'message' => $this->etmfw_extract_growth_lead_success_message( $response_body ),
			);
		}

		return array(
			'success' => false,
			'message' => $this->etmfw_extract_growth_lead_error_message( $response_body ),
		);
	}

	/**
	 * Build the HubSpot field array for the growth lead.
	 *
	 * @param array $sanitized_data Sanitized form values.
	 * @return array
	 */
	private function etmfw_build_growth_lead_fields( $sanitized_data ) {
		$fields = array(
			'firstname' => $sanitized_data['firstname'],
			'lastname'  => $sanitized_data['lastname'],
			'email'     => $sanitized_data['email'],
			'phone'     => $sanitized_data['phone'],
			'what_services_do_you_need_help_with' => ! empty( $sanitized_data['what_services_do_you_need_help_with'] ) ? implode( ';', $sanitized_data['what_services_do_you_need_help_with'] ) : '',
			'industry_type_' => $this->etmfw_get_growth_lead_industry_type(),
			'budget'    => $sanitized_data['budget'],
			'message'   => $sanitized_data['message'],
			'currency'  => function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : '',
			'org_plugin_name' => apply_filters( 'wps_etmfw_growth_lead_source_label', 'Grow Your Store with WP Swings' ),
			'company'   => get_bloginfo( 'name' ),
			'website'   => home_url(),
			'country'   => $this->etmfw_get_store_country_name(),
			'annualrevenue' => $this->etmfw_get_last_year_paid_revenue(),
		);

		$prepared_fields = array();
		foreach ( $fields as $name => $value ) {
			if ( '' === $value || null === $value ) {
				continue;
			}

			$prepared_fields[] = array(
				'name'  => $name,
				'value' => $value,
			);
		}

		return $prepared_fields;
	}

	/**
	 * Get the default HubSpot industry type for growth leads.
	 *
	 * @return string
	 */
	private function etmfw_get_growth_lead_industry_type() {
		$allowed_industries = array(
			'agency',
			'consumer-services',
			'ecommerce',
			'financial-services',
			'healthcare',
			'manufacturing',
			'nonprofit-and-education',
			'professional-services',
			'real-estate',
			'software',
			'startups',
			'restaurant',
			'fitness',
			'jewelry',
			'beauty',
			'celebrity',
			'gaming',
			'government',
			'sports',
			'retail-store',
			'travel',
			'political-campaign',
		);

		$industry_type = sanitize_text_field( (string) apply_filters( 'wps_etmfw_growth_lead_industry_type', 'ecommerce' ) );

		if ( ! in_array( $industry_type, $allowed_industries, true ) ) {
			return 'ecommerce';
		}

		return $industry_type;
	}

	/**
	 * Get the current page URL for the growth lead payload.
	 *
	 * @return string
	 */
	private function etmfw_get_growth_lead_page_uri() {
		$referer = wp_get_referer();
		if ( $referer ) {
			return $referer;
		}

		return admin_url( 'admin.php?page=event_tickets_manager_for_woocommerce_menu' );
	}

	/**
	 * Convert the default store country to a country label when possible.
	 *
	 * @return string
	 */
	private function etmfw_get_store_country_name() {
		$default_country = (string) get_option( 'woocommerce_default_country', '' );
		if ( '' === $default_country ) {
			return '';
		}

		$country_parts = explode( ':', $default_country );
		$country_code  = strtoupper( trim( $country_parts[0] ) );

		if ( '' === $country_code ) {
			return '';
		}

		if ( class_exists( 'WC_Countries' ) ) {
			$countries = new WC_Countries();
			if ( ! empty( $countries->countries[ $country_code ] ) ) {
				return $countries->countries[ $country_code ];
			}
		}

		return $country_code;
	}

	/**
	 * Get paid revenue for the last 12 months.
	 *
	 * @return string
	 */
	private function etmfw_get_last_year_paid_revenue() {
		$analytics_total = $this->etmfw_get_last_year_paid_revenue_from_analytics();
		if ( '' !== $analytics_total ) {
			return $analytics_total;
		}

		$paid_statuses = function_exists( 'wc_get_is_paid_statuses' ) ? (array) wc_get_is_paid_statuses() : array();
		if ( empty( $paid_statuses ) || ! function_exists( 'wc_get_orders' ) ) {
			return '';
		}

		$cutoff = gmdate( 'Y-m-d H:i:s', strtotime( '-12 months', current_time( 'timestamp', true ) ) );
		$order_ids = wc_get_orders(
			array(
				'limit'     => -1,
				'return'    => 'ids',
				'status'    => $paid_statuses,
				'date_paid' => '>=' . $cutoff,
			)
		);

		if ( empty( $order_ids ) || ! is_array( $order_ids ) ) {
			return '';
		}

		$total = 0.0;
		foreach ( $order_ids as $order_id ) {
			$order = wc_get_order( $order_id );
			if ( ! $order ) {
				continue;
			}
			$total += (float) $order->get_total();
		}

		return number_format( $total, 2, '.', '' );
	}

	/**
	 * Try the analytics order stats table first for revenue.
	 *
	 * @return string
	 */
	private function etmfw_get_last_year_paid_revenue_from_analytics() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'wc_order_stats';
		$table      = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) );

		if ( $table_name !== $table ) {
			return '';
		}

		$paid_statuses = function_exists( 'wc_get_is_paid_statuses' ) ? (array) wc_get_is_paid_statuses() : array();
		if ( empty( $paid_statuses ) ) {
			return '';
		}

		$prefixed_statuses = array_map(
			static function( $status ) {
				return 0 === strpos( $status, 'wc-' ) ? $status : 'wc-' . $status;
			},
			$paid_statuses
		);

		$placeholders = implode( ',', array_fill( 0, count( $prefixed_statuses ), '%s' ) );
		$cutoff       = gmdate( 'Y-m-d H:i:s', strtotime( '-12 months', current_time( 'timestamp', true ) ) );
		$query_args   = array_merge( $prefixed_statuses, array( $cutoff ) );

		$query = $wpdb->prepare(
			"SELECT SUM(total_sales)
			FROM {$table_name}
			WHERE status IN ($placeholders)
				AND parent_id = 0
				AND date_paid IS NOT NULL
				AND date_paid >= %s",
			$query_args
		);

		$total = $wpdb->get_var( $query );

		if ( null === $total ) {
			return '';
		}

		return number_format( (float) $total, 2, '.', '' );
	}

	/**
	 * Extract a readable success message from HubSpot.
	 *
	 * @param string $response_body Raw response body.
	 * @return string
	 */
	private function etmfw_extract_growth_lead_success_message( $response_body ) {
		$message = '';
		$decoded = json_decode( $response_body, true );

		if ( is_array( $decoded ) ) {
			if ( ! empty( $decoded['inlineMessage'] ) && is_string( $decoded['inlineMessage'] ) ) {
				$message = $decoded['inlineMessage'];
			} elseif ( ! empty( $decoded['message'] ) && is_string( $decoded['message'] ) ) {
				$message = $decoded['message'];
			}
		}

		if ( '' === $message && '' !== $response_body ) {
			$message = $response_body;
		}

		$message = wp_strip_all_tags( html_entity_decode( $message, ENT_QUOTES, 'UTF-8' ) );
		$message = preg_replace( '/[\x{00a0}\s]+/u', ' ', (string) $message );
		$message = trim( (string) $message );

		if ( '' === $message ) {
			$message = __( 'Thank you for submitting your request. Our team will contact you soon.', 'event-tickets-manager-for-woocommerce' );
		}

		return $message;
	}

	/**
	 * Extract a readable error message from HubSpot.
	 *
	 * @param string $response_body Raw response body.
	 * @return string
	 */
	private function etmfw_extract_growth_lead_error_message( $response_body ) {
		$decoded = json_decode( $response_body, true );

		if ( is_array( $decoded ) ) {
			if ( ! empty( $decoded['errors'] ) && is_array( $decoded['errors'] ) ) {
				foreach ( $decoded['errors'] as $error ) {
					if ( is_array( $error ) && ! empty( $error['message'] ) && is_string( $error['message'] ) ) {
						return $error['message'];
					}
					if ( is_string( $error ) && '' !== trim( $error ) ) {
						return trim( $error );
					}
				}
			}

			if ( ! empty( $decoded['message'] ) && is_string( $decoded['message'] ) ) {
				return $decoded['message'];
			}
		}

		return __( 'Something went wrong while submitting the form. Please try again.', 'event-tickets-manager-for-woocommerce' );
	}

	/**
	 * Get the request IP address.
	 *
	 * @return string
	 */
	private function etmfw_get_client_ip() {
		$server_keys = array(
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		);

		foreach ( $server_keys as $server_key ) {
			if ( ! empty( $_SERVER[ $server_key ] ) ) {
				return sanitize_text_field( wp_unslash( $_SERVER[ $server_key ] ) );
			}
		}

		return 'UNKNOWN';
	}
}
