<?php
if (!defined("ABSPATH")) {
    exit();
}
?>
<tr scope="row">
    <th><label for="wmuPhraseImageSizesError"><?php _e("Message if image sizes (w/h) bigger than allowed", "wpdiscuz-media-uploader"); ?></label></th>
    <td><input type="text" value="<?php echo $this->wmuPhrases["wmuPhraseImageSizesError"]; ?>" name="wmuPhrases[wmuPhraseImageSizesError]" class="wmu-phrase"/></td>
</tr>
<tr scope="row">
    <th><label for="wmuPhraseMediaTabTitle"><?php _e("Media tab title", "wpdiscuz-media-uploader"); ?></label></th>
    <td><input type="text" value="<?php echo $this->wmuPhrases["wmuPhraseMediaTabTitle"]; ?>" name="wmuPhrases[wmuPhraseMediaTabTitle]" class="wmu-phrase"/></td>
</tr>