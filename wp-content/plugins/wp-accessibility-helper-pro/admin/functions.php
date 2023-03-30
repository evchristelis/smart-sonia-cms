<?php
function get_pageNumber(){
    if( get_query_var('page') ) {
        $paged = get_query_var('page');
    } elseif( !empty( $_GET['paged'] ) && is_numeric( $_GET['paged'] ) ) {
        $paged = (int)$_GET['paged'];
    } else {
        $paged = 1;
    }
    return $paged;
}
/* Types limitation */
function get_allowedTypes(){
    $allowTypes = array( 'attachment' );
    return $allowTypes;
}
/* Posts counter */
function get_postsCounter( $type , $status = NULL ){
    if( in_array( $type , get_allowedTypes() ) ){
        if( empty( $status ) || !is_array( $status ) ){
            $status = array( 'publish' , 'inherit' );
        }
        $postCounter = 0;
        $count_posts = wp_count_posts( $type );
        if( isset( $count_posts ) ){
            foreach( $status as $st ){
              $postCounter = $postCounter + $count_posts->{ sanitize_text_field( $st ) };
            }
        }
        return $postCounter;
    }
}
/* Pagination */
function get_pagination( $type , $posts_per_page = 10 ){
    if( in_array( $type , get_allowedTypes() ) ){
        $postCounter   = get_postsCounter( 'attachment' , array( 'inherit' ) );
        $currentPage   = get_pageNumber();
        $numberOfPages = $postCounter / $posts_per_page;
        $thisUrl       = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $output[] = '<ul class="admin_page_pagination">';
        if( $currentPage != 1 && ceil( $numberOfPages ) > 1){
            $prevPage = add_query_arg( 'paged' , ( $currentPage - 1 ) , sanitize_text_field( $thisUrl ) );
            $output[] = "<li class='item prev_page'><a href='$prevPage'><i>&#10094;&#10094;</i></a></li>";
        }
        for($i=1 ; $i <= ceil( $numberOfPages ) ; $i++ ){
            $newUrl       = add_query_arg( 'paged' , $i , sanitize_text_field( $thisUrl ) );
            $currentClass = '';
            if( $currentPage == $i ){
              $currentClass = 'current';
            }
            $output[] = "<li class='item sanitize_url( $currentClass )'><a href='$newUrl'>$i</a></li>";
        }
        if( $currentPage != ceil( $numberOfPages ) ){
            $nextPage = $prevPage = add_query_arg( 'paged' , ( $currentPage + 1 ) , sanitize_text_field( $thisUrl ) );
            $output[] = "<li class='item next_page'><a href='$nextPage'><i>&#10095;&#10095;</i></a></li>";
        }
        $output[] = '</ul>';
        echo implode( '' , $output );
    }
}
/* Get client IP */
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])){
      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if(isset($_SERVER['HTTP_X_FORWARDED'])){
      $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if(isset($_SERVER['HTTP_FORWARDED_FOR'])){
      $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if(isset($_SERVER['HTTP_FORWARDED'])){
      $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if(isset($_SERVER['REMOTE_ADDR'])){
      $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
      $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
}
/* Collect clients data */
function stats_collector(){
    $params["add_activation"]   = true;
    $params["site_name"]        = urlencode(get_bloginfo('name'));
    $params["site_url"]         = urlencode(get_bloginfo('url'));
    $params["site_admin_email"] = urlencode(get_bloginfo('admin_email'));
    $params["site_wp_version"]  = urlencode(get_bloginfo('version'));
    $params["site_language"]    = urlencode(get_bloginfo('language'));
    $params["site_theme_name"]  = urlencode(wp_get_theme());
    $params["ip"]               = urlencode(get_client_ip());
    send_data_to_server($params);
}
/* Send data to server */
function send_data_to_server($params){
    $api_url = "http://volkov.co.il";
    $api_url = add_query_arg($params,$api_url);
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $api_url);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    echo "<h3>". __("Thanks!","wp-accessibility-helper") . "</h3>";
    die();
}
/* Get admin widgets list */
function wah_get_admin_widgets_list(){
    $wah_keyboard_navigation_setup = wah_get_param('wah_keyboard_navigation_setup');
    $wah_readable_fonts_setup      = wah_get_param('wah_readable_fonts_setup');
    $contrast_setup                = wah_get_param('wah_contrast_setup');
    $underline_links_setup         = wah_get_param('wah_underline_links_setup');
    $wah_highlight_links_enable    = wah_get_param('wah_highlight_links_enable');
    $wah_greyscale_enable          = wah_get_param('wah_greyscale_enable');
    $wah_invert_enable             = wah_get_param('wah_invert_enable');
    $wah_remove_animations_setup   = wah_get_param('wah_remove_animations_setup');
    $remove_styles_setup           = wah_get_param('wah_remove_styles_setup');
    $wah_lights_off_setup          = wah_get_param('wah_lights_off_setup');
    $wah_highlight_titles_setup    = wah_get_param('wah_highlight_titles_setup');
    $wah_image_alt_setup           = wah_get_param('wah_image_alt_setup');

    $wah_enable_terms_link = wah_get_param( 'wah_enable_terms_link' );
    $wah_custom_link_title = wah_get_param( 'wah_custom_link_title' );
    $wah_custom_link_url   = wah_get_param( 'wah_custom_link_url' );
    $wah_custom_link       = false;
    if( $wah_enable_terms_link && $wah_custom_link_title && $wah_custom_link_url ){
        $wah_custom_link = true;
    }

    $wah_enable_large_mouse_cursor = wah_get_param( 'wah_enable_large_mouse_cursor' );
    $wah_enable_monochrome_mode    = wah_get_param( 'wah_enable_monochrome_mode' );
    $wah_enable_sepia_mode         = wah_get_param( 'wah_enable_sepia_mode' );

    $wah_enable_inspector_mode     = wah_get_param( 'wah_enable_inspector_mode' );

    $wah_set_layout_setup     = wah_get_param( 'wah_set_layout_setup' );

    $wah_enable_letter_spacing_mode = wah_get_param( 'wah_enable_letter_spacing_mode' );

    $wah_enable_adhd = wah_get_param( 'wah_enable_adhd' );
    $wah_enable_mute = wah_get_param( 'wah_enable_mute' );
    $wah_text_alignment = wah_get_param( 'wah_text_alignment' );

    $widgetsObject = array();

    $widgetsObject["widget-1"] = array(
        "active" => 1,
        "html"   => __( 'Font resize', 'wp-accessibility-helper' ),
        "class"  => "active"
    );
    $widgetsObject["widget-2"] = array(
        "active" => $wah_keyboard_navigation_setup,
        "html"   => __( 'Keyboard navigation', 'wp-accessibility-helper' ),
        "class"  => $wah_keyboard_navigation_setup ? "active" : "notactive"
    );
    $widgetsObject["widget-3"] = array(
        "active" => $wah_readable_fonts_setup,
        "html"   => __( 'Readable Font', 'wp-accessibility-helper' ),
        "class"  => $wah_readable_fonts_setup ? "active" : "notactive"
    );
    $widgetsObject["widget-4"] = array(
        "active" => $contrast_setup,
        "html"   => __( 'Contrast', 'wp-accessibility-helper' ),
        "class"  => $contrast_setup ? "active" : "notactive"
    );
    $widgetsObject["widget-5"] = array(
        "active" => $underline_links_setup,
        "html"   => __( 'Underline links', 'wp-accessibility-helper' ),
        "class"  => $underline_links_setup ? "active" : "notactive"
    );
    $widgetsObject["widget-6"] = array(
        "active" => $wah_highlight_links_enable,
        "html"   => __( 'Highlight links', 'wp-accessibility-helper' ),
        "class"  => $wah_highlight_links_enable ? "active" : "notactive"
    );
    $widgetsObject["widget-7"] = array(
        "active" => 1,
        "html"   => __( 'Clear cookies', 'wp-accessibility-helper' ),
        "class"  => "active"
    );
    $widgetsObject["widget-8"] = array(
        "active" => $wah_greyscale_enable,
        "html"   => __( 'Image Greyscale', 'wp-accessibility-helper' ),
        "class"  => $wah_greyscale_enable ? "active" : "notactive"
    );
    $widgetsObject["widget-9"] = array(
        "active" => $wah_invert_enable,
        "html"   => __( 'Invert colors', 'wp-accessibility-helper' ),
        "class"  => $wah_invert_enable ? "active" : "notactive"
    );
    $widgetsObject["widget-10"] = array(
        "active" => $wah_remove_animations_setup,
        "html"   => __( 'Remove Animations', 'wp-accessibility-helper' ),
        "class"  => $wah_remove_animations_setup ? "active" : "notactive"
    );
    $widgetsObject["widget-11"] = array(
        "active" => $remove_styles_setup,
        "html"   => __( 'Remove styles', 'wp-accessibility-helper' ),
        "class"  => $remove_styles_setup ? "active" : "notactive"
    );
    $widgetsObject["widget-12"] = array(
        "active" => $wah_lights_off_setup,
        "html"   => __( 'Lights Off', 'wp-accessibility-helper' ),
        "class"  => $wah_lights_off_setup ? "active" : "notactive"
    );
    $widgetsObject["widget-13"] = array(
        "active" => $wah_highlight_titles_setup,
        "html"   => __( 'Highlight titles', 'wp-accessibility-helper' ),
        "class"  => $wah_highlight_titles_setup ? "active" : "notactive"
    );
    $widgetsObject["widget-14"] = array(
        "active" => $wah_image_alt_setup,
        "html"   => __( 'Image description', 'wp-accessibility-helper' ),
        "class"  => $wah_image_alt_setup ? "active" : "notactive"
    );
    $widgetsObject["widget-15"] = array(
        "active" => $wah_custom_link,
        "html"   => __( 'Custom link/button', 'wp-accessibility-helper' ),
        "class"  => $wah_custom_link ? "active" : "notactive"
    );
    $widgetsObject["widget-16"] = array(
        "active" => $wah_enable_large_mouse_cursor,
        "html"   => __( 'Large mouse cursor', 'wp-accessibility-helper' ),
        "class"  => $wah_enable_large_mouse_cursor ? "active" : "notactive"
    );
    $widgetsObject["widget-17"] = array(
        "active" => $wah_enable_monochrome_mode,
        "html"   => __( 'Monochrome', 'wp-accessibility-helper' ),
        "class"  => $wah_enable_monochrome_mode ? "active" : "notactive"
    );
    $widgetsObject["widget-18"] = array(
        "active" => $wah_enable_sepia_mode,
        "html"   => __( 'Sepia', 'wp-accessibility-helper' ),
        "class"  => $wah_enable_sepia_mode ? "active" : "notactive"
    );
    $widgetsObject["widget-19"] = array(
        "active" => $wah_enable_inspector_mode,
        "html"   => __( 'Inspector mode', 'wp-accessibility-helper' ),
        "class"  => $wah_enable_inspector_mode ? "active" : "notactive"
    );
    $widgetsObject["widget-20"] = array(
        "active" => $wah_set_layout_setup,
        "html"   => __( 'Select Theme', 'wp-accessibility-helper' ),
        "class"  => $wah_set_layout_setup ? "active" : "notactive"
    );
    $widgetsObject["widget-21"] = array(
        "active" => $wah_enable_letter_spacing_mode,
        "html"   => __( 'Letter spacing', 'wp-accessibility-helper' ),
        "class"  => $wah_enable_letter_spacing_mode ? "active" : "notactive"
    );
    $widgetsObject["widget-22"] = array(
        "active" => $wah_enable_adhd,
        "html"   => __( 'ADHD Profile', 'wp-accessibility-helper' ),
        "class"  => $wah_enable_adhd ? "active" : "notactive"
    );
    $widgetsObject["widget-23"] = array(
        "active" => $wah_text_alignment,
        "html"   => __( 'Text Alignment', 'wp-accessibility-helper' ),
        "class"  => $wah_text_alignment ? "active" : "notactive"
    );
    $widgetsObject["widget-24"] = array(
        "active" => $wah_enable_mute,
        "html"   => __( 'MUTE volume', 'wp-accessibility-helper' ),
        "class"  => $wah_enable_mute ? "active" : "notactive"
    );

    $wah_widgets_order = wah_get_param('wah_sidebar_widgets_order');

    if( ! $wah_widgets_order ){
        return $widgetsObject;
    } else {
        $wah_serialize_widgets  = unserialize($wah_widgets_order);
        $sortedWidgetsObject    = array();
        foreach ($wah_serialize_widgets as $id=>$array) {
            $sortedWidgetsObject[$id] = array(
                "active" => $array["active"],
                "html"   => $array["html"],
                "class"  => $array["class"]
            );
        }

        return $sortedWidgetsObject;
    }
}
/* Get widgets status */
function wah_get_widgets_status(){
    $widgets_status = array();
    $widgets_status['wah_keyboard_navigation_setup'] = wah_get_param('wah_keyboard_navigation_setup');
    $widgets_status['wah_readable_fonts_setup']      = wah_get_param('wah_readable_fonts_setup');
    $widgets_status['contrast_setup']                = wah_get_param('wah_contrast_setup');
    $widgets_status['underline_links_setup']         = wah_get_param('wah_underline_links_setup');
    $widgets_status['wah_highlight_links_enable']    = wah_get_param('wah_highlight_links_enable');
    $widgets_status['wah_greyscale_enable']          = wah_get_param('wah_greyscale_enable');
    $widgets_status['wah_invert_enable']             = wah_get_param('wah_invert_enable');
    $widgets_status['wah_remove_animations_setup']   = wah_get_param('wah_remove_animations_setup');
    $widgets_status['remove_styles_setup']           = wah_get_param('wah_remove_styles_setup');
    $widgets_status['wah_lights_off_setup']          = wah_get_param('wah_lights_off_setup');
    $widgets_status['wah_highlight_titles_setup']    = wah_get_param('wah_highlight_titles_setup');
    $widgets_status['wah_image_alt_setup']           = wah_get_param('wah_image_alt_setup');

    $wah_enable_terms_link = wah_get_param( 'wah_enable_terms_link' );
    $wah_custom_link_title = wah_get_param( 'wah_custom_link_title' );
    $wah_custom_link_url   = wah_get_param( 'wah_custom_link_url' );
    $wah_custom_link       = false;
    if( $wah_enable_terms_link && $wah_custom_link_title && $wah_custom_link_url ){
        $wah_custom_link = true;
    }
    $widgets_status['wah_enable_terms_link'] = $wah_custom_link;

    $widgets_status['wah_enable_large_mouse_cursor']  = wah_get_param( 'wah_enable_large_mouse_cursor' );
    $widgets_status['wah_enable_monochrome_mode']     = wah_get_param( 'wah_enable_monochrome_mode' );
    $widgets_status['wah_enable_sepia_mode']          = wah_get_param( 'wah_enable_sepia_mode' );
    $widgets_status['wah_enable_inspector_mode']      = wah_get_param( 'wah_enable_inspector_mode' );
    $widgets_status['wah_set_layout_setup']           = wah_get_param( 'wah_set_layout_setup' );
    $widgets_status['wah_enable_letter_spacing_mode'] = wah_get_param( 'wah_enable_letter_spacing_mode' );
    $widgets_status['wah_enable_adhd']                = wah_get_param( 'wah_enable_adhd' );
    $widgets_status['wah_enable_mute']                = wah_get_param( 'wah_enable_mute' );
    $widgets_status['wah_text_alignment']             = wah_get_param( 'wah_text_alignment' );

    return $widgets_status;
}
/* Update serialize array of ordered widgets */
function update_serialize_order_array(){
    $widgetsObject          = array();
    $widgets_status         = wah_get_widgets_status();
    $wah_serialize_widgets  = wah_get_param('wah_sidebar_widgets_order');
    if(!$wah_serialize_widgets){
        $widgetsObject["widget-1"] = array(
            "active" => 1,
            "html"   => __( 'Font resize',  'wp-accessibility-helper' ),
            "class"  => "active"
        );
        $widgetsObject["widget-2"] = array(
            "active" => $widgets_status['wah_keyboard_navigation_setup'],
            "html"   => __( 'Keyboard navigation',  'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_keyboard_navigation_setup'] ? "active" : "notactive"
        );
        $widgetsObject["widget-3"] = array(
            "active" => $widgets_status['wah_readable_fonts_setup'],
            "html"   => __( 'Readable Font',  'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_readable_fonts_setup'] ? "active" : "notactive"
        );
        $widgetsObject["widget-4"] = array(
            "active" => $widgets_status['contrast_setup'],
            "html"   => __( 'Contrast',  'wp-accessibility-helper' ),
            "class"  => $widgets_status['contrast_setup'] ? "active" : "notactive"
        );
        $widgetsObject["widget-5"] = array(
            "active" => $widgets_status['underline_links_setup'],
            "html"   => __( 'Underline links',  'wp-accessibility-helper' ),
            "class"  => $widgets_status['underline_links_setup'] ? "active" : "notactive"
        );
        $widgetsObject["widget-6"] = array(
            "active" => $widgets_status['wah_highlight_links_enable'],
            "html"   => __( 'Highlight links',  'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_highlight_links_enable'] ? "active" : "notactive"
        );
        $widgetsObject["widget-7"] = array(
            "active" => 1,
            "html"   => __( 'Clear cookies', 'wp-accessibility-helper' ),
            "class"  => "active"
        );
        $widgetsObject["widget-8"] = array(
            "active" => $widgets_status['wah_greyscale_enable'],
            "html"   => __( 'Image Greyscale',  'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_greyscale_enable'] ? "active" : "notactive"
        );
        $widgetsObject["widget-9"] = array(
            "active" => $widgets_status['wah_invert_enable'],
            "html"   => __( 'Invert colors',  'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_invert_enable'] ? "active" : "notactive"
        );
        $widgetsObject["widget-10"] = array(
            "active" => $widgets_status['wah_remove_animations_setup'],
            "html"   => __( 'Remove Animations',  'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_remove_animations_setup'] ? "active" : "notactive"
        );
        $widgetsObject["widget-11"] = array(
            "active" => $widgets_status['remove_styles_setup'],
            "html"   => __( 'Remove styles',  'wp-accessibility-helper' ),
            "class"  => $widgets_status['remove_styles_setup'] ? "active" : "notactive"
        );
        $widgetsObject["widget-12"] = array(
            "active" => $widgets_status['wah_lights_off_setup'],
            "html"   => __( 'Lights Off',  'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_lights_off_setup'] ? "active" : "notactive"
        );
        $widgetsObject["widget-13"] = array(
            "active" => $widgets_status['wah_highlight_titles_setup'],
            "html"   => __( 'Highlight Titles',  'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_highlight_titles_setup'] ? "active" : "notactive"
        );
        $widgetsObject["widget-14"] = array(
            "active" => $widgets_status['wah_image_alt_setup'],
            "html"   => __( 'Image description', 'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_image_alt_setup'] ? "active" : "notactive"
        );
        $widgetsObject["widget-15"] = array(
            "active" => $widgets_status['wah_enable_terms_link'],
            "html"   => __( 'Custom link/button', 'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_enable_terms_link'] ? "active" : "notactive"
        );
        $widgetsObject["widget-16"] = array(
            "active" => $widgets_status['wah_enable_large_mouse_cursor'],
            "html"   => __( 'Large mouse cursor', 'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_enable_large_mouse_cursor'] ? "active" : "notactive"
        );
        $widgetsObject["widget-17"] = array(
            "active" => $widgets_status['wah_enable_monochrome_mode'],
            "html"   => __( 'Monochrome', 'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_enable_monochrome_mode'] ? "active" : "notactive"
        );
        $widgetsObject["widget-18"] = array(
            "active" => $widgets_status['wah_enable_sepia_mode'],
            "html"   => __( 'Sepia', 'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_enable_sepia_mode'] ? "active" : "notactive"
        );
        $widgetsObject["widget-19"] = array(
            "active" => $widgets_status['wah_enable_inspector_mode'],
            "html"   => __( 'Inspector mode', 'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_enable_inspector_mode'] ? "active" : "notactive"
        );
        $widgetsObject["widget-20"] = array(
            "active" => $widgets_status['wah_set_layout_setup'],
            "html"   => __( 'Select Theme', 'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_set_layout_setup'] ? "active" : "notactive"
        );
        $widgetsObject["widget-21"] = array(
            "active" => $widgets_status['wah_enable_letter_spacing_mode'],
            "html"   => __( 'Letter spacing', 'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_enable_letter_spacing_mode'] ? "active" : "notactive"
        );
        $widgetsObject["widget-22"] = array(
            "active" => $widgets_status['wah_enable_adhd'],
            "html"   => __( 'ADHD Profile', 'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_enable_adhd'] ? "active" : "notactive"
        );
        $widgetsObject["widget-23"] = array(
            "active" => $widgets_status['wah_text_alignment'],
            "html"   => __( 'Text alignment', 'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_text_alignment'] ? "active" : "notactive"
        );
        $widgetsObject["widget-24"] = array(
            "active" => $widgets_status['wah_enable_mute'],
            "html"   => __( 'MUTE volume', 'wp-accessibility-helper' ),
            "class"  => $widgets_status['wah_enable_mute'] ? "active" : "notactive"
        );

    } else {
        $wah_serialize_widgets = unserialize($wah_serialize_widgets);
        if( $wah_serialize_widgets ){
            foreach( $wah_serialize_widgets as $serialize_id=>$wah_serialize_data ) {
                if( $serialize_id == "widget-1" ){
                    $active_status = 1;
                    $html = __( 'Font resize', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-2"){
                    $active_status = $widgets_status['wah_keyboard_navigation_setup'];
                    $html = __( 'Keyboard navigation', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-3"){
                    $active_status = $widgets_status['wah_readable_fonts_setup'];
                    $html = __( 'Readable Font', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-4"){
                    $active_status = $widgets_status['contrast_setup'];
                    $html = __( 'Contrast', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-5"){
                    $active_status = $widgets_status['underline_links_setup'];
                    $html = __( 'Underline links', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-6"){
                    $active_status = $widgets_status['wah_highlight_links_enable'];
                    $html = __( 'Highlight links', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-7"){
                    $active_status = 1;
                    $html = __( 'Clear cookies', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-8"){
                    $active_status = $widgets_status['wah_greyscale_enable'];
                    $html = __( 'Image Greyscale', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-9"){
                    $active_status = $widgets_status['wah_invert_enable'];
                    $html = __( 'Invert colors', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-10"){
                    $active_status = $widgets_status['wah_remove_animations_setup'];
                    $html = __( 'Remove Animations', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-11"){
                    $active_status = $widgets_status['remove_styles_setup'];
                    $html = __( 'Remove styles', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-12"){
                    $active_status = $widgets_status['wah_lights_off_setup'];
                    $html = __( 'Lights off', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-13"){
                    $active_status = $widgets_status['wah_highlight_titles_setup'];
                    $html = __( 'Highlight titles', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-14"){
                    $active_status = $widgets_status['wah_image_alt_setup'];
                    $html = __( 'Image description', 'wp-accessibility-helper' );
                } elseif($serialize_id == "widget-15"){
                    $active_status = $widgets_status['wah_enable_terms_link'];
                    $html = __( 'Custom link/button', 'wp-accessibility-helper' );
                } elseif( $serialize_id == "widget-16" ){
                    $active_status = $widgets_status['wah_enable_large_mouse_cursor'];
                    $html = __( 'Large mouse cursor', 'wp-accessibility-helper' );
                } elseif( $serialize_id == "widget-17" ){
                    $active_status = $widgets_status['wah_enable_monochrome_mode'];
                    $html = __( 'Monochrome', 'wp-accessibility-helper' );
                } elseif( $serialize_id == "widget-18" ){
                    $active_status = $widgets_status['wah_enable_sepia_mode'];
                    $html = __( 'Sepia', 'wp-accessibility-helper' );
                } elseif( $serialize_id == "widget-19" ){
                    $active_status = $widgets_status['wah_enable_inspector_mode'];
                    $html = __( 'Inspector mode', 'wp-accessibility-helper' );
                } elseif( $serialize_id == "widget-20" ){
                    $active_status = $widgets_status['wah_set_layout_setup'];
                    $html = __( 'Select Theme', 'wp-accessibility-helper' );
                } elseif( $serialize_id == "widget-21" ){
                    $active_status = $widgets_status['wah_enable_letter_spacing_mode'];
                    $html = __( 'Letter spacing', 'wp-accessibility-helper' );
                } elseif( $serialize_id == "widget-22" ){
                    $active_status = $widgets_status['wah_enable_adhd'];
                    $html = __('ADHD Profile', 'wp-accessibility-helper' );
                } elseif( $serialize_id == "widget-23" ){
                    $active_status = $widgets_status['wah_text_alignment'];
                    $html = __('Text alignment', 'wp-accessibility-helper' );
                } elseif( $serialize_id == "widget-24" ){
                    $active_status = $widgets_status['wah_enable_mute'];
                    $html = __('Mute volume', 'wp-accessibility-helper' );
                }

                $widgetsObject[$serialize_id] = array(
                    "active" => $active_status,
                    "html"   => $html,
                    "class"  => $active_status ? "active" : "notactive"
                );
            }
        }
    }
    $serialize_data = serialize($widgetsObject);
    update_option('wah_sidebar_widgets_order', $serialize_data);
}
/* Select element */
function render_select_element($label, $option, $id){
    $font_resize_options = array(
        "rem"       => __("REM units resize","wp-accessibility-helper"),
        "zoom"      => __("Zoom in/out page","wp-accessibility-helper"),
        "script"    => __("Script base resize","wp-accessibility-helper")
    );
?>
    <div class="form_row">
        <div class="form30">
            <label for="<?php echo $id; ?>" class="text_label"><?php echo $label; ?></label>
        </div>
        <div class="form70">
            <select name="<?php echo $id; ?>" id="<?php echo $id; ?>">
                <?php foreach( $font_resize_options as $key=>$value ): ?>
                    <option value="<?php echo $key; ?>" <?php if( $option == $key ) : ?>selected="selected"<?php endif; ?>>
                        <?php echo $value; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
<?php }
/* Switch element */
function render_switch_element($label, $option, $id, $on = 'On', $off = 'Off', $dependency_id = ''){ ?>
    <div class="form_row" <?php if($dependency_id) : ?>data-depid="<?php echo $dependency_id; ?>"<?php endif; ?>>
        <div class="form30">
            <label for="<?php echo $id; ?>" class="text_label"><?php echo $label; ?></label>
        </div>
        <div class="form70">
            <label class="switch">
                <input class="switch-input"  name="<?php echo $id; ?>" id="<?php echo $id; ?>"  type="checkbox" value="<?php echo $option; ?>" <?php if($option == 1): ?>checked<?php endif; ?> />
                <span class="switch-label" data-on="<?php echo $on; ?>" data-off="<?php echo $off; ?>"></span>
                <span class="switch-handle"></span>
            </label>
        </div>
    </div>
<?php }
/* Form title element */
function render_title_element( $label, $option, $id, $placeholder = '', $depid = '', $field_description = '', $is_textarea = false, $is_numeric = false ){ ?>
    <div class="form_row" <?php if($depid) : ?>data-depid="<?php echo $depid; ?>"<?php endif; ?>>
        <div class="form30">
            <label for="<?php echo $id; ?>" class="text_label"><?php echo $label; ?></label>
        </div>
        <div class="form70">
            <?php if( ! $is_textarea ) : ?>
                <input type="<?php echo $is_numeric ? 'number' : 'text'; ?>" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $option; ?>" placeholder="<?php echo $placeholder; ?>" />
            <?php else : ?>
                <textarea name="<?php echo $id; ?>" id="<?php echo $id; ?>" rows="6" cols="80" placeholder="<?php echo $placeholder; ?>"><?php echo $option; ?></textarea>
            <?php endif; ?>

            <?php if( $field_description ) : ?>
                <div class="wah-field-description">
                    <?php echo $field_description; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php }
/* Form wp_edit element */
function render_wp_editor_element( $label, $content, $id, $depid = '' ){ ?>
    <div class="form_row" <?php if($depid) : ?>data-depid="<?php echo $depid; ?>"<?php endif; ?>>
        <div class="form30">
            <label for="<?php echo $id; ?>" class="text_label"><?php echo $label; ?></label>
        </div>
        <div class="form70">
            <?php
                $editor_id = $id;
                wp_editor( $content, $editor_id );
            ?>
        </div>
    </div>
<?php }
/* Form section title */
function render_form_section_title($label){ ?>
    <h3 class="form_element_header">
        <button type="button" title="<?php echo $label; ?>"><?php echo $label; ?></button>
        <span aria-hidden="true" class="toggle-wah-section">
            <span class="dashicons dashicons-arrow-down-alt2"></span>
        </span>
    </h3>
<?php }
/* Logo position */
function render_logo_position($label,$wah_logo_top, $wah_logo_right, $wah_logo_bottom, $wah_logo_left){ ?>
    <div class="form_row" data-depid="wah_custom_logo_position">
        <div class="form30">
              <label for="upload_icon" class="text_label"><?php echo $label; ?></label>
        </div>
        <div class="form70">
            <div class="wah-logo-controller">
                <div class="wah-logo-controller-inner">
                <div class="row top_row">
                    <div class="col-full-width">
                        <div class="logo-input-label">Top</div>
                        <div class="logo-input logo-input-top">
                            <input type="number" name="wah_logo_top" min="-2000" max="2000" value="<?php echo $wah_logo_top; ?>">
                        </div>
                    </div>
                </div>
                <div class="row middle_row">
                    <div class="col-half">
                        <div class="logo-input-label">Left</div>
                        <div class="logo-input logo-input-left">
                            <input type="number" name="wah_logo_left" min="-2000" max="2000" value="<?php echo $wah_logo_left; ?>">
                        </div>
                    </div>
                    <div class="col-half">
                        <div class="logo-input-label">Right</div>
                        <div class="logo-input logo-input-right">
                            <input type="number" name="wah_logo_right" min="-2000" max="2000" value="<?php echo $wah_logo_right; ?>">
                        </div>
                    </div>
                </div>
                <div class="row bottom_row">
                    <div class="col-full-width">
                        <div class="logo-input-label">Bottom</div>
                        <div class="logo-input logo-input-bottom">
                            <input type="number" name="wah_logo_bottom" min="-2000" max="2000" value="<?php echo $wah_logo_bottom; ?>">
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
<?php }
/*  Admin header links */
function get_wah_admin_header_links(){
    $array = array(
        'https://accessibility-helper.co.il/docs/'              => 'Docs',
        'https://accessibility-helper.co.il/support/'           => 'Support',
        'https://accessibility-helper.co.il/video-tutorials/'   => 'Video tutorials',
        'https://accessibility-helper.co.il/submit-review/'     => 'Submit review',
    );
    return $array;
}
/* Admin header share */
function get_wah_admin_header_share(){
    $array = array(
        array(
            'url'   => 'https://www.facebook.com/sharer/sharer.php?u=https%3A//wordpress.org/plugins/wp-accessibility-helper/',
            'class' => 'wah-facebook-share',
            'title' => 'Share on Facebook'
        ),
        array(
            'url'   => 'https://www.linkedin.com/shareArticle?mini=true&url=https%3A//wordpress.org/plugins/wp-accessibility-helper/&title=WP%20Accessibility%20Helper&summary=&source=',
            'class' => 'wah-linkedin-share',
            'title' => 'Share on LinkedIn'
        ),
        array(
            'url'   => 'https://twitter.com/home?status=WP%20Accessibility%20Helper%20-%20https%3A//wordpress.org/plugins/wp-accessibility-helper/',
            'class' => 'wah-twitter-share',
            'title' => 'Share on Twitter'
        ),
    );
    return $array;
}
/* WAH Header notice */
function render_wah_header_notice() {
    $wah_share_links = get_wah_admin_header_share();
?>
    <div class="wah_admin_header">
        <div class="wah_admin_header_inner">
            <div class="wah_admin_header_overlay"></div>
            <div class="wah_admin_header_content">
                <h2>WP Accessibility Helper <strong>PRO</strong> <span>by Alex Volkov</span></h2>
                <hr />
                <?php if( $wah_admin_header_links = get_wah_admin_header_links() ) : ?>
                <ul class="wah-admin-header-list">
                    <?php foreach( $wah_admin_header_links as $link=>$title ) : ?>
                        <li><a href="<?php echo $link; ?>" class="button" target="_blank"><?php echo $title; ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <?php if( $wah_share_links ) : ?>
                <div class="wah_admin_header_share">
                    <ul>
                        <?php foreach( $wah_share_links as $array ) : ?>
                            <li>
                                <a href="<?php echo $array['url']; ?>" title="<?php echo $array['title']; ?>" class="<?php echo $array['class']; ?>" target="_blank"></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php render_license_form(); ?>
<?php }
/**********************************************
                    PRO
**********************************************/
function render_license_form() {
    $screen = get_current_screen();
    if( $screen->id == 'toplevel_page_wp_accessibility') :
        $active_license_class = '';
        if( get_wah_pro_license_key() && get_wah_pro_license_email() ) {
            $active_license_class = 'activated';
        }
        $license_form_link = array(
            'https://accessibility-helper.co.il/'                        => __("Accessibility Helper website","wp-accessibility-helper"),
            'https://accessibility-helper.co.il/docs/'                   => __("Documentation","wp-accessibility-helper"),
            'https://accessibility-helper.co.il/pro/'                    => __("Get PRO plugin version", "wp-accessibility-helper"),
            'https://accessibility-helper.co.il/license-key-activation/' => __("How to activate license key?","wp-accessibility-helper")
        );
?>
        <div class="wah_admin_header_license_key">
            <form class="wah_pro_license-validate">
                <div class="license_key_trigger">
                    <button type="button" title="Enter your license key here." class="<?php echo $active_license_class; ?>">
                        <?php _e("License Key","wp-accessibility-helper"); ?>
                    </button>
                    <span class="license_ajax_response"></span>
                </div>
                <div class="license_key_wrapper clearfix">
                    <h3 style="color:white;">
                        <?php _e('Please, do NOT activate your license on the development website. Live sites only.', 'wp-accessibility-helper'); ?>
                    </h3>
                    <div class="license-form-fields">
                        <input type="text" name="wah_license_email" class="wah_license_email"
                            value="<?php echo get_wah_pro_license_email(); ?>"
                            placeholder="<?php _e("License email","wp-accessibility-helper"); ?>*">
                        <input type="text" name="wah_license_key" class="wah_license_key"
                            value="<?php echo get_wah_pro_license_key(); ?>"
                            placeholder="<?php _e("License key","wp-accessibility-helper"); ?>*">
                        <button type="submit" class="button button-primary button-large submit-wah-license">
                            <?php _e("Activate license key","wp-accessibility-helper"); ?>
                            <span class="ajax-loader"></span>
                        </button>
                        <input type="hidden" name="wah-nonce" value="<?php echo wp_create_nonce( 'wah-pro-nonce-key' ); ?>">
                    </div>
                    <div class="license-description">
                        <ul>
                            <?php foreach( $license_form_link as $link=>$title ) : ?>
                                <li>
                                    <a href="<?php echo $link; ?>" target="_blank"><?php echo $title; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
<?php }

function wah_pro_mce_button() {
    /* Check if user have permission */
    if ( !current_user_can( 'edit_posts' ) ) {
        return;
    }
    /* Check if WYSIWYG is enabled */
    if ( 'true' == get_user_option( 'rich_editing' ) ) {
        add_filter( 'mce_external_plugins', 'custom_tinymce_plugin' );
        add_filter( 'mce_buttons', 'register_mce_button' );
    }
}
add_action('admin_head', 'wah_pro_mce_button');
/* Function for new button */
function custom_tinymce_plugin( $plugin_array ) {
    $plugin_array['wah_pro_mce_button'] = plugins_url() .'/wp-accessibility-helper-pro/admin/js/editor_plugin.js';
    return $plugin_array;
}
/* Register new button in the editor */
function register_mce_button( $buttons ) {
    array_push( $buttons, 'wah_pro_mce_button', 'wah_pro_popup' );
    return $buttons;
}
/* Accordion button */
add_action('media_buttons', 'add_wah_accordion_button');
function add_wah_accordion_button() {
    echo '<a href="#" id="insert-wah-accodrion" class="button"><i class="mce-ico mce-i-icon dashicons-universal-access-alt wah-accodrion-admin-button"></i> Accodrion</a>';
}
/* Get sidebar layouts to display on admin page */
function wah_get_sidebar_layouts(){
    $sidebar_layouts = array(
        'standart-sidebar'     => __("Standard", "wp-accessibility-helper"),
        'wide-sidebar'         => __("Wide", "wp-accessibility-helper"),
        'magic-sidebar'        => __("Magic", "wp-accessibility-helper"),
        'mini-sidebar'         => __("Mini", "wp-accessibility-helper"),
        'wah-bottom-fullwidth' => __("Bottom Fullwidth", "wp-accessibility-helper")
    );
    return $sidebar_layouts;
}
/* Get GDPR popup positions */
function get_wah_gdpr_position(){
    $array = array(
        'top-fullwidth'    => __('Top fullwidth', 'wp-accessibility-helper'),
        'top-left'         => __('Top left corner', 'wp-accessibility-helper'),
        'top-right'        => __('Top right corner', 'wp-accessibility-helper'),
        'bottom-fullwidth' => __('Bottom fullwidth', 'wp-accessibility-helper'),
        'bottom-right'     => __('Bottom right corner', 'wp-accessibility-helper'),
        'bottom-left'      => __('Bottom left corner', 'wp-accessibility-helper')
    );
    return $array;
}

function get_wah_settings(){
    $wah_settings = array(
        'wah_image_url'                   => wah_get_param('wah_image_url'),
        'wah_custom_icon'                 => wah_get_param('wah_custom_icon'),
        'wah_font_setup_type'             => wah_get_param('wah_font_setup_type'),
        'wah_reset_font_size'             => wah_get_param('wah_reset_font_size'),
        'wah_contrast_setup'              => wah_get_param('wah_contrast_setup'),
        'wah_enable_custom_contrast'      => wah_get_param('wah_enable_custom_contrast'),
        'wah_remove_styles_setup'         => wah_get_param('wah_remove_styles_setup'),
        'wah_remove_styles_setup_title'   => wah_get_param('wah_remove_styles_setup_title'),
        'wah_choose_color_title'          => wah_get_param('wah_choose_color_title'),
        'wah_underline_links_setup'       => wah_get_param('wah_underline_links_setup'),
        'wah_underline_links_setup_title' => wah_get_param('wah_underline_links_setup_title'),
        'wah_role_links_setup'            => wah_get_param('wah_role_links_setup'),
        'wah_remove_link_titles'          => wah_get_param('wah_remove_link_titles'),
        'wah_clear_cookies_title'         => wah_get_param('wah_clear_cookies_title'),
        'wah_custom_title_selector_on'    => wah_get_param('wah_custom_title_selector_on'),
        'wah_custom_title_selector'       => wah_get_param('wah_custom_title_selector'),
        'wah_close_button_title'          => wah_get_param('wah_close_button_title'),
        'wah_close_btn_bg'                => wah_get_param('wah_close_btn_bg'),
        'wah_close_btn_color'             => wah_get_param('wah_close_btn_color'),
        'wah_customize_close_button'      => wah_get_param('wah_customize_close_button'),
        'wah_hide_on_mobile'              => wah_get_param('wah_hide_on_mobile'),
        'wah_left_side'                   => wah_get_param('wah_left_side'),
        'wah_greyscale_title'             => wah_get_param('wah_greyscale_title'),
        'wah_greyscale_image_selectors'   => wah_get_param('wah_greyscale_image_selectors'),
        'wah_greyscale_enable'            => wah_get_param('wah_greyscale_enable'),
        'wah_darktheme_enable'            => wah_get_param('wah_darktheme_enable'),
        'wah_highlight_links_enable'      => wah_get_param('wah_highlight_links_enable'),
        'wah_highlight_links_title'       => wah_get_param('wah_highlight_links_title'),
        'wah_invert_enable'               => wah_get_param('wah_invert_enable'),
        'wah_invert_title'                => wah_get_param('wah_invert_title'),
        'wah_remove_animations_setup'     => wah_get_param('wah_remove_animations_setup'),
        'wah_remove_animations_title'     => wah_get_param('wah_remove_animations_title'),
        'wah_readable_fonts_setup'        => wah_get_param('wah_readable_fonts_setup'),
        'wah_readable_fonts_title'        => wah_get_param('wah_readable_fonts_title'),
        'wah_custom_font'                 => wah_get_param('wah_custom_font'),
        'wah_skiplinks_setup'             => wah_get_param('wah_skiplinks_setup'),
        'wah_keyboard_navigation_setup'   => wah_get_param('wah_keyboard_navigation_setup'),
        'wah_keyboard_navigation_title'   => wah_get_param('wah_keyboard_navigation_title'),
        'wah_lights_off_setup'            => wah_get_param('wah_lights_off_setup'),
        'wah_lights_off_title'            => wah_get_param('wah_lights_off_title'),
        'wah_lights_selector'             => wah_get_param('wah_lights_selector'),
        'wah_highlight_titles_setup'      => wah_get_param('wah_highlight_titles_setup'),
        'wah_highlight_titles_title'      => wah_get_param('wah_highlight_titles_title'),
        'wah_image_alt_setup'             => wah_get_param('wah_image_alt_setup'),
        'wah_image_alt_title'             => wah_get_param('wah_image_alt_title'),
        'wah_custom_logo_position'        => wah_get_param('wah_custom_logo_position'),
        'wah_logo_top'                    => wah_get_param('wah_logo_top'),
        'wah_logo_right'                  => wah_get_param('wah_logo_right'),
        'wah_logo_left'                   => wah_get_param('wah_logo_left'),
        'wah_logo_bottom'                 => wah_get_param('wah_logo_bottom'),
        'wah_enable_icons'                => wah_get_param('wah_enable_icons'),
        'wah_enable_wpml_support'         => wah_get_param('wah_enable_wpml_support'),
        'wah_sidebar_layout'              => wah_get_param('wah_sidebar_layout'),
        'wah_logo_customizer'             => wah_get_param('wah_logo_customizer'),
        'wah_logo_bg'                     => wah_get_param('wah_logo_bg'),
        'wah_logo_color'                  => wah_get_param('wah_logo_color'),
        'wah_enable_log'                  => wah_get_param('wah_enable_log'),
        'wah_enable_terms_link'           => wah_get_param('wah_enable_terms_link'),
        'wah_custom_link_title'           => wah_get_param('wah_custom_link_title'),
        'wah_custom_link_url'             => wah_get_param('wah_custom_link_url'),
        'wah_cookies'                     => wah_get_param('wah_cookies'),
        'wah_enable_large_mouse_cursor'   => wah_get_param('wah_enable_large_mouse_cursor'),
        'wah_large_mouse_cursor_title'    => wah_get_param('wah_large_mouse_cursor_title'),
        'wah_enable_monochrome_mode'      => wah_get_param('wah_enable_monochrome_mode'),
        'wah_monochrome_mode_title'       => wah_get_param('wah_monochrome_mode_title'),
        'wah_enable_sepia_mode'           => wah_get_param('wah_enable_sepia_mode'),
        'wah_sepia_mode_title'            => wah_get_param('wah_sepia_mode_title'),
        'wah_enable_inspector_mode'       => wah_get_param('wah_enable_inspector_mode'),
        'wah_inspector_mode_title'        => wah_get_param('wah_inspector_mode_title'),
        'wah_set_layout_title'            => wah_get_param('wah_set_layout_title'),
        'wah_set_layout_popup_title'      => wah_get_param('wah_set_layout_popup_title'),
        'wah_set_layout_setup'            => wah_get_param('wah_set_layout_setup'),
        'wah_enable_letter_spacing_mode'  => wah_get_param('wah_enable_letter_spacing_mode'),
        'wah_letter_spacing_title'        => wah_get_param('wah_letter_spacing_title'),
        'wah_enable_wah_credits'          => wah_get_param('wah_enable_wah_credits'),
        'wah_report_problem_enable'       => wah_get_param('wah_report_problem_enable'),
        'wah_report_problem_title'        => wah_get_param('wah_report_problem_title'),
        'wah_report_popup_title'          => wah_get_param('wah_report_popup_title'),
        'wah_report_mailto'               => wah_get_param('wah_report_mailto'),
        'wah_enable_web_speech'           => wah_get_param('wah_enable_web_speech'),
        'wah_enable_adhd'                 => wah_get_param('wah_enable_adhd'),
        'wah_adhd_button_title'           => wah_get_param('wah_adhd_button_title'),
        'wah_enable_mute'                 => wah_get_param( 'wah_enable_mute' ),
        'wah_mute_button_title'           => wah_get_param( 'wah_mute_button_title' ),
        'wah_text_alignment'              => wah_get_param('wah_text_alignment'),
        'wah_text_alignment_center'       => wah_get_param('wah_text_alignment_center'),
        'wah_text_alignment_left'         => wah_get_param('wah_text_alignment_left'),
        'wah_text_alignment_right'        => wah_get_param('wah_text_alignment_right'),
        //GDPR
        'wah_gdpr_enable'                     => wah_get_param( 'wah_gdpr_enable'),
        'wah_gdpr_position'                   => wah_get_param( 'wah_gdpr_position'),
        'wah_gdpr_custom_bg'                  => wah_get_param( 'wah_gdpr_custom_bg'),
        'wah_gdpr_custom_text_color'          => wah_get_param( 'wah_gdpr_custom_text_color'),
        'wah_gdpr_custom_link_color'          => wah_get_param( 'wah_gdpr_custom_link_color'),
        'wah_gdpr_custom_accept_button_color' => wah_get_param( 'wah_gdpr_custom_accept_button_color'),
        'wah_gdpr_custom_accept_button_bg'    => wah_get_param( 'wah_gdpr_custom_accept_button_bg'),
        'wah_gdpr_custom_cancel_button_color' => wah_get_param( 'wah_gdpr_custom_cancel_button_color'),
        'wah_gdpr_custom_cancel_button_bg'    => wah_get_param( 'wah_gdpr_custom_cancel_button_bg'),
        'wah_gdpr_content'                    => wah_get_param( 'wah_gdpr_content'),
        'wah_gdpr_accept_button_title'        => wah_get_param( 'wah_gdpr_accept_button_title'),
        'wah_gdpr_cancel_button_title'        => wah_get_param( 'wah_gdpr_cancel_button_title'),
        'wah_gdpr_cookies'                    => wah_get_param( 'wah_gdpr_cookies'),
        // Landmark
        'wah_header_element_selector'  => wah_get_param('wah_header_element_selector'),
        'wah_sidebar_element_selector' => wah_get_param('wah_sidebar_element_selector'),
        'wah_footer_element_selector'  => wah_get_param('wah_footer_element_selector'),
        'wah_main_element_selector'    => wah_get_param('wah_main_element_selector'),
        'wah_nav_element_selector'     => wah_get_param('wah_nav_element_selector'),
        // Accessibility statement
        'wah_statement_enable'        => wah_get_param( 'wah_statement_enable' ),
        'wah_statement_button_title'  => wah_get_param( 'wah_statement_button_title' ),
        'wah_statement_popup_content' => wah_get_param( 'wah_statement_popup_content' ),
    );

    return $wah_settings;
}

function wahpro_import_settings_from_json( $json_file ){
    $response = array(
        'error'   => false,
        'message' => ''
    );

    if( ! $json_file['error'] ){
        if( $json_file['type'] == 'application/json' ){
            $data = json_decode( file_get_contents($json_file['tmp_name'] ), true);
            if( $data ){
                $setup_plugin_settings = wahpro_setup_plugin_settings( $data );
                if( $setup_plugin_settings['widgets'] == 'ok' ){
                    $response['message'] = 'Widgets order imported successfuly! ';
                }
                if( $setup_plugin_settings['settings'] == 'ok' ){
                    $response['message'] .= 'Global settings imported successfuly!';
                }
            }
        } else {
            $response['error']   = true;
            $response['message'] = 'File type error! Only .JSON file please.';
        }
    } else {
        $response['error'] = $json_file['error'];
        $response['message'] = 'Error: ' . $json_file['error'];
    }

    return $response;
}

function wahpro_setup_plugin_settings( $data ){
    $setup_response = array(
        'widgets'  => '',
        'settings' => ''
    );
    /* Step #1 - import global plugin settings */
    if( isset( $data['settings'] ) && $data['settings'] ){
        foreach( $data['settings'] as $key=>$value ){
            wah_set_param( $key, $value );
        }
        $setup_response['settings'] = 'ok';
    }
    /* Step #2 - import widgets order */
    if( isset( $data['widgets'] ) && $data['widgets'] ){
        $update_widgets = wah_update_widgets_order( $data['widgets'] );
        if( $update_widgets == 'ok' ){
            $setup_response['widgets'] = $update_widgets;
        }
    }

    return $setup_response;
}
