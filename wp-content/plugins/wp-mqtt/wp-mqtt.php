<?php
/*
	Plugin Name: WP-MQTT
	Plugin URI:  http://www.roytanck.com
	Description: Send MQTT messages from WordPress
	Version:     1.0.2
	Author:      Roy Tanck
	Author URI:  http://www.roytanck.com
	Text Domain: wp-mqtt
	Domain path: /languages
	License:     GPL
*/

// if called without WordPress, exit
if( !defined('ABSPATH') ){ exit; }

// require the phpMQTT library
require_once( 'lib/vendor/phpMQTT/phpMQTT.php' );

// require the setting page
require_once( 'inc/wp-mqtt-settings.php' );

// require the settings page contextual help
require_once( 'inc/wp-mqtt-help.php' );


/**
 * Class WP_MQTT
 */
if( !class_exists('WP_MQTT') ){

	class WP_MQTT {

		public $client_id = 'wp-mqtt';
		public $settings = null;
		public $mqtt = null;
		public $connected = false;

		/**
		 * Constructor
		 */
		function __construct() {

			// load the plugin's text domain			
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			// enque the admin js
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_js' ) );
			// shut down the broker connection when WordPress is about to finish
			add_action( 'shutdown', array( $this, 'close_connection' ) );

			// get the plugin's settings, and use them to connect to the server
			$settings = get_option('wp_mqtt_settings');
			if( $settings ){
				$this->settings = $settings;

				// set up the pageview event
				if( isset( $settings['event_pageview']['checkbox'] ) && $settings['event_pageview']['checkbox'] == 'true' ){
					add_action( 'template_redirect', array( $this, 'event_pageview' ), 10, 0 );
				}

				// set up the login event
				if( isset( $settings['event_login']['checkbox'] ) && $settings['event_login']['checkbox'] == 'true' ){
					add_action( 'wp_login', array( $this, 'event_login' ), 10, 2 );
				}

				// set up the failed login event
				if( isset( $settings['event_login_failed']['checkbox'] ) && $settings['event_login_failed']['checkbox'] == 'true' ){
					add_action( 'wp_login_failed', array( $this, 'event_login_failed' ), 10, 1 );
				}

				// set up the new post event
				if( isset( $settings['event_new_post']['checkbox'] ) && $settings['event_new_post']['checkbox'] == 'true' ){
					add_action( 'publish_post', array( $this, 'event_new_post' ), 10, 2 );
				}

				// set up the new page event
				if( isset( $settings['event_new_page']['checkbox'] ) && $settings['event_new_page']['checkbox'] == 'true' ){
					add_action( 'publish_page', array( $this, 'event_new_page' ), 10, 2 );
				}

				// set up the new comment event
				if( isset( $settings['event_new_comment']['checkbox'] ) && $settings['event_new_comment']['checkbox'] == 'true' ){
					add_action( 'wp_insert_comment', array( $this, 'event_new_comment' ), 10 ,2 );
				}

				// set up custom events, checking for valid settings
				if( $settings['custom_events_enable'] == true ){
					if( isset( $settings['custom_events'] ) && is_array( $settings['custom_events'] ) ){
						// loop through the custom events
						foreach( $settings['custom_events'] as $key => $event ){
							// if no hook, skip
							if( !empty( $event['hook'] ) ){
								//  use a closure to be able to read the settings
								add_action( $event['hook'], function() use ( $key ) {
									// get the function's arguments
									$args = func_get_args();
									//  get the event's settings from the array
									$custom_event = $this->settings['custom_events'][$key];
									// check if the event is active
									if( $custom_event['checkbox'] == true ){
										$topic = $custom_event['topic'];
										$message = $custom_event['message'];
										// replace placeholders in the message, if possible
										$message = $this->replace_placeholders( $message, $args );
										// publish the message
										$this->publish( $topic, $message );
									}
									// this could be a filter action, so return the first argument
									if( $args[0] ){
										return $args[0];
									}
								}, 10, 10 );
							}
						}
					}
				}

			}

		}


		/**
		 * Load the translated strings
		 */
		function load_textdomain(){
			load_plugin_textdomain( 'wp-mqtt', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * Enqueue the admin javascript file
		 */
		public function enqueue_admin_js( $hook ){
			if ( 'settings_page_wp-mqtt-settings' != $hook ) {
				return;
			}
			wp_enqueue_script( 'wp-mqtt-admin-js', plugins_url( 'js/wp-mqtt-admin.js', __FILE__ ), array('jquery') );
		}


		/**
		 * Connect to the MQTT broker (if not already connected)
		 */
		public function connect(){
			if( $this->mqtt == null ){
				$this->mqtt = new phpMQTT( $this->settings['broker_url'], $this->settings['broker_port'], $this->settings['broker_client_id'] );
				if( $this->mqtt->connect( true, null, $this->settings['broker_username'], $this->settings['broker_password'] ) ){
					$this->connected = true;
				}
			}
		}


		/**
		 * Close the connection just before WordPress shuts down
		 */
		public function close_connection(){
			if( $this->connected ){
				$this->mqtt->close();
			}
		}


		/**
		 * Send the MQTT message
		 */
		public function publish( $topic, $message ){
			// apply filters to the topic
			$topic = apply_filters( 'wp_mqtt_filter_topic', $topic, $message );
			// apply filters to the message
			$message = apply_filters( 'wp_mqtt_filter_message', $message, $topic );
			// attempt to connect to the broker
			$this->connect();
			// check if the connection was made
			if( $this->connected ){
				// publish the message
				$this->mqtt->publish( $topic, $message, $this->settings['broker_qos'] );
			}			
		}


		/**
		 * Event handler functions
		 */
		public function event_pageview(){
			$this->assemble_mqtt_message( 'event_pageview', func_get_args() );
		}

		public function event_login( $login, $user ){
			$this->assemble_mqtt_message( 'event_login', func_get_args() );
		}

		public function event_login_failed( $login ){
			$this->assemble_mqtt_message( 'event_login_failed', func_get_args() );
		}

		public function event_new_post( $id, $post ){
			$this->assemble_mqtt_message( 'event_new_post', func_get_args() );
		}

		public function event_new_page( $id, $post ){
			$this->assemble_mqtt_message( 'event_new_page', func_get_args() );
		}

		public function event_new_comment( $id, $comment ){
			$this->assemble_mqtt_message( 'event_new_comment', func_get_args() );
		}

		public function assemble_mqtt_message( $event_id, $args ){
			$topic = isset( $this->settings[$event_id]['topic'] ) ? $this->settings[$event_id]['topic'] : 'default';
			$message = isset( $this->settings[$event_id]['message'] ) ? $this->settings[$event_id]['message'] : 'default';
			// replace placeholders with actual content
			$message = $this->replace_placeholders( $message, $args );
			$this->publish( $topic, $message );
		}


		/**
		 * Replace placholders ('%SOMETHING%') with actual content from the hook arguments
		 */
		public function replace_placeholders( $message, $args ){

			// insert "simple" (number/string) arguments directly
			for( $i = 0; $i < count( $args ); $i++ ){
				if( is_numeric( $args[$i] ) || is_string( $args[$i] ) ){
					$message = str_replace( '%ARG' . $i . '%', $args[$i], $message );
				}
			}

			// loop through the argument looking for known WP classes
			foreach( $args as $arg ){

				// if the argument is of type WP_Post, replace the appropriate placeholders
				if( is_a( $arg, 'WP_Post' ) ){
					$search = array(
						'%POST_ID%',
						'%POST_AUTHOR%',
						'%POST_AUTHOR_ID%',
						'%POST_TITLE%',
						'%POST_EXCERPT%',
						'%POST_CONTENT%',
						'%POST_TYPE%',
						'%POST_DATE%',
						'%POST_DATE_GMT%',
						'%POST_STATUS%',
						'%POST_SLUG%',
						'%POST_MODIFIED%',
						'%POST_MODIFIED_GMT%',
						'%POST_PARENT%',
						'%POST_GUID%',
						'%POST_COMMENT_COUNT%',
					);
					$replace = array(
						$arg->ID,
						get_the_author_meta( 'display_name', $arg->post_author ),
						$arg->post_author,
						$arg->post_title,
						$arg->post_excerpt,
						$arg->post_content,
						$arg->post_type,
						$arg->post_date,
						$arg->post_date_gmt,
						$arg->post_status,
						$arg->post_name,
						$arg->post_modified,
						$arg->post_modified_gmt,
						$arg->post_parent,
						$arg->guid,
						$arg->comment_count,
					);
					$message = str_replace( $search, $replace, $message );
				}

				// if the argument is of type WP_Comment, replace the appropriate placeholders
				if( is_a( $arg, 'WP_Comment' ) ){
					$search = array(
						'%COMMENT_AUTHOR%',
						'%COMMENT_AUTHOR_EMAIL%',
						'%COMMENT_AUTHOR_URL%',
						'%COMMENT_AUTHOR_IP%',
						'%COMMENT_CONTENT%',
						'%COMMENT_ID%',
						'%COMMENT_POST_ID%',
						'%COMMENT_PARENT%',
						'%COMMENT_USER_ID%',
						'%COMMENT_DATE%',
						'%COMMENT_DATE_GMT%',
						'%COMMENT_AGENT%',
					);
					$replace = array(
						$arg->comment_author,
						$arg->comment_author_email,
						$arg->comment_author_url,
						$arg->comment_author_IP,
						$arg->comment_content,
						$arg->comment_ID,
						$arg->comment_post_ID,
						$arg->comment_parent,
						$arg->user_id,
						$arg->comment_date,
						$arg->comment_date_gmt,
						$arg->comment_agent,
					);
					$message = str_replace( $search, $replace, $message );
				}

				// if the argument is of type WP_User, replace the appropriate placeholders
				if( is_a( $arg, 'WP_User' ) ){
					$search = array(
						'%USER_DISPLAY_NAME%',
						'%USER_ID',
						'%USER_LOGIN',
						'%USER_NAME',
						'%USER_EMAIL',
						'%USER_URL',
						'%USER_REGISTRATION_DATE',
						'%USER_ROLES%'
					);
					$replace = array(
						$arg->display_name,
						$arg->ID,
						$arg->user_login,
						$arg->user_nicename,
						$arg->user_email,
						$arg->user_url,
						$arg->user_registered,
						join( ',', $arg->roles )
					);
					$message = str_replace( $search, $replace, $message );
				}

			}

			// some overall statistics
			if( strpos( $message, '%POSTS_PUBLISHED%' ) !== false ){
				$message = str_replace( '%POSTS_PUBLISHED%', wp_count_posts('post')->publish, $message );
			}
			if( strpos( $message, '%PAGES_PUBLISHED%' ) !== false ){
				$message = str_replace( '%PAGES_PUBLISHED%', wp_count_posts('page')->publish, $message );
			}
			if( strpos( $message, '%COMMENTS_APPROVED%' ) !== false ){
				$message = str_replace( '%COMMENTS_APPROVED%', wp_count_comments()->approved, $message );
			}
			if( strpos( $message, '%COMMENTS_MODERATION%' ) !== false ){
				$message = str_replace( '%COMMENTS_MODERATION%', wp_count_comments()->moderated, $message );
			}

			// return the updated message
			return $message;
		}

	}

}

// create an instance
$wp_mqtt_instance = new WP_MQTT();