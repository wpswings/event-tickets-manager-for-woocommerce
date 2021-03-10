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
class Event_Tickets_Manager_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Event_Tickets_Manager_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $etmfw_onboard    To initializsed the object of class onboard.
	 */
	protected $etmfw_onboard;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_VERSION' ) ) {

			$this->version = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_VERSION;
		} else {

			$this->version = '1.0.0';
		}

		$this->plugin_name = 'event-tickets-manager-for-woocommerce';

		$this->event_tickets_manager_for_woocommerce_dependencies();
		$this->event_tickets_manager_for_woocommerce_locale();
		if ( is_admin() ) {
			$this->event_tickets_manager_for_woocommerce_admin_hooks();
		} else {
			$this->event_tickets_manager_for_woocommerce_public_hooks();
		}
		$this->event_tickets_manager_for_woocommerce_api_hooks();
		$this->event_tickets_manager_for_woocommerce_mail_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Event_Tickets_Manager_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Event_Tickets_Manager_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Event_Tickets_Manager_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Event_Tickets_Manager_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function event_tickets_manager_for_woocommerce_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-tickets-manager-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-tickets-manager-for-woocommerce-i18n.php';

		if ( is_admin() ) {

			// The class responsible for defining all actions that occur in the admin area.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-event-tickets-manager-for-woocommerce-admin.php';

			// The class responsible for on-boarding steps for plugin.
			if ( is_dir(  plugin_dir_path( dirname( __FILE__ ) ) . '.onboarding' ) && ! class_exists( 'Event_Tickets_Manager_For_Woocommerce_Onboarding_Steps' ) ) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-event-tickets-manager-for-woocommerce-onboarding-steps.php';
			}

			if ( class_exists( 'Event_Tickets_Manager_For_Woocommerce_Onboarding_Steps' ) ) {
				$etmfw_onboard_steps = new Event_Tickets_Manager_For_Woocommerce_Onboarding_Steps();
			}
		} else {

			// The class responsible for defining all actions that occur in the public-facing side of the site.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-event-tickets-manager-for-woocommerce-public.php';

		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'package/rest-api/class-event-tickets-manager-for-woocommerce-rest-api.php';

		$this->loader = new Event_Tickets_Manager_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Event_Tickets_Manager_For_Woocommerce_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function event_tickets_manager_for_woocommerce_locale() {

		$plugin_i18n = new Event_Tickets_Manager_For_Woocommerce_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function event_tickets_manager_for_woocommerce_admin_hooks() {

		$etmfw_plugin_admin = new Event_Tickets_Manager_For_Woocommerce_Admin( $this->etmfw_get_plugin_name(), $this->etmfw_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $etmfw_plugin_admin, 'etmfw_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $etmfw_plugin_admin, 'etmfw_admin_enqueue_scripts' );

		// Add settings menu for Event Tickets Manager for WooCommerce.
		$this->loader->add_action( 'admin_menu', $etmfw_plugin_admin, 'etmfw_options_page' );
		$this->loader->add_action( 'admin_menu', $etmfw_plugin_admin, 'mwb_etmfw_remove_default_submenu', 50 );

		// All admin actions and filters after License Validation goes here.
		$this->loader->add_filter( 'mwb_add_plugins_menus_array', $etmfw_plugin_admin, 'etmfw_admin_submenu_page', 15 );
		$this->loader->add_filter( 'mwb_etmfw_general_settings_array', $etmfw_plugin_admin, 'mwb_etmfw_admin_general_settings_page', 10 );
		$this->loader->add_filter( 'mwb_etmfw_integration_settings_array', $etmfw_plugin_admin, 'mwb_etmfw_admin_integration_settings_page', 10 );
		$this->loader->add_filter( 'mwb_etmfw_email_template_settings_array', $etmfw_plugin_admin, 'mwb_etmfw_admin_email_template_settings_page', 10 );
		$this->loader->add_filter( 'mwb_etmfw_supprot_tab_settings_array', $etmfw_plugin_admin, 'mwb_etmfw_admin_support_settings_page', 10 );

		// Saving tab settings.
		$this->loader->add_action( 'admin_init', $etmfw_plugin_admin, 'mwb_etmfw_admin_save_tab_settings' );

		// Create an Event Product Type
		$this->loader->add_filter( 'product_type_selector', $etmfw_plugin_admin, 'mwb_etmfw_event_ticket_product' );
		$this->loader->add_filter( 'woocommerce_product_data_tabs', $etmfw_plugin_admin, 'mwb_etmfw_event_ticket_tab' );
		$this->loader->add_action( 'woocommerce_product_data_panels', $etmfw_plugin_admin, 'mwb_etmfw_event_tab_content' );
		$this->loader->add_action( 'save_post', $etmfw_plugin_admin, 'mwb_etmfw_save_product_data' );
		$this->loader->add_action( 'admin_menu', $etmfw_plugin_admin, 'mwb_etmfw_event_menu' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function event_tickets_manager_for_woocommerce_public_hooks() {

		$etmfw_plugin_public = new Event_Tickets_Manager_For_Woocommerce_Public( $this->etmfw_get_plugin_name(), $this->etmfw_get_version() );

		add_action( 'woocommerce_event_ticket_manager_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
		$this->loader->add_action( 'wp_enqueue_scripts', $etmfw_plugin_public, 'etmfw_public_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $etmfw_plugin_public, 'etmfw_public_enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_before_add_to_cart_button', $etmfw_plugin_public, 'mwb_etmfw_before_add_to_cart_button_html' );
		$this->loader->add_filter( 'woocommerce_is_sold_individually', $etmfw_plugin_public, 'mwb_etmfw_allow_single_quantity', 10, 2 );
		$this->loader->add_filter( 'woocommerce_add_cart_item_data', $etmfw_plugin_public, 'mwb_etmfw_cart_item_data', 10, 3 );
		$this->loader->add_filter( 'woocommerce_get_item_data', $etmfw_plugin_public, 'mwb_etmfw_get_cart_item_data', 10, 2 );
		$this->loader->add_action( 'woocommerce_checkout_create_order_line_item', $etmfw_plugin_public, 'mwb_etmfw_create_order_line_item', 10, 3 );
		$this->loader->add_action( 'woocommerce_order_status_changed', $etmfw_plugin_public, 'mwb_etmfw_process_event_order', 10, 3 );
		$this->loader->add_action( 'woocommerce_email_attachments', $etmfw_plugin_public, 'mwb_etmfw_attach_pdf_to_emails', 10, 4 );
		$this->loader->add_action( 'woocommerce_order_details_after_order_table', $etmfw_plugin_public, 'mwb_etmfw_view_ticket_button', 10 );


	}


	/**
	 * Register all of the hooks related to the api functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function event_tickets_manager_for_woocommerce_api_hooks() {

		$etmfw_plugin_api = new Event_Tickets_Manager_For_Woocommerce_Rest_Api( $this->etmfw_get_plugin_name(), $this->etmfw_get_version() );

		$this->loader->add_action( 'rest_api_init', $etmfw_plugin_api, 'mwb_etmfw_add_endpoint' );

	}

	/**
	 * Register all of the hooks related to the mail functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function event_tickets_manager_for_woocommerce_mail_hooks() {
		add_filter( 'woocommerce_email_classes', array( $this, 'mwb_etmfw_woocommerce_email_classes' ) );
	}

	/**
	 * Initialization function to include mail template.
	 *
	 * @param array $emails email templates.
	 * @since    1.0.0
	 */
	public function mwb_etmfw_woocommerce_email_classes( $emails ) {
		$emails['mwb_etmfw_email_notification'] = include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/class-mwb-etmfw-emails-notification.php';
		return $emails;
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function etmfw_run() {
		$this->loader->etmfw_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function etmfw_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Event_Tickets_Manager_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function etmfw_get_loader() {
		return $this->loader;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Event_Tickets_Manager_For_Woocommerce_Onboard    Orchestrates the hooks of the plugin.
	 */
	public function etmfw_get_onboard() {
		return $this->etmfw_onboard;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function etmfw_get_version() {
		return $this->version;
	}

	/**
	 * Predefined default mwb_etmfw_plug tabs.
	 *
	 * @return  Array       An key=>value pair of Event Tickets Manager for WooCommerce tabs.
	 */
	public function mwb_etmfw_plug_default_tabs() {

		$etmfw_default_tabs = array();

		$etmfw_default_tabs['event-tickets-manager-for-woocommerce-general'] = array(
			'title'       => esc_html__( 'General Setting', 'event-tickets-manager-for-woocommerce' ),
			'name'        => 'event-tickets-manager-for-woocommerce-general',
		);
		$etmfw_default_tabs['event-tickets-manager-for-woocommerce-email-template'] = array(
			'title'       => esc_html__( 'Ticket Setting', 'event-tickets-manager-for-woocommerce' ),
			'name'        => 'event-tickets-manager-for-woocommerce-email-template',
		);
		$etmfw_default_tabs['event-tickets-manager-for-woocommerce-integrations'] = array(
			'title'       => esc_html__( 'Integrations', 'event-tickets-manager-for-woocommerce' ),
			'name'        => 'event-tickets-manager-for-woocommerce-integrations',
		);
		$etmfw_default_tabs = apply_filters( 'mwb_etmfw_plugin_standard_admin_settings_tabs', $etmfw_default_tabs );

		$etmfw_default_tabs['event-tickets-manager-for-woocommerce-system-status'] = array(
			'title'       => esc_html__( 'System Status', 'event-tickets-manager-for-woocommerce' ),
			'name'        => 'event-tickets-manager-for-woocommerce-system-status',
		);

		return $etmfw_default_tabs;
	}

	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 * @param string $path path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function mwb_etmfw_plug_load_template( $path, $params = array() ) {

		$etmfw_file_path = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . $path;

		if ( file_exists( $etmfw_file_path ) ) {

			include $etmfw_file_path;
		} else {

			/* translators: %s: file path */
			$etmfw_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'event-tickets-manager-for-woocommerce' ), $etmfw_file_path );
			$this->mwb_etmfw_plug_admin_notice( $etmfw_notice, 'error' );
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param  string $etmfw_message    Message to display.
	 * @param  string $type       notice type, accepted values - error/update/update-nag.
	 * @since  1.0.0
	 */
	public static function mwb_etmfw_plug_admin_notice( $etmfw_message, $type = 'error' ) {

		$etmfw_classes = 'notice ';

		switch ( $type ) {

			case 'update':
			$etmfw_classes .= 'updated is-dismissible';
			break;

			case 'update-nag':
			$etmfw_classes .= 'update-nag is-dismissible';
			break;

			case 'success':
			$etmfw_classes .= 'notice-success is-dismissible';
			break;

			default:
			$etmfw_classes .= 'notice-error is-dismissible';
		}

		$etmfw_notice  = '<div class="' . esc_attr( $etmfw_classes ) . ' mwb-errorr-8">';
		$etmfw_notice .= '<p>' . esc_html( $etmfw_message ) . '</p>';
		$etmfw_notice .= '</div>';

		echo wp_kses_post( $etmfw_notice );
	}


	/**
	 * Show wordpress and server info.
	 *
	 * @return  Array $etmfw_system_data       returns array of all wordpress and server related information.
	 * @since  1.0.0
	 */
	public function mwb_etmfw_plug_system_status() {
		global $wpdb;
		$etmfw_system_status = array();
		$etmfw_wordpress_status = array();
		$etmfw_system_data = array();

		// Get the web server.
		$etmfw_system_status['web_server'] = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '';

		// Get PHP version.
		$etmfw_system_status['php_version'] = function_exists( 'phpversion' ) ? phpversion() : __( 'N/A (phpversion function does not exist)', 'event-tickets-manager-for-woocommerce' );

		// Get the server's IP address.
		$etmfw_system_status['server_ip'] = isset( $_SERVER['SERVER_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_ADDR'] ) ) : '';

		// Get the server's port.
		$etmfw_system_status['server_port'] = isset( $_SERVER['SERVER_PORT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_PORT'] ) ) : '';

		// Get the uptime.
		$etmfw_system_status['uptime'] = function_exists( 'exec' ) ? @exec( 'uptime -p' ) : __( 'N/A (make sure exec function is enabled)', 'event-tickets-manager-for-woocommerce' );

		// Get the server path.
		$etmfw_system_status['server_path'] = defined( 'ABSPATH' ) ? ABSPATH : __( 'N/A (ABSPATH constant not defined)', 'event-tickets-manager-for-woocommerce' );

		// Get the OS.
		$etmfw_system_status['os'] = function_exists( 'php_uname' ) ? php_uname( 's' ) : __( 'N/A (php_uname function does not exist)', 'event-tickets-manager-for-woocommerce' );

		// Get WordPress version.
		$etmfw_wordpress_status['wp_version'] = function_exists( 'get_bloginfo' ) ? get_bloginfo( 'version' ) : __( 'N/A (get_bloginfo function does not exist)', 'event-tickets-manager-for-woocommerce' );

		// Get and count active WordPress plugins.
		$etmfw_wordpress_status['wp_active_plugins'] = function_exists( 'get_option' ) ? count( get_option( 'active_plugins' ) ) : __( 'N/A (get_option function does not exist)', 'event-tickets-manager-for-woocommerce' );

		// See if this site is multisite or not.
		$etmfw_wordpress_status['wp_multisite'] = function_exists( 'is_multisite' ) && is_multisite() ? __( 'Yes', 'event-tickets-manager-for-woocommerce' ) : __( 'No', 'event-tickets-manager-for-woocommerce' );

		// See if WP Debug is enabled.
		$etmfw_wordpress_status['wp_debug_enabled'] = defined( 'WP_DEBUG' ) ? __( 'Yes', 'event-tickets-manager-for-woocommerce' ) : __( 'No', 'event-tickets-manager-for-woocommerce' );

		// See if WP Cache is enabled.
		$etmfw_wordpress_status['wp_cache_enabled'] = defined( 'WP_CACHE' ) ? __( 'Yes', 'event-tickets-manager-for-woocommerce' ) : __( 'No', 'event-tickets-manager-for-woocommerce' );

		// Get the total number of WordPress users on the site.
		$etmfw_wordpress_status['wp_users'] = function_exists( 'count_users' ) ? count_users() : __( 'N/A (count_users function does not exist)', 'event-tickets-manager-for-woocommerce' );

		// Get the number of published WordPress posts.
		$etmfw_wordpress_status['wp_posts'] = wp_count_posts()->publish >= 1 ? wp_count_posts()->publish : __( '0', 'event-tickets-manager-for-woocommerce' );

		// Get PHP memory limit.
		$etmfw_system_status['php_memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'event-tickets-manager-for-woocommerce' );

		// Get the PHP error log path.
		$etmfw_system_status['php_error_log_path'] = ! ini_get( 'error_log' ) ? __( 'N/A', 'event-tickets-manager-for-woocommerce' ) : ini_get( 'error_log' );

		// Get PHP max upload size.
		$etmfw_system_status['php_max_upload'] = function_exists( 'ini_get' ) ? (int) ini_get( 'upload_max_filesize' ) : __( 'N/A (ini_get function does not exist)', 'event-tickets-manager-for-woocommerce' );

		// Get PHP max post size.
		$etmfw_system_status['php_max_post'] = function_exists( 'ini_get' ) ? (int) ini_get( 'post_max_size' ) : __( 'N/A (ini_get function does not exist)', 'event-tickets-manager-for-woocommerce' );

		// Get the PHP architecture.
		if ( PHP_INT_SIZE == 4 ) {
			$etmfw_system_status['php_architecture'] = '32-bit';
		} elseif ( PHP_INT_SIZE == 8 ) {
			$etmfw_system_status['php_architecture'] = '64-bit';
		} else {
			$etmfw_system_status['php_architecture'] = 'N/A';
		}

		// Get server host name.
		$etmfw_system_status['server_hostname'] = function_exists( 'gethostname' ) ? gethostname() : __( 'N/A (gethostname function does not exist)', 'event-tickets-manager-for-woocommerce' );

		// Show the number of processes currently running on the server.
		$etmfw_system_status['processes'] = function_exists( 'exec' ) ? @exec( 'ps aux | wc -l' ) : __( 'N/A (make sure exec is enabled)', 'event-tickets-manager-for-woocommerce' );

		// Get the memory usage.
		$etmfw_system_status['memory_usage'] = function_exists( 'memory_get_peak_usage' ) ? round( memory_get_peak_usage( true ) / 1024 / 1024, 2 ) : 0;

		// Get CPU usage.
		// Check to see if system is Windows, if so then use an alternative since sys_getloadavg() won't work.
		if ( stristr( PHP_OS, 'win' ) ) {
			$etmfw_system_status['is_windows'] = true;
			$etmfw_system_status['windows_cpu_usage'] = function_exists( 'exec' ) ? @exec( 'wmic cpu get loadpercentage /all' ) : __( 'N/A (make sure exec is enabled)', 'event-tickets-manager-for-woocommerce' );
		}

		// Get the memory limit.
		$etmfw_system_status['memory_limit'] = function_exists( 'ini_get' ) ? (int) ini_get( 'memory_limit' ) : __( 'N/A (ini_get function does not exist)', 'event-tickets-manager-for-woocommerce' );

		// Get the PHP maximum execution time.
		$etmfw_system_status['php_max_execution_time'] = function_exists( 'ini_get' ) ? ini_get( 'max_execution_time' ) : __( 'N/A (ini_get function does not exist)', 'event-tickets-manager-for-woocommerce' );

		// Get outgoing IP address.
		$etmfw_system_status['outgoing_ip'] = function_exists( 'file_get_contents' ) ? file_get_contents( 'http://ipecho.net/plain' ) : __( 'N/A (file_get_contents function does not exist)', 'event-tickets-manager-for-woocommerce' );

		$etmfw_system_data['php'] = $etmfw_system_status;
		$etmfw_system_data['wp'] = $etmfw_wordpress_status;

		return $etmfw_system_data;
	}

	/**
	 * Generate html components.
	 *
	 * @param  string $etmfw_components    html to display.
	 * @since  1.0.0
	 */
	public function mwb_etmfw_plug_generate_html( $etmfw_components = array() ) {
		if ( is_array( $etmfw_components ) && ! empty( $etmfw_components ) ) {
			foreach ( $etmfw_components as $etmfw_component ) {
				switch ( $etmfw_component['type'] ) {

					case 'hidden':
					case 'number':
					case 'email':
					case 'text':
					?>
					<div class="mwb-form-group mwb-etmfw-<?php echo esc_attr($etmfw_component['type']); ?>">
						<div class="mwb-form-group__label">
							<label for="<?php echo esc_attr( $etmfw_component['id'] ); ?>" class="mwb-form-label"><?php echo esc_html( $etmfw_component['title'] ); // WPCS: XSS ok. ?></label>
						</div>
						<div class="mwb-form-group__control">
							<label class="mdc-text-field mdc-text-field--outlined">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
										<?php if ( 'number' != $etmfw_component['type'] ) { ?>
											<span class="mdc-floating-label" id="my-label-id" style=""><?php echo esc_attr( $etmfw_component['placeholder'] ); ?></span>
										<?php } ?>
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<input 
								class="mdc-text-field__input <?php echo esc_attr( $etmfw_component['class'] ); ?>" 
								name="<?php echo esc_attr( $etmfw_component['id'] ); ?>"
								id="<?php echo esc_attr( $etmfw_component['id'] ); ?>"
								type="<?php echo esc_attr( $etmfw_component['type'] ); ?>"
								value="<?php echo esc_attr( $etmfw_component['value'] ); ?>"
								placeholder="<?php echo esc_attr( $etmfw_component['placeholder'] ); ?>"
								>
							</label>
							<div class="mdc-text-field-helper-line">
								<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo wp_kses_post( $etmfw_component['description'] ); ?></div>
							</div>
						</div>
					</div>
					<?php
					break;

					case 'password':
					?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label for="<?php echo esc_attr( $etmfw_component['id'] ); ?>" class="mwb-form-label"><?php echo esc_html( $etmfw_component['title'] ); // WPCS: XSS ok. ?></label>
						</div>
						<div class="mwb-form-group__control">
							<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<input 
								class="mdc-text-field__input <?php echo esc_attr( $etmfw_component['class'] ); ?> mwb-form__password" 
								name="<?php echo esc_attr( $etmfw_component['id'] ); ?>"
								id="<?php echo esc_attr( $etmfw_component['id'] ); ?>"
								type="<?php echo esc_attr( $etmfw_component['type'] ); ?>"
								value="<?php echo esc_attr( $etmfw_component['value'] ); ?>"
								placeholder="<?php echo esc_attr( $etmfw_component['placeholder'] ); ?>"
								>
								<i class="material-icons mdc-text-field__icon mdc-text-field__icon--trailing mwb-password-hidden" tabindex="0" role="button">visibility</i>
							</label>
							<div class="mdc-text-field-helper-line">
								<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo wp_kses_post( $etmfw_component['description'] ); ?></div>
							</div>
						</div>
					</div>
					<?php
					break;

					case 'textarea':
					?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label class="mwb-form-label" for="<?php echo esc_attr( $etmfw_component['id'] ); ?>"><?php echo esc_attr( $etmfw_component['title'] ); ?></label>
						</div>
						<div class="mwb-form-group__control">
							<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea"  	for="text-field-hero-input">
								<span class="mdc-notched-outline">
									<span class="mdc-notched-outline__leading"></span>
									<span class="mdc-notched-outline__notch">
										<span class="mdc-floating-label"><?php echo esc_attr( $etmfw_component['placeholder'] ); ?></span>
									</span>
									<span class="mdc-notched-outline__trailing"></span>
								</span>
								<span class="mdc-text-field__resizer">
									<textarea class="mdc-text-field__input <?php echo esc_attr( $etmfw_component['class'] ); ?>" rows="2" cols="25" aria-label="Label" name="<?php echo esc_attr( $etmfw_component['id'] ); ?>" id="<?php echo esc_attr( $etmfw_component['id'] ); ?>" placeholder="<?php echo esc_attr( $etmfw_component['placeholder'] ); ?>"><?php echo esc_textarea( $etmfw_component['value'] ); // WPCS: XSS ok. ?></textarea>
								</span>
							</label>

						</div>
					</div>

					<?php
					break;

					case 'select':
					case 'multiselect':
					?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label class="mwb-form-label" for="<?php echo esc_attr( $etmfw_component['id'] ); ?>"><?php echo esc_html( $etmfw_component['title'] ); ?></label>
						</div>
						<div class="mwb-form-group__control">
							<div class="mwb-form-select">
								<select name="<?php echo esc_attr( $etmfw_component['id'] ); ?><?php echo ( 'multiselect' === $etmfw_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr( $etmfw_component['id'] ); ?>" class="mdl-textfield__input <?php echo esc_attr( $etmfw_component['class'] ); ?>" <?php echo 'multiselect' === $etmfw_component['type'] ? 'multiple="multiple"' : ''; ?> >
									<?php
									foreach ( $etmfw_component['options'] as $etmfw_key => $etmfw_val ) {
										?>
										<option value="<?php echo esc_attr( $etmfw_key ); ?>"
											<?php
											if ( is_array( $etmfw_component['value'] ) ) {
												selected( in_array( (string) $etmfw_key, $etmfw_component['value'], true ), true );
											} else {
												selected( $etmfw_component['value'], (string) $etmfw_key );
											}
											?>
											>
											<?php echo esc_html( $etmfw_val ); ?>
										</option>
										<?php
									}
									?>
								</select>
								<label class="mdl-textfield__label" for="octane"><?php echo esc_html( $etmfw_component['description'] ); ?></label>
							</div>
						</div>
					</div>

					<?php
					break;

					case 'checkbox':
					?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label for="<?php echo esc_attr( $etmfw_component['id'] ); ?>" class="mwb-form-label"><?php echo esc_html( $etmfw_component['title'] ); ?></label>
						</div>
						<div class="mwb-form-group__control mwb-pl-4">
							<div class="mdc-form-field">
								<div class="mdc-checkbox">
									<input 
									name="<?php echo esc_attr( $etmfw_component['id'] ); ?>"
									id="<?php echo esc_attr( $etmfw_component['id'] ); ?>"
									type="checkbox"
									class="mdc-checkbox__native-control <?php echo esc_attr( isset( $etmfw_component['class'] ) ? $etmfw_component['class'] : '' ); ?>"
									value="<?php echo esc_attr( $etmfw_component['value'] ); ?>"
									<?php if( 'on' === $etmfw_component['checked'] ){
										checked( $etmfw_component['checked'], 'on' );
									}
									?>
									/>
									<div class="mdc-checkbox__background">
										<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
											<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
										</svg>
										<div class="mdc-checkbox__mixedmark"></div>
									</div>
									<div class="mdc-checkbox__ripple"></div>
								</div>
								<label for="checkbox-1"><?php echo esc_html( $etmfw_component['description'] ); // WPCS: XSS ok. ?></label>
							</div>
						</div>
					</div>
					<?php
					break;

					case 'radio':
					?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label for="<?php echo esc_attr( $etmfw_component['id'] ); ?>" class="mwb-form-label"><?php echo esc_html( $etmfw_component['title'] ); ?></label>
						</div>
						<div class="mwb-form-group__control mwb-pl-4">
							<div class="mwb-flex-col">
								<?php
								foreach ( $etmfw_component['options'] as $etmfw_radio_key => $etmfw_radio_val ) {
									?>
									<div class="mdc-form-field">
										<div class="mdc-radio">
											<input
											name="<?php echo esc_attr( $etmfw_component['id'] ); ?>"
											value="<?php echo esc_attr( $etmfw_radio_key ); ?>"
											type="radio"
											class="mdc-radio__native-control <?php echo esc_attr( $etmfw_component['class'] ); ?>"
											<?php checked( $etmfw_radio_key, $etmfw_component['value'] ); ?>
											>
											<div class="mdc-radio__background">
												<div class="mdc-radio__outer-circle"></div>
												<div class="mdc-radio__inner-circle"></div>
											</div>
											<div class="mdc-radio__ripple"></div>
										</div>
										<label for="radio-1"><?php echo esc_html( $etmfw_radio_val ); ?></label>
									</div>	
									<?php
								}
								?>
							</div>
						</div>
					</div>
					<?php
					break;

					case 'radio-switch':
					?>

					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label for="" class="mwb-form-label"><?php echo esc_html( $etmfw_component['title'] ); ?></label>
						</div>
						<div class="mwb-form-group__control">
							<div>
								<div class="mdc-switch">
									<div class="mdc-switch__track"></div>
									<div class="mdc-switch__thumb-underlay">
										<div class="mdc-switch__thumb"></div>
										<input name="<?php echo esc_html( $etmfw_component['id'] ); ?>" type="checkbox" id="basic-switch" value="on" class="mdc-switch__native-control" role="switch" aria-checked="<?php if ( 'on' == $etmfw_component['value'] ) echo 'true'; else echo 'false'; ?>"
										<?php checked( $etmfw_component['value'], 'on' ); ?>
										>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
					break;

					case 'button':
					?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label"></div>
						<div class="mwb-form-group__control">
							<button class="mdc-button mdc-button--raised" name="<?php echo esc_attr( $etmfw_component['id'] ); ?>"
								id="<?php echo esc_attr( $etmfw_component['id'] ); ?>"> <span class="mdc-button__ripple"></span>
								<span class="mdc-button__label"><?php echo esc_attr( $etmfw_component['button_text'] ); ?></span>
							</button>
						</div>
					</div>

					<?php
					break;

					case 'submit':
					?>
					<tr valign="top">
						<td scope="row">
							<input type="submit" class="button button-primary" 
							name="<?php echo esc_attr( $etmfw_component['id'] ); ?>"
							id="<?php echo esc_attr( $etmfw_component['id'] ); ?>"
							value="<?php echo esc_attr( $etmfw_component['button_text'] ); ?>"
							/>
						</td>
					</tr>
					<?php
					break;

					case 'wp_editor':
					?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label for="" class="mwb-form-label"><?php echo esc_html( $etmfw_component['title'] ); ?></label>
						</div>
						<div class="mwb-form-group__control">
							<label for="<?php echo esc_attr( $etmfw_component['id'] ); ?>">
								<?php
								$content = stripcslashes( $etmfw_component['value'] );
								$editor_id = $etmfw_component['id'];
								$settings = array(
									'media_buttons'    => false,
									'drag_drop_upload' => true,
									'dfw'              => true,
									'teeny'            => true,
									'editor_height'    => 200,
									'editor_class'       => 'mwb_etmfw_new_woo_ver_style_textarea',
									'textarea_name'    => esc_attr( $etmfw_component['id'] ),
								);
								wp_editor( $content, $editor_id, $settings );
								?>
							</label>
							<div class="mdc-text-field-helper-line">
								<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo wp_kses_post( $etmfw_component['description'] ); ?></div>
							</div>
						</div>
					</div>
					<?php
					break;

					case 'textWithButton':
					?>
					<div class="mwb-form-group">
						<div class="mwb-form-group__label">
							<label for="" class="mwb-form-label"><?php echo esc_html( $etmfw_component['title'] ); ?></label>
						</div>
						<div class="mwb-form-group__control">
							<label class="mdc-text-field mdc-text-field--outlined">
							<?php
							if ( isset( $etmfw_component['custom_attribute'] ) && ! empty( $etmfw_component['custom_attribute'] ) && is_array( $etmfw_component['custom_attribute'] ) ) {
									foreach ( $etmfw_component['custom_attribute'] as $key => $val ) {
										if ( 'text' == $val['type'] ) {
											$this->mwb_etmfw_generate_text_html( $val );
										} elseif ( 'button' == $val['type'] ) {
											$this->mwb_etmfw_generate_button_html( $val );
										} elseif ( 'paragraph' == $val['type'] ) {
											$this->mwb_etmfw_generate_showbox( $val );
										}
									}
								}
							?>
							</label>
							<div class="mdc-text-field-helper-line">
								<div class="mdc-text-field-helper-text--persistent mwb-helper-text" id="" aria-hidden="true"><?php echo wp_kses_post( $etmfw_component['description'] ); ?></div>
							</div>
						</div>
					</div>
					<?php
					break;
					do_action( 'mwb_etmfw_add_custom_field_type', $etmfw_component );

					default:
					break;
				}
			}
		}
	}

	public function mwb_etmfw_generate_text_html( $value ){
		?>
		<span class="mdc-notched-outline">
			<span class="mdc-notched-outline__leading"></span>
			<span class="mdc-notched-outline__notch">
				<?php if ( 'number' != $value['type'] ) { ?>
					<span class="mdc-floating-label" id="my-label-id" style=""><?php echo esc_attr( $value['placeholder'] ); ?></span>
				<?php } ?>
			</span>
			<span class="mdc-notched-outline__trailing"></span>
		</span>
		<input 
		class="mdc-text-field__input <?php echo esc_attr( $value['class'] ); ?>" 
		name="<?php echo esc_attr( $value['id'] ); ?>"
		id="<?php echo esc_attr( $value['id'] ); ?>"
		type="<?php echo esc_attr( $value['type'] ); ?>"
		value="<?php echo esc_attr( $value['value'] ); ?>"
		>
		<?php
	}

	public function mwb_etmfw_generate_button_html( $value ){
		?>
		<div class="mwb-form-group">
			<div class="mwb-form-group__label"></div>
			<div class="mwb-form-group__control">
				<button class="mdc-button mdc-button--raised" name="<?php echo esc_attr( $value['id'] ); ?>"
					id="<?php echo esc_attr( $value['id'] ); ?>"> <span class="mdc-button__ripple"></span>
					<span class="mdc-button__label"><?php echo esc_attr( $value['button_text'] ); ?></span>
				</button>
			</div>
		</div>
		<?php
	}

	public function mwb_etmfw_generate_showbox( $value ) {
		?>
		<p id="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>">
			<span class="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>">
				<img src="" width="150px" height="150px" id="<?php echo esc_attr( array_key_exists( 'imgId', $value ) ? $value['imgId'] : '' ); ?>">
				<span class="<?php echo esc_attr( array_key_exists( 'spanX', $value ) ? $value['spanX'] : '' ); ?>">X</span>
			</span>
		</p>
		<?php
	}
}