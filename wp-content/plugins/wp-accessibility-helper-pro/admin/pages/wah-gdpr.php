<?php
    $wah_gdpr_hidden = isset($_POST['wah_gdpr_hidden']) ? sanitize_text_field($_POST['wah_gdpr_hidden']) : '';

    if( $wah_gdpr_hidden == 'Y' && !empty($wah_gdpr_hidden) ) {

        $wah_gdpr_enable = isset( $_POST['wah_gdpr_enable'] ) ? 1 : 0;
        wah_set_param('wah_gdpr_enable', $wah_gdpr_enable);

        $wah_gdpr_position = isset( $_POST['wah_gdpr_position'] ) ? sanitize_text_field( $_POST['wah_gdpr_position'] ) : 'top-fullwidth';
        wah_set_param('wah_gdpr_position', $wah_gdpr_position);

        //wah_gdpr_theme
        $wah_gdpr_theme = isset( $_POST['wah_gdpr_theme'] ) ? $_POST['wah_gdpr_theme'] : 'light';
        wah_set_param('wah_gdpr_theme', $wah_gdpr_theme);

        $wah_gdpr_custom_bg = isset( $_POST['wah_gdpr_custom_bg'] ) ? $_POST['wah_gdpr_custom_bg'] : '#000000';
        wah_set_param('wah_gdpr_custom_bg', $wah_gdpr_custom_bg);

        $wah_gdpr_custom_text_color = isset( $_POST['wah_gdpr_custom_text_color'] ) ? $_POST['wah_gdpr_custom_text_color'] : '#FFFFFF';
        wah_set_param('wah_gdpr_custom_text_color', $wah_gdpr_custom_text_color);

        $wah_gdpr_custom_link_color = isset( $_POST['wah_gdpr_custom_link_color'] ) ? $_POST['wah_gdpr_custom_link_color'] : '#ff8040';
        wah_set_param('wah_gdpr_custom_link_color', $wah_gdpr_custom_link_color);

        $wah_gdpr_custom_accept_button_color = isset( $_POST['wah_gdpr_custom_accept_button_color'] ) ? $_POST['wah_gdpr_custom_accept_button_color'] : '#FFFFFF';
        wah_set_param('wah_gdpr_custom_accept_button_color', $wah_gdpr_custom_accept_button_color);

        $wah_gdpr_custom_accept_button_bg = isset( $_POST['wah_gdpr_custom_accept_button_bg'] ) ? $_POST['wah_gdpr_custom_accept_button_bg'] : '#008080';
        wah_set_param('wah_gdpr_custom_accept_button_bg', $wah_gdpr_custom_accept_button_bg);

        $wah_gdpr_custom_cancel_button_color = isset( $_POST['wah_gdpr_custom_cancel_button_color'] ) ? $_POST['wah_gdpr_custom_cancel_button_color'] : '#ff8040';
        wah_set_param('wah_gdpr_custom_cancel_button_color', $wah_gdpr_custom_cancel_button_color);

        $wah_gdpr_custom_cancel_button_bg = isset( $_POST['wah_gdpr_custom_cancel_button_bg'] ) ? $_POST['wah_gdpr_custom_cancel_button_bg'] : '#ff0000';
        wah_set_param('wah_gdpr_custom_cancel_button_bg', $wah_gdpr_custom_cancel_button_bg);

        $allowed_html = array (
    		'a' => array (
    			'href'   => array(),
    			'class'  => array(),
                'target' => array(),
                'rel'    => array()
    		),
            'br'     => array(),
            'em'     => array(),
            'strong' => array()
    	);
        $wah_gdpr_content = isset($_POST['wah_gdpr_content']) ? wp_kses( $_POST['wah_gdpr_content'], $allowed_html ) : '';
        wah_set_param('wah_gdpr_content', $wah_gdpr_content);

        $wah_gdpr_accept_button_title = isset($_POST['wah_gdpr_accept_button_title']) ? sanitize_text_field($_POST['wah_gdpr_accept_button_title']) : __('Accept','wp-accessibility-helper');
        wah_set_param('wah_gdpr_accept_button_title', $wah_gdpr_accept_button_title);

        $wah_gdpr_cancel_button_title = isset($_POST['wah_gdpr_cancel_button_title']) ? sanitize_text_field($_POST['wah_gdpr_cancel_button_title']) : __('Cancel','wp-accessibility-helper');
        wah_set_param('wah_gdpr_cancel_button_title', $wah_gdpr_cancel_button_title);

        $wah_gdpr_cookies = isset($_POST['wah_gdpr_cookies']) ? $_POST['wah_gdpr_cookies'] : 30;
        wah_set_param('wah_gdpr_cookies', $wah_gdpr_cookies); ?>

        <div class="notice notice-success is-dismissible">
            <p><strong><?php _e('WAH GDPR options saved.','wp-accessibility-helper'); ?></strong></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>

<?php  } else {
        $wah_gdpr_enable              = wah_get_param( 'wah_gdpr_enable' );
        $wah_gdpr_position            = wah_get_param( 'wah_gdpr_position' );
        $wah_gdpr_theme               = wah_get_param( 'wah_gdpr_theme' );

        $wah_gdpr_custom_bg                  = wah_get_param( 'wah_gdpr_custom_bg' ) ? wah_get_param( 'wah_gdpr_custom_bg' ) : '#000000';
        $wah_gdpr_custom_text_color          = wah_get_param( 'wah_gdpr_custom_text_color' ) ? wah_get_param( 'wah_gdpr_custom_text_color' ) : '#ffffff';
        $wah_gdpr_custom_link_color          = wah_get_param( 'wah_gdpr_custom_link_color' ) ? wah_get_param( 'wah_gdpr_custom_link_color' ) : '#ff8040';
        $wah_gdpr_custom_accept_button_color = wah_get_param( 'wah_gdpr_custom_accept_button_color' ) ? wah_get_param( 'wah_gdpr_custom_accept_button_color' ) : '#ffffff';
        $wah_gdpr_custom_accept_button_bg    = wah_get_param( 'wah_gdpr_custom_accept_button_bg' ) ? wah_get_param( 'wah_gdpr_custom_accept_button_bg' ) : '#008080';
        $wah_gdpr_custom_cancel_button_color = wah_get_param( 'wah_gdpr_custom_cancel_button_color' ) ? wah_get_param( 'wah_gdpr_custom_cancel_button_color' ) : '#ff8040';
        $wah_gdpr_custom_cancel_button_bg    = wah_get_param( 'wah_gdpr_custom_cancel_button_bg' ) ? wah_get_param( 'wah_gdpr_custom_cancel_button_bg' ) : '#ff0000';

        $wah_gdpr_content             = wah_get_param( 'wah_gdpr_content' );
        $wah_gdpr_accept_button_title = wah_get_param( 'wah_gdpr_accept_button_title' );
        $wah_gdpr_cancel_button_title = wah_get_param( 'wah_gdpr_cancel_button_title' );
        $wah_gdpr_cookies             = wah_get_param( 'wah_gdpr_cookies' );

    }
?>

<div class="wrap">

    <h1 class="wah-admin-page-header">
        <?php _e("WAH Cookie Notice for GDPR","wp-accessibility-helper"); ?>
    </h1>

    <?php render_wah_header_notice(); ?>

    <div id="wah-gdpr-wrapper">

        <div class="wah-gdpr-about">
            <div class="wah-gdpr-about-content">
                <h3>GDPR - General Data Protection Regulation</h3>
                <div class="wah-gdpr-content-inner" style="display:none;">
                    <p>The <strong>General Data Protection Regulation</strong> (EU) 2016/679 (GDPR) is a regulation in EU law on data protection and privacy for all individual citizens of the European Union (EU) and the European Economic Area (EEA). It also addresses the transfer of personal data outside the EU and EEA areas. The GDPR aims primarily to give control to individuals over their personal data and to simplify the regulatory environment for international business by unifying the regulation within the EU. Superseding the Data Protection Directive 95/46/EC, the regulation contains provisions and requirements related to the processing of personal data of individuals (formally called data subjects in the GDPR) inside the EEA, and applies to any enterprise established in the EEA or—regardless of its location and the data subjects' citizenship—that is processing the personal information of data subjects inside the EEA.</p>

                    <p>Controllers of personal data must put in place appropriate technical and organizational measures to implement the data protection principles. Business processes that handle personal data must be designed and built with consideration of the principles and provide safeguards to protect data (for example, using pseudonymization or full anonymization where appropriate), and use the highest-possible privacy settings by default, so that the datasets are not publicly available without explicit, informed consent, and cannot be used to identify a subject without additional information (which must be stored separately). No personal data may be processed unless this processing is done under a lawful basis specified by the regulation, or unless the data controller or processor has received an unambiguous and individualized affirmation of consent from the data subject. The data subject has the right to revoke this consent at any time.</p>

                    <p><small>Source: Wikipedia, the free encyclopedia [<a href="https://en.wikipedia.org/wiki/General_Data_Protection_Regulation" target="_blank">Read more about GDPR</a>]</small></p>
                </div>
            </div>
            <div class="wah-gdpr-about-toggle">
                <button type="button" class="components-button is-button is-default is-large toggle-about-gdpr"><?php _e("Toggle","wp-accessibility-helper"); ?></button>
            </div>
        </div>

    </div>

    <div class="wah-gdpr-settings">

        <form name="wah_gdrp_form" class="clearfix" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

            <input type="hidden" name="wah_gdpr_hidden" value="Y">

            <?php /* WAH GDPR Settings */ ?>
            <?php render_form_section_title(__( 'WAH GDPR Settings', 'wp-accessibility-helper' )); ?>
            <div class="wah_form_elements_wrapper">
                <div class="form_element_content">

                    <?php render_switch_element(__("Enable WAH GDPR?","wp-accessibility-helper"),$wah_gdpr_enable,"wah_gdpr_enable"); ?>

                    <hr>

                    <div class="form_row">
                        <div class="form30">
                            <label for="wah_gdpr_position" class="text_label">
                                <?php _e("WAH GDPR popup position","wp-accessibility-helper"); ?>
                            </label>
                            <p class="wah-small-description"><?php _e('All positions are fixed','wp-accessibility-helper'); ?></p>
                        </div>
                        <div class="form70">
                            <select name="wah_gdpr_position">
                                <?php $position_options = get_wah_gdpr_position(); ?>
                                <?php foreach( $position_options as $key=>$value ) : ?>
                                    <option value="<?php echo $key; ?>" <?php echo selected( $wah_gdpr_position, $key ); ?>>
                                        <?php echo $value; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="form_row">
                        <div class="form30">
                            <label for="wah_gdpr_theme" class="text_label">
                                <?php _e("WAH GDPR popup theme","wp-accessibility-helper"); ?>
                            </label>
                        </div>
                        <div class="form70">
                            <select name="wah_gdpr_theme">
                                <option value="light" <?php echo selected( $wah_gdpr_theme, 'light' ); ?>>
                                    <?php _e('Light theme', 'wp-accessibility-helper'); ?>
                                </option>
                                <option value="dark" <?php echo selected( $wah_gdpr_theme, 'dark' ); ?>>
                                    <?php _e('Dark theme', 'wp-accessibility-helper'); ?>
                                </option>
                                <option value="custom" <?php echo selected( $wah_gdpr_theme, 'custom' ); ?>>
                                    <?php _e('Custom theme', 'wp-accessibility-helper'); ?>
                                </option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="if_wah_gdpr_theme_custom" style="display:none;">

                        <div class="wah_gdpr_theme_preview">
                            <div class="popup-wrapper" data-target="wah_gdpr_custom_bg">
                                <div class="popup-content">
                                    <span class="text" data-target="wah_gdpr_custom_text_color">This website uses cookies. We use cookies to personalise content and to provide the better accessibility features.</span> <a href="#" onclick="return false;" data-target="wah_gdpr_custom_link_color">Term and conditions</a>
                                </div>
                                <div class="popup-buttons">
                                    <a href="#" onclick="return false;" class="accept-btn" data-target="wah_gdpr_custom_accept_button_bg">
                                        <span data-target="wah_gdpr_custom_accept_button_color">Accept<span>
                                    </a>
                                    <a href="#" onclick="return false;" class="cancel-btn" data-target="wah_gdpr_custom_cancel_button_bg">
                                        <span data-target="wah_gdpr_custom_cancel_button_color">Cancel</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form_row">
                            <div class="form30">
                                <label for="wah_gdpr_custom_bg" class="text_label">
                                    <?php _e("Popup background color","wp-accessibility-helper"); ?>
                                </label>
                            </div>
                            <div class="form70">
                                <input type="color" name="wah_gdpr_custom_bg" value="<?php echo $wah_gdpr_custom_bg; ?>" data-style="bg">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form30">
                                <label for="wah_gdpr_custom_text_color" class="text_label">
                                    <?php _e("Popup text color","wp-accessibility-helper"); ?>
                                </label>
                            </div>
                            <div class="form70">
                                <input type="color" name="wah_gdpr_custom_text_color" value="<?php echo $wah_gdpr_custom_text_color; ?>" data-style="color">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form30">
                                <label for="wah_gdpr_custom_link_color" class="text_label">
                                    <?php _e("Popup link color","wp-accessibility-helper"); ?>
                                </label>
                            </div>
                            <div class="form70">
                                <input type="color" name="wah_gdpr_custom_link_color" value="<?php echo $wah_gdpr_custom_link_color; ?>" data-style="color">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form30">
                                <label for="wah_gdpr_custom_accept_button_color" class="text_label">
                                    <?php _e("'Accept' button text color","wp-accessibility-helper"); ?>
                                </label>
                            </div>
                            <div class="form70">
                                <input type="color" name="wah_gdpr_custom_accept_button_color" value="<?php echo $wah_gdpr_custom_accept_button_color; ?>" data-style="color">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form30">
                                <label for="wah_gdpr_custom_accept_button_bg" class="text_label">
                                    <?php _e("'Accept' button background","wp-accessibility-helper"); ?>
                                </label>
                            </div>
                            <div class="form70">
                                <input type="color" name="wah_gdpr_custom_accept_button_bg" value="<?php echo $wah_gdpr_custom_accept_button_bg; ?>" data-style="bg">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form30">
                                <label for="wah_gdpr_custom_cancel_button_color" class="text_label">
                                    <?php _e("'Cancel' button text color","wp-accessibility-helper"); ?>
                                </label>
                            </div>
                            <div class="form70">
                                <input type="color" name="wah_gdpr_custom_cancel_button_color" value="<?php echo $wah_gdpr_custom_cancel_button_color; ?>" data-style="color">
                            </div>
                        </div>
                        <div class="form_row">
                            <div class="form30">
                                <label for="wah_gdpr_custom_cancel_button_bg" class="text_label">
                                    <?php _e("'Cancel' button background","wp-accessibility-helper"); ?>
                                </label>
                            </div>
                            <div class="form70">
                                <input type="color" name="wah_gdpr_custom_cancel_button_bg" value="<?php echo $wah_gdpr_custom_cancel_button_bg; ?>" data-style="bg">
                            </div>
                        </div>

                        <hr>

                    </div>

                    <div class="form_row">
                        <div class="form30">
                            <label for="wah_gdrp_content" class="text_label">
                                <?php _e("WAH GDPR popup content","wp-accessibility-helper"); ?>
                            </label>
                        </div>
                        <div class="form70">
                            <?php
                                $editor_id = 'wah_gdpr_content';
                                wp_editor( $wah_gdpr_content, $editor_id );
                            ?>
                        </div>
                    </div>

                    <hr>

                    <?php render_title_element(__("Accept/OK button title","wp-accessibility-helper"),$wah_gdpr_accept_button_title,"wah_gdpr_accept_button_title","", ""); ?>

                    <hr>

                    <?php render_title_element(__("Cancel button title","wp-accessibility-helper"),$wah_gdpr_cancel_button_title,"wah_gdpr_cancel_button_title","", ""); ?>

                    <hr>

                    <?php render_title_element(__("Cookies","wp-accessibility-helper"), $wah_gdpr_cookies, "wah_gdpr_cookies","","", "",false, true ); ?>
                    <p class="wah-small-description"><?php _e('Enter days number to save users cookies', 'wp-accessibility-helper'); ?></p>

                </div>

            </div>

            <p class="submit" style="padding-left:10px;">
                <input type="submit" name="Submit" class="button button-primary button-large" value="<?php _e('Update Options', 'wp-accessibility-helper' ) ?>" />
            </p>

        </form>

    </div>

</div>
