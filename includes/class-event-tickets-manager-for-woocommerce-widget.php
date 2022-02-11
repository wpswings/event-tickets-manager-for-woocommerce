<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wpswings.com/
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
 * @author     WPSwings <webmaster@wpswings.com>
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
			__( 'Event Ticket Widget', 'event-tickets-manager-for-woocommerce' ),
			// widget description.
			array( 'description' => __( 'Event Ticket Widget', 'event-tickets-manager-for-woocommerce' ) )
		);
	}

	/**
	 * Set up event widget data for the plugin.
	 *
	 * @param array $args Arguments.
	 * @param array $instance Instance.
	 * @since    1.0.0
	 */
	public function widget( $args, $instance ) {
		// Check the widget options.
		$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$select   = isset( $instance['select'] ) ? $instance['select'] : '';
		$radio = ! empty( $instance['radio'] ) ? $instance['radio'] : 'list';

		if ( 'calendar' === $radio ) {
			echo '<div id="calendar"></div>';
		} else {
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

				if ( $title ) {
					echo wp_kses_post( $title );
				}
				?>
				<ul class="product_list_widget">
				<?php
				while ( $query_data->have_posts() ) {
					$query_data->the_post();
					$this->wps_generate_list_view( $select );
				}
				?>
				</ul>
				<?php

			}
			wp_reset_postdata();
		}
	}

	/**
	 * Creates list view.
	 *
	 * @since 1.0.0
	 * @name wps_generate_list_view().
	 * @param string $select selected view.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://wpswings.com/
	 */
	public function wps_generate_list_view( $select ) {
		global $product;
		$wps_etmfw_product_array = get_post_meta( $product->get_id(), 'wps_etmfw_product_array', true );
		$start_date = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
		$end_date = isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '';
		$current_timestamp = current_time( 'timestamp' );
		$end_date_timestamp = strtotime( $end_date );
		$start_date_timestamp = strtotime( gmdate( 'Y-m-d', strtotime( $start_date ) ) );
		$current_timestamp = strtotime( gmdate( 'Y-m-d', $current_timestamp ) );
		switch ( $select ) {
			case 'all':
				?>
				<li>
					<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
						<?php echo wp_kses_post( $product->get_image() ); ?>
						<?php echo esc_html( $product->get_title() ); ?>
					</a>
					<?php
						echo esc_html( wps_etmfw_get_date_format( $start_date ) );
						echo esc_html( ' To ' );
						echo esc_html( wps_etmfw_get_date_format( $end_date ) );
					?>
				</li>
				<?php
				break;

			case 'future':
				if ( $end_date_timestamp > $current_timestamp ) {
					?>
					<li>
						<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
							<?php echo wp_kses_post( $product->get_image() ); ?>
							<?php echo esc_html( $product->get_title() ); ?>
						</a>
						<?php
							echo esc_html( wps_etmfw_get_date_format( $start_date ) );
							echo esc_html( ' To ' );
							echo esc_html( wps_etmfw_get_date_format( $end_date ) );
						?>
					</li>
					<?php
				}
				break;

			case 'past':
				if ( $end_date_timestamp < $current_timestamp ) {
					?>
					<li>
						<a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
							<?php echo wp_kses_post( $product->get_image() ); ?>
							<?php echo esc_html( $product->get_title() ); ?>
						</a>
						<?php
							echo esc_html( wps_etmfw_get_date_format( $start_date ) );
							echo esc_html( ' To ' );
							echo esc_html( wps_etmfw_get_date_format( $end_date ) );
						?>
					</li>
					<?php
				}
				break;

			default:
				// code...
				break;
		}
	}

	/**
	 * Create input form to get widget data.
	 *
	 * @name form().
	 * @param array $instance Instance.
	 * @since    1.0.0
	 */
	public function form( $instance ) {

		$setting_title_id   = $this->get_field_id( 'title' );
		$setting_title_name = $this->get_field_name( 'title' );
		$setting_radio_id   = $this->get_field_id( 'radio' );
		$setting_radio_name = $this->get_field_name( 'radio' );
		$setting_select_id  = $this->get_field_id( 'select' );
		$setting_select_name = $this->get_field_name( 'select' );

		$title_val   = isset( $instance['title'] ) ? wp_strip_all_tags( $instance['title'] ) : 'New title';
		$radio_val   = isset( $instance['radio'] ) ? wp_strip_all_tags( $instance['radio'] ) : 'list';
		$select_val  = isset( $instance['select'] ) ? wp_strip_all_tags( $instance['select'] ) : '';
		?>

		<p>
			<label for="<?php echo esc_attr( $setting_title_id ); ?>"><?php esc_html_e( 'Widget Title', 'event-tickets-manager-for-woocommerce' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $setting_title_id ); ?>" name="<?php echo esc_attr( $setting_title_name ); ?>" type="text" value="<?php echo esc_attr( $title_val ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $setting_radio_id ); ?>"><?php esc_html_e( 'Event View', 'event-tickets-manager-for-woocommerce' ); ?></label><br>
			<input id="<?php echo esc_attr( $setting_radio_id ); ?>" name="<?php echo esc_attr( $setting_radio_name ); ?>" type="radio" value="list" <?php echo esc_html( 'list' === $radio_val ) ? 'checked' : ''; ?> />
			<label for="<?php echo esc_attr( $setting_radio_id ); ?>"><?php esc_html_e( 'List', 'event-tickets-manager-for-woocommerce' ); ?></label>

			<input id="<?php echo esc_attr( $setting_radio_id ); ?>" name="<?php echo esc_attr( $setting_radio_name ); ?>" type="radio" value="calendar" <?php echo esc_html( 'calendar' === $radio_val ) ? 'checked' : ''; ?> />
			<label for="<?php echo esc_attr( $setting_radio_id ); ?>"><?php esc_html_e( 'Calendar', 'event-tickets-manager-for-woocommerce' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $setting_select_id ); ?>"><?php esc_html_e( 'Scope of the Event', 'event-tickets-manager-for-woocommerce' ); ?></label>
			<select name="<?php echo esc_attr( $setting_select_name ); ?>" id="<?php echo esc_attr( $setting_select_id ); ?>" class="widefat">
			<?php
			$scope_options = $this->wps_etmfw_get_scopes();
			foreach ( $scope_options as $key => $name ) {
				echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" ' . selected( $select_val, $key, false ) . '>' . esc_html( $name ) . '</option>';

			}
			?>
			</select>
		</p>

		<?php
	}

	/**
	 * Update widget information when modification is done.
	 *
	 * @since    1.0.0
	 * @param array $new_instance Widget Instance Array.
	 * @param array $old_instance Old Widget Instance Array.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['radio'] = isset( $new_instance['radio'] ) ? wp_strip_all_tags( $new_instance['radio'] ) : 'list';
		$instance['select']   = isset( $new_instance['select'] ) ? wp_strip_all_tags( $new_instance['select'] ) : '';
		update_option( 'wps_etmfw_display_duration', $instance['select'] );
		update_option( 'wps_etmfw_event_view', $instance['radio'] );
		return $instance;
	}

	/**
	 * Return possible filters for event duration.
	 *
	 * @since    1.0.0
	 */
	public function wps_etmfw_get_scopes() {
		$scopes = array(
			'all'       => __( 'All events', 'event-tickets-manager-for-woocommerce' ),
			'future'    => __( 'Future events', 'event-tickets-manager-for-woocommerce' ),
			'past'      => __( 'Past events', 'event-tickets-manager-for-woocommerce' ),
		);
		return apply_filters( 'wps_etmfw_get_scopes', $scopes );
	}
}
