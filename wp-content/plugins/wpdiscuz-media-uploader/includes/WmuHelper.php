<?php
if (!defined("ABSPATH")) {
    exit();
}

class WmuHelper implements WmuConstants {

    private $dbManager;
    private $options;
    private $maxFileSize;
    private $html5VideoTypes = [".mp4" => "video/mp4", ".webm" => "video/webm", ".ogg" => "video/ogg"];
    private $html5AudioTypes = [".mp3" => "audio/mpeg", ".ogg" => "audio/ogg", ".wav" => "audio/wav"];
    public $wpUploadsPath;
    public $wpUploadsUrl;

    public function __construct($dbManager, $options) {
        $this->dbManager = $dbManager;
        $this->options = $options;
        $wpUploadsDir = wp_upload_dir();
        $this->wpUploadsPath = $wpUploadsDir["path"];
        $this->wpUploadsUrl = $wpUploadsDir["url"];

        add_action("wpdiscuz_mu_tabs", [&$this, "addTabs"], 10);
        add_action("wpdiscuz_mu_file_count", function() {
            return intval($this->options->wmuMaxFileCount);
        });

        add_filter("wpdiscuz_mu_upload_type", [&$this, "uploadType"]);
        add_filter("wpdiscuz_mu_upload_icon", function() {
            return "fas fa-paperclip";
        });

        add_filter("wpdiscuz_mu_attachment_ids", function($args) {
            $args[self::KEY_VIDEOS] = [];
            $args[self::KEY_FILES] = [];
            return $args;
        });

        add_filter("wpdiscuz_mu_attachments_data", function($args) {
            $args[self::KEY_VIDEOS] = [];
            $args[self::KEY_FILES] = [];
            return $args;
        });

        add_filter("wpdiscuz_mu_add_attachment_ids", [&$this, "addAtttachmentIds"], 10, 3);
        add_filter("wpdiscuz_mu_add_attachments_data", [&$this, "addAtttachmentsData"], 10, 3);

        add_filter("wpdiscuz_mu_exists", "__return_true");
        add_filter("wpdiscuz_mu_export_data", [&$this, "exportPersonalData"], 10, 3);


        if ($this->options->wmuEnableCommentsFiltering) {
            add_action("pre_get_comments", [&$this, "preGetComments"]);
            add_action("restrict_manage_comments", [&$this, "dashboardCommentsFilterHtml"]);
            add_action("wpdiscuz_filtering_buttons", [&$this, "frontendCommentsFilterHtml"], 10, 2);
        }

        if ($this->options->wmuEnableMediaTab) {
            add_filter("wpdiscuz_content_modal_title", [&$this, "getMediaTabTitleHtml"], 10, 2);
            add_filter("wpdiscuz_content_modal_content", [&$this, "getMediaTabContentHtml"], 10, 3);
        }

        add_filter("wpdiscuz_mu_allowed_extensions", function() {
            return "";
        });
        if ($this->options->wmuLazyLoadImages) {
            add_filter("wpdiscuz_mu_lazyload_images", "__return_true");
            add_filter("wpdiscuz_comment_post", function ($response) {
                $response["callbackFunctions"][] = "wmuImagesInit";
                return $response;
            });
            add_filter("wpdiscuz_ajax_callbacks", function ($response) {
                $response["callbackFunctions"][] = "wmuImagesInit";
                return $response;
            });
        }

        add_filter("wpdiscuz_enable_content_modal", function() {
            return $this->options->wmuEnableMediaTab;
        });

        add_action("wpdiscuz_mu_preupload", [&$this, "wmuPreupload"], 10, 2);
        add_action("wpdiscuz_mu_compress_image", [&$this, "uploadImage"], 10, 3);
        add_filter("wpdiscuz_mu_attachment_parent", function ($parent) {
            if ($this->options->wmuAttachToPost) {
                $postId = empty($_POST["postId"]) ? 0 : intval($_POST["postId"]);
                if ($postId) {
                    $parent = $postId;
                }
            }
            return $parent;
        });
    }

    public function addAtttachmentIds($attachmentIds, $attachmentData, $file) {
        if (strpos($file["type"], "video/") !== false || strpos($file["type"], "audio/") !== false) {
            $attachmentIds[self::KEY_VIDEOS][] = $attachmentData["id"];
        } else {
            $attachmentIds[self::KEY_FILES][] = $attachmentData["id"];
        }
        return $attachmentIds;
    }

    public function addAtttachmentsData($attachmentsData, $attachmentData, $file) {
        if (strpos($file["type"], "video/") !== false || strpos($file["type"], "audio/") !== false) {
            $attachmentsData[self::KEY_VIDEOS][] = $attachmentData;
        } else {
            $attachmentsData[self::KEY_FILES][] = $attachmentData;
        }
        return $attachmentsData;
    }

    public function getAttachedVideos($attachIds, $currentUser = null, $size = "thumbnail") {
        $video = "";
        if ($attachIds) {
            $attachments = get_posts(["include" => $attachIds, "post__in" => $attachIds, "post_type" => "attachment"]);
            $width = $this->options->wmuVideoWidth;
            $height = $this->options->wmuVideoHeight;
            $style = "max-height:{$height}px;";
            $style .= "width:auto;";
			$wpdiscuz = wpDiscuz();
            foreach ($attachments as $attachment) {
                $deleteHtml = $wpdiscuz->helperUpload->getDeleteHtml($currentUser, $attachment, "video");
                $url = wp_get_attachment_url($attachment->ID);
                $src = "";
                $filename = $this->getFileName($attachment);
                if (preg_match("#^video#is", $attachment->post_mime_type)) {
                    if ($this->options->wmuIsHtml5Video && in_array($attachment->post_mime_type, $this->html5VideoTypes)) {
                        $attrs = apply_filters("wmu_video_attributes", "", $currentUser, $attachment);
                        $video .= "<div class='wmu-attachment wmu-attachment-$attachment->ID wmu-video'>";
                        $video .= "<video title='" . esc_attr($attachment->post_excerpt) . "' controls style='$style' {$attrs}>";
                        $video .= "<source src='$url' type='$attachment->post_mime_type'>";
                        $video .= __("Your browser does not support the video tag.", "wpdiscuz-media-uploader");
                        $video .= "</video>";
                        $video .= $deleteHtml;
                        $video .= "</div>";
                    } else {
                        $src = plugins_url(WMU_DIR_NAME . "/assets/img/file-icons/video-audio-type.png");
                        $video .= "<div class='wmu-attachment wmu-attachment-$attachment->ID'>";
                        $video .= "<a href='$url' class='wmu-attached-video-link' target='_blank'>";
                        $video .= "<img class='attachment-$size size-$size wmu-attached-video' src='$src' />";
                        $video .= $filename ? " <span class='wmu-attachment-name' title='" . esc_attr($attachment->post_excerpt) . "'> $filename </span> " : '';
                        $video .= "</a>";
                        $video .= $deleteHtml;
                        $video .= "</div>";
                    }
                } else if (preg_match("#^audio#is", $attachment->post_mime_type)) {
                    if ($this->options->wmuIsHtml5Audio && in_array($attachment->post_mime_type, $this->html5AudioTypes)) {
                        $video .= "<div class='wmu-attachment wmu-attachment-$attachment->ID wmu-audio'>";
                        $video .= "<audio title='" . esc_attr($attachment->post_excerpt) . "' controls style='width:{$width}px'>";
                        $video .= "<source src='$url' type='$attachment->post_mime_type'>";
                        $video .= __("Your browser does not support the audio tag.", "wpdiscuz-media-uploader");
                        $video .= "</audio>";
                        $video .= $deleteHtml;
                        $video .= "</div>";
                    } else {
                        $src = plugins_url(WMU_DIR_NAME . "/assets/img/file-icons/video-audio-type.png");
                        $video .= "<div class='wmu-attachment wmu-attachment-$attachment->ID'>";
                        $video .= "<a href='$url' class='wmu-attached-video-link' target='_blank'>";
                        $video .= "<img class='attachment-$size size-$size wmu-attached-video' src='$src' />";
                        $video .= $filename ? " <span class='wmu-attachment-name' title='" . esc_attr($attachment->post_excerpt) . "'> $filename </span>" : '';
                        $video .= "</a>";
                        $video .= $deleteHtml;
                        $video .= "</div>";
                    }
                }
            }
        }
        return do_shortcode($video);
    }

    public function getAttachedFiles($attachIds, $currentUser = null, $size = "thumbnail") {
        $files = "";
        if ($attachIds) {
			$wpdiscuz = wpDiscuz();
            $attachments = get_posts(["include" => $attachIds, "post__in" => $attachIds, "post_type" => "attachment"]);
            foreach ($attachments as $attachment) {
                $deleteHtml = $wpdiscuz->helperUpload->getDeleteHtml($currentUser, $attachment, "file");
                $url = wp_get_attachment_url($attachment->ID);
                $src = plugins_url(WMU_DIR_NAME . "/assets/img/file-icons/file-type.png");
                $filename = $this->getFileName($attachment);
                $files .= "<div class='wmu-attachment wmu-attachment-$attachment->ID'>";
                $files .= "<a href='$url' class='wmu-attached-file-link' target='_blank'>";
                $files .= "<img class='attachment-$size size-$size wmu-attached-file' src='$src' />";
                $files .= $filename ? " <span class='wmu-attachment-name' title='" . esc_attr($attachment->post_excerpt) . "'> $filename </span> " : '';
                $files .= "</a>";
                $files .= $deleteHtml;
                $files .= "</div>";
            }
        }
        return $files;
    }

    public function uploadType($type) {
        if ($this->options->wmuMaxFileCount > 1) {
            $type = "multiple";
        }
        return $type;
    }

    public function addTabs($html) {
        $html .= "<div class='wmu-tabs wmu-" . self::KEY_VIDEOS . "-tab wmu-hide'></div>";
        $html .= "<div class='wmu-tabs wmu-" . self::KEY_FILES . "-tab wmu-hide'></div>";
        return $html;
    }

    public function getFileName($attachment) {
        $name = false;
        if ($attachment) {
            if (is_object($attachment) && (isset($attachment->post_excerpt) || isset($attachment->post_title))) {
                $name = $attachment->post_excerpt ? $attachment->post_excerpt : $attachment->post_title;
            } else {
                $name = $attachment;
            }
            if (strlen($name) > 40) {
                $name = function_exists("mb_substr") ? mb_substr($name, -40, 40, "UTF-8") : substr($name, -40, 40);
                $name = "..." . $name;
            }
            $name = ucfirst(str_replace(["-", "_"], " ", $name));
        }
        return $name;
    }

    public static function hasAttachments($attachments) {
        $hasItems = false;
        if ($attachments && is_array($attachments)) {
            foreach ($attachments as $attachment) {
                if (is_array($attachment) && count($attachment)) {
                    $hasItems = true;
                    break;
                }
            }
        }
        return $hasItems;
    }

    public function imageFixOrientation($filename) {
        $isFunctionsExists = function_exists("exif_read_data") && function_exists("imagecreatefromjpeg") && function_exists("imagerotate") && function_exists("imagejpeg");
        if ($isFunctionsExists) {
            $exif = exif_read_data($filename);
            if (!empty($exif["Orientation"])) {
                $image = imagecreatefromjpeg($filename);
                switch ($exif["Orientation"]) {
                    case 3:
                        $image = imagerotate($image, 180, 0);
                        break;
                    case 6:
                        $image = imagerotate($image, -90, 0);
                        break;
                    case 8:
                        $image = imagerotate($image, 90, 0);
                        break;
                }
                imagejpeg($image, $filename, 90);
            }
        }
    }

    public function exportPersonalData($data, $key, $attachId) {
        if ($key === self::KEY_VIDEOS) {
            $data[] = ["name" => __("Attached Videos", "wpdiscuz-media-uploader"), "value" => wp_get_attachment_url($attachId)];
        } else if ($key === self::KEY_FILES) {
            $data[] = ["name" => __("Attached Files", "wpdiscuz-media-uploader"), "value" => wp_get_attachment_url($attachId)];
        }
        return $data;
    }

    public function getIPAddress() {
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {   //check ip from share internet
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {   //to check ip is pass from proxy
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        if ($ip == "::1") {
            $ip = "127.0.0.1";
        }
        return $ip;
    }

    public function preGetComments($query) {
        if ($this->options->wmuEnableCommentsFiltering) {
            global $current_screen;

            $isFilter = false;
            if ($current_screen && !empty($current_screen->id) && $current_screen->id == "edit-comments") {
                $isFilter = true;
            }

            $requestUri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";
            if ($requestUri && (strpos($requestUri, "/edit-comments.php") !== false)) {
                $isFilter = true;
            }

            if ($isFilter && !empty($_GET["wmu_attachments_filter"])) {
                $filter = trim($_GET["wmu_attachments_filter"]);
                if ($filter == "with_attachments") {
                    $query->query_vars["meta_query"] = [
                        [
                            "key" => WpdiscuzCore::METAKEY_ATTACHMENTS,
                            "value" => "",
                            "compare" => " != "
                        ]
                    ];
                }
            }
        }
    }

    public function dashboardCommentsFilterHtml() {
        $select = empty($_GET["wmu_attachments_filter"]) ? "all" : trim($_GET["wmu_attachments_filter"]);
        ?>
        <select name="wmu_attachments_filter">
            <option value="all" <?php selected($select == "all"); ?>><?php _e("All", "wpdiscuz-media-uploader"); ?></option>
            <option value="with_attachments" <?php selected($select == "with_attachments"); ?>><?php _e("With Attachments", "wpdiscuz-media-uploader"); ?></option>
        </select>
        <?php
    }

    public function frontendCommentsFilterHtml($currentUser, $wpdOptions) {
        if (!$wpdOptions->wp["isPaginate"]) {
            ?>
            <div class="wpd-filter wpdf-attachments wpd_not_clicked" data-filter-type="attachments" wpd-tooltip="<?php _e("Comments with attachments", "wpdiscuz-media-uploader") ?>">
                <i class="fas fa-photo-video"></i>
            </div>
            <?php
        }
    }

    public function getMediaTabTitleHtml($html, $currentUser) {
        if (!empty($currentUser->ID)) {
            ob_start();
            include_once WMU_DIR_PATH . "/includes/media/title.php";
            $html .= ob_get_clean();
        }
        return $html;
    }

    public function getMediaTabContentHtml($html, $currentUser, $isFirstTab) {
        if (!empty($currentUser->ID)) {
            $wpdiscuz = wpDiscuz();
            $wmuMediaObj = $this;
            $currentUserId = $currentUser->ID;
            $html .= "<div id='wpd-content-item-4' class='wpd-content-item'>";
            if ($isFirstTab) {
                include_once WMU_DIR_PATH . "/includes/media/content.php";
            }
            $html .= ob_get_clean();
            $html .= "</div>";
        }
        return $html;
    }

    public function wmuPreupload($file) {
        if (strpos($file["type"], "image/") !== false && ($imageInfo = getimagesize($file["tmp_name"]))) {
            $maxWidth = intval($this->options->wmuImagesMaxWidth);
            $maxHeight = intval($this->options->wmuImagesMaxHeight);
            if (($w = intval($imageInfo[0])) && ($h = intval($imageInfo[1])) && $maxWidth > 0 && $maxHeight > 0) {
                if ($w > $maxWidth || $h > $maxHeight) {
                    $response = ["errorCode" => "wmuPhraseImageSizesError"];
                    wp_send_json_error($response);
                }
            }
        }
    }

    public function uploadImage($success, $file, $dest, $q = 75) {
        if ($this->options->wmuIsCompressImages) {
            $info = getimagesize($file);
            $q = absint($this->options->wmuCompressionQuality);
            if ($info && function_exists("imagecreatefromjpeg") &&
                    function_exists("imagecreatefromgif") &&
                    function_exists("imagecreatefrompng") &&
                    function_exists("imagejpeg")) {
                if ($info["mime"] == "image/jpeg") {
                    $image = imagecreatefromjpeg($file);
                } elseif ($info["mime"] == "image/gif") {
                    $image = imagecreatefromgif($file);
                } elseif ($info["mime"] == "image/png") {
                    $image = imagecreatefrompng($file);
                }

                $success = imagejpeg($image, $dest, $q);
            }
        }
        return $success;
    }

}
