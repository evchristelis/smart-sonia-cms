<?php

/*
 * Plugin Name: wpDiscuz - Google reCAPTCHA
 * Description: Adds No CAPTCHA on all comment forms. Stops spam and bot comments with Google reCAPTCHA
 * Version: 7.0.3
 * Author: gVectors Team
 * Plugin URI: https://gvectors.com/product/wpdiscuz-recaptcha/
 * Author URI: https://gvectors.com/product/wpdiscuz-recaptcha/
 * Text Domain: wpdiscuz_recaptcha
 * Domain Path: /languages/
 */
define("WRC_DIR_PATH", dirname(__FILE__));

require_once WRC_DIR_PATH . "/includes/gvt-api-manager.php";
require_once WRC_DIR_PATH . "/ReCaptchaOptions.php";

class wpDiscuzReCaptcha {

    private $options;
	public $apimanager;

    public function __construct() {
	    $this->apimanager = new GVT_API_Manager(__FILE__, "wpdiscuz_options_page", "wpdiscuz_option_page");
        add_action("plugins_loaded", [&$this, "pluginsLoaded"], 1589);
    }

    public function pluginsLoaded() {
        if (function_exists("wpDiscuz")) {
            $this->options = new ReCaptchaOptions();
            $this->options->addFilters();
            add_action("wpdiscuz_settings_tab_after", [&$this->options, "optionsPageHtml"], 2, 2);
            add_action("wpdiscuz_save_options", [&$this->options, "saveOptions"]);
            add_action("wpdiscuz_reset_options", [&$this->options, "resetOptions"]);
            add_action("wpdiscuz_dynamic_css", [&$this->options, "customStyleScript"]);
            add_filter("wpdiscuz_settings", [&$this->options, "settingsArray"]);
            load_plugin_textdomain("wpdiscuz_recaptcha", false, dirname(plugin_basename(__FILE__)) . "/languages/");
        }
    }

}

$wpDiscuzReCaptcha = new wpDiscuzReCaptcha();

