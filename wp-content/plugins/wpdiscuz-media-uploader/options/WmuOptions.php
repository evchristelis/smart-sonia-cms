<?php

if (!defined("ABSPATH")) {
    exit();
}

class WmuOptions implements WmuConstants {

    /**
     * Post types supporting upload
     */
    public $wmuAllowedPostTypes;

    /**
     * User roles allowed to upload
     */
    public $wmuAllowedRoles;

    /**
     * Images max width
     */
    public $wmuImagesMaxWidth;

    /**
     * Images max height
     */
    public $wmuImagesMaxHeight;

    /**
     *  Server simultaneously upload files count
     */
    public $wmuMaxFileUploads;

    /**
     * Type - checkbox
     * Description - enable/disable html5 video player
     */
    public $wmuIsHtml5Video;

    /**
     * Type - checkbox
     * Description - enable/disable html5 audio player
     */
    public $wmuIsHtml5Audio;

    /**
     * Type - input
     * Description - define how many files can user upload at same time
     */
    public $wmuMaxFileCount;

    /**
     * Type - input
     * Description - define the width of video player
     */
    public $wmuVideoWidth;

    /**
     * Type - input
     * Description - define the height of video player
     */
    public $wmuVideoHeight;

    /**
     * Type - input
     * Description - define the width of image in comment
     */
    public $wmuImageWidth;

    /**
     * Type - input
     * Description - define the height of image in comment
     */
    public $wmuImageHeight;

    /**
     * Type - array of phrases
     * WMU Phrases
     */
    public $wmuPhrases;

    /**
     * Type - checkbox
     * Description - enable/disable uploaded images lazy loading
     */
    public $wmuLazyLoadImages;

    /**
     * Type - checkbox
     * Description - enable/disable files auto attach to current post
     */
    public $wmuAttachToPost;

    /**
     * Type - checkbox
     * Description - enable/disable My Media tab
     */
    public $wmuEnableCommentsFiltering;

    /**
     * Type - checkbox
     * Description - enable/disable My Media tab
     */
    public $wmuEnableMediaTab;

    /**
     * Type - checkbox
     * Description - enable/disable images compression
     */
    public $wmuIsCompressImages;

    /**
     * Type - input
     * Description - images compression quality
     */
    public $wmuCompressionQuality;

    /**
     * Type - array of inputs
     * Description - custom mime types
     */
    public $wmuCustomMimeTypes;

    public function __construct() {
        $this->wmuMaxFileUploads = trim(ini_get("max_file_uploads"));
        $this->addOptions();
        $this->initOptions(get_option(self::OPTION_MAIN));


        add_filter("wpdiscuz_mu_file_types", [&$this, "getDefaultMimeTypes"]);

        add_filter("wpdiscuz_mu_image_sizes", function($args) {
            $args["width"] = $this->wmuImageWidth;
            $args["height"] = $this->wmuImageHeight;
            return $args;
        });

        add_action("wpdiscuz_settings_tab_after", [&$this, "addSettings"], 1, 2);
        add_action("wpdiscuz_mu_phrases", [&$this, "addPhrases"]);
        add_action("wpdiscuz_save_options", [&$this, "saveOptions"]);
        add_action("wpdiscuz_reset_options", [&$this, "resetOptions"], 13);
        add_filter("wpdiscuz_settings", [&$this, "settingsArray"]);
        add_action("wpdiscuz_mu_custom_mime_types", [&$this, "customMimeTypes"], 10, 2);

        add_filter("wpdiscuz_mu_mime_types", function ($mimetypes) {
            foreach ($this->wmuCustomMimeTypes as $ext => $mimes) {
                $mimetypes[$ext] = !empty($mimetypes[$ext]) ? ($mimetypes[$ext] . "|" . $mimes) : $mimes;
            }
            return $mimetypes;
        });
    }

    public function getDefaultMimeTypes() {
        $newMimeTypes = [];
        foreach (get_allowed_mime_types() as $exts => $type) {
            foreach (explode('|', $exts) as $k => $ext) {
                $newMimeTypes[$ext] = $type;
            }
        }
        $newMimeTypes["m4a"] = !empty($newMimeTypes["m4a"]) ? ($newMimeTypes["m4a"] . "|audio/x-m4a") : "audio/x-m4a";
        $newMimeTypes["aac"] = !empty($newMimeTypes["aac"]) ? ($newMimeTypes["aac"] . "|audio/x-hx-aac-adts") : "audio/x-hx-aac-adts";
        $newMimeTypes["wav"] = !empty($newMimeTypes["wav"]) ? ($newMimeTypes["wav"] . "|audio/x-wav") : "audio/x-wav";
        return $newMimeTypes;
    }

    public function addSettings($tab, $setting) {
        if (WpdiscuzCore::TAB_CONTENT === $tab) {
            include_once WMU_DIR_PATH . "/options/settings.php";
        }
    }

    public function addPhrases() {
        ob_start();
        include_once "phrases.php";
        $html = ob_get_clean();
        echo $html;
    }

    public function saveOptions() {
        if (WpdiscuzCore::TAB_CONTENT === $_POST["wpd_tab"]) {
            $this->wmuAllowedPostTypes = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuAllowedPostTypes"]) ? $_POST[WpdiscuzCore::TAB_CONTENT]["wmuAllowedPostTypes"] : [];
            $this->wmuAllowedRoles = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuAllowedRoles"]) ? $_POST[WpdiscuzCore::TAB_CONTENT]["wmuAllowedRoles"] : [];
            $this->wmuImagesMaxWidth = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuImagesMaxWidth"]) ? $_POST[WpdiscuzCore::TAB_CONTENT]["wmuImagesMaxWidth"] : "";
            $this->wmuImagesMaxHeight = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuImagesMaxHeight"]) ? $_POST[WpdiscuzCore::TAB_CONTENT]["wmuImagesMaxHeight"] : "";
            $this->wmuIsHtml5Video = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuIsHtml5Video"]) ? $_POST[WpdiscuzCore::TAB_CONTENT]["wmuIsHtml5Video"] : 0;
            $this->wmuIsHtml5Audio = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuIsHtml5Audio"]) ? $_POST[WpdiscuzCore::TAB_CONTENT]["wmuIsHtml5Audio"] : 0;
            $this->wmuMaxFileCount = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuMaxFileCount"]) && ($v = intval($_POST[WpdiscuzCore::TAB_CONTENT]["wmuMaxFileCount"])) ? $v : $this->wmuMaxFileUploads;
            $this->wmuVideoWidth = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuVideoWidth"]) && ($v = absint($_POST[WpdiscuzCore::TAB_CONTENT]["wmuVideoWidth"])) ? $v : 320;
            $this->wmuVideoHeight = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuVideoHeight"]) && ($v = absint($_POST[WpdiscuzCore::TAB_CONTENT]["wmuVideoHeight"])) ? $v : 200;
            $this->wmuImageWidth = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuImageWidth"]) && ($v = trim($_POST[WpdiscuzCore::TAB_CONTENT]["wmuImageWidth"])) && ($v == "auto" || ($v = absint($v))) ? $v : 90;
            $this->wmuImageHeight = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuImageHeight"]) && ($v = trim($_POST[WpdiscuzCore::TAB_CONTENT]["wmuImageHeight"])) && ($v == "auto" || ($v = absint($v))) ? $v : 90;
            $this->wmuPhrases = isset($_POST[WpdiscuzCore::TAB_CONTENT]['wmuPhrases']) && is_array($_POST[WpdiscuzCore::TAB_CONTENT]['wmuPhrases']) ? $_POST[WpdiscuzCore::TAB_CONTENT]['wmuPhrases'] : $this->getDefaultPhrases();
            $this->wmuLazyLoadImages = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuLazyLoadImages"]) && ($v = intval($_POST[WpdiscuzCore::TAB_CONTENT]["wmuLazyLoadImages"])) ? $v : 0;
            $this->wmuAttachToPost = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuAttachToPost"]) && ($v = intval($_POST[WpdiscuzCore::TAB_CONTENT]["wmuAttachToPost"])) ? $v : 0;
            $this->wmuEnableCommentsFiltering = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuEnableCommentsFiltering"]) && ($v = intval($_POST[WpdiscuzCore::TAB_CONTENT]["wmuEnableCommentsFiltering"])) ? $v : 0;
            $this->wmuEnableMediaTab = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuEnableMediaTab"]) && ($v = intval($_POST[WpdiscuzCore::TAB_CONTENT]["wmuEnableMediaTab"])) ? $v : 0;
            $this->wmuIsCompressImages = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuIsCompressImages"]) && ($v = intval($_POST[WpdiscuzCore::TAB_CONTENT]["wmuIsCompressImages"])) ? $v : 0;
            $this->wmuCompressionQuality = isset($_POST[WpdiscuzCore::TAB_CONTENT]["wmuCompressionQuality"]) && ($v = intval($_POST[WpdiscuzCore::TAB_CONTENT]["wmuCompressionQuality"])) && ($v >= 1 && $v <= 99) ? $v : 75;
            $this->wmuCustomMimeTypes = [];
            if (!empty($_POST[WpdiscuzCore::TAB_CONTENT]["wmuCustomMimeTypes"]) && is_array($_POST[WpdiscuzCore::TAB_CONTENT]["wmuCustomMimeTypes"])) {
                foreach ($_POST[WpdiscuzCore::TAB_CONTENT]["wmuCustomMimeTypes"] as $k => $val) {
                    if (!empty($val["ext"]) && !empty($val["mimetypes"])) {
                        $this->wmuCustomMimeTypes[$val["ext"]] = $val["mimetypes"];
                    }
                }
            }
            $this->updateOptions();
        }
    }

    public function addOptions() {
        $options = [
            "wmuAllowedPostTypes" => $this->getDefaultPostTypes(),
            "wmuAllowedRoles" => $this->getDefaultRoles(),
            "wmuImagesMaxWidth" => "",
            "wmuImagesMaxHeight" => "",
            "wmuIsHtml5Video" => 1,
            "wmuIsHtml5Audio" => 1,
            "wmuMaxFileCount" => $this->wmuMaxFileUploads,
            "wmuVideoWidth" => 320,
            "wmuVideoHeight" => 200,
            "wmuImageWidth" => 90,
            "wmuImageHeight" => 90,
            'wmuPhrases' => $this->getDefaultPhrases(),
            "wmuLazyLoadImages" => 1,
            "wmuAttachToPost" => 0,
            "wmuEnableCommentsFiltering" => 1,
            "wmuEnableMediaTab" => 1,
            "wmuIsCompressImages" => 0,
            "wmuCompressionQuality" => 75,
            "wmuCustomMimeTypes" => [],
        ];
        add_option(self::OPTION_MAIN, $options, '', 'no');
    }

    public function initOptions($options) {
        $o = maybe_unserialize($options);
        $this->wmuAllowedPostTypes = isset($o["wmuAllowedPostTypes"]) ? $o["wmuAllowedPostTypes"] : [];
        $this->wmuAllowedRoles = isset($o["wmuAllowedRoles"]) ? $o["wmuAllowedRoles"] : [];
        $this->wmuImagesMaxWidth = isset($o["wmuImagesMaxWidth"]) ? $o["wmuImagesMaxWidth"] : "";
        $this->wmuImagesMaxHeight = isset($o["wmuImagesMaxHeight"]) ? $o["wmuImagesMaxHeight"] : "";
        $this->wmuIsHtml5Video = isset($o["wmuIsHtml5Video"]) ? $o["wmuIsHtml5Video"] : 0;
        $this->wmuIsHtml5Audio = isset($o["wmuIsHtml5Audio"]) ? $o["wmuIsHtml5Audio"] : 0;
        $this->wmuMaxFileCount = isset($o["wmuMaxFileCount"]) ? $o["wmuMaxFileCount"] : 3;
        $this->wmuVideoWidth = isset($o["wmuVideoWidth"]) ? $o["wmuVideoWidth"] : 320;
        $this->wmuVideoHeight = isset($o["wmuVideoHeight"]) ? $o["wmuVideoHeight"] : 200;
        $this->wmuImageWidth = isset($o["wmuImageWidth"]) ? $o["wmuImageWidth"] : 90;
        $this->wmuImageHeight = isset($o["wmuImageHeight"]) ? $o["wmuImageHeight"] : 90;
        $this->wmuPhrases = isset($o['wmuPhrases']) ? $o['wmuPhrases'] : $this->getDefaultPhrases();
        $this->wmuLazyLoadImages = isset($o["wmuLazyLoadImages"]) ? $o["wmuLazyLoadImages"] : 0;
        $this->wmuAttachToPost = isset($o["wmuAttachToPost"]) ? $o["wmuAttachToPost"] : 0;
        $this->wmuEnableCommentsFiltering = isset($o["wmuEnableCommentsFiltering"]) ? $o["wmuEnableCommentsFiltering"] : 1;
        $this->wmuEnableMediaTab = isset($o["wmuEnableMediaTab"]) ? $o["wmuEnableMediaTab"] : 1;
        $this->wmuIsCompressImages = isset($o["wmuIsCompressImages"]) ? $o["wmuIsCompressImages"] : 0;
        $this->wmuCompressionQuality = isset($o["wmuCompressionQuality"]) ? $o["wmuCompressionQuality"] : 75;
        $this->wmuCustomMimeTypes = isset($o["wmuCustomMimeTypes"]) ? $o["wmuCustomMimeTypes"] : [];
    }

    public function toArray() {
        $options = [
            "wmuAllowedPostTypes" => $this->wmuAllowedPostTypes,
            "wmuAllowedRoles" => $this->wmuAllowedRoles,
            "wmuImagesMaxWidth" => $this->wmuImagesMaxWidth,
            "wmuImagesMaxHeight" => $this->wmuImagesMaxHeight,
            "wmuIsHtml5Video" => $this->wmuIsHtml5Video,
            "wmuIsHtml5Audio" => $this->wmuIsHtml5Audio,
            "wmuMaxFileCount" => $this->wmuMaxFileCount,
            "wmuVideoWidth" => $this->wmuVideoWidth,
            "wmuVideoHeight" => $this->wmuVideoHeight,
            "wmuImageWidth" => $this->wmuImageWidth,
            "wmuImageHeight" => $this->wmuImageHeight,
            'wmuPhrases' => $this->wmuPhrases,
            "wmuLazyLoadImages" => $this->wmuLazyLoadImages,
            "wmuAttachToPost" => $this->wmuAttachToPost,
            "wmuEnableCommentsFiltering" => $this->wmuEnableCommentsFiltering,
            "wmuEnableMediaTab" => $this->wmuEnableMediaTab,
            "wmuIsCompressImages" => $this->wmuIsCompressImages,
            "wmuCompressionQuality" => $this->wmuCompressionQuality,
            "wmuCustomMimeTypes" => $this->wmuCustomMimeTypes,
        ];
        return $options;
    }

    public function updateOptions() {
        update_option(self::OPTION_MAIN, $this->toArray());
    }

    private function getDefaultPhrases() {
        $phrases = [
            "wmuPhraseImageSizesError" => __("Size restriction, try uploading an image with smaller width/height", "wpdiscuz-media-uploader"),
            "wmuPhraseMediaTabTitle" => __("Media", "wpdiscuz-media-uploader"),
        ];
        return $phrases;
    }

    private function getDefaultPostTypes() {
        return ["post", "page", "attachment"];
    }

    private function getDefaultRoles() {
        return ["administrator", "editor", "author", "contributor", "subscriber"];
    }

    public function resetOptions($tab) {
        if (WpdiscuzCore::TAB_CONTENT === $tab || $tab === "all") {
            delete_option(self::OPTION_MAIN);
            $this->addOptions();
            $this->initOptions(get_option(self::OPTION_MAIN));
        }
    }

    public function customMimeTypes($setting, $options) {
        include_once 'settings-custom_mime_types.php';
    }

    public function settingsArray($settings) {
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuAllowedPostTypes"] = [
            "label" => __("Allow Media Uploading for Post Types", "wpdiscuz"),
            "label_original" => "Allow Media Uploading for Post Types",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuAllowedRoles"] = [
            "label" => __("Allow Media Uploading for User Roles", "wpdiscuz"),
            "label_original" => "Allow Media Uploading for User Roles",
            "description" => __("By default comment media uploading button is available for default WordPress user roles. if you have custom user roles please manage this option for those too.", "wpdiscuz"),
            "description_original" => "By default comment media uploading button is available for default WordPress user roles. if you have custom user roles please manage this option for those too.",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuImagesMaxWidth"] = [
            "label" => __("Images max width for uploading", "wpdiscuz-media-uploader"),
            "label_original" => "Images max width for uploading",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuImagesMaxHeight"] = [
            "label" => __("Images max height for uploading", "wpdiscuz-media-uploader"),
            "label_original" => "Images max height for uploading",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuIsHtml5Video"] = [
            "label" => __("Enable HTML5 video player", "wpdiscuz-media-uploader"),
            "label_original" => "Enable HTML5 video player",
            "description" => __("Uploaded video files will be added as a download link under comment text. However if file format is .mp4, .webm or .ogg, it'll convert link to HTML5 video player.", "wpdiscuz-media-uploader"),
            "description_original" => "Uploaded video files will be added as a download link under comment text. However if file format is .mp4, .webm or .ogg, it'll convert link to HTML5 video player.",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuIsHtml5Audio"] = [
            "label" => __("Enable HTML5 audio player", "wpdiscuz-media-uploader"),
            "label_original" => "Enable HTML5 audio player",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuMaxFileCount"] = [
            "label" => __("Max number of files", "wpdiscuz-media-uploader"),
            "label_original" => "Max number of files",
            "description" => __("You can not set this value more than 'max_file_uploads'. If you want to increase server parameters please contact to your hosting service support.", "wpdiscuz-media-uploader"),
            "description_original" => "You can not set this value more than 'max_file_uploads'. If you want to increase server parameters please contact to your hosting service support.",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuVideoSizes"] = [
            "label" => __("Video player sizes", "wpdiscuz-media-uploader"),
            "label_original" => "Video player sizes",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuImageSizes"] = [
            "label" => __("Image sizes on comment", "wpdiscuz-media-uploader"),
            "label_original" => "Image sizes on comment",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuLazyLoadImages"] = [
            "label" => __("Enable images lazy loading?", "wpdiscuz-media-uploader"),
            "label_original" => "Enable images lazy loading?",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuAttachToPost"] = [
            "label" => __("Attach files to current post?", "wpdiscuz-media-uploader"),
            "label_original" => "Attach files to current post?",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuEnableCommentsFiltering"] = [
            "label" => __("Enable comments filtering", "wpdiscuz-media-uploader"),
            "label_original" => "Enable comments filtering",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuEnableMediaTab"] = [
            "label" => __("Show 'My Content and Settings' 'Media' tab", "wpdiscuz-media-uploader"),
            "label_original" => "Show 'My Content and Settings' 'Media' tab",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuIsCompressImages"] = [
            "label" => __("Compress images before upload", "wpdiscuz-media-uploader"),
            "label_original" => "Compress images before upload",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuCompressionQuality"] = [
            "label" => __("Compression quality", "wpdiscuz-media-uploader"),
            "label_original" => "Compression quality",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuRegenerateMetakeys"] = [
            "label" => __("Regenerate attachments", "wpdiscuz-media-uploader"),
            "label_original" => "Regenerate attachments",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_CONTENT]["options"]["wmuCustomMimeTypes"] = [
            "label" => __("Custom File Types", "wpdiscuz-media-uploader"),
            "label_original" => "Custom File Types",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        return $settings;
    }

}
