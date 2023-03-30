<?php

class wpdiscuzSmileUtils {

    public static function smileDimensions(&$width, &$height, $code, $options, $content = "text") {
        $width = (isset($options["size"]) && ($w = absint($options["size"]))) ? $w : 40;
        if($w > 40 && $content == "dialog"){
           $width = 40; 
        }
        $height = $width;
        $dimensions = ["width" => $width, "height" => $height];
        $dimensions = apply_filters("wpdiscuz_emoji_dimensions_in_".$content, $dimensions, $code);
        $height = $dimensions["height"];
        $width = $dimensions["width"];
    }

}
