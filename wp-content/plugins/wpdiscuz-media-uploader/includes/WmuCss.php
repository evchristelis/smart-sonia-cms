<?php

if (!defined("ABSPATH")) {
    exit();
}

class WmuCss {

    private $options;

    function __construct($options) {
        $this->options = $options;
        add_action("wpdiscuz_dynamic_css", [&$this, "dynamicCss"]);
    }

    /**
     * init wpdiscuz styles
     */
    public function dynamicCss($options) {
        $wpdPrimaryColor = $options->thread_styles["primaryColor"];
        $styles = "#wpcomm .wmu-active{ border-bottom:1px solid $wpdPrimaryColor;}";
        echo $styles;
    }

}
