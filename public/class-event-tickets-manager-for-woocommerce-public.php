<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/public
 */

use Dompdf\Dompdf;
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * namespace event_tickets_manager_for_woocommerce_public.
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/public
 * @author     WPSwings <webmaster@wpswings.com>
 */
class Event_Tickets_Manager_For_Woocommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function etmfw_public_enqueue_styles() {
		$event_view = get_option( 'wps_etmfw_event_view', 'list' );
		if ( 'calendar' === $event_view ) {
			wp_enqueue_style( 'wps-etmfw-fullcalendar-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/fullcalendar/main.min.css', array(), time(), 'all' );
		}

		wp_enqueue_style( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/scss/event-tickets-manager-for-woocommerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function etmfw_public_enqueue_scripts() {
		$event_view = get_option( 'wps_etmfw_event_view', 'list' );
		if ( 'calendar' === $event_view ) {
			wp_enqueue_script( 'wps-etmfw-fullcalendar-js', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/fullcalendar/fullcalendar.min.js', array( 'jquery' ), $this->version, false );
			wp_register_script( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/js/event-tickets-manager-for-woocommerce-public.js', array( 'jquery', 'wps-etmfw-fullcalendar-js' ), $this->version, false );
		} else {
			wp_register_script( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/js/event-tickets-manager-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
		}
		$public_param_data = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'event_view' => $event_view,
			'wps_etmfw_public_nonce' => wp_create_nonce( 'wps-etmfw-verify-public-nonce' ),
			'is_required' => __( ' Is Required', 'event-tickets-manager-for-woocommerce' ),
		);
		wp_localize_script( $this->plugin_name, 'etmfw_public_param', $public_param_data );
		wp_enqueue_script( $this->plugin_name );

		if ( is_product() ) {
			$wps_etmfw_product_type = $this->wps_etmfw_get_product_type();
			$wps_etmfw_if_expired   = $this->wps_etmfw_check_if_event_is_expired();
			$wps_etmfw_show_map     = $this->wps_etmfw_show_google_map_on_product_page();
			if ( 'event_ticket_manager' === $wps_etmfw_product_type && ! $wps_etmfw_if_expired && $wps_etmfw_show_map ) {
				$wps_google_api_key = get_option( 'wps_etmfw_google_maps_api_key', '' );
				wp_register_script( 'wps_etmfw_google_map', 'https://maps.googleapis.com/maps/api/js?&key=' . $wps_google_api_key . '&callback=initMap&libraries=&v=weekly', array(), $this->version, true );
				wp_enqueue_script( 'wps_etmfw_google_map' );
			}
		}

		global $wp_query;
		$checkin_page_id = get_option( 'event_checkin_page_created', '' );
		if ( '' !== $checkin_page_id ) {
			if ( isset( $wp_query->post ) && $wp_query->post->ID == $checkin_page_id ) {
				wp_register_script( $this->plugin_name . '-checkin-page', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/js/event-tickets-manager-for-woocommerce-checkin-page.js', array( 'jquery' ), $this->version, false );
				$param_data = array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wps_etmfw_nonce' => wp_create_nonce( 'wps-etmfw-verify-checkin-nonce' ),
					'wps_etmfw_require_text' => __( 'Please Fill All the Required (*) Fields', 'event-tickets-manager-for-woocommerce' ),
					'wps_etmfw_email_text'   => __( 'Please enter correct email', 'event-tickets-manager-for-woocommerce' ),

				);
				wp_localize_script( $this->plugin_name . '-checkin-page', 'etmfw_checkin_param', $param_data );
				wp_enqueue_script( $this->plugin_name . '-checkin-page' );
			}
		}

		wp_enqueue_script( $this->plugin_name . 'public-org-custom-js', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/js/event-tickets-manager-for-woocommerce-org-custom-public.js', array( 'jquery', 'jquery-ui-sortable' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name . 'public-org-custom-js',
			'etmfw_org_custom_param_public',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wps_wet_custom_ajax_nonce' ),
				'wps_etmfw_public_nonce' => wp_create_nonce( 'wps-etmfw-verify-public-nonce' ),
			)
		);
	}

	/**
	 * Create product html before add to cart button.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_before_add_to_cart_button_html()
	 * @param object $event_product  Event Project Object.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_before_add_to_cart_button_html( $event_product ) {
		global $product;
		if ( isset( $product ) && ! empty( $product ) ) {
			$wps_etmfw_enable = get_option( 'wps_etmfw_enable_plugin', false );
			if ( $wps_etmfw_enable ) {
				$product_id = $product->get_id();
				if ( isset( $product_id ) && ! empty( $product_id ) ) {
					$product_types = wp_get_object_terms( $product_id, 'product_type' );
					$product_type = $product_types[0]->slug;
					if ( 'event_ticket_manager' === $product_type ) {
						$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
						$start_date = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
						$end_date = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
						$event_venue = isset( $wps_etmfw_product_array['etmfw_event_venue'] ) ? $wps_etmfw_product_array['etmfw_event_venue'] : '';
						$event_field_array = isset( $wps_etmfw_product_array['wps_etmfw_field_data'] ) ? $wps_etmfw_product_array['wps_etmfw_field_data'] : '';
						require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'templates/frontend/event-tickets-manager-for-woocommerce-before-atc-html.php';
					}
				}
			}
		}
	}

	/**
	 * Create additional fiields to get user basic information before add to cart button.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_generate_addional_fields()
	 * @param int   $product_id  Project Id.
	 * @param array $event_field_array  Html Field Array.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_generate_addional_fields( $product_id, $event_field_array ) {
		if ( is_array( $event_field_array ) && ! empty( $event_field_array ) ) {
			foreach ( $event_field_array as $key => $value ) {
				$field_label = strtolower( str_replace( ' ', '_', $value['label'] ) );
				if ( array_key_exists( 'required', $value ) && 'on' === $value['required'] ) {
					$required = 'required="required"';
					$mandatory = true;
				} else {
					$mandatory = false;
					$required = '';
				}
				require EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'templates/frontend/event-tickets-manager-for-woocommerce-additional-field-html.php';

			}
		}
	}

	/**
	 * Add only single product to cart.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_allow_single_quantity()
	 * @param boolean $allow_qty default false.
	 * @param object  $product Product Object.
	 * @return boolean $allow_qty default true.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_allow_single_quantity( $allow_qty, $product ) {

		if ( $product->is_type( 'event_ticket_manager' ) ) {
			$active_plugins = (array) get_option( 'active_plugins', array() );

			if ( ! empty( $active_plugins ) ) {

				if ( ! in_array( 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php', $active_plugins ) ) {

					$allow_qty = true;
				} else {
					$allow_qty = false;
				}
			}
		}
		return apply_filters( 'wps_etmfw_increase_event_product_quantity', $allow_qty, $product );
	}

	/**
	 * Add item data to cart.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_cart_item_data()
	 * @param array $the_cart_data Hold cart content.
	 * @param int   $product_id Product Id.
	 * @param int   $variation_id Variation Id.
	 * @return array $the_cart_data Holds cart content.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_cart_item_data( $the_cart_data, $product_id, $variation_id ) {

		if ( ! isset( $_POST['wps_etwmfw_atc_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wps_etwmfw_atc_nonce'] ) ), 'wps_etwmfw_atc_nonce' ) ) {
			return;
		}
		$wps_etmfw_enable = get_option( 'wps_etmfw_enable_plugin', false );
		if ( $wps_etmfw_enable ) {
			$product_types = wp_get_object_terms( $product_id, 'product_type' );
			if ( isset( $product_types[0] ) ) {
				$product_type = $product_types[0]->slug;
				if ( 'event_ticket_manager' == $product_type ) {

					$cart_values = ! empty( $_POST ) ? map_deep( wp_unslash( $_POST ), 'sanitize_text_field' ) : array();
					foreach ( $cart_values as $key => $value ) {
						if ( false !== strpos( $key, 'wps_etmfw_' ) && 'wps_etmfw_single_nonce_field' !== $key ) {
							if ( isset( $key ) && ! empty( $value ) ) {
								$item_meta['wps_etmfw_field_info'][ $key ] = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
							}
						}
					}
					$item_meta = apply_filters( 'wps_etmfw_add_cart_item_data', $item_meta, $the_cart_data, $product_id, $variation_id );
					$the_cart_data['product_meta'] = array( 'meta_data' => $item_meta );
				}
			}
		}
		return $the_cart_data;

	}

	/**
	 * Add item data to cart.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_cart_item_data()
	 * @param array $item_meta holds cart item meta data.
	 * @param array $existing_item_meta Existing Item Meta.
	 * @return array $item_meta holds updated cart item meta data values.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_get_cart_item_data( $item_meta, $existing_item_meta ) {
		$wps_etmfw_enable = get_option( 'wps_etmfw_enable_plugin', false );
		if ( $wps_etmfw_enable ) {
			if ( isset( $existing_item_meta ['product_meta']['meta_data'] ) ) {
				foreach ( $existing_item_meta['product_meta'] ['meta_data'] as $key => $val ) {
					if ( 'wps_etmfw_field_info' == $key ) {
						if ( ! empty( $val ) ) {
							$info_array = $this->wps_etmfw_generate_key_value_pair( $val );
							foreach ( $info_array as $info_key => $info_value ) {
								$item_meta [] = array(
									'name'  => esc_html( $info_key ),
									'value' => stripslashes( $info_value ),
								);
							}
						}
					}
					$item_meta = apply_filters( 'wps_etmfw_get_item_meta', $item_meta, $key, $val );
				}
			}
		}
		return $item_meta;
	}

	/**
	 * Generate key value pair.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_generate_key_value_pair()
	 * @param array $field_post User Additional Info Values.
	 * @return array $field_post User Additional Info Values.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_generate_key_value_pair( $field_post ) {
		$field_array = array();
		$label = '';
		$discard_keys = array( 'wps_etmfw_event_start', 'wps_etmfw_event_finish', 'wps_etmfw_event_venue' );
		foreach ( $field_post as $key => $value ) {
			if ( strpos( $key, 'wps_etmfw_' ) !== false && ! in_array( $key, $discard_keys ) ) {
				$key = ucwords( str_replace( '_', ' ', substr( $key, 10 ) ) );
				$field_array[ $key ] = $value;
			}
		}
		return $field_array;
	}

	/**
	 * Add meta data to order.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_create_order_line_item().
	 * @param object $item Order Item.
	 * @param string $cart_key cart unique key.
	 * @param array  $values cart values.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_create_order_line_item( $item, $cart_key, $values ) {
		$wps_etmfw_enable = get_option( 'wps_etmfw_enable_plugin', false );
		if ( $wps_etmfw_enable ) {
			if ( isset( $values ['product_meta'] ) ) {
				foreach ( $values ['product_meta'] ['meta_data'] as $key => $val ) {
					if ( 'wps_etmfw_field_info' == $key && $val ) {
						$info_array = $this->wps_etmfw_generate_key_value_pair( $val );
						foreach ( $info_array as $info_key => $info_value ) {
							$item->add_meta_data( $info_key, $info_value );
						}
						do_action( 'wps_etmfw_checkout_create_order_line_item', $item, $key, $val );
					}
				}
			}
		}
	}

	/**
	 * Create event order on order status change.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_event_status_changed()
	 * @param string $order_id Order Id.
	 * @param string $old_status Old Status.
	 * @param string $new_status New Status.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_event_status_changed( $order_id, $old_status, $new_status ) {
		$wps_etmfw_enable = get_option( 'wps_etmfw_enable_plugin', false );
		$wps_etmfw_in_processing = get_option( 'wps_wet_enable_after_payment_done_ticket', false );
		$temp_status = 'completed';
		if ( 'on' == $wps_etmfw_in_processing ) {
			$temp_status = 'processing';
		}
		if ( $wps_etmfw_enable ) {
			if ( $old_status != $new_status ) {
				if ( 'completed' == $new_status || 'processing' == $new_status ) {     // Order Status For Creating Event Ticket.
					$this->wps_etmfw_process_event_order( $order_id, $old_status, $new_status );
				}
			}
		}
	}


	/**
	 * Process order on order status change.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_process_event_order()
	 * @param string $order_id Order Id.
	 * @param string $old_status Old Status.
	 * @param string $new_status New Status.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_process_event_order( $order_id, $old_status, $new_status ) {
		$order = wc_get_order( $order_id );
		$billing_email = $order->get_billing_email();
		$wps_etmfw_mail_template_data = array();
		foreach ( $order->get_items() as $item_id => $item ) {
			$product = $item->get_product();
			if ( isset( $product ) && $product->is_type( 'event_ticket_manager' ) ) {
				$item_quantity = wc_get_order_item_meta( $item_id, '_qty', true );

				$product_id = $product->get_id();
				$item_meta_data = $item->get_meta_data();
				$wps_etmfw_mail_template_data = array(
					'product_id' => $product_id,
					'item_id'   => $item_id,
					'order_id'   => $order_id,
					'product_name' => $product->get_name(),
				);
				foreach ( $item_meta_data as $key => $value ) {
					if ( isset( $value->key ) && ! empty( $value->value ) ) {
						$wps_etmfw_mail_template_data[ $value->key ] = $value->value;
					}
				}

				if ( 1 < $item_quantity ) {
					$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
					if ( empty( $ticket_number ) ) {
						$ticket_number = array();

						for ( $i = 0; $i < $item_quantity; $i++ ) {
							$temp = wps_etmfw_ticket_generator();
							$ticket_number[ $i ] = $temp;
							$wps_ticket_content = $this->wps_etmfw_get_html_content( $item_meta_data, $order, $order_id, $temp, $product_id );
							$this->wps_etmfw_generate_ticket_pdf( $wps_ticket_content, $order, $order_id, $temp );
						}
						update_post_meta( $order_id, "event_ticket#$order_id#$item_id", $ticket_number );
					}

					if ( is_array( $ticket_number ) && ! empty( $ticket_number ) ) {
						$wps_etmfw_mail_template_data['ticket_number'] = $ticket_number;
						$generated_tickets = get_post_meta( $product_id, 'wps_etmfw_generated_tickets', true );
						if ( empty( $generated_tickets ) ) {
							$generated_tickets = array();

							foreach ( $ticket_number as $key => $value ) {

								$generated_tickets[] = array(
									'ticket' => $value,
									'status' => 'pending',
									'order_id' => $order_id,
									'item_id' => $item_id,
									'email'   => $billing_email,
									'user'    => get_current_user_id(),
								);
							}
							update_post_meta( $product_id, 'wps_etmfw_generated_tickets', $generated_tickets );
						} else {

							foreach ( $ticket_number as $key => $value ) {

								$generated_tickets[] = array(
									'ticket' => $value,
									'status' => 'pending',
									'order_id' => $order_id,
									'item_id' => $item_id,
									'email'   => $billing_email,
									'user'    => get_current_user_id(),
								);
							}
							update_post_meta( $product_id, 'wps_etmfw_generated_tickets', $generated_tickets );
						}
					}
				} else {
					$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
					if ( '' === $ticket_number ) {
						$ticket_number = wps_etmfw_ticket_generator();
						update_post_meta( $order_id, "event_ticket#$order_id#$item_id", $ticket_number );
						$wps_ticket_content = $this->wps_etmfw_get_html_content( $item_meta_data, $order, $order_id, $ticket_number, $product_id );// tickt pdf html.
						$this->wps_etmfw_generate_ticket_pdf( $wps_ticket_content, $order, $order_id, $ticket_number );

						if ( isset( $ticket_number ) ) {
							$wps_etmfw_mail_template_data['ticket_number'] = $ticket_number;
							$generated_tickets = get_post_meta( $product_id, 'wps_etmfw_generated_tickets', true );
							if ( empty( $generated_tickets ) ) {
								$generated_tickets = array();
								$generated_tickets[] = array(
									'ticket' => $ticket_number,
									'status' => 'pending',
									'order_id' => $order_id,
									'item_id' => $item_id,
									'email'   => $billing_email,
									'user'    => get_current_user_id(),
								);
								update_post_meta( $product_id, 'wps_etmfw_generated_tickets', $generated_tickets );
							} else {
								$generated_tickets[] = array(
									'ticket' => $ticket_number,
									'status' => 'pending',
									'order_id' => $order_id,
									'item_id' => $item_id,
									'email'   => $billing_email,
									'user' => get_current_user_id(),
								);
								update_post_meta( $product_id, 'wps_etmfw_generated_tickets', $generated_tickets );
							}
						}
					}
				}

				$wps_etmfw_mail_template_data = apply_filters( 'wps_etmfw_common_arr_data', $wps_etmfw_mail_template_data, $item );
				$this->wps_etmfw_send_ticket_mail( $order, $wps_etmfw_mail_template_data );
			}
		}
		do_action( 'wps_etmfw_action_on_order_status_changed', $order_id, $old_status, $new_status );
	}

	/**
	 * Send Ticket Mail when event order is placed.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_send_ticket_mail()
	 * @param object $order Order.
	 * @param array  $wps_etmfw_mail_template_data Mail Template data.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_send_ticket_mail( $order, $wps_etmfw_mail_template_data ) {

		$user_email = $order->get_billing_email();
		$mailer_obj = WC()->mailer()->emails['wps_etmfw_email_notification'];
		$wps_etmfw_email_discription = $this->wps_etmfw_generate_ticket_info_in_mail( $wps_etmfw_mail_template_data );
		$wps_etmfw_email_subject = get_option( 'wps_etmfw_email_subject', '' );
		if ( '' === $wps_etmfw_email_subject ) {
			$wps_etmfw_email_subject = 'Your ticket is here.';
		}
		$wps_etmfw_email_subject = str_replace( '[SITENAME]', get_bloginfo(), $wps_etmfw_email_subject );
		$email_status = $mailer_obj->trigger( $user_email, $wps_etmfw_email_discription, $wps_etmfw_email_subject, $order );
		do_action( 'wps_etmfw_send_sms_ticket', $wps_etmfw_mail_template_data );
	}

	/**
	 * Send Ticket Mail when event  is shared.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_send_ticket_mail()
	 * @param object $order Order.
	 * @param array  $wps_etmfw_mail_template_data Mail Template data.
	 * @param string $user_email Mail of receiver.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_send_ticket_mail_shared( $order, $wps_etmfw_mail_template_data, $user_email ) {

		$mailer_obj = WC()->mailer()->emails['wps_etmfw_email_notification'];
		$wps_etmfw_email_discription = $this->wps_etmfw_generate_ticket_info_in_mail( $wps_etmfw_mail_template_data );
		$wps_etmfw_email_subject = get_option( 'wps_etmfw_email_subject', '' );
		if ( '' === $wps_etmfw_email_subject ) {
			$wps_etmfw_email_subject = 'Your ticket is here.';
		}
		$wps_etmfw_email_subject = str_replace( '[SITENAME]', get_bloginfo(), $wps_etmfw_email_subject );
		$email_status = $mailer_obj->trigger( $user_email, $wps_etmfw_email_discription, $wps_etmfw_email_subject, $order );
		do_action( 'wps_etmfw_send_sms_ticket', $wps_etmfw_mail_template_data );
	}

	/**
	 * Send Ticket Mail when event order is placed.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_generate_ticket_info_in_mail()
	 * @param array $wps_etmfw_mail_template_data Mail Template Data.
	 * @return array $template_html Template Html.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_generate_ticket_info_in_mail( $wps_etmfw_mail_template_data ) {
		$product_id = null;
		if ( ! empty( $wps_etmfw_mail_template_data ) ) {
			$order = wc_get_order( $wps_etmfw_mail_template_data['order_id'] );
			$product_id = $wps_etmfw_mail_template_data['product_id'];
			$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
			$start = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
			$end = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
			$venue = isset( $wps_etmfw_product_array['etmfw_event_venue'] ) ? $wps_etmfw_product_array['etmfw_event_venue'] : '';
			$product = wc_get_product( $product_id );
			$image = wp_get_attachment_image_url( $product->get_image_id() );
			if ( '' == $image ) {
				$image = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/placeholder.png';
			}
		}
		$template_html = array();
		if ( key_exists( 'product_name', $wps_etmfw_mail_template_data ) ) {

			$template_html['event'] = $wps_etmfw_mail_template_data['product_name'];
		}
		if ( key_exists( 'product_name', $wps_etmfw_mail_template_data ) ) {
			$template_html['ticket'] = $wps_etmfw_mail_template_data['ticket_number'];
		}
		if ( ! empty( $order ) ) {

			$template_html['purchaser'] = ! empty( $order->get_billing_first_name() ) ? $order->get_billing_first_name() : '';
		}
		$template_html['email_body'] = get_option( 'wps_etmfw_email_body_content', '' );
		$template_html['venue'] = $venue;
		$template_html['time'] = wps_etmfw_get_date_format( $start ) . '-' . wps_etmfw_get_date_format( $end );
		// Inline style used for sending in email.
		$template_html['featuredimage'] = '<img src="' . $image . '" style="margin-right: 20px; " alt="image" />';
		return apply_filters( 'wps_etmfw_ticket_info', $template_html, $product_id );

	}

	/**
	 * Generate Pdf attachment for mail.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_attach_pdf_to_emails()
	 * @param array  $attachments Mail Attachments.
	 * @param string $email_id receiver's mail id.
	 * @param object $order Order Object.
	 * @param string $email email.
	 * @return array $attachments email attachments.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_attach_pdf_to_emails( $attachments, $email_id, $order, $email ) {

		if ( ! isset( $_SESSION ) ) {
			session_start();
		}
		$wps_etmfw_in_processing = get_option( 'wps_wet_enable_after_payment_done_ticket', false );
		if ( 'wps_etmfw_email_notification' == $email_id ) {
			if ( is_a( $order, 'WC_Order' ) ) {
				$order_status  = $order->get_status();
				$temp_status = 'completed';
				if ( 'on' == $wps_etmfw_in_processing ) {
					$temp_status = 'processing';
				}
				$wps_check_point = isset( $_SESSION['wps_Check_point'] ) ? $_SESSION['wps_Check_point'] : 0;
				if ( ( 'completed' === $order_status || 'processing' === $order_status ) && ( 0 == $wps_check_point || 1 != $wps_check_point ) ) { // Order Status For Creating Event Ticket.
					$order_id = $order->get_id();
					$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';
					foreach ( $order->get_items() as $item_id => $item ) {
						$product = $item->get_product();
						if ( isset( $product ) && $product->is_type( 'event_ticket_manager' ) ) {
							$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
							if ( is_array( $ticket_number ) && ! empty( $ticket_number ) ) {

								foreach ( $ticket_number as $key => $value ) {

									$generated_ticket_pdf = $upload_dir_path . '/events' . $order_id . $value . '.pdf';
									$attachments[] = $generated_ticket_pdf;
								}
							} else {

								$generated_ticket_pdf = $upload_dir_path . '/events' . $order_id . $ticket_number . '.pdf';
								$attachments[] = $generated_ticket_pdf;

							}
						}
					}
				} else {
					$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';
					$generated_ticket_pdf = $upload_dir_path . '/events' . $_SESSION['order_id'] . $_SESSION['ticket_no'] . '.pdf';
					$attachments[] = $generated_ticket_pdf;
				}
			}
		}

		return $attachments;
		// Finally, destroy the session.
		unset( $_SESSION['order_id'] );
		unset( $_SESSION['ticket_no'] );
		unset( $_SESSION['wps_Check_point'] );
	}


	/**
	 * Generate html content for ticket pdf.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_get_html_content()
	 * @param array  $item_meta_data Item meta data.
	 * @param object $order Order.
	 * @param int    $order_id Order Id.
	 * @param string $ticket_number Ticket Number.
	 * @param int    $product_id Product Id.
	 * @return string $wps_ticket_details Ticket Details.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_get_html_content( $item_meta_data, $order, $order_id, $ticket_number, $product_id ) {
		ob_start();
		$wps_is_qr_is_enable = false;
		$wps_set_the_pdf_ticket_template = get_option( 'wps_etmfw_ticket_template', '1' );

		if ( '1' == $wps_set_the_pdf_ticket_template ) {
			include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/wps-etmfw-mail-html-content.php';// Zenith.
		}

		$file = apply_filters( 'wps_etmfw_generate_qr_code', $order_id, $ticket_number, $product_id );
		$ticket_number1 = '';
		$wps_etmfw_qr_size = ! empty( get_option( 'wps_etmfw_qr_size' ) ) ? get_option( 'wps_etmfw_qr_size' ) : '180';

		if ( ! empty( $file ) ) {

			if ( 'string' == gettype( $file ) ) {
				$ticket_number1 = $ticket_number;
				$wps_is_qr_is_enable = true;
				$ticket_number = '<img src="' . get_site_url() . '/' . str_replace( ABSPATH, '', $file ) . '" alt= "QR" height="' . $wps_etmfw_qr_size . '" width="' . $wps_etmfw_qr_size . '"  />';
				if ( '1' == $wps_set_the_pdf_ticket_template ) {
					$ticket_number = '<img src="' . get_site_url() . '/' . str_replace( ABSPATH, '', $file ) . '" alt= "QR" height="' . $wps_etmfw_qr_size . '" width="' . $wps_etmfw_qr_size . '"  />';
				}
			}
		}

		if ( '2' == $wps_set_the_pdf_ticket_template ) {
			include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/wps-etmfw-mail-html-content-1.php';// Elixir.
		} elseif ( '3' == $wps_set_the_pdf_ticket_template ) {
			include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/wps-etmfw-mail-html-content-2.php';// Demure.
		} elseif ( '4' == $wps_set_the_pdf_ticket_template ) {
			include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/wps-etmfw-mail-html-content-3.php';// Mellifluous.
		}

		$wps_ticket_details = ob_get_contents();
		ob_end_clean();
		$additinal_info = '';
		$product = wc_get_product( $product_id );
		$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
		$start = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
		$end = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
		$venue = isset( $wps_etmfw_product_array['etmfw_event_venue'] ) ? $wps_etmfw_product_array['etmfw_event_venue'] : '';
		$wps_etmfw_stock_status = get_post_meta( $product_id, '_manage_stock', true );
		if ( ! empty( $item_meta_data ) && ( ( 'yes' === $wps_etmfw_stock_status && 1 < count( $item_meta_data ) ) || ( 'no' === $wps_etmfw_stock_status && 0 < count( $item_meta_data ) ) ) ) {
			$additinal_info = '<table border="0" cellspacing="0" cellpadding="0" style="table-layout: auto; width: 100%;"><tbody><tr><td style="padding: 20px 0 10px;"><h2 style="margin: 0;font-size: 24px; color: #000000;">Details :-</h2></td></tr>';
			foreach ( $item_meta_data as $key => $value ) {
				if ( isset( $value->key ) && ! empty( $value->value ) ) {
					if ( '_reduced_stock' === $value->key ) {
						continue;
					}
					$additinal_info .= '<tr><td style="padding: 5px 0;"><p style="margin: 0;">' . $value->key . ' - ' . $value->value . '</p></td></tr>';
				}
			}
			$additinal_info .= '</tbody></table>';
		}
		// $image_attributes = wp_get_attachment_image_src( get_option('wps_etmfw_background_image'), 'thumbnail' );
		$wps_etmfw_logo_size = ! empty( get_option( 'wps_etmfw_logo_size', true ) ) ? get_option( 'wps_etmfw_logo_size', true ) : '180';
		$site_logo = '<img id="wps_wem_logo_id" class="wps_wem_logo" src="' . get_option( 'wps_etmfw_mail_setting_upload_logo', '' ) . '" style="width:' . $wps_etmfw_logo_size . 'px;margin-left: 25px">';
		$wps_ticket_details = str_replace( '[EVENTNAME]', $product->get_name(), $wps_ticket_details );

		$wps_ticket_details = str_replace( '[TICKET]', $ticket_number, $wps_ticket_details );
		$wps_ticket_details = str_replace( '[TICKET1]', $ticket_number1, $wps_ticket_details );
		$wps_ticket_details = str_replace( '[VENUE]', $venue, $wps_ticket_details );
		$wps_ticket_details = str_replace( '[STARTDATE]', wps_etmfw_get_date_format( $start ), $wps_ticket_details );
		$wps_ticket_details = str_replace( '[ENDDATE]', wps_etmfw_get_date_format( $end ), $wps_ticket_details );
		$wps_ticket_details = str_replace( '[EMAILBODYCONTENT]', get_option( 'wps_etmfw_email_body_content', '' ), $wps_ticket_details );
		$wps_ticket_details = str_replace( '[SITENAME]', get_bloginfo(), $wps_ticket_details );
		$wps_ticket_details = str_replace( '[ADDITIONALINFO]', $additinal_info, $wps_ticket_details );
		$wps_ticket_details = str_replace( '[LOGO]', $site_logo, $wps_ticket_details );
		return apply_filters( 'wps_etmfw_ticket_html_content', $wps_ticket_details );
	}

	/**
	 * Function to generate pdf.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_generate_ticket_pdf()
	 * @param string $wps_ticket_content Ticket content.
	 * @param object $order Order Object.
	 * @param int    $order_id Order Id.
	 * @param string $ticket_number Ticket Number.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_generate_ticket_pdf( $wps_ticket_content, $order, $order_id, $ticket_number ) {
		require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'package/lib/dompdf/vendor/autoload.php';
		$dompdf = new Dompdf( array( 'enable_remote' => true ) );
		$dompdf->setPaper( 'A4', 'landscape' );
		$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';
		if ( ! is_dir( $upload_dir_path ) ) {
			wp_mkdir_p( $upload_dir_path );
			chmod( $upload_dir_path, 0775 );
		}
		$dompdf->loadHtml( $wps_ticket_content );
		@ob_end_clean(); // phpcs:ignore
		$dompdf->render();
		$dompdf->set_option( 'isRemoteEnabled', true );
		$output = $dompdf->output();

		$generated_ticket_pdf = $upload_dir_path . '/events' . $order_id . $ticket_number . '.pdf';
		if ( file_exists( $generated_ticket_pdf ) ) {
			$generated_pdf = file_put_contents( $upload_dir_path . '/events' . $order_id . $ticket_number . '-new.pdf', $output );
		} else {
			$generated_pdf = file_put_contents( $upload_dir_path . '/events' . $order_id . $ticket_number . '.pdf', $output );
		}
	}

	/**
	 * View and edit ticket button on thankyou page .
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_view_ticket_button()
	 * @param string $item_id Item Id.
	 * @param object $item Item.
	 * @param object $order Order Object.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_view_ticket_button( $item_id, $item, $order ) {
		$order_id = $order->get_id();
		$order_status = $order->get_status();
		$wps_etmfw_in_processing = get_option( 'wps_wet_enable_after_payment_done_ticket', false );
		$temp_status = 'completed';
		if ( 'on' == $wps_etmfw_in_processing ) {
			$temp_status = 'processing';
		}
		if ( ( 'completed' === $order_status || 'processing' === $order_status ) ) { // Order Status For Creating Event Ticket.
			$_product = apply_filters( 'wps_etmfw_woo_order_item_product', $product = $item->get_product(), $item );
			if ( isset( $_product ) && ! empty( $_product ) ) {
				$product_id = $_product->get_id();
			}
			if ( isset( $product_id ) && ! empty( $product_id ) ) {
				$product_types = wp_get_object_terms( $product_id, 'product_type' );
				if ( isset( $product_types[0] ) ) {
					$product_type = $product_types[0]->slug;
					$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
					if ( is_array( $ticket_number ) ) {

						foreach ( $ticket_number as $key => $value ) {
							if ( '' !== $value && 'event_ticket_manager' == $product_type ) {
								$updated_meta_pdf = get_post_meta( $order_id, 'wps_etmfw_order_meta_updated', true );
								if ( '' === $updated_meta_pdf ) {
									$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $value . '.pdf';
								} else {
									$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $value . '-new.pdf';
								}
								$event_name = $_product->get_name();
								$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
								$start_date = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
								$end_date = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
								$event_venue = isset( $wps_etmfw_product_array['etmfw_event_venue'] ) ? $wps_etmfw_product_array['etmfw_event_venue'] : '';
								$pro_short_desc = $_product->get_short_description();
								$start_timestamp = strtotime( $start_date );
								$end_timestamp = strtotime( $end_date );
								$gmt_offset_seconds = $this->wps_etmfw_get_gmt_offset_seconds( $start_timestamp );

								$calendar_url = 'https://calendar.google.com/calendar/r/eventedit?text=' . $event_name . '&dates=' . gmdate( 'Ymd\\THi00\\Z', ( $start_timestamp - $gmt_offset_seconds ) ) . '/' . gmdate( 'Ymd\\THi00\\Z', ( $end_timestamp - $gmt_offset_seconds ) ) . '&details=' . $pro_short_desc . '&location=' . $event_venue;

								?>
								<div class='wps_order_section_for_meta'>
								<div class="wps_etmfw_view_ticket_section">
									<a href="<?php echo esc_attr( $upload_dir_path ); ?>" class="wps_view_ticket_pdf" target="_blank"><?php esc_html_e( 'View', 'event-tickets-manager-for-woocommerce' ); ?></a>
								</div>
								<div class="wps_etmfw_calendar_section">
									<a href="<?php echo esc_attr( $calendar_url ); ?>" class="wps_etmfw_add_event_calendar" target="_blank"><?php esc_html_e( '+ Add to Google Calendar', 'event-tickets-manager-for-woocommerce' ); ?></a>
								</div>
								</div>
								<?php
								$item_meta_data = $item->get_meta_data();
								$wps_etmfw_field_data = isset( $wps_etmfw_product_array['wps_etmfw_field_data'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_field_data'] ) ? $wps_etmfw_product_array['wps_etmfw_field_data'] : array();
								$wps_etmfw_flag = false;
								if ( ! empty( $item_meta_data ) && ! empty( $wps_etmfw_field_data ) ) {
									foreach ( $item_meta_data as $key => $value ) {
										if ( isset( $value->key ) && ! empty( $value->value ) ) {
											$wps_etmfw_mail_template_data[ $value->key ] = $value->value;
										}
									}
									if ( is_array( $wps_etmfw_mail_template_data ) ) {
										foreach ( $wps_etmfw_mail_template_data as $label_key => $user_data_value ) {
											foreach ( $wps_etmfw_field_data as $key => $html_value ) {
												if ( 0 === strcasecmp( $html_value['label'], $label_key ) ) {
													$wps_etmfw_flag = true;
												}
											}
										}
									}
									if ( is_wc_endpoint_url( 'order-received' ) || is_wc_endpoint_url( 'view-order' ) ) {
										if ( $wps_etmfw_flag ) {
											?>
											<div class="wps_etmfw_edit_ticket_section">
												<span id="wps_etmfw_edit_ticket">
													<?php esc_html_e( 'Edit Ticket Information', 'event-tickets-manager-for-woocommerce' ); ?>
												</span>
												<form id="wps_etmfw_edit_ticket_form">
													<input type="hidden" id="wps_etmfw_edit_info_order" value="<?php echo esc_attr( $order_id ); ?>">
													<?php

													foreach ( $wps_etmfw_mail_template_data as $label_key => $user_data_value ) {
														foreach ( $wps_etmfw_field_data as $key => $html_value ) {
															if ( 0 === strcasecmp( $html_value['label'], $label_key ) ) {
																$this->generate_edit_ticket_inputs( $html_value, $user_data_value );
																echo '<span id=wps_etmfw_error_' . wp_kses_post( $html_value['label'] ) . '></span>';
															}
														}
													}
													?>
													<input type="submit" class="button button-primary"
														name="wps_etmfw_save_edit_ticket_info_btn"
														id="wps_etmfw_save_edit_ticket_info_btn"
														value="<?php esc_attr_e( 'Save Changes', 'event-tickets-manager-for-woocommerce' ); ?>"
														/>
														<div class="wps_etmfw_loader" id="wps_etmfw_edit_info_loader">
															<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/loading.gif' ); ?>">
														</div>
												</form>
											</div>
											<?php
										}
									}
								}
							}
						}
					} else {

						if ( '' !== $ticket_number && 'event_ticket_manager' == $product_type ) {
							$updated_meta_pdf = get_post_meta( $order_id, 'wps_etmfw_order_meta_updated', true );
							if ( '' === $updated_meta_pdf ) {
								$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket_number . '.pdf';
							} else {
								$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket_number . '-new.pdf';
							}
							$event_name = $_product->get_name();
							$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
							$start_date = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
							$end_date = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
							$event_venue = isset( $wps_etmfw_product_array['etmfw_event_venue'] ) ? $wps_etmfw_product_array['etmfw_event_venue'] : '';
							$pro_short_desc = $_product->get_short_description();
							$start_timestamp = strtotime( $start_date );
							$end_timestamp = strtotime( $end_date );
							$gmt_offset_seconds = $this->wps_etmfw_get_gmt_offset_seconds( $start_timestamp );

							$calendar_url = 'https://calendar.google.com/calendar/r/eventedit?text=' . $event_name . '&dates=' . gmdate( 'Ymd\\THi00\\Z', ( $start_timestamp - $gmt_offset_seconds ) ) . '/' . gmdate( 'Ymd\\THi00\\Z', ( $end_timestamp - $gmt_offset_seconds ) ) . '&details=' . $pro_short_desc . '&location=' . $event_venue;

							?>
							<div class="wps_etmfw_view_ticket_section">
								<a href="<?php echo esc_attr( $upload_dir_path ); ?>" class="wps_view_ticket_pdf" target="_blank"><?php esc_html_e( 'View', 'event-tickets-manager-for-woocommerce' ); ?></a>
							</div>
							<div class="wps_etmfw_calendar_section">
								<a href="<?php echo esc_attr( $calendar_url ); ?>" class="wps_etmfw_add_event_calendar" target="_blank"><?php esc_html_e( '+ Add to Google Calendar', 'event-tickets-manager-for-woocommerce' ); ?></a>
							</div>
							<?php
							$item_meta_data = $item->get_meta_data();
							$wps_etmfw_field_data = isset( $wps_etmfw_product_array['wps_etmfw_field_data'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_field_data'] ) ? $wps_etmfw_product_array['wps_etmfw_field_data'] : array();
							$wps_etmfw_flag = false;
							if ( ! empty( $item_meta_data ) && ! empty( $wps_etmfw_field_data ) ) {
								foreach ( $item_meta_data as $key => $value ) {
									if ( isset( $value->key ) && ! empty( $value->value ) ) {
										$wps_etmfw_mail_template_data[ $value->key ] = $value->value;
									}
								}
								foreach ( $wps_etmfw_mail_template_data as $label_key => $user_data_value ) {
									foreach ( $wps_etmfw_field_data as $key => $html_value ) {
										if ( 0 === strcasecmp( $html_value['label'], $label_key ) ) {
											$wps_etmfw_flag = true;
										}
									}
								}
								if ( is_wc_endpoint_url( 'order-received' ) || is_wc_endpoint_url( 'view-order' ) ) {
									if ( $wps_etmfw_flag ) {
										?>
										<div class="wps_etmfw_edit_ticket_section">
											<span id="wps_etmfw_edit_ticket">
												<?php esc_html_e( 'Edit Ticket Information', 'event-tickets-manager-for-woocommerce' ); ?>
											</span>
											<form id="wps_etmfw_edit_ticket_form">
												<input type="hidden" id="wps_etmfw_edit_info_order" value="<?php echo esc_attr( $order_id ); ?>">
												<?php

												foreach ( $wps_etmfw_mail_template_data as $label_key => $user_data_value ) {
													foreach ( $wps_etmfw_field_data as $key => $html_value ) {
														if ( 0 === strcasecmp( $html_value['label'], $label_key ) ) {
															$this->generate_edit_ticket_inputs( $html_value, $user_data_value );
															echo '<span id=wps_etmfw_error_' . wp_kses_post( $html_value['label'] ) . '></span>';
														}
													}
												}
												?>
												<input type="submit" class="button button-primary"
													name="wps_etmfw_save_edit_ticket_info_btn"
													id="wps_etmfw_save_edit_ticket_info_btn"
													value="<?php esc_attr_e( 'Save Changes', 'event-tickets-manager-for-woocommerce' ); ?>"
													/>
													<div class="wps_etmfw_loader" id="wps_etmfw_edit_info_loader">
														<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/loading.gif' ); ?>">
													</div>
											</form>
										</div>
										<?php
									}
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Create additional input fields to modify user input data.
	 *
	 * @since 1.0.0
	 * @name generate_edit_ticket_inputs()
	 * @param array  $html_value Html Values.
	 * @param string $user_data_value User data Values.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function generate_edit_ticket_inputs( $html_value, $user_data_value ) {
		if ( array_key_exists( 'required', $html_value ) && 'on' === $html_value['required'] ) {
			$required = 'required="required"';
			$mandatory = true;
		} else {
			$mandatory = false;
			$required = '';
		}
		$html_value_label = strtolower( str_replace( ' ', '_', $html_value['label'] ) );
		require EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'templates/frontend/event-tickets-manager-for-woocommerce-view-ticket-html.php';

	}

	/**
	 * This is function is used to create shortcode to check event check in.
	 *
	 * @name wps_etmfw_add_eventcheckin_shortcode
	 * @author WPSwings<webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_etmfw_add_eventcheckin_shortcode() {
		add_shortcode( 'wps_etmfw_event_checkin_page', array( $this, 'wps_etmfw_create_event_checkin_page' ) );
	}

	/**
	 * This is function is used to display event check in page.
	 *
	 * @name wps_etmfw_create_event_checkin_page
	 * @author WPSwings<webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_etmfw_create_event_checkin_page() {

		$query_args = array(
			'post_type'          => 'product',
			'post_status'        => 'publish',
			'posts_per_page'     => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'event_ticket_manager',
				),
			),
		);
		$product_array = new WP_Query( $query_args );
		$html = '<div class="wps_etmfw_checkin_wrapper">
			<form method="post">
			<div id="wps_etmfw_error_message"></div>
			<div class="wps_etmfw_events_section">
				<label>' . __( 'For', 'event-tickets-manager-for-woocommerce' ) . '</label>';
		if ( $product_array->have_posts() ) {
			if ( isset( $product_array->posts ) && ! empty( $product_array->posts ) ) {
				$html .= '<select id="wps_etmfw_event_selected">';
				foreach ( $product_array->posts as $event_per_product ) {
					$html .= '<option value="' . $event_per_product->ID . '">' . $event_per_product->post_title . '</option>';
				}
				$html .= '</select>';
			}
		}

			$html .= '</div>
			<div class="wps_etmfw_input_ticket_section">
				<label>' . __( 'Ticket Number *', 'event-tickets-manager-for-woocommerce' ) . '</label>
				<input type="text" name="wps_etmfw_imput_ticket" id="wps_etmfw_imput_ticket">
			</div>
			<div class="wps_etmfw_input_ticket_section">
				<label>' . __( 'Enter Email *', 'event-tickets-manager-for-woocommerce' ) . '</label>
				<input type="email" name="wps_etmfw_chckin_email" id="wps_etmfw_chckin_email">
			</div>


			<div class="wps_etmfw--loader-btn-wrapper">
				<div class="wps_etmfw_checkin_button_section">
					<input type="submit" name="wps_etmfw_checkin_button" id="wps_etmfw_checkin_button" value="' . __( 'Check In', 'event-tickets-manager-for-woocommerce' ) . '">
				</div>
				<div class="wps_etmfw_loader" id="wps_etmfw_checkin_loader">
					<img src="' . esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/loading.gif' ) . '">
				</div>
			</div>
			</form>
		</div>';
		do_action( 'wps_etmfw_transfer_ticket_hook' );
		return $html;
	}

	/**
	 * Create form for user checkin.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_make_user_checkin_for_event().
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_make_user_checkin_for_event() {
		check_ajax_referer( 'wps-etmfw-verify-checkin-nonce', 'wps_nonce' );
		$response['result'] = false;
		$response['message'] = 'No tickets found for the event.';
		$product_id = isset( $_REQUEST['for_event'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['for_event'] ) ) : '';
		$ticket_num = isset( $_REQUEST['ticket_num'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ticket_num'] ) ) : '';
		$user_email = isset( $_REQUEST['user_email'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['user_email'] ) ) : '';
		$generated_tickets = get_post_meta( $product_id, 'wps_etmfw_generated_tickets', true );
		if ( ! empty( $generated_tickets ) ) {
			foreach ( $generated_tickets as $key => $value ) {
				if ( $ticket_num == $value['ticket'] ) {
					if ( $user_email === $value['email'] ) {
						if ( 'pending' === $value['status'] ) {
							$post = get_post( $value['order_id'] );
							if ( 'trash' !== $post->post_status ) {
								$current_timestamp = current_time( 'timestamp' );
								$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
								$end_date = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
								$start_date = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
								$end_date_timestamp = strtotime( $end_date );
								$start_date_timestamp = strtotime( $start_date );
								if ( $end_date_timestamp > $current_timestamp ) {
									if ( $current_timestamp > $start_date_timestamp ) {
										$response['result'] = true;
										$generated_tickets[ $key ]['status'] = 'checked_in';
										update_post_meta( $product_id, 'wps_etmfw_generated_tickets', $generated_tickets );
										$response['message'] = __( 'User checked in successfully.', 'event-tickets-manager-for-woocommerce' );
									} else {
										$response['message'] = __( 'Event has not started yet.', 'event-tickets-manager-for-woocommerce' );
									}
								} else {
									$response['message'] = __( 'Event Expired!', 'event-tickets-manager-for-woocommerce' );
								}
							} else {
								$response['message'] = __( 'Order not exist.', 'event-tickets-manager-for-woocommerce' );
							}
						} else {
							$response['message'] = __( 'User has already checked in for the event.', 'event-tickets-manager-for-woocommerce' );
						}
					} else {
						$response['message'] = __( 'Please Enter the correct email.', 'event-tickets-manager-for-woocommerce' );

					}
				}
			}
		}
		echo json_encode( $response );
		wp_die();

	}

	/**
	 * Edit user information for event.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_edit_user_info_for_event().
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_edit_user_info_for_event() {
		check_ajax_referer( 'wps-etmfw-verify-public-nonce', 'wps_nonce' );

		$response['result'] = false;
		$posted_value = ! empty( $_REQUEST['form_value'] ) ? map_deep( wp_unslash( $_REQUEST['form_value'] ), 'sanitize_text_field' ) : array();
		$order_id = isset( $_REQUEST['order_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ) ) : '';
		$order = wc_get_order( $order_id );
		foreach ( $order->get_items() as $item_id => $item ) {
			$product = $item->get_product();
			if ( isset( $product ) && $product->is_type( 'event_ticket_manager' ) ) {
				if ( count( $posted_value ) > 0 ) {

					foreach ( $posted_value as $key => $value ) {
						$key = ucwords( str_replace( '_', ' ', $key ) );
						$item->update_meta_data( $key, $value );
						$item->save();
						$response['result'] = true;
						update_post_meta( $order_id, 'wps_etmfw_order_meta_updated', 'yes' );
						$product_id = $product->get_id();
						$item_meta_data = $item->get_meta_data();
						$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
						if ( is_array( $ticket_number ) && ! empty( $ticket_number ) ) {

							foreach ( $ticket_number as $key => $value ) {

								$wps_ticket_content = $this->wps_etmfw_get_html_content( $item_meta_data, $order, $order_id, $value, $product_id );
								$this->wps_etmfw_generate_ticket_pdf( $wps_ticket_content, $order, $order_id, $value );
							}
						} else {

							$wps_ticket_content = $this->wps_etmfw_get_html_content( $item_meta_data, $order, $order_id, $ticket_number, $product_id );
							$this->wps_etmfw_generate_ticket_pdf( $wps_ticket_content, $order, $order_id, $ticket_number );
						}
					}
				}
			}
		}
		echo json_encode( $response );
		wp_die();

	}

	/**
	 * Get events for calendar.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_get_calendar_widget_data().
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_get_calendar_widget_data() {
		check_ajax_referer( 'wps-etmfw-verify-public-nonce', 'wps_nonce' );

		$calendar_data = array();
		$filter_duration = get_option( 'wps_etmfw_display_duration', 'all' );
		$query_args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page'    => -1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'event_ticket_manager',
				),
			),
		);

		$query_data = new WP_Query( $query_args );
		if ( $query_data->have_posts() ) {
			while ( $query_data->have_posts() ) {
				$query_data->the_post();
				$calendar_data[] = $this->wps_generate_list_view( $filter_duration, $calendar_data );
			}
		}

		wp_reset_postdata();
		$response['result'] = $calendar_data;
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * Generate list view for event.
	 *
	 * @since 1.0.0
	 * @name wps_generate_list_view().
	 * @param string $filter_duration Duration on the basis of filter.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_generate_list_view( $filter_duration ) {
		global $product;
		$event_array = array();
		$current_timestamp = current_time( 'timestamp' );
		$wps_etmfw_product_array = get_post_meta( $product->get_id(), 'wps_etmfw_product_array', true );
		$start_date = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
		$end_date = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
		$end_date_timestamp = strtotime( gmdate( 'Y-m-d', strtotime( $end_date ) ) );
		$start_date_timestamp = strtotime( gmdate( 'Y-m-d', strtotime( $start_date ) ) );
		$current_timestamp = strtotime( gmdate( 'Y-m-d', $current_timestamp ) );
		switch ( $filter_duration ) {
			case 'all':
				$event_array = array(
					'title' => $product->get_title(),
					'start' => gmdate( 'Y-m-d', strtotime( $start_date ) ),
					'end' => gmdate( 'Y-m-d', strtotime( $end_date . ' +1 day' ) ),
					'url'   => get_permalink( $product->get_id() ),
				);

				break;

			case 'future':
				if ( $end_date_timestamp > $current_timestamp ) {
					$event_array = array(
						'title' => $product->get_title(),
						'start' => gmdate( 'Y-m-d', strtotime( $start_date ) ),
						'end' => gmdate( 'Y-m-d', strtotime( $end_date . ' +1 day' ) ),
						'url'   => get_permalink( $product->get_id() ),
					);
				}
				break;

			case 'past':
				if ( $end_date_timestamp < $current_timestamp ) {
					$event_array = array(
						'title' => $product->get_title(),
						'start' => gmdate( 'Y-m-d', strtotime( $start_date ) ),
						'end' => gmdate( 'Y-m-d', strtotime( $end_date . ' +1 day' ) ),
						'url'   => get_permalink( $product->get_id() ),
					);
				}
				break;

			default:
				break;
		}
		return $event_array;
	}

	/**
	 * Unset COD payment gateway for cod.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_unset_cod_payment_gateway_for_event().
	 * @param array $available_gateways Available payment gateways.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_unset_cod_payment_gateway_for_event( $available_gateways ) {
		if ( is_admin() || ! is_checkout() ) {
			return $available_gateways;
		}

		$wps_etmfw_event = false;
		foreach ( WC()->cart->get_cart_contents() as $key => $values ) {
			if ( $this->wps_etmfw_check_product_is_event( $values['data'] ) ) {
				$wps_etmfw_event = true;
				break;
			}
		}
		if ( $wps_etmfw_event ) {
			if ( isset( $available_gateways ) && ! empty( $available_gateways ) && is_array( $available_gateways ) ) {
				foreach ( $available_gateways as $key => $gateways ) {
					if ( 'cod' === $key ) {
						unset( $available_gateways[ $key ] );
					}
				}
			}
		}
		return $available_gateways;
	}

	/**
	 * Check if product is event type.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_check_product_is_event().
	 * @param object $product Product.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_check_product_is_event( $product ) {
		$wps_is_event = false;
		if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
			$wps_is_event = true;
		}
		return $wps_is_event;
	}

	/**
	 * Handle expired events.
	 *
	 * @param boolean $purchasable If product is purchaseable.
	 * @param array   $product Event Venue.
	 * @return boolean $purchasable If product is purchaseable.
	 * @since    1.0.0
	 */
	public function wps_etmfw_handle_expired_events( $purchasable, $product ) {
		if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
			$product_id = $product->get_id();
			$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
			$end_date = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
			$current_timestamp = current_time( 'timestamp' );
			$end_date_timestamp = strtotime( $end_date );
			if ( $end_date_timestamp < $current_timestamp ) {
				$purchasable = false;
			}
		}
		return $purchasable;
	}

	/**
	 * Get GMT offset based on seconds.
	 *
	 * @param string $date Event Start Date.
	 * @return string.
	 */
	public function wps_etmfw_get_gmt_offset_seconds( $date = null ) {
		if ( $date ) {
			$timezone = new DateTimeZone( $this->wps_etmfw_get_timezone() );

			// Convert to Date.
			if ( is_numeric( $date ) ) {
				$date = gmdate( 'Y-m-d', $date );
			}

			$target = new DateTime( $date, $timezone );
			return $timezone->getOffset( $target );
		} else {
			$gmt_offset = get_option( 'gmt_offset' );
			$seconds = $gmt_offset * HOUR_IN_SECONDS;

			return ( substr( $gmt_offset, 0, 1 ) == '-' ? '' : '+' ) . $seconds;
		}
	}

	/**
	 * Get default timezone of WordPress.
	 *
	 * @param mixed $event Event Date.
	 * @return string.
	 */
	public function wps_etmfw_get_timezone( $event = null ) {
		$timezone_string = get_option( 'timezone_string' );
		$gmt_offset = get_option( 'gmt_offset' );

		if ( trim( $timezone_string ) == '' && trim( $gmt_offset ) ) {
			$timezone_string = $this->wps_etmfw_get_timezone_by_offset( $gmt_offset );
		} elseif ( trim( $timezone_string ) == '' && trim( $gmt_offset ) == '0' ) {
			$timezone_string = 'UTC';
		}

		return $timezone_string;
	}

	/**
	 * Get timezone by offset.
	 *
	 * @param mixed $offset Time offset.
	 * @return string.
	 */
	public function wps_etmfw_get_timezone_by_offset( $offset ) {
		$seconds = $offset * 3600;

		$timezone = timezone_name_from_abbr( '', $seconds, 0 );
		if ( false === $timezone ) {
			$timezones = array(
				'-12' => 'Pacific/Auckland',
				'-11.5' => 'Pacific/Auckland', // Approx.
				'-11' => 'Pacific/Apia',
				'-10.5' => 'Pacific/Apia', // Approx.
				'-10' => 'Pacific/Honolulu',
				'-9.5' => 'Pacific/Honolulu', // Approx.
				'-9' => 'America/Anchorage',
				'-8.5' => 'America/Anchorage', // Approx.
				'-8' => 'America/Los_Angeles',
				'-7.5' => 'America/Los_Angeles', // Approx.
				'-7' => 'America/Denver',
				'-6.5' => 'America/Denver', // Approx.
				'-6' => 'America/Chicago',
				'-5.5' => 'America/Chicago', // Approx.
				'-5' => 'America/New_York',
				'-4.5' => 'America/New_York', // Approx.
				'-4' => 'America/Halifax',
				'-3.5' => 'America/Halifax', // Approx.
				'-3' => 'America/Sao_Paulo',
				'-2.5' => 'America/Sao_Paulo', // Approx.
				'-2' => 'America/Sao_Paulo',
				'-1.5' => 'Atlantic/Azores', // Approx.
				'-1' => 'Atlantic/Azores',
				'-0.5' => 'UTC', // Approx.
				'0' => 'UTC',
				'0.5' => 'UTC', // Approx.
				'1' => 'Europe/Paris',
				'1.5' => 'Europe/Paris', // Approx.
				'2' => 'Europe/Helsinki',
				'2.5' => 'Europe/Helsinki', // Approx.
				'3' => 'Europe/Moscow',
				'3.5' => 'Europe/Moscow', // Approx.
				'4' => 'Asia/Dubai',
				'4.5' => 'Asia/Tehran',
				'5' => 'Asia/Karachi',
				'5.5' => 'Asia/Kolkata',
				'5.75' => 'Asia/Katmandu',
				'6' => 'Asia/Yekaterinburg',
				'6.5' => 'Asia/Yekaterinburg', // Approx.
				'7' => 'Asia/Krasnoyarsk',
				'7.5' => 'Asia/Krasnoyarsk', // Approx.
				'8' => 'Asia/Shanghai',
				'8.5' => 'Asia/Shanghai', // Approx.
				'8.75' => 'Asia/Tokyo', // Approx.
				'9' => 'Asia/Tokyo',
				'9.5' => 'Asia/Tokyo', // Approx.
				'10' => 'Australia/Melbourne',
				'10.5' => 'Australia/Adelaide',
				'11' => 'Australia/Melbourne', // Approx.
				'11.5' => 'Pacific/Auckland', // Approx.
				'12' => 'Pacific/Auckland',
				'12.75' => 'Pacific/Apia', // Approx.
				'13' => 'Pacific/Apia',
				'13.75' => 'Pacific/Honolulu', // Approx.
				'14' => 'Pacific/Honolulu',
			);

			$timezone = isset( $timezones[ $offset ] ) ? $timezones[ $offset ] : null;
		}

		return $timezone;
	}

	/**
	 * Get product type.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_get_product_type().
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @return string $product_type Product type.
	 */
	public function wps_etmfw_get_product_type() {
		global $post;
		if ( isset( $post ) && ! empty( $post ) ) {
			$product = wc_get_product( $post->ID );
			if ( isset( $product ) && ! empty( $product ) ) {
				$product_id = $product->get_id();
				if ( isset( $product_id ) && ! empty( $product_id ) ) {
					$product_types = wp_get_object_terms( $product_id, 'product_type' );
					$product_type = $product_types[0]->slug;
					return $product_type;
				}
			}
		}
	}

	/**
	 * Check if event is expired.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_check_if_event_is_expired().
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @return boolean $wps_etmfw_if_expired If event is expired.
	 */
	public function wps_etmfw_check_if_event_is_expired() {
		$wps_etmfw_if_expired = false;
		global $post;
		if ( isset( $post ) && ! empty( $post ) ) {
			$product = wc_get_product( $post->ID );
			if ( isset( $product ) && ! empty( $product ) ) {
				if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
					$product_id = $product->get_id();
					$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
					$wps_trash_event_expire = isset( $wps_etmfw_product_array['etmfw_event_trash_event'] ) ? $wps_etmfw_product_array['etmfw_event_trash_event'] : true;
					$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
					$end_date = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
					$current_timestamp = current_time( 'timestamp' );
					$end_date_timestamp = strtotime( $end_date );
					if ( $end_date_timestamp < $current_timestamp ) {
						if ( 'yes' == $wps_trash_event_expire ) {
							wp_trash_post( $product_id );
						}
						$wps_etmfw_if_expired = true;
					}
				}
			}
		}
		return $wps_etmfw_if_expired;
	}

	/**
	 * Show google map on single product page.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_show_google_map_on_product_page().
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @return boolean $if_show_map display map.
	 */
	public function wps_etmfw_show_google_map_on_product_page() {
		$if_show_map = false;
		global $post;
		if ( isset( $post ) && ! empty( $post ) ) {
			$product = wc_get_product( $post->ID );
			if ( isset( $product ) && ! empty( $product ) ) {
				if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
					$product_id = $product->get_id();
					$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
					$display_map = isset( $wps_etmfw_product_array['etmfw_display_map'] ) ? $wps_etmfw_product_array['etmfw_display_map'] : 'no';
					$location_site = get_option( 'wps_etmfw_enabe_location_site', 'off' );
					$map_api_key = get_option( 'wps_etmfw_google_maps_api_key', '' );
					if ( 'yes' === $display_map && 'on' === $location_site && '' !== $map_api_key ) {
						$if_show_map = true;
					}
				}
			}
		}
		return $if_show_map;
	}

	/**
	 * Function name wps_etmfw_show_expired_message.
	 * this function is used to show event expiration message
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function wps_etmfw_show_expired_message() {
		$wps_etmfw_if_expired   = $this->wps_etmfw_check_if_event_is_expired();
		if ( $wps_etmfw_if_expired ) {
			require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'templates/frontend/event-tickets-manager-for-woocommerce-event-expired-html.php';

		}
	}
	/**
	 * Registering custom product type.
	 *
	 * @return void
	 */
	public function wps_wgc_register_event_ticket_manager_product_types() {
		require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-wc-product-event-ticket-manager.php';
	}

	/**
	 * Set order as booking type.
	 *
	 * @param int    $order_id current order id.
	 * @param object $order current order object.
	 * @return void
	 */
	public function wps_etmfw_set_order_as_event_ticket_manager( $order_id, $order ) {
		$order_items = $order->get_items();
		foreach ( $order_items as $item ) {
			$product = $item->get_product();
			if ( 'event_ticket_manager' === $product->get_type() ) {
				$order->update_meta_data( 'wps_order_type', 'event' );
				$order->save();
				break;
			}
		}
	}

	/**
	 * Register Endpoint for My Event Tab.
	 */
	public function wps_my_event_register_endpoint() {

		add_rewrite_endpoint( 'wps-myevent-tab', EP_PERMALINK | EP_PAGES );
		flush_rewrite_rules();
	}

	/**
	 * Adding a query variable for the Endpoint.
	 *
	 * @param array $vars An array of query variables.
	 */
	public function wps_myevent_endpoint_query_var( $vars ) {

		$vars[] = 'wps-myevent-tab';

		/**
		 * Filter for endpoints.
		 *
		 * @since 1.0.0
		 */
		$vars = apply_filters( 'wps_myevent_endpoint_query_var', $vars );

		return $vars;
	}

	/**
	 * Inserting custom membership endpoint.
	 *
	 * @param array $items An array of all menu items on My Account page.
	 */
	public function wps_event_add_myevent_tab( $items ) {
		// Placing the custom tab just above logout tab.
		$items['wps-myevent-tab'] = esc_html__( 'My Event', 'membership-for-woocommerce' );

		/**
		 * Filter for my event tab.
		 *
		 * @since 1.0.0
		 */
		$items = apply_filters( 'wps_event_add_myevent_tab', $items );

		return $items;
	}

	/**
	 * Add content to My Event details tab.
	 *
	 * @return void
	 */
	public function wps_myevent_populate_tab() {
		require plugin_dir_path( __FILE__ ) . 'partials/wps-myevent-details-tab.php';

	}
	/**
	 * This is function is used to share the tickets in Mail.
	 *
	 * @name wps_etmfwp_sharing_tickets.
	 * @link http://www.wpswings.com/
	 */
	public function wps_etmfwp_sharing_tickets_org() {
		$response['result'] = true;
		$product_id = isset( $_REQUEST['for_event'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['for_event'] ) ) : '';
		$ticket_num = isset( $_REQUEST['ticket_num'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ticket_num'] ) ) : '';
		$user_email = isset( $_REQUEST['user_email'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['user_email'] ) ) : '';

		// ==> Define HERE the statuses of that orders.
		$wps_etmfw_in_processing = get_option( 'wps_wet_enable_after_payment_done_ticket', false );
		if ( 'on' == $wps_etmfw_in_processing ) {
			$order_statuses = array( 'wc-completed', 'wc-processing' );
		} else {
			$order_statuses = array( 'wc-completed', 'wc-processing' ); // Order Status For Creating Event Ticket.
		}

		// // ==> Define HERE the customer ID.
		$customer_user_id = get_current_user_id(); // current user ID here for example.

		// Getting current customer orders.
		$customer_orders = wc_get_orders(
			array(
				'meta_key' => '_customer_user',
				'meta_value' => $customer_user_id,
				'post_status' => $order_statuses,
				'numberposts' => -1,
			)
		);

		$generated_tickets = get_post_meta( $product_id, 'wps_etmfw_generated_tickets', true );
		$user_id = get_current_user_id();
		$transfer_id = '';
		if ( ! empty( $generated_tickets ) ) {
			foreach ( $generated_tickets as $key => $value ) {
				if ( $ticket_num == $value['ticket'] ) {
					$response['order_id'] = $value['order_id'];
					$current_ticket_order_id = $value['order_id'];
				}
			}
			// Loop through each customer WC_Order objects.
			$order_id = array();
			foreach ( $customer_orders as $order ) {
				// Order ID (added WooCommerce 3+ compatibility).

				$order_id[] = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;

			}

			if ( ! empty( $order_id ) ) {

				if ( in_array( $current_ticket_order_id, $order_id ) ) {
					$post = get_post( $current_ticket_order_id );
					if ( 'trash' !== $post->post_status ) {
						foreach ( $generated_tickets as $key => $value ) {
							if ( $ticket_num == $value['ticket'] ) {
								$transfer_id = $value['order_id'];
								$order = wc_get_order( $transfer_id );
								$billing_email = $order->get_billing_email();
								$wps_etmfw_mail_template_data = array();

									session_start();
									$_SESSION['order_id'] = $value['order_id'];
									$_SESSION['ticket_no'] = $ticket_num;

									$wps_etmfw_mail_template_data = array(
										'product_id' => $product_id,
										// 'item_id'   => $item_id,
										'order_id'   => $transfer_id,
										'product_name' => get_the_title( $product_id ),

									);
										$item_meta_data = array();
										$ticket_number = wps_etmfw_ticket_generator();
										$wps_ticket_content = $this->wps_etmfw_get_html_content( $item_meta_data, $order, $transfer_id, $ticket_num, $product_id );
										$this->wps_etmfw_generate_ticket_pdf( $wps_ticket_content, $order, $transfer_id, $ticket_num );

										$wps_etmfw_mail_template_data['ticket_number'] = $ticket_num;
									$this->wps_etmfw_send_ticket_mail_shared( $order, $wps_etmfw_mail_template_data, $user_email );
							}
						}
						$response['message'] = __( 'Ticket Transfer successfully.', 'event-tickets-manager-for-woocommerce-pro' );
						$_SESSION['wps_Check_point'] = 1;

						// here send code will added.
						$response['result'] = true;
					} else {
						$response['message'] = __( 'Order not exist.', 'event-tickets-manager-for-woocommerce-pro' );
					}
				} else {
					$response['message'] = __( 'Wrong Ticket Number / Not Yours Ticket.', 'event-tickets-manager-for-woocommerce-pro' );
				}
			}
		} else {
			$response['message'] = __( 'Ticket of Event is not yet purchase.', 'event-tickets-manager-for-woocommerce-pro' );
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * This is function is used to resend the tickets in Mail For Frontend.
	 *
	 * @name wps_etmfw_resend_mail_ticket_view_order_frontend.
	 * @param array $order An array of query variables.
	 * @link http://www.wpswings.com/
	 */
	public function wps_etmfw_resend_mail_ticket_view_order_frontend( $order ) {
		$order_id = $order->get_id();
		$order_status = $order->get_status();

		if ( 'completed' == $order_status || 'processing' == $order_status ) {
			$wps_etmfw_is_product = false;
			foreach ( $order->get_items() as $item_id => $item ) {
				$_product = apply_filters( 'woocommerce_order_item_product', $product = $item->get_product(), $item );

				if ( isset( $_product ) && ! empty( $_product ) ) {
					$product_id = $_product->get_id();
				}

				if ( isset( $product_id ) && ! empty( $product_id ) ) {

					$product_types = wp_get_object_terms( $product_id, 'product_type' );
					$product_type = $product_types[0]->slug;

					if ( 'event_ticket_manager' == $product_type ) {
						$wps_etmfw_is_product = true;
					}
				}
			}
			if ( $wps_etmfw_is_product ) {
				?>
				<style>
				#wps_etmfw_loader {
					background-color: rgba(255, 255, 255, 0.6);
					bottom: 0;
					height: 100%;
					left: 0;		
					position: fixed;
					right: 0;
					top: 0;
					width: 100%;
					z-index: 99999;
				}

				#wps_etmfw_loader img {
					display: block;
					left: 0;
					margin: 0 auto;
					position: absolute;
					right: 0;
					top: 40%;
				}
			</style>
									<div class="resend_mail_wrapper">
						<span id="wps_etmfw_resend_mail_frontend_notification"></span>
						<h4>
							<strong><?php esc_html_e( 'Resend Ticket PDF Email', 'event-tickets-manager-for-woocommerce-pro' ); ?></strong>
						</h4>
						<div id="wps_etmfw_loader" style="display: none;">
							<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ); ?>public/src/image/loading.gif">
						</div>
						<span class="wps_resend_content"><?php esc_html_e( "Press the icon to resend tickte pdf mail if the receiver hasn't received the ticket you sent.", 'event-tickets-manager-for-woocommerce-pro' ); ?>
						</span>
						<a href="javascript:void(0);" data-id="<?php echo esc_attr( $order_id ); ?>" class="wps_uwgc_resend_mail" id="wps_etmfw_resend_mail_button_frontend"> 
							<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ); ?>public/src/image/ticket.png" class="wps_resend_image">
						</a>
					</div>
					<?php
			}
		}
	}

	/**
	 * This is function is used to resend the tickets in Mail.
	 *
	 * @name wps_etmfw_resend_mail_pdf_order_deatails.
	 * @link http://www.wpswings.com/
	 */
	public function wps_etmfw_resend_mail_pdf_order_deatails() {
		check_ajax_referer( 'wps-etmfw-verify-public-nonce', 'wps_nonce' );
		if ( isset( $_POST['order_id'] ) ) {
			$order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( wp_unslash( $_POST['order_id'] ) ) : '';
			$this->wps_etmfw_process_event_order( $order_id, $old_status = '', $new_status = '' );
			$response = __( 'Ticket PDF Sent Successfully!', 'event-tickets-manager-for-woocommerce-pro' );
		} else {
			$response = __( 'Ticket PDF Not Sent!', 'event-tickets-manager-for-woocommerce-pro' );
		}
		echo json_encode( $response );
		wp_die();
	}
}
