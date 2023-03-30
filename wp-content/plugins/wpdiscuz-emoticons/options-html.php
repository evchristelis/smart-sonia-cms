<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="enable_emoji">
    <div class="wpd-opt-name">
        <label for="enable_emoji"><?php echo $setting["options"]["enable_emoji"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["enable_emoji"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->options["enable_emoji"] == 1) ?> value="1" name="<?php echo $setting["values"]->tabKey; ?>[enable_emoji]" id="enable_emoji">
            <label for="enable_emoji"></label>
        </div>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="enable_emoji_shortname">
    <div class="wpd-opt-name">
        <label for="enable_emoji_shortname"><?php echo $setting["options"]["enable_emoji_shortname"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["enable_emoji_shortname"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->options["enable_emoji_shortname"] == 1) ?> value="1" name="<?php echo $setting["values"]->tabKey; ?>[enable_emoji_shortname]" id="enable_emoji_shortname">
            <label for="enable_emoji_shortname"></label>
        </div>
    </div>
</div>
<!-- Option end -->
<div class="wpd-subtitle">
    <?php _e("Sticker", "wpdiscuz_sm") ?>
</div>
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="enable_stickers">
    <div class="wpd-opt-name">
        <label for="enable_stickers"><?php echo $setting["options"]["enable_stickers"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["enable_stickers"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <div class="wpd-switcher">
            <input type="checkbox" <?php checked($setting["values"]->options["enable_stickers"] == 1) ?> value="1" name="<?php echo $setting["values"]->tabKey; ?>[enable_stickers]" id="enable_stickers">
            <label for="enable_stickers"></label>
        </div>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="size">
    <div class="wpd-opt-name">
        <label for="size"><?php echo $setting["options"]["size"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["size"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <input type="number" id="size" min="0" name="<?php echo $setting["values"]->tabKey; ?>[size]" value="<?php echo $setting["values"]->options["size"]; ?>" style="width: 80px;"/> px
    </div>
</div>
<!-- Option end -->
<?php
if ($setting["values"]->packs && is_array($setting["values"]->packs)) {
    ?>
    <!-- Option start -->
    <div class="wpd-opt-row" data-wpd-opt="theme">
        <div class="wpd-opt-name">
            <label><?php echo $setting["options"]["theme"]["label"] ?></label>
            <p class="wpd-desc"><?php echo $setting["options"]["theme"]["description"] ?></p>
        </div>
        <div class="wpd-opt-input">
            <div class="wpd-radio">
                <input type="radio" value="default" <?php checked($setting["values"]->options["theme"] == "default"); ?> name="<?php echo $setting["values"]->tabKey; ?>[theme]" id="themeDefault" class="theme"/>
                <label for="themeDefault" class="wpd-radio-circle"></label>
                <label for="themeDefault"><img width="20px" height="20px" alt="Default" src="<?php echo plugins_url("/emoticons/icon.png", __FILE__); ?>"></label>
            </div>
            <?php
            foreach ($setting["values"]->packs as $pack) {
                ?>
                <div class="wpd-radio">
                    <input type="radio" value="<?php echo $pack; ?>" <?php checked($setting["values"]->options["theme"] == $pack); ?> name="<?php echo $setting["values"]->tabKey; ?>[theme]" id="theme<?php echo $pack; ?>" class="theme"/>
                    <label for="theme<?php echo $pack; ?>" class="wpd-radio-circle"></label>
                    <label for="theme<?php echo $pack; ?>"><img width="20px" height="20px" alt="<?php echo $pack; ?>" src="<?php echo WP_CONTENT_URL . "/wpdiscuz/emoticons/" . $pack . "/icon.png"; ?>"></label>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <!-- Option end -->
    <?php
} else {
    ?>
    <!-- Option start -->
    <div class="wpd-opt-row" data-wpd-opt="enableDisableStickers">
        <div class="wpd-opt-name">
            <label><?php _e("Emoticon Packages", "wpdiscuz_sm"); ?></label>
            <div class="wpd-desc">
                wpDiscuz Emoticons Addon allows you to add new sticker packages and switch to any package you want.<br />
                To create a new Smiles package, please follow this instruction: <span class="show-smile-instruction">Show more ... </span>
                <div id="custom-smile-instruction" class="custom-smile-instruction" style="display: none;">
                    <strong>1.</strong>&nbsp; Create "wpdiscuz" folder in WordPress <span class="wpddir">/wp-content/</span> directory<br />
                    <strong>2.</strong>&nbsp; Create "emoticons" folder in WordPress <span class="wpddir">/wp-content/wpdiscuz/</span> directory<br />
                    <strong>3.</strong>&nbsp; Choose a unique name for your new package and create a folder using this name in <span class="wpddir">/wp-content/wpdiscuz/emoticons/</span> directory. <br />
                    For example "mysmiles", the end directory will be <span class="wpddir">/wp-content/wpdiscuz/emoticons/mysmiles/</span><br />
                    <strong>4.</strong>&nbsp; Copy all files from <span class="wpddir">/wp-content/plugins/wpdiscuz-emoticons/emoticons/</span> directory to <span class="wpddir">/wp-content/wpdiscuz/emoticons/mysmiles/</span><br />
                    <strong>5.</strong>&nbsp; Change the new package demonstration icon <span class="wpddir">mysmiles/icon.png</span>, but do not rename it.<br />
                    <strong>6.</strong>&nbsp; Delete all images in <span class="wpddir">mysmiles<strong>/</strong>img/</span> folder and upload your new emoticons images.<br />
                    <strong>7.</strong>&nbsp; Open <span class="wpddir">mysmiles<strong>/</strong>wpsmiliestrans.php</span> file and change new image names for according emoticons' code.<br />
                    <strong>8.</strong>&nbsp; Then come back to this page and find the emoticons package switcher.<span class="hide-smile-instruction">Show less</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Option end -->
    <?php
}
?>
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="enableDisableStickers">
    <div class="wpd-opt-name">
        <label><?php echo $setting["options"]["enableDisableStickers"]["label"] ?></label>
        <p class="wpd-desc"><?php echo $setting["options"]["enableDisableStickers"]["description"] ?></p>
    </div>
    <div class="wpd-opt-input">
        <?php
        foreach ($setting["values"]->wpsmiliestrans as $code => $imageData) {
            $class = in_array($code, $setting["values"]->options["desabled_smiles"]) ? "disabled" : "";
            $eCode = trim($code, ":");
            $title = trim($imageData["title"]);
            $url = $setting["values"]->detectUrl($imageData["file"]);
            echo "<img src='$url' alt='$title' title='$title'  data-emoticon-code='$eCode' class='wpdiscuz-option-smile $class' />";
        }
        ?>
    </div>
</div>
<!-- Option end -->
<!-- Option start -->
<div class="wpd-opt-row" data-wpd-opt="customSmiles" style="border-bottom: none;">
    <div class="wpd-opt-input" style="width: calc(100% - 40px);">
        <h2 style="margin-bottom: 0px;font-size: 15px; color: #555;"><?php echo $setting["options"]["customSmiles"]["label"] ?></h2>
        <p class="wpd-desc"><?php echo $setting["options"]["customSmiles"]["description"] ?></p>
        <hr />
        <div id="custom-smiles">
            <?php
            foreach ($setting["values"]->options["custom_smiles"] as $code => $imageData) {
                $id = uniqid();
                ?>
                <div id="custom_smile_container_<?php echo $id; ?>" class="custom-smile">
                    <code><?php echo $code; ?></code>
                    <img align="absmiddle" id="smile_<?php echo $id; ?>" src="<?php echo $imageData["file"]; ?>" alt='<?php echo $imageData["title"]; ?>' title='<?php echo $imageData["title"]; ?>' data-emoticon-code="<?php echo trim($code, ":"); ?>" />
                    <span id="<?php echo $id; ?>" class="button delete-custom-smile"><?php _e("Delete", "wpdiscuz_sm"); ?></span>
                </div>
                <?php
            }
            ?>
            <div class="add-custom-smile">
                <table width="100%">
                    <tr>
                        <td style="padding-top:5px; width:7%;"><label for="custom-smile-code"><?php _e("Code", "wpdiscuz_sm"); ?></label></td>
                        <td class="sprefix">:</td>
                        <td style="width:10%;padding:0px 1px;"><input type="text" name="custom-smile-code" valu="" id="custom-smile-code" placeholder="shock" style="margin:1px;padding:3px 5px; width:100%;"/></td>
                        <td class="sprefix">:</td>
                        <td style="padding:5px 10px 0px 20px; width:12%;"><label for="custom-smile-url" style="white-space:nowrap;"><?php _e("Sticker Image URL", "wpdiscuz_sm"); ?>:</label></td>
                        <td style="width:30%;"><input type="text" name="custom-smile-url" valu="" id="custom-smile-url" placeholder="http://example.com/shock.png" style="margin:1px;padding:3px 5px; width:100%;"/></td>
                        <td style="padding-left:15px;"><span id="custom-smile-button" class="button"><?php _e("Add", "wpdiscuz_sm"); ?></span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Option end -->