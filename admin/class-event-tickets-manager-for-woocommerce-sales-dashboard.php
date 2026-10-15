<?php
/**
 * "Event Tickets Sales" wp-admin Dashboard widget.
 *
 * Net ticket sales and tickets sold this month, upcoming events, low ticket stock and
 * sold out events at a glance. Moved here from the Pro plugin (where it shipped in
 * 2.3.9) so every store gets it.
 *
 * The widget id (`wps_etmfwp_sales_dashboard`) and CSS classes are kept from the Pro
 * version on purpose: users keep their Screen Options (hidden/collapsed/position), and
 * an older Pro that still registers the widget simply replaces this registration with
 * the same widget instead of showing it twice.
 *
 * @link  https://wpswings.com/
 * @since 1.6.2
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/admin
 */

use Automattic\WooCommerce\Utilities\OrderUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sales dashboard widget class.
 */
class Event_Tickets_Manager_For_Woocommerce_Sales_Dashboard {

	/**
	 * Dashboard widget id (unchanged from Pro 2.3.9).
	 */
	const WIDGET_ID = 'wps_etmfwp_sales_dashboard';

	/**
	 * Plugin version, used for asset cache busting.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Initialize the class.
	 *
	 * @param string $version Plugin version.
	 */
	public function __construct( $version ) {
		$this->version = $version;
	}

	/**
	 * Register the "Event Tickets Sales" wp-admin Dashboard widget.
	 *
	 * @return void
	 */
	public function wps_etmfw_register_sales_dashboard_widget() {
		if ( ! current_user_can( 'view_woocommerce_reports' ) && ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		wp_add_dashboard_widget(
			self::WIDGET_ID,
			__( 'Event Tickets Sales', 'event-tickets-manager-for-woocommerce' ),
			array( $this, 'wps_etmfw_render_sales_dashboard_widget' )
		);
	}

	/**
	 * Enqueue the widget stylesheet on the Dashboard screen only.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 * @return void
	 */
	public function wps_etmfw_enqueue_sales_dashboard_styles( $hook_suffix ) {
		if ( 'index.php' !== $hook_suffix ) {
			return;
		}
		wp_enqueue_style(
			'event-tickets-manager-for-woocommerce-dashboard-widget',
			EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/css/event-tickets-manager-for-woocommerce-dashboard-widget.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Render the "Event Tickets Sales" Dashboard widget content.
	 *
	 * @return void
	 */
	public function wps_etmfw_render_sales_dashboard_widget() {
		$stats = $this->wps_etmfw_get_sales_dashboard_stats();

		/**
		 * Filter the stat rows shown in the Event Tickets Sales dashboard widget.
		 *
		 * Each row is an array with keys: class, icon (a dashicons-* class), width
		 * ('full' or 'half'), label, value (safe HTML/text) and url. Append to this
		 * array to add more stats to the widget without touching its render logic.
		 *
		 * @since 1.6.2
		 * @param array $stats Stat rows.
		 */
		$stats = apply_filters( 'wps_etmfw_sales_dashboard_stats', $stats );

		/**
		 * Filter name used while the widget lived in the Pro plugin; still applied so
		 * existing customisations keep working.
		 *
		 * @since 1.6.2
		 * @param array $stats Stat rows.
		 */
		$stats = apply_filters( 'wps_etmfwp_sales_dashboard_stats', $stats );

		echo '<ul class="wps-etmfwp-status-list">';
		foreach ( $stats as $stat ) {
			$width_class = 'full' === ( isset( $stat['width'] ) ? $stat['width'] : 'half' ) ? 'wps-etmfwp-full' : '';
			printf(
				'<li class="%1$s %2$s"><a href="%3$s"><span class="dashicons %4$s"></span><span class="wps-etmfwp-stat-label">%5$s</span><strong>%6$s</strong></a></li>',
				esc_attr( isset( $stat['class'] ) ? $stat['class'] : '' ),
				esc_attr( $width_class ),
				esc_url( isset( $stat['url'] ) ? $stat['url'] : '#' ),
				esc_attr( isset( $stat['icon'] ) ? $stat['icon'] : 'dashicons-chart-bar' ),
				esc_html( isset( $stat['label'] ) ? $stat['label'] : '' ),
				wp_kses_post( isset( $stat['value'] ) ? $stat['value'] : '' )
			);
		}
		echo '</ul>';
	}

	/**
	 * Build the default stat rows for the sales dashboard widget. Computed live on
	 * every render — the widget only loads on the wp-admin Dashboard screen, and a
	 * cached figure here would go stale right after the order/stock change a store
	 * owner is checking the widget for in the first place.
	 *
	 * @return array
	 */
	private function wps_etmfw_get_sales_dashboard_stats() {
		$orders_url          = OrderUtil::custom_orders_table_usage_is_enabled() ? admin_url( 'admin.php?page=wc-orders' ) : admin_url( 'edit.php?post_type=shop_order' );
		$totals              = $this->wps_etmfw_get_monthly_ticket_totals();
		$low_stock_threshold = absint( max( (int) get_option( 'woocommerce_notify_low_stock_amount' ), 1 ) );

		return array(
			array(
				'class' => 'wps-etmfwp-net-sales',
				'icon'  => 'dashicons-chart-line',
				'width' => 'full',
				'label' => __( 'Net ticket sales this month', 'event-tickets-manager-for-woocommerce' ),
				'value' => wc_price( $totals['net_sales'] ),
				'url'   => $orders_url,
			),
			array(
				'class' => 'wps-etmfwp-tickets-sold',
				'icon'  => 'dashicons-tickets-alt',
				'width' => 'full',
				'label' => __( 'Tickets sold this month', 'event-tickets-manager-for-woocommerce' ),
				'value' => number_format_i18n( $totals['tickets_sold'] ),
				'url'   => $orders_url,
			),
			array(
				'class' => 'wps-etmfwp-upcoming-events',
				'icon'  => 'dashicons-calendar-alt',
				'width' => 'half',
				'label' => __( 'Upcoming events', 'event-tickets-manager-for-woocommerce' ),
				'value' => number_format_i18n( $this->wps_etmfw_get_upcoming_events_count() ),
				'url'   => admin_url( 'edit.php?post_type=product&product_type=event_ticket_manager' ),
			),
			array(
				'class' => 'wps-etmfwp-low-stock',
				'icon'  => 'dashicons-warning',
				'width' => 'half',
				'label' => __( 'Low ticket stock', 'event-tickets-manager-for-woocommerce' ),
				'value' => number_format_i18n( $this->wps_etmfw_get_low_stock_events_count( $low_stock_threshold ) ),
				'url'   => admin_url( 'admin.php?page=wc-admin&type=lowstock&path=%2Fanalytics%2Fstock' ),
			),
			array(
				'class' => 'wps-etmfwp-out-of-stock',
				'icon'  => 'dashicons-dismiss',
				'width' => 'half',
				'label' => __( 'Sold out events', 'event-tickets-manager-for-woocommerce' ),
				'value' => number_format_i18n( $this->wps_etmfw_get_out_of_stock_events_count() ),
				'url'   => admin_url( 'edit.php?post_type=product&product_type=event_ticket_manager&stock_status=outofstock' ),
			),
		);
	}

	/**
	 * Sum this month's event-ticket net sales and quantity from paid orders.
	 *
	 * @return array{net_sales: float, tickets_sold: int}
	 */
	private function wps_etmfw_get_monthly_ticket_totals() {
		$net_sales    = 0.0;
		$tickets_sold = 0;

		$orders = wc_get_orders(
			array(
				'status'       => array( 'wc-completed', 'wc-processing' ),
				'date_created' => '>=' . gmdate( 'Y-m-01' ),
				'limit'        => -1,
				'return'       => 'objects',
			)
		);

		foreach ( $orders as $order ) {
			if ( ! $order instanceof WC_Order ) {
				continue;
			}
			foreach ( $order->get_items() as $item ) {
				$product = $item->get_product();
				if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
					$net_sales    += (float) $item->get_total();
					$tickets_sold += (int) $item->get_quantity();
				}
			}
		}

		return array(
			'net_sales'    => $net_sales,
			'tickets_sold' => $tickets_sold,
		);
	}

	/**
	 * Published event ticket product IDs.
	 *
	 * @return int[]
	 */
	private function wps_etmfw_get_event_product_ids() {
		return wc_get_products(
			array(
				'type'   => 'event_ticket_manager',
				'status' => 'publish',
				'limit'  => -1,
				'return' => 'ids',
			)
		);
	}

	/**
	 * Count published events whose start date is still in the future.
	 *
	 * @return int
	 */
	private function wps_etmfw_get_upcoming_events_count() {
		$count = 0;
		$now   = current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested -- event dates are stored as local wall-clock times.

		foreach ( $this->wps_etmfw_get_event_product_ids() as $product_id ) {
			$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
			$start_date              = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';

			if ( '' !== $start_date && strtotime( $start_date ) > $now ) {
				++$count;
			}
		}

		return $count;
	}

	/**
	 * Count stock-managed events at or below the low-stock threshold (but not sold out).
	 *
	 * @param int $threshold Low stock threshold.
	 * @return int
	 */
	private function wps_etmfw_get_low_stock_events_count( $threshold ) {
		$count = 0;

		foreach ( $this->wps_etmfw_get_event_product_ids() as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( ! $product instanceof WC_Product || ! $product->get_manage_stock() ) {
				continue;
			}

			$stock_quantity = $product->get_stock_quantity();
			if ( null !== $stock_quantity && $stock_quantity > 0 && $stock_quantity <= $threshold ) {
				++$count;
			}
		}

		return $count;
	}

	/**
	 * Count events currently marked out of stock.
	 *
	 * @return int
	 */
	private function wps_etmfw_get_out_of_stock_events_count() {
		$count = 0;

		foreach ( $this->wps_etmfw_get_event_product_ids() as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( $product instanceof WC_Product && 'outofstock' === $product->get_stock_status() ) {
				++$count;
			}
		}

		return $count;
	}
}
