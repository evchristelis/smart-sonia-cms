<?php
/**********************************************
***   Add front body classes                ***
**********************************************/
if ( ! function_exists( 'wp_access_helper_body_class' ) ) {

    function wp_access_helper_body_class($classes) {
        global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
        if( $is_lynx ) $classes[] = 'lynx';
        elseif( $is_gecko ) $classes[] = 'gecko';
        elseif( $is_opera ) $classes[] = 'opera';
        elseif( $is_NS4 ) $classes[] = 'ns4';
        elseif( $is_safari ) $classes[] = 'safari';
        elseif( $is_chrome ) $classes[] = 'chrome';
        elseif( $is_IE ) {
            $classes[] = 'ie';
            if( isset( $_SERVER['HTTP_USER_AGENT'] ) && preg_match( '/MSIE ( [0-11]+ )( [a-zA-Z0-9.]+ )/', $_SERVER['HTTP_USER_AGENT'], $browser_version ) ) {
                $classes[] = 'ie' . $browser_version[1];
            }

        } else $classes[] = 'unknown';
        if( $is_iphone ) $classes[] = 'iphone';

        if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && stristr( $_SERVER['HTTP_USER_AGENT'],"mac") ) {
            $classes[] = 'osx';
        } elseif ( isset( $_SERVER['HTTP_USER_AGENT'] ) && stristr( $_SERVER['HTTP_USER_AGENT'],"linux") ) {
            $classes[] = 'linux';
        } elseif ( isset( $_SERVER['HTTP_USER_AGENT'] ) && stristr( $_SERVER['HTTP_USER_AGENT'],"windows") ) {
            $classes[] = 'windows';
        }

        $classes[]             = 'wp-accessibility-helper';
        $contrast_setup        = wah_get_param('wah_contrast_setup') ? wah_get_param('wah_contrast_setup') : 0;
        $font_setup_type       = wah_get_param('wah_font_setup_type') ? wah_get_param('wah_font_setup_type') : 'zoom';
        $remove_styles_setup   = wah_get_param('wah_remove_styles_setup') ? wah_get_param('wah_remove_styles_setup') : 0;
        $location_setup        = wah_get_param('wah_left_side') ? 'left' : 'right';
        $underline_links_setup = wah_get_param('wah_underline_links_setup') ? wah_get_param('wah_underline_links_setup') : 0;
        $wah_left_side         = wah_get_param('wah_left_side');
        $wah_enable_icons      = wah_get_param('wah_enable_icons');
        $wah_sidebar_layout    = wah_get_user_wahstyle();
        $wah_statement_enable  = wah_get_param( 'wah_statement_enable' );

        if( $contrast_setup ) { $classes[]        = 'accessibility-contrast_mode_on'; }
        if( $font_setup_type ) { $classes[]       = 'wah_fstype_'.$font_setup_type; }
        if( $remove_styles_setup ) { $classes[]   = 'accessibility-remove-styles-setup'; }
        if( $underline_links_setup ) { $classes[] = 'accessibility-underline-setup'; }
        if( $wah_enable_icons ) { $classes[]      = 'wahpro-icon-font'; }
        if( $wah_statement_enable ) { $classes[]  = 'wah_statement_enabled'; }
        if( $location_setup == 'left' ) {
            $classes[] = 'accessibility-location-left';
        } else {
            $classes[] = 'accessibility-location-right';
        }
        // sidebar layout selector
        $classes[] = 'wahpro-' . $wah_sidebar_layout;

    	return $classes;
    }
    add_filter('body_class','wp_access_helper_body_class');
}
/****************************************************
****   WAH Analyzer                              ***
****************************************************/
add_action('wp','wah_analyzer');
function wah_analyzer(){
    if( wah_analyzer_isset() && wah_admin_only() ) {
        run_front_dom_scanner();
    } elseif( wah_analyzer_isset() && !wah_admin_only() ) {
        echo "<h1 style='text-align:center;'>".__("You do NOT have permissions to access this page","wp-accessibility-helper")."</h1>";
        echo "<h3 style='text-align:center;'>".__("Please contact site administrator.","wp-accessibility-helper")."</h3>";
        die();
    }
}
function run_front_dom_scanner() {
    wp_register_style( 'wah_analyzer-styles',  plugins_url() . '/wp-accessibility-helper-pro/admin/wah-analyzer/style.css' );
    wp_enqueue_style( 'wah_analyzer-styles' );

    wp_localize_script( 'wah_analyzer-js', 'ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ));
    wp_register_script( 'wah_analyzer-js', plugins_url().'/wp-accessibility-helper-pro/admin/wah-analyzer/wah_analyzer.js' , array('jquery'), '', true );
    wp_enqueue_script( 'wah_analyzer-js' );
}
/****************************************************
****   Get attachment id by image source         ***
****************************************************/
function get_attachment_id( $url ) {

	$attachment_id = 0;
	$dir           = wp_upload_dir();

	if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?

		$file = basename( $url );
		$query_args = array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
					'value'   => $file,
					'compare' => 'LIKE',
					'key'     => '_wp_attachment_metadata',
				),
			)
		);

		$query = new WP_Query( $query_args );
		if ( $query->have_posts() ) {
			foreach ( $query->posts as $post_id ) {

				$meta = wp_get_attachment_metadata( $post_id );
				$original_file       = basename( $meta['file'] );
				$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );

				if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
					$attachment_id = $post_id;
					break;
				}
			}
		}

	}
	return $attachment_id;
}
/***********************************************
****   Analyzer Access                      ***
***********************************************/
function wah_analyzer_isset(){
    if( isset($_GET['wah_analyzer']) && $_GET['wah_analyzer'] == 'wah' ) {
        return true;
    }
    return false;
}
function wah_admin_only(){
    if( current_user_can('administrator') ){
        return true;
    }
    return false;
}
function icons_enabled() {
    if( wah_get_param('wah_enable_icons') ) {
        return true;
    }
    return false;
}
/**********************************************
***     Widgets                             ***
**********************************************/
function wah_get_front_widgets_list(){

    //Get all vars

    $contrast_setup = wah_get_param('wah_contrast_setup');
    $choose_color_title = wah_get_param('wah_choose_color_title') ? wah_get_param('wah_choose_color_title') : __("Choose color","wp-accessibility-helper");
    $custom_contrast_variations = wah_get_param('wah_enable_custom_contrast');

    $underline_links_setup = wah_get_param('wah_underline_links_setup');
    $underline_links_setup_title = wah_get_param('wah_underline_links_setup_title') ? wah_get_param('wah_underline_links_setup_title'): __("Underline links","wp-accessibility-helper");
    $role_links_setup = wah_get_param('wah_role_links_setup');
    $remove_link_titles = wah_get_param('wah_remove_link_titles');
    $remove_styles_setup = wah_get_param('wah_remove_styles_setup');
    $remove_styles_setup_title = wah_get_param('wah_remove_styles_setup_title') ? wah_get_param('wah_remove_styles_setup_title'): __("Remove styles","wp-accessibility-helper");
    $close_button_title = wah_get_param('wah_close_button_title') ? wah_get_param('wah_close_button_title'): __("Close","wp-accessibility-helper");

    $wah_clear_cookies_title = wah_get_param('wah_clear_cookies_title') ? wah_get_param('wah_clear_cookies_title') : __("Clear cookies","wp-accessibility-helper");

    $wah_greyscale_enable = wah_get_param('wah_greyscale_enable');
    $wah_greyscale_title = wah_get_param('wah_greyscale_title') ? wah_get_param('wah_greyscale_title') : __("Images Greyscale","wp-accessibility-helper");

    $wah_highlight_links_enable = wah_get_param('wah_highlight_links_enable');
    $wah_highlight_title = wah_get_param('wah_highlight_links_title') ? wah_get_param('wah_highlight_links_title'): __("Highlight Links","wp-accessibility-helper");

    $wah_invert_enable = wah_get_param('wah_invert_enable');
    $wah_invert_title = wah_get_param('wah_invert_title') ? wah_get_param('wah_invert_title'): __("Invert Colors","wp-accessibility-helper");

    $wah_remove_animations_setup = wah_get_param('wah_remove_animations_setup');
    $wah_remove_animations_title = wah_get_param('wah_remove_animations_title') ? wah_get_param('wah_remove_animations_title'): __("Remove Animations","wp-accessibility-helper");

    $wah_readable_fonts_setup = wah_get_param('wah_readable_fonts_setup');
    $wah_readable_fonts_title = wah_get_param('wah_readable_fonts_title') ? wah_get_param('wah_readable_fonts_title'): __("Readable Font","wp-accessibility-helper");

    $wah_keyboard_navigation_setup = wah_get_param('wah_keyboard_navigation_setup');
    $wah_keyboard_navigation_title = wah_get_param('wah_keyboard_navigation_title') ? wah_get_param('wah_keyboard_navigation_title'): __("Keyboard navigation","wp-accessibility-helper");

    $wah_lights_off_setup = wah_get_param('wah_lights_off_setup');
    $wah_lights_off_title = wah_get_param('wah_lights_off_title') ? wah_get_param('wah_lights_off_title') : __("Lights Off","wp-accessibility-helper");

    $wah_highlight_titles_setup = wah_get_param('wah_highlight_titles_setup');
    $wah_highlight_titles_title = wah_get_param('wah_highlight_titles_title') ? wah_get_param('wah_highlight_titles_title') : __("Highlight titles","wp-accessibility-helper");

    $wah_image_alt_setup = wah_get_param('wah_image_alt_setup');
    $wah_image_alt_title = wah_get_param('wah_image_alt_title') ? wah_get_param('wah_image_alt_title') : __("Image description","wp-accessibility-helper");

    $wah_enable_terms_link = wah_get_param( 'wah_enable_terms_link' );
    $wah_custom_link_title = wah_get_param( 'wah_custom_link_title' );
    $wah_custom_link_url   = wah_get_param( 'wah_custom_link_url' );
    $wah_custom_link = false;
    if( $wah_enable_terms_link && $wah_custom_link_title && $wah_custom_link_url ){
        $wah_custom_link = true;
    }

    // Large mouse cursor
    $wah_enable_large_mouse_cursor = wah_get_param( 'wah_enable_large_mouse_cursor' );
    $wah_large_mouse_cursor_title  = wah_get_param( 'wah_large_mouse_cursor_title' ) ? wah_get_param( 'wah_large_mouse_cursor_title' ) : __("Large mouse cursor", "wp-accessibility-helper");

    // Font resize
    //$wah_sidebar_layout = wah_get_param( 'wah_sidebar_layout' );
    $wah_sidebar_layout = wah_get_user_wahstyle();

    $reset_font_size_title = wah_get_param('wah_reset_font_size') ? wah_get_param('wah_reset_font_size') : __("Reset font size","wp-accessibility-helper");
    $font_setup_type = wah_get_param('wah_font_setup_type') ? wah_get_param('wah_font_setup_type') : 'zoom';
    $reset_button   = '';
    $icon_reset     = icons_enabled() ? '<span class="goi-refresh"></span>' : '';

    if( $font_setup_type == 'script' ) {
        $reset_button = '<button tabindex="-1" type="button" class="wah-action-button wah-font-reset wahout" title="'.$reset_font_size_title.'"
            aria-label="'.$reset_font_size_title.'">'. $reset_font_size_title . $icon_reset . '</button>';
    }

    $wah_enable_monochrome_mode = wah_get_param( 'wah_enable_monochrome_mode' );
    $wah_monochrome_mode_title  = wah_get_param('wah_monochrome_mode_title') ? wah_get_param('wah_monochrome_mode_title') : __("Monochrome","wp-accessibility-helper");

    $wah_enable_sepia_mode = wah_get_param('wah_enable_sepia_mode');
    $wah_sepia_mode_title = wah_get_param('wah_sepia_mode_title') ? wah_get_param('wah_sepia_mode_title') : __("Sepia","wp-accessibility-helper");

    $wah_enable_inspector_mode = wah_get_param('wah_enable_inspector_mode');
    $wah_inspector_mode_title = wah_get_param('wah_inspector_mode_title') ? wah_get_param('wah_inspector_mode_title') : __("Inspector mode","wp-accessibility-helper");

    $wah_set_layout_setup = wah_get_param( 'wah_set_layout_setup' );
    $wah_set_layout_title = wah_get_param( 'wah_set_layout_title') ? wah_get_param( 'wah_set_layout_title') : __('Select Theme', 'wp-accessibility-helper');
    $wah_set_layout_popup_title = wah_get_param( 'wah_set_layout_popup_title' ) ? wah_get_param( 'wah_set_layout_popup_title' ) : __('Select Theme', 'wp-accessibility-helper');

    // Letter spacing
    $wah_enable_letter_spacing_mode = wah_get_param( 'wah_enable_letter_spacing_mode' );
    $wah_letter_spacing_title = wah_get_param( 'wah_letter_spacing_title' ) ? wah_get_param( 'wah_letter_spacing_title' ) : __( 'Letter spacing', 'wp-accessibility-helper' );

    // ADHD
    $wah_enable_adhd = wah_get_param( 'wah_enable_adhd' );
    $wah_adhd_button_title = wah_get_param( 'wah_adhd_button_title' ) ? wah_get_param( 'wah_adhd_button_title' ) : __( 'ADHD Profile', 'wp-accessibility-helper' );

    // MUTE
    $wah_enable_mute = wah_get_param( 'wah_enable_mute' );
    $wah_mute_button_title = wah_get_param( 'wah_mute_button_title' ) ? wah_get_param( 'wah_mute_button_title' ) : __( 'MUTE Volume', 'wp-accessibility-helper' );

    // Text alignment
    $wah_text_alignment = wah_get_param( 'wah_text_alignment' );
    $wah_text_alignment_center = wah_get_param( 'wah_text_alignment_center' );
    $wah_text_alignment_left   = wah_get_param( 'wah_text_alignment_left' );
    $wah_text_alignment_right  = wah_get_param( 'wah_text_alignment_right' );

    //Build widgets array
    if( $wah_sidebar_layout !='wah-bottom-fullwidth' ){
        $icon_widget1 = '';
        $smaller_font_size_button = '<button tabindex="-1" type="button" class="wah-action-button smaller wahout" title="'.__("smaller font size","wp-accessibility-helper").'" aria-label="'.__("smaller font size","wp-accessibility-helper").'">A-'.$icon_widget1.'</button>';
        $larger_font_size_button = '<button tabindex="-1" type="button" class="wah-action-button larger wahout" title="'.__("larger font size","wp-accessibility-helper").'" aria-label="'.__("larger font size","wp-accessibility-helper").'">A+'.$icon_widget1.'</button>';
        $wah_default_front_widget["widget-1"] = array(
            "active" => 1,
            "html"   => '<div class="a_module wah_font_resize">
                <div class="a_module_exe font_resizer">' . $smaller_font_size_button . $larger_font_size_button . $reset_button . '</div>
            </div>'
        );
    } else {
        $icon_widget1 = icons_enabled() ? '<span class="goi-letter-a"></span>' : '';
        $smaller_font_size_button = '<button tabindex="-1" type="button" class="wah-action-button smaller wahout" title="'.__("smaller font size","wp-accessibility-helper").'" aria-label="'.__("smaller font size","wp-accessibility-helper").'">A-'.$icon_widget1.'</button>';
        $larger_font_size_button = '<button tabindex="-1" type="button" class="wah-action-button larger wahout" title="'.__("larger font size","wp-accessibility-helper").'" aria-label="'.__("larger font size","wp-accessibility-helper").'">A+'.$icon_widget1.'</button>';
        $wah_default_front_widget["widget-1"] = array(
            "active" => 1,
            "html"   => '<div class="a_module wah_font_resize separate_small_font_size">
                <div class="a_module_exe font_resizer">' . $smaller_font_size_button . '</div>
            </div>' . '<div class="a_module wah_font_resize separate_large_font_size">
                <div class="a_module_exe font_resizer">' . $larger_font_size_button . '</div>
            </div>' . '<div class="a_module wah_font_resize separate_reset_font_size">' . $reset_button . '</div>'
        );
    }

    $icon_widget2 = icons_enabled() ? '<span class="goi-keyboard"></span>' : '';
    $wah_default_front_widget["widget-2"] = array(
        "active"    => $wah_keyboard_navigation_setup,
        "html"      => '<div class="a_module wah_keyboard_navigation">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-2" type="button" class="wah-action-button wahout wah-call-keyboard-navigation"
                aria-label="'.$wah_keyboard_navigation_title.'" title="'.$wah_keyboard_navigation_title.'">'.$wah_keyboard_navigation_title.$icon_widget2.'</button>
            </div>
        </div>'
    );

    $icon_widget3 = icons_enabled() ? '<span class="goi-letter-inside-black"></span>' : '';
    $wah_default_front_widget["widget-3"] = array(
        "active"    => $wah_readable_fonts_setup,
        "html"      => '<div class="a_module wah_readable_fonts">
            <div class="a_module_exe readable_fonts">
                <button tabindex="-1" data-widgetid="widget-3" type="button" class="wah-action-button wahout wah-call-readable-fonts" aria-label="'.$wah_readable_fonts_title.'" title="'.$wah_readable_fonts_title.'">'.$wah_readable_fonts_title.$icon_widget3.'</button>
            </div>
        </div>'
    );

    if($custom_contrast_variations){
        $wah_default_front_widget["widget-4"] = array(
            "active"    => $contrast_setup
        );
        $wah_default_front_widget["widget-4"]["html"] = get_custom_contrast_variations($choose_color_title);
    } else {
        $icon_widget4 = icons_enabled() ? '<span class="goi-pipette"></span>' : '';
        $wah_default_front_widget["widget-4"] = array(
            "active"    => $contrast_setup,
            "html"      => '<div class="a_module wah_contrast_trigger">
                <div class="a_module_exe contrast_module_exe">
                    <button tabindex="-1" data-widgetid="widget-4" type="button" id="contrast_trigger" class="contrast_trigger wah-action-button wahout wah-call-contrast-trigger" title="Contrast">'.$choose_color_title.$icon_widget4.'</button>
                    <div class="color_selector" aria-hidden="true">
                        <button type="button" class="convar black wahout" data-bgcolor="#000" data-color="#FFF"
                        title="'.__("black","wp-accessibility-helper").'">'.__("black","wp-accessibility-helper").'<span style="color:#FFF !important;">T</span></button>
                        <button type="button" class="convar white wahout" data-bgcolor="#FFF" data-color="#000"
                        title="'.__("white","wp-accessibility-helper").'">'.__("white","wp-accessibility-helper").'<span style="color:#000 !important;">T</span></button>
                        <button type="button" class="convar green wahout" data-bgcolor="#00FF21" data-color="#000"
                        title="'.__("green","wp-accessibility-helper").'">'.__("green","wp-accessibility-helper").'<span style="color:#000 !important;">T</span></button>
                        <button type="button" class="convar blue wahout" data-bgcolor="#000" data-color="#FFD800"
                        title="'.__("blue","wp-accessibility-helper").'">'.__("blue","wp-accessibility-helper").'<span style="color:#000 !important;">T</span></button>
                        <button type="button" class="convar red wahout" data-bgcolor="#F00" data-color="#000"
                        title="'.__("red","wp-accessibility-helper").'">'.__("red","wp-accessibility-helper").'<span style="color:#000 !important;">T</span></button>
                        <button type="button" class="convar orange wahout" data-bgcolor="#FF6A00" data-color="#000" title="'.__("orange","wp-accessibility-helper").'">'.__("orange","wp-accessibility-helper").'<span style="color:#000 !important;">T</span></button>
                        <button type="button" class="convar yellow wahout" data-bgcolor="#FFD800" data-color="#000"
                        title="'.__("yellow","wp-accessibility-helper").'">'.__("yellow","wp-accessibility-helper").'<span style="color:#000 !important;">T</span></button>
                        <button type="button" class="convar navi wahout" data-bgcolor="#B200FF" data-color="#000"
                        title="'.__("navi","wp-accessibility-helper").'">'.__("navi","wp-accessibility-helper").'<span style="color:#000 !important;">T</span></button>
                    </div>
                </div>
            </div>'
        );
    }

    $icon_widget5 = icons_enabled() ? '<span class="goi-underline"></span>' : '';
    $wah_default_front_widget["widget-5"] = array(
        "active"    => $underline_links_setup,
        "html"      => '<div class="a_module wah_underline_links">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-5" type="button" class="wah-action-button wahout wah-call-underline-links" aria-label="'.$underline_links_setup_title.'" title="'.$underline_links_setup_title.'">'.$underline_links_setup_title.$icon_widget5.'</button>
            </div>
        </div>'
    );

    $icon_widget6 = icons_enabled() ? '<span class="goi-luminous-bulb"></span>' : '';
    $wah_default_front_widget["widget-6"] = array(
        "active"    => $wah_highlight_links_enable,
        "html"      => '<div class="a_module wah_highlight_links">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-6" type="button" class="wah-action-button wahout wah-call-highlight-links" aria-label="'.$wah_highlight_title.'" title="'.$wah_highlight_title.'">'.$wah_highlight_title.$icon_widget6.'</button>
            </div>
        </div>'
    );

    $icon_widget7 = icons_enabled() ? '<span class="goi-beveled-rows"></span>' : '';
    $wah_default_front_widget["widget-7"] = array(
        "active"    => 1,
        "html"      => '<div class="a_module wah_clear_cookies">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-7" type="button" class="wah-action-button wahout wah-call-clear-cookies"
                aria-label="'.$wah_clear_cookies_title.'" title="'.$wah_clear_cookies_title.'">'.$wah_clear_cookies_title.$icon_widget7.'</button>
            </div>
        </div>'
    );

    $icon_widget8 = icons_enabled() ? '<span class="goi-crossed-out-drop"></span>' : '';
    $wah_default_front_widget["widget-8"] = array(
        "active"    => $wah_greyscale_enable,
        "html"      => '<div class="a_module wah_greyscale">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-8" type="button" id="greyscale" class="greyscale wah-action-button wahout wah-call-greyscale"
                aria-label="'.$wah_greyscale_title.'" title="'.$wah_greyscale_title.'">'.$wah_greyscale_title.$icon_widget8.'</button>
            </div>
        </div>'
    );

    $icon_widget9 = icons_enabled() ? '<span class="goi-half-a-drop"></span>' : '';
    $wah_default_front_widget["widget-9"] = array(
        "active"    => $wah_invert_enable,
        "html"      => '<div class="a_module wah_invert">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-9" type="button" class="wah-action-button wahout wah-call-invert"
                aria-label="'.$wah_invert_title.'" title="'.$wah_invert_title.'">'.$wah_invert_title.$icon_widget9.'</button>
            </div>
        </div>'
    );

    $icon_widget10 = icons_enabled() ? '<span class="goi-animation"></span>' : '';
    $wah_default_front_widget["widget-10"] = array(
        "active"    => $wah_remove_animations_setup,
        "html"      => '<div class="a_module wah_remove_animations">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-10" type="button" accesskey="'.apply_filters( 'wah_remove_animations_accesskey', 'a' ).'" class="wah-action-button wahout wah-call-remove-animations"
                aria-label="'.$wah_remove_animations_title.'" title="'.$wah_remove_animations_title.'">'.$wah_remove_animations_title.$icon_widget10.'</button>
            </div>
        </div>'
    );

    $icon_widget11 = icons_enabled() ? '<span class="goi-delete"></span>' : '';
    $wah_default_front_widget["widget-11"] = array(
        "active"    => $remove_styles_setup,
        "html"      => '<div class="a_module wah_remove_styles">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-11" type="button" class="wah-action-button wahout wah-call-remove-styles"
                aria-label="'.$remove_styles_setup_title.'" title="'.$remove_styles_setup_title.'">'.$remove_styles_setup_title.$icon_widget11.'</button>
            </div>
        </div>'
    );

    $icon_widget12 = icons_enabled() ? '<span class="goi-bulb"></span>' : '';
    $wah_default_front_widget["widget-12"] = array(
        "active"    => $wah_lights_off_setup,
        "html"      => '<div class="a_module wah_lights_off">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-12" type="button" id="wah_lights_off" class="wah-action-button wahout wah-lights-off wah-call-lights-off"
                aria-label="'.$wah_lights_off_title.'">'.$wah_lights_off_title.$icon_widget12.'</button>
            </div>
        </div>'
    );

    $icon_widget13 = icons_enabled() ? '<span class="goi-bulb"></span>' : '';
    $wah_default_front_widget["widget-13"] = array(
        "active"    => $wah_highlight_titles_setup,
        "html"      => '<div class="a_module wah_highlight_titles_setup">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-13" type="button" id="wah_highlight_titles_setup" class="wah-action-button wahout wah-highlight-titles wah-call-highlight-titles"
                aria-label="'.$wah_highlight_titles_title.'">'.$wah_highlight_titles_title.$icon_widget13.'</button>
            </div>
        </div>'
    );

    $icon_widget14 = icons_enabled() ? '<span class="goi-image"></span>' : '';
    $wah_default_front_widget["widget-14"] = array(
        "active"    => $wah_image_alt_setup,
        "html"      => '<div class="a_module wah_image_alt_setup">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-14" type="button" id="wah_image_alt_setup" class="wah-action-button wahout wah-image-alt wah-call-image-alt"
                aria-label="'.$wah_image_alt_title.'">'.$wah_image_alt_title.$icon_widget14.'</button>
            </div>
        </div>'
    );

    $wah_default_front_widget["widget-15"] = array(
        "active"    => $wah_enable_terms_link,
        "html"      => '<div class="a_module wah_custom_link_setup">
            <div class="a_module_exe">
                <a href="'.$wah_custom_link_url.'" data-widgetid="widget-15" tabindex="-1" id="wah_custom_link" class="wah-action-button wahout is-wah-link"
                aria-label="'.$wah_custom_link_title.'">'.$wah_custom_link_title.'</a>
            </div>
        </div>'
    );

    $icon_widget16 = icons_enabled() ? '<span class="goi-arrows"></span>' : '';
    $wah_default_front_widget["widget-16"] = array(
        "active"    => $wah_enable_large_mouse_cursor,
        "html"      => '<div class="a_module wah_large_cursor">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-16" type="button" id="wah_large_cursor" class="wah-action-button wahout wah_large_cursor wah-call-large_cursor"
                aria-label="'.$wah_large_mouse_cursor_title.'">'.$wah_large_mouse_cursor_title.$icon_widget16.'</button>
            </div>
        </div>'
    );

    $icon_widget17 = icons_enabled() ? '<span class="goi-black-and-white"></span>' : '';
    $wah_default_front_widget["widget-17"] = array(
        "active"    => $wah_enable_monochrome_mode,
        "html"      => '<div class="a_module wah_enable_monochrome_mode">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-17" type="button" id="wah_enable_monochrome_mode" class="wah-action-button wahout wah_enable_monochrome_mode wah-call-monochrome_mode"
                aria-label="'.$wah_monochrome_mode_title.'">'.$wah_monochrome_mode_title.$icon_widget17.'</button>
            </div>
        </div>'
    );

    $icon_widget18 = icons_enabled() ? '<span class="goi-b-w-drop"></span>' : '';
    $wah_default_front_widget["widget-18"] = array(
        "active"    => $wah_enable_sepia_mode,
        "html"      => '<div class="a_module wah_enable_sepia_mode">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-18" type="button" id="wah_enable_sepia_mode" class="wah-action-button wahout wah_enable_sepia_mode wah-call-sepia_mode"
                aria-label="'.$wah_sepia_mode_title.'">'.$wah_sepia_mode_title.$icon_widget18.'</button>
            </div>
        </div>'
    );

    $icon_widget19 = icons_enabled() ? '<span class="goi-corners-screen"></span>' : '';
    $wah_default_front_widget["widget-19"] = array(
        "active"    => $wah_enable_inspector_mode,
        "html"      => '<div class="a_module wah_enable_inspector_mode">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-19" type="button" id="wah_enable_inspector_mode" class="wah-action-button wahout wah_enable_inspector_mode wah-call-inspector_mode"
                aria-label="'.$wah_inspector_mode_title.'">'.$wah_inspector_mode_title.$icon_widget19.'</button>
            </div>
        </div>'
    );

    $icon_widget20 = icons_enabled() ? '<span class="goi-color"></span>' : '';
    $wah_default_front_widget["widget-20"] = array(
        "active"    => $wah_set_layout_setup,
        "html"      => '<div class="a_module wah_set_wah_layout">
            <div class="a_module_exe set_wah_layout">
                <button tabindex="-1" data-widgetid="widget-20" type="button" id="wah_set_wah_layout_mode" class="wah-action-button wahout set-wah-layout-popup"
                aria-label="'.$wah_set_layout_title.'">'.$wah_set_layout_title.$icon_widget20.'</button>
            </div>
        </div>'
    );

    $icon_widget21 = icons_enabled() ? '<span class="goi-columns"></span>' : '';
    $wah_default_front_widget["widget-21"] = array(
        "active"    => $wah_enable_letter_spacing_mode,
        "html"      => '<div class="a_module wah_enable_letter_spacing_mode">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-21" type="button" id="wah_letter_spacing" class="wah-action-button wahout set-wah-letter_spacing"
                aria-label="'.$wah_letter_spacing_title.'">'.$wah_letter_spacing_title.$icon_widget21.'</button>
            </div>
        </div>'
    );

    $icon_widget22 = icons_enabled() ? '<span class="goi-view-eye"></span>' : '';
    $wah_default_front_widget["widget-22"] = array(
        "active"    => $wah_enable_adhd,
        "html"      => '<div class="a_module wah_enable_adhd_profile">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-22" type="button" id="wah_adhd_profile" class="wah-action-button wahout set-wah-adhd_profile"
                aria-label="'.$wah_adhd_button_title.'">'.$wah_adhd_button_title.$icon_widget22.'</button>
            </div>
        </div>'
    );
    $icon_widget23_right = icons_enabled() ? '<span class="goi-align-right"></span>' : '';
    $icon_widget23_center = icons_enabled() ? '<span class="goi-align-center-hor"></span>' : '';
    $icon_widget23_left = icons_enabled() ? '<span class="goi-align-left"></span>' : '';
    if( $wah_sidebar_layout !='wah-bottom-fullwidth' ) {
        if( $wah_sidebar_layout == 'magic-sidebar' ) {
            $icon_widget23_right = $icon_widget23_center = $icon_widget23_left = '';
        }
        $wah_default_front_widget["widget-23"] = array(
            "active"    => $wah_text_alignment,
            "html"      => '<div class="a_module wah_text_alignment_wrapper">
                <div class="a_module_exe">
                    <button tabindex="-1" data-widgetid="widget-23" type="button" id="wah_text_alignment_left" class="wah-action-button wah-uniqid wahout set-wah_text_alignment_left"
                    aria-label="'.$wah_text_alignment_left.'" data-wahaction="align-left">'.$wah_text_alignment_left.$icon_widget23_left.'</button>
                </div>
                <div class="a_module_exe">
                    <button tabindex="-1" data-widgetid="widget-23" type="button" id="wah_text_alignment_center" class="wah-action-button wah-uniqid wahout set-wah_text_alignment_center"
                    aria-label="'.$wah_text_alignment_center.'" data-wahaction="align-center">'.$wah_text_alignment_center.$icon_widget23_center.'</button>
                </div>
                <div class="a_module_exe">
                    <button tabindex="-1" data-widgetid="widget-23" type="button" id="wah_text_alignment_right" class="wah-action-button wah-uniqid wahout set-wah_text_alignment_right"
                    aria-label="'.$wah_text_alignment_right.'" data-wahaction="align-right">'.$wah_text_alignment_right.$icon_widget23_right.'</button>
                </div>
            </div>'
        );
    } else {
        $wah_default_front_widget["widget-23"] = array(
            "active"    => $wah_text_alignment,
            "html"      => '
                <div class="a_module"><div class="a_module_exe">
                    <button tabindex="-1" data-widgetid="widget-23" type="button" id="wah_text_alignment_left" class="wah-action-button wah-uniqid wahout set-wah_text_alignment_left"
                    aria-label="'.$wah_text_alignment_left.'" data-wahaction="align-left">'.$wah_text_alignment_left.$icon_widget23_left.'</button>
                </div></div>
                <div class="a_module"><div class="a_module_exe">
                    <button tabindex="-1" data-widgetid="widget-23" type="button" id="wah_text_alignment_center" class="wah-action-button wah-uniqid wahout set-wah_text_alignment_center"
                    aria-label="'.$wah_text_alignment_center.'" data-wahaction="align-center">'.$wah_text_alignment_center.$icon_widget23_center.'</button>
                </div></div>
                <div class="a_module"><div class="a_module_exe">
                    <button tabindex="-1" data-widgetid="widget-23" type="button" id="wah_text_alignment_right" class="wah-action-button wah-uniqid wahout set-wah_text_alignment_right"
                    aria-label="'.$wah_text_alignment_right.'" data-wahaction="align-right">'.$wah_text_alignment_right.$icon_widget23_right.'</button>
                </div></div>'
        );
    }

    $icon_widget24 = icons_enabled() ? '<span class="goi-sound-full"></span>' : '';
    $wah_default_front_widget["widget-24"] = array(
        "active"    => $wah_enable_mute,
        "html"      => '<div class="a_module wah_enable_mute">
            <div class="a_module_exe">
                <button tabindex="-1" data-widgetid="widget-24" type="button" id="wah_enable_mute" class="wah-action-button wahout set-wah-mute"
                aria-label="'.$wah_mute_button_title.'">'.$wah_mute_button_title.$icon_widget24.'</button>
            </div>
        </div>'
    );


    return $wah_default_front_widget;
}

function wah_get_user_wahstyle(){
    if( isset( $_COOKIE['user_wahstyle'] ) && $_COOKIE['user_wahstyle'] ){
        $wah_sidebar_layout = $_COOKIE['user_wahstyle'];
    } else {
        $wah_sidebar_layout = wah_get_param( 'wah_sidebar_layout' ) ? wah_get_param( 'wah_sidebar_layout' ) : 'standart-sidebar';
    }
    return $wah_sidebar_layout;
}

function wah_calculate_enabled_widgets(){
    $front_widgets     = wah_get_front_widgets_list();
    $enabled_widgets   = array();
    $wah_widgets_order = wah_get_param('wah_sidebar_widgets_order');
    if($wah_widgets_order){
        $wah_widgets       = unserialize($wah_widgets_order);
        foreach ($wah_widgets as $id=>$value) {
            if($value["active"] && $value["active"] == 1){
                $enabled_widgets[$id] = $front_widgets[$id];
            }
        }
    } else {
        foreach ($front_widgets as $id=>$value) {
            if($value["active"] && $value["active"] == 1){
                $enabled_widgets[$id] = $front_widgets[$id];
            }
        }
    }

	return apply_filters('wah_enabled_widgets',$enabled_widgets);
}

function wah_render_enabled_widgets_list(){
    $enabled_widgets = wah_calculate_enabled_widgets();
    foreach($enabled_widgets as $wah_widget){
        echo $wah_widget["html"];
    }
}

function wah_default_contrast_options(){
    $contrast_array = array();
    $contrast_array["contrast-1"] = array(
        "label"   => "black",
        "bgcolor" => "#000",
        "color"   => "#FFF"
    );
    $contrast_array["contrast-2"] = array(
        "label"   => "white",
        "bgcolor" => "#FFF",
        "color"   => "#000"
    );
    $contrast_array["contrast-3"] = array(
        "label"   => "green",
        "bgcolor" => "#00FF21",
        "color"   => "#000"
    );
    $contrast_array["contrast-4"] = array(
        "label"   => "blue",
        "bgcolor" => "#0FF",
        "color"   => "#000"
    );
    $contrast_array["contrast-5"] = array(
        "label"   => "red",
        "bgcolor" => "#F00",
        "color"   => "#000"
    );
    $contrast_array["contrast-6"] = array(
        "label"   => "orange",
        "bgcolor" => "#FF6A00",
        "color"   => "#000"
    );
    $contrast_array["contrast-7"] = array(
        "label"   => "yellow",
        "bgcolor" => "#FFD800",
        "color"   => "#000"
    );
    $contrast_array["contrast-8"] = array(
        "label"   => "navi",
        "bgcolor" => "#B200FF",
        "color"   => "#000"
    );

    return $contrast_array;
}

function get_custom_contrast_variations($choose_color_title){
    $contrast_variations = wah_get_param('wah_contrast_variations');
    $contrast_variations = unserialize($contrast_variations);
    $custom_contrast_html = '';
    $icon_widget4 = icons_enabled() ? '<span class="goi-pipette"></span>' : '';
    $wah_sidebar_layout = wah_get_user_wahstyle();
    ob_start();
    if($contrast_variations){  ?>
            <div class="a_module">
                <div class="a_module_exe">
                    <button type="button" id="contrast_trigger" class="contrast_trigger wah-action-button wahout wah-call-contrast-trigger">
                        <?php echo $choose_color_title . $icon_widget4; ?>
                    </button>
                    <div class="color_selector" aria-hidden="true">
                        <?php foreach($contrast_variations as $contrast) : ?>
                            <button type="button" class="convar wahout wahcolor" style="background:#<?php echo $contrast['bgcolor']; ?> !important;" data-bgcolor="#<?php echo $contrast['bgcolor']; ?>" data-color="#<?php echo $contrast['textcolor']; ?>" title="<?php echo $contrast['title']; ?>">
                                <span class="wah-screen-reader-text"><?php echo $contrast['title']; ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
    <?php }
    $custom_contrast_html = ob_get_clean();
    return $custom_contrast_html;

}

function wah_get_limit_contrast_variations(){
    return 5;
}

function wah_render_last_skiplink(){
    $close_button_title = wah_get_param('wah_close_button_title') ? wah_get_param('wah_close_button_title'): __("Close","wp-accessibility-helper");
    ?>
    <button type="button" title="<?php _e("Close sidebar","wp-accessibility-helper"); ?>" class="wah-skip close-wah-sidebar">
        <?php echo $close_button_title; ?>
    </button>
<?php }

/*******************************
    WAH PRO Accessibility bar
********************************/
//todo: add params to this function (show/hide icons, custom icons image url)
function wah_accessibility_minibar(){ ?>

  <?php do_action('wah_access_bar_before'); ?>

  <div class="wah-access-bar-container">

    <?php do_action('wah_access_bar_inner_before'); ?>

      <div class="wah-access-bar-buttons">
        <button type="button" name="wah-fontsize-toggle">
            <span class="goi-font-size-plus"></span>
        </button>
        <button type="button" name="wah-contrast-toggle">
            <span class="goi-black-and-white"></span>
        </button>
        <button type="button" name="wah-invert-toggle">
            <span class="goi-crossed-out-drop"></span>
        </button>
      </div>

    <?php do_action('wah_access_bar_inner_after'); ?>

  </div>

  <?php do_action('wah_access_bar_after'); ?>

<?php }

/**************************************
    WPML Support
*************************************/
function is_wah_wpml(){
    if ( function_exists('icl_object_id') ) {
        return true;
    }
    return false;
}

function is_wah_polylang(){
    if( function_exists( 'pll_current_language' ) ){
        return true;
    }
    return false;
}

function wah_get_param( $option ){

    $wah_lang = '';
    $wah_enable_wpml_support = get_option( 'wah_enable_wpml_support' );

    if( is_wah_wpml() ){
        if( $wah_enable_wpml_support ){
            $wah_lang = '_'.ICL_LANGUAGE_CODE;
        }
    }

    if( is_wah_polylang() ){
        $wah_lang = '_' . pll_current_language('slug');
    }

    $param = get_option( $option . $wah_lang );

    return $param;
}

function wah_set_param( $option, $value ){

    $wah_lang = '';
    $wah_enable_wpml_support = get_option( 'wah_enable_wpml_support' );

    if( is_wah_wpml() ){
        if( $wah_enable_wpml_support ){
            $wah_lang = '_'.ICL_LANGUAGE_CODE;
        }
    }

    if( is_wah_polylang() ){
        $wah_lang = '_' . pll_current_language('slug');
    }

    update_option( $option . $wah_lang, $value );

}

function wah_get_custom_button_params(){
    $wah_logo_customizer  = wah_get_param( 'wah_logo_customizer' );
    $custom_button_params = array();

    if( $wah_logo_customizer ){
        $custom_button_params = array(
            'wah_logo_bg'    => wah_get_param('wah_logo_bg'),
            'wah_logo_color' => wah_get_param('wah_logo_color')
        );
    }
    return $custom_button_params;
}

function wah_render_custom_gdpr_popup_style(){
    $wah_gdpr_custom_bg                  = wah_get_param( 'wah_gdpr_custom_bg' ) ? wah_get_param( 'wah_gdpr_custom_bg' ) : '#000000';
    $wah_gdpr_custom_text_color          = wah_get_param( 'wah_gdpr_custom_text_color' ) ? wah_get_param( 'wah_gdpr_custom_text_color' ) : '#ffffff';
    $wah_gdpr_custom_link_color          = wah_get_param( 'wah_gdpr_custom_link_color' ) ? wah_get_param( 'wah_gdpr_custom_link_color' ) : '#ff8040';
    $wah_gdpr_custom_accept_button_color = wah_get_param( 'wah_gdpr_custom_accept_button_color' ) ? wah_get_param( 'wah_gdpr_custom_accept_button_color' ) : '#ffffff';
    $wah_gdpr_custom_accept_button_bg    = wah_get_param( 'wah_gdpr_custom_accept_button_bg' ) ? wah_get_param( 'wah_gdpr_custom_accept_button_bg' ) : '#008080';
    $wah_gdpr_custom_cancel_button_color = wah_get_param( 'wah_gdpr_custom_cancel_button_color' ) ? wah_get_param( 'wah_gdpr_custom_cancel_button_color' ) : '#ff8040';
    $wah_gdpr_custom_cancel_button_bg    = wah_get_param( 'wah_gdpr_custom_cancel_button_bg' ) ? wah_get_param( 'wah_gdpr_custom_cancel_button_bg' ) : '#ff0000';
    ob_start(); ?>
    <style media="screen">
        #wah-gdpr-popup { background: <?php echo $wah_gdpr_custom_bg; ?>; }
        #wah-gdpr-popup { color: <?php echo $wah_gdpr_custom_text_color; ?>; }
        #wah-gdpr-popup .wah-gdpr-popup-content a { color: <?php echo $wah_gdpr_custom_link_color; ?>; }
        .accept-wah-gdpr-popup { color: <?php echo $wah_gdpr_custom_accept_button_color; ?>; background: <?php echo $wah_gdpr_custom_accept_button_bg; ?>; }
        .close-wah-gdpr-popup { color: <?php echo $wah_gdpr_custom_cancel_button_color; ?>; background: <?php echo $wah_gdpr_custom_cancel_button_bg; ?>; }
    </style>
    <?php echo ob_get_clean();
}

/***************************************
*   Ajax hooks
****************************************/
add_action( 'wp_ajax_submit_wah_report_form', 'submit_wah_report_form' );
add_action( 'wp_ajax_nopriv_submit_wah_report_form', 'submit_wah_report_form' );
function submit_wah_report_form(){
    $response = array(
        'error' => true,
        'html'  => __('Error', 'wp-accessibility-helper')
    );
    $form = ( isset( $_POST['form'] ) && $_POST['form'] ) ? $_POST['form'] : array();
    parse_str( $form, $form_args );

    if( $form_args ){
        $page_url    = $form_args['wah_report_page_url'];
        $user_email  = $form_args['wah_report_user_email'];
        $subject     = $form_args['wah_report_subject'];
        $description = $form_args['wah_report_description'];
        $date        = date('d/m/Y');
        $mailto      = wah_get_param( 'wah_report_mailto' );

        $headers = "From: report@" . $_SERVER['SERVER_NAME'] . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $message = '<html><body>';

        $message .= '<h3>Accessibility report information:</h3>';

        $message .= '<p><strong>' . __("Page url:", "wp-accessibility-helper") . '</strong> ' . $page_url . '</p>';
        $message .= '<p><strong>' . __("User email:", "wp-accessibility-helper") . '</strong> ' . $user_email . '</p>';
        $message .= '<p><strong>' . __("Subject:", "wp-accessibility-helper") . '</strong> ' . $subject . '</p>';
        $message .= '<p><strong>' . __("Description:", "wp-accessibility-helper") . '</strong> ' . $description . '</p>';
        $message .= '<p><strong>' . __("Log date:", "wp-accessibility-helper") . '</strong> ' . $date . '</p>';

        $message .= '</body></html>';

        $wp_mail = wp_mail( $mailto, $subject, $message, $headers );

        if( $wp_mail ){
            $response['error'] = false;
            $response['html']  = __('Thank you, your report has been sent.');
        }

    }

    wp_send_json( $response );
}


function wah_render_bottom_links(){

    $wah_report_problem_enable = wah_get_param( 'wah_report_problem_enable' );
    $wah_enable_wah_credits    = wah_get_param( 'wah_enable_wah_credits' );
    $wah_sidebar_layout        = wah_get_param( 'wah_sidebar_layout' );
?>
    <?php if( $wah_report_problem_enable || $wah_enable_wah_credits ) : ?>

        <?php if( 'magic-sidebar' == $wah_sidebar_layout ) : ?>
            <div class="a_module wah-footer-links-module-wrapper">
        <?php endif; ?>

        <div class="wah-footer-links" data-layout="<?php echo $wah_sidebar_layout; ?>">
            <div class="wah-footer-links-inner">
                <ul>
                    <?php if( $wah_report_problem_enable ) : $wah_report_problem_title = wah_get_param( 'wah_report_problem_title' ); ?>
                        <li>
                            <a href="#" type="button" class="wah-popup-trigger wah-report-problem" data-dialogid="wah-report-problem">
                                <?php echo $wah_report_problem_title; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if( $wah_enable_wah_credits ) : ?>
                        <li>
                            <a href="https://accessibility-helper.co.il/" target="_blank">
                                <?php _e('Accessibility by WAH Pro', 'wp-accessibility-helper'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <?php if( 'magic-sidebar' == $wah_sidebar_layout ) : ?>
            </div>
        <?php endif; ?>

<?php endif;
}
