(function($) {
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

        // Available Ticket Template Change - Start.
        $('.wps_etmfw_colorpicker').wpColorPicker();

        //Dynamic text color for pdf ticket start here.
        var wps_etmfw_text_color_change = $('.wps_etmfw_pdf_text_color'); //2.1.7.
        var wps_etmfw_text_color = '';
        var wps_etmfw_text_color_selector = $('.wps_etmfw_pdf_text_colour');//2.1.7.

        wps_etmfw_text_color_change.wpColorPicker({ //2.1.7.
            change: (event, ui) => {

                wps_etmfw_text_color = ui.color.toString();
                // console.log(product_tcolor);
                wps_etmfw_text_color_selector.css('color', wps_etmfw_text_color);
                // ProductQty.css('color', product_tcolor);
            }
        });
        //Dynamic text color for pdf ticket end here.




        //Dynamic color change for border start here.
        var wps_etmfw_border_color_change = $('.wps_etmfw_select_ticket_border_color'); //2.1.7.
        var wps_etmfw_border_color = '';
        var wps_etmfw_border_color_selector = $('.wps_etmfw_border_color');//2.1.7.

        wps_etmfw_border_color_change.wpColorPicker({ //2.1.7.
            change: (event, ui) => {

                wps_etmfw_border_color = ui.color.toString();
                // console.log(product_tcolor);
                wps_etmfw_border_color_selector.css('border-color', wps_etmfw_border_color);
                // ProductQty.css('color', product_tcolor);
            }
        });
        //Dynamic color change for border ends here.


        //Dynamic Changes for background start Here.
        var wps_etmfw_background_color_change = $('.wps_etmfw_select_ticket_background'); //2.1.7.
        var wps_etmfw_background_color = '';
        var wps_etmfw_background_selector = $('.wps_etmfw_ticket_body');//2.1.7.

        wps_etmfw_background_color_change.wpColorPicker({ //2.1.7.
            change: (event, ui) => {

                wps_etmfw_background_color = ui.color.toString();
                // console.log(product_tcolor);
                wps_etmfw_background_selector.css('background-color', wps_etmfw_background_color);
                // ProductQty.css('color', product_tcolor);
            }
        });
        //Dynamic Changes for background end Here.

        //Dynamic Changes for logo size start Here.
        var VerticalSpacingTop = '';
        var VerticalSpacingTop1 = '';

        $('.wps_etmfw_logo_size_slider').on('change', function() {

            VerticalSpacingTop = $(this).val();
            console.log(VerticalSpacingTop);

            var yourImg = document.getElementById('wps_wem_logo_id');
            if (yourImg && yourImg.style) {
                yourImg.style.width = VerticalSpacingTop + 'px';
            }

            $('.wps_etmfw_logo_size_slider_span').html(VerticalSpacingTop + 'px');
        });
        //Dynamic Changes for logo end Here.

        //Dyanmic chnages for the QR Image start here.
        $('.wps_etmfw_qr_size_slider').on('change', function() {

            VerticalSpacingTop1 = $(this).val();
            console.log(VerticalSpacingTop1);

            var yourImg = document.getElementById('wps_qr_image');
            if (yourImg && yourImg.style) {
                yourImg.style.width = VerticalSpacingTop1 + 'px';
                yourImg.style.height = VerticalSpacingTop1 + 'px';
            }

            $('.wps_etmfw_qr_size_slider_span').html(VerticalSpacingTop1 + 'px');
        });
        //Dyanmic chnages for the QR Image end here.

        jQuery('body').on('click', '.wps_etmfw_upload_image_button', function(e) {

            e.preventDefault();
            var button = jQuery(this),
                custom_uploader = wp.media({
                    title: 'Insert image',
                    library: {
                        type: 'image'
                    },
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                }).on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    jQuery(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:150px;display:block;" />').next().val(attachment.id).next().show();
                }).open();
        });

        jQuery('body').on('click', '.wps_etmfw_remove_image_button', function(e) {
			// alert('me');
            e.preventDefault();
            jQuery(this).hide().prev().val('').prev().addClass('button').html('Upload image');
            return false;
        });

        $('#wps_etmfw_new_layout_setting_save_2').on('click', function(e) {
            $('#wps_etmfw_new_layout_setting_save').trigger("click");
        });

        var temp_id;
        $('.wps_etmfw_template_link').on('click', function(e) {

            e.preventDefault();
            temp_id = $(this).attr('data_link');
            // alert(temp_id);
            $('.wps_etmfw_skin_popup_wrapper').css('display', 'flex');

            // On yes, reset the css
            $('.wps_ubo_template_layout_yes').on('click', function(e) { //Ticket Template chnage css and design.

                e.preventDefault();
                $('#wps_etmfw_ticket_template').val(temp_id);
                $('.wps_etmfw_animation_loader').css('display', 'flex'); // Loader.

                // For Scroll back.
                $('#wps_etmfw_new_layout_setting_save').click(); // Save Ticket Layout.
                $('.wps_etmfw_skin_popup_wrapper').css('display', 'none');
            });

            // On No, do nothing.
            $('.wps_ubo_template_layout_no').on('click', function(e) {
                e.preventDefault();
                $('.wps_etmfw_skin_popup_wrapper').css('display', 'none');

            });

        });

        // Onclick outside the popup close the popup.
        $('body').click(function(e) {
            if (e.target.className == 'wps_etmfw_skin_popup_wrapper') {
                $('.wps_etmfw_skin_popup_wrapper').hide();
            }
        });
        // Available Ticket Template Change - End.


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

        $(document).on(
            'click',
            '.wps-password-hidden',
            function() {
                if ($('.wps-form__password').attr('type') == 'text') {
                    $('.wps-form__password').attr('type', 'password');
                } else {
                    $('.wps-form__password').attr('type', 'text');
                }
            });

        var imageurl = $("#wps_etmfw_mail_setting_upload_logo").val();
        if (imageurl != null && imageurl != "") {
            $("#wps_etmfw_mail_setting_upload_image").attr("src", imageurl);
            $("#wps_etmfw_mail_setting_remove_logo").show();
            $("#wps_etmfw_mail_setting_upload_logo_button").hide();
        } else {
            $("#wps_etmfw_mail_setting_remove_logo").hide();
        }

        $(document).on(
            'click',
            '.wps_etmfw_mail_setting_remove_logo_span',
            function() {
                $("#wps_etmfw_mail_setting_remove_logo").hide();
                $("#wps_etmfw_mail_setting_upload_logo").val("");
                $("#wps_etmfw_mail_setting_upload_logo_button").show();
            }
        );

        $(document).on(
            'click',
            '#wps_etmfw_mail_setting',
            function() {
                $("#wps_etmfw_mail_setting_wrapper").slideToggle();
            }
        );

        $(document).on(
            'click',
            '#wps_etmfw_mail_setting_upload_logo_button',
            function(e) {
                e.preventDefault();
                var imageurl = $("#wps_etmfw_mail_setting_upload_logo").val();
                tb_show('', 'media-upload.php?TB_iframe=true');

                window.send_to_editor = function(html) {
                    var imageurl = $(html).attr('href');

                    if (typeof imageurl == 'undefined') {
                        imageurl = $(html).attr('src');
                    }
                    var last_index = imageurl.lastIndexOf('/');
                    var url_last_part = imageurl.substr(last_index + 1);
                    if (url_last_part == '') {

                        imageurl = $(html).children("img").attr("src");
                    }
                    $("#wps_etmfw_mail_setting_upload_logo").val(imageurl);
                    $("#wps_etmfw_mail_setting_upload_image").attr("src", imageurl);
                    $("#wps_etmfw_mail_setting_remove_logo").show();
                    $("#wps_etmfw_mail_setting_upload_logo_button").hide();
                    tb_remove();
                };
                return false;
            }
        );

        $(".wps-password-hidden").click(function() {
            var cur_targetEl = $(this).siblings(".wps-form__password");
            if (cur_targetEl.attr("type") == "text") {
                $(this).text('visibility');
                cur_targetEl.attr("type", "password");
            } else {
                $(this).text('visibility_off');
                cur_targetEl.attr("type", "text");
            }
        });


    });

    $(window).load(function() {
        // add select2 for multiselect.
        if ($(document).find('.wps-defaut-multiselect').length > 0) {
            $(document).find('.wps-defaut-multiselect').select2();
        }
    });
    // PDF Setting Layout Section JS - start.
    $(document).on('click', '.wps-etmfw-appearance-template', function(e) {
        e.preventDefault();
        $('.wps-etmfw-appearance-nav-tab a').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.wps-etmfw-template-section').css("display", "block");
        $('.wps_etmfw_table_column_wrapper').css("display", "none");
    });
    $(document).on('click', '.wps-etmfw-appearance-design', function(e) {
        $('.wps-etmfw-appearance-nav-tab a').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.wps-etmfw-template-section').css("display", "none");
        $('.wps_etmfw_table_column_wrapper').css("display", "block");
    });

		
	// Live Preview JS start.

	// Border Styling.
	var BumpOfferBox = $('#wps_etmfw_parent_wrapper');
	var border_type = '';
	var border_color = '';
	var border_size = '';
	var background_color = '';

	

	$(document).on('change', '.wps_etmfw_preview_select_border_type' , function () {

		// alert('clicked');
		wps_etmfw_apply_border_styling();
		
	});
	// var Bordercolorpicker = $('.wps_etmfw_select_ticket_border_color');
	// // var Bordercolorpicker1 = $('.wps_etmfw_select_ticket_background');

	// Bordercolorpicker.wpColorPicker({
	// 	change: (event, ui) => {

	// 		border_color = ui.color.toString();
	// 		// alert('prince');

	// 		wps_etmfw_apply_border_styling( border_color );

	// 	}
	// });

	// Apply Border stylings.
	function wps_etmfw_apply_border_styling( border_color = '' ) {
			border_type = $('.wps_etmfw_preview_select_border_type').val();

			console.log(border_type);
			// // document.getElementById('wps_etmfw_parent_wrapper').style.border = "1px" + ' ' +border_type;
			if( border_color == '' ) {

				border_color = $('.wps_etmfw_select_ticket_border_color').val();
			}else {
				border_color = 'red';
			}
			

			if( 'double' == border_type ) {

				border_size = '4px';
			}

			else {

				border_size = '2px';
			}

			document.getElementById('wps_etmfw_parent_wrapper').style.border = border_size + ' ' + border_type + ' ' + border_color;
            document.getElementById('wps_etmfw_parent_wrapper_2').style.border = border_size + ' ' + border_type + ' ' + border_color;
            // document.getElementById('wps_etmfw_parent_wrapper_3').style.border = border_size + ' ' + border_type + ' ' + border_color;
			// BumpOfferBox.css( 'border', border_type);

		}
        $('.wps_etmfw_colorpicker').wpColorPicker();

})(jQuery);