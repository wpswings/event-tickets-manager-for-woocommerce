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
	 $(document).ready(function(){
	 	$("#mwb_etmfw_edit_ticket").click(function(){
	 		$("#mwb_etmfw_edit_ticket_form").toggleClass("mwb_etmfw_show_ticket_form");
	 	});
	 });
	 jQuery( document ).ready( function($){
	 	
	 	$( document ).on(
	 		'click',
	 		'#mwb_etmfw_save_edit_ticket_info_btn',
	 		function(e){
	 			e.preventDefault();
	 			$( '#mwb_etmfw_edit_info_loader' ).css('display','inline-block');
	 			var modifiedValues = {};
	 			var order_id = '';
	 			if( $(document).find('.mwb-edit-form-group').length > 0 ){
	 				$( '.mwb-edit-form-group' ).each(
	 					function() {
	 						var label = $( this ).attr('data-id');
	 						var fieldType = $( this ).find('#mwb_etmfw_'+label).attr('type');
	 						if( fieldType == 'radio'){
	 							var radio_value = $( this ).find( 'input[name="mwb_etmfw_'+label+'"]:checked' ).val();
	 							modifiedValues[ label ] = radio_value;
	 						} else{
	 							modifiedValues[ label ] = $('#mwb_etmfw_'+label).val();
	 						}
	 						order_id = $(document).find('#mwb_etmfw_edit_info_order').val();
	 					}
	 					);
	 			}
	 			var data = {
	 				action:'mwb_etmfw_edit_user_info',
	 				form_value : modifiedValues, 
	 				order_id:order_id,
	 				mwb_nonce:etmfw_public_param.mwb_etmfw_public_nonce
	 			};
	 			$.ajax({
	 				type: 'POST',
	 				url: etmfw_public_param.ajaxurl,
	 				data: data,
	 				dataType: 'json',
	 				success: function(response) {
	 					$( '#mwb_etmfw_edit_info_loader' ).css('display','none');	
	 					window.location.reload();
	 				},
	 				error: function(response) {

	 				}
	 			});
	 		}
	 		);
	 });


	 jQuery(window).load( function(){
	 	var event_view = etmfw_public_param.event_view;
	 	if( event_view == 'calendar') {
	 		var data = {
	 			action:'mwb_etmfw_get_calendar_events',
	 			mwb_nonce:etmfw_public_param.mwb_etmfw_public_nonce

	 		};
	 		$.ajax({
	 			type: 'POST',
	 			url: etmfw_public_param.ajaxurl,
	 			data: data,
	 			dataType: 'json',
	 			success: function(response) {
	 				var calendarEl = document.getElementById('calendar');
	 				var calendar = new FullCalendar.Calendar(calendarEl, {
	 					initialView: 'dayGridMonth',
	 					initialDate: new Date(),
	 					headerToolbar: {
	 						left: 'prev,next today',
	 						center: 'title',
	 						right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
	 					},
	 					events: response.result
	 				});
	 				calendar.render();
	 			},
	 			error: function(response) {

	 			}
	 		});
	 	}
	 })

	})( jQuery );
