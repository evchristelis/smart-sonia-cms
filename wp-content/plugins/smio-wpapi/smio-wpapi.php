<?php
/*
Plugin Name: WordPress API Complete Solution
Plugin URI: https://smartiolabs.com/product/wordpress-api-complete-solution
Description: Provides a complete solution for sharing any data in your Wordpress blog with high control on shared data and high security. It is dedicated to mobile, web developers and the template designers.
Author: Smart IO Labs
Version: 5.8.7
Author URI: https://smartiolabs.com
*/

define('smapi_dir', plugin_dir_path(__FILE__));
define('smapi_imgpath', plugins_url('/images', __FILE__));
define('smapi_csspath', plugins_url('/css', __FILE__));
define('smapi_jspath', plugins_url('/js', __FILE__));
define('smapi_env', 'production');
define('SMAPIVERSION', 5.87);
define('SMAPIDEMO', false);
define('SMAPI_MOBAPP_MODE', true);

require(smapi_dir.'/class.helper.php');
require(smapi_dir.'/class.controller.php');
require(smapi_dir.'/class.build.php');
require(smapi_dir.'/class.mobile.app.php');
require(smapi_dir.'/class.api.php');
require(smapi_dir.'/class.cron.php');
require(smapi_dir.'/class.auth.php');
require(smapi_dir.'/class.cache.php');
require(smapi_dir.'/class.geolocation.php');
require(smapi_dir.'/class.autoupdate.php');

register_activation_hook(__FILE__, 'smapi_install');
register_uninstall_hook(__FILE__, 'smapi_uninstall');

add_action('registered_taxonomy', 'smapi_before_age');
add_action('init', 'smapi_start');
add_action('wpmu_new_blog', 'smapi_new_blog_installed', 99, 6);
add_action('add_meta_boxes', array('smapi_mobapp', 'build_gps_meta_box'));
add_action('save_post', array('smapi_mobapp', 'save_gps_metakeys'), 10, 2);
add_filter('cron_schedules', array('smapi_controller', 'register_cron'));
add_filter('query_vars', array('smapi_controller', 'register_vars'));

function smapi_before_age(){
  $upload_dir = wp_upload_dir();

  $cache_path = $upload_dir['basedir'].'/smio_cache';
  $cache_config_path = $cache_path.'/config';

  if(! file_exists($cache_config_path)){
    return;
  }
  $cache_config = unserialize(file_get_contents($cache_config_path));
  if($cache_config['status'] == 0){
    return;
  }
  if(strpos($_SERVER['REQUEST_URI'], '/'.$cache_config['basename'].'/') !== false){
    preg_match('/'.$cache_config['basename'].'\/([A-Za-z]+)\//', $_SERVER['REQUEST_URI'], $endpoint);
    $endpoint = $endpoint[1];
    $endpointDir = $cache_config['endpoints'][$endpoint];
    if(! in_array($endpointDir, ['getposts','authors','getpages','categories'])){
      return;
    }
    if($endpoint == 'appBootstrape'){
      $hash_name = $endpoint;
    }
    else{
      $hash_name = md5($_SERVER['REQUEST_URI'].http_build_query($_POST, '', '&amp;'));
    }
    $cache_file_path = $cache_path.'/'.$endpointDir.'/'.$hash_name;
    if(file_exists($cache_file_path)){
      if($endpoint != 'appBootstrape' && (filemtime($cache_file_path)+($cache_config['expire']*3600)) < time()){
        @unlink($cache_file_path);
        return;
      }
      header('Access-Control-Allow-Origin: *');
      header('Content-Type: application/json');
      echo file_get_contents($cache_file_path);
      exit;
    }
  }
}

function smapi_start(){
  $smapi_version = get_option('smapi_version');
  if($smapi_version != SMAPIVERSION){
    smapi_upgrade($smapi_version);
  }

  $smapi_controller = new smapi_controller();
  $smapi_crons = new smapi_crons();
  $smapi_cache = new smapi_cache($smapi_controller->get_option('cache_status'), $smapi_controller->get_option('cache_expire'));
  $smapi_cache->set_cache_listener($smapi_controller->get_option('cache_listener'));
  $smapi_cache->start_hooks();

  add_action('pre_get_posts', array($smapi_controller, 'authentication'));
  add_action('template_redirect', array($smapi_controller, 'start_fetch_method'));
  add_action('deleted_user', array('smapi_core', 'delete_relw_app'));
  add_action('wpw_fp_follow_author_action', array('smapi_core', 'wpw_fp_followers_counter'), 1);
  add_action('admin_menu', array($smapi_controller, 'build_menus'));
  add_action('admin_enqueue_scripts', 'smapi_scripts');
  add_action('wp_loaded', 'smapi_flush_rules');
  add_action('smapi_cron_fewdays', array($smapi_controller, 'check_update_notify'));
  add_action('smapi_cron_daily', array($smapi_crons, 'cron_daily'));
  add_action('smapi_cron_monthly', array($smapi_crons, 'cron_monthly'));
  add_action('smapi_cron_cleaner', array($smapi_crons, 'cron_cleaner'));
}

function smapi_scripts(){
  wp_register_style('smapi-mainstyle', smapi_csspath.'/autoload-style.css', array(), SMAPIVERSION);
  wp_register_style('smapi-style', smapi_csspath.'/smio-style.css', array(), SMAPIVERSION);
  wp_register_style('smapi-style-selectize', smapi_csspath.'/selectize.css', array(), SMAPIVERSION);
  wp_register_style('smapi-style-labelauty', smapi_csspath.'/jquery-labelauty.css', array(), SMAPIVERSION);
  wp_register_style('smapi-style-switcher', smapi_csspath.'/switcher.css', array(), SMAPIVERSION);
  wp_register_script('smapi-mainscript', smapi_jspath.'/smio-function.js', array('jquery-core'), SMAPIVERSION);
  wp_register_script('smapi-js-plugins', smapi_jspath.'/smio-plugins.js', array('jquery-core'), SMAPIVERSION);
  wp_register_script('smapi-js-selectize', smapi_jspath.'/selectize.min.js', array('jquery-core','jquery-ui-sortable'), SMAPIVERSION);
  wp_register_script('smapi-js-labelauty', smapi_jspath.'/jquery-labelauty.js', array('jquery-core'), SMAPIVERSION);
  wp_register_script('smapi-mobile-settings', smapi_jspath.'/app-settings.js', array('jquery-core'), SMAPIVERSION);
  wp_enqueue_style('smapi-mainstyle');
  if(is_rtl()){
    wp_register_style('smapi-rtl', smapi_csspath.'/smio-style-rtl.css', array(), SMPUSHVERSION);
  }
  if(get_bloginfo('version') > 3.7){
    wp_register_style('smapi-fix38', smapi_csspath.'/autoload-style38.css', array(), SMAPIVERSION);
    wp_enqueue_style('smapi-fix38');
  }
}

function smapi_flush_rules(){
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}

function smapi_new_blog_installed($blog_id, $user_id, $domain, $path, $site_id, $meta) {
  $purchase_code = '';
  if(is_multisite()){
    $settings = get_option('smapi_options');
    $purchase_code = $settings['purchase_code'];
  }
  smapi_install_code($blog_id, $purchase_code);
}

function smapi_install(){
  global $wpdb;
  if(is_multisite()){
    $blogs = $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs");
    if($blogs){
      foreach($blogs as $blog){
        smapi_install_code($blog->blog_id);
      }
    }
  }
  else{
    smapi_install_code();
  }
}

function smapi_install_code($blog_id = false, $purchase_code=''){
  if(SMAPI_MOBAPP_MODE && is_multisite()){
    $network_authkey = get_option('smapi_network_authkey');
    if(!empty($network_authkey)){
      $network_authkey = get_option('smapi_network_authkey');
    }
    else{
      $network_authkey = smapi_helper::saltHash(25);
      update_option('smapi_network_authkey', $network_authkey);
    }
  }
  if($blog_id !== false){
    switch_to_blog($blog_id);
  }
  if(get_option('smapi_version') > 0){
    if($blog_id !== false){
      restore_current_blog();
    }
    return;
  }
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  require(smapi_dir.'/install.php');
}

function smapi_uninstall(){
  global $wpdb;
  if(is_multisite()){
    $blogs = $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs");
    if($blogs){
      foreach($blogs as $blog){
        switch_to_blog($blog->blog_id);
        smapi_uninstall_code();
      }
      restore_current_blog();
    }
  }
  else{
    smapi_uninstall_code();
  }
}

function smapi_uninstall_code(){
  global $wpdb;
  global $wp_rewrite;
  $wpdb->hide_errors();
  $wp_rewrite->flush_rules();
  $wpdb->query("DROP TABLE `".$wpdb->prefix."smapi_social_login`");
  $wpdb->query("DROP TABLE `".$wpdb->prefix."smapi_option`");
  $wpdb->query("DROP TABLE `".$wpdb->prefix."smapi_service`");
  $wpdb->query("DROP TABLE `".$wpdb->prefix."smapi_engine`");
  $wpdb->query("DROP TABLE `".$wpdb->prefix."smapi_auth_tokens`");
  $wpdb->query("DROP TABLE `".$wpdb->prefix."smapi_oauth_clients`");
  delete_option('smapi_options');
  delete_option('smapi_version');
  delete_option('smapi_network_authkey');
  wp_clear_scheduled_hook('smapi_cron_daily');
  wp_clear_scheduled_hook('smapi_cron_monthly');
  wp_clear_scheduled_hook('smapi_cron_fewdays');
}

function smapi_upgrade($version){
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  require(smapi_dir.'/upgrade.php');
}
