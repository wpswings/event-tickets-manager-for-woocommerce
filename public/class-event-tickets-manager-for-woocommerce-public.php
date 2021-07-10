<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
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
 * @author     makewebbetter <webmaster@makewebbetter.com>
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
		$event_view = get_option( 'mwb_etmfw_event_view', 'list' );
		if ( 'calendar' === $event_view ) {
			wp_enqueue_style( 'mwb-etmfw-fullcalendar-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/fullcalendar/main.min.css', array(), time(), 'all' );
		}

		wp_enqueue_style( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/scss/event-tickets-manager-for-woocommerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function etmfw_public_enqueue_scripts() {
		$event_view = get_option( 'mwb_etmfw_event_view', 'list' );
		if ( 'calendar' === $event_view ) {
			wp_enqueue_script( 'mwb-etmfw-fullcalendar-js', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/fullcalendar/fullcalendar.min.js', array( 'jquery' ), $this->version, false );
			wp_register_script( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/js/event-tickets-manager-for-woocommerce-public.js', array( 'jquery', 'mwb-etmfw-fullcalendar-js' ), $this->version, false );
		} else {
			wp_register_script( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/js/event-tickets-manager-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
		}
		$public_param_data = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'event_view' => $event_view,
			'mwb_etmfw_public_nonce' => wp_create_nonce( 'mwb-etmfw-verify-public-nonce' ),
		);
		wp_localize_script( $this->plugin_name, 'etmfw_public_param', $public_param_data );
		wp_enqueue_script( $this->plugin_name );

		if ( is_product() ) {
			$mwb_etmfw_product_type = $this->mwb_etmfw_get_product_type();
			$mwb_etmfw_if_expired   = $this->mwb_etmfw_check_if_event_is_expired();
			$mwb_etmfw_show_map     = $this->mwb_etmfw_show_google_map_on_product_page();
			if ( 'event_ticket_manager' === $mwb_etmfw_product_type && ! $mwb_etmfw_if_expired && $mwb_etmfw_show_map ) {
				$mwb_google_api_key = get_option( 'mwb_etmfw_google_maps_api_key', '' );
				wp_register_script( 'mwb_etmfw_google_map', 'https://maps.googleapis.com/maps/api/js?&key=' . $mwb_google_api_key . '&callback=initMap&libraries=&v=weekly', array(), $this->version, true );
				wp_enqueue_script( 'mwb_etmfw_google_map' );
			}
		}

		global $wp_query;
		$checkin_page_id = get_option( 'event_checkin_page_created', '' );
		if ( '' !== $checkin_page_id ) {
			if ( isset( $wp_query->post ) && $wp_query->post->ID == $checkin_page_id ) {
				wp_register_script( $this->plugin_name . '-checkin-page', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/js/event-tickets-manager-for-woocommerce-checkin-page.js', array( 'jquery' ), $this->version, false );
				$param_data = array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'mwb_etmfw_nonce' => wp_create_nonce( 'mwb-etmfw-verify-checkin-nonce' ),
				);
				wp_localize_script( $this->plugin_name . '-checkin-page', 'etmfw_checkin_param', $param_data );
				wp_enqueue_script( $this->plugin_name . '-checkin-page' );
			}
		}
	}

	/**
	 * Create product html before add to cart button.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_before_add_to_cart_button_html()
	 * @param object $event_product  Event Project Object.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_before_add_to_cart_button_html( $event_product ) {
		global $product;
		if ( isset( $product ) && ! empty( $product ) ) {
			$mwb_etmfw_enable = get_option( 'mwb_etmfw_enable_plugin', false );
			if ( $mwb_etmfw_enable ) {
				$product_id = $product->get_id();
				if ( isset( $product_id ) && ! empty( $product_id ) ) {
					$product_types = wp_get_object_terms( $product_id, 'product_type' );
					$product_type = $product_types[0]->slug;
					if ( 'event_ticket_manager' === $product_type ) {
						$mwb_etmfw_product_array = get_post_meta( $product_id, 'mwb_etmfw_product_array', true );
						$start_date = isset( $mwb_etmfw_product_array['event_start_date_time'] ) ? $mwb_etmfw_product_array['event_start_date_time'] : '';
						$end_date = isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '';
						$event_venue = isset( $mwb_etmfw_product_array['etmfw_event_venue'] ) ? $mwb_etmfw_product_array['etmfw_event_venue'] : '';
						$event_field_array = isset( $mwb_etmfw_product_array['mwb_etmfw_field_data'] ) ? $mwb_etmfw_product_array['mwb_etmfw_field_data'] : '';
						?>
						<div class="mwb_etmfw_product_wrapper">								
							<div class="mwb_etmfw_event_info_section">
								<?php do_action( 'mwb_etmfw_before_event_general_info', $product_id ); ?>
								<input type="hidden" name="mwb_etmfw_event_start" value=<?php echo esc_html( $start_date ); ?>>
								<input type="hidden" name="mwb_etmfw_event_finish" value=<?php echo esc_html( $end_date ); ?>>
								<div id="mwb_etmwf_event_date" class="mwb_etmfw_event_general_info">
									<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/calendar_icone.svg' ); ?>" height="20px" width="20px">
									<span class="mwb_etmfw_date_label"><?php echo esc_html( mwb_etmfw_get_only_date_format( $start_date ), 'event-tickets-manager-for-woocommerce' ); ?><span><?php echo esc_html( ' - ' ); ?></span><?php echo esc_html( mwb_etmfw_get_only_date_format( $end_date ), 'event-tickets-manager-for-woocommerce' ); ?></span>
								</div>	
								<div id="mwb_etmwf_event_time" class="mwb_etmfw_event_general_info">
									<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/clock.svg' ); ?>" height="20px" width="20px">
									<span class="mwb_etmfw_date_label"><?php echo esc_html( mwb_etmfw_get_only_time_format( $start_date ), 'event-tickets-manager-for-woocommerce' ); ?><span><?php echo esc_html( ' - ' ); ?></span><?php echo esc_html( mwb_etmfw_get_only_time_format( $end_date ), 'event-tickets-manager-for-woocommerce' ); ?></span>
								</div>
								<div id="mwb_etmwf_event_venue" class="mwb_etmfw_event_general_info">
									<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/map_pin.svg' ); ?>" height="20px" width="20px">
									<span><?php echo esc_html( $event_venue, 'event-tickets-manager-for-woocommerce' ); ?></span>
									<input type="hidden" name="mwb_etmfw_event_venue" value=<?php echo esc_html( $event_venue ); ?>>	
								</div>
								<?php do_action( 'mwb_etmfw_before_event_general_info', $product_id ); ?>	
							</div>
							<?php
							$display_map = isset( $mwb_etmfw_product_array['etmfw_display_map'] ) ? $mwb_etmfw_product_array['etmfw_display_map'] : 'no';
							$location_site = get_option( 'mwb_etmfw_enabe_location_site', 'off' );
							$map_api_key = get_option( 'mwb_etmfw_google_maps_api_key', '' );
							if ( 'yes' === $display_map && 'on' === $location_site && '' !== $map_api_key ) {
								?>
								<div class="mwb_etmfw_event_map_wrapper">
									<?php
									$event_lat = isset( $mwb_etmfw_product_array['etmfw_event_venue_lat'] ) ? $mwb_etmfw_product_array['etmfw_event_venue_lat'] : '';
									$event_lng = isset( $mwb_etmfw_product_array['etmfw_event_venue_lng'] ) ? $mwb_etmfw_product_array['etmfw_event_venue_lng'] : '';
									?>
									<input type="hidden" id="etmfw_event_lat" value="<?php echo esc_attr( $event_lat ); ?>">
									<input type="hidden" id="etmfw_event_lng" value="<?php echo esc_attr( $event_lng ); ?>">
									<script>
									function initMap() {
										  let event_lat = parseInt( document.getElementById('etmfw_event_lat').value );
										let event_lng = parseInt( document.getElementById('etmfw_event_lng').value );
										const myLatLng = { lat: event_lat, lng: event_lng };
										  const map = new google.maps.Map(document.getElementById("mwb_etmfw_event_map"), {
											zoom: 4,
											center: myLatLng,
										  });
										  new google.maps.Marker({
											position: myLatLng,
											map,
											title: "Event!",
										});
									}
									</script>
									<div id="mwb_etmfw_event_map"></div>
								</div>
								<?php
							}
							?>
							<div class="mwb_etmfw_addition_info_section">
								<?php do_action( 'mwb_etmfw_before_more_info', $product_id ); ?>
								<?php $this->mwb_etmfw_generate_addional_fields( $product_id, $event_field_array ); ?>
								<?php do_action( 'mwb_etmfw_after_more_info', $product_id ); ?>
							</div>
						</div>
						<?php
					}
				}
			}
		}
	}

	/**
	 * Create additional fiields to get user basic information before add to cart button.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_generate_addional_fields()
	 * @param int   $product_id  Project Id.
	 * @param array $event_field_array  Html Field Array.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_generate_addional_fields( $product_id, $event_field_array ) {
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
				?>
				<p>
					<?php
					switch ( $value['type'] ) {
						case 'text':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb_etmfw_field_label"><?php echo esc_html( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
									<?php if ( $mandatory ) : ?>	
										<span class="mwb_etmfw_mandatory_fields">						
											<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
									<?php endif; ?>
								</label>
							</div>
							<div class="mwb-form-group__control">
								<input type="text" name="mwb_etmfw_<?php echo esc_attr( $field_label ); ?>" <?php echo esc_html( $required ); ?>>
							</div>
						</div>
						
							<?php
							break;

						case 'email':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb_etmfw_field_label"><?php echo esc_html( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
									<?php if ( $mandatory ) : ?>	
										<span class="mwb_etmfw_mandatory_fields">						
											<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
									<?php endif; ?>
								</label>
							</div>
							<div class="mwb-form-group__control">
								<input type="email" name="mwb_etmfw_<?php echo esc_attr( $field_label ); ?>" <?php echo esc_html( $required ); ?> >
							</div>
						</div>
							<?php
							break;

						case 'textarea':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb_etmfw_field_label"><?php echo esc_html( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
									<?php if ( $mandatory ) : ?>	
										<span class="mwb_etmfw_mandatory_fields">						
											<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
									<?php endif; ?>
								</label>
							</div>
							<div class="mwb-form-group__control">
								<textarea name="mwb_etmfw_<?php echo esc_attr( $field_label ); ?>" <?php echo esc_html( $required ); ?>></textarea>
							</div>
						</div>


							<?php
							break;

						case 'number':
							?>

						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb_etmfw_field_label"><?php echo esc_html( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
									<?php if ( $mandatory ) : ?>	
										<span class="mwb_etmfw_mandatory_fields">						
											<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
									<?php endif; ?>
								</label>
							</div>
							<div class="mwb-form-group__control">
								<input type="number" name="mwb_etmfw_<?php echo esc_attr( $field_label ); ?>" <?php echo esc_html( $required ); ?>>
							</div>
						</div>

							<?php
							break;

						case 'date':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb_etmfw_field_label"><?php echo esc_html( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
									<?php if ( $mandatory ) : ?>	
										<span class="mwb_etmfw_mandatory_fields">						
											<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
									<?php endif; ?>
								</label>
							</div>
							<div class="mwb-form-group__control">
								<input type="date" name="mwb_etmfw_<?php echo esc_attr( $field_label ); ?>" <?php echo esc_html( $required ); ?>>
							</div>
						</div>


							<?php
							break;

						case 'yes-no':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb_etmfw_field_label"><?php echo esc_html( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
									<?php if ( $mandatory ) : ?>	
										<span class="mwb_etmfw_mandatory_fields">						
											<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
									<?php endif; ?>
								</label>
							</div>
							<div class="mwb-form-group__control">
								<div>
									<input type="radio" id="mwb_etmfw_<?php echo esc_attr( $field_label ); ?>" name="mwb_etmfw_<?php echo esc_attr( $field_label ); ?>" value="yes" <?php echo esc_html( $required ); ?>>
									<label for="yes"><?php esc_html_e( 'Yes', 'event-tickets-manager-for-woocommerce' ); ?></label>
								</div>
								<div>
									<input type="radio" id="mwb_etmfw_<?php echo esc_attr( $field_label ); ?>" name="mwb_etmfw_<?php echo esc_attr( $field_label ); ?>" value="no">
									<label for="no"><?php esc_html_e( 'No', 'event-tickets-manager-for-woocommerce' ); ?></label>
								</div>
							</div>
						</div>
							<?php
							break;

						default:
							do_action( 'mwb_etmfw_after_input_fields', $value, $required );
							break;
					}
					do_action( 'mwb_etmfw_after_input_fields', $value );
					?>
				</p>
				<?php
			}
		}
	}

	/**
	 * Add only single product to cart.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_allow_single_quantity()
	 * @param boolean $allow_qty default false.
	 * @param object  $product Product Object.
	 * @return boolean $allow_qty default true.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_allow_single_quantity( $allow_qty, $product ) {
		if ( $product->is_type( 'event_ticket_manager' ) ) {
			$allow_qty = true;
		}
		return apply_filters( 'mwb_etmfw_increase_event_product_quantity', $allow_qty, $product );
	}

	/**
	 * Add item data to cart.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_cart_item_data()
	 * @param array $the_cart_data Hold cart content.
	 * @param int   $product_id Product Id.
	 * @param int   $variation_id Variation Id.
	 * @return array $the_cart_data Holds cart content.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_cart_item_data( $the_cart_data, $product_id, $variation_id ) {
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$mwb_etmfw_enable = get_option( 'mwb_etmfw_enable_plugin', false );
		if ( $mwb_etmfw_enable ) {
			$product_types = wp_get_object_terms( $product_id, 'product_type' );
			if ( isset( $product_types[0] ) ) {
				$product_type = $product_types[0]->slug;
				if ( 'event_ticket_manager' == $product_type ) {
					$cart_values = ! empty( $_POST ) ? map_deep( wp_unslash( $_POST ), 'sanitize_text_field' ) : array();
					foreach ( $cart_values as $key => $value ) {
						if ( false !== strpos( $key, 'mwb_etmfw_' ) && 'mwb_etmfw_single_nonce_field' !== $key ) {
							if ( isset( $key ) && ! empty( $value ) ) {
								$item_meta['mwb_etmfw_field_info'][ $key ] = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
							}
						}
					}
					$item_meta = apply_filters( 'mwb_etmfw_add_cart_item_data', $item_meta, $the_cart_data, $product_id, $variation_id );
					$the_cart_data['product_meta'] = array( 'meta_data' => $item_meta );
				}
			}
		}
		return $the_cart_data;
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}

	/**
	 * Add item data to cart.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_cart_item_data()
	 * @param array $item_meta holds cart item meta data.
	 * @param array $existing_item_meta Existing Item Meta.
	 * @return array $item_meta holds updated cart item meta data values.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_get_cart_item_data( $item_meta, $existing_item_meta ) {
		$mwb_etmfw_enable = get_option( 'mwb_etmfw_enable_plugin', false );
		if ( $mwb_etmfw_enable ) {
			if ( isset( $existing_item_meta ['product_meta']['meta_data'] ) ) {
				foreach ( $existing_item_meta['product_meta'] ['meta_data'] as $key => $val ) {
					if ( 'mwb_etmfw_field_info' == $key ) {
						if ( ! empty( $val ) ) {
							$info_array = $this->mwb_etmfw_generate_key_value_pair( $val );
							foreach ( $info_array as $info_key => $info_value ) {
								$item_meta [] = array(
									'name'  => esc_html( $info_key ),
									'value' => stripslashes( $info_value ),
								);
							}
						}
					}
					$item_meta = apply_filters( 'mwb_etmfw_get_item_meta', $item_meta, $key, $val );
				}
			}
		}
		return $item_meta;
	}

	/**
	 * Generate key value pair.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_generate_key_value_pair()
	 * @param array $field_post User Additional Info Values.
	 * @return array $field_post User Additional Info Values.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_generate_key_value_pair( $field_post ) {
		$field_array = array();
		$label = '';
		$discard_keys = array( 'mwb_etmfw_event_start', 'mwb_etmfw_event_finish', 'mwb_etmfw_event_venue' );
		foreach ( $field_post as $key => $value ) {
			if ( strpos( $key, 'mwb_etmfw_' ) !== false && ! in_array( $key, $discard_keys ) ) {
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
	 * @name mwb_etmfw_create_order_line_item().
	 * @param object $item Order Item.
	 * @param string $cart_key cart unique key.
	 * @param array  $values cart values.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_create_order_line_item( $item, $cart_key, $values ) {
		$mwb_etmfw_enable = get_option( 'mwb_etmfw_enable_plugin', false );
		if ( $mwb_etmfw_enable ) {
			if ( isset( $values ['product_meta'] ) ) {
				foreach ( $values ['product_meta'] ['meta_data'] as $key => $val ) {
					if ( 'mwb_etmfw_field_info' == $key && $val ) {
						$info_array = $this->mwb_etmfw_generate_key_value_pair( $val );
						foreach ( $info_array as $info_key => $info_value ) {
							$item->add_meta_data( $info_key, $info_value );
						}
						do_action( 'mwb_etmfw_checkout_create_order_line_item', $item, $key, $val );
					}
				}
			}
		}
	}

	/**
	 * Create event order on order status change.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_event_status_changed()
	 * @param string $order_id Order Id.
	 * @param string $old_status Old Status.
	 * @param string $new_status New Status.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_event_status_changed( $order_id, $old_status, $new_status ) {
		$mwb_etmfw_enable = get_option( 'mwb_etmfw_enable_plugin', false );
		if ( $mwb_etmfw_enable ) {
			if ( $old_status != $new_status ) {
				if ( 'completed' == $new_status ) {
					$this->mwb_etmfw_process_event_order( $order_id, $old_status, $new_status );
				}
			}
		}
	}


	/**
	 * Process order on order status change.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_process_event_order()
	 * @param string $order_id Order Id.
	 * @param string $old_status Old Status.
	 * @param string $new_status New Status.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_process_event_order( $order_id, $old_status, $new_status ) {
		$order = wc_get_order( $order_id );
		$mwb_etmfw_mail_template_data = array();
		foreach ( $order->get_items() as $item_id => $item ) {
			$product = $item->get_product();
			if ( isset( $product ) && $product->is_type( 'event_ticket_manager' ) ) {
				$item_quantity = wc_get_order_item_meta( $item_id, '_qty', true );
				$product_id = $product->get_id();
				$item_meta_data = $item->get_meta_data();
				$mwb_etmfw_mail_template_data = array(
					'product_id' => $product_id,
					'item_id'   => $item_id,
					'order_id'   => $order_id,
					'product_name' => $product->get_name(),
				);
				foreach ( $item_meta_data as $key => $value ) {
					if ( isset( $value->key ) && ! empty( $value->value ) ) {
						$mwb_etmfw_mail_template_data[ $value->key ] = $value->value;
					}
				}
				$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
				if ( '' === $ticket_number ) {
					$ticket_number = mwb_etmfw_ticket_generator();
					update_post_meta( $order_id, "event_ticket#$order_id#$item_id", $ticket_number );
					$mwb_ticket_content = $this->mwb_etmfw_get_html_content( $item_meta_data, $order, $order_id, $ticket_number, $product_id );
					$this->mwb_etmfw_generate_ticket_pdf( $mwb_ticket_content, $order, $order_id, $ticket_number );
				}

				if ( isset( $ticket_number ) ) {
					$mwb_etmfw_mail_template_data['ticket_number'] = $ticket_number;
					$generated_tickets = get_post_meta( $product_id, 'mwb_etmfw_generated_tickets', true );
					if ( empty( $generated_tickets ) ) {
						$generated_tickets = array();
						$generated_tickets[] = array(
							'ticket' => $ticket_number,
							'status' => 'pending',
							'order_id' => $order_id,
							'item_id' => $item_id,
						);
						update_post_meta( $product_id, 'mwb_etmfw_generated_tickets', $generated_tickets );
					} else {
						$generated_tickets[] = array(
							'ticket' => $ticket_number,
							'status' => 'pending',
							'order_id' => $order_id,
							'item_id' => $item_id,
						);
						update_post_meta( $product_id, 'mwb_etmfw_generated_tickets', $generated_tickets );
					}
				}

				$mwb_etmfw_mail_template_data = apply_filters( 'mwb_etmfw_common_arr_data', $mwb_etmfw_mail_template_data, $item );
				$this->mwb_etmfw_send_ticket_mail( $order, $mwb_etmfw_mail_template_data );
				do_action( 'mwb_etmfw_action_on_order_status_changed', $order_id, $old_status, $new_status );
			}
		}
	}

	/**
	 * Send Ticket Mail when event order is placed.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_send_ticket_mail()
	 * @param object $order Order.
	 * @param array  $mwb_etmfw_mail_template_data Mail Template data.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_send_ticket_mail( $order, $mwb_etmfw_mail_template_data ) {
		$user_email = $order->get_billing_email();
		$mailer_obj = WC()->mailer()->emails['mwb_etmfw_email_notification'];
		$mwb_etmfw_email_discription = $this->mwb_etmfw_generate_ticket_info_in_mail( $mwb_etmfw_mail_template_data );
		$mwb_etmfw_email_subject = get_option( 'mwb_etmfw_email_subject', '' );
		if ( '' === $mwb_etmfw_email_subject ) {
			$mwb_etmfw_email_subject = 'Your ticket is here.';
		}
		$mwb_etmfw_email_subject = str_replace( '[SITENAME]', get_bloginfo(), $mwb_etmfw_email_subject );
		$email_status = $mailer_obj->trigger( $user_email, $mwb_etmfw_email_discription, $mwb_etmfw_email_subject, $order );
		do_action( 'mwb_etmfw_send_sms_ticket', $mwb_etmfw_mail_template_data );
	}

	/**
	 * Send Ticket Mail when event order is placed.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_generate_ticket_info_in_mail()
	 * @param array $mwb_etmfw_mail_template_data Mail Template Data.
	 * @return array $template_html Template Html.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_generate_ticket_info_in_mail( $mwb_etmfw_mail_template_data ) {
		$product_id = null;
		if ( ! empty( $mwb_etmfw_mail_template_data ) ) {
			$order = wc_get_order( $mwb_etmfw_mail_template_data['order_id'] );
			$product_id = $mwb_etmfw_mail_template_data['product_id'];
			$mwb_etmfw_product_array = get_post_meta( $product_id, 'mwb_etmfw_product_array', true );
			$start = isset( $mwb_etmfw_product_array['event_start_date_time'] ) ? $mwb_etmfw_product_array['event_start_date_time'] : '';
			$end = isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '';
			$venue = isset( $mwb_etmfw_product_array['etmfw_event_venue'] ) ? $mwb_etmfw_product_array['etmfw_event_venue'] : '';
			$product = wc_get_product( $product_id );
			$image = wp_get_attachment_image_url( $product->get_image_id() );
			if ( '' == $image ) {
				$image = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/placeholder.png';
			}
		}
		$template_html = array();
		$template_html['event'] = $mwb_etmfw_mail_template_data['product_name'];
		$template_html['ticket'] = $mwb_etmfw_mail_template_data['ticket_number'];
		$template_html['purchaser'] = $order->get_billing_first_name();
		$template_html['venue'] = $venue;
		$template_html['time'] = mwb_etmfw_get_date_format( $start ) . '-' . mwb_etmfw_get_date_format( $end );
		$template_html['featuredimage'] = '<img src="' . $image . '" style="margin-right: 20px;" alt="image"/>';
		return apply_filters( 'mwb_etmfw_ticket_info', $template_html, $product_id );

	}

	/**
	 * Generate Pdf attachment for mail.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_attach_pdf_to_emails()
	 * @param array  $attachments Mail Attachments.
	 * @param string $email_id receiver's mail id.
	 * @param object $order Order Object.
	 * @param string $email email.
	 * @return array $attachments email attachments.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_attach_pdf_to_emails( $attachments, $email_id, $order, $email ) {

		if ( 'mwb_etmfw_email_notification' == $email_id ) {
			if ( is_a( $order, 'WC_Order' ) ) {
				$order_status  = $order->get_status();
				if ( 'completed' === $order_status ) {
					$order_id = $order->get_id();
					foreach ( $order->get_items() as $item_id => $item ) {
						$product = $item->get_product();
						if ( isset( $product ) && $product->is_type( 'event_ticket_manager' ) ) {
							$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
							$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';
							$generated_ticket_pdf = $upload_dir_path . '/events' . $order_id . $ticket_number . '.pdf';
							$attachments[] = $generated_ticket_pdf;
						}
					}
				}
			}
		}
		return $attachments;
	}


	/**
	 * Generate html content for ticket pdf.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_get_html_content()
	 * @param array  $item_meta_data Item meta data.
	 * @param object $order Order.
	 * @param int    $order_id Order Id.
	 * @param string $ticket_number Ticket Number.
	 * @param int    $product_id Product Id.
	 * @return string $mwb_ticket_details Ticket Details.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_get_html_content( $item_meta_data, $order, $order_id, $ticket_number, $product_id ) {
		ob_start();
		include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/mwb-etmfw-mail-html-content.php';
		$mwb_ticket_details = ob_get_contents();
		ob_end_clean();
		$additinal_info = '';
		$product = wc_get_product( $product_id );
		$mwb_etmfw_product_array = get_post_meta( $product_id, 'mwb_etmfw_product_array', true );
		$start = isset( $mwb_etmfw_product_array['event_start_date_time'] ) ? $mwb_etmfw_product_array['event_start_date_time'] : '';
		$end = isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '';
		$venue = isset( $mwb_etmfw_product_array['etmfw_event_venue'] ) ? $mwb_etmfw_product_array['etmfw_event_venue'] : '';
		$mwb_etmfw_stock_status = get_post_meta( $product_id, '_manage_stock', true );
		if ( ! empty( $item_meta_data ) && ( ( 'yes' === $mwb_etmfw_stock_status && 1 < count( $item_meta_data ) ) || ( 'no' === $mwb_etmfw_stock_status && 0 < count( $item_meta_data ) ) ) ) {
			$additinal_info = '<table border="0" cellspacing="0" cellpadding="0" style="table-layout: auto; width: 100%;"><tbody><tr><td style="padding: 20px 0 10px;"><h2 style="margin: 0;font-weight: bold;">Details :-</h2></td></tr>';
			foreach ( $item_meta_data as $key => $value ) {
				if ( isset( $value->key ) && ! empty( $value->value ) ) {
					if ( '_reduced_stock' === $value->key ) {
						continue;
					}
					$additinal_info .= '<tr><td style="padding: 10px 0;"><p style="margin: 0;">' . $value->key . ' - ' . $value->value . '</p></td></tr>';
				}
			}
			$additinal_info .= '</tbody></table>';
		}
		$site_logo = '<img src="' . get_option( 'mwb_etmfw_mail_setting_upload_logo', '' ) . '" style="width: 100%;">';
		$mwb_ticket_details = str_replace( '[EVENTNAME]', $product->get_name(), $mwb_ticket_details );
		$mwb_ticket_details = str_replace( '[TICKET]', $ticket_number, $mwb_ticket_details );
		$mwb_ticket_details = str_replace( '[VENUE]', $venue, $mwb_ticket_details );
		$mwb_ticket_details = str_replace( '[STARTDATE]', mwb_etmfw_get_date_format( $start ), $mwb_ticket_details );
		$mwb_ticket_details = str_replace( '[ENDDATE]', mwb_etmfw_get_date_format( $end ), $mwb_ticket_details );
		$mwb_ticket_details = str_replace( '[EMAILBODYCONTENT]', get_option( 'mwb_etmfw_email_body_content', '' ), $mwb_ticket_details );
		$mwb_ticket_details = str_replace( '[SITENAME]', get_bloginfo(), $mwb_ticket_details );
		$mwb_ticket_details = str_replace( '[ADDITIONALINFO]', $additinal_info, $mwb_ticket_details );
		$mwb_ticket_details = str_replace( '[LOGO]', $site_logo, $mwb_ticket_details );
		return apply_filters( 'mwb_etmfw_ticket_html_content', $mwb_ticket_details );
	}

	/**
	 * Function to generate pdf.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_generate_ticket_pdf()
	 * @param string $mwb_ticket_content Ticket content.
	 * @param object $order Order Object.
	 * @param int    $order_id Order Id.
	 * @param string $ticket_number Ticket Number.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_generate_ticket_pdf( $mwb_ticket_content, $order, $order_id, $ticket_number ) {
		require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'package/lib/dompdf/vendor/autoload.php';
		$dompdf = new Dompdf( array( 'enable_remote' => true ) );
		$dompdf->setPaper( 'A4', 'landscape' );
		$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';
		if ( ! is_dir( $upload_dir_path ) ) {
			wp_mkdir_p( $upload_dir_path );
			chmod( $upload_dir_path, 0775 );
		}
		$dompdf->loadHtml( $mwb_ticket_content );
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
	 * @name mwb_etmfw_view_ticket_button()
	 * @param string $item_id Item Id.
	 * @param object $item Item.
	 * @param object $order Order Object.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_view_ticket_button( $item_id, $item, $order ) {
		$order_id = $order->get_id();
		$order_status = $order->get_status();
		if ( 'completed' == $order_status ) {
			$_product = apply_filters( 'mwb_etmfw_woo_order_item_product', $product = $item->get_product(), $item );
			if ( isset( $_product ) && ! empty( $_product ) ) {
				$product_id = $_product->get_id();
			}
			if ( isset( $product_id ) && ! empty( $product_id ) ) {
				$product_types = wp_get_object_terms( $product_id, 'product_type' );
				if ( isset( $product_types[0] ) ) {
					$product_type = $product_types[0]->slug;
					$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
					if ( '' !== $ticket_number && 'event_ticket_manager' == $product_type ) {
						$updated_meta_pdf = get_post_meta( $order_id, 'mwb_etmfw_order_meta_updated', true );
						if ( '' === $updated_meta_pdf ) {
							$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket_number . '.pdf';
						} else {
							$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket_number . '-new.pdf';
						}
						$event_name = $_product->get_name();
						$mwb_etmfw_product_array = get_post_meta( $product_id, 'mwb_etmfw_product_array', true );
						$start_date = isset( $mwb_etmfw_product_array['event_start_date_time'] ) ? $mwb_etmfw_product_array['event_start_date_time'] : '';
						$end_date = isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '';
						$event_venue = isset( $mwb_etmfw_product_array['etmfw_event_venue'] ) ? $mwb_etmfw_product_array['etmfw_event_venue'] : '';
						$pro_short_desc = $_product->get_short_description();
						$start_timestamp = strtotime( $start_date );
						$end_timestamp = strtotime( $end_date );
						$gmt_offset_seconds = $this->mwb_etmfw_get_gmt_offset_seconds( $start_timestamp );

						$calendar_url = 'https://calendar.google.com/calendar/r/eventedit?text=' . $event_name . '&dates=' . gmdate( 'Ymd\\THi00\\Z', ( $start_timestamp - $gmt_offset_seconds ) ) . '/' . gmdate( 'Ymd\\THi00\\Z', ( $end_timestamp - $gmt_offset_seconds ) ) . '&details=' . $pro_short_desc . '&location=' . $event_venue;

						?>
						<div class="mwb_etmfw_view_ticket_section">
							<a href="<?php echo esc_attr( $upload_dir_path ); ?>" class="mwb_view_ticket_pdf" target="_blank"><?php esc_html_e( 'View', 'event-tickets-manager-for-woocommerce' ); ?></a>
						</div>
						<div class="mwb_etmfw_calendar_section">
							<a href="<?php echo esc_attr( $calendar_url ); ?>" class="mwb_etmfw_add_event_calendar" target="_blank"><?php esc_html_e( '+ Add to Google Calendar', 'event-tickets-manager-for-woocommerce' ); ?></a>
						</div>
						<?php
						$item_meta_data = $item->get_meta_data();
						$mwb_etmfw_field_data = isset( $mwb_etmfw_product_array['mwb_etmfw_field_data'] ) && ! empty( $mwb_etmfw_product_array['mwb_etmfw_field_data'] ) ? $mwb_etmfw_product_array['mwb_etmfw_field_data'] : array();
						$mwb_etmfw_flag = false;
						if ( ! empty( $item_meta_data ) && ! empty( $mwb_etmfw_field_data ) ) {
							foreach ( $item_meta_data as $key => $value ) {
								if ( isset( $value->key ) && ! empty( $value->value ) ) {
									$mwb_etmfw_mail_template_data[ $value->key ] = $value->value;
								}
							}
							foreach ( $mwb_etmfw_mail_template_data as $label_key => $user_data_value ) {
								foreach ( $mwb_etmfw_field_data as $key => $html_value ) {
									if ( 0 === strcasecmp( $html_value['label'], $label_key ) ) {
										$mwb_etmfw_flag = true;
									}
								}
							}
							if ( is_wc_endpoint_url( 'order-received' ) || is_wc_endpoint_url( 'view-order' ) ) {
								if ( $mwb_etmfw_flag ) {
									?>
									<div class="mwb_etmfw_edit_ticket_section">
										<span id="mwb_etmfw_edit_ticket">
											<?php esc_html_e( 'Edit Ticket Information', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
										<form id="mwb_etmfw_edit_ticket_form">
											<input type="hidden" id="mwb_etmfw_edit_info_order" value="<?php echo esc_attr( $order_id ); ?>">
											<?php

											foreach ( $mwb_etmfw_mail_template_data as $label_key => $user_data_value ) {
												foreach ( $mwb_etmfw_field_data as $key => $html_value ) {
													if ( 0 === strcasecmp( $html_value['label'], $label_key ) ) {
														$this->generate_edit_ticket_inputs( $html_value, $user_data_value );
													}
												}
											}
											?>
											<input type="submit" class="button button-primary" 
												name="mwb_etmfw_save_edit_ticket_info_btn"
												id="mwb_etmfw_save_edit_ticket_info_btn"
												value="<?php esc_attr_e( 'Save Changes', 'event-tickets-manager-for-woocommerce' ); ?>"
												/>
												<div class="mwb_etmfw_loader" id="mwb_etmfw_edit_info_loader">
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

	/**
	 * Create additional input fields to modify user input data.
	 *
	 * @since 1.0.0
	 * @name generate_edit_ticket_inputs()
	 * @param array  $html_value Html Values.
	 * @param string $user_data_value User data Values.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
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
		switch ( $html_value['type'] ) {
			case 'hidden':
			case 'number':
			case 'email':
			case 'text':
			case 'date':
				?>
				<input type="hidden" value="<?php echo esc_attr( $html_value_label ); ?>">
				<div class="mwb-edit-form-group" data-id="<?php echo esc_attr( $html_value_label ); ?>">
					<div class="mwb-edit-form-group__label">
						<label class="mwb_etmfe_input_label" for="<?php echo esc_attr( $html_value_label ); ?>"><?php echo esc_html( $html_value['label'] ); ?></label>
						<?php if ( $mandatory ) : ?>	
							<span class="mwb_etmfw_mandatory_fields">						
								<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
							</span>
						<?php endif; ?>
					</div>
					<div class="mwb-edit-form-group__control">
						<input type="<?php echo esc_html( $html_value['type'] ); ?>" value="<?php echo esc_html( $user_data_value ); ?>" id="mwb_etmfw_<?php echo esc_html( $html_value_label ); ?>" <?php echo esc_html( $required ); ?>>
					</div>
				</div>
				<?php
				break;

			case 'textarea':
				?>
				<div class="mwb-edit-form-group" data-id="<?php echo esc_attr( $html_value_label ); ?>">
					<label class="mwb_etmfe_input_label" for="<?php echo esc_attr( $html_value_label ); ?>"><?php echo esc_html( $html_value['label'] ); ?></label>
					<?php if ( $mandatory ) : ?>	
							<span class="mwb_etmfw_mandatory_fields">						
								<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
							</span>
						<?php endif; ?>
					<textarea class="" rows="2" cols="25" id="mwb_etmfw_<?php echo esc_attr( $html_value_label ); ?>" id="<?php echo esc_attr( $html_value_label ); ?>" <?php echo esc_html( $required ); ?>><?php echo esc_textarea( $user_data_value ); // WPCS: XSS ok. ?></textarea>
				</div>
				<?php
				break;
			case 'yes-no':
				?>
				<div class="mwb-edit-form-group" data-id="<?php echo esc_attr( $html_value_label ); ?>">
					<label class="mwb_etmfe_input_label" for="<?php echo esc_attr( $html_value_label ); ?>"><?php echo esc_html( $html_value['label'] ); ?></label>
					<?php if ( $mandatory ) : ?>	
							<span class="mwb_etmfw_mandatory_fields">						
								<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
							</span>
						<?php endif; ?>
					<div>
						<input type="radio" id="mwb_etmfw_<?php echo esc_attr( $html_value_label ); ?>" name="mwb_etmfw_<?php echo esc_attr( $html_value_label ); ?>" value="yes" <?php echo esc_html( 'yes' === $user_data_value ) ? 'checked' : ''; ?> <?php echo esc_html( $required ); ?>>
						<label for="yes"><?php esc_html_e( 'Yes', 'event-tickets-manager-for-woocommerce' ); ?></label>
					</div>
					<div>
						<input type="radio" id="mwb_etmfw_<?php echo esc_attr( $html_value_label ); ?>" name="mwb_etmfw_<?php echo esc_attr( $html_value_label ); ?>" value="no" <?php echo esc_html( 'no' === $user_data_value ) ? 'checked' : ''; ?>>
						<label for="no"><?php esc_html_e( 'No', 'event-tickets-manager-for-woocommerce' ); ?></label>
					</div>
				</div>
				<?php
				break;

				do_action( 'mwb_etmfw_edit_ticket_info_field', $html_value, $user_data_value );

			default:
				break;
		}

	}

	/**
	 * This is function is used to create shortcode to check event check in.
	 *
	 * @name mwb_etmfw_add_eventcheckin_shortcode
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_etmfw_add_eventcheckin_shortcode() {
		add_shortcode( 'mwb_etmfw_event_checkin_page', array( $this, 'mwb_etmfw_create_event_checkin_page' ) );
	}

	/**
	 * This is function is used to display event check in page.
	 *
	 * @name mwb_etmfw_create_event_checkin_page
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_etmfw_create_event_checkin_page() {

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
		$html = '<div class="mwb_etmfw_checkin_wrapper">
			<form method="post">
			<div id="mwb_etmfw_error_message"></div>
			<div class="mwb_etmfw_events_section">
				<label>' . __( 'For', 'event-tickets-manager-for-woocommerce' ) . '</label>';
		if ( $product_array->have_posts() ) {
			if ( isset( $product_array->posts ) && ! empty( $product_array->posts ) ) {
				$html .= '<select id="mwb_etmfw_event_selected">';
				foreach ( $product_array->posts as $event_per_product ) {
					$html .= '<option value="' . $event_per_product->ID . '">' . $event_per_product->post_title . '</option>';
				}
				$html .= '</select>';
			}
		}

			$html .= '</div>			
			<div class="mwb_etmfw_input_ticket_section">
				<label>' . __( 'Ticket Number', 'event-tickets-manager-for-woocommerce' ) . '</label>
				<input type="text" name="mwb_etmfw_imput_ticket" id="mwb_etmfw_imput_ticket">
			</div>
			<div class="mwb_etmfw--loader-btn-wrapper">	
				<div class="mwb_etmfw_checkin_button_section">
					<input type="submit" name="mwb_etmfw_checkin_button" id="mwb_etmfw_checkin_button" value="' . __( 'Check In', 'event-tickets-manager-for-woocommerce' ) . '">
				</div>
				<div class="mwb_etmfw_loader" id="mwb_etmfw_checkin_loader">
					<img src="' . esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/image/loading.gif' ) . '">
				</div>
			</div>
			</form>			
		</div>';
		return $html;
	}

	/**
	 * Create form for user checkin.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_make_user_checkin_for_event().
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_make_user_checkin_for_event() {
		if ( isset( $_REQUEST['mwb_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mwb_nonce'] ) ), 'mwb-etmfw-verify-checkin-nonce' ) ) { // WPCS: input var ok, sanitization ok.
			$response['result'] = false;
			$response['message'] = 'No tickets found for the event.';
			$product_id = isset( $_REQUEST['for_event'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['for_event'] ) ) : '';
			$ticket_num = isset( $_REQUEST['ticket_num'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['ticket_num'] ) ) : '';
			$generated_tickets = get_post_meta( $product_id, 'mwb_etmfw_generated_tickets', true );
			if ( ! empty( $generated_tickets ) ) {
				foreach ( $generated_tickets as $key => $value ) {
					if ( $ticket_num == $value['ticket'] ) {
						if ( 'pending' === $value['status'] ) {
							$post = get_post( $value['order_id'] );
							if ( 'trash' !== $post->post_status ) {
								$current_timestamp = current_time( 'timestamp' );
								$mwb_etmfw_product_array = get_post_meta( $product_id, 'mwb_etmfw_product_array', true );
								$end_date = isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '';
								$start_date = isset( $mwb_etmfw_product_array['event_start_date_time'] ) ? $mwb_etmfw_product_array['event_start_date_time'] : '';
								$end_date_timestamp = strtotime( $end_date );
								$start_date_timestamp = strtotime( $start_date );
								if ( $end_date_timestamp > $current_timestamp ) {
									if ( $current_timestamp > $start_date_timestamp ) {
										$response['result'] = true;
										$generated_tickets[ $key ]['status'] = 'checked_in';
										update_post_meta( $product_id, 'mwb_etmfw_generated_tickets', $generated_tickets );
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
					}
				}
			}
			echo json_encode( $response );
			wp_die();
		}
	}

	/**
	 * Edit user information for event.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_edit_user_info_for_event().
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_edit_user_info_for_event() {
		if ( isset( $_REQUEST['mwb_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mwb_nonce'] ) ), 'mwb-etmfw-verify-public-nonce' ) ) { // WPCS: input var ok, sanitization ok.
			$response['result'] = false;
			$posted_value = ! empty( $_REQUEST['form_value'] ) ? map_deep( wp_unslash( $_REQUEST['form_value'] ), 'sanitize_text_field' ) : array();
			$order_id = isset( $_REQUEST['order_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ) ) : '';
			$order = wc_get_order( $order_id );
			foreach ( $order->get_items() as $item_id => $item ) {
				$product = $item->get_product();
				if ( isset( $product ) && $product->is_type( 'event_ticket_manager' ) ) {
					foreach ( $posted_value as $key => $value ) {
						$key = ucwords( str_replace( '_', ' ', $key ) );
						$item->update_meta_data( $key, $value );
						$item->save();
						$response['result'] = true;
						update_post_meta( $order_id, 'mwb_etmfw_order_meta_updated', 'yes' );
						$product_id = $product->get_id();
						$item_meta_data = $item->get_meta_data();
						$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
						$mwb_ticket_content = $this->mwb_etmfw_get_html_content( $item_meta_data, $order, $order_id, $ticket_number, $product_id );
						$this->mwb_etmfw_generate_ticket_pdf( $mwb_ticket_content, $order, $order_id, $ticket_number );
					}
				}
			}
			echo json_encode( $response );
			wp_die();
		}
	}

	/**
	 * Get events for calendar.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_get_calendar_widget_data().
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_get_calendar_widget_data() {
		if ( isset( $_REQUEST['mwb_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mwb_nonce'] ) ), 'mwb-etmfw-verify-public-nonce' ) ) { // WPCS: input var ok, sanitization ok.
			$calendar_data = array();
			$filter_duration = get_option( 'mwb_etmfw_display_duration', 'all' );
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
					$calendar_data[] = $this->mwb_generate_list_view( $filter_duration, $calendar_data );
				}
			}

			wp_reset_postdata();
		}
		$response['result'] = $calendar_data;
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * Generate list view for event.
	 *
	 * @since 1.0.0
	 * @name mwb_generate_list_view().
	 * @param string $filter_duration Duration on the basis of filter.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_generate_list_view( $filter_duration ) {
		global $product;
		$event_array = array();
		$current_timestamp = current_time( 'timestamp' );
		$mwb_etmfw_product_array = get_post_meta( $product->get_id(), 'mwb_etmfw_product_array', true );
		$start_date = isset( $mwb_etmfw_product_array['event_start_date_time'] ) ? $mwb_etmfw_product_array['event_start_date_time'] : '';
		$end_date = isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '';
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
				// code...
				break;
		}
		return $event_array;
	}

	/**
	 * Unset COD payment gateway for cod.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_unset_cod_payment_gateway_for_event().
	 * @param array $available_gateways Available payment gateways.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_unset_cod_payment_gateway_for_event( $available_gateways ) {
		if ( is_admin() || ! is_checkout() ) {
			return $available_gateways;
		}

		$mwb_etmfw_event = false;
		foreach ( WC()->cart->get_cart_contents() as $key => $values ) {
			if ( $this->mwb_etmfw_check_product_is_event( $values['data'] ) ) {
				$mwb_etmfw_event = true;
				break;
			}
		}
		if ( $mwb_etmfw_event ) {
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
	 * @name mwb_etmfw_check_product_is_event().
	 * @param object $product Product.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_check_product_is_event( $product ) {
		$mwb_is_event = false;
		if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
			$mwb_is_event = true;
		}
		return $mwb_is_event;
	}

	/**
	 * Handle expired events.
	 *
	 * @param boolean $purchasable If product is purchaseable.
	 * @param array   $product Event Venue.
	 * @return boolean $purchasable If product is purchaseable.
	 * @since    1.0.0
	 */
	public function mwb_etmfw_handle_expired_events( $purchasable, $product ) {
		if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
			$product_id = $product->get_id();
			$mwb_etmfw_product_array = get_post_meta( $product_id, 'mwb_etmfw_product_array', true );
			$end_date = isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '';
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
	public function mwb_etmfw_get_gmt_offset_seconds( $date = null ) {
		if ( $date ) {
			$timezone = new DateTimeZone( $this->mwb_etmfw_get_timezone() );

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
	public function mwb_etmfw_get_timezone( $event = null ) {
		$timezone_string = get_option( 'timezone_string' );
		$gmt_offset = get_option( 'gmt_offset' );

		if ( trim( $timezone_string ) == '' && trim( $gmt_offset ) ) {
			$timezone_string = $this->mwb_etmfw_get_timezone_by_offset( $gmt_offset );
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
	public function mwb_etmfw_get_timezone_by_offset( $offset ) {
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
	 * @name mwb_etmfw_get_product_type().
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 * @return string $product_type Product type.
	 */
	public function mwb_etmfw_get_product_type() {
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
	 * @name mwb_etmfw_check_if_event_is_expired().
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 * @return boolean $mwb_etmfw_if_expired If event is expired.
	 */
	public function mwb_etmfw_check_if_event_is_expired() {
		$mwb_etmfw_if_expired = false;
		global $post;
		if ( isset( $post ) && ! empty( $post ) ) {
			$product = wc_get_product( $post->ID );
			if ( isset( $product ) && ! empty( $product ) ) {
				if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
					$product_id = $product->get_id();
					$mwb_etmfw_product_array = get_post_meta( $product_id, 'mwb_etmfw_product_array', true );
					$end_date = isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '';
					$current_timestamp = current_time( 'timestamp' );
					$end_date_timestamp = strtotime( $end_date );
					if ( $end_date_timestamp < $current_timestamp ) {
						$mwb_etmfw_if_expired = true;
					}
				}
			}
		}
		return $mwb_etmfw_if_expired;
	}

	/**
	 * Show google map on single product page.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_show_google_map_on_product_page().
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 * @return boolean $if_show_map display map.
	 */
	public function mwb_etmfw_show_google_map_on_product_page() {
		$if_show_map = false;
		global $post;
		if ( isset( $post ) && ! empty( $post ) ) {
			$product = wc_get_product( $post->ID );
			if ( isset( $product ) && ! empty( $product ) ) {
				if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
					$product_id = $product->get_id();
					$mwb_etmfw_product_array = get_post_meta( $product_id, 'mwb_etmfw_product_array', true );
					$display_map = isset( $mwb_etmfw_product_array['etmfw_display_map'] ) ? $mwb_etmfw_product_array['etmfw_display_map'] : 'no';
					$location_site = get_option( 'mwb_etmfw_enabe_location_site', 'off' );
					$map_api_key = get_option( 'mwb_etmfw_google_maps_api_key', '' );
					if ( 'yes' === $display_map && 'on' === $location_site && '' !== $map_api_key ) {
						$if_show_map = true;
					}
				}
			}
		}
		return $if_show_map;
	}
}
