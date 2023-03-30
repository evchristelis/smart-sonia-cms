<?php

class smapi_auth extends smapi_controller{

  public function __construct(){}

  public static function checkAuthKey($auth_key){
    if(!empty($auth_key)){
      if(empty($_REQUEST['auth_key']) || $_REQUEST['auth_key'] != $auth_key){
        return false;
      }
    }
  }

  public static function getOauthClient($sent_appid, $sent_authkey){
    if(empty($sent_appid)){
      return false;
    }
    global $wpdb;
    $client = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."smapi_oauth_clients WHERE app_id='$sent_appid'", ARRAY_A);
    if(empty($client) || $client['auth_key'] != $sent_authkey){
      return false;
    }
    if($client['status'] == 0){
      return -3;
    }
    if($client['quota'] > 0 && $client['req_usage'] >= $client['quota']){
      return -1;
    }
    elseif($client['quota'] > 0){
      $wpdb->update($wpdb->prefix.'smapi_oauth_clients', array('req_usage' => ($client['req_usage']+1)), array('id' => $client['id']));
    }
    $client['settings'] = unserialize($client['settings']);
    return $client;
  }

  public static function checkAccessToken($access_token, $oAuthClient){
    global $wpdb;
    $where = ' AND clientid>0';
    if($oAuthClient === false){
      $where = " AND clientid='0'";
    }
    $token = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."smapi_auth_tokens WHERE md5_access_token='".md5($access_token)."' $where", ARRAY_A);
    if(!empty($token)){
      if(!empty($token['expire']) && time() > $token['expire']){
        return -2;
      }
      if($token['clientid'] > 0){
        $client = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."smapi_oauth_clients WHERE id='".$token['clientid']."'", ARRAY_A);
        if($client['status'] == 0){
          return -3;
        }
        if($client['quota'] > 0 && $client['req_usage'] >= $client['quota']){
          return -1;
        }
        elseif($client['quota'] > 0){
          $wpdb->update($wpdb->prefix.'smapi_oauth_clients', array('req_usage' => ($client['req_usage']+1)), array('id' => $client['id']));
        }
        $client['settings'] = unserialize($client['settings']);
        $token['oauth2client'] = $client;
      }

      return $token;
    }
    else{
      return false;
    }
  }

  public static function generateAccessToken($userid, $reqscope, $oAuthClient, $refresh=false){
    global $wpdb;
    if($oAuthClient !== false){
      $configurations = array('allowed_scopes' => $oAuthClient['settings']['allowed_scopes'], 'token_expire' => $oAuthClient['settings']['token_expire']);
      $oAuthClientID = $oAuthClient['id'];
    }
    else{
      $configurations = array('allowed_scopes' => self::$apisetting['allowed_scopes'], 'token_expire' => self::$apisetting['token_expire']);
      $oAuthClientID = 0;
    }
    $scopes = array_map('trim', explode(',', $reqscope));

    $oldtoken = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."smapi_auth_tokens WHERE userid='$userid' AND clientid='$oAuthClientID'");
    if($oldtoken){
      if(json_encode($scopes) != $oldtoken->scope){
        foreach($scopes as $scope){
          if(! in_array($scope, $configurations['allowed_scopes'])){
            return false;
          }
        }
        $wpdb->update($wpdb->prefix.'smapi_auth_tokens', array('scope' => json_encode($scopes)), array('id' => $oldtoken->id));
      }
      if($refresh && !empty($configurations['token_expire'])){
        $oldtoken->expire = current_time('timestamp')+($configurations['token_expire']*86400);
        $wpdb->update($wpdb->prefix.'smapi_auth_tokens', array('expire' => $oldtoken->expire), array('id' => $oldtoken->id));
      }
      $access_token_data = array(
        'type' => 'bearer',
        'token' => $oldtoken->access_token,
        'expire_at' => (empty($oldtoken->expire))? 0 : $oldtoken->expire
      );
      return $access_token_data;
    }

    if($refresh){
      return false;
    }

    foreach($scopes as $scope){
      if(! in_array($scope, $configurations['allowed_scopes'])){
        return false;
      }
    }

    $access_token = self::generateKey(256, ($userid+$oAuthClientID));

    $token = array();
    $token['md5_access_token'] = md5($access_token);
    $token['access_token'] = $access_token;
    $token['clientid'] = $oAuthClientID;
    $token['userid'] = $userid;
    if(!empty($configurations['token_expire'])){
      $token['expire'] = current_time('timestamp')+($configurations['token_expire']*86400);
    }
    $token['scope'] = json_encode($scopes);
    $wpdb->insert($wpdb->prefix.'smapi_auth_tokens', $token);

    $access_token_data = array(
      'type' => 'bearer',
      'token' => $access_token,
      'expire_at' => (!empty($configurations['token_expire']))? $token['expire'] : 0
    );

    return $access_token_data;
  }

  private static function generateKey($length, $uniqueid){
    $token = uniqid($uniqueid).'_';
    $length = $length-strlen($token);
    $codeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codeAlphabet.= 'abcdefghijklmnopqrstuvwxyz';
    $codeAlphabet.= '0123456789';
    $max = strlen($codeAlphabet);

    for ($i=1; $i < $length; $i++) {
      $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return substr(($uniqueid.$token), 0, 256);
  }

}