<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/admin
 */

use Automattic\WooCommerce\Utilities\OrderUtil;
use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/admin
 * @author     WPSwings <webmaster@wpswings.com>
 */
class Event_Tickets_Manager_For_Woocommerce_Admin {





	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The object of common class file.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $wps_common_fun    The common variable used for classes.
	 */
	public $etmfw_public;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->etmfw_public = new Event_Tickets_Manager_For_Woocommerce_Public( $plugin_name, $version );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function etmfw_admin_enqueue_styles( $hook ) {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && ( 'wp-swings_page_event_tickets_manager_for_woocommerce_menu' == $screen->id || 'wp-swings_page_home' == $screen->id ) ) {
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_style( 'wps-etmfw-select2-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/event-tickets-manager-for-woocommerce-select2.css', array(), time(), 'all' );
			wp_enqueue_style( 'wps-etmfw-meterial-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'wps-etmfw-meterial-css2', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'wps-etmfw-meterial-lite', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );

			wp_enqueue_style( 'wps-etmfw-meterial-icons-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );

			wp_enqueue_style( $this->plugin_name . '-admin-global', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/event-tickets-manager-for-woocommerce-admin-global.css', array( 'wps-etmfw-meterial-icons-css' ), time(), 'all' );

			wp_enqueue_style( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/event-tickets-manager-for-woocommerce-admin.scss', array(), $this->version, 'all' );
		}
		if ( isset( $screen->id ) && ( 'product' == $screen->id || 'woocommerce_page_wps-etmfw-events-info' == $screen->id ) ) {
			// Date Time Picker Library.
			wp_enqueue_style( 'wps-etmfw-date-time-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datetimepicker-master/jquery.datetimepicker.css', array(), time(), 'all' );
			wp_enqueue_style( $this->plugin_name . '-admin-edit-product', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/event-tickets-manager-for-woocommerce-admin-edit-product.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 'event-tickets-manager-for-woocommerce-admin-icon.css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/event-tickets-manager-for-woocommerce-admin-icon.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function etmfw_admin_enqueue_scripts( $hook ) {

		$wps_plugin_list = get_option( 'active_plugins' );
		$wps_is_pro_active = false;
		$wps_plugin = 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php';
		if ( in_array( $wps_plugin, $wps_plugin_list ) ) {
			$wps_is_pro_active = true;
		}

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'wp-swings_page_event_tickets_manager_for_woocommerce_menu' == $screen->id ) {
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_script( 'wps-etmfw-select2', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/event-tickets-manager-for-woocommerce-select2.js', array( 'jquery' ), time(), false );

			wp_enqueue_script( 'wps-etmfw-metarial-js', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
			wp_enqueue_script( 'wps-etmfw-metarial-js2', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
			wp_enqueue_script( 'wps-etmfw-metarial-lite', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			wp_register_script( $this->plugin_name . 'admin-js', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/js/event-tickets-manager-for-woocommerce-admin.js', array( 'jquery', 'wps-etmfw-select2', 'wps-etmfw-metarial-js', 'wps-etmfw-metarial-js2', 'wps-etmfw-metarial-lite', 'wp-color-picker' ), $this->version, false );

			$wps_etmfw_selected_template = get_option( 'wps_etmfw_ticket_template', '1' );

			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'etmfw_admin_param',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'reloadurl' => admin_url( 'admin.php?page=event_tickets_manager_for_woocommerce_menu' ),
					'etmfw_gen_tab_enable' => get_option( 'wps_etmfw_radio_switch_demo' ),
					'wps_etmfw_selected_template' => $wps_etmfw_selected_template,
					'is_pro_active' => $wps_is_pro_active,
				)
			);

			wp_enqueue_script( $this->plugin_name . 'admin-js' );
			wp_enqueue_script( 'wp-color-picker' );
		}
		if ( isset( $screen->id ) && 'product' == $screen->id ) {
			// Date Time Picker Library.
			wp_enqueue_script( 'wps-etmfw-date-time', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/datetimepicker-master/jquery.datetimepicker.full.js', array( 'jquery' ), time(), false );
			wp_register_script( $this->plugin_name . 'admin-edit-product-js', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/js/event-tickets-manager-for-woocommerce-edit-product.js', array( 'jquery', 'wps-etmfw-date-time', 'jquery-ui-sortable' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name . 'admin-edit-product-js',
				'etmfw_edit_prod_param',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'wps_etmfw_edit_prod_nonce' => wp_create_nonce( 'wps-etmfw-verify-edit-prod-nonce' ),
					'is_pro_active' => $wps_is_pro_active,
				)
			);

			wp_enqueue_script( $this->plugin_name . 'admin-edit-product-js' );
		}

		wp_enqueue_script( $this->plugin_name . 'org-custom-js', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/js/event-tickets-manager-for-woocommerce-org-custom-admin.js', array( 'jquery', 'jquery-ui-sortable' ), $this->version, false );

		wp_localize_script(
			$this->plugin_name . 'org-custom-js',
			'wet_org_custom_param',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'wps_etmfw_edit_prod_nonce'   => wp_create_nonce( 'wps-etmfw-verify-edit-prod-nonce' ),
				'is_pro_active' => $wps_is_pro_active,
			)
		);
		wp_enqueue_media();
	}

	/**
	 * Dequeue theme.
	 *
	 * @return void
	 */
	public function etmfw_dequeque_theme_script() {
		 $active_theme = wp_get_theme(); // Get information about the active theme.
		$target_theme = 'Divi'; // Replace with the folder name of the theme you want to check.

		$screen = get_current_screen();

		if ( $screen instanceof WP_Screen && ! empty( $screen->id ) && 'product' == $screen->id ) {

			if ( $active_theme->get_template() === $target_theme ) {

				wp_dequeue_script( 'et_bfb_admin_date_addon_js' );
			}

			wp_dequeue_script( 'acf-timepicker' );
		}
	}

	/**
	 * Adding settings menu for Event Tickets Manager for WooCommerce.
	 *
	 * @since    1.0.0
	 */
	public function etmfw_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['wps-plugins'] ) ) {
			add_menu_page( 'WP Swings', 'WP Swings', 'manage_options', 'wps-plugins', array( $this, 'wps_plugins_listing_page' ), EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/wpswings_logo.png', 15 );

			// Add menus.
			if ( wps_etmfw_check_multistep() ) {
				add_submenu_page( 'wps-plugins', 'Home', 'Home', 'manage_options', 'home', array( $this, 'wps_etmfw_welcome_callback_function' ) );
			}

			$etmfw_menus = apply_filters( 'wps_add_plugins_menus_array', array() );
			if ( is_array( $etmfw_menus ) && ! empty( $etmfw_menus ) ) {
				foreach ( $etmfw_menus as $etmfw_key => $etmfw_value ) {
					add_submenu_page( 'wps-plugins', $etmfw_value['name'], $etmfw_value['name'], 'manage_options', $etmfw_value['menu_link'], array( $etmfw_value['instance'], $etmfw_value['function'] ) );
				}
			}
		} else {
			if ( ! empty( $submenu['wps-plugins'] ) ) {
				foreach ( $submenu['wps-plugins'] as $key => $value ) {
					if ( 'Home' === $value[0] ) {
						$is_home = true;
					}
				}
				if ( ! $is_home ) {
					if ( wps_etmfw_check_multistep() ) {
						add_submenu_page( 'wps-plugins', 'Home', 'Home', 'manage_options', 'home', array( $this, 'wps_etmfw_welcome_callback_function' ), 1 );
					}
				}
			}
		}
	}

	/**
	 *
	 * Adding the default menu into the wordpress menu
	 *
	 * @name wpswings_callback_function
	 * @since 1.0.0
	 */
	public function wps_etmfw_welcome_callback_function() {
		 include EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/event-ticket-for-woocommerce-welcome.php';
	}


	/**
	 * Removing default submenu of parent menu in backend dashboard.
	 *
	 * @since   1.0.0
	 */
	public function wps_etmfw_remove_default_submenu() {
		global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'wps-plugins', $submenu ) ) {
			if ( isset( $submenu['wps-plugins'][0] ) ) {
				unset( $submenu['wps-plugins'][0] );
			}
		}
	}


	/**
	 * Event Tickets Manager for WooCommerce etmfw_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function etmfw_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'            => __( 'Event Tickets Manager for WooCommerce', 'event-tickets-manager-for-woocommerce' ),
			'slug'            => 'event_tickets_manager_for_woocommerce_menu',
			'menu_link'       => 'event_tickets_manager_for_woocommerce_menu',
			'instance'        => $this,
			'function'        => 'etmfw_options_menu_html',
		);
		return $menus;
	}


	/**
	 * Event Tickets Manager for WooCommerce wps_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function wps_plugins_listing_page() {
		$active_marketplaces = apply_filters( 'wps_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * Event Tickets Manager for WooCommerce admin menu page.
	 *
	 * @since    1.0.0
	 */
	public function etmfw_options_menu_html() {
		 include_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/event-tickets-manager-for-woocommerce-admin-dashboard.php';
	}


	/**
	 * Event Tickets Manager for WooCommerce general setting tab.
	 *
	 * @since    1.0.0
	 * @param array $etmfw_settings_general Settings fields.
	 */
	public function wps_etmfw_admin_general_settings_page( $etmfw_settings_general ) {

		$etmfw_settings_general = array(
			array(
				'title' => __( 'Enable / Disable', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable plugin to start the functionality.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_etmfw_enable_plugin',
				'value' => get_option( 'wps_etmfw_enable_plugin' ),
				'class' => 'etmfw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),

			array(
				'title' => __( 'Enable Event Location Site', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable this option to display event location on Google Map.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_etmfw_enabe_location_site',
				'value' => get_option( 'wps_etmfw_enabe_location_site' ),
				'class' => 'etmfw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),

			array(
				'title' => __( 'Send Ticket During Processing Order', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable this option to send ticket during processing.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_wet_enable_after_payment_done_ticket',
				'value' => get_option( 'wps_wet_enable_after_payment_done_ticket' ),
				'class' => 'etmfw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),

			array(
				'title' => __( 'Include Barcode in ticket', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable this option to display Ticket code as Bar code in the ticket.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_etmfwp_include_barcode',
				'value' => get_option( 'wps_etmfwp_include_barcode' ),
				'class' => 'etmfw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Shortcode For Event Listing', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'wps_simple_text',
				'description'  => __( 'Use these shortcode --> [wps_my_all_event_list] to list all event on any page with search filter.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_event_list',
				'class' => 'etmfw-radio-switch-class',
				'value' => '',
			),

			array(
				'title' => __( 'Shortcode For Event Listing On Calender', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'wps_simple_text',
				'description'  => __( 'Use these shortcode --> [wps_event_in_calender] to show all event on any page in calender.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_event_calender',
				'class' => 'etmfw-radio-switch-class-pro',
				'value' => '',
			),
			array(
				'title' => __( 'Enable Check-in Count Show on Event Listing Page', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable this option to display Check-in Count', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_etmfwp_checkin_count',
				'value' => '',
				'class' => 'etmfw-radio-switch-class-pro',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Include QR code in ticket', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable this option to display qr code in the ticket.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_etmfwp_include_qr',
				'value' => '',
				'class' => 'etmfw-radio-switch-class-pro',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Enable Resend Button', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable it to resend the pdf ticket by admin and customer.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_etmfw_resend_plugin',
				'value' => '',
				'class' => 'etmfw-radio-switch-class-pro',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Enable Product Image as Logo', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable it to set the product image as logo on PDF Ticket.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_etmfw_prod_logo_plugin',
				'value' => '',
				'class' => 'etmfw-radio-switch-class-pro',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Event Date Format On Product Page', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'select',
				'id'    => 'wp_date_time_event_format',
				'class' => 'etmfw-radio-switch-class-pro',
				'value' => '',
				'options' => array(
					''       => __( 'Select Date Format', 'event-tickets-manager-for-woocommerce' ),
					'Y-m-d'   => __( 'YYYY-MM-DD', 'event-tickets-manager-for-woocommerce' ),
					'm/d/Y'   => __( 'MM/DD/YYYY', 'event-tickets-manager-for-woocommerce' ),
					'd-m-Y'   => __( 'DD-MM-YYYY', 'event-tickets-manager-for-woocommerce' ),
					'F j, Y'   => __( 'January 31, 2024', 'event-tickets-manager-for-woocommerce' ),
					'j-F-Y'   => __( '31-January-2024', 'event-tickets-manager-for-woocommerce' ),
					'M j, Y'   => __( 'Jan 31, 2024', 'event-tickets-manager-for-woocommerce' ),
					'j-M-Y'   => __( '31-Jan-2024', 'event-tickets-manager-for-woocommerce' ),
					'l, F j, Y'   => __( 'Thursday, January 31, 2024', 'event-tickets-manager-for-woocommerce' ),
					'l, j-F-Y'   => __( 'Thursday, 31-January-2024', 'event-tickets-manager-for-woocommerce' ),
				),
				'description'  => __( 'To Set The Date Format Which Will Show On The Product Page', 'event-tickets-manager-for-woocommerce' ),
			),
		);
		$etmfw_settings_general = apply_filters( 'wps_etmfw_extent_general_settings_array', $etmfw_settings_general );
		$etmfw_settings_general[] = array(
			'type'  => 'button',
			'id'    => 'wps_etmfw_save_general_settings',
			'button_text' => __( 'Save', 'event-tickets-manager-for-woocommerce' ),
			'class' => 'etmfw-button-class',
		);

		return $etmfw_settings_general;
	}

	/**
	 * Event Tickets Manager for WooCommerce  Integration Setting Tab.
	 *
	 * @since    1.0.0
	 * @param array $etmfw_settings_integrations Settings fields.
	 */
	public function wps_etmfw_admin_integration_settings_page( $etmfw_settings_integrations ) {
		$etmfw_settings_integrations = array(
			array(
				'title' => __( 'Google API Key', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'text',
				'description'  => __( 'To get your API key, visit <a target="_blank" href="http://www.gmapswidget.com/documentation/generate-google-maps-api-key/">here</a>, Make sure to enable <a target="_blank" href="https://console.cloud.google.com/apis/library">Maps JavaScript API</a> and <a target="_blank" href="https://console.cloud.google.com/apis/library">Geocoding API</a> in order to get Google Maps functionality.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_etmfw_google_maps_api_key',
				'value' => get_option( 'wps_etmfw_google_maps_api_key', '' ),
				'class' => 'etmfw-text-class',
				'placeholder' => __( 'Google API Key', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title' => __( 'Enable Twilio Integration', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable this send messages using twilio api.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_wet_enable_twilio_integration',
				'value' => '',
				'class' => 'etmfw-radio-switch-class-pro',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Enter Twilio API Sid', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'text',
				'description'  => __( 'Enter twilio API sid here', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_wet_twilio_sid',
				'value' => '',
				'class' => 'etmfw-radio-switch-class-pro',
				'placeholder' => __( 'Enter twilio API sid', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title' => __( 'Enter Twilio API Token', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'text',
				'description'  => __( 'Enable twilio API token here.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_wet_twilio_token',
				'value' => '',
				'class' => 'etmfw-radio-switch-class-pro',
				'placeholder' => __( 'Enter twilio API Token', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title' => __( 'Enter Twilio Sending Number', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'text',
				'description'  => __( 'Enable twilio sending number here.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_wet_twilio_number',
				'value' => '',
				'class' => 'etmfw-radio-switch-class-pro',
				'placeholder' => __( 'Enter twilio api sending number', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title' => __( 'Enter Content to send in Sms with ticket', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'textarea',
				'id'    => 'wps_wet_twilio_sms_content',
				'value' => '',
				'description'  => esc_html__( 'Use Placeholders  ', 'event-tickets-manager-for-woocommerce' ) . esc_html( '{event-time}' ) . esc_html__( ' for Event starting - ending time and ', 'event-tickets-manager-for-woocommerce' ) . esc_html( ' {venue} ' ) . esc_html__( ' for event location ,', 'event-tickets-manager-for-woocommerce' ) . esc_html( ' {event-name} ' ) . esc_html__( ' for Event-Name,', 'event-tickets-manager-for-woocommerce' ) . esc_html( ' {customer} ' ) . esc_html__( ' for Customer-Name,', 'event-tickets-manager-for-woocommerce' ) . esc_html( ' {ticket} ' ) . esc_html__( ' for Ticket-Number,', 'event-tickets-manager-for-woocommerce' ),
				'class' => 'etmfw-radio-switch-class-pro',
				'placeholder' => __( 'Enter content to send in sms', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Enable Sharing on Facebook', 'event-tickets-manager-for-woocommerce' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to share event product on facebook.', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_wet_facebook_sharing_enable',
				'value'       => '',
				'class'       => 'etmfw-radio-switch-class-pro',
				'name'        => 'wps_wet_facebook_sharing_enable',
			),
			array(
				'title'            => __( 'Enter Facebook App-Id here', 'event-tickets-manager-for-woocommerce' ),
				'type'             => 'text',
				'description'      => __( 'Enter Facebook app id here. Create an application in Facebook developer profile and enter the credentials here.', 'event-tickets-manager-for-woocommerce' ),
				'id'               => 'wps_wet_fb_app_id',
				'value'            => '',
				'class'            => 'etmfw-radio-switch-class-pro',
				'name'             => 'wps_wet_fb_app_id',
				'placeholder'      => __( 'Facebook App Id', 'event-tickets-manager-for-woocommerce' ),
				'custom_attribute' => array( 'autocomplete' => 'new-password' ),
			),
			array(
				'title'            => __( 'Enter Facebook App-Secret here', 'event-tickets-manager-for-woocommerce' ),
				'type'             => 'password',
				'description'      => __( 'Enter Facebook app secret here. Create an application in Facebook developer profile and enter the credentials here.', 'event-tickets-manager-for-woocommerce' ),
				'id'               => 'wps_wet_fb_app_secret',
				'value'            => '',
				'class'            => 'etmfw-radio-switch-class-pro',
				'name'             => 'wps_wet_fb_app_secret',
				'placeholder'      => __( 'Facebook App Secret', 'event-tickets-manager-for-woocommerce' ),
				'custom_attribute' => array( 'autocomplete' => 'new-password' ),
			),
			array(
				'title'            => __( 'Enter Facebook Access Token here', 'event-tickets-manager-for-woocommerce' ),
				'type'             => 'text',
				'description'      => __( '<a class="mdc-button generate-token mdc-button--raised mdc-ripple-upgraded" href="https://developers.facebook.com/tools/explorer" target="_blank">Generate Token</a>.', 'event-tickets-manager-for-woocommerce' ),
				'id'               => 'wps_wet_fb_app_access_token',
				'value'            => '',
				'class'            => 'etmfw-radio-switch-class-pro',
				'name'             => 'wps_wet_fb_app_access_token',
				'placeholder'      => __( 'Facebook Access Token', 'event-tickets-manager-for-woocommerce' ),

			),
			array(
				'title'       => __( 'Enable Whatsapp Integration', 'event-tickets-manager-for-woocommerce' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to send message on whatsapp on order notification,  you can go through this <a href="https://developers.facebook.com/docs/whatsapp/cloud-api/get-started" target="_blank">docs</a> you need to register from <a href="https://developers.facebook.com/docs/development/register" target="_blank">here</a>', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_wet_whatsapp_sharing_enable',
				'value'       => '',
				'class'       => 'etmfw-radio-switch-class-pro',
				'name'        => 'wps_wet_whatsapp_sharing_enable',
			),
			array(
				'title'       => __( 'Enter Phone number ID', 'event-tickets-manager-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'Enter Phone number ID here.', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_wet_whatsapp_phone_number_id',
				'value'       => '',
				'class'       => 'etmfw-radio-switch-class-pro',
				'placeholder' => __( 'Enter Phone number ID', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Enter Access Token', 'event-tickets-manager-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'Enable Access Token here.', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_wet_access_token',
				'value'       => '',
				'class'       => 'etmfw-radio-switch-class-pro',
				'placeholder' => __( 'Enter Access Token', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Enable Google Meet Integration', 'event-tickets-manager-for-woocommerce' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to send Google Meet link on email to customer for virtual event product,  Make Sure you enable the <a target="_blank" href="https://console.cloud.google.com/apis/library">Google Meet API</a> and <a target="_blank" href="https://console.cloud.google.com/apis/library">Google Calendar API</a> you need to create project from <a href="https://console.cloud.google.com/apis/dashboard" target="_blank">here</a>', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_wet_gmeet_sharing_enable',
				'value'       => '',
				'class'       => 'etmfw-radio-switch-class-pro',
				'name'        => 'wps_wet_gmeet_sharing_enable',
			),
			array(
				'title'       => __( 'Enter OAuth 2.0 Client ID', 'event-tickets-manager-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'You can create from credentials section under the APIs & Services in <a href="https://console.cloud.google.com/apis/dashboard" target="_blank">Google Cloud Console</a>.', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_wet_gmeet_client_id',
				'value'       => '',
				'class'       => 'etmfw-radio-switch-class-pro',
				'placeholder' => __( 'Enter Client ID here', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Enter OAuth 2.0 Client Secret', 'event-tickets-manager-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'After Setup, Make sure you have set the scopes( .../auth/calendar, .../auth/calendar.events, ./auth/meetings.space.created ) under the OAuth Consent Screen -> App Registration )  in <a href="https://console.cloud.google.com/apis/dashboard" target="_blank">Google Cloud Console</a>. <p><a class="mdc-button generate-token mdc-button--raised mdc-ripple-upgraded" href="#" target="_blank">Generate Token</a></p>.', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_wet_gmeet_client_secret',
				'value'       => '',
				'class'       => 'etmfw-radio-switch-class-pro',
				'placeholder' => __( 'Enable Client Secret here', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Enable Zoom Integration', 'event-tickets-manager-for-woocommerce' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to send zoom link on email to customer for virtual event product', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_wet_zoom_meeting_enable',
				'value'       => '',
				'class'       => 'etmfw-radio-switch-class-pro',
				'name'        => 'wps_wet_zoom_meeting_enable',
			),
			array(
				'title'       => __( 'Enter Client ID', 'event-tickets-manager-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'You can generate credentials from <a href="https://marketplace.zoom.us/" target="_blank">Zoom Market Place</a> after sign in, click on Develop and choose build app.', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_wet_zoom_meeting_client_id',
				'value'       => '',
				'class'       => 'etmfw-radio-switch-class-pro',
				'placeholder' => __( 'Enter Client ID here', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Enter Client Secret', 'event-tickets-manager-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'Make sure you have set the scopes( meeting:write and user:read or user:write ). Make Sure you enable the <a target="_blank" href="https://console.cloud.google.com/apis/library">Google Calendar API</a> so that zoom meeting link will be added to Google Calendar also. <p><a class="mdc-button generate-token mdc-button--raised mdc-ripple-upgraded" href="#" target="_blank">Generate Token</a></p>.', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_wet_zoom_meeting_client_secret',
				'value'       => '',
				'class'       => 'etmfw-radio-switch-class-pro',
				'placeholder' => __( 'Enable Client Secret here', 'event-tickets-manager-for-woocommerce' ),
			),
		);
		$etmfw_settings_integrations = apply_filters( 'wps_etmfw_extent_integration_settings_array', $etmfw_settings_integrations );
		$etmfw_settings_integrations[] = array(
			'type'  => 'button',
			'id'    => 'wps_etmfw_save_integrations_settings',
			'button_text' => __( 'Save', 'event-tickets-manager-for-woocommerce' ),
			'class' => 'etmfw-button-class',
		);

		return $etmfw_settings_integrations;
	}

	/**
	 * Event Tickets Manager for WooCommerce Email Template Setting Tab.
	 *
	 * @since    1.0.0
	 * @param array $etmfw_email_template_settings Settings fields.
	 */
	public function wps_etmfw_admin_email_template_settings_page( $etmfw_email_template_settings ) {
		$wps_etmfw_default_site_logo = get_option( 'wps_etmfw_mail_setting_upload_logo', '' );
		if ( isset( $wps_etmfw_default_site_logo ) && '' === $wps_etmfw_default_site_logo ) :
			if ( function_exists( 'the_custom_logo' ) ) {
				if ( has_custom_logo() ) {
					$site_logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
					$wps_etmfw_default_site_logo = $site_logo[0];
				}
			}
		endif;
		$etmfw_email_template_settings = array(
			array(
				'title' => __( 'Event Ticket Email Subject', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'text',
				'description'  => __( 'Email Subject to notify receiver about the event ticket received. Use [SITENAME] and [PRODUCTNAME] shortcode as the name of the site and product name respectively.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_etmfw_email_subject',
				'value' => get_option( 'wps_etmfw_email_subject', '' ),
				'class' => 'etmfw-text-class',
				'placeholder' => __( 'Email Subject', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title' => __( 'Event PDF Body Content', 'event-tickets-manager-for-woocommerce' ),
				'type' => 'wp_editor',
				'description'  => __( 'Use [SITENAME] shortcode as the name of the site.', 'event-tickets-manager-for-woocommerce' ),
				'id' => 'wps_etmfw_email_body_content',
				'value' => get_option( 'wps_etmfw_email_body_content', '' ),
			),

			array(
				'title' => __( 'Upload Default Logo', 'event-tickets-manager-for-woocommerce' ),
				'type' => 'textWithButton',
				'id' => 'wps_etmfw_mail_setting_upload_logo',
				'custom_attribute' => array(
					array(
						'type' => 'text',
						'custom_attributes' => array( 'readonly' => 'readonly' ),
						'class' => 'etmfw-text-class',
						'id' => 'wps_etmfw_mail_setting_upload_logo',
						'value' => $wps_etmfw_default_site_logo,
					),
					array(
						'type'  => 'button',
						'id'    => 'wps_etmfw_mail_setting_upload_logo_button',
						'button_text' => __( 'Upload Logo', 'event-tickets-manager-for-woocommerce' ),
						'class' => 'etmfw-button-class',
					),
					array(
						'type' => 'paragraph',
						'id' => 'wps_etmfw_mail_setting_remove_logo',
						'imgId' => 'wps_etmfw_mail_setting_upload_image',
						'spanX' => 'wps_etmfw_mail_setting_remove_logo_span',
					),
				),
				'class' => 'wps_etmfw_mail_setting_upload_logo_box',
				'description' => __( 'Upload the image which is used as a logo on your Email Template.', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title' => __( 'Hide Details on PDF Ticket', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable this option to hide details on PDF tickets.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_wet_hide_details_pdf_ticket',
				'value' => get_option( 'wps_wet_hide_details_pdf_ticket' ),
				'class' => 'etmfw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),
		);
		if ( ! is_plugin_active( 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php' ) ) {

			$wps_set_the_pdf_ticket_template = get_option( 'wps_etmfw_ticket_template', '1' );

			$etmfw_email_template_settings[] =
				array(
					'title' => __( 'Ticket Background Colour', 'event-tickets-manager-for-woocommerce' ),
					'type'  => 'text',
					'description'  => __( 'Select the colour code( e.g. #0000FF ).', 'event-tickets-manager-for-woocommerce' ),
					'id'    => 'wps_etmfw_ticket_bg_color',
					'value' => get_option( 'wps_etmfw_ticket_bg_color', '' ),
					'class' => 'wps_etmfw_colorpicker',
					'placeholder' => __( 'Enter colour/colour code', 'event-tickets-manager-for-woocommerce' ),
				);

			if ( '5' == $wps_set_the_pdf_ticket_template ) {
				$etmfw_email_template_settings[] =
					array(
						'title' => __( 'Ticket Text Colour', 'event-tickets-manager-for-woocommerce' ),
						'type'  => 'text',
						'description'  => __( 'Select the colour code( e.g. #FFFFFF ).', 'event-tickets-manager-for-woocommerce' ),
						'id'    => 'wps_etmfw_ticket_text_color',
						'value' => get_option( 'wps_etmfw_ticket_text_color', '' ),
						'class' => 'wps_etmfw_colorpicker',
						'placeholder' => __( 'Enter colour/colour code', 'event-tickets-manager-for-woocommerce' ),
					);
			} else {
				$etmfw_email_template_settings[] =
					array(
						'title' => __( 'Ticket Header Text Colour', 'event-tickets-manager-for-woocommerce' ),
						'type'  => 'text',
						'description'  => __( 'Select the colour code( e.g. #FFFFFF ).', 'event-tickets-manager-for-woocommerce' ),
						'id'    => 'wps_etmfw_ticket_text_color',
						'value' => get_option( 'wps_etmfw_ticket_text_color', '' ),
						'class' => 'wps_etmfw_colorpicker',
						'placeholder' => __( 'Enter colour/colour code', 'event-tickets-manager-for-woocommerce' ),
					);
				$etmfw_email_template_settings[] =
					array(
						'title' => __( 'Ticket Body Text Colour', 'event-tickets-manager-for-woocommerce' ),
						'type'  => 'text',
						'description'  => __( 'Select the colour code( e.g. #FFFFFF ).', 'event-tickets-manager-for-woocommerce' ),
						'id'    => 'wps_etmfw_ticket_body_text_color',
						'value' => get_option( 'wps_etmfw_ticket_body_text_color', '' ),
						'class' => 'wps_etmfw_colorpicker',
						'placeholder' => __( 'Enter colour/colour code', 'event-tickets-manager-for-woocommerce' ),
					);
			}
		}
		$etmfw_email_template_settings = apply_filters( 'wps_etmfw_extent_email_template_settings_array', $etmfw_email_template_settings );
		$etmfw_email_template_settings[] = array(
			'type'  => 'button',
			'id'    => 'wps_etmfw_save_email_template_settings',
			'button_text' => __( 'Save', 'event-tickets-manager-for-woocommerce' ),
			'class' => 'etmfw-button-class',
		);

		return $etmfw_email_template_settings;
	}


	/**
	 * Registering custom product type.
	 *
	 * @return void
	 */
	public function wps_wgc_register_event_ticket_manager_product_type() {
		require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'includes/class-wc-product-event-ticket-manager.php';
	}

	/**
	 * Event Tickets Manager for WooCommerce save tab settings.
	 *
	 * @since 1.0.0
	 */
	public function wps_etmfw_admin_save_tab_settings() {
		global $etmfw_wps_etmfw_obj, $error_notice;
		$etmfw_post_check = false;
		if ( wp_doing_ajax() ) {
			return;
		}
		if ( isset( $_POST['wps_event_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wps_event_nonce'] ) ), 'wps_event_nonce' ) ) {

			if ( isset( $_POST['wps_etmfw_save_general_settings'] ) ) {
				$etmfw_genaral_settings = apply_filters( 'wps_etmfw_general_settings_array', array() );
				$etmfw_post_check       = true;
			} elseif ( isset( $_POST['wps_etmfw_save_email_template_settings'] ) ) {
				$etmfw_genaral_settings = apply_filters( 'wps_etmfw_email_template_settings_array', array() );
				$etmfw_post_check       = true;
			} elseif ( isset( $_POST['wps_etmfw_save_integrations_settings'] ) ) {
				$etmfw_genaral_settings = apply_filters( 'wps_etmfw_integration_settings_array', array() );
				$etmfw_post_check       = true;
			} elseif ( isset( $_POST['wps_etmfw_save_other_settings'] ) ) {
				$etmfw_genaral_settings = apply_filters( 'wps_etmfw_other_settings_array', array() );
				$etmfw_post_check       = true;
			} elseif ( isset( $_POST['wps_etmfw_save_dashboard_settings'] ) ) {
				$etmfw_genaral_settings = apply_filters( 'wps_etmfw_dashboard_settings_array', array() );
				$etmfw_post_check       = true;
			}

			if ( $etmfw_post_check ) {

				$wps_etmfw_gen_flag = false;
				$etmfw_button_index = array_search( 'submit', array_column( $etmfw_genaral_settings, 'type' ) );
				if ( isset( $etmfw_button_index ) && ( null == $etmfw_button_index || '' == $etmfw_button_index ) ) {
					$etmfw_button_index = array_search( 'button', array_column( $etmfw_genaral_settings, 'type' ) );
				}
				if ( isset( $etmfw_button_index ) && '' !== $etmfw_button_index ) {
					unset( $etmfw_genaral_settings[ $etmfw_button_index ] );
					if ( is_array( $etmfw_genaral_settings ) && ! empty( $etmfw_genaral_settings ) ) {
						foreach ( $etmfw_genaral_settings as $etmfw_genaral_setting ) {
							if ( isset( $etmfw_genaral_setting['id'] ) && '' !== $etmfw_genaral_setting['id'] ) {
								if ( 'multi' === $etmfw_genaral_setting['type'] ) {
									$etmfw_general_settings_sub_arr = $etmfw_genaral_setting['value'];
									$settings_general_arr = array();
									foreach ( $etmfw_general_settings_sub_arr as $etmfw_genaral_sub_setting ) {
										if ( isset( $_POST[ $etmfw_genaral_sub_setting['id'] ] ) ) {
											$value                  = sanitize_text_field( wp_unslash( $_POST[ $etmfw_genaral_sub_setting['id'] ] ) );
											$settings_general_arr[] = $value;
										}
									}
									update_option( $etmfw_genaral_setting['id'], $settings_general_arr );
								} else {
									if ( isset( $_POST[ $etmfw_genaral_setting['id'] ] ) ) {
										update_option( $etmfw_genaral_setting['id'], is_array( $_POST[ $etmfw_genaral_setting['id'] ] ) ? map_deep( wp_unslash( $_POST[ $etmfw_genaral_setting['id'] ] ), 'sanitize_text_field' ) : wp_kses_post( wp_unslash( $_POST[ $etmfw_genaral_setting['id'] ] ) ) );
									} else {
										update_option( $etmfw_genaral_setting['id'], '' );
									}
								}
							} else {
								$wps_etmfw_gen_flag = true;
							}
						}
					}
					if ( $wps_etmfw_gen_flag ) {
						$wps_etmfw_error_text = esc_html__( 'Id of some field is missing', 'event-tickets-manager-for-woocommerce' );
						$etmfw_wps_etmfw_obj->wps_etmfw_plug_admin_notice( $wps_etmfw_error_text, 'error' );
					} else {
						$error_notice = false;
						do_action( 'wps_etmfw_save_admin_global_settings', $_POST );
					}
				}

				do_action( 'wps_etmfw_generate_access_token_unlimited_time' );
			}
		}
		if ( isset( $_POST['etmfw_track_button'] ) && isset( $_POST['wps-sfw-general-nonce-field'] ) ) {
			$wps_etmfw_geberal_nonce = sanitize_text_field( wp_unslash( $_POST['wps-sfw-general-nonce-field'] ) );
			if ( wp_verify_nonce( $wps_etmfw_geberal_nonce, 'wps-sfw-general-nonce' ) ) {

				if ( isset( $_POST['wps_etmfw_enable_tracking'] ) && '' !== $_POST['wps_etmfw_enable_tracking'] ) {
					$posted_value = sanitize_text_field( wp_unslash( $_POST['wps_etmfw_enable_tracking'] ) );
					update_option( 'wps_etmfw_enable_tracking', $posted_value );
				} else {
					update_option( 'wps_etmfw_enable_tracking', '' );
				}
				$etmfw_post_check = true;
			}
		}
	}

	/**
	 * Create a tab for Event Ticket Manager
	 *
	 * @since 1.0.0
	 * @name wps_etmfw_event_ticket_tab()
	 * @param array $tabs event tab.
	 * @return $tabs.
	 * @author WPSwings<ticket@wpswings.com>
	 * @link https://wpswings.com/
	 */
	public function wps_etmfw_event_ticket_tab( $tabs ) {
		if ( isset( $tabs ) && ! empty( $tabs ) ) {
			foreach ( $tabs as $key => $tab ) {
				if ( 'general' != $key && 'inventory' != $key ) {
					if ( isset( $tabs[ $key ]['class'] ) && is_array( $tabs[ $key ]['class'] ) ) {
						array_push( $tabs[ $key ]['class'], 'hide_if_event_ticket_manager' );
					}
				}
			}
		}

		$tabs['event_ticket'] = array(
			'label'   => __( 'Events', 'event-tickets-manager-for-woocommerce' ),
			'target'  => 'wps_etmfw_event_data',
			'priority' => 60,
			'class'   => array( 'show_if_event_ticket_manager' ),
		);
		return apply_filters( 'wps_etmfw_product_data_tabs', $tabs );
	}

	/**
	 * Create an event tab on product edit page.
	 *
	 * @since    1.0.0
	 */
	public function wps_etmfw_event_tab_content() {
		 global $post;
		$product_id = $post->ID;
		if ( isset( $product_id ) ) {
			if ( ! current_user_can( 'edit_post', $product_id ) ) {
				return;
			}
		}

		$wps_etmfw_product_array = get_post_meta( $product_id, 'wps_etmfw_product_array', true );
		$wps_etmfw_field_data = isset( $wps_etmfw_product_array['wps_etmfw_field_data'] ) && ! empty( $wps_etmfw_product_array['wps_etmfw_field_data'] ) ? $wps_etmfw_product_array['wps_etmfw_field_data'] : array();

		$wps_is_product_is_recurring = get_post_meta( $product_id, 'is_recurring_' . $product_id, '' );
		?>
		<div id="wps_etmfw_event_data" class="panel woocommerce_options_panel">
			<?php
			woocommerce_wp_text_input(
				array(
					'id'            => 'etmfw_start_date_time',
					'wrapper_class' => 'show_if_event_ticket_manager',
					'label'         => __( 'Start Date/ Time', 'event-tickets-manager-for-woocommerce' ),
					'value'         => isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '',
					'custom_attributes' => array( 'required' => 'required' ),
					'desc_tip'    => true,
					'description' => __( 'Enter the date and time when the event will start.', 'event-tickets-manager-for-woocommerce' ),
				)
			);

			woocommerce_wp_text_input(
				array(
					'id'            => 'etmfw_booking_offset_start_days',
					'wrapper_class' => 'show_if_event_ticket_manager',
					'label'         => __( 'Booking Offset (before Start Date)', 'event-tickets-manager-for-woocommerce' ),
					'value'         => isset( $wps_etmfw_product_array['etmfw_booking_offset_start_days'] ) ? $wps_etmfw_product_array['etmfw_booking_offset_start_days'] : '',
					'description'   => __( 'Users must book at least this many days before the event starts.', 'event-tickets-manager-for-woocommerce' ),
					'desc_tip'      => true,
					'type'          => 'number',
					'custom_attributes' => array(
						'min'  => '0',
						'step' => '1',
					),
				)
			);

			woocommerce_wp_text_input(
				array(
					'id'            => 'etmfw_end_date_time',
					'wrapper_class' => 'show_if_event_ticket_manager',
					'label'         => __( 'End Date/ Time', 'event-tickets-manager-for-woocommerce' ),
					'value'         => isset( $wps_etmfw_product_array['event_end_date_time'] ) ? $wps_etmfw_product_array['event_end_date_time'] : '',
					'custom_attributes' => array( 'required' => 'required' ),
					'desc_tip'    => true,
					'description' => __( 'Enter the date and time when the event will end.', 'event-tickets-manager-for-woocommerce' ),
				)
			);

			woocommerce_wp_text_input(
				array(
					'id'            => 'etmfw_booking_offset_end_days',
					'wrapper_class' => 'show_if_event_ticket_manager',
					'label'         => __( 'Booking Offset (before End Date)', 'event-tickets-manager-for-woocommerce' ),
					'value'         => isset( $wps_etmfw_product_array['etmfw_booking_offset_end_days'] ) ? $wps_etmfw_product_array['etmfw_booking_offset_end_days'] : '',
					'description'   => __( 'Users must book at least this many days before the event ends.', 'event-tickets-manager-for-woocommerce' ),
					'desc_tip'      => true,
					'type'          => 'number',
					'custom_attributes' => array(
						'min'  => '0',
						'step' => '1',
					),
				)
			);

			woocommerce_wp_text_input(
				array(
					'id'            => 'etmfw_event_venue',
					'wrapper_class' => 'show_if_event_ticket_manager',
					'label'         => __( 'Venue', 'event-tickets-manager-for-woocommerce' ),
					'value'         => isset( $wps_etmfw_product_array['etmfw_event_venue'] ) ? $wps_etmfw_product_array['etmfw_event_venue'] : '',
					'custom_attributes' => array( 'required' => 'required' ),
					'desc_tip'    => true,
					'description' => __( 'Enter the accurate full address of event where it will be held to get best google result. Ex: Dharmapuri, Forest Colony, Tajganj, Agra, Uttar Pradesh 282001, India', 'event-tickets-manager-for-woocommerce' ),
				)
			);

			require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'templates/backend/event-tickets-manager-for-woocommerce-api-loader.php';

			woocommerce_wp_text_input(
				array(
					'id'            => 'etmfw_event_venue_lat',
					'wrapper_class' => 'show_if_event_ticket_manager',
					'label'         => __( 'Location Latitude', 'event-tickets-manager-for-woocommerce' ),
					'value'         => isset( $wps_etmfw_product_array['etmfw_event_venue_lat'] ) ? $wps_etmfw_product_array['etmfw_event_venue_lat'] : '',
					'custom_attributes' => array( 'readonly' => 'readonly' ),
					'desc_tip'    => true,
					'description' => __( 'Latitude of the event geographic location.', 'event-tickets-manager-for-woocommerce' ),
				)
			);

			woocommerce_wp_text_input(
				array(
					'id'            => 'etmfw_event_venue_lng',
					'wrapper_class' => 'show_if_event_ticket_manager',
					'label'         => __( 'Location Longitude', 'event-tickets-manager-for-woocommerce' ),
					'value'         => isset( $wps_etmfw_product_array['etmfw_event_venue_lng'] ) ? $wps_etmfw_product_array['etmfw_event_venue_lng'] : '',
					'custom_attributes' => array( 'readonly' => 'readonly' ),
					'desc_tip'    => true,
					'description' => __( 'Longitude of the event geographic location ', 'event-tickets-manager-for-woocommerce' ),
				)
			);

			woocommerce_wp_checkbox(
				array(
					'id' => 'etmfw_event_trash_event',
					'wrapper_class' => 'show_if_event_ticket_manager',
					'label' => __( 'Remove/Hide Product', 'event-tickets-manager-for-woocommerce' ),
					'value' => isset( $wps_etmfw_product_array['etmfw_event_trash_event'] ) ? $wps_etmfw_product_array['etmfw_event_trash_event'] : true,
					'desc_tip'    => true,
					'description' => __( 'Remove/Hide Current Event Product On Event Expire ', 'event-tickets-manager-for-woocommerce' ),
				)
			);

			if ( 'on' === get_option( 'wps_etmfw_enabe_location_site', 'off' ) ) {
				woocommerce_wp_checkbox(
					array(
						'id' => 'etmfw_display_map',
						'wrapper_class' => 'show_if_event_ticket_manager',
						'label' => __( 'Display event on google map', 'event-tickets-manager-for-woocommerce' ),
						'value' => isset( $wps_etmfw_product_array['etmfw_display_map'] ) ? $wps_etmfw_product_array['etmfw_display_map'] : true,
						'desc_tip'    => true,
						'description' => __( 'To Show The Location On Map On Product Page', 'event-tickets-manager-for-woocommerce' ),
					)
				);
			}
			do_action( 'wps_etmfw_edit_product_settings', $product_id );

			if ( empty( $wps_is_product_is_recurring ) ) {
				woocommerce_wp_checkbox(
					array(
						'id' => 'etmfwp_recurring_event_enable',
						'wrapper_class' => 'show_if_event_ticket_manager',
						'label' => __( 'Recurring Event', 'event-tickets-manager-for-woocommerce' ),
						'value' => isset( $wps_etmfw_product_array['etmfwp_recurring_event_enable'] ) ? $wps_etmfw_product_array['etmfwp_recurring_event_enable'] : true,
						'desc_tip'    => true,
						'description' => __( 'Makes this event as recurring event.', 'event-tickets-manager-for-woocommerce' ),
					)
				);
			}

			require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'templates/backend/event-tickets-manager-for-woocommerce-recurring-event.php';

			require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'templates/backend/event-tickets-manager-for-woocommerce-user-type-product-price.php';

			do_action( 'wps_etmfw_edit_product_settings_extend', $product_id );

			require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'templates/backend/event-tickets-manager-for-woocommerce-add-ticket-dynamic-fields.php';

			wp_nonce_field( 'wps_etmfw_lite_nonce', 'wps_etmfw_product_nonce_field' );
			do_action( 'wps_etmfw_event_type_field', $product_id );
	}

		/**
		 * Save all the setting on product edit page for a particular product.
		 *
		 * @since    1.0.0
		 */
	public function wps_etmfw_save_product_data() {
		 global $post;
		if ( isset( $post->ID ) ) {
			if ( ! current_user_can( 'edit_post', $post->ID ) ) {
				return;
			}

			$product_id = $post->ID;
			$product = wc_get_product( $product_id );
			if ( isset( $product ) && is_object( $product ) ) {
				if ( $product->get_type() == 'event_ticket_manager' ) {
					if ( ! isset( $_POST['wps_etmfw_product_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wps_etmfw_product_nonce_field'] ) ), 'wps_etmfw_lite_nonce' ) ) {
						return;
					}
					$price = $product->get_price();

					$wps_etmfw_product_array = array();
					$wps_etmfw_product_array['etmfw_event_price'] = ! empty( $price ) ? $price : 0;

					$plugin = 'tutor-pro/tutor-pro.php'; // Tutor Pro.
					if ( is_plugin_active( $plugin ) ) {
						$wps_event_start_date = isset( $_POST['etmfw_start_date_time'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_start_date_time'] ) ) : '';
						$wps_event_end_date = isset( $_POST['etmfw_end_date_time'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_end_date_time'] ) ) : '';

						$wps_start_date_time = DateTime::createFromFormat( 'd/m/Y H:i', $wps_event_start_date );
						$wps_end_date_time = DateTime::createFromFormat( 'd/m/Y H:i', $wps_event_end_date );

						$wps_etmfw_product_array['event_start_date_time'] = $wps_start_date_time->format( 'Y-m-d h:i a' );
						$wps_etmfw_product_array['event_end_date_time'] = $wps_end_date_time->format( 'Y-m-d h:i a' );
					} else {
						$wps_etmfw_product_array['event_start_date_time'] = isset( $_POST['etmfw_start_date_time'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_start_date_time'] ) ) : '';
						$wps_etmfw_product_array['event_end_date_time'] = isset( $_POST['etmfw_end_date_time'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_end_date_time'] ) ) : '';
					}
					$wps_etmfw_product_array['wps_etmfw_field_user_type_price_data_baseprice'] = isset( $_POST['wps_base_price_cal'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_base_price_cal'] ) ) : 'base_price';
					$event_venue = isset( $_POST['etmfw_event_venue'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_event_venue'] ) ) : '';
					$event_lat = isset( $_POST['etmfw_event_venue_lat'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_event_venue_lat'] ) ) : '';
					$event_lng = isset( $_POST['etmfw_event_venue_lng'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_event_venue_lng'] ) ) : '';
					$wps_event_trash_on_expire_event = isset( $_POST['etmfw_event_trash_event'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_event_trash_event'] ) ) : 'no';
					$wps_etmfw_product_array['etmfw_event_venue'] = $event_venue;
					$wps_etmfw_product_array['etmfw_event_venue_lat'] = $event_lat;
					$wps_etmfw_product_array['etmfw_event_venue_lng'] = $event_lng;
					$wps_etmfw_product_array['etmfw_event_trash_event'] = $wps_event_trash_on_expire_event;
					$wps_etmfw_field_data = ! empty( $_POST['etmfw_fields'] ) ? map_deep( wp_unslash( $_POST['etmfw_fields'] ), 'sanitize_text_field' ) : array();

					// Save Data For The Dynamic Form Collection.
					$wps_etmfw_product_array['wps_etmfw_dyn_name'] = isset( $_POST['wps_etmfw_dyn_name'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_dyn_name'] ) ) : '';
					$wps_etmfw_product_array['wps_etmfw_dyn_mail'] = isset( $_POST['wps_etmfw_dyn_mail'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_dyn_mail'] ) ) : '';
					$wps_etmfw_product_array['wps_etmfw_dyn_contact'] = isset( $_POST['wps_etmfw_dyn_contact'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_dyn_contact'] ) ) : '';
					$wps_etmfw_product_array['wps_etmfw_dyn_date'] = isset( $_POST['wps_etmfw_dyn_date'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_dyn_date'] ) ) : '';
					$wps_etmfw_product_array['wps_etmfw_dyn_address'] = isset( $_POST['wps_etmfw_dyn_address'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_etmfw_dyn_address'] ) ) : '';
					$wps_etmfw_field_data_array = array();
					if ( is_array( $wps_etmfw_field_data ) && ! empty( $wps_etmfw_field_data ) ) {
						if ( '' !== $wps_etmfw_field_data[0]['_label'] ) {
							foreach ( $wps_etmfw_field_data as $key => $value ) {
								$wps_etmfw_field_data_array[] = array(
									'label' => $value['_label'],
									'type' => $value['_type'],
									'required' => isset( $value['_required'] ) ? $value['_required'] : 'off',
								);
							}
						}
					}

					$wps_etmfw_product_array['etmfw_attendees/organizer_tab_name'] = isset( $_POST['etmfw_attendees/organizer_tab_name'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_attendees/organizer_tab_name'] ) ) : __( 'Event Organizer and Attendees', 'event-tickets-manager-for-woocommerce' );
					$wps_etmfw_product_array['etmfw_display_attendees/organizer'] = isset( $_POST['etmfw_display_attendees/organizer'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_display_attendees/organizer'] ) ) : '';
					$wps_etmfw_product_array['etmfw_display_organizer'] = isset( $_POST['etmfw_display_organizer'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_display_organizer'] ) ) : '';
					$wps_etmfw_product_array['wps_organizer_multiselect'] = ! empty( $_POST['wps_event_organizer']['multiselect'] ) ? map_deep( wp_unslash( $_POST['wps_event_organizer']['multiselect'] ), 'sanitize_text_field' ) : array();

					$wps_etmfw_product_array['wps_event_recurring_type'] = ! empty( $_POST['wps_recurring_type'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_recurring_type'] ) ) : '';
					$wps_etmfw_product_array['wps_event_recurring_value'] = ! empty( $_POST['wps_recurring_value'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_recurring_value'] ) ) : '';

					// For the limit settiing.
					$wps_etmfw_product_array['wps_limit_user_purchase_event'] = ! empty( $_POST['wps_limit_user_purchase_event'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_limit_user_purchase_event'] ) ) : '';
					$wps_etmfw_product_array['etmfw_set_limit_qty'] = ! empty( $_POST['etmfw_set_limit_qty'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_set_limit_qty'] ) ) : '';

					// Daily Start/End Time Saving.
					$wps_etmfw_product_array['wps_event_recurring_daily_start_time'] = ! empty( $_POST['wps_event_daily_start_time_val'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_event_daily_start_time_val'] ) ) : '';
					$wps_etmfw_product_array['wps_event_recurring_daily_end_time'] = ! empty( $_POST['wps_event_daily_end_time_val'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_event_daily_end_time_val'] ) ) : '';

					$wps_etmfw_field_stock_price_data = ! empty( $_POST['etmfwp_fields'] ) ? map_deep( wp_unslash( $_POST['etmfwp_fields'] ), 'sanitize_text_field' ) : array();
					$wps_etmfw_field_stock_price_data_array = array();
					if ( is_array( $wps_etmfw_field_stock_price_data ) && ! empty( $wps_etmfw_field_stock_price_data ) ) {
						if ( '' !== $wps_etmfw_field_stock_price_data[0]['_label'] ) {
							foreach ( $wps_etmfw_field_stock_price_data as $key => $value ) {
								$wps_etmfw_field_stock_price_data_array[] = array(
									'label' => $value['_label'],
									'type' => $value['_type'],
									'price' => $value['_price'],
								);
							}
						}
					}
					$wps_etmfw_field_days_price_data = ! empty( $_POST['etmfwpp_fields'] ) ? map_deep( wp_unslash( $_POST['etmfwpp_fields'] ), 'sanitize_text_field' ) : array();
					$wps_etmfw_field_days_price_data_array = array();
					if ( is_array( $wps_etmfw_field_days_price_data ) && ! empty( $wps_etmfw_field_days_price_data ) ) {
						if ( '' !== $wps_etmfw_field_days_price_data[0]['_label'] ) {
							foreach ( $wps_etmfw_field_days_price_data as $key => $value ) {
								$wps_etmfw_field_days_price_data_array[] = array(
									'label' => $value['_label'],
									'type' => $value['_type'],
									'price' => $value['_price'],
								);
							}
						}
					}

					$wps_etmfw_field_user_type_price_data = ! empty( $_POST['etmfwppp_fields'] ) ? map_deep( wp_unslash( $_POST['etmfwppp_fields'] ), 'sanitize_text_field' ) : array();
					$wps_etmfw_field_days_user_type_data_array = array();
					if ( is_array( $wps_etmfw_field_user_type_price_data ) && ! empty( $wps_etmfw_field_user_type_price_data ) ) {
						if ( '' !== $wps_etmfw_field_user_type_price_data[0]['_label'] ) {
							foreach ( $wps_etmfw_field_user_type_price_data as $key => $value ) {
								$wps_etmfw_field_days_user_type_data_array[] = array(
									'label' => $value['_label'],
									'type' => $value['_type'],
									'price' => $value['_price'],
								);
							}
						}
					}
					$wps_etmfw_product_array['wps_etmfw_field_user_type_price_data'] = $wps_etmfw_field_days_user_type_data_array;
					$wps_etmfw_product_array['wps_etmfw_field_days_price_data'] = $wps_etmfw_field_days_price_data_array;
					$wps_etmfw_product_array['wps_etmfw_field_stock_price_data'] = $wps_etmfw_field_stock_price_data_array;
					$wps_etmfw_product_array['wps_etmfw_field_data'] = $wps_etmfw_field_data_array;
					$etmfw_display_map = isset( $_POST['etmfw_display_map'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_display_map'] ) ) : 'no';
					$wps_etmfw_product_array['etmfw_display_map'] = $etmfw_display_map;
					$wps_etmfw_product_array['etmfwp_recurring_event_enable'] = isset( $_POST['etmfwp_recurring_event_enable'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfwp_recurring_event_enable'] ) ) : 'no';
					$wps_etmfw_product_array['etmfw_booking_offset_start_days'] = isset( $_POST['etmfw_booking_offset_start_days'] ) ? sanitize_text_field( wp_unslash( absint( $_POST['etmfw_booking_offset_start_days'] ) ) ) : '';
					$wps_etmfw_product_array['etmfw_booking_offset_end_days'] = isset( $_POST['etmfw_booking_offset_end_days'] ) ? sanitize_text_field( wp_unslash( absint( $_POST['etmfw_booking_offset_end_days'] ) ) ) : '';
					$wps_etmfw_product_array = apply_filters( 'wps_etmfw_product_pricing', $wps_etmfw_product_array, $_POST );
					update_post_meta( $product_id, 'wps_etmfw_product_array', $wps_etmfw_product_array );

					if ( 'yes' === $wps_etmfw_product_array['etmfwp_recurring_event_enable'] ) {
						update_post_meta( $product_id, 'product_has_recurring', 'yes' );
					} else {
						update_post_meta( $product_id, 'product_has_recurring', 'no' );
					}
					do_action( 'wps_etmfw_event_product_type_save_fields', $product_id );

					do_action( 'wps_etmfw_share_event_on_fb', $product_id );
					do_action( 'wps_etmfw_generate_gmeet_link', $product_id );
					do_action( 'wps_etmfw_generate_zoom_link', $product_id );
				}
			}
		}
	}

		/**
		 *  Return array of all the possible fields to get user additional information.
		 *
		 * @since    1.0.0
		 * @return array $field_array.
		 */
	public function wps_etmfw_event_fields() {
		$field_array = array(
			'text'      => __( 'Text', 'event-tickets-manager-for-woocommerce' ),
			'textarea'  => __( 'Textarea', 'event-tickets-manager-for-woocommerce' ),
			'email'     => __( 'Email', 'event-tickets-manager-for-woocommerce' ),
			'number'    => __( 'Number', 'event-tickets-manager-for-woocommerce' ),
			'date'      => __( 'Date', 'event-tickets-manager-for-woocommerce' ),
			'yes-no'    => __( 'Yes/No', 'event-tickets-manager-for-woocommerce' ),
		);
		return apply_filters( 'wps_etmfw_extend_event_fields', $field_array );
	}


		/**
		 * Add a events submenu inside the Woocommerce Menu Page
		 *
		 * @since 1.0.0
		 * @name wps_etmfw_event_menu()
		 * @author WPSwings<webmaster@wpswings.com>
		 * @link https://wpswings.com/
		 */
	public function wps_etmfw_event_menu() {
		add_submenu_page( 'woocommerce', __( 'Events', 'event-tickets-manager-for-woocommerce' ), __( 'Events', 'event-tickets-manager-for-woocommerce' ), 'manage_options', 'wps-etmfw-events-info', array( $this, 'wps_etmfw_display_event_info' ) );
		do_action( 'wps_etmfw_admin_sub_menu' );
	}

		/**
		 * Display events information on events panel.
		 *
		 * @since    1.0.0
		 */
	public function wps_etmfw_display_event_info() {
		require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/class-event-tickets-manager-for-woocommerce-events-info.php';
		$url = esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL );
		?>
			<div class="wps_etmfw_event_display_wrapper">
				<h1><?php echo esc_html( 'Events', 'event-tickets-manager-for-woocommerce' ); ?></h1>

			<?php if ( is_plugin_active( 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php' ) ) { ?>
					<div class="wps_show_import_set">
						<button class="button" id="wps_show_attendee_set"><?php esc_html_e( 'Import Bulk Event Order Attendees', 'event-tickets-manager-for-woocommerce' ); ?></button>
					</div>

					<!-- Add this code to your page or custom template -->
					<form class="wps_new_attendee" method="post" enctype="multipart/form-data">
						<label for="csv_file">Upload CSV File:</label>
						<input type="file" name="csv_file" accept=".csv" required />
						<?php wp_nonce_field( 'name_of_your_action', 'name_of_your_nonce_field' ); ?>
						<p><?php esc_html_e( 'Download the ', 'event-tickets-manager-for-woocommerce' ); ?><a href="<?php echo esc_url( $url ) . 'admin/resources/icons/wps_import_attendee.csv'; ?>" download><?php esc_html_e( 'Sample CSV.', 'event-tickets-manager-for-woocommerce' ); ?></a> <?php esc_html_e( 'for reference.', 'event-tickets-manager-for-woocommerce' ); ?></p>
						<input type="submit" name="upload_csv" value="Upload CSV" />
					</form>
				<?php } ?>

				<form method="post">
				<?php
				do_action( 'wps_etmfw_support_csv' );
				$secure_nonce      = wp_create_nonce( 'wps-event-auth-nonce' );
				$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-event-auth-nonce' );
				if ( ! $id_nonce_verified ) {
					wp_die( esc_html__( 'Nonce Not verified', 'event-tickets-manager-for-woocommerce' ) );
				}
				$current_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
				?>
					<input type="hidden" name="page" value="<?php echo esc_attr( $current_page ); ?>">
					<?php
					wp_nonce_field( 'wps-etmfw-events', 'wps-etmfw-events' );
					$mylisttable = new Event_Tickets_Manager_For_Woocommerce_Events_Info();
					$mylisttable->prepare_items();
					$mylisttable->search_box( __( 'Search ', 'event-tickets-manager-for-woocommerce' ), 'wps-etmfw-events' );
					$mylisttable->display();
					?>
				</form>
			</div>
			<?php
	}

		/**
		 * Import the Attendes with order creation.
		 *
		 * @since    1.0.0
		 */
	public function wps_etmfw_import_attendess_callbck() {
		if ( ! isset( $_REQUEST['name_of_your_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['name_of_your_nonce_field'] ) ), 'name_of_your_action' ) ) {
			return;
		}
		if ( isset( $_POST['upload_csv'] ) ) {
			if ( ! empty( $_FILES['csv_file']['tmp_name'] ) ) {
				$csv_file = sanitize_text_field( wp_unslash( $_FILES['csv_file']['tmp_name'] ) );

				// Parse CSV file.
				$csv_data = array_map( 'str_getcsv', file( $csv_file ) );

				// Process each row in the CSV starting from index 1.
				$wps_count_item = count( $csv_data );
				for ( $i = 1; $i < $wps_count_item; $i++ ) {
					$row = $csv_data[ $i ];

					// Assuming CSV structure: product_id,customer_id,quantity,custom_meta_value.
					$product_id = (int) $row[0];           // Product Id.
					$customer_id = get_current_user_id();  // User Id.
					$quantity = (int) $row[2];             // quantity.
					$custom_meta_value = $row[3];          // User name.
					$billing_address = array(              // Billing Address.
						'first_name' => $row[3], // Replace with the first name.
					);

					$billing_email = $row[4];   // User Email.
					$billing_phone = $row[5];   // User Phone.

					$wps_order_note = $row[1];  // order note.

					// Create order.
					$order_id = $this->wps_create_order_from_csv( $product_id, $wps_order_note, $customer_id, $quantity, $custom_meta_value, $billing_address, $billing_email, $billing_phone );
				}
			}
		}
	}

		/**
		 * Create a csv for event order.
		 *
		 * @since 1.0.0
		 * @name wps_create_order_from_csv()
		 * @param int    $product_id product id.
		 * @param string $wps_order_note order notes.
		 * @param int    $customer_id customer id.
		 * @param int    $quantity product quantity.
		 * @param array  $custom_meta_value order meta data.
		 * @param array  $billing_address order billing address.
		 * @param array  $billing_email order email.
		 * @param array  $billing_phone order phone.
		 * @return $order.
		 * @author WPSwings<ticket@wpswings.com>
		 * @link https://wpswings.com/
		 */
	public function wps_create_order_from_csv( $product_id, $wps_order_note, $customer_id, $quantity, $custom_meta_value, $billing_address, $billing_email, $billing_phone ) {
		// Include WooCommerce functions.
		if ( ! function_exists( 'WC' ) ) {
			include_once( ABSPATH . 'wp-content/plugins/woocommerce/woocommerce.php' );
		}

		// Create an instance of the WC_Order class.
		$order = wc_create_order();

		// Get the product object.
		$product = wc_get_product( $product_id );

		// Add the product to the order.
		$order->add_product( $product, $quantity );

		// Set customer ID.
		$order->set_customer_id( $customer_id );

		// Set billing details.
		$order->set_address( $billing_address, 'billing' );
		$order->set_billing_email( $billing_email );
		$order->set_billing_phone( $billing_phone );

		if ( ! empty( $wps_order_note ) ) {
			$order->add_order_note( $wps_order_note );
		} else {
			$order->add_order_note( 'Manually Created Order By Admin' );
		}

		if ( isset( $custom_meta_value ) && ! empty( $custom_meta_value ) ) {
			foreach ( $order->get_items() as $item_key => $item ) {

				$item->add_meta_data( 'Name', $custom_meta_value, true );
			}
		}

		// Calculate totals and save the order.
		$order->calculate_totals();
		$order->save();

		// Return the order ID.
		return $order->get_id();
	}


		/**
		 * Display tickets on order item meta.
		 *
		 * @param string $item_id Event Item Id.
		 * @param object $item Event Order Item.
		 * @param object $_product Product Object.
		 * @since    1.0.0
		 */
	public function wps_etmfw_after_order_itemmeta( $item_id, $item, $_product ) {
		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			return;
		}
		$secure_nonce      = wp_create_nonce( 'wps-event-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-event-auth-nonce' );
		if ( ! $id_nonce_verified ) {
			wp_die( esc_html__( 'Nonce Not verified', 'event-tickets-manager-for-woocommerce' ) );
		}
		$wps_etmfw_enable = get_option( 'wps_etmfw_enable_plugin', false );
		$wps_etmfw_in_processing = get_option( 'wps_wet_enable_after_payment_done_ticket', false );
		if ( $wps_etmfw_enable ) {
			if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
				// HPOS Enabled.
				$post_id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
			} else {
				$post_id = isset( $_GET['post'] ) ? sanitize_text_field( wp_unslash( $_GET['post'] ) ) : '';
			}
			if ( isset( $post_id ) ) {
				$order_id = $post_id;
				$order = new WC_Order( $order_id );
				$order_status = $order->get_status();
				$temp_status = 'completed';
				if ( 'on' == $wps_etmfw_in_processing ) {
					$temp_status = 'processing';
				}
				if ( ( 'completed' == $order_status ) || ( 'processing' == $order_status ) ) {  // Create During Event Ticket.
					if ( null != $_product ) {
						$product_id = $_product->get_id();
						if ( isset( $product_id ) && ! empty( $product_id ) ) {
							$product_types = wp_get_object_terms( $product_id, 'product_type' );

							if ( isset( $product_types[0] ) ) {
								$product_type = $product_types[0]->slug;
								if ( 'event_ticket_manager' == $product_type ) {

									if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
										// HPOS usage is enabled.
										$ticket = $order->get_meta( "event_ticket#$order_id#$item_id", true );
									} else {
										$ticket = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
									}

									if ( is_array( $ticket ) && ! empty( $ticket ) ) {
										$length = count( $ticket );
										for ( $i = 0; $i < $length; $i++ ) {
											// Inline Style used for ticket infrormation.
											?>

												<p style="margin:0;"><b>
													<?php
													esc_html_e( 'Ticket ', 'event-tickets-manager-for-woocommerce' );
													echo esc_html( $i + 1 );
													?>
														:</b>
													<span style="background: rgb(0, 115, 170) none repeat scroll 0% 0%; color: white; padding: 1px 5px 1px 6px; font-weight: bolder; margin-left: 10px;"><?php echo esc_attr( $ticket[ $i ] ); ?></span>
												</p>
											<?php
										}
									} else {

										if ( isset( $ticket ) && ! empty( $ticket ) ) {
											// Inline Style used for ticket infrormation.
											?>
												<p style="margin:0;"><b><?php esc_html_e( 'Ticket', 'event-tickets-manager-for-woocommerce' ); ?> :</b>
													<span style="background: rgb(0, 115, 170) none repeat scroll 0% 0%; color: white; padding: 1px 5px 1px 6px; font-weight: bolder; margin-left: 10px;"><?php echo esc_attr( $ticket ); ?></span>
												</p>
											<?php
										}
									}
									do_action( 'wps_etmfw_after_order_itemmeta', $item_id, $item, $_product );
								}
							}
						}
					}
				}
			}
		}
	}

		/**
		 * This is used to add row meta on plugin activation.
		 *
		 * @since 1.0.0
		 * @name wps_etmfw_plugin_row_meta
		 * @author WPSwings<ticket@wpswings.com>
		 * @param mixed $links Contains links.
		 * @param mixed $file Contains main file.
		 * @link https://wpswings.com/
		 */
	public function wps_etmfw_plugin_row_meta( $links, $file ) {
		if ( strpos( $file, 'event-tickets-manager-for-woocommerce/event-tickets-manager-for-woocommerce.php' ) !== false ) {
			$new_links = array(
				'demo' => '<a href="https://demo.wpswings.com/event-tickets-manager-for-woocommerce-pro/?utm_source=wpswings-events-demo&utm_medium=events-org-backend&utm_campaign=demo" target="_blank"><img src="' . EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/Demo.svg"  style="margin-right: 6px;margin-top: -3px;max-width: 15px;">' . __( 'Demo', 'event-tickets-manager-for-woocommerce' ) . '</a>',
				'documentation' => '<a href="https://docs.wpswings.com/event-tickets-manager-for-woocommerce/?utm_source=wpswings-events-doc&utm_medium=events-org-backend&utm_campaign=documentation" target="_blank"><img src="' . EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/Documentation.svg" style="margin-right: 6px;margin-top: -3px;max-width: 15px;" >' . __( 'Documentation', 'event-tickets-manager-for-woocommerce' ) . '</a>',
				'video' => '<a href="https://www.youtube.com/embed/9KyB4qpal6M" target="_blank"><img src="' . EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/video.png" style="margin-right: 6px;margin-top: -3px;max-width: 15px;" >' . __( 'Video', 'event-tickets-manager-for-woocommerce' ) . '</a>',
				'support' => '<a href="https://wpswings.com/submit-query/?utm_source=wpswings-events-support&utm_medium=events-org-backend&utm_campaign=support" target="_blank"><img src="' . EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/Support.svg" style="margin-right: 6px;margin-top: -3px;max-width: 15px;" >' . __( 'Support', 'event-tickets-manager-for-woocommerce' ) . '</a>',
				'services' => '<a href="https://wpswings.com/woocommerce-services/?utm_source=wpswings-events-services&utm_medium=events-org-backend&utm_campaign=woocommerce-services" target="_blank"><img src="' . EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/services.svg" class="wps-info-img" style="margin-right: 6px;margin-top: -3px;max-width: 15px;" alt="Services image">' . __( 'Services', 'event-tickets-manager-for-woocommerce' ) . '</a>',
			);

			$links = array_merge( $links, $new_links );
		}
		return $links;
	}

		/**
		 * Get latitude and longitude of the event location.
		 *
		 * @since 1.0.0
		 * @name wps_etmfw_get_event_geocode_value
		 * @author WPSwings<ticket@wpswings.com>
		 * @link https://wpswings.com/
		 */
	public function wps_etmfw_get_event_geocode_value() {
		$response['result'] = false;
		$response['message'] = '';
		if ( ! isset( $_POST['wps_edit_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wps_edit_nonce'] ) ), 'wps-etmfw-verify-edit-prod-nonce' ) ) {
			return;
		}

		$api_key = get_option( 'wps_etmfw_google_maps_api_key', '' );
		$event_venue = isset( $_POST['venue'] ) ? sanitize_text_field( wp_unslash( $_POST['venue'] ) ) : '';
		if ( '' !== $api_key && '' !== $event_venue ) {
			$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $event_venue . '&key=' . $api_key;
			$args = array(
				'timeout' => 10,
				'headers' => array(
					'Content-Type' => 'application/json; charset=utf-8',
				),
			);
			$get_response = wp_remote_get( $url, $args );
			if ( is_wp_error( $get_response ) ) {
				$response['message'] = $get_response->get_error_message();
			} else {
				$get_response = json_decode( wp_remote_retrieve_body( $get_response ) );
				if ( 'OK' == $get_response->status ) {
					$response['result'] = true;
					$response['message'] = array(
						'lat' => $get_response->results[0]->geometry->location->lat,
						'lng' => $get_response->results[0]->geometry->location->lng,
					);
				} else {
					$response['message'] = $get_response->error_message;
				}
			}
		} else {
			$response['message'] = sprintf(
				/* translators: %s: Google API key setting link */
				esc_html__( 'Please add Google API key %s to display event location on google map.', 'event-tickets-manager-for-woocommerce' ),
				'<a href="' . admin_url( 'admin.php?page=event_tickets_manager_for_woocommerce_menu&etmfw_tab=event-tickets-manager-for-woocommerce-integrations' ) . '" target="_blank">here</a>'
			);
		}
		echo wp_json_encode( $response );
		wp_die();
	}

		/**
		 * Set order as booking type.
		 *
		 * @param int    $order_id current order id.
		 * @param object $order current order object.
		 * @return void
		 */
	public function wps_etmfw_set_order_as_event_ticket_manager( $order_id, $order ) {
		$order_items = $order->get_items();
		foreach ( $order_items as $item ) {
			$product = $item->get_product();
			if ( $product && is_object( $product ) && method_exists( $product, 'get_type' ) ) {
				if ( 'event_ticket_manager' === $product->get_type() ) {
					$order->update_meta_data( 'wps_order_type', 'event' );
					$order->save();
					break;
				}
			}
		}
	}

		/**
		 * Add custom badge of booking at order listing page.
		 *
		 * @param string $column_name current table columnname.
		 * @param int    $order_id current order id.
		 * @return void
		 */
	public function etmfw_add_label_for_event_type( $column_name, $order_id ) {
		if ( 'order_number' === $column_name ) {
			$order = wc_get_order( $order_id );
			if ( 'event' === $order->get_meta( 'wps_order_type', true ) ) {
				?>
					<span class="wps-etmfw-event-product-order-listing" title="<?php esc_html_e( 'This order contains Event Product.', 'event-tickets-manager-for-woocommerce' ); ?>">
					<?php esc_html_e( 'Event', 'event-tickets-manager-for-woocommerce' ); ?>
					</span>
				<?php
			}
		}
	}

		/**
		 * This function is used to add meta box on order detail page
		 *
		 * @name wps_uwgc_order_edit_meta_box
		 * @param String $post_type Contains post type.
		 * @param object $post Contains Post .
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
	public function wps_etmfw_order_edit_meta_box( $post_type, $post ) {

		$woo_ver = WC()->version;
		global $post;
		if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
			$screen = wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order';
			add_meta_box( 'wps_etmfw_resend_mail', __( 'Resend Ticket PDF Mail', 'event-tickets-manager-for-woocommerce' ), array( $this, 'wps_etmfw_resend_mail' ), $screen );
		} else {
			if ( isset( $post->ID ) && 'shop_order' == $post->post_type ) {
				$order_id = $post->ID;
				$order = new WC_Order( $order_id );
				$order_status = $order->get_status();

				if ( 'completed' == $order_status || 'processing' == $order_status ) {

					$giftcard = false;
					foreach ( $order->get_items() as $item_id => $item ) {
						if ( version_compare( $woo_ver, '3.0.0', '<' ) ) {
							$_product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
						} else {
							$_product = apply_filters( 'woocommerce_order_item_product', $item->get_product(), $item );
						}
						if ( isset( $_product ) && ! empty( $_product ) ) {
							$product_id = $_product->get_id();
						}
						if ( isset( $product_id ) && ! empty( $product_id ) ) {
							$product_types = wp_get_object_terms( $product_id, 'product_type' );
							if ( isset( $product_types[0] ) ) {
								$product_type = $product_types[0]->slug;

								if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
									// HPOS usage is enabled.
									$wps_gift_product = $order->get_meta( "event_ticket#$order_id#$item_id", true );
								} else {
									$wps_gift_product = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
								}

								if ( 'event_ticket_manager' == $product_type || ! empty( $wps_gift_product ) ) {
									$giftcard = true;
								}
							}
						}
					}

					if ( $giftcard ) {
						$screen = wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order';
						add_meta_box( 'wps_etmfw_resend_mail', __( 'Resend Ticket PDF Mail', 'event-tickets-manager-for-woocommerce' ), array( $this, 'wps_etmfw_resend_mail' ), $screen );
					}
				}
			}
		}
	}

		/**
		 * This function is used to add resend email button on order detal page
		 *
		 * @name wps_etmfw_resend_mail
		 * @param object $post_or_order_object Contains Post .
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
	public function wps_etmfw_resend_mail( $post_or_order_object ) {
		echo 'This is the resending the mail pdf';
		global $post;
		$order = ( $post_or_order_object instanceof WP_Post ) ? wc_get_order( $post_or_order_object->ID ) : $post_or_order_object;
		if ( null != $order->get_id() ) {
			$order_id = $order->get_id();
			?>
				<div id="wps_etmfw_loader" style="display: none;">
					<img src="<?php echo esc_url( EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL ); ?>admin/src/images/loading.gif">
				</div>
				<p><?php esc_html_e( 'If the user is not received a Ticket PDF or PDF is not generated , then PDF Ticket Mail then resend mail.', 'event-tickets-manager-for-woocommerce' ); ?> </p>
				<p id="wps_etmfw_resend_mail_notification"></p>
				<input type="button" data-id="<?php echo esc_html( $order_id ); ?>" id="wps_etmfw_resend_mail_button" class="button button-primary" value="<?php esc_html_e( 'Resend Ticket PDF Mail', 'event-tickets-manager-for-woocommerce' ); ?>">
				<?php
		}
	}

		/**
		 * This is function is used to resend the tickets in Mail.
		 *
		 * @name wps_etmfw_resend_the_ticket_pdf.
		 * @link http://www.wpswings.com/
		 */
	public function wps_etmfw_resend_the_ticket_pdf() {
		if ( ! isset( $_POST['wps_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wps_nonce'] ) ), 'wps-etmfw-verify-edit-prod-nonce' ) ) {
			return;
		}
		$response['result'] = false;
		if ( isset( $_POST['order_id'] ) ) {
			$response['result'] = true;
			$order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( wp_unslash( $_POST['order_id'] ) ) : '';
			$this->etmfw_public->wps_etmfw_process_event_order( $order_id, $old_status = '', $new_status = '' );
			$response['message_success'] = __( 'Email Sent Successfully!', 'event-tickets-manager-for-woocommerce' );
		} else {
			$response['message_error'] = __( 'Email Not Sent!', 'event-tickets-manager-for-woocommerce' );
		}

		echo wp_json_encode( $response );
		wp_die();
	}

		/**
		 * This is function is used to set cron for banner image.
		 *
		 * @name wps_etmfw_set_cron_for_plugin_notification.
		 * @link http://www.wpswings.com/
		 */
	public function wps_etmfw_set_cron_for_plugin_notification() {
		$wps_sfw_offset = get_option( 'gmt_offset' );
		$wps_sfw_time   = time() + $wps_sfw_offset * 60 * 60;
		if ( ! wp_next_scheduled( 'wps_wgm_check_for_notification_update' ) ) {
			wp_schedule_event( $wps_sfw_time, 'daily', 'wps_wgm_check_for_notification_update' );
		}

		$this->wps_etmfw_list_shortcode_in_gutenburg_block();
	}

		/**
		 * This is function is used to save data for banner image.
		 *
		 * @name wps_sfw_save_notice_message.
		 * @link http://www.wpswings.com/
		 */
	public function wps_sfw_save_notice_message() {
		 $wps_notification_data = $this->wps_sfw_get_update_notification_data();
		if ( is_array( $wps_notification_data ) && ! empty( $wps_notification_data ) ) {
			$banner_id      = array_key_exists( 'notification_id', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_id'] : '';
			$banner_image = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_image'] : '';
			$banner_url = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_url'] : '';
			$banner_type = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_type'] : '';
			update_option( 'wps_wgm_notify_new_banner_id', $banner_id );
			update_option( 'wps_wgm_notify_new_banner_image', $banner_image );
			update_option( 'wps_wgm_notify_new_banner_url', $banner_url );
			if ( 'regular' == $banner_type ) {
				update_option( 'wps_wgm_notify_hide_baneer_notification', 0 );
			}
		}
	}

		/**
		 * This is function is used to get data for banner image.
		 *
		 * @name wps_sfw_get_update_notification_data.
		 * @link http://www.wpswings.com/
		 */
	public function wps_sfw_get_update_notification_data() {
		$wps_notification_data = array();
		$url                   = 'https://demo.wpswings.com/client-notification/woo-gift-cards-lite/wps-client-notify.php';
		$attr                  = array(
			'action'         => 'wps_notification_fetch',
			'plugin_version' => EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_VERSION,
		);
		$query                 = esc_url_raw( add_query_arg( $attr, $url ) );
		$response              = wp_remote_get(
			$query,
			array(
				'timeout'   => 20,
				'sslverify' => false,
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo '<p><strong>Something went wrong: ' . esc_html( stripslashes( $error_message ) ) . '</strong></p>';
		} else {
			$wps_notification_data = json_decode( wp_remote_retrieve_body( $response ), true );
		}
		return $wps_notification_data;
	}

		/**
		 * This is function is used to run the ajax for banner image.
		 *
		 * @name wps_sfw_dismiss_notice_banner_callback.
		 * @link http://www.wpswings.com/
		 */
	public function wps_sfw_dismiss_notice_banner_callback() {
		if ( ! isset( $_POST['wps_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wps_nonce'] ) ), 'wps-etmfw-verify-edit-prod-nonce' ) ) {
			return;
		}
		$banner_id = get_option( 'wps_wgm_notify_new_banner_id', false );

		if ( isset( $banner_id ) && '' != $banner_id ) {
			update_option( 'wps_wgm_notify_hide_baneer_notification', $banner_id );
		}

		wp_send_json_success();
	}


		/**
		 * This is function is used to control css pro tag.
		 *
		 * @name wps_etmfw_css_control_callbck.
		 * @link http://www.wpswings.com/
		 */
	public function wps_etmfw_css_control_callbck() {
		$wps_plugin_list = get_option( 'active_plugins' );
		$wps_plugin = 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php';
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'wp-swings_page_event_tickets_manager_for_woocommerce_menu' == $screen->id ) {
			if ( in_array( $wps_plugin, $wps_plugin_list ) && is_admin() && ! wp_doing_ajax() ) {
				?>
					<style>
						.wps_etmfw_creation_setting td:before {
							display: none !important;
						}
					</style>
				<?php
			}
		}
	}

		/**
		 * Add tab in plugin setting.
		 *
		 * @param array $etmfw_settings_other default setting tabs.
		 * @return array
		 */
	public function wps_etmfw_other_settings_page( $etmfw_settings_other ) {
		$etmfw_settings_other = array(
			array(
				'title' => __( 'Reminder Send Before Event Day', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'number',
				'min' => '0',
				'max' => '7',
				'id'    => 'wps_etmfwp_send_remainder_before_event',
				'value' => get_option( 'wps_etmfwp_send_remainder_before_event' ),
				'class' => 'wps_etmfw_remainder_field',
				'description'  => __( 'Enter no. of days before event, email should be send as remainder. ', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Reminder Email Subject', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_etmfw_reminder_email_subject',
				'type'        => 'text',
				'description' => __( 'Subject for reminder emails.', 'event-tickets-manager-for-woocommerce' ),
				'placeholder' => __( 'Reminder Subject', 'event-tickets-manager-for-woocommerce' ),
				'value'       => get_option( 'wps_etmfw_reminder_email_subject', 'Reminder' ),
			),
			array(
				'title'       => __( 'Reminder Email Body', 'event-tickets-manager-for-woocommerce' ),
				'type'        => 'wp_editor',
				'description' => __( 'Use [STARTDATE], [SITENAME] and [PRODUCTNAME] shortcode as the start date of the event, name of the site and product name respectively.', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_etmfw_reminder_email_body',
				'value'       => get_option( 'wps_etmfw_reminder_email_body', 'Hello, This is a short Reminder for your Event which is start from [STARTDATE]. Enjoy your day!' ),
			),
			array(
				'title'       => __( 'Event Reschedule Notification', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_etmfw_schedule_change_emails',
				'class'       => 'etmfw-radio-switch-class-pro',
				'type'        => 'radio-switch',
				'description' => __( 'Notifies event purchasers of date/time changes, with an option to enable per product.', 'event-tickets-manager-for-woocommerce' ),
				'value'       => '',
				'options'     => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no'  => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),
			array(
				'title'       => __( 'Notify Pending/On-Hold Orders Too', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_etmfw_schedule_change_pending_onhold',
				'class'       => 'etmfw-radio-switch-class-pro',
				'type'        => 'radio-switch',
				'description' => __( 'If enabled, schedule change emails also go to pending and on-hold orders (unpaid). Keep off to notify only completed/processing orders.', 'event-tickets-manager-for-woocommerce' ),
				'value'       => '',
				'options'     => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no'  => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),
			array(
				'title'       => __( 'Copy to Clipboard Button', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_etmfw_copy_to_clipboard',
				'type'        => 'radio-switch',
				'description' => __( 'Enable Copy to Clipboard button on product page.', 'event-tickets-manager-for-woocommerce' ),
				'value'       => get_option( 'wps_etmfw_copy_to_clipboard', '' ),
			),
			array(
				'title'       => __( 'Enable Subscribe Checkbox', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_etmfw_enable_subscribe_checkbox',
				'class'       => 'etmfw-radio-switch-class-pro',
				'type'        => 'radio-switch',
				'description' => __( 'Enable subscribe checkbox on the checkout page.', 'event-tickets-manager-for-woocommerce' ),
				'value'       => '',
			),
			array(
				'title'       => __( 'Email Subject', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_etmfw_subscribe_email_subject',
				'class'       => 'etmfw-radio-switch-class-pro',
				'type'        => 'text',
				'description' => __( 'Subject for subscription emails.', 'event-tickets-manager-for-woocommerce' ),
				'value'       => '',
			),
			array(
				'title'       => __( 'Email Body', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_etmfw_subscribe_email_bodys',
				'class'       => 'etmfw-radio-switch-class-pro',
				'type'        => 'textarea',
				'description' => __( 'Use [SITENAME] and [PRODUCTNAME] shortcode as the name of the site and product name respectively.', 'event-tickets-manager-for-woocommerce' ),
				'value'       => '',
			),
			array(
				'title'         => __( 'Enable Social Share Settings', 'event-tickets-manager-for-woocommerce' ),
				'id'            => 'wps_etmfw_enable_social_sharing',
				'class'       => 'etmfw-radio-switch-class-pro',
				'type'          => 'radio-switch',
				'description'   => __( 'You can display social sharing icons to share event products.', 'event-tickets-manager-for-woocommerce' ),
				'value'         => '',
			),
			array(
				'title'         => __( 'Enable Social Share Name', 'event-tickets-manager-for-woocommerce' ),
				'id'            => 'wps_etmfw_enable_social_share_name',
				'class'         => 'etmfw-radio-switch-class-pro',
				'type'          => '',
				'description'   => __( 'You can display social sharing icons to share event products.', 'event-tickets-manager-for-woocommerce' ),
				'value'         => '',
			),
			array(
				'title'         => __( 'Delay Feedback email by a Day after event ends', 'event-tickets-manager-for-woocommerce' ),
				'type'          => 'number',
				'min'           => '0',
				'max'           => '15',
				'id'            => 'wps_etmfwp_send_email_day_after_event',
				'class'         => 'etmfw-radio-switch-class-pro',
				'value'         => '',
				'description'   => __( 'Enter no. of days after event ends, post feedback email should be send. ', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title'       => __( 'Post Event Feedback Email Subject', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_etmfw_post_event_feedback_email_subject',
				'class'       => 'etmfw-radio-switch-class-pro',
				'type'        => 'text',
				'description' => __( 'Subject for Post Event Feedback. Use [EVENTNAME] shortcode as event name', 'event-tickets-manager-for-woocommerce' ),
				'placeholder' => __( "We'd love your feedback on [EVENTNAME]!", 'event-tickets-manager-for-woocommerce' ),
				'value'       => '',
			),
			array(
				'title'       => __( 'Post Event Feedback Email Body', 'event-tickets-manager-for-woocommerce' ),
				'type'        => 'textarea',
				'description' => __( 'Use [USERNAME], [EVENTNAME], [FEEDBACKFORMLINK] and [SITENAME] shortcode as customer name, event name, feedback form link and site name respectively.', 'event-tickets-manager-for-woocommerce' ),
				'id'          => 'wps_etmfw_post_event_feedback_email_bodys',
				'class'       => 'etmfw-radio-switch-class-pro',
				'value'       => '',
			),
		);

		$etmfw_settings_other = apply_filters( 'wps_etmfw_extent_other_settings_array', $etmfw_settings_other );
		$etmfw_settings_other[] = array(
			'type'  => 'button',
			'id'    => 'wps_etmfw_save_other_settings',
			'button_text' => __( 'Save', 'event-tickets-manager-for-woocommerce' ),
			'class' => 'etmfw-button-class',
		);

		return $etmfw_settings_other;
	}

		/**
		 * Send Remainder.
		 *
		 * @return void
		 */
	public function wps_etmfwp_send_email_reminder() {
		$no_of_days = get_option( 'wps_etmfwp_send_remainder_before_event' );

		if ( empty( $no_of_days ) || '' == $no_of_days ) {
			return;
		}

		$shop_orders = wc_get_orders(
			array(
				'status'       => array( 'wc-completed', 'wc-processing' ),
				'return' => 'ids',
			)
		);
		if ( isset( $shop_orders ) && ! empty( $shop_orders ) ) {
			foreach ( $shop_orders as $shop_order ) {
				$order_id = $shop_order;
				$order = wc_get_order( $order_id );
				foreach ( $order->get_items() as $item_id => $item ) {
					$product = $item->get_product();
					$product_title = $item->get_name();

					if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {

						if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
							// HPOS usage is enabled.
							$ticket = $order->get_meta( "event_ticket#$order_id#$item_id", true );
						} else {
							$ticket = get_post_meta( $order_id, "event_ticket#$order_id#$item_id", true );
						}
						if ( '' !== $ticket ) {
							if ( ! empty( $product ) ) {
								$pro_id = $product->get_id();
							}
							$wps_etmfw_product_array = get_post_meta( $pro_id, 'wps_etmfw_product_array', true );
							$start = isset( $wps_etmfw_product_array['event_start_date_time'] ) ? $wps_etmfw_product_array['event_start_date_time'] : '';
							$start_timestamp = strtotime( $start );

							$current_date_time = strtotime( gmdate( 'Y-m-d h:i ', time() ) );
							$diff = (int) $start_timestamp - $current_date_time;
							$no_of_days_to_seconds = $no_of_days * 86400;
							$user_id = get_post_meta( $order_id, '_customer_user', true );
							$customer = new WC_Customer( $user_id );
							$user_email   = $order->get_billing_email();

							if ( $diff >= 0 && $diff <= $no_of_days_to_seconds ) {
								$mailer = WC()->mailer();
								$subject = get_option( 'wps_etmfw_reminder_email_subject', 'Reminder' );
								$message = get_option( 'wps_etmfw_reminder_email_body', 'Hello, This is a short Reminder for your Event which is start from [STARTDATE]. Enjoy your day!' );

								$message = str_replace( '[SITENAME]', get_bloginfo(), $message );
								$message = str_replace( '[STARTDATE]', $start, $message );
								$message = str_replace( '[PRODUCTNAME]', $product_title, $message );
								$wc_email = new WC_Email();

								$html_message = $wc_email->style_inline( $message );

								$mailer->send( $user_email, $subject, $html_message, HTML_EMAIL_HEADERS );
							}
						}
					}
				}
			}
		}
	}

		/**
		 * Create the Recurrence Event here.
		 *
		 * @return void
		 */
	public function wps_etmfw_create_recurring_event_callbck() {
		check_ajax_referer( 'wps-etmfw-verify-edit-prod-nonce', 'nonce' );
		$wps_event_product_id  = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';

		$wps_is_error = false;

		if ( ! empty( $wps_event_product_id ) && isset( $wps_event_product_id ) ) {

			$product_data = get_post_meta( $wps_event_product_id, 'wps_etmfw_product_array', array() );
			$wps_recurring_event_enable = $product_data[0]['etmfwp_recurring_event_enable'];

			$wps_event_recurring_type = $product_data[0]['wps_event_recurring_type'];
			$wps_event_recurring_value = $product_data[0]['wps_event_recurring_value'];

			$end_date = $product_data[0]['event_end_date_time']; // Replace with the desired end date.
			$start_date = $product_data[0]['event_start_date_time'];

			$start_date_time = new DateTime( $start_date );
			$end_date_time = new DateTime( $end_date );
			// Calculate the difference between the two dates.
			$interval = $start_date_time->diff( $end_date_time );

			// Check if the difference is exactly one week (7 days) and 0 hours, 0 minutes, and 0 seconds.
			if ( ( $interval->days < 7 && ( 'weekly' === $wps_event_recurring_type ) ) || ( $interval->days < 30 && ( 'monthly' === $wps_event_recurring_type ) ) || ( $interval->days < 1 && ( 'daily' === $wps_event_recurring_type ) ) ) {
				$wps_is_error = true;
			}

			$timestamp = strtotime( $end_date );

			if ( false != $timestamp ) {
				$wps_formatted_end_date = gmdate( 'Y-m-d', $timestamp );
			}

			$timestamp_start = strtotime( $start_date );

			if ( false != $timestamp_start ) {
				$wps_formatted_start_date = gmdate( 'Y-m-d', $timestamp_start );
			}

			if ( empty( $wps_event_recurring_type ) || empty( $wps_event_recurring_value || empty( $end_date ) || empty( $start_date ) ) || ( $wps_is_error ) ) {

				$wps_is_error = true;
			} else {

				$this->convert_to_recurring_event( $wps_event_product_id, $wps_event_recurring_type, $wps_formatted_end_date, $wps_formatted_start_date, $wps_event_recurring_value, $product_data );
			}
		}
		echo wp_json_encode( $wps_is_error );
		wp_die();
	}

		/**
		 * Creae the Recurrence Event Callback.
		 *
		 * @param int   $event_id is a eventproduct id.
		 * @param int   $recurring_type is a recurring type.
		 * @param int   $end_date is a end date.
		 * @param int   $start_date is a start date.
		 * @param int   $recurring_value is a recurring value.
		 * @param array $product_data is a product value.
		 * @return void
		 */
	public function convert_to_recurring_event( $event_id, $recurring_type, $end_date, $start_date, $recurring_value, $product_data ) {

		$product = wc_get_product( $event_id );
		$thumbnail_id = get_post_thumbnail_id( $event_id );
		$event_title = get_the_title( $event_id );

		$wps_event_venue = $product_data[0]['etmfw_event_venue'];
		$wps_event_map = $product_data[0]['etmfw_display_map'];
		$wps_event_lat = $product_data[0]['etmfw_event_venue_lat'];
		$wps_event_log = $product_data[0]['etmfw_event_venue_lng'];

		if ( 'daily' === $recurring_type ) {
			$wps_daily_event_start_time = $product_data[0]['wps_event_recurring_daily_start_time'];
			$wps_daily_event_end_time = $product_data[0]['wps_event_recurring_daily_end_time'];

			// Split the time string into hour and minute using explode.
			list($hour, $minute) = explode( ':', $wps_daily_event_start_time );

			// Convert the hour and minute components to integers.
			$hour = (int) $hour;
			$minute = (int) $minute;

			$date = new DateTime( $product_data[0]['event_start_date_time'] );
			$date->setTime( $hour, $minute );
			$wps_start_date = $date->format( 'Y-m-d g:i a' );

			// Split the time string into hour and minute using explode.
			list($hour, $minute) = explode( ':', $wps_daily_event_end_time );

			// Convert the hour and minute components to integers.
			$hour = (int) $hour;
			$minute = (int) $minute;

			$date = new DateTime( $product_data[0]['event_end_date_time'] );
			$date->setTime( $hour, $minute );
			// Format the DateTime object as a string in the desired format.
			$wps_end_date = $date->format( 'Y-m-d g:i a' );
		} else {

			$wps_start_date = $product_data[0]['event_start_date_time'];
			$wps_end_date = $product_data[0]['event_end_date_time'];
		}

		$wps_event_trash = $product_data[0]['etmfw_event_trash_event'];
		$wps_event_fb_share = $product_data[0]['etmfwp_share_on_fb'];

		// Parse the input date using strtotime for start date.
		$start_date_timestamp = strtotime( $wps_start_date );
		$start_formatted_date = gmdate( 'Y-m-d H:i:s', $start_date_timestamp );

		// Parse the input date using strtotime for end date.
		$end_date_timestamp = strtotime( $wps_end_date );
		$end_formatted_date = gmdate( 'Y-m-d H:i:s', $end_date_timestamp );

		// Set end date.
		$wps_event_set_end_formatted_time = gmdate( '(H, i, s)', $end_date_timestamp );

		// Remove the parentheses and split the string by commas.
		$time_parts = explode( ',', str_replace( array( '(', ')', ' ' ), '', $wps_event_set_end_formatted_time ) );

		// Extract the individual time components (hours, minutes, seconds).
		$hours = (int) trim( $time_parts[0] );
		$minutes = (int) trim( $time_parts[1] );
		$seconds = (int) trim( $time_parts[2] );

		$current_date = new DateTime( $start_formatted_date );
		$end_date_obj = new DateTime( $end_formatted_date );
		update_post_meta( $event_id, 'product_has_recurring', 'yes' );

		while ( $current_date <= $end_date_obj ) {

			// Calculate end date for the current instance based on recurring type.
			$current_end_date = clone $current_date;
			if ( 'weekly' === $recurring_type ) {
				$current_end_date->modify( '+6 days' );
				$current_end_date->setTime( $hours, $minutes, $seconds );
			} elseif ( 'monthly' === $recurring_type ) {
				$current_end_date->modify( 'last day of this month' );
				$current_end_date->setTime( $hours, $minutes, $seconds );
			}
			$current_end_date->setTime( $hours, $minutes, $seconds );

			$timestamp = strtotime( $current_date->format( 'Y-m-d' ) );
			$formatted_date = gmdate( 'j M Y', $timestamp );

			// Create a new event post for each recurring instance.
			$new_event_id = wp_insert_post(
				array(
					'post_title' => $event_title . ' (' . $formatted_date . ')',
					'post_type' => 'product',
					'post_status' => 'publish',
					'post_excerpt' => get_post_field( 'post_excerpt', $event_id ),
					'post_content' => get_post_field( 'post_content', $event_id ),
				)
			);

			// Define the array data for your recurring product (replace with your actual data).
			$recurring_product_data = array(
				'etmfw_event_price' => $product->get_price(),
				'event_start_date_time' => $current_date->format( 'Y-m-d H:i:s' ),
				'wps_etmfw_field_user_type_price_data_baseprice' => 'base_price',
				'event_end_date_time' => $current_end_date->format( 'Y-m-d H:i:s' ),
				'etmfw_event_venue' => $wps_event_venue,
				'etmfw_event_venue_lat' => $wps_event_lat,
				'etmfw_event_venue_lng' => $wps_event_log,
				'etmfw_event_trash_event' => $wps_event_trash,
				'wps_etmfw_dyn_name' => $product_data[0]['wps_etmfw_dyn_name'],
				'wps_etmfw_dyn_mail' => $product_data[0]['wps_etmfw_dyn_mail'],
				'wps_etmfw_dyn_contact' => $product_data[0]['wps_etmfw_dyn_contact'],
				'wps_etmfw_dyn_date' => $product_data[0]['wps_etmfw_dyn_date'],
				'wps_etmfw_dyn_address' => $product_data[0]['wps_etmfw_dyn_address'],
				'wps_etmfw_field_user_type_price_data' => $product_data[0]['wps_etmfw_field_user_type_price_data'],
				'wps_etmfw_field_days_price_data' => $product_data[0]['wps_etmfw_field_days_price_data'],
				'wps_etmfw_field_stock_price_data' => $product_data[0]['wps_etmfw_field_stock_price_data'],
				'wps_etmfw_field_data' => $product_data[0]['wps_etmfw_field_data'],
				'etmfw_display_map' => $wps_event_map,
				'etmfwp_share_on_fb' => $wps_event_fb_share,
				'etmfwp_recurring_event_enable' => 'no',
			);

			// Assuming $product_id contains the ID of the created recurring product.
			if ( $new_event_id ) {

				$categories = wp_get_object_terms( $event_id, 'product_cat', array( 'fields' => 'ids' ) );
				$tags = wp_get_object_terms( $event_id, 'product_tag', array( 'fields' => 'ids' ) );

				if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) {
					wp_set_object_terms( $new_event_id, $categories, 'product_cat' );
				}

				if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
					wp_set_object_terms( $new_event_id, $tags, 'product_tag' );
				}

				$manage_stock = get_post_meta( $event_id, '_manage_stock', true );
				$stock = get_post_meta( $event_id, '_stock', true );
				$stock_status = get_post_meta( $event_id, '_stock_status', true );

				// Inherit stock management settings from the parent event.
				update_post_meta( $new_event_id, '_manage_stock', $manage_stock );
				update_post_meta( $new_event_id, '_stock', $stock );
				update_post_meta( $new_event_id, '_stock_status', $$stock_status );

				// Save the array as post meta for the product.
				wp_set_object_terms( $new_event_id, 'event_ticket_manager', 'product_type' );
				update_post_meta( $new_event_id, 'wps_etmfw_product_array', $recurring_product_data );
				update_post_meta( $new_event_id, '_price', $product->get_price() );
				update_post_meta( $new_event_id, '_featured', 'yes' );
				update_post_meta( $new_event_id, '_sku', $product->get_sku() );
				update_post_meta( $new_event_id, '_thumbnail_id', $thumbnail_id );
				update_post_meta( $new_event_id, 'is_recurring_' . $new_event_id, 'yes' );
				update_post_meta( $new_event_id, 'parent_of_recurring', $event_id );
			}
			// Move to the next recurring date.
			if ( 'daily' === $recurring_type ) {
				$current_date->modify( "+$recurring_value day" );
			} elseif ( 'weekly' === $recurring_type ) {
				$current_date->modify( "+$recurring_value week" );
			} elseif ( 'monthly' === $recurring_type ) {
				$current_date->modify( "+$recurring_value month" );
			}
		}
	}

		/**
		 * Delete the Recurrence Event here.
		 *
		 * @return void
		 */
	public function wps_etmfw_delete_recurring_event_callbck() {
		check_ajax_referer( 'wps-etmfw-verify-edit-prod-nonce', 'nonce' );
		$wps_event_product_id  = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		delete_post_meta( $wps_event_product_id, 'product_has_recurring' );
		$wps_is_error = false;
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'parent_of_recurring',
					'value' => $wps_event_product_id,
					'compare' => '=',
					'type' => 'NUMERIC',
				),
			),
		);

		$products_query = new WP_Query( $args );

		if ( $products_query->have_posts() ) {
			while ( $products_query->have_posts() ) {
				$products_query->the_post();

				$post_id = get_the_ID();
				// Delete the post.
				wp_delete_post( $post_id, true ); // Set the second parameter to true to force deletion.

				// Delete all associated meta values.
				$meta_keys = get_post_custom_keys( $post_id );
				if ( ! empty( $meta_keys ) ) {
					foreach ( $meta_keys as $meta_key ) {
						delete_post_meta( $post_id, $meta_key );
					}
				}
				wp_reset_postdata();
			}
			echo wp_json_encode( $wps_is_error );
		}
	}

		/**
		 * Dsiplay the Recurrence Event here.
		 *
		 * @return void
		 */
	public function wps_etmfw_admin_recurring_submenu() {
		add_submenu_page( 'woocommerce', __( 'Events Recurring', 'event-tickets-manager-for-woocommerce' ), __( 'Events Recurring', 'event-tickets-manager-for-woocommerce' ), 'manage_options', 'wps-etmfw-recurring-events-info', array( $this, 'wps_etmfw_display_recurring_event_info' ) );
	}

		/**
		 * Display  The Recurrence Event here.
		 *
		 * @return void
		 */
	public function wps_etmfw_display_recurring_event_info() {
		$secure_nonce      = wp_create_nonce( 'wps-event-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-event-auth-nonce' );
		if ( ! $id_nonce_verified ) {
			wp_die( esc_html__( 'Nonce Not verified', 'event-tickets-manager-for-woocommerce' ) );
		}
		require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/class-event-tickets-manager-for-woocommerce-recurring-events-info.php';
		$wp_list_table = new Event_Tickets_Manager_For_Woocommerce_Recurring_Events_Info();
		$current_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
		?>
			<div>
				<form id="wps-events-filter" method="get">
				<?php wp_nonce_field( 'wps-etmfw-events', 'wps-etmfw-events' ); ?>
					<input type="hidden" name="page" value="<?php echo esc_attr( $current_page ); ?>" />
				<?php $wp_list_table->prepare_items(); ?>
				<?php $wp_list_table->search_box( __( 'Search ', 'event-tickets-manager-for-woocommerce' ), 'wps-etmfw-recurring-events' ); ?>
				<?php $wp_list_table->display(); ?>
				</form>
			</div>
		<?php
	}

		/**
		 * Display  The Recurrence Event here.
		 *
		 * @param array $query is a  listing data.
		 * @return void
		 */
	public function wps_exclude_recurring_products_from_product_listing( $query ) {

		try {
			if ( is_admin() && $query->is_main_query() && $query->get( 'post_type' ) === 'product' ) {
				$query->set(
					'meta_query',
					array(
						array(
							'key' => 'parent_of_recurring',
							'compare' => 'NOT EXISTS',
						),
					)
				);
			}
		} catch ( Exception $e ) {
			// Handle the exception here, e.g., log the error or display an error message.
			update_option( 'wps_query_error', $e->getMessage() );
		}
	}

		/**
		 * This function is used to list shortcodes in Gutenburg.
		 *
		 * @return void
		 */
	public function wps_etmfw_list_shortcode_in_gutenburg_block() {
		 wp_register_script( 'google-embeds-org-block-event', plugins_url( 'src/js/event-tickets-manager-for-woocommerce-org-custom-admin.js', __FILE__ ), array( 'wp-blocks', 'wp-editor', 'wp-element', 'wp-components' ), $this->version, false );
		register_block_type( 'wpswings/googles-embed-org-event', array( 'editor_script' => 'google-embeds-org-block-event' ) );
	}

		/**
		 * This function is used to modify event data with get prefix.
		 *
		 * @param array $event_data Event data.
		 * @return array
		 */
	public function wps_event_modify_event_data_with_get_prefix( $event_data ) {
		$fields = array(
			'etmfw_event_price',
			'event_start_date_time',
			'event_end_date_time',
			'wps_etmfw_field_user_type_price_data_baseprice',
			'etmfw_event_venue',
			'etmfw_event_venue_lat',
			'etmfw_event_venue_lng',
			'etmfw_event_trash_event',
			'wps_etmfw_dyn_name',
			'wps_etmfw_dyn_mail',
			'wps_etmfw_dyn_contact',
			'wps_etmfw_dyn_date',
			'wps_etmfw_dyn_address',
			'etmfw_attendees/organizer_tab_name',
			'etmfw_display_attendees/organizer',
			'etmfw_display_organizer',
			'wps_organizer_multiselect',
			'wps_event_recurring_type',
			'wps_event_recurring_value',
			'wps_limit_user_purchase_event',
			'etmfw_set_limit_qty',
			'wps_event_recurring_daily_start_time',
			'wps_event_recurring_daily_end_time',
			'wps_etmfw_field_user_type_price_data',
			'wps_etmfw_field_days_price_data',
			'wps_etmfw_field_stock_price_data',
			'wps_etmfw_field_data',
			'etmfw_display_map',
			'etmfwp_recurring_event_enable',
		);

		$event_data = apply_filters( 'wps_etmfw_zoho_crm_custom_event_data_add_fields', $fields );
		return $event_data;
	}

		/**
		 * This function is used to save the dashboard settings.
		 *
		 * @param array $etmfw_settings_dashboard event data.
		 * @return array
		 */
	public function wps_etmfw_save_dashboard_settings( $etmfw_settings_dashboard ) {
		$etmfw_settings_dashboard = array(
			array(
				'title' => __( 'Allow Ticket Transfer', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable this option  to transfer the tickets to another on my account section - my event tickets tab.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_wet_enable_ticket_sharing',
				'value' => get_option( 'wps_wet_enable_ticket_sharing' ),
				'class' => 'etmfw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),

			array(
				'title' => __( 'Events Dashboard Content', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'text',
				'description'  => __( 'This content will display in the my account section - My Event Tickets Tab.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_etmfw_event_dashboard',
				'value' => get_option( 'wps_etmfw_event_dashboard', 'View and transfer events tickets seamlessly for better organization and efficient event handling.' ),
				'class' => 'etmfw-text-class',
				'placeholder' => __( 'Events Dashboard Content', 'event-tickets-manager-for-woocommerce' ),
			),

			array(
				'title' => __( 'Event Dashboard/Listing Colour', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'text',
				'description'  => __( 'Select the colour code( e.g. #0000FF ).', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'wps_etmfw_event_dashboard_color',
				'value' => get_option( 'wps_etmfw_event_dashboard_color', '#0095eb' ),
				'class' => 'wps_etmfw_colorpicker',
				'placeholder' => __( 'Enter colour/colour code', 'event-tickets-manager-for-woocommerce' ),
			),

			array(
				'title' => __( 'Enter External Css', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'textarea',
				'id'    => 'wps_etmfw_external_css',
				'value' => get_option( 'wps_etmfw_external_css', '' ),
				'class' => 'etmfw-text-class',
				'placeholder' => __( 'Add External Css for the site.', 'event-tickets-manager-for-woocommerce' ),
			),

		);

		$etmfw_settings_dashboard = apply_filters( 'wps_etmfw_extent_dashboard_settings_array', $etmfw_settings_dashboard );
		$etmfw_settings_dashboard[] = array(
			'type'  => 'button',
			'id'    => 'wps_etmfw_save_dashboard_settings',
			'button_text' => __( 'Save', 'event-tickets-manager-for-woocommerce' ),
			'class' => 'etmfw-button-class',
		);

		return $etmfw_settings_dashboard;
	}
}
