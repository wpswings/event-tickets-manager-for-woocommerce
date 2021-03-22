<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Event_Tickets_Manager_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Event Tickets Manager for WooCommerce
 * Plugin URI:        https://makewebbetter.com/product/event-tickets-manager-for-woocommerce/
 * Description:       Event Tickets Manager for WooCommerce allows you to manage, sell and assign tickets easily.
 * Version:           1.0.0
 * Author:            makewebbetter
 * Author URI:        https://makewebbetter.com/
 * Text Domain:       event-tickets-manager-for-woocommerce
 * Domain Path:       /languages
 *
 * Requires at least: 4.6
 * Tested up to:      4.9.5
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
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

		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_VERSION', '1.0.0' );
		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH', plugin_dir_path( __FILE__ ) );
		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL', plugin_dir_url( __FILE__ ) );
		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_SERVER_URL', 'https://makewebbetter.com' );
		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_ITEM_REFERENCE', 'Event Tickets Manager for WooCommerce' );
		$wp_upload = wp_upload_dir();
		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_DIR', $wp_upload['basedir'] );
		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_UPLOAD_URL', $wp_upload['baseurl'] );
		event_tickets_manager_for_woocommerce_constants( 'CLIENT_ID', get_option( 'mwb_etmfw_google_client_id', '' ) );
		event_tickets_manager_for_woocommerce_constants( 'CLIENT_SECRET', get_option( 'mwb_etmfw_google_client_secret', '' ) );
		event_tickets_manager_for_woocommerce_constants( 'CLIENT_REDIRECT_URL', get_option( 'mwb_etmfw_google_redirect_url', '' ) );

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
		$mwb_etmfw_active_plugin = get_option( 'mwb_all_plugins_active', false );
		if ( is_array( $mwb_etmfw_active_plugin ) && ! empty( $mwb_etmfw_active_plugin ) ) {
			$mwb_etmfw_active_plugin['event-tickets-manager-for-woocommerce'] = array(
				'plugin_name' => __( 'Event Tickets Manager for WooCommerce', 'event-tickets-manager-for-woocommerce' ),
				'active' => '1',
			);
		} else {
			$mwb_etmfw_active_plugin = array();
			$mwb_etmfw_active_plugin['event-tickets-manager-for-woocommerce'] = array(
				'plugin_name' => __( 'Event Tickets Manager for WooCommerce', 'event-tickets-manager-for-woocommerce' ),
				'active' => '1',
			);
		}
		update_option( 'mwb_all_plugins_active', $mwb_etmfw_active_plugin );
		mwb_etmfw_create_checkin_page();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-event-tickets-manager-for-woocommerce-deactivator.php
	 */
	function deactivate_event_tickets_manager_for_woocommerce() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-event-tickets-manager-for-woocommerce-deactivator.php';
		Event_Tickets_Manager_For_Woocommerce_Deactivator::event_tickets_manager_for_woocommerce_deactivate();
		$mwb_etmfw_deactive_plugin = get_option( 'mwb_all_plugins_active', false );
		if ( is_array( $mwb_etmfw_deactive_plugin ) && ! empty( $mwb_etmfw_deactive_plugin ) ) {
			foreach ( $mwb_etmfw_deactive_plugin as $mwb_etmfw_deactive_key => $mwb_etmfw_deactive ) {
				if ( 'event-tickets-manager-for-woocommerce' === $mwb_etmfw_deactive_key ) {
					$mwb_etmfw_deactive_plugin[ $mwb_etmfw_deactive_key ]['active'] = '0';
				}
			}
		}
		update_option( 'mwb_all_plugins_active', $mwb_etmfw_deactive_plugin );
		mwb_etmfw_delete_checkin_page();
	}

	/**
	 * Delete checkin page created when plugin is deactivated.
	 */
	function mwb_etmfw_delete_checkin_page() {
		$checkin_pageid = get_option( 'event_checkin_page_created', false );
		if ( $checkin_pageid ) {
			wp_delete_post( $checkin_pageid );
		}
	}

	register_activation_hook( __FILE__, 'activate_event_tickets_manager_for_woocommerce' );
	register_deactivation_hook( __FILE__, 'deactivate_event_tickets_manager_for_woocommerce' );

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
		session_start();
		$etmfw_plugin_standard = new Event_Tickets_Manager_For_Woocommerce();
		$etmfw_plugin_standard->etmfw_run();
		$GLOBALS['etmfw_mwb_etmfw_obj'] = $etmfw_plugin_standard;

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
		return array_merge( $my_link, $links );
	}

	// on plugin load.
	add_action( 'plugins_loaded', 'mwb_wgc_register_event_ticket_manager_product_type' );

	/**
	 * Saving the Product Type by creating the Instance of this.
	 *
	 * @since 1.0.0
	 * @name mwb_wgc_register_gift_card_product_type
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wgc_register_event_ticket_manager_product_type() {
		/**
		 * Set the giftcard product type.
		 *
		 * @since 1.0.0
		 * @author makewebbetter<ticket@makewebbetter.com>
		 * @link https://www.makewebbetter.com/
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
	 * @name mwb_etmfw_ticket_generator
	 * @param int $length length of ticket number.
	 * @return string $ticket_number.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_etmfw_ticket_generator( $length = 5 ) {
		$ticket_number = '';
		$alphabets = range( 'A', 'Z' );
		$numbers = range( '0', '9' );
		$final_array = array_merge( $alphabets, $numbers );
		while ( $length-- ) {
			$key = array_rand( $final_array );
			$ticket_number .= $final_array[ $key ];
		}
		$ticket_prefix = apply_filters( 'mwb_etmfw_event_ticket_prefix', '' );
		$ticket_number = $ticket_prefix . $ticket_number;
		$ticket_number = apply_filters( 'mwb_wgm_custom_coupon', $ticket_number );
		return $ticket_number;
	}

	/**
	 * Return wordpress date time format.
	 */
	function mwb_etmfw_get_date_format( $date ) {
		return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $date ) );// get format from WordPress settings.
	}

	/**
	 * Function to create check in page template.
	 */
	function mwb_etmfw_create_checkin_page() {
		/* ===== ====== Create the Check Gift Card Page ====== ======*/
		if ( ! get_option( 'event_checkin_page_created', false ) ) {

			$checkin_content = '[mwb_etmfw_event_checkin_page]';

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
	 * Function to register event widget.
	 */
	function mwb_etmfw_register_widget() {
		register_widget( 'event_tickets_manager_for_woocommerce_widget' );
	}
	add_action( 'widgets_init', 'mwb_etmfw_register_widget' );

	require plugin_dir_path( __FILE__ ) . 'includes/class-event-tickets-manager-for-woocommerce-widget.php';
} else {

	/**
	 * Show warning message fif woocommerce if not activated.
	 */
	function mwb_etmfw_plugin_error_notice() {

		unset( $_GET['activate'] );
		?>
		  <div class="error notice is-dismissible">
			 <p><?php esc_html_e( 'Woocommerce is not activated, Please activate Woocommerce first to install Event Tickets Manager for WooCommerce', 'event-tickets-manager-for-woocommerce' ); ?></p>
		   </div>
		<?php
	}
	add_action( 'admin_notices', 'mwb_etmfw_plugin_error_notice' );

	/**
	 * Deactivate plugin is woocommerce if not activated.
	 */
	function mwb_etmfw_plugin_deactivate() {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	/**
	 * Register the action with WordPress.
	 */
	add_action( 'admin_init', 'mwb_etmfw_plugin_deactivate' );
}

