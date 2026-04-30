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
			'growth_card' => array(),
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

		self::render_growth_consultation_card( $args['growth_card'] );

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
		self::render_growth_consultation_modal();
	}

	/**
	 * Render the growth consultation CTA card.
	 *
	 * @param array $args CTA card arguments.
	 * @return void
	 */
	private static function render_growth_consultation_card( $args = array() ) {
		$defaults = array(
			'eyebrow'      => '',
			'title'        => '',
			'description'  => '',
			'button_label' => __( 'Talk to an Expert', 'event-tickets-manager-for-woocommerce' ),
			'footer_label' => __( 'Services by WP Swings', 'event-tickets-manager-for-woocommerce' ),
			'services'     => array(
				array(
					'icon'        => 'search',
					'tone'        => 'gold',
					'title'       => __( 'SEO Services', 'event-tickets-manager-for-woocommerce' ),
					'description' => __( 'Improve rankings & organic traffic', 'event-tickets-manager-for-woocommerce' ),
				),
				array(
					'icon'        => 'chart-line',
					'tone'        => 'pink',
					'title'       => __( 'Google Ads Setup And G4 Setup', 'event-tickets-manager-for-woocommerce' ),
					'description' => __( 'Run profitable ad campaigns', 'event-tickets-manager-for-woocommerce' ),
				),
				array(
					'icon'        => 'dashboard',
					'tone'        => 'lavender',
					'title'       => __( 'Speed Optimization', 'event-tickets-manager-for-woocommerce' ),
					'description' => __( 'Faster store, happier customers', 'event-tickets-manager-for-woocommerce' ),
				),
				array(
					'icon'        => 'admin-tools',
					'tone'        => 'violet',
					'title'       => __( 'WooCommerce Development Services', 'event-tickets-manager-for-woocommerce' ),
					'description' => __( 'Custom Solution For your store needs', 'event-tickets-manager-for-woocommerce' ),
				),
			),
		);
		$args     = wp_parse_args( $args, $defaults );

		if ( empty( $args['title'] ) && empty( $args['description'] ) ) {
			return;
		}

		echo '<section class="wps-etmfw-ui-card wps-etmfw-ui-sidebar-card wps-etmfw-ui-sidebar-card--growth">';
		echo '<div class="wps-etmfw-growth-card__header">';
		echo '<div class="wps-etmfw-growth-card__title">';
		echo '<h3>' . esc_html( $args['title'] ) . '</h3>';
		if ( $args['description'] ) {
			echo '<p>' . esc_html( $args['description'] ) . '</p>';
		}
		echo '</div>';
		echo '<span class="wps-etmfw-growth-card__badge" aria-hidden="true"><span class="dashicons dashicons-star-filled"></span></span>';
		echo '</div>';

		if ( ! empty( $args['services'] ) && is_array( $args['services'] ) ) {
			echo '<div class="wps-etmfw-growth-card__services">';
			foreach ( $args['services'] as $service ) {
				$icon_class = ! empty( $service['icon'] ) ? sanitize_html_class( $service['icon'] ) : 'star-filled';
				$tone_class = ! empty( $service['tone'] ) ? sanitize_html_class( $service['tone'] ) : 'gold';
				echo '<div class="wps-etmfw-growth-card__service">';
				echo '<span class="wps-etmfw-growth-card__service-icon wps-etmfw-growth-card__service-icon--' . esc_attr( $tone_class ) . '" aria-hidden="true"><span class="dashicons dashicons-' . esc_attr( $icon_class ) . '"></span></span>';
				echo '<span class="wps-etmfw-growth-card__service-copy">';
				echo '<strong>' . esc_html( $service['title'] ) . '</strong>';
				echo '<small>' . esc_html( $service['description'] ) . '</small>';
				echo '</span>';
				echo '<span class="wps-etmfw-growth-card__service-arrow" aria-hidden="true">&rsaquo;</span>';
				echo '</div>';
			}
			echo '</div>';
		}

		echo '<button type="button" class="wps-etmfw-ui-button wps-etmfw-ui-button--primary wps-etmfw-growth-trigger" data-wps-etmfw-growth-open="true">' . esc_html( $args['button_label'] ) . '</button>';
		echo '<div class="wps-etmfw-growth-card__footer"><span>' . esc_html( $args['footer_label'] ) . '</span><span class="dashicons dashicons-shield-alt" aria-hidden="true"></span></div>';
		echo '</section>';
	}

	/**
	 * Render the growth consultation modal shell.
	 *
	 * @return void
	 */
	private static function render_growth_consultation_modal() {
		$current_user = wp_get_current_user();
		$first_name   = '';
		$last_name    = '';
		$email        = '';

		if ( $current_user instanceof WP_User && $current_user->exists() ) {
			$first_name = (string) $current_user->user_firstname;
			$last_name  = (string) $current_user->user_lastname;
			$email      = (string) $current_user->user_email;

			if ( '' === $first_name && '' === $last_name && '' !== $current_user->display_name ) {
				$name_parts = preg_split( '/\s+/', trim( (string) $current_user->display_name ) );
				$first_name = isset( $name_parts[0] ) ? $name_parts[0] : '';
				$last_name  = isset( $name_parts[1] ) ? $name_parts[1] : '';
			}
		}
		?>
		<div class="wps-etmfw-growth-modal" data-wps-etmfw-growth-modal="true" aria-hidden="true" hidden>
			<div class="wps-etmfw-growth-modal__backdrop" data-wps-etmfw-growth-close="true"></div>
			<div class="wps-etmfw-growth-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="wps-etmfw-growth-modal-title">
				<div class="wps-etmfw-growth-modal__header">
					<div class="wps-etmfw-growth-modal__heading">
						<h2 id="wps-etmfw-growth-modal-title"><?php esc_html_e( 'Talk to an Expert', 'event-tickets-manager-for-woocommerce' ); ?></h2>
						<p><?php esc_html_e( 'Share your store goals and our team will reach out with the right next step.', 'event-tickets-manager-for-woocommerce' ); ?></p>
					</div>
					<button type="button" class="wps-etmfw-growth-modal__close" data-wps-etmfw-growth-close="true" aria-label="<?php esc_attr_e( 'Close growth consultation form', 'event-tickets-manager-for-woocommerce' ); ?>">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="wps-etmfw-growth-modal__body">
					<div class="wps-etmfw-growth-panel wps-etmfw-growth-panel--form" data-wps-etmfw-growth-form-panel="true">
						<form class="wps-etmfw-growth-form" data-wps-etmfw-growth-form="true">
							<div class="wps-etmfw-growth-form__surface">
								<div class="wps-etmfw-growth-form__grid">
									<div class="wps-etmfw-growth-field">
										<label for="wps-etmfw-growth-firstname"><?php esc_html_e( 'First Name', 'event-tickets-manager-for-woocommerce' ); ?></label>
										<input type="text" id="wps-etmfw-growth-firstname" name="firstname" value="<?php echo esc_attr( $first_name ); ?>" placeholder="<?php esc_attr_e( 'John', 'event-tickets-manager-for-woocommerce' ); ?>">
									</div>
									<div class="wps-etmfw-growth-field">
										<label for="wps-etmfw-growth-lastname"><?php esc_html_e( 'Last Name', 'event-tickets-manager-for-woocommerce' ); ?></label>
										<input type="text" id="wps-etmfw-growth-lastname" name="lastname" value="<?php echo esc_attr( $last_name ); ?>" placeholder="<?php esc_attr_e( 'Doe', 'event-tickets-manager-for-woocommerce' ); ?>">
									</div>
								</div>

								<div class="wps-etmfw-growth-field wps-etmfw-growth-field--full">
									<label for="wps-etmfw-growth-email"><?php esc_html_e( 'Work Email', 'event-tickets-manager-for-woocommerce' ); ?> <span class="wps-etmfw-growth-field__required">*</span></label>
									<input type="email" id="wps-etmfw-growth-email" name="email" value="<?php echo esc_attr( $email ); ?>" placeholder="<?php esc_attr_e( 'name@company.com', 'event-tickets-manager-for-woocommerce' ); ?>" required>
								</div>

								<div class="wps-etmfw-growth-field wps-etmfw-growth-field--half">
									<label for="wps-etmfw-growth-phone"><?php esc_html_e( 'Contact Number', 'event-tickets-manager-for-woocommerce' ); ?></label>
									<input type="text" id="wps-etmfw-growth-phone" name="phone" value="" placeholder="<?php esc_attr_e( '+1 000 000 0000', 'event-tickets-manager-for-woocommerce' ); ?>">
								</div>

								<div class="wps-etmfw-growth-field wps-etmfw-growth-field--full">
									<fieldset class="wps-etmfw-growth-services">
										<legend><?php esc_html_e( 'What services do you need help with?', 'event-tickets-manager-for-woocommerce' ); ?></legend>
										<div class="wps-etmfw-growth-services__grid">
											<label class="wps-etmfw-growth-services__option"><input type="checkbox" name="what_services_do_you_need_help_with[]" value="seo_services"> <span><?php esc_html_e( 'SEO services', 'event-tickets-manager-for-woocommerce' ); ?></span></label>
											<label class="wps-etmfw-growth-services__option"><input type="checkbox" name="what_services_do_you_need_help_with[]" value="google_ads_setup_and_ga4_setup"> <span><?php esc_html_e( 'Google Ads Setup and GA4 setup', 'event-tickets-manager-for-woocommerce' ); ?></span></label>
											<label class="wps-etmfw-growth-services__option"><input type="checkbox" name="what_services_do_you_need_help_with[]" value="speed_optimization"> <span><?php esc_html_e( 'Speed Optimization', 'event-tickets-manager-for-woocommerce' ); ?></span></label>
											<label class="wps-etmfw-growth-services__option"><input type="checkbox" name="what_services_do_you_need_help_with[]" value="woocommerce_development_services"> <span><?php esc_html_e( 'WooCommerce Development Services', 'event-tickets-manager-for-woocommerce' ); ?></span></label>
										</div>
									</fieldset>
								</div>

								<div class="wps-etmfw-growth-field wps-etmfw-growth-field--full">
									<label for="wps-etmfw-growth-budget"><?php esc_html_e( 'Budget', 'event-tickets-manager-for-woocommerce' ); ?></label>
									<select id="wps-etmfw-growth-budget" name="budget">
										<option value=""><?php esc_html_e( 'Please Select', 'event-tickets-manager-for-woocommerce' ); ?></option>
										<option value="500-1000"><?php esc_html_e( '$500 - $1000', 'event-tickets-manager-for-woocommerce' ); ?></option>
										<option value="1001-5000"><?php esc_html_e( '$1001 - $5000', 'event-tickets-manager-for-woocommerce' ); ?></option>
										<option value="5001-10000"><?php esc_html_e( '$5001 - $10000', 'event-tickets-manager-for-woocommerce' ); ?></option>
										<option value="10001-15000"><?php esc_html_e( '$10001 - $15000', 'event-tickets-manager-for-woocommerce' ); ?></option>
									</select>
								</div>

								<div class="wps-etmfw-growth-field wps-etmfw-growth-field--full">
									<label for="wps-etmfw-growth-message"><?php esc_html_e( 'What do you need help with?', 'event-tickets-manager-for-woocommerce' ); ?></label>
									<textarea id="wps-etmfw-growth-message" name="message" rows="5" placeholder="<?php esc_attr_e( 'Share your goals, blockers, or the service you need.', 'event-tickets-manager-for-woocommerce' ); ?>"></textarea>
								</div>

								<div class="wps-etmfw-growth-form__status" data-wps-etmfw-growth-status="true" hidden></div>

								<div class="wps-etmfw-growth-form__actions">
									<button type="submit" class="wps-etmfw-ui-button wps-etmfw-ui-button--primary" data-wps-etmfw-growth-submit="true" data-default-label="<?php esc_attr_e( 'Submit Request', 'event-tickets-manager-for-woocommerce' ); ?>" data-loading-label="<?php esc_attr_e( 'Sending...', 'event-tickets-manager-for-woocommerce' ); ?>">
										<?php esc_html_e( 'Submit Request', 'event-tickets-manager-for-woocommerce' ); ?>
									</button>
								</div>
							</div>
						</form>
					</div>

					<div class="wps-etmfw-growth-panel wps-etmfw-growth-panel--thankyou" data-wps-etmfw-growth-thankyou-panel="true" hidden>
						<div class="wps-etmfw-growth-thankyou" aria-live="polite">
							<div class="wps-etmfw-growth-thankyou__icon" aria-hidden="true">
								<span class="dashicons dashicons-yes-alt"></span>
							</div>
							<span class="wps-etmfw-growth-thankyou__eyebrow"><?php esc_html_e( 'Talk to an Expert', 'event-tickets-manager-for-woocommerce' ); ?></span>
							<h3><?php esc_html_e( 'Thank You', 'event-tickets-manager-for-woocommerce' ); ?></h3>
							<p class="wps-etmfw-growth-thankyou__message" data-wps-etmfw-growth-thankyou-message="true"><?php esc_html_e( 'Thank you for submitting your request.', 'event-tickets-manager-for-woocommerce' ); ?></p>
							<p class="wps-etmfw-growth-thankyou__helper"><?php esc_html_e( 'Our team will review the details and contact you with the right next step. Redirecting you back to the dashboard in a moment.', 'event-tickets-manager-for-woocommerce' ); ?></p>
							<button type="button" class="wps-etmfw-ui-button wps-etmfw-ui-button--secondary" data-wps-etmfw-growth-close="true"><?php esc_html_e( 'Done', 'event-tickets-manager-for-woocommerce' ); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
