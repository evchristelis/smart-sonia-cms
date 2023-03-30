<?php
    $wah_gdpr_enable              = wah_get_param( 'wah_gdpr_enable' );
    if( $wah_gdpr_enable ) :
        $wahgdpr_cookies              = ( isset( $_COOKIE['wahgdpr'] ) && ( $_COOKIE['wahgdpr'] == 'on') ) ? true : false;
        $wah_gdpr_position            = wah_get_param( 'wah_gdpr_position' );
        $wah_gdpr_theme               = wah_get_param( 'wah_gdpr_theme' );
        $wah_gdpr_content             = wah_get_param( 'wah_gdpr_content' );
        $wah_gdpr_accept_button_title = wah_get_param( 'wah_gdpr_accept_button_title' );
        $wah_gdpr_cancel_button_title = wah_get_param( 'wah_gdpr_cancel_button_title' );
        $wah_gdpr_cookies             = wah_get_param( 'wah_gdpr_cookies' );
?>

    <?php if( ! $wahgdpr_cookies ) : ?>

        <div id="wah-gdpr-popup" class="<?php echo $wah_gdpr_theme; ?>_theme is-<?php echo $wah_gdpr_position; ?>" data-type="<?php echo $wah_gdpr_position; ?>" style="display:none;">

            <?php if( $wah_gdpr_theme == 'custom' ) { wah_render_custom_gdpr_popup_style(); } ?>

            <div class="wah-gdpr-popup-inner">

                <?php if( $wah_gdpr_content ) : ?>
                    <div class="wah-gdpr-popup-content">
                        <?php echo wpautop($wah_gdpr_content); ?>
                    </div>
                <?php endif; ?>

                <div class="wah-gdpr-popup-btn">
                    <button type="button" class="accept-wah-gdpr-popup"
                        title="<?php echo $wah_gdpr_accept_button_title; ?>"
                        aria-label="<?php echo $wah_gdpr_accept_button_title; ?>">
                        <?php echo $wah_gdpr_accept_button_title; ?>
                    </button>
                    <button type="button" class="close-wah-gdpr-popup"
                        title="<?php echo $wah_gdpr_cancel_button_title; ?>"
                        aria-label="<?php echo $wah_gdpr_cancel_button_title; ?>">
                        <?php echo $wah_gdpr_cancel_button_title; ?>
                    </button>
                </div>

            </div>

        </div>

    <?php endif; ?>

<?php endif; ?>
