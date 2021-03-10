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

			wp_enqueue_style( 'thickbox' );
			wp_enqueue_style( 'mwb-etmfw-select2-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/event-tickets-manager-for-woocommerce-select2.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-etmfw-meterial-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-etmfw-meterial-css2', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-etmfw-meterial-lite', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-etmfw-meterial-icons-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );

			wp_enqueue_style( $this->plugin_name . '-admin-global', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/event-tickets-manager-for-woocommerce-admin-global.css', array( 'mwb-etmfw-meterial-icons-css' ), time(), 'all' );

			wp_enqueue_style( $this->plugin_name, EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/event-tickets-manager-for-woocommerce-admin.scss', array(), $this->version, 'all' );
		}
		if ( isset( $screen->id ) && 'product' == $screen->id ){
			// Date Time Picker Library
			wp_enqueue_style( 'mwb-etmfw-date-time-css', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/date-time/datetimepicker.min.css', array(), time(), 'all' );
			wp_enqueue_style( $this->plugin_name . '-admin-edit-product', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/event-tickets-manager-for-woocommerce-admin-edit-product.scss', array(), $this->version, 'all' );
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
			wp_enqueue_script( 'thickbox' );
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
					'etmfw_gen_tab_enable' => get_option( 'mwb_etmfw_radio_switch_demo' ),
				)
			);

			wp_enqueue_script( $this->plugin_name . 'admin-js' );
		}
		if ( isset( $screen->id ) && 'product' == $screen->id ){
			// Date Time Picker Library
			wp_enqueue_script( 'mwb-etmfw-date-time', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/date-time/datetimepicker.min.js', array( 'jquery' ), time(), false );
			wp_register_script( $this->plugin_name . 'admin-edit-product-js', EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/js/event-tickets-manager-for-woocommerce-edit-product.js', array( 'jquery', 'mwb-etmfw-date-time' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name . 'admin-edit-product-js',
				'etmfw_admin_param',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
				)
			);

			wp_enqueue_script( $this->plugin_name . 'admin-edit-product-js' );

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
	 * Event Tickets Manager for WooCommerce general setting tab.
	 *
	 * @since    1.0.0
	 * @param array $etmfw_settings_general Settings fields.
	 */
	public function mwb_etmfw_admin_general_settings_page( $etmfw_settings_general ) {

		$etmfw_settings_general = array(
			array(
				'title' => __( 'Enable plugin', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable plugin to start the functionality.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'mwb_etmfw_enable_plugin',
				'value' => get_option( 'mwb_etmfw_enable_plugin' ),
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
				'id'    => 'mwb_etmfw_enabe_location_site',
				'value' => get_option( 'mwb_etmfw_enabe_location_site' ),
				'class' => 'etmfw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
					'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
				),
			),

			array(
				'type'  => 'button',
				'id'    => 'mwb_etmfw_save_general_settings',
				'button_text' => __( 'Save', 'event-tickets-manager-for-woocommerce' ),
				'class' => 'etmfw-button-class',
			),
		);
	
		return apply_filters( 'mwb_etmfw_extent_general_settings_array', $etmfw_settings_general );
	}

	/**
	 * Event Tickets Manager for WooCommerce  Integration Setting Tab.
	 *
	 * @since    1.0.0
	 * @param array $etmfw_settings_integrations Settings fields.
	 */
	public function mwb_etmfw_admin_integration_settings_page( $etmfw_settings_integrations ) {
		$etmfw_settings_integrations = array(
			array(
				'title' => __( 'Google API Key', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'password',
				'description'  => __( 'To get your API key, visit here <a target="_blank" href="http://www.gmapswidget.com/documentation/generate-google-maps-api-key/">here</a>', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'mwb_etmfw_google_maps_api_key',
				'value' => get_option( 'mwb_etmfw_google_maps_api_key','' ),
				'class' => 'etmfw-password-class',
				'placeholder' => __( 'Google API Key', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title' => __( 'Client ID', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'password',
				'description'  => __( 'To get your Client ID, visit here <a target="_blank" href="https://console.developers.google.com/apis/credentials">here</a>', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'mwb_etmfw_google_client_id',
				'value' => get_option( 'mwb_etmfw_google_client_id','' ),
				'class' => 'etmfw-password-class',
				'placeholder' => __( 'Client ID', 'event-tickets-manager-for-woocommerce' ),
			),
			
			array(
				'title' => __( 'Client Secret Key', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'password',
				'description'  => __( 'To get your Client Secret key, visit here <a target="_blank" href="https://console.developers.google.com/apis/credentials">here</a>', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'mwb_etmfw_google_client_secret',
				'value' => get_option( 'mwb_etmfw_google_client_secret','' ),
				'class' => 'etmfw-password-class',
				'placeholder' => __( 'Client Secret Key', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title' => __( 'Redirect Url', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'text',
				'description'  => __( 'To get your redirect url, visit here <a target="_blank" href="https://console.developers.google.com/apis/credentials">here</a>', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'mwb_etmfw_google_redirect_url',
				'value' => get_option( 'mwb_etmfw_google_redirect_url','' ),
				'class' => 'etmfw-text-class',
				'placeholder' => __( 'Redirect Url', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'type'  => 'button',
				'id'    => 'mwb_etmfw_save_integrations_settings',
				'button_text' => __( 'Save', 'event-tickets-manager-for-woocommerce' ),
				'class' => 'etmfw-button-class',
			),
		);

		return apply_filters( 'mwb_etmfw_extent_integration_settings_array', $etmfw_settings_integrations );
	}

	/**
	 * Event Tickets Manager for WooCommerce Email Template Setting Tab.
	 *
	 * @since    1.0.0
	 * @param array $etmfw_email_template_settings Settings fields.
	 */
	public function mwb_etmfw_admin_email_template_settings_page( $etmfw_email_template_settings ){
		$mwb_etmfw_default_site_logo = get_option('mwb_etmfw_mail_setting_upload_logo', '');
		if( isset( $mwb_etmfw_default_site_logo ) && $mwb_etmfw_default_site_logo === '' ) : 
			if( function_exists( 'the_custom_logo' ) ) {
    			if(has_custom_logo()) {
					$site_logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
					$mwb_etmfw_default_site_logo = $site_logo[0];
				}
			}
		endif;
		$etmfw_email_template_settings = array(
			array(
				'title' => __( 'Event Ticket Email Subject', 'event-tickets-manager-for-woocommerce' ),
				'type'  => 'text',
				'description'  => __( 'Email Subject to notify receiver about the event ticket received. Use [SITENAME] shortcode as the name of the site.', 'event-tickets-manager-for-woocommerce' ),
				'id'    => 'mwb_etmfw_email_subject',
				'value' => get_option('mwb_etmfw_email_subject', ''),
				'class' => 'etmfw-text-class',
				'placeholder' => __( 'Email Subject', 'event-tickets-manager-for-woocommerce' ),
			),
			array(
				'title' =>  __( 'Email Body Content', 'event-tickets-manager-for-woocommerce' ),
				'type' => 'wp_editor',
				'description'  => __( 'Use [SITENAME] shortcode as the name of the site.', 'event-tickets-manager-for-woocommerce' ),
				'id' => 'mwb_etmfw_email_body_content',
				'value' => get_option('mwb_etmfw_email_body_content', ''),
		 	),

		 	array(
			 	'title' => __( 'Upload Default Logo', 'event-tickets-manager-for-woocommerce' ),
			 	'type' => 'textWithButton',
			 	'id' => 'mwb_etmfw_mail_setting_upload_logo',
			 	'custom_attribute' => array(
					array(
						'type' => 'text',
					 	'custom_attributes' => array( 'readonly' => 'readonly' ),
					 	'class' => 'etmfw-text-class',
					 	'id' => 'mwb_etmfw_mail_setting_upload_logo',
					 	'value' => $mwb_etmfw_default_site_logo,
					 	'placeholder' => __( '', 'event-tickets-manager-for-woocommerce' ),
				 	),
				 	array(
					 	'type'  => 'button',
						'id'    => 'mwb_etmfw_mail_setting_upload_logo_button',
						'button_text' => __( 'Upload Logo', 'event-tickets-manager-for-woocommerce' ),
						'class' => 'etmfw-button-class',
				 	),
				 	array(
					 	'type' => 'paragraph',
					 	'id' => 'mwb_etmfw_mail_setting_remove_logo',
					 	'imgId' => 'mwb_etmfw_mail_setting_upload_image',
					 	'spanX' => 'mwb_etmfw_mail_setting_remove_logo_span',
				 	),
			 	),
			 	'class' => 'mwb_etmfw_mail_setting_upload_logo_box',
			 	'description' => __( 'Upload the image which is used as logo on your Email Template.', 'event-tickets-manager-for-woocommerce' ),
		 	),

			array(
				'type'  => 'button',
				'id'    => 'mwb_etmfw_save_email_template_settings',
				'button_text' => __( 'Save', 'event-tickets-manager-for-woocommerce' ),
				'class' => 'etmfw-button-class',
			),
		);

		return apply_filters( 'mwb_etmfw_extent_email_template_settings_array', $etmfw_email_template_settings );
	}

	/**
	 * Event Tickets Manager for WooCommerce support page tabs.
	 *
	 * @since    1.0.0
	 * @param    Array $mwb_etmfw_support Settings fields.
	 * @return   Array  $mwb_etmfw_support
	 */
	public function mwb_etmfw_admin_support_settings_page( $mwb_etmfw_support ) {
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
	public function mwb_etmfw_admin_save_tab_settings() {
		global $etmfw_mwb_etmfw_obj;
		if ( isset( $_POST['mwb_etmfw_save_general_settings'] ) && isset( $_POST['mwb-etmfw-general-nonce-field'] ) ) {
			$mwb_etmfw_general_nonce = sanitize_text_field( wp_unslash( $_POST['mwb-etmfw-general-nonce-field'] ) );
			if ( wp_verify_nonce( $mwb_etmfw_general_nonce, 'mwb-etmfw-general-nonce' ) ) {
				$mwb_etmfw_gen_flag = false;
				$etmfw_genaral_settings = apply_filters( 'mwb_etmfw_general_settings_array', array() );
				$etmfw_button_index = array_search( 'submit', array_column( $etmfw_genaral_settings, 'type' ) );
				if ( isset( $etmfw_button_index ) && ( null == $etmfw_button_index || '' == $etmfw_button_index ) ) {
					$etmfw_button_index = array_search( 'button', array_column( $etmfw_genaral_settings, 'type' ) );
				}
				if ( isset( $etmfw_button_index ) && '' !== $etmfw_button_index ) {
					unset( $etmfw_genaral_settings[$etmfw_button_index] );
					if ( is_array( $etmfw_genaral_settings ) && ! empty( $etmfw_genaral_settings ) ) {
						foreach ( $etmfw_genaral_settings as $etmfw_genaral_setting ) {
							if ( isset( $etmfw_genaral_setting['id'] ) && '' !== $etmfw_genaral_setting['id'] ) {
								if ( isset( $_POST[$etmfw_genaral_setting['id']] ) && ! empty( $_POST[ $etmfw_genaral_setting['id'] ] ) ) {
									$posted_value = sanitize_text_field( wp_unslash( $_POST[ $etmfw_genaral_setting['id'] ] ) );
									update_option( $etmfw_genaral_setting['id'], $posted_value );
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
		if ( isset( $_POST['mwb_etmfw_save_integrations_settings'] ) && isset( $_POST['mwb-etmfw-integration-nonce-field'] ) ) {
			$mwb_etmfw_integration_nonce = sanitize_text_field( wp_unslash( $_POST['mwb-etmfw-integration-nonce-field'] ) );
			if ( wp_verify_nonce( $mwb_etmfw_integration_nonce, 'mwb-etmfw-integration-nonce' ) ) {
				$mwb_etmfw_integration_flag = false;
				$etmfw_integration_settings = apply_filters( 'mwb_etmfw_integration_settings_array', array() );
				$etmfw_button_index = array_search( 'submit', array_column( $etmfw_integration_settings, 'type' ) );
				if ( isset( $etmfw_button_index ) && ( null == $etmfw_button_index || '' == $etmfw_button_index ) ) {
					$etmfw_button_index = array_search( 'button', array_column( $etmfw_integration_settings, 'type' ) );
				}
				if ( isset( $etmfw_button_index ) && '' !== $etmfw_button_index ) {
					unset( $etmfw_integration_settings[$etmfw_button_index] );
					if ( is_array( $etmfw_integration_settings ) && ! empty( $etmfw_integration_settings ) ) {
						foreach ( $etmfw_integration_settings as $etmfw_integration_setting ) {
							if ( isset( $etmfw_integration_setting['id'] ) && '' !== $etmfw_integration_setting['id'] ) {
								if ( isset( $_POST[$etmfw_integration_setting['id']] ) && ! empty( $_POST[ $etmfw_integration_setting['id'] ] ) ) {
									$posted_value = sanitize_text_field( wp_unslash( $_POST[ $etmfw_integration_setting['id'] ] ) );
									update_option( $etmfw_integration_setting['id'], $posted_value );
								} else {
									update_option( $etmfw_integration_setting['id'], '' );
								}
							}else{
								$mwb_etmfw_integration_flag = true;
							}
						}
					}
					if ( $mwb_etmfw_integration_flag ) {
						$mwb_etmfw_error_text = esc_html__( 'Id of some field is missing', 'event-tickets-manager-for-woocommerce' );
						$etmfw_mwb_etmfw_obj->mwb_etmfw_plug_admin_notice( $mwb_etmfw_error_text, 'error' );
					}else{
						$mwb_etmfw_error_text = esc_html__( 'Settings saved !', 'event-tickets-manager-for-woocommerce' );
						$etmfw_mwb_etmfw_obj->mwb_etmfw_plug_admin_notice( $mwb_etmfw_error_text, 'success' );
					}
				}
			}
		}
		if ( isset( $_POST['mwb_etmfw_save_email_template_settings'] ) && isset( $_POST['mwb-etmfw-email-template-nonce-field'] ) ) {
			$mwb_etmfw_email_template_nonce = sanitize_text_field( wp_unslash( $_POST['mwb-etmfw-email-template-nonce-field'] ) );
			if ( wp_verify_nonce( $mwb_etmfw_email_template_nonce, 'mwb-etmfw-email-template-nonce' ) ) {
				$mwb_etmfw_email_template_flag = false;
				$etmfw_email_template_settings = apply_filters( 'mwb_etmfw_email_template_settings_array', array() );
				$etmfw_button_index = array_search( 'submit', array_column( $etmfw_email_template_settings, 'type' ) );
				if ( isset( $etmfw_button_index ) && ( null == $etmfw_button_index || '' == $etmfw_button_index ) ) {
					$etmfw_button_index = array_search( 'button', array_column( $etmfw_email_template_settings, 'type' ) );
				}
				if ( isset( $etmfw_button_index ) && '' !== $etmfw_button_index ) {
					unset( $etmfw_email_template_settings[$etmfw_button_index] );
					if ( is_array( $etmfw_email_template_settings ) && ! empty( $etmfw_email_template_settings ) ) {
						foreach ( $etmfw_email_template_settings as $etmfw_email_template_setting ) {
							if ( isset( $etmfw_email_template_setting['id'] ) && '' !== $etmfw_email_template_setting['id'] ) {
								if ( isset( $_POST[$etmfw_email_template_setting['id']] ) && ! empty( $_POST[ $etmfw_email_template_setting['id'] ] ) ) {
									$posted_value = map_deep( wp_unslash( $_POST[ $etmfw_email_template_setting['id'] ] ), 'sanitize_text_field' );
									update_option( $etmfw_email_template_setting['id'], $posted_value );
								} else {
									update_option( $etmfw_email_template_setting['id'], '' );
								}
							}else{
								$mwb_etmfw_email_template_flag = true;
							}
						}
					}
					if ( $mwb_etmfw_email_template_flag ) {
						$mwb_etmfw_error_text = esc_html__( 'Id of some field is missing', 'event-tickets-manager-for-woocommerce' );
						$etmfw_mwb_etmfw_obj->mwb_etmfw_plug_admin_notice( $mwb_etmfw_error_text, 'error' );
					}else{
						$mwb_etmfw_error_text = esc_html__( 'Settings saved !', 'event-tickets-manager-for-woocommerce' );
						$etmfw_mwb_etmfw_obj->mwb_etmfw_plug_admin_notice( $mwb_etmfw_error_text, 'success' );
					}
				}
			}
		}
		do_action( 'mwb_etmfw_save_admin_global_settings', $_POST );
	}

	/**
	 * Create a custom Product Type for Event Ticket Manager
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_event_ticket_product()
	 * @param array $types product types.
	 * @return $types.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_event_ticket_product( $types ) {
		$mwb_etmfw_enable = get_option( 'mwb_etmfw_enable_plugin', false );
		if ( $mwb_etmfw_enable ) {
			$types['event_ticket_manager'] = __( 'Events', 'event-tickets-manager-for-woocommerce' );
		}
		return $types;
	}

	/**
	 * Create a tab for Event Ticket Manager
	 *
	 * @since 1.0.0
	 * @name mwb_etmfw_event_ticket_tab()
	 * @param array $tabs event tab.
	 * @return $tabs.
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_event_ticket_tab( $tabs ) {
		if ( isset( $tabs ) && ! empty( $tabs ) ) {
			foreach ( $tabs as $key => $tab ) {
				if ( 'general' != $key && 'inventory' != $key ) {
					$tabs[ $key ]['class'][] = 'hide_if_event_ticket_manager';
				}
			}
		}
		
		$tabs['event_ticket'] = array(
	        'label'   =>  __( 'Events', 'event-tickets-manager-for-woocommerce' ),
	        'target'  =>  'mwb_etmfw_event_data',
	        'priority' => 60,
	        'class'   => array( 'show_if_event_ticket_manager')
	    );
	    return apply_filters( 'mwb_etmfw_product_data_tabs', $tabs );
	}

	public function mwb_etmfw_event_tab_content(){
		global $post;
		$product_id = $post->ID;
		if ( isset( $product_id ) ) {
			if ( ! current_user_can( 'edit_post', $product_id ) ) {
				return;
			}
		}
		$typeselected = '';
		$mwb_etmfw_product_array = get_post_meta( $product_id, 'mwb_etmfw_product_array', true );
		$mwb_etmfw_field_data = isset( $mwb_etmfw_product_array['mwb_etmfw_field_data'] ) && !empty( $mwb_etmfw_product_array['mwb_etmfw_field_data'] ) ? $mwb_etmfw_product_array['mwb_etmfw_field_data'] : array();

	    ?>
	    <div id="mwb_etmfw_event_data" class="panel woocommerce_options_panel">
	        <?php
	        woocommerce_wp_text_input( array( 
	            'id'            => 'etmfw_start_date_time', 
	            'wrapper_class' => 'show_if_event_ticket_manager', 
	            'label'         => __( 'Start Date/ Time', 'event-tickets-manager-for-woocommerce' ),
	            'desc_tip'      => false,
	            'value'			=> isset( $mwb_etmfw_product_array['event_start_date_time'] ) ? $mwb_etmfw_product_array['event_start_date_time'] : '' ,
	        ) );
	        
	        woocommerce_wp_text_input( array( 
	            'id'            => 'etmfw_end_date_time', 
	            'wrapper_class' => 'show_if_event_ticket_manager', 
	            'label'         => __( 'End Date/ Time', 'event-tickets-manager-for-woocommerce' ),
	            'desc_tip'      => false,
	            'value'			=> isset( $mwb_etmfw_product_array['event_end_date_time'] ) ? $mwb_etmfw_product_array['event_end_date_time'] : '',
	        ) );

	        woocommerce_wp_text_input( array( 
	            'id'            => 'etmfw_event_venue', 
	            'wrapper_class' => 'show_if_event_ticket_manager', 
	            'label'         => __( 'Venue', 'event-tickets-manager-for-woocommerce' ),
	            'desc_tip'      => false,
	            'value' 		=> isset( $mwb_etmfw_product_array['etmfw_event_venue'] ) ? $mwb_etmfw_product_array['etmfw_event_venue'] : '',
	        ) );

	        woocommerce_wp_checkbox(
				array(
					'id' => 'etmfw_display_map',
					'wrapper_class' => 'show_if_event_ticket_manager', 
					'label' => __( 'Display event venue on google map on product page', 'giftware' ),
					'value' => isset( $mwb_etmfw_product_array['etmfw_display_map'] ) ? $mwb_etmfw_product_array['etmfw_display_map'] : true,
				)
			);

	        do_action( 'mwb_etmfw_edit_product_settings');
	        ?>
	        <div id="mwb_etmfw_add_fields_wrapper">
				<div class="mwb_etmfw_add_fields_title">
					<h2>
						<strong class="attribute_name"><?php _e( 'Add custom fields on the tickets for this event', 'event-tickets-manager-for-woocommerce' );?></strong></h2>
				</div>
				<div class="mwb_etmfw_add_fields_data">
					<div class="mwb_etmfw_fields_panel">
						<table class="field-options wp-list-table widefat mwb_etmfw_field_table">
							<thead>
							<tr>
								<th></th>
								<th class="etmfw_field_label"><?php _e( 'Label', 'event-tickets-manager-for-woocommerce' );?></th>
								<th class="etmfw_field_type"><?php _e( 'Type', 'event-tickets-manager-for-woocommerce' );?></th>
								<th class="etmfw_field_required"><?php _e( 'Required', 'event-tickets-manager-for-woocommerce' );?></th>
								<th class="etmfw_field_actions"><?php _e( 'Actions', 'event-tickets-manager-for-woocommerce' );?></th>
							</tr>
							</thead>
							<tbody class="ui-sortable mwb_etmfw_field_body" style="">
								<?php if( empty($mwb_etmfw_field_data)) :  ?>
									<tr class="mwb_etmfw_field_wrap" data-id="0">
										<td class="drag-icon">
											<i class="dashicons dashicons-move"></i>
										</td>
										<td class="form-field mwb_etmfw_label_fields">
											<input type="text" class="mwb_etmfw_field_label" style="" name="etmfw_fields[0][_label]" id="label_fields_0" value="" placeholder="">
										</td>
										<td class="form-field mwb_etmfw_type_fields">
											<select id="type_fields_0" name="etmfw_fields[0][_type]" class="mwb_etmfw_field_type">
												<?php $mwb_etmfw_field_array = $this->mwb_etmfw_event_fields();
												foreach ($mwb_etmfw_field_array as $key => $value) : ?>
													<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value );?></option>
												<?php endforeach;?> 
											</select>
										</td>
										<td class="form-field mwb_etmfw_required_fields">
											<input type="checkbox" class="checkbox" style="" name="etmfw_fields[0][_required]" id="required_fields_0">
										</td>
										<td class="mwb_etmfw_remove_row">
											<input type="button" name="mwb_etmfw_remove_fields_button" class="mwb_etmfw_remove_row_btn" value="Remove">
										</td>
									</tr>
								<?php else : 
									foreach ($mwb_etmfw_field_data as $row_id => $row_value) :
										if ( isset( $row_value['required'] ) && ( 'on' == $row_value['required'] ) ) {
											$mwb_etmfw_required = 1;
										} else {
											$mwb_etmfw_required = 0;
										}
									 	?>
										<tr class="mwb_etmfw_field_wrap" data-id="<?php echo esc_attr( $row_id );?>">
											<td class="drag-icon">
												<i class="dashicons dashicons-move"></i>
											</td>
											<td class="form-field mwb_etmfw_label_fields">
												<input type="text" class="mwb_etmfw_field_label" style="" name="etmfw_fields[<?php echo esc_attr( $row_id );?>][_label]" id="label_fields_<?php echo esc_attr( $row_id );?>" value="<?php echo esc_attr( $row_value['label'] );?>" placeholder="">
											</td>
											<td class="form-field mwb_etmfw_type_fields">
												<select id="type_fields_<?php echo esc_attr( $row_id );?>" name="etmfw_fields[<?php echo esc_attr( $row_id );?>][_type]" class="mwb_etmfw_field_type">
													<?php $mwb_etmfw_field_array = $this->mwb_etmfw_event_fields();
													foreach ($mwb_etmfw_field_array as $key => $value) : ?>
														<?php if( $key === $row_value['type'] ) : 
															$typeselected = "selected='selected'";
															?>
															<option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $typeselected ); ?>><?php echo esc_attr( $value ); ?></option>
														<?php endif; ?>
													<?php endforeach;?> 
												</select>
											</td>
											<td class="form-field mwb_etmfw_required_fields">
												<input type="checkbox" class="checkbox" style="" name="etmfw_fields[<?php echo esc_attr( $row_id );?>][_required]" id="required_fields_<?php echo esc_attr( $row_id );?>" <?php checked( $mwb_etmfw_required, 1 ); ?>>
											</td>
											<td class="mwb_etmfw_remove_row">
												<input type="button" name="mwb_etmfw_remove_fields_button" class="mwb_etmfw_remove_row_btn" value="Remove">
											</td>
										</tr>
									<?php endforeach;?>
								<?php endif;?>				
							</tbody>
							<tfoot>
							<tr>
								<td colspan="5">
									<input type="button" name="mwb_etmfw_add_fields_button" class="button mwb_etmfw_add_fields_button" value="<?php _e( 'Add More', 'event-tickets-manager-for-woocommerce' );?>">
								</td>
							</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
	    </div>
	    <?php
	    wp_nonce_field( 'mwb_etmfw_lite_nonce', 'mwb_etmfw_product_nonce_field' );
	    do_action( 'mwb_etmfw_event_type_field', $product_id );
	}

	public function mwb_etmfw_save_product_data(){
		global $post;
		if ( isset( $post->ID ) ) {
			if ( ! current_user_can( 'edit_post', $post->ID ) ) {
				return;
			}
			$product_id = $post->ID;
			$product = wc_get_product( $product_id );
			if ( isset( $product ) && is_object( $product ) ) {
				if ( $product->get_type() == 'event_ticket_manager' ) {
					if ( isset( $_POST['mwb_etmfw_product_nonce_field'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mwb_etmfw_product_nonce_field'] ) ), 'mwb_etmfw_lite_nonce' ) ) {
						return;
					}
					$mwb_etmfw_product_array = array(); 
					$mwb_etmfw_product_array['event_start_date_time'] = isset( $_POST['etmfw_start_date_time'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_start_date_time'] ) ) : '';
					$mwb_etmfw_product_array['event_end_date_time'] = isset( $_POST['etmfw_end_date_time'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_end_date_time'] ) ) : '';
					$event_venue = isset( $_POST['etmfw_event_venue'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_event_venue'] ) ) : '';
					$event_coordinates = $this->mwb_etmfw_get_coordinates( $event_venue );
					$mwb_etmfw_product_array['etmfw_event_venue'] = $event_venue;
					$mwb_etmfw_product_array['etmfw_event_venue_coordinates'] = $event_coordinates;
					$mwb_etmfw_field_data = map_deep( wp_unslash( $_POST['etmfw_fields'] ), 'sanitize_text_field' );
					$mwb_etmfw_field_data_array = array();
					if( is_array( $mwb_etmfw_field_data ) && !empty( $mwb_etmfw_field_data ) ) {
						if( ! $mwb_etmfw_field_data[0]['_label'] == '' ) {
							foreach ($mwb_etmfw_field_data as $key => $value) {
								$mwb_etmfw_field_data_array[] = array(
									'label' => $value['_label'],
				                    'type' => $value['_type'],
				                    'required' => isset( $value['_required'] ) ? $value['_required'] : 'off',
								);
							}
						}
					}
					$mwb_etmfw_product_array['mwb_etmfw_field_data'] = $mwb_etmfw_field_data_array;
					$etmfw_display_map = isset( $_POST['etmfw_display_map'] ) ? sanitize_text_field( wp_unslash( $_POST['etmfw_display_map'] ) ) : 'no';
					$mwb_etmfw_product_array['etmfw_display_map'] = $etmfw_display_map;
					do_action( 'mwb_etmfw_product_pricing', $mwb_etmfw_product_array );
					$mwb_etmfw_product_array = apply_filters( 'mwb_etmfw_product_pricing', $mwb_etmfw_product_array );
					update_post_meta( $product_id, 'mwb_etmfw_product_array', $mwb_etmfw_product_array );
					do_action( 'mwb_etmfw_event_product_type_save_fields', $product_id );
				}
			}
		}
	}

	public function mwb_etmfw_event_fields(){
		$field_array = array(
			'text' 		=> __('Text', 'event-tickets-manager-for-woocommerce' ), 
			'textarea'  => __('Textarea', 'event-tickets-manager-for-woocommerce' ), 
			'email' 	=> __('Email', 'event-tickets-manager-for-woocommerce' ), 
			'number' 	=> __('Number', 'event-tickets-manager-for-woocommerce' ), 
			'date' 		=> __('Date', 'event-tickets-manager-for-woocommerce' ), 
			'yes-no' 	=> __('Yes/No', 'event-tickets-manager-for-woocommerce' ), 
		);
		return apply_filters( 'mwb_etmfw_extend_event_fields', $field_array );
	}

	public function mwb_etmfw_get_coordinates( $event_venue ){
		$event_coordinates = '';
		$api_key = 'AIzaSyCW_52b_zBroMrtqV849iQYidYpt1QGqNw';
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$event_venue.'&key='.$api_key;
		return $event_coordinates;
	}

	/**
	 * Add a submenu inside the Woocommerce Menu Page
	 * @since 1.0.0
	 * @name mwb_etmfw_event_menu()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_etmfw_event_menu(){
		add_submenu_page( "woocommerce", __("Events", 'event-tickets-manager-for-woocommerce' ), __("Events", 'event-tickets-manager-for-woocommerce' ), "manage_options", "mwb-etmfw-events-info", array($this, "mwb_etmfw_display_event_info"));
		//hooks to add sub menu 
		do_action('mwb_etmfw_admin_sub_menu');
	}

	public function mwb_etmfw_display_event_info(){
		require_once EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/class-event-tickets-manager-for-woocommerce-events-info.php';
		?>
		<div class="mwb_etmfw_event_display_wrapper">
			<h1><?php echo esc_html( 'Events', 'event-tickets-manager-for-woocommerce' ); ?></h1>
			<form method="post">
				<?php
				$current_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
				?>
				<input type="hidden" name="page" value="<?php echo esc_attr( $current_page ); ?>">
				<?php
				wp_nonce_field( 'mwb-etmfw-events', 'mwb-etmfw-events' );
				$mylisttable = new Event_Tickets_Manager_For_Woocommerce_Events_Info();
				$mylisttable->prepare_items();
				$mylisttable->display();
				?>
			</form>
		</div>
		<?php
	}
}