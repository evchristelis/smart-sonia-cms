<?php
    $role_links_setup           = wah_get_param('wah_role_links_setup');
    $remove_link_titles         = wah_get_param('wah_remove_link_titles');
    $header_element_selector    = wah_get_param('wah_header_element_selector');
    $sidebar_element_selector   = wah_get_param('wah_sidebar_element_selector');
    $footer_element_selector    = wah_get_param('wah_footer_element_selector');
    $main_element_selector      = wah_get_param('wah_main_element_selector');
    $nav_element_selector       = wah_get_param('wah_nav_element_selector');
    $lights_off_selector        = wah_get_param('wah_lights_selector');

    $wah_greyscale_enable          = wah_get_param('wah_greyscale_enable');
    $wah_greyscale_image_selectors = wah_get_param('wah_greyscale_image_selectors');
    $wah_greyscale_selectors       = '';
    if( $wah_greyscale_enable && $wah_greyscale_image_selectors ){
        $wah_greyscale_selectors = $wah_greyscale_image_selectors;
    }

    $wahi = isset($_GET['wahi']) ? base64_decode($_GET['wahi']) : '';
    $wahl = isset($_GET['wahl']) ? base64_decode($_GET['wahl']) : '';
?>

<script type="text/javascript">
    var WAHPro_Controller = {
        roleLink                : <?php if($role_links_setup): ?>1<?php else: ?>0<?php endif; ?>,
        removeLinkTitles        : <?php if($remove_link_titles): ?>1<?php else: ?>0<?php endif; ?>,
        headerElementSelector   : <?php if($header_element_selector):?>'<?php echo $header_element_selector; ?>'<?php else: ?>''<?php endif; ?>,
        sidebarElementSelector  : <?php if($sidebar_element_selector):?>'<?php echo $sidebar_element_selector; ?>'<?php else: ?>''<?php endif; ?>,
        footerElementSelector   : <?php if($footer_element_selector):?>'<?php echo $footer_element_selector; ?>'<?php else: ?>''<?php endif; ?>,
        mainElementSelector     : <?php if($main_element_selector):?>'<?php echo $main_element_selector; ?>'<?php else: ?>''<?php endif; ?>,
        navElementSelector      : <?php if($nav_element_selector):?>'<?php echo $nav_element_selector; ?>'<?php else: ?>''<?php endif; ?>,
        wah_target_src          : <?php if($wahi): ?>'<?php echo $wahi; ?>'<?php else: ?>''<?php endif; ?>,
        wah_target_link         : <?php if($wahl): ?>'<?php echo $wahl; ?>'<?php else: ?>''<?php endif; ?>,
        wah_lights_off_selector : <?php if($lights_off_selector): ?>'<?php echo $lights_off_selector; ?>'<?php else: ?>''<?php endif; ?>,
        wah_greyscale_selectors : '<?php echo $wah_greyscale_selectors; ?>'
    };
</script>
