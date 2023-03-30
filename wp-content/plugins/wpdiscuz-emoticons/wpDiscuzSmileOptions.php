<?php

class wpDiscuzSmileOptions {

    private $optionSlug = "wpdiscuz_smiles";
    public $options;
    private $themeDir;
    public $wpsmiliestrans;
    public $patern;
    public $srcUrl;
    public $packs;
    public $jsStickersMap;
    public $isEnabledStickers;
    public $tabKey = "wsm";

    public function __construct() {
        $this->addOption();
        $this->initOption();
        $this->smilesPackExists();
        $this->initSmiles();
        $this->isEnabledStickers();
        add_filter("wpdiscuz_js_options", [&$this, "addStickerPack"]);
    }

    private function initSmiles() {
        if ($this->options["theme"] == "default") {
            include_once WPDISCUZ_SM_DIR_PATH . "/emoticons/wpsmiliestrans.php";
            $this->srcUrl = plugins_url("/emoticons", __FILE__);
        } else {
            include_once $this->themeDir . "/" . $this->options["theme"] . "/wpsmiliestrans.php";
            $this->supportOldTemplate();
            $this->srcUrl = WP_CONTENT_URL . "/wpdiscuz/emoticons/" . $this->options["theme"];
        }
        if ($this->options["custom_smiles"] && is_array($this->options["custom_smiles"])) {
            $this->wpsmiliestrans = array_merge($this->wpsmiliestrans, $this->options["custom_smiles"]);
        }
        $this->initJSStickerMap();
        $this->patern = $this->initSmiliesSearch();
    }

    private function initJSStickerMap() {
        $this->jsStickersMap = [];
        $wpsmiliestrans = $this->filterSmiles($this->wpsmiliestrans);
        foreach ($wpsmiliestrans as $key => $value) {
            $file = isset($value["file"]) ? trim($value["file"]) : "";
            if ($file) {
                $this->jsStickersMap[$key] = ["name" => $key, "path" => $this->detectUrl($file)];
            }
        }
    }

    public function addStickerPack($jsOptions) {
        if ($this->jsStickersMap) {
            $jsOptions["wpdemStickers"] = $this->jsStickersMap;
        }
        return $jsOptions;
    }

    private function supportOldTemplate() {
        $isOldTemplate = false;
        if ($this->wpsmiliestrans && is_array($this->wpsmiliestrans)) {
            foreach ($this->wpsmiliestrans as $smile) {
                if ($smile && !is_array($smile)) {
                    $isOldTemplate = true;
                    break;
                }
            }
        }
        if ($isOldTemplate) {
            $tmp = [];
            foreach ($this->wpsmiliestrans as $key => $value) {
                $tmp[$key] = ["file" => $value, "title" => trim($key, ":")];
            }
            $this->wpsmiliestrans = $tmp;
        }
    }

    private function initSmiliesSearch() {
        $keys = [];
        foreach ($this->wpsmiliestrans as $k => $v) {
            $keys[] = preg_quote($k);
        }
        return "#" . implode("|", $keys) . "#m";
    }

    public function enableOrDisableSmile() {
        if (current_user_can("manage_options")) {
            $options = get_option($this->optionSlug);
            $code = ":" . trim($_POST["key"]) . ":";
            $status = trim($_POST["status"]);
            if ($status == "disable") {
                $options["desabled_smiles"][] = $code;
            } else {
                if (($k = array_search($code, $options["desabled_smiles"])) !== false) {
                    unset($options["desabled_smiles"][$k]);
                }
            }
            update_option($this->optionSlug, $options);
            wp_die("1");
        }
        wp_die("0");
    }

    public function addCustomSmile() {
        if (current_user_can("manage_options")) {
            $options = get_option($this->optionSlug);
            $title = trim($_POST["code"], " :");
            $code = ":" . $title . ":";
            $url = trim($_POST["iconurl"]);
            $options["custom_smiles"][$code] = ["file" => $url, "title" => $title];
            update_option($this->optionSlug, $options);
            $id = uniqid();
            wp_die("<div id='custom_smile_container_$id' class='custom-smile'><code>$code</code><img id='smile_$id' src='$url' alt='$title' title='$title' data-emoticon-code='" . trim($code, ":") . "' /><span id='$id' class='button delete-custom-smile'>" . __("Delete", "wpdiscuz_sm") . "</span></div>");
        }
        wp_die("0");
    }

    public function deleteCustomSmile() {
        if (current_user_can("manage_options")) {
            $options = get_option($this->optionSlug);
            $code = ":" . trim($_POST["code"]) . ":";
            unset($options["custom_smiles"][$code]);
            if (($k = array_search($code, $options["desabled_smiles"])) !== false) {
                unset($options["desabled_smiles"][$k]);
            }
            update_option($this->optionSlug, $options);
            wp_die("1");
        }
        wp_die("0");
    }

    private function smilesPackExists() {
        $this->packs = [];
        $dirs = $this->dirNames($this->themeDir);
        foreach ($dirs as $k => $v) {
            if (file_exists($this->themeDir . "/" . $v . "/wpsmiliestrans.php")) {
                $this->packs[] = $v;
            }
        }
    }

    private function dirNames($path) {
        $dirs = [];
        if (file_exists($path) && $dirs = scandir($path)) {
            foreach ($dirs as $k => $v) {
                if ($v == "." || $v == "..") {
                    unset($dirs[$k]);
                }
            }
        }
        return $dirs;
    }

    public function smileDialog() {
        $wpsmiliestrans = $this->filterSmiles($this->wpsmiliestrans);
        $html = "";
        foreach ($wpsmiliestrans as $code => $imageData) {
            $width = "";
            $height = "";
            wpdiscuzSmileUtils::smileDimensions($width, $height, $code, $this->options, "dialog");
            if ($width == $height) {
                $style = "style='width:" . $width . "px;max-width:" . $width . "px;'";
            } else {
                $style = "style='width:" . $width . "px;max-width:" . $width . "px;height:" . $height . "px;max-height:" . $height . "px;'";
            }
            $file = isset($imageData["file"]) ? trim($imageData["file"]) : "";
            if (!$file) {
                continue;
            }
            $url = $this->detectUrl($file);
            $html .= "<img src='$url' alt='$code'  class='wpdem-editor-sticker'  $style/>";
        }
        return $html;
    }

    private function filterSmiles($wpsmiliestrans) {
        if ($this->options["desabled_smiles"] && is_array($this->options["desabled_smiles"])) {
            foreach ($this->options["desabled_smiles"] as $kod) {
                unset($wpsmiliestrans[$kod]);
            }
        }
        return $wpsmiliestrans;
    }

    public function detectUrl($url) {
        if (!strstr($url, "http://") && !strstr($url, "https://")) {
            $url = $this->srcUrl . "/img/" . $url;
        }
        return $url;
    }

    private function initOption() {
        $this->options = get_option($this->optionSlug);
        if (!isset($this->options["enable_stickers"])) {
            $this->options["enable_stickers"] = 1;
        }
        if (!isset($this->options["enable_emoji"])) {
            $this->options["enable_emoji"] = 1;
        }
        if (!isset($this->options["enable_emoji_shortname"])) {
            $this->options["enable_emoji_shortname"] = 1;
        }
        $this->themeDir = WP_CONTENT_DIR . "/wpdiscuz/emoticons";
    }

    private function addOption() {
        $options = $this->getDefaultOptions();
        add_option($this->optionSlug, $options, "", "no");
    }

    public function saveOptions() {
        if ($this->tabKey === $_POST["wpd_tab"]) {
            $this->options["theme"] = isset($_POST[$this->tabKey]["theme"]) ? trim($_POST[$this->tabKey]["theme"]) : "default";
            $this->options["size"] = isset($_POST[$this->tabKey]["size"]) && ($s = intval($_POST[$this->tabKey]["size"])) ? $s : 20;
            $this->options["enable_stickers"] = isset($_POST[$this->tabKey]["enable_stickers"]) && $_POST[$this->tabKey]["enable_stickers"] ? intval($_POST[$this->tabKey]["enable_stickers"]) : 0;
            $this->options["enable_emoji"] = isset($_POST[$this->tabKey]["enable_emoji"]) && $_POST[$this->tabKey]["enable_emoji"] ? intval($_POST[$this->tabKey]["enable_emoji"]) : 0;
            $this->options["enable_emoji_shortname"] = isset($_POST[$this->tabKey]["enable_emoji_shortname"]) && $_POST[$this->tabKey]["enable_emoji_shortname"] ? intval($_POST[$this->tabKey]["enable_emoji_shortname"]) : 0;
            update_option($this->optionSlug, $this->options);
        }
    }

    public function resetOptions($tab) {
        if ($this->tabKey === $tab || $tab === "all") {
            delete_option($this->optionSlug);
            $this->addOption();
            $this->initOption();
        }
    }

    private function getDefaultOptions() {
        return [
            "theme" => "default",
            "size" => "40",
            "enable_stickers" => 1,
            "enable_emoji" => 1,
            "enable_emoji_shortname" => 1,
            "desabled_smiles" => [],
            "custom_smiles" => [],
        ];
    }

    public function replaceCustomSmiles() {
        $options = get_option($this->optionSlug);
        $customSmiles = isset($options["custom_smiles"]) ? $options["custom_smiles"] : "";
        $newCustomSmiles = [];
        if ($customSmiles) {
            foreach ($customSmiles as $key => $file) {
                $newCustomSmiles[$key] = ["file" => $file, "title" => trim($key, ":")];
            }
            $options["custom_smiles"] = $newCustomSmiles;
            update_option($this->optionSlug, $options);
        }
    }

    private function isEnabledStickers() {
        $this->isEnabledStickers = false;
        $wpdiscuz = wpDiscuz();
        if ($wpdiscuz->isWpdiscuzLoaded && $this->options["enable_stickers"] && $this->options->jsStickersMap) {
            $this->isEnabledStickers = true;
        }
    }

    public function settingsArray($settings) {
        $this->initSmiles();
        $settings["addons"][$this->tabKey] = [
            "title" => __("Emoticons", "wpdiscuz_sm"),
            "title_original" => "Emoticons",
            "icon" => "",
            "icon-height" => "",
            "file_path" => WPDISCUZ_SM_DIR_PATH . "/options-html.php",
            "values" => $this,
            "options" => [
                "enable_emoji" => [
                    "label" => __("Enable Emoji", "wpdiscuz_sm"),
                    "label_original" => "Enable Emoji",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "enable_emoji_shortname" => [
                    "label" => __("Enable Emoji Shortname", "wpdiscuz_sm"),
                    "label_original" => "Enable Emoji Shortname",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "enable_stickers" => [
                    "label" => __("Enable Stickers", "wpdiscuz_sm"),
                    "label_original" => "Enable Stickers",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "size" => [
                    "label" => __("Sticker Size", "wpdiscuz_sm"),
                    "label_original" => "Sticker Size",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "theme" => [
                    "label" => __("Sticker Packages", "wpdiscuz_sm"),
                    "label_original" => "Sticker Packages",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
                "enableDisableStickers" => [
                    "label" => __("Sticker enable/disable", "wpdiscuz_sm"),
                    "label_original" => "Sticker enable/disable",
                    "description" => __("Click on sticker to disable. It may take 1-2 seconds to become grey/inactive.", "wpdiscuz_sm"),
                    "description_original" => "Click on sticker to disable. It may take 1-2 seconds to become grey/inactive.",
                    "docurl" => "#"
                ],
                "customSmiles" => [
                    "label" => __("Custom stickers", "wpdiscuz_sm"),
                    "label_original" => "Custom stickers",
                    "description" => "",
                    "description_original" => "",
                    "docurl" => "#"
                ],
            ],
        ];
        return $settings;
    }

}
