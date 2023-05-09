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
                // wps_uwgc_nonce:wps_uwgc_param.wps_uwgc_nonce
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

     });

    })( jQuery );