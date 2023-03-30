<div class="wpb-display-settings wpb-input-field" style="display:none">
    <div class="wpb-input-field-wrap">
        <label>
            <?php echo __('Width', 'wp-popup-banners') ?>
        </label>
        <div class="wpb-input-field">
            <input type="text" id="wpb_width" placeholder="eg. 100px" name="wpb_width"  value="<?php echo (isset($edit)) ? $width : ''; ?>">
        </div>
    </div>

    <div class="wpb-input-field-wrap">
        <label>
            <?php echo __('Height', 'wp-popup-banners') ?>
        </label>
        <div class="wpb-input-field">
            <input type="text" id="wpb_height" placeholder="eg. 100px" name="wpb_height"  value="<?php echo (isset($edit)) ? $height : ''; ?>">
        </div>
    </div>
    <div class="wpb-input-field-wrap">
        <label><?php _e('Popup Display Type', 'wp-popup-banners'); ?></label>
        <div class="wpb-input-field">
            <select name="popup_display_type">
                <option value="fixed" <?php if(isset($popup_display_type)){ selected($popup_display_type, 'fixed'); } ?>><?php _e('Fixed', 'wp-popup-banners'); ?></option>
                <option value="absolute" <?php  if(isset($popup_display_type)){ selected($popup_display_type, 'absolute'); }?>><?php _e('Absoulute', 'wp-popup-banners'); ?></option>
            </select> 
        </div>
    </div>
    <div class="wpb-input-field-wrap">
        <label>
            <?php echo __('Shape', 'wp-popup-banners') ?>
        </label>
        <div class="wpb-input-field">
            <select id="wpb_radious" name="wpb_radious">
                <option value="rounded_corner" <?php if (isset($edit) && $border_radius == 'rounded_corner') { ?> selected="selected" <?php } ?> ><?php echo __('Rounded Corner', 'wp-popup-banners') ?></option>
                <option value="sharp_corner" <?php if (isset($edit) && $border_radius == 'sharp_corner') { ?> selected="selected" <?php } ?>><?php echo __('Sharp Corner', 'wp-popup-banners') ?></option>
            </select>
        </div>
    </div>
    <div class="wpb-input-field-wrap">
        <label>
            <?php echo __('Overlay', 'wp-popup-banners') ?>
        </label>
        <div class="wpb-input-field">
            <select id="wpb_overlay_type" name="wpb_overlay_type">
                <option value="0.8"  <?php if (isset($edit) && $overlay_type == '0.8') { ?> selected="selected" <?php } ?>><?php echo __('Dark Overlay', 'wp-popup-banners') ?></option>
                <option value="0.3"  <?php if (isset($edit) && $overlay_type == '0.3') { ?> selected="selected" <?php } ?>><?php echo __('Light Overlay', 'wp-popup-banners') ?></option>
                <option value="0"  <?php if (isset($edit) && $overlay_type == '0') { ?> selected="selected" <?php } ?>><?php echo __('No Overlay', 'wp-popup-banners') ?></option>
            </select>
        </div>
    </div>
    <div class="wpb-input-field-wrap">
        <label>
            <?php echo __('Overlay Color', 'wp-popup-banners') ?>
        </label>
        <div class="wpb-input-field">
            <input type="text" id="wpb_overlay_color" name="wpb_overlay_color"  value="<?php echo (isset($edit)) ? $overlay_color : 'black'; ?>">
            <p class="description" ><?php echo __('Color of the overlay', 'wp-popup-banners') ?></p>
        </div>
    </div>

    <div class="wpb-input-field-wrap">
        <label>
            <?php echo __('Close Button', 'wp-popup-banners') ?> 
        </label>
        <div class="wpb-input-field">
            <select name="wpb_close">
                <option value="yes" <?php
                if (isset($edit)) {
                    selected($display_close, 'yes');
                }
                ?>><?php _e('Enable', 'wp-popup-banner'); ?></option>
                <option value="no" <?php
                if (isset($edit)) {
                    selected($display_close, 'no');
                }
                ?>><?php _e('Disable', 'wp-popup-banner'); ?></option>
            </select>


        </div>
    </div>



    <!-- new added start-->
    <div class="wpb-input-field-wrap">
        <label>
            <?php echo __('Background Type', 'wp-popup-banners') ?> 
        </label>
        <div class="wpb-input-field">
            <label><input type="radio" name="background_type"  value="color" class="wpb-background-type" <?php  if(isset($popup_background_type)){ checked($popup_background_type, 'color'); } ?>><?php echo __('Color', 'wp-popup-banners') ?></label>
            <label><input type="radio" name="background_type" value="image" class="wpb-background-type" <?php if(isset($popup_background_type)){ checked($popup_background_type, 'image'); } ?>><?php echo __('Image', 'wp-popup-banners') ?></label>

        </div>
    </div>
    <div data-background-ref="color" <?php if(isset($popup_background_type)) {
        if($popup_background_type != 'color' ) { ?>style="display:none;"<?php } } ?>>
        <div class="wpb-option-field" >
            <label>
                <?php echo __('Background Color', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
                <input type="text" id="wpb_background_color" name="wpb_background_color"  data-alpha="true" value="<?php echo (isset($edit)) ? $background_color : '#fff'; ?>">
                <p class="description" ><?php echo __('Background color for the popup', 'wp-popup-banners') ?></p>
            </div>
        </div>
    </div>
    <div data-background-ref="image" <?php if(isset($popup_background_type)) { if($popup_background_type != 'image') { ?>style="display:none;"<?php } }?>>
        <div class="wpb-option-field">
            <label>
                <?php echo __('Background Image', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
                <input name = "wpb_upload_image2" id="wpb_upload_image2" type="text"  value="<?php echo (isset($edit)) ? $text_image_path : ''; ?>" name="wpb_upload_image2" />
                <input class="upload_image_button button button-primary" type="button" value="Upload Image" />
                <p class="description" ><?php echo __('We can upload the image or we can also enter the external image link on the input field. eg: https://upload.wikimedia.org/wikipedia/commons/3/36/Hopetoun_falls.jpg', 'wp-popup-banners') ?></p>
            </div>
        </div>
        <div class="wpb-input-field-wrap">
            <label>
                <?php echo __('Background Repeat Type', 'wp-popup-banners') ?>
            </label>
            <div class="wpb-input-field">
                <select id="wpb_background_repeat" name="wpb_background_repeat">
                    <option value="no-repeat" <?php if (isset($edit) && $wpb_background_repeat == 'no-repeat') { ?> selected="selected" <?php } ?> ><?php echo __('No repeat', 'wp-popup-banners') ?></option>
                    <option value="repeat" <?php if (isset($edit) && $wpb_background_repeat == 'repeat') { ?> selected="selected" <?php } ?> ><?php echo __('Repeat', 'wp-popup-banners') ?></option>
                    <option value="repeat-x" <?php if (isset($edit) && $wpb_background_repeat == 'repeat-x') { ?> selected="selected" <?php } ?> ><?php echo __('Repeat Horizontal', 'wp-popup-banners') ?></option>
                    <option value="repeat-y" <?php if (isset($edit) && $wpb_background_repeat == 'repeat-y') { ?> selected="selected" <?php } ?> ><?php echo __('Repeat Vertical', 'wp-popup-banners') ?></option>
                </select>
                <p class="description" ><?php echo __('Show the popup only once or every time the page loads', 'wp-popup-banners') ?></p>
            </div>
        </div>             
    </div>
    <div class="wpb-input-field-wrap">
        <label><?php echo __('Border', 'wp-popup-banners') ?></label>
        <div class="wpb-input-field">
            <select id="wpb_border" name="wpb_border">
                <option value="no" <?php if (isset($edit) && $wpb_border == 'no') { ?> selected="selected" <?php } ?> ><?php echo __('No', 'wp-popup-banners') ?></option>
                <option value="yes" <?php if (isset($edit) && $wpb_border == 'yes') { ?> selected="selected" <?php } ?> ><?php echo __('Yes', 'wp-popup-banners') ?></option>
            </select>
            <p class="description" ><?php echo __('Enable to get the option to customize border.', 'wp-popup-banners') ?></p>
        </div>
    </div>               
    <div  class="wpb-popup-border" <?php if ((isset($edit) && $wpb_border == 'no' || $wpb_border == '') || (!isset($edit))) { ?>style="display:none"<?php } ?>>
        <div>
            <label><?php echo __('Border Type', 'wp-popup-banners') ?></label>
            <div class="wpb-input-field">
                <select class="wpb-popup-border-type" name="wpb_border_type">
                    <option value="">
                        <?php echo __('None', 'wp-popup-banners') ?></option>
                    <option value="solid" <?php
                    if (isset($edit) && $wpb_border_type == 'solid') {
                        echo 'selected="selected"';
                    }
                    ?>>
                        <?php echo __('Solid', 'wp-popup-banners') ?></option>
                    <option value="dotted" <?php
                    if (isset($edit) && $wpb_border_type == 'dotted') {
                        echo 'selected="selected"';
                    }
                    ?>>
                        <?php echo __('Dotted', 'wp-popup-banners') ?></option>
                    <option value="dashed" <?php
                    if (isset($edit) && $wpb_border_type == 'dashed') {
                        echo 'selected="selected"';
                    }
                    ?>>
                        <?php echo __('Dashed', 'wp-popup-banners') ?></option>
                    <option value="double" <?php
                    if (isset($edit) && $wpb_border_type == 'double') {
                        echo 'selected="selected"';
                    }
                    ?>>
                        <?php echo __('Double', 'wp-popup-banners') ?></option>
                    <option value="groove" <?php
                    if (isset($edit) && $wpb_border_type == 'groove') {
                        echo 'selected="selected"';
                    }
                    ?>>
                        <?php echo __('Groove', 'wp-popup-banners') ?></option>
                    <option value="ridge" <?php
                    if (isset($edit) && $wpb_border_type == 'ridge') {
                        echo 'selected="selected"';
                    }
                    ?>>
                        <?php echo __('Ridge', 'wp-popup-banners') ?></option>
                    <option value="inset" <?php
                    if (isset($edit) && $wpb_border_type == 'inset') {
                        echo 'selected="selected"';
                    }
                    ?>>
                        <?php echo __('Inset', 'wp-popup-banners') ?></option>
                </select>
            </div>
        </div> 
        <div>
            <label><?php echo __('Border Color', 'wp-popup-banners') ?></label>
            <div class="wpb-input-field">
                <input type="text" id="wpb_border_color" name="wpb_border_color"  value="<?php echo (isset($edit)) ? $wpb_border_color : ''; ?>">
                <p class="description" ><?php echo __('Color of the overlay', 'wp-popup-banners') ?></p>
            </div>
        </div>             
        <div>
            <label><?php echo __('Border Size', 'wp-popup-banners') ?></label>
            <div class="wpb-input-field">
                <input type="text" id="wpb_border_size" placeholder="eg. 5px" name="wpb_border_size"  value="<?php echo (isset($edit)) ? $wpb_border_size : ''; ?>">
            </div>
        </div>                     
    </div>


    <div class="wpb-input-field-wrap">
        <label><?php echo __('Padding', 'wp-popup-banners') ?></label>
        <div class="wpb-input-field">
            <input type="text" id="wpb_main_padding" placeholder="eg. 15px" name="wpb_main_padding"  value="<?php echo (isset($edit)) ? $wpb_main_padding : ''; ?>">
            <p class="description"><?php _e('This will control the uniform spacing inside the banner', 'wp-popup-banners'); ?></p>
        </div>
    </div>
</div> <!-- end of display settings -->          