<?php

class smapi_documentation {
  private $allScope;

  public function __construct(){}

  public function build($allScope){
    $this->allScope = $allScope;

    $request = array();
    $request['oauth'] = array(
    'request_token' => 'Request new access token',
    'refresh_token' => 'Refresh expired access token'
    );
    $request['user'] = array(
    'login' => 'Login',
    'signup' => 'Singup',
    'social' => 'Social signup and login',
    'edit_profile' => 'Edit the user profile',
    'logout' => 'Logout',
    'lostpwd' => 'Lost password',
    'changepwd' => 'Change user password',
    'profile_image' => 'Change user profile image',
    'authors' => 'List of users',
    'get_author' => 'Get the full profile for any user',
    'author_posts' => 'List of posts published by an author',
    'posts_subscribedin' => 'Get posts that user comment in it'
    );
    $request['posts'] = array(
    'newpost' => 'Publish new post',
    'updatepost' => 'Update or edit a post',
    'upload_media' => 'Upload media file',
    'getposts' => 'Get posts by category or custom taxonomy',
    'search_posts' => 'Search in posts',
    'last_posts' => 'Last posts in blog',
    'popular_posts' => 'Popular Posts in range days',
    'getposts_format' => 'Get posts by post format',
    'getpost' => 'View post by id',
    'get_archive' => 'Archive',
    'get_posts_archive' => 'Get posts by archive time',
    'menu_items' => 'Retrieve items of menu'
    );
    $request['comments'] = array(
    'getComments' => 'Get comments of post',
    'newcomment' => 'Comment in a post',
    'updatecomment' => 'Update or edit a comment',
    'last_comments' => 'Last comments in the blog',
    'get_comment' => 'View a comment by id'
    );
    $request['pages'] = array(
    'getpages' => 'Get a list of pages',
    'getpage' => 'View a page by ID'
    );
    $request['cats'] = array(
    'categories' => 'Get a list of all categories',
    'viewcategory' => 'View a category by ID'
    );
     $request['taxonomies'] = array(
    'get_taxonomies' => 'Get a list of all custom taxonomies',
    'get_taxonomy' => 'View custom taxonomy object'
    );
    $request['tags'] = array(
    'tags' => 'Get a list of all tags',
    'tag_posts' => 'Get posts by tag'
    );
    $request['bbpress'] = array(
    'bb_new_topic' => 'Add new topic',
    'bb_new_comment' => 'Add new reply'
    );
    $request['services'] = array(
    'network_sites' => 'Display the list of network sites',
    'social_links' => 'Social accounts links and stats',
    'bloginfo' => 'Blog options and information',
    'contactus' => 'Contact Wordpress administrator'
    );
    $request['admin'] = array(
    'post_status' => 'Change the post status',
    'comment_status' => 'Change the comment status',
    'delete_user' => 'Delete user permanently',
    'delete_post' => 'Delete post permanently',
    'delete_comment' => 'Delete comment permanently'
    );
    $request['customise'] = array(
    'custom_service' => 'Call one of the custom services you made',
    'custom_options' => 'Get a list of all custom options value',
    );
    $request['push'] = array(
    'savetoken' => 'Save new device token',
    'channels_subscribe' => 'Edit the device subscription in channels',
    'device_channels' => 'Get a list of channels and whichever device subscribed',
    'get_channels' => 'Get the list of all channels'
    );

    foreach($request AS $key=>$value){
      foreach($value AS $model=>$title){
        $api[$model] = $this->$model();
      }
    }

    $document['api'] = $api;
    $document['links'] = $request;
    $document['group'] = array(
    'user' => 'User operations',
    'oauth' => 'OAuth2 Authentication',
    'posts' => 'Posts',
    'comments' => 'Comments',
    'pages' => 'Pages',
    'cats' => 'Categories',
    'taxonomies' => 'Taxonomies',
    'tags' => 'Tags',
    'bbpress' => 'bbPress',
    'services' => 'Services',
    'admin' => 'Management Actions',
    'customise' => 'Custom Services & Options',
    'push' => 'Push Notification'
    );
    return $document;
  }

  public function request_token(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['CONSUMER_KEY'] = array(
    'description' => 'It can be sent in the HEADER of request also.',
    'type' => 'string',
    'required' => true
    );
    $api['params']['SECRET_KEY'] = array(
    'description' => 'It can be sent in the HEADER of request also.',
    'type' => 'string',
    'required' => true
    );
    $api['params']['auth_key'] = array(
    'description' => 'In case of you enabled normal authentication with access token capability option, you should send this parameter only.<br>It can be sent in the HEADER of request also.',
    'type' => 'string',
    'required' => false
    );
    $api['params']['scope'] = array(
      'description' => 'Available scope ['.implode(', ', $this->allScope).']',
      'type' => 'string',
      'required' => true
    );
    $api['example'] = 'request_token/?scope=public,core,posts,comments';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = false;
    $api['errors'] = array(
    'Invalid oAuth 2.0 cardinalities',
    'You have consumed the allowed API requests quota per month.',
    'This client cardinalities is disabled'
    );
    return $api;
  }

  public function refresh_token(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['ACCESS_TOKEN'] = array(
      'description' => 'It can be sent in the HEADER of request also.<br>Or send it as bearer authorization like<br><code>Authorization: Bearer ACCESS_TOKEN_VALUE_HERE</code>',
      'type' => 'string',
      'required' => true
    );
    $api['params']['scope'] = array(
      'description' => 'Available scope ['.implode(', ', $this->allScope).']',
      'type' => 'string',
      'required' => true
    );
    $api['example'] = 'refresh_token/?scope=public,core,posts,comments';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = false;
    $api['errors'] = array(
    'Invalid oAuth 2.0 cardinalities',
    'You have consumed the allowed API requests quota per month.',
    'This client cardinalities is disabled'
    );
    return $api;
  }

  public function lostpwd(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['username'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['params']['email'] = array(
    'description' => '',
    'type' => 'email',
    'required' => false
    );
    $api['example'] = 'lostpwd/?{api_key}username=smartiolabs';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = false;
    $api['errors'] = array(
    'Sorry, Did not find user with this entry',
    'The E-mail could not be sent may be your host disabled the mail() public function'
    );
    return $api;
  }

  public function login(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['username'] = array(
    'description' => '',
    'type' => 'string',
    'required' => true
    );
    $api['params']['password'] = array(
    'description' => '',
    'type' => 'string',
    'required' => true
    );
    $api['params']['remember'] = array(
    'description' => 'save login info in cookies to login later',
    'type' => '(boolean) true or false value',
    'required' => false
    );
    $api['example'] = 'login/?{api_key}username=admin&password=demo';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_author_filter';
    $api['wp_query_filter'] = 'smio_api_author_sql_filter';
    $api['errors'] = array(
    'Sorry, Enter wrong username or password',
    'Sorry, The user is not a member of the given blog'
    );
    return $api;
  }

  public function signup(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['username'] = array(
    'description' => '',
    'type' => 'string',
    'required' => true
    );
    $api['params']['display_name'] = array(
    'description' => 'Author full name',
    'type' => 'string',
    'required' => false
    );
    $api['params']['password'] = array(
    'description' => '',
    'type' => 'string',
    'required' => true
    );
    $api['params']['email'] = array(
    'description' => '',
    'type' => 'email',
    'required' => true
    );
    $api['params']['user_url'] = array(
    'description' => 'A string containing the user\'s URL for the user\'s web site',
    'type' => 'URL',
    'required' => false
    );
    $api['params']['first_name'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['params']['last_name'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['params']['description'] = array(
    'description' => 'A string containing content about the user',
    'type' => 'string',
    'required' => false
    );
    $api['params']['custom_meta'] = array(
    'description' => 'Set the meta and custom meta values for the user',
    'type' => 'JSON string like {"key1":"value1","key2":"value2","key3":"value3"}',
    'required' => false
    );
    $api['params']['custom_field'] = array(
    'description' => 'Set the custom field values for the user',
    'type' => 'JSON string like {"fieldkey1":"fieldvalue1","fieldkey2":"fieldvalue2"}',
    'required' => false
    );
    $api['params']['userimg_fieldkey'] = array(
    'description' => 'The custom field key of the profile image',
    'type' => 'string',
    'requiredtxt' => 'Required if you want to set the profile image'
    );
    $api['params']['thumbnail_id'] = array(
    'description' => 'Set the profile image for the user. Note: you can get ID value from `upload_media` service',
    'type' => 'int',
    'required' => false
    );
    $api['params']['file'] = array(
    'description' => 'Send in the array $_FILES like $_FILES[\'file\'] and set as the profile image for the user',
    'type' => '$_FILES',
    'required' => false
    );
    $api['params']['size'] = array(
    'description' => 'In case of upload profile image you can select an optional size',
    'type' => 'string choose(thumbnail, medium, large or full) default(thumbnail)',
    'required' => false
    );
    $api['example'] = 'signup/?{api_key}username=smartiolabs&password=123&email=support@smartiolabs.com';
    $api['note'] = 'Feature of custom fields depends on <a href="http://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_author_filter';
    $api['wp_query_filter'] = 'smio_api_author_sql_filter';
    $api['errors'] = array(
    'Sorry, that username already exists!',
    'Sorry, Registeration is closed',
    'E-mail address not valid',
    'URL is not valid',
    'Sorry, that email address is already used!',
    );
    return $api;
  }

  public function social(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['username'] = array(
    'description' => '',
    'type' => 'string',
    'required' => true
    );
    $api['params']['socialid'] = array(
    'description' => 'UID from the social website API like Facebook ID and Twitter ID',
    'type' => 'int',
    'required' => true
    );
    $api['params']['token'] = array(
    'description' => 'Token always return with UID and other information from the API social site',
    'type' => 'string',
    'required' => true
    );
    $api['params']['socialtype'] = array(
    'description' => 'Social site name and must be unique for every website like facebook, twitter',
    'type' => 'string',
    'required' => true
    );
    $api['params']['email'] = array(
    'description' => 'User\'s social email if you can get it and if not it\'s not required',
    'type' => 'string',
    'required' => false
    );
    $api['params']['custom_meta'] = array(
    'description' => 'Set the meta and custom meta values for the user',
    'type' => 'JSON string like {"key1":"value1","key2":"value2","key3":"value3"}',
    'required' => false
    );
    $api['params']['custom_field'] = array(
    'description' => 'Set the custom field values for the user',
    'type' => 'JSON string like {"fieldkey1":"fieldvalue1","fieldkey2":"fieldvalue2"}',
    'required' => false
    );
    $api['params']['userimg_metakey'] = array(
    'description' => 'The field name of meta key for the profile image',
    'type' => 'string',
    'requiredtxt' => 'Required if you want to set the profile image'
    );
    $api['params']['thumbnail_id'] = array(
    'description' => 'Set the profile image for the user. Note: get ID value from `upload_media` service',
    'type' => 'int',
    'required' => false
    );
    $api['params']['file'] = array(
    'description' => 'Send in the array $_FILES like $_FILES[\'file\'] and set as the profile image for the user',
    'type' => '$_FILES',
    'required' => false
    );
    $api['params']['size'] = array(
    'description' => 'In case of upload image you can select an optional size',
    'type' => 'string choose(thumbnail, medium, large or full) default(thumbnail)',
    'required' => false
    );
    $api['example'] = 'social/?{api_key}username=smartiolabs&socialid=226597315&token=ak5idnjfd5sjkflksdf6dHmd62&socialtype=facebook';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_author_filter';
    $api['wp_query_filter'] = 'smio_api_author_sql_filter';
    $hertapi = $this->signup();
    $api['errors'] = $hertapi['errors'];
    return $api;
  }
  
  public function edit_profile(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['username'] = array(
    'description' => '',
    'type' => 'string',
    'required' => true
    );
    $api['params']['display_name'] = array(
    'description' => 'Author full name',
    'type' => 'string',
    'required' => true
    );
    $api['params']['password'] = array(
    'description' => '',
    'type' => 'string',
    'required' => true
    );
    $api['params']['email'] = array(
    'description' => '',
    'type' => 'email',
    'required' => true
    );
    $api['params']['user_url'] = array(
    'description' => 'A string containing the user\'s URL for the user\'s web site',
    'type' => 'URL',
    'required' => false
    );
    $api['params']['first_name'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['params']['last_name'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['params']['description'] = array(
    'description' => 'A string containing content about the user',
    'type' => 'string',
    'required' => false
    );
    $api['params']['custom_meta'] = array(
    'description' => 'Set the meta and custom meta values for the user',
    'type' => 'JSON string like {"key1":"value1","key2":"value2","key3":"value3"}',
    'required' => false
    );
    $api['params']['custom_field'] = array(
    'description' => 'Set the custom field values for the user',
    'type' => 'JSON string like {"fieldkey1":"fieldvalue1","fieldkey2":"fieldvalue2"}',
    'required' => false
    );
    $api['params']['userimg_fieldkey'] = array(
    'description' => 'The custom field key of the profile image',
    'type' => 'string',
    'requiredtxt' => 'Required if you want to set the profile image'
    );
    $api['params']['thumbnail_id'] = array(
    'description' => 'Set the profile image for the user. Note: you can get ID value from `upload_media` service',
    'type' => 'int',
    'required' => false
    );
    $api['params']['file'] = array(
    'description' => 'Send in the array $_FILES like $_FILES[\'file\'] and set as the profile image for the user',
    'type' => '$_FILES',
    'required' => false
    );
    $api['params']['file64'] = array(
    'description' => 'Send in the array $_FILES like $_FILES[\'file\'] and set as the profile image for the user',
    'type' => 'string',
    'required' => false
    );
    $api['params']['size'] = array(
    'description' => 'In case of upload profile image you can select an optional size',
    'type' => 'string choose(thumbnail, medium, large or full) default(thumbnail)',
    'required' => false
    );
    $api['example'] = 'edit_profile/?{api_key}username=smartiolabs&password=123&email=support@smartiolabs.com';
    $api['note'] = 'Feature of custom fields depends on <a href="http://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = true;
    $api['errors'] = array(
    'Sorry, that username already exists!',
    'E-mail address not valid',
    'URL is not valid',
    'Sorry, that email address is already used!',
    );
    return $api;
  }

  public function profile_image(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['userimg_fieldkey'] = array(
    'description' => 'The custom field key of the profile image',
    'type' => 'string',
    'required' => true
    );
    $api['params']['thumbnail_id'] = array(
    'description' => 'You can get ID value from `upload_media` service or from Wordpress media library',
    'type' => 'int',
    'requiredtxt' => 'Required if you do not upload an image'
    );
    $api['params']['file'] = array(
    'description' => 'Send in the array $_FILES like $_FILES[\'file\'] and set as the profile image for the user',
    'type' => '$_FILES',
    'required' => false
    );
    $api['params']['size'] = array(
    'description' => 'You can select an optional size for the picture',
    'type' => 'string choose(thumbnail, medium, large or full) default(thumbnail)',
    'required' => false
    );
    $api['example'] = 'profile_image/?{api_key}userimg_fieldkey=field_52c7136505602&thumbnail_id=9';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = true;
    $hertapi = $this->upload_media();
    $api['errors'] = $hertapi['errors'];
    return $api;
  }

  public function changepwd(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['password'] = array(
    'description' => '',
    'type' => 'string',
    'required' => true
    );
    $api['params']['oldpassword'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['example'] = 'changepwd/?{api_key}password=demo';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = true;
    $api['errors'] = array(
    'Password is wrong',
    );
    return $api;
  }

  public function savetoken(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['device_token'] = array(
    'description' => 'The device token for push notification purpose',
    'type' => 'string',
    'required' => true
    );
    $api['params']['device_type'] = array(
    'description' => 'Support IOS and Android devices',
    'type' => 'Choose(ios,android)',
    'required' => true
    );
    $api['params']['channels_id'] = array(
    'description' => 'IDS of channels to subscribe in, seperated by (,) like 1,2,3',
    'type' => 'int',
    'requiredtxt' => 'If there are no channels ID sent, the subscription will go to the default channel'
    );
    $api['example'] = 'savetoken/?{api_key}device_token=ajfurlNhTlkB4ldb&device_type=ios&channels_id=1,2';
    $api['note'] = 'Token saved to user ID automatically if user already logged<br>This feature depends on <a href="http://codecanyon.net/item/send-mobile-push-notification-messages/6548533" target="_blank">Mobile Push Notification</a> plugin';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = false;
    $api['errors'] = array();
    return $api;
  }

  public function channels_subscribe(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $hertapi = $this->savetoken();
    $api['params'] = $hertapi['params'];
    $api['params']['channels_id']['requiredtxt'] = '';
    $api['params']['channels_id']['required'] = true;
    $api['example'] = 'channels_subscribe/?{api_key}device_token=ajfurlNhTlkB4ldb&device_type=ios&channels_id=1,2';
    $api['note'] = 'This feature depends on <a href="http://codecanyon.net/item/send-mobile-push-notification-messages/6548533" target="_blank">Mobile Push Notification</a> plugin';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = false;
    $api['errors'] = array();
    return $api;
  }

  public function device_channels(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $hertapi = $this->savetoken();
    $api['params']['device_token'] = $hertapi['params']['device_token'];
    $api['params']['device_type'] = $hertapi['params']['device_type'];
    $hertapi = $this->get_channels();
    $api['example'] = 'device_channels/?{api_key}device_token=ajfurlNhTlkB4ldb&device_type=ios';
    $api['note'] = 'This feature depends on <a href="http://codecanyon.net/item/send-mobile-push-notification-messages/6548533" target="_blank">Mobile Push Notification</a> plugin';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = false;
    $api['errors'] = array('No result found');
    return $api;
  }

  public function get_channels(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['order']['date'] = array(
    'description' => '',
    'type' => 'ASC',
    'default' => true
    );
    $api['order']['name'] = array(
    'description' => '',
    'type' => '',
    'default' => false
    );
    $api['order']['subscribers'] = array(
    'description' => 'Count of subscribers in the channel',
    'type' => '',
    'default' => false
    );
    $api['example'] = 'get_channels/?{api_key}orderby=date&order=asc';
    $api['note'] = 'This feature depends on <a href="http://codecanyon.net/item/send-mobile-push-notification-messages/6548533" target="_blank">Mobile Push Notification</a> plugin';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = false;
    $api['errors'] = array('No result found');
    return $api;
  }

  public function newcomment(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['post_id'] = array(
    'description' => 'ID of post',
    'type' => 'int',
    'required' => true
    );
    $api['params']['parentid'] = array(
    'description' => 'comment_ID of any comment you want to comment in it',
    'type' => 'int',
    'required' => false
    );
    $api['params']['content'] = array(
    'description' => 'The content of comment',
    'type' => 'string',
    'required' => true
    );
    $api['params']['author'] = array(
    'description' => 'Name of the person who want comment',
    'type' => 'string',
    'requiredtxt' => 'Required if not login'
    );
    $api['params']['email'] = array(
    'description' => 'E-mail of the person who want comment',
    'type' => 'email',
    'requiredtxt' => 'Required if not login'
    );
    $api['params']['url'] = array(
    'description' => 'Website of the person who want comment',
    'type' => 'url',
    'required' => false
    );
    $api['params']['custom_meta'] = array(
    'description' => 'Set the meta and custom meta values for the comment',
    'type' => 'JSON string like {"key1":"value1","key2":"value2","key3":"value3"}',
    'required' => false
    );
    $api['note'] = 'May be return that comment is pending review depending on the plugin settings';
    $api['example'] = 'newcomment/?{api_key}post_id=1&content=just for test&author=myname&email=support@smartiolabs.com';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = false;
    $api['errors'] = array(
    'Comment is closed in this post',
    '`Must be login to proceed` if choose comment for users only in plugin setting'
    );
    return $api;
  }
  
  public function updatecomment(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['id'] = array(
    'description' => 'ID of comment',
    'type' => 'int',
    'required' => true
    );
    $api['params']['content'] = array(
    'description' => 'The content of comment',
    'type' => 'string',
    'required' => false
    );
    $api['params']['custom_meta'] = array(
    'description' => 'Set the meta and custom meta values for the comment',
    'type' => 'JSON string like {"key1":"value1","key2":"value2","key3":"value3"}',
    'required' => false
    );
    $api['example'] = 'updatecomment/?{api_key}id=1&content=just for update a comment';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = true;
    $api['errors'] = array(
    'You do not have the permission to do that process',
    );
    return $api;
  }

  public function last_comments(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['example'] = 'last_comments/?{api_key}';
    $api['paging'] = true;
    $api['multisite'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_comment_filter';
    $api['wp_query_filter'] = 'smio_api_comment_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function get_comment(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['comment_id'] = array(
    'description' => 'comment_ID',
    'type' => 'int',
    'required' => true
    );
    $api['example'] = 'get_comment/?{api_key}comment_id=1';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_comment_filter';
    $api['wp_query_filter'] = 'smio_api_comment_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function getComments(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['post_id'] = array(
    'description' => 'ID of post',
    'type' => 'int',
    'required' => true
    );
    $api['params']['comment_status'] = array(
    'description' => 'Filter comments by status',
    'type' => 'choose(approved, pending, spam, trash) default(approved)',
    'required' => false
    );
    $api['order']['date'] = array(
    'description' => '',
    'type' => 'DESC',
    'default' => true
    );
    $api['example'] = 'getcomments/?{api_key}post_id=1&orderby=date&order=desc';
    $api['multisite'] = true;
    $api['paging'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_comment_filter';
    $api['wp_query_filter'] = 'smio_api_comment_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function newPost(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['subject'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['params']['content'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['params']['author_id'] = array(
    'description' => 'Set a custom author ID for post and it does not require logged session but needs visitor can post option is enabled',
    'type' => 'int',
    'required' => false
    );
    $api['params']['categoryid'] = array(
    'description' => 'ID or IDS of the categories separated by (,) like 1,2,3',
    'type' => 'int',
    'required' => false
    );
    $api['params']['taxonomy'] = array(
    'description' => 'Link the post to custom taxonomies, How to send? look to the field type below',
    'type' => '[taxonomy_name,ID1,ID2][category,1,2][tags,"apple","microsoft"]',
    'required' => false
    );
    $api['params']['geolocation'] = array(
    'description' => 'Enable geolocation service to detect current user location and link it with the post<br />Store country, city, ip, latitude and longitude as custom meta values',
    'type' => 'boolean(Set value 1 to enable geolocation service)',
    'required' => false
    );
    $api['params']['custom_meta'] = array(
    'description' => 'Set the meta and custom meta values for the post. send SMIOPOSTID as a value to replace it with the real new post ID',
    'type' => 'JSON string like {"key1":"value1","key2":"value2","key3":"value3"}',
    'required' => false
    );
    $api['params']['custom_field'] = array(
    'description' => 'Set the custom field values for the post',
    'type' => 'JSON string like {"fieldkey1":"fieldvalue1","fieldkey2":"fieldvalue2"}',
    'required' => false
    );
    $api['params']['custom_post'] = array(
    'description' => 'In case publish post under a custom post type like product in WooCommerce or anything',
    'type' => 'string default(post)',
    'required' => false
    );
    $api['params']['slug'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['params']['tags'] = array(
    'description' => 'Tags word separated by (,) like tag1,tag2,tag3',
    'type' => 'string',
    'required' => false
    );
    $api['params']['post_parent'] = array(
    'description' => 'If post is a child for another parent post',
    'type' => 'int (post ID)',
    'required' => false
    );
    $api['params']['post_status'] = array(
    'description' => 'If you want to use another status except publish and pending status, you should first set `New Post Status` option in the plugin setting page',
    'type' => 'string (draft, publish, pending, future, private) default(default post status in plugin setting)',
    'required' => false
    );
    $api['params']['post_date'] = array(
    'description' => 'You can set a custom date for the post or leave it empty to set it in current time',
    'type' => 'datetime [Y-m-d H:i:s]',
    'requiredtxt' => 'Required if you choose the post status is future'
    );
    $api['params']['comment_status'] = array(
    'description' => 'Open or close comments in this post',
    'type' => 'choose (open or closed) default(open)',
    'required' => false
    );
    $api['params']['format'] = array(
    'description' => 'Post format',
    'type' => 'string choose(aside,chat,gallery,link,image etc) default(null)',
    'required' => false
    );
    $api['params']['thumbnail_id'] = array(
    'description' => 'Set a featured image for the post, get ID value from `upload_media` service',
    'type' => 'int',
    'required' => false
    );
    $api['params']['file'] = array(
    'description' => 'Send in the array $_FILES like $_FILES[\'file\'] and set as featured image for the post',
    'type' => '$_FILES',
    'required' => false
    );
    $api['params']['size'] = array(
    'description' => 'In case of image type you can select an option size',
    'type' => 'string choose(thumbnail, medium, large or full) ',
    'required' => false
    );
    $api['note'] = 'May be return that post is pending review depending on the plugin settings';
    $api['note'] .= '<br />Feature of custom fields depends on <a href="http://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin';
    $api['example'] = 'newpost/?{api_key}subject=just%20for%20test&content=test&categoryid=1,2,3&taxonomy=[location,20,23][weather,24,25]&custom_meta={"size":"XL"}&custom_field={"field_52c5adaaffb3a":"5"}&comment_status=open&slug=test&tags=test,tags,post';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = true;
    $api['errors'] = array(
    'Wrong post date format',
    'ACF plugin needs to be enabled, Back to documentation for further information',
    );
    return $api;
  }
  
  public function updatePost(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['id'] = array(
    'description' => '',
    'type' => 'int',
    'required' => true
    );
    $api['params']['subject'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['params']['content'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['params']['author_id'] = array(
      'description' => 'Set a custom author ID for post and it does not require logged session but needs visitor can post option is enabled',
      'type' => 'int',
      'required' => false
    );
    $api['params']['categoryid'] = array(
    'description' => 'ID or IDS of the categories separated by (,) like 1,2,3',
    'type' => 'int',
    'required' => false
    );
    $api['params']['taxonomy'] = array(
    'description' => 'Link the post to custom taxonomies, How to send? look to the field type below',
    'type' => '[taxonomy_name,ID1,ID2][category,1,2][tags,"apple","microsoft"]',
    'required' => false
    );
    $api['params']['geolocation'] = array(
    'description' => 'Enable geolocation service to detect current user location and link it with the post<br />Store country, city, ip, latitude and longitude as custom meta values',
    'type' => 'boolean(Set value 1 to enable geolocation service)',
    'required' => false
    );
    $api['params']['custom_meta'] = array(
    'description' => 'Set the meta and custom meta values for the post',
    'type' => 'JSON string like {"key1":"value1","key2":"value2","key3":"value3"}',
    'required' => false
    );
    $api['params']['custom_field'] = array(
    'description' => 'Set the custom field values for the post',
    'type' => 'JSON string like {"fieldkey1":"fieldvalue1","fieldkey2":"fieldvalue2"}',
    'required' => false
    );
    $api['params']['custom_post'] = array(
    'description' => 'In case publish post under a custom post type like product in WooCommerce or anything',
    'type' => 'string default(post)',
    'required' => false
    );
    $api['params']['slug'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['params']['tags'] = array(
    'description' => 'Tags word separated by (,) like tag1,tag2,tag3',
    'type' => 'string',
    'required' => false
    );
    $api['params']['post_parent'] = array(
      'description' => 'If post is a child for another parent post',
      'type' => 'int (post ID)',
      'required' => false
    );
    $api['params']['post_status'] = array(
    'description' => 'If you want to use another status except publish and pending status, you should first set `New Post Status` option in the plugin setting page',
    'type' => 'string (draft, publish, pending, future, private) default(default post status in plugin setting)',
    'required' => false
    );
    $api['params']['post_date'] = array(
    'description' => 'You can set a custom date for the post or leave it empty to set it in current time',
    'type' => 'datetime [Y-m-d H:i:s]',
    'requiredtxt' => 'Required if you choose the post status is future'
    );
    $api['params']['comment_status'] = array(
    'description' => 'Open or close comments in this post',
    'type' => 'choose (open or closed) default(open)',
    'required' => false
    );
    $api['params']['format'] = array(
    'description' => 'Post format',
    'type' => 'string choose(aside,chat,gallery,link,image etc) default(null)',
    'required' => false
    );
    $api['params']['thumbnail_id'] = array(
    'description' => 'Set a featured image for the post, get ID value from `upload_media` service',
    'type' => 'int',
    'required' => false
    );
    $api['params']['file'] = array(
    'description' => 'Send in the array $_FILES like $_FILES[\'file\'] and set as featured image for the post',
    'type' => '$_FILES',
    'required' => false
    );
    $api['params']['size'] = array(
    'description' => 'In case of image type you can select an option size',
    'type' => 'string choose(thumbnail, medium, large or full) ',
    'required' => false
    );
    $api['note'] = 'Feature of custom fields depends on <a href="http://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin';
    $api['example'] = 'updatepost/?{api_key}id=1&subject=just%20for%20test%20post%20updating&content=test%20updating%20post%20content&categoryid=1,2&taxonomy=[location,20,23][weather,24,25]&custom_meta={"size":"SMALL"}&custom_field={"field_52c5adaaffb3a":"5"}&comment_status=open&slug=test&tags=test,update,tags,post';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = true;
    $api['errors'] = array(
    'You do not have the permission to do that process',
    'Wrong post date format',
    'ACF plugin needs to be enabled, Back to documentation for further information',
    );
    return $api;
  }

  public function upload_media(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['file'] = array(
    'description' => 'The file binary data, send in the array $_FILES like $_FILES[\'file\']',
    'type' => 'Binary Data',
    'required' => true
    );
    $api['params']['handler'] = array(
    'description' => 'File parameter name to handle from server as $_FILES[\'file\']',
    'type' => 'string default(file)',
    'required' => false
    );
    $api['params']['post_id'] = array(
    'description' => 'Add the uplaoded image as WordPress attachment post',
    'type' => 'int',
    'required' => false
    );
    $api['params']['author_id'] = array(
    'description' => 'Link WordPress attachment post with custom user ID if there no logged session or access token but needs visitor can post option is enabled',
    'type' => 'int',
    'required' => false
    );
    $api['params']['type'] = array(
    'description' => 'In case of file API will return file path and link while image type API returns image link and attachment post ID besides you can customize image size',
    'type' => 'string choose(file,image) default is (image)',
    'required' => false
    );
    $api['params']['size'] = array(
    'description' => 'You can select an optional size',
    'type' => 'string choose(thumbnail, medium, large or full) ',
    'required' => false
    );
    $api['example'] = 'upload_media/?{api_key}';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = true;
    $api['errors'] = array(
    'No file was uploaded',
    'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
    'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
    'The uploaded file was only partially uploaded.',
    'Missing a temporary folder.',
    'Failed to write file to disk.',
    'File upload stopped by extension.',
    'Sorry, this file type is not permitted for security reasons.'
    );
    return $api;
  }

  public function get_archive(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['example'] = 'get_archive/?{api_key}';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = false;
    $api['errors'] = array(
    'No result found'
    );
    return $api;
  }

  public function menu_items(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['menu'] = array(
    'description' => 'Send menu ID or title',
    'type' => 'int',
    'required' => true
    );
    $api['example'] = 'menu_items/?{api_key}menu=1';
    $api['multisite'] = true;
    $api['paging'] = true;
    $api['login'] = false;
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }
  
  public function getpost(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['post_id'] = array(
    'description' => '',
    'type' => 'int',
    'required' => true
    );
    $api['params']['password'] = array(
    'description' => 'Send password string if post is protected by password',
    'type' => 'string',
    'required' => false
    );
    $api['example'] = 'getpost/?{api_key}post_id=1';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_post_filter';
    $api['wp_query_filter'] = 'smio_api_post_sql_filter';
    $api['errors'] = array(
    'This content is password protected',
    'No result found',
    );
    return $api;
  }

  public function author_posts(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['author_id'] = array(
    'description' => 'ID of editor',
    'type' => 'int',
    'required' => true
    );
    $api['params']['custom_post'] = array(
    'description' => 'In case displaying a custom post type',
    'type' => 'string default(post)',
    'required' => false
    );
    $api['params']['post_status'] = array(
    'description' => 'Display posts that published in a certain status',
    'type' => 'string (draft, publish, pending, future, private or custom status) default(publish)',
    'required' => false
    );
    $api['params']['categoryid'] = array(
    'description' => 'Filter results by category ID or IDS separated by (,) like 1,2,3',
    'type' => 'int',
    'required' => false
    );
    $api['params']['taxonomy_id'] = array(
    'description' => 'Filter results by custom taxonomy ID or IDS separated by (,) like 1,2,3',
    'type' => 'int',
    'required' => false
    );
    $api['params']['geolocation'] = array(
    'description' => 'Enable geolocation service to detect current user location and return the results near his location',
    'type' => 'boolean(Set value 1 to enable geolocation service)',
    'required' => false
    );
    $herapi = $this->getposts();
    $api['order'] = $herapi['order'];
    $api['example'] = 'author_posts/?{api_key}author_id=1&orderby=date&order=desc';
    $api['multisite'] = true;
    $api['paging'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_post_filter';
    $api['wp_query_filter'] = 'smio_api_post_sql_filter';
    $api['errors'] = array(
    'No result found'
    );
    return $api;
  }

  public function last_posts(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['custom_post'] = array(
    'description' => 'In case displaying a custom post type',
    'type' => 'string default(post)',
    'required' => false
    );
    $api['params']['post_status'] = array(
    'description' => 'Display posts that published in a certain status',
    'type' => 'string (draft, publish, pending, future, private or custom status) default(publish)',
    'required' => false
    );
    $api['params']['geolocation'] = array(
    'description' => 'Enable geolocation service to detect current user location and return the results near his location',
    'type' => 'boolean(Set value 1 to enable geolocation service)',
    'required' => false
    );
    $api['example'] = 'last_posts/?{api_key}';
    $api['multisite'] = true;
    $api['paging'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_post_filter';
    $api['wp_query_filter'] = 'smio_api_post_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function search_posts(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['query'] = array(
    'description' => 'A word or phrase to search for',
    'type' => 'string',
    'required' => false
    );
    $api['params']['categoryid'] = array(
    'description' => 'Filter results by category ID or IDs separated by (,) like 1,2,3',
    'type' => 'int',
    'required' => false
    );
    $api['params']['taxonomy_id'] = array(
    'description' => 'Filter results by custom taxonomy ID or IDs separated by (,) like 1,2,3',
    'type' => 'int',
    'required' => false
    );
    $api['params']['tag_id'] = array(
      'description' => 'Filter results by tag ID or IDs separated by (,) like 1,2,3',
      'type' => 'int',
      'required' => false
    );
    $api['params']['search_level'] = array(
    'description' => 'Search deep level and consists of 4 levels:<br />
    Level 0: Search only in title of posts<br />
    Level 1: Search only in title and content of posts<br />
    Level 2: Search only in categories, tags and custom taxonomies<br />
    Level 3: Search only in meta and custom fields of posts<br />
    Level 4: The top level and include on all of the above',
    'type' => 'int(from 1 to 4)',
    'required' => false
    );
    $api['params']['geolocation'] = array(
    'description' => 'Enable geolocation service to detect current user location and return the results near his location',
    'type' => 'boolean(Set value 1 to enable geolocation service)',
    'required' => false
    );
    $api['params']['custom_post'] = array(
    'description' => 'In case displaying a custom post type',
    'type' => 'string default(post)',
    'required' => false
    );
    $api['params']['post_status'] = array(
    'description' => 'Display posts that published in a certain status',
    'type' => 'string (draft, publish, pending, future, private or custom status) default(publish)',
    'required' => false
    );
    $api['order']['comment_count'] = array(
    'description' => '',
    'type' => '',
    'default' => false
    );
    $api['order']['date'] = array(
    'description' => '',
    'type' => '',
    'default' => false
    );
    $api['example'] = 'search_posts/?{api_key}query=test&search_level=0';
    $api['multisite'] = true;
    $api['paging'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_post_filter';
    $api['wp_query_filter'] = 'smio_api_post_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function posts_subscribedin(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['author_id'] = array(
    'description' => '',
    'type' => 'int',
    'required' => true
    );
    $api['params']['post_status'] = array(
    'description' => 'Display posts that published in a certain status',
    'type' => 'string (draft, publish, pending, future, private or custom status) default(publish)',
    'required' => false
    );
    $api['params']['custom_post'] = array(
    'description' => 'In case displaying a custom post type',
    'type' => 'string default(post)',
    'required' => false
    );
    $api['params']['limit'] = array(
    'description' => 'Max number of posts to return it',
    'type' => 'int default(20)',
    'required' => false
    );
    $api['params']['geolocation'] = array(
    'description' => 'Enable geolocation service to detect current user location and return the results near his location',
    'type' => 'boolean(Set value 1 to enable geolocation service)',
    'required' => false
    );
    $herapi = $this->getposts();
    $api['order'] = $herapi['order'];
    $api['example'] = 'posts_subscribedin/?{api_key}author_id=1&orderby=date&order=desc';
    $api['multisite'] = true;
    $api['paging'] = true;
    $api['login'] = true;
    $api['wp_filter'] = 'smio_api_post_filter';
    $api['wp_query_filter'] = 'smio_api_post_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function get_posts_archive(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['srchinyear'] = array(
    'description' => '',
    'type' => 'year',
    'required' => true
    );
    $api['params']['month'] = array(
    'description' => '',
    'type' => 'int',
    'required' => false
    );
    $api['params']['categoryid'] = array(
    'description' => 'Filter results by category ID or IDs separated by (,) like 1,2,3',
    'type' => 'int',
    'required' => false
    );
    $api['params']['taxonomy_id'] = array(
    'description' => 'Filter results by custom taxonomy ID or IDs separated by (,) like 1,2,3',
    'type' => 'int',
    'required' => false
    );
    $api['params']['post_status'] = array(
    'description' => 'Display posts that published in a certain status',
    'type' => 'string (draft, publish, pending, future, private or custom status) default(publish)',
    'required' => false
    );
    $api['params']['custom_post'] = array(
    'description' => 'In case displaying a custom post type',
    'type' => 'string default(post)',
    'required' => false
    );
    $api['params']['geolocation'] = array(
    'description' => 'Enable geolocation service to detect current user location and return the results near his location',
    'type' => 'boolean(Set value 1 to enable geolocation service)',
    'required' => false
    );
    $herapi = $this->getposts();
    $api['order'] = $herapi['order'];
    $api['example'] = 'get_posts_archive/?{api_key}srchinyear=2013&month=12&orderby=date&order=desc';
    $api['multisite'] = true;
    $api['paging'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_post_filter';
    $api['wp_query_filter'] = 'smio_api_post_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function popular_posts(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['limit'] = array(
    'description' => 'Max number of posts to return it',
    'type' => 'int',
    'required' => true
    );
    $api['params']['range'] = array(
    'description' => 'Number of days to calculate and return the most popular posts',
    'type' => 'int or word(all) default(option that user chosen it in setting)',
    'required' => false
    );
    $api['params']['post_status'] = array(
    'description' => 'Display posts that published in a certain status',
    'type' => 'string (draft, publish, pending, future, private or custom status) default(publish)',
    'required' => false
    );
    $api['params']['custom_post'] = array(
    'description' => 'In case displaying a custom post type',
    'type' => 'string default(post)',
    'required' => false
    );
    $api['params']['geolocation'] = array(
    'description' => 'Enable geolocation service to detect current user location and return the results near his location',
    'type' => 'boolean(Set value 1 to enable geolocation service)',
    'required' => false
    );
    $api['example'] = 'popular_posts/?{api_key}limit=20';
    $api['note'] = 'This featrue is depends on <a href="http://wordpress.org/plugins/jetpack/" target="_blank">Jetpack plugin</a> with Stats module enabled';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_post_filter';
    $api['wp_query_filter'] = 'smio_api_post_sql_filter';
    $api['errors'] = array(
    'Jetpack plugin with Stats module needs to be enabled',
    'Sorry, No data yet',
    'No result found',
    );
    return $api;
  }

  public function tag_posts(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['tag'] = array(
    'description' => '',
    'type' => 'string',
    'required' => true
    );
    $api['params']['post_status'] = array(
    'description' => 'Display posts that published in a certain status',
    'type' => 'string (draft, publish, pending, future, private or custom status) default(publish)',
    'required' => false
    );
    $api['params']['custom_post'] = array(
    'description' => 'In case displaying a custom post type',
    'type' => 'string default(post)',
    'required' => false
    );
    $api['params']['geolocation'] = array(
    'description' => 'Enable geolocation service to detect current user location and return the results near his location',
    'type' => 'boolean(Set value 1 to enable geolocation service)',
    'required' => false
    );
    $herapi = $this->getposts();
    $api['order'] = $herapi['order'];
    $api['example'] = 'tag_posts/?{api_key}tag=test&orderby=date&order=desc';
    $api['multisite'] = true;
    $api['paging'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_post_filter';
    $api['wp_query_filter'] = 'smio_api_post_sql_filter';
    $api['errors'] = array(
    'Did not find the tag name',
    'No result found',
    );
    return $api;
  }

  public function getpage(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['pageid'] = array(
    'description' => '',
    'type' => 'int',
    'required' => true
    );
    $api['example'] = 'getpage/?{api_key}pageid=2';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_post_filter';
    $api['wp_query_filter'] = 'smio_api_post_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function getpages(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['parentid'] = array(
    'description' => 'Filter results by parent page ID',
    'type' => 'int',
    'required' => false
    );
    $api['params']['taxonomy_id'] = array(
    'description' => 'Filter results by custom taxonomy ID',
    'type' => 'int',
    'required' => false
    );
    $api['params']['geolocation'] = array(
    'description' => 'Enable geolocation service to detect current user location and return the results near his location',
    'type' => 'boolean(Set value 1 to enable geolocation service)',
    'required' => false
    );
    $herapi = $this->getposts();
    $api['order'] = $herapi['order'];
    $api['example'] = 'getpages/?{api_key}orderby=date&order=desc';
    $api['multisite'] = true;
    $api['paging'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_post_filter';
    $api['wp_query_filter'] = 'smio_api_post_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function getposts_format(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['format'] = array(
    'description' => '',
    'type' => 'string',
    'required' => true
    );
    $api['params']['post_status'] = array(
    'description' => 'Display posts that published in a certain status',
    'type' => 'string (draft, publish, pending, future, private or custom status) default(publish)',
    'required' => false
    );
    $api['params']['custom_post'] = array(
    'description' => 'In case displaying a custom post type',
    'type' => 'string default(post)',
    'required' => false
    );
    $api['params']['geolocation'] = array(
    'description' => 'Enable geolocation service to detect current user location and return the results near his location',
    'type' => 'boolean(Set value 1 to enable geolocation service)',
    'required' => false
    );
    $herapi = $this->getposts();
    $api['order'] = $herapi['order'];
    $api['example'] = 'getposts_format/?{api_key}format=image&orderby=date&order=desc';
    $api['multisite'] = true;
    $api['paging'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_post_filter';
    $api['wp_query_filter'] = 'smio_api_post_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function getposts(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['categoryid'] = array(
    'description' => 'Filter results by category ID or IDs separated by (,) like 1,2,3',
    'type' => 'int',
    'required' => false
    );
    $api['params']['taxonomy_id'] = array(
    'description' => 'Filter results by custom taxonomy ID or IDs separated by (,) like 1,2,3',
    'type' => 'int',
    'required' => false
    );
    $api['params']['tag_id'] = array(
    'description' => 'Filter results by tag ID or IDs separated by (,) like 1,2,3',
    'type' => 'int',
    'required' => false
    );
    $api['params']['exclude'] = array(
    'description' => 'Excluding some data from the query is important and makes it lite on server. it can be separated by (,) like tags,taxonomies,postmeta,custom_fields',
    'type' => 'string choose(taxonomies,category,tags,author,custom_fields,postmeta)',
    'required' => false
    );
    $api['params']['custom_search_or'] = array(
    'description' => 'Filter results to match any of post attributes, Send as JSON string like {"comment_count":"=\'0\'","post_title":" LIKE \'value\'","key":" BETWEEN \'value1\' AND \'value2\'"}',
    'type' => 'string',
    'required' => false
    );
    $api['params']['custom_search_and'] = array(
    'description' => 'Filter results to match all post attributes, Send as JSON string like {"comment_count":"=\'0\'","post_title":" LIKE \'value\'","key":" BETWEEN \'value1\' AND \'value2\'"}',
    'type' => 'string',
    'required' => false
    );
    $api['params']['custom_meta_or'] = array(
    'description' => 'Filter results to match any custom meta name and value, Send as JSON string like {"key1":"=\'value1\'","key2":">=\'value2\'","key3":"BETWEEN \'value3\' AND \'value4\'"}',
    'type' => 'string',
    'required' => false
    );
    $api['params']['custom_meta_and'] = array(
    'description' => 'Filter results to match all custom meta name and value, Send as JSON string like {"key1":"=\'value1\'","key2":">=\'value2\'","key3":"BETWEEN \'value3\' AND \'value4\'"}',
    'type' => 'string',
    'required' => false
    );
    $api['params']['custom_post'] = array(
    'description' => 'In case displaying a custom post type',
    'type' => 'string default(post)',
    'required' => false
    );
    $api['params']['post_status'] = array(
    'description' => 'Display posts that published in a certain status. can be sent multiple status like publish,private,future',
    'type' => 'string (draft, publish, pending, future, private or custom status) default(publish)',
    'required' => false
    );
    $api['params']['disable_content_filters'] = array(
      'description' => 'Output the post contents without applying WordPress the_content filter',
      'type' => 'boolean 1 or 0',
      'required' => false
    );
    $api['params']['remove_filters'] = array(
      'description' => 'Stop some plugins the_content filter separated by | sign like PutWtiLikePost|plugin_filter_name1|plugin_filter_name2',
      'type' => 'string',
      'required' => false
    );
    $api['params']['geolocation'] = array(
    'description' => 'Enable geolocation service to detect current user location and return the results near his location<br />Store country, city, ip, latitude and longitude as custom meta values',
    'type' => 'boolean(Set value 1 to enable geolocation service)',
    'required' => false
    );
    $api['order']['comment_count'] = array(
    'description' => '',
    'type' => '',
    'default' => false
    );
    $api['order']['custom_column'] = array(
    'description' => 'Order the results using any columns like posts.post_date, posts.menu_order or postmeta._edit_lock',
    'type' => '',
    'default' => false
    );
    $api['order']['date'] = array(
    'description' => '',
    'type' => 'DESC',
    'default' => true
    );
    $api['example'] = 'getposts/?{api_key}categoryid=1&orderby=date&order=desc';
    $api['note'] = 'Feature of category image depends on <a href="http://wordpress.org/plugins/categories-images/" target="_blank">Categories Images</a> plugin';
    $api['note'] .= '<br />Feature of custom fields depends on <a href="http://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin';
    $api['paging'] = true;
    $api['multisite'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_post_filter';
    $api['wp_query_filter'] = 'smio_api_post_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function get_author(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['author_id'] = array(
    'description' => '',
    'type' => 'int',
    'required' => true
    );
    $api['example'] = 'get_author/?{api_key}author_id=1';
    $api['note'] = 'Feature of custom fields depends on <a href="http://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_author_filter';
    $api['wp_query_filter'] = 'smio_api_author_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function authors(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['role'] = array(
    'description' => 'To get the profiles of Administrator, Editors, Author, Contributor and Subscriber',
    'type' => 'string(editor)',
    'required' => false
    );
    $api['params']['custom_search_or'] = array(
    'description' => 'Filter results to match any of users attributes, Send as JSON string like {"user_email":"=\'0\'","user_nicename":" LIKE \'value\'","key":" BETWEEN \'value1\' AND \'value2\'"}',
    'type' => 'string',
    'required' => false
    );
    $api['params']['custom_search_and'] = array(
    'description' => 'Filter results to match all users attributes, Send as JSON string like {"user_email":"=\'0\'","user_nicename":" LIKE \'value\'","key":" BETWEEN \'value1\' AND \'value2\'"}',
    'type' => 'string',
    'required' => false
    );
    $api['params']['custom_meta_or'] = array(
    'description' => 'Filter results to match any custom meta name and value, Send as JSON string like {"key1":"=\'value1\'","key2":">=\'value2\'","key3":"BETWEEN \'value3\' AND \'value4\'"}',
    'type' => 'string',
    'required' => false
    );
    $api['params']['custom_meta_and'] = array(
    'description' => 'Filter results to match all custom meta name and value, Send as JSON string like {"key1":"=\'value1\'","key2":">=\'value2\'","key3":"BETWEEN \'value3\' AND \'value4\'"}',
    'type' => 'string',
    'required' => false
    );
    $api['order']['name'] = array(
    'description' => '',
    'type' => 'ASC',
    'default' => true
    );
    $api['order']['date'] = array(
    'description' => '',
    'type' => '',
    'default' => false
    );
    $api['example'] = 'authors/?{api_key}role=editor&orderby=name&order=asc';
    $api['note'] = 'Feature of custom fields depends on <a href="http://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin';
    $api['paging'] = true;
    $api['multisite'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_author_filter';
    $api['wp_query_filter'] = 'smio_api_author_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function tags(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $herapi = $this->categories();
    $api['order'] = $herapi['order'];
    $api['example'] = 'tags/?{api_key}orderby=name&order=asc';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_taxonomy_filter';
    $api['wp_query_filter'] = 'smio_api_taxonomy_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function get_taxonomies(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $herapi = $this->categories();
    $api['order'] = $herapi['order'];
    $api['example'] = 'get_taxonomies/?{api_key}orderby=name&order=asc';
    $api['paging'] = false;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_taxonomy_filter';
    $api['wp_query_filter'] = 'smio_api_taxonomy_sql_filter';
    $api['multisite'] = true;
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function get_taxonomy(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['taxonomy_name'] = array(
    'description' => 'The custom taxonomy\'s name',
    'type' => 'string',
    'required' => true
    );
    $api['example'] = 'get_taxonomy/?{api_key}taxonomy_name=location';
    $api['paging'] = false;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_taxonomy_filter';
    $api['wp_query_filter'] = 'smio_api_taxonomy_sql_filter';
    $api['multisite'] = true;
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function viewcategory(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['cat_id'] = array(
    'description' => '',
    'type' => 'int',
    'required' => true
    );
    $api['example'] = 'viewcategory/?{api_key}cat_id=1';
    $api['paging'] = false;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_taxonomy_filter';
    $api['wp_query_filter'] = 'smio_api_taxonomy_sql_filter';
    $api['multisite'] = true;
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function categories(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['excluded'] = array(
    'description' => 'Exclude categories that have been identified in the plugin setting',
    'type' => 'int',
    'required' => false
    );
    $api['order']['postcount'] = array(
    'description' => 'Number of posts in the category',
    'type' => '',
    'default' => false
    );
    $api['order']['name'] = array(
    'description' => '',
    'type' => 'ASC',
    'default' => true
    );
    $api['order']['term_order'] = array(
    'description' => '',
    'type' => 'ASC',
    'default' => false
    );
    $api['order']['date'] = array(
    'description' => '',
    'type' => '',
    'default' => false
    );
    $api['note'] = 'Categories image feature depends on <a href="http://wordpress.org/plugins/categories-images/" target="_blank">Categories Images</a> plugin';
    $api['note'] .= '<br />Feature of custom fields depends on <a href="http://wordpress.org/plugins/advanced-custom-fields/" target="_blank">Advanced Custom Fields</a> plugin';
    $api['example'] = 'categories/?{api_key}orderby=name&order=asc';
    $api['multisite'] = true;
    $api['paging'] = true;
    $api['login'] = false;
    $api['wp_filter'] = 'smio_api_taxonomy_filter';
    $api['wp_query_filter'] = 'smio_api_taxonomy_sql_filter';
    $api['errors'] = array(
    'No result found',
    );
    return $api;
  }

  public function bb_new_topic(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['subject'] = array(
      'description' => '',
      'type' => 'string',
      'required' => true
    );
    $api['params']['content'] = array(
      'description' => '',
      'type' => 'string',
      'required' => true
    );
    $api['params']['post_parent'] = array(
      'description' => 'Forum ID that new topic will be exist',
      'type' => 'int (forum ID)',
      'required' => true
    );
    $api['params']['taxonomy'] = array(
      'description' => 'Link the post to custom taxonomies, How to send? look to the field type below',
      'type' => '[taxonomy_name,ID1,ID2][category,1,2][tags,"apple","microsoft"]',
      'required' => false
    );
    $api['note'] = '';
    $api['example'] = 'bb_new_topic/?{api_key}subject=just%20for%20test&content=test&post_parent=1';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = true;
    $api['errors'] = array(
    );
    return $api;
  }

  public function bb_new_comment(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['content'] = array(
      'description' => '',
      'type' => 'string',
      'required' => true
    );
    $api['params']['post_parent'] = array(
      'description' => 'Topic ID that new reply will be exist',
      'type' => 'int (forum ID)',
      'required' => true
    );
    $api['params']['forumid'] = array(
      'description' => 'Forum ID that topic follows it',
      'type' => 'int (forum ID)',
      'required' => true
    );
    $api['params']['taxonomy'] = array(
      'description' => 'Link the post to custom taxonomies, How to send? look to the field type below',
      'type' => '[taxonomy_name,ID1,ID2][category,1,2][tags,"apple","microsoft"]',
      'required' => false
    );
    $api['note'] = '';
    $api['example'] = 'bb_new_comment/?{api_key}content=test&forumid=1&post_parent=5';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = true;
    $api['errors'] = array(
    );
    return $api;
  }

  public function custom_service(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['service'] = array(
    'description' => 'The name of service you want to call it',
    'type' => 'string',
    'required' => true
    );
    $api['example'] = 'custom_service/?{api_key}service=getoption&option=home';
    $api['note'] = 'First you must build a custom service via `Build Service` tab';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = false;
    $api['errors'] = array(
    'Query has something error',
    'No service found with this name',
    'No result found'
    );
    return $api;
  }

  public function custom_options(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['example'] = 'custom_options/?{api_key}';
    $api['note'] = 'First you must create the custom options you want via `Create Options` tab';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = false;
    $api['errors'] = array(
    'No result found'
    );
    return $api;
  }

  public function social_links(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['example'] = 'social_links/?{api_key}';
    $api['note'] = 'Social links depends on <a href="http://wordpress.org/plugins/social-count-plus/" target="_blank">Social Count Plus</a> plugin';
    $api['paging'] = false;
    $api['login'] = false;
    $api['multisite'] = true;
    $api['errors'] = array(
    'Plugin `Social Count Plus` must be enabled',
    );
    return $api;
  }

  public function network_sites(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['example'] = 'network_sites/?{api_key}';
    $api['paging'] = false;
    $api['login'] = false;
    $api['errors'] = array(
    'Wordpress multisite feature is not enabled',
    );
    return $api;
  }

  public function bloginfo(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['example'] = 'bloginfo/?{api_key}';
    $api['paging'] = false;
    $api['multisite'] = true;
    $api['login'] = false;
    return $api;
  }

  public function logout(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['example'] = 'logout/?{api_key}';
    $api['multisite'] = true;
    $api['paging'] = false;
    $api['login'] = true;
    return $api;
  }

  public function contactus(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['name'] = array(
    'description' => '',
    'type' => 'string',
    'required' => true
    );
    $api['params']['email'] = array(
    'description' => '',
    'type' => 'email',
    'required' => true
    );
    $api['params']['subject'] = array(
    'description' => '',
    'type' => 'string',
    'required' => false
    );
    $api['params']['message'] = array(
    'description' => '',
    'type' => 'string',
    'required' => true
    );
    $api['example'] = 'contactus/?{api_key}name=smartiolabs&email=support@smartiolabs.com&message=test';
    $api['paging'] = false;
    $api['login'] = false;
    $api['errors'] = array(
    'E-mail address not valid',
    'The E-mail could not be sent may be your host disabled the mail() public function'
    );
    return $api;
  }

  public function post_status(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['post_id'] = array(
    'description' => '',
    'type' => 'int',
    'required' => true
    );
    $api['params']['status'] = array(
    'description' => 'The new status for the post',
    'type' => 'string (draft, publish, pending, future, private) default(default post status in plugin setting)',
    'required' => true
    );
    $api['example'] = 'post_status/?{api_key}post_id=1&status=publish';
    $api['multisite'] = true;
    $api['admin'] = true;
    return $api;
  }

  public function comment_status(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['comment_id'] = array(
    'description' => '',
    'type' => 'int',
    'required' => true
    );
    $api['params']['status'] = array(
    'description' => 'The new status for the comment',
    'type' => 'choose(approved, pending, spam, trash)',
    'required' => true
    );
    $api['example'] = 'comment_status/?{api_key}comment_id=1&status=approved';
    $api['multisite'] = true;
    $api['admin'] = true;
    return $api;
  }

  public function delete_user(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['user_id'] = array(
    'description' => '',
    'type' => 'int',
    'required' => true
    );
    $api['params']['reassignto'] = array(
    'description' => 'Reassign the user posts to another user ID',
    'type' => 'int',
    'required' => false
    );
    $api['example'] = 'delete_user/?{api_key}user_id=5';
    $api['multisite'] = true;
    $api['admin'] = true;
    return $api;
  }

  public function delete_post(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['post_id'] = array(
    'description' => '',
    'type' => 'int',
    'required' => true
    );
    $api['example'] = 'delete_post/?{api_key}post_id=22';
    $api['multisite'] = true;
    $api['admin'] = true;
    return $api;
  }

  public function delete_comment(){
    $api = array('params' => array(),'order' => array(),'note' => '','example' => '','multisite' => false,'paging' => false,'login' => false,'admin' => false,'errors' => array());
    $api['params']['comment_id'] = array(
    'description' => '',
    'type' => 'int',
    'required' => true
    );
    $api['example'] = 'delete_comment/?{api_key}comment_id=22';
    $api['multisite'] = true;
    $api['admin'] = true;
    return $api;
  }

}