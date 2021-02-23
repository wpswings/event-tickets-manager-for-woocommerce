<?php
/**
 * The plugin bootstrap file .
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Events_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       events for woocommerce
 * Plugin URI:        https://makewebbetter.com/product/events-for-woocommerce/
 * Description:       events for woocommerce
 * Version:           1.0.0
 * Author:            makewebbetter
 * Author URI:        https://makewebbetter.com/
 * Text Domain:       events-for-woocommerce
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
function define_events_for_woocommerce_constants() {

	events_for_woocommerce_constants( 'EVENTS_FOR_WOOCOMMERCE_VERSION', '1.0.0' );
	events_for_woocommerce_constants( 'EVENTS_FOR_WOOCOMMERCE_DIR_PATH', plugin_dir_path( __FILE__ ) );
	events_for_woocommerce_constants( 'EVENTS_FOR_WOOCOMMERCE_DIR_URL', plugin_dir_url( __FILE__ ) );
	events_for_woocommerce_constants( 'EVENTS_FOR_WOOCOMMERCE_SERVER_URL', 'https://makewebbetter.com' );
	events_for_woocommerce_constants( 'EVENTS_FOR_WOOCOMMERCE_ITEM_REFERENCE', 'events for woocommerce' );
}


/**
 * Callable function for defining plugin constants.
 *
 * @param   String $key    Key for contant.
 * @param   String $value   value for contant.
 * @since             1.0.0
 */
function events_for_woocommerce_constants( $key, $value ) {

	if ( ! defined( $key ) ) {

		define( $key, $value );
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-events-for-woocommerce-activator.php
 */
function activate_events_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-events-for-woocommerce-activator.php';
	Events_For_Woocommerce_Activator::events_for_woocommerce_activate();
	$mwb_efw_active_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_efw_active_plugin ) && ! empty( $mwb_efw_active_plugin ) ) {
		$mwb_efw_active_plugin['events-for-woocommerce'] = array(
			'plugin_name' => __( 'events for woocommerce', 'events-for-woocommerce' ),
			'active' => '1',
		);
	} else {
		$mwb_efw_active_plugin = array();
		$mwb_efw_active_plugin['events-for-woocommerce'] = array(
			'plugin_name' => __( 'events for woocommerce', 'events-for-woocommerce' ),
			'active' => '1',
		);
	}
	update_option( 'mwb_all_plugins_active', $mwb_efw_active_plugin );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-events-for-woocommerce-deactivator.php
 */
function deactivate_events_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-events-for-woocommerce-deactivator.php';
	Events_For_Woocommerce_Deactivator::events_for_woocommerce_deactivate();
	$mwb_efw_deactive_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_efw_deactive_plugin ) && ! empty( $mwb_efw_deactive_plugin ) ) {
		foreach ( $mwb_efw_deactive_plugin as $mwb_efw_deactive_key => $mwb_efw_deactive ) {
			if ( 'events-for-woocommerce' === $mwb_efw_deactive_key ) {
				$mwb_efw_deactive_plugin[ $mwb_efw_deactive_key ]['active'] = '0';
			}
		}
	}
	update_option( 'mwb_all_plugins_active', $mwb_efw_deactive_plugin );
}

register_activation_hook( __FILE__, 'activate_events_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_events_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-events-for-woocommerce.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_events_for_woocommerce() {
	define_events_for_woocommerce_constants();

	$efw_plugin_standard = new Events_For_Woocommerce();
	$efw_plugin_standard->efw_run();
	$GLOBALS['efw_mwb_efw_obj'] = $efw_plugin_standard;

}
run_events_for_woocommerce();


// Add settings link on plugin page.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'events_for_woocommerce_settings_link' );

/**
 * Settings link.
 *
 * @since    1.0.0
 * @param   Array $links    Settings link array.
 */
function events_for_woocommerce_settings_link( $links ) {

	$my_link = array(
		'<a href="' . admin_url( 'admin.php?page=events_for_woocommerce_menu' ) . '">' . __( 'Settings', 'events-for-woocommerce' ) . '</a>',
	);
	return array_merge( $my_link, $links );
}
