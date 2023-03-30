<?php
/*
Plugin Name: DIOT SCADA with MQTT
Plugin URI:
Description: Allows live data to be displayed with shortcodes by using MQTT subscriber
Version: 1.0.5.1
Author: Ecava
Author URI: https://www.integraxor.com/?utm_source=wp
License: GPLv2
*/

// Exit if accessed directly. info: ABSPATH should be defined in wp-config and would not be defined if file was directly accessed rather than going through wordpress
if ( ! defined( 'ABSPATH' ) ) exit;

// Singleton : https://code.tutsplus.com/articles/design-patterns-in-wordpress-the-singleton-pattern--wp-31621
if ( ! class_exists( 'ECAVA_DIOT_SCADA' ) ) {
	class ECAVA_DIOT_SCADA {
		/**
		 * @var         ECAVA_DIOT_SCADA $instance
		 * @since       1.0.0
		 */
		private static $instance;


		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      object self::$instance
		 */
		public static function instance() {
			if( !self::$instance ) {
				self::$instance = new ECAVA_DIOT_SCADA();
				self::$instance->setup_constants();
				self::$instance->includes();
			}

			return self::$instance;
		}
		
		/**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {

            // Plugin version
            define( 'ECAVA_DIOT_SCADA_VERS', '1.0.0' );

            // Plugin path
            define( 'ECAVA_DIOT_SCADA_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'ECAVA_DIOT_SCADA_URL', plugin_dir_url( __FILE__ ) );
			
			// Plugin menu
			define('ECAVA_DIOT_SCADA_MANAGEMENT_PERMISSION', 'manage_options');
			define('ECAVA_DIOT_SCADA_MAIN_MENU_SLUG', 'ecava-diot-scada');
			define('ECAVA_DIOT_SCADA_MENU_ICON', ECAVA_DIOT_SCADA_URL . 'assets/icon.png'); //'dashicons-editor-contract');
				
			define('ECAVA_DIOT_SCADA_MQTT_SETTINGS_OPTIONS', 'ecava_diot_scada_mqtt_settings');
		}
		
		 /**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function includes() {

			require_once ECAVA_DIOT_SCADA_DIR . 'includes/shortcodes.php';
			require_once ECAVA_DIOT_SCADA_DIR . 'includes/scripts.php';
			require_once ECAVA_DIOT_SCADA_DIR . 'includes/listener.php';
			
			//Include admin side only files
			if (is_admin()) {
				include_once('menu/settings.php');
			}
		}		
	}
	
	/**
	 * The main function responsible for returning the only instance of ECAVA_DIOT_SCADA
	 *
	 * @since       1.0.0
	 * @return      ECAVA_DIOT_SCADA instance
	 */
	function ECAVA_DIOT_SCADA_Load() {
		return ECAVA_DIOT_SCADA::instance();
	}
	
	ECAVA_DIOT_SCADA_Load();
}
?>