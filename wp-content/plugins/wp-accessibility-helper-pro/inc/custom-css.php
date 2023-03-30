<?php
$wah_hide_on_mobile         = wah_get_param('wah_hide_on_mobile');
$wah_custom_css             = wah_get_param('wah_custom_css');
$wah_customize_close_button = wah_get_param('wah_customize_close_button');

if( $wah_hide_on_mobile || $wah_custom_css ): ?><style><?php endif; ?>
    <?php if( $wah_hide_on_mobile ) : ?>
        @media only screen and (max-width: 480px) {div#wp_access_helper_container {display: none;}}
    <?php endif;
    if( $wah_custom_css ) {
        echo $wah_custom_css;
    } ?>
<?php if( $wah_hide_on_mobile || $wah_custom_css ): ?></style><?php endif; ?>


<?php if( wah_get_param('wah_custom_title_selector_on') && $wah_custom_title_selector = wah_get_param('wah_custom_title_selector') ) : ?>
    <style id="wahpro_custom_selectors_style_tag">
        <?php $wah_custom_title_selector = explode( ",", $wah_custom_title_selector );
        foreach( $wah_custom_title_selector as $selector ) : ?>
            body.wah_highlight_titles <?php echo $selector; ?> { background-color: yellow !important; }
        <?php endforeach; ?>
    </style>
<?php endif; ?>

<?php if( $wah_customize_close_button && wah_get_param('wah_close_btn_bg') && wah_get_param('wah_close_btn_color') ) : ?>
<style>
    button.close_container { background: <?php echo wah_get_param('wah_close_btn_bg'); ?> !important; color: <?php echo wah_get_param('wah_close_btn_color'); ?> !important;}
</style>
<?php endif; ?>
