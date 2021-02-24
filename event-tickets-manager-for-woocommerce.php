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
