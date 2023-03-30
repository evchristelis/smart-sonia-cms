<?php

class smapi_build_custom extends smapi_controller{

  public function __construct(){}

  public static function engine_services(){
    global $wpdb;
    self::load_jsplugins();
    $pageurl = admin_url().'admin.php?page=smapi_engine';
    if($_POST){
      if(isset($_POST['id'])){
        if(SMAPIDEMO){
          echo 1;
          exit;
        }
        if(isset($_POST['params'])){
          $params = unserialize($wpdb->get_var("SELECT params FROM ".$wpdb->prefix."smapi_engine WHERE id='$_POST[id]'"));
          foreach($params AS $key => $param){
            if(isset($_POST['params'][$key])){
              $params[$key]['active'] = 1;
            }
            else{
              $params[$key]['active'] = 0;
            }
          }
          $params = serialize($params);
        }
        else{
          $params = '';
        }
        if(!isset($_POST['access'])){
          $_POST['access'] = array('anyone');
        }
        $wpdb->update($wpdb->prefix.'smapi_engine', array('active'=>$_POST['active'], 'scope'=>$_POST['scope'], 'access_level'=>serialize($_POST['access']), 'params'=>$params), array('id'=>$_POST['id']));
      }
      elseif(isset($_POST['service'])){
        if(SMAPIDEMO){
          echo 1;
          exit;
        }
        $serviceid = implode(',', $_POST['service']);
        if($_POST['doaction'] == 'activate' OR $_POST['doaction2'] == 'activate')
          $wpdb->query("UPDATE ".$wpdb->prefix."smapi_engine SET active='1' WHERE id IN($serviceid)");
        elseif($_POST['doaction'] == 'deactivate' OR $_POST['doaction2'] == 'deactivate')
          $wpdb->query("UPDATE ".$wpdb->prefix."smapi_engine SET active='0' WHERE id IN($serviceid)");
        wp_redirect($pageurl);
      }
      echo 1;
      exit;
    }
    elseif(isset($_GET['id'])){
      if(is_numeric($_GET['id'])){
        $where = 'id';
      }
      else{
        $where = 'name';
      }
      $service = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."smapi_engine WHERE `$where`='$_GET[id]'", 'ARRAY_A');
      $service = array_map('stripslashes', $service);
      $service['params'] = unserialize($service['params']);
      $service['access_level'] = unserialize($service['access_level']);
      include(smapi_dir.'/pages/engine_form.php');
      exit;
    }
    else{
      $services = $wpdb->get_results("SELECT id,name,scope,description,active FROM ".$wpdb->prefix."smapi_engine ORDER BY id ASC");
      include(smapi_dir.'/pages/engine_manage.php');
    }
  }

  private static function generateKey($uniqueid){
    $saltHash = md5(base64_encode(pack('N6', mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), $uniqueid))).md5(base64_encode(pack('N6', mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), uniqid())));
    $maxTimes = ceil(strlen($saltHash)/3);
    $length = strlen($saltHash);
    for($i=1;$i<=$maxTimes;$i++){
      $pos = rand(0, $length);
      $saltHash = substr_replace($saltHash, strtoupper(substr($saltHash, 0, $pos)), 0, $pos);
    }
    return $saltHash;
  }

  public static function oauth(){
    global $wpdb;
    self::load_jsplugins();
    $pageurl = admin_url().'admin.php?page=smapi_oauth';
    if($_POST){
      if(empty($_POST['name']) OR empty($_POST['allowed_scopes'])){
        self::jsonPrint(0, 'Fields Name and Allowed Scope are required.');
      }
      $client = array(
        'req_usage' => 0,
        'quota' => $_POST['quota'],
        'status' => $_POST['status'],
      );
      $client['settings'] = array(
        'name' => $_POST['name'],
        'about' => $_POST['about'],
        'token_expire' => $_POST['token_expire'],
        'allowed_scopes' => $_POST['allowed_scopes'],
        'public_scopes' => $_POST['public_scopes'],
        'output_type' => $_POST['output_type'],
        'visitor_can_post' => $_POST['visitor_can_post'],
        'postlist_content' => $_POST['postlist_content'],
        'post_content' => $_POST['post_content'],
        'max_perpage' => $_POST['max_perpage'],
        'users_can_register' => $_POST['users_can_register'],
        'new_post_status' => $_POST['new_post_status'],
        'exclude_cats' => $_POST['exclude_cats'],
        'resize_image' => $_POST['resize_image'],
        'who_can_comment' => $_POST['who_can_comment'],
        'comment_moderation' => $_POST['comment_moderation'],
        'popular_range' => $_POST['popular_range'],
      );
      $client['settings'] = serialize($client['settings']);

      if($_POST['id'] > 0){
        unset($client['req_usage']);
        $wpdb->update($wpdb->prefix.'smapi_oauth_clients', $client, array('id' => $_POST['id']));
      }
      else{
        $wpdb->insert($wpdb->prefix.'smapi_oauth_clients', $client);
        $clientid = $wpdb->insert_id;
        $wpdb->update($wpdb->prefix.'smapi_oauth_clients', array('app_id' => ($clientid.rand(10000000,20000000)), 'auth_key' => self::generateKey($clientid)), array('id' => $clientid));
      }
      echo 1;
      exit;
    }
    elseif(isset($_GET['delete'])){
      $wpdb->query("DELETE FROM ".$wpdb->prefix."smapi_oauth_clients WHERE id='$_GET[id]'");
      wp_redirect($pageurl);
    }
    elseif(isset($_GET['id'])){
      if($_GET['id'] == -1){
        $client = array('id' => 0, 'quota' => 0, 'status' => 1);
        $client['settings'] = array(
          'name' => '',
          'about' => '',
          'token_expire' => 0,
          'allowed_scopes' => array('public','core','posts','publish_posts','comments','publish_comments','profiles','edit_profile','taxonomies','publish_posts','manage_posts','manage_comments'),
          'public_scopes' => array('public','posts','comments','profiles','taxonomies','core'),
          'output_type' => 'json',
          'visitor_can_post' => 0,
          'postlist_content' => 'html',
          'post_content' => 'html',
          'max_perpage' => 100,
          'users_can_register' => 2,
          'new_post_status' => 'pending',
          'exclude_cats' => '',
          'resize_image' => '98',
          'who_can_comment' => 'default',
          'comment_moderation' => 2,
          'popular_range' => 0,
          'quota' => 0,
          'status' => 0,
        );
      }
      else{
        $client = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."smapi_oauth_clients WHERE id='$_GET[id]'", 'ARRAY_A');
        $client = wp_unslash($client);
        $client['settings'] = unserialize($client['settings']);
      }
      include(smapi_dir.'/pages/oauth_form.php');
      exit;
    }
    else{
      $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."smapi_oauth_clients ORDER BY id DESC");
      foreach($results as $client){
        $client->settings = unserialize($client->settings);
        $clients[] = $client;
      }
      include(smapi_dir.'/pages/oauth_manage.php');
    }
  }

  public static function scopes(){
    self::load_jsplugins();
    $pageurl = admin_url().'admin.php?page=smapi_scopes';
    if($_POST){
      if(empty($_POST['name'])){
        self::jsonPrint(0, 'Field Name is required.');
      }
      $scope = str_replace(' ', '', $_POST['name']);
      array_push(self::$apisetting['oauth2scopes'], $scope);
      update_option('smapi_options', self::$apisetting);
      echo 1;
      exit;
    }
    elseif(isset($_GET['delete'])){
      global $wpdb;
      $bool = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."smapi_engine WHERE scope='$_GET[id]'");
      if($bool){
        echo 'This scope can not be deleted because it is linked with one of end-points';
        exit;
      }
      foreach(self::$apisetting['oauth2scopes'] as $key => $scope){
        if($scope == $_GET['id']){
          unset(self::$apisetting['oauth2scopes'][$key]);
          break;
        }
      }
      self::$apisetting['oauth2scopes'] = array_values(self::$apisetting['oauth2scopes']);
      update_option('smapi_options', self::$apisetting);
      echo 1;
      exit;
    }
    elseif(isset($_GET['id']) && $_GET['id'] == -1){
      include(smapi_dir.'/pages/scopes_form.php');
      exit;
    }
    else{
      include(smapi_dir.'/pages/scopes_manage.php');
    }
  }

  public static function build_service(){
    global $wpdb;
    self::load_jsplugins();
    $pageurl = admin_url().'admin.php?page=smapi_service';
    if($_POST){
      if((empty($_POST['name']) OR empty($_POST['query'])) AND $_POST['codetype'] == 'query'){
        self::jsonPrint(0, 'Fields Name and Query is required.');
      }
      elseif((empty($_POST['name']) OR empty($_POST['phpcode'])) AND $_POST['codetype'] == 'php'){
        self::jsonPrint(0, 'Fields Name and PHP Code is required.');
      }
      if(!isset($_POST['access'])){
        $_POST['access'] = array('anyone');
      }
      $count = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix."smapi_service WHERE name='$_POST[name]' AND id!='$_POST[id]'");
      if($count > 0){
        self::jsonPrint(0, 'Duplicate service name must be unique.');
      }
      if($_POST['codetype'] == 'php'){
        $_POST['paging'] = 'disable';
        $_POST['query'] = $_POST['phpcode'];
      }
      if($_POST['id'] > 0){
        $wpdb->update($wpdb->prefix.'smapi_service', array('name'=>$_POST['name'], 'query'=>$_POST['query'], 'codetype'=>$_POST['codetype'], 'description'=>$_POST['description'], 'paging'=>$_POST['paging'], 'access_level'=>serialize($_POST['access'])), array('id'=>$_POST['id']));
      }
      else{
        $wpdb->insert($wpdb->prefix.'smapi_service', array('name'=>$_POST['name'], 'query'=>$_POST['query'], 'codetype'=>$_POST['codetype'], 'description'=>$_POST['description'], 'paging'=>$_POST['paging'], 'access_level'=>serialize($_POST['access'])));
      }
      echo 1;
      exit;
    }
    elseif(isset($_GET['delete'])){
      $wpdb->query("DELETE FROM ".$wpdb->prefix."smapi_service WHERE id='$_GET[id]'");
      wp_redirect($pageurl);
    }
    elseif(isset($_GET['id'])){
      if($_GET['id'] == -1){
        $service = array('id'=>0, 'name'=>'', 'description'=>'', 'query'=>'', 'codetype' => 'query', 'paging'=>'', 'access_level' => array('anyone'));
      }
      else{
        $service = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."smapi_service WHERE id='$_GET[id]'", 'ARRAY_A');
        $service = array_map('stripslashes', $service);
        $service['access_level'] = unserialize($service['access_level']);
      }
      include(smapi_dir.'/pages/service_form.php');
      exit;
    }
    else{
      $services = $wpdb->get_results("SELECT id,name,description,paging FROM ".$wpdb->prefix."smapi_service ORDER BY id DESC");
      include(smapi_dir.'/pages/service_manage.php');
    }
  }

  public static function creat_coptions(){
    global $wpdb;
    self::load_jsplugins();
    $pageurl = admin_url().'admin.php?page=smapi_coption';
    if($_POST){
      if(empty($_POST['name']) OR empty($_POST['title'])){
        self::jsonPrint(0, 'Fields name and title is required.');
      }
      if($_POST['type']=='select' AND empty($_POST['values'])){
        self::jsonPrint(0, 'Field values of select menu is required.');
      }
      $count = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix."smapi_option WHERE name='$_POST[name]' AND id!='$_POST[id]'");
      if($count > 0){
        self::jsonPrint(0, 'Duplicate option name must be unique.');
      }
      $data = array('title'=>$_POST['title'], 'name'=>$_POST['name'], 'type'=>$_POST['type'], 'description'=>$_POST['description'], 'hint'=>$_POST['hint']);
      if($_POST['type'] == 'select'){
        if(empty($_POST['values'])){
          self::jsonPrint(0, 'Elements of select menu is required.');
        }
        $data['values'] = $_POST['values'];
      }
      if($_POST['id'] > 0){
        $wpdb->update($wpdb->prefix.'smapi_option', $data, array('id' => $_POST['id']));
      }
      else{
        $wpdb->insert($wpdb->prefix.'smapi_option', $data);
      }
      echo 1;
      exit;
    }
    elseif(isset($_GET['delete'])){
      $optname = 'co_'.$wpdb->get_var("SELECT name FROM ".$wpdb->prefix."smapi_option WHERE id='$_GET[id]'");
      $wpdb->query("DELETE FROM ".$wpdb->prefix."smapi_option WHERE id='$_GET[id]'");
      unset(self::$apisetting[$optname]);
      update_option('smapi_options', self::$apisetting);
      wp_redirect($pageurl);
    }
    elseif(isset($_GET['id'])){
      if($_GET['id'] == -1){
        $coption = array('id'=>0, 'type'=>'', 'title'=>'', 'name'=>'', 'description'=>'', 'hint'=>'', 'type'=>'', 'values'=>'');
      }
      else{
        $coption = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."smapi_option WHERE id='$_GET[id]'", 'ARRAY_A');
        $coption = array_map('stripslashes', $coption);
      }
      include(smapi_dir.'/pages/option_form.php');
      exit;
    }
    else{
      $coptions = $wpdb->get_results("SELECT id,title,name,description,type FROM ".$wpdb->prefix."smapi_option ORDER BY id DESC");
      include(smapi_dir.'/pages/option_manage.php');
    }
  }

  private static function custom_option_type($type){
    if($type == 'select'){
      return 'Select Menu';
    }
    elseif($type == 'text'){
      return 'Text Input';
    }
    elseif($type == 'textarea'){
      return 'Textarea';
    }
    else{
      return 'Number Input';
    }
  }

}

?>