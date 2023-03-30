<div class="wpb-general-settings wpb-input-field">
    <div class="wpb-input-field-wrap">
        <label>
            <?php echo __('Title', 'wp-popup-banners') ?>
        </label>
        <div class="wpb-input-field">
            <input type="text" placeholder="<?php echo __('Title of the Banner', 'wp-popup-banners') ?>" name="wpb_title" value="<?php echo (isset($edit)) ? $title : ''; ?>">
        </div>
    </div> 

    <div class="wpb-input-field-wrap">
        <label><?php echo __('Select Option', 'wp-popup-banners') ?></label>
        <div class="wpb-input-field">
            <select id="select_option" name="select_option">
                <!-- <option value="">Select Option</option> -->
                <option value="image" <?php if (isset($edit) && $popup_options == 'image') { ?> selected="selected" <?php } ?>><?php echo __('Image', 'wp-popup-banners') ?></option>
                <option value="text" <?php if (isset($edit) && $popup_options == 'text') { ?> selected="selected" <?php } ?>><?php echo __('Text', 'wp-popup-banners') ?></option>               
            </select>
        </div>
    </div>
    <div class="wpb-image-type" <?php if (isset($edit) && $popup_options == 'text') { ?> style="display:none" <?php } ?>>
        <div class="wpb-option-field">
            <label>
                <?php echo __('Upload Image', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
                <input id="wpb_upload_image" type="text" name="wpb_upload_image" value="<?php echo (isset($edit)) ? $image_path : ''; ?>" />
                <input class="upload_image_button button button-primary" type="button" value="Upload Image" />
                <p class="description" ><?php echo __('We can directly upload the image or we can also give the external image link on the input field. eg: https://upload.wikimedia.org/wikipedia/commons/3/36/Hopetoun_falls.jpg', 'wp-popup-banners') ?></p>
            </div>

        </div>              

        <div class="wpb-option-field">
            <label>
                <?php echo __('Link', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
                <input type="text" name="wpb_link" placeholder="eg. http://www.google.com" value="<?php echo (isset($edit)) ? $image_link : ''; ?>">
                <p class="description" ><?php echo __('Link for the image', 'wp-popup-banners') ?></p>
            </div>
        </div>              

        <div class="wpb-option-field">
            <label>
                <?php echo __('Target', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
                <select id="" name="wpb_target">
                    <option value="_blank" <?php if (isset($edit) && $target == '_blank') { ?> selected="selected" <?php } ?>>_blank</option>
                    <option value="_self" <?php if (isset($edit) && $target == '_self') { ?> selected="selected" <?php } ?>>_self</option>
                    <option value="_top" <?php if (isset($edit) && $target == '_top') { ?> selected="selected" <?php } ?>>_top</option>
                    <option value="_parent" <?php if (isset($edit) && $target == '_parent') { ?> selected="selected" <?php } ?>>_parent</option>
                </select>
                <p class="description" ><?php echo __('Open the link on the current or on new tab', 'wp-popup-banners') ?></p>

            </div>
        </div>
    </div>
    <div class="wpb-text-type" <?php if (isset($edit) && $popup_options == 'image') { ?> style="display:none" <?php } else if (!isset($edit)) { ?> style="display:none" <?php } ?> >
        <div class="wpb-option-field">
            <label>
                <?php echo __('Content', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
                <?php wp_editor((isset($edit)) ? $content : "", 'wpb-popup-content', $settings = array('textarea_name' => 'wpb_input_field_textarea', 'class' => 'wpb-popup-content', 'media_buttons' => true)); ?> 
                <p class="description" ><?php echo __('Enter the popup content', 'wp-popup-banners') ?></p>
            </div>
        </div>
    </div>
    <div class="wpb-input-field-wrap">
        <label><?php echo __('Popup Animation', 'wp-popup-banners') ?> </label>
        <div class="wpb-input-field">
            <select id="wpb_popup_option" name="wpb_popup_option">
                <option value="fade" <?php if (isset($edit) && $popup_type == 'fade') { ?> selected="selected" <?php } ?>><?php echo __('Fade(Default)', 'wp-popup-banners') ?></option>
                <option value="slide-from-top" <?php if (isset($edit) && $popup_type == 'slide-from-top') { ?> selected="selected" <?php } ?>><?php echo __('Slide From Top', 'wp-popup-banners') ?></option>
                <option value="slide-from-top2" <?php if (isset($edit) && $popup_type == 'slide-from-top2') { ?> selected="selected" <?php } ?>><?php echo __('Popup', 'wp-popup-banners') ?></option>
                <option value="zoom" <?php if (isset($edit) && $popup_type == 'zoom') { ?> selected="selected" <?php } ?>><?php echo __('Zoom', 'wp-popup-banners') ?></option> 
                <option value="slide-fix" <?php if (isset($edit) && $popup_type == 'slide-fix') { ?> selected="selected" <?php } ?>><?php echo __('Slide', 'wp-popup-banners') ?></option>
            </select>
        </div>
    </div>            
    <div class="wpb-input-field-wrap">
        <label><?php echo __('Show', 'wp-popup-banners') ?></label>
        <div class="wpb-input-field">
            <select id="wpb_show_popup" name="wpb_show_popup">
                <option value="1" <?php if (isset($edit) && $show_popup == '1') { ?> selected="selected" <?php } ?> ><?php echo __('Show Every Time', 'wp-popup-banners') ?></option>
                <option value="2" <?php if (isset($edit) && $show_popup == '2') { ?> selected="selected" <?php } ?> ><?php echo __('Show Once', 'wp-popup-banners') ?></option>
            </select>
            <p class="description" ><?php echo __('Show the popup only once or every time the page loads', 'wp-popup-banners') ?></p>
        </div>
    </div>

    <div class="wpb-option-field wpb-input-field-wrap">
        <label>
            <?php echo __('Disable on Mobile', 'wp-popup-banners'); ?>
        </label>
        <div class="wpb-input-field">
            <input type="checkbox" name="wpb_mobile_enable" <?php if (isset($edit) && $mobile_enable == 'yes') { ?> checked <?php } ?> >
            <p class="description" ><?php echo __('We can enable or diasble popup on mobile browsers', 'wp-popup-banners') ?></p>
        </div>
    </div>            

    <div class="wpb-input-field-wrap">
        <label>
            <?php echo __('Enable Popup Delay', 'wp-popup-banners'); ?>
        </label>
        <div class="wpb-input-field">
            <input type="checkbox" id="wpb_popup_delay_enable" name="wpb_popup_delay_enable" <?php if (isset($edit) && $wpb_popup_delay_enable == 'yes') { ?> checked <?php } ?> >
            <p class="description" ><?php echo __('Enable or disable popup delay after page load', 'wp-popup-banners') ?></p>
        </div>
    </div>

    <div class="wpb-popup-delay-div" <?php if ((isset($edit) && $wpb_popup_delay_enable != 'yes') || !(isset($edit))) { ?> style="display:none;" <?php } ?> >
        <div class="wpb-input-field-wrap">
            <label>
                <?php echo __('Popup Delay Time', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
                <input type="number" min="1" id="wpb_popup_delay" name="wpb_popup_delay"  value="<?php echo (isset($edit)) ? $wpb_popup_delay : ''; ?>"  onchange="handleChange(this);" > In second
                 <!-- <p class="description" ><?php echo __('Please enter the popup delay between 0-10 seconds', 'wp-popup-banners') ?></p> -->
            </div>
        </div>
    </div>


    <div class="wpb-input-field-wrap">
        <label>
            <?php echo __('Enable Auto close', 'wp-popup-banners'); ?>
        </label>
        <div class="wpb-input-field">
            <input type="checkbox" id="wpb_autoclose_enable" name="wpb_autoclose_enable" <?php if (isset($edit) && $wpb_autoclose_enable == 'yes') { ?> checked <?php } ?> >
            <p class="description" ><?php echo __('Enable or disable popup autoclose. The popup will automatically close after some period of time if user does not close.', 'wp-popup-banners') ?></p>
        </div>
    </div>


    <div class="wpb-enable-autoclose" <?php if ((isset($edit) && $wpb_autoclose_enable != 'yes') || !(isset($edit))) { ?> style="display:none;" <?php } ?>>
        <div class="wpb-input-field-wrap">
            <label>
                <?php echo __('Close Countdown', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
                <input type="number" id="wpb_close_countdown" name="wpb_close_countdown" min="10" value="<?php echo (isset($edit)) ? $wpb_close_countdown : ''; ?>"  onchange="handleChange(this);" > In second
                <p class="description" ><?php echo __('Please enter the autoclose countdown time in seconds. Minimum time required is 10 seconds.', 'wp-popup-banners') ?></p>
            </div>
        </div>

        <div class="wpb-input-field-wrap">
            <label>
                <?php echo __('Show count down message', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
                <select id="wpb-countdown-msg" name="wpb_countdown_msg">
                    <option value="no" <?php if (isset($edit) && $wpb_countdown_message == 'no') { ?> selected="selected" <?php } ?> ><?php echo __('No', 'wp-popup-banners') ?></option>
                    <option value="yes" <?php if (isset($edit) && $wpb_countdown_message == 'yes') { ?> selected="selected" <?php } ?> ><?php echo __('Yes', 'wp-popup-banners') ?></option>
                </select>
                <p class="description" ><?php echo __('Show countdown message', 'wp-popup-banners') ?></p>
            </div>
        </div>

        <div class="wpb-countdown-message-div" <?php if ((isset($edit) && $wpb_countdown_message != 'yes') || !isset($edit)) { ?>style="display:none" <?php } ?> >
            <label>
                <?php echo __('Countdown Message', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
                <textarea id="wpb_close_countdown_msg"  name="wpb_close_countdown_msg" placeholder="eg. Popup will automatically close in #timer seconds">
                    <?php echo (isset($edit)) ? $wpb_close_countdown_msg : ''; ?>
                </textarea>
                <p class="description" ><?php echo __('Please use #timer in the message where you want to display the timer.', 'wp-popup-banners') ?></p>
            </div>
        </div>
        <div class="wpb-countdown-message-div" <?php if ((isset($edit) && $wpb_countdown_message != 'yes') || !isset($edit)) { ?>style="display:none" <?php } ?> >
            <label>
                <?php echo __('Countdown Message Font Color', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
            <input type="text" id="wpb_countdown_color" name="wpb_countdown_color" value="<?php echo (isset($edit)) ? $wpb_countdown_color : ''; ?>">    
            </div>
        </div>
    </div>

    <div class="wpb-input-field-wrap">
        <label>
            <?php echo __('Disable Window Scrolling', 'wp-popup-banners'); ?>
        </label>
        <div class="wpb-input-field">
            <input type="checkbox" id="wpb_disable_window_scroll" name="disable_window_scroll" value="on" <?php if (isset($edit) && $disable_window_scroll == 'on') { ?> checked <?php } ?> >
            <p class="description" ><?php echo __('If enabled, popup will be fixed on screen and user cannot scroll the page until some action.', 'wp-popup-banners') ?></p>
        </div>
    </div>

    <div class="wpb-input-field-wrap">
        <label>
            <?php echo __('Popup Display', 'wp-popup-banners') ?>
        </label>
        <div class="wpb-input-field">
            <select id="wpb_display_on" name="wpb_display_on">
                <option value="1" <?php if (isset($edit) && $wpb_display_on == '1') { ?> selected="selected" <?php } ?> ><?php echo __('Only on Home Page', 'wp-popup-banners') ?></option>
                <option value="2" <?php if (isset($edit) && $wpb_display_on == '2') { ?> selected="selected" <?php } ?> ><?php echo __('All Pages', 'wp-popup-banners') ?></option>
                <option value="3" <?php if (isset($edit) && $wpb_display_on == '3') { ?> selected="selected" <?php } ?> ><?php echo __('Specific Pages', 'wp-popup-banners') ?></option>
            </select>
        </div>
    </div>

    <div  class="wpb-page-list" <?php if ((isset($edit) && ($wpb_display_on != '3')) || !isset($edit)) { ?> style="display:none"<?php } ?> >
        <?php
        $args = array(
            'sort_order' => 'asc',
            'sort_column' => 'post_title',
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        ?>

        <div class="wpb-input-field-wrap">
            <label>
                <?php echo __('Select Pages', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
                <select id="wpb_select_pages" name="wpb_select_pages[]" multiple>
                    <?php
                    foreach ($pages as $key => $value) {
                        ?>

                        <option value="<?php echo $value->ID; ?>" <?php if ((isset($edit)) && !empty($wpb_page_list) && in_array($value->ID, $wpb_page_list)) { ?> selected="selected" <?php } ?> ><?php echo __($value->post_title, 'wp-popup-banners') ?></option>                       

                    <?php } ?>                    
                </select>
            </div>
        </div>                 
    </div>


</div> <!-- general settings end -->