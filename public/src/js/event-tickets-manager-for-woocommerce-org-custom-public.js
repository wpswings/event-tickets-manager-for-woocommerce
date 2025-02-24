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


        $('#wps_etmfw_resend_mail_button_frontend').on('click',function(e){
            e.preventDefault();
            $( "#wps_etmfw_resend_mail_frontend_notification" ).html( "" );
            var order_id = $( this ).data( 'id' );
            $( "#wps_etmfw_loader" ).show();
            var data = {
                action:'wps_etmfw_resend_mail_pdf_order_deatails',
                order_id:order_id,
                wps_nonce:etmfw_org_custom_param_public.wps_etmfw_public_nonce
            };

            jQuery.ajax({
                type: 'POST',
                url: etmfw_org_custom_param_public.ajaxurl,
                data: data,
                dataType: 'text',
                success: function(response) {

                $( "#wps_etmfw_loader" ).hide();
                var html = '<b style="color:green;">' + response + '</b>';
                $( "#wps_etmfw_resend_mail_frontend_notification" ).html( html );
                
                },
                error: function(response) {
                 console.log(response);
                 var html = '<b style="color:green;">' + response + '</b>';
                 $( "#wps_etmfw_resend_mail_frontend_notification" ).html( html );
                }
            });

        });
         
         
        /* Functionality For Filtering The Product On Search */
         $(document).on("input", "#wps-search-event", function (e) {
             var wps_input_value = $(this).val();
             var wps_search_input = $(this).val().trim();
             var wps_search_word = wps_input_value.split("");

            if (wps_search_input === "") {
                wps_display_default_product_listing();

            }else {
                if (wps_search_word.length >= 3) {
                  $("#wps-loader").show();
        
                  var data = {
                    action: "wps_default_filter_product_search",
                    search_term: wps_input_value,
                    wps_nonce:etmfw_org_custom_param_public.wps_etmfw_public_nonce
                  };
                  $.ajax({
                    type: "POST",
                    url: etmfw_org_custom_param_public.ajaxurl,
                    data: data,
                    success: function (response) {
                      $("#wps-search-results").html(response);

                      $("#wps-loader").hide();
                    },
                    error: function (response) {
                      console.log('ajax fails');
                    },
                  });
                } else {
                  $("#wps-loader").show();
                  $("#wps-search-results").html("Please enter 3 or more characters");
                }
              }
         });

         wps_display_default_product_listing();

         function wps_display_default_product_listing(wps_selected_value = '') {
            var data = {
                action: "wps_default_filter_product_search",
              wps_selected_value: wps_selected_value,
              wps_nonce:etmfw_org_custom_param_public.wps_etmfw_public_nonce
            };
            $.ajax({
                type: "POST",
                url: etmfw_org_custom_param_public.ajaxurl,
                data: data,
                success: function (response) {
                $("#wps-search-results").html(response);
                $("#wps-loader").hide();
                },
                error: function (response) {
                console.log("ajax fails");
                },
            }); 
         }
         
         $('input[name="wps_select_event_listing_type"]').change(function () {
            var wps_selected_value = $(this).val();
             var data = {
                action: "wps_select_event_listing_type",
               wps_selected_value: wps_selected_value,
               wps_nonce:etmfw_org_custom_param_public.wps_etmfw_public_nonce,
            };
            $.ajax({
                type: "POST",
                url: etmfw_org_custom_param_public.ajaxurl,
                data: data,
                success: function (response) {
                    var wps_search_result_div = document.getElementById('wps-search-results');
                    // Remove all classes from the div
                    wps_search_result_div.className = '';
                    wps_search_result_div.classList.add(response);
                },
                error: function (response) {
                console.log("ajax fails");
                },
            });  
         });

        $(document).on('click', '.wps_woocommerce-pagination a.page-numbers', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            var search_term = $('#wps-search-event').val();
            var data = {
                action: 'wps_default_filter_product_search',
                wps_nonce: etmfw_org_custom_param_public.wps_etmfw_public_nonce,
                page: page,
                search_term: search_term,
            };
            console.log(data);

            $.ajax({
                type: "POST",
                url: etmfw_org_custom_param_public.ajaxurl,
                data: data,
                success: function (response) {
                  $('#wps-search-results').html(response);
                },
                error: function (response) {
                  console.log("ajax fails");
                },
            });
        });
      
     });

    })( jQuery );