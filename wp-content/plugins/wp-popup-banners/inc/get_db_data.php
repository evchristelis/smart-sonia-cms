<?php

defined('ABSPATH') or die("No direct script allowed!!!");

/**
 * get the popup data from database with popup id
 * return the data for functio call or for ajax call
 */
global $wpdb;

$wpb_default_settings = get_option('wpb_default_settings');

$table_name = $wpdb->prefix . "popup_banners";
$wpb_default_id = (isset($_POST['value'])) ? $_POST['value'] : $wpb_default_settings['default_popup_id'];

$popup_details = $wpdb->get_row("SELECT * FROM $table_name where id=$wpb_default_id");

if ((isset($_SESSION['load_popup']) && $_SESSION['load_popup'] == 'false' || !isset($_SESSION['load_popup'])) || isset($_POST['value'])) {
    if ( ! is_null($popup_details) ) {
              $value = unserialize($popup_details->option_value);
    }
    /**
     * if the parameters does not exist on databse, assign the variable with blank
     */
    $table_data = array(
        'overlay_color' => (isset($value['popup_parameters']['overlay_color'])) ? $value['popup_parameters']['overlay_color'] : '',
        'image_path' => (isset($value['popup_image_field']['image_path'])) ? $value['popup_image_field']['image_path'] : '',
        'image_link' => (isset($value['popup_image_field']['image_link'])) ? $value['popup_image_field']['image_link'] : '',
        'target' => (isset($value['popup_image_field']['target'])) ? $value['popup_image_field']['target'] : '',
        'text_image_path' => (isset($value['popup_text_field']['image_path'])) ? $value['popup_text_field']['image_path'] : '',
        'background_color' => (isset($value['popup_text_field']['background_color'])) ? $value['popup_text_field']['background_color'] : '',
        'content' => (isset($value['popup_text_field']['content'])) ? $value['popup_text_field']['content'] : '',
        'popup_options' => (isset($value['popup_parameters']['popup_options'])) ? $value['popup_parameters']['popup_options'] : '',
        'overlay_type' => (isset($value['popup_parameters']['overlay_type'])) ? $value['popup_parameters']['overlay_type'] : '',
        'popup_type' => (isset($value['popup_parameters']['popup_type'])) ? $value['popup_parameters']['popup_type'] : '',
        'border_radius' => (isset($value['popup_parameters']['shape'])) ? $value['popup_parameters']['shape'] : '',
        'height' => (isset($value['popup_parameters']['height'])) ? $value['popup_parameters']['height'] : '',
        'width' => (isset($value['popup_parameters']['width'])) ? $value['popup_parameters']['width'] : '',
        'show_popup' => (isset($value['popup_parameters']['show_popup'])) ? $value['popup_parameters']['show_popup'] : '',
        'close_button' => (isset($value['popup_parameters']['display_close'])) ? $value['popup_parameters']['display_close'] : '',
        'border' => (isset($value['popup_parameters']['border'])) ? $value['popup_parameters']['border'] : '',
        'show_countdown_message' => (isset($value['popup_parameters']['show_countdown_message'])) ? $value['popup_parameters']['show_countdown_message'] : '',
        'border_size' => (isset($value['popup_parameters']['border_size'])) ? $value['popup_parameters']['border_size'] : '',
        'border_color' => (isset($value['popup_parameters']['border_color'])) ? $value['popup_parameters']['border_color'] : '',
        'main_padding' => (isset($value['popup_parameters']['main_padding'])) ? $value['popup_parameters']['main_padding'] : '',
        'popup_delay_enable' => (isset($value['popup_parameters']['popup_delay_enable'])) ? $value['popup_parameters']['popup_delay_enable'] : '',
        'popup_delay' => (isset($value['popup_parameters']['popup_delay'])) ? $value['popup_parameters']['popup_delay'] : '',
        'autoclose_enable' => (isset($value['popup_parameters']['autoclose_enable'])) ? $value['popup_parameters']['autoclose_enable'] : '',
        'popup_close_countdown' => (isset($value['popup_parameters']['popup_close_countdown'])) ? $value['popup_parameters']['popup_close_countdown'] : '',
        'close_countdown_msg' => (isset($value['popup_parameters']['close_countdown_msg'])) ? $value['popup_parameters']['close_countdown_msg'] : '',
        'wpb_countdown_color'=> (isset($value['popup_parameters']['wpb_countdown_color'])) ? $value['popup_parameters']['wpb_countdown_color'] : '',
        'display_on' => (isset($value['popup_parameters']['display_on'])) ? $value['popup_parameters']['display_on'] : '',
        'page_list' => (isset($value['popup_parameters']['page_list'])) ? $value['popup_parameters']['page_list'] : '',
        'disable_window_scroll' => (isset($value['popup_parameters']['disable_window_scroll'])) ? $value['popup_parameters']['disable_window_scroll'] : '',
        'wpb_border_type' => (isset( $value['popup_parameters']['wpb_border_type'] )) ? $value['popup_parameters']['wpb_border_type'] : ''
    );

    if (isset($_POST['value']) && wp_verify_nonce($_POST['nc'], 'wpb_ajax_nonce')) {
        echo json_encode($table_data);
        wp_die();
    } else {
        return $table_data;
    }
}