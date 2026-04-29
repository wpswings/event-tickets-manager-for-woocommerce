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

    $(document).ready(function () {
        $('#wps_etmfwp_include_barcode').change(function () {
            console.log('barcode');
            if ($(this).is(":checked")) {
                $('#wps_etmfwp_include_qr').parent().parent().removeClass('mdc-switch--checked');
                $('#wps_etmfwp_include_qr').attr('checked', false);
                $('#wps_etmfwp_include_qr').attr('aria-checked', 'false').attr('value', '');
            }
            });

        $(document).on('change', '#wps_etmfwp_include_qr',function () {
                $('#wps_etmfwp_include_barcode').parent().parent().removeClass('mdc-switch--checked');
                    $('#wps_etmfwp_include_barcode').attr('checked', false);
                    $('#wps_etmfwp_include_barcode').attr('aria-checked', 'false').attr('value', '');
            });

        wps_etmfw_hide_bck_ground_image_setting();
        if ( typeof window.etmfwInitColorPickers === 'function' ) {
            window.etmfwInitColorPickers( document );
        }

        //Dynamic text color for pdf ticket start here.
        function wps_etmfw_apply_text_color() {
            var wps_etmfw_text_color = $('.wps_etmfw_pdf_text_color').val() || '';
            $('.wps_etmfw_pdf_text_colour').css('color', wps_etmfw_text_color);
            $('#wps_not_change_color').css('color', 'white');
        }

        $(document).on('change input', '.wps_etmfw_pdf_text_color', wps_etmfw_apply_text_color);
        wps_etmfw_apply_text_color();
        //Dynamic text color for pdf ticket end here.
        //Dynamic color change for border start here.
        function wps_etmfw_apply_border_preview_color() {
            var wps_etmfw_border_color = $('.wps_etmfw_select_ticket_border_color').val() || '';
            $('.wps_etmfw_border_color').css('border-color', wps_etmfw_border_color);
        }

        $(document).on('change input', '.wps_etmfw_select_ticket_border_color', function() {
            wps_etmfw_apply_border_preview_color();
            wps_etmfw_apply_border_styling();
        });
        wps_etmfw_apply_border_preview_color();
        //Dynamic color change for border ends here.


        //Dynamic Changes for background start Here.
        function wps_etmfw_apply_background_color() {
            var wps_etmfw_background_color = $('.wps_etmfw_select_ticket_background').val() || '';
            $('.wps_etmfw_ticket_body').css('background-color', wps_etmfw_background_color);
            $('#wps_etmfw_parent_wrapper_3').css('background-color', 'white');
        }

        $(document).on('change input', '.wps_etmfw_select_ticket_background', wps_etmfw_apply_background_color);
        wps_etmfw_apply_background_color();
        //Dynamic Changes for background end Here.


        //Dynamic Changes for header background color start Here.
        function wps_etmfw_apply_header_background_color() {
            var wps_etmfw_header_background_color = $('.wps_etmfw_select_ticket_header_background').val() || '';
            $('.ticket-header').css('background-color', wps_etmfw_header_background_color);
        }

        $(document).on('change input', '.wps_etmfw_select_ticket_header_background', wps_etmfw_apply_header_background_color);
        wps_etmfw_apply_header_background_color();
        //Dynamic Changes for header background color end Here.


        //Dynamic Changes for logo size start Here.
        function wps_etmfw_sync_size_slider($input, valueSelector) {
            var value = parseInt($input.val(), 10) || 0;
            var min = parseInt($input.attr('min'), 10) || 0;
            var max = parseInt($input.attr('max'), 10) || 100;
            var progress = 0;

            if ( max > min ) {
                progress = ((value - min) / (max - min)) * 100;
            }

            $input.closest('.wps-etmfw-size-slider').css('--etmfw-slider-progress', progress + '%');
            $(valueSelector).html(value + 'px');
            return value;
        }

        $('.wps_etmfw_logo_size_slider').on('input change', function() {
            var value = wps_etmfw_sync_size_slider($(this), '.wps_etmfw_logo_size_slider_span');
            var yourImg = document.getElementById('wps_wem_logo_id');
            if (yourImg && yourImg.style) {
                yourImg.style.width = value + 'px';
            }
        }).each(function() {
            wps_etmfw_sync_size_slider($(this), '.wps_etmfw_logo_size_slider_span');
        });
        //Dynamic Changes for logo end Here.

        //Dyanmic chnages for the QR Image start here.
        $('.wps_etmfw_qr_size_slider').on('input change', function() {
            var value = wps_etmfw_sync_size_slider($(this), '.wps_etmfw_qr_size_slider_span');
            var yourImg = document.getElementById('wps_qr_image');
            if (yourImg && yourImg.style) {
                yourImg.style.width = value + 'px';
                yourImg.style.height = value + 'px';
            }
        }).each(function() {
            wps_etmfw_sync_size_slider($(this), '.wps_etmfw_qr_size_slider_span');
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

        function wps_checking_for_pro(){
            var wps_event_pro_is_enable = etmfw_admin_param.is_pro_active;
            if(1 != wps_event_pro_is_enable){
            var element = document.getElementById("wps_etmfw_is_for_pro");
            element.classList.add("wps_etmfw_class_for_pro");
            $('#wps_etmfw_new_layout_setting_save_3').hide();
            }else{
            var element = document.getElementById("wps_etmfw_is_for_pro");
            //    element.classList.remove("wps_etmfw_class_for_pro");
            }
        }

        wps_checking_for_pro();

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
		wps_etmfw_apply_border_styling();
	});

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
           
            // document.getElementById('wps_event_border_type').style.border = border_size + ' ' + border_type + ' ' + border_color;
			document.getElementById('wps_etmfw_parent_wrapper').style.border = border_size + ' ' + border_type + ' ' + border_color;
            document.getElementById('wps_etmfw_parent_wrapper_2').style.border = border_size + ' ' + border_type + ' ' + border_color;
            document.getElementById('wps_new_template_border').style.border = border_size + ' ' + border_type + ' ' + border_color;

		}
        function wps_etmfw_hide_bck_ground_image_setting(){
            if('3'== etmfw_admin_param.wps_etmfw_selected_template || '4' == etmfw_admin_param.wps_etmfw_selected_template || '6' == etmfw_admin_param.wps_etmfw_selected_template || '8' == etmfw_admin_param.wps_etmfw_selected_template){
                $('.wps_etmfw_hide_setting').show();
            }else{
                $('.wps_etmfw_hide_setting').hide();
            }
        }
})(jQuery);
