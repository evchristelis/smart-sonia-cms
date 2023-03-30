<?php

class smapi_crons extends smapi_controller{

  public function __construct(){}

  public function cron_daily(){
    global $wpdb;
    $wpdb->query("DELETE FROM ".$wpdb->prefix."smapi_auth_tokens WHERE expire IS NOT NULL AND expire>'".current_time('timestamp')."'");
  }

  public function cron_monthly(){
  }

  public function cron_cleaner(){
  }

  private static function generateKeys($uniqueid){
    $keys = array();
    $keys['client_id'] = hexdec($uniqueid.rand(100000, 200000));
    $keys['client_secret'] = md5($uniqueid.uniqid(rand(), true));
    $keys['access_token'] = md5(base64_encode(pack('N6', mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), get_current_user_id()))).md5(base64_encode(pack('N6', mt_rand(), mt_rand(), mt_rand(), mt_rand(), mt_rand(), uniqid())));
    return $keys;
  }

}