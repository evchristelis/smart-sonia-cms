<?php
    $wah_custom_logo_position = wah_get_param('wah_custom_logo_position');
    if( $wah_custom_logo_position && ! isset( $_COOKIE['user_wahstyle'] ) ) :
        $wah_logo_top    = wah_get_param('wah_logo_top');
        $wah_logo_right  = wah_get_param('wah_logo_right');
        $wah_logo_left   = wah_get_param('wah_logo_left');
        $wah_logo_bottom = wah_get_param('wah_logo_bottom');
?>
    <style media="screen">
        body #wp_access_helper_container button.aicon_link {
            <?php if($wah_logo_top || ( $wah_logo_top == '0' ) ): ?>top:<?php echo $wah_logo_top; ?>px !important;bottom: auto !important;<?php endif; ?>
            <?php if($wah_logo_right || ( $wah_logo_right == '0' ) ): ?>right:<?php echo $wah_logo_right; ?>px !important;left: auto !important;<?php endif; ?>
            <?php if($wah_logo_left || ( $wah_logo_left == '0' ) ): ?>left:<?php echo $wah_logo_left; ?>px !important;right: auto !important;<?php endif; ?>
            <?php if($wah_logo_bottom || ( $wah_logo_bottom == '0' ) ): ?>bottom:<?php echo $wah_logo_bottom; ?>px !important;top: auto !important;<?php endif; ?>
        }
    </style>
<?php endif; ?>
