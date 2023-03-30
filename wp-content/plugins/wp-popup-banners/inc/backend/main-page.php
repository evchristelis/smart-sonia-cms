<?php
defined('ABSPATH') or die("No script kiddies please!");
$wpb_default_settings = get_option('wpb_default_settings');

unset($_SESSION['wpb_edit_id']);
?>
<div class="wrap clearfix">
    <div class="wpb-add-set-wrapper clearfix">
        <div class="wpb-panel">
            <div class="wpb-manage-header">               
                <div class="wpb-boards-wrapper">

                    <div class="wpb-banner clearfix">

                        <div class="wpb_main_heading">
                            <?php echo __('WP Popup Banners', 'wp-popup-banners') ?>
                            <span class="wpb-author-detail"><a href="http://accesspressthemes.com" target="_blank">By AccessPress Themes</a></span>
                        </div>
                        <div class="wpb-version">
                            <span> Version  <?php echo WPB_VERSION; ?></span>
                        </div>
                        <div class="wpb-social-wrap">
                            <p><?php echo __('Follow us for new updates', 'wp-popup-banners') ?></p>
                            <div class="wbp-social-bttns">

                                <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FAccessPress-Themes%2F1396595907277967&amp;width&amp;layout=button&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId=1411139805828592" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:20px; width:50px " allowtransparency="true"></iframe>
                                &nbsp;&nbsp;
                                <iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" class="twitter-follow-button twitter-follow-button-rendered" title="Twitter Follow Button" src="http://platform.twitter.com/widgets/follow_button.b212c8422d3b3079acc6183618b32f10.en.html#dnt=false&amp;id=twitter-widget-0&amp;lang=en&amp;screen_name=apthemes&amp;show_count=false&amp;show_screen_name=true&amp;size=m&amp;time=1457511428769" style="position: static; visibility: visible; width: 124px; height: 20px;" data-screen-name="apthemes"></iframe>
                                <script>!function (d, s, id) {
                                        var js, fjs = d.getElementsByTagName(s)[0];
                                        if (!d.getElementById(id)) {
                                            js = d.createElement(s);
                                            js.id = id;
                                            js.src = "//platform.twitter.com/widgets.js";
                                            fjs.parentNode.insertBefore(js, fjs);
                                        }
                                    }(document, "script", "twitter-wjs");</script>

                            </div>
                        </div>  

                    </div>
                    <div class="wbp-left-section-wrapper clearfix">   
                        <h2 class="nav-tab-wrapper">
                            <!-- <a <?php if (isset($_GET['action'])) { ?> href="<?php echo admin_url('admin.php'); ?>?page=wpb" <?php } else { ?>href="javascript:void(0)"<?php } ?> id="wpb_manage_popups" class="nav-tab  main-nav nav-tab-active"><?php _e('Manage Popups', 'wp-popup-banners') ?></a> -->
                            <a href="javascript:void(0)" id="wpb_manage_popups" class="nav-tab  main-nav nav-tab-active"><?php _e('Manage Popups', 'wp-popup-banners') ?></a>
                            <a href="javascript:void(0)" id="wpb_settings" class="nav-tab main-nav"><?php _e('Settings', 'wp-popup-banners'); ?></a>
                            <a href="javascript:void(0)" id="wpb_about" class="nav-tab main-nav"><?php _e('About', 'wp-popup-banners'); ?></a>
                        </h2>

                        <?php
                        /**
                         * session messages are displayed here
                         * such as database insert, delete, update
                         * success or failure messages
                         */
                        if (isset($_SESSION['wpb_db_success'])) {
                            ?>
                            <div class="notice notice-success is-dismissible">
                                <?php
                                echo $_SESSION['wpb_db_success'];
                                unset($_SESSION['wpb_db_success']);
                                ?>
                            </div>
                        <?php } else if (isset($_SESSION['wpb_db_fail'])) { ?>
                            <div class="notice notice-error is-dismissible">
                                <?php
                                echo $_SESSION['wpb_db_fail'];
                                unset($_SESSION['wpb_db_fail']);
                                ?>
                            </div> 
                            <?php
                        }
                        ?>

                        <div class="wpb_tab_content_holder">
                            <!-- <div id="optionsframework" class="postbox"> -->
                            <div id="optionsframework">
                                <?php
                                /**
                                 * Manage Option section for tabs
                                 * */
                                include('tabs/manage_option.php');

                                /**
                                 * Setting section for tabs
                                 * */
                                include('tabs/setting.php');

                                /**
                                 * Setting section for tabs
                                 * */
                                include('tabs/about.php');
                                ?>
                                <div class="wpb_contents"></div>

                            </div><!--optionsframework-->
                        </div>
                    </div>
                    <?php include( 'sidebar.php' ); ?>
                </div>    
            </div>
            <div class="clear"></div>
        </div>
    </div>


</div><!--div class wrap-->
