<?php
if (!defined("ABSPATH")) {
    exit();
}
?>
<!-- Option start -->
<div class="wpd-opt-row wpd-custom-option-group-wrapper" data-wpd-opt="wmuCustomMimeTypes">
    <div class="wpd-opt-name" style="width: 100%;">
        <div>
            <label for="wmuCustomMimeTypes"><?php echo esc_html($setting["options"]["wmuCustomMimeTypes"]["label"]) ?></label>
            <p class="wpd-desc"><?php echo $setting["options"]["wmuCustomMimeTypes"]["description"] ?></p>
        </div>
        <div class="wpd-opt-doc" style="float: right;">
            <?php $options->printDocLink($setting["options"]["wmuCustomMimeTypes"]["docurl"]) ?>
        </div>
        <div class="wpd-clear"></div>
    </div>
    <div class="wpd-opt-input">
        <?php
        $i = 0;
        foreach ($this->wmuCustomMimeTypes as $ext => $mimetypes) {
            ?>
            <div class="wpd-custom-option-group">
                <input type="text" class="wpd-custom-option-label" value="<?php echo $ext; ?>" name="<?php echo esc_attr(WpdiscuzCore::TAB_CONTENT); ?>[wmuCustomMimeTypes][<?php echo $i; ?>][ext]" placeholder="<?php esc_attr_e("extension", "wpdiscuz-media-uploader") ?>" />
                <input type="text" class="wpd-custom-option-value" value="<?php echo $mimetypes; ?>" name="<?php echo esc_attr(WpdiscuzCore::TAB_CONTENT); ?>[wmuCustomMimeTypes][<?php echo $i; ?>][mimetypes]" placeholder="<?php esc_attr_e("mime type(s) (pipe separated)", "wpdiscuz-media-uploader") ?>" />
                <span class="dashicons dashicons-trash"></span>
            </div>
            <?php
            $i++;
        }
        ?>
        <div class="wpd-custom-option-group wpd-custom-option-group-default">
            <input type="text" class="wpd-custom-option-label" value="" name="<?php echo esc_attr(WpdiscuzCore::TAB_CONTENT); ?>[wmuCustomMimeTypes][default][ext]" placeholder="<?php esc_attr_e("extension", "wpdiscuz-media-uploader") ?>" />
            <input type="text" class="wpd-custom-option-value" value="" name="<?php echo esc_attr(WpdiscuzCore::TAB_CONTENT); ?>[wmuCustomMimeTypes][default][mimetypes]" placeholder="<?php esc_attr_e("mime type(s) (pipe separated)", "wpdiscuz-media-uploader") ?>" />
            <span class="dashicons dashicons-trash"></span>
        </div>
        <div class="wpd-custom-option-group-add" style="font-size: 16px;">
            <span class="dashicons dashicons-plus" style="font-size: 26px; height: 20px;"></span>
            <span><?php esc_attr_e("Add New File Type", "wpdiscuz-media-uploader") ?></span>
        </div>
    </div>
</div>
<!-- Option end -->