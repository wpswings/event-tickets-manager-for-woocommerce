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
	 	$( document ).on(
	 		'click',
	 		'#wps_etmfw_edit_ticket',
	 		function(){
	 		$("#wps_etmfw_edit_ticket_form").toggleClass("wps_etmfw_show_ticket_form");
	 	});
	 });
	 jQuery( document ).ready( function($){
	 	$( document ).on(
	 		'click',
	 		'#wps_etmfw_save_edit_ticket_info_btn',
	 		function(e){
	 			var check_validation = false;

	 			e.preventDefault();
	 			$( '#wps_etmfw_edit_info_loader' ).css('display','inline-block');
	 			var modifiedValues = {};
	 			var order_id = '';
	 			if( $(document).find('.wps-edit-form-group').length > 0 ){
	 				$( '.wps-edit-form-group' ).each(
	 					function() {
	 						var label = $( this ).attr('data-id');
	 						var fieldType = $( this ).find('#wps_etmfw_'+label).attr('type');
	 						var check_required = $( this ).find('#wps_etmfw_'+label).attr('required');
							if( check_required && ( '' == $('#wps_etmfw_'+label).val() ) ) {
								$("#wps_etmfw_error_" + label).html( label + etmfw_public_param.is_required);
								$('#wps_etmfw_'+label).css( 'border','2px solid red');
								check_validation = true;
								return;
							}
							$('#wps_etmfw_'+label).css('border', '');
	 						if( fieldType == 'radio'){
	 							var radio_value = $( this ).find( 'input[name="wps_etmfw_'+label+'"]:checked' ).val();
	 							modifiedValues[ label ] = radio_value;
	 						} else{
	 							modifiedValues[ label ] = $('#wps_etmfw_'+label).val();
	 						}
	 						order_id = $(document).find('#wps_etmfw_edit_info_order').val();
	 					}
					);
	 			}
				 if( ! check_validation ) {

					 var data = {
						 action:'wps_etmfw_edit_user_info',
						 form_value : modifiedValues, 
						 order_id:order_id,
						 wps_nonce:etmfw_public_param.wps_etmfw_public_nonce
					 };
					 $.ajax({
						 type: 'POST',
						 url: etmfw_public_param.ajaxurl,
						 data: data,
						 dataType: 'json',
						 success: function(response) {
							 $( '#wps_etmfw_edit_info_loader' ).css('display','none');	
							 window.location.reload();
						 },
						 error: function(response) {
	
						 }
					 });
				 }
	 		}
	 		);
	 });


	 jQuery(window).load( function(){
	 	var event_view = etmfw_public_param.event_view;
	 	if( event_view == 'calendar') {
	 		var data = {
	 			action:'wps_etmfw_get_calendar_events',
	 			wps_nonce:etmfw_public_param.wps_etmfw_public_nonce

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
	function initMap() {
		let event_lat = parseInt( document.getElementById('etmfw_event_lat').value );
		let event_lng = parseInt( document.getElementById('etmfw_event_lng').value );
		const myLatLng = { lat: event_lat, lng: event_lng };
			const map = new google.maps.Map(document.getElementById("wps_etmfw_event_map"), {
			zoom: 4,
			center: myLatLng,
			});
			new google.maps.Marker({
			position: myLatLng,
			map,
			title: "Event!",
		});
	}

	jQuery( document ).on( 'click', '#wps_etmfwp_checkin_button', function(e) {
		e.preventDefault();
		jQuery("#wps_etmfw_checkin_loader").show();
		var user_email =  jQuery('#wps_etmfw_chckin_email').val();
		var for_event = jQuery('#wps_etmfw_event_selected').val();
		var ticket_num = jQuery('#wps_etmfw_imput_ticket').val();
		var user_email =  jQuery('#wps_etmfw_chckin_email').val();

		var sel = document.getElementById("wps_etmfw_event_selected");
		var text= sel.options[sel.selectedIndex].text;
		var data = {
			action:'wps_etmfwp_transfer_ticket_org',
			for_event:for_event,
			ticket_num:ticket_num,
			event_name:text,
			// wps_nonce:etmfwp_public_param.wps_etmfw_nonce,
			user_email : user_email
		};
		jQuery.ajax(
			{
				dataType:'json',
				url: etmfw_public_param.ajaxurl,
				type: "POST",
				data: data,
				success: function(response)
				{
					console.log('shared');
				}
			}
		);
	});