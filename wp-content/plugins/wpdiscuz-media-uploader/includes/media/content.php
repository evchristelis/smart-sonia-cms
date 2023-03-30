<?php
if (!defined("ABSPATH")) {
    exit();
}
ob_start();
$wpdiscuz = wpDiscuz();
$action = "wmuGetMediaPage";
$perPage = apply_filters("wpdiscuz_content_per_page", 3);
$lrItemsCount = 3;
$args = [
    "number" => $perPage,
    "status" => "all",
    "user_id" => $currentUserId,
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
    $page = 0;
    foreach ($items as $item) {
        include WMU_DIR_PATH . "/includes/media/item.php";
    }
    include WMU_DIR_PATH . "/includes/media/pagination.php";
    ?>
    <input type="hidden" class="wpd-page-number" value="0"/>
<?php } else { ?>
    <div class='wpd-item'><?php echo $wpdiscuz->options->phrases["wc_user_settings_no_data"]; ?></div>
    <?php
}