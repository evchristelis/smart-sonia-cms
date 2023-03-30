<?php

defined('ABSPATH') or die("No direct script allowed!!!");
/*
  Plugin Name: WP Popup Banners
  Plugin URI:  https://accesspressthemes.com/wordpress-plugins/wp-popup-banners/
  Description: Popup Banners plugin helps to add popups to your site with custom messages and effects
  Version:     1.2.5
  Author:      AccessPress Themes
  Author URI:  http://accesspressthemes.com
  License:     GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Domain Path: /languages/
  Text Domain: wp-popup-banners
 */

/**
 * Declaration of necessary constants for plugin
 */
defined('WPB_IMAGE_DIR') or define('WPB_IMAGE_DIR', plugin_dir_url(__FILE__) . 'images');
defined('WPB_BACKEND_DIR') or define('WPB_BACKEND_DIR', plugin_dir_url(__FILE__) . 'inc/backend');
defined('WPB_JS_DIR') or define('WPB_JS_DIR', plugin_dir_url(__FILE__) . 'js');
defined('WPB_CSS_DIR') or define('WPB_CSS_DIR', plugin_dir_url(__FILE__) . 'css_files');
defined('WPB_VERSION') or define('WPB_VERSION', '1.2.5');
defined('WPB_DEFAULT_VARIABLE') or define('WPB_DEFAULT_VARIABLE', 'wpb_default_settings');
defined('WPB_ABSPATH') or define('WPB_ABSPATH', plugin_dir_path(__FILE__));

if (!class_exists('WPB_Class')) {

    /**
     * Declaration of plugin main class
     */
    class WPB_Class {

      var $wpb_settings;

        /**
         * Constructor
         */
        function __construct() {

          $this->wpb_settings = get_option('wpb_default_settings');
            register_activation_hook(__FILE__, array($this, 'activation_function')); //load the function on plugin Activation
            add_action('admin_init', array($this, 'plugin_init')); //session start and load the text domain on plugin initialization
            add_action('admin_menu', array($this, 'add_wpb_menu')); //add the plugin menu on menu bar
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_script')); //register admin scripts and css
            add_action('admin_post_wpb_save_options', array($this, 'wpb_save_options')); //save the form data to the database
            add_action('admin_post_wpb_delete', array($this, 'delete_wpb')); //delete popup data from database       
            add_action('wp_ajax_get_popup_data', array($this, 'wpb_get_popup_data')); //get the popup data from database on ajax call
            add_action('wp_ajax_nopriv_get_popup_data', array($this, 'no_perminission')); //restrict the ajax call for other users except admin login

            /* Frontend Script */
            add_action('wp_enqueue_scripts', array($this, 'wpb_register_frontend_assets')); //register the front end scripts and css
            add_action('wp_footer', array($this, 'wpb_appendto_footer'), 100); //append the popup div on the footer of page
            add_filter( 'admin_footer_text', array( $this, 'wppb_admin_footer_text' ) );
            add_filter( 'plugin_row_meta', array( $this, 'wppb_plugin_row_meta' ), 10, 2 );
          }

          function wppb_admin_footer_text( $text ){
            global $post;
            if ( (isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'wpb' ) ) {
              $link = 'https://wordpress.org/support/plugin/wp-popup-banners/reviews/#new-post';
              $pro_link = 'https://accesspressthemes.com/wordpress-plugins/wp-popup-banners-pro';
              $text = 'Enjoyed WP Popup Banners? <a href="' . $link . '" target="_blank">Please leave us a ★★★★★ rating</a> We really appreciate your support! | Try premium version of <a href="' . $pro_link . '" target="_blank">WP Popup Banners Pro</a> - more features, more power!';
              return $text;
            } else {
              return $text;
            }
          }

          function wppb_plugin_row_meta( $links, $file ){

            if ( strpos( $file, 'wp-popup-banners.php' ) !== false ) {
              $new_links = array(
                'demo' => '<a href="http://demo.accesspressthemes.com/wordpress-plugins/wp-popup-banner" target="_blank"><span class="dashicons dashicons-welcome-view-site"></span>Live Demo</a>',
                'doc' => '<a href="https://accesspressthemes.com/documentation/wp-popup-banners/" target="_blank"><span class="dashicons dashicons-media-document"></span>Documentation</a>',
                'support' => '<a href="http://accesspressthemes.com/support" target="_blank"><span class="dashicons dashicons-admin-users"></span>Support</a>',
                'pro' => '<a href="https://accesspressthemes.com/wordpress-plugins/wp-popup-banners-pro" target="_blank"><span class="dashicons dashicons-cart"></span>Premium version</a>'
              );

              $links = array_merge( $links, $new_links );
            }

            return $links;
          }
        /*
         * Plugin Activation 
         */

        function activation_function() {
          include('inc/activation.php');
        }

        /**
         * Starts session on admin_init hook
         */
        function plugin_init() {
          if (!session_id() && !headers_sent()) {
            session_start();
          }
          load_plugin_textdomain('wp-popup-banners', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        /**
         * Returns Default Settings
         */
        function get_default_settings() {
          $default_settings = array(
            'check_enable' => '1',
            'default_popup_id' => ''
          );

          return $default_settings;
        }

        /**
         * Plugin Admin Menu
         */
        function add_wpb_menu() {
          $menu = add_menu_page(
            __('WP Popup Banners', 'wp-popup-banners'), __('WP Popup Banners', 'wp-popup-banners'), 'manage_options', 'wpb', array($this, 'wpb_settings'), WPB_IMAGE_DIR . '/wpb1.png'
          );
        }

        /**
         * Plugin Main Settings Page
         */
        function wpb_settings() {
          include('inc/backend/main-page.php');
        }

        /**
         * Registers Admin Assets
         */
        function enqueue_admin_script($hook) {
          if (isset($_GET['page']) && $_GET['page'] == 'wpb') {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_style('wpb-fontawesome', WPB_CSS_DIR . '/font-awesome/font-awesome.min.css');
            wp_enqueue_style('wpb-admin-css', WPB_CSS_DIR . '/backend.css', array(), WPB_VERSION);
            wp_enqueue_style('wpb-popup-css', WPB_CSS_DIR . '/wpb_popup.css', array(), WPB_VERSION);
            wp_enqueue_script('wpb-popup-js', WPB_JS_DIR . '/backend_popup.js', array('jquery', 'wp-color-picker'), WPB_VERSION, false);
            wp_enqueue_script('wpb-popup-colorpicker-alpha', WPB_JS_DIR . '/wp-color-picker-alpha.js', array('wp-color-picker'), WPB_VERSION );
                /**
                 * localize the variable with javascript
                 */
                wp_localize_script(
                  'wpb-popup-js', 'wpb_admin_js', array(
                    'wpb_ajaxurl' => admin_url('admin-ajax.php'),
                    'wpb_ajax_nonce' => wp_create_nonce('wpb_ajax_nonce')
                  )
                );
              }
            }

        /**
         * Save popup parameters to the database
         * 
         */
        function wpb_save_options() {
          if (isset($_POST['wpb_add_nonce_save_settings'], $_POST['wpb_save_settings']) && wp_verify_nonce($_POST['wpb_add_nonce_save_settings'], 'wpb_nonce_save_settings')) {
                /**
                 * include to add/update popup if condition matched
                 */
                include( 'inc/backend/save_new_popup.php' );
              } else {
                die('No script kiddies please!!');
              }
            }

        /**
         * 
         * Delete banner from database
         */
        function delete_wpb() {
          if (!empty($_GET) && wp_verify_nonce($_GET['_wpnonce'], 'wpb_delete_nonce')) {
            $log_id = $_GET['wpb_id'];
            global $wpdb;
            $table_name = $wpdb->prefix . 'popup_banners';
            $delete = $wpdb->delete($table_name, array('id' => $log_id), array('%d'));

            if ($delete) {
              $_SESSION['wpb_db_success'] = __('Banner deleted Successfully', 'wp-popup-banners');
            } else {
              $_SESSION['wpb_db_fail'] = __('Failed to delete Banner.', 'wp-popup-banners');
            }

            wp_redirect(admin_url('admin.php?page=wpb'));
          } else {
            die('No script kiddies please!!');
          }
        }

        /**
         * Get popup parameters from database
         */
        function wpb_get_popup_data() {
          return (include('inc/get_db_data.php'));
        }

        /**
         * restrict the Ajax call to the unauthorized users
         */
        function no_perminission() {
          die('No script kiddies please!!');
        }

        /**
         * Registers frontend Assets
         */
        function wpb_register_frontend_assets() {
            /**
             * check the conditions such as if the popup option is enabled or not
             * and check the active popup. And also check where to display the popup such
             * as only on home page or all page or only on specific pages and enque the scripts 
             * and css on that pages. 
             */
            $condition = $this->check_popup_condition();
            if ($condition == 1) {//if the condition is true
              $arr_data = $this->wpb_get_popup_data();

              wp_enqueue_style('wpb-admin-css', WPB_CSS_DIR . '/wpb_popup.css', array(), WPB_VERSION);
                wp_enqueue_script('wpb-nicescroll-js-script', WPB_JS_DIR . '/jquery.nicescroll.js', array('jquery'), true, WPB_VERSION); //registering nice scroll js 
                wp_enqueue_script('wpb-frontend-js-script', WPB_JS_DIR . '/frontend_popup.js', array('jquery', 'wpb-nicescroll-js-script'), true, WPB_VERSION); //registering frontend js 
                wp_localize_script(
                  'wpb-frontend-js-script', 'wpb_frontend_js', array(
                    'popup_delay_enable' => $arr_data['popup_delay_enable'],
                    'popup_delay' => $arr_data['popup_delay'],
                    'popup_close_countdown' => $arr_data['popup_close_countdown'],
                    'show_popup_cookie' => $arr_data['show_popup'],
                    'show_countdown_message' => $arr_data['show_countdown_message'],
                    'popup_type' => $arr_data['popup_type'],
                    'autoclose_enable' => $arr_data['autoclose_enable'],
                  )
                );
            }//end of if condition
          }

        /**
         * append the hidden popup div on the footer of page
         */
        function wpb_appendto_footer() {
          if (isset($_GET['wpb_preview']) && $_GET['wpb_preview'] && is_user_logged_in()) {
            $default_wpb_id = $popup_banner_id = $_GET['banner_id'];
            global $wpdb;
            $table_name = $wpdb->prefix . "popup_banners";
            $popup_details = $wpdb->get_results("SELECT * FROM $table_name where id=$popup_banner_id");
            if (!empty($popup_details)) {
              $arr_data = $this->wpb_get_popup_data();
                    $show_popup = $arr_data['show_popup']; //show popup once, every time
                    $display_on = $arr_data['display_on'];
                    $page_list = $arr_data['page_list'];

                    include('inc/frontend_main_popup.php'); //include the popup div for front end pages
                  }
                } else {
                $condition = $this->check_popup_condition(); //check the popup condition

                if ($condition == 1) {//if the condition is true
                  if (!isset($_COOKIE['wp_popup_once'])) {
                    $wpb_default_settings = get_option('wpb_default_settings');
                    $default_wpb_id = $wpb_default_settings['default_popup_id'];
                    global $wpdb;
                    $table_name = $wpdb->prefix . "popup_banners";
                    $popup_details = $wpdb->get_results("SELECT * FROM $table_name where id=$default_wpb_id");
                    if (!empty($popup_details)) {
                      $arr_data = $this->wpb_get_popup_data();
                            $show_popup = $arr_data['show_popup']; //show popup once, every time
                            $display_on = $arr_data['display_on'];
                            $page_list = $arr_data['page_list'];

                            include('inc/frontend_main_popup.php'); //include the popup div for front end pages
                          }
                        }
                }//end of if condition
              }
            }

        /**
         * check the conditions to display the popup
         * and if true, reutrn 1 and if false, return 0
         */
        function check_popup_condition() {
          $wpb_default_settings = get_option('wpb_default_settings');
          if (isset($_GET['wpb_preview']) && $_GET['wpb_preview'] && is_user_logged_in()) {
            $wpb_default_settings['check_enable'] = 1;
            $wpb_default_settings['default_popup_id'] = sanitize_text_field($_GET['banner_id']);
          }
          if (($wpb_default_settings['check_enable'] == 1 && $wpb_default_settings['default_popup_id'] != '')) {
            global $post;
            if (is_404()) {
              $post_id = '';
            } else {
                    $post_id = $post->ID; //check the current page id
                  }
                  $arr_data = $this->wpb_get_popup_data();
                if (is_home() && $arr_data['display_on'] != '3') {//check if the current page is home page
                  return 1;
                } else if ($arr_data['display_on'] == '3' && !empty($arr_data['page_list']) && $post->post_type == 'page') {
                    /**
                     * check if the popup to display on only specific pages and
                     * and if the current page belongs on the pages list
                     */
                    if (in_array($post_id, $arr_data['page_list'])) {
                      return 1;
                    }
                    if (is_home()) {
                      return 0;
                    }
                  } else if ($arr_data['display_on'] == '2') {
                    /**
                     * check if the popup to display on all pages and
                     * current page is of 'page' type
                     */
                    return 1;
                  } else {
                    return 0; //return false if all conditions does not match
                  }
                }
              }

              function print_array($array) {
                echo "<pre style=\"background:#fff;\">";
                print_r($array);
                echo "</pre>";
              }

              function output_converting_br($text) {
                $text = implode("\n", explode("<br />", $text));
                return $text;
              }

              function sanitize_escaping_linebreaks($text) {
                $text = implode("<br />", explode("\n", $text));
                return $text;
              }

            }

    $wpb_object = new WPB_Class(); //initialization of plugin
  }

  // end of plugin




