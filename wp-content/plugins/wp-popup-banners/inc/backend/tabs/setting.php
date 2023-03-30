<div  class="wpb_settings wpb_tabs_content postbox">
    <form id="wpb_setting_form" action="<?php echo admin_url() . 'admin-post.php' ?>" method='post'>
        <input type="hidden" name="action" value="wpb_save_options" />
        <?php wp_nonce_field('wpb_nonce_save_settings', 'wpb_add_nonce_save_settings'); ?>
        <div class="wpb-field-setting">
            <label>                
                <?php echo __('Active', 'wp-popup-banners'); ?>
            </label>
            <div class="wpb-setting-input-field">                
                <input type="checkbox" name="wbp_enable" <?php if ($wpb_default_settings['check_enable'] == 1) { ?>checked="checked" <?php } ?>>
                <p class="description" ><?php echo __('We can activate or disable the popup on page load', 'wp-popup-banners') ?></p>
            </div>

        </div>


        <div class="wpb-field-setting">
            <label>  
                <?php echo __('Default Popup Banner', 'wp-popup-banners'); ?>
            </label>

            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . "popup_banners";

            $rows = $wpdb->get_results("SELECT * FROM $table_name");

            $wpb_default_settings = get_option('wpb_default_settings');
            ?>

            <div class="wpb-setting-input-field">
                <select id="" name="wpb_default_id">
                    <option value=""><?php echo __('Select Popup Banner', 'wp-popup-banners') ?></option>
                    <?php
                    foreach ($rows as $key => $value) {
                        $parameteres = (unserialize($value->option_value));
                        ?>
                        <option value="<?php echo $value->id; ?>" <?php if ($wpb_default_settings['default_popup_id'] == $value->id) { ?>selected="selected" <?php } ?> ><?php echo isset($parameteres['popup_parameters']['title']) && !empty($parameteres['popup_parameters']['title']) ? esc_attr($parameteres['popup_parameters']['title']) : __('Untitled Popup', 'wp-popup-banners'); ?></option>
                        <?php
                    }
                    ?>                        
                </select>
                <p class="description"><?php echo __('Select the popup popup to display on page load', 'wp-popup-banners') ?></p>
            </div>
        </div>

        <div class="wpb-field-setting">                            
            <div class="wpb-field-setting-title">
                &nbsp;
            </div>
            <div class="wpb-setting-input-field">
                <input type="submit" class="button-primary wpb-bottom-submit" name='wpb_save_settings' value="<?php
                       if (isset($edit)) {
                           _e('Update', 'wp-popup-banners');
                       } else {
                           _e('Save', 'wp-popup-banners');
                       }
                       ?>" />
            </div>                            
        </div>
    </form>

</div>
