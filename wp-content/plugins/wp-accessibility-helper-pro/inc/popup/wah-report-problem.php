<div class="wah-dialog-popup" id="wah-report-problem-popup" data-dialogid="wah-report-problem" aria-hidden="true">
    <?php
        global $wp;
        $wah_current_page_url   = home_url( $wp->request );
        $wah_report_popup_title = wah_get_param( 'wah_report_popup_title' );
    ?>
    <div class="wah-dialog-popup-inner">

        <button type="button" class="wah-close-dialog">&times;</button>

        <?php if( $wah_report_popup_title ) : ?>
            <h3 class="wah-report-popup-title"><?php echo esc_html($wah_report_popup_title); ?></h3>
        <?php endif; ?>

        <form id="wah-report-problem-form" method="post">
            <div class="wah-report-form-row">
                <label>
                    <span><?php _e('Page url', 'wp-accessibility-helper'); ?></span>
                    <input type="text" name="wah_report_page_url" value="<?php echo $wah_current_page_url; ?>" readonly>
                </label>
            </div>
            <div class="wah-report-form-row">
                <label>
                    <span><?php _e('Your email', 'wp-accessibility-helper'); ?></span>
                    <input type="email" name="wah_report_user_email" required>
                </label>
            </div>
            <div class="wah-report-form-row">
                <label>
                    <span><?php _e('Subject', 'wp-accessibility-helper'); ?></span>
                    <input type="text" name="wah_report_subject" required>
                </label>
            </div>
            <div class="wah-report-form-row">
                <label>
                    <span><?php _e('Description', 'wp-accessibility-helper'); ?></span>
                    <textarea name="wah_report_description" rows="6" cols="80" required></textarea>
                </label>
            </div>
            <div class="wah-report-form-row">
                <button type="submit"><?php _e( 'Send','wp-accessibility-helper' ); ?></button>
            </div>
            <div class="wah-report-form-row ajax-response"></div>
        </form>
    </div>
</div>
