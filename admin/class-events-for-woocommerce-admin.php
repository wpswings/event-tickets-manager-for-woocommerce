<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Events_For_Woocommerce
 * @subpackage Events_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Events_For_Woocommerce
 * @subpackage Events_For_Woocommerce/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Events_For_Woocommerce_Admin {

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
	public function efw_admin_enqueue_styles( $hook ) {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_events_for_woocommerce_menu' == $screen->id ) {

			wp_enqueue_style( 'mwb-efw-select2-css', EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/events-for-woocommerce-select2.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-efw-meterial-css', EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-efw-meterial-css2', EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-efw-meterial-lite', EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-efw-meterial-icons-css', EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );

			wp_enqueue_style( $this->plugin_name . '-admin-global', EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/events-for-woocommerce-admin-global.css', array( 'mwb-efw-meterial-icons-css' ), time(), 'all' );

			wp_enqueue_style( $this->plugin_name, EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/scss/events-for-woocommerce-admin.scss', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function efw_admin_enqueue_scripts( $hook ) {

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_events_for_woocommerce_menu' == $screen->id ) {
			wp_enqueue_script( 'mwb-efw-select2', EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/select-2/events-for-woocommerce-select2.js', array( 'jquery' ), time(), false );

			wp_enqueue_script( 'mwb-efw-metarial-js', EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-efw-metarial-js2', EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-efw-metarial-lite', EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );

			wp_register_script( $this->plugin_name . 'admin-js', EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/js/events-for-woocommerce-admin.js', array( 'jquery', 'mwb-efw-select2', 'mwb-efw-metarial-js', 'mwb-efw-metarial-js2', 'mwb-efw-metarial-lite' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'efw_admin_param',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'reloadurl' => admin_url( 'admin.php?page=events_for_woocommerce_menu' ),
					'efw_gen_tab_enable' => get_option( 'efw_radio_switch_demo' ),
				)
			);

			wp_enqueue_script( $this->plugin_name . 'admin-js' );
		}
	}

	/**
	 * Adding settings menu for events for woocommerce.
	 *
	 * @since    1.0.0
	 */
	public function efw_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( __( 'MakeWebBetter', 'events-for-woocommerce' ), __( 'MakeWebBetter', 'events-for-woocommerce' ), 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), EVENTS_FOR_WOOCOMMERCE_DIR_URL . 'admin/src/images/mwb-logo.png', 15 );
			$efw_menus = apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $efw_menus ) && ! empty( $efw_menus ) ) {
				foreach ( $efw_menus as $efw_key => $efw_value ) {
					add_submenu_page( 'mwb-plugins', $efw_value['name'], $efw_value['name'], 'manage_options', $efw_value['menu_link'], array( $efw_value['instance'], $efw_value['function'] ) );
				}
			}
		}
	}

	/**
	 * Removing default submenu of parent menu in backend dashboard
	 *
	 * @since   1.0.0
	 */
	public function mwb_efw_remove_default_submenu() {
		global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'mwb-plugins', $submenu ) ) {
			if ( isset( $submenu['mwb-plugins'][0] ) ) {
				unset( $submenu['mwb-plugins'][0] );
			}
		}
	}


	/**
	 * events for woocommerce efw_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function efw_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'            => __( 'events for woocommerce', 'events-for-woocommerce' ),
			'slug'            => 'events_for_woocommerce_menu',
			'menu_link'       => 'events_for_woocommerce_menu',
			'instance'        => $this,
			'function'        => 'efw_options_menu_html',
		);
		return $menus;
	}


	/**
	 * events for woocommerce mwb_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function mwb_plugins_listing_page() {
		$active_marketplaces = apply_filters( 'mwb_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require EVENTS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * events for woocommerce admin menu page.
	 *
	 * @since    1.0.0
	 */
	public function efw_options_menu_html() {

		include_once EVENTS_FOR_WOOCOMMERCE_DIR_PATH . 'admin/partials/events-for-woocommerce-admin-dashboard.php';
	}


	/**
	 * events for woocommerce admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $efw_settings_general Settings fields.
	 */
	public function efw_admin_general_settings_page( $efw_settings_general ) {

		$efw_settings_general = array(
			array(
				'title' => __( 'Enable plugin', 'events-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'Enable plugin to start the functionality.', 'events-for-woocommerce' ),
				'id'    => 'efw_radio_switch_demo',
				'value' => get_option( 'efw_radio_switch_demo' ),
				'class' => 'efw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'events-for-woocommerce' ),
					'no' => __( 'NO', 'events-for-woocommerce' ),
				),
			),

			array(
				'type'  => 'button',
				'id'    => 'efw_button_demo',
				'button_text' => __( 'Button Demo', 'events-for-woocommerce' ),
				'class' => 'efw-button-class',
			),
		);
		// print_r($efw_settings_general);die;
		return $efw_settings_general;
	}

	/**
	 * events for woocommerce admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $efw_settings_template Settings fields.
	 */
	public function efw_admin_template_settings_page( $efw_settings_template ) {
		$efw_settings_template = array(
			array(
				'title' => __( 'Text Field Demo', 'events-for-woocommerce' ),
				'type'  => 'text',
				'description'  => __( 'This is text field demo follow same structure for further use.', 'events-for-woocommerce' ),
				'id'    => 'efw_text_demo',
				'value' => '',
				'class' => 'efw-text-class',
				'placeholder' => __( 'Text Demo', 'events-for-woocommerce' ),
			),
			array(
				'title' => __( 'Number Field Demo', 'events-for-woocommerce' ),
				'type'  => 'number',
				'description'  => __( 'This is number field demo follow same structure for further use.', 'events-for-woocommerce' ),
				'id'    => 'efw_number_demo',
				'value' => '',
				'class' => 'efw-number-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Password Field Demo', 'events-for-woocommerce' ),
				'type'  => 'password',
				'description'  => __( 'This is password field demo follow same structure for further use.', 'events-for-woocommerce' ),
				'id'    => 'efw_password_demo',
				'value' => '',
				'class' => 'efw-password-class',
				'placeholder' => '',
			),
			array(
				'title' => __( 'Textarea Field Demo', 'events-for-woocommerce' ),
				'type'  => 'textarea',
				'description'  => __( 'This is textarea field demo follow same structure for further use.', 'events-for-woocommerce' ),
				'id'    => 'efw_textarea_demo',
				'value' => '',
				'class' => 'efw-textarea-class',
				'rows' => '5',
				'cols' => '10',
				'placeholder' => __( 'Textarea Demo', 'events-for-woocommerce' ),
			),
			array(
				'title' => __( 'Select Field Demo', 'events-for-woocommerce' ),
				'type'  => 'select',
				'description'  => __( 'This is select field demo follow same structure for further use.', 'events-for-woocommerce' ),
				'id'    => 'efw_select_demo',
				'value' => '',
				'class' => 'efw-select-class',
				'placeholder' => __( 'Select Demo', 'events-for-woocommerce' ),
				'options' => array(
					'' => __( 'Select option', 'events-for-woocommerce' ),
					'INR' => __( 'Rs.', 'events-for-woocommerce' ),
					'USD' => __( '$', 'events-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Multiselect Field Demo', 'events-for-woocommerce' ),
				'type'  => 'multiselect',
				'description'  => __( 'This is multiselect field demo follow same structure for further use.', 'events-for-woocommerce' ),
				'id'    => 'efw_multiselect_demo',
				'value' => '',
				'class' => 'efw-multiselect-class mwb-defaut-multiselect',
				'placeholder' => '',
				'options' => array(
					'default' => __( 'Select currency code from options', 'events-for-woocommerce' ),
					'INR' => __( 'Rs.', 'events-for-woocommerce' ),
					'USD' => __( '$', 'events-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Checkbox Field Demo', 'events-for-woocommerce' ),
				'type'  => 'checkbox',
				'description'  => __( 'This is checkbox field demo follow same structure for further use.', 'events-for-woocommerce' ),
				'id'    => 'efw_checkbox_demo',
				'value' => '',
				'class' => 'efw-checkbox-class',
				'placeholder' => __( 'Checkbox Demo', 'events-for-woocommerce' ),
			),

			array(
				'title' => __( 'Radio Field Demo', 'events-for-woocommerce' ),
				'type'  => 'radio',
				'description'  => __( 'This is radio field demo follow same structure for further use.', 'events-for-woocommerce' ),
				'id'    => 'efw_radio_demo',
				'value' => '',
				'class' => 'efw-radio-class',
				'placeholder' => __( 'Radio Demo', 'events-for-woocommerce' ),
				'options' => array(
					'yes' => __( 'YES', 'events-for-woocommerce' ),
					'no' => __( 'NO', 'events-for-woocommerce' ),
				),
			),
			array(
				'title' => __( 'Enable', 'events-for-woocommerce' ),
				'type'  => 'radio-switch',
				'description'  => __( 'This is switch field demo follow same structure for further use.', 'events-for-woocommerce' ),
				'id'    => 'efw_radio_switch_demo',
				'value' => '',
				'class' => 'efw-radio-switch-class',
				'options' => array(
					'yes' => __( 'YES', 'events-for-woocommerce' ),
					'no' => __( 'NO', 'events-for-woocommerce' ),
				),
			),

			array(
				'type'  => 'button',
				'id'    => 'efw_button_demo',
				'button_text' => __( 'Button Demo', 'events-for-woocommerce' ),
				'class' => 'efw-button-class',
			),
		);
		return $efw_settings_template;
	}


	/**
	 * events for woocommerce support page tabs.
	 *
	 * @since    1.0.0
	 * @param    Array $mwb_efw_support Settings fields.
	 * @return   Array  $mwb_efw_support
	 */
	public function efw_admin_support_settings_page( $mwb_efw_support ) {
		$mwb_efw_support = array(
			array(
				'title' => __( 'User Guide', 'events-for-woocommerce' ),
				'description' => __( 'View the detailed guides and documentation to set up your plugin.', 'events-for-woocommerce' ),
				'link-text' => __( 'VIEW', 'events-for-woocommerce' ),
				'link' => '',
			),
			array(
				'title' => __( 'Free Support', 'events-for-woocommerce' ),
				'description' => __( 'Please submit a ticket , our team will respond within 24 hours.', 'events-for-woocommerce' ),
				'link-text' => __( 'SUBMIT', 'events-for-woocommerce' ),
				'link' => '',
			),
		);

		return apply_filters( 'mwb_efw_add_support_content', $mwb_efw_support );
	}

	/**
	* events for woocommerce save tab settings.
	*
	* @since 1.0.0
	*/
	public function efw_admin_save_tab_settings() {
		global $efw_mwb_efw_obj;
		if ( isset( $_POST['efw_button_demo'] ) ) {
			$mwb_efw_gen_flag = false;
			$efw_genaral_settings = apply_filters( 'efw_general_settings_array', array() );
			$efw_button_index = array_search( 'submit', array_column( $efw_genaral_settings, 'type' ) );
			if ( isset( $efw_button_index ) && ( null == $efw_button_index || '' == $efw_button_index ) ) {
				$efw_button_index = array_search( 'button', array_column( $efw_genaral_settings, 'type' ) );
			}
			if ( isset( $efw_button_index ) && '' !== $efw_button_index ) {
				unset( $efw_genaral_settings[$efw_button_index] );
				if ( is_array( $efw_genaral_settings ) && ! empty( $efw_genaral_settings ) ) {
					foreach ( $efw_genaral_settings as $efw_genaral_setting ) {
						if ( isset( $efw_genaral_setting['id'] ) && '' !== $efw_genaral_setting['id'] ) {
							if ( isset( $_POST[$efw_genaral_setting['id']] ) ) {
								update_option( $efw_genaral_setting['id'], $_POST[$efw_genaral_setting['id']] );
							} else {
								update_option( $efw_genaral_setting['id'], '' );
							}
						}else{
							$mwb_efw_gen_flag = true;
						}
					}
				}
				if ( $mwb_efw_gen_flag ) {
					$mwb_efw_error_text = esc_html__( 'Id of some field is missing', 'events-for-woocommerce' );
					$efw_mwb_efw_obj->mwb_efw_plug_admin_notice( $mwb_efw_error_text, 'error' );
				}else{
					$mwb_efw_error_text = esc_html__( 'Settings saved !', 'events-for-woocommerce' );
					$efw_mwb_efw_obj->mwb_efw_plug_admin_notice( $mwb_efw_error_text, 'success' );
				}
			}
		}
	}
}
