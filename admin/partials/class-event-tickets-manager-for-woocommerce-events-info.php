<?php
/**
 * Exit if accessed directly
 *
 * @since      1.0.0
 * @package    Event_Tickets_Manager_For_Woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * This is construct of class where all event users details listed.
 *
 * @name Event_Tickets_Manager_For_Woocommerce_Events_Info
 * @since      1.0.0
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @author makewebbetter<webmaster@makewebbetter.com>
 * @link https://www.makewebbetter.com/
 */
class Event_Tickets_Manager_For_Woocommerce_Events_Info extends WP_List_Table {
	/**
	 * This is variable which is used for the store all the data.
	 *
	 * @var array $example_data variable for store data.
	 */
	public $example_data;

	/**
	 * This construct colomns in event table.
	 *
	 * @name get_columns.
	 * @since      1.0.0
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function get_columns() {

		$columns = array(
			'cb'             	=> '<input type="checkbox" />',
			'ticket'            => __( 'Ticket', 'wp-chatbot-builder' ),
			'order'             => __( 'Order', 'wp-chatbot-builder' ),
			'user'              => __( 'User', 'wp-chatbot-builder' ),
			'venue'             => __( 'Venue', 'wp-chatbot-builder' ),
			'purchase_date'     => __( 'Purchase Date', 'wp-chatbot-builder' ),
			'schedule'          => __( 'Schedule', 'wp-chatbot-builder' ),
			'check_in_status'   => __( 'Check In Status', 'wp-chatbot-builder' ),
			'action'            => __( 'Action', 'wp-chatbot-builder' ),
		);
		return $columns;
	}

	/**
	 * This show event table list.
	 *
	 * @name column_default.
	 * @since      1.0.0
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 * @param array  $item  array of the items.
	 * @param string $column_name name of the colmn.
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'ticket':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'order':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'user':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'venue':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'purchase_date':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'schedule':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'check_in_status':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'action':
				return '<b>' . $item[ $column_name ] . '</b>';
			default:
				return false;
		}
	}

	/**
	 * This function is used for the add the checkbox.
	 *
	 * @name column_cb.
	 * @since      1.0.0
	 * @return array
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 * @param array $item array of the items.
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="mwb_etmfw_event_ids[]" value="%s" />',
			$item['id']
		);
	}

	/**
	 * Perform admin bulk action setting for event table.
	 *
	 * @name process_bulk_action.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function process_bulk_action() {
		if ( 'bulk-delete' === $this->current_action() ) {
			if ( isset( $_POST['mwb-etmfw-events'] ) ) {
				$mwb_event_nonce = sanitize_text_field( wp_unslash( $_POST['mwb-etmfw-events'] ) );
				if ( wp_verify_nonce( $mwb_event_nonce, 'mwb-etmfw-events' ) ) {
					if ( isset( $_POST['mwb_etmfw_event_ids'] ) && ! empty( $_POST['mwb_etmfw_event_ids'] ) ) {
						$all_id = map_deep( wp_unslash( $_POST['mwb_etmfw_event_ids'] ), 'sanitize_text_field' );
						foreach ( $all_id as $key => $value ) {
							wp_delete_post($value,true);
						}
					}
				}
			}
		}
		do_action( 'mwb_wpr_process_bulk_reset_option', $this->current_action(), $_POST );
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @name get_bulk_actions.
	 * @since      1.0.0
	 * @return array
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => __( 'Delete', 'event-tickets-manager-for-woocommerce' ),
		);
		return apply_filters( 'mwb_etmfw_perform_bulk_option', $actions );
	}

	/**
	 * Returns an associative array containing the bulk action for sorting.
	 *
	 * @name get_sortable_columns.
	 * @since      1.0.0
	 * @return array
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'order'         => array( 'order', false ),
			'purchase_date' => array( 'purchase_date', false ),
		);
		return $sortable_columns;
	}


	/**
	 * Prepare items for sorting.
	 *
	 * @name prepare_items.
	 * @since      1.0.0
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function prepare_items() {
		$per_page              = 10;
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->example_data = $this->get_attendees_data();
		$data               = $this->example_data;

		$current_page = $this->get_pagenum();
		$this->process_bulk_action();
		$total_items = count( $data );
		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->items = $data;
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);
	}

	/**
	 * This function return the attendees generated.
	 *
	 * @name get_attendees_data.
	 * @since      1.0.0
	 * @return array
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function get_attendees_data() {
		$event_attendees_details = array();
		$order_statuses = array(
			'wc-processing' => __( 'Processing', 'event-tickets-manager-for-woocommerce' ),
			'wc-completed'  => __( 'Completed', 'event-tickets-manager-for-woocommerce' ),
		);

		$shop_orders = new WP_Query(
			array(
				'post_type'         => 'shop_order',
				'post_status'       => array_keys( $order_statuses ),
				'posts_per_page'    => -1,
			)
		);
		if ( isset( $shop_orders ) && ! empty( $shop_orders ) ) {
			if ( isset( $shop_orders->posts ) && ! empty( $shop_orders->posts ) ) {
				foreach ( $shop_orders->posts as $shop_order ) {
					$order_id = $shop_order->ID;
					$order = wc_get_order( $order_id );
					foreach ( $order->get_items() as $item_id => $item ) {
						$product = $item->get_product();
						if ( 'event_ticket_manager' === $product->get_type() ) {
							if ( ! empty( $product ) ) {
								$pro_id = $product->get_id();
							}
							$mwb_etmfw_product_array = get_post_meta( $pro_id, 'mwb_etmfw_product_array', true );
							$start = isset( $mwb_etmfw_product_array['event_start_date_time'] ) ? $mwb_etmfw_product_array['event_start_date_time'] : '';
							$end = isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '';
							$ticket = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
							$venue = isset( $mwb_etmfw_product_array['etmfw_event_venue'] ) ? $mwb_etmfw_product_array['etmfw_event_venue'] : '';
							$order_date = $order->get_date_created()->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
							$user_id = ( 0 != $order->get_user_id() ) ? '#' . $order->get_user_id() : 'Guest';
							$checkin_status = 'Pending';
							$generated_tickets = get_post_meta( $pro_id, 'mwb_etmfw_generated_tickets', true );
							if ( ! empty( $generated_tickets ) ) {
								foreach ( $generated_tickets as $key => $value ) {
									if ( $ticket == $value['ticket'] ) {
										$checkin_status = $value['status'];
										if ( 'checked_in' === $checkin_status ) :
											$checkin_status = 'Checked In';
									endif;
									}
								}
							}
							$updated_meta_pdf = get_post_meta( $order_id, 'mwb_etmfw_order_meta_updated', true );
							if ( '' === $updated_meta_pdf ) {
								$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket . '.pdf';
							} else {
								$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket . '-new.pdf';
							}

							$event_attendees_details[] = array(
								'id'          		=> $order_id,
								'ticket'            => $item->get_name() . '#' . $ticket,
								'order'             => '<a href="' . admin_url( 'post.php?post=' . $order_id . '&action=edit' ) . '">#' . $order_id . '</a>',
								'user'              => $user_id,
								'venue'             => $venue,
								'purchase_date'     => $order_date,
								'schedule'          => mwb_etmfw_get_date_format( $start ) . '-' . mwb_etmfw_get_date_format( $end ),
								'check_in_status'   => $checkin_status,
								'action'            => '<a href="' . $upload_dir_path . '" target="_blank">View Ticket Pdf</a>',
							);
						}
					}
				}
			}
		}
		return $event_attendees_details;
	}
}
