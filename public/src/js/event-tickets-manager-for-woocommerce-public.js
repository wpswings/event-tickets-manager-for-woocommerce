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
	jQuery( document ).ready( function($){
	 	$("#mwb_etmfw_save_edit_ticket_info_btn").click( 
	 		function(e){
	 			e.preventDefault();
	 			$( '#mwb_etmfw_edit_info_loader' ).css('display','inline-block');
	 			var modifiedValues = {};
	 			var order_id = '';
	 			if( $(document).find('.mwb-edit-form-group').length > 0 ){
	 				$( '.mwb-edit-form-group' ).each(
						function() {
							var label = $( this ).attr('data-id');
							order_id = $(document).find('#mwb_etmfw_edit_info_order').val();
							modifiedValues[ label ] = $('#mwb_etmfw_'+label).val();
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

})( jQuery );
