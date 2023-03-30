<?php

class ReCaptchaOptions {

    private $optionSlug = "wpdiscuz_recaptcha_v3";
    private $siteKey;
    private $secretKey;
    private $score;
    private $useV3;
    private $version;
    private $showBadge;

    public function __construct() {
        $this->initOptions();
    }

    public function initOptions() {
        $options = get_option($this->optionSlug);
        $this->version = "3.0";
        $this->siteKey = isset($options["site_key"]) ? $options["site_key"] : "";
        $this->secretKey = isset($options["secret_key"]) ? $options["secret_key"] : "";
        $this->score = isset($options["score"]) ? $options["score"] : 0.5;
        $this->useV3 = isset($options["useV3"]) ? $options["useV3"] : 0;
        $this->showBadge = isset($options["showBadge"]) ? $options["showBadge"] : 1;
    }

    public function addFilters() {
        if ($this->useV3 && $this->siteKey && $this->secretKey) {
            add_filter("wpdiscuz_recaptcha_version", function() {
                return $this->version;
            });
            add_filter("wpdiscuz_recaptcha_url", function() {
                return "https://www.google.com/recaptcha/api.js?render=" . $this->siteKey;
            });
            add_filter("wpdiscuz_recaptcha_site_key", function() {
                return $this->siteKey;
            });
            add_filter("wpdiscuz_recaptcha_secret", function() {
                return $this->secretKey;
            });
            add_filter("wpdiscuz_recaptcha_score", function() {
                return $this->score;
            });
        }
    }

    public function saveOptions() {
        if (WpdiscuzCore::TAB_RECAPTCHA === $_POST["wpd_tab"]) {
            $options = [];
            $options["site_key"] = isset($_POST[WpdiscuzCore::TAB_RECAPTCHA]["v3_sitekey"]) ? trim($_POST[WpdiscuzCore::TAB_RECAPTCHA]["v3_sitekey"]) : "";
            $options["secret_key"] = isset($_POST[WpdiscuzCore::TAB_RECAPTCHA]["v3_secretkey"]) ? trim($_POST[WpdiscuzCore::TAB_RECAPTCHA]["v3_secretkey"]) : "";
            $options["score"] = isset($_POST[WpdiscuzCore::TAB_RECAPTCHA]["v3_score"]) ? $_POST[WpdiscuzCore::TAB_RECAPTCHA]["v3_score"] : 0.5;
            $options["useV3"] = isset($_POST[WpdiscuzCore::TAB_RECAPTCHA]["useV3"]) ? 1 : 0;
            $options["showBadge"] = isset($_POST[WpdiscuzCore::TAB_RECAPTCHA]["v3_showBadge"]) ? 1 : 0;
            if ($options["useV3"] && (!$options["site_key"] || !$options["secret_key"])) {
                $wpdiscuz = wpDiscuz();
                $wpdiscuz->options->recaptcha["showForGuests"] = 0;
                $wpdiscuz->options->recaptcha["showForUsers"] = 0;
                $wpdiscuz->options->recaptcha["isShowOnSubscribeForm"] = 0;
            }
            update_option($this->optionSlug, $options);
        }
    }

    public function resetOptions($tab) {
        if (WpdiscuzCore::TAB_RECAPTCHA === $tab || $tab === "all") {
            $options = [
                "useV3" => 0,
                "site_key" => "",
                "secret_key" => "",
                "score" => 0.5,
                "showBadge" => 1,
            ];
            update_option($this->optionSlug, $options);
        }
    }

    public function optionsPageHtml($tab, $setting) {
        if ($tab === WpdiscuzCore::TAB_RECAPTCHA) {
            $this->initOptions();
            ?>
            <div class="wpd-subtitle">
                <?php _e("reCAPTCHA v3", "wpdiscuz_recaptcha"); ?>
            </div>
            <!-- Option start -->
            <div class="wpd-opt-row" data-wpd-opt="useV3">
                <div class="wpd-opt-name">
                    <label for="useV3"><?php echo $setting["options"]["useV3"]["label"] ?></label>
                    <p class="wpd-desc"><?php echo $setting["options"]["useV3"]["description"] ?></p>
                </div>
                <div class="wpd-opt-input">
                    <div class="wpd-switcher">
                        <input type="checkbox" <?php checked($this->useV3 == 1) ?> value="1" name="<?php echo WpdiscuzCore::TAB_RECAPTCHA; ?>[useV3]" id="useV3">
                        <label for="useV3"></label>
                    </div>
                </div>
                <div class="wpd-opt-doc"></div>
            </div>
            <!-- Option end -->
            <!-- Option start -->
            <div class="wpd-opt-row" data-wpd-opt="v3_sitekey">
                <div class="wpd-opt-name">
                    <label for="v3_sitekey"><?php echo $setting["options"]["v3_sitekey"]["label"] ?></label>
                    <p class="wpd-desc"><?php echo $setting["options"]["v3_sitekey"]["description"] ?></p>
                </div>
                <div class="wpd-opt-input">
                    <input type="text" name="<?php echo WpdiscuzCore::TAB_RECAPTCHA; ?>[v3_sitekey]" placeholder="reCAPTCHA V3 Site Key" id="v3_sitekey" value="<?php echo $this->siteKey; ?>" style="margin:1px;padding:3px 5px; width:90%;"/>
                </div>
                <div class="wpd-opt-doc"></div>
            </div>
            <!-- Option end -->
            <!-- Option start -->
            <div class="wpd-opt-row" data-wpd-opt="v3_secretkey">
                <div class="wpd-opt-name">
                    <label for="v3_secretkey"><?php echo $setting["options"]["v3_secretkey"]["label"] ?></label>
                    <p class="wpd-desc"><?php echo $setting["options"]["v3_secretkey"]["description"] ?></p>
                </div>
                <div class="wpd-opt-input">
                    <input type="text" name="<?php echo WpdiscuzCore::TAB_RECAPTCHA; ?>[v3_secretkey]" placeholder="reCAPTCHA V3 Site Key" id="v3_secretkey" value="<?php echo $this->secretKey; ?>" style="margin:1px;padding:3px 5px; width:90%;"/>
                </div>
                <div class="wpd-opt-doc"></div>
            </div>
            <!-- Option end -->
            <!-- Option start -->
            <div class="wpd-opt-row" data-wpd-opt="v3_score">
                <div class="wpd-opt-name">
                    <label><?php echo $setting["options"]["v3_score"]["label"] ?></label>
                    <p class="wpd-desc"><?php echo $setting["options"]["v3_score"]["description"] ?></p>
                </div>
                <div class="wpd-opt-input">
                    <div class="wpd-switch-field">
                        <input type="radio" value="0.5" <?php checked($this->score == "0.5"); ?> name="<?php echo WpdiscuzCore::TAB_RECAPTCHA; ?>[v3_score]" id="wpdiscuz_recaptcha_score_middle" />
                        <label for="wpdiscuz_recaptcha_score_middle" style="min-width:60px;"><?php _e("Middle", "wpdiscuz_recaptcha"); ?></label>
                        <input type="radio" value="0.9" <?php checked($this->score == "0.9"); ?> name="<?php echo WpdiscuzCore::TAB_RECAPTCHA; ?>[v3_score]" id="wpdiscuz_recaptcha_score_high" />
                        <label for="wpdiscuz_recaptcha_score_high" style="min-width:60px;"><?php _e("High", "wpdiscuz_recaptcha"); ?></label>
                    </div>
                </div>
                <div class="wpd-opt-doc"></div>
            </div>
            <!-- Option end -->
            <!-- Option start -->
            <div class="wpd-opt-row" data-wpd-opt="v3_showBadge">
                <div class="wpd-opt-name">
                    <label for="v3_showBadge"><?php echo $setting["options"]["v3_showBadge"]["label"] ?></label>
                    <p class="wpd-desc"><?php echo $setting["options"]["v3_showBadge"]["description"] ?></p>
                </div>
                <div class="wpd-opt-input">
                    <div class="wpd-switcher">
                        <input type="checkbox" <?php checked($this->showBadge == 1) ?> value="1" name="<?php echo WpdiscuzCore::TAB_RECAPTCHA; ?>[v3_showBadge]" id="v3_showBadge">
                        <label for="v3_showBadge"></label>
                    </div>
                </div>
                <div class="wpd-opt-doc"></div>
            </div>
            <!-- Option end -->
            <?php
        }
    }

    public function customStyleScript() {
        if (!$this->showBadge) {
            ?>
            .grecaptcha-badge{
            visibility: hidden; 
            }
            <?php
        }
    }

    public function settingsArray($settings) {
        if ($this->useV3) {
            $settings["core"][WpdiscuzCore::TAB_RECAPTCHA]["status"] = $this->siteKey && $this->secretKey ? "ok" : "note";
        }
        $settings["core"][WpdiscuzCore::TAB_RECAPTCHA]["options"]["useV3"] = [
            "label" => __("Use version 3", "wpdiscuz_recaptcha"),
            "label_original" => "Use version 3",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_RECAPTCHA]["options"]["v3_sitekey"] = [
            "label" => __("Site Key", "wpdiscuz_recaptcha"),
            "label_original" => "Site Key",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_RECAPTCHA]["options"]["v3_secretkey"] = [
            "label" => __("Secret Key", "wpdiscuz_recaptcha"),
            "label_original" => "Secret Key",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_RECAPTCHA]["options"]["v3_score"] = [
            "label" => __("Score", "wpdiscuz_recaptcha"),
            "label_original" => "Score",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        $settings["core"][WpdiscuzCore::TAB_RECAPTCHA]["options"]["v3_showBadge"] = [
            "label" => __("Show reCaptcha badge", "wpdiscuz_recaptcha"),
            "label_original" => "Show reCaptcha badge",
            "description" => "",
            "description_original" => "",
            "docurl" => "#"
        ];
        return $settings;
    }

}
