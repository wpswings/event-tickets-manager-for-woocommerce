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

		wp_enqueue_style( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/scss/event-tickets-manager-for-woocommerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function etmfw_public_enqueue_scripts() {

		wp_register_script( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'public/src/js/event-tickets-manager-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
		$public_param_data = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'mwb_etmfw_public_nonce' => wp_create_nonce( 'mwb-etmfw-verify-public-nonce' ),
		);
		wp_localize_script( $this->plugin_name, 'etmfw_public_param', $public_param_data );
		wp_enqueue_script( $this->plugin_name );

		global $wp_query;
		$checkin_page_id = get_option( 'event_checkin_page_created', '' );
		if ( '' !== $checkin_page_id ) {
			if ( $wp_query->post->ID == $checkin_page_id ) {
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
					wp_nonce_field( 'mwb_etmfw_single_nonce', 'mwb_etmfw_single_nonce_field' );
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
								<div class="mwb_etmwf_event_date">
									<span class="mwb_etmfw_date_label"><?php esc_html_e( 'Date : ', 'event-tickets-manager-for-woocommerce' ); ?></span>
									<span><?php esc_html_e( mwb_etmfw_get_date_format( $start_date ), 'event-tickets-manager-for-woocommerce' ); ?></span>
									<span><?php esc_html_e( ' to ', 'event-tickets-manager-for-woocommerce' ); ?></span>
									<span><?php esc_html_e( mwb_etmfw_get_date_format( $end_date ), 'event-tickets-manager-for-woocommerce' ); ?></span>
								</div>
								<div class="mwb_etmwf_venue">
									<span><?php esc_html_e( 'Venue : ', 'event-tickets-manager-for-woocommerce' ); ?></span>
									<span><?php esc_html_e( $event_venue, 'event-tickets-manager-for-woocommerce' ); ?></span>
									<input type="hidden" name="mwb_etmfw_event_venue" value=<?php echo esc_html( $event_venue ); ?>>	
								</div>
								<?php do_action( 'mwb_etmfw_before_event_general_info', $product_id ); ?>	
							</div>
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
	 * @param int $product_id  Project Id.
	 * @param array $event_field_array  Html Field Array.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_generate_addional_fields( $product_id, $event_field_array ) {
		if ( is_array( $event_field_array ) && ! empty( $event_field_array ) ) {
			foreach ( $event_field_array as $key => $value ) {
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
								<label class="mwb_etmfw_field_label"><?php esc_html_e( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
									<?php if ( $mandatory ) : ?>	
										<span class="mwb_etmfw_mandatory_fields">						
											<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
									<?php endif; ?>
								</label>
							</div>
							<div class="mwb-form-group__control">
								<input type="hidden" name="mwb_etmfw_text_label" value="<?php echo esc_html( $value['label'] ); ?>">
								<input type="text" name="mwb_etmfw_info_text" <?php echo esc_html( $required ); ?>>
							</div>
						</div>
						
							<?php
							break;

						case 'email':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb_etmfw_field_label"><?php esc_html_e( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
									<?php if ( $mandatory ) : ?>	
										<span class="mwb_etmfw_mandatory_fields">						
											<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
									<?php endif; ?>
								</label>
							</div>
							<div class="mwb-form-group__control">
								<input type="hidden" name="mwb_etmfw_email_label" value="<?php echo esc_html( $value['label'] ); ?>">
								<input type="email" name="mwb_etmfw_info_email" <?php echo esc_html( $required ); ?> >
							</div>
						</div>
							<?php
							break;

						case 'textarea':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb_etmfw_field_label"><?php esc_html_e( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
									<?php if ( $mandatory ) : ?>	
										<span class="mwb_etmfw_mandatory_fields">						
											<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
									<?php endif; ?>
								</label>
							</div>
							<div class="mwb-form-group__control">
								<input type="hidden" name="mwb_etmfw_textarea_label" value="<?php echo esc_html( $value['label'] ); ?>">
								<textarea name="mwb_etmfw_info_textarea" <?php echo esc_html( $required ); ?>></textarea>
							</div>
						</div>


							<?php
							break;

						case 'number':
							?>

						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb_etmfw_field_label"><?php esc_html_e( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
									<?php if ( $mandatory ) : ?>	
										<span class="mwb_etmfw_mandatory_fields">						
											<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
									<?php endif; ?>
								</label>
							</div>
							<div class="mwb-form-group__control">
								<input type="hidden" name="mwb_etmfw_number_label" value="<?php echo esc_html( $value['label'] ); ?>">
								<input type="number" name="mwb_etmfw_info_number" <?php echo esc_html( $required ); ?>>
							</div>
						</div>

							<?php
							break;

						case 'date':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb_etmfw_field_label"><?php esc_html_e( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
									<?php if ( $mandatory ) : ?>	
										<span class="mwb_etmfw_mandatory_fields">						
											<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
									<?php endif; ?>
								</label>
							</div>
							<div class="mwb-form-group__control">
								<input type="hidden" name="mwb_etmfw_date_label" value="<?php echo esc_html( $value['label'] ); ?>">
								<input type="date" name="mwb_etmfw_info_date" <?php echo esc_html( $required ); ?>>
							</div>
						</div>


							<?php
							break;

						case 'yes-no':
							?>
						<div class="mwb-form-group">
							<div class="mwb-form-group__label">
								<label class="mwb_etmfw_field_label"><?php esc_html_e( $value['label'], 'event-tickets-manager-for-woocommerce' ); ?>
									<?php if ( $mandatory ) : ?>	
										<span class="mwb_etmfw_mandatory_fields">						
											<?php esc_html_e( '*', 'event-tickets-manager-for-woocommerce' ); ?>
										</span>
									<?php endif; ?>
								</label>
							</div>
							<div class="mwb-form-group__control">
								<input type="hidden" name="mwb_etmfw_radio_label" value="<?php echo esc_html( $value['label'] ); ?>">
								<div>
									<input type="radio" id="mwb_etmfw_info_radio" name="mwb_etmfw_info_radio" value="yes" <?php echo esc_html( $required ); ?>>
									<label for="yes"><?php esc_html_e( 'Yes', 'event-tickets-manager-for-woocommerce' ); ?></label>
								</div>
								<div>
									<input type="radio" id="mwb_etmfw_info_radio" name="mwb_etmfw_info_radio" value="no">
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
	 * @param boolean $return.
	 * @param object $product Product Object.
	 * @return boolean $return.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_allow_single_quantity( $return, $product ) {
		if ( $product->is_type( 'event_ticket_manager' ) ) {
			return apply_filters( 'mwb_etmfw_increase_event_product_quantity', true );
		} else {
			return $return;
		}
	}

	/**
	 * Add item data to cart.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_cart_item_data()
	 * @param array $the_cart_data.
	 * @param int $product_id Product Id.
	 * @param int $variation_id Variation Id
	 * @return array $the_cart_data.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_cart_item_data( $the_cart_data, $product_id, $variation_id ) {
		$mwb_etmfw_enable = get_option( 'mwb_etmfw_enable_plugin', false );
		if ( $mwb_etmfw_enable ) {
			$product_types = wp_get_object_terms( $product_id, 'product_type' );
			if ( isset( $product_types[0] ) ) {
				$product_type = $product_types[0]->slug;
				if ( 'event_ticket_manager' == $product_type ) {
					$mwb_field_nonce = isset( $_POST['mwb_etmfw_single_nonce_field'] ) ? stripcslashes( sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_single_nonce_field'] ) ) ) : '';
					if ( ! isset( $mwb_field_nonce ) || ! wp_verify_nonce( $mwb_field_nonce, 'mwb_etmfw_single_nonce' ) ) {
						echo esc_html__( 'Sorry, your nonce did not verify.', 'event-tickets-manager-for-woocommerce' );
						exit;
					} else {
						if ( isset( $_POST['mwb_etmfw_event_start'] ) && ! empty( $_POST['mwb_etmfw_event_start'] ) ) {
							$item_meta['mwb_etmfw_event_start'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_event_start'] ) );
						}
						if ( isset( $_POST['mwb_etmfw_event_finish'] ) && ! empty( $_POST['mwb_etmfw_event_finish'] ) ) {
							$item_meta['mwb_etmfw_event_finish'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_event_finish'] ) );
						}
						if ( isset( $_POST['mwb_etmfw_event_venue'] ) && ! empty( $_POST['mwb_etmfw_event_venue'] ) ) {
							$item_meta['mwb_etmfw_event_venue'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_event_venue'] ) );
						}
						if ( isset( $_POST['mwb_etmfw_info_text'] ) && ! empty( $_POST['mwb_etmfw_info_text'] ) ) {
							$item_meta['mwb_etmfw_field_info']['mwb_etmfw_text_label'] = isset( $_POST['mwb_etmfw_text_label'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_text_label'] ) ) : '';
							$item_meta['mwb_etmfw_field_info']['mwb_etmfw_info_text'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_info_text'] ) );
						}
						if ( isset( $_POST['mwb_etmfw_info_email'] ) && ! empty( $_POST['mwb_etmfw_info_email'] ) ) {
							$item_meta['mwb_etmfw_field_info']['mwb_etmfw_email_label'] =  isset( $_POST['mwb_etmfw_email_label'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_email_label'] ) ) : '';
							$item_meta['mwb_etmfw_field_info']['mwb_etmfw_info_email'] = sanitize_email( wp_unslash( $_POST['mwb_etmfw_info_email'] ) );
						}
						if ( isset( $_POST['mwb_etmfw_info_textarea'] ) && ! empty( $_POST['mwb_etmfw_info_textarea'] ) ) {
							$item_meta['mwb_etmfw_field_info']['mwb_etmfw_textarea_label'] = isset( $_POST['mwb_etmfw_textarea_label'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_textarea_label'] ) ) : '';
							$item_meta['mwb_etmfw_field_info']['mwb_etmfw_info_textarea'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_info_textarea'] ) );
						}
						if ( isset( $_POST['mwb_etmfw_info_number'] ) && ! empty( $_POST['mwb_etmfw_info_number'] ) ) {
							$item_meta['mwb_etmfw_field_info']['mwb_etmfw_number_label'] = isset( $_POST['mwb_etmfw_number_label'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_number_label'] ) ) : '';
							$item_meta['mwb_etmfw_field_info']['mwb_etmfw_info_number'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_info_number'] ) );
						}
						if ( isset( $_POST['mwb_etmfw_info_date'] ) && ! empty( $_POST['mwb_etmfw_info_date'] ) ) {
							$item_meta['mwb_etmfw_field_info']['mwb_etmfw_date_label'] = isset( $_POST['mwb_etmfw_date_label'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_date_label'] ) ) : '';
							$item_meta['mwb_etmfw_field_info']['mwb_etmfw_info_date'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_info_date'] ) );
						}
						if ( isset( $_POST['mwb_etmfw_info_radio'] ) && ! empty( $_POST['mwb_etmfw_info_radio'] ) ) {
							$item_meta['mwb_etmfw_field_info']['mwb_etmfw_radio_label'] = isset( $_POST['mwb_etmfw_radio_label'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_radio_label'] ) ) : '';
							$item_meta['mwb_etmfw_field_info']['mwb_etmfw_info_radio'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_info_radio'] ) );
						}
						$item_meta = apply_filters( 'mwb_etmfw_add_cart_item_data', $item_meta, $the_cart_data, $product_id, $variation_id );
						$the_cart_data ['product_meta'] = array( 'meta_data' => $item_meta );
					}
				}
			}
		}
		return $the_cart_data;
	}

	/**
	 * Add item data to cart.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_cart_item_data()
	 * @param array $item_meta.
	 * @param array $existing_item_meta Existing Item Meta.
	 * @return array $item_meta.
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
									'name' => esc_html__( $info_key, 'event-tickets-manager-for-woocommerce' ),
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
	 * @param array $field_post.
	 * @return array $field_post.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_generate_key_value_pair( $field_post ) {
		$field_array = array();
		$label = '';
		foreach ( $field_post as $key => $value ) {
			if ( strpos( $key, 'label' ) !== false ) {
				$label = $field_post[ $key ];
			} else {
				$field_array[ $label ] = $field_post[ $key ];
			}
		}
		return $field_array;
	}

	/**
	 * Create order Link Item.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_create_order_line_item()
	 * @param object $item.
	 * @param int $cart_key.
	 * @param array $values.
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
	 * @name mwb_etmfw_process_event_order()
	 * @param int $order_id.
	 * @param string $old_status.
	 * @param string $new_status.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_process_event_order( $order_id, $old_status, $new_status ) {
		$mwb_etmfw_mail_template_data = array();
		$mwb_etmfw_enable = get_option( 'mwb_etmfw_enable_plugin', false );
		if ( $mwb_etmfw_enable ) {
			if ( $old_status != $new_status ) {
				if ( 'completed' == $new_status || 'processing' == $new_status ) {
					$order = wc_get_order( $order_id );
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
									);
									update_post_meta( $product_id, 'mwb_etmfw_generated_tickets', $generated_tickets );
								} else {
									$generated_tickets[] = array(
										'ticket' => $ticket_number,
										'status' => 'pending',
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
			}
		}
	}

	/**
	 * Send Ticket Mail when event order is placed.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_send_ticket_mail()
	 * @param object $order.
	 * @param array $mwb_etmfw_mail_template_data.
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
	}

	/**
	 * Send Ticket Mail when event order is placed.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_generate_ticket_info_in_mail()
	 * @param array $mwb_etmfw_mail_template_data.
	 * @return array $template_html.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_generate_ticket_info_in_mail( $mwb_etmfw_mail_template_data ) {
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
		return apply_filters( 'mwb_etmfw_ticket_info', $template_html );

	}

	/**
	 * Generate Pdf attachment for mail.
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_attach_pdf_to_emails()
	 * @param array $attachments.
	 * @param string $email_id.
	 * @param object $order.
	 * @param string $email.email
	 * @return array $attachments.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_attach_pdf_to_emails( $attachments, $email_id, $order, $email ) {

		if ( $email_id == 'mwb_etmfw_email_notification' ) {
			if ( is_a( $order, 'WC_Order' ) ) {
				$order_status  = $order->get_status();
				if ( 'processing' === $order_status || 'completed' === $order_status ) {
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
	 * @param array $item_meta_data.
	 * @param object $order.
	 * @param int $order_id.
	 * @param string $ticket_number
	 * @param int $product_id
	 * @return string $mwb_ticket_details.
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
	
		if ( ! empty( $item_meta_data ) ) {
			$additinal_info = '<table border="0" cellspacing="0" cellpadding="0" style="table-layout: auto; width: 100%;"><tbody><tr><td style="padding: 20px 0 10px;"><h2 style="margin: 0;font-weight: bold;">Details :-</h2></td></tr>';
			foreach ( $item_meta_data as $key => $value ) {
				if ( isset( $value->key ) && ! empty( $value->value ) ) {
					$additinal_info .= '<tr><td style="padding: 10px 0;"><p style="margin: 0;">' . $value->key . ' - ' . $value->value . '</p></td></tr>';
				}
			}
			$additinal_info .= '</tbody></table>';
		}
		$site_logo = '<img src="' . get_option( 'mwb_etmfw_mail_setting_upload_logo', '' ) . '">';
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
	 * @param string $mwb_ticket_content.
	 * @param object $order.
	 * @param int $order_id.
	 * @param string $ticket_number
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_generate_ticket_pdf( $mwb_ticket_content, $order, $order_id, $ticket_number ) {

		require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'package/lib/dompdf/autoload.inc.php';
		$dompdf = new Dompdf\Dompdf( array( 'enable_remote' => true ) ); 
		$dompdf->setPaper( 'A4', 'landscape' );
		$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';
		if ( ! is_dir( $upload_dir_path ) ) {
			wp_mkdir_p( $upload_dir_path );
			chmod( $upload_dir_path, 0775 );
		}
		$dompdf->load_html( $mwb_ticket_content );
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
	 * view and edit ticket button on thankyou page .
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_view_ticket_button()
	 * @param object $order.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_view_ticket_button( $order ) {
		$order_id = $order->get_id();
		$order_status = $order->get_status();
		if ( 'completed' == $order_status || 'processing' == $order_status ) {
			foreach ( $order->get_items() as $item_id => $item ) {
				$_product = apply_filters( 'woocommerce_order_item_product', $product = $item->get_product(), $item );
				if ( isset( $_product ) && ! empty( $_product ) ) {
					$product_id = $_product->get_id();
				}
				if ( isset( $product_id ) && ! empty( $product_id ) ) {
					$product_types = wp_get_object_terms( $product_id, 'product_type' );
					if ( isset( $product_types[0] ) ) {
						$product_type = $product_types[0]->slug;
						$ticket_number = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
						if ( 'event_ticket_manager' == $product_type ) {
							$updated_meta_pdf = get_post_meta( $order_id, 'mwb_etmfw_order_meta_updated', true );
							if ( '' === $updated_meta_pdf ) {
								$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket_number . '.pdf';
							} else {
								$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket_number . '-new.pdf';
							}

							?>
							<div class="mwb_etmfw_view_ticket_section">
								<a href="<?php echo esc_attr( $upload_dir_path ); ?>" class="mwb_view_ticket_pdf" target="_blank"><?php esc_html_e( 'View Ticket', 'event-tickets-manager-for-woocommerce' ); ?><i class="fas fa-file-view_ticket mwb_etmfw_view_ticket_pdf"></i></a>
							</div>
							<?php
							$item_meta_data = $item->get_meta_data();
							if ( ! empty( $item_meta_data ) ) {
								?>
								<div class="mwb_etmfw_edit_ticket_section">
									<span id="mwb_etmfw_edit_ticket">
										<?php esc_html_e( 'Edit Ticket Information', 'event-tickets-manager-for-woocommerce' ); ?>
									</span>
									<form id="mwb_etmfw_edit_ticket_form">
										<input type="hidden" id="mwb_etmfw_edit_info_order" value="<?php echo esc_attr( $order_id ); ?>">
										<?php
										foreach ( $item_meta_data as $key => $value ) {
											if ( isset( $value->key ) && ! empty( $value->value ) ) {
												$mwb_etmfw_mail_template_data[ $value->key ] = $value->value;
											}
										}
										$mwb_etmfw_product_array = get_post_meta( $product_id, 'mwb_etmfw_product_array', true );
										$mwb_etmfw_field_data = isset( $mwb_etmfw_product_array['mwb_etmfw_field_data'] ) && ! empty( $mwb_etmfw_product_array['mwb_etmfw_field_data'] ) ? $mwb_etmfw_product_array['mwb_etmfw_field_data'] : array();

										foreach ( $mwb_etmfw_mail_template_data as $label_key => $user_data_value ) {
											foreach ( $mwb_etmfw_field_data as $key => $html_value ) {
												if ( $html_value['label'] === $label_key ) {
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
											<div style="display: none;" class="mwb_etmfw_loader" id="mwb_etmfw_edit_info_loader">
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

	/**
	 * Create additional input fields to modify user input data.
	 *
	 * @since 1.0.0
	 * @name generate_edit_ticket_inputs()
	 * @param array $html_value.
	 * @param string $user_data_value.
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
		$html_value_label = str_replace( ' ', '_', $html_value['label'] );
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
			<div class="mwb_etmfw_checkin_button_section">
				<input type="submit" name="mwb_etmfw_checkin_button" id="mwb_etmfw_checkin_button" value="' . __( 'Check In', 'event-tickets-manager-for-woocommerce' ) . '">
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
							$response['result'] = true;
							$generated_tickets[ $key ]['status'] = 'checked_in';
							update_post_meta( $product_id, 'mwb_etmfw_generated_tickets', $generated_tickets );
							$response['message'] = __( 'User checked in successfully.', 'event-tickets-manager-for-woocommerce' );
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
			$posted_value = !empty( $_REQUEST['form_value'] ) ? map_deep( wp_unslash( $_REQUEST['form_value'] ), 'sanitize_text_field' ) : array();
			$order_id = isset( $_REQUEST['order_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ) ) : '';
			$order = wc_get_order( $order_id );
			foreach ( $order->get_items() as $item_id => $item ) {
				$product = $item->get_product();
				if ( isset( $product ) && $product->is_type( 'event_ticket_manager' ) ) {
					foreach ( $posted_value as $key => $value ) {
						$key = str_replace( '_', ' ', $key );
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
}
