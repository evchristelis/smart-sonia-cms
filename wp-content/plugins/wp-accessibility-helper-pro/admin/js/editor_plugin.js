var shortcode_template;
var popup_template;

(function() {
    tinymce.PluginManager.add('wah_pro_mce_button', function(editor, url) {
        //WAH Pro widgets list
        editor.addButton('wah_pro_mce_button', {
            icon : 'icon dashicons-universal-access-alt',
            text : 'WAH Widgets',
            tooltip : 'WAH Widgets',
            onclick : function() {
                editor.windowManager.open({
                    title : 'WAH Pro Widgets',
                    body : [
                        {
                            type    : 'textbox',
                            name    : 'wah_widget_title',
                            label   : 'Widget title',
                            classes : 'wahpro_widget_title_class',
                            value   : ''
                        },
                        {
                            type    : 'textbox',
                            name    : 'wah_widget_class',
                            label   : 'Widget class',
                            classes : 'wahpro_widget_class',
                            value   : ''
                        },
                        {
                            type      : 'listbox',
                            name      : 'wah_widget_type',
                            label     : 'Widget type',
                            onselect: function (e) {
                                if( this.value() == 'wahpro_accessibility_bar' ){
                                    jQuery(".mce-wahpro_widget_title_class").attr("disabled", true);
                                    jQuery(".mce-wahpro_widget_title_class").addClass("disabled");
                                } else {
                                    jQuery(".mce-wahpro_widget_title_class").attr("disabled", false);
                                    jQuery(".mce-wahpro_widget_title_class").removeClass("disabled");
                                }
                            },
                            values    : [
                                {
                                    text  : 'Font Resize',
                                    value : 'wah_font_resize'
                                },
                                {
                                    text  : 'Disable Animations',
                                    value : 'wah_disable_animations'
                                },
                                {
                                    text  : 'Underline Links',
                                    value : 'wah_underline_links'
                                },
                                {
                                    text  : 'Images Greyscale',
                                    value : 'wah_images_greyscale'
                                },
                                // {
                                //     text  : 'Vertical accessibility bar',
                                //     value : 'wah_wahpro_accessibility_bar'
                                // },
                                {
                                    text  : 'Readable fonts',
                                    value : 'wah_readable_fonts'
                                },
                                {
                                    text  : 'Invert Colors',
                                    value : 'wah_invert_colors'
                                },
                                {
                                    text  : 'Highlight Links',
                                    value : 'wah_highlight_links'
                                }
                            ]
                        },
                    ],
                    onsubmit: function(e) {
                        if( e.data.wah_widget_type == 'wahpro_accessibility_bar' ) {
                            shortcode_template = '[wah_pro_widget class="' + e.data.wah_widget_class + '" type="' + e.data.wah_widget_type +'"]';
                        } else {
                            shortcode_template = '[wah_pro_widget class="' + e.data.wah_widget_class + '" title="' + e.data.wah_widget_title + '" type="' + e.data.wah_widget_type +'"]';
                        }
                        editor.insertContent(shortcode_template);
                    }
                });
            }
        });
        // WAH Pro modal windows popup
        editor.addButton('wah_pro_popup', {
            icon : 'icon dashicons-universal-access-alt',
            text : 'WAH Popup',
            tooltip : 'WAH Popup',
            onclick : function() {
                editor.windowManager.open({
                    title : 'WAH Pro Assessible Modal window',
                    body : [
                        {
                            type    : 'textbox',
                            name    : 'wahpopup_id',
                            label   : 'Popup ID [unique ID text or number]',
                            classes : 'wahpopup_id',
                            value   : ''
                        },
                        {
                            type    : 'textbox',
                            name    : 'wah_popup_trigger',
                            label   : 'Popup trigger title',
                            classes : 'wah_popup_trigger_title',
                            value   : ''
                        },
                        {
                            type    : 'textbox',
                            name    : 'wah_popup_title',
                            label   : 'Popup content title',
                            classes : 'wahpro_widget_class',
                            value   : ''
                        },
                        {
                            type      : 'textbox',
                            multiline : true,
                            minWidth  : 600,
                            minHeight : 340,
                            name      : 'wah_popup_content',
                            label     : 'Popup content (Contact Form 7 support)',
                            classes   : 'wahpro_widget_class',
                            value     : ''
                        },
                        {
                            type    : 'textbox',
                            name    : 'wah_popup_close_title',
                            label   : 'Close button title',
                            classes : 'wahpro_widget_class',
                            value   : ''
                        },
                        {
                            type    : 'textbox',
                            name    : 'wah_popup_close_label',
                            label   : 'Close button [aria-label]',
                            classes : 'wahpro_widget_class',
                            value   : ''
                        },
                        {
                            type    : 'textbox',
                            name    : 'wah_popup_width',
                            label   : 'Popup max-width (px)',
                            classes : 'wahpro_widget_class',
                            value   : '600px',
                            tooltip : 'Size in px'
                        },
                    ],
                    onsubmit: function(e) {

                        var content_string  = e.data.wah_popup_content;
                        var wah_popup_id    = e.data.wahpopup_id;

                        if( wah_popup_id && wah_popup_id !=' ' ) {

                            wah_popup_id = wah_popup_id.split(' ').join('-');

                            popup_template = '[wah_pro_popup ' +
                            'wahpopup_trigger="' + e.data.wah_popup_trigger +
                            '" wahpopup_title="' + e.data.wah_popup_title +
                            // '" wahpopup_content="' + content_string +
                            '" wahpopup_close_title="' + e.data.wah_popup_close_title +
                            '" wahpopup_close_label="' + e.data.wah_popup_close_label +
                            '" wahpopup_width="' + e.data.wah_popup_width +
                            '" wahpopup_id="wah-popup-' + wah_popup_id + '"]' +
                            content_string +
                            ' [/wah_pro_popup]';

                            editor.insertContent(popup_template);
                        } else {
                            alert("Popup ID is required");
                            return false;
                        }

                    }
                });
            }
        });
    });
})();

/**********************************
    WAH PRO Accordion
***********************************/

jQuery(document).ready(function(){

});
// Open popup window
jQuery(document).on('click', '#insert-wah-accodrion', function(){
    open_wah_popup_window( title = 'WAH PRO Accordion' );
});
// Close popup window
jQuery(document).on('click', '.close-wah-popup', function(){
    close_wah_popup_window();
});
// Delete popup item
jQuery(document).on('click', '.wah-accordion-item-actions button.delete-item', function(e){
    e.preventDefault();
    var index = jQuery(this).data('index');
    remove_wah_accordion_item( index );
    recalculate_wah_item_index();
});
// Add popup item
jQuery(document).on("click", ".wah-add-accodrion-item", function(){
    var counter = jQuery(".wah-accordion-item").length;
    add_wah_accordion_item( counter );
});
// Open popup settings
jQuery(document).on("click", ".wah-popup-settings", function(){
    jQuery('.wah-popup-settings-wrapper').toggleClass('active');
});
// Generate and print shortcode
jQuery(document).on("click", ".get-shortcode-code", function(){
    var accordion_form = jQuery('.wah-accordion-shortcode-generator').serialize();
    if( accordion_form ) {
        generate_accordion_shortcode(accordion_form);
    }
});

function generate_accordion_shortcode(accordion_form){
    jQuery.ajax({
       type       : "post",
       dataType   : "json",
       url        : ajaxurl,
       data       : {
           action : "generate_accordion_shortcode",
           form   : accordion_form
       },
       success: function(response) {
           if( typeof response.html != 'undefined' && response.html ) {

               if( jQuery('#shortcode-response-wrapper').length ) {
                   jQuery("#shortcode-response-wrapper").remove();
               }
               jQuery('.wah-popup-footer')
                .append('<div class="shortcode-response" id="shortcode-response-wrapper"><textarea>'+response.html+'</textarea></div>');
                jQuery('.wah-popup-inner').animate({
                    scrollTop: jQuery( "#shortcode-response-wrapper" ).offset().top},
                    'slow');
           }
       }
    });
}

function open_wah_popup_window( title ) {

    var wah_popup_template = '<div class="wah-popup-wrapper">'+
        '<div class="wah-popup-inner">'+
            '<div class="wah-popup-header">'+title+'<button class="button wah-popup-settings">Settings</button></div>'+
            '<div class="wah-popup-body">'+
                '<form class="wah-accordion-shortcode-generator">'+
                    '<div class="wah-popup-settings-wrapper">'+
                        '<div class="wah-settings-item">'+
                            '<span>Active background color:</span> <input name="wah-acc-bg-active" type="color" value="#2e829c" />'+
                        '</div>'+
                        '<div class="wah-settings-item">'+
                            '<span>Default background color:</span> <input name="wah-acc-bg-default" type="color" value="#236478" />'+
                        '</div>'+
                        '<div class="wah-settings-item">'+
                            '<span>Active text color:</span> <input name="wah-acc-text-active" type="color" value="#FFFFFF" />'+
                        '</div>'+
                        '<div class="wah-settings-item">'+
                            '<span>Default text color:</span> <input name="wah-acc-text-default" type="color" value="#FFFFFF" />'+
                        '</div>'+
                        '<div class="wah-settings-item is-wah-checkbox">'+
                            '<label><input name="wah-acc-animations" type="checkbox" /> <span>Enable animations:</span></label>'+
                        '</div>'+
                        '<div class="wah-settings-item is-wah-checkbox">'+
                            '<label><input name="wah-acc-first-is-active" type="checkbox" /><span>First acordion item active?</span></label>'+
                        '</div>'+
                    '</div>'+
                    '<div class="wah-accordion-fields" id="wah-sortable-widgets"></div>'+
                '</form>'+
                '<button type="button" class="button wah-add-accodrion-item">Add item</button>'+
            '</div>'+
            '<div class="wah-popup-footer">'+
                '<div class="column column-left">WP Accessibility Helper PRO</div>'+
                '<div class="column column-right">'+
                '<button type="button" class="button get-shortcode-code">Get shortcode</button>'+
                '<button type="button" class="button button-primary close-wah-popup">Close</button>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</div>';

    jQuery('body').append( wah_popup_template );

}

function update_sortable_list(){
    jQuery('.wah-accordion-fields').sortable({
        placeholder: "ui-state-highlight",
        update: function( event, ui ) {
            recalculate_wah_item_index();
        }
    });
    jQuery('.wah-accordion-fields').disableSelection();
}

function add_wah_accordion_item( counter ){

    if( typeof counter == 'undefined' ) {
        counter = 1;
    }

    counter = counter++;

    var wah_accordion_item = '<div class="wah-accordion-item" data-index="'+counter+'">'+
        '<div class="wah-order-counter"><span class="number">'+counter+'</span></div>'+
        '<div class="wah-item-container">'+
            '<div class="wah-accordion-item-title"><input type="text" placeholder="Accordion item title" name="item-'+counter+'[wah-acc-title]" /></div>'+
            '<div class="wah-accordion-item-content"><textarea id="item-textarea-'+counter+'" name="item-'+counter+'[wah-acc-content]" placeholder="Accordion item content" /></textarea></div>'+
            '<div class="wah-accordion-item-actions"><button class="button delete delete-item" data-index="'+counter+'">Delete</button></div>'+
        '</div>'+
    '</div>';

    jQuery(".wah-accordion-fields").append( wah_accordion_item );

    // enable tinyMCE
    // if ( typeof( tinyMCE ) == "object" && typeof( tinyMCE.execCommand ) == "function" ) {
    //     tinyMCE.execCommand("mceAddEditor", false, 'item-textarea-'+counter);
    // }

    update_sortable_list();

}

function remove_wah_accordion_item( index ) {
    jQuery('.wah-accordion-item[data-index="'+index+'"]').fadeOut().remove();
}

function close_wah_popup_window(){
    jQuery(".wah-popup-wrapper").fadeOut(250, function(){
        jQuery(this).remove();
    });
}

function recalculate_wah_item_index() {
    var index_counter = 0;
    jQuery('.wah-accordion-item').each( function(){
        jQuery(this).attr('data-index', index_counter);
        jQuery(this).find('.number').html( index_counter );
        jQuery(this).find('.wah-accordion-item-actions .delete-item').attr('data-index', index_counter);
        index_counter++;
    });
}
