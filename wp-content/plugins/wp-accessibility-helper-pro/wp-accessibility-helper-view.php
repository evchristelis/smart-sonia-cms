<?php
if( ! wah_analyzer_isset() ) :
    $wah_custom_icon = wah_get_param('wah_custom_icon');
    if( $wah_custom_icon ) {
      $default_wah_logo = plugins_url().'/wp-accessibility-helper-pro/assets/images/accessibility-48.jpg';
      $icon = wah_get_param('wah_image_url') ? wah_get_param('wah_image_url') : $default_wah_logo;
    }
    $close_button_title = wah_get_param('wah_close_button_title') ? wah_get_param('wah_close_button_title'): __("Close","wp-accessibility-helper");
    $wah_clear_cookies_title = wah_get_param('wah_clear_cookies_title') ? wah_get_param('wah_clear_cookies_title') : __("Clear cookies","wp-accessibility-helper");
    $wah_on_off_title     = wah_get_param('wah_on_off_title') ? wah_get_param('wah_on_off_title') : __("ON/OFF","wp-accessibility-helper");
    $wah_darktheme_enable = wah_get_param('wah_darktheme_enable');
    $wah_sidebar_layout = wah_get_user_wahstyle(); //wah_get_param( 'wah_sidebar_layout' )
    $dark_theme_class     = 'light_theme';
    if($wah_darktheme_enable){
        $dark_theme_class = 'dark_theme';
    }
    $wah_statement_enable = wah_get_param( 'wah_statement_enable' );
    $wah_statement_button_title  = wah_get_param( 'wah_statement_button_title' );
    $wah_custom_button    = wah_get_custom_button_params();
    $button = array(
        'style'      => '',
        'icon_style' => ''
    );
    if( $wah_custom_button && ! $wah_custom_icon ){
        $button = array(
            'style' => 'font-size:30px;background-color:'.$wah_custom_button['wah_logo_bg'].' !important;border:0;padding:0;width:50px;height: 50px;',
            'icon_style' => 'font-size:30px;color:'.$wah_custom_button['wah_logo_color'].' !important;'
        );
    }
?>
<div id="wp_access_helper_container" class="accessability_container <?php echo $dark_theme_class; ?>">
    <!-- WP Accessibility Helper PRO (<?php echo WAHPRO_VERSION; ?>) -->
    <?php do_action('before_wah_wrapper'); ?>

        <button type="button" class="wahout aicon_link <?php echo 'layout-' . $wah_sidebar_layout; ?>"
            accesskey="<?php echo apply_filters( 'wah_open_accesskey', 'z' ); ?>"
            aria-label="<?php _e("Accessibility Helper sidebar","wp-accessibility-helper"); ?>"
            title="<?php _e("Accessibility Helper sidebar","wp-accessibility-helper"); ?>"
            style="<?php echo $button['style']; ?>">
            <?php if( $wah_custom_icon ) : ?>
                <img src="<?php echo apply_filters( 'wah_icon_url', $icon ); ?>"
                    alt="<?php echo apply_filters( 'wah_icon_alt',  __("Accessibility","wp-accessibility-helper") ); ?>" class="aicon_image" />
            <?php else: ?>
              <span class="goi-wah-icon wah-font-icon" style="<?php echo $button['icon_style']; ?>"></span>
            <?php endif; ?>
        </button>

        <div id="access_container" aria-hidden="false">

            <?php if( $wah_sidebar_layout != 'magic-sidebar' ) : ?>

                <?php if( $wah_sidebar_layout == 'wah-bottom-fullwidth' ) : ?>
                    <div class="wah-bottom-fullwidth-close-button-wrapper">
                <?php endif; ?>
                    <div class="wahpro-buttons-header">
                        <button tabindex="-1" type="button" class="close_container wahout"
                            accesskey="<?php echo apply_filters( 'wah_close_accesskey', 'x' ); ?>"
                            aria-label="<?php echo $close_button_title; ?>"
                            title="<?php echo $close_button_title; ?>">
                            <?php echo $close_button_title; ?>
                        </button>

                        <?php if( $wah_statement_enable ) : ?>
                            <button type="button" id="wahpro-accessibility-statement" class="wahout"
                                aria-label="<?php echo $wah_statement_button_title; ?>"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <?php echo $wah_statement_button_title; ?>
                            </button>
                            <?php include('wp-accessibility-helper-statement-popup.php'); ?>
                        <?php endif; ?>
                    </div>
                <?php if( $wah_sidebar_layout == 'wah-bottom-fullwidth' ) : ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

            <div class="access_container_inner">

                <?php if( $wah_sidebar_layout == 'magic-sidebar' && $wah_statement_enable ) : ?>
                    <div class="a_module">
                        <div class="a_module_exe">
                            <button type="button" id="wahpro-accessibility-statement" class="wahout wah-action-button"
                                aria-label="<?php echo $wah_statement_button_title; ?>"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <?php echo $wah_statement_button_title; ?>
                            </button>
                        </div>
                    </div>

                    <?php include('wp-accessibility-helper-statement-popup.php'); ?>
                <?php endif; ?>

                <?php wah_render_enabled_widgets_list(); ?>

                <?php if( $wah_sidebar_layout == 'magic-sidebar' ) :
                    $close_button_title = wah_get_param('wah_close_button_title') ? wah_get_param('wah_close_button_title'): __("Close","wp-accessibility-helper");
                    ?>
                    <div class="a_module wah_magic_skip_links">
                        <div class="a_module_exe">
                            <button type="button" class="wah-action-button wahout close-wah-magic-sidebar" aria-label="<?php echo $close_button_title; ?>" title="<?php echo $close_button_title; ?>">
                                <?php echo $close_button_title; ?>
                            </button>
                        </div>
                    </div>
                <?php else : ?>
                    <?php wah_render_last_skiplink(); ?>
                <?php endif; ?>

                <?php wah_render_bottom_links(); ?>

            </div>
        </div>
        <?php
            include_once( dirname(__FILE__) . '/inc/js-vars.php' );
            include_once( dirname(__FILE__) . '/inc/custom-font.php' );
            include_once( dirname(__FILE__) . '/inc/custom-css.php' );
            include_once( dirname(__FILE__) . '/inc/custom-logo-position.php' );
        ?>
    <?php do_action('after_wah_wrapper'); ?>
    <!-- WP Accessibility Helper PRO (<?php echo WAHPRO_VERSION; ?>). Created by Alex Volkov. -->
</div>
<?php endif; ?>
