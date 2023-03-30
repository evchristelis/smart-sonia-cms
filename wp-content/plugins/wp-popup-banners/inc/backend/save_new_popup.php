<?php

defined( 'ABSPATH' ) or die( "No script kiddies please!" );
$_POST = array_map( 'stripslashes_deep', $_POST );
global $wpdb;
$wpb_settings = array();

/**
 * get the popup id if the action is edit
 */
$wpbid = (isset( $_POST['wpb_id'] )) ? $_POST['wpb_id'] : '';


/**
 * check id the form submission for add/edit popup details for add/edit page
 */
if ( isset( $_POST['wpb_save_settings'] ) ) {
    foreach ( $_POST as $key => $val ) {
       if ( $key != 'wpb_input_field_textarea' && $key!= 'wpb_select_pages') {
            $$key = sanitize_text_field( $val );
        } else {
            $$key = $val;
        }
    }
}

/**
 * check id the form submission for popup default value setting for settingspage
 */
if ( isset( $_POST['wbp_enable'] ) || isset( $_POST['wpb_default_id'] ) ) {
    $wpb_settings = array(
        'check_enable' => (isset( $wbp_enable )) ? 1 : '',
        'default_popup_id' => $wpb_default_id
    );


    $update_option_table = update_option( WPB_DEFAULT_VARIABLE, $wpb_settings );
    if ( $update_option_table || $wpdb->last_error == '' ) {
        $_SESSION['wpb_db_success'] = __( 'Default Value Updated Successfully', 'wp-popup-banners' );
    } else {
        $_SESSION['wpb_db_fail'] = __( 'Failed to Update Default Option.', 'wp-popup-banners' );
    }
} else {
    $table_name = $wpdb->prefix . "popup_banners";

    $pages_list = '';
    if ( isset( $_POST['wpb_select_pages'] ) ) {
        $pages_list = $_POST['wpb_select_pages'];
    }


    $popup_parameters = array(
        'title' => $wpb_title,
        'popup_options' => $select_option,
        'popup_type' => $wpb_popup_option,
        'width' => $wpb_width,
        'height' => $wpb_height,
        'popup_display_type' => $popup_display_type,
        'shape' => $wpb_radious,
        'overlay_type' => $wpb_overlay_type,
        'overlay_color' => $wpb_overlay_color,
        'show_popup' => $wpb_show_popup,
        'mobile_enable' => (isset( $wpb_mobile_enable )) ? 'yes' : 'no',
        'display_close' => $wpb_close,
        'added_date' => date( 'Y-m-d h:i:s' ),
        'background_type' => isset( $background_type ) ? $background_type : 'color',
        'background_repeat' => $wpb_background_repeat,
        'border' => $wpb_border,
        'show_countdown_message' => $wpb_countdown_msg,
        'border_size' => $wpb_border_size,
        'border_color' => $wpb_border_color,
        'main_padding' => $wpb_main_padding,
        'popup_delay_enable' => (isset( $wpb_popup_delay_enable )) ? 'yes' : 'no',
        'popup_delay' => $wpb_popup_delay,
        'autoclose_enable' => (isset( $wpb_autoclose_enable )) ? 'yes' : 'no',
        'popup_close_countdown' => $wpb_close_countdown,
        'close_countdown_msg' => $wpb_close_countdown_msg,
        'wpb_countdown_color' => $wpb_countdown_color,
        'display_on' => $wpb_display_on,
        'page_list' => $pages_list,
        'disable_window_scroll' => isset( $disable_window_scroll )?'on':'off',
        'wpb_border_type' => $wpb_border_type,
    );
    //$this->print_array($popup_parameters);die();
    $wpb_image_fields = array(
        'image_path' => $wpb_upload_image,
        'image_link' => $wpb_link,
        'target' => $wpb_target
    );

    $contents = $this->sanitize_escaping_linebreaks( $wpb_input_field_textarea );

    $wpb_text_fields = array(
        'content' => (isset( $wpb_input_field_textarea )) ? $contents : '',
        'background_color' => (isset( $wpb_background_color )) ? $wpb_background_color : '',
        'image_path' => $wpb_upload_image2
    );

    $all_parameters = array(
        'popup_parameters' => $popup_parameters,
        'popup_image_field' => $wpb_image_fields,
        'popup_text_field' => $wpb_text_fields,
    );
    /**
     * update the popup details
     */
    if ( $wpbid != '' ) {
        $update = $wpdb->update(
                $table_name, array(
            'option_value' => serialize( $all_parameters )
                ), array(
            'id' => $wpbid
                )
        );

        if ( $update ) {//if update success
            $_SESSION['wpb_db_success'] = __( 'Banner Updated Successfully', 'wp-popup-banners' );
        } else {
            $_SESSION['wpb_db_fail'] = __( 'Failed to Update Banner.', 'wp-popup-banners' );
        }
    } else {

        /**
         * insert the popup details
         */
        $insert = $wpdb->insert(
                $table_name, array(
            'option_value' => serialize( $all_parameters )
                )
        );

        $wpb_default_settings = get_option( 'wpb_default_settings' );
        if ( $wpb_default_settings['default_popup_id'] == '' ) {
            $wpb_settings = array(
                'default_popup_id' => $wpdb->insert_id
            );

            $update_option_table = update_option( WPB_DEFAULT_VARIABLE, $wpb_settings );
        }

        if ( $insert ) {//if insert success
            $wpbid = $wpdb->insert_id;
            $_SESSION['wpb_db_success'] = __( 'Banner inserted Successfully', 'wp-popup-banners' );
        } else {
            $_SESSION['wpb_db_fail'] = __( 'Failed to add new Banner.', 'wp-popup-banners' );
        }
    }
}

wp_redirect( admin_url() . 'admin.php?page=wpb&action=edit&wpbid=' . $wpbid );
exit;

// end