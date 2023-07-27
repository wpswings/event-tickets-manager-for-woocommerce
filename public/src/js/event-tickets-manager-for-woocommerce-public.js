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

///// ---- Js For The Dynamic Form Start Here ----- //////
jQuery(document).ready(function(){
	var wps_etmfw_dyn_name = etmfw_public_param.wps_etmfw_dyn_name;
	var wps_etmfw_dyn_mail = etmfw_public_param.wps_etmfw_dyn_mail;
	var wps_etmfw_dyn_contact = etmfw_public_param.wps_etmfw_dyn_contact;
	var wps_etmfw_dyn_date = etmfw_public_param.wps_etmfw_dyn_date;
	var wps_etmfw_dyn_address = etmfw_public_param.wps_etmfw_dyn_address;

	if(('' != wps_etmfw_dyn_name) || ('' != wps_etmfw_dyn_mail) || ('' != wps_etmfw_dyn_contact) || ('' != wps_etmfw_dyn_date) || ('' != wps_etmfw_dyn_address)){
	console.log(wps_etmfw_dyn_name);

	const val = document.querySelector('.qty').value;

jQuery('.qty').on('keyup',function(){
	var wps_qty_no = jQuery(this).val();
	if((wps_qty_no == NaN) && (wps_qty_no == undefined)){
	wps_qty = parseInt(jQuery(this).val());
	// console.log(wps_qty);
	}
});


function wps_add_more_form(i){
var $ = jQuery;
var product_id = $('.single_add_to_cart_button').val();
var contentElement = $('#wps_etmfw_dynamic_form_fr_' + product_id +'');
// console.log(contentElement);
var contentNew;

// while (i > 0) {
contentNew = '<div class = "wps_etmfw_sub_wrapper" id ="wps_etmfw_form_'+ i + '">';
contentNew += '<div class="wps_etmfw_div_close removable button">CLOSE</div>';
if(wps_etmfw_dyn_name == 'dyn_name' ){
contentNew += '<label>Name</label><input type="text" class="wps_text_class" required name = "wps_etmfw_name_text_'+ i + '" >';
}
if(wps_etmfw_dyn_mail == 'dyn_mail' ){
contentNew += '<label>Email</label><input type="email" class="wps_mail_class" required name = "wps_etmfw_name_email_'+ i + '" >';
}
if(wps_etmfw_dyn_contact == 'dyn_contact' ){
contentNew += '<label>Contact</label><input type="number" class="wps_contact_class" required name = "wps_etmfw_name_contact_'+ i + '" >';
}
if(wps_etmfw_dyn_date== 'dyn_date' ){
contentNew += '<label>Date</label><input type="date" class="wps_date_class" required name = "wps_etmfw_name_date_'+ i + '" >';
}

if(wps_etmfw_dyn_address== 'dyn_address' ){
contentNew += '<label>Address</label><input type="textarea" class="wps_address_class"  required name = "wps_etmfw_name_address_'+ i + '" >';
}
// contentNew += '<select name = "wps_user_type_'+ i + '" id = "wps_select_type_'+ i + '"></select>';
contentNew += '</div>';
// i--;
contentElement.append(contentNew);
// }
}


jQuery('#wps_add_more_people').on('click', function(){
	const randomInteger = Math.floor(Math.random() * 100) + 1;

    var i = randomInteger;
	wps_add_more_form(i);

	console.log(countChildDivs());
	const inputElement = document.querySelector('.qty');
	inputElement.value = countChildDivs();
	document.querySelector('.single_add_to_cart_button').style.opacity = '1';
	document.querySelector('.single_add_to_cart_button').style.cursor = 'pointer';
});

// document.querySelector('.qty').value = 0;

jQuery(document).on('click','.wps_etmfw_div_close', function(){
    jQuery(this).parent().remove()
	const inputElement = document.querySelector('.qty');
	var qty_no = countChildDivs();
	inputElement.value = qty_no --;

	if((inputElement.value) <= 0){
	document.querySelector('.single_add_to_cart_button').style.opacity = '0.5';
	document.querySelector('.single_add_to_cart_button').style.cursor = 'none';
	}
});



function countChildDivs() {
	// Get the parent div element using its ID
	var product_id = jQuery('.single_add_to_cart_button').val();
	const parentDiv = document.getElementById('wps_etmfw_dynamic_form_fr_' + product_id +'');
  
	// Get all child div elements within the parent div
	const childDivs = parentDiv.querySelectorAll('div.wps_etmfw_sub_wrapper');
  
	// Return the number of child div elements
	return childDivs.length;
  }



// contentElement.innerHTML += '<div class="wps_etmfw_main_wrapper">';

// var i = 2;
// while (i > 0) {
// // some code
// contentElement.innerHTML += '<div class = "wps_etmfw_sub_wrapper" id ="wps_etmfw_form_'+ i + '"><div class="wps_etmfw_div_close">CLOSE</div>';
// contentElement.innerHTML += '<span><label>Name</label><input type="text" class="wps_text_class"  name = "wps_etmfw_name_text_'+ i + '" ></span>';
// contentElement.innerHTML += '</div>';
// i--;
// }

// contentElement.innerHTML += '</div>';

// contentElement.innerHTML += '<div><div>CLOSE</div>';
// contentElement.innerHTML += '<p><input type="text" class="wps_text_class"  name = "wps_etmfw_name_text" value = "" ></p>';
// contentElement.innerHTML += '</div></div>';
	}
});
///// ---- Js For The Dynamic Form End Here ----- //////