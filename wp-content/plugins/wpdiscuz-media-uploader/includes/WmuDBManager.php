<?php

if (!defined("ABSPATH")) {
    exit();
}

class WmuDBManager implements WmuConstants {

    private $db;
    private $dbprefix;

    function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->dbprefix = $wpdb->prefix;
    }

    public function getAttachments($commentId) {
        $data = [];
        if ($commentId) {
            $metakeys = "'" . self::METAKEY_IMAGE . "','" . self::METAKEY_VIDEO . "','" . self::METAKEY_FILE . "'";
            $sql = $this->db->prepare("SELECT `meta_key`, `meta_value` FROM `{$this->db->commentmeta}` WHERE `comment_id` = %d AND `meta_key` IN ($metakeys);", $commentId);
            $data = $this->db->get_results($sql, ARRAY_A);
        }
        return $data;
    }

    public function getCommentsWithOldkeys($startId = 0, $limit = "") {
        $start = $startId ? "`comment_id` > " . $startId . " AND" : "";
        $limit = $limit ? "LIMIT " . $limit : "";
        $metakeys = "'" . self::METAKEY_IMAGE . "','" . self::METAKEY_VIDEO . "','" . self::METAKEY_FILE . "'";
        $sql = "SELECT `comment_id` FROM `{$this->db->commentmeta}` WHERE $start `meta_key` IN ($metakeys) GROUP BY `comment_id` $limit;";
        $data = $this->db->get_results($sql, ARRAY_A);
        return $data;
    }

}
