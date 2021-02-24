<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 * @subpackage Event_Tickets_Manager_For_Woocommerce/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function etmfw_admin_enqueue_styles( $hook ) {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_event_tickets_manager_for_woocommerce_menu' == $screen->id ) {

			wp_enqueue_style( 'mwb-etmfw-select2-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/event-tickets-manager-for-woocommerce-select2.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-etmfw-meterial-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-etmfw-meterial-css2', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-etmfw-meterial-lite', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-etmfw-meterial-icons-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );

			wp_enqueue_style( $this->plugin_name . '-admin-global', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/event-tickets-manager-for-woocommerce-admin-global.css', array( 'mwb-etmfw-meterial-icons-css' ), time(), 'all' );

			wp_enqueue_style( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/event-tickets-manager-for-woocommerce-admin.scss', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function etmfw_admin_enqueue_scripts( $hook ) {

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_event_tickets_manager_for_woocommerce_menu' == $screen->id ) {
			wp_enqueue_script( 'mwb-etmfw-select2', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/event-tickets-manager-for-woocommerce-select2.js', array( 'jquery' ), time(), false );

			wp_enqueue_script( 'mwb-etmfw-metarial-js', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-etmfw-metarial-js2', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-etmfw-metarial-lite', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );

			wp_register_script( $this->plugin_name . 'admin-js', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/js/event-tickets-manager-for-woocommerce-admin.js', array( 'jquery', 'mwb-etmfw-select2', 'mwb-etmfw-metarial-js', 'mwb-etmfw-metarial-js2', 'mwb-etmfw-metarial-lite' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'etmfw_admin_param',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'reloadurl' => admin_url( 'admin.php?page=event_tickets_manager_for_woocommerce_menu' ),
					'etmfw_gen_tab_enable' => get_option( 'etmfw_radio_switch_demo' ),
				)
			);

			wp_enqueue_script( $this->plugin_name . 'admin-js' );
		}
	}

	/**
	 * Adding settings menu for Event Tickets Manager for WooCommerce.
	 *
	 * @since    1.0.0
	 */
	public function etmfw_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( __( 'MakeWebBetter', 'event-tickets-manager-for-woocommerce' ), __( 'MakeWebBetter', 'event-tickets-manager-for-woocommerce' ), 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/mwb-logo.png', 15 );
			$etmfw_menus = apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $etmfw_menus ) && ! empty( $etmfw_menus ) ) {
				foreach ( $etmfw_menus as $etmfw_key => $etmfw_value ) {
					add_submenu_page( 'mwb-plugins', $etmfw_value['name'], $etmfw_value['name'], 'manage_options', $etmfw_value['menu_link'], array( $etmfw_value['instance'], $etmfw_value['function'] ) );
				}
			}
		}
	}

	/**
	 * Removing default submenu of parent menu in backend dashboard
	 *
	 * @since   1.0.0
	 */
	public function mwb_etmfw_remove_default_submenu() {
		global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'mwb-plugins', $submenu ) ) {
			if ( isset( $submenu['mwb-plugins'][0] ) ) {
				unset( $submenu['mwb-plugins'][0] );
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
	 * Event Tickets Manager for WooCommerce mwb_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function mwb_plugins_listing_page() {
		$active_marketplaces = apply_filters( 'mwb_add_plugins_menus_array', array() );
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
	 * Event Tickets Manager for WooCommerce admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $etmfw_settings_general Settings fields.
	 */
	public function etmfw_admin_general_settings_page( $etmfw_settings_general ) {

		$etmfw_settings_general = array(
			array(
				'title' => __( 'Enable plugin', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable plugin to start the functionality.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'etmfw_radio_switch_demo',
				'value' => get_option( 'etmfw_radio_switch_demo' ),
				'class' => 'etmfw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),

			array(
				'type'  => 'button',
				'id'    => 'etmfw_button_demo',
				'button_text' => __( 'Button Demo', 'event-tickets-manager-for-woocommerce' ),
				'class' => 'etmfw-button-class',
			),
		);
		// print_r($etmfw_settings_general);die;
		return $etmfw_settings_general;
	}

	/**
	 * Event Tickets Manager for WooCommerce admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $etmfw_settings_template Settings fields.
	 */
	public function etmfw_admin_template_settings_page( $etmfw_settings_template ) {
		$etmfw_settings_template = array(
			array(
				'title' => __( 'Text Field Demo', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'text',
				'description'  => __( 'This is text field demo follow same structure for further use.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'etmfw_text_demo',
				'value' => '',
				'class' => 'etmfw-text-class',
				'placeholder' => __( 'Text Demo', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title' => __( 'Number Field Demo', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'number',
				'description'  => __( 'This is number field demo follow same structure for further use.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'etmfw_number_demo',
				'value' => '',
				'class' => 'etmfw-number-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Password Field Demo', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'password',
				'description'  => __( 'This is password field demo follow same structure for further use.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'etmfw_password_demo',
				'value' => '',
				'class' => 'etmfw-password-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Textarea Field Demo', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'textarea',
				'description'  => __( 'This is textarea field demo follow same structure for further use.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'etmfw_textarea_demo',
				'value' => '',
				'class' => 'etmfw-textarea-class',
				'rows' => '5',
				'cols' => '10',
				'placeholder' => __( 'Textarea Demo', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title' => __( 'Select Field Demo', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'select',
				'description'  => __( 'This is select field demo follow same structure for further use.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'etmfw_select_demo',
				'value' => '',
				'class' => 'etmfw-select-class',
				'placeholder' => __( 'Select Demo', 'event-tickets-manager-for-woocommerce' ),
				'options' => array(
					'' => __( 'Select option', 'event-tickets-manager-for-woocommerce' ),
					'INR' => __( 'Rs.', 'event-tickets-manager-for-woocommerce' ),
					'USD' => __( '$', 'event-tickets-manager-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Multiselect Field Demo', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'multiselect',
				'description'  => __( 'This is multiselect field demo follow same structure for further use.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'etmfw_multiselect_demo',
				'value' => '',
				'class' => 'etmfw-multiselect-class mwb-defaut-multiselect',
				'placeholder' => '',
				'options' => array(
					'default' => __( 'Select currency code from options', 'event-tickets-manager-for-woocommerce' ),
					'INR' => __( 'Rs.', 'event-tickets-manager-for-woocommerce' ),
					'USD' => __( '$', 'event-tickets-manager-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Checkbox Field Demo', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'checkbox',
				'description'  => __( 'This is checkbox field demo follow same structure for further use.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'etmfw_checkbox_demo',
				'value' => '',
				'class' => 'etmfw-checkbox-class',
				'placeholder' => __( 'Checkbox Demo', 'event-tickets-manager-for-woocommerce' ),
			),

			array(
				'title' => __( 'Radio Field Demo', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio',
				'description'  => __( 'This is radio field demo follow same structure for further use.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'etmfw_radio_demo',
				'value' => '',
				'class' => 'etmfw-radio-class',
				'placeholder' => __( 'Radio Demo', 'event-tickets-manager-for-woocommerce' ),
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Enable', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'This is switch field demo follow same structure for further use.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'etmfw_radio_switch_demo',
				'value' => '',
				'class' => 'etmfw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),

			array(
				'type'  => 'button',
				'id'    => 'etmfw_button_demo',
				'button_text' => __( 'Button Demo', 'event-tickets-manager-for-woocommerce' ),
				'class' => 'etmfw-button-class',
			),
		);
		return $etmfw_settings_template;
	}


	/**
	 * Event Tickets Manager for WooCommerce support page tabs.
	 *
	 * @since    1.0.0
	 * @param    Array $mwb_etmfw_support Settings fields.
	 * @return   Array  $mwb_etmfw_support
	 */
	public function etmfw_admin_support_settings_page( $mwb_etmfw_support ) {
		$mwb_etmfw_support = array(
			array(
				'title' => __( 'User Guide', 'event-tickets-manager-for-woocommerce' ),
				'description' => __( 'View the detailed guides and documentation to set up your plugin.', 'event-tickets-manager-for-woocommerce' ),
				'link-text' => __( 'VIEW', 'event-tickets-manager-for-woocommerce' ),
				'link' => '',
			),
			array(
				'title' => __( 'Free Support', 'event-tickets-manager-for-woocommerce' ),
				'description' => __( 'Please submit a ticket , our team will respond within 24 hours.', 'event-tickets-manager-for-woocommerce' ),
				'link-text' => __( 'SUBMIT', 'event-tickets-manager-for-woocommerce' ),
				'link' => '',
			),
		);

		return apply_filters( 'mwb_etmfw_add_support_content', $mwb_etmfw_support );
	}

	/**
	* Event Tickets Manager for WooCommerce save tab settings.
	*
	* @since 1.0.0
	*/
	public function etmfw_admin_save_tab_settings() {
		global $etmfw_mwb_etmfw_obj;
		if ( isset( $_POST['etmfw_button_demo'] ) ) {
			$mwb_etmfw_gen_flag = false;
			$etmfw_genaral_settings = apply_filters( 'etmfw_general_settings_array', array() );
			$etmfw_button_index = array_search( 'submit', array_column( $etmfw_genaral_settings, 'type' ) );
			if ( isset( $etmfw_button_index ) && ( null == $etmfw_button_index || '' == $etmfw_button_index ) ) {
				$etmfw_button_index = array_search( 'button', array_column( $etmfw_genaral_settings, 'type' ) );
			}
			if ( isset( $etmfw_button_index ) && '' !== $etmfw_button_index ) {
				unset( $etmfw_genaral_settings[$etmfw_button_index] );
				if ( is_array( $etmfw_genaral_settings ) && ! empty( $etmfw_genaral_settings ) ) {
					foreach ( $etmfw_genaral_settings as $etmfw_genaral_setting ) {
						if ( isset( $etmfw_genaral_setting['id'] ) && '' !== $etmfw_genaral_setting['id'] ) {
							if ( isset( $_POST[$etmfw_genaral_setting['id']] ) ) {
								update_option( $etmfw_genaral_setting['id'], $_POST[$etmfw_genaral_setting['id']] );
							} else {
								update_option( $etmfw_genaral_setting['id'], '' );
							}
						}else{
							$mwb_etmfw_gen_flag = true;
						}
					}
				}
				if ( $mwb_etmfw_gen_flag ) {
					$mwb_etmfw_error_text = esc_html__( 'Id of some field is missing', 'event-tickets-manager-for-woocommerce' );
					$etmfw_mwb_etmfw_obj->mwb_etmfw_plug_admin_notice( $mwb_etmfw_error_text, 'error' );
				}else{
					$mwb_etmfw_error_text = esc_html__( 'Settings saved !', 'event-tickets-manager-for-woocommerce' );
					$etmfw_mwb_etmfw_obj->mwb_etmfw_plug_admin_notice( $mwb_etmfw_error_text, 'success' );
				}
			}
		}
	}
}
