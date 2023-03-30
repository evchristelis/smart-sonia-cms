var AjaxURL = wpb_admin_js.wpb_ajaxurl;
var wpb_ajax_nonce = wpb_admin_js.wpb_ajax_nonce;

// define the variables for ajax response
var
        aj_popup_options,
        aj_image_path,
        aj_image_link,
        aj_target,
        aj_text_image_path,
        aj_background_color,
        aj_content,
        aj_overlay_type,
        aj_popup_type,
        aj_border_radius,
        aj_height,
        aj_width,
        aj_close_button,
        aj_overlay_color,
        transition,
        aj_background_repeat,
        aj_border,
        aj_border_size,
        aj_border_color,
        aj_main_padding,
        aj_show_countdown_message,
        background_repeat,
        border,
        show_countdown_message,
        border_size,
        border_color,
        main_padding,
        aj_wpb_popup_delay,
        wpb_popup_delay,
        aj_wpb_close_countdown,
        aj_close_countdown_msg,
        wpb_close_countdown,
        wpb_autoclose_enable,
        aj_autoclose_enable,
        close_countdown_msg,
        interval;

(function ($) {
    $(function () {
        $('.main-nav').click(function () {
            var tab_class = $('.main-nav');
            tab_class.removeClass('nav-tab-active');
            var id = $(this).attr('id');
            $(this).addClass('nav-tab-active');
            $('.wpb_tabs_content').hide();
            $('.' + id).show();
        });

        $('.second-nav').click(function () {
            var tab_class = $('.second-nav');
            tab_class.removeClass('nav-tab-active');
            var id = $(this).attr('id');
            $(this).addClass('nav-tab-active');
            if (id == 'wpb-general-settings') {
                $('.wpb-display-settings').hide();
                $('.wpb-general-settings').show();
            } else {
                $('.wpb-display-settings').show();
                $('.wpb-general-settings').hide();
            }
        });

        $('#wpb_popup_delay_enable').click(function () {
            if (this.checked) {
                $('.wpb-popup-delay-div').show();
            } else {
                $('.wpb-popup-delay-div').hide();
            }
        });

        $('#wpb_autoclose_enable').click(function () {
            if (this.checked) {
                $('.wpb-enable-autoclose').show();
            } else {
                $('.wpb-enable-autoclose').hide();
            }
        });

        $('#wpb-countdown-msg').change(function () {
            // alert($(this).val());
            if ($(this).val() == 'yes') {
                $('.wpb-countdown-message-div').show();
            } else {
                $('.wpb-countdown-message-div').hide();
            }
        });


        $('.upload_image_button').click(function (e) {
            e.preventDefault();

            formfieldID = jQuery(this).prev().attr("id");
            formfield = jQuery("#" + formfieldID).attr('name');
            var image = wp.media({
                title: 'Upload Image',
                multiple: true
            }).open()
                    .on('select', function (e) {
                        var uploaded_image = image.state().get('selection').first();
                        var image_url = uploaded_image.toJSON().url;
                        jQuery("#" + formfieldID).val(image_url);
                        tb_remove();
                    });
        });
        function get_tinymce_content() {
            if (jQuery("#wp-wpb-popup-content-wrap").hasClass("tmce-active")) {
                return tinyMCE.activeEditor.getContent();
            } else {
                return jQuery('#html_text_area_id').val();
            }
        }

        $('#wpb_background_color').wpColorPicker();
        $('#wpb_overlay_color').wpColorPicker();
        $('#wpb_border_color').wpColorPicker();
        $('#wpb_countdown_color').wpColorPicker();



        var background_type = 'none';
        var background_image_url, popup_background_color, wpb_textarea_content, close_y_n, target, link;
        background_type = $('#select_option option:selected').val();

        $('#select_option').on('change', function () {
            if (this.value == 'image') {
                background_type = "image";
                $('.wpb-text-type').hide();
                $('.wpb-image-type').show();
            } else if (this.value == 'text') {
                background_type = "text";
                $('.wpb-image-type').hide();
                $('.wpb-text-type').show();
            } else {
                background_type = 'none';
                $('.wpb-option-field').hide();
            }
        });//select option on change end

        $('.wpb_close_btn, #wpb_overlay').click(function () {
            hide(transition);
        });

        $('#wpb_display_on').change(function () {
            if ($(this).val() == 3) {
                $('.wpb-page-list').show();
            } else {
                $('.wpb-page-list').hide();
            }
        });

        $('#wpb_border').change(function () {
            if ($(this).val() == 'yes') {
                $('.wpb-popup-border').show();
            } else {
                $('.wpb-popup-border').hide();
            }
        });

        $('.wpb-background-type').click(function () {
            var type = $(this).val();
            if (type == 'image') {
                $('div[data-background-ref="color"]').hide();
                $('div[data-background-ref="image"]').show();
            } else {
                $('div[data-background-ref="color"]').show();
                $('div[data-background-ref="image"]').hide();

            }
        });

        function countdown(counter) {
            interval = setInterval(function () {
                $('.wpb_countdown').text(counter);
                if (counter == 0) {
                    clearInterval(interval);
                    hide();
                }
                counter--;
            }, 1000);
        }


    });
}(jQuery));//main function end

function handleChange(input) {
    var value = jQuery(input).val();
    var newValue = value.replace(/\D/g, '');
    newValue = parseInt(newValue);
    jQuery(input).val(newValue);

    value = jQuery(input).val();

    if (isNaN(value) || value == "") {
        jQuery(input).val('');
    }
    if (input.value < 0)
        input.value = 0;
    if (input.value > 300)
        input.value = 300;
}

