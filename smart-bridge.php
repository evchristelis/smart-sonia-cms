<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$_SERVER['REQUEST_URI'] = str_replace(array('%3F','%26','/smart-bridge.php?to='), ['?','&',''], $_SERVER['REQUEST_URI']);

$cache_path = '/var/www/vhosts/terramine.gr/httpdocs/wp-content/uploads/smio_cache';
$cache_config_path = $cache_path.'/config';

if(! file_exists($cache_config_path)){
  redirect();
}
$cache_config = unserialize(readlocalfile($cache_config_path));
if($cache_config['status'] == 0){
  redirect();
}
if(strpos($_SERVER['REQUEST_URI'], '/'.$cache_config['basename'].'/') !== false){
  preg_match('/' . $cache_config['basename'] . '\/([A-Za-z]+)\//', $_SERVER['REQUEST_URI'], $endpoint);
  $endpoint = $endpoint[1];
  $endpointDir = $cache_config['endpoints'][$endpoint];
  if(!in_array($endpointDir, ['getposts', 'authors', 'getpages', 'categories'])){
    redirect();
  }
  if($endpoint == 'appBootstrape'){
    $hash_name = $endpoint;
  } else{
    $hash_name = md5($_SERVER['REQUEST_URI'] . http_build_query($_POST, '', '&amp;'));
  }
  $cache_file_path = $cache_path . '/' . $endpointDir . '/' . $hash_name;
  if(file_exists($cache_file_path)){
    if($endpoint != 'appBootstrape' && (filemtime($cache_file_path)+($cache_config['expire']*3600)) < time()){
      @unlink($cache_file_path);
      redirect();
    }
    echo readlocalfile($cache_file_path);
    exit;
  }
  else{
    redirect();
  }
}

function redirect(){
  $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?  "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  //header("HTTP/1.1 301 Moved Permanently");
  //header("Location: ".$link);

  $params = array(
    'http' => array(
      'method' => 'POST',
      'content' => http_build_query($_POST)
    )
  );
  $headers = null;
  if (!is_null($headers)) {
    $params['http']['header'] = '';
    foreach ($headers as $k => $v) {
      $params['http']['header'] .= "$k: $v\n";
    }
  }
  $ctx = stream_context_create($params);
  $fp = @fopen($link, 'rb', false, $ctx);
  if ($fp) {
    echo @stream_get_contents($fp);
    die();
  } else {
    // Error
    throw new Exception("Error loading '$link', $php_errormsg");
  }

  exit;
}

function getallheaders() {
  if (function_exists('getallheaders')){
    return getallheaders();
  }
  else{
    $headers = [];
    foreach ($_SERVER as $name => $value){
      if (substr($name, 0, 5) == 'HTTP_'){
        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
      }
    }
    return $headers;
  }
}

function readlocalfile($path) {
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