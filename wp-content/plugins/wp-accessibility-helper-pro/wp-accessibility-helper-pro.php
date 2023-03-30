<?php
/*
    Plugin Name: WP Accessibility Helper PRO
    Plugin URI: https://accessibility-helper.co.il/
    Description: WP Accessibility Helper PRO sidebar
    Author: Alexander Volkov
    Version: 0.1.7.4
    Author URI: http://www.volkov.co.il
    Text Domain: wp-accessibility-helper
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define( 'WAHPRO_VERSION', '0.1.7.4' );
include_once( dirname(__FILE__)  . '/api/api-functions.php');
include_once( dirname(__FILE__)  . '/inc/wah-front-functions.php');
include_once( dirname(__FILE__)  . '/admin/shortcodes.php');
include_once( dirname(__FILE__)  . '/gutenberg/init.php');

/******************************
    Auto updates
*******************************/

add_filter( 'plugins_api', 'wah_pro_plugin_info', 20, 3);
function wah_pro_plugin_info( $res, $action, $args ){

    $license_user = get_wah_pro_license_email();
    $license_key  = get_wah_pro_license_key();
    $plugin_slug  = 'wp-accessibility-helper-pro';

	// do nothing if this is not about getting plugin information
	if( $action !== 'plugin_information' ){
        return false;
    }

	// do nothing if it is not our plugin
	if( $plugin_slug !== $args->slug ){
        return false;
    }

	// trying to get from cache first
	if( false == $remote = get_transient( 'wah_pro_upgrade_wp-accessibility-helper-pro' ) ) {

		// info.json is the file with the actual plugin information on your server
		$remote = wp_remote_get( 'https://accessibility-helper.co.il/info.json', array(
			'timeout' => 120,
			'headers' => array(
				'Accept' => 'application/json'
			))
		);

		if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
			set_transient( 'wah_pro_upgrade_wp-accessibility-helper-pro', $remote, 43200 ); // 12 hours cache
		}

	}

    // validate if user & license submitted
	if( !is_wp_error( $remote ) && $license_user && $license_key ) {

		$remote              = json_decode( $remote['body'] );
		$res                 = new stdClass();
		$res->name           = $remote->name;
		$res->slug           = $plugin_slug;
		$res->version        = $remote->version;
		$res->tested         = $remote->tested;
		$res->requires       = $remote->requires;
		$res->author         = '<a href="https://accessibility-helper.co.il/">Alex Volkov & WAH Team</a>';
		$res->author_profile = 'https://profiles.wordpress.org/vol4ikman/';
		$res->download_link  = $remote->download_url;
		$res->trunk          = $remote->download_url;
		$res->last_updated   = $remote->last_updated;

		$res->sections       = array();
        if( ! empty( $remote->sections->changelog ) ){
            $res->sections['changelog'] = $remote->sections->changelog;
        }
		if( !empty( $remote->sections->screenshots ) ) {
			$res->sections['screenshots'] = $remote->sections->screenshots;
		}

		$res->banners = array(
			'low'  => 'https://ps.w.org/wp-accessibility-helper/assets/banner-772x250.jpg',
            // 'high' => 'https://YOUR_WEBSITE/banner-1544x500.jpg'
		);

        return $res;

	}

	return false;

}

add_filter('site_transient_update_plugins', 'wah_pro_push_update' );
function wah_pro_push_update( $transient ){

    $license_user = get_wah_pro_license_email();
    $license_key  = get_wah_pro_license_key();

	if ( empty( $transient->checked ) ) {
        return $transient;
    }

	// trying to get from cache first
	if( false == $remote = get_transient( 'wah_pro_upgrade_wp-accessibility-helper-pro' ) ) {

		// info.json is the file with the actual plugin information on your server
		$remote = wp_remote_get( 'https://accessibility-helper.co.il/info.json', array(
			'timeout' => 120,
			'headers' => array(
				'Accept' => 'application/json'
			) )
		);

		if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
			set_transient( 'wah_pro_upgrade_wp-accessibility-helper-pro', $remote, 43200 ); // 12 hours cache
		}

	}

	if( !is_wp_error( $remote ) && $remote && $license_user && $license_key ) {

		$remote = json_decode( $remote['body'] );

		if( $remote && version_compare( WAHPRO_VERSION, $remote->version, '<' ) && version_compare( $remote->requires, get_bloginfo('version'), '<' ) ) {
			$res                               = new stdClass();
			$res->slug                         = 'wp-accessibility-helper-pro';
			$res->plugin                       = 'wp-accessibility-helper-pro/wp-accessibility-helper-pro.php';
			$res->new_version                  = $remote->version;
			$res->tested                       = $remote->tested;
			$res->package                      = $remote->download_url;
			$res->compatibility                = new stdClass();
       		$transient->response[$res->plugin] = $res;
       		//$transient->checked[$res->plugin] = $remote->version;
       	}

	}

    return $transient;

}

add_action( 'upgrader_process_complete', 'wah_pro_after_update', 10, 2 );
function wah_pro_after_update( $upgrader_object, $options ) {
	if ( $options['action'] == 'update' && $options['type'] === 'plugin' )  {
		delete_transient( 'wah_pro_upgrade_wp-accessibility-helper-pro' );
	}
}

function wah_admin() {
    include("admin/pages/wah-admin.php");
}
function wah_attachments() {
    include("admin/pages/wah-attachments.php");
}
function wah_landmark() {
    include("admin/pages/wah-landmark.php");
}
function wah_dom_scanner() {
    include("admin/pages/wah-dom-scanner.php");
}
function wah_sidebar_controls() {
    include("admin/pages/wah-sidebar-controls.php");
}
function wah_import_export() {
    include("admin/pages/wah-import_export.php");
}
function wah_gdpr() {
    include("admin/pages/wah-gdpr.php");
}

function wp_accessibility_helper_admin_actions() {
    add_menu_page(
        __( 'Accessibility', 'wp-accessibility-helper' ),
        'Accessibility PRO','manage_options','wp_accessibility','wah_admin','dashicons-universal-access-alt'
    );
    add_submenu_page(
      	'wp_accessibility',
        __( 'Widgets Order', 'wp-accessibility-helper' ),'Widgets Order','manage_options','wp_accessibility_sidebar_controls','wah_sidebar_controls'
  	);
    add_submenu_page(
      	'wp_accessibility',
        __( 'DOM Scanner', 'wp-accessibility-helper' ),'DOM Scanner','manage_options','wp_accessibility_dom_scanner','wah_dom_scanner'
  	);
    add_submenu_page(
    	'wp_accessibility',
        __( 'Attachments Control', 'wp-accessibility-helper' ),'Attachments Control','manage_options','wp_accessibility_image','wah_attachments'
	);
    add_submenu_page(
    	'wp_accessibility',
        __( 'WAH GDPR', 'wp-accessibility-helper' ), 'WAH GDPR','manage_options','wp_accessibility_gdpr','wah_gdpr'
	);
    add_submenu_page(
    	'wp_accessibility',
        __( 'Landmark & CSS', 'wp-accessibility-helper' ),'Landmark & CSS','manage_options','wp_accessibility_landmark','wah_landmark'
	);
    add_submenu_page(
    	'wp_accessibility',
        __( 'Import/Export', 'wp-accessibility-helper' ),'Import/Export','manage_options','wah_import_export','wah_import_export'
	);
}
add_action('admin_menu', 'wp_accessibility_helper_admin_actions');
/*********************************************
*   Load WP Accessibility Helper TextDomain
**********************************************/
function wp_access_helper_load_plugin_textdomain() {
	$domain = 'wp-accessibility-helper';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	if ( $loaded = load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' ) ) {
		return $loaded;
	} else {
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
}
add_action( 'init', 'wp_access_helper_load_plugin_textdomain' );
/*********************************************
*   Register front styles & scripts
**********************************************/
add_action( 'wp_enqueue_scripts', 'wp_access_helper_scripts' );
function wp_access_helper_scripts() {
    wp_register_style( 'wpah-front-styles',  plugin_dir_url( __FILE__ ) . 'assets/css/wp-accessibility-helper.min.css' );
    wp_enqueue_style( 'wpah-front-styles' );

    // Register the script
    wp_register_script( 'wah-nicescroll', plugin_dir_url( __FILE__ ) . 'assets/js/nice.scroll.js', array('jquery'), NULL, true ); wp_enqueue_script( 'wah-nicescroll' );
    wp_register_script( 'wp-accessibility-helper', plugin_dir_url( __FILE__ ) . 'assets/js/wp-accessibility-helper.min.js', array('jquery'), NULL, true );

    $wahpro_settings = array(
    	'plugin_version'        => WAHPRO_VERSION,
        'plugin_author'         => 'Alex Volkov',
        'plugin_website'        => 'https://accessibility-helper.co.il/',
        'ajax_url'              => admin_url( 'admin-ajax.php' ),
        'wahpro_log'            => wah_get_param( 'wah_enable_log' ),
        'wahpro_cookies'        => wah_get_param( 'wah_cookies' ) ? wah_get_param( 'wah_cookies' ) : 14,
        'wahpro_gdpr_cookies'   => wah_get_param( 'wah_gdpr_cookies' ) ? wah_get_param( 'wah_gdpr_cookies' ) : 30,
        'wah_enable_web_speech' => wah_get_param( 'wah_enable_web_speech' ),
        'wah_enable_adhd'       => wah_get_param( 'wah_enable_adhd' )
    );
    wp_localize_script( 'wp-accessibility-helper', 'wahpro_settings', $wahpro_settings );
    wp_enqueue_script( 'wp-accessibility-helper' );
}
/*********************************************
*   Register admin styles
**********************************************/
add_action('admin_head', 'admin_styles');
function admin_styles() {
    wp_register_style( 'wp-accessibility-helper', plugin_dir_url( __FILE__ ).'admin/css/wp-accessibility-helper.css' );
    wp_enqueue_style( 'wp-accessibility-helper' );
    if( is_rtl() ){
        wp_register_style( 'wp-accessibility-helper-rtl', plugin_dir_url( __FILE__ ).'admin/css/wp-accessibility-helper_rtl.css' );
        wp_enqueue_style( 'wp-accessibility-helper-rtl' );
    }
}
/*********************************************
*   Register admin scripts
**********************************************/
function plugin_admin_scripts() {
    wp_enqueue_script( 'jqui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js' );
    wp_enqueue_media();
    wp_enqueue_script( 'admin_colors', plugin_dir_url( __FILE__ ) . 'admin/js/jscolor.min.js' );
    wp_enqueue_script( 'admin_scripts', plugin_dir_url( __FILE__ ) . 'admin/js/admin_scripts.js', array('jquery'), NULL, true );
}
add_action('admin_enqueue_scripts', 'plugin_admin_scripts');
/*********************************************
*   Create WP-Accessibility-Helper HTML Elements
**********************************************/
add_action('wp_footer','wp_access_helper_create_container', 10);
function wp_access_helper_create_container() {

    include_once dirname( __FILE__ ) . '/wp-accessibility-helper-view.php';
    include_once dirname( __FILE__ ) . '/inc/wah-skip-links.php';

    $wah_set_layout_setup      = wah_get_param('wah_set_layout_setup');
    $wah_gdpr_enable           = wah_get_param( 'wah_gdpr_enable' );
    $wah_report_problem_enable = wah_get_param( 'wah_report_problem_enable' );

    if( $wah_set_layout_setup ){
        include_once dirname( __FILE__ ) . '/inc/wah-set-layout-popup.php';
    }
    if( $wah_gdpr_enable ){
        include_once dirname( __FILE__ ) . '/inc/wah-gdpr-popup.php';
    }
    if( $wah_report_problem_enable ){
        include_once dirname( __FILE__ ) . '/inc/popup/wah-report-problem.php';
    }
}

if( is_admin() ) {
    include_once( dirname(__FILE__)  . '/admin/functions.php');
    include_once( dirname(__FILE__)  . '/admin/ajax-functions.php');
}
/*********************************************
*   Register WAH Skiplinks
**********************************************/
add_action( 'after_setup_theme', 'register_wah_skiplinks_menu' );
function register_wah_skiplinks_menu() {
    register_nav_menu( 'wah_skiplinks', __( 'WAH Skiplinks menu', 'wp-accessibility-helper' ) );
}
