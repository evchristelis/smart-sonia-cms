<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

global $wpdb;
$table_name = $wpdb->prefix . "popup_banners";

if ( isset( $_GET['wpbid'] ) && $_GET['wpbid'] != '' && isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {
    $wpb_id = sanitize_text_field($_GET['wpbid']);

    $popup_details = $wpdb->get_results( "SELECT * FROM $table_name where id=$wpb_id" );


    if ( !empty( $popup_details ) ) {
        $edit = true;

        $parameteres = array();

        foreach ( $popup_details as $key => $value ) {
            $parameteres = unserialize( $value->option_value );

            $popup_options = (isset( $parameteres['popup_parameters']['popup_options'] )) ? $parameteres['popup_parameters']['popup_options'] : '';
            $title = (isset( $parameteres['popup_parameters']['title'] )) ? $parameteres['popup_parameters']['title'] : '';
            $overlay_type = (isset( $parameteres['popup_parameters']['overlay_type'] )) ? $parameteres['popup_parameters']['overlay_type'] : '';
            $show_popup = (isset( $parameteres['popup_parameters']['show_popup'] )) ? $parameteres['popup_parameters']['show_popup'] : '';
            $display_close = (isset( $parameteres['popup_parameters']['display_close'] )) ? $parameteres['popup_parameters']['display_close'] : 'yes';
            $mobile_enable = (isset( $parameteres['popup_parameters']['mobile_enable'] )) ? $parameteres['popup_parameters']['mobile_enable'] : '';
            $overlay_color = (isset( $parameteres['popup_parameters']['overlay_color'] )) ? $parameteres['popup_parameters']['overlay_color'] : '';
            $popup_type = (isset( $parameteres['popup_parameters']['popup_type'] )) ? $parameteres['popup_parameters']['popup_type'] : '';
            $border_radius = (isset( $parameteres['popup_parameters']['shape'] )) ? $parameteres['popup_parameters']['shape'] : '';
            $height = (isset( $parameteres['popup_parameters']['height'] )) ? $parameteres['popup_parameters']['height'] : '';
            $width = (isset( $parameteres['popup_parameters']['width'] )) ? $parameteres['popup_parameters']['width'] : '';
            $popup_display_type = (isset( $parameteres['popup_parameters']['popup_display_type'] )) ? $parameteres['popup_parameters']['popup_display_type'] : 'fixed';

            $image_path = (isset( $parameteres['popup_image_field']['image_path'] )) ? $parameteres['popup_image_field']['image_path'] : '';
            $image_link = (isset( $parameteres['popup_image_field']['image_link'] )) ? $parameteres['popup_image_field']['image_link'] : '';
            $target = (isset( $parameteres['popup_image_field']['target'] )) ? $parameteres['popup_image_field']['target'] : '';

            $text_image_path = (isset( $parameteres['popup_text_field']['image_path'] )) ? $parameteres['popup_text_field']['image_path'] : '';
            $popup_background_type = isset($parameteres['popup_parameters']['background_type'])?esc_attr($parameteres['popup_parameters']['background_type']):'color';
            $background_color = (isset( $parameteres['popup_text_field']['background_color'] )) ? $parameteres['popup_text_field']['background_color'] : '';
            $wpb_countdown_color = (isset( $parameteres['popup_parameters']['wpb_countdown_color'] )) ? $parameteres['popup_parameters']['wpb_countdown_color'] : '';
            $content1 = $this->output_converting_br( $parameteres['popup_text_field']['content'] );
            $content = (isset( $parameteres['popup_text_field']['content'] )) ? $content1 : '';
            $wpb_background_repeat = (isset( $parameteres['popup_parameters']['background_repeat'] )) ? $parameteres['popup_parameters']['background_repeat'] : '';
            $wpb_border = (isset( $parameteres['popup_parameters']['border'] )) ? $parameteres['popup_parameters']['border'] : '';
            $wpb_countdown_message = (isset( $parameteres['popup_parameters']['show_countdown_message'] )) ? $parameteres['popup_parameters']['show_countdown_message'] : '';
            $wpb_border_size = (isset( $parameteres['popup_parameters']['border_size'] )) ? $parameteres['popup_parameters']['border_size'] : '';
            $wpb_border_color = (isset( $parameteres['popup_parameters']['border_color'] )) ? $parameteres['popup_parameters']['border_color'] : '';
            $wpb_main_padding = (isset( $parameteres['popup_parameters']['main_padding'] )) ? $parameteres['popup_parameters']['main_padding'] : '';
            $wpb_popup_delay = (isset( $parameteres['popup_parameters']['popup_delay'] )) ? $parameteres['popup_parameters']['popup_delay'] : '';
            $wpb_popup_delay_enable = (isset( $parameteres['popup_parameters']['popup_delay_enable'] )) ? $parameteres['popup_parameters']['popup_delay_enable'] : '';
            $wpb_autoclose_enable = (isset( $parameteres['popup_parameters']['autoclose_enable'] )) ? $parameteres['popup_parameters']['autoclose_enable'] : '';
            $wpb_close_countdown = (isset( $parameteres['popup_parameters']['popup_close_countdown'] )) ? $parameteres['popup_parameters']['popup_close_countdown'] : '';
            $wpb_close_countdown_msg = (isset( $parameteres['popup_parameters']['close_countdown_msg'] )) ? $parameteres['popup_parameters']['close_countdown_msg'] : '';
            $wpb_display_on = (isset( $parameteres['popup_parameters']['display_on'] )) ? $parameteres['popup_parameters']['display_on'] : '';
            $wpb_page_list = (isset( $parameteres['popup_parameters']['page_list'] )) ? $parameteres['popup_parameters']['page_list'] : '';
            $disable_window_scroll = (isset( $parameteres['popup_parameters']['disable_window_scroll'] )) ? $parameteres['popup_parameters']['disable_window_scroll'] : '';
            $wpb_border_type = (isset( $parameteres['popup_parameters']['wpb_border_type'] )) ? $parameteres['popup_parameters']['wpb_border_type'] : '';
        }
        
    } else {

        $_SESSION['wpb_db_fail'] = __( 'Invalid link or data not found', 'wp-popup-banners' );

        echo "<script>
            window.location.href = 'admin.php?page=wpb';
            </script>";
    }
} else if ( isset( $_GET['wpbid'] ) && $_GET['wpbid'] == '' ) {
    // $_SESSION['wpb_db_fail'] = __('Invalid link', 'wp-popup-banners');

    echo "<script>
        window.location.href = 'admin.php?page=wpb';
        </script>";
}
?>

<div class="wbp-tab-title">
    <?php echo ( isset( $_GET['action'] ) && $_GET['action'] == 'add' ) ? __( 'Add New Popup Banner', 'wp-popup-banners' ) : __( 'Edit Popup Banner', 'wp-popup-banners' ); ?>
</div>


<h2 class="nav-tab-wrapper">
    <a  href="javascript:void(0)" class="nav-tab second-nav nav-tab-active" id="wpb-general-settings"><?php _e( 'General Settings', 'wp-popup-banners' ); ?></a>
    <a  href="javascript:void(0)" class="nav-tab second-nav" id="wpb-display-settings"><?php _e( 'Display Settings', 'wp-popup-banners' ); ?></a>
</h2>  

<div class="wpb-general-options postbox">    
    <form action="<?php echo admin_url() . 'admin-post.php' ?>" method='post' id="wpb_form">
        <input type="hidden" name="action" value="wpb_save_options" />
        <?php
        wp_nonce_field( 'wpb_nonce_save_settings', 'wpb_add_nonce_save_settings' );
        include(WPB_ABSPATH . '/inc/backend/manage-popup-tabs/general-settings.php');
        include(WPB_ABSPATH . '/inc/backend/manage-popup-tabs/display-settings.php');
        ?>
        <div class="clearfix"></div>
        <input type="hidden" value="<?php if(isset($wpb_id)){ echo $wpb_id; } ?>" name="wpb_id">
        <input class="button-primary wpb-save-button" type="submit" name="wpb_save_settings" value="<?php _e( 'Save', 'wp-popup-banner' ); ?>" />
        <?php if(isset($_GET['wpbid'])){?>
        <a href="<?php echo site_url('?wpb_preview=true&banner_id='.$_GET['wpbid']);?>" target="_blank">
            <input class="button-primary" type="button"  value="<?php esc_attr_e( 'Preview' ); ?>" />
        </a>
        <p class="description"><?php _e('Please save the banner before preview.','wp-popup-banners');?></p>
        <?php }?>
        <div id="wpb_submit_msg"></div>
    </form>
</div>
