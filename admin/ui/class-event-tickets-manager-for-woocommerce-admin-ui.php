<?php
/**
 * Reusable admin UI config and helpers.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reusable admin UI config and helpers.
 */
class Event_Tickets_Manager_For_Woocommerce_Admin_UI {

	/**
	 * Get page header config.
	 *
	 * @return array
	 */
	public static function get_header_config() {
		$is_pro_active = self::is_pro_active();
		$active_tab    = isset( $_GET['etmfw_tab'] ) ? sanitize_key( wp_unslash( $_GET['etmfw_tab'] ) ) : 'event-tickets-manager-for-woocommerce-general'; // phpcs:ignore WordPress.Security.NonceVerification
		$expert_url    = admin_url( 'admin.php?page=event_tickets_manager_for_woocommerce_menu&etmfw_tab=' . rawurlencode( $active_tab ) . '&etmfw_open=talk-to-expert' );

		return array(
			'badge'   => $is_pro_active ? __( 'PRO ACTIVE', 'event-tickets-manager-for-woocommerce' ) : __( 'FREE ACTIVE', 'event-tickets-manager-for-woocommerce' ),
			'title'   => __( 'Event Tickets Manager for WooCommerce', 'event-tickets-manager-for-woocommerce' ),
			'subtitle' => $is_pro_active ? __( 'Pro admin workspace', 'event-tickets-manager-for-woocommerce' ) : __( 'Core admin workspace', 'event-tickets-manager-for-woocommerce' ),
			'actions' => array(
				array(
					'label'  => __( 'Talk to an Expert', 'event-tickets-manager-for-woocommerce' ),
					'url'    => $expert_url,
					'class'  => 'wps-etmfw-ui-button--primary',
					'target' => '_self',
					'rel'    => '',
				),
			),
		);
	}

	/**
	 * Get sidebar config.
	 *
	 * @return array
	 */
	public static function get_sidebar_config() {
		return array(
			'growth_card' => array(
				'eyebrow'      => __( 'Growth Services', 'event-tickets-manager-for-woocommerce' ),
				'title'        => __( 'Grow Your Store with WP Swings', 'event-tickets-manager-for-woocommerce' ),
				'description'  => __( 'Expert solutions to boost your store\'s performance.', 'event-tickets-manager-for-woocommerce' ),
				'button_label' => __( 'Talk to an Expert', 'event-tickets-manager-for-woocommerce' ),
			),
			'help_links' => array(
				array(
					'label' => __( 'Watch Video', 'event-tickets-manager-for-woocommerce' ),
					'url'   => self::is_pro_active() ? 'https://www.youtube.com/watch?v=kSlD1p1SQEA&t=3s' : 'https://www.youtube.com/embed/9KyB4qpal6M',
				),
				array(
					'label' => __( 'Documentation', 'event-tickets-manager-for-woocommerce' ),
					'url'   => 'https://docs.wpswings.com/event-tickets-manager-for-woocommerce/?utm_source=wpswings-events-doc&utm_medium=events-admin&utm_campaign=documentation',
				),
				array(
					'label' => __( 'Support', 'event-tickets-manager-for-woocommerce' ),
					'url'   => 'https://wpswings.com/submit-query/?utm_source=wpswings-events-support&utm_medium=events-admin&utm_campaign=support',
				),
			),
			'support_url' => 'https://wpswings.com/submit-query/?utm_source=wpswings-events-support&utm_medium=events-admin&utm_campaign=support',
			'support_label' => __( 'Contact Us', 'event-tickets-manager-for-woocommerce' ),
			'explore_label' => __( 'View More Plugins', 'event-tickets-manager-for-woocommerce' ),
		);
	}

	/**
	 * Get tab context config.
	 *
	 * @param string $tab_key Tab key.
	 * @return array
	 */
	public static function get_tab_context( $tab_key ) {
		$config = array(
				'event-tickets-manager-for-woocommerce-overview' => array(
					'eyebrow'           => __( 'Overview', 'event-tickets-manager-for-woocommerce' ),
					'title'             => __( 'Overview', 'event-tickets-manager-for-woocommerce' ),
					'description'       => __( 'Product summary, feature highlights, and onboarding guidance.', 'event-tickets-manager-for-woocommerce' ),
					'documentation_url' => 'https://docs.wpswings.com/event-tickets-manager-for-woocommerce/',
				),
				'event-tickets-manager-for-woocommerce-general' => array(
					'eyebrow'           => __( 'Settings', 'event-tickets-manager-for-woocommerce' ),
					'title'             => __( 'General', 'event-tickets-manager-for-woocommerce' ),
					'description'       => __( 'Configure the core ticketing behavior, product controls, and essential storefront options.', 'event-tickets-manager-for-woocommerce' ),
					'documentation_url' => 'https://docs.wpswings.com/event-tickets-manager-for-woocommerce/',
				),
				'event-tickets-manager-for-woocommerce-email-template' => array(
					'eyebrow'           => __( 'Settings', 'event-tickets-manager-for-woocommerce' ),
					'title'             => __( 'Ticket Settings', 'event-tickets-manager-for-woocommerce' ),
					'description'       => __( 'Manage ticket content, branding, and PDF/email presentation settings.', 'event-tickets-manager-for-woocommerce' ),
					'documentation_url' => 'https://docs.wpswings.com/event-tickets-manager-for-woocommerce/',
				),
				'event-tickets-manager-for-woocommerce-ticket-layout-setting' => array(
					'eyebrow'           => __( 'Settings', 'event-tickets-manager-for-woocommerce' ),
					'title'             => __( 'PDF Ticket Layout', 'event-tickets-manager-for-woocommerce' ),
					'description'       => __( 'Adjust template selection and ticket appearance settings.', 'event-tickets-manager-for-woocommerce' ),
					'documentation_url' => 'https://docs.wpswings.com/event-tickets-manager-for-woocommerce/',
				),
				'event-tickets-manager-for-woocommerce-other-settings' => array(
					'eyebrow'           => __( 'Settings', 'event-tickets-manager-for-woocommerce' ),
					'title'             => __( 'Other Settings', 'event-tickets-manager-for-woocommerce' ),
					'description'       => __( 'Control advanced notifications, feedback flows, and secondary behaviors.', 'event-tickets-manager-for-woocommerce' ),
					'documentation_url' => 'https://docs.wpswings.com/event-tickets-manager-for-woocommerce/',
				),
				'event-tickets-manager-for-woocommerce-dashboard-settings' => array(
					'eyebrow'           => __( 'Settings', 'event-tickets-manager-for-woocommerce' ),
					'title'             => __( 'Dashboard Settings', 'event-tickets-manager-for-woocommerce' ),
					'description'       => __( 'Tune the event dashboard view and reporting defaults.', 'event-tickets-manager-for-woocommerce' ),
					'documentation_url' => 'https://docs.wpswings.com/event-tickets-manager-for-woocommerce/',
				),
				'event-tickets-manager-for-woocommerce-integrations' => array(
					'eyebrow'           => __( 'Settings', 'event-tickets-manager-for-woocommerce' ),
					'title'             => __( 'Integrations', 'event-tickets-manager-for-woocommerce' ),
					'description'       => __( 'Connect maps, messaging, social channels, and external automation tools.', 'event-tickets-manager-for-woocommerce' ),
					'documentation_url' => 'https://docs.wpswings.com/event-tickets-manager-for-woocommerce/',
				),
				'event-tickets-manager-for-woocommerce-system-status' => array(
					'eyebrow'           => __( 'Diagnostics', 'event-tickets-manager-for-woocommerce' ),
					'title'             => __( 'System Status', 'event-tickets-manager-for-woocommerce' ),
					'description'       => __( 'Review WordPress and server state for support and diagnostics.', 'event-tickets-manager-for-woocommerce' ),
					'documentation_url' => 'https://docs.wpswings.com/event-tickets-manager-for-woocommerce/',
				),
				'event-tickets-manager-for-woocommerce-pro-license' => array(
					'eyebrow'           => __( 'Settings', 'event-tickets-manager-for-woocommerce' ),
					'title'             => __( 'License', 'event-tickets-manager-for-woocommerce' ),
					'description'       => __( 'Activate and manage the pro license from the same design system.', 'event-tickets-manager-for-woocommerce' ),
					'documentation_url' => 'https://docs.wpswings.com/event-tickets-manager-for-woocommerce/',
			),
		);

		$config = apply_filters( 'wps_etmfw_admin_ui_tab_context', $config );

		return isset( $config[ $tab_key ] ) ? $config[ $tab_key ] : array(
			'eyebrow'           => __( 'Settings', 'event-tickets-manager-for-woocommerce' ),
			'title'             => __( 'Settings', 'event-tickets-manager-for-woocommerce' ),
			'description'       => __( 'Manage the selected module settings.', 'event-tickets-manager-for-woocommerce' ),
			'documentation_url' => 'https://docs.wpswings.com/event-tickets-manager-for-woocommerce/',
		);
	}

	/**
	 * Check whether the pro plugin is active.
	 *
	 * @return bool
	 */
	public static function is_pro_active() {
		return is_plugin_active( 'event-tickets-manager-for-woocommerce-pro/event-tickets-manager-for-woocommerce-pro.php' );
	}
}
