<?php

class smapi_helper {
  private static $paging = array(
  'stillmore' => 0,
  'perpage' => 0,
  'callpage' => 0,
  'next' => 0,
  'previous' => 0,
  'pages' => 0,
  'result' => 0
  );
  private $cdata_tags = array('post_title','post_content','comment_content','avatar','featuredimage','featuredthumb','description','youtube','custom_fields','authormeta','commentmeta','postmeta');
  public $ParseOutput;
  public $functionInUse;
  public $result;
  public $curl_status;

  public function __construct(){}

  public function buildCurl($url, $ssl = false, $postfields = false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.6 (KHTML, like Gecko) Chrome/16.0.897.0 Safari/535.6');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    if ($ssl !== false) {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($ch, CURLOPT_CAINFO, smiopubap_lib.'/cacert.pem');
    } else {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    if(!empty($postfields)){
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    }
    if(defined('WP_PROXY_HOST')){
      curl_setopt($ch, CURLOPT_PROXY, WP_PROXY_HOST);
      curl_setopt($ch, CURLOPT_PROXYPORT, WP_PROXY_PORT);
      curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
      if(defined('WP_PROXY_USERNAME')){
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, WP_PROXY_USERNAME.':'.WP_PROXY_PASSWORD);
        curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_ANY);
      }
    }
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    $result = curl_exec($ch);
    $this->curl_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $result;
  }

  public function Paging($sql){
    $sql = str_replace(array('{pre_select}','{after_select}','{pre_where}','{after_where}','{pre_order}','{after_order}',), '', $sql);
    if(!$this->ParseOutput){
      return $sql;
    }
    global $wpdb;
  	if(isset($_REQUEST['perpage'])) $limit = $_REQUEST['perpage'];
  	else $limit = 10;
    if($limit > $this->get_option('max_perpage')){
      $limit = $this->get_option('max_perpage');
    }
  	if(isset($_REQUEST['callpage'])) $currentpage = $_REQUEST['callpage'];
  	else $currentpage = 1;

  	if(isset($_REQUEST['perpage']) && $_REQUEST['perpage'] == -1) return $sql;

    /*
    if(preg_match('/group by ([a-zA-Z0-9`*(),._\n\r]+)\s?/i', $sql, $match)){
      $cselect = 'DISTINCT('.$match[1].')';
      $countsql = preg_replace('/group by ([a-zA-Z0-9`*(),._\n\r\s]+)\s?/i', '', $sql);
    }
    else{
      $cselect = '*';
      $countsql = $sql;
    }
    if(preg_match('/XSELECT/i', $countsql, $matches)){
      $countsql = preg_replace('/XSELECT (.*) XFROM/i', 'SELECT COUNT('.$cselect.') FROM', $countsql);
      $count = $wpdb->get_var($countsql);
      $sql = str_replace(array('XSELECT','XFROM'), array('SELECT','FROM'), $sql);
    }
    else{
      $count = count($wpdb->get_results($countsql));
    }
    if($wpdb->num_rows > 1)
      $count = $wpdb->num_rows;
    */
    $count = count($wpdb->get_results($sql));
    if($count == 0)
        return $sql;
  	$pages = $count/$limit;
  	$pages = ceil($pages);

  	if($currentpage < $pages)
  		self::$paging['stillmore'] = 1;
  	else{
  		$currentpage = $pages;
  		self::$paging['stillmore'] = 0;
  	}
  	if($currentpage == 1){
  		self::$paging['previous'] = 0;
  		self::$paging['next'] = $currentpage+1;
  	}
  	elseif($currentpage == $pages){
  		self::$paging['previous'] = $currentpage-1;
  		self::$paging['next'] = 0;
  	}
  	else{
  		self::$paging['previous'] = $currentpage-1;
  		self::$paging['next'] = $currentpage+1;
  	}

    self::$paging['result'] = $count;
    self::$paging['pages'] = $pages;
    self::$paging['perpage'] = $limit;
    self::$paging['callpage'] = $currentpage;

  	if($currentpage > 0) $currentpage--;
  	$from = $currentpage*$limit;
  	return $sql." LIMIT $from,$limit";
  }

  public function queryBuild($sql, $arg, $force_where=false){
    global $wpdb;
    if(isset($arg['like'])){
      foreach($arg['like'] AS $index=>$value){
        $index = $this->replacePrefix($index, $wpdb->prefix);
        $where[] = "$index LIKE '$value'";
      }
    }
    if(isset($arg['likeor'])){
      foreach($arg['likeor'] AS $index=>$value){
        $index = $this->replacePrefix($index, $wpdb->prefix);
        $likeor[] = "$index LIKE '$value'";
      }
      $where[] = '('.implode(' OR ', $likeor).')';
    }
    if(isset($arg['in'])){
      foreach($arg['in'] AS $index=>$value){
        if(is_array($value)){
          $index = $this->replacePrefix($index, $wpdb->prefix);
          foreach($value AS $value2){
            $where[] = "$index IN ($value2)";
          }
        }
        else{
          $index = $this->replacePrefix($index, $wpdb->prefix);
          $where[] = "$index IN ($value)";
        }
      }
    }
    if(isset($arg['notin'])){
      foreach($arg['notin'] AS $index=>$value){
        $index = $this->replacePrefix($index, $wpdb->prefix);
        $where[] = "$index NOT IN ($value)";
      }
    }
    if(isset($arg['between'])){
      foreach($arg['between'] AS $index=>$value){
        $index = $this->replacePrefix($index, $wpdb->prefix);
        $where[] = "$index='$value' BETWEEN $value[0] AND $value[1]";
      }
    }
    if(isset($arg['date'])){
      foreach($arg['date'] AS $tb=>$value){
        $tb = $this->replacePrefix($tb, $wpdb->prefix);
        foreach($value AS $index=>$key){
          $where[] = "$key[index]($tb)='$key[value]'";
        }
      }
    }
    if(isset($arg['where'])){
      foreach($arg['where'] AS $index=>$value){
        $index = $this->replacePrefix($index, $wpdb->prefix);
        $where[] = "$index='$value'";
      }
    }
    if(isset($where))
        $where = 'WHERE '.implode(' AND ', $where);
    elseif($force_where)
      $where = 'WHERE 1';
    else
        $where = '';
    if(isset($arg['orderby'])){
      $arg['orderby'] = $this->replacePrefix($arg['orderby'], $wpdb->prefix);
      $order = 'ORDER BY '.$arg['orderby'].' '.$arg['order'];
    }
    else
        $order = '';
    return str_replace(array('{where}','{order}'), array($where, $order), $sql);
  }

  private function replacePrefix($query, $prefix){
    if(preg_match_all("/{([a-zA-Z0-9_]+)}/", $query, $matches)){
      global $wpdb;
      $match = $matches[1][0];
      $query = str_replace('{'.$match.'}', $wpdb->$match, $query);
    }
    else{
      $query = $prefix.$query;
    }
    return $query;
  }

  public function output($respond, $result, $cache=false){
    if($this->ParseOutput === false || (defined('SMAPI_RETURN_OUTPUT_START') && !defined('SMAPI_RETURN_OUTPUT_END'))){
      $this->ParseOutput = true;
      if(is_array($result))
        return $result;
      else
        return array();
    }
    if($this->functionInUse){
      if($respond == 0 && $result != 'No result found'){
        throw new Exception($result);
      }
      if(is_array($result)){
        $this->result = $result;
        return true;
      }
      else{
        $this->result = array();
        return $this->result;
      }
    }
    ob_end_clean();
    ob_start();
    if($this->get_option('output_type') == 'json'){
      self::jsonPrint($respond, $result);
    }
    elseif($this->get_option('output_type') == 'jsonp'){
      self::jsonPPrint($respond, $result, $this->get_option('jsonp_param'));
    }
    elseif($this->get_option('output_type') == 'xml'){
      $this->xmlPrint($respond, $result);
    }
    if($cache !== false && $cache['cache_status'] == 1){
      $cacheHandler = new smapi_cache($cache['cache_status'], $cache['cache_expire']);
      $cacheHandler->store($cache['method'], ob_get_contents());
    }
    if(empty($_REQUEST['smapi_noexit'])){
      die();
    }
  }

  public function xmlPrint($respond, $result){
    header('Content-Type: application/xml');
    echo '<?xml version="1.0" encoding="'.DB_CHARSET.'"?><Data>';
  	if(is_array($result)){
      $custom_tags = $this->get_option('cdata_tags');
      if(!empty($custom_tags)){
        $this->cdata_tags = array_merge($this->cdata_tags, explode(',', $custom_tags));
      }
      echo '<respond>'.$respond.'</respond>';
      echo '<message></message>';
      echo '<paging>';
      echo $this->xmlPaging();
      echo '</paging>';
      echo '<result>';
      echo $this->xmlArrayLoop($result);
      echo '</result>';
  	}
  	else{
        echo '<respond>'.$respond.'</respond>';
        echo '<paging>';
        echo $this->xmlPaging();
        echo '</paging>';
        echo '<message>'.$result.'</message>';
        echo '<result></result>';
  	}
    echo '</Data>';
  }

  public function xmlArrayLoop($array, $xmlkey=0, $noindex=true, $allcdata=false){
    if(is_numeric($xmlkey)) $xmlkey = 'child';
    if(!$noindex) echo '<'.$xmlkey.'>';
    foreach($array AS $key=>$value){
      if(is_array($value) OR is_object($value)){
        if(!is_numeric($key) AND in_array($key, $this->cdata_tags)){
          $allcdata = true;
        }
        $this->xmlArrayLoop($value, $key, false, $allcdata);
      }
      elseif($allcdata OR in_array($key, $this->cdata_tags)){
        if(is_numeric($key)) $key = 'child';
        echo '<'.$key.'>';
        echo '<![CDATA['.$value.']]>';
        echo '</'.$key.'>';
      }
      else{
        if(is_numeric($key)) $key = 'child';
        echo '<'.$key.'>';
        echo $value;
        echo '</'.$key.'>';
      }
    }
    if(!$noindex) echo '</'.$xmlkey.'>';
  }

  public function xmlPaging(){
    foreach(self::$paging AS $key=>$value){
      echo '<'.$key.'>';
      echo $value;
      echo '</'.$key.'>';
    }
  }

  public static function jsonPrint($respond, $result){
    header('Content-Type: application/json');
    $json = array();
  	if(is_array($result)){
  		$json['respond'] = $respond;
  		$json['paging'] = self::$paging;
        $json['message'] = '';
        $json['result'] = $result;
  	}
  	else{
  		$json['respond'] = $respond;
        $json['paging'] = self::$paging;
  		$json['message'] = $result;
        $json['result'] = array();
  	}
  	echo json_encode($json);
  }

  public static function jsonPPrint($respond, $result, $callback){
    header('Content-Type: application/json');
    $json = array();
  	if(is_array($result)){
  		$json['respond'] = $respond;
  		$json['paging'] = self::$paging;
      $json['message'] = '';
      $json['result'] = $result;
  	}
  	else{
  		$json['respond'] = $respond;
      $json['paging'] = self::$paging;
  		$json['message'] = $result;
      $json['result'] = array();
      $json['result'] = array();
  	}
  	echo (isset($_REQUEST[$callback]))?$_REQUEST[$callback]:'result','(',json_encode($json),')';
  }

  public function convertInts($ints){
    if(empty($ints)) return $ints;
    foreach($ints as $key => $int){
      if(is_numeric($int)){
        $ints[$key] = (int) $int;
      }
    }
    return $ints;
  }

  public function CheckParams($params, $or=false){
    if(! is_array($params)){
        $this->output(0, 'Parameters `'.$params.'` is required');
    }
    $indexes = [];
    foreach($params AS $param){
        if(!isset($_REQUEST[$param]) OR empty($_REQUEST[$param])){
            if($or) $indexes[] = $param;
            else $this->output(0, 'Parameter `'.$param.'` is required, All required parameters are `'.implode($params, '`,`').'`');
        }
        elseif($or) return;
    }
    if($or){
        $this->output(0, 'Parameters `'.implode($params, '`,`').'` at least one of them is required');
    }
  }

  public static function ShortString($string, $charcount){
    $lenght = strlen($string);
    if($lenght > $charcount){
      $string = substr($string, 0, $charcount).'...';
      return $string;
    }
    else{
      return $string;
    }
  }

  public static function Security($value){
  	if(! is_numeric($value)){
  		if(is_array($value)){
  			foreach($value AS $key=>$v){
  				if(is_array($v)) $value[$key] = Security($v);
  				else{
  					if(get_magic_quotes_gpc())
  						$value[$key] = htmlspecialchars(trim($v));
  					else
  						$value[$key] = htmlspecialchars(addslashes(trim($v)));
  				}
  			}
  		}
  		else{
  			if(get_magic_quotes_gpc())
  				$value = htmlspecialchars(trim($value));
  			else
  				$value = htmlspecialchars(addslashes(trim($value)));
  		}
  	}
  	return $value;
  }

  public static function saltHash($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public function checkHeaderSession(){
    if (function_exists('getallheaders')){
      foreach(getallheaders() as $name => $value){
        if($name == 'SESSION_ID'){
          session_id($value);
          session_start();
          if(!empty($_SESSION['smio_user_id'])){
            wp_set_current_user($_SESSION['smio_user_id']);
            return;
          }
        }
      }
    }
    elseif (function_exists('apache_request_headers')){
      foreach(apache_request_headers() as $name => $value){
        if($name == 'SESSION_ID'){
          session_id($value);
          session_start();
          if(!empty($_SESSION['smio_user_id'])){
            wp_set_current_user($_SESSION['smio_user_id']);
            return;
          }
        }
      }
    }
    if(!empty($_REQUEST['SESSION_ID'])){
      session_id($_REQUEST['SESSION_ID']);
      session_start();
      if(!empty($_SESSION['smio_user_id'])){
        wp_set_current_user($_SESSION['smio_user_id']);
      }
    }
  }

  public static function checkReqHeader($detect){
    $return = false;
    if (function_exists('getallheaders')){
      foreach(getallheaders() as $name => $value){
        if($name == $detect){
          $return = $value;
        }
      }
    }
    elseif (function_exists('apache_request_headers')){
      foreach(apache_request_headers() as $name => $value){
        if($name == $detect){
          $return = $value;
        }
      }
    }
    if(empty($return) && !empty($_REQUEST[$detect])){
      return $_REQUEST[$detect];
    }
    return $return;
  }

  public function ExtractImgSrc($html){
    preg_match('/src=[\'|"]([^"\']*)/i', $html, $match);
    return $match[1];
  }

}
