(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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
	jQuery( document ).ready(
		function($){
			$( document ).on(
			'click',
			'#wps_etmfw_checkin_button',
			function(e){
				e.preventDefault();
				$("#wps_etmfw_checkin_loader").show();
				var for_event = $('#wps_etmfw_event_selected').val();
				var ticket_num = $('#wps_etmfw_imput_ticket').val();
				var user_email =  $('#wps_etmfw_chckin_email').val();
				if (for_event == null || for_event == "" || ticket_num == null || ticket_num == "" || user_email == null || user_email == "" ) {
					$("#wps_etmfw_error_message").html(etmfw_checkin_param.wps_etmfw_require_text);
					$("#wps_etmfw_error_message").addClass("wps_check_in_error");
					return false;
				}
				var pat = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(user_email);
				if ( ! pat) {
					$('#wps_etmfw_error_message').html(etmfw_checkin_param.wps_etmfw_email_text);
					$('#wps_etmfw_chckin_email').focus();
					$('#wps_etmfw_chckin_email').css("border", "2px solid red");
					return;
				 }
				 else {
					$('#wps_etmfw_chckin_email').css("border", "2px solid green");
				 }
				var data = {
					action:'wps_etmfw_make_user_checkin',
					for_event:for_event,
					ticket_num:ticket_num,
					wps_nonce:etmfw_checkin_param.wps_etmfw_nonce,
					user_email : user_email
				};
				$.ajax(
					{
						dataType: 'json',
						url: etmfw_checkin_param.ajaxurl,
						type: "POST",
						data: data,
						success: function(response)
						{
							$("#wps_etmfw_checkin_loader").hide();
							$("#wps_etmfw_error_message").html(response.message);
							if( response.result ){
								$("#wps_etmfw_error_message").addClass("wps_check_in_success");
							} else{
								$("#wps_etmfw_error_message").addClass("wps_check_in_error");
							}
						}
					}
				);
			}
		);

	});

})( jQuery );
