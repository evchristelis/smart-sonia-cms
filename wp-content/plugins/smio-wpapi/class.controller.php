<?php

class smapi_controller extends smapi_helper{
  public static $apisetting;
  public static $allScope;

  public function __construct(){
    header('Access-Control-Allow-Origin: *');
    self::$allScope = array('public', 'core', 'posts', 'publish_posts', 'comments', 'publish_comments', 'profiles', 'edit_profile', 'taxonomies', 'manage_posts', 'manage_comments');
    $this->checkHeaderSession();
    if(!session_id()) session_start();
    $this->get_api_setting();
    $this->add_rewrite_rules();
    $this->cron_setup();
    $this->generateCacheConfig();
  }

  public static function SecurityFilter(){
    if(isset($_REQUEST)){
      $_REQUEST = array_map('smapi_helper::Security', $_REQUEST);
    }
    if(isset($_POST)){
      $_POST = array_map('smapi_helper::Security', $_POST);
    }
    if(isset($_GET)){
      $_GET = array_map('smapi_helper::Security', $_GET);
    }
  }

  public static function setting(){
    if($_POST){
      if(SMAPIDEMO){
        echo 1;
        exit;
      }
      $checkbox = array('developer_mode');
      foreach($checkbox AS $inptname){
        if(!isset($_POST[$inptname]))
            self::$apisetting[$inptname] = 0;
      }
      self::saveOptions();
    }
    else{
      self::loadpage('setting', 1);
    }
  }

  public static function dev_setting(){
    if(isset($_GET['purge_cache'])){
      $cache = new smapi_cache(self::$apisetting['cache_status'], self::$apisetting['cache_expire']);
      $cache->purgeCache();
      wp_redirect($_SERVER['HTTP_REFERER']);
    }
    if($_POST){
      if(SMAPIDEMO){
        echo 1;
        exit;
      }
      $checkbox = array('acctoken_auth','visitor_can_post','oauth2_only','cache_status','cache_listener');
      foreach($checkbox AS $inptname){
        if(!isset($_POST[$inptname]))
            self::$apisetting[$inptname] = 0;
      }
      self::saveOptions();
    }
    else{
      self::loadpage('dev_setting', 1);
    }
  }

  public function generateCacheConfig(){
    $cache = new smapi_cache();
    $cache->generateConfig(self::$apisetting['api_basename'], self::$apisetting['cache_status'], self::$apisetting['cache_expire']);
  }

  public static function documentation(){
    include(smapi_dir.'/class.documentation.php');
    self::load_jsplugins();
    $document = new smapi_documentation();
    $document = $document->build(self::$allScope);
    $user = wp_get_current_user();
    $smapiexurl['api_base'] = site_url().'/'.self::$apisetting['api_basename'];
    include(smapi_dir.'/pages/documentation.php');
  }

  public static function load_custom_options(){
    global $wpdb;
    $coptions = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."smapi_option ORDER BY id ASC");
    if($coptions){
      include(smapi_dir.'/pages/option_load.php');
    }
  }

  public static function loadpage($template, $noheader=0, $params=0){
    self::load_jsplugins();
    $page_url = admin_url().'admin.php?page=smapi_'.$template.'&noheader='.$noheader;
    include(smapi_dir.'/pages/'.$template.'.php');
  }

  public static function saveOptions(){
    $newsetting = array();
    foreach($_POST AS $key=>$value){
      if($key != 'submit'){
        $newsetting[$key] = $value;
        unset(self::$apisetting[$key]);
      }
    }
    self::$apisetting = array_map('wp_slash', self::$apisetting);
    self::$apisetting = array_merge($newsetting, self::$apisetting);
    update_option('smapi_options', self::$apisetting);
    echo 1;
    die();
  }

  public static function load_jsplugins(){
    wp_enqueue_style('smapi-style-selectize');
    wp_enqueue_style('smapi-style-labelauty');
    wp_enqueue_style('smapi-style-switcher');
    wp_enqueue_style('thickbox');
    wp_enqueue_style('smapi-style');
    if(is_rtl()){
      wp_enqueue_style('smapi-rtl');
    }
    wp_enqueue_script('smapi-js-selectize');
    wp_enqueue_script('smapi-js-labelauty');
    wp_enqueue_script('smapi-mainscript');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-ui-accordion');
    wp_enqueue_script('smapi-js-plugins', array('jquery-ui-widget'));
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
  }

  public function build_menus(){
    add_menu_page('Wordpress API Settings', 'Wordpress API', 'delete_pages', 'smapi_setting', array('smapi_controller', 'setting'), 'div', 3);
    if(SMAPI_MOBAPP_MODE){
      add_submenu_page('smapi_setting', 'Mobile Application Settings', 'Mobile App', 'delete_pages', 'smapi_mobapp', array('smapi_mobapp', 'settings'));
    }
    if(is_multisite() && ! is_super_admin()){
      return;
    }
    add_submenu_page('smapi_setting', 'Engine Control', 'Engine Control', 'delete_pages', 'smapi_engine', array('smapi_build_custom', 'engine_services'));
    if($this->get_option('developer_mode') == 1){
      add_submenu_page('smapi_setting', 'Developer Settings', 'Developer Settings', 'delete_pages', 'smapi_dev_setting', array('smapi_controller', 'dev_setting'));
      add_submenu_page('smapi_setting', 'oAuth2 Clients', 'oAuth2 Clients', 'delete_pages', 'smapi_oauth', array('smapi_build_custom', 'oauth'));
      add_submenu_page('smapi_setting', 'Manage Scopes', 'Manage Scopes', 'delete_pages', 'smapi_scopes', array('smapi_build_custom', 'scopes'));
      add_submenu_page('smapi_setting', 'Build Custom Service', 'Build Service', 'delete_pages', 'smapi_service', array('smapi_build_custom', 'build_service'));
      add_submenu_page('smapi_setting', 'Create Custom Options', 'Create Options', 'delete_pages', 'smapi_coption', array('smapi_build_custom', 'creat_coptions'));
      add_submenu_page('smapi_setting', 'Developer Documentation', 'API Documentation', 'delete_pages', 'smapi_documentation', array('smapi_controller', 'documentation'));
    }
    add_submenu_page('smapi_setting', 'System Auto Update', 'Auto Update', 'delete_pages', 'smapi_autoupdate', array('smapi_autoupdate', 'auto_update'));
  }

  public static function register_cron($schedules){
    $schedules['smapi_monthly'] = array(
      'interval' => 2592000,
      'display' => __('Once Monthly')
    );
    $schedules['smapi_few_days'] = array(
      'interval' => 259200,
      'display' => __('Once every 3 days')
    );
    return $schedules;
  }

  public function cron_setup(){
    if(! wp_next_scheduled('smapi_cron_daily')){
      wp_schedule_event(mktime(3,0,0,date('m'),date('d'),date('Y')), 'daily', 'smapi_cron_daily');
	  }
    if(! wp_next_scheduled('smapi_cron_fewdays')){
      wp_schedule_event(mktime(15,0,0,date('m'),date('d'),date('Y')), 'smapi_few_days', 'smapi_cron_fewdays');
	  }
    if(! wp_next_scheduled('smapi_cron_monthly')){
      wp_schedule_event(mktime(0,0,0,date('m'),1,date('Y')), 'smapi_monthly', 'smapi_cron_monthly');
	  }
    if(get_transient('smapi_update_notice') !== false){
      add_action('admin_notices', array('smapi_controller', 'update_notice'));
    }
  }

  public function check_update_notify(){
    if(function_exists('curl_init')){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "http://smartiolabs.com/update/wp_api");
      curl_setopt($ch, CURLOPT_REFERER, 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
      $data = json_decode(curl_exec($ch));
      curl_close($ch);
      if($data !== NULL){
        if($data->version > SMAPIVERSION){
          set_transient('smapi_update_notice', $data, 60);
        }
      }
    }
  }

  public static function update_notice(){
    $data = get_transient('smapi_update_notice');
    delete_transient('smapi_update_notice');
    echo '<div class="update-nag"><p><a href="'.$data->link.'" target="_blank">'.$data->plugin.' '.$data->version.'</a> is available! Please update your system using the <a href="'.admin_url().'admin.php?page=smapi_autoupdate">auto update page</a>.</p></div>';
  }

  public function get_option($index){
    return self::$apisetting[$index];
  }

  public function get_api_setting(){
    self::$apisetting = get_option('smapi_options');
    self::$apisetting = stripslashes_deep(self::$apisetting);
  }

  public function authentication(){
    if($this->get_option('auth_type') == 'acctoken' || $this->get_option('auth_type') == 'oauth2'){
      $smapi_auth = new smapi_auth();
      $sent_access_token = smapi_helper::checkReqHeader('ACCESS_TOKEN');
      if(empty($sent_access_token)){
        $sent_access_token = trim(preg_replace('/bearer/i', '', smapi_helper::checkReqHeader('Authorization')));
      }
      if(!empty($sent_access_token)){
        $access_token = $smapi_auth::checkAccessToken($sent_access_token, false);
        if(!empty($access_token['userid'])){
          $user = get_user_by('id', $access_token['userid']);
          if(!session_id()) session_start();
          $_SESSION['smio_user_id'] = $user->ID;
          $_SESSION['smio_user_roles'] = $user->roles;

          wp_set_current_user($user->ID, $user->user_login);
          //wp_set_auth_cookie($user->ID);
        }
      }
    }
  }

  public function add_rewrite_rules(){
    $apiname = self::$apisetting['api_basename'];
    add_rewrite_rule($apiname.'/?$', 'index.php?smapicontrol=debug', 'top');
    add_rewrite_rule($apiname.'/(.+)$', 'index.php?smapicontrol=$matches[1]', 'top');
  }

  public function start_fetch_method(){
    $method = get_query_var('smapicontrol');
    if(!empty($method)){
      $smapi_method = new smapi_core($method);
    }
  }

  public static function register_vars($vars){
      $vars[] = 'smapicontrol';
      return $vars;
  }

}