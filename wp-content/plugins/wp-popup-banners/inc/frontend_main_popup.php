<?php
defined( 'ABSPATH' ) or die( "No direct script allowed!!!" );

/**
 * display popup on front page with dynamic values from database
 * note: this page is only for front page and for backend preview,
 * we have different page with same structure
 */
if ( isset( $default_wpb_id ) && $default_wpb_id != '' ) {
    $parameteres = array();
    $value = $popup_details[0];
    $parameteres = unserialize( $value->option_value );
    // $this->print_array( $parameteres );
    $popup_options = esc_attr( $parameteres['popup_parameters']['popup_options'] );
    $title = esc_attr( $parameteres['popup_parameters']['title'] );
    $overlay_type = esc_attr( $parameteres['popup_parameters']['overlay_type'] );
    $show_popup = esc_attr( $parameteres['popup_parameters']['show_popup'] );
    $display_close = esc_attr( $parameteres['popup_parameters']['display_close'] );
    $mobile_enable = esc_attr( $parameteres['popup_parameters']['mobile_enable'] );
    $overlay_color = esc_attr( $parameteres['popup_parameters']['overlay_color'] );
    $popup_type = esc_attr( $parameteres['popup_parameters']['popup_type'] );
    $border_radius = esc_attr( $parameteres['popup_parameters']['shape'] );
    $height = esc_attr( $parameteres['popup_parameters']['height'] );
    $width = esc_attr( $parameteres['popup_parameters']['width'] );
    $popup_display_type = isset($parameteres['popup_parameters']['popup_display_type'])?esc_attr( $parameteres['popup_parameters']['popup_display_type'] ):'fixed';
    $wpb_background_repeat = esc_attr( $parameteres['popup_parameters']['background_repeat'] );
    $wpb_background_type = esc_attr( $parameteres['popup_parameters']['background_type'] );
    $wpb_background_image = esc_attr( $parameteres['popup_text_field']['image_path'] );
    $wpb_border = esc_attr( $parameteres['popup_parameters']['border'] );
    $wpb_countdown_message = esc_attr( $parameteres['popup_parameters']['show_countdown_message'] );
    $border = esc_attr( $parameteres['popup_parameters']['border'] );
    $wpb_border_size = esc_attr( $parameteres['popup_parameters']['border_size'] );
    $wpb_border_color = esc_attr( $parameteres['popup_parameters']['border_color'] );
    $wpb_main_padding = esc_attr( $parameteres['popup_parameters']['main_padding'] );
    $wpb_popup_delay = esc_attr( $parameteres['popup_parameters']['popup_delay'] );
    $wpb_popup_close_countdown = isset($parameteres['popup_parameters']['popup_close_countdown'])?esc_attr( $parameteres['popup_parameters']['popup_close_countdown'] ):'';
    $close_countdown_msg = esc_attr( $parameteres['popup_parameters']['close_countdown_msg'] );
    $autoclose_enable = esc_attr( $parameteres['popup_parameters']['autoclose_enable'] );
    $wpb_display_on = esc_attr( $parameteres['popup_parameters']['display_on'] );
    $wpb_page_list = $parameteres['popup_parameters']['page_list'];
    $image_path = esc_url( $parameteres['popup_image_field']['image_path'] );
    $image_link = esc_url( $parameteres['popup_image_field']['image_link'] );
    $target = esc_attr( $parameteres['popup_image_field']['target'] );
    $wpb_countdown_color = isset($parameteres['popup_parameters']['wpb_countdown_color'])?esc_attr( $parameteres['popup_parameters']['wpb_countdown_color'] ):'';
    $text_image_path = esc_url( $parameteres['popup_text_field']['image_path'] );
    $background_color = esc_attr( $parameteres['popup_text_field']['background_color'] );
    $content = str_replace( '<br>', '', $parameteres['popup_text_field']['content'] );
    $disable_window_scroll = isset($parameteres['popup_parameters']['disable_window_scroll']) && !empty($parameteres['popup_parameters']['disable_window_scroll'])?esc_attr($parameteres['popup_parameters']['disable_window_scroll'] ):'';
    $wpb_border_type = (isset( $parameteres['popup_parameters']['wpb_border_type'] )) ? esc_attr($parameteres['popup_parameters']['wpb_border_type']) : '';
    $frontend_popup = true;
    $wpb_display_on; //if 3, list the pages
    $wpb_page_list; // list of pages
    ?>

<div class="wpb-outer-wrap">
        <style type="text/css">
    <?php if ( $popup_options == 'image' && $height != '') {
        ?>
                div#wpb-scroll-div.wpb-main-wrapper.wpb-image-popup{ max-height:<?php echo $height?>;height: <?php echo $height?>;}
        <?php
    }
    ?>
            .wpb-main-wrapper{
                border-radius: <?php echo ($border_radius == 'rounded_corner') ? '10px' : '0px' ?>;
    <?php if ( $height != '' && $popup_options != 'image') { ?>
                    max-height: <?php echo $height; ?>;
    <?php } ?>
    <?php if ( trim( $width != '' ) ) { ?>
                    max-width: <?php echo $width; ?>;
    <?php } ?>
    <?php if ( $background_color != '' && $wpb_background_type == 'color' ) { ?>
                    background-color: <?php echo $background_color; ?>;
        <?php
    }
    if ( $wpb_background_type == 'image' && $wpb_background_image != '' ) {
        ?>
                    background-image:url("<?php echo $wpb_background_image ?>");
                    background-repeat: <?php echo $wpb_background_repeat; ?>;
                    background-position: center; 
        <?php
    }
    ?>
    <?php if ( $border == 'yes' && $wpb_border_size != '' ) { ?>
                    border-style: <?php echo $wpb_border_type;?>;
                    border-width: <?php
        echo $wpb_border_size;
        echo (strpos( $wpb_border_size, 'px' ) == false) ? 'px' : '';
        ?>;
    <?php } ?>
    <?php if ( $wpb_border_color != '' ) { ?>
                    border-color : <?php echo $wpb_border_color; ?>;
    <?php } ?>
    <?php if ( $wpb_main_padding != '' ) { ?>
                    padding: <?php
        echo $wpb_main_padding;
        echo (strpos( $wpb_main_padding, 'px' ) == false) ? 'px' : '';
        ?> !important;
    <?php } ?>
                position:<?php echo esc_attr( $popup_display_type ); ?>;

            }
    <?php if ( $overlay_type != 0 ) { ?>
                #wpb_overlay{
                    opacity: <?php echo $overlay_type; ?>;
                    background-color: <?php echo $overlay_color; ?>;
                }
    <?php } ?>
            .wpb-preview-title {
                position: fixed;
                top: 6%;
                z-index: 99999;
                right: 40px;
                color: #fff;
                font-size: 25px;
            }
    <?php if( isset($disable_window_scroll) &&  $disable_window_scroll == 'on'){  ?>
            body{
                overflow:hidden;
            }  
    <?php } ?>
            .wpb-popup-timer-content{
            color:<?php if(isset($wpb_countdown_color)){ echo $wpb_countdown_color; }else{ echo '#000000'; }?>
            }
        </style>

        <?php
    }
    ?>
    <?php if ( isset( $_GET['wpb_preview'] ) && $_GET['wpb_preview'] ) {
        ?>
        <span class="wpb-preview-title"><?php _e( 'Popup Preview', 'wp-popup-banners' ); ?></span>
        <?php
    }
    ?>
    <div class="wpb-main-wrapper wpb-<?php echo $popup_options; ?>-popup" id="wpb-scroll-div">
        <?php if ( isset( $frontend_popup ) && $display_close == 'yes' ) { ?>
            <div class="wpb_close_btn"></div>
            <div class="clear"></div>
        <?php } ?>
        <div class="wpb-popup-content">
            <?php
            if ( isset( $frontend_popup ) ) {

                if ( $popup_options == 'image' ) {
                    $image = $image_path;
                } else if ( $popup_options == 'text' ) {
                    $image = $text_image_path;
                } else {
                    $image = '';
                }
                ?>

                <?php if ( $image != '' && $popup_options == 'image' ) { ?>
                    <a href="<?php echo ($image_link != '') ? $image_link : '#'; ?>" target="<?php echo $target; ?>" >
                        <div class="wpb-background-image">
                            <img class="wpb_background_img wpb_overlay" src="<?php echo $image; ?>" >
                        </div>
                    </a>
                    <?php
                }
            }
            ?>

            <?php
            if ( isset( $frontend_popup ) && $content != '' && $popup_options == 'text' ) {
                ?>
                <div class="wpb-background-content">
                    <?php
                    echo $content;
                    ?>

                </div>
                <?php
            }
            ?>
            <?php if ( $autoclose_enable == 'yes' && $wpb_countdown_message == 'yes' && $wpb_popup_close_countdown != '' ) { ?>
                <div class="wpb-popup-timer-content">
                    <?php
                    echo str_replace( '#timer', '<span class="wpb_countdown"></span>', $close_countdown_msg );
                    ?>
                </div>
            <?php } ?>				
        </div>	
    </div>
    <?php if ( $overlay_type != 0 ) { ?><div id="wpb_overlay"></div><?php } ?>
</div>
<?php if($mobile_enable == 'yes') { ?>
    <style type="text/css">
        @media (max-width: 425px) {
            .wpb-outer-wrap{
                display: none;
            }
        }
    </style>
<?php } ?>