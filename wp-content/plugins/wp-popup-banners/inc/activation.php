<?php

defined('ABSPATH') or die("No direct script allowed!!!");

/**
 * update the default setting if not exist
 */
if (is_multisite()) {
    global $wpdb;
    $current_blog = $wpdb->blogid;

// Get all blogs in the network and activate plugin on each one
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);


        if (!get_option('wpb_default_settings')) {
            $wpb_settings = $this->get_default_settings();
            update_option('wpb_default_settings', $wpb_settings);
        }

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . "popup_banners";

        /**
         * create the table id not exist
         */
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          option_value longtext NOT NULL,
          UNIQUE KEY id (id)
          )
          $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
        $table_name = strtolower($table_name);

        /**
         * if table is empty, insert the default popup values
         */
        $empty = $wpdb->get_results("SELECT * FROM $table_name");
        if (empty($empty)) {
            /**
             * the popup values are saved to the database
             * with separate three keys. such as for image, 
             * text and other parameters 
             */
            $wpb_image_fields = array(
                'image_path' => 'https://pixabay.com/static/uploads/photo/2014/04/09/09/56/dandelion-319939_960_720.jpg',
                'image_link' => 'https://pixabay.com/static/uploads/photo/2014/04/09/09/56/dandelion-319939_960_720.jpg',
                'target' => '_blank'
            );

            $wpb_text_fields = array(
                'content' => 'This is the sample content.',
                'background_color' => 'white',
                'image_path' => 'https://pixabay.com/static/uploads/photo/2014/04/09/09/56/dandelion-319939_960_720.jpg',
            );

            $popup_parameters = array(
                'title' => 'Demo Popup',
                'popup_options' => 'image',
                'popup_type' => 'fade',
                'width' => '',
                'height' => '',
                'shape' => 'rounded_corner',
                'overlay_type' => '0.8',
                'overlay_color' => '#000',
                'show_popup' => '2',
                'display_close' => 'yes',
                'mobile_enable' => 'yes',
                'added_date' => date('Y-m-d h:i:sa'),
                'background_type' => 'color',
                'wpb_background_color' => '#fff',
                'background_repeat' => '',
                'border' => '',
                'autoclose_enable' => '',
                'show_countdown_message' => '',
                'close_countdown_msg' => '',
                'border_size' => '',
                'border_color' => '',
                'main_padding' => '',
                'popup_delay' => '',
                'disable_window_scroll' => '',
                'popup_delay_enable' => '',
                'display_on' => '',
                'page_list' => '',
            );

            $all_parameters = array(
                'popup_parameters' => $popup_parameters,
                'popup_image_field' => $wpb_image_fields,
                'popup_text_field' => $wpb_text_fields
            );

            $insert_default = $wpdb->insert($table_name, array(
                'option_value' => serialize($all_parameters)
            ));
        }
        restore_current_blog();
    }
} else {
    if (!get_option('wpb_default_settings')) {
        $wpb_settings = $this->get_default_settings();
        update_option('wpb_default_settings', $wpb_settings);
    }

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $table_name = $wpdb->prefix . "popup_banners";

    /**
     * create the table id not exist
     */
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          option_value longtext NOT NULL,
          UNIQUE KEY id (id)
          )
          $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
    $table_name = strtolower($table_name);

    /**
     * if table is empty, insert the default popup values
     */
    $empty = $wpdb->get_results("SELECT * FROM $table_name");
    if (empty($empty)) {
        /**
         * the popup values are saved to the database
         * with separate three keys. such as for image, 
         * text and other parameters 
         */
        $wpb_image_fields = array(
            'image_path' => 'https://pixabay.com/static/uploads/photo/2014/04/09/09/56/dandelion-319939_960_720.jpg',
            'image_link' => 'https://pixabay.com/static/uploads/photo/2014/04/09/09/56/dandelion-319939_960_720.jpg',
            'target' => '_blank'
        );

        $wpb_text_fields = array(
            'content' => 'This is the sample content.',
            'background_color' => 'white',
            'image_path' => 'https://pixabay.com/static/uploads/photo/2014/04/09/09/56/dandelion-319939_960_720.jpg',
        );

        $popup_parameters = array(
            'title' => 'Demo Popup',
            'popup_options' => 'image',
            'popup_type' => 'fade',
            'width' => '',
            'height' => '',
            'shape' => 'rounded_corner',
            'overlay_type' => '0.8',
            'overlay_color' => '#000',
            'show_popup' => '2',
            'display_close' => 'yes',
            'mobile_enable' => 'yes',
            'added_date' => date('Y-m-d h:i:sa'),
            'background_type' => 'color',
            'wpb_background_color' => '#fff',
            'background_repeat' => '',
            'border' => '',
            'autoclose_enable' => '',
            'show_countdown_message' => '',
            'close_countdown_msg' => '',
            'border_size' => '',
            'border_color' => '',
            'main_padding' => '',
            'popup_delay' => '',
            'disable_window_scroll' => '',
            'popup_delay_enable' => '',
            'display_on' => '',
            'page_list' => '',
        );

        $all_parameters = array(
            'popup_parameters' => $popup_parameters,
            'popup_image_field' => $wpb_image_fields,
            'popup_text_field' => $wpb_text_fields
        );

        $insert_default = $wpdb->insert($table_name, array(
            'option_value' => serialize($all_parameters)
        ));
    }
}

  