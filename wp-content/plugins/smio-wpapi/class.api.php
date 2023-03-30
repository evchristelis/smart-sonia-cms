<?php

class smapi_core extends smapi_controller{
  public $counter = 0;
  public $dateformat;
  public $queryorder;
  public $error;
  private $read_params;
  private $exten_read_params;
  private $oAuthClient;
  private $cache;

  public function __construct($method, $args=false){
    $this->ParseOutput = true;
    $this->error = false;
    $this->oAuthClient = false;
    if($args !== false){
      foreach($args as $param=>$value){
        $_REQUEST[$param] = $value;
      }
      $this->functionInUse = true;
    }
    else{
      $this->functionInUse = false;
      $auth_key = $this->get_option('auth_key');
      $smapi_auth = new smapi_auth();
      if($this->get_option('maintenance_mode') == 1){
        return $this->output(0, $this->get_option('maintenance_msg'));
      }
      if($this->get_option('auth_type') == 'auth_key' && $smapi_auth::checkAuthKey($auth_key) === false){
        return $this->output(0, 'Authentication failed: Authentication key is required to proceed');
      }
      if($this->get_option('auth_type') == 'oauth2'){
        $sent_appid = smapi_helper::checkReqHeader('CONSUMER_KEY');
        $sent_authkey = smapi_helper::checkReqHeader('SECRET_KEY');
        $sent_access_token = smapi_helper::checkReqHeader('ACCESS_TOKEN');
        if(empty($sent_access_token)){
          $sent_access_token = trim(preg_replace('/bearer/i', '', smapi_helper::checkReqHeader('Authorization')));
        }
        if(!empty($sent_access_token)){
          $access_token = $smapi_auth::checkAccessToken($sent_access_token, true);
          if($access_token === false){
            header("HTTP/1.1 400 Unauthorized");
            return $this->output(0, 'Access token is invalid or expired');
          }
          elseif($access_token == -1){
            header("HTTP/1.1 401 Unauthorized");
            return $this->output(0, 'You have consumed the allowed API requests quota per month.');
          }
          elseif($access_token == -2){
            header("HTTP/1.1 400 Unauthorized");
            return $this->output(0, 'Access token is expired');
          }
          elseif($access_token == -3){
            header("HTTP/1.1 401 Unauthorized");
            return $this->output(0, 'This client cardinalities is disabled');
          }
          if(!empty($access_token['userid'])){
            $user = get_user_by('id', $access_token['userid']);
            if(!session_id()) session_start();
            $_SESSION['smio_user_id'] = $user->ID;
            $_SESSION['smio_user_roles'] = $user->roles;

            wp_set_current_user($user->ID, $user->user_login);
            wp_set_auth_cookie($user->ID);
          }
          $oauth2client = $access_token['oauth2client'];
        }
        else{
          $oauth2client = $smapi_auth::getOauthClient($sent_appid, $sent_authkey);
          if($oauth2client === false){
            header("HTTP/1.1 401 Unauthorized");
            return $this->output(0, 'Invalid oAuth 2.0 cardinalities');
          }
          elseif($oauth2client == -1){
            header("HTTP/1.1 401 Unauthorized");
            return $this->output(0, 'You have consumed the allowed API requests quota per month.');
          }
          elseif($oauth2client == -3){
            header("HTTP/1.1 401 Unauthorized");
            return $this->output(0, 'This client cardinalities is disabled');
          }
          $public_scopes = json_encode($oauth2client['settings']['public_scopes']);
          $access_token = array('scope' => $public_scopes);
        }
        $this->oAuthClient = $oauth2client;
        $apisettingOverride = $oauth2client['settings'];
        unset($apisettingOverride['name']);
        unset($apisettingOverride['about']);
        self::$apisetting = array_merge(self::$apisetting, $apisettingOverride);
      }
      elseif($this->get_option('auth_type') == 'acctoken'){
        $sent_access_token = smapi_helper::checkReqHeader('ACCESS_TOKEN');
        if(empty($sent_access_token)){
          $sent_access_token = trim(preg_replace('/bearer/i', '', smapi_helper::checkReqHeader('Authorization')));
        }
        if(!empty($sent_access_token)){
          $access_token = $smapi_auth::checkAccessToken($sent_access_token, false);
          if($access_token === false){
            header("HTTP/1.1 400 Unauthorized");
            return $this->output(0, 'Access token is invalid or expired');
          }
          if(!empty($access_token['userid'])){
            $user = get_user_by('id', $access_token['userid']);
            if(!session_id()) session_start();
            $_SESSION['smio_user_id'] = $user->ID;
            $_SESSION['smio_user_roles'] = $user->roles;

            wp_set_current_user($user->ID, $user->user_login);
            wp_set_auth_cookie($user->ID);
          }
        }
        else{
          if($smapi_auth::checkAuthKey($auth_key) === false){
            return $this->output(0, 'Authentication failed: Authentication key is required to proceed');
          }
          $public_scopes = json_encode($this->get_option('public_scopes'));
          $access_token = array('scope' => $public_scopes);
        }
      }
      else{
        $access_token = array('scope' => false);
      }
    }
    $this->dateformat = get_option('date_format');
    if(!isset($_REQUEST['orderby'])){
      $_REQUEST['orderby'] = '';
    }
    if(isset($_REQUEST['order'])){
      if(strtolower($_REQUEST['order']) == 'asc')
        $this->queryorder = 'ASC';
      elseif(strtolower($_REQUEST['order']) == 'desc')
        $this->queryorder = 'DESC';
      else
        $this->queryorder = false;
    }

    $this->cache = ['method' => $method, 'cache_status' => self::$apisetting['cache_status'], 'cache_expire' => self::$apisetting['cache_expire']];

    if(! function_exists('is_plugin_active')){
      include(ABSPATH.'/wp-admin/includes/plugin.php');
    }

    if(method_exists($this, $method)){
      $this->fetchMethod($method, $access_token['scope']);
      if(!empty($_REQUEST['siteid'])){
        if(function_exists('switch_to_blog')){
          switch_to_blog($_REQUEST['siteid']);
        }
        else{
          return $this->output(0, 'WordPress multisite feature is not enabled');
        }
      }
      try{
        $this->$method();
      }catch(Exception $e){
        $this->error = $e->getMessage();
      }
    }
    else{
      return $this->output(0, 'You called unavailable service `'.$method.'`');
    }
  }

  public function appBootstrape(){
    define('SMAPI_RETURN_OUTPUT_START', true);
    $gallery = array();
    for($i=1;$i<=4;$i++){
      if(!empty(self::$apisetting['mob_contact_photo'.$i])){
        array_push($gallery, self::$apisetting['mob_contact_photo'.$i]);
      }
    }
    if(empty($gallery)){
      $gallery = false;
    }
    $json = array();
    $json['homepage'] = array(
      'autogps' => self::$apisetting['mob_common_gps'],
      'autopush' => self::$apisetting['mob_common_push'],
      'categories' => (empty(self::$apisetting['mob_home_catmetro']))? false : $this->categories('ids', self::$apisetting['mob_home_catids'], false, self::$apisetting['mob_cat_post_type_tax']),
      'posts' => (empty(self::$apisetting['mob_home_recent']))? false : $this->getposts('last'),
      'popular' => (empty(self::$apisetting['mob_home_popular']))? false : $this->popular_posts(),
      'banner' => array('title' => self::$apisetting['mob_headtitle'], 'image' => self::$apisetting['mob_home_cover']),
      'ios_ad_status' => self::$apisetting['mob_home_iosads'],
      'android_ad_status' => self::$apisetting['mob_home_andads'],
      'test_ad' => self::$apisetting['mob_debug_ads'],
    );
    $_REQUEST['custom_post'] = 'page';
    $json['menu'] = array(
      'categories' => (empty(self::$apisetting['mob_categories']))? array() : $this->categories('ids', explode(',', self::$apisetting['mob_categories']), false, self::$apisetting['mob_cat_post_type_tax']),
      'pages' => (empty(self::$apisetting['mob_pages']))? array() : $this->getposts('inlist', explode(',', self::$apisetting['mob_pages']), false, false),
      'nearby' => self::$apisetting['mob_menu_nearby'],
      'follow' => self::$apisetting['mob_menu_follow'],
      'subspage' => self::$apisetting['mob_menu_subscription'],
      'notifHistory' => self::$apisetting['mob_menu_notfhistory'],
      'contact' => self::$apisetting['mob_menu_contactus'],
      'contactdata' => array(
        'gallery' => $gallery,
        'name' => self::$apisetting['mob_contact_name'],
        'subtitle' => self::$apisetting['mob_contact_desc'],
        'rating' => self::$apisetting['mob_contact_rating'],
        'phone' => self::$apisetting['mob_contact_phone'],
        'website' => self::$apisetting['mob_contact_website'],
        'email' => self::$apisetting['mob_contact_email'],
        'map' => (empty(self::$apisetting['mob_contact_lat']))? '' : 'https://maps.googleapis.com/maps/api/staticmap?key='.self::$apisetting['mob_gmaps_apikey'].'&center='.self::$apisetting['mob_contact_lat'].','.self::$apisetting['mob_contact_lng'].'&zoom=15&size=400x300&scale=2&markers=icon:https://dl.dropboxusercontent.com/s/9ajgkiuni9hymgq/pin.min.png|'.self::$apisetting['mob_contact_lat'].','.self::$apisetting['mob_contact_lng'],
        'address' => self::$apisetting['mob_contact_address']
      ),
    );
    unset($_REQUEST['custom_post']);
    $json['postfeeds'] = array(
      'style' => self::$apisetting['mob_feeds_style'],
      'featuredImage' => self::$apisetting['mob_feeds_fimage'],
      'postContent' => (self::$apisetting['mob_feeds_contsource'] == 'contents')? 1 : 0,
      'excerpt' => (self::$apisetting['mob_feeds_contsource'] == 'excerpt')? 1 : 0,
    );
    $json['postview'] = array(
      'featuredImage' => self::$apisetting['mob_post_fimage'],
      'comments' => self::$apisetting['mob_post_showcomms'],
      'addComment' => self::$apisetting['mob_post_addcomms'],
      'showAuthor' => self::$apisetting['mob_post_author'],
      'showCats' => self::$apisetting['mob_post_categories'],
      'ios_ad_status' => self::$apisetting['mob_post_iosads'],
      'android_ad_status' => self::$apisetting['mob_post_andads'],
      'ios_ad_id' => self::$apisetting['mob_common_iosadid'],
      'android_ad_id' => self::$apisetting['mob_common_andadid'],
      'adtype' => self::$apisetting['mob_common_adtype'],
      'test_ad' => self::$apisetting['mob_debug_ads'],
    );
    $json['common'] = array(
      'ios_appid' => self::$apisetting['mob_common_iosappid'],
      'android_appid' => self::$apisetting['mob_common_andappid'],
      'ios_ad_id' => self::$apisetting['mob_common_iosadid'],
      'android_ad_id' => self::$apisetting['mob_common_andadid'],
      'windows_appid' => self::$apisetting['mob_common_winappid'],
      'adtype' => self::$apisetting['mob_common_adtype'],
      'test_ad' => self::$apisetting['mob_debug_ads'],
    );
    $json['timeout'] = self::$apisetting['mob_cache_expire']*60;
    define('SMAPI_RETURN_OUTPUT_END', true);
    return $this->output(1, $json, $this->cache);
  }

  public function lostpwd(){
    $this->CheckParams(array('username','email'), true);
    global $wpdb;
    if(!empty($_REQUEST['username']))
      $userinfo = get_user_by('login', $_REQUEST['username']);
    if(!empty($_REQUEST['email']))
      $userinfo = get_user_by('email', $_REQUEST['email']);
    if(empty($userinfo)) return $this->output(0, 'Sorry, Did not find user with this entry');
    else $user_login = $userinfo->user_login;

    $key = wp_generate_password(20, false);
    if(empty($wp_hasher)){
      require_once ABSPATH . 'wp-includes/class-phpass.php';
      $wp_hasher = new PasswordHash( 8, true );
    }
    $hashed = current_time('timestamp').':'.$wp_hasher->HashPassword($key);
    $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));

    $message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
    $message .= network_home_url( '/' ) . "\r\n\r\n";
    $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
    $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
    $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
    $message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n";

    if(is_multisite())
      $blogname = $GLOBALS['current_site']->site_name;
    else
      $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
    $headers = 'From: '.$blogname.' <'.get_option('admin_email').'>' . "\r\n";

    $title = sprintf( __('[%s] Password Reset'), $blogname );
    $title = apply_filters('retrieve_password_title', $title);
    $user_email = $wpdb->get_var($wpdb->prepare("SELECT user_email FROM $wpdb->users WHERE user_login=%s", $user_login));
    $message = apply_filters('retrieve_password_message', $message, $key);
    //if ( $message && !wp_mail($user_email, $title, $message, $headers) )
      //return $this->output(0, 'The E-mail could not be sent may be your host disabled the mail() public function');
    $wpmail = wp_mail($user_email, $title, $message);
    if(is_wp_error($wpmail)) return $this->output(0, $wpmail->get_error_message());
    return $this->output(1, 'E-mail sent successfully');
  }

  public function request_token(){
    $this->CheckParams(array('scope'));
    $userid = (!empty($_SESSION['smio_user_id']))? $_SESSION['smio_user_id'] : 0;
    $access_token = smapi_auth::generateAccessToken($userid, $_REQUEST['scope'], $this->oAuthClient);
    if($access_token === false){
      return $this->output(0, 'not allowed scope');
    }
    return $this->output(1, $access_token);
  }

  public function refresh_token(){
    $this->CheckParams(array('scope'));
    $userid = (!empty($_SESSION['smio_user_id']))? $_SESSION['smio_user_id'] : 0;
    $access_token = smapi_auth::generateAccessToken($userid, $_REQUEST['scope'], $this->oAuthClient, true);
    if($access_token === false){
      return $this->output(0, 'not allowed scope');
    }
    return $this->output(1, $access_token);
  }

  public function login(){
    $this->CheckParams(array('username','password'));
    $logininfo = array(
    'user_login' => $_REQUEST['username'],
    'user_password' => $_REQUEST['password']
    );
    if(!empty($_REQUEST['remember'])){
      $logininfo['remember'] = true;
    }
    $user = wp_signon($logininfo, false);
    if(is_wp_error($user)) return $this->output(0, 'Sorry, Enter wrong username or password');
    else{
      if(is_multisite()){
        if(is_user_member_of_blog($user->ID, $_REQUEST['siteid']) === false){
          return $this->output(0, 'Sorry, The user is not a member of the given blog');
        }
      }
      $userid = $user->ID;
      $_SESSION['smio_user_id'] = $userid;
      $_SESSION['smio_user_roles'] = $user->roles;
      if(!empty($_REQUEST['device_token']) && !empty($_REQUEST['device_type'])){
        $this->savetoken(false);
      }
      $userinfo = $this->authors('one', $userid, false);
      if(!empty($_REQUEST['scope'])){
        $access_token = smapi_auth::generateAccessToken($userid, $_REQUEST['scope'], $this->oAuthClient);
        if($access_token === false){
          return $this->output(0, 'not allowed scope');
        }
        $userinfo[0]['Access_Token'] = $access_token['token'];
        $userinfo[0]['access_token_data'] = $access_token;
      }
      return $this->output(1, $userinfo);
    }
  }

  public function logout(){
    $this->MustLogin();
    wp_logout();
    unset($_SESSION['smio_user_id']);
    unset($_SESSION['smio_user_roles']);
    return $this->output(1, 'Successfully logout');
  }

  public function signup(){
    $this->CheckParams(array('username','password','email'));
    if((get_option('users_can_register') == 0 AND $this->get_option('users_can_register') == 2) OR $this->get_option('users_can_register') == 0){
      return $this->output(0, 'Sorry, Registeration is closed');
    }
    if(!is_email($_REQUEST['email'])) return $this->output(0, 'E-mail address not valid');
    if(!empty($_REQUEST['user_url'])){
      if(filter_var($_REQUEST['user_url'], FILTER_VALIDATE_URL) === FALSE){
        return $this->output(0, 'URL is not valid');
      }
    }
    if(!empty($_REQUEST['thumbnail_id'])){
      $this->CheckParams(array('userimg_fieldkey'));
    }
    if(!empty($_FILES['file']['name'])){
      $this->CheckParams(array('userimg_fieldkey'));
      $_REQUEST['size'] = (empty($_REQUEST['size']))?'thumbnail':$_REQUEST['size'];
      $profileimg = $this->upload_media(false);
      if($profileimg !== false){
        $_REQUEST['thumbnail_id'] = $profileimg['media_id'];
      }
    }
    $userdata = array(
    'user_login' => $_REQUEST['username'],
    'user_pass' => $_REQUEST['password'],
    'user_nicename' => $_REQUEST['username'],
    'user_email' => $_REQUEST['email'],
    'display_name' => (empty($_REQUEST['display_name']))? $_REQUEST['username']: $_REQUEST['display_name'],
    'user_url' => (!empty($_REQUEST['user_url']))? $_REQUEST['user_url'] : '',
    'first_name' => (!empty($_REQUEST['first_name']))? $_REQUEST['first_name'] : '',
    'last_name' => (!empty($_REQUEST['last_name']))? $_REQUEST['last_name'] : '',
    'description' => (!empty($_REQUEST['description']))? $_REQUEST['description'] : '',
    'role' => (!empty($_REQUEST['role']))? $_REQUEST['role'] : 'subscriber',
    );
    $user = wp_insert_user($userdata);
    if(is_wp_error($user)) return $this->output(0, $user->get_error_message());
    else{
      do_action( 'smapi_after_user_register', $user->ID );
      $logininfo = array(
      'user_login' => $_REQUEST['username'],
      'user_password' => $_REQUEST['password']
      );
      $user = wp_signon($logininfo, false);
      if(is_wp_error($user)) return $this->output(0, $user->get_error_message());
      $userid = $user->ID;
      $_SESSION['smio_user_id'] = $user->ID;
      $_SESSION['smio_user_roles'] = $user->roles;
      if(!empty($_REQUEST['token'])){
        global $wpdb;
        $wpdb->query("INSERT INTO ".$wpdb->base_prefix."smapi_social_login (userid,social_id,token,social_type) VALUES ('$userid','$_REQUEST[socialid]','$_REQUEST[token]','$_REQUEST[socialtype]')");
      }
      if(!empty($_REQUEST['device_token']) && !empty($_REQUEST['device_type'])){
        $this->savetoken(false);
      }
      if(!empty($_REQUEST['thumbnail_id'])){
        update_field($_REQUEST['userimg_fieldkey'], $_REQUEST['thumbnail_id'], 'user_'.$userid);
      }
      if(!empty($_REQUEST['custom_meta'])){
        $custom_meta = json_decode(stripslashes($_REQUEST['custom_meta']), true);
        if(isset($custom_meta)){
          foreach($custom_meta as $key=>$meta){
            update_user_meta($userid, $key, wp_slash($meta));
          }
        }
      }
      if(!empty($_REQUEST['custom_field'])){
        if(!function_exists('update_field')){
          return $this->output(0, 'ACF plugin needs to be enabled, Back to documentation for further information');
        }
        $custom_field = json_decode(stripslashes($_REQUEST['custom_field']), true);
        if(isset($custom_field)){
          foreach($custom_field as $fieldkey=>$value){
            update_field($fieldkey, wp_slash($value), 'user_'.$userid);
          }
        }
      }
      $userinfo = $this->authors('one', $userid, false);
      if(!empty($_REQUEST['scope'])){
        $access_token = smapi_auth::generateAccessToken($userid, $_REQUEST['scope'], $this->oAuthClient);
        if($access_token === false){
          return $this->output(0, 'not allowed scope');
        }
        $userinfo[0]['Access_Token'] = $access_token['token'];
        $userinfo[0]['access_token_data'] = $access_token;
      }
      return $this->output(1, $userinfo);
    }
  }

  public function edit_profile(){
    $this->MustLogin();
    if(!empty($_REQUEST['email']) && !is_email($_REQUEST['email'])) return $this->output(0, 'E-mail address not valid');
    if(!empty($_REQUEST['user_url'])){
      if(filter_var($_REQUEST['user_url'], FILTER_VALIDATE_URL) === FALSE){
        return $this->output(0, 'URL is not valid');
      }
    }
    if(!empty($_REQUEST['thumbnail_id'])){
      $this->CheckParams(array('userimg_fieldkey'));
    }
    if(!empty($_FILES['file']['name'])){
      $this->CheckParams(array('userimg_fieldkey'));
      $_REQUEST['size'] = (empty($_REQUEST['size']))?'thumbnail':$_REQUEST['size'];
      $profileimg = $this->upload_media(false);
      if($profileimg !== false){
        $_REQUEST['thumbnail_id'] = $profileimg['media_id'];
      }
    }
    if(!empty($_POST['file64'])){
      $upload_dir = wp_upload_dir();
      $avatar_folder = '/avatars/'.$_SESSION['smio_user_id'].'/'.$_SESSION['smio_user_id'].'-'.rand(1000,9000);
      $_POST['file64'] = explode('base64,', $_POST['file64']);
      $imgdata = base64_decode(str_replace(' ', '+', $_POST['file64'][1]));
      if(strpos($_POST['file64'][0], 'image/png') !== false){
        $ext = '.png';
      }
      else{
        $ext = '.jpeg';
      }
      $temp_file = $upload_dir['basedir'].'/userpic_'.$_SESSION['smio_user_id'].$ext;
      $handle = fopen($temp_file, "w");
      fwrite($handle, $imgdata);
      fclose($handle);

      $image = wp_get_image_editor($temp_file);
      if(! is_wp_error($image)){
        $image->resize(300, 300, true);
        $image->save($upload_dir['basedir'].$avatar_folder.'bpfull'.$ext);

        $image->resize(150, 150, true);
        $image->save($upload_dir['basedir'].$avatar_folder.'bpmedium'.$ext);

        $image->resize(50, 50, true);
        $image->save($upload_dir['basedir'].$avatar_folder.'bpthumb'.$ext);

        update_user_meta($_SESSION['smio_user_id'], 'smio_avatar', $avatar_folder.'bpfull'.$ext);
      }
      unlink($temp_file);
    }
    $userdata = array(
    'ID' => $_SESSION['smio_user_id']
    );
    if(!empty($_REQUEST['username'])){
      $userdata['user_login'] = $_REQUEST['username'];
    }
    if(!empty($_REQUEST['email'])){
      $userdata['user_email'] = $_REQUEST['email'];
    }
    if(!empty($_REQUEST['display_name'])){
      $userdata['display_name'] = $_REQUEST['display_name'];
    }
    if(!empty($_REQUEST['password'])){
      $userdata['user_pass'] = $_REQUEST['password'];
    }
    if(!empty($_REQUEST['user_url'])){
      $userdata['user_url'] = $_REQUEST['user_url'];
    }
    if(!empty($_REQUEST['first_name'])){
      $userdata['first_name'] = $_REQUEST['first_name'];
    }
    if(!empty($_REQUEST['last_name'])){
      $userdata['last_name'] = $_REQUEST['last_name'];
    }
    if(!empty($_REQUEST['description'])){
      $userdata['description'] = $_REQUEST['description'];
    }
    $user = wp_update_user($userdata);
    if(is_wp_error($user)) return $this->output(0, $user->get_error_message());
    else{
      $userid = $_SESSION['smio_user_id'];
      if(!empty($_REQUEST['thumbnail_id'])){
        update_field($_REQUEST['userimg_fieldkey'], $_REQUEST['thumbnail_id'], 'user_'.$userid);
      }
      if(!empty($_REQUEST['custom_meta'])){
        $custom_meta = json_decode(stripslashes($_REQUEST['custom_meta']), true);
        if(isset($custom_meta)){
          foreach($custom_meta as $key=>$meta){
            update_user_meta($userid, $key, wp_slash($meta));
          }
        }
      }
      if(!empty($_REQUEST['custom_field'])){
        if(!function_exists('update_field')){
          return $this->output(0, 'ACF plugin needs to be enabled, Back to documentation for further information');
        }
        $custom_field = json_decode(stripslashes($_REQUEST['custom_field']), true);
        if(isset($custom_field)){
          foreach($custom_field as $fieldkey=>$value){
            update_field($fieldkey, wp_slash($value), 'user_'.$userid);
          }
        }
      }
      return $this->output(1, 'Author profile updated successfully');
    }
  }

  public function social(){
    $this->CheckParams(array('username','socialid','token','socialtype'));
    global $wpdb;
    $userid = $wpdb->get_var("SELECT userid FROM ".$wpdb->base_prefix."smapi_social_login WHERE social_id='$_REQUEST[socialid]' AND social_type='$_REQUEST[socialtype]'");
    if(empty($userid)){
      $_REQUEST['password'] = time().rand(1000,2000);
      if(empty($_REQUEST['email'])){
        $_REQUEST['email'] = str_replace(' ', '_', $_REQUEST['username']).rand(1000,2000).'@sociallogin.com';
      }
      $this->signup();
    }
    else{
      $wpdb->query("UPDATE ".$wpdb->base_prefix."smapi_social_login SET token='$_REQUEST[token]' WHERE userid='$userid'");
    }
    if(is_multisite()){
      if(is_user_member_of_blog($userid, $_REQUEST['siteid']) === false){
        return $this->output(0, 'Sorry, The user is not a member of the given blog');
      }
    }
    $user = get_userdata($userid);
    $_SESSION['smio_user_id'] = $userid;
    $_SESSION['smio_user_roles'] = $user->roles;
    if(!empty($_REQUEST['device_token']) && !empty($_REQUEST['device_type'])){
      $this->savetoken(false);
    }
    $userinfo = $this->authors('one', $userid, false);
    if(!empty($_REQUEST['scope'])){
      $access_token = smapi_auth::generateAccessToken($userid, $_REQUEST['scope'], $this->oAuthClient);
      if($access_token === false){
        return $this->output(0, 'not allowed scope');
      }
      $userinfo[0]['Access_Token'] = $access_token['token'];
      $userinfo[0]['access_token_data'] = $access_token;
    }
    return $this->output(1, $userinfo);
  }

  public function profile_image(){
    $this->CheckParams(array('userimg_fieldkey'));
    $this->MustLogin();
    if(!empty($_FILES['file']['name'])){
      $_REQUEST['size'] = (empty($_REQUEST['size']))?'thumbnail':$_REQUEST['size'];
      $profileimg = $this->upload_media(false);
      if($profileimg !== false){
        $_REQUEST['thumbnail_id'] = $profileimg['media_id'];
      }
    }
    if(!empty($_REQUEST['thumbnail_id'])){
      update_field($_REQUEST['userimg_fieldkey'], $_REQUEST['thumbnail_id'], 'user_'.$_SESSION['smio_user_id']);
    }
    else{
      $this->CheckParams(array('thumbnail_id'));
    }
    return $this->output(1, 'Profile image changed successfully');
  }

  public function changepwd(){
    $this->CheckParams(array('password'));
    $this->MustLogin();
    if(!empty($_REQUEST['oldpassword'])){
      global $wpdb;
      $user_pass = $wpdb->get_var("SELECT user_pass FROM $wpdb->users WHERE ID='$_SESSION[smio_user_id]'");
      if(!wp_check_password($_REQUEST['oldpassword'], $user_pass, $_SESSION['smio_user_id'])){
        return $this->output(0, 'Password is wrong');
      }
    }
    wp_set_password($_REQUEST['password'], $_SESSION['smio_user_id']);
    return $this->output(1, 'Password changed successfully');
  }

  public function like_post(){
    global $wpdb;

    $this->MustLogin();
    $this->CheckParams(array('postid'));

    $isLiked = $wpdb->get_row("SELECT id,`value` FROM ".$wpdb->prefix."wti_like_post WHERE user_id='$_SESSION[smio_user_id]' AND post_id='$_REQUEST[postid]'");
    if($isLiked){
      $wpdb->update($wpdb->prefix.'wti_like_post', array('ip' => $_SERVER['REMOTE_ADDR'], 'value' => ($isLiked->value+1)), ['id' => $isLiked->id]);
    }
    else{
      $wpdb->insert($wpdb->prefix.'wti_like_post', array('user_id' => $_SESSION['smio_user_id'], 'post_id' => $_REQUEST['postid'], 'ip' => $_SERVER['REMOTE_ADDR'], 'value' => 1));
    }

    $post = get_post($_REQUEST['postid']);
    do_action('post_updated', $_REQUEST['postid'], $post, $post);

    return $this->output(1, 'Post has been liked successfully');
  }

  public function follow_author(){
    global $wpdb;
    $this->CheckParams(array('authorid'));
    $this->MustLogin();
    if(is_plugin_active('follow-my-blog-post/follow-my-blog-post.php')){
      $isfollowed = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."um_followers WHERE user_id2='$_SESSION[smio_user_id]' AND user_id1='$_REQUEST[authorid]'");
    }
    else{
      $isfollowed = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."smapi_author_followers WHERE userid='$_SESSION[smio_user_id]' AND authorid='$_REQUEST[authorid]'");
    }
    if($isfollowed){
      return $this->output(0, 'Already followed this author');
    }
    if(is_plugin_active('follow-my-blog-post/follow-my-blog-post.php')){
      $wpdb->insert($wpdb->prefix.'um_followers', array('user_id2' => $_SESSION['smio_user_id'], 'user_id1' => $_REQUEST['authorid']));

      $followers = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."um_followers WHERE user_id1='$_REQUEST[authorid]'");
      update_user_meta($_REQUEST['authorid'], 'smio_followers', $followers);

      $my_post = array(
        'post_title'    => $_SESSION['smio_user_id'],
        'post_content'  => ' ',
        'post_status'   => 'publish',
        'post_type'   => 'wpwfollowauthor',
        'post_author'   => $_SESSION['smio_user_id'],
        'post_parent' => $_REQUEST['authorid']
      );
      $postid = wp_insert_post( $my_post );
      $current_user = wp_get_current_user();
      update_post_meta($postid, WPW_FP_META_PREFIX.'follow_status', 1);
      update_post_meta($postid, WPW_FP_META_PREFIX.'post_user_email', $current_user->user_email);
    }
    else{
      $wpdb->insert($wpdb->prefix.'smapi_author_followers', array('userid' => $_SESSION['smio_user_id'], 'authorid' => $_REQUEST['authorid']));

      $followers = get_user_meta($_REQUEST['authorid'], 'smio_followers', true);
      if(empty($followers)){
        $followers = 0;
      }
      update_user_meta($_REQUEST['authorid'], 'smio_followers', ($followers+1));
    }

    return $this->output(1, 'Author has been followed successfully');
  }

  public function unfollow_author(){
    global $wpdb;
    $this->CheckParams(array('authorid'));
    $this->MustLogin();
    if(is_plugin_active('follow-my-blog-post/follow-my-blog-post.php')){
      $wpdb->query("DELETE FROM ".$wpdb->prefix."um_followers WHERE user_id2='$_SESSION[smio_user_id]' AND user_id1='$_REQUEST[authorid]'");
      if($wpdb->rows_affected > 0){
        $followers = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."um_followers WHERE user_id1='$_REQUEST[authorid]'");
        if(!empty($followers)){
          update_user_meta($_REQUEST['authorid'], 'smio_followers', $followers);
        }

        $isFollowed = $wpdb->get_var("SELECT ID FROM ".$wpdb->posts." WHERE post_author='$_SESSION[smio_user_id]' AND post_type='wpwfollowauthor' AND post_parent='$_REQUEST[authorid]'");
        if(! $isFollowed){
          $wpdb->query("DELETE FROM ".$wpdb->posts." WHERE ID='$isFollowed'");
          $wpdb->query("DELETE FROM ".$wpdb->postmeta." WHERE post_id='$isFollowed'");
        }
      }
    }
    else{
      $wpdb->query("DELETE FROM ".$wpdb->prefix."smapi_author_followers WHERE userid='$_SESSION[smio_user_id]' AND authorid='$_REQUEST[authorid]'");
      if($wpdb->rows_affected > 0){
        $followers = get_user_meta($_REQUEST['authorid'], 'smio_followers', true);
        if(!empty($followers)){
          update_user_meta($_REQUEST['authorid'], 'smio_followers', ($followers-1));
        }
      }
    }

    return $this->output(1, 'Author has been unfollowed successfully');
  }

  public function savetoken($output=true){
    if(class_exists('smpush_api')){
      $this->CheckParams(array('device_token','device_type'));
      if(!empty($_SESSION['smio_user_id'])) $_REQUEST['user_id'] = $_SESSION['smio_user_id'];
      else $_REQUEST['user_id'] = 0;
      $push = new smpush_api('savetoken', true);
      $staticResult = $push->fetchPrintResult();
      if($output) return $this->output($staticResult['respond'], $staticResult['result']);
      else return true;
    }
    else{
      if($output) return $this->output(0, 'Plugin `Mobile Push Notification` is required to proceed.');
      else return true;
    }
  }

  public function channels_subscribe(){
    if(class_exists('smpush_api')){
      $this->CheckParams(array('device_token','device_type','channels_id'));
      $push = new smpush_api('channels_subscribe', true);
      $staticResult = $push->fetchPrintResult();
      return $this->output($staticResult['respond'], $staticResult['result']);
    }
    else{
      return $this->output(0, 'Plugin `Mobile Push Notification` is required to proceed.');
    }
  }

  public function device_channels(){
    if(class_exists('smpush_api')){
      $this->CheckParams(array('device_token','device_type'));
      $push = new smpush_api('device_channels', true);
      $staticResult = $push->fetchPrintResult();
      return $this->output($staticResult['respond'], $staticResult['result']);
    }
    else{
      return $this->output(0, 'Plugin `Mobile Push Notification` is required to proceed.');
    }
  }

  public function get_channels(){
    if(class_exists('smpush_api')){
      $push = new smpush_api('get_channels', true);
      $staticResult = $push->fetchPrintResult();
      return $this->output($staticResult['respond'], $staticResult['result']);
    }
    else{
      return $this->output(0, 'Plugin `Mobile Push Notification` is required to proceed.');
    }
  }

  public function newcomment(){
    if(!empty($_SESSION['smio_user_id'])){
      $authorid = $_SESSION['smio_user_id'];
      $userinfo = get_userdata($authorid);
      $author = $userinfo->user_login;
      $email = $userinfo->user_email;
      $url = $userinfo->user_url;
      $this->CheckParams(array('post_id','content'));
    }
    else{
      $cancomment = $this->get_option('who_can_comment');
      if($cancomment == 'usersonly') $this->MustLogin();
      elseif($cancomment == 'default'){
        if(get_option('comment_registration') == 1) $this->MustLogin();
      }
      $this->CheckParams(array('post_id','content','author','email'));
      $author = $_REQUEST['author'];
      $email = $_REQUEST['email'];
      $url = $_REQUEST['url'];
      $authorid = 0;
    }
    $commentstatus = $this->get_option('comment_moderation');
    if($commentstatus == 2){
      if(get_option('comment_moderation') == 1) $commentstatus = 0;
      else $commentstatus = 1;
    }
    $data = array(
    'comment_post_ID' => $_REQUEST['post_id'],
    'comment_author' => $author,
    'comment_author_email' => $email,
    'comment_author_url' => $url,
    'comment_content' => $_REQUEST['content'],
    'comment_parent' => (!empty($_REQUEST['parentid']))?$_REQUEST['parentid']:0,
    'user_id' => $authorid,
    'comment_approved' => $commentstatus
    );
    $post = get_post($_REQUEST['post_id'], 'ARRAY_A');
    if($post['comment_status'] == 'closed'){
      return $this->output(0, 'Comment is closed in this post');
    }
    $comid = wp_insert_comment($data);
    if(is_wp_error($comid)) return $this->output(0, $comid->get_error_message());
    if(!empty($_REQUEST['custom_meta'])){
      $custom_meta = json_decode(stripslashes($_REQUEST['custom_meta']), true);
      if(isset($custom_meta)){
        foreach($custom_meta as $key=>$meta){
          update_comment_meta($comid, $key, wp_slash($meta));
        }
      }
    }
    if($commentstatus == 0) $commentstatus = 'pending review';
    else $commentstatus = 'published';
    return $this->output(1, 'Comment inserted successfully in '.$commentstatus.' status');
  }

  public function updateComment(){
    $this->CheckParams(array('id'));
    $commentdata = get_comment($_REQUEST['id']);
    $comment = array();
    $comment['comment_ID'] = $_REQUEST['id'];
    if(!empty($_REQUEST['content'])){
      $comment['comment_content'] = $_REQUEST['content'];
    }
    wp_update_comment($comment);
    if(!empty($_REQUEST['custom_meta'])){
      $custom_meta = json_decode(stripslashes($_REQUEST['custom_meta']), true);
      if(isset($custom_meta)){
        foreach($custom_meta as $key=>$meta){
          update_comment_meta($_REQUEST['id'], $key, wp_slash($meta));
        }
      }
    }
    return $this->output(1, 'Comment has been updated successfully');
  }

  public function last_comments(){
    $this->getComments('last', 0, true);
  }

  public function get_comment(){
    $this->CheckParams(array('comment_id'));
    $this->getComments('one', $_REQUEST['comment_id'], true);
  }

  public function getComments($type='list', $params=0, $printout=true, $fetchparams=false){
    global $wpdb;
    $this->ParseOutput = $printout;
    if($fetchparams !== false){
      $fetchparams = $this->fetchMethod('getcomments');
    }
    else{
      $fetchparams = $this->read_params;
    }
    $counter = 0;
    if($_REQUEST['orderby'] == 'date')
        $orderby = 'comments.comment_ID';
    else
        $orderby = 'comments.comment_ID';
    if($type == 'list'){
      $this->CheckParams(array('post_id'));
      if(!empty($_REQUEST['comment_status'])){
        if($_REQUEST['comment_status'] == 'pending'){
          $_REQUEST['comment_status'] = 0;
        }
        elseif($_REQUEST['comment_status'] == 'spam'){
          $_REQUEST['comment_status'] = 'spam';
        }
        elseif($_REQUEST['comment_status'] == 'trash'){
          $_REQUEST['comment_status'] = 'trash';
        }
        else{
          $_REQUEST['comment_status'] = 1;
        }
      }
      else{
        $_REQUEST['comment_status'] = 1;
      }
      $arg = array(
      'where' => array('comments.comment_post_ID' => $_REQUEST['post_id'], 'comments.comment_parent' => $params, 'comments.comment_approved' => $_REQUEST['comment_status']),
      'orderby' => $orderby,
      'order' => ($this->queryorder) ? $this->queryorder:'DESC'
      );
    }
    elseif($type == 'last'){
      $arg = array(
      'where' => array('comments.comment_parent'=>$params),
      'orderby' => $orderby,
      'order' => ($this->queryorder) ? $this->queryorder:'DESC'
      );
    }
    elseif($type == 'one'){
      $arg = array(
      'where' => array('comments.comment_ID'=>$params)
      );
      $type = 'last';
    }
    $sql = "SELECT {pre_select} comment_ID,comment_post_ID,comment_author,comment_author_email,comment_author_url,comment_author_IP,comment_date,comment_date_gmt
    ,comment_content,comment_agent,comment_approved,user_id {after_select} FROM ".$wpdb->prefix."comments {pre_where} {where} {after_where} {pre_order} {order} {after_order}";
    $sql = $this->queryBuild($sql, $arg);
    $sql = apply_filters('smio_api_comment_sql_filter', $sql);
    $sql = $this->Paging($sql);
    $gets = $wpdb->get_results($sql, 'ARRAY_A');
    if(!$gets) return $this->output(0, 'No result found');
    foreach($gets AS $get){
      $get['comment_date'] = $this->DateFormat($get['comment_date']);
      $get['comment_date_gmt'] = $this->DateFormat($get['comment_date_gmt']);
      $get['childcomment'] = $this->getComments($type, $get['comment_ID'], false);
      if($get['user_id'] > 0)
        $get['author'] = $this->authors('one', $get['user_id'], false, true);
      else
        $get['author'] = array();
      if(!in_array('commentmeta', $fetchparams)){
        $get['commentmeta'] = $this->get_meta_values($get['comment_ID'], 'commentmeta');
      }
      $this->isolateParams($get, $fetchparams);
      $get = apply_filters('smio_api_comment_filter', $get);
      $comment[$counter] = $get;
      $counter++;
    }
    $this->ParseOutput = $printout;
    return $this->output(1, $comment, $this->cache);
  }

  public function newPost(){
    if(self::$apisetting['visitor_can_post'] == 1){
      if(!empty($_REQUEST['author_id'])){
        $authorID = $_REQUEST['author_id'];
      }
      else{
        $this->MustLogin();
        $authorID = $_SESSION['smio_user_id'];
      }
    }
    else{
      $this->MustLogin();
      $authorID = $_SESSION['smio_user_id'];
    }
    $post_date = current_time('timestamp');
    $post_status = $this->get_option('new_post_status');
    if($post_status == 'open'){
      if(!empty($_REQUEST['post_status'])){
        $post_status = $_REQUEST['post_status'];
        if($post_status == 'future'){
          $this->CheckParams(array('post_date'));
        }
      }
      else{
        $post_status = 'pending';
      }
    }
    if(!empty($_REQUEST['comment_status'])){
      if($_REQUEST['comment_status'] == 'closed') $comment_status = 'closed';
      else $comment_status = 'open';
    }
    else $comment_status = 'open';
    if(!empty($_FILES['file']['name'])){
      $featuredimg = $this->upload_media(false);
      if($featuredimg !== false){
        $_REQUEST['thumbnail_id'] = $featuredimg['media_id'];
      }
    }
    $post = array(
    'comment_status' => $comment_status,
    'post_author'    => $authorID,
    'post_category'  => (!empty($_REQUEST['categoryid']))?explode(',', $_REQUEST['categoryid']):array(),
    'post_content'   => (empty($_REQUEST['content'])) ? ' ' : $_REQUEST['content'],
    'post_parent'   => (empty($_REQUEST['post_parent'])) ? 0 : $_REQUEST['post_parent'],
    'post_name'      => (empty($_REQUEST['slug'])) ? '' : $_REQUEST['slug'],
    'post_status'    => $post_status,
    'post_title'     => (empty($_REQUEST['subject'])) ? ' ' : $_REQUEST['subject'],
    'post_type'      => (!empty($_REQUEST['custom_post']))?$_REQUEST['custom_post']:'post',
    'tags_input'     => (empty($_REQUEST['tags'])) ? '' : $_REQUEST['tags'],
    );
    if(!empty($_REQUEST['post_date'])){
      $post_date = strtotime($_REQUEST['post_date']);
      if($post_date === false){
        return $this->output(0, 'Wrong post date format');
      }
      $post['post_date'] = date('Y-m-d H:i:s', $post_date);
      $post['post_date_gmt'] = gmdate('Y-m-d H:i:s', $post_date);
    }
    $post_id = wp_insert_post($post, true);
    if(is_wp_error($post_id)) return $this->output(0, $post_id->get_error_message());

    if(!empty($_REQUEST['taxonomy'])){
      $_REQUEST['taxonomy'] = explode('][', trim($_REQUEST['taxonomy'], '[]'));
      if(is_array($_REQUEST['taxonomy'])){
        foreach($_REQUEST['taxonomy'] as $tax){
          $tax = explode(',', $tax);
          $taxtname = $tax[0];
          unset($tax[0]);
          //$tax = array_map('intval', $tax);
          wp_set_object_terms($post_id, $this->convertInts($tax), $taxtname, true);
        }
      }
    }
    if(!empty($_REQUEST['geolocation'])){
      $locationinfo = smapi_geoloc::get_location_info();
      if($locationinfo !== false){
        foreach($locationinfo as $key=>$meta){
          update_post_meta($post_id, $key, wp_slash($meta));
        }
      }
    }
    if(!empty($_REQUEST['custom_meta'])){
      $custom_meta = json_decode(stripslashes($_REQUEST['custom_meta']), true);
      if(isset($custom_meta)){
        foreach($custom_meta as $key => $meta){
          if(!empty($meta) && $meta == 'SMIOPOSTID'){
            $meta = $post_id;
          }
          update_post_meta($post_id, $key, wp_slash($meta));
        }
      }
    }
    if(!empty($_REQUEST['custom_field'])){
      if(!function_exists('update_field')){
        return $this->output(0, 'ACF plugin needs to be enabled, Back to documentation for further information');
      }
      $custom_field = json_decode(stripslashes($_REQUEST['custom_field']), true);
      if(isset($custom_field)){
        foreach($custom_field as $fieldkey=>$value){
          if($value == 'SMIOPOSTID'){
            $value = $post_id;
          }
          update_field($fieldkey, wp_slash($value), $post_id);
        }
      }
    }
    if(!empty($_REQUEST['format'])){
      set_post_format($post_id , $_REQUEST['format']);
    }
    if(!empty($_REQUEST['thumbnail_id'])){
      set_post_thumbnail($post_id , $_REQUEST['thumbnail_id']);
    }
    return $this->output($post_id, 'Post inserted successfully in '.$post_status.' status');
  }

  public function updatePost(){
    $this->MustLogin();
    $this->CheckParams(array('id'));
    if(!empty($_REQUEST['post_status']) && $_REQUEST['post_status'] == 'trash'){
      $post = get_post($_REQUEST['id']);
      if($post->post_author != $_SESSION['smio_user_id']){
        $this->output(0, 'You do not have permission to delete this post');
      }
      wp_delete_post($_REQUEST['id'], true);
      $this->output(1, 'Post has been deleted successfully');
    }
    $post = array();
    $post['ID'] = $_REQUEST['id'];
    if(!empty($_FILES['file']['name'])){
      $featuredimg = $this->upload_media(false);
      if($featuredimg !== false){
        $_REQUEST['thumbnail_id'] = $featuredimg['media_id'];
      }
      set_post_thumbnail($_REQUEST['id'] , $_REQUEST['thumbnail_id']);
    }
    $post_date = current_time('timestamp');
    if(!empty($_REQUEST['post_status'])){
      if($_REQUEST['post_status'] == 'future'){
        $this->CheckParams(array('post_date'));
      }
      $post['post_status'] = $_REQUEST['post_status'];
    }
    if(!empty($_REQUEST['post_date'])){
      $post_date = strtotime($_REQUEST['post_date']);
      if($post_date === false){
        return $this->output(0, 'Wrong post date format');
      }
      $post['post_date'] = date('Y-m-d H:i:s', $post_date);
      $post['post_date_gmt'] = gmdate('Y-m-d H:i:s', $post_date);
    }
    $post['post_modified'] = date('Y-m-d H:i:s', $post_date);
    $post['post_modified_gmt'] = gmdate('Y-m-d H:i:s', $post_date);
    if(!empty($_REQUEST['comment_status'])){
      if($_REQUEST['comment_status'] == 'closed'){
        $post['comment_status'] = 'closed';
      }
      else{
        $post['comment_status'] = 'open';
      }
    }
    if(!empty($_REQUEST['categoryid'])){
      $post['post_category'] = explode(',', $_REQUEST['categoryid']);
    }
    if(!empty($_REQUEST['content'])){
      $post['post_content'] = $_REQUEST['content'];
    }
    if(!empty($_REQUEST['slug'])){
      $post['post_name'] = $_REQUEST['slug'];
    }
    if(!empty($_REQUEST['subject'])){
      $post['post_title'] = $_REQUEST['subject'];
    }
    if(!empty($_REQUEST['tags'])){
      $post['tags_input'] = $_REQUEST['tags'];
    }
    if(!empty($_REQUEST['tags'])){
      $post['post_parent'] = $_REQUEST['post_parent'];
    }
    if(!empty($_REQUEST['custom_post'])){
      $post['post_type'] = $_REQUEST['custom_post'];
    }
    $wp_update = wp_update_post($post);
    if(is_wp_error($wp_update)) return $this->output(0, $wp_update->get_error_message());
    $post_id = $_REQUEST['id'];
    if(!empty($_REQUEST['taxonomy'])){
      $_REQUEST['taxonomy'] = explode('][', trim($_REQUEST['taxonomy'], '[]'));
      if(is_array($_REQUEST['taxonomy'])){
        foreach($_REQUEST['taxonomy'] as $tax){
          $newTaxIDs = explode(',', $tax);
          $taxtname = $newTaxIDs[0];
          unset($newTaxIDs[0]);
          if(! empty($newTaxIDs)){
            wp_set_object_terms($post_id, $this->convertInts($newTaxIDs), $taxtname, true);
          }
          else{
            $newTaxIDs = array();
          }

          $currentTaxsIDs = array();
          $currentTaxs = get_the_terms($post_id, $taxtname);
          foreach($currentTaxs AS $currentTax){
            $currentTaxsIDs[] = $currentTax->term_id;
          }
          $taxsToRemove = array_diff($currentTaxsIDs, $newTaxIDs);
          wp_remove_object_terms($post_id, $taxsToRemove, $taxtname);
        }
      }
    }
    if(!empty($_REQUEST['geolocation'])){
      $locationinfo = smapi_geoloc::get_location_info();
      if($locationinfo !== false){
        foreach($locationinfo as $key=>$meta){
          update_post_meta($post_id, $key, wp_slash($meta));
        }
      }
    }
    if(!empty($_REQUEST['custom_meta'])){
      $custom_meta = json_decode(stripslashes($_REQUEST['custom_meta']), true);
      if(isset($custom_meta)){
        foreach($custom_meta as $key=>$meta){
          update_post_meta($post_id, $key, wp_slash($meta));
        }
      }
    }
    if(!empty($_REQUEST['custom_field'])){
      if(!function_exists('update_field')){
        return $this->output(0, 'ACF plugin needs to be enabled, Back to documentation for further information');
      }
      $custom_field = json_decode(stripslashes($_REQUEST['custom_field']), true);
      if(isset($custom_field)){
        foreach($custom_field as $fieldkey=>$value){
          update_field($fieldkey, wp_slash($value), $post_id);
        }
      }
    }
    if(!empty($_REQUEST['format'])){
      set_post_format($post_id , $_REQUEST['format']);
    }
    return $this->output(1, 'Post has been updated successfully');
  }

  public function upload_media($printout=true){
    $this->ParseOutput = $printout;
    if(!empty($_REQUEST['handler'])){
      $handler = $_REQUEST['handler'];
    }
    else{
      $handler = 'file';
    }
    if(empty($_FILES[$handler]['name'])){
      return $this->output(0, 'No file was uploaded');
    }
    if(self::$apisetting['visitor_can_post'] == 1 && !empty($_REQUEST['author_id'])){
      $attachPost = array('post_author' => $_REQUEST['author_id']);
    }
    else{
      $this->MustLogin();
      $attachPost = array();
    }
    if(!empty($_REQUEST['post_id'])){
      $post_id = $_REQUEST['post_id'];
    }
    else{
      $post_id = 0;
    }
    require_once(ABSPATH.'wp-admin/includes/media.php');
    require_once(ABSPATH.'wp-admin/includes/file.php');
    require_once(ABSPATH.'wp-admin/includes/image.php');

    if(!empty($post_id)){
      $attachid = media_handle_upload($handler, $post_id, $attachPost);
      if(is_wp_error($attachid)) return $this->output(0, $attachid->get_error_message());

      if(empty($_REQUEST['type']) || $_REQUEST['type'] == 'image'){
        $size = (!empty($_REQUEST['size'])) ? $_REQUEST['size'] : '';
        $upload_guid = wp_get_attachment_image_src($attachid, $size);
        $upload_data = array('media_id' => $attachid, 'url' => $upload_guid[0]);
      }
      else{
        $upload_data = array('media_id' => $attachid, 'url' => wp_get_attachment_url($attachid));
      }
    }
    elseif($_REQUEST['type'] == 'file' || empty($post_id)){
      $upload_guid = wp_handle_upload($_FILES[$handler], array('test_form' => false));
      if ( $upload_guid && ! isset( $upload_guid['error'] ) ) {
        $upload_data = array('url' => $upload_guid['url'], 'path' => $upload_guid['file']);
      } else {
        return $this->output(0, $upload_guid['error']);
      }
    }
    return $this->output(1, $upload_data);
  }

  public function get_archive(){
    global $wpdb, $wp_locale;
    $defaults = array('type' => 'monthly');
    $r = wp_parse_args('', $defaults);
    extract($r, EXTR_SKIP);
    $where = apply_filters('getarchives_where', "WHERE post_type='post' AND post_status='publish'", $r);
    $join = apply_filters('getarchives_join', '', $r);
    $last_changed = wp_cache_get('last_changed', 'posts');
    if(!$last_changed){
      $last_changed = microtime();
      wp_cache_set('last_changed', $last_changed, 'posts');
    }
    $query = "SELECT YEAR(post_date) AS `year`,MONTH(post_date) AS `month`,count(ID) as posts FROM $wpdb->posts $join $where GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
    $key = md5($query);
    $key = "wp_get_archives:$key:$last_changed";
    if (!$results = wp_cache_get($key, 'posts')){
      $results = $wpdb->get_results($query);
      wp_cache_set($key, $results, 'posts');
    }
    if($results){
      foreach((array) $results as $result){
        $text = sprintf(__('%1$s %2$d'), $wp_locale->get_month($result->month), $result->year);
        $get = array('text'=>$text, 'year'=>$result->year, 'month'=>$result->month, 'count'=>$result->posts);
        $this->isolateParams($get, $this->read_params);
        $archive[] = $get;
      }
      return $this->output(1, $archive);
    }
    else return $this->output(0, 'No result found');
  }

  public function getpost(){
    global $wpdb;
    $this->CheckParams(array('post_id'));
    if(! is_numeric($_REQUEST['post_id'])){
      $post_type = 'post';
      if(!empty($_REQUEST['custom_post'])){
        $post_type = $_REQUEST['custom_post'];
      }
      $_REQUEST['post_id'] = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$_REQUEST['post_id']."'AND post_type = '$post_type' LIMIT 1");
    }
    $this->getposts('one', $_REQUEST['post_id'], true);
  }

  public function author_posts(){
    $this->CheckParams(array('author_id'));
    $this->getposts('author', $_REQUEST['author_id'], true);
  }

  public function last_posts(){
    $this->getposts('last', 0, true);
  }

  public function search_posts(){
    //$this->CheckParams(array('query'));
    $this->getposts('search', $_REQUEST['query'], true);
  }

  public function get_posts_archive(){
    $this->CheckParams(array('srchinyear'));
    $this->getposts('indate', $_REQUEST['srchinyear'], true);
  }

  public function posts_subscribedin(){
    $this->CheckParams(array('author_id'));
    global $wpdb;
    if(!empty($_REQUEST['limit']))
        $limit = $_REQUEST['limit'];
    else
        $limit = 20;
    $sql = "SELECT comment_post_ID FROM ".$wpdb->prefix."comments WHERE user_id='$_REQUEST[author_id]' GROUP BY comment_post_ID DESC LIMIT 0,$limit";
    $gets = $wpdb->get_results($sql, 'ARRAY_A');
    if(!$gets){
      return $this->output(0, 'No result found');
    }
    foreach($gets AS $get){
      $ids[] = $get['comment_post_ID'];
    }
    $this->getposts('inlist', $ids, true);
  }

  public function popular_posts(){
    $this->CheckParams(array('limit'));
    if(!function_exists('stats_get_csv'))
        return $this->output(0, 'Jetpack plugin with Stats module needs to be enabled');
    if(!empty($_REQUEST['range']))
        $range = $_REQUEST['range'];
    else{
        $range = $this->get_option('popular_range');
        if($range == 0) $range = 'all';
    }
    $popular = stats_get_csv('postviews', array('days'=>$range, 'limit'=>$_REQUEST['limit']));
    if(!$popular) return $this->output(0, 'Sorry, No data yet');
    $popular = array_filter(wp_list_pluck($popular, 'post_id'));
    if(!$popular) return $this->output(0, 'Sorry, No data yet');
    $ids = array_unique($popular);
    $this->getposts('inlist', $ids, true);
  }

  public function menu_items(){
    $this->CheckParams(array('menu'));
    $menus = wp_get_nav_menu_items($_REQUEST['menu']);
    if(empty($menus)){
      return $this->output(0, 'No result found');
    }
    else{
      return $this->output(1, $menus);
    }
  }

  public function tag_posts(){
    $this->CheckParams(array('tag'));
    $tag = get_term_by('name', $_REQUEST['tag'], 'post_tag');
    if(!$tag)
        return $this->output(0, 'Did not find the tag name');
    $_REQUEST['categoryid'] = $tag->term_id;
    $this->getposts('list', 0, true);
  }

  public function getpage(){
    global $wpdb;
    $this->CheckParams(array('pageid'));

    if(! is_numeric($_REQUEST['pageid'])){
      $_REQUEST['pageid'] = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$_REQUEST['pageid']."'AND post_type = 'page' LIMIT 1");
    }

    $this->getposts('one', $_REQUEST['pageid'], true);
  }

  public function getpages(){
    $this->getposts('pagelist', 0, true);
  }

  public function getposts_format(){
    $this->CheckParams(array('format'));
    $term = get_term_by('name', 'post-format-'.$_REQUEST['format'], 'post_format');
    if($term !== false){
      $_REQUEST['categoryid'] = $term->term_id;
    }
    else{
      return $this->output(0, 'No result found');
    }
    $this->getposts('list', 0, true);
  }

  private function getcustomfromReq($requests){
    $result = array();
    $requests = explode('][', trim($requests, '[]'));
    if(is_array($requests)){
      foreach($requests as $request){
        $request = explode(',', $request);
        if(count($request) > 0){
          $result[$request[0]] = $request[1];
        }
      }
      if(is_array($request)){
        return $result;
      }
    }
    return false;
  }

  public function getposts($type='list', $params=0, $printout=true, $fetchparams=false, $with_contents=true){
    global $wpdb;
    $this->ParseOutput = $printout;
    if($fetchparams !== false){
      $fetchparams = $this->fetchMethod('getposts');
    }
    else{
      $fetchparams = $this->read_params;
    }
    if(defined('SMAPI_RETURN_OUTPUT_START') && !defined('SMAPI_RETURN_OUTPUT_END')){
      $this->counter = 0;
    }
    $innersql = '';
    $select = '';
    $where = '';
    $havingsql = '';
    $innerloop = 0;
    $table = $wpdb->prefix.'posts';

    if($_REQUEST['orderby'] == 'comment_count')
        $orderby = 'posts.comment_count';
    elseif($_REQUEST['orderby'] == 'date')
        $orderby = 'posts.post_date';
    elseif(!empty($_REQUEST['orderby']))
        $orderby = $_REQUEST['orderby'];
    else
        $orderby = 'posts.ID';
    if(!empty($_REQUEST['custom_post'])){
      $post_type = $_REQUEST['custom_post'];
    }
    elseif(!empty($_REQUEST['client']) && $_REQUEST['client'] == 'smartwpapp'){
      $post_type = self::$apisetting['mob_cat_post_type'];
    }
    else{
      $post_type = 'post';
    }
    if(!empty($_REQUEST['taxonomy_id'])){
      $_REQUEST['categoryid'] = $_REQUEST['taxonomy_id'];
    }
    if(!empty($_REQUEST['categoryid'])){
      $whterms = explode(',', $_REQUEST['categoryid']);
      $taxonomies = [];
      $_REQUEST['categoryid'] = [];
      foreach($whterms as $whtermid){
        if(is_numeric($whtermid)){
          $whterm = get_term($whtermid);
        } else {
          $whterm = get_term_by('name', $whtermid, ((empty($_REQUEST['taxonomy'])) ? 'category' : $_REQUEST['taxonomy']));
        }
        array_push($_REQUEST['categoryid'], $whterm->term_id);
        $taxonomies[$whterm->taxonomy][] = $whtermid;
        $categories = get_terms(['child_of' => $whtermid, 'hide_empty' => false, 'taxonomy' => $whterm->taxonomy]);
        if(!empty($categories)){
          foreach($categories as $category){
            $taxonomies[$whterm->taxonomy][] = $category->term_id;
            array_push($_REQUEST['categoryid'], $category->term_id);
          }
        }
      }
      $_REQUEST['categoryid'] = implode(',', $_REQUEST['categoryid']);
    }
    if(!empty($_REQUEST['post_status'])){
      $post_status = $_REQUEST['post_status'];
      if(strpos($_REQUEST['post_status'], ',') !== false){
        $post_status = implode("','", array_map('trim', explode(',', $post_status)));
        $post_status_sql = "AND $table.post_status IN ('$post_status')";
      }
      else{
        $post_status_sql = "AND $table.post_status='$post_status'";
      }
    }
    else{
      $post_status = 'publish';
      $post_status_sql = "AND $table.post_status='$post_status'";
    }
    if($type == 'list'){
      $arg = array(
      'where' => array('posts.post_type' => $post_type),
      'orderby' => $orderby,
      'order' => ($this->queryorder) ? $this->queryorder:'DESC'
      );
      if(!empty($_REQUEST['categoryid']) && (empty($_REQUEST['categoryrel']) || $_REQUEST['categoryrel'] == 'or')){
        $arg['in']['term_taxonomy.term_id'] = $_REQUEST['categoryid'];
      }
      elseif(!empty($taxonomies) && $_REQUEST['categoryrel'] == 'and'){
        $taxloop = 1;
        foreach($taxonomies as $taxonomy => $taxids){
          $arg['in']['tax'.$taxloop.'.term_id'][] = implode(',', $taxids);
          $innersql .= ' INNER JOIN slshw_term_relationships AS term'.$taxloop.' ON(slshw_posts.ID=term'.$taxloop.'.object_id)
          INNER JOIN slshw_term_taxonomy AS '.$wpdb->prefix.'tax'.$taxloop.' ON('.$wpdb->prefix.'tax'.$taxloop.'.term_taxonomy_id=term'.$taxloop.'.term_taxonomy_id)';
          $taxloop++;
        }
      }

      if(!empty($_REQUEST['author_id'])){
        $arg['where']['posts.post_author'] = $_REQUEST['author_id'];
      }
      if(!empty($_REQUEST['query'])){
        $arg['likeor'] = array('posts.post_title' => "%".$_REQUEST['query']."%");
      }
      if(!empty($_REQUEST['custom_meta'])){
        $_REQUEST['custom_meta'] = stripslashes($_REQUEST['custom_meta']);
        $custom_metas = json_decode($_REQUEST['custom_meta'], true);
        if(empty($custom_metas)){
          $custom_metas = $this->getcustomfromReq($_REQUEST['custom_meta']);
        }
        if($custom_metas !== false){
          foreach($custom_metas as $key=>$value){
            $value = "LIKE '$value'";
            $inner[] = $wpdb->prefix."postmeta.meta_key='$key' AND ".$wpdb->prefix."postmeta.meta_value $value";
          }
          $innersql = "INNER JOIN ".$wpdb->prefix."postmeta ON(((".implode(') OR (', $inner).")) AND ".$wpdb->prefix."postmeta.post_id=".$wpdb->prefix."posts.ID)";
        }
      }
      if(!empty($_REQUEST['custom_meta_or'])){
        $_REQUEST['custom_meta_or'] = stripslashes($_REQUEST['custom_meta_or']);
        $custom_metas = json_decode($_REQUEST['custom_meta_or'], true);
        if(!empty($custom_metas)){
          foreach($custom_metas as $key=>$value){
            $inner[] = $wpdb->prefix."postmeta.meta_key='$key' AND ".$wpdb->prefix."postmeta.meta_value $value";
          }
          $innersql = "INNER JOIN ".$wpdb->prefix."postmeta ON(((".implode(') OR (', $inner).")) AND ".$wpdb->prefix."postmeta.post_id=".$wpdb->prefix."posts.ID)";
        }
        else{
          return $this->output(0, 'string in `custom_meta_or` parameter is not in a JSON format');
        }
      }
      if(!empty($_REQUEST['custom_meta_and'])){
        $_REQUEST['custom_meta_and'] = stripslashes($_REQUEST['custom_meta_and']);
        $custom_metas = json_decode($_REQUEST['custom_meta_and'], true);
        if(!empty($custom_metas)){
          foreach($custom_metas as $key=>$value){
            $innerloop++;
            $innersql .= " INNER JOIN ".$wpdb->prefix."postmeta AS postmeta$innerloop ON(postmeta$innerloop.meta_key='$key' AND postmeta$innerloop.meta_value $value AND postmeta$innerloop.post_id=".$wpdb->prefix."posts.ID) ";
          }
        }
        else{
          return $this->output(0, 'string in `custom_meta_and` parameter is not in a JSON format');
        }
      }
      if(!empty($_REQUEST['custom_search_or'])){
        $custom_metas = json_decode(stripslashes($_REQUEST['custom_search_or']), true);
        if($custom_metas !== false){
          $cwhere = array();
          foreach($custom_metas as $key=>$value){
            $cwhere[] = $wpdb->prefix.'posts.'.$key.' '.$value;
          }
          $where = ' AND ('.implode(' OR ', $cwhere).')';
        }
        else{
          return $this->output(0, 'string in `custom_search_or` parameter is not in a JSON format');
        }
      }
      if(!empty($_REQUEST['custom_search_and'])){
        $custom_metas = json_decode(stripslashes($_REQUEST['custom_search_and']), true);
        if(!empty($custom_metas)){
          $cwhere = array();
          foreach($custom_metas as $key=>$value){
            $cwhere[] = $wpdb->prefix.'posts.'.$key.' '.$value;
          }
          $where = ' AND ('.implode(' AND ', $cwhere).')';
        }
        else{
          return $this->output(0, 'string in `custom_search_and` parameter is not in a JSON format');
        }
      }
      if(strpos($orderby, 'postmeta') !== false){
        $orderby = str_replace(array('postmeta.',$wpdb->prefix.'postmeta.'), '', $orderby);
        $select = ",(select subpmeta.meta_value from ".$wpdb->prefix."postmeta AS subpmeta WHERE subpmeta.post_id=$table.ID AND subpmeta.meta_key='$orderby' limit 1) AS ".$wpdb->prefix."OrderedValue";
        $arg['orderby'] = 'OrderedValue';
      }
      if(! empty($_REQUEST['load_bbforums'])){
        $arg['where']['posts.post_parent'] = $params;
      }
      if(isset($_REQUEST['unload_bb_stick'])){
        $superStickIDs = get_option('_bbp_super_sticky_topics');
        if(empty($superStickIDs)){
          $superStickIDs = [];
        }
        $forumStickIDs = get_post_meta($_REQUEST['parent_id'], '_bbp_sticky_topics', true);
        if(!empty($forumStickIDs)){
          $superStickIDs = array_merge($superStickIDs, $forumStickIDs);
        }
        if(!empty($superStickIDs)){
          $arg['notin']['posts.ID'] = implode(',', $superStickIDs);
        }
      }
      if(! empty($_REQUEST['load_bb_super_stick'])){
        $superStickIDs = get_option('_bbp_super_sticky_topics');
        if(empty($superStickIDs)){
          return $this->output(0, 'No result found');
        }
        $arg['in']['posts.ID'] = implode(',', $superStickIDs);
      }
      if(isset($_REQUEST['load_bb_forum_stick'])){
        $forumStickIDs = get_post_meta($_REQUEST['parent_id'], '_bbp_sticky_topics', true);
        if(empty($forumStickIDs)){
          return $this->output(0, 'No result found');
        }
        $arg['in']['posts.ID'] = implode(',', $forumStickIDs);
      }
    }
    elseif($type == 'inlist'){
      $arg = array(
      'where' => array('posts.post_type'=>$post_type),
      'in' => array('posts.ID'=>implode(',', $params)),
      'orderby' => 'FIELD({posts}.ID,',
      'order' => implode(',', $params).')'
      );
    }
    elseif($type == 'indate'){
      $arg = array(
      'where' => array('posts.post_type'=>$post_type),
      'date' => array('posts.post_date'=>array(0=>array('index'=>'year', 'value'=>$params))),
      'orderby' => $orderby,
      'order' => ($this->queryorder) ? $this->queryorder:'DESC'
      );
      if(!empty($_REQUEST['month']))
        $arg['date']['posts.post_date'][1] = array('index'=>'month', 'value'=>$_REQUEST['month']);
      if(!empty($_REQUEST['categoryid']))
        $arg['in']['term_taxonomy.term_id'] = $_REQUEST['categoryid'];
    }
    elseif($type == 'last'){
      if(empty($_REQUEST['perpage'])){
        $_REQUEST['perpage'] = 10;
      }
      $arg = array(
      'where' => array('posts.post_type' => $post_type),
      'orderby' => 'posts.ID',
      'order' => 'DESC'
      );
    }
    elseif($type == 'pagelist'){
      $arg = array(
      'where' => array('posts.post_type'=>'page'),
      'orderby' => $orderby,
      'order' => ($this->queryorder) ? $this->queryorder:'DESC'
      );
      if(!empty($_REQUEST['parentid']))
        $arg['where']['term_taxonomy.term_id'] = $_REQUEST['parentid'];
      if(!empty($_REQUEST['taxonomy_id']))
        $arg['where']['term_taxonomy.term_id'] = $_REQUEST['taxonomy_id'];
    }
    elseif($type == 'author'){
      $arg = array(
      'where' => array('posts.post_author'=>$params, 'posts.post_type'=>$post_type),
      'orderby' => $orderby,
      'order' => ($this->queryorder) ? $this->queryorder:'DESC'
      );
      if(!empty($_REQUEST['categoryid']))
        $arg['in']['term_taxonomy.term_id'] = $_REQUEST['categoryid'];
    }
    elseif($type == 'search'){
      $arg = array(
      'where' => array('posts.post_type' => $post_type)
      );
      if(!empty($params)){
        $arg['likeor'] = array('posts.post_title' => "%$params%");
      }
      if(!empty($_REQUEST['search_level'])){
        if($_REQUEST['search_level'] == 1 OR $_REQUEST['search_level'] == 4){
          $arg['likeor']['posts.post_content'] = "%$params%";
        }
        if($_REQUEST['search_level'] == 2 OR $_REQUEST['search_level'] == 4){
          $arg['likeor']['terms.name'] = "%$params%";
          $innersql .= "LEFT JOIN ".$wpdb->prefix."terms ON(".$wpdb->prefix."terms.term_id=".$wpdb->prefix."term_taxonomy.term_id)";
        }
        if($_REQUEST['search_level'] == 3 OR $_REQUEST['search_level'] == 4){
          $arg['likeor']['postmeta.meta_value'] = "%$params%";
          $innersql .= "LEFT JOIN ".$wpdb->prefix."postmeta ON(".$wpdb->prefix."postmeta.post_id=".$wpdb->prefix."posts.ID)";
        }
      }
      if(!empty($_REQUEST['orderby'])){
        $arg['orderby'] = $orderby;
        $arg['order'] = ($this->queryorder) ? $this->queryorder:'';
      }
      if(!empty($_REQUEST['categoryid']))
        $arg['in']['term_taxonomy.term_id'] = $_REQUEST['categoryid'];
    }
    elseif($type == 'one'){
      $arg = array(
      'where' => array('posts.ID' => $params)
      );
    }
    if(!empty($_REQUEST['geolocation'])){
      $locationinfo = smapi_geoloc::get_location_info();
      if($locationinfo !== false){
        foreach($locationinfo as $key=>$value){
          if(in_array($key, array('country','city'))){
            $innerloop++;
            $innersql .= " INNER JOIN ".$wpdb->prefix."postmeta AS postmeta$innerloop ON(postmeta$innerloop.meta_key='$key' AND postmeta0.meta_value $value AND postmeta$innerloop.post_id=".$wpdb->prefix."posts.ID) ";
          }
        }
      }
    }
    if(!empty($_REQUEST['client']) && $_REQUEST['client'] == 'smartwpapp'){
      if(!empty($_REQUEST['latitude']) && !empty($_REQUEST['longitude']) && !empty(self::$apisetting['mob_metakey_lat']) && !empty(self::$apisetting['mob_metakey_lng'])){
        $select .= ',(3959*acos(cos(radians('.$_REQUEST['latitude'].'))*cos(radians(lat_meta.meta_value))*cos(radians(long_meta.meta_value)-radians('.$_REQUEST['longitude'].'))+sin(radians('.$_REQUEST['latitude'].'))*sin(radians(lat_meta.meta_value)))) AS '.$wpdb->prefix.'geodistance';
        $innersql .= ' INNER JOIN '.$wpdb->postmeta.' AS lat_meta ON(lat_meta.meta_key="'.self::$apisetting['mob_metakey_lat'].'" AND lat_meta.post_id='.$table.'.ID)';
        $innersql .= ' INNER JOIN '.$wpdb->postmeta.' AS long_meta ON(long_meta.meta_key="'.self::$apisetting['mob_metakey_lng'].'" AND long_meta.post_id='.$table.'.ID)';
        if(!empty($_REQUEST['radius'])){
          $havingsql .= ' HAVING '.$wpdb->prefix.'geodistance<='.$_REQUEST['radius'];
        }
        else{
          $havingsql .= ' HAVING '.$wpdb->prefix.'geodistance<=50';
        }
        $arg['orderby'] = 'geodistance';
        $arg['order'] = 'ASC';
      }
      if(!empty($_REQUEST['follow_authors']) && !empty($_SESSION['smio_user_id'])){
        $followedAuthors = $wpdb->get_results("SELECT authorid FROM ".$wpdb->prefix."smapi_author_followers WHERE userid='$_SESSION[smio_user_id]'");
        if($followedAuthors){
          $followedAuthorIDs = array();
          foreach($followedAuthors as $followedAuthor){
            $followedAuthorIDs[] = $followedAuthor->authorid;
          }
          $followedAuthorIDs = implode(',', $followedAuthorIDs);
          $arg['in']['posts.post_author'] = $followedAuthorIDs;
        }
        else{
          $arg['where']['posts.post_author'] = 0;
        }
      }
      if(function_exists('GetWtiLikeCount')){
        $wtiLikeTable = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}wti_like_post'");
        if($wtiLikeTable){
          $select .= ",(SELECT SUM({$wpdb->prefix}wti_like_post.value) FROM {$wpdb->prefix}wti_like_post WHERE {$wpdb->prefix}wti_like_post.post_id={$wpdb->prefix}posts.ID AND {$wpdb->prefix}wti_like_post.value>0 GROUP BY {$wpdb->prefix}wti_like_post.post_id) AS wti_likes";
        }
      }
    }
    if((current_user_can('read_private_posts') || current_user_can('read_private_pages')) AND !empty($_REQUEST['private'])){
      $post_status_sql = "AND ($table.post_status IN ('$post_status') OR $table.post_status='private')";
    }
    if(!empty($_REQUEST['parent_id'])){
      $arg['where']['posts.post_parent'] = $_REQUEST['parent_id'];
    }
    if(!empty($_REQUEST['tag_id'])){
      $arg['in']['tags_taxs.term_id'] = $_REQUEST['tag_id'];
      $innersql .= "LEFT JOIN ".$wpdb->prefix."term_relationships AS ".$wpdb->prefix."tags_relat ON($table.ID=".$wpdb->prefix."tags_relat.object_id)
      LEFT JOIN ".$wpdb->prefix."term_taxonomy AS ".$wpdb->prefix."tags_taxs ON(".$wpdb->prefix."tags_taxs.term_taxonomy_id=".$wpdb->prefix."tags_relat.term_taxonomy_id AND ".$wpdb->prefix."tags_taxs.taxonomy='post_tag')";
    }
    $sql = "SELECT {pre_select} $table.* $select {after_select} FROM $table
    LEFT JOIN ".$wpdb->prefix."term_relationships ON($table.ID=".$wpdb->prefix."term_relationships.object_id)
    LEFT JOIN ".$wpdb->prefix."term_taxonomy ON(".$wpdb->prefix."term_taxonomy.term_taxonomy_id=".$wpdb->prefix."term_relationships.term_taxonomy_id)
    $innersql
    {pre_where} {where} $where {after_where} $post_status_sql GROUP BY $table.ID $havingsql {pre_order} {order} {after_order}";
    $sql = $this->queryBuild($sql, $arg);
    $sql = apply_filters('smio_api_post_sql_filter', $sql);
    $sql = $this->Paging($sql);
    $gets = $wpdb->get_results($sql, 'ARRAY_A');
    if(!$gets){
      return $this->output(0, 'No result found');
    }
    $resizepercent = $this->get_option('resize_image');
    $imgresize = get_option('thumbnail_size_h').'x'.get_option('thumbnail_size_w');
    remove_filter('the_content', 'PutWtiLikePost');
    if(!empty($_REQUEST['remove_filters'])){
      $remove_filters = explode('|', $_REQUEST['remove_filters']);
      foreach($remove_filters as $remove_filter){
        remove_filter('the_content', $remove_filter);
      }
    }
    foreach($gets AS $get){
      $get['post_title'] = htmlspecialchars_decode($get['post_title']);
      if(!in_array('featuredimage', $fetchparams) && !in_array('featuredthumb', $fetchparams)){
        $image = get_the_post_thumbnail($get['ID'], 'full');
        if(!empty($image)){
          preg_match_all('/src="([^"]*)"/i', $image, $src);
          $get['featuredimage'] = $src[1][0];
          $ext = strtolower(substr($src[1][0], strrpos($src[1][0], '.')+1));
          $get['featuredthumb'] = str_replace('.'.$ext, '', $src[1][0]).'-'.$imgresize.'.'.$ext;
        }
        else{
          $get['featuredimage'] = '';
          $get['featuredthumb'] = '';
        }
      }
      if(!empty($get['wti_likes'])){
        $get['likes'] = $get['wti_likes'];
      }
      else{
        $get['likes'] = 0;
      }
      if($type == 'one'){
        $postpass = $wpdb->get_var("SELECT post_password FROM $wpdb->posts WHERE ID='$get[ID]'");
        if(!empty($postpass)){
          if($_REQUEST['password'] != $postpass){
            return $this->output(0, 'This content is password protected');
          }
        }
        if(!in_array('video', $fetchparams)){
          $video = array();
          preg_match_all('#(?:httpv://)?(?:www\.)?(?:youtube\.com/(?:v/|watch\?v=)|youtu\.be/)([\w-]+)(?:\S+)?#', $get['post_content'], $matchs);
          if(count($matchs[1]) > 0){
            foreach($matchs[1] AS $match)
              $vidids[] = $match;
            $vidids = array_unique($vidids);
            foreach($vidids AS $vid){
              $video[]['youtube'] = 'http://www.youtube.com/watch?v='.$vid;
            }
          }
          $get['video'] = $video;
        }
        if(!in_array('post_content', $fetchparams) && $with_contents && empty($_REQUEST['no_content'])){
          if($this->get_option('post_content') == 'plain')
            $get['post_content'] = strip_tags($get['post_content']);
          else{
            $get['post_content'] = htmlspecialchars_decode($get['post_content']);
            if(empty($_REQUEST['disable_content_filters'])){
              $get['post_content'] = apply_filters('the_content', $get['post_content']);
            }
          }
        }
      }
      else{
        if(!in_array('post_content', $fetchparams) && $with_contents && empty($_REQUEST['no_content'])){
          if($this->get_option('postlist_content') == 'plain')
            $get['post_content'] = strip_tags($get['post_content']);
          else{
            $get['post_content'] = htmlspecialchars_decode($get['post_content']);
            if(empty($_REQUEST['disable_content_filters'])){
              $get['post_content'] = apply_filters('the_content', $get['post_content']);
            }
          }
        }
      }
      if($resizepercent > 0 && !in_array('post_content', $fetchparams)){
        $get['post_content'] = preg_replace("/width=\"[0-9]*\"/", 'width="'.$resizepercent.'%"', $get['post_content']);
      }
      if((empty($_REQUEST['exclude']) || !in_array('taxonomies', explode(',', $_REQUEST['exclude']))) && !in_array('taxonomies', $fetchparams)){
        $get['taxonomies'] = $this->getPostTexnomies($get['ID']);
      }
      if((empty($_REQUEST['exclude']) || !in_array('category', explode(',', $_REQUEST['exclude']))) && !in_array('category', $fetchparams))
        $get['category'] = $this->getPostCats($get['ID']);
      if((empty($_REQUEST['exclude']) || !in_array('tags', explode(',', $_REQUEST['exclude']))) && !in_array('tags', $fetchparams))
        $get['tags'] = $this->getPostTags($get['ID']);
      if(! empty($_REQUEST['load_bbforums']) && empty($params)){
        if(! defined('SMAPI_RETURN_OUTPUT_START')){
          define('SMAPI_RETURN_OUTPUT_START', true);
        }
        $counter = $this->counter;
        $this->counter = 0;
        $get['subforums'] = $this->getposts('list', $get['ID'], false);
        $this->counter = $counter;
      }
      $get['permalink'] = get_permalink($get);
      $get['post_date_unformatted'] = $get['post_date'];
      $get['post_date_gmt_unformatted'] = $get['post_date_gmt'];
      $get['post_modified_unformatted'] = $get['post_modified'];
      $get['post_modified_gmt_unformatted'] = $get['post_modified_gmt'];
      $get['post_date'] = $this->DateFormat($get['post_date']);
      $get['post_date_gmt'] = $this->DateFormat($get['post_date_gmt']);
      $get['post_modified'] = $this->DateFormat($get['post_modified']);
      $get['post_modified_gmt'] = $this->DateFormat($get['post_modified_gmt']);
      if((empty($_REQUEST['exclude']) || !in_array('author', explode(',', $_REQUEST['exclude']))) && !in_array('author', $fetchparams))
        $get['author'] = $this->authors('one', $get['post_author'], false, true);
      if(function_exists('get_post_format')){
        $get['post_format'] = get_post_format($get['ID']);
      }
      if((empty($_REQUEST['exclude']) || !in_array('custom_fields', explode(',', $_REQUEST['exclude']))) && !in_array('custom_fields', $fetchparams)){
        if(function_exists('get_fields')){
          $postmeta = get_fields($get['ID']);
          $get['custom_fields'] = (empty($postmeta))? array() : $postmeta;
        }
        else{
          $get['custom_fields'] = 'ACF plugin needs to be enabled, Back to documentation for further information';
        }
      }
      if((empty($_REQUEST['exclude']) || !in_array('postmeta', explode(',', $_REQUEST['exclude']))) && !in_array('postmeta', $fetchparams)){
        $get['postmeta'] = $this->get_meta_values($get['ID'], 'postmeta');
      }
      if(!empty($_REQUEST['no_content'])){
        $get['post_content'] = '';
      }
      if(!empty($_REQUEST['no_content_filtered'])){
        $get['post_content_filtered'] = '';
      }
      $this->isolateParams($get, $fetchparams);
      $get = apply_filters('smio_api_post_filter', $get);
      $posts[$this->counter] = $get;
      $this->counter++;
    }
    if(! empty($_REQUEST['load_bbforums']) && empty($params)){
      define('SMAPI_RETURN_OUTPUT_END', true);
    }
    return $this->output(1, $posts, $this->cache);
  }

  private function get_meta_values($id, $type){
    global $wpdb;
    if($type == 'postmeta')
        $sql = "SELECT meta_key,meta_value FROM ".$wpdb->postmeta." WHERE post_id='$id'";
    elseif($type == 'commentmeta')
        $sql = "SELECT meta_key,meta_value FROM ".$wpdb->commentmeta." WHERE comment_id='$id'";
    elseif($type == 'usermeta')
        $sql = "SELECT meta_key,meta_value FROM ".$wpdb->usermeta." WHERE user_id='$id'";
    $gets = $wpdb->get_results($sql, 'ARRAY_A');
    if(!$gets){
      return array();
    }
    foreach($gets AS $get){
      $unserialize = @unserialize($get['meta_value']);
      $meta[$get['meta_key']] = ($unserialize !== false)?$unserialize:$get['meta_value'];
    }
    return $meta;
  }

  private function getPostCats($post_id){
    if(!empty($_REQUEST['client']) && $_REQUEST['client'] == 'smartwpapp'){
      $taxonomy = self::$apisetting['mob_cat_post_type_tax'];
      $taxs = get_the_terms($post_id, $taxonomy);
      if(empty($taxs)) return array();
      foreach($taxs AS $tax)
        $ids[] = $tax->term_id;
    }
    else{
      $taxonomy = 'category';
      $cats = get_the_category($post_id);
      if(count($cats) < 1) return array();
      foreach($cats AS $cat)
        $ids[] = $cat->cat_ID;
    }
    return $this->categories('ids', $ids, false, $taxonomy);
  }

  private function getPostTags($post_id){
    $tags = wp_get_post_tags($post_id);
    if(count($tags) < 1) return array();
    foreach($tags AS $tag)
        $ids[] = $tag->term_id;
    return $this->categories('ids', $ids, false, 'post_tag');
  }

  private function getPostTexnomies($post_id){
    $args = array('public' => true,'_builtin' => false);
    $get_taxonomies = get_taxonomies($args, 'names', 'and');
    if($get_taxonomies){
      foreach($get_taxonomies as $taxonomy){
        $ids = array();
        $taxs = get_the_terms($post_id, $taxonomy);
        if(!is_array($taxs)) continue;
        foreach($taxs AS $tax)
            $ids[] = $tax->term_id;
        $posttaxs[$taxonomy] = $this->categories('ids', $ids, false, $taxonomy);
      }
    }
    if(empty($posttaxs)){
      return array();
    }
    else{
      return $posttaxs;
    }
  }

  public function get_author(){
    $this->CheckParams(array('author_id'));
    $this->authors('one', $_REQUEST['author_id'], true);
  }

  public function authors($type='list', $params=0, $printout=true, $fetchparams=false){
    global $wpdb;
    $this->ParseOutput = $printout;
    if($fetchparams !== false){
      $fetchparams = $this->fetchMethod('authors');
    }
    else{
      $fetchparams = $this->read_params;
    }
    if($_REQUEST['orderby'] == 'name')
      $orderby = '{users}.display_name';
    elseif($_REQUEST['orderby'] == 'date')
      $orderby = '{users}.ID';
    else
      $orderby = '{users}.display_name';
    if(!empty($_REQUEST['role'])){
      $role = $_REQUEST['role'];
    }
    else{
      $role = '';
    }
    $innersql = $where = '';
    if($type == 'list'){
      $arg = array(
        'like' => (!empty($role))?array('{usermeta}.meta_value'=>'%'.$role.'%'):array(),
        'orderby' => $orderby,
        'order' => ($this->queryorder) ? $this->queryorder:'ASC'
      );
      if(!empty($_REQUEST['custom_meta_or'])){
        $_REQUEST['custom_meta_or'] = stripslashes($_REQUEST['custom_meta_or']);
        $custom_metas = json_decode($_REQUEST['custom_meta_or'], true);
        if(!empty($custom_metas)){
          foreach($custom_metas as $key=>$value){
            $inner[] = "usermeta2.meta_key='$key' AND usermeta2.meta_value $value";
          }
          $innersql = "INNER JOIN ".$wpdb->usermeta." AS usermeta2 ON(((".implode(') OR (', $inner).")) AND usermeta2.user_id=".$wpdb->users.".ID)";
        }
        else{
          return $this->output(0, 'string in `custom_meta_or` parameter is not in a JSON format');
        }
      }
      if(!empty($_REQUEST['custom_meta_and'])){
        $_REQUEST['custom_meta_and'] = stripslashes($_REQUEST['custom_meta_and']);
        $custom_metas = json_decode($_REQUEST['custom_meta_and'], true);
        if(!empty($custom_metas)){
          $innerloop = 0;
          foreach($custom_metas as $key=>$value){
            $innerloop++;
            $innersql .= " INNER JOIN ".$wpdb->usermeta." AS usermeta$innerloop ON(usermeta$innerloop.meta_key='$key' AND usermeta$innerloop.meta_value $value AND usermeta$innerloop.user_id=".$wpdb->users.".ID) ";
          }
        }
        else{
          return $this->output(0, 'string in `custom_meta_and` parameter is not in a JSON format');
        }
      }
      if(!empty($_REQUEST['custom_search_or'])){
        $custom_metas = json_decode(stripslashes($_REQUEST['custom_search_or']), true);
        if($custom_metas !== false){
          $cwhere = array();
          foreach($custom_metas as $key=>$value){
            $cwhere[] = $wpdb->users.'.'.$key.' '.$value;
          }
          $where = ' AND ('.implode(' OR ', $cwhere).')';
        }
        else{
          return $this->output(0, 'string in `custom_search_or` parameter is not in a JSON format');
        }
      }
      if(!empty($_REQUEST['custom_search_and'])){
        $custom_metas = json_decode(stripslashes($_REQUEST['custom_search_and']), true);
        if(!empty($custom_metas)){
          $cwhere = array();
          foreach($custom_metas as $key=>$value){
            $cwhere[] = $wpdb->users.'.'.$key.' '.$value;
          }
          $where = ' AND ('.implode(' AND ', $cwhere).')';
        }
        else{
          return $this->output(0, 'string in `custom_search_and` parameter is not in a JSON format');
        }
      }
    }
    elseif($type == 'one'){
      $arg = array(
        'where' => array('{users}.ID' => $params)
      );
    }
    $counter = 0;
    $content_url = wp_upload_dir();
    $table = $wpdb->users;
    $sql = "SELECT {pre_select} $table.ID,$table.user_login,$table.user_nicename,$table.user_email,$table.user_url,$table.user_registered,$table.display_name,".$wpdb->usermeta.".meta_value {after_select}
     FROM $table
     LEFT JOIN ".$wpdb->usermeta." ON(".$wpdb->usermeta.".user_id=$table.ID AND ".$wpdb->usermeta.".meta_key LIKE '%capabilities')
     $innersql
     {pre_where} {where} $where {after_where} {pre_order} {order} {after_order}";
    $sql = $this->queryBuild($sql, $arg, true);
    $sql = apply_filters('smio_api_author_sql_filter', $sql);
    $sql = $this->Paging($sql);
    $gets = $wpdb->get_results($sql, 'ARRAY_A');
    if(!$gets){
      return $this->output(0, 'No result found');
    }
    foreach($gets AS $get){
      $meta = get_user_meta($get['ID']);
      $get['session_id'] = session_id();
      $get['user_registered'] = $this->DateFormat($get['user_registered']);
      $get['role'] = (empty($get['meta_value'])) ?  ['subscriber'] : unserialize($get['meta_value']);
      $get['first_name'] = $meta['first_name'][0];
      $get['last_name'] = $meta['last_name'][0];
      $get['nickname'] = $meta['nickname'][0];
      $get['description'] = $meta['description'][0];

      if(!empty($meta['aim'])){
        $get['aim'] = $meta['aim'][0];
        $get['jabber'] = $meta['jabber'][0];
        $get['yim'] = $meta['yim'][0];
      }
      else{
        $get['aim'] = '';
        $get['jabber'] = '';
        $get['yim'] = '';
      }
      if(!in_array('custom_fields', $fetchparams)){
        if(function_exists('get_fields')){
          $user_meta = get_fields('user_'.$get['ID']);
          $get['custom_fields'] = (empty($user_meta))? array() : $user_meta;
        }
        else{
          $get['custom_fields'] = 'ACF plugin needs to be enabled, Back to documentation for further information';
        }
      }
      if(!in_array('authormeta', $fetchparams)){
        $get['authormeta'] = $this->get_meta_values($get['ID'], 'usermeta');
        if(empty($get['authormeta']['smio_followers'])){
          $get['authormeta']['smio_followers'] = 0;
        }
        if(!empty($_SESSION['smio_user_id'])){
          if(is_plugin_active('follow-my-blog-post/follow-my-blog-post.php')){
            $isfollowed = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."um_followers WHERE user_id2='$_SESSION[smio_user_id]' AND user_id1='$get[ID]'");
          }
          else{
            $isfollowed = $wpdb->get_var("SELECT id FROM ".$wpdb->prefix."smapi_author_followers WHERE userid='$_SESSION[smio_user_id]' AND authorid='$get[ID]'");
          }
          $get['authormeta']['smio_followed'] = (empty($isfollowed))? 0 : 1;
        }
        else{
          $get['authormeta']['smio_followed'] = 0;
        }
      }
      if(!empty($get['authormeta']['smio_avatar'])){
        $get['avatar'] = $content_url['baseurl'].$get['authormeta']['smio_avatar'];
      }
      else{
        $defAvatar = apply_filters('smio_def_avatar', '');
        if(empty($defAvatar)){
          $defAvatar = [];
        } else {
          $defAvatar = ['default' => $defAvatar];
        }
        $get['avatar'] = get_avatar_url($get['ID'], $defAvatar);
      }
      $this->isolateParams($get, $fetchparams);
      $get = apply_filters('smio_api_author_filter', $get);
      $authors[$counter] = $get;
      $counter++;
    }
    return $this->output(1, $authors, $this->cache);
  }

  public function tags(){
    $this->categories('list', 0, true, 'post_tag');
  }

  public function viewcategory(){
    $this->CheckParams(array('cat_id'));
    $this->categories('one', $_REQUEST['cat_id'], true);
  }

  public function get_taxonomies(){
    $args = array('public' => true,'_builtin' => false);
    $get_taxonomies = get_taxonomies($args, 'names', 'and');
    if($get_taxonomies){
      foreach($get_taxonomies as $taxonomy){
        $taxonomies[$taxonomy] = $this->categories('list', 0, false, $taxonomy, true);
      }
      return $this->output(1, $taxonomies);
    }
    else{
      return $this->output(0, 'No result found');
    }
  }

  public function get_taxonomy(){
    if(empty($_REQUEST['taxonomy_name']) && !empty($_REQUEST['client']) && $_REQUEST['client'] == 'smartwpapp'){
      $_REQUEST['taxonomy_name'] = self::$apisetting['mob_cat_post_type_tax'];
    }
    $this->CheckParams(array('taxonomy_name'));
    $this->categories('list', 0, true, $_REQUEST['taxonomy_name']);
  }

  public function categories($type='list', $params=0, $printout=true, $listtype='category', $fetchparams=false){
    global $wpdb;
    $this->ParseOutput = $printout;
    if($type == 'ids'){
      $fetchparams = $this->fetchMethod('categories');
    }
    elseif($fetchparams !== false && $printout !== false){
      $fetchparams = $this->fetchMethod('categories');
    }
    else{
      $fetchparams = $this->read_params;
    }
    $counter = 0;
    $categories = [];
    if($_REQUEST['orderby'] == 'postcount')
        $orderby = 'term_taxonomy.count';
    elseif($_REQUEST['orderby'] == 'name')
        $orderby = 'terms.name';
    elseif($_REQUEST['orderby'] == 'date')
        $orderby = 'term_taxonomy.term_id';
    elseif($_REQUEST['orderby'] == 'term_order')
        $orderby = 'term_relationships.term_order';
    else
        $orderby = 'terms.name';
    if($type == 'list'){
      $arg = array(
      'where' => array('term_taxonomy.parent' => $params, 'term_taxonomy.taxonomy' => $listtype),
      'orderby' => $orderby,
      'order' => ($this->queryorder) ? $this->queryorder:'ASC'
      );
    }
    elseif($type == 'ids'){
      $arg = array(
      'in' => array('term_taxonomy.term_id' => implode(',', $params)),
      'where' => array('term_taxonomy.taxonomy' => $listtype),
      'orderby' => 'FIELD({term_taxonomy}.term_id, '.implode(',', $params).')',
      'order' => ''
      );
    }
    elseif($type == 'one'){
      $arg = array(
      'in' => array('term_taxonomy.term_id' => $params),
      'where' => array('term_taxonomy.taxonomy' => $listtype),
      'orderby' => $orderby,
      'order' => ($this->queryorder) ? $this->queryorder:'ASC'
      );
    }
    if(!empty($_REQUEST['excluded'])){
      $exccats = $this->get_option('exclude_cats');
      if(!empty($exccats))
          $arg['notin'] = array('term_taxonomy.term_id' => $exccats);
    }
    $table = $wpdb->prefix.'term_taxonomy';
    $sql = "SELECT {pre_select} $table.term_id AS id,".$wpdb->prefix."terms.name,".$wpdb->prefix."terms.slug
    ,$table.description,$table.count,$table.parent {after_select} FROM $table
    INNER JOIN ".$wpdb->prefix."terms ON($table.term_id=".$wpdb->prefix."terms.term_id) {pre_where} {where} {after_where} {pre_order} {order} {after_order}";
    $sql = $this->queryBuild($sql, $arg);
    $sql = apply_filters('smio_api_taxonomy_sql_filter', $sql);
    $sql = $this->Paging($sql);
    $gets = $wpdb->get_results($sql, 'ARRAY_A');
    if(!$gets){
      return $this->output(0, 'No result found');
    }
    $catimageplugin = (function_exists('z_taxonomy_image_url'))?true:false;
    foreach($gets AS $get){
      $get['name'] = htmlspecialchars_decode($get['name']);
      $get['description'] = htmlspecialchars_decode($get['description']);
      if($listtype != 'post_tag'){
        if($catimageplugin){
          $cimage = z_taxonomy_image_url($get['id'], true);
          if($cimage !== false)
            $get['image'] = $cimage;
          else
            $get['image'] = '';
        }
        else{
          $get['image'] = 'Plugin Categories Images must be enabled';
        }
        if(!in_array('custom_fields', $fetchparams)){
          if(function_exists('get_fields')){
            $postmeta = get_fields($listtype.'_'.$get['id']);
            $get['custom_fields'] = (empty($postmeta))? array() : $postmeta;
          }
          else{
            $get['custom_fields'] = 'ACF plugin needs to be enabled, Back to documentation for further information';
          }
        }
      }
      if(($type=='list' OR  $type=='one') && $listtype!='post_tag' && !in_array('subcategory', $fetchparams))
        $get['subcategory'] = $this->categories('list', $get['id'], false, $listtype, true);
      $this->isolateParams($get, $fetchparams);
      $get = apply_filters('smio_api_taxonomy_filter', $get);
      $categories[$counter] = $get;
      $counter++;
    }
    $this->ParseOutput = $printout;
    return $this->output(1, $categories, $this->cache);
  }

  //bbPress Functions
  public function bb_new_topic(){
    if(!function_exists('bbp_insert_topic')){
      return $this->output(0, 'bbPress plugin is not installed');
    }
    $this->CheckParams(array('post_parent','subject','content'));
    $this->MustLogin();

    $topic_data = array();
    $topic_data['post_parent'] = $_REQUEST['post_parent'];
    $topic_data['post_author'] = $_SESSION['smio_user_id'];
    $topic_data['post_content'] = $_REQUEST['content'];
    $topic_data['post_title'] = $_REQUEST['subject'];

    $topic_meta = array();
    $topic_meta['forum_id'] = $_REQUEST['post_parent'];
    $topic_meta['author_ip'] = $_SERVER['REMOTE_ADDR'];

    $post_id = bbp_insert_topic($topic_data, $topic_meta);

    if(is_wp_error($post_id)) return $this->output(0, $post_id->get_error_message());

    if(!empty($_REQUEST['taxonomy'])){
      $_REQUEST['taxonomy'] = explode('][', trim($_REQUEST['taxonomy'], '[]'));
      if(is_array($_REQUEST['taxonomy'])){
        foreach($_REQUEST['taxonomy'] as $tax){
          $tax = explode(',', $tax);
          $taxtname = $tax[0];
          unset($tax[0]);
          wp_set_object_terms($post_id, $this->convertInts($tax), $taxtname, true);
        }
      }
    }

    return $this->output($post_id, 'New topic inserted successfully');
  }

  public function bb_new_comment(){
    if(!function_exists('bbp_insert_reply')){
      return $this->output(0, 'bbPress plugin is not installed');
    }
    $this->CheckParams(array('post_parent','forumid','content'));
    $this->MustLogin();

    $topic_data = array();
    $topic_data['post_parent'] = $_REQUEST['post_parent'];
    $topic_data['post_author'] = $_SESSION['smio_user_id'];
    $topic_data['post_content'] = $_REQUEST['content'];

    $topic_meta = array();
    $topic_meta['forum_id'] = $_REQUEST['forumid'];
    $topic_meta['topic_id'] = $_REQUEST['post_parent'];
    $topic_meta['author_ip'] = $_SERVER['REMOTE_ADDR'];

    $reply_id = bbp_insert_reply($topic_data, $topic_meta);

    if(is_wp_error($reply_id)) return $this->output(0, $reply_id->get_error_message());

    if(!empty($_REQUEST['taxonomy'])){
      $_REQUEST['taxonomy'] = explode('][', trim($_REQUEST['taxonomy'], '[]'));
      if(is_array($_REQUEST['taxonomy'])){
        foreach($_REQUEST['taxonomy'] as $tax){
          $tax = explode(',', $tax);
          $taxtname = $tax[0];
          unset($tax[0]);
          wp_set_object_terms($reply_id, $this->convertInts($tax), $taxtname, true);
        }
      }
    }

    return $this->output($reply_id, 'New topic inserted successfully');
  }
  //bbPress Functions


  //Administrator Operations
  public function post_status(){
    $this->CheckParams(array('post_id','status'));
    $post = array(
    'ID'           => $_REQUEST['post_id'],
    'post_status'  => $_REQUEST['status']
    );
    if(wp_update_post($post) == 0){
      return $this->output(0, 'Error occured');
    }
    else{
      return $this->output(1, 'Post status changed successfully');
    }
  }

  public function comment_status(){
    $this->CheckParams(array('comment_id','status'));
    if($_REQUEST['status'] == 'approved'){
      $_REQUEST['status'] = 1;
    }
    elseif($_REQUEST['status'] == 'pending'){
      $_REQUEST['status'] = 0;
    }
    elseif($_REQUEST['status'] == 'spam'){
      $_REQUEST['status'] = 'spam';
    }
    elseif($_REQUEST['status'] == 'trash'){
      $_REQUEST['status'] = 'trash';
    }
    $comment = array(
    'comment_ID'        => $_REQUEST['comment_id'],
    'comment_approved'  => $_REQUEST['status']
    );
    if(wp_update_comment($comment) == 0){
      return $this->output(0, 'Error occured');
    }
    else{
      return $this->output(1, 'Comment status changed successfully');
    }
  }

  public function delete_myaccount(){
    $this->MustLogin();
    require_once(ABSPATH.'wp-admin/includes/user.php');
    if(empty($_REQUEST['reassignto'])){
      $_REQUEST['reassignto'] = 'novalue';
    }
    if(wp_delete_user($_SESSION['smio_user_id'], $_REQUEST['reassignto'])){
      return $this->output(1, 'User removed successfully');
    }
    else{
      return $this->output(0, 'Error occured');
    }
  }

  public function delete_user(){
    $this->CheckParams(array('user_id'));
    require_once(ABSPATH.'wp-admin/includes/user.php');
    if(empty($_REQUEST['reassignto'])){
      $_REQUEST['reassignto'] = 'novalue';
    }
    if(wp_delete_user($_REQUEST['user_id'], $_REQUEST['reassignto'])){
      return $this->output(1, 'User removed successfully');
    }
    else{
      return $this->output(0, 'Error occured');
    }
  }

  public function delete_post(){
    $this->CheckParams(array('post_id'));
    $this->haveAdminRole();

    if(wp_delete_post($_REQUEST['post_id'], true) === false){
      return $this->output(0, 'Error occured');
    }
    else{
      return $this->output(1, 'Post removed successfully');
    }
  }

  public function delete_author_post(){
    $this->CheckParams(array('post_id'));
    $this->MustLogin();

    $author_id = get_post_field('post_author', $_REQUEST['post_id']);

    if($author_id == $_SESSION['smio_user_id']){
      if(wp_delete_post($_REQUEST['post_id'], true) === false){
        return $this->output(0, 'Error occured');
      }
      else{
        return $this->output(1, 'Post removed successfully');
      }
    }
    return $this->output(0, 'Error occured');
  }

  public function delete_comment(){
    $this->CheckParams(array('comment_id'));
    if(wp_delete_comment($_REQUEST['comment_id'], true) === false){
      return $this->output(0, 'Error occured');
    }
    else{
      return $this->output(1, 'Comment removed successfully');
    }
  }
  //Administrator Operations

  public function custom_service(){
    global $wpdb;
    $this->CheckParams(array('service'));
    $service = $wpdb->get_row("SELECT `name`,`query`,`paging`,`access_level`,codetype FROM ".$wpdb->prefix."smapi_service WHERE name='$_REQUEST[service]'");
    if(!$service){
      return $this->output(0, 'No service found with this name');
    }
    if($this->haveRole($service->access_level) === false){
      return $this->output(0, 'You do not have permission to use `'.$service->name.'` service');
    }
    $service->query = stripslashes($service->query);
    if($service->codetype == 'php'){
      if(SMAPIDEMO){
        return $this->output(0, 'Not allowed in demo version');
      }
      eval($service->query);
    }
    else{
      $service->query = str_replace('{wp_prefix}', $wpdb->prefix , $service->query);
      if(preg_match_all("/{([a-zA-Z0-9_]+)}/", $service->query, $matches)){
        foreach($matches[1] AS $match){
          if(!empty($_REQUEST[$match])){
            $service->query = str_replace('{'.$match.'}', $_REQUEST[$match] , $service->query);
          }
        }
      }
      if($service->paging == 'enable'){
        $service->query = $this->Paging($service->query);
      }
      $getservice = $wpdb->get_results($service->query, 'ARRAY_A');
      if($wpdb->last_error){
        return $this->output(0, 'Query has something error: '.$wpdb->last_error);
      }
      if($getservice){
        return $this->output(1, $getservice);
      }
      else{
        return $this->output(0, 'No result found');
      }
    }
  }

  public function custom_options(){
    global $wpdb;
    $options = $wpdb->get_results("SELECT name FROM ".$wpdb->prefix."smapi_option", 'ARRAY_A');
    if($options){
      foreach($options AS $option){
        if(!empty(self::$apisetting['co_'.$option['name']])){
          $alloption[$option['name']] = self::$apisetting['co_'.$option['name']];
        }
        else{
          $alloption[$option['name']] = '';
        }
      }
      return $this->output(1, $alloption);
    }
    else{
      return $this->output(0, 'No result found');
    }
  }

  public function social_links(){
    if(function_exists('get_scp_twitter')){
      $settings = get_option('socialcountplus_settings');
      $socplugin = new Social_Count_Plus_Counter();
      $count = $socplugin->update_transients();
      $social = array();
      if(!empty($settings['twitter_active'])){
        $social[] = array('title'=>'Twitter', 'link'=>'http://twitter.com/'.$settings['twitter_user'], 'count'=>$count['twitter']);
      }
      if(!empty($settings['facebook_active'])){
        $social[] = array('title'=>'Facebook', 'link'=>'http://www.facebook.com/'.$settings['facebook_id'], 'count'=>$count['facebook']);
      }
      if(!empty($settings['youtube_active'])){
        $social[] = array('title'=>'Youtube', 'link'=>'http://www.youtube.com/user/'.$settings['youtube_user'], 'count'=>$count['youtube']);
      }
      if(!empty($settings['googleplus_active'])){
        $social[] = array('title'=>'Google Plus', 'link'=>'https://plus.google.com/'.$settings['googleplus_id'], 'count'=>$count['googleplus']);
      }
      if(!empty($settings['instagram_active'])){
        $social[] = array('title'=>'Instagram', 'link'=>'http://instagram.com/'.$settings['instagram_username'], 'count'=>$count['instagram']);
      }
      if(!empty($settings['steam_active'])){
        $social[] = array('title'=>'Stream', 'link'=>'http://steamcommunity.com/groups/'.$settings['steam_group_name'], 'count'=>$count['steam']);
      }
      if(!empty($settings['soundcloud_active'])){
        $social[] = array('title'=>'SoundCloud', 'link'=>'https://soundcloud.com/'.$settings['soundcloud_username'], 'count'=>$count['soundcloud']);
      }
      return $this->output(1, $social);
    }
    else{
      return $this->output(0, 'Plugin `Social Count Plus` must be enabled');
    }
  }

  public function network_sites(){
    global $wpdb;
    if(is_multisite()){
      $blogs = $wpdb->get_results("SELECT blog_id,site_id,domain,path,registered,public,lang_id,last_updated FROM $wpdb->blogs WHERE public='1' AND deleted='0' AND spam='0' AND archived='0' ORDER BY blog_id ASC", 'ARRAY_A');
      if($blogs){
        foreach($blogs as $get){
          $bloginfo = get_blog_details(array('blog_id' => $get['blog_id']));
          $get['blogname'] = htmlspecialchars_decode($bloginfo->blogname);
          $get['siteurl'] = $bloginfo->siteurl;
          $get['post_count'] = $bloginfo->post_count;
          $get['last_updated'] = $bloginfo->last_updated;
          $this->isolateParams($get, $this->read_params);
          $sites[] = $get;
        }
      }
      return $this->output(1, $sites);
    }
    else{
      return $this->output(0, 'Wordpress multisite feature is not enabled');
    }
  }

  public function bloginfo(){
    $get = array();
    $get['blogname'] = get_option('blogname');
    $get['blogdescription'] = get_option('blogdescription');
    $get['admin_email'] = get_option('admin_email');
    $get['siteurl'] = get_option('siteurl');
    $get['home'] = get_option('home');
    $get['default_category'] = get_option('default_category');
    $get['start_of_week'] = get_option('start_of_week');
    $get['require_name_email'] = get_option('require_name_email');
    $this->isolateParams($get, $this->read_params);
    return $this->output(1, $get);
  }

  public function contactus(){
    $this->CheckParams(array('name','email','message'));
    if(!is_email($_REQUEST['email'])) return $this->output(0, 'E-mail address not valid');
    $headers = 'From: '.$_REQUEST['name'].' <'.$_REQUEST['email'].'>' . "\r\n";
    $message = __('Someone sent to you a message from the mobile application:') . "\r\n";
    $message .= sprintf(__('Name: %s'), $_REQUEST['name']) . "\r\n";
    $message .= sprintf(__('Email: %s'), $_REQUEST['email']) . "\r\n";
    $message .= __('Message:') . "\r\n";
    $message .= $_REQUEST['message']."\r\n";

    if(is_multisite())
      $blogname = $GLOBALS['current_site']->site_name;
    else
      $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    if(!empty($_REQUEST['subject'])){
      $title = $_REQUEST['subject'];
    } else{
      $title = '['.$blogname.'] new message from '.$_REQUEST['name'];
    }
    if($message && !wp_mail(get_option('admin_email'), $title, $message, $headers))
      return $this->output(0, 'The E-mail could not be sent may be your host disabled the mail() public function');
    return $this->output(1, 'E-mail sent successfully');
  }

  public function debug(){
    return $this->output(1, 'Plugin is active now and work under version '.get_option('smapi_version'));
  }

  private function fetchMethod($method, $allowed_scopes=false){
    if(!$this->ParseOutput && !empty($this->exten_read_params[$method])){
      return $this->exten_read_params[$method];
    }
    global $wpdb;
    $service = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."smapi_engine WHERE name=%s", $method));
    if(!$service){
      return $this->output(0, '`'.$method.'` service stopped working by administrator');
    }
    if($service->active == 0){
      return $this->output(0, '`'.$method.'` service stopped working by administrator');
    }
    if($this->haveRole($service->access_level) === false){
      return $this->output(0, 'You do not have permission to use `'.$method.'` service');
    }
    if($allowed_scopes !== false && ! in_array($method, array('request_token','refresh_token'))){
      $allowed_scopes = json_decode($allowed_scopes, true);
      if(! in_array($service->scope, $allowed_scopes)){
        return $this->output(0, '`'.$method.'` service is not included in your allowed scope');
      }
    }
    $service->params = unserialize($service->params);
    $paramslist = array();
    if(is_array($service->params)){
      foreach($service->params as $param){
        if($param['active'] == 0){
          $paramslist[] = $param['name'];
        }
      }
    }
    if($this->ParseOutput !== false){
      $this->read_params = $paramslist;
    }
    else{
      $this->exten_read_params[$method] = $paramslist;
      return $this->exten_read_params[$method];
    }
  }

  private function haveRole($roles){
    $roles = unserialize($roles);
    if(!in_array('anyone', $roles)){
      if(in_array('logged', $roles)){
        $this->MustLogin();
      }
      else{
        if(empty($_SESSION['smio_user_roles']) || count(array_intersect($_SESSION['smio_user_roles'], $roles)) < 1){
          return false;
        }
      }
    }
  }

  private function haveAdminRole(){
    if(!isset($_SESSION['smio_user_roles'])){
      return $this->output(0, 'You do not have the permission to do that process');
    }
    if(count(array_intersect($_SESSION['smio_user_roles'], array('administrator'))) > 0){
      return true;
    }
    return $this->output(0, 'You do not have the permission to do that process');
  }

  private function MustLogin(){
    if(!session_id()) session_start();
    if(!empty($_SESSION['smio_user_id'])){
      return true;
    }
    if(is_user_logged_in() === false){
      return $this->output(0, 'Must be login to proceed');
    }
    $user = wp_get_current_user();
    $_SESSION['smio_user_id'] = $user->ID;
    $_SESSION['smio_user_roles'] = $user->roles;
  }

  private function isolateParams(&$get, $fetchparams){
    if(is_array($fetchparams)){
      foreach($fetchparams as $param){
        unset($get[$param]);
      }
    }
    else{
      return true;
    }
  }

  public static function delete_relw_app($user_id){
    global $wpdb;
    $wpdb->delete($wpdb->base_prefix.'smapi_social_login', array('userid' => $user_id));
    $wpdb->delete($wpdb->prefix.'smapi_auth_tokens', array('userid' => $user_id));
  }

  public static function wpw_fp_followers_counter($postid){
    global $wpdb;
    $post = get_post($postid);
    $authorid = $post->post_author;
    $followers = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."um_followers WHERE user_id1='$authorid'");
    update_user_meta($authorid, 'smio_followers', $followers);
  }

  private function DateFormat($date){
    return date($this->dateformat, strtotime($date));
  }

}
