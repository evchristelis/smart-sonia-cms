<?php

// if called without WordPress, exit
if( !defined('ABSPATH') ){ exit; }


if( !class_exists('WP_MQTT_Help') ){

	class WP_MQTT_Help {

		/**
		 * Start up
		 */
		public function __construct(){
			add_action( 'load-settings_page_wp-mqtt-settings', array( $this, 'my_plugin_add_help' ) );
		}


		function my_plugin_add_help() {

			$screen = get_current_screen();
						
			$broker = '<h3>' . __( 'Setting up the broker', 'wp-mqtt' ) . '</h3>';
			$broker .= '<p>' . __( 'An MQTT broker is a server that distrubutes MQTT messages to subscribed devices.', 'wp-mqtt' ) . ' ';
			$broker .= __( "In order for WP-MQTT to connect to a broker, it needs the following information.", 'wp-mqtt' ) . '</p>';
			$broker .= '<ul>';
			$broker .= '<li>' . __( 'URL (for example: "iot.somedomain.com")', 'wp-mqtt' ) . '</li>';
			$broker .= '<li>' . __( 'Port number (defaults to 1883, or 8883 for connections using TLS)', 'wp-mqtt' ) . '</li>';
			$broker .= '<li>' . __( 'QoS ("Quality of Service", defaults to 0)', 'wp-mqtt' ) . '</li>';
			$broker .= '<li>' . __( 'Client ID (must be unique for each device that connects to a broker)', 'wp-mqtt' ) . '</li>';
			$broker .= '<li>' . __( 'Username (optional, only needed if required by the broker)', 'wp-mqtt' ) . '</li>';
			$broker .= '<li>' . __( 'Password (optional, only needed if required by the broker)', 'wp-mqtt' ) . '</li>';
			$broker .= '</ul>';

			$screen->add_help_tab( array( 'id' => 'mqtt-broker-help', 'title' => __( 'Broker settings', 'wp-mqtt' ), 'content' => $broker ));
			
			$events = '<h3>' . __( 'Common events', 'wp-mqtt' ) . '</h3>';
			$events .= '<p>' . __( 'This section allows you to set up messages for common WordPress events. Simply activate them using the checkbox and supply a topic and message.', 'wp-mqtt' ) . '</p>';
			$events .= '<ul>';
			$events .= '<li>' . __( 'Pageview (fires on each page view on the front end of your site)', 'wp-mqtt' ) . '</li>';
			$events .= '<li>' . __( 'User login (fires when a user logs in successfully)', 'wp-mqtt' ) . '</li>';
			$events .= '<li>' . __( 'Failed user login (fires on failed login attempts)', 'wp-mqtt' ) . '</li>';
			$events .= '<li>' . __( 'Post published (fires when a new post is published)', 'wp-mqtt' ) . '</li>';
			$events .= '<li>' . __( 'Page published (fires when a new page is published)', 'wp-mqtt' ) . '</li>';
			$events .= '<li>' . __( 'New comment (fires when a new comment is submitted)', 'wp-mqtt' ) . '</li>';
			$events .= '</ul>';

			$screen->add_help_tab( array( 'id' => 'mqtt-events-help', 'title' => __( 'Common events', 'wp-mqtt' ), 'content' => $events ));

			$custom = '<h3>' . __( 'Common events', 'wp-mqtt' ) . '</h3>';
			$custom .= '<p>' . __( 'Create custom events by providing the following values', 'wp-mqtt' ) . '</p>';
			$custom .= '<ul>';
			$custom .= '<li>' . __( 'Use the checkbox to enable/disable the event', 'wp-mqtt' ) . '</li>';
			$custom .= '<li>' . __( "Enter a WordPress hook, i.e. 'spam_comment'", 'wp-mqtt' ) . '</li>';
			$custom .= '<li>' . __( 'Provided the topic for the MQTT message', 'wp-mqtt' ) . '</li>';
			$custom .= '<li>' . __( 'The message content', 'wp-mqtt' ) . '</li>';
			$custom .= '</ul>';

			$screen->add_help_tab( array( 'id' => 'mqtt-custom-events-help', 'title' => __( 'Custom events', 'wp-mqtt' ), 'content' => $custom ));

			$placeholders = '<h3>' . __( 'Content placeholders', 'wp-mqtt' ) . '</h3>';
			$placeholders .= '<p>' . __( "All events support a number of placeholders. These are replaced with actual content in the outgoing message, provided the information is supplied by WordPress when the event occurs.", 'wp-mqtt' ) . '</p>';
			
			$placeholders .= '<h4>' . __( 'Placeholders for events "Post published" and "Page published" (or custom events involving WP_Post)', 'wp-mqtt' ) . '</h4>';
			$placeholders .= '<ul>';
			$placeholders .= '<li>' . __( "%POST_ID% (the post's ID number)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_AUTHOR% (the post author's display name)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_AUTHOR_ID% (the post author's ID number)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_TITLE% (the post's title)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_EXCERPT% (the post excerpt, if available)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_CONTENT% (full post content, could be rather long and usually contains HTML markup)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_TYPE% (the post's type)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_DATE% (the post's date)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_DATE_GMT% (the post's GMT date)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_STATUS% (the post's status, i.e. 'publish')", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_SLUG% (the post's 'slug')", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_MODIFIED% (the post's modification date)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_MODIFIED_GMT% (the post's GMT modification date)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_PARENT% (the post's parent ID, if any)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_GUID% (the post's URL)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%POST_COMMENT_COUNT% (the number of comments for the post)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '</ul>';
			
			$placeholders .= '<h4>' . __( 'Placeholders for "New Comment" (or custom events involving WP_Comment)', 'wp-mqtt' ) . '</h4>';
			$placeholders .= '<ul>';
			$placeholders .= '<li>' . __( "%COMMENT_AUTHOR% (the comment author's display name)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENT_AUTHOR_EMAIL% (the comment author's email address)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENT_AUTHOR_URL% (the comment author's website URL)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENT_AUTHOR_IP% (the comment author's IP number)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENT_CONTENT% (the comment's content)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENT_ID% (the comment's database ID)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENT_POST_ID% (the post's database ID)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENT_PARENT% (the comment's parent's ID, if any)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENT_USER_ID% (the comment author's user ID, if logged in)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENT_DATE% (the comment's date)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENT_DATE_GMT% (the comment's GMT date)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENT_AGENT% (the user agent used to post the comment)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '</ul>';

			$placeholders .= '<h4>' . __( 'Placeholders for "User login" (or custom events involving WP_User)', 'wp-mqtt' ) . '</h4>';
			$placeholders .= '<ul>';
			$placeholders .= '<li>' . __( "%USER_DISPLAY_NAME% (the user's display name)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%USER_ID% (the user's database ID)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%USER_LOGIN% (the user's login name)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%USER_NAME% (the user's name)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%USER_EMAIL% (the user's email address)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%USER_URL% (the user's website URL)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%USER_REGISTRATION_DATE% (the user's registration date)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%USER_ROLES% (comma separated list of the user's roles)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '</ul>';

			$placeholders .= '<h4>' . __( 'General statistics (all events)', 'wp-mqtt' ) . '</h4>';
			$placeholders .= '<ul>';
			$placeholders .= '<li>' . __( "%POSTS_PUBLISHED% (the total number of published posts)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%PAGES_PUBLISHED% (the total number of published pages)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENTS_APPROVED% (the total number of approved comments)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '<li>' . __( "%COMMENTS_MODERATION% (the number of comments in the moderation queue)", 'wp-mqtt' ) . '</li>';
			$placeholders .= '</ul>';

			$placeholders .= '<h4>' . __( 'Directly output hook arguments', 'wp-mqtt' ) . '</h4>';
			$placeholders .= '<p>' . __( 'Use %ARG0% in your message to output the contents of the first argument supplied by the WordPress hook. %ARG1% for the second argument, etc. This only works if the argument is a string or a number.', 'wp-mqtt' ) . '</p>';

			$screen->add_help_tab( array( 'id' => 'mqtt-placeholders-help', 'title' => __( 'Content placeholders', 'wp-mqtt' ), 'content' => $placeholders ));

			$about = '<h3>' . __( 'About WP-MQTT</h3>', 'wp-mqtt' ) . '</h3>';
			$about .= '<p>' . __( 'Connect WordPress to the Internet of Things. WP-MQTT allows you to automatically send MQTT messages when something happens on your WordPress website.', 'wp-mqtt' ) . '</p>';
			$about .= '<p>' . __( 'WP-MQTT was created by Roy Tanck and released under the GPL license.', 'wp-mqtt' ) . '</p>';

			$screen->add_help_tab( array( 'id' => 'mqtt-about', 'title' => __( 'About WP-MQTT', 'wp-mqtt' ), 'content'  => $about ));

			// Help sidebars are optional
			$screen->set_help_sidebar(
				'<p><strong>' . __( 'For more information:' ) . '</strong></p>' .
				'<p><a href="http://wordpress.org/support/" target="_blank">' . __( 'Support Forums', 'wp-mqtt' ) . '</a><br />' .
				'<a href="http://wordpress.org/support/" target="_blank">' . __( 'Support Forums', 'wp-mqtt' ) . '</a><br />' .
				'<a href="http://mqtt.org" target="_blank">' . __( 'MQTT.org', 'wp-mqtt' ) . '</a><br />' .
				'<a href="http://mqtt.org/faq" target="_blank">' . __( 'MQTT.org FAQ', 'wp-mqtt' ) . '</a></p>'
			);
		}

	}
}

// create an instance
if( is_admin() ){
	$wp_mqtt_help_instance = new WP_MQTT_Help();
}

?>