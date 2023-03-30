<?php
if (!defined("ABSPATH")) {
    exit();
}

$commentIds = [];
$isKeysRegenerated = get_option(self::OPTION_REGENERATE_KEYS, 0);
if (!$isKeysRegenerated) {
    $args = [
        "orderby" => "comment_ID",
        "order" => "asc",
        "fields" => "ids",
        "meta_query" => [
            "relation" => "OR",
            ["key" => self::METAKEY_IMAGE],
            ["key" => self::METAKEY_VIDEO],
            ["key" => self::METAKEY_FILE],
        ]
    ];
    $commentIds = get_comments($args);
}
?>

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuAllowedPostTypes">
    <div class="wpd-opt-name">
        <label for="wmuAllowedPostTypes"><?php echo $setting["options"]["wmuAllowedPostTypes"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuAllowedPostTypes"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <?php
        $postTypes = get_post_types();
        foreach ($postTypes as $postType) {
            if (post_type_supports($postType, "comments")) {
                ?>
                <div class="wpd-mublock-inline" style="width:32%;">
                    <input type="checkbox" <?php checked(in_array($postType, $this->wmuAllowedPostTypes)); ?> value="<?php echo $postType; ?>" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuAllowedPostTypes][]" id="mu<?php echo $postType; ?>" style="margin:0px; vertical-align: middle;" />
                    <label for="mu<?php echo $postType; ?>" style="white-space:nowrap; font-size:13px;"><?php echo $postType; ?></label>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <div class="wpd-opt-doc">
        <a href="<?php echo esc_url($setting["options"]["wmuAllowedPostTypes"]["docurl"]) ?>" title="<?php _e("Read the documentation", "wpdiscuz") ?>" target="_blank"><i class="far fa-question-circle"></i></a>
    </div>
</div>
<!-- Option end -->


<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuAllowedRoles">
    <div class="wpd-opt-name">
        <label for="wmuAllowedRoles"><?php echo $setting["options"]["wmuAllowedRoles"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuAllowedRoles"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <?php
        $blogRoles = get_editable_roles();
        foreach ($blogRoles as $role => $info) {
            ?>
            <div class="wpd-mublock-inline" style="width: 45%;">
                <input type="checkbox" <?php checked(in_array($role, $this->wmuAllowedRoles)); ?> value="<?php echo $role; ?>" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuAllowedRoles][]" id="mu<?php echo $role; ?>" style="margin:0px; vertical-align: middle;" />
                <label for="mu<?php echo $role; ?>" style=""><?php echo $info["name"]; ?></label>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="wpd-opt-doc">
        <a href="<?php echo esc_url($setting["options"]["wmuAllowedRoles"]["docurl"]) ?>" title="<?php _e("Read the documentation", "wpdiscuz") ?>" target="_blank"><i class="far fa-question-circle"></i></a>
    </div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuImagesMaxWidth">
    <div class="wpd-opt-name">
        <label for="wmuImagesMaxWidth"><?php echo $setting["options"]["wmuImagesMaxWidth"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuImagesMaxWidth"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="number" value="<?php echo $this->wmuImagesMaxWidth; ?>" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuImagesMaxWidth]" id="wmuImagesMaxWidth" style="width:100px;" />
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuImagesMaxHeight">
    <div class="wpd-opt-name">
        <label for="wmuImagesMaxHeight"><?php echo $setting["options"]["wmuImagesMaxHeight"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuImagesMaxHeight"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="number" value="<?php echo $this->wmuImagesMaxHeight; ?>" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuImagesMaxHeight]" id="wmuImagesMaxHeight" style="width:100px;" />
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuIsHtml5Video">
    <div class="wpd-opt-name">
        <label for="wmuIsHtml5Video"><?php echo $setting["options"]["wmuIsHtml5Video"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuIsHtml5Video"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="checkbox" <?php checked($this->wmuIsHtml5Video == 1); ?> value="1" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuIsHtml5Video]" id="wmuIsHtml5Video" />
        <img src="<?php echo plugins_url(WMU_DIR_NAME . "/assets/img/options/html5-video-player.png") ?>" style="vertical-align:middle; height:70px;" title="Player Screenshot" />
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuIsHtml5Audio">
    <div class="wpd-opt-name">
        <label for="wmuIsHtml5Audio"><?php echo $setting["options"]["wmuIsHtml5Audio"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuIsHtml5Audio"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="checkbox" <?php checked($this->wmuIsHtml5Audio == 1); ?> value="1" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuIsHtml5Audio]" id="wmuIsHtml5Audio" />
        <img src="<?php echo plugins_url(WMU_DIR_NAME . "/assets/img/options/html5-audio-player.png"); ?>" style="vertical-align:middle;" title="Player Screenshot" />
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuMaxFileCount">
    <div class="wpd-opt-name">
        <label for="wmuMaxFileCount"><?php echo $setting["options"]["wmuMaxFileCount"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuMaxFileCount"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="number" value="<?php echo $this->wmuMaxFileCount; ?>" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuMaxFileCount]" id="wmuMaxFileCount" style="width:100px;" />
        <p style="padding-top:0px;">
            <?php
            $serverMFUText = $this->wmuMaxFileUploads == "" ? __("unlimit", "wpdiscuz-media-uploader") : $this->wmuMaxFileUploads;
            ?>
            <span><?php echo __("Server 'max_file_uploads' is ", "wpdiscuz-media-uploader") . $serverMFUText; ?></span>
        </p>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuVideoSizes">
    <div class="wpd-opt-name">
        <label for="wmuVideoSizes"><?php echo $setting["options"]["wmuVideoSizes"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuVideoSizes"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <span for="wmuVideoWidth"><input type="number" value="<?php echo $this->wmuVideoWidth; ?>" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuVideoWidth]" id="wmuVideoWidth" style="width:70px;"> <?php _e("width (px)", "wpdiscuz-media-uploader"); ?> </span>
        <span for="wmuVideoHeight">&nbsp; <input type="number" value="<?php echo $this->wmuVideoHeight; ?>" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuVideoHeight]" id="wmuVideoHeight" style="width:70px;"> <?php _e("height (px)", "wpdiscuz-media-uploader"); ?></span>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuImageSizes">
    <div class="wpd-opt-name">
        <label for="wmuImageSizes"><?php echo $setting["options"]["wmuImageSizes"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuImageSizes"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <span for="wmuImageWidth"><input type="text" value="<?php echo $this->wmuImageWidth; ?>" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuImageWidth]" id="wmuImageWidth" class="wmu-number wmu-image-dimension wmu-image-width" style="width:70px;"> <?php _e("width (px)", "wpdiscuz-media-uploader"); ?> </span>
        <span for="wmuImageHeight">&nbsp; <input type="text" value="<?php echo $this->wmuImageHeight; ?>" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuImageHeight]" id="wmuImageHeight" class="wmu-number wmu-image-dimension wmu-image-height" style="width:70px;"> <?php _e("height (px)", "wpdiscuz-media-uploader"); ?></span>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuLazyLoadImages">
    <div class="wpd-opt-name">
        <label for="wmuLazyLoadImages"><?php echo $setting["options"]["wmuLazyLoadImages"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuLazyLoadImages"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($this->wmuLazyLoadImages == 1); ?> value="1" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuLazyLoadImages]" id="wmuLazyLoadImages" />
            <label for="wmuLazyLoadImages"></label>
        </div>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuAttachToPost">
    <div class="wpd-opt-name">
        <label for="wmuAttachToPost"><?php echo $setting["options"]["wmuAttachToPost"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuAttachToPost"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($this->wmuAttachToPost == 1); ?> value="1" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuAttachToPost]" id="wmuAttachToPost" />
            <label for="wmuAttachToPost"></label>
        </div>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuEnableCommentsFiltering">
    <div class="wpd-opt-name">
        <label for="wmuEnableCommentsFiltering"><?php echo $setting["options"]["wmuEnableCommentsFiltering"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuEnableCommentsFiltering"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($this->wmuEnableCommentsFiltering == 1); ?> value="1" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuEnableCommentsFiltering]" id="wmuEnableCommentsFiltering" />
            <label for="wmuEnableCommentsFiltering"></label>
        </div>
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuEnableMediaTab">
    <div class="wpd-opt-name">
        <label for="wmuEnableMediaTab"><?php echo $setting["options"]["wmuEnableMediaTab"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuEnableMediaTab"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($this->wmuEnableMediaTab == 1); ?> value="1" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuEnableMediaTab]" id="wmuEnableMediaTab" />
            <label for="wmuEnableMediaTab"></label>
        </div>
    </div>    
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuIsCompressImages">
    <div class="wpd-opt-name">
        <label for="wmuIsCompressImages"><?php echo $setting["options"]["wmuIsCompressImages"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuIsCompressImages"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($this->wmuIsCompressImages == 1); ?> value="1" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuIsCompressImages]" id="wmuIsCompressImages" />
            <label for="wmuIsCompressImages"></label>
        </div>
    </div>    
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="wmuCompressionQuality">
    <div class="wpd-opt-name">
        <label for="wmuCompressionQuality"><?php echo $setting["options"]["wmuCompressionQuality"]["label"]; ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["wmuCompressionQuality"]["description"]; ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="number" min="1" max="99" value="<?php echo $this->wmuCompressionQuality; ?>" name="<?php echo WpdiscuzCore::TAB_CONTENT; ?>[wmuCompressionQuality]" id="wmuCompressionQuality" style="width:70px;" />
    </div>
    <div class="wpd-opt-doc"></div>
</div>
<!-- Option end -->

<?php if ($commentIds) { ?>
    <!-- Option start -->
    <div class="wpd-opt-row" data-wpd-opt="wmuRegenerateMetakeys">
        <div class="wpd-opt-name">
            <label for="wmuRegenerateMetakeys"><?php echo $setting["options"]["wmuRegenerateMetakeys"]["label"] ?></label>
            <p class="wpd-desc"><?php echo $setting["options"]["wmuRegenerateMetakeys"]["description"] ?></p>
        </div>
        <div class="wpd-opt-input">
            <button type="button" id="wmu-regenerate-metakeys" class="button button-secondary"><?php _e("Regenerate attachments", "wpdiscuz-media-uploader"); ?></button>
            <span class="wmu-regenerate-percent-wrap wmu-hide">
                <i class="fas fa-spinner fa-pulse"></i>
                <span class="wmu-regenerate-percent">1%</span>
            </span>
            <input id="wmuCommentsCount" type="hidden" value="<?php echo count($commentIds); ?>" />
        </div>
        <div class="wpd-opt-doc"></div>
    </div>
    <!-- Option end -->
<?php } ?>