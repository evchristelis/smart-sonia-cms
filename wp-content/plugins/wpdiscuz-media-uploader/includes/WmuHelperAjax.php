<?php

if (!defined("ABSPATH")) {
    exit();
}

class WmuHelperAjax implements WmuConstants {

    private $dbManager;
    private $options;
    private $helper;
    private $currentUser;

    public function __construct($dbManager, $options, $helper) {
        $this->dbManager = $dbManager;
        $this->options = $options;
        $this->helper = $helper;

        add_action("wp_ajax_wmuRegenerateMetakeys", [&$this, "regenerateMetakeys"]);
        add_action("wp_ajax_wpdImportCIR", [&$this, "importCIR"]);
        add_action("wp_ajax_wpdImportDCO", [&$this, "importDCO"]);

        add_filter("wpdiscuz_filter_args", [&$this, "attachmentsCommentsArgs"]);
//        add_filter('wpdiscuz_comment_list_args', [&$this, 'attachmentsCommentListArgs']);

        add_action("wp_ajax_wmuGetMediaPage", [&$this, "getMediaPage"]);
        add_action("wp_ajax_nopriv_wmuGetMediaPage", [&$this, "getMediaPage"]);
    }

    public function regenerateMetakeys() {
        $response = ["progress" => 100];
        $allCommentsCount = empty($_POST["allCommentsCount"]) ? 0 : $_POST["allCommentsCount"];
        if ($allCommentsCount) {
            $limit = 25;
            $commentId = empty($_POST["commentId"]) ? 0 : intval($_POST["commentId"]);
            $comments = $this->dbManager->getCommentsWithOldkeys($commentId, $limit);
            foreach ($comments as $comment) {
                $commentId = intval($comment["comment_id"]);
                $attachments = $this->dbManager->getAttachments($commentId);
                $metadata = [];
                if ($attachments && is_array($attachments)) {
                    foreach ($attachments as $attachment) {
                        $key = $this->getNewKey($attachment["meta_key"]);
                        $attachIds = array_map("intval", explode(",", $attachment["meta_value"]));
                        if ($attachIds && is_array($attachIds)) {
                            foreach ($attachIds as $attachId) {
                                if (get_attached_file($attachId)) {
                                    $metadata[$key][] = $attachId;
                                    update_post_meta($attachId, WpdiscuzCore::METAKEY_ATTCHMENT_COMMENT_ID, $commentId);
                                }
                            }
                        }
                        delete_comment_meta($commentId, $attachment["meta_key"]);
                    }
                }
                if (WmuHelper::hasAttachments($metadata)) {
                    update_comment_meta($commentId, WpdiscuzCore::METAKEY_ATTACHMENTS, $metadata);
                } else {
                    delete_comment_meta($commentId, WpdiscuzCore::METAKEY_ATTACHMENTS);
                }
            }

            $commentsLeft = $this->dbManager->getCommentsWithOldkeys($commentId);
            $progress = intval(($allCommentsCount - count($commentsLeft)) * 100 / $allCommentsCount);
            if ($progress >= 100) {
                $progress = 100;
                update_option(self::OPTION_REGENERATE_KEYS, 1, "no");
            }

            $response["commentId"] = $commentId;
            $response["progress"] = $progress;
            $response["allCommentsCount"] = $allCommentsCount;
        }
        wp_send_json_success($response);
    }

    /**
     * Import images uploaded via "Comment Images Reloaded" plugin
     */
    public function importCIR() {
        $response = ["progress" => 0];
        $cirData = isset($_POST["cirData"]) ? $_POST["cirData"] : "";
        if ($cirData) {
            parse_str($cirData, $data);
            $limit = 100;
            $step = isset($data["cir-step"]) ? intval($data["cir-step"]) : 0;
            $cirImagesCount = isset($data["cir-images-count"]) ? intval($data["cir-images-count"]) : 0;
            $nonce = isset($data["wpd-cir-images"]) ? trim($data["wpd-cir-images"]) : "";
            if (wp_verify_nonce($nonce, "wc_tools_form") && $cirImagesCount) {
                $offset = $limit * $step;
                if ($limit && $offset >= 0) {
                    $cirMetakey = "comment_image_reloaded";
                    $cirSlug = "comment-images-reloaded";
                    $commentIds = get_comments(
                            [
                                "number" => $limit,
                                "offset" => $offset,
                                "fields" => "ids",
                                "orderby" => "comment_date",
                                "order" => "asc",
                                "meta_query" => [
                                    [
                                        "key" => $cirMetakey,
                                        "value" => "",
                                        "compare" => "!="
                                    ]
                                ]
                            ]
                    );

                    if ($commentIds) {
                        foreach ($commentIds as $commentId) {
                            $cirAttachmentIds = get_comment_meta($commentId, $cirMetakey, true);
                            if ($cirAttachmentIds && is_array(maybe_unserialize($cirAttachmentIds))) {
                                $wpdiscuzMUMeta = ["images" => []];
                                foreach ($cirAttachmentIds as $cirAttachId) {
                                    $cirAttachPath = $cirAttachId ? get_attached_file($cirAttachId) : false;
                                    if ($cirAttachPath && getimagesize($cirAttachPath)) {
                                        $wpdiscuzMUMeta["images"][] = $cirAttachId;
                                        update_post_meta($cirAttachId, "_wp_attachment_image_alt", get_the_title($cirAttachId));
                                        update_post_meta($cirAttachId, WpdiscuzCore::METAKEY_ATTCHMENT_OWNER_IP, "127.0.0.1");
                                        update_post_meta($cirAttachId, WpdiscuzCore::METAKEY_ATTCHMENT_COMMENT_ID, $commentId);
                                        update_post_meta($cirAttachId, WpdiscuzCore::METAKEY_ATTCHMENT_IMPORTED_FROM, $cirSlug);
                                    }
                                }
                                update_comment_meta($commentId, WpdiscuzCore::METAKEY_ATTACHMENTS, $wpdiscuzMUMeta);
                            }
                        }

                        ++$step;
                        $response["step"] = $step;
                        $progress = $offset ? $offset * 100 / $cirImagesCount : $limit * 100 / $cirImagesCount;
                        $response["progress"] = (($p = intval($progress)) > 100) ? 100 : $p;
                    } else {
                        $response["progress"] = 100;
                    }
                }
            }
        }
        wp_die(json_encode($response));
    }

    /**
     * Import images uploaded via "DCO Comment Attachment" plugin
     */
    public function importDCO() {
        $response = ["progress" => 0];
        $dcoData = isset($_POST["dcoData"]) ? $_POST["dcoData"] : "";
        if ($dcoData) {
            parse_str($dcoData, $data);
            $limit = 100;
            $step = isset($data["dco-step"]) ? intval($data["dco-step"]) : 0;
            $dcoImagesCount = isset($data["dco-images-count"]) ? intval($data["dco-images-count"]) : 0;
            $nonce = isset($data["wpd-dco-images"]) ? trim($data["wpd-dco-images"]) : "";
            if (wp_verify_nonce($nonce, "wc_tools_form") && $dcoImagesCount) {
                $offset = $limit * $step;
                if ($limit && $offset >= 0) {
                    $dcoMetakey = "attachment_id";
                    $dcoSlug = "dco-comment-attachment";
                    $commentIds = get_comments(
                            [
                                "number" => $limit,
                                "offset" => $offset,
                                "fields" => "ids",
                                "orderby" => "comment_date",
                                "order" => "asc",
                                "meta_query" => [
                                    [
                                        "key" => $dcoMetakey,
                                        "value" => "",
                                        "compare" => "!="
                                    ]
                                ]
                            ]
                    );

                    if ($commentIds) {
                        foreach ($commentIds as $commentId) {
                            $dcoAttachId = intval(get_comment_meta($commentId, $dcoMetakey, true));
                            if ($dcoAttachId) {
                                $dcoAttachPath = get_attached_file($dcoAttachId);
                                if ($dcoAttachPath) {

                                    if (getimagesize($dcoAttachPath)) {
                                        $wpdiscuzMUMeta = ["images" => [$dcoAttachId]];
                                        update_comment_meta($commentId, WpdiscuzCore::METAKEY_ATTACHMENTS, $wpdiscuzMUMeta);

                                        update_post_meta($dcoAttachId, "_wp_attachment_image_alt", get_the_title($dcoAttachId));
                                        update_post_meta($dcoAttachId, WpdiscuzCore::METAKEY_ATTCHMENT_OWNER_IP, "127.0.0.1");
                                        update_post_meta($dcoAttachId, WpdiscuzCore::METAKEY_ATTCHMENT_COMMENT_ID, $commentId);
                                        update_post_meta($dcoAttachId, WpdiscuzCore::METAKEY_ATTCHMENT_IMPORTED_FROM, $dcoSlug);
                                    } else {
                                        $attachment = get_post($dcoAttachId);
                                        $key = (strpos($attachment->post_mime_type, "video/") === false) && (strpos($attachment->post_mime_type, "audio/") === false) ? "files" : "videos";

                                        $wpdiscuzMUMeta = [$key => [$dcoAttachId]];
                                        update_comment_meta($commentId, WpdiscuzCore::METAKEY_ATTACHMENTS, $wpdiscuzMUMeta);

                                        update_post_meta($dcoAttachId, "_wp_attachment_image_alt", get_the_title($dcoAttachId));
                                        update_post_meta($dcoAttachId, WpdiscuzCore::METAKEY_ATTCHMENT_OWNER_IP, "127.0.0.1");
                                        update_post_meta($dcoAttachId, WpdiscuzCore::METAKEY_ATTCHMENT_COMMENT_ID, $commentId);
                                        update_post_meta($dcoAttachId, WpdiscuzCore::METAKEY_ATTCHMENT_IMPORTED_FROM, $dcoSlug);
                                    }
                                }
                            }
                        }

                        ++$step;
                        $response["step"] = $step;
                        $progress = $offset ? $offset * 100 / $dcoImagesCount : $limit * 100 / $dcoImagesCount;
                        $response["progress"] = (($p = intval($progress)) > 100) ? 100 : $p;
                    } else {
                        $response["progress"] = 100;
                    }
                }
            }
        }
        wp_die(json_encode($response));
    }

    private function getNewKey($key) {
        if ($key == self::METAKEY_IMAGE) {
            $key = WpdiscuzCore::KEY_IMAGES;
        } else if ($key == self::METAKEY_VIDEO) {
            $key = self::KEY_VIDEOS;
        } else if ($key == self::METAKEY_FILE) {
            $key = self::KEY_FILES;
        }
        return $key;
    }

    public function attachmentsCommentsArgs($args) {
        if ($args["wpdType"] === "attachments") {
            $args["meta_query"] = [
                [
                    "key" => WpdiscuzCore::METAKEY_ATTACHMENTS,
                    "value" => "",
                    "compare" => "!="
                ]
            ];
        }
        return $args;
    }

    public function attachmentsCommentListArgs($args) {
        return $args;
    }

    public function getMediaPage() {
        ob_start();
        include_once WMU_DIR_PATH . "/includes/media/media-page.php";
        $html = ob_get_clean();
        wp_die($html);
    }

    public function setCurrentUser($currentUser) {
        $this->currentUser = $currentUser;
    }

}
