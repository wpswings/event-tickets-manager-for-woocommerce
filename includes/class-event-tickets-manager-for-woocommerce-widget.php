<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Event_Tickets_Manager_For_Woocommerce_Widget extends WP_Widget {

	/**
	 * Set up event widget for the plugin.
	 *
	 * Set the widget id the widget name and widget description that can be used throughout the widget.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		parent::__construct(
			// widget ID.
			'event_tickets_manager_for_woocommerce_widget',
			// widget name.
			__( 'Event Widget', ' event-tickets-manager-for-woocommerce' ),
			// widget description.
			array( 'description' => __( 'Event Widget', 'event-tickets-manager-for-woocommerce' ) )
		);
	}

	/**
	 * Set up event widget data for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		$query_args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page'    => 5,
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
			echo wp_kses_post( $before_widget );
			$current_timestamp = current_time( 'timestamp' );
			if ( $title ) {
				echo wp_kses_post( $before_title . $title . $after_title );
			}
			?>
			<ul class="product_list_widget">
			<?php
			while ( $query_data->have_posts() ) {
				$query_data->the_post();
				global $product;
				$mwb_etmfw_product_array = get_post_meta( $product->get_id(), 'mwb_etmfw_product_array', true );
				$start_date = isset( $mwb_etmfw_product_array['event_start_date_time'] ) ? $mwb_etmfw_product_array['event_start_date_time'] : '';
				$end_date = isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '';
				$end_date_timestamp = strtotime( $end_date );
				if ( $end_date_timestamp > $current_timestamp ) {
					?>
					<li>
						<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
							<?php echo wp_kses_post( $product->get_image() ); ?>
							<?php echo esc_html( $product->get_title() ); ?>
						</a>
						<?php
							echo esc_html( mwb_etmfw_get_date_format( $start_date ) );
							echo esc_html( ' To ' );
							echo esc_html( mwb_etmfw_get_date_format( $end_date ) );
						?>
					</li>
					<?php
				}
			}?>
			</ul>
			<?php
			echo wp_kses_post( $after_widget );
		}

		wp_reset_postdata();
	}

	/**
	 * Create input form to get widget data.
	 *
	 * @since    1.0.0
	 */
	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Upcoming Events', 'event-tickets-manager-for-woocommerce' );
		}
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}

	/**
	 * Update widget information when modification is done.
	 *
	 * @since    1.0.0
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}
