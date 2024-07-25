<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://wpswings.com/
 * @since 1.0.0
 *
 * @package    Subscriptions_For_Woocommerce
 * @subpackage Subscriptions_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly.
}
global $etmfw_wps_etmfw_obj;
$etmf_default_tabs = $etmfw_wps_etmfw_obj->wps_etmfw_plug_default_tabs();
$etmfw_tab_key = '';
?>
<header>
	<?php
	// desc - This hook is used for trial.
	do_action( 'wps_etmfw_settings_saved_notice' );
	?>
	<div class="wps-header-container wps-bg-white wps-r-8">
		<h1 class="wps-header-title"><?php echo esc_attr( 'WP Swings' ); ?></h1>
	</div>
</header>
<main class="wps-main wps-bg-white wps-r-8">
	<section class="wps-section">
		<div>
			<?php
				// desc - This hook is used for trial.
			do_action( 'wps_etmfw_before_common_settings_form' );
				// if submenu is directly clicked on woocommerce.
			$emfw_genaral_settings = apply_filters(
				'etmfw_home_settings_array',
				array(
					array(
						'title' => __( 'Enable Tracking', 'subscriptions-for-woocommerce' ),
						'type'  => 'radio-switch',
						'id'    => 'wps_etmfw_enable_tracking',
						'value' => get_option( 'wps_etmfw_enable_tracking' ),
						'class' => 'etmfw-radio-switch-class',
						'options' => array(
							'yes' => __( 'YES', 'event-tickets-manager-for-woocommerce' ),
							'no' => __( 'NO', 'event-tickets-manager-for-woocommerce' ),
						),
					),
					array(
						'type'  => 'button',
						'id'    => 'etmfw_track_button',
						'button_text' => __( 'Save', 'event-tickets-manager-for-woocommerce' ),
						'class' => 'etmfw-button-class',
					),
				)
			);
			?>
			<form action="" method="POST" class="wps-etfmfw-gen-section-form">
				<div class="etmfw-secion-wrap">
					<?php
					$sfw_general_html = $etmfw_wps_etmfw_obj->wps_etmfw_plug_generate_html( $emfw_genaral_settings );
					echo esc_html( $sfw_general_html );
					wp_nonce_field( 'wps-sfw-general-nonce', 'wps-sfw-general-nonce-field' );
					?>
				</div>
			</form>
			<?php
			do_action( 'wps_etmfw_before_common_settings_form' );
			$all_plugins = get_plugins();
			?>
		</div>
	</section>
	<style type="text/css">
		.cards {
			   display: flex;
			   flex-wrap: wrap;
			   padding: 20px 40px;
		}
		.card {
			flex: 1 0 518px;
			box-sizing: border-box;
			margin: 1rem 3.25em;
			text-align: center;
		}

	</style>
	<div class="centered">
		<section class="cards">
			<?php foreach ( get_plugins() as $key => $value ) : ?>
				<?php if ( 'WP Swings' === $value['Author'] ) : ?>
					<article class="card">
						<div class="container">
							<h4><b><?php echo esc_html( $value['Name'] ); ?></b></h4> 
							<p><?php echo esc_html( $value['Version'] ); ?></p> 
							<p><?php echo wp_kses_post( $value['Description'] ); ?></p>
						</div>
					</article>
				<?php endif; ?>
			<?php endforeach; ?>
		</section>
	</div>
