<?php

class smapi_cache {
  private $cache_path;
  private $cache_status;
  private $cache_expire;
  public $cache_listener;

  public function __construct($status=0, $expire=0){
    $this->cache_status = $status;
    $this->cache_expire = $expire*3600;
    $upload_dir = wp_upload_dir();
    $this->cache_path = $upload_dir['basedir'].'/smio_cache';
  }

  public function generateConfig($api_basename, $cache_status, $cache_expire){
    if(file_exists($this->cache_path.'/config')){
      return;
    }
    $this->checkDirExist('/');
    global $wpdb;
    $config = [];
    $groups = $wpdb->get_results('SELECT name,`group` FROM '.$wpdb->prefix.'smapi_engine WHERE `group` IS NOT NULL', 'ARRAY_A');
    foreach($groups as $group){
      $config['endpoints'][$group['name']] = $group['group'];
    }
    $config['basename'] = $api_basename;
    $config['status'] = $cache_status;
    $config['expire'] = $cache_expire;
    $this->storelocalfile($this->cache_path.'/config', serialize($config));
  }

  public function store($endpoint, $data){
    $cache_config = unserialize($this->readlocalfile($this->cache_path.'/config'));
    $category = $cache_config['endpoints'][$endpoint];
    if(! in_array($category, ['getposts','authors','getpages','categories','getComments'])){
      return;
    }
    $this->checkDirExist($category);
    if($endpoint == 'appBootstrape'){
      $hash_name = $endpoint;
    }
    else{
      $hash_name = md5($_SERVER['REQUEST_URI'].http_build_query($_POST, '', '&amp;'));
    }
    $cache_file_path = $this->cache_path.'/'.$category.'/'.$hash_name;
    if($this->cache_status == 1){
      $this->storelocalfile($cache_file_path, $data);
    }
  }

  private function isExpired($path) {
    $last_mod_time = filemtime($path)+$this->cache_expire;
    if($last_mod_time < time()){
      return false;
    }
    return true;
  }

  private function checkDirExist($dir) {
    $dirpath = $this->cache_path.'/'.$dir;
    if(! file_exists($this->cache_path)){
      @mkdir($this->cache_path);
    }
    if(! file_exists($dirpath)){
      @mkdir($dirpath);
    }
  }

  private function readlocalfile($path) {
    if(function_exists('file_get_contents')){
      $content = file_get_contents($path);
    }
    elseif(function_exists('fopen') && function_exists('stream_get_contents')){
      $handler = fopen($path, 'rb');
      $content = stream_get_contents($handler);
      fclose($handler);
    }
    elseif(function_exists('readfile')){
      $content = readfile($path);
    }
    else{
      error_log('Server closes all saving functions fopen(), readfile(), file_get_contents() !');
      return false;
    }
    return $content;
  }

  private function storelocalfile($path, $contents) {
    if(function_exists('fopen')){
      $handle = fopen($path, 'w');
      fwrite($handle, $contents);
      fclose($handle);
    }
    elseif(function_exists('file_put_contents')){
      @file_put_contents($path, $contents);
    }
    else{
      error_log('Server closes all saving functions fopen(), file_put_contents() !');
    }
  }

  public function set_cache_listener($status) {
    $this->cache_listener = $status;
  }

  public function start_hooks() {
    if(!file_exists(ABSPATH.'/smart-bridge.php')){
      $bridge = $this->readlocalfile(smapi_dir.'/bridge.php');
      $bridge = str_replace('$cache_path = \'\';', '$cache_path = \''.$this->cache_path.'\';', $bridge);
      $this->storelocalfile(ABSPATH.'/smart-bridge.php', $bridge);
    }
    if($this->cache_listener == 0){
      return;
    }
    //posts authors categories comments

    add_action('deleted_user', array($this, 'emptyCacheAuthors'));
    add_action('edit_user_profile', array($this, 'emptyCacheAuthors'));
    add_action('user_register', array($this, 'emptyCacheAuthors'));

    add_action('delete_post', array($this, 'emptyCachePosts'));
    add_action('edit_post', array($this, 'emptyCachePosts'));
    add_action('post_updated', array($this, 'emptyCachePosts'));
    add_action('transition_post_status  ', array($this, 'emptyCachePosts'));
    add_action('publish_post', array($this, 'emptyCachePosts'));
    add_action('publish_page', array($this, 'emptyCachePosts'));
    add_action('publish_future_post', array($this, 'emptyCachePosts'));
    add_action('updated_postmeta', array($this, 'emptyCachePosts'));
    add_action('wp_insert_post', array($this, 'emptyCachePosts'));
    add_action('save_post', array($this, 'emptyCachePosts'));

    add_action('add_category', array($this, 'emptyCacheCategories'));
    add_action('create_category', array($this, 'emptyCacheCategories'));
    add_action('delete_category', array($this, 'emptyCacheCategories'));
    add_action('edit_category', array($this, 'emptyCacheCategories'));
    add_action('create_term', array($this, 'emptyCacheCategories'));
    add_action('edit_terms', array($this, 'emptyCacheCategories'));
    add_action('edit_term_taxonomy', array($this, 'emptyCacheCategories'));
    add_action('edit_term_taxonomies', array($this, 'emptyCacheCategories'));
    add_action('delete_term_taxonomy', array($this, 'emptyCacheCategories'));
    add_action('delete_term', array($this, 'emptyCacheCategories'));
    add_action('delete_term_relationships', array($this, 'emptyCacheCategories'));

    add_action('comment_post', array($this, 'emptyCacheComments'));
    add_action('edit_comment', array($this, 'emptyCacheComments'));
    add_action('delete_comment', array($this, 'emptyCacheComments'));
    add_action('wp_insert_comment', array($this, 'emptyCacheComments'));
    add_action('wp_set_comment_status', array($this, 'emptyCacheComments'));
  }

  public function emptyCacheAuthors() {
    $this->emptyCache('authors');
  }
  public function emptyCacheComments() {
    $this->emptyCache('getComments');
  }
  public function emptyCachePosts() {
    $this->emptyCache('getposts');
  }
  public function emptyCacheCategories() {
    $this->emptyCache('categories');
  }
  
  private function emptyCache($category) {
    $dirpath = $this->cache_path.'/'.$category;
    if(file_exists($dirpath)){
      $this->delDir($dirpath);
    }
  }

  public function emptyCacheEntry($category, $hash) {
    $entryPath = $this->cache_path.'/'.$category.'/'.$hash;
    if(file_exists($entryPath)){
      unlink($entryPath);
    }
  }

  public function purgeCache() {
    if(file_exists($this->cache_path)){
      $this->delDir($this->cache_path);
    }
  }

  private function delDir($dir, $filesonly=false) {
    $structure = glob(rtrim($dir, "/").'/*');
    if(is_array($structure)){
      foreach($structure as $file){
        if (is_dir($file)){
          $this->delDir($file);
        }
        elseif (is_file($file)){
          @unlink($file);
        }
      }
    }
    if($filesonly === false){
      @rmdir($dir);
    }
  }

}