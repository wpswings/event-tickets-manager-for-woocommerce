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

        $( document ).on(
			'click',
			'.wps-password-hidden',
			function(){
            if ($('.wps-form__password').attr('type') == 'text') {
                $('.wps-form__password').attr('type', 'password');
            } else {
                $('.wps-form__password').attr('type', 'text');
            }
        });

        var imageurl = $( "#wps_etmfw_mail_setting_upload_logo" ).val();
		if (imageurl != null && imageurl != "") {
			$( "#wps_etmfw_mail_setting_upload_image" ).attr( "src",imageurl );
			$( "#wps_etmfw_mail_setting_remove_logo" ).show();
			$( "#wps_etmfw_mail_setting_upload_logo_button" ).hide();
		} else{
			$( "#wps_etmfw_mail_setting_remove_logo" ).hide();
		}

		$( document ).on(
			'click',
			'.wps_etmfw_mail_setting_remove_logo_span',
			function(){
				$( "#wps_etmfw_mail_setting_remove_logo" ).hide();
				$( "#wps_etmfw_mail_setting_upload_logo" ).val( "" );
				$( "#wps_etmfw_mail_setting_upload_logo_button" ).show();
			}
		);
			
		$( document ).on(
			'click',
			'#wps_etmfw_mail_setting',
			function(){
				$( "#wps_etmfw_mail_setting_wrapper" ).slideToggle();
			}
		);

		$( document ).on(
			'click',
			'#wps_etmfw_mail_setting_upload_logo_button',
			function(e){
				e.preventDefault();
				var imageurl = $( "#wps_etmfw_mail_setting_upload_logo" ).val();
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
						$( "#wps_etmfw_mail_setting_upload_logo" ).val( imageurl );
						$( "#wps_etmfw_mail_setting_upload_image" ).attr( "src",imageurl );
						$( "#wps_etmfw_mail_setting_remove_logo" ).show();
						$( "#wps_etmfw_mail_setting_upload_logo_button" ).hide();
						tb_remove();
				};
				return false;
			}
		);

	});

	$(window).load(function(){
		// add select2 for multiselect.
		if( $(document).find('.wps-defaut-multiselect').length > 0 ) {
			$(document).find('.wps-defaut-multiselect').select2();
		}
	});

	})( jQuery );
