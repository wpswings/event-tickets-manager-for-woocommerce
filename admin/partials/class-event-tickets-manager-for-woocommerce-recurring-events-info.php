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
 * @name Event_Tickets_Manager_For_Woocommerce_Recurring_Events_Info
 * @since      1.0.0
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @author WPSwings<webmaster@wpswings.com>
 * @link https://wpswings.com/
 */
class Event_Tickets_Manager_For_Woocommerce_Recurring_Events_Info extends WP_List_Table {

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
			'wps_recurring_title'   => __( 'Event Title', 'event-tickets-manager-for-woocommerce' ),
			'venue'             => __( 'Venue', 'event-tickets-manager-for-woocommerce' ),
			'wps_date_time'            => __( 'Date/Time', 'event-tickets-manager-for-woocommerce' ),
			'wps_parent'             => __( 'Parent Event', 'event-tickets-manager-for-woocommerce' ),
		);
		return $columns;
	}

	/**
	 * This function is used to add edit and view option.
	 *
	 * @name item.
	 * @since      1.0.0
	 * @return array
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://wpswings.com/
	 * @param array $item array of the items.
	 */
	public function column_wps_recurring_title( $item ) {

        $item = apply_filters( 'wps_etmfw_view_edit_options_recurring_event', $item );

        if ( is_array( $item ) ) {
            return '<b>' . esc_html( $item['wps_recurring_title'] ) . '</b>';
        }
    
        return $item;
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
			case 'wps_recurring_title':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'venue':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'wps_date_time':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'wps_parent':
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
			'<input type="checkbox" name="wps_etmfw_recurring_event_ids[]" value="%s" />',
			$item['id']
		);
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
		return apply_filters( 'wps_etmfw_recurring_perform_bulk_option', $actions );
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
	 * Extra box for Filter Parent Product Wise.
	 *
	 * @param  array $which location.
	 */
	public function extra_tablenav( $which ) {
		if ( 'top' === $which ) {
            do_action( 'wps_etmfw_extra_tablenav_for_recurring', $which );
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
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'parent_of_recurring', 
					'compare' => 'EXISTS',
				),
			),
			'product_type' => 'event_ticket_manager',
		);

		$event_attendees_details = array();

		$products = wc_get_products( $args );

		if ( ! empty( $products ) ) {
			foreach ( $products as $product ) {
				// Output or manipulate the product data here.
				$wps_etmfw_product_array = get_post_meta( $product->get_id(), 'wps_etmfw_product_array', true );
				$start = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
				$end = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
				$schdule_date = wps_etmfw_get_date_format( $start ) . ' To ' . wps_etmfw_get_date_format( $end );
				$schdule_date = str_replace( ',', '', $schdule_date );
				$wps_recurring_parent = get_post_meta( $product->get_id(), 'parent_of_recurring' );
				if ( is_array( $wps_recurring_parent ) && isset( $wps_recurring_parent[0] ) ) {
					$wps_parent_product = $wps_recurring_parent[0];
					$event_attendees_details[] = array(
						'id'                  => $product->get_id(),
						'wps_recurring_title' => $product->get_name(),
						'venue'               => $wps_etmfw_product_array['etmfw_event_venue'],
						'wps_date_time'       => $schdule_date,
						'wps_parent'          => '<img src="'.esc_html( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ) . 'admin/src/images/Support.svg" alt="alternative text" title="'.__( 'Parent Event Is The Event From which The Respective Event Is Created', 'event-tickets-manager-for-woocommerce' ).'"/> <a href="' . admin_url( 'post.php?post=' . $wps_parent_product . '&action=edit' ) . '">#' . $wps_parent_product . '</a>',
						'wps_parent_id'       => $wps_parent_product,
					);
				}
			}
		}

		$event_attendees_details = apply_filters( 'wps_etmfw_unfiltered_recurring_events_data', $event_attendees_details );
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
		return apply_filters( 'wps_etmfw_filtered_recurring__events_data', $event_attendees_details );
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
			if ( isset( $_GET['wps-etmfw-events'] ) && '' !== $_GET['wps-etmfw-events'] ) {
				$wps_event_nonce = sanitize_text_field( wp_unslash( $_GET['wps-etmfw-events'] ) );
				if ( wp_verify_nonce( $wps_event_nonce, 'wps-etmfw-events' ) ) {
					if ( isset( $_GET['wps_etmfw_recurring_event_ids'] ) && ! empty( $_GET['wps_etmfw_recurring_event_ids'] ) ) {
						$all_id = map_deep( wp_unslash( $_GET['wps_etmfw_recurring_event_ids'] ), 'sanitize_text_field' );
						if ( is_array( $all_id ) && ! empty( $all_id ) ) {
							foreach ( $all_id as $key => $value ) {
								// Delete the post.
								wp_delete_post( $value, true ); // Set the second parameter to true to force deletion.

								// Delete all associated meta values.
								$meta_keys = get_post_custom_keys( $value );
								if ( ! empty( $meta_keys ) ) {
									foreach ( $meta_keys as $meta_key ) {
										delete_post_meta( $value, $meta_key );
									}
								}
							}
							$wps_reload_url = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
							header( 'Location: ' . $wps_reload_url );
						}
					}
				}
			}
		}
		do_action( 'wps_wpr_process_bulk_reset_option', $this->current_action(), $_GET );
	}

	/**
	 * Decide which columns to activate the sorting functionality on
	 *
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
	public function get_sortable_columns() {
		$sortable = array();
		$sortable = array(
			'wps_recurring_title' => 'wps_recurring_title_id',
			'wps_parent' => 'wps_parent_link',
			'venue' => 'venue_link',
			'wps_date_time' => 'wps_date_time_link',
		);
		return $sortable;
	}
}
