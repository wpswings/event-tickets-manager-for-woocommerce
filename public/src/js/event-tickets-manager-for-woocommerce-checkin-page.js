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
			'#mwb_etmfw_checkin_button',
			function(e){
				e.preventDefault();
				$("#mwb_etmfw_checkin_loader").show();
				var for_event = $('#mwb_etmfw_event_selected').val();
				var ticket_num = $('#mwb_etmfw_imput_ticket').val();
				if (for_event == null || for_event == "" || ticket_num == null || ticket_num == "" ) {
					$("#mwb_etmfw_error_message").html("Fields required.");
					return false;
				}
				var data = {
					action:'mwb_etmfw_make_user_checkin',
					for_event:for_event,
					ticket_num:ticket_num,
					mwb_nonce:etmfw_checkin_param.mwb_etmfw_nonce
				};
				$.ajax(
					{
						dataType: 'json',
						url: etmfw_checkin_param.ajaxurl,
						type: "POST",
						data: data,
						success: function(response)
						{
							$("#mwb_etmfw_checkin_loader").hide();
							$("#mwb_etmfw_error_message").html(response.message);
						}
					}
				);
			}
		);

	});

})( jQuery );
