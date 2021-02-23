<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Makewebbetter_Onboarding
 * @subpackage Makewebbetter_Onboarding/admin/onboarding
 */
global $efw_mwb_efw_obj;
$efw_onboarding_form_fields = apply_filters( 'mwb_efw_on_boarding_form_fields', array() );
?>

<?php if ( ! empty( $efw_onboarding_form_fields ) ) : ?>
	<div class="mdc-dialog mdc-dialog--scrollable">
		<div class="mwb-efw-on-boarding-wrapper-background mdc-dialog__container">
			<div class="mwb-efw-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="mwb-efw-on-boarding-close-btn">
						<a href="#"><span class="efw-close-form material-icons mwb-efw-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span></a>
					</div>

					<h3 class="mwb-efw-on-boarding-heading mdc-dialog__title"><?php esc_html_e( 'Welcome to MakeWebBetter', 'events-for-woocommerce' ); ?> </h3>
					<p class="mwb-efw-on-boarding-desc"><?php esc_html_e( 'We love making new friends! Subscribe below and we promise to keep you up-to-date with our latest new plugins, updates, awesome deals and a few special offers.', 'events-for-woocommerce' ); ?></p>

					<form action="#" method="post" class="mwb-efw-on-boarding-form">
						<?php 
						$efw_onboarding_html = $efw_mwb_efw_obj->mwb_efw_plug_generate_html( $efw_onboarding_form_fields );
						echo esc_html( $efw_onboarding_html );
						?>
						<div class="mwb-efw-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="mwb-efw-on-boarding-form-submit mwb-efw-on-boarding-form-verify ">
								<input type="submit" class="mwb-efw-on-boarding-submit mwb-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="mwb-efw-on-boarding-form-no_thanks">
								<a href="#" class="mwb-efw-on-boarding-no_thanks mdc-button" data-mdc-dialog-action="discard"><?php esc_html_e( 'Skip For Now', 'events-for-woocommerce' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
