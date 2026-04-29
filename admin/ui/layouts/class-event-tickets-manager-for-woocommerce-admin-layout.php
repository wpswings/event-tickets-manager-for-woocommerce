<?php
/**
 * Reusable admin UI layout helpers.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reusable admin UI layout helpers.
 */
class Event_Tickets_Manager_For_Woocommerce_Admin_Layout {

	/**
	 * Render the shared intro card used above each selected tab.
	 *
	 * @param array $args Intro card arguments.
	 * @return void
	 */
	private static function render_intro_card( $args = array() ) {
		$defaults = array(
			'eyebrow'             => '',
			'title'               => '',
			'description'         => '',
			'documentation_label' => __( 'Read Documentation', 'event-tickets-manager-for-woocommerce' ),
			'documentation_url'   => '',
		);
		$args = wp_parse_args( $args, $defaults );

		if ( empty( $args['eyebrow'] ) && empty( $args['title'] ) && empty( $args['description'] ) && empty( $args['documentation_url'] ) ) {
			return;
		}

		echo '<section class="wps-etmfw-ui-card wps-etmfw-ui-intro-card">';
		echo '<div class="wps-etmfw-ui-card__header">';
		echo '<div class="wps-etmfw-ui-card__heading">';
		if ( $args['eyebrow'] ) {
			echo '<span class="wps-etmfw-ui-card__eyebrow">' . esc_html( $args['eyebrow'] ) . '</span>';
		}
		if ( $args['title'] ) {
			echo '<h2>' . esc_html( $args['title'] ) . '</h2>';
		}
		if ( $args['description'] ) {
			echo '<p>' . esc_html( $args['description'] ) . '</p>';
		}
		echo '</div>';

		if ( $args['documentation_url'] ) {
			echo '<a class="wps-etmfw-ui-button wps-etmfw-ui-button--primary" href="' . esc_url( $args['documentation_url'] ) . '" target="_blank" rel="noreferrer noopener">' . esc_html( $args['documentation_label'] ) . '</a>';
		}
		echo '</div>';
		echo '</section>';
	}

	/**
	 * Render the top header bar.
	 *
	 * @param array $args Header arguments.
	 * @return void
	 */
	public static function render_header( $args = array() ) {
		$defaults = array(
			'badge'   => __( 'CORE', 'event-tickets-manager-for-woocommerce' ),
			'title'   => __( 'Plugin Dashboard', 'event-tickets-manager-for-woocommerce' ),
			'subtitle' => '',
			'actions' => array(),
		);
		$args     = wp_parse_args( $args, $defaults );

		echo '<section class="wps-etmfw-ui-hero">';
		echo '<div class="wps-etmfw-ui-hero__content">';
			echo '<span class="wps-etmfw-ui-badge">' . esc_html( $args['badge'] ) . '</span>';
			echo '<div class="wps-etmfw-ui-hero__heading">';
			echo '<h1>' . esc_html( $args['title'] ) . '</h1>';
			if ( ! empty( $args['subtitle'] ) ) {
				echo '<p>' . esc_html( $args['subtitle'] ) . '</p>';
			}
			echo '</div>';
		echo '</div>';

		if ( ! empty( $args['actions'] ) ) {
			echo '<div class="wps-etmfw-ui-hero__actions">';
			foreach ( $args['actions'] as $action ) {
				if ( empty( $action['url'] ) || empty( $action['label'] ) ) {
					continue;
				}

				$class = ! empty( $action['class'] ) ? $action['class'] : 'wps-etmfw-ui-button--ghost';
				echo '<a class="wps-etmfw-ui-button ' . esc_attr( $class ) . '" href="' . esc_url( $action['url'] ) . '" target="_blank" rel="noreferrer noopener">' . esc_html( $action['label'] ) . '</a>';
			}
			echo '</div>';
		}

		echo '</section>';
	}

	/**
	 * Render the tab navigation.
	 *
	 * @param array  $tabs Tabs.
	 * @param string $active_tab Active tab key.
	 * @param string $page_slug Page slug.
	 * @return void
	 */
	public static function render_tabs( $tabs, $active_tab, $page_slug, $meta_label = '' ) {
		echo '<nav class="wps-etmfw-ui-tabs" aria-label="' . esc_attr__( 'Plugin settings tabs', 'event-tickets-manager-for-woocommerce' ) . '">';
		echo '<div class="wps-etmfw-ui-tabs__track">';

		if ( $meta_label ) {
			echo '<span class="wps-etmfw-ui-tabs__meta">' . esc_html( $meta_label ) . '</span>';
		}

		foreach ( $tabs as $tab_key => $tab ) {
			$classes = array( 'wps-etmfw-ui-tab' );
			if ( $active_tab === $tab_key ) {
				$classes[] = 'is-active';
			}

			$url = admin_url( 'admin.php?page=' . $page_slug . '&etmfw_tab=' . $tab_key );
			echo '<a class="' . esc_attr( implode( ' ', $classes ) ) . '" data-tab-key="' . esc_attr( $tab_key ) . '" href="' . esc_url( $url ) . '">' . esc_html( $tab['title'] ) . '</a>';
		}

		echo '</div>';
		echo '</nav>';
	}

	/**
	 * Render the page grid wrapper open tag.
	 *
	 * @return void
	 */
	public static function open_page_grid() {
		echo '<div class="wps-etmfw-ui-page-grid"><div class="wps-etmfw-ui-main-column">';
	}

	/**
	 * Render the page grid wrapper close tag and sidebar.
	 *
	 * @param array $sidebar_args Sidebar configuration.
	 * @return void
	 */
	public static function close_page_grid( $sidebar_args = array() ) {
		echo '</div>';
		self::render_sidebar( $sidebar_args );
		echo '</div>';
	}

	/**
	 * Render a settings card.
	 *
	 * @param array $args Card arguments.
	 * @return void
	 */
	public static function render_settings_card( $args = array() ) {
		$defaults = array(
			'eyebrow'             => '',
			'title'               => '',
			'description'         => '',
			'documentation_label' => __( 'Read Documentation', 'event-tickets-manager-for-woocommerce' ),
			'documentation_url'   => '',
			'form_class'          => '',
			'fields'              => array(),
			'nonce_name'          => '',
			'nonce_action'        => '',
			'extra_hidden_fields' => array(),
		);
		$args     = wp_parse_args( $args, $defaults );

		self::render_intro_card( $args );

		echo '<section class="wps-etmfw-ui-card wps-etmfw-ui-card--section">';
		echo '<form action="" method="post" class="wps-etmfw-ui-form ' . esc_attr( $args['form_class'] ) . '">';
		if ( $args['nonce_name'] && $args['nonce_action'] ) {
			printf(
				'<input type="hidden" name="%1$s" value="%2$s" />',
				esc_attr( $args['nonce_name'] ),
				esc_attr( wp_create_nonce( $args['nonce_action'] ) )
			);
		}

		if ( ! empty( $args['extra_hidden_fields'] ) && is_array( $args['extra_hidden_fields'] ) ) {
			foreach ( $args['extra_hidden_fields'] as $hidden_name => $hidden_value ) {
				printf(
					'<input type="hidden" name="%1$s" value="%2$s" />',
					esc_attr( $hidden_name ),
					esc_attr( $hidden_value )
				);
			}
		}

		echo '<div class="wps-etmfw-ui-form__fields">';
		Event_Tickets_Manager_For_Woocommerce_UI_Components::render_fields( $args['fields'] );
		echo '</div>';
		echo '</form>';
		echo '</section>';
	}

	/**
	 * Render a generic content card wrapper.
	 *
	 * @param array  $args Content arguments.
	 * @param string $content Inner content html.
	 * @return void
	 */
	public static function render_content_card( $args, $content ) {
		$defaults = array(
			'eyebrow'           => '',
			'title'             => '',
			'description'       => '',
			'documentation_url' => '',
			'documentation_label' => __( 'Read Documentation', 'event-tickets-manager-for-woocommerce' ),
			'card_class'        => '',
			'content_class'     => '',
			'show_header'       => true,
		);
		$args     = wp_parse_args( $args, $defaults );
		if ( $args['show_header'] ) {
			self::render_intro_card( $args );
		}

		$card_class = trim( 'wps-etmfw-ui-card wps-etmfw-ui-card--section ' . $args['card_class'] );
		$content_class = trim( 'wps-etmfw-ui-card__content ' . $args['content_class'] );

		echo '<section class="' . esc_attr( $card_class ) . '">';
		echo '<div class="' . esc_attr( $content_class ) . '">' . $content . '</div>';
		echo '</section>';
	}

	/**
	 * Render the shared sidebar.
	 *
	 * @param array $args Sidebar config.
	 * @return void
	 */
	public static function render_sidebar( $args = array() ) {
		$defaults = array(
			'help_links' => array(),
			'support_url' => '',
			'support_label' => __( 'Contact Support', 'event-tickets-manager-for-woocommerce' ),
			'explore_url' => 'https://wpswings.com/plugins/?utm_source=wpswings-events&utm_medium=events-admin&utm_campaign=explore-more',
			'explore_label' => __( 'Browse Plugins', 'event-tickets-manager-for-woocommerce' ),
		);
		$args     = wp_parse_args( $args, $defaults );

		echo '<aside class="wps-etmfw-ui-sidebar">';
		echo '<section class="wps-etmfw-ui-card wps-etmfw-ui-sidebar-card">';
		echo '<div class="wps-etmfw-ui-sidebar-card__header">';
		echo '<h3>' . esc_html__( 'Need help with this plugin?', 'event-tickets-manager-for-woocommerce' ) . '</h3>';
		echo '<p>' . esc_html__( 'Guides, documentation, and support links in one place.', 'event-tickets-manager-for-woocommerce' ) . '</p>';
		echo '</div>';
		echo '<div class="wps-etmfw-ui-resource-list">';
		foreach ( $args['help_links'] as $link ) {
			if ( empty( $link['url'] ) || empty( $link['label'] ) ) {
				continue;
			}
			echo '<a class="wps-etmfw-ui-resource" href="' . esc_url( $link['url'] ) . '" target="_blank" rel="noreferrer noopener"><span>' . esc_html( $link['label'] ) . '</span><strong>' . esc_html__( 'Open', 'event-tickets-manager-for-woocommerce' ) . '</strong></a>';
		}
		echo '</div>';
		echo '</section>';

		echo '<section class="wps-etmfw-ui-card wps-etmfw-ui-sidebar-card wps-etmfw-ui-sidebar-card--accent">';
		echo '<div class="wps-etmfw-ui-sidebar-card__header">';
		echo '<h3>' . esc_html__( 'Still facing problems?', 'event-tickets-manager-for-woocommerce' ) . '</h3>';
		echo '<p>' . esc_html__( 'Reach the product team with the exact context behind your issue.', 'event-tickets-manager-for-woocommerce' ) . '</p>';
		echo '</div>';
		if ( $args['support_url'] ) {
			echo '<a class="wps-etmfw-ui-button wps-etmfw-ui-button--primary" href="' . esc_url( $args['support_url'] ) . '" target="_blank" rel="noreferrer noopener">' . esc_html( $args['support_label'] ) . '</a>';
		}
		echo '</section>';

		echo '<section class="wps-etmfw-ui-card wps-etmfw-ui-sidebar-card">';
		echo '<div class="wps-etmfw-ui-sidebar-card__header">';
		echo '<h3>' . esc_html__( 'Explore more plugins', 'event-tickets-manager-for-woocommerce' ) . '</h3>';
		echo '<p>' . esc_html__( 'This design system is meant to scale across the rest of your WooCommerce plugins.', 'event-tickets-manager-for-woocommerce' ) . '</p>';
		echo '</div>';
		echo '<a class="wps-etmfw-ui-button wps-etmfw-ui-button--secondary" href="' . esc_url( $args['explore_url'] ) . '" target="_blank" rel="noreferrer noopener">' . esc_html( $args['explore_label'] ) . '</a>';
		echo '</section>';
		echo '</aside>';
	}
}
