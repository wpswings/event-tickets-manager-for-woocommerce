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
			__( 'Event Ticket Widget', 'event-tickets-manager-for-woocommerce' ),
			// widget description.
			array( 'description' => __( 'Event Ticket Widget', 'event-tickets-manager-for-woocommerce' ) )
		);
	}

	/**
	 * Set up event widget data for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		// Check the widget options
		$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$select   = isset( $instance['select'] ) ? $instance['select'] : '';
		$radio = ! empty( $instance['radio'] ) ? $instance['radio'] : 'list';
		// WordPress core before_widget hook (always include )
		echo wp_kses_post( $before_widget );
		if( 'calendar' === $radio ){
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
					echo wp_kses_post( $before_title . $title . $after_title );
				}
				?>
				<ul class="product_list_widget">
				<?php
				while ( $query_data->have_posts() ) {
					$query_data->the_post();
					$this->mwb_generate_list_view( $select );
				}?>
				</ul>
				<?php
				
			}
			wp_reset_postdata();
		}
		// WordPress core after_widget hook (always include )
		echo wp_kses_post( $after_widget );
	}

	public function mwb_generate_list_view( $select ){
		global $product;
		$mwb_etmfw_product_array = get_post_meta( $product->get_id(), 'mwb_etmfw_product_array', true );
		$start_date = isset( $mwb_etmfw_product_array['event_start_date_time'] ) ? $mwb_etmfw_product_array['event_start_date_time'] : '';
		$end_date = isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '';
		$current_timestamp = current_time( 'timestamp' );
		$end_date_timestamp = strtotime( $end_date );
		$start_date_timestamp = strtotime( date('Y-m-d', strtotime( $start_date ) ) );
		$current_timestamp = strtotime( date('Y-m-d', $current_timestamp ) );
		switch ($select) {
			case 'all':
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
							echo esc_html( mwb_etmfw_get_date_format( $start_date ) );
							echo esc_html( ' To ' );
							echo esc_html( mwb_etmfw_get_date_format( $end_date ) );
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
							echo esc_html( mwb_etmfw_get_date_format( $start_date ) );
							echo esc_html( ' To ' );
							echo esc_html( mwb_etmfw_get_date_format( $end_date ) );
						?>
					</li>
					<?php
				}
				break;

			default:
				# code...
				break;
		}
	}

	/**
	 * Create input form to get widget data.
	 *
	 * @since    1.0.0
	 */
	public function form( $instance ) {
		// Set widget defaults.
		$defaults = array(
			'title'     => '',
			'radio' 	=> '',
			'select'    => '',
		);
		
		// Parse current settings with defaults.
		extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

		<?php // Widget Title. ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'event-tickets-manager-for-woocommerce' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<?php // Widget View. ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'radio' ) ); ?>"><?php _e( 'Event View', 'event-tickets-manager-for-woocommerce' ); ?></label><br>
			<input id="<?php echo esc_attr( $this->get_field_id( 'radio' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'radio' ) ); ?>" type="radio" value="list" <?php echo esc_html( 'list' === $radio ) ? 'checked' : ''; ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'radio' ) ); ?>"><?php _e( 'List', 'event-tickets-manager-for-woocommerce' ); ?></label>

			<input id="<?php echo esc_attr( $this->get_field_id( 'radio' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'radio' ) ); ?>" type="radio" value="calendar" <?php echo esc_html( 'calendar' === $radio ) ? 'checked' : ''; ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'radio' ) ); ?>"><?php _e( 'Calendar', 'event-tickets-manager-for-woocommerce' ); ?></label>
		</p>

		<?php // Filter event by duration ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'select' ); ?>"><?php _e( 'Scope of the Event', 'event-tickets-manager-for-woocommerce' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'select' ); ?>" id="<?php echo $this->get_field_id( 'select' ); ?>" class="widefat">
			<?php
			$scope_options = $this->mwb_etmfw_get_scopes();
			foreach ( $scope_options as $key => $name ) {
				echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" '. selected( $select, $key, false ) . '>'. $name . '</option>';

			} ?>
			</select>
		</p>

	<?php
	}

	/**
	 * Update widget information when modification is done.
	 *
	 * @since    1.0.0
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['radio'] = isset( $new_instance['radio'] ) ? wp_strip_all_tags( $new_instance['radio'] ) : 'list';
		$instance['select']   = isset( $new_instance['select'] ) ? wp_strip_all_tags( $new_instance['select'] ) : '';
		update_option( 'mwb_etmfw_display_duration', $instance['select'] );
		update_option( 'mwb_etmfw_event_view', $instance['radio'] );
		return $instance;
	}

	/**
	 * Return possible filters for event duration.
	 *
	 * @since    1.0.0
	 */
	public function mwb_etmfw_get_scopes(){
		$scopes = array(
			'all' 		=> __('All events','events-manager'),
			'future' 	=> __('Future events','events-manager'),
			'past' 		=> __('Past events','events-manager'),
		);
		return apply_filters('mwb_etmfw_get_scopes',$scopes);
	}
}
