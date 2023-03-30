<?php

/*
 * Plugin Name: wpDiscuz - Media Uploader
 * Description: Better comment system. Wordpress post comments and discussion plugin. Allows your visitors discuss, vote for comments and share.
 * Version: 7.0.10
 * Author: gVectors Team
 * Author URI: https://www.gvectors.com/
 * Plugin URI: https://www.gvectors.com/wpdiscuz/
 * Text Domain: wpdiscuz-media-uploader
 * Domain Path: /languages/
 */
if (!defined("ABSPATH")) {
    exit();
}

define("WMU_DIR_PATH", dirname(__FILE__));
define("WMU_DIR_NAME", basename(WMU_DIR_PATH));

require_once "includes/gvt-api-manager.php";
include_once "includes/WmuConstants.php";
include_once "includes/WmuDBManager.php";
include_once "includes/WmuHelper.php";
include_once "includes/WmuHelperAjax.php";
include_once "includes/WmuCss.php";
include_once "options/WmuOptions.php";

class WpdiscuzMediaUploader implements WmuConstants {

    private $options;
    private $css;
    private $helper;
    private $helperAjax;
    private $dbManager;
    private $versionDB;
    private $versionPluginFile;
    private $currentUser;
    private $requestUri;
	public $apimanager;

    public function __construct() {
        add_action("init", [&$this, "uploaderRequirments"]);
    }

    public function uploaderRequirments() {
        if (function_exists("wpDiscuz")) {
	        $this->apimanager = new GVT_API_Manager(__FILE__, WpdiscuzCore::PAGE_SETTINGS, "wpdiscuz_option_page");
            add_option(self::OPTION_VERSION, "1.0.0");
            $this->versionDB = get_option(self::OPTION_VERSION, "1.0.0");
            $this->dbManager = new WmuDBManager();
            $this->options = new WmuOptions();
            $this->css = new WmuCss($this->options);
            $this->helper = new WmuHelper($this->dbManager, $this->options);
            $this->helperAjax = new WmuHelperAjax($this->dbManager, $this->options, $this->helper);
            $this->requestUri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";

            load_plugin_textdomain("wpdiscuz-media-uploader", false, WMU_DIR_NAME . "/languages/");
            add_action("wpdiscuz_check_version", [&$this, "newVersion"]);


            add_filter("wpdiscuz_mu_allowed_posttypes", function() {
                return $this->options->wmuAllowedPostTypes;
            });
            add_filter("wpdiscuz_mu_allowed_roles", function() {
                return $this->options->wmuAllowedRoles;
            });
            add_filter("wpdiscuz_mu_isactive", "__return_true");
            add_filter("wpdiscuz_mu_import_dco_attachments", "__return_true");

            add_filter("wpdiscuz_js_options", [&$this, "getFrontendJsArgs"]);
            add_filter("wpdiscuz_comment_list_args", [&$this, "commentListArgs"]);

            add_filter("wpdiscuz_mu_get_attachments", [&$this, "getAttachments"], 10, 4);

            add_action("admin_enqueue_scripts", [&$this, "addBackendFiles"]);
            add_action("wpdiscuz_front_scripts", [&$this, "addFrontendFiles"]);
            add_action("admin_notices", [&$this, "wmuRegenerateKeysNotice"], 1);
        } else {
            add_action("admin_notices", [&$this, "wmuRequirementsNotice"], 1);
        }
    }

    public function wmuRegenerateKeysNotice() {
        if (current_user_can("manage_options") && !get_option(self::OPTION_REGENERATE_KEYS, 0)) {
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
            if ($commentIds) {
                $adminUrl = admin_url("admin.php?page=" . WpdiscuzCore::PAGE_SETTINGS . '&wpd_tab=' . WpdiscuzCore::TAB_CONTENT . '#wpdOpt-wmuRegenerateMetakeys');
                $url = "<a class='button button-primary' href='$adminUrl'>" . __("Regenerate attachments", "wpdiscuz-media-uploader") . "</a>";
                $string = sprintf(__("Please navigate to settings page and click %s button.", "wpdiscuz-media-uploader"), $url);
                echo "<div class='notice notice-warning'><p>" . $string . "</p></div>";
            }
        }
    }

    public function wmuRequirementsNotice() {
        if (current_user_can("manage_options")) {
            echo "<div class='error'><p>" . __("wpDiscuz - Media Uploader requires wpDiscuz to be installed!", "wpdiscuz-media-uploader") . "</p></div>";
        }
    }

    public function newVersion() {
        $pluginData = get_plugin_data(__FILE__);
        $this->versionPluginFile = empty($pluginData["Version"]) ? "1.0.0" : $pluginData["Version"];
        $this->versionDB = get_option(self::OPTION_VERSION, $this->versionPluginFile);
        if (version_compare($this->versionPluginFile, $this->versionDB, ">")) {
            $options = get_option(self::OPTION_MAIN);
            $this->addNewOptions($options);
            update_option(self::OPTION_VERSION, $this->versionPluginFile);
            if (!get_option(self::OPTION_ENABLE_ALL_MIMES)) {
                $wpd = wpDiscuz();
                $wpd->options->content["wmuMimeTypes"] = $this->options->getDefaultMimeTypes();
                $wpd->options->updateOptions();
                update_option(self::OPTION_ENABLE_ALL_MIMES, true, "no");
            }
        }
    }

    /**
     * merge old and new options
     */
    private function addNewOptions($options) {
        $this->options->initOptions($options);
        $newOptions = $this->options->toArray();
        update_option(self::OPTION_MAIN, $newOptions);
    }

    public function getFrontendJsArgs($args = []) {
        $args["wmuVersion"] = $this->versionDB;
        $args["wmuMaxFileCount"] = $this->options->wmuMaxFileCount;
        $args["msgEmptyFile"] = __("File is empty. Please upload something more substantial. This error could also be caused by uploads being disabled in your php.ini or by post_max_size being defined as smaller than upload_max_filesize in php.ini.", "wpdiscuz-media-uploader");
        $args["msgUploadingNotAllowed"] = __("Sorry, uploading not allowed for this post", "wpdiscuz-media-uploader");
        $args["msgPermissionDenied"] = __("You do not have sufficient permissions to perform this action", "wpdiscuz-media-uploader");
        $args["wmuPhraseImageSizesError"] = isset($this->options->wmuPhrases["wmuPhraseImageSizesError"]) ? __($this->options->wmuPhrases["wmuPhraseImageSizesError"], "wpdiscuz-media-uploader") : __("Size restriction, try uploading an image with smaller width/height", "wpdiscuz-media-uploader");
        $args["wmuLazyLoadImages"] = $this->options->wmuLazyLoadImages;        
        $args["wmuIconVideo"] = plugins_url(WMU_DIR_NAME . "/assets/img/file-icons/video-audio-type.png");
        $args["wmuIconFile"] = plugins_url(WMU_DIR_NAME . "/assets/img/file-icons/file-type.png");
        $args["wmuKeyVideos"] = self::KEY_VIDEOS;
        $args["wmuKeyFiles"] = self::KEY_FILES;
        return $args;
    }

    public function commentListArgs($args) {
        if (empty($args["current_user"])) {
            $wpdiscuzHelper = wpDiscuz()->helper;
            $this->currentUser = $wpdiscuzHelper::getCurrentUser();
        } else {
            $this->currentUser = $args["current_user"];
        }
        $this->helperAjax->setCurrentUser($this->currentUser);
        return $args;
    }

    public function getAttachments($content, $attachIds, $currentUser, $key) {
        if ($key == self::KEY_VIDEOS) {
            $videoHtml = $this->helper->getAttachedVideos($attachIds, $currentUser);
            $content .= "<div class='wmu-attached-videos'>$videoHtml</div>";
        } else if ($key == self::KEY_FILES) {
            $filesHtml = $this->helper->getAttachedFiles($attachIds, $currentUser);
            $content .= "<div class='wmu-attached-files'>$filesHtml</div>";
        }
        return $content;
    }

    public function addFrontendFiles($wpdOptions) {
        if ($wpdOptions->content["wmuIsEnabled"]) {
            $minified = $wpdOptions->general["loadMinVersion"] ? ".min" : "";

            wp_register_style("wmu-frontend-css", plugins_url(WMU_DIR_NAME . "/assets/css/wmu-frontend$minified.css"), null, $this->versionDB);
            wp_enqueue_style("wmu-frontend-css");

            if (is_rtl()) {
                wp_register_style("wmu-frontend-rtl-css", plugins_url(WMU_DIR_NAME . "/assets/css/wmu-frontend-rtl$minified.css"), null, $this->versionDB);
                wp_enqueue_style("wmu-frontend-rtl-css");
            }

            $args = $this->getFrontendJsArgs();
            wp_register_script("wmu-frontend-js", plugins_url(WMU_DIR_NAME . "/assets/js/wmu-frontend$minified.js"), ["jquery"], $this->versionDB, true);
            wp_enqueue_script("wmu-frontend-js");
            wp_localize_script("wmu-frontend-js", "wmuJsObj", $args);
        }
    }

    public function addBackendFiles() {
        $args = [];
        $args["wmuLazyLoadImages"] = $this->options->wmuLazyLoadImages;
        $args["wmuWpdiscuzPageSettings"] = WpdiscuzCore::PAGE_SETTINGS;
        $args["wmuTabSlug"] =  WpdiscuzCore::TAB_CONTENT;
        $args["wmuRemoveCustomMimeMessage"] = __("Are you sure you want to delete custom file type?", "wpdiscuz-media-uploader");

        wp_register_style("wmu-backend-css", plugins_url(WMU_DIR_NAME . "/assets/css/wmu-backend.css"), null, $this->versionDB);
        wp_enqueue_style("wmu-backend-css");
        wp_register_script("wmu-backend-js", plugins_url(WMU_DIR_NAME . "/assets/js/wmu-backend.js"), ["jquery"]);
        wp_localize_script("wmu-backend-js", "wmuJsObj", $args);
        wp_enqueue_script("wmu-backend-js");
    }

}

$wpdiscuzMU = new WpdiscuzMediaUploader();
