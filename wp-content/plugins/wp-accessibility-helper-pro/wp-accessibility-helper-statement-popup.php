<?php $wah_statement_popup_content = wah_get_param( 'wah_statement_popup_content' ); ?>
<div class="wahpro-accessibility-statement-popup" aria-hidden="true" tabindex="-1">
    <div class="accessibility-statement-popup-inner">
        <div class="wah-nicescroll-box">
            <div class="wrap">
                <button type="button" aria-label="<?php _e('Close popup', 'wp-accessibility-helper'); ?>" id="wahpro-close-statement-popup">&#10006;</button>
                <div class="wahpro-accessibility-statement-content">
                    <?php echo wpautop($wah_statement_popup_content); ?>
                </div>
            </div>
        </div>
    </div>
</div>
