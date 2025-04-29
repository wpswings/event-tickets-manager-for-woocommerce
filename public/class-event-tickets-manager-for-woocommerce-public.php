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
use Automattic\WooCommerce\Utilities\OrderUtil;
use Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils;

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
		wp_enqueue_style( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/scss/event-tickets-manager-for-woocommerce-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function etmfw_public_enqueue_scripts() {
		$wps_plugin_list = get_option( 'active_plugins' );
		$wps_is_pro_active = 'no';
		$wps_plugin = 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php';
		if ( in_array( $wps_plugin, $wps_plugin_list ) ) {
			$wps_is_pro_active = 'yes';
		}

		// Get the Details For the Dynamic Form Start Here.
		$wps_etmfw_product_array = get_post_meta( get_the_ID(), 'wps_etmfw_product_array', true );

		$wps_etmfw_dyn_name = isset( $wps_etmfw_product_array['wps_etmfw_dyn_name'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_name'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_name'] : '';
		$wps_etmfw_dyn_mail = isset( $wps_etmfw_product_array['wps_etmfw_dyn_mail'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_mail'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_mail'] : '';
		$wps_etmfw_dyn_contact = isset( $wps_etmfw_product_array['wps_etmfw_dyn_contact'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_contact'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_contact'] : '';
		$wps_etmfw_dyn_date = isset( $wps_etmfw_product_array['wps_etmfw_dyn_date'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_date'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_date'] : '';
		$wps_etmfw_dyn_address = isset( $wps_etmfw_product_array['wps_etmfw_dyn_address'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_address'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_address'] : '';
		$wps_limit_user_purchase_event = isset( $wps_etmfw_product_array['wps_limit_user_purchase_event'] ) && ! empty( $wps_etmfw_product_array['wps_limit_user_purchase_event'] ) ? $wps_etmfw_product_array['wps_limit_user_purchase_event'] : '';
		$etmfw_set_limit_qty = isset( $wps_etmfw_product_array['etmfw_set_limit_qty'] ) && ! empty( $wps_etmfw_product_array['etmfw_set_limit_qty'] ) ? $wps_etmfw_product_array['etmfw_set_limit_qty'] : '';

		wp_register_script( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/js/event-tickets-manager-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );

		$wps_event_product_url = is_product() ? get_permalink() : '';
		$public_param_data = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'wps_etmfw_public_nonce' => wp_create_nonce( 'wps-etmfw-verify-public-nonce' ),
			'is_required' => __( ' Is Required', 'event-tickets-manager-for-woocommerce' ),
			'wps_etmfw_dyn_name' => $wps_etmfw_dyn_name,
			'wps_etmfw_dyn_mail' => $wps_etmfw_dyn_mail,
			'wps_etmfw_dyn_contact' => $wps_etmfw_dyn_contact,
			'wps_etmfw_dyn_date' => $wps_etmfw_dyn_date,
			'wps_etmfw_dyn_address' => $wps_etmfw_dyn_address,
			'wps_is_pro_active' => $wps_is_pro_active,
			'wps_dyn_name' => __( ' Name', 'event-tickets-manager-for-woocommerce' ),
			'wps_dyn_mail' => __( ' Email', 'event-tickets-manager-for-woocommerce' ),
			'wps_dyn_contact' => __( ' Contact', 'event-tickets-manager-for-woocommerce' ),
			'wps_dyn_date' => __( ' Date', 'event-tickets-manager-for-woocommerce' ),
			'wps_dyn_address' => __( ' Address', 'event-tickets-manager-for-woocommerce' ),
			'wps_event_product_url' => $wps_event_product_url,
			'wps_event_purchase_limit' => $wps_limit_user_purchase_event,
			'wps_etmfw_set_limit_qty' => $etmfw_set_limit_qty,
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
		$post = get_post();
		if ( '' !== $checkin_page_id ) {
			if ( $post && property_exists( $post, 'post_content' ) && has_shortcode( $post->post_content, 'wps_etmfw_event_checkin_page' ) ) {
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
				'wps_etmfw_event_dashboard_color' => get_option( 'wps_etmfw_event_dashboard_color' ),
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
						$etmfw_event_venue_lat = isset( $wps_etmfw_product_array['etmfw_event_venue_lat'] ) ? $wps_etmfw_product_array['etmfw_event_venue_lat'] : '';
						$etmfw_event_venue_lng = isset( $wps_etmfw_product_array['etmfw_event_venue_lng'] ) ? $wps_etmfw_product_array['etmfw_event_venue_lng'] : '';
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

					$allow_qty = false;
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

		$wps_etmfw_enable = get_option( 'wps_etmfw_enable_plugin', false );
		if ( $wps_etmfw_enable ) {
			$product_types = wp_get_object_terms( $product_id, 'product_type' );
			if ( isset( $product_types[0] ) ) {
				$product_type = $product_types[0]->slug;
				if ( 'event_ticket_manager' == $product_type ) {
					if ( ! isset( $_POST['wps_etwmfw_atc_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wps_etwmfw_atc_nonce'] ) ), 'wps_etwmfw_atc_nonce' ) ) {
						return;
					}

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
			if ( isset( $existing_item_meta['product_meta']['meta_data'] ) ) {
				foreach ( $existing_item_meta['product_meta']['meta_data'] as $key => $val ) {
					if ( 'wps_etmfw_field_info' == $key ) {
						if ( ! empty( $val ) ) {
							$info_array = $this->wps_etmfw_generate_key_value_pair( $val );
							foreach ( $info_array as $info_key => $info_value ) {
								if ( 'Event Venue' == $info_key ) {
									$item_meta[] = array(
										'name'  => esc_html__( 'Event Venue', 'event-tickets-manager-for-woocommerce' ),
										'value' => stripslashes( $info_value ),
									);
								} else {
									$item_meta[] = array(
										'name'  => esc_html( $info_key ),
										'value' => stripslashes( $info_value ),
									);
								}
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

		// Get the Details For the Dynamic Form Start Here.
		$wps_etmfw_product_array = get_post_meta( get_the_ID(), 'wps_etmfw_product_array', true );

		$wps_etmfw_dyn_name = isset( $wps_etmfw_product_array['wps_etmfw_dyn_name'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_name'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_name'] : '';
		$wps_etmfw_dyn_mail = isset( $wps_etmfw_product_array['wps_etmfw_dyn_mail'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_mail'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_mail'] : '';
		$wps_etmfw_dyn_contact = isset( $wps_etmfw_product_array['wps_etmfw_dyn_contact'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_contact'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_contact'] : '';
		$wps_etmfw_dyn_date = isset( $wps_etmfw_product_array['wps_etmfw_dyn_date'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_date'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_date'] : '';
		$wps_etmfw_dyn_address = isset( $wps_etmfw_product_array['wps_etmfw_dyn_address'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_dyn_address'] ) ? $wps_etmfw_product_array['wps_etmfw_dyn_address'] : '';

		$field_array = array();
		$label = '';
		$discard_keys = array( 'wps_etmfw_event_start', 'wps_etmfw_event_finish' );
		foreach ( $field_post as $key => $value ) {
			if ( strpos( $key, 'wps_etmfw_' ) !== false && ! in_array( $key, $discard_keys ) ) {
				if ( '' == $wps_etmfw_dyn_name && '' == $wps_etmfw_dyn_mail && '' == $wps_etmfw_dyn_contact && '' == $wps_etmfw_dyn_date && '' == $wps_etmfw_dyn_address ) {
					$key = ucwords( str_replace( '_', ' ', substr( $key, 10 ) ) );
					$field_array[ $key ] = $value;
				} else {
					$key = ucwords( str_replace( '_', ' ', substr( $key, 10 ) ) );
					$wps_modified_string1 = substr( $key, 5 );
					$field_array[ $wps_modified_string1 ] = $value;
				}
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
			if ( isset( $values['product_meta'] ) ) {
				foreach ( $values['product_meta']['meta_data'] as $key => $val ) {
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
	 * This function is used to make the meta keys translatable
	 *
	 * @name wps_etmfw_woocommerce_order_item_display_meta_key
	 * @param string $display_key show display key.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_etmfw_woocommerce_order_item_display_meta_key( $display_key ) {
		if ( 'Event Venue' == $display_key ) {
			$display_key = __( 'Event Venue', 'event-tickets-manager-for-woocommerce' );
		}
		return $display_key;
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
				if ( $temp_status == $new_status ) {
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
			if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {

				if ( isset( $product ) ) {
					$item_quantity = wc_get_order_item_meta( $item_id, '_qty', true );

					$product_id = $product->get_id();
					$item_meta_data = $item->get_meta_data();

					$wps_etmfw_mail_template_data = array(
						'product_id' => $product_id,
						'item_id'   => $item_id,
						'item'      => $item,
						'order_id'   => $order_id,
						'product_name' => $product->get_name(),
					);
					foreach ( $item_meta_data as $key => $value ) {
						if ( isset( $value->key ) && ! empty( $value->value ) ) {
							$wps_etmfw_mail_template_data[ $value->key ] = $value->value;
						}
					}

					if ( 1 < $item_quantity ) {
						if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
							// HPOS usage is enabled.
							$ticket_number = $order->get_meta( "event_ticket#$order_id#$item_id", true );
						} else {
							$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true ); // ticket code.
						}
						$wps_etmfw_mail_template_data['ticket_number'] = $ticket_number;

						$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';
						$generated_ticket_pdf = $upload_dir_path . '/events' . $order_id . $ticket_number . '.pdf';

						if ( empty( $ticket_number ) ) {
							$ticket_number = array(); // store the code for quantity more than 1.

							for ( $i = 0; $i < $item_quantity; $i++ ) {
								$temp = wps_etmfw_ticket_generator();
								$ticket_number[ $i ] = $temp;
								$j = $i + 1;
								$wps_ticket_content = $this->wps_etmfw_get_html_content( $item_meta_data, $order, $order_id, $temp, $product_id, $j ); // need to change on this line for dynamics details.
								$this->wps_etmfw_generate_ticket_pdf( $wps_ticket_content, $order, $order_id, $temp );
							}

							if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
								// HPOS usage is enabled.
								$order->update_meta_data( "event_ticket#$order_id#$item_id", $ticket_number );
								$order->save();
							} else {
								update_post_meta( $order_id, "event_ticket#$order_id#$item_id", $ticket_number );
							}
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
										'user'    => $order->get_customer_id(),
										'event_quantity' => $item_quantity,
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
										'user'    => $order->get_customer_id(),
										'event_quantity' => $item_quantity,
									);
								}
								update_post_meta( $product_id, 'wps_etmfw_generated_tickets', $generated_tickets );
							}
						}
					} else {
						if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
							// HPOS usage is enabled.
							$ticket_number = $order->get_meta( "event_ticket#$order_id#$item_id", true );
						} else {
							$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
						}
						$wps_etmfw_mail_template_data['ticket_number'] = $ticket_number;

						$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';
						$generated_ticket_pdf = $upload_dir_path . '/events' . $order_id . $ticket_number . '.pdf';

						if ( empty( $ticket_number ) ) {
							$ticket_number = wps_etmfw_ticket_generator();

							if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
								// HPOS usage is enabled.
								$order->update_meta_data( "event_ticket#$order_id#$item_id", $ticket_number );
								$order->save();
							} else {
								update_post_meta( $order_id, "event_ticket#$order_id#$item_id", $ticket_number );
							}

							$wps_ticket_content = $this->wps_etmfw_get_html_content( $item_meta_data, $order, $order_id, $ticket_number, $product_id, $j = 1 ); // tickt pdf html.
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
										'user'    => $order->get_customer_id(),
										'event_quantity' => $item_quantity,
									);
									update_post_meta( $product_id, 'wps_etmfw_generated_tickets', $generated_tickets );
								} else {
									$generated_tickets[] = array(
										'ticket' => $ticket_number,
										'status' => 'pending',
										'order_id' => $order_id,
										'item_id' => $item_id,
										'email'   => $billing_email,
										'user' => $order->get_customer_id(),
										'event_quantity' => $item_quantity,
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
		$ticket_number = $wps_etmfw_mail_template_data['ticket_number'];
		$order_id = $wps_etmfw_mail_template_data['order_id'];
		$item = $wps_etmfw_mail_template_data['item'];
		$product_id = $wps_etmfw_mail_template_data['product_id'];
		$item_meta_data = $item->get_meta_data();
		$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';
		if ( is_array( $ticket_number ) && ! empty( $ticket_number ) ) {
			$j = 1;
			foreach ( $ticket_number as $key => $value ) {
				$generated_ticket_pdf = $upload_dir_path . '/events' . $order_id . $value . '.pdf';
				if ( ! file_exists( $generated_ticket_pdf ) ) {
					$wps_ticket_content = $this->wps_etmfw_get_html_content( $item_meta_data, $order, $order_id, $value, $product_id, $j++ ); // tickt pdf html.
					$this->wps_etmfw_generate_ticket_pdf( $wps_ticket_content, $order, $order_id, $value );
				}
				$attachments[] = $generated_ticket_pdf;
			}
		} else {
			$generated_ticket_pdf = $upload_dir_path . '/events' . $order_id . $ticket_number . '.pdf';
			if ( ! file_exists( $generated_ticket_pdf ) ) {
				$wps_ticket_content = $this->wps_etmfw_get_html_content( $item_meta_data, $order, $order_id, $ticket_number, $product_id, $j = 1 ); // tickt pdf html.
				$this->wps_etmfw_generate_ticket_pdf( $wps_ticket_content, $order, $order_id, $ticket_number );
			}
			$attachments[] = $generated_ticket_pdf;
		}
		$wps_etmfw_email_subject = str_replace( '[SITENAME]', get_bloginfo(), $wps_etmfw_email_subject );
		$email_status = $mailer_obj->trigger( $user_email, $wps_etmfw_email_discription, $wps_etmfw_email_subject, $order, $attachments );
		do_action( 'wps_etmfw_send_sms_ticket', $wps_etmfw_mail_template_data );
		do_action( 'wps_etmfw_send_whatsapp_msg', $wps_etmfw_mail_template_data );
		do_action( 'wps_etmfw_send_gmeet_invitation', $wps_etmfw_mail_template_data );
		do_action( 'wps_etmfw_send_zoom_invitation', $wps_etmfw_mail_template_data );
	}

	/**
	 * Send Ticket Mail when event  is shared.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_send_ticket_mail()
	 * @param object $order Order.
	 * @param array  $wps_etmfw_mail_template_data Mail Template data.
	 * @param string $user_email Mail of receiver.
	 * @param array  $attachments Mail Pdf Attachment.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_send_ticket_mail_shared( $order, $wps_etmfw_mail_template_data, $user_email, $attachments ) {

		$mailer_obj = WC()->mailer()->emails['wps_etmfw_email_notification'];
		$wps_etmfw_email_discription = $this->wps_etmfw_generate_ticket_info_in_mail( $wps_etmfw_mail_template_data );
		$wps_etmfw_email_subject = get_option( 'wps_etmfw_email_subject', '' );
		if ( '' === $wps_etmfw_email_subject ) {
			$wps_etmfw_email_subject = 'Your ticket is here.';
		}
		$wps_etmfw_email_subject = str_replace( '[SITENAME]', get_bloginfo(), $wps_etmfw_email_subject );
		$email_status = $mailer_obj->trigger( $user_email, $wps_etmfw_email_discription, $wps_etmfw_email_subject, $order, $attachments );
		do_action( 'wps_etmfw_send_sms_ticket', $wps_etmfw_mail_template_data );
		do_action( 'wps_etmfw_send_whatsapp_msg', $wps_etmfw_mail_template_data );
		do_action( 'wps_etmfw_send_gmeet_invitation', $wps_etmfw_mail_template_data );
		do_action( 'wps_etmfw_send_zoom_invitation', $wps_etmfw_mail_template_data );
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
			$venue = isset( $wps_etmfw_mail_template_data['Event Venue'] ) ? $wps_etmfw_mail_template_data['Event Venue'] : $wps_etmfw_product_array['etmfw_event_venue'];
			$product = wc_get_product( $product_id );
			$image = wp_get_attachment_image_url( $product->get_image_id() );
			if ( '' == $image ) {
				$image = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/placeholder.jpg';
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
	 * Generate html content for ticket pdf.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_get_html_content()
	 * @param array  $item_meta_data Item meta data.
	 * @param object $order Order.
	 * @param int    $order_id Order Id.
	 * @param string $ticket_number Ticket Number.
	 * @param int    $product_id Product Id.
	 * @param int    $j Count.
	 * @return string $wps_ticket_details Ticket Details.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_etmfw_get_html_content( $item_meta_data, $order, $order_id, $ticket_number, $product_id, $j = 1 ) {
		$venue = '';
		if ( ! empty( $item_meta_data ) ) {
			foreach ( $item_meta_data as $key => $value ) {
				if ( isset( $value->key ) && ! empty( $value->value ) ) {
					if ( 'Event Venue' == $value->key ) {
						$venue = $value->value;
					}
				}
			}
		}
		ob_start();
		$wps_is_qr_is_enable = false;
		$wps_set_the_pdf_ticket_template = get_option( 'wps_etmfw_ticket_template' );

		if ( '1' == $wps_set_the_pdf_ticket_template ) {
			include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/wps-etmfw-mail-html-content.php'; // Zenith.
		}

		$ticket_number1 = '';
		if ( 'on' == get_option( 'wps_etmfwp_include_barcode' ) ) {
			$wps_etmfw_qr_size = 100;
			$file = $this->wps_etmfwp_generate_bar_code_callback( $order_id, $ticket_number, $product_id );
		} elseif ( 'on' == get_option( 'wps_etmfwp_include_qr' ) ) {
			$wps_etmfw_qr_size = ! empty( get_option( 'wps_etmfw_qr_size' ) ) ? get_option( 'wps_etmfw_qr_size' ) : '180';
			$file = apply_filters( 'wps_etmfw_generate_qr_code', $order_id, $ticket_number, $product_id );
		}

		$ticket_url = '';

		if ( ! empty( $file ) ) {

			$ticket_url = get_site_url() . '/' . str_replace( ABSPATH, '', $file );
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
			include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/wps-etmfw-mail-html-content-1.php'; // Elixir.
		} elseif ( '3' == $wps_set_the_pdf_ticket_template ) {
			include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/wps-etmfw-mail-html-content-2.php'; // Demure.
		} elseif ( '4' == $wps_set_the_pdf_ticket_template ) {
			include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/wps-etmfw-mail-html-content-3.php'; // Mellifluous.
		} elseif ( '5' == $wps_set_the_pdf_ticket_template ) {
			include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/wps-etmfw-mail-html-content-4.php'; // Vertico.
		} elseif ( '6' == $wps_set_the_pdf_ticket_template ) {
			include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/wps-etmfw-mail-html-content-5.php'; // Nexus.
		} elseif ( '7' == $wps_set_the_pdf_ticket_template ) {
			include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/wps-etmfw-mail-html-content-6.php'; // Eclipse.
		} elseif ( '8' == $wps_set_the_pdf_ticket_template ) {
			include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/wps-etmfw-mail-html-content-7.php'; // Fusion.
		}

		if ( is_plugin_active( 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php' ) ) {
			$wps_etmfw_text_color = ! empty( get_option( 'wps_etmfw_pdf_text_color' ) ) ? get_option( 'wps_etmfw_pdf_text_color' ) : '#ffffff';
		} else {
			if ( '1' == $wps_set_the_pdf_ticket_template ) {
				$wps_etmfw_text_color = ! empty( get_option( 'wps_etmfw_ticket_body_text_color', '' ) ) ? get_option( 'wps_etmfw_ticket_body_text_color', '' ) : '#f5ebeb';
			} elseif ( '5' == $wps_set_the_pdf_ticket_template ) {
				$wps_etmfw_text_color = ! empty( get_option( 'wps_etmfw_ticket_text_color', '' ) ) ? get_option( 'wps_etmfw_ticket_text_color', '' ) : '#f5ebeb';
			}
		}

		$wps_ticket_details = ob_get_contents();
		ob_end_clean();
		$additinal_info = '';
		$product = wc_get_product( $product_id );
		$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
		$start = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
		$end = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
		if ( empty( $venue ) ) {
			$venue = isset( $wps_etmfw_product_array['etmfw_event_venue'] ) ? $wps_etmfw_product_array['etmfw_event_venue'] : '';
		}
		$wps_etmfw_stock_status = get_post_meta( $product_id, '_manage_stock', true );

		if ( '6' == $wps_set_the_pdf_ticket_template || '7' == $wps_set_the_pdf_ticket_template ) {

			// Additional Info Details.
			if ( ! empty( $item_meta_data ) && (
					( 'yes' === $wps_etmfw_stock_status && 1 < count( $item_meta_data ) ) ||
					( 'no' === $wps_etmfw_stock_status && 0 < count( $item_meta_data ) ) )
			) {
				$additinal_info = "<h4 style='color:#000;font-weight:bold;font-size:18px;margin:0 0 10px;letter-spacing:0.5px;line-height:1;'>"
								. esc_html__( 'Details:', 'event-tickets-manager-for-woocommerce' )
								. "</h4><p style='color:#000;font-size:14px;margin:0 0 2px;letter-spacing:0.5px;border-bottom:1px solid #FFC525;padding:5px 0;'>";

				foreach ( $item_meta_data as $key => $value ) {
					if ( isset( $value->key ) && ! empty( $value->value ) && ( ! ctype_digit( substr( $value->key, -1 ) ) || substr( $value->key, -1 ) == $j ) ) {
						if ( '_reduced_stock' === $value->key ) {
							continue;
						}
						$additinal_info .= '<span style="margin:0 10px 0 0;">';
						$additinal_info .= '<strong>' . esc_html( $value->key ) . ':</strong> ' . esc_html( $value->value ) . '</span>';
					}
				}
				$additinal_info .= '</p>';
			}
		} elseif ( '8' == $wps_set_the_pdf_ticket_template ) {

			// Additional Info Details.
			if ( ! empty( $item_meta_data ) && (
					( 'yes' === $wps_etmfw_stock_status && 1 < count( $item_meta_data ) ) ||
					( 'no' === $wps_etmfw_stock_status && 0 < count( $item_meta_data ) ) )
			) {
				$additinal_info = '<table border="0" cellspacing="0" cellpadding="0" style="table-layout: auto; width: 100%;"><tbody>'
								. '<tr><td style="padding: 20px 0 10px;"><h2 style="margin: 0;font-size: 24px;color:black;">Details :-</h2></td></tr>';

				foreach ( $item_meta_data as $key => $value ) {
					if ( isset( $value->key ) && ! empty( $value->value ) && ( ! ctype_digit( substr( $value->key, -1 ) ) || substr( $value->key, -1 ) == $j ) ) {
						if ( '_reduced_stock' === $value->key ) {
							continue;
						}
						$additinal_info .= '<tr><td style="padding: 5px 0;"><p style="margin: 0;color : black">'
										. esc_html( $value->key ) . ' - ' . esc_html( $value->value ) . '</p></td></tr>';
					}
				}
				$additinal_info .= '</tbody></table>';
			}
		} elseif ( empty( $wps_set_the_pdf_ticket_template ) || ( '6' !== $wps_set_the_pdf_ticket_template && '7' !== $wps_set_the_pdf_ticket_template && '8' !== $wps_set_the_pdf_ticket_template ) ) {

			// Additional Info Details.
			if ( ! empty( $item_meta_data ) && (
					( 'yes' === $wps_etmfw_stock_status && 1 < count( $item_meta_data ) ) ||
					( 'no' === $wps_etmfw_stock_status && 0 < count( $item_meta_data ) ) )
			) {
				$wps_etmfw_text_color = ! empty( get_option( 'wps_etmfw_pdf_text_color' ) )
										? get_option( 'wps_etmfw_pdf_text_color' )
										: '#120505';

				$additinal_info = '<table border="0" cellspacing="0" cellpadding="0" style="table-layout: auto; width: 100%;"><tbody>'
								. '<tr><td style="padding: 20px 0 10px;"><h2 style="margin: 0;font-size: 24px; color:'
								. esc_attr( $wps_etmfw_text_color ) . ';">Details :-</h2></td></tr>';

				foreach ( $item_meta_data as $key => $value ) {
					if ( isset( $value->key ) && ! empty( $value->value ) && ( ! ctype_digit( substr( $value->key, -1 ) ) || substr( $value->key, -1 ) == $j ) ) {
						if ( '_reduced_stock' === $value->key ) {
							continue;
						}
						$additinal_info .= '<tr><td style="padding: 5px 0;"><p style="margin: 0;color : '
										. esc_attr( $wps_etmfw_text_color ) . '">'
										. esc_html( $value->key ) . ' - ' . esc_html( $value->value ) . '</p></td></tr>';
					}
				}
				$additinal_info .= '</tbody></table>';
			}
		} else {
			// Fallback in case no conditions are met, to avoid undefined variable.
			$additinal_info = '';
		}

		$wps_etmfw_logo_size = ! empty( get_option( 'wps_etmfw_logo_size', true ) ) ? get_option( 'wps_etmfw_logo_size', true ) : '180';

		// Get the product image URL.
		$product_image_url = '';
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
		if ( 'on' == get_option( 'wps_etmfw_prod_logo_plugin' ) ) {
			$product_image_url = ( is_array( $image ) && isset( $image[0] ) ) ? $image[0] : '';
		} else {
			$product_image_url = ! empty( get_option( 'wps_etmfw_mail_setting_upload_logo' ) ) ? get_option( 'wps_etmfw_mail_setting_upload_logo' ) : '';
		}

		$site_logo = '<img id="wps_wem_logo_id" class="wps_wem_logo" src="' . $product_image_url . '" style="width:' . $wps_etmfw_logo_size . 'px;margin-left: 25px">';
		$wps_ticket_details = str_replace( '[EVENTNAME]', $product->get_name(), $wps_ticket_details );

		// Create a DateTime object from the input date.
		$wps_start_date = strtotime( $start );
		$wps_end_date = strtotime( $end );

		// Format the date into the desired output format.
		$wps_start_output_date = gmdate( 'F j, Y | h:ia', $wps_start_date );
		$wps_end_output_date   = gmdate( 'F j, Y | h:ia', $wps_end_date );

		$wps_ticket_details = str_replace( '[TICKET]', $ticket_number, $wps_ticket_details );
		$wps_ticket_details = str_replace( '[TICKET1]', $ticket_number1, $wps_ticket_details );
		$wps_ticket_details = str_replace( '[VENUE]', $venue, $wps_ticket_details );
		$wps_ticket_details = str_replace( '[STARTDATE]', wps_etmfw_get_date_format( $start ), $wps_ticket_details );
		$wps_ticket_details = str_replace( '[ENDDATE]', wps_etmfw_get_date_format( $end ), $wps_ticket_details );
		$wps_ticket_details = str_replace( '[EMAILBODYCONTENT]', get_option( 'wps_etmfw_email_body_content', '' ), $wps_ticket_details );
		$wps_ticket_details = str_replace( '[SITENAME]', get_bloginfo(), $wps_ticket_details );
		$wps_ticket_details = str_replace( '[ADDITIONALINFO]', $additinal_info, $wps_ticket_details );
		$wps_ticket_details = str_replace( '[LOGO]', $site_logo, $wps_ticket_details );
		$wps_ticket_details = str_replace( '[TICKET_URL]', $ticket_url, $wps_ticket_details );

		// New Start Code.
		$wps_ticket_details = str_replace( '[NEW_START_DATE]', $wps_start_output_date, $wps_ticket_details );
		$wps_ticket_details = str_replace( '[NEW_END_DATE]', $wps_end_output_date, $wps_ticket_details );

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
		$wps_set_the_pdf_ticket_template = get_option( 'wps_etmfw_ticket_template', '1' );
		if ( '5' == $wps_set_the_pdf_ticket_template ) {
			$dompdf->setPaper( 'A4' );
		} else {
			$dompdf->setPaper( 'A4', 'landscape' );
		}
		$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';

		if ( ! is_dir( $upload_dir_path ) ) {
			wp_mkdir_p( $upload_dir_path );
			chmod( $upload_dir_path, 0755 );
		}

		$dompdf->loadHtml( $wps_ticket_content );
		@ob_end_clean(); // phpcs:ignore.
		$dompdf->render();
		$dompdf->set_option( 'isRemoteEnabled', true );
		$dompdf->setPaper( 'A4', 'landscape' );
		$output = $dompdf->output();

		$generated_ticket_pdf = $upload_dir_path . '/events' . $order_id . $ticket_number . '.pdf';
		$file = fopen( $generated_ticket_pdf, 'w' );
		fwrite( $file, $output );
		fclose( $file );

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
					if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
						// HPOS usage is enabled.
						$ticket_number = $order->get_meta( "event_ticket#$order_id#$item_id", true );
					} else {
						$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
					}
					if ( is_array( $ticket_number ) ) {

						foreach ( $ticket_number as $key => $value ) {
							if ( '' !== $value && 'event_ticket_manager' == $product_type ) {

								if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
									// HPOS usage is enabled.
									$updated_meta_pdf = $order->get_meta( 'wps_etmfw_order_meta_updated', true );
								} else {
									$updated_meta_pdf = get_post_meta( $order_id, 'wps_etmfw_order_meta_updated', true );
								}

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
									$wps_etmfw_flag = false;
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
													<input type="submit" class="button button-primary" name="wps_etmfw_save_edit_ticket_info_btn" id="wps_etmfw_save_edit_ticket_info_btn" value="<?php esc_attr_e( 'Save Changes', 'event-tickets-manager-for-woocommerce' ); ?>" />
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
							if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
								// HPOS usage is enabled.
								$updated_meta_pdf = $order->get_meta( 'wps_etmfw_order_meta_updated', true );
							} else {
								$updated_meta_pdf = get_post_meta( $order_id, 'wps_etmfw_order_meta_updated', true );
							}

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
								$wps_etmfw_flag = false;
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
												<input type="submit" class="button button-primary" name="wps_etmfw_save_edit_ticket_info_btn" id="wps_etmfw_save_edit_ticket_info_btn" value="<?php esc_attr_e( 'Save Changes', 'event-tickets-manager-for-woocommerce' ); ?>" />
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
		echo wp_json_encode( $response );
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
			if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
				if ( count( $posted_value ) > 0 ) {

					foreach ( $posted_value as $key => $value ) {
						$key = ucwords( str_replace( '_', ' ', $key ) );
						$item->update_meta_data( $key, $value );
						$item->save();
						$response['result'] = true;

						if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
							// HPOS usage is enabled.
							$order->update_meta_data( 'wps_etmfw_order_meta_updated', 'yes' );
							$order->save();
						} else {
							update_post_meta( $order_id, 'wps_etmfw_order_meta_updated', 'yes' );
						}

						$product_id = $product->get_id();
						$item_meta_data = $item->get_meta_data();

						if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
							// HPOS usage is enabled.
							$ticket_number = $order->get_meta( "event_ticket#$order_id#$item_id", true );
						} else {
							$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
						}

						if ( is_array( $ticket_number ) && ! empty( $ticket_number ) ) {

							$j = 1;
							foreach ( $ticket_number as $key => $value ) {

								$wps_ticket_content = $this->wps_etmfw_get_html_content( $item_meta_data, $order, $order_id, $value, $product_id, $j++ );
								$this->wps_etmfw_generate_ticket_pdf( $wps_ticket_content, $order, $order_id, $value );
							}
						} else {

							$wps_ticket_content = $this->wps_etmfw_get_html_content( $item_meta_data, $order, $order_id, $ticket_number, $product_id, $j = 1 );
							$this->wps_etmfw_generate_ticket_pdf( $wps_ticket_content, $order, $order_id, $ticket_number );
						}
					}
				}
			}
		}
		echo wp_json_encode( $response );
		wp_die();
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
	 * Unset COD payment gateway for cod.
	 *
	 * @since 1.0.0
	 * @name restrict_cod_for_specific_product_types().
	 * @param array $available_gateways Available payment gateways.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function restrict_cod_for_specific_product_types( $available_gateways ) {
		if ( is_admin() ) {
			return $available_gateways;
		}

		// Define the product types for which COD should be restricted.
		$restricted_product_types = array( 'event_ticket_manager' );

		// Check the cart for products of the restricted types.
		$whole_cart = WC()->cart;
		if ( isset( $whole_cart ) && ! empty( $whole_cart ) ) {
			foreach ( $whole_cart->get_cart() as $cart_item ) {
				$product = wc_get_product( $cart_item['product_id'] );
				if ( in_array( $product->get_type(), $restricted_product_types ) ) {
					unset( $available_gateways['cod'] );
					break;
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
			if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
				$order->update_meta_data( 'wps_order_type', 'event' );
				$order->save();
				break;
			}
		}
	}

	/**
	 * This is function is used to share the tickets in Mail.
	 *
	 * @name wps_etmfwp_sharing_tickets.
	 * @link http://www.wpswings.com/
	 */
	public function wps_etmfwp_sharing_tickets_org() {
		$secure_nonce      = wp_create_nonce( 'wps-event-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-event-auth-nonce' );
		if ( ! $id_nonce_verified ) {
			wp_die( esc_html__( 'Nonce Not verified', 'event-tickets-manager-for-woocommerce' ) );
		}
		$response['result'] = false;
		$product_id = isset( $_REQUEST['for_event'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['for_event'] ) ) : '';
		$ticket_num = isset( $_REQUEST['ticket_num'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ticket_num'] ) ) : '';
		$user_email = isset( $_REQUEST['user_email'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['user_email'] ) ) : '';

		$wps_etmfw_in_processing = get_option( 'wps_wet_enable_after_payment_done_ticket', false );
		if ( 'on' == $wps_etmfw_in_processing ) {
			$order_statuses = array( 'wc-completed', 'wc-processing' );
		} else {
			$order_statuses = array( 'wc-completed' );
		}

		$customer_user_id = get_current_user_id();

		$customer_orders = new WC_Order_Query(
			array(
				'customer_id' => $customer_user_id,
				'status' => $order_statuses,
				'return' => 'ids',
				'limit' => -1,
			)
		);

		$generated_tickets = get_post_meta( $product_id, 'wps_etmfw_generated_tickets', true );
		$user_id = get_current_user_id();

		if ( ! empty( $generated_tickets ) ) {

			foreach ( $generated_tickets as $key => $value ) {
				if ( $ticket_num == $value['ticket'] ) {
					$response['order_id'] = $value['order_id'];
					$current_ticket_order_id = $value['order_id'];
					$item_id = $value['item_id'];
					if ( isset( $value['transfer_id'] ) ) {
						$wps_is_tranfered = true;
					} else {
						$wps_is_tranfered = false;
					}
					$wps_assignee_mail = $value['email'];
				}
			}

			$order_id = array();
			$order_id = $customer_orders->get_orders();

			if ( in_array( $current_ticket_order_id, $order_id ) && ( false == $wps_is_tranfered ) && ( $user_email != $wps_assignee_mail ) ) {
				$post = get_post( $current_ticket_order_id );
				if ( 'trash' !== $post->post_status ) {
					foreach ( $generated_tickets as $key => $value ) {
						if ( $ticket_num == $value['ticket'] ) {
							$generated_tickets[ $key ]['email'] = $user_email;
							$transfer_id = $value['order_id'];
							$generated_tickets[ $key ]['transfer_id'] = $transfer_id;
							$order = wc_get_order( $transfer_id );
							$billing_email = $order->get_billing_email();
							$wps_etmfw_mail_template_data = array();
							update_post_meta( $product_id, 'wps_etmfw_generated_tickets', $generated_tickets );
							session_start();
							$_SESSION['order_id'] = $value['order_id'];
							$_SESSION['ticket_no'] = $ticket_num;

							$wps_etmfw_mail_template_data = array(
								'product_id' => $product_id,
								'order_id'   => $transfer_id,
								'product_name' => get_the_title( $product_id ),

							);
							$item_meta_data = array();
							$item = $order->get_item( $item_id );
							$item_meta_data = $item->get_meta_data();
							$ticket_number = wps_etmfw_ticket_generator();
							$wps_ticket_content = $this->wps_etmfw_get_html_content( $item_meta_data, $order, $transfer_id, $ticket_num, $product_id, $j = 1 );
							$this->wps_etmfw_generate_ticket_pdf( $wps_ticket_content, $order, $transfer_id, $ticket_num );

							$wps_etmfw_mail_template_data['ticket_number'] = $ticket_num;
							$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';
							$attachments = $upload_dir_path . '/events' . $value['order_id'] . $ticket_num . '.pdf';
							$this->wps_etmfw_send_ticket_mail_shared( $order, $wps_etmfw_mail_template_data, $user_email, $attachments );
						}
					}
					$response['message'] = __( 'Ticket Transfer successfully.', 'event-tickets-manager-for-woocommerce' );
					$_SESSION['wps_Check_point'] = 1;

					// here send code will added.
					$response['result'] = true;
				} else {
					$response['message'] = __( 'Order not exist.', 'event-tickets-manager-for-woocommerce' );
				}
			} else {
				$response['message'] = __( 'Wrong Ticket Number / Not Yours Ticket / Cannot transfer to yourself.', 'event-tickets-manager-for-woocommerce' );
			}
		} else {
			$response['message'] = __( 'Ticket of Event is not yet purchase.', 'event-tickets-manager-for-woocommerce' );
		}
		echo wp_json_encode( $response );
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
		$wps_etmfw_in_processing = get_option( 'wps_wet_enable_after_payment_done_ticket', false );

		if ( ( 'completed' == $order_status && '' == $wps_etmfw_in_processing ) || ( 'processing' == $order_status && 'on' == $wps_etmfw_in_processing ) ) {
			$wps_etmfw_is_product = false;
			foreach ( $order->get_items() as $item_id => $item ) {
				$_product = apply_filters( 'woocommerce_order_item_product', $product = $item->get_product(), $item );

				if ( isset( $_product ) && ! empty( $_product ) ) {
					$product_id = $_product->get_id();
				}

				if ( isset( $product_id ) && ! empty( $product_id ) ) {

					$product_types = wp_get_object_terms( $product_id, 'product_type' );
					if ( is_array( $product_types ) ) {
						$product_type = '';
						if ( isset( $product_types[0] ) ) {
							$product_type = $product_types[0]->slug;
						}

						if ( 'event_ticket_manager' == $product_type ) {
							$wps_etmfw_is_product = true;
						}
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
						<strong><?php esc_html_e( 'Resend Ticket PDF Email', 'event-tickets-manager-for-woocommerce' ); ?></strong>
					</h4>
					<div id="wps_etmfw_loader" style="display: none;">
						<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ); ?>public/src/image/loading.gif">
					</div>
					<span class="wps_resend_content"><?php esc_html_e( "Press the icon to resend the ticket PDF email if the recipient hasn't received the ticket you sent.", 'event-tickets-manager-for-woocommerce' ); ?>
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
			$response = __( 'Ticket PDF Sent Successfully!', 'event-tickets-manager-for-woocommerce' );
		} else {
			$response = __( 'Ticket PDF Not Sent!', 'event-tickets-manager-for-woocommerce' );
		}
		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * Function for disabling the shipping on cart.
	 */
	public function wp_shortcode_init_callback() {
		add_shortcode( 'wps_my_all_event_list', array( $this, 'wps_event_listing_shortcode_callback' ) );
	}


	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @param array $wps_atts is an id of the product.
	 * @since    1.0.0
	 */
	public function wps_event_listing_shortcode_callback( $wps_atts ) {

		$wps_html = '';

		// Extract attributes and set defaults.
		$wps_atts = shortcode_atts(
			array(
				'category' => '', // Default category is empty.
			),
			$wps_atts
		);
		$wps_html = $this->wps_custom_html_part_callback( '' );
		return $wps_html;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @param array $content is an id of the product.
	 * @since    1.0.0
	 */
	public function wps_custom_html_part_callback( $content = '' ) {
		ob_start();
		require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . '/public/partials/event-all-listing-public-display.php';
		?>
		<?php
		return ob_get_clean();
	}


	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function wps_default_filter_product_search_callback() {
		check_ajax_referer( 'wps-etmfw-verify-public-nonce', 'wps_nonce' );
		$search_term = isset( $_POST['search_term'] ) ? sanitize_text_field( wp_unslash( $_POST['search_term'] ) ) : '';

		// Get the current page from AJAX request, default to page 1.
		$paged = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$posts_per_page = 12;
	
		// Get total number of products.
		$total_products = count( wc_get_products( array(
			'limit'    => -1,
			'status'   => 'publish',
			'type'     => 'event_ticket_manager',
			's'        => $search_term,
			'sentence' => true,
			'return'   => 'ids',
		) ) );
	
		$total_pages = ceil( $total_products / $posts_per_page );

		$args = array(
			'post_type' => 'product',
			'status'    => array( 'publish' ),
			'type'      => 'event_ticket_manager',
			's'         => $search_term,
			'sentence'  => true,
			'limit'     => $posts_per_page,
			'page'      => $paged,
		);

		$html = '';
		$product_query = new WC_Product_Query( $args );
		$products = $product_query->get_products();
		$events = array();

		if ( ! empty( $products ) ) {
			foreach ( $products as $product ) {
				$product_id = $product->get_id();
				$product_name = $product->get_name();
				$product_price = $product->get_price();
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
				$product_url = get_permalink( $product_id );
				$wps_product_image_src = ( is_array( $image ) && isset( $image[0] ) ) ? $image[0] : EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/placeholder.jpg';
				$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );

				$event_start_date_time = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
				$wps_event_start_date_time = strtotime( $event_start_date_time );

				$events[] = array(
					'product' => $product,
					'start_date' => $wps_event_start_date_time,
					'image_src' => $wps_product_image_src,
					'event_data' => $wps_etmfw_product_array,
				);
			}

			// Sort the events by start date.
			usort(
				$events,
				function( $a, $b ) {
					return $a['start_date'] <=> $b['start_date'];
				}
			);

			foreach ( $events as $event ) {
				$product = $event['product'];
				$product_id = $product->get_id();
				$product_name = $product->get_name();
				$product_price = $product->get_price();
				$product_url = get_permalink( $product_id );
				$wps_product_image_src = $event['image_src'];
				$wps_event_start_date_time = $event['start_date'];
				$wps_etmfw_product_array = $event['event_data'];

				// Format the date.
				$wps_event_formated_start_date_time = gmdate( 'F j, Y | h:ia', $wps_event_start_date_time );
				$wps_event_formated_start_day       = gmdate( 'l', $wps_event_start_date_time );
				$wps_event_formated_start_date      = gmdate( 'j', $wps_event_start_date_time );
				$wps_event_formated_start_month     = gmdate( 'F', $wps_event_start_date_time ); 

				$html .= '<a href="' . esc_url( $product_url ) . '" class="button btn" id="wps-etmw_list-card">
					<div class="wps-etmw_single-event">
						<img src="' . esc_url( $wps_product_image_src ) . '" />
						<div class="wps-etmw_prod-desc">
							<h4>' . esc_html( $product_name ) . '</h4>
							<div class="wps-etmw_prod-venue">
								<img src="' . esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . 'public/src/image/map_pin.svg" alt="venue" class="venue">' . esc_html( $wps_etmfw_product_array['etmfw_event_venue'] ) . '
							</div>
							<div class="wps-etmw_all-date">
								<span class="wps-etmw_start-time">
									<img src="' . esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . 'public/src/image/calendar.svg" alt="date" class="date">
									' . esc_html( $wps_event_formated_start_date_time ) . ' ' . esc_html__( 'Onwards', 'event-tickets-manager-for-woocommerce' ) . '
								</span>
							</div>
							<div class="wps-etmw_prod-price">' . wc_price( $product_price ) . '</div>
						</div>
						<div class="wps-etmw_prod-date">
							<div class="wps-etmw_prod-date-in">
								<span class="wps-etmw_start-time-day">' . esc_html( substr( $wps_event_formated_start_day, 0, 3 ) ) . '</span>
								<span class="wps-etmw_start-time-date">' . esc_html( $wps_event_formated_start_date ) . '</span>
							</div>
							<span class="wps-etmw_start-time-month">' . esc_html( $wps_event_formated_start_month ) . '</span>
						</div>
					</div>
				</a>';
			}
	
			$html .= '<nav class="wps_woocommerce-pagination"><ul class="page-numbers">';

			if ( $paged > 1 ) {
				$html .= '<li><a class="prev page-numbers" data-page="' . ( $paged - 1 ) . '"><img src="' . esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/left.svg' ) . '"></a></li>';
			}

			for ( $i = 1; $i <= $total_pages; $i++ ) {
				if ( 
					$i <= 2 || 
					$i > $total_pages - 2 
				) {
					if ( $i == $paged ) {
						$html .= '<li><span class="page-numbers current">' . $i . '</span></li>';
					} else {
						$html .= '<li><a class="page-numbers" data-page="' . $i . '">' . $i . '</a></li>';
					}
				}
				elseif ( $i == $paged || $i == $paged + 1 ) {
					if ( $i == $paged ) {
						$html .= '<li><span class="page-numbers current">' . $i . '</span></li>';
					} else {
						$html .= '<li><a class="page-numbers" data-page="' . $i . '">' . $i . '</a></li>';
					}
				}
				elseif ( 3 == $i && $paged > 3 ) {
					$html .= '<li class="page-numbers_static">...</li>';
				}
				elseif ( $i == $total_pages - 2 && $paged < $total_pages - 3 ) {
					$html .= '<li class="page-numbers_static">...</li>';
				}
			}

			if ( $paged < $total_pages ) {
				$html .= '<li><a class="next page-numbers" data-page="' . ( $paged + 1 ) . '"><img src="' . esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/right.svg' ) . '"></a></li>';
			}

			$html .= '</ul></nav>';
			echo wp_kses_post( $html );

		} else {
			echo 'No Event Products Found.';
		}

		wp_die();
	}	

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function wps_select_event_listing_type_callback() {
		check_ajax_referer( 'wps-etmfw-verify-public-nonce', 'wps_nonce' );
		$wps_selected_value = isset( $_POST['wps_selected_value'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_selected_value'] ) ) : '';
		echo esc_attr( $wps_selected_value );
		wp_die();
	}


	/**
	 * Function to generate qr code.
	 *
	 * @param int    $order_id is the id of order.
	 * @param string $ticket_number is ticket number.
	 * @param int    $product_id is the id of product.
	 * @return string
	 */
	public function wps_etmfwp_generate_bar_code_callback( $order_id, $ticket_number, $product_id ) {
		require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'vendor-barcode/autoload.php';

		$order = wc_get_order( $order_id );

		$billing_email = $order->get_billing_email();

		// $site_url is link in the barcode.
		$site_url = get_site_url() . '/event-check-in-using-qr?action=checkin&id=' . esc_html( $product_id ) . '&ticket=' . esc_html( $ticket_number ) . '&email=' . esc_html( $billing_email ) . '';
		$uploads = wp_upload_dir();
		$path = $uploads['basedir'] . '/images/';
		$file  = $path . $order_id . $ticket_number . 'checkin.png';  // address of the image od barcode in which  url is saved.

		// Initialize WP_Filesystem.
		if ( function_exists( 'WP_Filesystem' ) ) {
			WP_Filesystem();

			global $wp_filesystem;

			// Check if WP_Filesystem initialization was successful.
			if ( is_wp_error( $wp_filesystem ) ) {
				// Failed to initialize WP_Filesystem, handle the error.
				// For example, log the error or display a message to the user.
				echo 'Failed to initialize the WordPress Filesystem.';
			} else {
				// Check if file exists and delete it.
				if ( $wp_filesystem->exists( $file ) ) {
					$wp_filesystem->delete( $file );
				}

				// Create the directory if it doesn't exist.
				if ( ! $wp_filesystem->is_dir( $path ) ) {
					$wp_filesystem->mkdir( $path, 0777 );
				}

				// Recreate the file path.
				$file = $path . $order_id . $ticket_number . 'checkin.png'; // path  of the image.

				$wps_etmfw_barcode_color = ! empty( get_option( 'wps_etmfw_pdf_barcode_color' ) ) ? get_option( 'wps_etmfw_pdf_barcode_color' ) : 'black';

				$barcode = new \Com\Tecnick\Barcode\Barcode();
				$bobj = $barcode->getBarcodeObj( 'C128B', "{$ticket_number}", 450, 70, $wps_etmfw_barcode_color, array( 0, 0, 0, 0 ) );

				$wps_image_data = $bobj->getPngData();

				// Write data to the file.
				$wp_filesystem->put_contents( $file, $wps_image_data, FS_CHMOD_FILE );

				return $file;
			}
		}

	}

	/**
	 * This function is used to disable shipping.
	 *
	 * @param object $enable shipping Object.
	 * @name wps_etmfw_wc_shipping_enabled
	 * @since 1.0.2
	 */
	public function wps_etmfw_wc_shipping_enabled( $enable ) {
		$wps_etmfw_enable_plugin = get_option( 'wps_etmfw_enable_plugin', false );

		if ( ( CartCheckoutUtils::is_cart_block_default() || CartCheckoutUtils::is_checkout_block_default() ) && 'on' == $wps_etmfw_enable_plugin ) {
			global $woocommerce;
			$event_bool = false;
			$other_bool = false;
			if ( $enable ) {
				if ( isset( WC()->cart ) && ! empty( WC()->cart ) ) {

					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

						$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
						$product_types = wp_get_object_terms( $product_id, 'product_type' );
						if ( isset( $product_types[0] ) ) {
							$product_type = $product_types[0]->slug;
							if ( 'event_ticket_manager' == $product_type ) {
								$event_bool  = true;
							} else if ( ! $cart_item['data']->is_virtual() ) {
								$other_bool = true;
							}
						}
					}

					if ( $event_bool && ! $other_bool ) {
						$enable = false;
					} else {
						$enable = true;
					}
				}
			}
		} else {
			if ( ( is_cart() || is_checkout() ) && 'on' == $wps_etmfw_enable_plugin ) {
				global $woocommerce;
				$event_bool = false;
				$other_bool = false;
				if ( $enable ) {
					if ( isset( WC()->cart ) && ! empty( WC()->cart ) ) {

						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

							$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
							$product_types = wp_get_object_terms( $product_id, 'product_type' );
							if ( isset( $product_types[0] ) ) {
								$product_type = $product_types[0]->slug;
								if ( 'event_ticket_manager' == $product_type ) {
									$event_bool  = true;
								} else if ( ! $cart_item['data']->is_virtual() ) {
									$other_bool = true;
								}
							}
						}

						if ( $event_bool && ! $other_bool ) {
							$enable = false;
						} else {
							$enable = true;
						}
					}
				}
			}
		}
		return $enable;
	}

	/**
	 * This function is used to set User Role to see Points Tab in MY ACCOUNT Page.
	 *
	 * @name wps_wpr_points_dashboard
	 * @since    1.0.0
	 * @link https://www.wpswings.com/
	 * @param array $items array of the items.
	 */
	public function etmfwp_event_dashboard( $items ) {
		$items['event-ticket'] = __( 'Event Tickets', 'event-tickets-manager-for-woocommerce' );
		return $items;
	}

	/**
	 * This function is show the Html on tab on my account.
	 *
	 * @name wps_wpr_account_event.
	 * @since    1.0.0
	 * @link https://www.wpswings.com/
	 */
	public function wps_wpr_account_event() {
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
		?>
		<!-- New Layout For Event Start -->
		<div class="wps-etmfw_modern-dashboard" id="wps-etmfw_modern-dashboard">
			<div class="wps-etmfw_md-in">
				<section class="wps-etmfw_mdi-sec wps-etmfw_mdis-head">
					<h2><?php esc_html_e( 'Events Dashboard', 'event-tickets-manager-for-woocommerce' ); ?></h2>
					<p><?php echo get_option( 'wps_etmfw_event_dashboard' ); ?></p>
				</section>
				<section class="wps-etmfw_mdi-sec wps-etmfw_mdis-main">
					<article class="wps-etmfw_mdis-art wps-etmfw_mdis-nav">
						<span class="wps-etmfw_mdisan-item wps-etmfw_mdisant-events wps-etmfw_mdisan-item--active">
							<?php esc_html_e( 'All Events', 'event-tickets-manager-for-woocommerce' ); ?>
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M19.6 3.20001H4.2C2.98497 3.20001 2 4.18499 2 5.40001V20.8C2 22.015 2.98497 23 4.2 23H19.6C20.815 23 21.8 22.015 21.8 20.8V5.40001C21.8 4.18499 20.815 3.20001 19.6 3.20001Z" stroke="#4BB543" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M16.3 1V5.4" stroke="#4BB543" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M7.5 1V5.4" stroke="#4BB543" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M2 9.79999H21.8" stroke="#4BB543" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
						<?php
						if ( 'on' === get_option( 'wps_wet_enable_ticket_sharing' ) && 'on' === get_option( 'wps_etmfw_enable_plugin', false ) ) {
						?>
							<span class="wps-etmfw_mdisan-item wps-etmfw_mdisant-trans">
								<?php esc_html_e( 'Transfer', 'event-tickets-manager-for-woocommerce' ); ?>
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M23 16.2778V20.5556C23 21.2039 22.7425 21.8256 22.284 22.284C21.8256 22.7425 21.2039 23 20.5556 23H3.44444C2.79614 23 2.17438 22.7425 1.71596 22.284C1.25754 21.8256 1 21.2039 1 20.5556V3.44444C1 2.79614 1.25754 2.17438 1.71596 1.71596C2.17438 1.25754 2.79614 1 3.44444 1H8.94444" stroke="#4BB543" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M10.7777 13.2222L21.7777 2.22223" stroke="#4BB543" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M15.6665 1H22.9998V8.33333" stroke="#4BB543" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
							<?php
						}
						?>
					</article>
					<article class="wps-etmfw_mdis-art wps-etmfw_mdisa-cont">
						<div class="wps-etmfw_mdisa-item wps-etmfw_mdisa-trans">
							<div class="wps-etmfw_mdisai-cont">
								<div class="wps_etmfw_checkin_wrapper">
									<div id="wps_etmfw_error_message"></div>
									<form method="post">
										<div class="wps_etmfw_events_section">
											<label><?php esc_html_e( 'For', 'event-tickets-manager-for-woocommerce' ); ?></label>
											<select id="wps_etmfw_event_selected"> 
												<option value="#" ><?php echo esc_html( 'Select Event', 'event-tickets-manager-for-woocommerce' ); ?>  </option> 
											<?php
											if ( $product_array->have_posts() ) {
												if ( isset( $product_array->posts ) && ! empty( $product_array->posts ) ) {
													
													foreach ( $product_array->posts as $event_per_product ) {
														?>
													<option value="<?php echo esc_attr( $event_per_product->ID ); ?>" ><?php echo esc_html( $event_per_product->post_title ); ?>  </option> 
														<?php
													}
												}
											}
											?>
											</select> 
												<?php
											?>
										</div>
										<div class="wps_etmfw_input_ticket_section">
											<label><?php esc_html_e( 'Ticket Number *', 'event-tickets-manager-for-woocommerce' ); ?></label>
											<input type="text" name="wps_etmfw_imput_ticket" id="wps_etmfw_imput_ticket">
										</div>
										<div class="wps_etmfw_input_ticket_section">
											<label><?php esc_html_e( 'Enter Email *', 'event-tickets-manager-for-woocommerce' ); ?></label>
											<input type="email" name="wps_etmfw_chckin_email" id="wps_etmfw_chckin_email">
										</div>


										<div class="wps_etmfw--loader-btn-wrapper">
											<div class="wps_etmfw_checkin_button_section">
												<input type="submit" name="wps_etmfw_checkin_button" id="wps_etmfwp_event_transfer_button" value="Transfer">
											</div>
											<div class="wps_etmfw_loader" id="wps_etmfw_checkin_loader">
												<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/loading.gif' ); // phpcs:ignore. ?>">
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<?php
						$event_attendees_details = array();
						$customer = wp_get_current_user(); // do this when user is logged in.

						$args = array(
							'status' => array( 'wc-processing', 'wc-completed' ),
							'return' => 'ids',
							'customer_id' => get_current_user_id(),
						);
						$shop_orders = wc_get_orders( $args );

						$user_orders = array();

						foreach ( $shop_orders as $order_id ) {
							$order_obj = wc_get_order( $order_id );

							foreach ( $order_obj->get_items() as $item_id => $item ) {
								$product = $item->get_product();
								$orderme = $order_obj->get_id();
								if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
									if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
										// HPOS usage is enabled.
										$ticket = $order_obj->get_meta( "event_ticket#$orderme#$item_id", true );
									} else {
										$ticket = get_post_meta( $order_obj->get_id(), "event_ticket#$orderme#$item_id", true );
									}
									if ( is_array( $ticket ) && ! empty( $ticket ) ) {
										$length = count( $ticket );
										for ( $i = 0;$i < $length; $i++ ) {

											if ( ! empty( $product ) ) {
												$pro_id = $product->get_id();
											}
											$wps_etmfw_product_array = get_post_meta( $pro_id, 'wps_etmfw_product_array', true );
											$wps_etmfw_product_array = get_post_meta( $pro_id, 'wps_etmfw_product_array', true );
											$start = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
											$end = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
											$venue = isset( $wps_etmfw_product_array['etmfw_event_venue'] ) ? $wps_etmfw_product_array['etmfw_event_venue'] : '';
											$order_date = $order_obj->get_date_created()->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
											$user_id = ( 0 != $order_obj->get_user_id() ) ? '#' . $order_obj->get_user_id() : 'Guest';
											$checkin_status = '';
											$upload_dir_path = '';

											$generated_tickets = get_post_meta( $pro_id, 'wps_etmfw_generated_tickets', true );

											if ( ! empty( $generated_tickets ) ) {
												foreach ( $generated_tickets as $key => $value ) {
													if ( $ticket[ $i ] == $value['ticket'] ) {
														$checkin_status = $value['status'];
														if ( 'checked_in' === $checkin_status ) :
															$upload_dir_path  = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $value['order_id'] . $value['ticket'] . '.pdf';
															$checkin_status = '<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/checked.png" width="20" height="20" title="' . esc_html__( 'Checked-In', 'event-tickets-manager-for-woocommerce' ) . '">';
														else :
															$upload_dir_path  = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $value['order_id'] . $value['ticket'] . '.pdf';
															$checkin_status = '<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/pending.svg" width="20" height="20" title="' . esc_html__( 'Pending', 'event-tickets-manager-for-woocommerce' ) . '">';
														endif;
													}
												}
											}
											$order_received_url = wc_get_endpoint_url( 'order-received', $order_obj->get_id(), wc_get_checkout_url() );
											$order_received_url = add_query_arg( 'key', $order_obj->get_order_key(), $order_received_url );

											$event_attendees_details[] = array(
												'id'                => $order_obj->get_id(),
												'check_in_status'   => $checkin_status,
												'event'            => $item->get_name(),
												'ticket'            => $ticket,
												'price'              => $item->get_total(),
												'order'             => '<a title="Ticket Order Detail" href="' . esc_url( $order_received_url ) . '">' . esc_html__( 'View', 'event-tickets-manager-for-woocommerce' ) . '</a>',
												'user'              => $user_id,
												'venue'             => $venue,
												'purchase_date'     => $order_date,
												'schedule'          => wps_etmfw_get_date_format( $start ) . '-' . wps_etmfw_get_date_format( $end ),
												'action'            => '<a title="Download Ticket" href="' . $upload_dir_path . '" target="_blank"><img src="' . esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/file.svg' ) . '" alt="export"></a>',
											);


										}
									} else if ( '' !== $ticket ) {
										if ( ! empty( $product ) ) {
											$pro_id = $product->get_id();
										}
										$wps_etmfw_product_array = get_post_meta( $pro_id, 'wps_etmfw_product_array', true );
										$start = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
										$end = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
										$venue = isset( $wps_etmfw_product_array['etmfw_event_venue'] ) ? $wps_etmfw_product_array['etmfw_event_venue'] : '';
										$order_date = $order_obj->get_date_created()->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
										$user_id = ( 0 != $order_obj->get_user_id() ) ? '#' . $order_obj->get_user_id() : 'Guest';
										$checkin_status = '';
										$upload_dir_path = '';
										$generated_tickets = get_post_meta( $pro_id, 'wps_etmfw_generated_tickets', true );
										$orderme = $order_obj->get_id();

										if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
											// HPOS usage is enabled.
											$ticket = $order_obj->get_meta( "event_ticket#$orderme#$item_id", true );
										} else {
											$ticket = get_post_meta( $order_obj->get_id(), "event_ticket#$orderme#$item_id", true );
										}

										if ( ! empty( $generated_tickets ) ) {
											foreach ( $generated_tickets as $key => $value ) {
												if ( $ticket == $value['ticket'] ) {
													$checkin_status = $value['status'];

													if ( 'checked_in' === $checkin_status && $order_obj->get_id() == $value['order_id'] ) {
														$upload_dir_path  = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $value['order_id'] . $value['ticket'] . '.pdf';
														$checkin_status = '<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/checked.png" width="20" height="20" title="' . esc_html__( 'Checked-In', 'event-tickets-manager-for-woocommerce' ) . '">';
													} else {
														$upload_dir_path  = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $value['order_id'] . $value['ticket'] . '.pdf';
														$checkin_status = '<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/pending.svg" width="20" height="20" title="' . esc_html__( 'Pending', 'event-tickets-manager-for-woocommerce' ) . '">';
													}
												}
											}
										}

										$order_received_url = wc_get_endpoint_url( 'order-received', $order_obj->get_id(), wc_get_checkout_url() );
										$order_received_url = add_query_arg( 'key', $order_obj->get_order_key(), $order_received_url );

										$event_attendees_details[] = array(
											'id'                => $order_obj->get_id(),
											'check_in_status'   => $checkin_status,
											'event'             => $item->get_name(),
											'ticket'            => $ticket,
											'price'             => $item->get_total(),
											'order'             => '<a title="Ticket Order Detail" href="' . esc_url( $order_received_url ) . '">' . esc_html__( 'View', 'event-tickets-manager-for-woocommerce' ) . '</a>',
											'user'              => $user_id,
											'venue'             => $venue,
											'purchase_date'     => $order_date,
											'schedule'          => wps_etmfw_get_date_format( $start ) . '-' . wps_etmfw_get_date_format( $end ),
											'action'            => '<a title="Download Ticket" href="' . $upload_dir_path . '" target="_blank"><img src="' . esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/file.svg' ) . '" alt="export"></a>',
										);
									}
								}
							}
						}
						?>
						<div class="wps-etmfw_mdisa-item wps-etmfw_mdisa-events wps-etmfw_mdisa-item--active">
							<h3><?php esc_html_e( 'All Events', 'event-tickets-manager-for-woocommerce' ); ?> <span><?php echo count( $event_attendees_details ) . esc_html__( ' Events', 'event-tickets-manager-for-woocommerce' ); ?></span></h3>
							<div class="wps-etmfw_mdisai-cont">
								<!-- Dummy HTML Cloneed from All Events tab Start -->
								<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table" id="wps_myevent_table_id">
									<thead>
										<tr>
											<th class="woocommerce-orders-table__header"><?php echo esc_html__( 'Event', 'event-tickets-manager-for-woocommerce' ); ?></th>
											<th class="woocommerce-orders-table__header"><?php echo esc_html__( 'Event Date', 'event-tickets-manager-for-woocommerce' ); ?></th>
											<th class="woocommerce-orders-table__header status-th"><?php echo esc_html__( 'Event Status', 'event-tickets-manager-for-woocommerce' ); ?></th>
											<th class="woocommerce-orders-table__header"><?php echo esc_html__( 'Event Price', 'event-tickets-manager-for-woocommerce' ); ?></th>
											<th class="woocommerce-orders-table__header"><?php echo esc_html__( 'Action', 'event-tickets-manager-for-woocommerce' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
											if ( ! empty( $event_attendees_details ) && is_array( $event_attendees_details ) ) {
												foreach ( $event_attendees_details as $mks ) {
												?>
											<tr>
												<td data-title="Event"><?php echo rtrim( $mks['event'] ); ?></td>
												<td data-title="Date"><?php echo rtrim( $mks['schedule'] ); ?></td>
												<td data-title="Status" class="status-td"><span class="#" title="Pending"><?php echo rtrim( $mks['check_in_status'] ); ?></span></td>
												<td data-title="Price"><span class="woocommerce-Price-amount amount"><?php echo wc_price( rtrim( $mks['price'] ) ); ?></span></td>
												<td data-title="Action"><?php echo rtrim( $mks['order'] ) . rtrim( $mks['action'] ); ?></td>
											</tr>
											<?php
												}
											} else {
												?>
												<tr>
													<td colspan="5" style="text-align:center; display:table-cell;">
														<?php
														esc_html_e( 'No Event Ticket has been purchased yet.', 'event-tickets-manager-for-woocommerce' );
														?>
													</td>
												</tr>
												<?php
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</article>
				</section>
			<sv>
		</div>
		<!-- New Layout For Event End -->
		<?php
	}

	/**
	 * This function is used to construct Points Tab in MY ACCOUNT Page.
	 *
	 * @name wps_wpr_add_my_account_endpoint
	 * @since    1.0.0
	 * @link http://www.wpswings.com/
	 */
	public function etmfwp_add_my_account_endpoint() {
		add_rewrite_endpoint( 'event-ticket', EP_ROOT | EP_PAGES );
		flush_rewrite_rules();
	}

	/**
	 * This function is used to add endpoints on account page.
	 *
	 * @since 1.1.4
	 * @name wps_wpr_custom_endpoint_query_vars
	 * @param array $vars array.
	 * @link https://wpswings.com
	 */
	public function etmfwp_custom_endpoint_query_vars( $vars ) {
		$vars[] = 'event-ticket';
		return $vars;
	}

	/**
	 * This is function is used to set the ticket price on user type.
	 *
	 * @name wps_user_type_ajax_callbck.
	 * @link http://www.wpswings.com/
	 */
	public function wps_user_type_ajax_callbck() {
		check_ajax_referer( 'wps-etmfw-verify-public-nonce', 'wps_nonce' );
		$wps_product_id = isset( $_REQUEST['event_product_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['event_product_id'] ) ) : '';
		$wps_etmfw_product_array = get_post_meta( $wps_product_id, 'wps_etmfw_product_array', true );
		$wps_base_price_condition = isset( $wps_etmfw_product_array['wps_etmfw_field_user_type_price_data_baseprice'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_field_user_type_price_data_baseprice'] ) ? $wps_etmfw_product_array['wps_etmfw_field_user_type_price_data_baseprice'] : array();

		if ( 'base_price' == $wps_base_price_condition ) {
			$wps_total_price = get_option( 'wps_total_increased_value' );

		} elseif ( 'not_base_price' == $wps_base_price_condition ) {
			$wps_total_price = 0;
		}

		$product_price_on_user_select = isset( $_REQUEST['user_type_value_data'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['user_type_value_data'] ) ) : '';
		$wps_product_type_name = isset( $_REQUEST['user_type_name_data'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['user_type_name_data'] ) ) : '';
		$final_price = ( $product_price_on_user_select + $wps_total_price );
		$response = wc_price( $final_price );
		echo wp_json_encode( $response );
		update_option( 'wps_user_type_value', $product_price_on_user_select );
		update_option( 'wps_user_type_text', $wps_product_type_name );
		wp_die();
	}

	/**
	 * This is function is used to set the cart price on user type.
	 *
	 * @param object $cart is an object of cart.
	 * @return void
	 */
	public function wps_user_type_cart_total_price( $cart ) {
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			return;
		}
		$cart_data = $cart->get_cart();
		foreach ( $cart_data as $cart ) {
			if ( 'event_ticket_manager' === $cart['data']->get_type() && isset( $cart['event_role'] ) ) {
				$product = $cart['data'];
				if ( isset( $product ) && is_object( $product ) ) {
					$price_html        = $cart['data']->get_price();
					$custom_cart_data = $cart['event_role'];
					$product_id = $product->get_id();
					$etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );

					if ( ! empty( $etmfw_product_array ) && is_array( $etmfw_product_array ) && array_key_exists( 'wps_etmfw_field_user_type_price_data', $etmfw_product_array ) && array_key_exists( 'etmfw_event_price', $etmfw_product_array ) ) {
						$wps_etmfw_field_days_price_data = $etmfw_product_array['wps_etmfw_field_user_type_price_data'];
						if ( ! empty( $wps_etmfw_field_days_price_data ) && is_array( $wps_etmfw_field_days_price_data ) ) {
							$price_html = $custom_cart_data['price'];
						}
					}

					$wps_base_price_condition = isset( $etmfw_product_array['wps_etmfw_field_user_type_price_data_baseprice'] ) && ! empty( $etmfw_product_array['wps_etmfw_field_user_type_price_data_baseprice'] ) ? $etmfw_product_array['wps_etmfw_field_user_type_price_data_baseprice'] : array();

					if ( 'base_price' == $wps_base_price_condition ) {
						$wps_total_price = get_option( 'wps_total_increased_value' );
					} elseif ( 'not_base_price' == $wps_base_price_condition ) {
						$wps_total_price = 0;
					}

					$price_html = $price_html + $wps_total_price;
					$cart['data']->set_price( $price_html );
				}
			}
				delete_option( 'wps_user_type_value' );
				delete_option( 'wps_user_type_text' );
		}
	}

	/**
	 * This is function is used to set the mini cart  on user type.
	 *
	 * @param string $output is an output.
	 * @param object $cart_item is cart item object.
	 * @param string $cart_item_key is key.
	 * @return string
	 */
	public function wps_user_type_widget_mini_cart( $output, $cart_item, $cart_item_key ) {

		return sprintf( '<span class="quantity">%1$s &times; <span class="woocommerce-Price-amount amount">%1$s</span>', $cart_item['quantity'] );
	}

	/**
	 * This is function is used to add additional data in  cart  on user type.
	 *
	 * @param array $cart_item_data array containing cart items.
	 * @param int   $product_id product id of the added prouct.
	 * @param int   $variation_id variation product id.
	 * @return array
	 */
	public function wps_user_type_add_moredata_on_cart( $cart_item_data, $product_id, $variation_id ) {

		$engraving_text = get_option( 'wps_user_type_text' );
		$price = get_option( 'wps_user_type_value' );

		if ( empty( $engraving_text ) ) {
			return $cart_item_data;
		}
		if ( 'Select User Type' !== $engraving_text ) {
				$custom_data = array(
					'role' => $engraving_text,
					'price'   => $price,
				);

				$cart_item_data['event_role'] = $custom_data;
		}
		return $cart_item_data;
	}

	/**
	 * This is function is used to set the mini cart  on user type.
	 *
	 * @param array $item_data array containing other data.
	 * @param array $cart_item array containing cart items.
	 * @return array
	 */
	public function wps_user_type_add_metadataset_cart( $item_data, $cart_item ) {

		if ( isset( $cart_item['event_role'] ) && 'Select User Type' !== $cart_item['event_role']['role'] ) {
			$custom_cart_data = $cart_item['event_role'];
			$item_data[] = array(
				'key'     => __( 'User Type', 'event-tickets-manager-for-woocommerce' ),
				'value'   => wc_clean( $custom_cart_data['role'] ),
				'display' => '',
			);
		}
		return $item_data;
	}

	/**
	 * This is function is used to set data on checkout and order edit page on user type.
	 *
	 * @param object $item object containing the item details.
	 * @param string $cart_item_key string containing arbitrary key of cart items.
	 * @param array  $values array containing the values for the cart item key.
	 * @param object $order current order object.
	 * @return void
	 */
	public function wps_user_type_checkout_order_dataset( $item, $cart_item_key, $values, $order ) {
		$custom_values = $item->legacy_values;
		if ( isset( $custom_values['event_role'] ) && 'Select User Type' !== $custom_values['event_role']['role'] ) {
			$custom_cart_data = $custom_values['event_role'];
			$item->add_meta_data( __( 'User Type', 'event-tickets-manager-for-woocommerce' ), $custom_cart_data['role'] );
		}
	}
	
	/**
	 * Change event price.
	 *
	 * @param string $price_html is a price value.
	 * @param object $product is a object.
	 * @return string
	 */
	public function wps_etmfwp_change_event_price( $price_html, $product ) {
		if ( isset( $product ) && is_object( $product ) && is_product() ) {
			if ( $product->is_type( 'event_ticket_manager' ) ) {
				$product_id = $product->get_id();
				$etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
				$stock = $product->get_stock_quantity();
				$wps_days_price = 0;
				$wps_stock_price = 0;
				$orig_price = 0;

				if ( ! empty( $etmfw_product_array ) && is_array( $etmfw_product_array ) ) {

					if ( ! empty( $etmfw_product_array ) && is_array( $etmfw_product_array ) && array_key_exists( 'wps_etmfw_field_stock_price_data', $etmfw_product_array ) && array_key_exists( 'etmfw_event_price', $etmfw_product_array ) ) {
						$wps_etmfw_field_stock_price_data = $etmfw_product_array['wps_etmfw_field_stock_price_data'];
						$orig_price = $etmfw_product_array['etmfw_event_price'];
						if ( ! empty( $wps_etmfw_field_stock_price_data ) && is_array( $wps_etmfw_field_stock_price_data ) ) {
							foreach ( $wps_etmfw_field_stock_price_data as $key => $value ) {
								if ( 0 != $stock ) {

									if ( $stock == $value['label'] ) {
										$wps_stock_price = 0;

										if ( 'fixed' == $value['type'] ) {

											$wps_stock_price  = ( $wps_stock_price + $value['price'] );
										} else if ( 'percentage' == $value['type'] ) {
											$wps_stock_price = $wps_stock_price + ( ( $orig_price * $value['price'] ) / 100 );
										}
									}
								}
							}
						}
					}
					if ( ! empty( $etmfw_product_array ) && is_array( $etmfw_product_array ) && array_key_exists( 'wps_etmfw_field_days_price_data', $etmfw_product_array ) && array_key_exists( 'etmfw_event_price', $etmfw_product_array ) ) {
						$wps_etmfw_field_days_price_data = $etmfw_product_array['wps_etmfw_field_days_price_data'];
						$orig_price = $etmfw_product_array['etmfw_event_price'];
						$start_date = $etmfw_product_array['event_start_date_time'];
						$start_timestamp = strtotime( $start_date );
						$current_date_time = strtotime( gmdate( 'Y-m-d h:i ', time() ) );
						$diff = (int) $start_timestamp - $current_date_time;
						if ( ! empty( $wps_etmfw_field_days_price_data ) && is_array( $wps_etmfw_field_days_price_data ) ) {
							foreach ( $wps_etmfw_field_days_price_data as $key => $value ) {
								$no_of_days = $value['label'];
								$no_of_days_to_seconds = ( (int) $no_of_days + 1 ) * 86400;
								if ( 0 != $no_of_days ) {
									$wps_days_price = 0;
									if ( $diff <= $no_of_days_to_seconds ) {

										if ( 'fixed' == $value['type'] ) {

											$wps_days_price = $wps_days_price + $value['price'];
										} else if ( 'percentage' == $value['type'] ) {
											$wps_days_price = $wps_days_price + ( ( $orig_price * $value['price'] ) / 100 );
										}
									}
								}
							}
						}
					}
					$wps_total_price = (float) $wps_days_price + (float) $wps_stock_price + (float) $orig_price;
					update_option( 'wps_total_increased_value', $wps_total_price );
					$price_html = wc_price( (float) $wps_days_price + (float) $wps_stock_price + (float) $orig_price );
					return $price_html;
				} else {
					return $price_html;
				}
			} else {
				return $price_html;
			}
		} else {
			return $price_html;
		}

	}

	/**
	 * This is function is used to show social link on product page.
	 *
	 * @name wps_etmfwp_show_social_share_link.
	 * @link http://www.wpswings.com/
	 */
	public function wps_etmfwp_show_social_share_link() {
		global $product;
		if ( is_single() ) {
			if ( ! is_product() || ! $product instanceof WC_Product ) {
				return;
			}
	
			?>
			<div id="wps_etmfw_title_and_social_share_icon" class="wps_etmfw_title_and_social_share_icon">
				<?php
				$product_id = get_the_ID();
				$product_types = wp_get_object_terms( $product_id, 'product_type' );
		
				if ( isset( $product_types[0] ) && $product_types[0]->slug === 'event_ticket_manager' ) {
					$page_permalink = get_permalink( $product_id );
		
					echo '<div class="wps_etmfw_social_share_wrapper">';

					if ( 'on' === get_option( 'wps_etmfw_copy_to_clipboard' ) ) {
						echo '<button class="wps-etmfw-copy-event-url" onclick="wpsEtmfwCopyToClipboard(\'' . esc_js( $page_permalink ) . '\')">
						<img src="' . EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/copy.svg" alt="copy""></button>';
					}

					do_action( 'wps_etmfw_show_social_share_link', $page_permalink );

					echo '</div>';
				}
				?>
			</div>
			<?php
		}
	}
}
