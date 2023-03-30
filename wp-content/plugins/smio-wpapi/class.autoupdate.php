<?php

class smapi_autoupdate extends smapi_controller {

  public static $wpdateformat;

  public function __construct() {
    parent::__construct();
  }
  
  public static function auto_update() {
    $helper = new smapi_helper();
    $content = '';
    if(!empty($_POST['startupdate'])){
      if(smapi_env == 'demo'){
        $content .= 'update feature is not allowed in the demo version';
      }
      if(empty(self::$apisetting['purchase_code'])){
        $content .= '<p>failed : enter your private purchase code to proceed in the updating process</p>';
      }
      if(!function_exists('rmdir')){
        $content .= '<p>failed : function rmdir() is not supported in your server</p>';
      }
      if(!function_exists('unlink')){
        $content .= '<p>failed : function unlink() is not supported in your server</p>';
      }
      if(!function_exists('fopen')){
        $content .= '<p>failed : function fopen() is not supported in your server</p>';
      }
      if(chmod(smapi_dir, 0777) === false){
        $content .= '<p>failed : directory wp-content does not have permission 0777</p>';
      }
      if(!class_exists('ZipArchive')){
        $content .= '<p>failed : ZipArchive library is not supported in your server</p>';
      }
      if(empty($content)){
        $lastupdate = json_decode($helper->buildCurl('https://smartiolabs.com/update/wp_api'), true);
        $updateData = $helper->buildCurl('https://smartiolabs.com/download', false, array('purchase_code' => self::$apisetting['purchase_code']));
        if($helper->curl_status == 200){
          $localzipfile = plugin_dir_path( __DIR__ ).'/smiowpapi_update_package.zip';
          @unlink($localzipfile);
          $handle = fopen($localzipfile, 'w');
          fwrite($handle, $updateData);
          fclose($handle);
          if(md5_file($localzipfile) == $lastupdate['md5_hash']){
            $zip = new ZipArchive;
            $ziphandle = $zip->open($localzipfile);
            if ($ziphandle === TRUE) {
              self::delTree(smapi_dir);
              $zip->extractTo(plugin_dir_path( __DIR__ ));
              $zip->close();
              @unlink($localzipfile);
              $content = '<p>your system has been successfully upgraded to the latest version '.$lastupdate['version'].'</p>';
            }
            else {
              @unlink($localzipfile);
              $content = '<p>Something happens while downloading the update package...Please try again later</p>';
            }
          }
          else{
            @unlink($localzipfile);
            $content = '<p>Something happens while downloading the update package...Please try again later</p>';
          }
        }
        else{
          $content = $updateData;
        }
      }
      include(smapi_dir.'/pages/auto_update.php');
      exit();
    }
    if(!empty($_POST['save'])){
      self::$apisetting['purchase_code'] = $_POST['purchase_code'];
      update_option('smapi_options', self::$apisetting);
      if(is_multisite()){
        self::updateNetworkPurchaseCode($_POST['purchase_code']);
      }
    }
    $lastupdate = json_decode($helper->buildCurl('https://smartiolabs.com/update/wp_api'));
    if($lastupdate !== NULL){
      $content = '<p>System current version : '.SMAPIVERSION.'</p><p>System last version : '.$lastupdate->version.'</p>';
    }
    else{
      $content = '<p>System current version : '.SMAPIVERSION.'</p><p>System last version : failed to connect</p>';
    }
    $content .= '<form action="" method="post">
      <input name="purchase_code" type="text" size="50" value="'.self::$apisetting['purchase_code'].'" />
      <input type="submit" name="save" class="button button-primary" value="Save Changes">';
    if(empty(self::$apisetting['purchase_code'])){
      $content .= '<p class="howto">For how to get your private purchase code <a href="https://smartiolabs.com/blog/52/where-is-my-purchase-code/" target="_blank">click here</a></p>';
      $content .= '<p><input type="submit" name="startupdate" class="button button-primary" value="Start System Update" disabled="disabled"></p>';
    }
    elseif(!empty($lastupdate->version) && $lastupdate->version > SMAPIVERSION){
      $content .= '<p><input type="submit" name="startupdate" class="button button-primary" value="Start System Update"></p>';
    }
    $content .= '</form>';
    include(smapi_dir.'/pages/auto_update.php');
  }
  
  private static function updateNetworkPurchaseCode($purchase_code) {
    global $wpdb;
    $blogs = $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs");
    if($blogs){
      foreach($blogs as $blog){
        switch_to_blog($blog->blog_id);
        $settings = get_option('smapi_options');
        $settings['purchase_code'] = $purchase_code;
        update_option('smapi_options', $settings);
      }
      restore_current_blog();
    }
  }

  private static function delTree($dirPath) {
    if (!is_dir($dirPath)) {
      return;
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
      $dirPath .= '/';
    }
    $files = glob($dirPath.'*', GLOB_MARK);
    foreach ($files as $file) {
      if (is_dir($file)) {
        self::delTree($file);
      } else {
        unlink($file);
      }
    }
    rmdir($dirPath);
    return true;
  }

}
