<?php
  $wah_hidden = isset($_POST['wah_hidden']) ? sanitize_text_field($_POST['wah_hidden']) : '';

  if( $wah_hidden == 'Y' && !empty($wah_hidden) ) {
    // Show custom icon
    $wah_custom_icon = isset($_POST['wah_custom_icon']) ? 1 : 0;
    wah_set_param('wah_custom_icon', $wah_custom_icon);
    //Upload icon
    $image_url = isset($_POST['wah_image_url']) ? $_POST['wah_image_url'] : '';
    wah_set_param('wah_image_url', $image_url);
    //Hide on mobile
    $wah_hide_on_mobile = isset($_POST['wah_hide_on_mobile']) ? 1 : 0;
    wah_set_param('wah_hide_on_mobile', $wah_hide_on_mobile);
    //Show on left side
    $wah_left_side = isset($_POST['wah_left_side']) ? 1 : 0;
    wah_set_param('wah_left_side', $wah_left_side);
    //Font resize mode
    $font_setup_type = isset($_POST['wah_font_setup_type']) ? sanitize_text_field($_POST['wah_font_setup_type']) : "zoom";
    wah_set_param('wah_font_setup_type', $font_setup_type);
    $reset_font_size_title = $_POST['wah_reset_font_size'];
    wah_set_param('wah_reset_font_size', $reset_font_size_title);
    //Remove styles mode
    $remove_styles_setup = isset($_POST['wah_remove_styles_setup']) ? 1 : 0;
      wah_set_param('wah_remove_styles_setup', $remove_styles_setup);
    $remove_styles_setup_title = isset($_POST['wah_remove_styles_setup_title']) ? sanitize_text_field($_POST['wah_remove_styles_setup_title']) : '';
      wah_set_param('wah_remove_styles_setup_title', $remove_styles_setup_title);
    //Contrast mode
    $contrast_setup = isset($_POST['wah_contrast_setup']) ? 1 : 0;
    wah_set_param('wah_contrast_setup', $contrast_setup);
    //Contrast custom colors
    $contrast_custom = isset($_POST['wah_enable_custom_contrast']) ? 1 : 0;
    wah_set_param('wah_enable_custom_contrast', $contrast_custom);

    $choose_color_title = isset($_POST['wah_choose_color_title']) ? sanitize_text_field($_POST['wah_choose_color_title']) : '';
    wah_set_param('wah_choose_color_title', $choose_color_title);
    //Underline links mode
    $underline_links_setup = isset($_POST['wah_underline_links_setup']) ? 1 : 0;
    wah_set_param('wah_underline_links_setup', $underline_links_setup);
    $underline_links_setup_title = isset($_POST['wah_underline_links_setup_title']) ? sanitize_text_field($_POST['wah_underline_links_setup_title']) : '';
    wah_set_param('wah_underline_links_setup_title', $underline_links_setup_title);
    //Role="link" mode
    $role_links_setup = isset($_POST['wah_role_links_setup']) ? 1 : 0;
    wah_set_param('wah_role_links_setup', $role_links_setup);
    //Remove link title attribute
    $remove_link_titles = isset($_POST['wah_remove_link_titles']) ? 1 : 0;
    wah_set_param('wah_remove_link_titles', $remove_link_titles);
    $wah_clear_cookies_title = isset($_POST['wah_clear_cookies_title']) ? sanitize_text_field($_POST['wah_clear_cookies_title']) : '';
      wah_set_param('wah_clear_cookies_title', $wah_clear_cookies_title);
    //Close button - title
    $close_button_title = isset($_POST['wah_close_button_title']) ? sanitize_text_field($_POST['wah_close_button_title']) : '';
    wah_set_param('wah_close_button_title', $close_button_title);
    // CLose button customizer
    $wah_customize_close_button = isset($_POST['wah_customize_close_button']) ? 1 : 0;
    wah_set_param('wah_customize_close_button', $wah_customize_close_button);
    // Close button - bg color
    $wah_close_btn_bg = isset( $_POST['wah_close_btn_bg'] ) ? $_POST['wah_close_btn_bg'] : '#236478';
    wah_set_param( 'wah_close_btn_bg', $wah_close_btn_bg );
    // Close button - color
    $wah_close_btn_color = isset( $_POST['wah_close_btn_color'] ) ? $_POST['wah_close_btn_color'] : '#FFFFFF';
    wah_set_param( 'wah_close_btn_color', $wah_close_btn_color );
    // Greyscale Images button title
    $wah_greyscale_title = isset($_POST['wah_greyscale_title']) ? sanitize_text_field($_POST['wah_greyscale_title']) : '';
    wah_set_param('wah_greyscale_title', $wah_greyscale_title);
    // Greyscale Image Selectors
    $wah_greyscale_image_selectors = isset($_POST['wah_greyscale_image_selectors']) ? sanitize_text_field($_POST['wah_greyscale_image_selectors']) : '';
    wah_set_param('wah_greyscale_image_selectors', $wah_greyscale_image_selectors);
    //Enable Greyscale Images
    $wah_greyscale_enable = isset($_POST['wah_greyscale_enable']) ? 1 : 0;
    wah_set_param('wah_greyscale_enable', $wah_greyscale_enable);
    //Enable Dark Theme
    $wah_darktheme_enable = isset($_POST['wah_darktheme_enable']) ? 1 : 0;
    wah_set_param('wah_darktheme_enable', $wah_darktheme_enable);
    //highlight links
    $wah_highlight_links_enable = isset($_POST['wah_highlight_links_enable']) ? 1 : 0;
    wah_set_param('wah_highlight_links_enable', $wah_highlight_links_enable);
    $wah_highlight_links_title = isset($_POST['wah_highlight_links_title']) ? sanitize_text_field($_POST['wah_highlight_links_title']) : '';
    wah_set_param('wah_highlight_links_title', $wah_highlight_links_title);
    //invert mode
    $wah_invert_enable = isset($_POST['wah_invert_enable']) ? 1 : 0;
    wah_set_param('wah_invert_enable', $wah_invert_enable);
    $wah_invert_title = isset($_POST['wah_invert_title']) ? sanitize_text_field($_POST['wah_invert_title']) : '';
    wah_set_param('wah_invert_title', $wah_invert_title);
    //remove animations
    $wah_remove_animations_setup = isset($_POST['wah_remove_animations_setup']) ? 1 : 0;
    wah_set_param('wah_remove_animations_setup', $wah_remove_animations_setup);
    $wah_remove_animations_title = isset($_POST['wah_remove_animations_title']) ? sanitize_text_field($_POST['wah_remove_animations_title']) : '';
    wah_set_param('wah_remove_animations_title', $wah_remove_animations_title);
    //Readable font
    $wah_readable_fonts_setup = isset($_POST['wah_readable_fonts_setup']) ? 1 : 0;
    wah_set_param('wah_readable_fonts_setup', $wah_readable_fonts_setup);
    $wah_readable_fonts_title = isset($_POST['wah_readable_fonts_title']) ? sanitize_text_field($_POST['wah_readable_fonts_title']) : '';
    wah_set_param('wah_readable_fonts_title', $wah_readable_fonts_title);
    //Custom font
    $wah_custom_font = isset($_POST['wah_custom_font']) ? sanitize_text_field($_POST['wah_custom_font']) : '';
    wah_set_param('wah_custom_font', $wah_custom_font);
    // Skiplinks
    $wah_skiplinks_setup = isset($_POST['wah_skiplinks_setup']) ? 1 : 0;
    wah_set_param('wah_skiplinks_setup', $wah_skiplinks_setup);

    // Enable log
    $wah_enable_log = isset( $_POST['wah_enable_log'] ) ? true : false;
    wah_set_param('wah_enable_log', $wah_enable_log);
    //Keyboard Navigation
    $wah_keyboard_navigation_setup = isset($_POST['wah_keyboard_navigation_setup']) ? 1 : 0;
    wah_set_param('wah_keyboard_navigation_setup', $wah_keyboard_navigation_setup);
    $wah_keyboard_navigation_title = isset($_POST['wah_keyboard_navigation_title']) ? sanitize_text_field($_POST['wah_keyboard_navigation_title']) : '';
    wah_set_param('wah_keyboard_navigation_title', $wah_keyboard_navigation_title);
    //Light OFF
    $wah_lights_off_setup = isset($_POST['wah_lights_off_setup']) ? 1 : 0;
    wah_set_param('wah_lights_off_setup', $wah_lights_off_setup);
    $wah_lights_off_title = isset($_POST['wah_lights_off_title']) ? sanitize_text_field($_POST['wah_lights_off_title']) : '';
    wah_set_param('wah_lights_off_title', $wah_lights_off_title);
    $wah_lights_selector = isset($_POST['wah_lights_selector']) ? sanitize_text_field($_POST['wah_lights_selector']) : '';
    wah_set_param('wah_lights_selector', $wah_lights_selector);
    //Custom logo position
    $wah_custom_logo_position = isset($_POST['wah_custom_logo_position']) ? 1 : 0;
    wah_set_param('wah_custom_logo_position', $wah_custom_logo_position);
    $wah_logo_top = isset($_POST['wah_logo_top']) ? sanitize_text_field($_POST['wah_logo_top']) : '';
    wah_set_param('wah_logo_top', $wah_logo_top);
    $wah_logo_right = isset($_POST['wah_logo_right']) ? sanitize_text_field($_POST['wah_logo_right']) : '';
    wah_set_param('wah_logo_right', $wah_logo_right);
    $wah_logo_bottom = isset($_POST['wah_logo_bottom']) ? sanitize_text_field($_POST['wah_logo_bottom']) : '';
    wah_set_param('wah_logo_bottom', $wah_logo_bottom);
    $wah_logo_left = isset($_POST['wah_logo_left']) ? sanitize_text_field($_POST['wah_logo_left']) : '';
    wah_set_param('wah_logo_left', $wah_logo_left);

    // [PRO] Enable button icons
    $wah_enable_icons = isset($_POST['wah_enable_icons']) ? 1 : 0;
    wah_set_param('wah_enable_icons', $wah_enable_icons);

    // [PRO] highlight titles (h1,h2,h3,h4,h5,h6)
    $wah_highlight_titles_setup = isset($_POST['wah_highlight_titles_setup']) ? 1 : 0;
    wah_set_param('wah_highlight_titles_setup', $wah_highlight_titles_setup);
    $wah_highlight_titles_title = isset($_POST['wah_highlight_titles_title']) ? sanitize_text_field($_POST['wah_highlight_titles_title']) : '';
    wah_set_param('wah_highlight_titles_title', $wah_highlight_titles_title);

    $wah_custom_title_selector_on = isset($_POST['wah_custom_title_selector_on']) ? 1 : 0;
    wah_set_param('wah_custom_title_selector_on', $wah_custom_title_selector_on);
    $wah_custom_title_selector = isset($_POST['wah_custom_title_selector']) ? sanitize_text_field($_POST['wah_custom_title_selector']) : '';
    wah_set_param('wah_custom_title_selector', $wah_custom_title_selector);

    // [PRO] image description (alt tags)
    $wah_image_alt_setup = isset($_POST['wah_image_alt_setup']) ? 1 : 0;
    wah_set_param('wah_image_alt_setup', $wah_image_alt_setup);
    $wah_image_alt_title = isset($_POST['wah_image_alt_title']) ? sanitize_text_field($_POST['wah_image_alt_title']) : '';
    wah_set_param('wah_image_alt_title', $wah_image_alt_title);

    // [PRO] Enable WPML support
    $wah_enable_wpml_support = isset( $_POST['wah_enable_wpml_support'] ) ? 1 : 0;
    wah_set_param('wah_enable_wpml_support', $wah_enable_wpml_support);

    // [PRO] Sidebar layouts selector
    $wah_sidebar_layout = isset( $_POST['wah_sidebar_layout'] ) ? sanitize_text_field( $_POST['wah_sidebar_layout'] ) : '';
    wah_set_param( 'wah_sidebar_layout', $wah_sidebar_layout );

    // [PRO] WAH Logo customizer
    $wah_logo_customizer = isset( $_POST['wah_logo_customizer'] ) ? 1 : 0;
    wah_set_param( 'wah_logo_customizer', $wah_logo_customizer );
    $wah_logo_bg = isset( $_POST['wah_logo_bg'] ) ? $_POST['wah_logo_bg'] : '#000000';
    wah_set_param( 'wah_logo_bg', $wah_logo_bg );
    $wah_logo_color = isset( $_POST['wah_logo_color'] ) ? $_POST['wah_logo_color'] : '#ffffff';
    wah_set_param( 'wah_logo_color', $wah_logo_color );

    // [PRO] Enable Terms link
    $wah_enable_terms_link = isset( $_POST['wah_enable_terms_link'] ) ? 1 : 0;
    wah_set_param('wah_enable_terms_link', $wah_enable_terms_link);

    $wah_custom_link_title = isset( $_POST['wah_custom_link_title'] ) ? $_POST['wah_custom_link_title'] : '';
    wah_set_param('wah_custom_link_title', $wah_custom_link_title);

    $wah_custom_link_url = isset( $_POST['wah_custom_link_url'] ) ? $_POST['wah_custom_link_url'] : '';
    wah_set_param('wah_custom_link_url', $wah_custom_link_url);

    // [PRO] Cookies
    $wah_cookies = isset( $_POST['wah_cookies'] ) ? $_POST['wah_cookies'] : 14;
    wah_set_param('wah_cookies', $wah_cookies);

    // [PRO] Large mouse cursor
    $wah_enable_large_mouse_cursor = isset( $_POST['wah_enable_large_mouse_cursor'] ) ? 1 : 0;
    wah_set_param('wah_enable_large_mouse_cursor', $wah_enable_large_mouse_cursor);

    $wah_large_mouse_cursor_title = isset( $_POST['wah_large_mouse_cursor_title'] ) ? $_POST['wah_large_mouse_cursor_title'] : '';
    wah_set_param('wah_large_mouse_cursor_title', $wah_large_mouse_cursor_title);

    $wah_enable_monochrome_mode = isset( $_POST['wah_enable_monochrome_mode'] ) ? 1 : 0;
    wah_set_param('wah_enable_monochrome_mode', $wah_enable_monochrome_mode);
    $wah_monochrome_mode_title = isset( $_POST['wah_monochrome_mode_title'] ) ? $_POST['wah_monochrome_mode_title'] : '';
    wah_set_param('wah_monochrome_mode_title', $wah_monochrome_mode_title);

    $wah_enable_sepia_mode = isset( $_POST['wah_enable_sepia_mode'] ) ? 1 : 0;
    wah_set_param('wah_enable_sepia_mode', $wah_enable_sepia_mode);
    $wah_sepia_mode_title = isset( $_POST['wah_sepia_mode_title'] ) ? $_POST['wah_sepia_mode_title'] : '';
    wah_set_param('wah_sepia_mode_title', $wah_sepia_mode_title);

    // [PRO] inspector mode
    $wah_enable_inspector_mode = isset( $_POST['wah_enable_inspector_mode'] ) ? 1 : 0;
    wah_set_param('wah_enable_inspector_mode', $wah_enable_inspector_mode);
    $wah_inspector_mode_title = isset( $_POST['wah_inspector_mode_title'] ) ? $_POST['wah_inspector_mode_title'] : '';
    wah_set_param('wah_inspector_mode_title', $wah_inspector_mode_title);

    // [PRO] set layout enable
    $wah_set_layout_setup = isset( $_POST['wah_set_layout_setup'] ) ? 1 : 0;
    wah_set_param('wah_set_layout_setup', $wah_set_layout_setup);
    // [PRO] set layout title
    $wah_set_layout_title = isset( $_POST['wah_set_layout_title'] ) ? $_POST['wah_set_layout_title'] : '';
    wah_set_param('wah_set_layout_title', $wah_set_layout_title);
    // [PRO] set layout popup title
    $wah_set_layout_popup_title = isset( $_POST['wah_set_layout_popup_title'] ) ? $_POST['wah_set_layout_popup_title'] : '';
    wah_set_param('wah_set_layout_popup_title', $wah_set_layout_popup_title);

    // [PRO] letter spacing mode
    $wah_enable_letter_spacing_mode = isset( $_POST['wah_enable_letter_spacing_mode'] ) ? 1 : 0;
    wah_set_param('wah_enable_letter_spacing_mode', $wah_enable_letter_spacing_mode);
    $wah_letter_spacing_title = isset( $_POST['wah_letter_spacing_title'] ) ? $_POST['wah_letter_spacing_title'] : '';
    wah_set_param('wah_letter_spacing_title', $wah_letter_spacing_title);

    // [PRO] enable wah credits
    $wah_enable_wah_credits = isset( $_POST['wah_enable_wah_credits'] ) ? 1 : 0;
    wah_set_param('wah_enable_wah_credits', $wah_enable_wah_credits);

    // Report a problem module
    $wah_report_problem_enable = isset( $_POST['wah_report_problem_enable'] ) ? 1 : 0;
    wah_set_param('wah_report_problem_enable', $wah_report_problem_enable);
    $wah_report_problem_title = isset( $_POST['wah_report_problem_title'] ) ? sanitize_text_field($_POST['wah_report_problem_title']) : __('Report a problem','wp-accessibility-helper');
    wah_set_param('wah_report_problem_title', $wah_report_problem_title);
    $wah_report_popup_title = isset( $_POST['wah_report_popup_title'] ) ? $_POST['wah_report_popup_title'] : '';
    wah_set_param('wah_report_popup_title', $wah_report_popup_title);

    $wah_report_mailto = ( isset( $_POST['wah_report_mailto'] ) && $_POST['wah_report_mailto'] ) ? $_POST['wah_report_mailto'] : get_option('admin_email');
    wah_set_param('wah_report_mailto', $wah_report_mailto);

    // Web Speech API
    $wah_enable_web_speech = isset( $_POST['wah_enable_web_speech'] ) ? 1 : 0;
    wah_set_param('wah_enable_web_speech', $wah_enable_web_speech);

    // ADHD Friendly Profile
    $wah_enable_adhd = isset( $_POST['wah_enable_adhd'] ) ? 1 : 0;
    wah_set_param('wah_enable_adhd', $wah_enable_adhd);
    $wah_adhd_button_title = isset( $_POST['wah_adhd_button_title'] ) ? $_POST['wah_adhd_button_title'] : __('ADHD Friendly profile','wp-accessibility-helper');
    wah_set_param('wah_adhd_button_title', $wah_adhd_button_title );

    // Text alignment module
    $wah_text_alignment = isset( $_POST['wah_text_alignment'] ) ? 1 : 0;
    wah_set_param( 'wah_text_alignment', $wah_text_alignment );
    $wah_text_alignment_center = isset( $_POST['wah_text_alignment_center'] ) ? $_POST['wah_text_alignment_center'] : __('Text align center','wp-accessibility-helper');
    wah_set_param( 'wah_text_alignment_center', $wah_text_alignment_center );
    $wah_text_alignment_left = isset( $_POST['wah_text_alignment_left'] ) ? $_POST['wah_text_alignment_left'] : __('Text align left','wp-accessibility-helper');
    wah_set_param( 'wah_text_alignment_left', $wah_text_alignment_left );
    $wah_text_alignment_right = isset( $_POST['wah_text_alignment_right'] ) ? $_POST['wah_text_alignment_right'] : __('Text align right','wp-accessibility-helper');
    wah_set_param( 'wah_text_alignment_right', $wah_text_alignment_right );

    // MUTE
    $wah_enable_mute = isset( $_POST['wah_enable_mute'] ) ? 1 : 0;
    wah_set_param('wah_enable_mute', $wah_enable_adhd);
    $wah_mute_button_title = isset( $_POST['wah_mute_button_title'] ) ? $_POST['wah_mute_button_title'] : __('Mute volume','wp-accessibility-helper');
    wah_set_param('wah_mute_button_title', $wah_mute_button_title );

    // Accessibility statement
    $wah_statement_enable       = isset( $_POST['wah_statement_enable'] ) ? 1 : 0;
    wah_set_param('wah_statement_enable', $wah_statement_enable );
    $wah_statement_button_title = isset( $_POST['wah_statement_button_title'] ) ? $_POST['wah_statement_button_title'] : __('Accessibility statement','wp-accessibility-helper');
    wah_set_param( 'wah_statement_button_title', $wah_statement_button_title );
    $allowed_html = array (
        'a' => array (
            'href'   => array(),
            'class'  => array(),
            'target' => array(),
            'rel'    => array()
        ),
        'ul'     => array(),
        'ol'     => array(),
        'li'     => array(),
        'h1'     => array(),
        'h2'     => array(),
        'h3'     => array(),
        'h4'     => array(),
        'h5'     => array(),
        'h6'     => array(),
        'p'      => array(),
        'i'      => array(),
        'b'      => array(),
        'br'     => array(),
        'em'     => array(),
        'strong' => array()
    );
    $wah_statement_popup_content = isset( $_POST['wah_statement_popup_content'] ) ? wp_kses_post( stripslashes( $_POST['wah_statement_popup_content'] ) ) : '';
    wah_set_param( 'wah_statement_popup_content', $wah_statement_popup_content );

    //Update serialized array
    update_serialize_order_array();

?>
    <div class="notice notice-success is-dismissible">
    	<p><strong><?php _e('WAH Options saved.','wp-accessibility-helper'); ?></strong></p>
        <button type="button" class="notice-dismiss">
    		<span class="screen-reader-text">Dismiss this notice.</span>
    	</button>
    </div>
<?php

} else {

    $image_url                     = wah_get_param('wah_image_url'); // upload image url
    $wah_custom_icon               = wah_get_param('wah_custom_icon'); // enable custom icon
    $font_setup_type               = wah_get_param('wah_font_setup_type');
    $reset_font_size_title         = wah_get_param('wah_reset_font_size');
    $contrast_setup                = wah_get_param('wah_contrast_setup');
    $contrast_custom               = wah_get_param('wah_enable_custom_contrast');
    $remove_styles_setup           = wah_get_param('wah_remove_styles_setup');
    $remove_styles_setup_title     = wah_get_param('wah_remove_styles_setup_title');
    $choose_color_title            = wah_get_param('wah_choose_color_title');
    $underline_links_setup         = wah_get_param('wah_underline_links_setup');
    $underline_links_setup_title   = wah_get_param('wah_underline_links_setup_title');
    $role_links_setup              = wah_get_param('wah_role_links_setup');
    $remove_link_titles            = wah_get_param('wah_remove_link_titles');
    $wah_clear_cookies_title       = wah_get_param('wah_clear_cookies_title');
    $wah_custom_title_selector_on  = wah_get_param('wah_custom_title_selector_on');
    $wah_custom_title_selector      = wah_get_param('wah_custom_title_selector');
    $close_button_title            = wah_get_param('wah_close_button_title');
    $wah_close_btn_bg              = wah_get_param('wah_close_btn_bg');
    $wah_close_btn_color           = wah_get_param('wah_close_btn_color');
    $wah_customize_close_button    = wah_get_param('wah_customize_close_button');

    $wah_hide_on_mobile            = wah_get_param('wah_hide_on_mobile');
    $wah_left_side                 = wah_get_param('wah_left_side');
    $wah_greyscale_title           = wah_get_param('wah_greyscale_title');
    $wah_greyscale_image_selectors = wah_get_param('wah_greyscale_image_selectors');
    $wah_greyscale_enable          = wah_get_param('wah_greyscale_enable');
    $wah_darktheme_enable          = wah_get_param('wah_darktheme_enable');
    $wah_highlight_links_enable    = wah_get_param('wah_highlight_links_enable');
    $wah_highlight_links_title     = wah_get_param('wah_highlight_links_title');
    $wah_invert_enable             = wah_get_param('wah_invert_enable');
    $wah_invert_title              = wah_get_param('wah_invert_title');
    $wah_remove_animations_setup   = wah_get_param('wah_remove_animations_setup');
    $wah_remove_animations_title   = wah_get_param('wah_remove_animations_title');
    $wah_readable_fonts_setup      = wah_get_param('wah_readable_fonts_setup');
    $wah_readable_fonts_title      = wah_get_param('wah_readable_fonts_title');
    $wah_custom_font               = wah_get_param('wah_custom_font');
    $wah_skiplinks_setup           = wah_get_param('wah_skiplinks_setup');
    $wah_keyboard_navigation_setup = wah_get_param('wah_keyboard_navigation_setup');
    $wah_keyboard_navigation_title = wah_get_param('wah_keyboard_navigation_title');

    $wah_lights_off_setup = wah_get_param('wah_lights_off_setup');
    $wah_lights_off_title = wah_get_param('wah_lights_off_title');
    $wah_lights_selector  = wah_get_param('wah_lights_selector');

    $wah_highlight_titles_setup = wah_get_param('wah_highlight_titles_setup');
    $wah_highlight_titles_title = wah_get_param('wah_highlight_titles_title');

    $wah_image_alt_setup = wah_get_param('wah_image_alt_setup');
    $wah_image_alt_title = wah_get_param('wah_image_alt_title');

    $wah_custom_logo_position = wah_get_param('wah_custom_logo_position');
    $wah_logo_top    = wah_get_param('wah_logo_top');
    $wah_logo_right  = wah_get_param('wah_logo_right');
    $wah_logo_left   = wah_get_param('wah_logo_left');
    $wah_logo_bottom = wah_get_param('wah_logo_bottom');
    // Enable icons
    $wah_enable_icons = wah_get_param('wah_enable_icons');
    // Enable WPML support
    $wah_enable_wpml_support = wah_get_param( 'wah_enable_wpml_support' );
    // Sidebar layout selector
    $wah_sidebar_layout = wah_get_param( 'wah_sidebar_layout' ) ? wah_get_param( 'wah_sidebar_layout' ) : 'standart-sidebar';
    // Enable wah logo customizer
    $wah_logo_customizer = wah_get_param( 'wah_logo_customizer' );
    $wah_logo_bg         = wah_get_param( 'wah_logo_bg' );
    $wah_logo_color      = wah_get_param( 'wah_logo_color' );

    // Enable log
    $wah_enable_log      = wah_get_param( 'wah_enable_log' );
    // Enable terms link
    $wah_enable_terms_link = wah_get_param( 'wah_enable_terms_link' );
    $wah_custom_link_title = wah_get_param( 'wah_custom_link_title' );
    $wah_custom_link_url   = wah_get_param( 'wah_custom_link_url' );
    // Cookies days
    $wah_cookies   = wah_get_param( 'wah_cookies' );
    // Large mouse cursor
    $wah_enable_large_mouse_cursor = wah_get_param( 'wah_enable_large_mouse_cursor' );
    $wah_large_mouse_cursor_title = wah_get_param( 'wah_large_mouse_cursor_title' );
    // Monochrome mode
    $wah_enable_monochrome_mode = wah_get_param( 'wah_enable_monochrome_mode' );
    $wah_monochrome_mode_title  = wah_get_param( 'wah_monochrome_mode_title' );
    // Sepia mode
    $wah_enable_sepia_mode = wah_get_param( 'wah_enable_sepia_mode' );
    $wah_sepia_mode_title  = wah_get_param( 'wah_sepia_mode_title' );
    // Inspect mode
    $wah_enable_inspector_mode = wah_get_param( 'wah_enable_inspector_mode' );
    $wah_inspector_mode_title  = wah_get_param( 'wah_inspector_mode_title' );
    // Set layout popup title
    $wah_set_layout_title = wah_get_param( 'wah_set_layout_title' );
    $wah_set_layout_popup_title = wah_get_param( 'wah_set_layout_popup_title' );
    // Set layout setup
    $wah_set_layout_setup = wah_get_param( 'wah_set_layout_setup' );
    // Letter spacing mode
    $wah_enable_letter_spacing_mode = wah_get_param( 'wah_enable_letter_spacing_mode' );
    $wah_letter_spacing_title = wah_get_param( 'wah_letter_spacing_title' );
    // WAH credits
    $wah_enable_wah_credits = wah_get_param( 'wah_enable_wah_credits' );

    // report a problem
    $wah_report_problem_enable = wah_get_param( 'wah_report_problem_enable' );
    $wah_report_problem_title  = wah_get_param( 'wah_report_problem_title' );
    $wah_report_popup_title    = wah_get_param( 'wah_report_popup_title' );
    $wah_report_mailto         = wah_get_param( 'wah_report_mailto' );

    // Web Speach API
    $wah_enable_web_speech = wah_get_param( 'wah_enable_web_speech' );

    // ADHD
    $wah_enable_adhd       = wah_get_param( 'wah_enable_adhd' );
    $wah_adhd_button_title = wah_get_param( 'wah_adhd_button_title' );

    // MUTE
    $wah_enable_mute       = wah_get_param( 'wah_enable_mute' );
    $wah_mute_button_title = wah_get_param( 'wah_mute_button_title' );

    // Text alignment
    $wah_text_alignment        = wah_get_param( 'wah_text_alignment' );
    $wah_text_alignment_center = wah_get_param( 'wah_text_alignment_center' );
    $wah_text_alignment_left   = wah_get_param( 'wah_text_alignment_left' );
    $wah_text_alignment_right  = wah_get_param( 'wah_text_alignment_right' );

    // Accessibility statement
    $wah_statement_enable        = wah_get_param( 'wah_statement_enable' );
    $wah_statement_button_title  = wah_get_param( 'wah_statement_button_title' );
    $wah_statement_popup_content = wah_get_param( 'wah_statement_popup_content' );

}
    $wah_custom_fonts_list = array(
        'Times New Roman, Times, serif',
        'Arial, Helvetica, sans-serif',
        'Comic Sans MS, cursive, sans-serif',
        'Tahoma, Geneva, sans-serif',
        'Trebuchet MS, Helvetica, sans-serif',
        'Verdana, Geneva, sans-serif',
        'Courier New, Courier, monospace',
        'Lucida Console, Monaco, monospace',
        'Georgia, serif'
    );
?>

<div class="wrap">

    <?php echo "<h1 class='wah-admin-page-header'>" . __( 'WP Accessibility Helper. <span class="wah_slogan">"Wordpress Accessibility made easy!"</span>', 'wp-accessibility-helper' ) . "</h1>"; ?>

    <?php render_wah_header_notice(); ?>

    <form name="oscimp_form" class="clearfix" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

        <input type="hidden" name="wah_hidden" value="Y">

        <?php if( is_wah_wpml() ) : /* WPML Support */ ?>
            <?php render_form_section_title(__( 'WPML Settings', 'wp-accessibility-helper' )); ?>
            <div class="wah_form_elements_wrapper">
                <?php render_switch_element(__("Enable WPML support?","wp-accessibility-helper"), $wah_enable_wpml_support, "wah_enable_wpml_support"); ?>
                <br>
                <div style="float: left;width: 100%;margin-bottom: 10px;">
                    <p>
                      <strong><?php _e("What does it mean? Please read our", "wp-accessibility-helper"); ?>
                      <a href="https://accessibility-helper.co.il/docs/wpml-support/" style="text-decoration:underline;" target="_blank">
                        <?php _e("official documentation", "wp-accessibility-helper"); ?></a>,
                      <?php _e("before enable this option.", "wp-accessibility-helper"); ?></strong>
                    </p>
                </div>
                <br>
            </div>
        <?php endif; ?>

        <?php /* Global Settings */ ?>
        <?php render_form_section_title(__( 'Global Settings', 'wp-accessibility-helper' )); ?>

        <div class="wah_form_elements_wrapper">
            <div class="form_element_content">

                <div class="form_row wahpro-sidebar-layout-selector">

                    <div class="cc-selector-title">
                        <?php _e('Please select sidebar layout:','wp-accessibility-helper'); ?>
                    </div>

                    <div class="cc-selector">
                        <?php $sidebar_layouts = wah_get_sidebar_layouts(); // admin/functions.php ?>
                        <?php foreach( $sidebar_layouts as $name=>$label ) : ?>
                            <div class="cc-selector-item">
                                <span class="layout-description"><?php echo $label; ?><?php if($label=='Wide'): ?> (<?php _e('most useful', 'wp-accessibility-helper'); ?>)<?php endif; ?></span>
                                <input id="<?php echo $name; ?>" type="radio" name="wah_sidebar_layout" value="<?php echo $name; ?>"
                                    <?php if( $wah_sidebar_layout == $name ) : ?>checked<?php endif; ?> />
                                <label class="drinkcard-cc <?php echo $name; ?>" for="<?php echo $name; ?>"></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
                <hr />
                <?php render_switch_element(__("Enable buttons icons?","wp-accessibility-helper"), $wah_enable_icons, "wah_enable_icons"); ?>
                <?php render_switch_element(__("Enable layout control by user?","wp-accessibility-helper"), $wah_set_layout_setup, "wah_set_layout_setup" ); ?>
                <?php render_title_element(__("Select Theme - title","wp-accessibility-helper"),$wah_set_layout_title,"wah_set_layout_title","", "wah_set_layout_setup"); ?>
                <?php render_title_element(__("Set layout popup - title","wp-accessibility-helper"),$wah_set_layout_popup_title,"wah_set_layout_popup_title","", "wah_set_layout_setup"); ?>
                <hr />

                <?php render_switch_element(__("Enable skip links menu?","wp-accessibility-helper"),$wah_skiplinks_setup, "wah_skiplinks_setup"); ?>
                <h5 class="wah-property-description">
                    You can manage Skiplinks menu <a href="<?php echo admin_url().'nav-menus.php'; ?>">HERE</a>
                </h5>
                <h5 class="wah-property-description">
                    You can manage Skiplinks css <a href="<?php echo menu_page_url( 'wp_accessibility_landmark', false ); ?>">HERE</a>
                </h5>
                <hr />

                <?php render_switch_element(__("Enable keyboard navigation","wp-accessibility-helper"),$wah_keyboard_navigation_setup, "wah_keyboard_navigation_setup"); ?>

                <?php render_title_element(__("Keyboard navigation - title","wp-accessibility-helper"),$wah_keyboard_navigation_title, "wah_keyboard_navigation_title", "", "wah_keyboard_navigation_setup"
                ); ?>
                <hr />

                <?php render_switch_element(__("Enable Dark Theme?","wp-accessibility-helper"),$wah_darktheme_enable,"wah_darktheme_enable"); ?>
                <hr />
                <?php render_title_element(__("Close button - title","wp-accessibility-helper"),$close_button_title,"wah_close_button_title"); ?>

                <?php render_switch_element(__("Customize close button?","wp-accessibility-helper"),$wah_customize_close_button,"wah_customize_close_button"); ?>

                <div class="form_row wah-logo-customizer wah-close-btn-customizer" data-depid="wah_customize_close_button">
                    <div class="form30">
                        <div class="wah-input-color">
                            <label>
                                <span><?php _e('"Close" background:','wp-accessibility-helper'); ?></span>
                                <input type="color" name="wah_close_btn_bg" value="<?php echo $wah_close_btn_bg; ?>">
                            </label>
                            <label>
                                <span><?php _e('"Close" color:','wp-accessibility-helper'); ?></span>
                                <input type="color" name="wah_close_btn_color" value="<?php echo $wah_close_btn_color; ?>">
                            </label>
                        </div>
                    </div>
                    <div class="form70">
                        <div class="wah-logo-preview">
                            <div class="wah-preview-label">
                                <?php _e('Close button preview', 'wp-accessibility-helper'); ?>
                            </div>
                            <div class="wah-close-btn-preview-inner">
                                <button type="button" class="wahout aicon_link wah-close-btn-preview"
                                style="background-color:<?php echo $wah_close_btn_bg ? $wah_close_btn_bg : '#236478'; ?>;color:<?php echo $wah_close_btn_color ? $wah_close_btn_color : '#FFFFFF'; ?> !important;">
                                    <?php _e('Close','wp-accessibility-helper'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />

                <?php render_switch_element(__("Enable accessibility statement?","wp-accessibility-helper"),$wah_statement_enable, "wah_statement_enable"); ?>
                <?php render_title_element(__("Accessibility statement button","wp-accessibility-helper"),$wah_statement_button_title,"wah_statement_button_title", '', 'wah_statement_enable'); ?>
                <?php render_wp_editor_element(__("Accessibility statement content","wp-accessibility-helper"),$wah_statement_popup_content,"wah_statement_popup_content", $depid = 'wah_statement_enable'); ?>
                <hr />
                <?php render_title_element(__("Clear cookies - title","wp-accessibility-helper"),$wah_clear_cookies_title,"wah_clear_cookies_title"); ?>
            </div>

            <hr />

            <?php render_switch_element(__("Upload custom icon?","wp-accessibility-helper"),$wah_custom_icon,"wah_custom_icon"); ?>

            <div class="form_row" data-depid="wah_custom_icon">
                <div class="form30">
                      <?php if( wah_get_param('wah_image_url') ) : ?>
                        <img src="<?php echo wah_get_param('wah_image_url'); ?>" width="48" height="48" />
                      <?php endif; ?>
                      <label for="upload_icon" class="text_label"><?php _e("Upload icon","wp-accessibility-helper"); ?></label>
                </div>
                <div class="form70">
            		<input type="text" name='wah_image_url' id="image_url"
            			class="regular-text" value='<?php echo wah_get_param('wah_image_url') ? wah_get_param('wah_image_url') : ''; ?>'>
            		<input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="<?php _e("Upload Logo","wp-accessibility-helper"); ?>">
            		<input type="button" name="clear-btn" id="clear-btn" class="button-secondary" value="<?php _e("Delete Logo","wp-accessibility-helper"); ?>">
            	</div>
            </div>

            <?php /***** Custom logo position ********/ ?>
            <?php render_switch_element(__("Custom logo position?","wp-accessibility-helper"), $wah_custom_logo_position, "wah_custom_logo_position"); ?>
            <?php render_logo_position(__("Logo position (px)","wp-accessibility-helper"), $wah_logo_top, $wah_logo_right, $wah_logo_bottom, $wah_logo_left); ?>

            <?php render_switch_element(__("Enable WAH logo customizer?","wp-accessibility-helper"), $wah_logo_customizer, "wah_logo_customizer"); ?>

            <div class="form_row wah-logo-customizer" data-depid="wah_logo_customizer">
                <div class="form30">
                    <div class="wah-input-color">
                        <label>
                            <span><?php _e('Button background:','wp-accessibility-helper'); ?></span>
                            <input type="color" name="wah_logo_bg" value="<?php echo $wah_logo_bg; ?>">
                        </label>
                        <label>
                            <span><?php _e('Button color:','wp-accessibility-helper'); ?></span>
                            <input type="color" name="wah_logo_color" value="<?php echo $wah_logo_color; ?>">
                        </label>
                    </div>
                </div>
                <div class="form70">
                    <div class="wah-logo-preview">
                        <div class="wah-preview-label">
                            <?php _e('Live button preview', 'wp-accessibility-helper'); ?>
                        </div>
                        <div class="wah-preview-inner">
                            <button type="button" class="wahout aicon_link" style="font-size:30px;background-color:<?php echo $wah_logo_bg; ?>;border:0; padding: 0;width: 50px;height: 50px;">
                                <span class="goi-wah-icon wah-font-icon" style="font-size:30px;color:<?php echo $wah_logo_color; ?> !important;"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <hr />

            <?php render_switch_element(__("Hide for mobile?","wp-accessibility-helper"), $wah_hide_on_mobile, "wah_hide_on_mobile"); ?>
            <?php
                if( $wah_sidebar_layout != 'wah-bottom-fullwidth' ){
                    render_switch_element(__("Show Sidebar on left side?","wp-accessibility-helper"), $wah_left_side, "wah_left_side");
                }
            ?>

            <hr />
            <?php /***** Greyscale Images ********/ ?>
            <?php render_switch_element(__("Enable Greyscale Images?","wp-accessibility-helper"), $wah_greyscale_enable, "wah_greyscale_enable"); ?>
            <?php render_title_element(__("Greyscale Images button - title","wp-accessibility-helper"),$wah_greyscale_title,"wah_greyscale_title","","wah_greyscale_enable"); ?>
            <?php
                render_title_element(
                    __("Greyscale Image selectors","wp-accessibility-helper"),
                    $wah_greyscale_image_selectors,
                    "wah_greyscale_image_selectors",
                    "",
                    "wah_greyscale_enable",
                    $field_description = __("For example, comma separated selectors of html elements with background image: <code>.bg-image, #my-background</code>","wp-accessibility-helper"),
                    $is_textarea = true
                );
            ?>

            <hr />
            <?php /***** Invert Colors & Images ********/ ?>
            <?php render_switch_element(__("Enable Invert Colors & Images?","wp-accessibility-helper"), $wah_invert_enable, "wah_invert_enable"); ?>
            <?php render_title_element(__("Invert button - title","wp-accessibility-helper"),$wah_invert_title,"wah_invert_title","","wah_invert_enable"); ?>
            <hr />
            <?php /***** ADHD profile *****/ ?>
            <?php render_switch_element(__("Enable ADHD Friendly profile?","wp-accessibility-helper"), $wah_enable_adhd, "wah_enable_adhd"); ?>
            <?php render_title_element(__("ADHD button - title","wp-accessibility-helper"),$wah_adhd_button_title,"wah_adhd_button_title","","wah_enable_adhd"); ?>
            <hr />
            <?php /***** Mute/unmute *****/ ?>
            <?php render_switch_element(__("Enable Mute volume? [beta]","wp-accessibility-helper"), $wah_enable_mute, "wah_enable_mute"); ?>
            <?php render_title_element(__("Mute volume - title","wp-accessibility-helper"),$wah_mute_button_title,"wah_mute_button_title","","wah_enable_mute"); ?>
        </div>
        <hr />

        <?php /* Fonts Settings */ ?>
        <?php render_form_section_title(__( 'Font Settings', 'wp-accessibility-helper' )); ?>
        <div class="wah_form_elements_wrapper">
            <div class="form_element_content">

        <?php /** Readable Font **/ ?>
        <?php render_switch_element(__("Enable Readable Font?","wp-accessibility-helper"), $wah_readable_fonts_setup, "wah_readable_fonts_setup"); ?>
        <?php render_title_element(__("Readable Font - title","wp-accessibility-helper"),$wah_readable_fonts_title,"wah_readable_fonts_title","","wah_readable_fonts_setup"); ?>
        <hr />

        <?php /* wah_letter_spacing */ ?>
        <?php render_switch_element(__("Enable Letter spacing?","wp-accessibility-helper"), $wah_enable_letter_spacing_mode, "wah_enable_letter_spacing_mode"); ?>
        <?php render_title_element(__("Letter spacing - title","wp-accessibility-helper"),$wah_letter_spacing_title,"wah_letter_spacing_title","","wah_enable_letter_spacing_mode"); ?>
        <hr />

        <div class="form_row">
            <div class="form30">
                <label for="wah_custom_font" class="text_label">
                    <?php _e("Choose custom font","wp-accessibility-helper"); ?>
                </label>
            </div>
            <div class="form70">
                <select name="wah_custom_font" id="wah_custom_font">
                    <option value="">
                        <?php _e("Please, choose font","wp-accessibility-helper"); ?>
                    </option>
                    <?php foreach( $wah_custom_fonts_list as $font ): ?>
                        <option value="<?php echo $font; ?>" <?php if( $wah_custom_font == $font ) : ?>selected="selected"<?php endif; ?>>
                            <?php echo $font; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <hr />

        <?php render_select_element(__("Choose font resize option","wp-accessibility-helper"), $font_setup_type, "wah_font_setup_type"); ?>
        <?php render_title_element(__("Reset font size - title","wp-accessibility-helper"),$reset_font_size_title,"wah_reset_font_size"); ?>
        <h5>** <?php _e('This field work only when script base resize option chosen in "<em>Choose font resize option</em>"','wp-accessibility-helper'); ?></h5>
        </div>
        </div>
        <hr />

        <?php /* Contrast Settings */ ?>
        <?php render_form_section_title(__( 'Contrast Settings', 'wp-accessibility-helper' )); ?>
        <div class="wah_form_elements_wrapper">
            <div class="form_element_content">

                <?php render_switch_element(__("Enable contrast mode?","wp-accessibility-helper"), $contrast_setup, "wah_contrast_setup"); ?>
                <?php render_title_element(__("Choose color button - title","wp-accessibility-helper"), $choose_color_title, "wah_choose_color_title"); ?>

                <?php render_switch_element(__("Contrast variations?","wp-accessibility-helper"), $contrast_custom, "wah_enable_custom_contrast","Custom","Default"); ?>

                <div class="form_row" id="contrast_custom_dep" <?php if(!$contrast_custom): ?>style="display:none;"<?php endif; ?>>
                    <div class="form100">
                        <h4 class="wah-sub-title"><?php _e("Please add custom contrast mode variation:","wp-accessibility-helper"); ?></h4>
                        <ul class="contrast-params-list">
                            <?php
                            $contrast_variations = wah_get_contrast_variations();
                            if($contrast_variations):
                                foreach($contrast_variations as $variation) : ?>
                                <li>
                            		<div class="contrast-mode-item bg-color">
                            			<label><?php _e('Background color','wp-accessibility-helper'); ?></label>
                            			<input type="text" class="jscolor" placeholder="<?php _e('Background color','wp-accessibility-helper'); ?>" value="<?php echo $variation['bgcolor']; ?>" />
                            		</div>
                            		<div class="contrast-mode-item text-color">
                            			<label><?php _e('Text color','wp-accessibility-helper'); ?></label>
                            			<input type="text" class="jscolor" placeholder="<?php _e('Text color','wp-accessibility-helper'); ?>" value="<?php echo $variation['textcolor']; ?>" />
                            		</div>
                            		<div class="contrast-mode-item button-title-alt">
                            			<label><?php _e('Title','wp-accessibility-helper'); ?></label>
                            			<input type="text" placeholder="<?php _e('Button title','wp-accessibility-helper'); ?>" value="<?php echo $variation['title']; ?>" />
                            		</div>
                            		<div class="contrast-mode-item action">
                            			<button class="wah-button delete-contrast-params">
                            				<?php _e("Delete","wp-accessibility-helper"); ?>
                            			</button>
                            			<span class="action-loader"></span>
                            		</div>
                            	</li>
                                <?php endforeach;
                            endif; ?>
                        </ul>
                        <div class="wah-action-buttons">
                            <button class="wah-button wah-add-item">
                                <?php _e("Add new color","wp-accessibility-helper"); ?>
                            </button>
                            <button class="wah-button save-contrast-params">
                				<?php _e("Save colors","wp-accessibility-helper"); ?>
                			</button>
                            <div class="wah-contrast-loader"></div>
                        </div>
                    </div>
                </div>

                <?php render_switch_element(__("Enable monochrome mode?","wp-accessibility-helper"), $wah_enable_monochrome_mode, "wah_enable_monochrome_mode"); ?>
                <?php render_title_element(__("Monochrome mode - title","wp-accessibility-helper"), $wah_monochrome_mode_title, "wah_monochrome_mode_title","","wah_enable_monochrome_mode"); ?>

                <?php render_switch_element(__("Enable sepia mode?","wp-accessibility-helper"), $wah_enable_sepia_mode, "wah_enable_sepia_mode"); ?>
                <?php render_title_element(__("Sepia mode - title","wp-accessibility-helper"), $wah_sepia_mode_title, "wah_sepia_mode_title","","wah_enable_sepia_mode"); ?>

            </div>
        </div>
        <hr />

        <?php /* Styles Settings */ ?>
        <?php render_form_section_title(__( 'Styles Settings', 'wp-accessibility-helper' )); ?>
        <div class="wah_form_elements_wrapper">
            <div class="form_element_content">
                <?php render_switch_element(__("Remove animations mode?","wp-accessibility-helper"), $wah_remove_animations_setup, "wah_remove_animations_setup"); ?>
                <?php render_title_element(__("Remove animations - title","wp-accessibility-helper"),$wah_remove_animations_title,"wah_remove_animations_title","","wah_remove_animations_setup"); ?>
                <hr />
                <?php render_switch_element(__("Remove styles mode?","wp-accessibility-helper"), $remove_styles_setup, "wah_remove_styles_setup"); ?>
                <?php render_title_element(__("Remove styles - title","wp-accessibility-helper"),$remove_styles_setup_title,"wah_remove_styles_setup_title","","wah_remove_styles_setup"); ?>
                <h5>** <?php _e("This feature doesn't works if you have 'Async JS and CSS' plugin installed.","wp-accessibility-helper"); ?></h5>
                <hr>
                <?php render_switch_element(__("Enable large mouse cursor?","wp-accessibility-helper"), $wah_enable_large_mouse_cursor, "wah_enable_large_mouse_cursor"); ?>
                <?php render_title_element(__("Large mouse cursor - title","wp-accessibility-helper"),$wah_large_mouse_cursor_title,"wah_large_mouse_cursor_title","","wah_enable_large_mouse_cursor"); ?>
            </div>
        </div>
        <hr />

        <?php /* Links Settings */ ?>
        <?php render_form_section_title(__( 'Links Settings', 'wp-accessibility-helper' )); ?>
        <div class="wah_form_elements_wrapper">
            <div class="form_element_content">
              <?php render_switch_element(__("Underline links mode?","wp-accessibility-helper"), $underline_links_setup, "wah_underline_links_setup"); ?>
              <?php render_title_element(__("Underline links title","wp-accessibility-helper"), $underline_links_setup_title, "wah_underline_links_setup_title","","wah_underline_links_setup"); ?>
              <hr />
              <?php render_switch_element(__("Highlight links mode?","wp-accessibility-helper"), $wah_highlight_links_enable, "wah_highlight_links_enable"); ?>
              <?php render_title_element(__("Highlight links - title","wp-accessibility-helper"),$wah_highlight_links_title, "wah_highlight_links_title","","wah_highlight_links_enable"); ?>
              <hr />
              <?php render_switch_element(__('Add role="link" to a tags?',"wp-accessibility-helper"), $role_links_setup, "wah_role_links_setup"); ?>
              <?php render_switch_element(__("Remove all links titles?","wp-accessibility-helper"),$remove_link_titles, "wah_remove_link_titles"); ?>
            </div>
        </div>
        <hr />

        <?php /* Content Settings */ ?>
        <?php render_form_section_title(__( 'Content Settings', 'wp-accessibility-helper' )); ?>
        <div class="wah_form_elements_wrapper">
            <p>
                <a class="wah-help-link" href="https://www.youtube.com/watch?v=D3xEK0sdjWk" target="_blank" style="font-size:16px;">
                    <?php _e('Need Help with <strong>Lights Off mode</strong>? Check this video!', 'wp-accessibility-helper'); ?>
                </a>
            </p>
            <div class="form_element_content">

                <?php /* Text alignment module */ ?>
                <?php render_switch_element(__("Enable Text alignment?","wp-accessibility-helper"), $wah_text_alignment, "wah_text_alignment"); ?>
                <?php render_title_element(__("Text align center","wp-accessibility-helper"), $wah_text_alignment_center, "wah_text_alignment_center","","wah_text_alignment"); ?>
                <?php render_title_element(__("Text align left","wp-accessibility-helper"), $wah_text_alignment_left, "wah_text_alignment_left","","wah_text_alignment"); ?>
                <?php render_title_element(__("Text align right","wp-accessibility-helper"), $wah_text_alignment_right, "wah_text_alignment_right","","wah_text_alignment"); ?>
                <hr />

                <?php render_switch_element(__("Lights Off mode?","wp-accessibility-helper"), $wah_lights_off_setup, "wah_lights_off_setup"); ?>
                <?php render_title_element(__("Lights Off title","wp-accessibility-helper"), $wah_lights_off_title, "wah_lights_off_title","","wah_lights_off_setup"); ?>
                <?php render_title_element(__("Main content selector","wp-accessibility-helper"), $wah_lights_selector, "wah_lights_selector", "div class or id","wah_lights_off_setup"); ?>
                <hr />

                <?php /* Highlight titles */ ?>
                <?php render_switch_element(__("Highlight titles mode?","wp-accessibility-helper"), $wah_highlight_titles_setup, "wah_highlight_titles_setup"); ?>
                <div class="wah-small-description"><?php _e("Titles elements: H1, H2, H3, H4, H5, H6","wp-accessibility-helper"); ?></div>
                <?php render_title_element(__("Highlight titles title","wp-accessibility-helper"), $wah_highlight_titles_title, "wah_highlight_titles_title","","wah_highlight_titles_setup",""); ?>
                <?php render_switch_element(__("Custom title selector","wp-accessibility-helper"), $wah_custom_title_selector_on, "wah_custom_title_selector_on","On","Off", "wah_highlight_titles_setup"); ?>
                <div class="wah-small-description"><?php _e("Custom title elements, for example: <code>.title, #page-title</code>","wp-accessibility-helper"); ?></div>
                <?php render_title_element(__("Enter custom title selectors","wp-accessibility-helper"), $wah_custom_title_selector, "wah_custom_title_selector","","wah_custom_title_selector_on","", $is_textarea = true ); ?>
                <hr />

              <?php /* Display image description */ ?>
              <?php render_switch_element(__("Image description mode?","wp-accessibility-helper"), $wah_image_alt_setup, "wah_image_alt_setup"); ?>
              <?php render_title_element(__("Display image description - title","wp-accessibility-helper"), $wah_image_alt_title, "wah_image_alt_title","","wah_image_alt_setup"); ?>
              <?php /* Inspect mode */ ?>
              <?php render_switch_element(__("Enable inspector mode?","wp-accessibility-helper"), $wah_enable_inspector_mode, "wah_enable_inspector_mode"); ?>
              <?php render_title_element(__("Inspector mode - title","wp-accessibility-helper"), $wah_inspector_mode_title, "wah_inspector_mode_title","","wah_enable_inspector_mode", "", true); ?>
            </div>
        </div>
        <hr />

        <?php /* Terms and conditions [Custom link] */ ?>
        <?php render_form_section_title(__( 'Custom link/button', 'wp-accessibility-helper' )); ?>
        <div class="wah_form_elements_wrapper">
            <div class="form_element_content">
                <?php render_switch_element(__("Enable custom link?","wp-accessibility-helper"), $wah_enable_terms_link, "wah_enable_terms_link"); ?>
                <?php render_title_element(__("Custom link - title","wp-accessibility-helper"), $wah_custom_link_title, "wah_custom_link_title","","wah_enable_terms_link"); ?>
                <?php render_title_element(__("Custom link - URL","wp-accessibility-helper"), $wah_custom_link_url, "wah_custom_link_url","","wah_enable_terms_link"); ?>
            </div>
        </div>
        <hr />

        <?php /* Advanced Settings */ ?>
        <?php render_form_section_title(__( 'Advanced Settings', 'wp-accessibility-helper' )); ?>
        <div class="wah_form_elements_wrapper">
            <div class="form_element_content">

                <?php render_switch_element(__("Enable Web Speach API?","wp-accessibility-helper"), $wah_enable_web_speech, "wah_enable_web_speech"); ?>
                <p class="wah-small-description">
                    <ol>
                        <li><a href="https://developer.mozilla.org/en-US/docs/Web/API/Web_Speech_API" target="_blank">About Web Speech API</a></li>
                        <li><a href="https://caniuse.com/#feat=speech-synthesis" target="_blank">Check browsers support</a></li>
                        <li>* Only English language supported</li>
                    </ol>
                </p>
                <hr>
                <?php render_switch_element(__("Enable log?","wp-accessibility-helper"), $wah_enable_log, "wah_enable_log"); ?>
                <p class="wah-small-description"><?php _e('Display plugin log in "console log"', 'wp-accessibility-helper'); ?></p>
                <hr>
                <?php render_title_element(__("Cookies","wp-accessibility-helper"), $wah_cookies, "wah_cookies","","", "",false, true ); ?>
                <p class="wah-small-description"><?php _e('Enter days number to save users cookies', 'wp-accessibility-helper'); ?></p>
                <hr>
                <?php render_switch_element(__("Show WAH credits?","wp-accessibility-helper"), $wah_enable_wah_credits, "wah_enable_wah_credits"); ?>
                <p class="wah-small-description"><?php _e('Display WAH credits on the sidebar bottom', 'wp-accessibility-helper'); ?></p>
                <hr>
                <?php render_switch_element(__("Enable 'Report a problem'?","wp-accessibility-helper"),$wah_report_problem_enable,"wah_report_problem_enable"); ?>
                <?php render_title_element(__("Report a problem - title","wp-accessibility-helper"),$wah_report_problem_title,"wah_report_problem_title","","wah_report_problem_enable"); ?>
                <?php render_title_element(__("Popup title","wp-accessibility-helper"),$wah_report_popup_title,"wah_report_popup_title","","wah_report_problem_enable"); ?>
                <?php render_title_element(__("Mail to","wp-accessibility-helper"),$wah_report_mailto,"wah_report_mailto","","wah_report_problem_enable", __('Default: admin email. Multiple emails are supported (comma separated).', 'wp-accessibility-helper')); ?>

            </div>
        </div>
        <hr />

        <p class="submit">
            <input type="submit" name="Submit" class="button button-primary button-large" value="<?php _e('Update Options', 'wp-accessibility-helper' ) ?>" />
        </p>
    </form>

</div>
