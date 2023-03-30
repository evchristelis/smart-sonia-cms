/************************
    JS Cookies
*************************/
;(function(factory){if(typeof define==='function'&&define.amd){define(factory);}else if(typeof exports==='object'){module.exports=factory();}else{var OldCookies=window.Cookies;var api=window.Cookies=factory();api.noConflict=function(){window.Cookies=OldCookies;return api;};}}(function(){function extend(){var i=0;var result={};for(;i<arguments.length;i++){var attributes=arguments[i];for(var key in attributes){result[key]=attributes[key];}}return result;}function init(converter){function api(key,value,attributes){var result;if(typeof document==='undefined'){return;}if(arguments.length>1){attributes=extend({path:'/'},api.defaults,attributes);if(typeof attributes.expires==='number'){var expires=new Date();expires.setMilliseconds(expires.getMilliseconds()+attributes.expires*864e+5);attributes.expires=expires;}try{result=JSON.stringify(value);if(/^[\{\[]/.test(result)){value=result;}}catch(e){}if(!converter.write){value=encodeURIComponent(String(value)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent);}else{value=converter.write(value,key);}key=encodeURIComponent(String(key));key=key.replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent);key=key.replace(/[\(\)]/g,escape);return(document.cookie=[key,'=',value,attributes.expires&&'; expires='+attributes.expires.toUTCString(),attributes.path&&'; path='+attributes.path,attributes.domain&&'; domain='+attributes.domain,attributes.secure?'; secure':''].join(''));}if(!key){result={};}var cookies=document.cookie?document.cookie.split('; '):[];var rdecode=/(%[0-9A-Z]{2})+/g;var i=0;for(;i<cookies.length;i++){var parts=cookies[i].split('=');var name=parts[0].replace(rdecode,decodeURIComponent);var cookie=parts.slice(1).join('=');if(cookie.charAt(0)==='"'){cookie=cookie.slice(1,-1);}try{cookie=converter.read?converter.read(cookie,name):converter(cookie,name)||cookie.replace(rdecode,decodeURIComponent);if(this.json){try{cookie=JSON.parse(cookie);}catch(e){}}if(key===name){result=cookie;break;}if(!key){result[name]=cookie;}}catch(e){}}return result;}api.set=api;api.get=function(key){return api(key);};api.getJSON=function(){return api.apply({json:true},[].slice.call(arguments));};api.defaults={};api.remove=function(key,attributes){api(key,'',extend(attributes,{expires:-1}));};api.withConverter=init;return api;}return init(function(){});}));
/************************
    JS Cookies END
*************************/
var wahCurrentMousePos      = { x: -1, y: -1 };
var wahpro_resize_clicks     = wahpro_get_resize_clicks();
var wahpro_magic_buttons_top = jQuery('body').hasClass('admin-bar') ? 80 : 60;
var resizable_elements       = jQuery("a,p,span,ul,ol,h1,h2,h3,h4,h5,h6");
var wahpro_cookies           = wahpro_settings.wahpro_cookies ? parseInt( wahpro_settings.wahpro_cookies ) : 14;
var wahpro_gdpr_cookies           = wahpro_settings.wahpro_gdpr_cookies ? parseInt( wahpro_settings.wahpro_gdpr_cookies ) : 30;
if( typeof wahpro_contrast_elements == 'undefined' && ! wahpro_contrast_elements ){
    var wahpro_contrast_elements    = 'button:not(.wahcolor),body,header,footer,#colophon,main,section,.section,h1,h2,h3,h4,h5,h6,p,#page';
}

jQuery(window).load(function(){
    jQuery(".aicon_link").fadeIn(350);
});

// text to speech
if( typeof wahpro_settings.wah_enable_web_speech !='undefined' && wahpro_settings.wah_enable_web_speech !=0 ){
    jQuery('body').on('mouseup', function(){
        var wah_speech       = window.speechSynthesis;
        var text             = window.getSelection().toString();
        var msg              = new SpeechSynthesisUtterance(text);
        // var message_language = document.documentElement.lang;
        // msg.lang             = message_language;

        if( text && wah_speech && msg ){
            // cancel previous selection speaking
            window.speechSynthesis.cancel();
            window.speechSynthesis.speak(msg);
        }
    });
}

jQuery(document).ready(function(){

    jQuery('#wahpro-accessibility-statement').on('click', function(e){
        e.preventDefault();
        jQuery(this).attr('aria-expanded', 'true');
        jQuery('.wahpro-accessibility-statement-popup').addClass('active').attr('aria-hidden', 'false').attr('tabindex', '0');
        jQuery('#wahpro-close-statement-popup').focus();
        jQuery('.wah-nicescroll-box .wahpro-accessibility-statement-content').niceScroll({
          cursorcolor        : "#18a1b7",
          cursorwidth        : "5px",
          cursorborderradius : "5px"
        });
    });
    jQuery('#wahpro-close-statement-popup').on('click', function(e){
        e.preventDefault();
        jQuery('.wahpro-accessibility-statement-popup').removeClass('active').attr('aria-hidden', 'true').attr('tabindex', '1');
        jQuery('#wahpro-accessibility-statement').attr('aria-expanded', 'false');
    });

    init_report_problem();

    wahpro_log();

    load_wah_cookies();

    var $body = jQuery("body"),
    $body_link = jQuery("body a");

    var currFFZoom = 1;
    var currIEZoom = 100;

    // Accept WAH GDPR
    if( jQuery('.accept-wah-gdpr-popup').length ){
        jQuery('.accept-wah-gdpr-popup').on('click', function(e){
            e.preventDefault();
            Cookies.set( 'wahgdpr', 'on', { expires: wahpro_gdpr_cookies } );
            jQuery('#wah-gdpr-popup').fadeOut(300, function(){
                jQuery(this).remove();
            });
        });
    }
    // Cancel WAH GDPR
    if( jQuery('.close-wah-gdpr-popup').length ){
        jQuery('.close-wah-gdpr-popup').on('click', function(e){
            e.preventDefault();
            jQuery('#wah-gdpr-popup').fadeOut(300, function(){
                jQuery(this).remove();
            });
        });
    }
    // wah gdpr - remove gdrp popup cache fix
    if( typeof Cookies.get('wahgdpr') !='undefined' && Cookies.get('wahgdpr') =='on' ){
        if(jQuery('#wah-gdpr-popup').length){
            jQuery('#wah-gdpr-popup').remove();
        }
    } else {
        if(jQuery('#wah-gdpr-popup').length){
            jQuery('#wah-gdpr-popup').css('display', 'block');
        }
    }

    //Accessibility
    if( jQuery('body').hasClass('ie') ){
        console.log('IE browser detected');
        jQuery("#wp_access_helper_container").prependTo('body');
    } else {
        jQuery("#wp_access_helper_container").prependTo('body');
    }

    // Add Skiplinks to DOM
    jQuery(".wah-skiplinks-menu").prependTo('body');

    // Open sidebar
    jQuery(".aicon_link").click(function(event){
        event.preventDefault();
        if( jQuery(this).hasClass('wah-is-active') ){
            if( ! jQuery(this).hasClass('layout-magic-sidebar') ){
                wah_close_sidebar();
                jQuery(this).removeClass('wah-is-active');
            } else {
                wahpro_hide_magic_buttons();
                jQuery(this).removeClass('wah-is-active');
            }

        } else {
            wah_open_sidebar();
            jQuery(this).addClass('wah-is-active');
        }

    });
    // Close sidebar
    jQuery(".close_container, .close-wah-sidebar").click(function(event){
        event.preventDefault();
        jQuery(".aicon_link.wah-is-active").removeClass('wah-is-active');
        wah_close_sidebar();
    });
    // Close magic sidebar
    jQuery(".close-wah-magic-sidebar").click(function(event){
        event.preventDefault();
        wahpro_hide_magic_buttons();
    });

    //FONT SIZE
    if( jQuery("body").hasClass('wah_fstype_rem') ) {
        jQuery(".smaller").click(function(event){
            event.preventDefault();
            var fontSize = parseInt(jQuery("html").css("font-size"));
            if( fontSize > 12 ){
              fontSize     = fontSize - 1 + "px";
              jQuery("html").css({'font-size':fontSize});
            }
        });
        jQuery(".larger").click(function(event){
            event.preventDefault();
            var fontSize = parseInt(jQuery("html").css("font-size"));
            if( fontSize < 24 ){
              fontSize     = fontSize + 1 + "px";
              jQuery("html").css({'font-size':fontSize});
            }
        });
    } else if ( jQuery("body").hasClass("wah_fstype_zoom") ){
        jQuery(".larger").click(function(){
            var step;
            if ( $body.hasClass('gecko') ){
                step = 0.05;
                currFFZoom += step;
                $body.css('MozTransform','scale(' + currFFZoom + ','+ currFFZoom + ')');
                $body.css('transform-origin','50% 50%');
            } else {
                step = 5;
                currIEZoom += step;
                $body.css('zoom', ' ' + currIEZoom + '%');
            }
        });
        jQuery(".smaller").click(function(){
            var step;
            if ( $body.hasClass('gecko') ){
                step = 0.05;
                currFFZoom -= step;
                $body.css('MozTransform','scale(' + currFFZoom + ','+ currFFZoom +')');
                $body.css('transform-origin','50% 50%');
            } else {
                step = 5;
                currIEZoom -= step;
                $body.css('zoom', ' ' + currIEZoom + '%');
            }
        });
    } else {
        resizable_elements.each(function(){
            jQuery(this).attr('data-wahfont',parseInt(jQuery(this).css('font-size')));
        });
        wah_font_resizer();
    }

    //Remove styles
    jQuery(".wah-call-remove-styles").click(function(event){
        event.preventDefault();
        jQuery('link:not(#wpah-front-styles-css)').each(function(index,value){
            if(jQuery(this).attr('disabled')){
                jQuery(this).removeAttr('disabled');
            } else {
                jQuery(this).attr('disabled','disabled');
            }
        });
    });

    // Mute volume
    jQuery(".set-wah-mute").click(function(event){
        event.preventDefault();
        if( ! jQuery(this).hasClass('active_button') ){
            wah_mute_volume( true );
        } else {
            wah_mute_volume( false );
        }
        jQuery(this).find('span').toggleClass('goi-sound-full goi-sound-off');
        toggleCookiesClasses("wah-mute-on");
    });

    //Greyscale images
    jQuery(".greyscale").click(function(event){
        event.preventDefault();
        toggleCookiesClasses("active_greyscale");
    });

    //Underline links
    jQuery(".wah-call-underline-links").click(function(event){
        event.preventDefault();
        toggleCookiesClasses("is_underline");
    });

    //wp-accessibility-helper #contrast
    jQuery("#contrast_trigger").click(function(event){
        event.preventDefault();
        jQuery(".color_selector").toggleClass('is_visible');
        jQuery(".color_selector").attr('aria-hidden','false');
    });

    // Color variable selector
    jQuery(".convar").click(function(event){
        event.preventDefault();
        var bg_color   = jQuery(this).data("bgcolor");
        var text_color = jQuery(this).data("color");
        jQuery(wahpro_contrast_elements).css({
            'background-color': bg_color,
            'color':text_color
        });
        setContrastCookie(bg_color,text_color);
        jQuery(".color_selector").removeClass('is_visible');
        jQuery(".color_selector").attr('aria-hidden','true');

        if( jQuery('body').hasClass('wah_custom_color') ){
            jQuery('body').removeClass('wah_custom_color');
            Cookies.remove( 'wahc_wah_custom_color' );
        } else {
            jQuery('body').addClass('wah_custom_color');
            Cookies.set( 'wahc_wah_custom_color', 'on', { expires: wahpro_cookies } );
        }

    });

    // enable background image selectors
    if( typeof WAHPro_Controller != 'undefined' && WAHPro_Controller.wah_greyscale_selectors ) {
        jQuery(WAHPro_Controller.wah_greyscale_selectors).each( function(){
            jQuery(this).addClass('wah-greyscale-element');
        });
    }

    //enable rel="link"
    if( typeof WAHPro_Controller != 'undefined' && WAHPro_Controller.roleLink == 1 ) {
        $body_link.each(function(){
            jQuery(this).attr("role","link");
        });
    }

    //remove link title attribute
    if( typeof WAHPro_Controller != 'undefined' && WAHPro_Controller.removeLinkTitles == 1 ) {
      $body_link.each(function(){
        if(jQuery(this).attr("title")){
          jQuery(this).attr("title","");
          jQuery(this).removeAttr("title");
        }
      });
    }

    //add header landmark
    if( typeof WAHPro_Controller != 'undefined' && WAHPro_Controller.headerElementSelector ) {
        $body.find(WAHPro_Controller.headerElementSelector).attr("role","banner");
    }

    //add sidebar landmark
    if( typeof WAHPro_Controller != 'undefined' && WAHPro_Controller.sidebarElementSelector ) {
        $body.find(WAHPro_Controller.sidebarElementSelector).attr("role","complementary");
    }

    //add footer landmark
    if( typeof WAHPro_Controller != 'undefined' && WAHPro_Controller.footerElementSelector ) {
        $body.find(WAHPro_Controller.footerElementSelector).attr("role","contentinfo");
    }

    //add main landmark
    if( typeof WAHPro_Controller != 'undefined' && WAHPro_Controller.mainElementSelector ) {
        $body.find(WAHPro_Controller.mainElementSelector).attr("role","main");
    }

    //add navigation landmark
    if( typeof WAHPro_Controller != 'undefined' && WAHPro_Controller.navElementSelector ) {
        $body.find(WAHPro_Controller.navElementSelector).attr("role","navigation");
    }

    //cookies
    if( typeof Cookies.get('wahFontColor') !='undefined' && typeof Cookies.get('wahBgColor') !='undefined' ){
        jQuery(wahpro_contrast_elements).css({
            'background-color': Cookies.get('wahBgColor'),
            'color':Cookies.get('wahFontColor')
        });
        jQuery('a').css('color', Cookies.get('wahFontColor') );
    }

    //Lights Off
    if( typeof WAHPro_Controller !='undefined' && WAHPro_Controller.wah_lights_off_selector){
        jQuery(".wah-lights-off").click(function(e){
            e.preventDefault();
            if( !jQuery("body").hasClass("wah-lights-off") ) {
                jQuery("body").append('<div class="wah-dark-overlay"></div>');
                jQuery("body").addClass("wah-lights-off");
                jQuery(WAHPro_Controller.wah_lights_off_selector).addClass('wah-lights-selector');
            } else {
                jQuery("body .wah-dark-overlay").remove();
                jQuery("body").removeClass("wah-lights-off");
                jQuery(WAHPro_Controller.wah_lights_off_selector).removeClass('wah-lights-selector');
            }
        });
    }

    jQuery(".wah-call-clear-cookies").click(function(e){
        e.preventDefault();
        removeAllCookies();
    });

    // Set wah layout [BETA]
    set_wah_layout();

    //highlight_links_setup
    jQuery(".wah-call-highlight-links").click(function(e){
        e.preventDefault();
        toggleCookiesClasses("highlight_links_on");
    });

    //invert mode
    jQuery(".wah-call-invert").click(function(e){
        e.preventDefault();
        toggleCookiesClasses("invert_mode_on");
    });

    //monochrome mode
    jQuery(".wah-call-monochrome_mode").click(function(e){
        e.preventDefault();
        toggleCookiesClasses("wah-enable-monochrome-filter");
    });

    // sepia mode
    jQuery(".wah-call-sepia_mode").click(function(e){
        e.preventDefault();
        toggleCookiesClasses("wah-enable-sepia-filter");
    });

    //remove animations
    jQuery(".wah-call-remove-animations").click(function(e){
        e.preventDefault();
        toggleCookiesClasses("remove_animations");
    });

    //active button
    jQuery(".wah-action-button").click(function(){
        if( jQuery(this).hasClass('wah-uniqid') ){
            var parent_alignment = jQuery(this).parents('.a_module');
            parent_alignment.find('button').removeClass('active_button');
            parent_alignment.find('button').attr('aria-pressed', 'false' )
            jQuery(this).addClass('active_button').attr('aria-pressed', 'true' );
            /* Text alignment settings [START] */
            if(
                'align-left' == jQuery(this).attr('data-wahaction') ||
                'align-right' == jQuery(this).attr('data-wahaction') ||
                'align-center' == jQuery(this).attr('data-wahaction')
            ){
                setTextAlignmentCookie( jQuery(this).attr('id') );
            }
            /* Text alignment settings [END] */
        } else {
            jQuery(this).toggleClass("active_button");
        }
    });

    //readable font
    jQuery(".readable_fonts .wah-action-button, .wp-block-wahpro-readable-font button.wah-g-action-button").click(function(e){
        e.preventDefault();
        toggleCookiesClasses("arial_font_on");
    });

    //letter spacing
    jQuery(".set-wah-letter_spacing").click(function(e){
        e.preventDefault();
        toggleCookiesClasses("wah_letter_spacing");
    });

    //Keyboard Navigation
    jQuery(".wah-call-keyboard-navigation").click(function(e){
        e.preventDefault();
        toggleCookiesClasses("wah_keyboard_access");
    });

    //ADHD Profile
    jQuery(".set-wah-adhd_profile").click(function(e){
        e.preventDefault();
        toggleCookiesClasses("wah_adhd_profile");
        if( ! jQuery('body').hasClass('wah-adhd_fiendly_profile') ){
            init_adhd_fiendly_profile();
        } else {
            remove_adhd_fiendly_profile();
        }
    });

    // Highlight titles (h1-h6)
    jQuery(".wah-call-highlight-titles").click(function(e){
        e.preventDefault();
        toggleCookiesClasses("wah_highlight_titles");
    });

    // Inspector mode
    jQuery(".wah-call-inspector_mode").click(function(e){
        e.preventDefault();
        toggleCookiesClasses("wah_inspector_mode");
        setTimeout( function(){
            location.reload();
        }, 300 );
    });

    // Image alt description
    jQuery('.wah-call-image-alt').click(function(e){
        e.preventDefault();
        toggleCookiesClasses("wah-image-desc-on");
        if( jQuery('body').hasClass('wah-image-desc-on') ){
            jQuery('img.wahImageTooltip').removeClass('wahImageTooltip');
        } else {
            wah_render_image_alt_description();
        }
    });

    // Call image alts if came from cookies
    if( jQuery('body').hasClass('wah-image-desc-on') ){
        wah_render_image_alt_description();
    }

    // Large mouse cursor
    jQuery(".wah-call-large_cursor").click(function(e){
        e.preventDefault();
        toggleCookiesClasses("wah_large_white_cursor");
    });

    //Fetch scanner params
    if( typeof WAHPro_Controller != 'undefined' && WAHPro_Controller.wah_target_src ){
        wah_target_element = jQuery("body").find("img[src='"+WAHPro_Controller.wah_target_src+"']");
        wah_target_element.addClass("wah_scanner_element");
    }
    if( typeof WAHPro_Controller != 'undefined' && WAHPro_Controller.wah_target_link ){
        wah_target_element = jQuery('a[href="'+WAHPro_Controller.wah_target_link+'"]');
        wah_target_element.addClass("wah_scanner_link");
    }

    wah_accessibility_minibar();

    wah_accordion();

    // LOAD Font size from cookies when script base font resize enabled
    if( jQuery("body").hasClass("wah_fstype_script") ){
        if( typeof wahpro_resize_clicks != 'undefined' && wahpro_resize_clicks != 0 ){
            resizable_elements.each(function(){
                var current_font_size = jQuery(this).css('font-size');
                jQuery(this).css('font-size',parseInt( current_font_size + wahpro_resize_clicks )+'px');
            });
        }
    }

    wahpro_load_font_size_from_cookies();

});

/**************************************
    ADHD Friendly Profile [START]
**************************************/
function init_adhd_fiendly_profile(){

    var windowHeight = jQuery(window).height();

    jQuery('body').addClass('wah-adhd_fiendly_profile');
    jQuery('.wah-adhd_fiendly_profile').append('<div class="wah-adhd-mask wah-adhd-top-element"></div>');
    jQuery('.wah-adhd_fiendly_profile').append('<div class="wah-adhd-mask wah-adhd-bottom-element"></div>');

    jQuery(window).on('mousemove', function(event) {
        // console.log( event.target.tagName + ': ' + event.type);
        wahCurrentMousePos.x = event.screenX;
        wahCurrentMousePos.y = event.screenY;
        var top_mask         = windowHeight - wahCurrentMousePos.y;
        var bottom_mask      = windowHeight - top_mask - 120;
        jQuery('.wah-adhd-top-element').height(bottom_mask );
        jQuery('.wah-adhd-bottom-element').height(top_mask);
    });
}

function remove_adhd_fiendly_profile(){
    jQuery('body').removeClass('wah-adhd_fiendly_profile');
    jQuery('.wah-adhd-top-element').remove();
    jQuery('.wah-adhd-bottom-element').remove();
}
/**************************************
    ADHD Friendly Profile [END]
**************************************/

function init_report_problem(){
    if( jQuery('#wah-report-problem-popup').length ){
        jQuery('#wah-report-problem-form').submit( function(e){
            e.preventDefault();
            var form = jQuery(this).serialize();
            jQuery('#wah-report-problem-popup .wah-report-form-row button[type="submit"]').prop('disabled', true );
            submit_wah_report_form(form);
        });
    }
}
function get_wah_score(page_url) {
    jQuery.ajax({
        type     : "post",
        dataType : "json",
        url      : wahpro_settings.ajax_url,
        data     : {
            action   : "get_wah_score",
            page_url : page_url
        },
        success: function(response) {
            if( response.html ){
                jQuery('body').append('<div class="wah-page-score-popup"><div class="wah-page-score-wrapper"><button class="close-wah-page-score">x</button><div class="wah-page-score-popup-inner"></div></div></div>');
                jQuery('.wah-page-score-popup-inner').html(response.html);
            }
        }
    });
}

jQuery(document).on('click', '.close-wah-page-score', function(e){
    e.preventDefault();
    jQuery('.wah-page-score-popup').fadeOut(250, function(){
        jQuery(this).remove();
    })
});

function submit_wah_report_form(form){
    jQuery.ajax({
        type     : "post",
        dataType : "json",
        url      : wahpro_settings.ajax_url,
        data     : {
            action : "submit_wah_report_form",
            form   : form
        },
        success: function(response) {
            if( ! response.error ){
                // clear the form
                jQuery('#wah-report-problem-form input[name="wah_report_user_email"], #wah-report-problem-form input[name="wah_report_subject"], #wah-report-problem-form textarea').val('');
                // show response
                jQuery('.wah-report-form-row.ajax-response').html( response.html );
                jQuery('#wah-report-problem-popup .wah-report-form-row button[type="submit"]').prop('disabled', false );
                setTimeout( function(){
                    jQuery('.wah-report-form-row.ajax-response').html('');
                }, 5000 );
            }
        }
    });
}

function wahpro_log(){
    if( typeof wahpro_settings.wahpro_log != 'undefined' && wahpro_settings.wahpro_log ){
        console.log('%c--- WAH PRO '+wahpro_settings.plugin_version+' initialized! ---', 'background: #236478; color: #FFF; font-size: 12px; font-weight:bold;');
        console.log('Images: ', jQuery('img').length );
        console.log('Images wihtout alt tag: ', jQuery('img[alt=""]').length );
        console.log('Heading titles: ', jQuery('h1, h2, h3, h4, h5, h6').length );
        console.log('Links: ', jQuery('a').length );
        console.log('Iframes: ', jQuery('iframe').length );
        console.log('WAH PRO: ', 'https://accessibility-helper.co.il/');
    }
}

function wah_render_image_alt_description(){

    if( jQuery('body').hasClass('wah-image-desc-on') ){
        jQuery('.wah-image-desc-on img').each(function(){
            if( jQuery(this).attr('alt') ){
                jQuery(this).addClass('wahImageTooltip');
            }
        });
    }

    jQuery('.wahImageTooltip').hover(function(){
        // Hover over code
        var title = jQuery(this).attr('alt');
        jQuery(this).data('tipText', title).removeAttr('title');
        jQuery('<p class="wah-tooltip"></p>')
        .text(title)
        .appendTo('body')
        .fadeIn('slow');
    }, function() {
        // Hover out code
        jQuery(this).attr('alt', jQuery(this).data('tipText'));
        jQuery('.wah-tooltip').remove();
    }).mousemove(function(e) {
        var mousex = e.pageX + 20; //Get X coordinates
        var mousey = e.pageY + 10; //Get Y coordinates
        jQuery('.wah-tooltip').css({ top: mousey, left: mousex });
    });
}

function load_wah_cookies(){

    var wah_all_cookies = Cookies.get();

    for ( var key in wah_all_cookies ) {
        if ( wah_all_cookies.hasOwnProperty(key) ) {
            if ( key.substring(0, 5) == "wahc_") {
                var body_class = key.replace("wahc_", "");
                jQuery('body').addClass(body_class);
                // console.log(body_class);
                // console.log(key + " -> " + wah_all_cookies[key] );
            }
        }
    }

    if( jQuery('body').hasClass('highlight_links_on') ){
        jQuery('.wah-call-highlight-links').addClass('active_button');
    }

    if( jQuery('body').hasClass('wah-mute-on') ){
        jQuery('#wah_enable_mute').addClass('active_button');
        jQuery('#wah_enable_mute span').toggleClass('goi-sound-full goi-sound-off');
        wah_mute_volume( true );
    }
    if( jQuery('body').hasClass('arial_font_on') ){
        jQuery('.wah-call-readable-fonts').addClass('active_button');
    }
    if( jQuery('body').hasClass('is_underline') ){
        jQuery('.wah-call-underline-links').addClass('active_button');
    }
    if( jQuery('body').hasClass('active_greyscale') ){
        jQuery('.wah-call-greyscale').addClass('active_button');
    }
    if( jQuery('body').hasClass('invert_mode_on') ){
        jQuery('.wah-call-invert').addClass('active_button');
    }
    if( jQuery('body').hasClass('remove_animations') ){
        jQuery('.wah-call-remove-animations').addClass('active_button');
    }
    if( jQuery('body').hasClass('wah_highlight_titles') ){
        jQuery('.wah-call-highlight-titles').addClass('active_button');
    }
    if( jQuery('body').hasClass('wah-image-desc-on') ){
        jQuery('.wah-call-image-alt').addClass('active_button');
    }
    if( jQuery('body').hasClass('wah_keyboard_access') ){
        jQuery('.wah-call-keyboard-navigation').addClass('active_button');
    }
    if( jQuery('body').hasClass('wah_custom_color') ){
        jQuery('.wah-call-contrast-trigger').addClass('active_button');
    }
    if( jQuery('body').hasClass('wah_large_white_cursor') ){
        jQuery('.wah-call-large_cursor').addClass('active_button');
    }
    if( jQuery('body').hasClass('wah-enable-monochrome-filter') ){
        jQuery('.wah-call-monochrome_mode').addClass('active_button');
    }
    if( jQuery('body').hasClass('wah-enable-sepia-filter') ){
        jQuery('.wah-call-sepia_mode').addClass('active_button');
    }
    if( jQuery('body').hasClass('wah_letter_spacing') ){
        jQuery('.set-wah-letter_spacing').addClass('active_button');
    }
    // Inspector mode
    if( jQuery('body').hasClass('wah_inspector_mode') ){
        jQuery('.wah-call-inspector_mode').addClass('active_button');
        init_wah_inspector();
    }
    if( jQuery('body').hasClass('wah_adhd_profile')){
        jQuery('.set-wah-adhd_profile').addClass('active_button');
        init_adhd_fiendly_profile();
    }
    if( jQuery('body').hasClass('wah_text_alignment_center') ){
        jQuery('#wah_text_alignment_center').addClass('active_button').attr('aria-pressed','true');
    }
    if( jQuery('body').hasClass('wah_text_alignment_left') ){
        jQuery('#wah_text_alignment_left').addClass('active_button').attr('aria-pressed','true');
    }
    if( jQuery('body').hasClass('wah_text_alignment_right') ){
        jQuery('#wah_text_alignment_right').addClass('active_button').attr('aria-pressed','true');
    }

}

function init_wah_inspector(){

    // Alpha : content selector
    jQuery('body').mousemove(function(e){
        if(
            ! jQuery(e.target).closest('.wah_content_selector_popup').length &&
            ! jQuery(e.target).closest('#wp_access_helper_container').length
        ){
            jQuery(e.target).addClass('wah_content_selector_is_active');
        }
    });

    jQuery('body').mouseout(function(e){
        jQuery(e.target).removeClass('wah_content_selector_is_active');
    });

    jQuery('body').on( 'click', function(e){
        if(
            ! jQuery(e.target).closest('.wah_content_selector_popup').length &&
            ! jQuery(e.target).closest('#wp_access_helper_container').length
        ){
            e.preventDefault();
            jQuery('.wahout.aicon_link').fadeOut(200);
            var element = e.target;
            var content_html = jQuery(element).html();
            jQuery('body').append('<div class="wah_content_selector_popup" aria-live="polite"><button class="close_wah_content_selector_popup" type="button" title="Close window">[x]</button><div class="wah_content_selector_popup_inner"></div></div>');
            jQuery('.wah_content_selector_popup_inner').html( content_html );
            jQuery('.close_wah_content_selector_popup').focus();
        }
    });

    jQuery('body').on( 'click', '.close_wah_content_selector_popup', function(e){
        e.preventDefault()
        jQuery('.wah_content_selector_popup').fadeOut(250).remove();
        jQuery('.wahout.aicon_link').fadeIn(200);
    });

}

function toggleCookiesClasses( classname ){

    if( ! jQuery('body').hasClass(classname) ){
        jQuery('body').addClass(classname);
        Cookies.set( 'wahc_'+classname, 'on', { expires: wahpro_cookies } );
    } else {
        jQuery('body').removeClass(classname);
        Cookies.remove( 'wahc_' + classname );
    }

}

function setTextAlignmentCookie( current_class ){

    var alignment_classes = [ 'wah_text_alignment_left', 'wah_text_alignment_center', 'wah_text_alignment_right' ];

    jQuery('body').removeClass('wah_text_alignment_left wah_text_alignment_center wah_text_alignment_right');
    Cookies.remove( 'wahc_wah_text_alignment_left' );
    Cookies.remove( 'wahc_wah_text_alignment_center' );
    Cookies.remove( 'wahc_wah_text_alignment_right' );

    jQuery('body').addClass(current_class);
    Cookies.set( 'wahc_'+current_class, 'on', { expires: wahpro_cookies } );

}

function setContrastCookie( bg_color,text_color ){
    Cookies.set('wahFontColor', text_color, { expires: wahpro_cookies });
    Cookies.set('wahBgColor', bg_color, { expires: wahpro_cookies });
}

function removeAllCookies(){
    Cookies.remove('wahFontColor');
    Cookies.remove('wahBgColor');
    Cookies.remove('wahc_wah_highlight_titles');
    Cookies.remove('wahc_arial_font_on');
    Cookies.remove('wahc_wah_keyboard_access');
    Cookies.remove('wahc_highlight_links_on');
    Cookies.remove('wahc_remove_animations');
    Cookies.remove('wahc_invert_mode_on');
    Cookies.remove('wahc_is_underline');
    Cookies.remove('wahc_active_greyscale');
    Cookies.remove('wahpro_resize_clicks');
    Cookies.remove('wahc_wah_custom_color');
    Cookies.remove('wahc_wah-image-desc-on');
    Cookies.remove('wahc_wah-enable-sepia-filter');
    Cookies.remove('wahc_wah-enable-monochrome-filter');
    Cookies.remove('wahc_wah_inspector_mode');
    Cookies.remove('wahc_wah_large_white_cursor');
    Cookies.remove('user_wahstyle');
    Cookies.remove('wahc_wah_letter_spacing');
    Cookies.remove('wahc_wah_adhd_profile');
    Cookies.remove('wahc_wah_text_alignment_left');
    Cookies.remove('wahc_wah_text_alignment_center');
    Cookies.remove('wahc_wah_text_alignment_right');
    Cookies.remove('wahc_wah-mute-on');

    location.reload();
}

function wah_font_resizer(){
    //Font++
    jQuery(".font_resizer .larger, .wp-block-wahpro-font-resize .larger").click(function(e){
        e.preventDefault();
        resizable_elements.each(function(){
            if( ! jQuery(this).hasClass('sr screen-reader') ){
                var el_font_size = parseInt(jQuery(this).css('font-size'));
                jQuery(this).css('font-size',parseInt(el_font_size+1)+'px');
            }
        });
        wahpro_resize_clicks++;
        wahpro_set_resize_clicks( wahpro_resize_clicks );
    });
    //Font--
    jQuery(".font_resizer .smaller, .wp-block-wahpro-font-resize .smaller").click(function(e){
        e.preventDefault();
        resizable_elements.each(function(){
            var el_font_size = parseInt(jQuery(this).css('font-size'));
            if(el_font_size > 12){
                jQuery(this).css('font-size',parseInt(el_font_size-1)+'px');
            }
        });
        wahpro_resize_clicks--;
        wahpro_set_resize_clicks( wahpro_resize_clicks );
    });
    //Font reset
    jQuery(".wah-font-reset").click(function(e){
        e.preventDefault();
        resizable_elements.each(function(){
            var el_font_size = parseInt(jQuery(this).css('font-size'));
            jQuery(this).css('font-size',parseInt(jQuery(this).data("wahfont"))+'px');
        });
        wahpro_resize_clicks = 0;
        wahpro_set_resize_clicks( 0 );
    });

}

/**************************
    PRO
**************************/
function wahpro_set_resize_clicks( clicks_number ){
    Cookies.set( 'wahpro_resize_clicks', clicks_number, { expires: wahpro_cookies } );
}
function wahpro_get_resize_clicks(){
    var clicks = Cookies.get( 'wahpro_resize_clicks' ) ? Cookies.get( 'wahpro_resize_clicks' ) : 0;
    return parseInt(clicks);
}

function wahpro_load_font_size_from_cookies(){
    var clicks_number = wahpro_get_resize_clicks();
    resizable_elements.each(function(){
        if( ! jQuery(this).hasClass('sr screen-reader') ){
            var el_font_size = parseInt(jQuery(this).css('font-size'));
            if( el_font_size ){
                jQuery(this).css('font-size', ( el_font_size + clicks_number )+'px');
            }
        }
    });
}

// POPUP
(function( $ ) {

    var lastFocus;

    jQuery(".wah-popup-trigger").click(function(e){
        e.preventDefault();
        var dialog_id = jQuery(this).attr("data-dialogid");
        lastFocus = document.activeElement;
        wahOpenDialog( dialog_id );
    });

    jQuery(".wah-close-dialog").click(function(e){
        e.preventDefault();
        wahCloseDialog();
    });

    jQuery(document).keyup(function(e) {
        if( jQuery(".wah-dialog-popup.openDialog").length ){
            if (e.keyCode == 27) { // escape key maps to keycode `27`
                wahCloseDialog();
            }
        }
    });

    function wahOpenDialog( dialog_id ) {
        var this_dialog = jQuery('.wah-dialog-popup[data-dialogid='+dialog_id+']');

        jQuery(".wah-dialog-popup").removeClass('openDialog').attr('aria-hidden',true);

        this_dialog.addClass('openDialog').attr('aria-hidden',false);
        this_dialog.focus();
    }

    function wahCloseDialog(){
        jQuery(".wah-dialog-popup").removeClass('openDialog').attr('aria-hidden',true);
        lastFocus.focus();
    }

}( jQuery ));

// MINIBAR
function wah_accessibility_minibar(){

    jQuery(document).on('click', '.wah-access-bar-buttons button', function(event){
        event.preventDefault();
        var button = jQuery(this);
        var button_name = button.attr('name');
        button.toggleClass('is-active-button');
        switch( button_name ) {
            case 'wah-contrast-toggle':
                jQuery('html').toggleClass('wah-minibar-contrast');
                break;
            case 'wah-invert-toggle':
                jQuery('html').toggleClass('wah-minibar-greyscale');
                break;
            default: //wah-fontsize-toggle
                jQuery('html').toggleClass('wah-minibar-fontsize');
        }
    });

}

// ACCORDION
function wah_accordion(){
    jQuery(document).on('click', '.wah-accordion-item a', function(event){
        event.preventDefault();
        if( ! jQuery(this).parents('.wah-accordion-container').hasClass('is-animated') ) {
            jQuery('.wah-accordion-item').removeClass('is-active');
            jQuery(this).parent().addClass('is-active');
        } else {
            if( ! jQuery(this).parent().hasClass('is-active') ){
                jQuery('.wah-accordion-content').slideUp(100);
                jQuery(this).parent().find('.wah-accordion-content').slideDown(200);
                jQuery('.wah-accordion-item').removeClass('is-active');
                jQuery(this).parent().addClass('is-active');
            } else {
                jQuery(this).parent().find('.wah-accordion-content').slideUp(200);
                jQuery(this).parent().removeClass('is-active');
            }
        }

    });
}

// open sidebar
function wah_open_sidebar(){
    if( ! jQuery('body').hasClass('wahpro-magic-sidebar') ) {
        jQuery(".accessability_container").addClass("active");
        jQuery("#access_container button, #access_container a").removeAttr("tabindex");
        jQuery("#access_container").attr("aria-hidden","false");
    } else {
        if( ! jQuery('.aicon_link').hasClass('magic-buttons-is-active') ){
            wahpro_show_magic_buttons();
        } else {
            wahpro_hide_magic_buttons();
        }
    }
}
// close sidebar
function wah_close_sidebar(){
    if( ! jQuery('body').hasClass('wahpro-magic-sidebar') ) {
        jQuery(".accessability_container").removeClass("active");
        jQuery("#access_container button, #access_container a").attr("tabindex","-1");
        jQuery("#access_container").attr("aria-hidden","true");
    }
}
// show magic buttons
function wahpro_show_magic_buttons(){

    var animation_speed = 40;

    jQuery("#access_container button").removeAttr("tabindex");

    jQuery('.a_module.wah_magic_skip_links').css('opacity',0);

    jQuery('.a_module').each(function(){

        jQuery(this).animate({
            top : wahpro_magic_buttons_top
        }, animation_speed , function(){
            jQuery(this).css('visibility', 'visible');
            jQuery(this).css('width', '240px');
            jQuery(this).css('opacity', 1);
        });

        var button_height = jQuery(this).height();

        wahpro_magic_buttons_top = wahpro_magic_buttons_top + button_height + 1;
        animation_speed          = animation_speed + 20;
    });

    jQuery('.aicon_link').addClass('magic-buttons-is-active');

}
// hide magic buttons
function wahpro_hide_magic_buttons(){

    var animation_speed = 40;

    jQuery('.a_module').each(function(){

        var button_height = jQuery(this).height();

        jQuery(this).animate({
            top : 50
        }, animation_speed , function(){
            jQuery(this).css('visibility', 'hidden');
            jQuery(this).css('width', '200px');
            jQuery(this).css('opacity', 0);
        });

        animation_speed = animation_speed + 10;

    });

    wahpro_magic_buttons_top = jQuery('body').hasClass('admin-bar') ? 80 : 60;

    jQuery('.aicon_link').removeClass('magic-buttons-is-active');

}

function set_wah_layout(){

    if( jQuery('.set-wah-layout').length && jQuery('.wah_set_layout_popup').length ) {

        jQuery('.set-wah-layout-popup').click( function(e){
            jQuery('.wah_set_layout_popup').addClass('active');
            setTimeout( function(){
                jQuery('.close-wah_set_layout_popup').focus();
            }, 100);
        });

        jQuery('.set-wah-layout').click( function(e){
            e.preventDefault();
            var new_wahstyle = jQuery(this).attr('data-new-wahstyle');
            Cookies.set('user_wahstyle', new_wahstyle, { expires: wahpro_cookies });
            location.reload();
        });

        jQuery('.close-wah_set_layout_popup').on('click', function(e){
            e.preventDefault();
            jQuery('.wah_set_layout_popup').removeClass('active');
        });
    }

}

function wah_mute_volume( state ){
    Array.prototype.slice.call(document.querySelectorAll('audio,video')).forEach(function(audio) {
        audio.muted = state;
    });

    // Youtube iFrame mute
    var youTubeIframes = jQuery('iframe[src^="https://www.youtube.com/embed/"]');
    youTubeIframes.each( function(){
        var src = jQuery(this).attr('src');
        if( state ){
            src = src+'&mute=1';
        } else {
            src = src+'&mute=0';
        }
        jQuery(this).attr('src', src);
    });

    // Vimeo iFrame mute
    var vimeoIframes = jQuery('iframe[src^="https://player.vimeo.com/"]');
    vimeoIframes.each( function(){
        var src = jQuery(this).attr('src');
        if( state ){
            src = src+'&background=1';
        } else {
            src = src+'&background=0';
        }
        jQuery(this).attr('src', src);
    });

}
