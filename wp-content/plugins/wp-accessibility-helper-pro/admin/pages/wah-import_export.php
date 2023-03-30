<?php

    $notice_message = '';

    if( isset($_POST) ){

        $wah_ie_action = isset( $_POST['wah_ie_action'] ) ? $_POST['wah_ie_action'] : '';

        if( $wah_ie_action == 'export' ){

            $wah_output   = array(
                'settings' => get_wah_settings(),
                'widgets'  => wah_get_admin_widgets_list()
            );

            $json_data = json_encode($wah_output);
            $file = fopen( __DIR__ . '/wahpro-config.json','w');
            fwrite($file, $json_data);
            fclose($file);

            $plugins_url = plugins_url() . '/wp-accessibility-helper-pro/admin/pages/wahpro-config.json';
            if( $plugins_url ){ ?>
                <script type="text/javascript">
                    jQuery(document).ready( function(){
                        jQuery('body').append('<a id="download_wahconfig" href="<?php echo $plugins_url; ?>" download></a>');
                        jQuery('#download_wahconfig')[0].click();
                    });
                </script>
            <?php }
        } elseif ( $wah_ie_action == 'import' ) {
            $json_file = isset( $_FILES['wah_ie_file'] ) ? $_FILES['wah_ie_file'] : '';
            if( $json_file ){
                $import_settings = wahpro_import_settings_from_json( $json_file );
                if( ! $import_settings['error'] ){
                    $notice_message = '<div class="notice notice-success is-dismissible"><p><strong>'.$import_settings['message'].'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                } else {
                    $notice_message = '<div class="notice notice-error is-dismissible"><p><strong>'.$import_settings['message'].'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                }
            }
        }
    }
?>

<div class="wrap" id="wah-pro-import-export">
    <form method="post" class="wah-pro-import-export-form" enctype="multipart/form-data">

        <h1 class="wah-admin-page-header">
            <?php _e("WP Accessibility Helper - Import/Export settings","wp-accessibility-helper"); ?>
        </h1>

        <?php render_wah_header_notice(); ?>

        <br/><br/>

        <?php echo $notice_message; ?>

        <p>Here you can import or export WAH Pro settings. Please use valid JSON file.</p>

        <hr />
        <div class="form_row">

            <div class="form100">

                <div class="form_row">
                    <div class="form30">
                        <label for="wah_ie_action"><?php _e('What do you want to do?','wp-accessibility-helper'); ?></label>
                    </div>
                    <div class="form70">
                        <select class="" name="wah_ie_action" id="wah_ie_action">
                            <option value=""><?php _e('Select action','wp-accessibility-helper'); ?></option>
                            <option value="import"><?php _e('Import from JSON file','wp-accessibility-helper'); ?></option>
                            <option value="export"><?php _e('Export current settings to JSON file','wp-accessibility-helper'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="form_row ie_file_selector" style="display:none;">
                    <div class="form30">
                        <label for="wah_ie_file"><?php _e('Select file','wp-accessibility-helper'); ?></label>
                    </div>
                    <div class="form70">
                        <input type="file" name="wah_ie_file" id="wah_ie_file">
                    </div>
                </div>
                <hr />
                <div class="form_row ie_submit">
                    <div class="form30">
                        <input type="submit" id="wah_ie_submit" class="button button-primary button-large" value="<?php _e('Submit','wp-accessibility-helper'); ?>">
                    </div>
                </div>

            </div>

        </div>


    </form>
</div>
