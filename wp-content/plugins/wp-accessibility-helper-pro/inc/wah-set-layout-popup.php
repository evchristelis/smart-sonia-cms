<?php
    $wah_set_layout_setup = wah_get_param('wah_set_layout_setup');
    $wah_set_layout_popup_title = wah_get_param('wah_set_layout_popup_title');
    if( $wah_set_layout_setup ) :
?>

<div class="wah_set_layout_popup">

    <div class="wah_set_layout_popup_inner">

        <?php if( $wah_set_layout_popup_title ) : ?>
            <h3><?php echo esc_html($wah_set_layout_popup_title); ?></h3>
        <?php endif; ?>

        <button type="button" class="close-wah_set_layout_popup">
            <span class="goi-close-b"></span>
        </button>

        <?php if( ! $wah_set_layout_popup_title ) : ?>
            <div class="popup-title-placeholder"></div>
        <?php endif; ?>

        <div class="wah_set_wah_layout">
            <button type="button" class="wahout set-wah-layout is-mini-sidebar" data-new-wahstyle="mini-sidebar"
                aria-label="<?php _e('mini sidebar','wp-acccessibility-helper'); ?>"></button>
            <?php /* <span><?php _e('Mini sidebar','wp-acccessibility-helper'); ?></span> */ ?>
        </div>

        <div class="wah_set_wah_layout">
            <button type="button" class="wahout set-wah-layout is-bottom-fullwidth" data-new-wahstyle="wah-bottom-fullwidth"
                aria-label="<?php _e('bottom fullwidth sidebar', 'wp-accessibility-helper'); ?>"></button>
            <?php /* <span><?php _e('Bottom fullwidth sidebar','wp-acccessibility-helper'); ?></span> */ ?>
        </div>

        <div class="wah_set_wah_layout">
            <button type="button" class="wahout set-wah-layout is-magic-sidebar" data-new-wahstyle="magic-sidebar"
                aria-label="<?php _e('magic sidebar', 'wp-accessibility-helper'); ?>">
            </button>
            <?php /* <span><?php _e('Magic sidebar','wp-acccessibility-helper'); ?></span> */ ?>
        </div>

        <div class="wah_set_wah_layout">
            <button type="button" class="wahout set-wah-layout is-wide-sidebar" data-new-wahstyle="wide-sidebar"
                aria-label="<?php _e('wide sidebar', 'wp-accessibility-helper'); ?>">
            </button>
            <?php /* <span><?php _e('Wide sidebar','wp-acccessibility-helper'); ?></span> */ ?>
        </div>

        <div class="wah_set_wah_layout">
            <button type="button" class="wahout set-wah-layout is-standart-sidebar"
                data-new-wahstyle="standart-sidebar" aria-label="<?php _e('standart sidebar','wp-acccessibility-helper'); ?>">
            </button>
            <?php /* <span><?php _e('Standart sidebar','wp-acccessibility-helper'); ?></span> */ ?>
        </div>
    </div>

    </div>

</div>

<?php endif; ?>
