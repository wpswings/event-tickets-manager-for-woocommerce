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
		wp_localize_script( $this->plugin_name, 'etmfw_public_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name );

	}

	public function mwb_etmfw_before_add_to_cart_button_html( $event_product ) {
		global $product;
		if( isset( $product ) && !empty( $product ) ){
			$mwb_etmfw_enable = get_option( 'mwb_etmfw_enable_plugin', false );
			if($mwb_etmfw_enable){
				$product_id = $product->get_id();
				if( isset( $product_id ) && !empty( $product_id ) ){
					$product_types = wp_get_object_terms( $product_id, 'product_type' );
					$product_type = $product_types[0]->slug;
					wp_nonce_field( 'mwb_etmfw_single_nonce', 'mwb_etmfw_single_nonce_field' );
					if( $product_type == 'event_ticket_manager' ){
						$mwb_etmfw_product_array = get_post_meta( $product_id, 'mwb_etmfw_product_array', true );
						$start_date = isset( $mwb_etmfw_product_array['event_start_date_time'] ) ? $mwb_etmfw_product_array['event_start_date_time'] : '';
						$end_date = isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '';
						$event_venue = isset( $mwb_etmfw_product_array['etmfw_event_venue'] ) ? $mwb_etmfw_product_array['etmfw_event_venue'] : '';
						$event_field_array = isset( $mwb_etmfw_product_array['mwb_etmfw_field_data'] ) ? $mwb_etmfw_product_array['mwb_etmfw_field_data'] : '';
						?>
						<div class="mwb_etmfw_product_wrapper">								
							<div class="mwb_etmfw_event_info_section">
								<?php do_action( 'mwb_etmfw_before_event_general_info', $product_id );?>
								<input type="hidden" name="mwb_etmfw_event_start" value=<?php echo esc_html( $start_date ); ?>>
								<input type="hidden" name="mwb_etmfw_event_finish" value=<?php echo esc_html( $end_date ); ?>>	
								<div class="mwb_etmwf_event_date">
									<span class="mwb_etmfw_date_label"><?php _e('Date : ', 'event-tickets-manager-for-woocommerce' );?></span>
									<span><?php _e( mwb_etmfw_get_date_format( $start_date ) , 'event-tickets-manager-for-woocommerce' );?></span>
									<span><?php _e( ' to ', 'event-tickets-manager-for-woocommerce' );?></span>
									<span><?php _e( mwb_etmfw_get_date_format( $end_date ), 'event-tickets-manager-for-woocommerce' );?></span>
								</div>
								<div class="mwb_etmwf_venue">
									<span><?php _e('Venue : ', 'event-tickets-manager-for-woocommerce' );?></span>
									<span><?php _e( $event_venue, 'event-tickets-manager-for-woocommerce' );?></span>
									<input type="hidden" name="mwb_etmfw_event_venue" value=<?php echo esc_html( $event_venue ); ?>>	
								</div>
								<?php do_action( 'mwb_etmfw_before_event_general_info', $product_id );?>	
							</div>
							<div class="mwb_etmfw_addition_info_section">
								<?php do_action( 'mwb_etmfw_before_more_info', $product_id );?>
								<?php $this->mwb_etmfw_generate_addional_fields( $product_id, $event_field_array );?>
								<?php do_action( 'mwb_etmfw_after_more_info', $product_id );?>
							</div>
						</div>
						<?php
					}
				}
			}
		}
	}

	public function mwb_etmfw_generate_addional_fields( $product_id, $event_field_array ){
		if( is_array( $event_field_array ) && !empty( $event_field_array ) ) {
			$mandatory = false;
			$required = '';
			foreach ( $event_field_array as $key => $value ) {
				if( array_key_exists( 'required', $value ) && 'on' === $value['required'] ) {
					$required = 'required="required"';
					$mandatory = true;
				}
				?>
				<p>
					<label class="mwb_etmfw_field_label"><?php _e($value['label'], 'event-tickets-manager-for-woocommerce');?>
					<?php if( $mandatory ) : ?>	
						<span class="mwb_etmfw_mandatory_fields">						
							<?php  _e( '*', 'event-tickets-manager-for-woocommerce'); ?>
						</span>
					<?php endif; ?>
				</label>
				<?php
				switch ( $value['type'] ) {
					case 'text':
					?>
					<input type="hidden" name="mwb_etmfw_text_label" value="<?php echo esc_html( $value['label'] ) ?>">
					<input type="text" name="mwb_etmfw_info_text" <?php echo esc_html( $required ) ?>><?php
					break;

					case 'email':
					?>
					<input type="hidden" name="mwb_etmfw_email_label" value="<?php echo esc_html( $value['label'] ) ?>">
					<input type="email" name="mwb_etmfw_info_email" <?php echo esc_html( $required ) ?> ><?php
					break;

					case 'textarea':
					?>
					<input type="hidden" name="mwb_etmfw_textarea_label" value="<?php echo esc_html( $value['label'] ) ?>">
					<textarea name="mwb_etmfw_info_textarea" <?php echo esc_html( $required ) ?>></textarea><?php
					break;

					case 'number':
					?>
					<input type="hidden" name="mwb_etmfw_number_label" value="<?php echo esc_html( $value['label'] ) ?>">
					<input type="number" name="mwb_etmfw_info_number" <?php echo esc_html( $required ) ?>><?php
					break;

					case 'date':
					?>
					<input type="hidden" name="mwb_etmfw_date_label" value="<?php echo esc_html( $value['label'] ) ?>">
					<input type="date" name="mwb_etmfw_info_date" <?php echo esc_html( $required ) ?>><?php
					break;

					case 'yes-no':
					?>
					<input type="hidden" name="mwb_etmfw_radio_label" value="<?php echo esc_html( $value['label'] ) ?>">
					<input type="radio" id="male" name="mwb_etmfw_info_radio" value="yes" <?php echo esc_html( $required ) ?>>
					<label for="yes"><?php  _e( 'Yes', 'event-tickets-manager-for-woocommerce'); ?></label><br>
					<input type="radio" id="female" name="mwb_etmfw_info_radio" value="no">
					<label for="no"><?php  _e( 'No', 'event-tickets-manager-for-woocommerce'); ?></label><br>
					<?php
					break;
					
					default:
					do_action('mwb_etmfw_after_input_fields', $value, $required  );
					break;
				}
				do_action('mwb_etmfw_after_input_fields', $value );
				?>
			</p>
			<?php
		}
	}
}

public function mwb_etmfw_allow_single_quantity( $return, $product ) {
	if ( $product->is_type( 'event_ticket_manager' ) ) {
		return apply_filters('mwb_etmfw_increase_event_product_quantity', true );
	} else {
		return $return;
	}
}

public function mwb_etmfw_cart_item_data( $the_cart_data, $product_id, $variation_id ){
	$mwb_etmfw_enable = get_option( 'mwb_etmfw_enable_plugin', false );
	if($mwb_etmfw_enable){
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
						$item_meta['mwb_etmfw_field_info']['mwb_etmfw_text_label'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_text_label'] ) );
						$item_meta['mwb_etmfw_field_info']['mwb_etmfw_info_text'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_info_text'] ) );
					}
					if ( isset( $_POST['mwb_etmfw_info_email'] ) && ! empty( $_POST['mwb_etmfw_info_email'] ) ) {
						$item_meta['mwb_etmfw_field_info']['mwb_etmfw_email_label'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_email_label'] ) );
						$item_meta['mwb_etmfw_field_info']['mwb_etmfw_info_email'] = sanitize_email( wp_unslash( $_POST['mwb_etmfw_info_email'] ) );
					}
					if ( isset( $_POST['mwb_etmfw_info_textarea'] ) && ! empty( $_POST['mwb_etmfw_info_textarea'] ) ) {
						$item_meta['mwb_etmfw_field_info']['mwb_etmfw_textarea_label'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_textarea_label'] ) );
						$item_meta['mwb_etmfw_field_info']['mwb_etmfw_info_textarea'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_info_textarea'] ) );
					}
					if ( isset( $_POST['mwb_etmfw_info_number'] ) && ! empty( $_POST['mwb_etmfw_info_number'] ) ) {
						$item_meta['mwb_etmfw_field_info']['mwb_etmfw_number_label'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_number_label'] ) );
						$item_meta['mwb_etmfw_field_info']['mwb_etmfw_info_number'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_info_number'] ) );
					}
					if ( isset( $_POST['mwb_etmfw_info_date'] ) && ! empty( $_POST['mwb_etmfw_info_date'] ) ) {
						$item_meta['mwb_etmfw_field_info']['mwb_etmfw_date_label'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_date_label'] ) );
						$item_meta['mwb_etmfw_field_info']['mwb_etmfw_info_date'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_info_date'] ) );
					}
					if ( isset( $_POST['mwb_etmfw_info_radio'] ) && ! empty( $_POST['mwb_etmfw_info_radio'] ) ) {
						$item_meta['mwb_etmfw_field_info']['mwb_etmfw_radio_label'] = sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_radio_label'] ) );
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

public function mwb_etmfw_get_cart_item_data( $item_meta, $existing_item_meta ){
	$mwb_etmfw_enable = get_option( 'mwb_etmfw_enable_plugin', false );
	if ( $mwb_etmfw_enable ) {
		if ( isset( $existing_item_meta ['product_meta']['meta_data'] ) ) {
			foreach ( $existing_item_meta['product_meta'] ['meta_data'] as $key => $val ) {
				if ( 'mwb_etmfw_field_info' == $key ) {
					if( !empty( $val ) ) {
						$info_array = $this->mwb_etmfw_generate_key_value_pair( $val );
						foreach ($info_array as $info_key => $info_value) {
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

public function mwb_etmfw_generate_key_value_pair( $field_post ){
	$field_array = array();
	$label = '';
	foreach ( $field_post as $key => $value ) {
		if (strpos($key, 'label') !== false) {
			$label = $field_post[$key];
		} else{
			$field_array[$label] = $field_post[$key];
		}
	}
	return $field_array;
}

public function mwb_etmfw_create_order_line_item( $item, $cart_key, $values ){
	$mwb_etmfw_enable = get_option( 'mwb_etmfw_enable_plugin', false );
	if ( $mwb_etmfw_enable ) {
		if ( isset( $values ['product_meta'] ) ) {
			foreach ( $values ['product_meta'] ['meta_data'] as $key => $val ) {
				if ( 'mwb_etmfw_field_info' == $key && $val ) {
					$info_array = $this->mwb_etmfw_generate_key_value_pair( $val );
					foreach ($info_array as $info_key => $info_value) {
						$item->add_meta_data( $info_key, $info_value );
					}					
					do_action( 'mwb_etmfw_checkout_create_order_line_item', $item, $key, $val );
				}
			}
		}
	}
}

public function mwb_etmfw_process_event_order( $order_id, $old_status, $new_status ){
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
						$pro_id = $product->get_id();
						$item_meta_data = $item->get_meta_data();
						$mwb_etmfw_mail_template_data = array(
							'product_id' => $pro_id,
							'item_id'   => $item_id,
							'order_id'   => $order_id,
						);							
						foreach ( $item_meta_data as $key => $value ) {
							if ( isset( $value->key ) && ! empty( $value->value ) ) {
								$mwb_etmfw_mail_template_data[$value->key] = $value->value;
							}
						}
						$ticket_number = mwb_etmfw_ticket_generator();
						if( isset( $ticket_number ) ) {
							$mwb_etmfw_mail_template_data['ticket_number'] = $ticket_number;
							update_post_meta( $order_id, "event_ticket#$order_id#$item_id", $ticket_number );
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

public function mwb_etmfw_send_ticket_mail( $order, $mwb_etmfw_mail_template_data ){
	$user_email = $order->get_billing_email();
	$mailer_obj = WC()->mailer()->emails['mwb_etmfw_email_notification'];
	$mwb_etmfw_email_discription = $this->mwb_etmfw_generate_ticket_info_in_mail( $mwb_etmfw_mail_template_data );
	$mwb_etmfw_email_subject = get_option('mwb_etmfw_email_subject', 'Ticket for order #$order');
	$email_status = $mailer_obj->trigger( $user_email, $mwb_etmfw_email_discription, $mwb_etmfw_email_subject );
}

public function mwb_etmfw_generate_ticket_info_in_mail( $mwb_etmfw_mail_template_data ){
	$template_html = '';
	// foreach ($mwb_etmfw_mail_template_data as $key => $value) {
	// 	$template_html .= $key . ' : ' . $value . '<br>';
	// }
	return apply_filters( 'mwb_etmfw_ticket_info', $template_html );

}
public function mwb_etmfw_attach_pdf_to_emails( $attachments, $email_id, $order, $email ){
	if( is_object($order) ){
		//$order_id = $order->get_id();
		$order_id = '61';
		$mwb_ticket_content = $this->mwb_etmfw_get_html_content( $order );
		$this->mwb_etmfw_generate_ticket_pdf( $mwb_ticket_content, $order, $order_id );
		$ticket_number = '1234';
		$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';
		$generated_ticket_pdf = $upload_dir_path . '/events' . $order_id . $ticket_number . '.pdf';
		$email_ids = array( 'mwb_etmfw_email_notification' );
		if ( in_array ( $email_id, $email_ids ) ) {
			$attachments[] = $generated_ticket_pdf;
		}
	}
	return $attachments;
}

public function mwb_etmfw_get_html_content( $order ){
	$mwb_ticket_details = '<h1>This is a test</h1>';
	// ob_start();
	// include_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/mwb-etmfw-mail-html-content.php';
	// $mwb_ticket_details = ob_get_clean();
	return apply_filters( 'mwb_etmfw_ticket_html_content', $mwb_ticket_details );
}

public function mwb_etmfw_generate_ticket_pdf( $mwb_ticket_content, $order, $order_id ){

	require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH .'dompdf/autoload.inc.php'; 
	$dompdf = new Dompdf\Dompdf(); 
	$dompdf->setPaper('A4', 'landscape'); 
	$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR . '/events_pdf';
	if ( ! is_dir( $upload_dir_path ) ) {
		wp_mkdir_p( $upload_dir_path );
		chmod( $upload_dir_path, 0775 );
	}
	$ticket_number = '1234';
	$dompdf->load_html($mwb_ticket_content);
	$dompdf->render();
	$output = $dompdf->output();
	$generated_pdf = file_put_contents( $upload_dir_path . '/events' . $order_id . $ticket_number . '.pdf', $output );
}

public function mwb_etmfw_view_ticket_button( $order ){
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
					if ( 'event_ticket_manager' == $product_type ) {
							//$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket_number . '.pdf';
						$upload_dir_path = '';
						?>
						<div class="mwb_etmfw_view_ticket_section">
							<a href="<?php echo esc_attr( $upload_dir_path ); ?>" class="mwb_view_ticket_pdf" target="_blank"><?php esc_html_e( 'View Ticket', 'giftware' ); ?><i class="fas fa-file-view_ticket mwb_etmfw_view_ticket_pdf"></i></a><br/>
						</div>
						<div class="mwb_etmfw_edit_ticket_section">
							<a href="<?php echo esc_attr( $upload_dir_path ); ?>" class="mwb_edit_ticket_info" target=""><?php esc_html_e( 'Edit Ticket', 'giftware' ); ?></a><br/>
						</div>
						<?php
					}
				}
			}
		}
	}

}
}
