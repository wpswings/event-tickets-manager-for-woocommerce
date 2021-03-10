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

	 $(document).ready(function() {

		const MDCText = mdc.textField.MDCTextField;
        const textField = [].map.call(document.querySelectorAll('.mdc-text-field'), function(el) {
            return new MDCText(el);
        });
        const MDCRipple = mdc.ripple.MDCRipple;
        const buttonRipple = [].map.call(document.querySelectorAll('.mdc-button'), function(el) {
            return new MDCRipple(el);
        });
        const MDCSwitch = mdc.switchControl.MDCSwitch;
        const switchControl = [].map.call(document.querySelectorAll('.mdc-switch'), function(el) {
            return new MDCSwitch(el);
        });

        $('.mwb-password-hidden').click(function() {
            if ($('.mwb-form__password').attr('type') == 'text') {
                $('.mwb-form__password').attr('type', 'password');
            } else {
                $('.mwb-form__password').attr('type', 'text');
            }
        });

        var imageurl = $( "#mwb_etmfw_mail_setting_upload_logo" ).val();
			if (imageurl != null && imageurl != "") {
				$( "#mwb_etmfw_mail_setting_upload_image" ).attr( "src",imageurl );
				$( "#mwb_etmfw_mail_setting_remove_logo" ).show();

			} else{
				$( "#mwb_etmfw_mail_setting_remove_logo" ).hide();
			}
			$( ".mwb_etmfw_mail_setting_remove_logo_span" ).click(
				function(){
					$( "#mwb_etmfw_mail_setting_remove_logo" ).hide();
					$( "#mwb_etmfw_mail_setting_upload_logo" ).val( "" );
				}
			);
			var imageurl = $( "#mwb_etmfw_mail_setting_upload_logo" ).val();
			if (imageurl != null && imageurl != "") {
				$( "#mwb_etmfw_mail_setting_upload_image" ).attr( "src",imageurl );
				$( "#mwb_etmfw_mail_setting_remove_logo" ).show();

			}
			$( "#mwb_etmfw_mail_setting" ).click(
				function(){
					$( "#mwb_etmfw_mail_setting_wrapper" ).slideToggle();
				}
			);

			$( '#mwb_etmfw_mail_setting_upload_logo_button' ).click(
				function(e){
					e.preventDefault();
					var imageurl = $( "#mwb_etmfw_mail_setting_upload_logo" ).val();
					tb_show( '', 'media-upload.php?TB_iframe=true' );

					window.send_to_editor = function(html)
					{
							var imageurl = $( html ).attr( 'href' );

						if (typeof imageurl == 'undefined') {
							imageurl = $( html ).attr( 'src' );
						}
							var last_index = imageurl.lastIndexOf( '/' );
							var url_last_part = imageurl.substr( last_index + 1 );
						if ( url_last_part == '' ) {

							imageurl = $( html ).children( "img" ).attr( "src" );
						}
							$( "#mwb_etmfw_mail_setting_upload_logo" ).val( imageurl );
							$( "#mwb_etmfw_mail_setting_upload_image" ).attr( "src",imageurl );
							$( "#mwb_etmfw_mail_setting_remove_logo" ).show();
							tb_remove();
					};
					return false;
				}
			);

	});

	$(window).load(function(){
		// add select2 for multiselect.
		if( $(document).find('.mwb-defaut-multiselect').length > 0 ) {
			$(document).find('.mwb-defaut-multiselect').select2();
		}
	});

	})( jQuery );
