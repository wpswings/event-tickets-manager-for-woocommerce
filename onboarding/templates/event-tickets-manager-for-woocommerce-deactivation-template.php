<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package     Event_Tickets_Manager_For_Woocommerce
 * @subpackage  Event_Tickets_Manager_For_Woocommerce/onboarding/templates
 */

global $pagenow, $etmfw_mwb_etmfw_obj;
if ( empty( $pagenow ) || 'plugins.php' != $pagenow ) {
	return false;
}

$etmfw_onboarding_form_deactivate = apply_filters( 'mwb_etmfw_deactivation_form_fields', array() );
?>
<?php if ( ! empty( $etmfw_onboarding_form_deactivate ) ) : ?>
	<div class="mdc-dialog mdc-dialog--scrollable mwb-etmfw-on-boarding-dialog">
		<div class="mwb-etmfw-on-boarding-wrapper-background mdc-dialog__container">
			<div class="mwb-etmfw-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="mwb-etmfw-on-boarding-close-btn">
						<a href="#">
							<span class="etmfw-close-form material-icons mwb-etmfw-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span>
						</a>
					</div>

					<h3 class="mwb-etmfw-on-boarding-heading mdc-dialog__title"></h3>
					<p class="mwb-etmfw-on-boarding-desc"><?php esc_html_e( 'May we have a little info about why you are deactivating?', 'event-tickets-manager-for-woocommerce' ); ?></p>
					<form action="#" method="post" class="mwb-etmfw-on-boarding-form">
						<?php
						$etmfw_onboarding_deactive_html = $etmfw_mwb_etmfw_obj->mwb_etmfw_plug_generate_html( $etmfw_onboarding_form_deactivate );
						echo esc_html( $etmfw_onboarding_deactive_html );
						?>
						<div class="mwb-etmfw-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="mwb-etmfw-on-boarding-form-submit mwb-etmfw-on-boarding-form-verify ">
								<input type="submit" class="mwb-etmfw-on-boarding-submit mwb-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="mwb-etmfw-on-boarding-form-no_thanks">
								<a href="#" class="mwb-etmfw-deactivation-no_thanks mdc-button"><?php esc_html_e( 'Skip and Deactivate Now', 'event-tickets-manager-for-woocommerce' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
