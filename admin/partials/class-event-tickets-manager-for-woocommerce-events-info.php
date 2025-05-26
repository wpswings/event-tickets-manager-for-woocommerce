<?php
/**
 * Exit if accessed directly
 *
 * @since      1.0.0
 * @package    Event_Tickets_Manager_For_Woocommerce
 */

use Automattic\WooCommerce\Utilities\OrderUtil;

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
 * @author WPSwings<webmaster@wpswings.com>
 * @link https://wpswings.com/
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
	 * @author WPSwings<webmaster@wpswings.com>
	 * @link https://wpswings.com/
	 */
	public function get_columns() {
		$columns = array(
			'cb'                => '<input type="checkbox" />',
			'check_in_status'   => __( 'Check-In Status', 'event-tickets-manager-for-woocommerce' ),
			'event'             => __( 'Event', 'event-tickets-manager-for-woocommerce' ),
			'ticket'            => __( 'Ticket', 'event-tickets-manager-for-woocommerce' ),
			'ticket_status'     => __( 'Ticket Status', 'event-tickets-manager-for-woocommerce' ),
			'order'             => __( 'Order', 'event-tickets-manager-for-woocommerce' ),
			'user'              => __( 'User', 'event-tickets-manager-for-woocommerce' ),
			'venue'             => __( 'Venue', 'event-tickets-manager-for-woocommerce' ),
			'purchase_date'     => __( 'Purchase Date', 'event-tickets-manager-for-woocommerce' ),
			'schedule'          => __( 'Schedule', 'event-tickets-manager-for-woocommerce' ),
			'event_subscribe'    => __( 'Event Subscribe', 'event-tickets-manager-for-woocommerce' ),
			'action'            => __( 'Action', 'event-tickets-manager-for-woocommerce' ),
		);
		return $columns;
	}

	/**
	 * This show event table list.
	 *
	 * @name column_default.
	 * @since      1.0.0
	 * @author WPSwings<webmaster@wpswings.com>
	 * @link https://wpswings.com/
	 * @param array  $item  array of the items.
	 * @param string $column_name name of the colmn.
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'check_in_status':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'event':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'ticket':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'ticket_status':
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
			case 'event_subscribe':
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
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://wpswings.com/
	 * @param array $item array of the items.
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="wps_etmfw_event_ids[]" value="%s" />',
			$item['id']
		);
	}

	/**
	 * Perform admin bulk action setting for event table.
	 *
	 * @name process_bulk_action.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://wpswings.com/
	 */
	public function process_bulk_action() {
		if ( 'bulk-delete' === $this->current_action() ) {
			if ( isset( $_POST['wps-etmfw-events'] ) && '' !== $_POST['wps-etmfw-events'] ) {
				$wps_event_nonce = sanitize_text_field( wp_unslash( $_POST['wps-etmfw-events'] ) );
				if ( wp_verify_nonce( $wps_event_nonce, 'wps-etmfw-events' ) ) {
					if ( isset( $_POST['wps_etmfw_event_ids'] ) && ! empty( $_POST['wps_etmfw_event_ids'] ) ) {
						$all_id = map_deep( wp_unslash( $_POST['wps_etmfw_event_ids'] ), 'sanitize_text_field' );
						foreach ( $all_id as $key => $value ) {
							wp_trash_post( $value, true );
							$wps_reload_url = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
							header( 'Location: ' . $wps_reload_url );
						}
					}
				}
			}
		}
		do_action( 'wps_wpr_process_bulk_reset_option', $this->current_action(), $_POST );
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @name get_bulk_actions.
	 * @since      1.0.0
	 * @return array
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://wpswings.com/
	 */
	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => __( 'Delete', 'event-tickets-manager-for-woocommerce' ),
		);
		return apply_filters( 'wps_etmfw_perform_bulk_option', $actions );
	}


	/**
	 * Prepare items for sorting.
	 *
	 * @name prepare_items.
	 * @since      1.0.0
	 * @author WPSwings<webmaster@wpswings.com>
	 * @link https://wpswings.com/
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
	 * Extra box for Export Report.
	 *
	 * @param  array $which location.
	 */
	public function extra_tablenav( $which ) {
		if ( 'top' === $which ) {
        	do_action( 'wps_etmfw_event_export_extra_tablenav', $which );
		}
    }

	/**
	 * This function return the attendees generated.
	 *
	 * @name get_attendees_data.
	 * @since      1.0.0
	 * @return array
	 * @author WPSwings<webmaster@wpswings.com>
	 * @link https://wpswings.com/
	 */
	public function get_attendees_data() {
		$event_attendees_details = array();
		$order_statuses = array(
			'wc-completed'  => __( 'Completed', 'event-tickets-manager-for-woocommerce' ),
			'wc-processing'  => __( 'Processing', 'event-tickets-manager-for-woocommerce' ),

		);

		$args = array(
			'status' => array( 'wc-processing', 'wc-completed' ),
			'return' => 'ids',
			'limit' => -1,
		);
		$shop_orders = wc_get_orders( $args );
		if ( isset( $shop_orders ) && ! empty( $shop_orders ) ) {

			foreach ( $shop_orders as $shop_order ) {
				$order_id = $shop_order;
				$order = wc_get_order( $shop_order );
				foreach ( $order->get_items() as $item_id => $item ) {
					$product = $item->get_product();
					$item_meta_data = $item->get_meta_data();
					if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
						if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
							// HPOS usage is enabled.
							$ticket = $order->get_meta( "event_ticket#$order_id#$item_id", true );
						} else {
							$ticket = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
						}
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

						if ( is_array( $ticket ) && ! empty( $ticket ) ) {
							$length = count( $ticket );
							for ( $i = 0; $i < $length; $i++ ) {

								if ( ! empty( $product ) ) {
									$pro_id = $product->get_id();
								}
								$wps_etmfw_product_array = get_post_meta( $pro_id, 'wps_etmfw_product_array', true );
								$start = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
								$end = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
								if ( empty( $venue ) ) {
									$venue = isset( $wps_etmfw_product_array['etmfw_event_venue'] ) ? $wps_etmfw_product_array['etmfw_event_venue'] : '';
								}
								$order_date = $order->get_date_created()->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
								$user_id = ( 0 != $order->get_user_id() ) ? '#' . $order->get_user_id() : 'Guest';
								$checkin_status = '';
								$generated_tickets = get_post_meta( $pro_id, 'wps_etmfw_generated_tickets', true );
								if ( ! empty( $generated_tickets ) ) {
									foreach ( $generated_tickets as $key => $value ) {
										if ( $ticket[ $i ] == $value['ticket'] ) {
											$checkin_status = $value['status'];
											$ticket_status  = isset( $value['is_waiting'] ) ? $value['is_waiting'] : '';
											if ( 'checked_in' === $checkin_status ) :
												$checkin_status = '<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/checked.png" width="20" height="20" title="' . esc_html__( 'Checked-In', 'event-tickets-manager-for-woocommerce' ) . '">';
												else :
													$checkin_status = '<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/pending.svg" width="20" height="20" title="' . esc_html__( 'Pending', 'event-tickets-manager-for-woocommerce' ) . '">';
												endif;
										}
									}
								}
								if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
									// HPOS usage is enabled.
									$updated_meta_pdf = $order->get_meta( 'wps_etmfw_order_meta_updated', true );
								} else {
									$updated_meta_pdf = get_post_meta( $order_id, 'wps_etmfw_order_meta_updated', true );
								}
								if ( '' === $updated_meta_pdf ) {
									$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket[ $i ] . '.pdf';
								} else {
									$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket[ $i ] . '-new.pdf';
								}

								if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
									// HPOS usage is enabled.
									$event_subscrib =$order->get_meta( 'wps_etmfw_subscribe_for_events');
								} else {
									$event_subscrib = get_post_meta( $order_id, 'wps_etmfw_subscribe_for_events');
								}
	
								if(isset($event_subscrib) && 'yes' == $event_subscrib){
									$wps_event_subscribe = 'Yes';
								} else {
									$wps_event_subscribe = 'No';
								}

								$event_attendees_details[] = array(
									'id'                => $order_id,
									'check_in_status'   => $checkin_status,
									'event'             => $item->get_name(),
									'ticket'            => $ticket[ $i ],
									'ticket_status'     => ( 'yes' === $ticket_status ) ? __( 'Waiting', 'event-tickets-manager-for-woocommerce' ) : __( 'Confirmed', 'event-tickets-manager-for-woocommerce' ),
									'order'             => '<a href="' . admin_url( 'post.php?post=' . $order_id . '&action=edit' ) . '">#' . $order_id . '</a>',
									'user'              => $user_id,
									'venue'             => $venue,
									'purchase_date'     => $order_date,
									'schedule'          => wps_etmfw_get_date_format( $start ) . '-' . wps_etmfw_get_date_format( $end ),
									'event_subscribe'   => $wps_event_subscribe,
									'action'            => '<a href="' . $upload_dir_path . '" target="_blank">
										<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/view_ticket.svg" width="20" height="20" title="' . esc_html__( 'View Ticket', 'event-tickets-manager-for-woocommerce' ) . '"></a>',
								);
							}
						} else if ( '' !== $ticket ) {
							if ( ! empty( $product ) ) {
								$pro_id = $product->get_id();
							}
							$wps_etmfw_product_array = get_post_meta( $pro_id, 'wps_etmfw_product_array', true );
							$start = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
							$end = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
							if ( empty( $venue ) ) {
								$venue = isset( $wps_etmfw_product_array['etmfw_event_venue'] ) ? $wps_etmfw_product_array['etmfw_event_venue'] : '';
							}
							$order_date = $order->get_date_created()->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
							$user_id = ( 0 != $order->get_user_id() ) ? '#' . $order->get_user_id() : 'Guest';
							$checkin_status = '';
							$generated_tickets = get_post_meta( $pro_id, 'wps_etmfw_generated_tickets', true );
							if ( ! empty( $generated_tickets ) ) {
								foreach ( $generated_tickets as $key => $value ) {
									if ( $ticket == $value['ticket'] ) {
										$checkin_status = $value['status'];
										$ticket_status  = isset( $value['is_waiting'] ) ? $value['is_waiting'] : '';
										if ( 'checked_in' === $checkin_status ) :
											$checkin_status = '<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/checked.png" width="20" height="20" title="' . esc_html__( 'Checked-In', 'event-tickets-manager-for-woocommerce' ) . '">';
											else :
												$checkin_status = '<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/pending.svg" width="20" height="20" title="' . esc_html__( 'Pending', 'event-tickets-manager-for-woocommerce' ) . '">';
											endif;
									}
								}
							}

							if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
								// HPOS usage is enabled.
								$updated_meta_pdf = $order->get_meta( 'wps_etmfw_order_meta_updated', true );
							} else {
								$updated_meta_pdf = get_post_meta( $order_id, 'wps_etmfw_order_meta_updated', true );
							}

							if ( '' === $updated_meta_pdf ) {
								$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket . '.pdf';
							} else {
								$upload_dir_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $order_id . $ticket . '-new.pdf';
							}


							if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
								// HPOS usage is enabled.
								$event_subscrib =$order->get_meta( 'wps_etmfw_subscribe_for_events');
							} else {
								$event_subscrib = get_post_meta( $order_id, 'wps_etmfw_subscribe_for_events');
							}

							if(isset($event_subscrib) && 'yes' == $event_subscrib){
								$wps_event_subscribe = 'Yes';
							} else {
								$wps_event_subscribe = 'No';
							}

							$event_attendees_details[] = array(
								'id'                => $order_id,
								'check_in_status'   => $checkin_status,
								'event'             => $item->get_name(),
								'ticket'            => $ticket,
								'ticket_status'     => ( 'yes' === $ticket_status ) ? __( 'Waiting', 'event-tickets-manager-for-woocommerce' ) : __( 'Confirmed', 'event-tickets-manager-for-woocommerce' ),
								'order'             => '<a href="' . admin_url( 'post.php?post=' . $order_id . '&action=edit' ) . '">#' . $order_id . '</a>',
								'user'              => $user_id,
								'venue'             => $venue,
								'purchase_date'     => $order_date,
								'schedule'          => wps_etmfw_get_date_format( $start ) . '-' . wps_etmfw_get_date_format( $end ),
								'event_subscribe'   => $wps_event_subscribe,
								'action'            => '<a href="' . $upload_dir_path . '" target="_blank">
										<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/view_ticket.svg" width="20" height="20" title="' . esc_html( 'View Ticket', 'event-tickets-manager-for-woocommerce' ) . '"></a>',
							);
						}
					}
				}
			}
		}
		$event_attendees_details = apply_filters( 'wps_etmfw_unfiltered_events_data', $event_attendees_details );
		$filtered_data = array();
		$secure_nonce      = wp_create_nonce( 'wps-event-auth-nonce' );
        $id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-event-auth-nonce' );
        if ( ! $id_nonce_verified ) {
            wp_die( esc_html__( 'Nonce Not verified', 'event-tickets-manager-for-woocommerce' ) );
        }
		if ( isset( $_REQUEST['s'] ) && '' !== $_REQUEST['s'] ) {
			$data           = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
			foreach ( $event_attendees_details as $key => $value ) {
				foreach ( array_values( $value ) as $data_value ) {
					if ( stripos( $data_value, $data ) !== false ) {
						$filtered_data[] = $value;
						break;
					}
				}
			}
			return $filtered_data;
		}
		return apply_filters( 'wps_etmfw_filtered_events_data', $event_attendees_details );
	}
}
