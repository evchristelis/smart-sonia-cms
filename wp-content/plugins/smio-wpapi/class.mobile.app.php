<?php

class smapi_mobapp extends smapi_controller{

  public function __construct(){}

  public static function settings(){
    if (isset($_GET['loadtaxs'])) {
      if(empty($_GET['smioapi_post_type'])){
        echo '';
        exit;
      }
      $html = '<option value=""></option>';
      $taxonomy_objects = get_object_taxonomies($_GET['smioapi_post_type'], 'objects');
      foreach ($taxonomy_objects as $type => $object){
        $html .= '<option value="'.$type.'">'.$type.'</option>';
      }
      echo $html;
      exit;
    }
    elseif (isset($_GET['loadcats'])) {
      if(empty($_GET['smioapi_object_name'])){
        echo '';
        exit;
      }
      $json = array();
      $json['checklist'] = wp_terms_checklist(0, array('taxonomy' => $_GET['smioapi_object_name'], 'echo' => false));

      $json['options'] = '';
      $terms = get_terms(array('taxonomy' => $_GET['smioapi_object_name'], 'hide_empty' => false));
      foreach ($terms as $term){
        $json['options'] .= '<option value="'.$term->term_id.'">'.$term->name.'</option>';
      }
      echo json_encode($json);
      exit;
    }

    $pageurl = admin_url().'admin.php?page=smapi_mobapp';
    self::load_jsplugins();

    if($_POST){
      if(SMAPIDEMO){
        echo 1;
        exit;
      }
      self::$apisetting['mob_categories'] = $_POST['mob_categories'];
      self::$apisetting['mob_pages'] = $_POST['mob_pages'];
      self::$apisetting['mob_cat_post_type'] = $_POST['mob_cat_post_type'];
      self::$apisetting['mob_cat_post_type_tax'] = $_POST['mob_cat_post_type_tax'];
      self::$apisetting['mob_home_cover'] = $_POST['mob_home_cover'];
      self::$apisetting['mob_headtitle'] = $_POST['mob_headtitle'];
      self::$apisetting['mob_home_catids'] = (empty($_POST['mob_home_catids']))? array() : $_POST['mob_home_catids'];
      self::$apisetting['mob_contact_photo1'] = $_POST['mob_contact_photo1'];
      self::$apisetting['mob_contact_photo2'] = $_POST['mob_contact_photo2'];
      self::$apisetting['mob_contact_photo3'] = $_POST['mob_contact_photo3'];
      self::$apisetting['mob_contact_photo4'] = $_POST['mob_contact_photo4'];
      self::$apisetting['mob_contact_name'] = $_POST['mob_contact_name'];
      self::$apisetting['mob_contact_desc'] = $_POST['mob_contact_desc'];
      self::$apisetting['mob_contact_lat'] = $_POST['mob_contact_lat'];
      self::$apisetting['mob_contact_lng'] = $_POST['mob_contact_lng'];
      self::$apisetting['mob_contact_address'] = $_POST['mob_contact_address'];
      self::$apisetting['mob_contact_website'] = $_POST['mob_contact_website'];
      self::$apisetting['mob_contact_email'] = $_POST['mob_contact_email'];
      self::$apisetting['mob_contact_phone'] = $_POST['mob_contact_phone'];
      self::$apisetting['mob_contact_rating'] = $_POST['mob_contact_rating'];
      self::$apisetting['mob_feeds_style'] = $_POST['mob_feeds_style'];
      self::$apisetting['mob_feeds_contsource'] = $_POST['mob_feeds_contsource'];
      self::$apisetting['mob_common_iosappid'] = $_POST['mob_common_iosappid'];
      self::$apisetting['mob_common_andappid'] = $_POST['mob_common_andappid'];
      self::$apisetting['mob_common_winappid'] = $_POST['mob_common_winappid'];
      self::$apisetting['mob_common_iosadid'] = $_POST['mob_common_iosadid'];
      self::$apisetting['mob_common_andadid'] = $_POST['mob_common_andadid'];
      self::$apisetting['mob_common_adtype'] = $_POST['mob_common_adtype'];
      self::$apisetting['mob_cache_expire'] = $_POST['mob_cache_expire'];
      self::$apisetting['mob_metakey_lat'] = $_POST['mob_metakey_lat'];
      self::$apisetting['mob_metakey_lng'] = $_POST['mob_metakey_lng'];
      self::$apisetting['mob_gmaps_apikey'] = $_POST['mob_gmaps_apikey'];

      self::$apisetting['mob_menu_nearby'] = isset($_POST['mob_menu_nearby'])? 1 : 0;
      self::$apisetting['mob_menu_subscription'] = isset($_POST['mob_menu_subscription'])? 1 : 0;
      self::$apisetting['mob_menu_notfhistory'] = isset($_POST['mob_menu_notfhistory'])? 1 : 0;
      self::$apisetting['mob_menu_contactus'] = isset($_POST['mob_menu_contactus'])? 1 : 0;
      self::$apisetting['mob_home_catmetro'] = isset($_POST['mob_home_catmetro'])? 1 : 0;
      self::$apisetting['mob_home_popular'] = isset($_POST['mob_home_popular'])? 1 : 0;
      self::$apisetting['mob_home_recent'] = isset($_POST['mob_home_recent'])? 1 : 0;
      self::$apisetting['mob_home_iosads'] = isset($_POST['mob_home_iosads'])? 1 : 0;
      self::$apisetting['mob_home_andads'] = isset($_POST['mob_home_andads'])? 1 : 0;
      self::$apisetting['mob_feeds_fimage'] = isset($_POST['mob_feeds_fimage'])? 1 : 0;
      self::$apisetting['mob_post_fimage'] = isset($_POST['mob_post_fimage'])? 1 : 0;
      self::$apisetting['mob_post_showcomms'] = isset($_POST['mob_post_showcomms'])? 1 : 0;
      self::$apisetting['mob_post_addcomms'] = isset($_POST['mob_post_addcomms'])? 1 : 0;
      self::$apisetting['mob_post_author'] = isset($_POST['mob_post_author'])? 1 : 0;
      self::$apisetting['mob_post_categories'] = isset($_POST['mob_post_categories'])? 1 : 0;
      self::$apisetting['mob_post_iosads'] = isset($_POST['mob_post_iosads'])? 1 : 0;
      self::$apisetting['mob_post_andads'] = isset($_POST['mob_post_andads'])? 1 : 0;
      self::$apisetting['mob_common_gps'] = isset($_POST['mob_common_gps'])? 1 : 0;
      self::$apisetting['mob_common_push'] = isset($_POST['mob_common_push'])? 1 : 0;
      self::$apisetting['mob_menu_follow'] = isset($_POST['mob_menu_follow'])? 1 : 0;
      self::$apisetting['mob_debug_ads'] = isset($_POST['mob_debug_ads'])? 1 : 0;

      self::$apisetting = array_map('wp_slash', self::$apisetting);
      update_option('smapi_options', self::$apisetting);

      $cache = new smapi_cache(self::$apisetting['cache_status'], self::$apisetting['cache_expire']);
      $cache->emptyCacheEntry('getposts', 'appBootstrape');

      echo 1;
      die();
    }
    else{
      wp_enqueue_script('nav-menu');
      wp_enqueue_script('postbox');
      wp_enqueue_script('smapi-mobile-settings');
      add_thickbox();
      include(smapi_dir.'/pages/app_settings.php');
    }
  }

  public static function save_gps_metakeys($postid, $post){
    if(self::$apisetting['mob_menu_nearby'] == 0){
      return;
    }
    if(!empty($_POST['smapi_latitude']) && !empty($_POST['smapi_longitude'])){
      update_post_meta($postid, 'smart_latitude', $_POST['smapi_latitude']);
      update_post_meta($postid, 'smart_longitude', $_POST['smapi_longitude']);
    } else {
      update_post_meta($postid, 'smart_latitude', '');
      update_post_meta($postid, 'smart_longitude', '');
    }
  }

  public static function gps_meta_box_design($post){
    $latitude = get_post_meta($post->ID, 'smart_latitude', true);
    $longitude = get_post_meta($post->ID, 'smart_longitude', true);
    if(empty($latitude)){
      $latitude = '';
    }
    if(empty($longitude)){
      $longitude = '';
    }
    include(smapi_dir.'/pages/gps_meta_box.php');
  }

  public static function build_gps_meta_box(){
    if(self::$apisetting['mob_menu_nearby'] == 0){
      return;
    }
    wp_enqueue_script('smpush-gmap-source', 'https://maps.googleapis.com/maps/api/js?v=3.exp&key='.self::$apisetting['mob_gmaps_apikey'], array('jquery'), SMAPIVERSION);
    add_meta_box('smapi-meta-box', 'GPS Location For Smart Mobile App', array('smapi_mobapp', 'gps_meta_box_design'), null, 'normal', 'high');
  }

}