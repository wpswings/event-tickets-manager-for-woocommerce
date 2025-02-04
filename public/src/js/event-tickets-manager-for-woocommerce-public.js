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
	 						var fieldType = $(this).find('#wps_etmfw_' + label.replace(/[+?]/g, '\\$&')).attr('type');
	 						var check_required = $( this ).find('#wps_etmfw_' + label.replace(/[+?]/g, '\\$&')).attr('required');
							if( check_required && ( '' == $('#wps_etmfw_' + label.replace(/[+?]/g, '\\$&')).val() ) ) {
								$("#wps_etmfw_error_" + label).html( label + etmfw_public_param.is_required);
								('#wps_etmfw_' + label.replace(/[+?]/g, '\\$&')).css( 'border','2px solid red');
								check_validation = true;
								return;
							}
							$('#wps_etmfw_' + label.replace(/[+?]/g, '\\$&')).css('border', '');
	 						if( fieldType == 'radio'){
	 							var radio_value = $( this ).find( 'input[name="wps_etmfw_'+label+'"]:checked' ).val();
	 							modifiedValues[ label ] = radio_value;
	 						} else{
	 							modifiedValues[ label ] = $('#wps_etmfw_' + label.replace(/[+?]/g, '\\$&')).val();
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

	})( jQuery );
	function initMap() {
		const latInput = document.getElementById('wps_etmfw_event_venue_lat');
        const lngInput = document.getElementById('wps_etmfw_event_venue_lng');
		if (latInput && lngInput) {
            let event_lat = parseFloat(latInput.value);
            let event_lng = parseFloat(lngInput.value);
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
        } else {
            console.error('Latitude or longitude inputs not found!');
        }
	}

jQuery(document).on('click', '#wps_etmfwp_event_transfer_button', function (e) {
		e.preventDefault();
		jQuery("#wps_etmfw_checkin_loader").show();
		var user_email =  jQuery('#wps_etmfw_chckin_email').val();
		var for_event = jQuery('#wps_etmfw_event_selected').val();
		var ticket_num = jQuery('#wps_etmfw_imput_ticket').val();
		var user_email = jQuery('#wps_etmfw_chckin_email').val();

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
				console.log('check');
				if (response.result) {
				jQuery("#wps_etmfw_checkin_loader").hide();
					jQuery("#wps_etmfw_error_message").addClass("wps_check_in_success");
					jQuery("#wps_etmfw_error_message").html(response.message);
				} else {
					jQuery("#wps_etmfw_checkin_loader").hide();
					jQuery("#wps_etmfw_error_message").addClass("wps_check_in_error");
					jQuery("#wps_etmfw_error_message").html(response.message);
				}
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

	if('yes' == etmfw_public_param.wps_is_pro_active){

	if(( '' != wps_etmfw_dyn_name) || ('' != wps_etmfw_dyn_mail) || ('' != wps_etmfw_dyn_contact) || ('' != wps_etmfw_dyn_date) || ('' != wps_etmfw_dyn_address)){

			const val = document.querySelector('.qty').value;

			jQuery('.qty').on('keyup',function(){
				var wps_qty_no = jQuery(this).val();
				if((wps_qty_no == NaN) && (wps_qty_no == undefined)){
				wps_qty = parseInt(jQuery(this).val());
				}
			});


		function wps_add_more_form(i){
		var $ = jQuery;
		var product_id = $('.single_add_to_cart_button').val();
		var contentElement = $('#wps_etmfw_dynamic_form_fr_' + product_id +'');
		var contentNew;

		if( i == 0){
			i = 1;
		} else {
			i = i + 1;
		}


		contentNew = '<div class = "wps_etmfw_sub_wrapper" id ="wps_etmfw_form_'+ i + '">';
		contentNew += '<div class="wps_etmfw_div_close removable">+</div>';

		if(wps_etmfw_dyn_name == 'dyn_name' ){
			contentNew += '<label>' + etmfw_public_param.wps_dyn_name +'</label><input type="text" class="wps_text_class" required name = "wps_etmfw_name_'+ i + '" >';
			}
			if(wps_etmfw_dyn_mail == 'dyn_mail' ){
			contentNew += '<label>' + etmfw_public_param.wps_dyn_mail +'</label><input type="email" class="wps_mail_class" required name = "wps_etmfw_email_'+ i + '" >';
			}
			if(wps_etmfw_dyn_contact == 'dyn_contact' ){
			contentNew += '<label>' + etmfw_public_param.wps_dyn_contact +'</label><input type="number" class="wps_contact_class" required name = "wps_etmfw_contact_'+ i + '" >';
			}
			if(wps_etmfw_dyn_date== 'dyn_date' ){
			contentNew += '<label>' + etmfw_public_param.wps_dyn_date +'</label><input type="date" class="wps_date_class" required name = "wps_etmfw_date_'+ i + '" >';
			}
	
			if(wps_etmfw_dyn_address== 'dyn_address' ){
			contentNew += '<label>' + etmfw_public_param.wps_dyn_address +'</label><textarea rows="2"  class="wps_address_class"  required name = "wps_etmfw_address_'+ i + '" ></textarea>';
			}

		contentNew += '</div>';

		contentElement.append(contentNew);

		}


		jQuery('#wps_add_more_people').on('click', function(){
			const randomInteger = Math.floor(Math.random() * 100) + 1;

			var i = countChildDivs();
			wps_add_more_form(i);

			console.log(countChildDivs());
			const inputElement = document.querySelector('.qty');
			inputElement.value = countChildDivs();
			document.querySelector('.single_add_to_cart_button').style.opacity = '1';
			document.querySelector('.single_add_to_cart_button').style.cursor = 'pointer';
			document.querySelector('.single_add_to_cart_button').style.display = 'block';

		});


		jQuery(document).on('click','.wps_etmfw_div_close', function(){
			jQuery(this).parent().remove()
			const inputElement = document.querySelector('.qty');
			var qty_no = countChildDivs();
			inputElement.value = qty_no --;

			if((inputElement.value) <= 0){
			document.querySelector('.single_add_to_cart_button').style.opacity = '0.5';
				document.querySelector('.single_add_to_cart_button').style.cursor = 'none';
				document.querySelector('.single_add_to_cart_button').style.display = 'none';

			}
		});

		jQuery('#wps_add_more_people_wrapper').parent().parent().nextAll('.quantity').hide();
		jQuery('#wps_add_more_people_wrapper').parent().parent().siblings().hide();
		
		function countChildDivs() {
			// Get the parent div element using its ID
			var product_id = jQuery('.single_add_to_cart_button').val();
			const parentDiv = document.getElementById('wps_etmfw_dynamic_form_fr_' + product_id +'');
		
			// Get all child div elements within the parent div
			const childDivs = parentDiv.querySelectorAll('div.wps_etmfw_sub_wrapper');
		
			// Return the number of child div elements
			return childDivs.length;
		}

	}
}
});
///// ---- Js For The Dynamic Form End Here ----- //////