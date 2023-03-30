<?php
// Elementor integration
include_once( ABSPATH . 'wp-admin/includes/plugin.php');
if(is_plugin_active('elementor/elementor.php') ) {
	if(!defined('ABSPATH')) exit;
	
	
	
	class mg_on_elementor {
		private $widgets_basepath = '';
	
		
		public function __construct() {
			
			/*** enqueue ***/
			$this->widgets_basepath = MG_DIR .'/builders_integration/elementor_elements';
			
			add_action('elementor/widgets/widgets_registered', array( $this, 'register_mg_grid'));
		}
			 
		public function register_mg_grid() {
			include_once($this->widgets_basepath .'/grid.php');
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new mg_grid_on_elementor() );
		}
	}
	add_action('wp_loaded', function() {
		new mg_on_elementor();
	}, 1);
	
	
	
	// style needed for LCweb icons
	add_action('elementor/editor/after_enqueue_styles', function() {
		wp_enqueue_style('lcweb-elementor-icon', MG_URL .'/builders_integration/elementor_elements/lcweb_icon.css');	
	});




////
} // end elementor's existence check