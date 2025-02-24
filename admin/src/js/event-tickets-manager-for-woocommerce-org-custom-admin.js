(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
     jQuery(document).ready(function($){
		$('#wps_etmfw_resend_mail_button').on('click',function(){
			$( "#wps_etmfw_loader" ).show();
			var data = {
				action:'wps_etmfw_resend_the_ticket_pdf',
				order_id:$(this).attr("data-id"),
				wps_nonce:wet_org_custom_param.wps_etmfw_edit_prod_nonce
			};
			jQuery.ajax({
				type: 'POST',
				url: wet_org_custom_param.ajaxurl,
				data: data,
				dataType: 'json',
				success: function(response) {
					$( "#wps_etmfw_loader" ).hide();

					if (response.result == true) {
						var message = response.message_success;
						var html = '<b style="color:green;">' + message + '</b>'
					} else {
						var message = response.message_error;
						var html = '<b style="color:red;">' + message + '</b>'

					}
					$( "#wps_etmfw_resend_mail_notification" ).html( html );
		
				},
				error: function(response) {
				console.log(response);
				}
			});

		});
			
		jQuery( document ).ready(
			function($){
				$( document ).on(
					'click',
					'#dismiss-banner',
					function(e){
						e.preventDefault();
						var data = {
							action:'wps_sfw_dismiss_notice_banner',
							wps_nonce:wet_org_custom_param.wps_etmfw_edit_prod_nonce
						};
						$.ajax(
							{
								url: wet_org_custom_param.ajaxurl,
								type: "POST",
								data: data,
								success: function(response)
								{
									window.location.reload();
								}
							}
						);
					}
				);
			}
		);
			
		$(document).on(
			'click',
			'#wps_show_attendee_set',
			function (e) {
				$('.wps_new_attendee').toggle(1000);
			}
		);

		//-------------------------------Pop-up For Pro Tags start -------------------------------------------//
			var wps_event_pro_is_enable = wet_org_custom_param.is_pro_active;
			if(1 == wps_event_pro_is_enable){
				jQuery('.etmfw-radio-switch-class-pro').closest('.wps-form-group').addClass('wps-etmfw-radio-switch-class-pro-tag').hide();  
			} else {
				jQuery('.etmfw-radio-switch-class-pro').closest('.wps-form-group').addClass('wps-etmfw-radio-switch-class-pro-tag').show();
			}
			jQuery('.etmfw-radio-switch-class-pro').closest('.wps-form-group').addClass('wps-etmfw-radio-switch-class-pro-tag');

			$('.wps_rma_pro_class_wrap label,.wps_rma_pro_div label').attr('for', '');

			$(document).on('click', '.wps-etmfw-radio-switch-class-pro-tag', function() {
				$('.wps-rma__popup-for-pro-shadow').show();
				$('.wps-rma__popup-for-pro').addClass('active-pro');
			})
		
			$(document).on('click', '.wps-rma__popup-for-pro-close', function() {
				$('.wps-rma__popup-for-pro-shadow').hide();
				$('.wps-rma__popup-for-pro').removeClass('active-pro');
			})
		
			$(document).on('click', '.wps-rma__popup-for-pro-shadow', function() {
				$(this).hide();
				$('.wps-rma__popup-for-pro').removeClass('active-pro');
			})
		
			$(document).on('click', '.wps_go_pro_link', function(e) {
				e.preventDefault();
				$('.wps-rma__popup-for-pro-shadow').show();
				$('.wps-rma__popup-for-pro').addClass('active-pro');
			})
			$('.button_wps_rma_pro_div').css('pointer-events','none');
		//-------------------------------Pop-up For Pro Tags End -------------------------------------------//

	});
 })( jQuery );
