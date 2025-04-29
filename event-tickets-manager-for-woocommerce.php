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
 * Description:          <code><strong>Event Tickets Manager for WooCommerce</strong></code> is all-in-one solution to create an event , manage ticket stocks download ticket as PDFs & much more. <a href="https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-events&utm_medium=events-org-backend&utm_campaign=official">Elevate your e-commerce store by exploring more on <strong>WP Swings</strong></a>
 * Version:              1.4.4
 * Author:               WP Swings
 * Author URI:           https://wpswings.com/?utm_source=wpswings-events-official&utm_medium=events-org-page&utm_campaign=official
 * Text Domain:          event-tickets-manager-for-woocommerce
 * Domain Path:          /languages
 *
 * Requires Plugins:  woocommerce
 * Requires at least:    6.1
 * Tested up to:         6.8
 * WC requires at least: 6.1
 * WC tested up to:      9.8.2
 * License:              GNU General Public License v3.0
 * License URI:          http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
use Automattic\WooCommerce\Utilities\OrderUtil;

$activated      = false;
$active_plugins = get_option( 'active_plugins', array() );
if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	$active_network_wide = get_site_option( 'active_sitewide_plugins', array() );
	if ( ! empty( $active_network_wide ) ) {
		foreach ( $active_network_wide as $key => $value ) {
			$active_plugins[] = $key;
		}
	}
	$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	if ( file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) && in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) ) {
		$activated = true;
	}
} elseif ( file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) && in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) ) {
		$activated = true;
}

if ( $activated ) {

	// HPOS Compatibility.
	add_action(
		'before_woocommerce_init',
		function() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		}
	);

	// Cart and Checkout Block Comaptibility.
	add_action(
		'before_woocommerce_init',
		function() {

			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {

				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );

			}

		}
	);

	/**
	 * Define plugin constants.
	 *
	 * @since             1.0.0
	 */
	function define_event_tickets_manager_for_woocommerce_constants() {

		event_tickets_manager_for_woocommerce_constants( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_VERSION', '1.4.4' );
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
		event_tickets_manager_for_woocommerce_constants( 'HTML_EMAIL_HEADERS', array( 'Content-Type: text/html; charset=UTF-8' ) );

	}

	if ( ! function_exists( 'wps_etmfw_check_multistep' ) ) {
		/**
		 * This function is used to check susbcripton product in cart.
		 *
		 * @name wps_sfw_check_multistep
		 * @since 1.0.2
		 */
		function wps_etmfw_check_multistep() {
			$bool = false;
			$wps_etmfw_enable_plugin = get_option( 'wps_etmfw_enable_plugin', false );
			if ( 'on' == $wps_etmfw_enable_plugin ) {
				$bool = true;
			}

			return $bool;
		}
	}


	if ( ! function_exists( 'wps_etmfw_is_enable_usage_tracking' ) ) {
		/**
		 * This function is used to check tracking enable.
		 *
		 * @name wps_sfw_is_enable_usage_tracking
		 * @since 1.0.2
		 */
		function wps_etmfw_is_enable_usage_tracking() {
			$is_enable = false;
			$wps_wps_enable = get_option( 'wps_etmfw_enable_tracking', '' );
			if ( 'on' == $wps_wps_enable ) {
				$is_enable = true;
			}
			return $is_enable;
		}
	}


	if ( wps_etmfw_is_enable_usage_tracking() ) {
		add_action( 'wpswings_tracker_send_event', 'wps_sfw_wpswings_tracker_send_event' );
	}

		/**
		 * Function is used for the sending the track data
		 *
		 * @name wps_sfw_wpswings_tracker_send_event
		 * @since 1.0.0
		 */
	function wps_sfw_wpswings_tracker_send_event() {

		require WC()->plugin_path() . '/includes/class-wc-tracker.php';

		$last_send = get_option( 'wpswings_tracker_last_send' );
		if ( ! apply_filters( 'wpswings_tracker_send_override', false ) ) {

			// Send a maximum of once per week by default.
			$last_send = wps_etmfw_last_send_time();
			if ( $last_send && $last_send > apply_filters( 'wpswings_tracker_last_send_interval', strtotime( '-1 week' ) ) ) {

				return;
			}
		} else {

			// Make sure there is at least a 1 hour delay between override sends, we don't want duplicate calls due to double clicking links.
			$last_send = wps_etmfw_last_send_time();
			if ( $last_send && $last_send > strtotime( '-1 hours' ) ) {

				return;
			}
		}
		// Update time first before sending to ensure it is set.
		update_option( 'wpswings_tracker_last_send', time() );
		$params = WC_Tracker::get_tracking_data();
		$params = apply_filters( 'wpswings_tracker_params', $params );
		$api_url = 'https://tracking.wpswings.com/wp-json/mps-route/v1/mps-testing-data/';
		$sucess = wp_safe_remote_post(
			$api_url,
			array(
				'method'      => 'POST',
				'body'        => wp_json_encode( $params ),
			)
		);

	}


		/**
		 * Get the updated time.
		 *
		 * @name wps_etmfw_last_send_time
		 *
		 * @since 1.0.0
		 */
	function wps_etmfw_last_send_time() {
		return apply_filters( 'wpswings_tracker_last_send_time', get_option( 'wpswings_tracker_last_send', false ) );
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

	add_action( 'init', 'wps_etmfw_create_images_folder_inside_uploads' );

	/**
	 * Function for create directory.
	 *
	 * @return void
	 */
	function wps_etmfw_create_images_folder_inside_uploads() {
		$upload = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_dir = $upload_dir . '/images';

		// Check if the directory doesn't exist.
		if ( ! is_dir( $upload_dir ) ) {
			wp_mkdir_p( $upload_dir );
			chmod( $upload_dir, 0775 );
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
	 *
	 * @param string $network_wide is a string.
	 * @return void
	 */
	function wps_etmfw_create_checkin_page( $network_wide ) {
		/* ===== ====== Create the Check Event Checkin Page ====== ======*/
		global $wpdb;
		if ( is_multisite() && $network_wide ) {

			// Attempt to retrieve the blog ids from the cache.
			$blogids = wp_cache_get( 'blog_ids', 'blog_ids_cache' );

			// If not found in the cache, query the database and cache the result.
			if ( false === $blogids ) {
				$blogids = get_sites( array( 'fields' => 'ids' ) );
				// Cache the result for future use.
				wp_cache_set( 'blog_ids', $blogids, 'blog_ids_cache' );
			}

			foreach ( $blogids as $blog_id ) {
				switch_to_blog( $blog_id );

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
					/* ===== ====== End of Create the Event Checkin Page ====== ======*/
				}

				restore_current_blog();
			}
		} else {

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
				/* ===== ====== End of Create the Event Checkin Page ====== ======*/
			}
		}
	}

	/**
	 * Delete checkin page created when plugin is deactivated.
	 */
	function wps_etmfw_delete_checkin_page() {
		global $wpdb;
		if ( is_multisite() && $network_wide ) {

			// Attempt to retrieve the blog ids from the cache.
			$blogids = wp_cache_get( 'blog_ids', 'blog_ids_cache' );

			// If not found in the cache, query the database and cache the result.
			if ( false === $blogids ) {
				$blogids = get_sites( array( 'fields' => 'ids' ) );
				// Cache the result for future use.
				wp_cache_set( 'blog_ids', $blogids, 'blog_ids_cache' );
			}
			foreach ( $blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				$checkin_pageid = get_option( 'event_checkin_page_created', false );
				if ( $checkin_pageid ) {
					wp_delete_post( $checkin_pageid );
					delete_option( 'event_checkin_page_created' );
				}
				restore_current_blog();
			}
		} else {
				$checkin_pageid = get_option( 'event_checkin_page_created', false );
			if ( $checkin_pageid ) {
				wp_delete_post( $checkin_pageid );
				delete_option( 'event_checkin_page_created' );
			}
		}

	}

	register_activation_hook( __FILE__, 'wps_etmfw_create_checkin_page' );
	register_deactivation_hook( __FILE__, 'wps_etmfw_delete_checkin_page' );

	add_action( 'wp_initialize_site', 'wps_etmfw_standard_plugin_on_create_blog', 900 );
	add_filter( 'woocommerce_account_menu_items', 'move_logout_tab_to_bottom', 99 );


	/**
	 * Function to set logout to last.
	 *
	 * @param array $menu_links is the array.
	 * @return array $menu_links.
	 */
	function move_logout_tab_to_bottom( $menu_links ) {
		// Store the logout tab.
		if ( isset( $menu_links['customer-logout'] ) ) {
			$logout_link = $menu_links['customer-logout'];

			// Remove the logout tab from its original position.
			unset( $menu_links['customer-logout'] );

			// Add the logout tab to the bottom.
			$menu_links['customer-logout'] = $logout_link;
		}
		return $menu_links;
	}

	/**
	 * Function to create blog.
	 *
	 * @param object $new_site is the object.
	 * @return void
	 */
	function wps_etmfw_standard_plugin_on_create_blog( $new_site ) {
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}
		if ( is_plugin_active_for_network( 'event-tickets-manager-for-woocommerce/event-tickets-manager-for-woocommerce.php' ) ) {
			$blog_id = isset( $new_site->blog_id ) ? $new_site->blog_id : '';
			switch_to_blog( $blog_id );

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
				/* ===== ====== End of Create the Event Checkin Page ====== ======*/
			}

			restore_current_blog();
		}
	}

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

			$my_link['goPro'] = '<a class="wps-wpr-go-pro" style="background: #05d5d8;
			color: white;
			font-weight: 700;
			padding: 2px 5px;
			border: 1px solid #05d5d8;
			border-radius: 5px;" target="_blank" href="https://wpswings.com/product/event-tickets-manager-for-woocommerce-pro/?utm_source=wpswings-events-pro&utm_medium=events-org-backend&utm_campaign=go-pro">' . esc_html__( 'GO PRO', 'event-tickets-manager-for-woocommerce' ) . '</a>';
		}
		return array_merge( $my_link, $links );
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
		if ( in_array( 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			$wps_changed_date_format = get_option( 'wp_date_time_event_format' );
			$wps_custom_date_format = isset( $wps_changed_date_format ) && ( 'no_select' != $wps_changed_date_format ) && ( '' != $wps_changed_date_format ) ? $wps_changed_date_format : get_option( 'date_format' );

			// Return the date in the custom format.
			return date_i18n( $wps_custom_date_format, strtotime( $date ) );
		} else {
			return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $date ) );// get format from WordPress settings.
		}
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

	add_action( 'admin_init', 'wps_etmfw_migration_code' );

	/**
	 * Migration code
	 */
	function wps_etmfw_migration_code() {
		$check = get_option( 'is_wps_etmfw_migration_done', 'not done' );
		if ( 'done' != $check ) {

			include_once plugin_dir_path( __FILE__ ) . 'includes/class-event-tickets-manager-for-woocommerce-activator.php';
			Event_Tickets_Manager_For_Woocommerce_Activator::upgrade_wp_etmfw_postmeta();
			Event_Tickets_Manager_For_Woocommerce_Activator::upgrade_wp_etmfw_options();

		}
		update_option( 'is_wps_etmfw_migration_done', 'done' );

	}

	/**
	 * Replacing get_post_meta with wps_etmfw_get_meta_data for HPOS.
	 *
	 * @param int    $id Date Passed.
	 * @param string $key key Passed.
	 * @param string $v $value Passed.
	 */
	function wps_etmfw_get_meta_data( $id, $key, $v ) {
		if ( 'shop_order' === OrderUtil::get_order_type( $id ) && OrderUtil::custom_orders_table_usage_is_enabled() ) {
			// HPOS usage is enabled.
			$order    = wc_get_order( $id );
			if ( '_customer_user' == $key ) {
				$meta_val = $order->get_customer_id();
				return $meta_val;
			}
			$meta_val = $order->get_meta( $key );
			return $meta_val;
		} else {
			// Traditional CPT-based orders are in use.
			$meta_val = get_post_meta( $id, $key, $v );
			return $meta_val;
		}
	}

	/**
	 * Update update_post_meta with wps_etmfw_update_meta_data for HPOS.
	 *
	 * @param int    $id Date Passed.
	 * @param string $key key Passed.
	 * @param string $value $value Passed.
	 */
	function wps_etmfw_update_meta_data( $id, $key, $value ) {
		if ( 'shop_order' === OrderUtil::get_order_type( $id ) && OrderUtil::custom_orders_table_usage_is_enabled() ) {
			// HPOS usage is enabled.
			$order = wc_get_order( $id );
			$order->update_meta_data( $key, $value );
			$order->save();
		} else {
			// Traditional CPT-based orders are in use.
			update_post_meta( $id, $key, $value );
		}
	}

	add_action( 'admin_notices', 'wps_banner_notification_plugin_html' );
	if ( ! function_exists( 'wps_banner_notification_plugin_html' ) ) {
		/**
		 * Common Function To show banner image.
		 *
		 * @return void
		 */
		function wps_banner_notification_plugin_html() {

			$screen = get_current_screen();
			if ( isset( $screen->id ) ) {
				$pagescreen = $screen->id;
			}
			if ( ( isset( $pagescreen ) && 'plugins' === $pagescreen ) || ( 'wp-swings_page_home' == $pagescreen ) ) {
				$banner_id = get_option( 'wps_wgm_notify_new_banner_id', false );
				if ( isset( $banner_id ) && '' !== $banner_id ) {
					$hidden_banner_id            = get_option( 'wps_wgm_notify_hide_baneer_notification', false );
					$banner_image = get_option( 'wps_wgm_notify_new_banner_image', '' );

					$banner_url = get_option( 'wps_wgm_notify_new_banner_url', '' );
					if ( isset( $hidden_banner_id ) && $hidden_banner_id < $banner_id ) {

						if ( '' !== $banner_image && '' !== $banner_url ) {

							?>
						   <div class="wps-offer-notice notice notice-warning is-dismissible">
							   <div class="notice-container">
								   <a href="<?php echo esc_url( $banner_url ); ?>" target="_blank"><img src="<?php echo esc_url( $banner_image ); ?>" alt="Subscription cards"/></a>
							   </div>
							   <button type="button" class="notice-dismiss dismiss_banner" id="dismiss-banner"><span class="screen-reader-text">Dismiss this notice.</span></button>
						   </div>
						  
							<?php
						}
					}
				}
			}
		}
	}

	add_action( 'admin_notices', 'wps_etmfw_banner_notification_html' );
	/**
	 * Function to show banner image based on subscription.
	 *
	 * @return void
	 */
	function wps_etmfw_banner_notification_html() {

		$screen = get_current_screen();
		if ( isset( $screen->id ) ) {
			$pagescreen = $screen->id;
		}
		$secure_nonce      = wp_create_nonce( 'wps-event-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-event-auth-nonce' );
		if ( ! $id_nonce_verified ) {
			wp_die( esc_html__( 'Nonce Not verified', 'event-tickets-manager-for-woocommerce' ) );
		}
		if ( ( isset( $_GET['page'] ) && 'event_tickets_manager_for_woocommerce_menu' === $_GET['page'] ) ) {
			$banner_id = get_option( 'wps_wgm_notify_new_banner_id', false );
			if ( isset( $banner_id ) && '' !== $banner_id ) {
				$hidden_banner_id            = get_option( 'wps_wgm_notify_hide_baneer_notification', false );
				$banner_image = get_option( 'wps_wgm_notify_new_banner_image', '' );
				$banner_url = get_option( 'wps_wgm_notify_new_banner_url', '' );
				if ( isset( $hidden_banner_id ) && $hidden_banner_id < $banner_id ) {

					if ( '' !== $banner_image && '' !== $banner_url ) {

						?>
							<div class="wps-offer-notice notice notice-warning is-dismissible">
								<div class="notice-container">
									<a href="<?php echo esc_url( $banner_url ); ?>"target="_blank"><img src="<?php echo esc_url( $banner_image ); ?>" alt="Subscription cards"/></a>
								</div>
								<button type="button" class="notice-dismiss dismiss_banner" id="dismiss-banner"><span class="screen-reader-text">Dismiss this notice.</span></button>
							</div>
						   
						<?php
					}
				}
			}
		}
	}

	/**
	 * Function for sending remainder.
	 *
	 * @return void
	 */
	function wps_event_reminder_checking() {
		if ( ! wp_next_scheduled( 'wps_event_tickets_manager_for_woocommerce_reminder_send' ) ) {
			wp_schedule_event( strtotime( 'tomorrow' ), 'daily', 'wps_event_tickets_manager_for_woocommerce_reminder_send' );
		}
	}

	add_action( 'init', 'wps_event_reminder_checking' );

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
	if ( is_multisite() ) {
		add_action( 'network_admin_notices', 'wps_etmfw_plugin_error_notice' );
	} else {

		add_action( 'admin_notices', 'wps_etmfw_plugin_error_notice' );
	}

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

