jQuery(document).ready(function($) {

    init_admin_gdpr_popup_preview();

    if( jQuery('.wah-gdpr-about-toggle').length ){
        jQuery('.wah-gdpr-about-toggle .toggle-about-gdpr').click( function(e){
            e.preventDefault();
            jQuery('.wah-gdpr-content-inner').slideToggle(250);
        });
    }

    if( jQuery('#wah-pro-import-export').length ){
        init_import_export_form();
    }

    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({
            title: 'Upload Logo',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            var uploaded_image = image.state().get('selection').first();
            var image_url = uploaded_image.toJSON().url;
            $('#image_url').val(image_url);
        });
    });
    $("#clear-btn").click(function(e){
        e.preventDefault();
        $('#image_url').val('');
    });

    // Logo preview
    jQuery(document).on('change', 'input[name="wah_logo_bg"]', function(){
        var new_color = jQuery(this).val();
        jQuery('.wah-preview-inner .aicon_link').css('background-color', new_color );
    });
    jQuery(document).on('change', 'input[name="wah_logo_color"]', function(){
        var new_color = jQuery(this).val();
        jQuery('.wah-preview-inner .aicon_link span.wah-font-icon').css("cssText", "color: "+new_color+" !important;");
    });

    // Close button preview
    jQuery(document).on('change', 'input[name="wah_close_btn_bg"]', function(){
        var new_color = jQuery(this).val();
        jQuery('.wah-close-btn-preview-inner .aicon_link').css('background-color', new_color );
    });
    jQuery(document).on('change', 'input[name="wah_close_btn_color"]', function(){
        var new_color = jQuery(this).val();
        jQuery('.wah-close-btn-preview-inner .aicon_link').css("cssText", "color: "+new_color+" !important;");
    });

    jQuery("body").on("focusin",".switch-input", function(){
        jQuery(this).parents(".switch").addClass("focusin");
    });
    jQuery("body").on("focusout",".switch-input", function(){
        jQuery(this).parents(".switch").removeClass("focusin");
    });

    //Toggle admin section WAH Admin
    jQuery(".form_element_header").click(function(e){
        e.preventDefault();
        var this_el     = jQuery(this);
        var toggle_span = jQuery(this).find("span.toggle-wah-section span.dashicons");
        if(toggle_span.hasClass('dashicons-arrow-down-alt2')){
            toggle_span.removeClass("dashicons-arrow-down-alt2");
            toggle_span.addClass('dashicons-arrow-up-alt2');
            this_el.next(".wah_form_elements_wrapper").slideUp(200);
        } else {
            toggle_span.removeClass("dashicons-arrow-up-alt2");
            toggle_span.addClass('dashicons-arrow-down-alt2');
            this_el.next(".wah_form_elements_wrapper").slideDown(200);
        }
    });
    //Add new contrast item
    add_new_contrast_item();
    //Save contrast variations
    save_contrast_variations();
    //Validate on custom contrast mode
    jQuery("#wah_enable_custom_contrast").change(function(){
        if( !jQuery(this).is(":checked") ) {
            jQuery("#contrast_custom_dep").fadeOut();
        } else {
            jQuery("#contrast_custom_dep").fadeIn();
        }
    });
    //Update Attachments title
    jQuery(".attachment_post_title").change(function(){
        var pid    = jQuery(this).parents('tr').data('item');
        var ptitle = jQuery(this).val();
        var data = {
            action: 'update_attachment_title',
            pid:    pid,
            ptitle: ptitle,
        };
        jQuery.post(ajaxurl, data, function(response) {
            var results = jQuery.parseJSON(response);
            if(results){
              jQuery('tr[data-item='+pid+'] input.attachment_post_title').effect( "highlight", {color:"#06924B"}, 1000 );
            }
        });
    });
    //Update Attachments alt
    jQuery(".attachment_post_alt").change(function(){
       var pid    = jQuery(this).parents('tr').data('item');
       var palt   = jQuery(this).val();
       var data = {
           action: 'update_attachment_alt',
           pid:    pid,
           palt: palt,
       };
       jQuery.post(ajaxurl, data, function(response) {
           var results = jQuery.parseJSON(response);
           if(results){
             if(!results.palt) {
               jQuery('tr[data-item='+pid+'] input.attachment_post_alt').attr("placeholder", "no alt tag");
             }
             jQuery('tr[data-item='+pid+'] input.attachment_post_alt').effect( "highlight", {color:"#06924B"}, 1000 );
           }
       });
    });
    //WAH SCANNER
    jQuery("#wah_scanner").click(function(e){
        e.preventDefault();
        var postID = jQuery("#wah_scanner_selector").val();
        if(postID){
            jQuery("#fountainG").fadeIn(200);
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : ajaxurl,
                data : { action: "wah_scan_homepage", postID : postID },
                success: function(response) {
                    if(response.response_code == 200){
                        jQuery("#wah_scanner_results").html('');
                        if(response.images){
                           jQuery("#wah_scanner_results").append(response.images);
                        }
                        if(response.links){
                           jQuery("#wah_scanner_results").append(response.links);
                        }
                        jQuery("#fountainG").fadeOut(200);
                    } else {
                        alert("Error. Response code: "+response.response_code);
                    }
                },
                error: function(response){
                    jQuery("#fountainG").fadeOut(200);
                    jQuery("#wah_scanner_results").html('');
                    alert("Error. Please try again...");
                }
            });
        } else {
            alert("Please select page");
        }
    });
    jQuery("body").on("click",".wah_scanner_table_trigger",function(event){
        event.preventDefault();
        jQuery(this).toggleClass("active");
        jQuery(this).next(".wah_scanner_table").slideToggle(300);
    });
    //Save wah widgets order
    jQuery( "#sortable-wah-widget" ).sortable({
        placeholder: "ui-state-highlight",
        update: function( event, ui ) {
            jQuery("#fountainG").fadeIn(50);
            var neworder = [];
            jQuery('#sortable-wah-widget li').each(function() {
                var id  = jQuery(this).attr("id");
                var obj = {};
                obj.id  = id;
                neworder.push(obj.id);
            });
            if(neworder){
                jQuery.ajax({
                    type        : "post",
                    dataType    : "json",
                    url         : ajaxurl,
                    data        : {
                        action  : "wah_update_widgets_order",
                        alldata : neworder
                    },
                    success: function(response) {
                        if(response == 'ok'){
                            jQuery("#fountainG").fadeOut(350);
                        }
                    },
                    error: function(response){
                        jQuery("#fountainG").fadeOut(350);
                        alert("Error. Please try again...");
                    }
                });
            }
        }
    });
    jQuery( "#sortable" ).disableSelection();

    //remove custom contrast from repeater
    jQuery("body").on("click","button.wah-button.delete-contrast-params",function(e){
        e.preventDefault();

        jQuery(this).parents("li").addClass("toDelete");
        jQuery(this).parents("li").find(".action-loader").fadeIn(50);
        jQuery.ajax({
            type        : "post",
            dataType    : "json",
            url         : ajaxurl,
            data        : {
                action  : "remove_contrast_item"
            },
            success: function(response) {
                if(response.status == 'ok'){
                    jQuery(".contrast-params-list").find("li.toDelete").fadeOut(250, function(){
                        jQuery(this).remove();
                    });
                }
            }
        });
    });

    //Check title inputs dependencies
    jQuery('[data-depid]').each(function(){
        var depid = jQuery(this).data("depid");
        var depid_checkbox = jQuery("input#"+depid);
        if( !depid_checkbox.is(":checked") ) {
            jQuery(this).fadeOut();
        } else {
            jQuery(this).fadeIn();
        }
    });
    jQuery(".switch-input").change(function(){
        var depid = jQuery(this).attr("id");
        if( !jQuery(this).is(":checked") ) {
            jQuery('[data-depid='+depid+']').fadeOut();
        } else {
            jQuery('[data-depid='+depid+']').fadeIn();
        }
    });

    /**************************
        WAH PRO
    **************************/
    jQuery(".license_key_trigger").click(function(e){
        e.preventDefault();
        jQuery(".license_key_wrapper").slideToggle(200, function(){
            jQuery( ".wah_license_email" ).focus();
        });
    });
    jQuery(".wah_pro_license-validate").submit(function(e){
        e.preventDefault();
        jQuery(".wah_license_key").removeClass("error");
        if( jQuery(".wah_license_email").val() && jQuery(".wah_license_key").val() ) {
            var form_data = jQuery( this ).serialize();
            wah_pro_validate_license_key( form_data );
            jQuery('.wah_admin_header_license_key .license_key_wrapper .license-form-fields button[type="submit"] span.ajax-loader').fadeTo(250,1);
        } else {
            jQuery(".wah_license_key").addClass("error");
        }
    });

    //Reset wah widgets order
    jQuery( "#wah-reset-widgets-order" ).click( function(){
        jQuery.ajax({
            type        : "post",
            dataType    : "json",
            url         : ajaxurl,
            data        : {
                action  : "wah_reset_widgets_order"
            },
            success: function(response) {
                if(response.status == 'ok'){
                    alert(response.message);
                    window.location.reload();
                }
            }
        });
    });

});

if( jQuery('select[name="wah_gdpr_theme"]').length ){
    jQuery(document).on('change', 'select[name="wah_gdpr_theme"]', function(){
        if( jQuery(this).val() != 'custom' ){
            jQuery('.if_wah_gdpr_theme_custom').fadeOut();
        } else {
            jQuery('.if_wah_gdpr_theme_custom').fadeIn();
            init_admin_gdpr_popup_preview();
        }
    });
}

function init_admin_gdpr_popup_preview(){

    if( jQuery('select[name="wah_gdpr_theme"]').length && jQuery('select[name="wah_gdpr_theme"]').val() == 'custom' ){

        jQuery('.if_wah_gdpr_theme_custom').fadeIn(250);

        jQuery('.if_wah_gdpr_theme_custom input[type="color"]').each( function(){
            var color      = jQuery(this).val();
            var input_name = jQuery(this).attr('name');
            var style_type = jQuery(this).attr('data-style');
            if( style_type == 'bg' ){
                jQuery('.wah_gdpr_theme_preview [data-target="'+input_name+'"]').css('background-color', color);
            } else {
                jQuery('.wah_gdpr_theme_preview [data-target="'+input_name+'"]').css('color', color);
            }
        });

        jQuery('.if_wah_gdpr_theme_custom .form_row input[type="color"]').on('input', function(){
            var color      = jQuery(this).val();
            var input_name = jQuery(this).attr('name');
            var style_type = jQuery(this).attr('data-style');

            console.log('color: ', color);
            console.log('input_name: ', input_name);
            console.log('style_type: ', style_type);

            if( style_type == 'bg' ){
                jQuery('.wah_gdpr_theme_preview [data-target="'+input_name+'"]').css('background-color', color);
            } else {
                jQuery('.wah_gdpr_theme_preview [data-target="'+input_name+'"]').css('color', color);
            }
        });
    }

}

//Add new contrast item
function add_new_contrast_item(){
    jQuery("button.wah-button.wah-add-item").click(function(e){
        e.preventDefault();
        var total_contrast_elements = jQuery('.contrast-params-list li').size() + 1;
        jQuery(".wah-contrast-loader").fadeTo(100,1);
        jQuery.ajax({
            type        : "post",
            dataType    : "json",
            url         : ajaxurl,
            data        : {
                action  : "add_new_contrast_item"
            },
            success: function(response) {
                if(response.status == 'ok' && response.html){
                    jQuery("ul.contrast-params-list").append(response.html);
                    jscolor.installByClassName("jscolor");
                    jQuery(".wah-contrast-loader").fadeTo(100,0);
                }
            },
            error: function(response){
            }
        });

    });
}
//Save contrast variations
function save_contrast_variations(){
    jQuery("body").on("click","button.save-contrast-params",function(e){
        e.preventDefault();
        var contrast_variations = [];
        if( jQuery('ul.contrast-params-list li').length ){
            jQuery('ul.contrast-params-list li').each(function() {
                var target_element = jQuery(this).find("input");
                if(!target_element.val() || target_element.val() === ' ') {
                    alert("Fill all fields or delete unnecessary fields please.");
                } else {
                    jQuery(".wah-contrast-loader").fadeTo(100,1);
                    var bgcolor   = jQuery(this).find(".bg-color input").val();
                    var textcolor = jQuery(this).find(".text-color input").val();
                    var button_title = jQuery(this).find(".button-title-alt input").val();
                    var obj       = {};
                    obj.bgcolor   = {"bgcolor": bgcolor, "textcolor": textcolor, "title": button_title};
                    contrast_variations.push(obj.bgcolor);
                }
            });
            if(contrast_variations){
                jQuery.ajax({
                    type        : "post",
                    dataType    : "json",
                    url         : ajaxurl,
                    data        : {
                        action  : "save_contrast_variations",
                        alldata : contrast_variations
                    },
                    success: function(response) {
                        if(response.status == 'ok'){
                            jQuery(".wah-contrast-loader").fadeTo(100,0);
                        }
                        if(response.status == 'error'){
                            alert(response.message);
                        }
                    },
                    error: function(response){
                    }
                });
            }
        } else {
            jQuery(".action-message").fadeTo(250,1);
            jQuery.ajax({
                type        : "post",
                dataType    : "json",
                url         : ajaxurl,
                data        : {
                    action  : "save_empty_contrast_variations"
                },
                success: function(response) {
                    if(response.status == 'ok'){
                        jQuery(".action-message").fadeTo(250,0);
                    }
                },
                error: function(response){
                }
            });
        }
    });
}

// WAH PRO update license key
function wah_pro_validate_license_key(form_data) {
    jQuery.ajax({
        type     : "post",
        dataType : "json",
        url      : ajaxurl,
        data     : {
            action    : "wah_pro_validate_license_key",
            form_data : form_data
        },
        success: function(response) {
            if( response.status == 'ok' ) {
                jQuery('.license_ajax_response').html(response.message);
            } else if ( response.status == 'error' ) {
                jQuery('.license_ajax_response').html(response.message);
                // Clear email and license fields
                jQuery('.license-form-fields input').val('');
            }
            jQuery('.wah_admin_header_license_key .license_key_wrapper .license-form-fields button[type="submit"] span.ajax-loader').fadeTo(250,0);
        }
    });
}

function init_import_export_form(){
    jQuery('select[name="wah_ie_action"]').on('change', function(){
        if( 'import' == jQuery(this).val() ){
            jQuery('#wah_ie_submit').val('Import');
            jQuery('.ie_file_selector').fadeIn(200);
        } else if ( 'export' == jQuery(this).val() ){
            jQuery('#wah_ie_submit').val('Export');
            jQuery('.ie_file_selector').fadeOut(200);
        } else {
            jQuery('#wah_ie_submit').val('Submit');
            jQuery('.ie_file_selector').fadeOut(200);
        }
    });
    jQuery('.wah-pro-import-export-form').on('submit', function(e){
        if( jQuery('select[name="wah_ie_action"]').val() == 'import' ){
            if( jQuery('#wah_ie_file').get(0).files.length === 0 ){
                e.preventDefault();
                alert('Please select JSON file');
            }
        }
        if( jQuery('select[name="wah_ie_action"]').val() == '' || ! jQuery('select[name="wah_ie_action"]').val() ){
            e.preventDefault();
            alert('Please select action');
        }
    });
}
