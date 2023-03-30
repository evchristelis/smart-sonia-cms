<?php
if (!defined("ABSPATH")) {
    exit();
}
$attachments = get_comment_meta($item, WpdiscuzCore::METAKEY_ATTACHMENTS, true);
if ($attachments && is_array($attachments)) {
    ?>    
    <div class="wmu-comment-attachments">
        <?php
        foreach ($attachments as $key => $ids) {
            if (!empty($ids)) {
                $attachIds = array_map("intval", $ids);
                $count = (count($attachIds) > 1) ? "multi" : "single";
                if ($key == WpdiscuzCore::KEY_IMAGES) {
                    $imgHtml = $wpdiscuz->helperUpload->getAttachedImages($attachIds, $currentUser, "full", false);
                    ?>
                    <div class="wmu-attached-images wmu-count-<?php echo $count; ?>"><?php echo $imgHtml; ?></div>
                    <?php
                } else if ($key == self::KEY_VIDEOS) {
                    $videoHtml = $wmuMediaObj->getAttachedVideos($attachIds, $currentUser);
                    ?>
                    <div class="wmu-attached-videos"><?php echo $videoHtml; ?></div>
                    <?php
                } else if ($key == self::KEY_FILES) {
                    $filesHtml = $wmuMediaObj->getAttachedFiles($attachIds, $currentUser);
                    ?>
                    <div class="wmu-attached-files"><?php echo $filesHtml; ?></div>
                    <?php
                }
            }
        }
        ?>
    </div>
    <?php
}