<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://support.catsplugins.com
 * @since      1.0.0
 *
 * @package    Acf_Form_Builder
 * @subpackage Acf_Form_Builder/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Acf_Form_Builder
 * @subpackage Acf_Form_Builder/includes
 * @author     Cat's Plugins <admin@catsplugins.com>
 */
class Acf_Form_Builder {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Acf_Form_Builder_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'acf-form-builder';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Acf_Form_Builder_Loader. Orchestrates the hooks of the plugin.
	 * - Acf_Form_Builder_i18n. Defines internationalization functionality.
	 * - Acf_Form_Builder_Admin. Defines all hooks for the admin area.
	 * - Acf_Form_Builder_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-acf-form-builder-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-acf-form-builder-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-acf-form-builder-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-acf-form-builder-public.php';

		$this->loader = new Acf_Form_Builder_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Acf_Form_Builder_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Acf_Form_Builder_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Acf_Form_Builder_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('init', $plugin_admin, 'register_post_types');
        $this->loader->add_action('acf/render_field_settings', $plugin_admin, 'add_field_display_label_pro');
        $this->loader->add_action('acf/create_field_options', $plugin_admin, 'add_field_display_label');

		$this->loader->add_action('show_user_profile', $plugin_admin, 'acf_user_profile_fields');
		$this->loader->add_action('edit_user_profile', $plugin_admin, 'acf_user_profile_fields');
		$this->loader->add_action('show_user_profile', $plugin_admin, 'show_package_in_user_profile');		
		$this->loader->add_action('admin_init', $plugin_admin, 'check_user_meta');
		$this->loader->add_filter('cs_metabox_options', $plugin_admin, 'acf_add_form_builder_metabox');
		$this->loader->add_filter('cs_metabox_options', $plugin_admin, 'acf_add_package_metabox');
		$this->loader->add_filter('cs_metabox_options', $plugin_admin, 'acf_add_transaction_metabox');
		$this->loader->add_filter('cs_metabox_options', $plugin_admin, 'acf_add_claim_listing_metabox');
		$this->loader->add_filter('cs_shortcode_options', $plugin_admin, 'acf_add_shortcode_metabox');
		$this->loader->add_filter('cs_framework_settings', $plugin_admin, 'acf_override_framework_settings');
		$this->loader->add_filter('cs_framework_options', $plugin_admin, 'acf_add_theme_options_metabox');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Acf_Form_Builder_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_action('template_redirect', $plugin_public, 'add_acf_head');
		$this->loader->add_action('init', $plugin_public, 'acf_fb_register_shortcode');
		$this->loader->add_action('init', $plugin_public, 'process_init_request');
		$this->loader->add_action('init', $plugin_public, 'process_free_package');		
		$this->loader->add_action('init', $plugin_public, 'process_transaction_status');		
		$this->loader->add_action('init', $plugin_public, 'process_ipn_return');		
		$this->loader->add_action('init', $plugin_public, 'process_stripe_ipn_return');		
		$this->loader->add_action('init', $plugin_public, 'check_user_meta');		
		$this->loader->add_action('init', $plugin_public, 'setup_wp_uploader');
		$this->loader->add_action('template_redirect', $plugin_public, 'acf_woo_gateway_submit');
		$this->loader->add_action('acf/save_post', $plugin_public, 'acf_before_save_post', 1);
		$this->loader->add_action('acf/save_post', $plugin_public, 'acf_after_save_post', 20);
		$this->loader->add_action('admin_bar_menu', $plugin_public, 'acf_fb_edit_post_link', 999);
		$this->loader->add_action('wp_ajax_nopriv_acf_fb_ajax_delete_post', $plugin_public, 'acf_fb_ajax_delete_post');
		$this->loader->add_action('wp_ajax_acf_fb_ajax_delete_post', $plugin_public, 'acf_fb_ajax_delete_post');
		$this->loader->add_filter('acf/load_field', $plugin_public, 'acf_render_field', 20);
		$this->loader->add_filter('the_content', $plugin_public, 'do_shortcode_content', 20);		
		$this->loader->add_filter('the_content', $plugin_public, 'add_claim_listing_button', 30);
        $this->loader->add_filter('acf/pre_save_post', $plugin_public, 'acf_pre_save_post', 0, 1);

        //Woocommerce gateway
        $this->loader->add_filter('woocommerce_payment_complete_order_status', $plugin_public, 'acf_woo_gateway_payment_completed', 10, 2);
        $this->loader->add_action('woocommerce_before_checkout_form', $plugin_public, 'acf_woo_gateway_update_payment_menthod_in_checkout_page', 10);
        $this->loader->add_action('woocommerce_after_checkout_validation', $plugin_public, 'acf_woo_gateway_update_payment_menthod_in_validate_checkout', 10, 2);
        $this->loader->add_action('woocommerce_order_status_completed', $plugin_public, 'acf_woo_gateway_order_completed', 10, 1);
        $this->loader->add_action('woocommerce_order_status_cancelled', $plugin_public, 'acf_woo_gateway_order_draft', 10, 1);
        $this->loader->add_action('woocommerce_order_status_failed', $plugin_public, 'acf_woo_gateway_order_draft', 10, 1);
        $this->loader->add_action('woocommerce_order_status_refunded', $plugin_public, 'acf_woo_gateway_order_draft', 10, 1);
        $this->loader->add_action('woocommerce_order_status_pending', $plugin_public, 'acf_woo_gateway_order_pending', 10, 1);
        $this->loader->add_action('woocommerce_order_status_on-hold', $plugin_public, 'acf_woo_gateway_order_pending', 10, 1);
        $this->loader->add_action('woocommerce_order_status_processing', $plugin_public, 'acf_woo_gateway_order_pending', 10, 1);
        $this->loader->add_action('woocommerce_thankyou', $plugin_public, 'acf_woo_gateway_redirect_page', 10, 1);
    }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Acf_Form_Builder_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
