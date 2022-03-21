<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link                 https://wpswings.com/
 * @since                1.0.0
 * @package              Event_Tickets_Manager_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:          Event Tickets Manager for WooCommerce
 * Plugin URI:           https://wordpress.org/plugins/event-tickets-manager-for-woocommerce/
 * Description:          Event Tickets Manager for WooCommerce allows you to manage, sell and assign tickets easily. <a href="https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-events&utm_medium=events-org-backend&utm_campaign=official">Elevate your e-commerce store by exploring more on <strong>WP Swings</strong></a>
 * Version:              1.0.5
 * Author:               WP Swings
 * Author URI:           https://wpswings.com/?utm_source=wpswings-events-official&utm_medium=events-org-backend&utm_campaign=official
 * Text Domain:          event-tickets-manager-for-woocommerce
 * Domain Path:          /languages
 * Requires at least:    4.6
 * Tested up to:         5.9.2
 * WC requires at least: 4.0
 * WC tested up to:      6.3.1
 * License:              GNU General Public License v3.0
 * License URI:          http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Check if woocommerce is activated.
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	/**
	 * Define plugin constants.
	 *
	 * @since             1.0.0
	 */
	function define_event_tickets_manager_for_woocommerce_constants() {

		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_VERSION', '1.0.5' );
		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH', plugin_dir_path( __FILE__ ) );
		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL', plugin_dir_url( __FILE__ ) );
		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_SERVER_URL', 'https://wpswings.com' );
		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_ITEM_REFERENCE', 'Event Tickets Manager for WooCommerce' );
		$wp_upload = wp_upload_dir();
		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR', $wp_upload['basedir'] );
		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL', $wp_upload['baseurl'] );
		event_tickets_manager_for_woocommerce_constants( 'CLIENT_ID', get_option( 'wps_etmfw_google_client_id', '' ) );
		event_tickets_manager_for_woocommerce_constants( 'CLIENT_SECRET', get_option( 'wps_etmfw_google_client_secret', '' ) );
		event_tickets_manager_for_woocommerce_constants( 'CLIENT_REDIRECT_URL', get_option( 'wps_etmfw_google_redirect_url', '' ) );

	}

	/**
	 * Callable function for defining plugin constants.
	 *
	 * @param   String $key    Key for contant.
	 * @param   String $value   value for contant.
	 * @since             1.0.0
	 */
	function event_tickets_manager_for_woocommerce_constants( $key, $value ) {

		if ( ! defined( $key ) ) {

			define( $key, $value );
		}
	}

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-event-tickets-manager-for-woocommerce-activator.php
	 */
	function activate_event_tickets_manager_for_woocommerce() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-event-tickets-manager-for-woocommerce-activator.php';
		Event_Tickets_Manager_For_Woocommerce_Activator::event_tickets_manager_for_woocommerce_activate();
		$wps_etmfw_active_plugin = get_option( 'wps_all_plugins_active', false );
		if ( is_array( $wps_etmfw_active_plugin ) && ! empty( $wps_etmfw_active_plugin ) ) {
			$wps_etmfw_active_plugin['event-tickets-manager-for-woocommerce'] = array(
				'plugin_name' => __( 'Event Tickets Manager for WooCommerce', 'event-tickets-manager-for-woocommerce' ),
				'active' => '1',
			);
		} else {
			$wps_etmfw_active_plugin = array();
			$wps_etmfw_active_plugin['event-tickets-manager-for-woocommerce'] = array(
				'plugin_name' => __( 'Event Tickets Manager for WooCommerce', 'event-tickets-manager-for-woocommerce' ),
				'active' => '1',
			);
		}
		update_option( 'wps_all_plugins_active', $wps_etmfw_active_plugin );
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-event-tickets-manager-for-woocommerce-deactivator.php
	 */
	function deactivate_event_tickets_manager_for_woocommerce() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-event-tickets-manager-for-woocommerce-deactivator.php';
		Event_Tickets_Manager_For_Woocommerce_Deactivator::event_tickets_manager_for_woocommerce_deactivate();
		$wps_etmfw_deactive_plugin = get_option( 'wps_all_plugins_active', false );
		if ( is_array( $wps_etmfw_deactive_plugin ) && ! empty( $wps_etmfw_deactive_plugin ) ) {
			foreach ( $wps_etmfw_deactive_plugin as $wps_etmfw_deactive_key => $wps_etmfw_deactive ) {
				if ( 'event-tickets-manager-for-woocommerce' === $wps_etmfw_deactive_key ) {
					$wps_etmfw_deactive_plugin[ $wps_etmfw_deactive_key ]['active'] = '0';
				}
			}
		}
		update_option( 'wps_all_plugins_active', $wps_etmfw_deactive_plugin );
	}

	register_activation_hook( __FILE__, 'activate_event_tickets_manager_for_woocommerce' );
	register_deactivation_hook( __FILE__, 'deactivate_event_tickets_manager_for_woocommerce' );

	/**
	 * Function to create check in page template.
	 */
	function wps_etmfw_create_checkin_page() {
		/* ===== ====== Create the Check Gift Card Page ====== ======*/
		if ( ! get_option( 'event_checkin_page_created', false ) ) {

			$checkin_content = '[wps_etmfw_event_checkin_page]';

			$checkin_page = array(
				'post_author'    => get_current_user_id(),
				'post_name'      => __( 'Event Check In', 'event-tickets-manager-for-woocommerce' ),
				'post_title'     => __( 'Event Check In', 'event-tickets-manager-for-woocommerce' ),
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_content'   => $checkin_content,
			);
			$page_id = wp_insert_post( $checkin_page );
			update_option( 'event_checkin_page_created', $page_id );
			/* ===== ====== End of Create the Gift Card Page ====== ======*/
		}
	}

	/**
	 * Delete checkin page created when plugin is deactivated.
	 */
	function wps_etmfw_delete_checkin_page() {
		$checkin_pageid = get_option( 'event_checkin_page_created', false );
		if ( $checkin_pageid ) {
			wp_delete_post( $checkin_pageid );
			delete_option( 'event_checkin_page_created' );
		}
	}

	register_activation_hook( __FILE__, 'wps_etmfw_create_checkin_page' );
	register_deactivation_hook( __FILE__, 'wps_etmfw_delete_checkin_page' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-event-tickets-manager-for-woocommerce.php';


	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_event_tickets_manager_for_woocommerce() {
		define_event_tickets_manager_for_woocommerce_constants();
		$etmfw_etmfw_plugin_standard = new Event_Tickets_Manager_For_Woocommerce();
		$etmfw_etmfw_plugin_standard->etmfw_run();
		$GLOBALS['etmfw_wps_etmfw_obj'] = $etmfw_etmfw_plugin_standard;
		$GLOBALS['error_notice'] = true;

	}
	run_event_tickets_manager_for_woocommerce();


	// Add settings link on plugin page.
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'event_tickets_manager_for_woocommerce_settings_link' );

	/**
	 * Settings link.
	 *
	 * @since    1.0.0
	 * @param   Array $links    Settings link array.
	 */
	function event_tickets_manager_for_woocommerce_settings_link( $links ) {

		$my_link = array(
			'<a href="' . admin_url( 'admin.php?page=event_tickets_manager_for_woocommerce_menu' ) . '">' . __( 'Settings', 'event-tickets-manager-for-woocommerce' ) . '</a>',
		);
		$etmfw_plugins = get_plugins();
		if ( ! isset( $etmfw_plugins['event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php'] ) ) {

			$my_link['goPro'] = '<a class="wps-wpr-go-pro" target="_blank" href="https://wpswings.com/product/event-tickets-manager-for-woocommerce-pro/?utm_source=wpswings-events-pro&utm_medium=events-org-backend&utm_campaign=go-pro">' . esc_html__( 'GO PRO', 'event-tickets-manager-for-woocommerce' ) . '</a>';
		}
		return array_merge( $my_link, $links );
	}

	// on plugin load.
	add_action( 'plugins_loaded', 'wps_wgc_register_event_ticket_manager_product_type' );

	/**
	 * Saving the Product Type by creating the Instance of this.
	 *
	 * @since 1.0.0
	 * @name wps_wgc_register_gift_card_product_type
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://wpswings.com/
	 */
	function wps_wgc_register_event_ticket_manager_product_type() {
		/**
		 * Set the giftcard product type.
		 *
		 * @since 1.0.0
		 * @author WPSwings<ticket@wpswings.com>
		 * @link https://wpswings.com/
		 */
		class WC_Product_Event_Ticket_Manager extends WC_Product {
			/**
			 * Initialize simple product.
			 *
			 * @param mixed $product product.
			 */
			public function __construct( $product ) {
				$this->product_type = 'event_ticket_manager';
				parent::__construct( $product );
			}
		}
	}

	/**
	 * Generate the Dynamic number for Tickets.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_ticket_generator
	 * @param int $length length of ticket number.
	 * @return string $ticket_number.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://wpswings.com/
	 */
	function wps_etmfw_ticket_generator( $length = 5 ) {
		$ticket_number = '';
		$alphabets = range( 'A', 'Z' );
		$numbers = range( '0', '9' );
		$final_array = array_merge( $alphabets, $numbers );
		while ( $length-- ) {
			$key = array_rand( $final_array );
			$ticket_number .= $final_array[ $key ];
		}
		$ticket_prefix = apply_filters( 'wps_etmfw_event_ticket_prefix', '' );
		$ticket_number = $ticket_prefix . $ticket_number;
		$ticket_number = apply_filters( 'wps_wgm_custom_coupon', $ticket_number );
		return $ticket_number;
	}

	/**
	 * Return wordpress date time format.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_get_date_format
	 * @param string $date Date Passed.
	 * @return string $date WP formated Date.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://wpswings.com/
	 */
	function wps_etmfw_get_date_format( $date ) {
		return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $date ) );// get format from WordPress settings.
	}

	/**
	 * Return wordpress date format.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_get_only_date_format
	 * @param string $date Date Passed.
	 * @return string $date WP formated Date.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://wpswings.com/
	 */
	function wps_etmfw_get_only_date_format( $date ) {
		return date_i18n( get_option( 'date_format' ), strtotime( $date ) );// get format from WordPress settings.
	}

	/**
	 * Return wordpress time format.
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_get_only_time_format
	 * @param string $date Date Passed.
	 * @return string $date WP formated Date.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://wpswings.com/
	 */
	function wps_etmfw_get_only_time_format( $date ) {
		return date_i18n( get_option( 'time_format' ), strtotime( $date ) );// get format from WordPress settings.
	}

	/**
	 * Function to register event widget.
	 */
	function wps_etmfw_register_widget() {
		register_widget( 'event_tickets_manager_for_woocommerce_widget' );
	}
	add_action( 'widgets_init', 'wps_etmfw_register_widget' );

	require plugin_dir_path( __FILE__ ) . 'includes/class-event-tickets-manager-for-woocommerce-widget.php';

	add_action( 'admin_init', 'wps_etmfw_migration_code' );

	/**
	 * Migration code
	 */
	function wps_etmfw_migration_code() {
		$check = get_option( 'is_wps_etmfw_migration_done', 'not done' );
		if( 'done' != $check ) {

			include_once plugin_dir_path( __FILE__ ) . 'includes/class-event-tickets-manager-for-woocommerce-activator.php';
			Event_Tickets_Manager_For_Woocommerce_Activator::upgrade_wp_etmfw_postmeta();
			Event_Tickets_Manager_For_Woocommerce_Activator::upgrade_wp_etmfw_options();
			Event_Tickets_Manager_For_Woocommerce_Activator::wpg_etmfw_replace_mwb_to_wps_in_shortcodes();
		}
		update_option( 'is_wps_etmfw_migration_done', 'done' );

	}

} else {

	/**
	 * Show warning message if woocommerce if not activated.
	 */
	function wps_etmfw_plugin_error_notice() {

		unset( $_GET['activate'] );
		?>
		<div class="error notice is-dismissible">
			<p><?php esc_html_e( 'Woocommerce is not activated, Please activate Woocommerce first to install Event Tickets Manager for WooCommerce', 'event-tickets-manager-for-woocommerce' ); ?></p>
		</div>
		<?php
	}
	add_action( 'admin_notices', 'wps_etmfw_plugin_error_notice' );

	/**
	 * Deactivate plugin is woocommerce if not activated.
	 */
	function wps_etmfw_plugin_deactivate() {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	/**
	 * Register the action with WordPress.
	 */
	add_action( 'admin_init', 'wps_etmfw_plugin_deactivate' );

}

add_action( 'after_plugin_row_' . plugin_basename( __FILE__ ), 'event_upgrade_notice', 0, 3 );

/**
 * Displays notice to WPSWings.
 *
 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
 * @param array  $plugin_data An array of plugin data.
 * @param string $status Status filter currently applied to the plugin list.
 */
function event_upgrade_notice( $plugin_file, $plugin_data, $status ) {

	?>
<tr class="plugin-update-tr active notice-warning notice-alt">
	<td colspan="4" class="plugin-update colspanchange">
		<div class="notice notice-success inline update-message notice-alt">
			<div class='wps-notice-title wps-notice-section'>
				<p><strong>IMPORTANT NOTICE:</strong></p>
			</div>
			<div class='wps-notice-content wps-notice-section'>
				<p>From this update <strong>Version 1.0.4</strong> onwards, the plugin and its support will be handled by <strong>WP Swings</strong>.</p><p><strong>WP Swings</strong> is just our improvised and rebranded version with all quality solutions and help being the same, so no worries at your end.
				Please connect with us for all setup, support, and update related queries without hesitation.</p>
			</div>
		</div>
	</td>
</tr>
<style>
	.wps-notice-section > p:before {
		content: none;
	}
</style>
	<?php
}

