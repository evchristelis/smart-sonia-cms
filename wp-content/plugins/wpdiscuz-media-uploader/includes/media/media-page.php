<?php
if (!defined("ABSPATH")) {
    exit();
}

$action = isset($_POST["action"]) ? $_POST["action"] : "";
$wpdiscuz = wpDiscuz();
$currentUser = WpdiscuzHelper::getCurrentUser();

if ($action && !empty($currentUser->ID)) {
    $wmuMediaObj = $this->helper;
    $page = isset($_POST["page"]) ? intval($_POST["page"]) : 0;
    $lrItemsCount = 3;
    $perPage = apply_filters("wpdiscuz_content_per_page", 3);
    $offset = $page * $perPage;
    $args = [
        "number" => $perPage,
        "status" => "all",
        "user_id" => $currentUser->ID,
        "offset" => $offset,
        "fields" => "ids",
        "meta_query" => [
            [
                "key" => WpdiscuzCore::METAKEY_ATTACHMENTS,
                "value" => "",
                "compare" => "!="
            ]
        ],
    ];

    $items = get_comments($args);
    if ($items && is_array($items)) {
        $args["number"] = null;
        $args["count"] = true;
        $itemsCount = get_comments($args);
        $pCount = intval($itemsCount / $perPage);
        $pageCount = ($itemsCount % $perPage == 0) ? $pCount : $pCount + 1;
        foreach ($items as $item) {
            include WMU_DIR_PATH . "/includes/media/item.php";
        }
        include WMU_DIR_PATH . "/includes/media/pagination.php";
        ?>
        <input type="hidden" class="wpd-page-number" value="<?php echo $page; ?>"/>
    <?php } else { ?>
        <div class='wpd-item'><?php echo $wpdiscuz->options->phrases["wc_user_settings_no_data"]; ?></div>
        <?php
    }
}
