<?php

global $wpdb;
$wpdb->hide_errors();
if($version == 1){
  $setting = unserialize(get_option('smapi_options'));
  $setting['cdata_tags'] = '';
  $setting['show_postmeta'] = 0;
  $setting['show_commentmeta'] = 0;
  $setting['show_authormeta'] = 0;
  $setting['show_post_acf'] = 0;
  $setting['show_author_acf'] = 0;
  update_option('smapi_options', serialize($setting));
  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."push_channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` mediumtext NOT NULL,
  `private` tinyint(1) NOT NULL,
  `default` tinyint(1) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
  );";
  dbDelta($sql);
  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."push_relation` (
  `channel_id` int(11) NOT NULL,
  `token_id` int(11) NOT NULL,
  KEY `channel_id` (`channel_id`)
  );";
  dbDelta($sql);
  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smapi_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` mediumtext NOT NULL,
  `hint` mediumtext NOT NULL,
  `type` varchar(20) NOT NULL,
  `values` text NOT NULL,
  PRIMARY KEY (`id`)
  );";
  dbDelta($sql);
  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smapi_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` mediumtext NOT NULL,
  `query` text NOT NULL,
  `paging` set('enable','disable') NOT NULL,
  PRIMARY KEY (`id`)
  );";
  dbDelta($sql);
  $wpdb->query("ALTER TABLE  `".$wpdb->prefix."push_queue` ADD  `extra_type` ENUM(  'normal',  'json' ) NOT NULL AFTER  `extravalue`");
  $wpdb->query("INSERT INTO `".$wpdb->prefix."push_channels` (`id`, `title`, `private`, `default`) VALUES (1, 'Main Channel', 0, 1);");
  $version = 2;
}
if($version == 2){
  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smapi_engine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `group` varchar(20) NOT NULL,
  `params` text NOT NULL,
  `access_level` mediumtext NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
  );";
  dbDelta($sql);
  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smapi_oauth_authcodes` (
  `code` varchar(40) NOT NULL,
  `client_id` varchar(20) NOT NULL,
  `redirect_uri` varchar(200) NOT NULL,
  `expires` int(11) NOT NULL,
  `scope` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`code`)
  );";
  dbDelta($sql);
  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smapi_oauth_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` varchar(20) NOT NULL,
  `client_secret` varchar(60) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `requester_name` varchar(64) NOT NULL,
  `requester_email` varchar(64) NOT NULL,
  `callback_uri` varchar(255) NOT NULL,
  `application_uri` varchar(255) NOT NULL,
  `application_title` varchar(80) NOT NULL,
  `application_descr` text NOT NULL,
  `application_notes` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `request_package` int(11) NOT NULL,
  `request_usage` int(11) NOT NULL,
  `request_usage_today` int(11) NOT NULL,
  `request_stat` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
  );";
  dbDelta($sql);
  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smapi_oauth_tokens` (
  `oauth_token` varchar(40) NOT NULL,
  `client_id` varchar(20) NOT NULL,
  `expires` int(11) NOT NULL,
  `scope` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`oauth_token`)
  );";
  dbDelta($sql);
  $wpdb->query('INSERT INTO `'.$wpdb->prefix.'smapi_engine` (`id`, `name`, `description`, `group`, `params`, `access_level`, `active`) VALUES
  (1, \'login\', \'Login\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (2, \'signup\', \'Singup\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (3, \'social\', \'Social signup and login\', \'\', \'a:1:{i:0;a:3:{s:4:"name";s:0:"";s:9:"depend_on";s:9:"5@authors";s:6:"active";i:1;}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (4, \'lostpwd\', \'Lost password\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (5, \'logout\', \'Logout and destroy sessions\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (6, \'authors\', \'List of authors\', \'authors\', \'a:18:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"user_login";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:13:"user_nicename";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:10:"user_email";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:8:"user_url";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:15:"user_registered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:12:"display_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:4:"role";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:10:"first_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"last_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:8:"nickname";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:11:"description";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:6:"avatar";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:3:"aim";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:6:"jabber";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:3:"yim";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:10:"authormeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (7, \'get_author\', \'Get the full profile for an author\', \'authors\', \'a:18:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"user_login";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:13:"user_nicename";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:10:"user_email";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:8:"user_url";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:15:"user_registered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:12:"display_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:4:"role";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:10:"first_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"last_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:8:"nickname";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:11:"description";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:6:"avatar";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:3:"aim";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:6:"jabber";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:3:"yim";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:10:"authormeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (8, \'author_posts\', \'List of posts published by an author\', \'getposts\', \'a:24:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (9, \'posts_subscribedin\', \'Get posts that user comment in it\', \'getposts\', \'a:24:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (10, \'newpost\', \'Publish new post\', \'\', \'\', \'a:1:{i:0;s:6:"logged";}\', 1),
  (11, \'upload_media\', \'Upload media file\', \'\', \'\', \'a:1:{i:0;s:6:"logged";}\', 1),
  (12, \'getposts\', \'Get posts by category or custom taxonomy\', \'getposts\', \'a:24:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (13, \'search_posts\', \'Search in posts by title\', \'getposts\', \'a:24:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (14, \'last_posts\', \'Last posts in blog\', \'getposts\', \'a:24:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (15, \'popular_posts\', \'Popular Posts in range days\', \'getposts\', \'a:24:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (16, \'getposts_format\', \'Get posts by post format\', \'getposts\', \'a:24:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (17, \'getpost\', \'View post by id\', \'getposts\', \'a:24:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (18, \'get_archive\', \'Archive\', \'\', \'a:4:{i:0;a:3:{s:4:"name";s:4:"text";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:4:"year";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:5:"month";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:5:"count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (19, \'get_posts_archive\', \'Get posts by archive time\', \'getposts\', \'a:24:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (20, \'getComments\', \'Get comments of post\', \'getComments\', \'a:15:{i:0;a:3:{s:4:"name";s:10:"comment_ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:15:"comment_post_ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:14:"comment_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:20:"comment_author_email";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:18:"comment_author_url";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:17:"comment_author_IP";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:12:"comment_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:16:"comment_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:15:"comment_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:13:"comment_agent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:16:"comment_approved";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:7:"user_id";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:12:"childcomment";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:11:"commentmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (21, \'newcomment\', \'Comment in post\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (22, \'last_comments\', \'Last comments in blog\', \'getComments\', \'a:15:{i:0;a:3:{s:4:"name";s:10:"comment_ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:15:"comment_post_ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:14:"comment_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:20:"comment_author_email";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:18:"comment_author_url";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:17:"comment_author_IP";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:12:"comment_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:16:"comment_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:15:"comment_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:13:"comment_agent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:16:"comment_approved";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:7:"user_id";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:12:"childcomment";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:11:"commentmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (23, \'get_comment\', \'View comment by id\', \'getComments\', \'a:15:{i:0;a:3:{s:4:"name";s:10:"comment_ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:15:"comment_post_ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:14:"comment_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:20:"comment_author_email";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:18:"comment_author_url";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:17:"comment_author_IP";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:12:"comment_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:16:"comment_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:15:"comment_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:13:"comment_agent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:16:"comment_approved";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:7:"user_id";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:12:"childcomment";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:11:"commentmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (24, \'getpages\', \'Get a list of pages\', \'getpages\', \'a:24:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (25, \'getpage\', \'View page by id\', \'getpages\', \'a:24:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (26, \'categories\', \'Get a list of all categories\', \'categories\', \'a:8:{i:0;a:3:{s:4:"name";s:2:"id";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:4:"name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"slug";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:11:"description";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:5:"count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:6:"parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:5:"image";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:11:"subcategory";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (27, \'viewcategory\', \'View category by id\', \'categories\', \'a:8:{i:0;a:3:{s:4:"name";s:2:"id";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:4:"name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"slug";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:11:"description";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:5:"count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:6:"parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:5:"image";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:11:"subcategory";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (28, \'get_taxonomies\', \'Get a list of all custom taxonomies\', \'\', \'a:1:{i:0;a:3:{s:4:"name";s:14:"get_taxonomies";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (29, \'get_taxonomy\', \'View custom taxonomy object\', \'\', \'a:8:{i:0;a:3:{s:4:"name";s:2:"id";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:4:"name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"slug";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:11:"description";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:5:"count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:6:"parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:5:"image";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:11:"subcategory";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (30, \'tags\', \'Get a list of all tags\', \'\', \'a:6:{i:0;a:3:{s:4:"name";s:2:"id";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:4:"name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"slug";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:11:"description";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:5:"count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:6:"parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (31, \'tag_posts\', \'Get posts by tag\', \'getposts\', \'a:24:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (32, \'social_links\', \'Social accounts links and stats\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (33, \'bloginfo\', \'Blog options and information\', \'\', \'a:8:{i:0;a:3:{s:4:"name";s:8:"blogname";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:15:"blogdescription";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:11:"admin_email";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:7:"siteurl";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:4:"home";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:16:"default_category";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"start_of_week";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:18:"require_name_email";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}}\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (34, \'contactus\', \'Contact Wordpress administrator\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (35, \'custom_service\', \'Call one of the custom services you made\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (36, \'custom_options\', \'Get a list of all custom options value\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (37, \'savetoken\', \'Save new device token\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (38, \'channels_subscribe\', \'Edit the device subscription in channels\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (39, \'device_channels\', \'Get a list of channels and whichever device subscribed\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (40, \'get_channels\', \'Get the list of all channels\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (41, \'debug\', \'Show plugin version and working status\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1);');
  $setting = unserialize(get_option('smapi_options'));
  unset($setting['show_postmeta']);
  unset($setting['show_commentmeta']);
  unset($setting['show_authormeta']);
  unset($setting['show_post_acf']);
  unset($setting['show_author_acf']);
  unset($setting['apple_sandbox']);
  unset($setting['apple_passphrase']);
  unset($setting['apple_cert_path']);
  unset($setting['google_apikey']);
  $setting['max_perpage'] = 100;
  $setting['oauth_mode'] = 0;
  $setting['stat_clean_interval'] = 6;
  $setting['maintenance_mode'] = 0;
  $setting['maintenance_msg'] = 'We are performing scheduled maintenance. We should be back online shortly.';
  update_option('smapi_options', serialize($setting));
  add_option('smapi_stats', '');
  $exist = $wpdb->query("ALTER TABLE  `".$wpdb->prefix."smapi_service` ADD  `access_level` MEDIUMTEXT NOT NULL");
  $exist = $wpdb->query('UPDATE `'.$wpdb->prefix.'smapi_service` SET `access_level`="a:1:{i:0;s:6:\"anyone\";}"');
  $version = 3;
}
if($version == 3){
  $setting = unserialize(get_option('smapi_options'));
  $setting['jsonp_param'] = 'callback';
  update_option('smapi_options', $setting);
  update_option('smapi_stats', unserialize(get_option('smapi_stats')));
  $wpdb->query('INSERT INTO `'.$wpdb->prefix.'smapi_engine` (`id`, `name`, `description`, `group`, `params`, `access_level`, `active`) VALUES (42, \'network_sites\', \'Display the list of network sites\', \'\', \'a:10:{i:0;a:3:{s:4:"name";s:7:"blog_id";s:9:"depend_on";s:0:"";s:6:"active";i:1;}i:1;a:3:{s:4:"name";s:8:"blogname";s:9:"depend_on";s:0:"";s:6:"active";i:1;}i:2;a:3:{s:4:"name";s:7:"siteurl";s:9:"depend_on";s:0:"";s:6:"active";i:1;}i:3;a:3:{s:4:"name";s:7:"site_id";s:9:"depend_on";s:0:"";s:6:"active";i:1;}i:4;a:3:{s:4:"name";s:6:"domain";s:9:"depend_on";s:0:"";s:6:"active";i:1;}i:5;a:3:{s:4:"name";s:4:"path";s:9:"depend_on";s:0:"";s:6:"active";i:1;}i:6;a:3:{s:4:"name";s:10:"registered";s:9:"depend_on";s:0:"";s:6:"active";i:1;}i:7;a:3:{s:4:"name";s:6:"public";s:9:"depend_on";s:0:"";s:6:"active";i:1;}i:8;a:3:{s:4:"name";s:7:"lang_id";s:9:"depend_on";s:0:"";s:6:"active";i:1;}i:9;a:3:{s:4:"name";s:10:"post_count";s:9:"depend_on";s:0:"";s:6:"active";i:1;}}\', \'a:1:{i:0;s:6:"anyone";}\', \'1\')');
  $wpdb->query('UPDATE `'.$wpdb->prefix.'smapi_engine` SET `params`=\'a:25:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}i:24;a:3:{s:4:"name";s:10:"taxonomies";s:9:"depend_on";s:0:"";s:6:"active";i:1;}}\' WHERE `group`=\'getposts\' OR `group`=\'getpages\'');
  $wpdb->query('UPDATE `'.$wpdb->prefix.'smapi_engine` SET `params`=\'a:26:{i:0;a:3:{s:4:"name";s:2:"ID";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:10:"post_title";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"guid";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:12:"post_content";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:11:"post_author";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:9:"post_date";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:13:"post_date_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:14:"comment_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:11:"ping_status";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:9;a:3:{s:4:"name";s:9:"post_name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:10;a:3:{s:4:"name";s:13:"post_modified";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:11;a:3:{s:4:"name";s:17:"post_modified_gmt";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:12;a:3:{s:4:"name";s:21:"post_content_filtered";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:13;a:3:{s:4:"name";s:11:"post_parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:14;a:3:{s:4:"name";s:10:"menu_order";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:15;a:3:{s:4:"name";s:13:"comment_count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:16;a:3:{s:4:"name";s:13:"featuredimage";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:17;a:3:{s:4:"name";s:13:"featuredthumb";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:18;a:3:{s:4:"name";s:8:"category";s:9:"depend_on";s:13:"25@categories";s:6:"active";s:1:"1";}i:19;a:3:{s:4:"name";s:4:"tags";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:20;a:3:{s:4:"name";s:6:"author";s:9:"depend_on";s:9:"5@authors";s:6:"active";s:1:"1";}i:21;a:3:{s:4:"name";s:11:"post_format";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:22;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:23;a:3:{s:4:"name";s:8:"postmeta";s:9:"depend_on";s:0:"";s:6:"active";s:1:"0";}i:24;a:3:{s:4:"name";s:10:"taxonomies";s:9:"depend_on";s:0:"";s:6:"active";i:1;}i:25;a:3:{s:4:"name";s:5:"video";s:9:"depend_on";s:0:"";s:6:"active";i:1;}}\' WHERE `id` IN(17,25)');
  $version = 3.1;
}
if($version == 3.1){
  $wpdb->query('INSERT INTO `'.$wpdb->prefix.'smapi_engine` (`id`, `name`, `description`, `group`, `params`, `access_level`, `active`) VALUES (43, \'changepwd\', \'Change user password\', \'\', \'\', \'a:1:{i:0;s:6:"logged";}\', \'1\'),
  (44, \'profile_image\', \'Change user profile image\', \'\', \'\', \'a:1:{i:0;s:6:"logged";}\', \'1\'),
  (45, \'post_status\', \'Change the post status\', \'management\', \'\', \'a:1:{i:0;s:13:"administrator";}\', \'1\'),
  (46, \'comment_status\', \'Change the comment status\', \'management\', \'\', \'a:1:{i:0;s:13:"administrator";}\', \'1\'),
  (47, \'delete_user\', \'Delete user permanently\', \'management\', \'\', \'a:1:{i:0;s:13:"administrator";}\', \'1\'),
  (48, \'delete_post\', \'Delete post permanently\', \'management\', \'\', \'a:1:{i:0;s:13:"administrator";}\', \'1\'),
  (49, \'delete_comment\', \'Delete comment permanently\', \'management\', \'\', \'a:1:{i:0;s:13:"administrator";}\', \'1\')');
  $wpdb->query("ALTER TABLE `".$wpdb->prefix."smapi_service` ADD `access_level` MEDIUMTEXT NOT NULL");
  $version = 3.2;
}
if($version == 3.2){
  $setting = get_option('smapi_options');
  $setting['geo_provider'] = 'telize.com';
  $setting['db_ip_apikey'] = '';
  update_option('smapi_options', $setting);
  $version = 3.3;
}
if($version == 3.3){
  $setting = get_option('smapi_options');
  unset($setting['notif_apprpost']);
  unset($setting['notif_appcomment']);
  unset($setting['notif_newcomment']);
  unset($setting['notif_usercomuser']);
  unset($setting['notif_postupdated']);
  update_option('smapi_options', $setting);
  $wpdb->query("ALTER TABLE `".$wpdb->prefix."smapi_service` ADD `codetype` ENUM('php','query') NOT NULL AFTER `query`");
  $wpdb->query("UPDATE `".$wpdb->prefix."smapi_service` SET `codetype`='query'");
  $version = 3.4;
}
if($version == 3.4){
  $version = 3.5;
}
if($version == 3.5){
  $wpdb->query('INSERT INTO `'.$wpdb->prefix.'smapi_engine` (`id`, `name`, `description`, `group`, `params`, `access_level`, `active`) VALUES
  (50, \'updatepost\', \'Update or edit a post\', \'\', \'\', \'a:1:{i:0;s:6:"logged";}\', 1),
  (51, \'updatecomment\', \'Update or edit a comment\', \'\', \'\', \'a:1:{i:0;s:6:"logged";}\', 1)');
  $version = 3.6;
}
if($version == 3.6){
  $setting = get_option('smapi_options');
  $setting['complex_auth'] = 0;
  update_option('smapi_options', $setting);
  $wpdb->query('INSERT INTO `'.$wpdb->prefix.'smapi_engine` (`id`, `name`, `description`, `group`, `params`, `access_level`, `active`)
  VALUES (52, \'edit_profile\', \'Edit the user profile\', \'\', \'\', \'a:1:{i:0;s:6:"logged";}\', 1)');
  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smapi_smart_auth` (
  `userid` int(11) NOT NULL,
  `lastactive` varchar(50) NOT NULL,
  `session` smallint(6) NOT NULL,
  `fails` tinyint(1) NOT NULL,
  UNIQUE KEY `userid` (`userid`)
  );";
  dbDelta($sql);
  $version = 3.7;
}
if($version == 3.7){
  $catparams = $wpdb->get_var('SELECT `params` FROM `'.$wpdb->prefix.'smapi_engine` WHERE id="26"');
  $catparams = unserialize($catparams);
  array_push($catparams, array('name' => 'custom_fields', 'depend_on' => '', 'active' => 1));
  $wpdb->query("UPDATE `".$wpdb->prefix."smapi_engine` SET `params`='".serialize($catparams)."' WHERE id='26'");

  $version = 3.8;
}
if($version == 3.8){
  $wpdb->query("ALTER TABLE `".$wpdb->prefix."smapi_oauth_clients` ADD `scope` TEXT NOT NULL AFTER `callback_uri`;");
  $wpdb->query("ALTER TABLE `".$wpdb->prefix."smapi_oauth_tokens` CHANGE `expires` `expires` VARCHAR(15) NOT NULL;");
  $wpdb->query("ALTER TABLE `".$wpdb->prefix."smapi_oauth_tokens` CHANGE `oauth_token` `oauth_token` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
  $wpdb->query("ALTER TABLE `".$wpdb->prefix."smapi_oauth_authcodes` CHANGE `code` `code` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
  $wpdb->query('TRUNCATE '.$wpdb->prefix.'smapi_oauth_authcodes');
  $wpdb->query('TRUNCATE '.$wpdb->prefix.'smapi_oauth_tokens');

  $wpdb->query('ALTER TABLE `'.$wpdb->prefix.'smapi_oauth_authcodes` ADD `userid` INT NOT NULL AFTER `client_id`;');
  $wpdb->query('ALTER TABLE `'.$wpdb->prefix.'smapi_oauth_tokens` ADD `userid` INT NOT NULL AFTER `client_id`;');
  $wpdb->query('ALTER TABLE `'.$wpdb->prefix.'smapi_oauth_tokens` ADD `quota` INT NOT NULL ;');
  $wpdb->query('ALTER TABLE `'.$wpdb->prefix.'smapi_oauth_clients` ADD `token_quota` INT NOT NULL AFTER `timestamp`;');
  $wpdb->query('ALTER TABLE `'.$wpdb->prefix.'smapi_oauth_tokens` ADD `md5_oauth_token` VARCHAR(32) NOT NULL FIRST;');
  $wpdb->query('ALTER TABLE '.$wpdb->prefix.'smapi_oauth_tokens DROP PRIMARY KEY');
  $wpdb->query('ALTER TABLE '.$wpdb->prefix.'smapi_oauth_authcodes DROP PRIMARY KEY');

  $wpdb->query('REPAIR TABLE '.$wpdb->prefix.'smapi_oauth_authcodes');
  $wpdb->query('REPAIR TABLE '.$wpdb->prefix.'smapi_oauth_tokens');

  $wpdb->query('ALTER TABLE `'.$wpdb->prefix.'smapi_oauth_tokens` ADD INDEX( `md5_oauth_token`, `userid`);');
  $wpdb->query('ALTER TABLE `'.$wpdb->prefix.'smapi_oauth_authcodes` ADD INDEX( `code`, `userid`);');

  $version = 3.9;
}
if($version == 3.9){
  $version = 4.0;
}
if($version == 4.0){
  $version = 4.1;
}
if($version == 4.1){
  $setting = get_option('smapi_options');
  $setting['purchase_code'] = '';
  update_option('smapi_options', $setting);
  $version = 4.2;
}
if($version == 4.2){
  $version = 4.3;
}
if($version == 4.3){
  $version = 4.4;
}
if($version == 4.4){
  $wpdb->query('INSERT INTO `'.$wpdb->prefix.'smapi_engine` (`id`, `name`, `description`, `group`, `params`, `access_level`, `active`)
  VALUES (53, \'menu_items\', \'Retrieve items of menu\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1)');
  $version = 4.5;
}
if($version == 4.5){
  $version = 4.6;
}
if($version == 4.6){
  $wpdb->query('ALTER TABLE `'.$wpdb->prefix.'social_login` CHANGE `userid` `userid` BIGINT NOT NULL, CHANGE `social_id` `social_id` BIGINT NOT NULL, CHANGE `token` `token` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;');
  $version = 4.7;
}
if($version == 4.7){
  $wpdb->query('INSERT INTO `'.$wpdb->prefix.'smapi_engine` (`id`, `name`, `description`, `group`, `params`, `access_level`, `active`) VALUES
  (54, \'appBootstrape\', \'Mobile application bootstrape loader\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (55, \'follow_author\', \'Users can follow authors\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1),
  (56, \'unfollow_author\', \'Users can unfollow authors\', \'\', \'\', \'a:1:{i:0;s:6:"anyone";}\', 1)');

  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smapi_author_followers` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `userid` int(11) NOT NULL,
    `authorid` int(11) NOT NULL,
    PRIMARY KEY (`id`)
  ) CHARSET=utf8;";
  dbDelta($sql);

  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smapi_auth_tokens` (
    `md5_access_token` varchar(32) NOT NULL,
    `access_token` tinytext NOT NULL,
    `userid` int(11) NOT NULL,
    `expire` varchar(15) DEFAULT NULL,
    `scope` tinytext NOT NULL,
    PRIMARY KEY (`md5_access_token`)
  ) CHARSET=utf8;";
  dbDelta($sql);

  $wpdb->query("RENAME TABLE `".$wpdb->prefix."social_login` TO `".$wpdb->prefix."smapi_social_login`;");
  $wpdb->query("ALTER TABLE `".$wpdb->prefix."smapi_engine` ADD `scope` VARCHAR(20) NOT NULL AFTER `description`;");
  $wpdb->query("ALTER TABLE `".$wpdb->prefix."smapi_social_login` CHANGE `social_id` `social_id` VARCHAR(40) NOT NULL;");

  $settings = get_option('smapi_options');
  $addSettings = array(
    'mob_categories' => '',
    'mob_pages' => '',
    'mob_cat_post_type' => 'post',
    'mob_cat_post_type_tax' => 'category',
    'mob_home_cover' => '',
    'mob_headtitle' => '',
    'mob_home_catids' => '',
    'mob_gmaps_apikey' => '',
    'mob_contact_photo1' => '',
    'mob_contact_photo2' => '',
    'mob_contact_photo3' => '',
    'mob_contact_photo4' => '',
    'mob_contact_name' => '',
    'mob_contact_desc' => '',
    'mob_contact_lat' => '',
    'mob_contact_lng' => '',
    'mob_contact_address' => '',
    'mob_contact_website' => '',
    'mob_contact_email' => '',
    'mob_contact_phone' => '',
    'mob_contact_rating' => '',
    'mob_feeds_style' => 'list',
    'mob_feeds_contsource' => 'contents',
    'mob_common_iosappid' => '',
    'mob_common_andappid' => '',
    'mob_common_winappid' => '',
    'mob_common_iosadid' => '',
    'mob_common_andadid' => '',
    'mob_common_adtype' => 'interstitial',
    'mob_cache_expire' => 60,
    'mob_metakey_lat' => '',
    'mob_metakey_lng' => '',
    'mob_menu_nearby' => 0,
    'mob_menu_follow' => 0,
    'mob_menu_subscription' => 0,
    'mob_menu_notfhistory' => 0,
    'mob_menu_contactus' => 0,
    'mob_home_catmetro' => 1,
    'mob_home_popular' => 0,
    'mob_home_recent' => 1,
    'mob_home_iosads' => 0,
    'mob_home_andads' => 0,
    'mob_feeds_fimage' => 1,
    'mob_post_fimage' => 1,
    'mob_post_showcomms' => 0,
    'mob_post_addcomms' => 0,
    'mob_post_author' => 0,
    'mob_post_categories' => 0,
    'mob_post_iosads' => 0,
    'mob_post_andads' => 0,
    'mob_common_gps' => 0,
    'mob_common_push' => 0,
    'acctoken_auth' => 0,
    'token_expire' => 0,
    'visitor_can_post' => 0,
    'allowed_scopes' => array('public','core','posts','publish_posts','comments','publish_comments','profiles','edit_profile','taxonomies'),
    'public_scopes' => array('public','posts','comments','profiles','taxonomies','core')
  );
  $settings = array_merge($settings, $addSettings);
  unset($settings['complex_auth']);
  unset($settings['oauth_mode']);
  unset($settings['stat_clean_interval']);
  update_option('smapi_options', $settings);

  delete_option('smapi_stats');
  wp_clear_scheduled_hook('smapi_cron_cleaner');

  $wpdb->query("DROP TABLE `".$wpdb->prefix."smapi_oauth_authcodes`");
  $wpdb->query("DROP TABLE `".$wpdb->prefix."smapi_oauth_clients`");
  $wpdb->query("DROP TABLE `".$wpdb->prefix."smapi_oauth_tokens`");
  $wpdb->query("DROP TABLE `".$wpdb->prefix."smapi_smart_auth`");

  $wp_smapi_engine = array(
    array('id' => '1','scope' => 'public'),
    array('id' => '2','scope' => 'public'),
    array('id' => '3','scope' => 'public'),
    array('id' => '4','scope' => 'public'),
    array('id' => '5','scope' => 'public'),
    array('id' => '6','scope' => 'profiles'),
    array('id' => '7','scope' => 'profiles'),
    array('id' => '8','scope' => 'posts'),
    array('id' => '9','scope' => 'posts'),
    array('id' => '10','scope' => 'publish_posts'),
    array('id' => '11','scope' => 'publish_posts'),
    array('id' => '12','scope' => 'posts'),
    array('id' => '13','scope' => 'posts'),
    array('id' => '14','scope' => 'posts'),
    array('id' => '15','scope' => 'posts'),
    array('id' => '16','scope' => 'posts'),
    array('id' => '17','scope' => 'posts'),
    array('id' => '18','scope' => 'posts'),
    array('id' => '19','scope' => 'posts'),
    array('id' => '20','scope' => 'comments'),
    array('id' => '21','scope' => 'publish_comments'),
    array('id' => '22','scope' => 'comments'),
    array('id' => '23','scope' => 'comments'),
    array('id' => '24','scope' => 'core'),
    array('id' => '25','scope' => 'core'),
    array('id' => '26','scope' => 'taxonomies'),
    array('id' => '27','scope' => 'taxonomies'),
    array('id' => '28','scope' => 'core'),
    array('id' => '29','scope' => 'taxonomies'),
    array('id' => '30','scope' => 'taxonomies'),
    array('id' => '31','scope' => 'posts'),
    array('id' => '32','scope' => 'public'),
    array('id' => '33','scope' => 'core'),
    array('id' => '34','scope' => 'public'),
    array('id' => '35','scope' => 'public'),
    array('id' => '36','scope' => 'public'),
    array('id' => '37','scope' => 'public'),
    array('id' => '38','scope' => 'public'),
    array('id' => '39','scope' => 'public'),
    array('id' => '40','scope' => 'public'),
    array('id' => '41','scope' => 'public'),
    array('id' => '42','scope' => 'core'),
    array('id' => '43','scope' => 'edit_profile'),
    array('id' => '44','scope' => 'edit_profile'),
    array('id' => '45','scope' => 'manage_posts'),
    array('id' => '46','scope' => 'manage_comments'),
    array('id' => '47','scope' => 'core'),
    array('id' => '48','scope' => 'manage_posts'),
    array('id' => '49','scope' => 'manage_comments'),
    array('id' => '50','scope' => 'manage_posts'),
    array('id' => '51','scope' => 'publish_comments'),
    array('id' => '52','scope' => 'edit_profile'),
    array('id' => '53','scope' => 'core'),
    array('id' => '54','scope' => 'public'),
    array('id' => '55','scope' => 'public'),
    array('id' => '56','scope' => 'public')
  );
  foreach($wp_smapi_engine as $engine_schema){
    $wpdb->update($wpdb->prefix.'smapi_engine', array('scope' => $engine_schema['scope']), array('id' => $engine_schema['id']));
  }

  $version = 5;
}
if($version == 5){
  $newEndPoint = array('id' => 57,'name' => 'bb_new_topic','description' => 'Add new bbPress topic','group' => '','params' => '','access_level' => 'a:1:{i:0;s:6:"logged";}','scope' => 'publish_posts','active' => 1);
  $wpdb->insert($wpdb->prefix.'smapi_engine', $newEndPoint);

  $newEndPoint = array('id' => 58,'name' => 'bb_new_comment','description' => 'Add new bbPress comment','group' => '','params' => '','access_level' => 'a:1:{i:0;s:6:"logged";}','scope' => 'publish_comments','active' => 1);
  $wpdb->insert($wpdb->prefix.'smapi_engine', $newEndPoint);

  $version = 5.1;
}
if($version == 5.1){
  $newEndPoint = array('id' => 59,'name' => 'request_token','description' => 'Request new access token','group' => '','params' => '','access_level' => 'a:1:{i:0;s:6:"anyone";}','scope' => 'core','active' => 1);
  $wpdb->insert($wpdb->prefix.'smapi_engine', $newEndPoint);

  $newEndPoint = array('id' => 60,'name' => 'refresh_token','description' => 'Refresh generated access token','group' => '','params' => '','access_level' => 'a:1:{i:0;s:6:"anyone";}','scope' => 'core','active' => 1);
  $wpdb->insert($wpdb->prefix.'smapi_engine', $newEndPoint);

  $sql = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."smapi_oauth_clients` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `app_id` int(11) NOT NULL,
    `auth_key` varchar(256) NOT NULL,
    `quota` varchar(50) NOT NULL,
    `req_usage` varchar(50) NOT NULL,
    `settings` text NOT NULL,
    `status` BOOLEAN NOT NULL,
    PRIMARY KEY (`id`)
  ) CHARSET=utf8;";
  dbDelta($sql);
  $wpdb->query("ALTER TABLE `".$wpdb->prefix."smapi_auth_tokens` DROP PRIMARY KEY;");
  $wpdb->query("ALTER TABLE `".$wpdb->prefix."smapi_auth_tokens` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`), ADD `clientid` INT NOT NULL AFTER `access_token`;");
  $wpdb->query("ALTER TABLE `".$wpdb->prefix."smapi_auth_tokens` CHANGE `access_token` `access_token` VARCHAR(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");

  $settings = get_option('smapi_options');
  $settings['auth_type'] = ($settings['acctoken_auth'] == 1)? 'acctoken' : 'auth_key';
  unset($settings['acctoken_auth']);
  update_option('smapi_options', $settings);

  $version = 5.2;
}
if($version == 5.2){
  $settings = get_option('smapi_options');
  $settings['oauth2scopes'] = array('public','core','posts','publish_posts','comments','publish_comments','profiles','edit_profile','taxonomies','manage_posts','manage_comments');
  update_option('smapi_options', $settings);

  $version = 5.3;
}
if($version == 5.3){
  $newEndPoint = array('id' => 61,'name' => 'delete_myaccount','description' => 'User terminate his account','group' => '','params' => '','access_level' => 'a:1:{i:0;s:6:"logged";}','scope' => 'core','active' => 1);
  $wpdb->insert($wpdb->prefix.'smapi_engine', $newEndPoint);

  $version = 5.31;
}
if($version == 5.31){
  $settings = get_option('smapi_options');
  $settings['cache_status'] = 0;
  $settings['cache_expire'] = 1;
  update_option('smapi_options', $settings);

  $version = 5.4;
}
if($version == 5.4){
  $version = 5.5;
}
if($version == 5.5){
  $wpdb->query('UPDATE '.$wpdb->prefix.'smapi_engine SET params=\'a:9:{i:0;a:3:{s:4:"name";s:2:"id";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:1;a:3:{s:4:"name";s:4:"name";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:2;a:3:{s:4:"name";s:4:"slug";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:3;a:3:{s:4:"name";s:11:"description";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:4;a:3:{s:4:"name";s:5:"count";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:5;a:3:{s:4:"name";s:6:"parent";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:6;a:3:{s:4:"name";s:5:"image";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:7;a:3:{s:4:"name";s:11:"subcategory";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}i:8;a:3:{s:4:"name";s:13:"custom_fields";s:9:"depend_on";s:0:"";s:6:"active";s:1:"1";}}\' WHERE name="categories"');
  $wpdb->query('ALTER TABLE '.$wpdb->prefix.'smapi_engine CHANGE `group` `group` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
  $wpdb->query('UPDATE '.$wpdb->prefix.'smapi_engine SET `group`=NULL WHERE `group`=""');
  $wpdb->query('UPDATE `'.$wpdb->prefix.'smapi_engine` SET `group` = \'categories\' WHERE `name` = "tags";');
  $wpdb->query('UPDATE `'.$wpdb->prefix.'smapi_engine` SET `group` = \'getposts\' WHERE `name` = "appBootstrape";');
  $version = 5.6;
}
if($version == 5.6){
  $version = 5.61;
}
if($version == 5.61){
  $wpdb->query("UPDATE `".$wpdb->prefix."smapi_engine` SET `group` = 'categories' WHERE `name` = 'get_taxonomy';");

  $settings = get_option('smapi_options');
  $settings['cache_listener'] = 0;
  update_option('smapi_options', $settings);

  $version = 5.62;
}
if($version == 5.62){
  $version = 5.63;
}
if($version == 5.63){
  $version = 5.64;
}
if($version == 5.64){
  $version = 5.65;
}
if($version == 5.65){
  $newEndPoint = array('id' => 62,'name' => 'delete_author_post','description' => 'Author deletes one of his posts','group' => '','params' => '','access_level' => 'a:1:{i:0;s:6:"logged";}','scope' => 'publish_posts','active' => 1);
  $wpdb->insert($wpdb->prefix.'smapi_engine', $newEndPoint);

  $version = 5.66;
}
if($version == 5.66){
  $version = 5.67;
}
if($version == 5.67){
  $version = 5.68;
}
if($version == 5.68){
  $version = 5.69;
}
if($version == 5.69){
  $version = 5.691;
}
if($version == 5.691){
  $settings = get_option('smapi_options');
  $settings['mob_debug_ads'] = 0;
  update_option('smapi_options', $settings);

  $cache = new smapi_cache($settings['cache_status'], $settings['cache_expire']);
  $cache->purgeCache();

  $version = 5.692;
}
if($version == 5.692){
  $version = 5.693;
}
if($version == 5.693){
  $version = 5.694;
}
if($version == 5.694){
  $version = 5.7;
}
if($version == 5.7){
  $version = 5.8;
}
if($version == 5.8){
  $version = 5.81;
}
if($version == 5.81){
  $version = 5.82;
}
if($version == 5.82){
  $version = 5.83;
}
if($version == 5.83){
  $version = 5.84;
}
if($version == 5.84){
  $version = 5.85;
}
if($version == 5.85){
  $version = 5.86;
}
if($version == 5.85){
  $version = 5.87;
}

@unlink(ABSPATH.'/smart-bridge.php');

update_option('smapi_version', $version);
