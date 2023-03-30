<?php
/*
 * Plugin Name: wpDiscuz - Emoticons
 * Description: An awesome Emoji and Stickers package. Allows to manage (add/edit/delete) emoticons.
 * Version: 7.0.12
 * Author: gVectors Team
 * Author URI: https://gvectors.com/
 * Plugin URI: https://gvectors.com/product/wpdiscuz-emoticons
 * Text Domain: wpdiscuz-sm
 * Domain Path: /languages/
 */

define("WPDISCUZ_SM_DIR_PATH", dirname(__FILE__));

include_once WPDISCUZ_SM_DIR_PATH . "/includes/gvt-api-manager.php";
include_once WPDISCUZ_SM_DIR_PATH . "/wpDiscuzSmileOptions.php";
include_once WPDISCUZ_SM_DIR_PATH . "/includes/wpdiscuzSmileUtils.php";

class wpDiscuzSmile {

    private $options;
    private $smilesSearch;
    private $pVersion;
	public $apimanager;

    public function __construct() {
        add_action("plugins_loaded", [&$this, "pluginsLoaded"], 165);
    }

    public function pluginsLoaded() {
        if (function_exists("wpDiscuz")) {
	        $this->apimanager = new GVT_API_Manager(__FILE__, "wpdiscuz_options_page", "wpdiscuz_option_page");
            $this->options = new wpDiscuzSmileOptions();
            $this->initSmilesSearch();
            $this->initPluginVersion();
            load_plugin_textdomain("wpdiscuz-sm", false, basename(dirname(__FILE__)) . "/languages/");
            add_filter("wpdiscuz_custom_field_text", [&$this, "convertSmilies"], 10);
            add_filter("wpdiscuz_custom_field_text", [&$this, "convertSmilies"], 10);
            add_filter("wpdiscuz_custom_field_textarea", "convert_smilies");
            add_filter("wpdiscuz_custom_field_text", "convert_smilies");
            add_filter("comment_text", [&$this, "convertSmilies"], 999);
            add_action("wpdiscuz_front_scripts", [&$this, "frontendScripts"]);
            add_action("wpdiscuz_dynamic_css", [&$this, "customStyleScript"]);
            add_action("wp_footer", [&$this, "stickersTemplate"]);

            //TODO change enqueue admin script to wpd admin script
            add_action("admin_enqueue_scripts", [&$this, "adminScripts"], 2385);
            add_action("wpdiscuz_save_options", [$this->options, "saveOptions"]);
            add_action("wpdiscuz_reset_options", [$this->options, "resetOptions"]);
            add_filter("wpdiscuz_settings", [$this->options, "settingsArray"], 40);
            add_action("admin_init", [&$this, "pluginNewVersion"], 1);

            add_action("wp_ajax_enable_or_disable_smile", [$this->options, "enableOrDisableSmile"]);
            add_action("wp_ajax_add_custom_smile", [$this->options, "addCustomSmile"]);
            add_action("wp_ajax_delete_custom_smile", [$this->options, "deleteCustomSmile"]);
            //emoji
            add_filter("wpdiscuz_editor_buttons", [&$this, "addEmojiButton"]);
            add_action("wpdiscuz_editor_modules", [&$this, "addEmojiModule"]);
        } else {
            add_action("admin_notices", [&$this, "smRequirements"], 1);
        }
    }

    public function addEmojiButton($buttons) {
        if ($this->options->options["enable_emoji"]) {
            $buttons[] = ["class" => "ql-emoji", "name" => "", "title" => "", "svg" => '<svg xmlns="https://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-smile"><title>Emoji</title><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>', "value" => ""];
        }
        if ($this->options->options["enable_stickers"] && $this->options->jsStickersMap) {
            $buttons[] = ["class" => "ql-wpdsticker", "name" => "sticker", "title" => "", "svg" => '<svg viewBox="0 0 24 24"><title>Stickers</title><path d="M5.5,2C3.56,2 2,3.56 2,5.5V18.5C2,20.44 3.56,22 5.5,22H16L22,16V5.5C22,3.56 20.44,2 18.5,2H5.5M5.75,4H18.25A1.75,1.75 0 0,1 20,5.75V15H18.5C16.56,15 15,16.56 15,18.5V20H5.75A1.75,1.75 0 0,1 4,18.25V5.75A1.75,1.75 0 0,1 5.75,4M14.44,6.77C14.28,6.77 14.12,6.79 13.97,6.83C13.03,7.09 12.5,8.05 12.74,9C12.79,9.15 12.86,9.3 12.95,9.44L16.18,8.56C16.18,8.39 16.16,8.22 16.12,8.05C15.91,7.3 15.22,6.77 14.44,6.77M8.17,8.5C8,8.5 7.85,8.5 7.7,8.55C6.77,8.81 6.22,9.77 6.47,10.7C6.5,10.86 6.59,11 6.68,11.16L9.91,10.28C9.91,10.11 9.89,9.94 9.85,9.78C9.64,9 8.95,8.5 8.17,8.5M16.72,11.26L7.59,13.77C8.91,15.3 11,15.94 12.95,15.41C14.9,14.87 16.36,13.25 16.72,11.26Z" /></svg>', "value" => ""];
        }
        return $buttons;
    }

    public function smRequirements() {
        if (current_user_can("manage_options")) {
            echo "<div class='error'><p>" . __("wpDiscuz - Emoticons requires wpDiscuz to be installed!", "wpdiscuz-sm") . "</p></div>";
        }
    }

    public function convertSmilies($text) {
        $output = "";
        if (!empty($this->smilesSearch)) {
            $textarr = preg_split('/(<.*>)/U', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
            $stop = count($textarr);
            $tags_to_ignore = "code|pre";
            $ignore_block_element = "";
            for ($i = 0; $i < $stop; $i++) {
                $content = $textarr[$i];
                if ("" == $ignore_block_element && preg_match('/^<(' . $tags_to_ignore . ')>/', $content, $matches)) {
                    $ignore_block_element = $matches[1];
                }
                if ("" == $ignore_block_element && strlen($content) > 0 && "<" != $content[0]) {
                    $content = preg_replace_callback($this->smilesSearch, [&$this, "translateSmiley"], $content);
                }
                if ("" != $ignore_block_element && "</" . $ignore_block_element . ">" == $content) {
                    $ignore_block_element = "";
                }
                $output .= $content;
            }
        } else {
            $output = $text;
        }
        return $output;
    }

    private function initSmilesSearch() {
        $allSmiles = $this->options->wpsmiliestrans;
        krsort($allSmiles);
        $spaces = wp_spaces_regexp();
        $this->smilesSearch = '/(?<=' . $spaces . '|^)';
        $subchar = "";
        foreach ((array) $allSmiles as $smiley => $img) {
            $firstchar = substr($smiley, 0, 1);
            $rest = substr($smiley, 1);
            if ($firstchar != $subchar) {
                if ($subchar != "") {
                    $this->smilesSearch .= ')(?=' . $spaces . '|$)';  // End previous "subpattern"
                    $this->smilesSearch .= '|(?<=' . $spaces . '|^)'; // Begin another "subpattern"
                }
                $subchar = $firstchar;
                $this->smilesSearch .= preg_quote($firstchar, '/') . '(?:';
            } else {
                $this->smilesSearch .= '|';
            }
            $this->smilesSearch .= preg_quote($rest, '/');
        }
        $this->smilesSearch .= ')(?=' . $spaces . '|$)/m';
    }

    public function translateSmiley($matches) {
        if (count($matches) == 0)
            return "";
        $smiley = trim(reset($matches));
        if (!key_exists($smiley, $this->options->wpsmiliestrans)) {
            return $smiley;
        }
        $imgData = $this->options->wpsmiliestrans[$smiley];
        $srcUrl = $this->options->detectUrl($imgData["file"]);
        $width = "";
        $height = "";
        wpdiscuzSmileUtils::smileDimensions($width, $height, $smiley, $this->options->options);
        if ($width == $height) {
            $style = "style='width:" . $width . "px;max-width:" . $width . "px;'";
        } else {
            $style = "style='width:" . $width . "px;max-width:" . $width . "px;height:" . $height . "px;max-height:" . $height . "px;'";
        }
        return sprintf("<img src='%s' alt=' %s '  class='wpdem-sticker' " . $style . " />", esc_url($srcUrl), $smiley, $width, $height);
    }

    public function frontendScripts($options) {
        $suf = $options->general["loadMinVersion"] ? ".min" : "";
        if ($options->form["richEditor"] === "both" || (!wp_is_mobile() && $options->form["richEditor"] === "desktop")) {
            $dep = $options->general["loadComboVersion"] ? "wpdiscuz-combo-js" : "wpd-editor";
            wp_register_script("wpdiscuz-smile-js", plugins_url("/assets/third-party/quill-emoji/quill-emoji$suf.js", __FILE__), [$dep], $this->pVersion, true);
            wp_enqueue_script("wpdiscuz-smile-js");
            if ($this->options->jsStickersMap) {
                wp_register_script("wpdiscuz-sticker-js", plugins_url("/assets/js/wpdiscuz-smile.js", __FILE__), [$dep], $this->pVersion, true);
                wp_enqueue_script("wpdiscuz-sticker-js");
            }
        }
        wp_register_style("wpdiscuz-smile-css", plugins_url("/assets/third-party/quill-emoji/quill-emoji$suf.css", __FILE__), null, $this->pVersion);
        wp_enqueue_style("wpdiscuz-smile-css");
    }

    public function adminScripts() {
        if (isset($_GET["page"]) && isset($_GET["wpd_tab"]) && $_GET["page"] === WpdiscuzCore::PAGE_SETTINGS && $_GET["wpd_tab"] === $this->options->tabKey) {
            wp_register_script("wpdiscuz-admin-smile-js", plugins_url("/assets/js/wpdiscuz-smile-option.js", __FILE__), ["jquery"], $this->pVersion, true);
            wp_enqueue_script("wpdiscuz-admin-smile-js");
            wp_localize_script("wpdiscuz-admin-smile-js", "wpdiscuz_smile_obj", ["wpdiscuz_smile_options" => ["fill_required" => __("Please fill code and url", "wpdiscuz_sm")]]);
            wp_register_style("wpdiscuz-admin-smile-css", plugins_url("/assets/css/wpdiscuz-admin-smile.css", __FILE__), null, $this->pVersion);
            wp_enqueue_style("wpdiscuz-admin-smile-css");
        }
    }

    public function smileHtml() {
        ?>
        <div class="wpdem-sticker-body" style=""><?php echo $this->options->smileDialog(); ?></div>
        <?php
    }

    private function initPluginVersion() {
        if (!function_exists("get_plugins")) {
            require_once ABSPATH . "wp-admin/includes/plugin.php";
        }
        $plugin_folder = get_plugins("/" . plugin_basename(dirname(__FILE__)));
        $plugin_file = basename(( __FILE__));
        $this->pVersion = $plugin_folder[$plugin_file]["Version"];
    }

    public function customStyleScript() {
        if ($this->options->jsStickersMap) {
            $stickerDialogSize = $this->options->options["size"] > 40 ? $this->options->options["size"] : $this->options->options["size"] * 2;
            ?>
            #wpdcom .wpdem-sticker-container.wpdem-sticker-open{height: auto; max-height: 200px; padding:15px; -moz-box-shadow: inset 0 0 15px #eee; -webkit-box-shadow: inset 0 0 15px #eee; box-shadow: inset 0 0 15px #eee;}
            #wpdcom .ql-editor img.wpdem-sticker{height: <?php echo $this->options->options["size"]; ?>px; max-height: <?php echo $this->options->options["size"]; ?>px; width: auto;}
            .ql-snow.ql-toolbar button.ql-wpdsticker svg{fill: #07b290; width:17px; height:17px;}
            .ql-snow.ql-toolbar button.ql-wpdsticker:hover svg{fill: #0f997d;}
            .ql-snow.ql-toolbar button.ql-emoji svg{ color: #ffa600; width:17px; height:17px;}
            .ql-snow.ql-toolbar button.ql-emoji:hover svg{color: #ff7200;}
            <?php
        }
    }

    public function stickersTemplate() {
        $wpdiscuz = wpDiscuz();
        if ($wpdiscuz->isWpdiscuzLoaded && $this->options->jsStickersMap) {
            ?>
            <div id="wpdem-sticker-contaniner-main" style="display: none;">
                <?php $this->smileHtml(); ?>
            </div>
            <?php
        }
    }

    public function pluginNewVersion() {
        $oldVersion = get_option("wpdiscuz_emoticons_version", "1.0.0");
        if (version_compare($this->pVersion, $oldVersion, ">")) {
            if ($oldVersion == "1.0.0") {
                $this->options->replaceCustomSmiles();
            }
            update_option("wpdiscuz_emoticons_version", $this->pVersion);
        }
    }

    public function addEmojiModule() {
        ?>
        "emoji-toolbar": <?php echo $this->options->options["enable_emoji"] ? "true" : "false"; ?>,
        "emoji-shortname": <?php echo $this->options->options["enable_emoji_shortname"] ? "true" : "false"; ?>,
        <?php
    }

}

$wpDiscuzSmile = new wpDiscuzSmile();

