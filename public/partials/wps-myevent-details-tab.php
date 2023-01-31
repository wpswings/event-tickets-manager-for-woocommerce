<?php
/**
 * This file is used to include my event tab.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/emails
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
		$event_attendees_details = array();
		$customer = wp_get_current_user(); // do this when user is logged in.
		$order_statuses = array(
			'wc-completed'  => __( 'Completed', 'event-tickets-manager-for-woocommerce' ),
		);
		$customer_orders = get_posts(
			array(
				'numberposts' => -1,
				'meta_key' => '_customer_user',
				'orderby' => 'date',
				'order' => 'DESC',
				'meta_value' => get_current_user_id(),
				'post_type' => wc_get_order_types(),
				'post_status'       => array_keys( $order_statuses ),
				'fields' => 'ids',
			)
		);

		$user_orders = array();
		foreach ( $customer_orders as $order_id ) {
			$order_obj = wc_get_order( $order_id );

			foreach ( $order_obj->get_items() as $item_id => $item ) {
				$product = $item->get_product();
				$orderme = $order_obj->get_id();
				if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
					$ticket = get_post_meta( $order_obj->get_id(), "event_ticket#$orderme#$item_id", true );
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
											$checkin_status = '<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/checked.png" width="20" height="20" title="' . esc_html( 'Checked-In', 'event-tickets-manager-for-woocommerce' ) . '">';
										else :
											$upload_dir_path  = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $value['order_id'] . $value['ticket'] . '.pdf';
											$checkin_status = '<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/pending.svg" width="20" height="20" title="' . esc_html( 'Pending', 'event-tickets-manager-for-woocommerce' ) . '">';
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
								'order'             => '<a title="Ticket Order Detail" href="' . esc_url( $order_received_url ) . '">#' . $order_obj->get_id() . '</a>',
								'user'              => $user_id,
								'venue'             => $venue,
								'purchase_date'     => $order_date,
								'schedule'          => wps_etmfw_get_date_format( $start ) . '-' . wps_etmfw_get_date_format( $end ),
								'action'            => '<br><a title="Download Ticket" href="' . $upload_dir_path . '" target="_blank">' . esc_html( 'View', 'event-tickets-manager-for-woocommerce' ) . '</a>',
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
						  $ticket = get_post_meta( $order_obj->get_id(), "event_ticket#$orderme#$item_id", true );

						if ( ! empty( $generated_tickets ) ) {
							foreach ( $generated_tickets as $key => $value ) {
								if ( $ticket == $value['ticket'] ) {
									$checkin_status = $value['status'];

									if ( 'checked_in' === $checkin_status && $order_obj->get_id() == $value['order_id'] ) {
										  $upload_dir_path  = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $value['order_id'] . $value['ticket'] . '.pdf';
										  $checkin_status = '<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/checked.png" width="20" height="20" title="' . esc_html( 'Checked-In', 'event-tickets-manager-for-woocommerce' ) . '">';
									} else {
										$upload_dir_path  = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL . '/events_pdf/events' . $value['order_id'] . $value['ticket'] . '.pdf';
										$checkin_status = '<img src="' . esc_attr( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . '/admin/src/images/pending.svg" width="20" height="20" title="' . esc_html( 'Pending', 'event-tickets-manager-for-woocommerce' ) . '">';
									}
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
							'order'             => '<a title="Ticket Order Detail" href="' . esc_url( $order_received_url ) . '">#' . $order_obj->get_id() . '</a>',
							'user'              => $user_id,
							'venue'             => $venue,
							'purchase_date'     => $order_date,
							'schedule'          => wps_etmfw_get_date_format( $start ) . '-' . wps_etmfw_get_date_format( $end ),
							'action'            => '<br><a title="Download Ticket" href="' . $upload_dir_path . '" target="_blank">' . esc_html( 'View', 'event-tickets-manager-for-woocommerce' ) . '</a>',
						);
					}
				}
			}
		}
		if ( ! empty( $event_attendees_details ) && is_array( $event_attendees_details ) ) {
			$wps_myevent_tab_html = '';
			$wps_currency_symbol = get_option( 'woocommerce_currency' );
			$wps_myevent_tab_html .= "<table class='woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table' id ='wps_myevent_table_id'>";
			$wps_myevent_tab_html .= "<tr><th class='woocommerce-orders-table__header'>Event</th><th class='woocommerce-orders-table__header'>Event Date</th><th class='woocommerce-orders-table__header'>Event Status</th><th class='woocommerce-orders-table__header'>Price</th><th class='woocommerce-orders-table__header'>Action</th></tr>";

			foreach ( $event_attendees_details as $mks ) {
				if ( in_array( 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
					$wps_myevent_tab_html .= '<tr><td>' . rtrim( $mks['event'] ) . '</td><td>' . rtrim( $mks['schedule'] ) . '</td><td>' . rtrim( $mks['check_in_status'] ) . '</td><td>' . wc_price( rtrim( $mks['price'] ) ) . '</td><td>' . rtrim( $mks['order'] ) . rtrim( $mks['action'] ) . '</td></tr>';
				} else {
					$wps_myevent_tab_html .= '<tr><td>' . rtrim( $mks['event'] ) . '</td><td>' . rtrim( $mks['schedule'] ) . '</td><td>' . rtrim( $mks['check_in_status'] ) . '</td><td>' . wc_price( rtrim( $mks['price'] ) ) . '</td><td>' . rtrim( $mks['order'] ) . '</td></tr>';
				}
			}
			$wps_myevent_tab_html .= '</table></div>';

			echo wp_kses_post( $wps_myevent_tab_html );
		} else {
			esc_html_e( 'No Event Ticket has been purchased yet.', 'membership-for-woocommerce' );
		}
