<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://support.catsplugins.com
 * @since      1.0.0
 *
 * @package    Acf_Form_Builder
 * @subpackage Acf_Form_Builder/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Acf_Form_Builder
 * @subpackage Acf_Form_Builder/admin
 * @author     Cat's Plugins <admin@catsplugins.com>
 */
class Acf_Form_Builder_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since copy.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    private static function get_all_taxonomy()
    {
        $taxs  = get_taxonomies([], OBJECT);

        $output = [];

        foreach ($taxs as $key => $tax) {
            $output[$key] = $tax->label;
        }

        return $output;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Acf_Form_Builder_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Acf_Form_Builder_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/acf-form-builder-admin.css', array('acf-datepicker'), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Acf_Form_Builder_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Acf_Form_Builder_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/acf-form-builder-admin.js', array('jquery', 'jquery-ui-datepicker'), $this->version, false);
    }

    public function get_role_names()
    {
        global $wp_roles;

        if (!isset($wp_roles))
            $wp_roles = new WP_Roles();

        return $wp_roles->get_names();
    }

    // NON-PRO ONLY: add a field to the admin interface, that decides whether this field's label gets displayed on the frontend or not
    public function add_field_display_label($field)
    {
        ?>
        <tr class="field_display_label">
            <td class="label"><label><?php _e('Form field type', ACF_FORM_BUILDER_TEXTDOMAIN); ?></label></td>
            <td>
                <?php
                do_action('acf/create_field', array(
                    'type' => 'select',
                    'name' => 'form_field_type',
                    'prefix' => $field['prefix'],
                    'value' => isset($field['form_field_type']) ? $field['form_field_type'] : 'custom',
                    'choices' => array(
                        'Default' => array(
                            'custom' => 'Custom Field',
                        ),
                        'General' => array(
                            'meta_key' => 'Meta Field',
                        ),
                        'Post Fields' => array(
                            'title' => 'Post title',
                            'content' => 'Post content',
                            'category' => 'Category',
                            'custom_taxonomy' => 'Custom Taxonomy',
                            'the_excerpt' => 'The Excerpt',
                            'tag' => 'Post Tag',
                            'feature-image' => 'Featured Image',
                            'parent_page' => 'Parent Page',
                        ),
                        'User Fields' => array(
                            'username' => 'User Name',
                            'usermail' => 'User Mail',
                            'userpassword' => 'User Password',
                            'userfirstname' => 'User First Name',
                            'userlastname' => 'User Last Name',
                            'usernicename' => 'User Nice Name',
                            'usernickname' => 'User Nick Name',
                            'userdescription' => 'User description',
                            'userurl' => 'User Website',
                        ),
                        'Subscription' => array(
                            'subscribe_email' => 'Subscribe Email',
                            'subscribe_first_name' => 'Subscribe First Name',
                            'subscribe_last_name' => 'Subscribe Last Name',
                            'subscribe_address' => 'Subscribe Address',
                        ),
                        'Contact Form' => array(
                            'to-send-email' => 'Contact Email',
                        ),
                    ),
                    'layout' => 'horizontal',
                ));
                ?>
            </td>
        </tr>

        <tr class="field_display_label hide">
            <td class="label"><label><?php _e('Meta key', ACF_FORM_BUILDER_TEXTDOMAIN); ?></label></td>
            <td>
                <?php
                do_action('acf/create_field', array(
                    'type' => 'select',
                    'instructions' => 'Only work with form field type is "Meta key"',
                    'name' => 'input_meta_key',
                    'prefix' => $field['prefix'],
                    'value' => isset($field['input_meta_key']) ? $field['input_meta_key'] : '',
                    'layout' => 'horizontal'
                ));
                ?>
            </td>
        </tr>

        <tr class="field_display_label hide">
            <td class="label"><label><?php _e('Taxonomy', ACF_FORM_BUILDER_TEXTDOMAIN); ?></label></td>
            <td>
                <?php
                do_action('acf/create_field', array(
                    'type' => 'text',
                    'instructions' => 'Only work with form field type is "Custom taxonomy"',
                    'name' => 'custom_taxonomy',
                    'prefix' => $field['prefix'],
                    'value' => isset($field['custom_taxonomy']) ? $field['custom_taxonomy'] : '',
                    'layout' => 'horizontal',
                    "choices" => Acf_Form_Builder_Admin::get_all_taxonomy()
                ));
                ?>
            </td>
        </tr>
        <?php
    }

    // PRO ONLY: add a field to the admin interface, that decides whether this field's label gets displayed on the frontend or not
    public function add_field_display_label_pro($field)
    {
        // required
        ?>
        <tr class="acf-field">
            <td class="acf-label"><label><?php _e('Form field type', ACF_FORM_BUILDER_TEXTDOMAIN); ?></label></td>
            <td>
                <?php
                acf_render_field_wrap(array(
                    'label' => __('Form field type', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'type' => 'select',
                    'name' => 'form_field_type',
                    'prefix' => $field['prefix'],
                    'value' => isset($field['form_field_type']) ? $field['form_field_type'] : 'custom',
                    'choices' => array(
                        'Default' => array(
                            'custom' => 'Custom Field',
                        ),
                        'General' => array(
                            'meta_key' => 'Meta Field',
                        ),
                        'Post Fields' => array(
                            'title' => 'Post title',
                            'content' => 'Post content',
                            'category' => 'Category',
                            'custom_taxonomy' => 'Custom Taxonomy',
                            'the_excerpt' => 'The Excerpt',
                            'tag' => 'Post Tag',
                            'feature-image' => 'Featured Image',
                            'parent_page' => 'Parent Page',
                        ),
                        'User Fields' => array(
                            'username' => 'User Name',
                            'usermail' => 'User Mail',
                            'userpassword' => 'User Password',
                            'userfirstname' => 'User First Name',
                            'userlastname' => 'User Last Name',
                            'usernicename' => 'User Nice Name',
                            'usernickname' => 'User Nick Name',
                            'userdescription' => 'User description',
                            'userurl' => 'User Website',
                        ),
                        'Subscription' => array(
                            'subscribe_email' => 'Subscribe Email',
                            'subscribe_first_name' => 'Subscribe First Name',
                            'subscribe_last_name' => 'Subscribe Last Name',
                            'subscribe_address' => 'Subscribe Address',
                        ),
                        'Contact Form' => array(
                            'to-send-email' => 'Contact Email',
                        ),
                    ),
                    'layout' => 'horizontal',
                ));
                ?>
            </td>
        </tr>
        <tr class="acf-field hide">
            <td class="acf-label"><label><?php _e('Meta key', ACF_FORM_BUILDER_TEXTDOMAIN); ?></label></td>
            <td>
                <?php
                acf_render_field_wrap(array(
                    'type' => 'text',
                    'instructions' => 'Only work with form field type is "Meta key"',
                    'name' => 'input_meta_key',
                    'prefix' => $field['prefix'],
                    'value' => isset($field['input_meta_key']) ? $field['input_meta_key'] : '',
                    'layout' => 'horizontal',
                ));
                ?>
            </td>
        </tr>


        <tr class="acf-field hide">
            <td class="acf-label"><label><?php _e('Taxonomy', ACF_FORM_BUILDER_TEXTDOMAIN); ?></label></td>
            <td>
                <?php
                acf_render_field_wrap(array(
                    'type' => 'select',
                    'instructions' => 'Only work with form field type is "Custom taxonomy"',
                    'name' => 'custom_taxonomy',
                    'prefix' => $field['prefix'],
                    'value' => isset($field['custom_taxonomy']) ? $field['custom_taxonomy'] : '',
                    'layout' => 'horizontal',
                    "choices" => Acf_Form_Builder_Admin::get_all_taxonomy()
                ));
                ?>
            </td>
        </tr>
        <?php
    }

    /**
     * Register post types
     *
     */
    public function register_post_types()
    {
        $labels = array(
            'name' => _x('Packages', 'Post Type General Name', 'acf-form-builder'),
            'singular_name' => _x('Package', 'Post Type Singular Name', 'acf-form-builder'),
            'menu_name' => __('Packages', 'acf-form-builder'),
            'name_admin_bar' => __('Packages', 'acf-form-builder'),
            'archives' => __('Item Archives', 'acf-form-builder'),
            'attributes' => __('Item Attributes', 'acf-form-builder'),
            'parent_item_colon' => __('Parent Item:', 'acf-form-builder'),
            'all_items' => __('All Items', 'acf-form-builder'),
            'add_new_item' => __('Add New Item', 'acf-form-builder'),
            'add_new' => __('Add New', 'acf-form-builder'),
            'new_item' => __('New Item', 'acf-form-builder'),
            'edit_item' => __('Edit Item', 'acf-form-builder'),
            'update_item' => __('Update Item', 'acf-form-builder'),
            'view_item' => __('View Item', 'acf-form-builder'),
            'view_items' => __('View Items', 'acf-form-builder'),
            'search_items' => __('Search Item', 'acf-form-builder'),
            'not_found' => __('Not found', 'acf-form-builder'),
            'not_found_in_trash' => __('Not found in Trash', 'acf-form-builder'),
            'featured_image' => __('Featured Image', 'acf-form-builder'),
            'set_featured_image' => __('Set featured image', 'acf-form-builder'),
            'remove_featured_image' => __('Remove featured image', 'acf-form-builder'),
            'use_featured_image' => __('Use as featured image', 'acf-form-builder'),
            'insert_into_item' => __('Insert into item', 'acf-form-builder'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'acf-form-builder'),
            'items_list' => __('Items list', 'acf-form-builder'),
            'items_list_navigation' => __('Items list navigation', 'acf-form-builder'),
            'filter_items_list' => __('Filter items list', 'acf-form-builder'),
        );
        $args = array(
            'label' => __('Package', 'acf-form-builder'),
            'description' => __('Form Packages', 'acf-form-builder'),
            'labels' => $labels,
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-category',
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'can_export' => false,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => false,
            'capability_type' => 'page',
        );
        register_post_type('cats_packages', $args);

        $labels = array(
            'name' => _x('Transactions', 'Post Type General Name', 'acf-form-builder'),
            'singular_name' => _x('Transaction', 'Post Type Singular Name', 'acf-form-builder'),
            'menu_name' => __('Transactions', 'acf-form-builder'),
            'name_admin_bar' => __('Transactions', 'acf-form-builder'),
            'archives' => __('Item Archives', 'acf-form-builder'),
            'attributes' => __('Item Attributes', 'acf-form-builder'),
            'parent_item_colon' => __('Parent Item:', 'acf-form-builder'),
            'all_items' => __('All Items', 'acf-form-builder'),
            'add_new_item' => __('Add New Item', 'acf-form-builder'),
            'add_new' => __('Add New', 'acf-form-builder'),
            'new_item' => __('New Item', 'acf-form-builder'),
            'edit_item' => __('Edit Item', 'acf-form-builder'),
            'update_item' => __('Update Item', 'acf-form-builder'),
            'view_item' => __('View Item', 'acf-form-builder'),
            'view_items' => __('View Items', 'acf-form-builder'),
            'search_items' => __('Search Item', 'acf-form-builder'),
            'not_found' => __('Not found', 'acf-form-builder'),
            'not_found_in_trash' => __('Not found in Trash', 'acf-form-builder'),
            'featured_image' => __('Featured Image', 'acf-form-builder'),
            'set_featured_image' => __('Set featured image', 'acf-form-builder'),
            'remove_featured_image' => __('Remove featured image', 'acf-form-builder'),
            'use_featured_image' => __('Use as featured image', 'acf-form-builder'),
            'insert_into_item' => __('Insert into item', 'acf-form-builder'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'acf-form-builder'),
            'items_list' => __('Items list', 'acf-form-builder'),
            'items_list_navigation' => __('Items list navigation', 'acf-form-builder'),
            'filter_items_list' => __('Filter items list', 'acf-form-builder'),
        );

        $capabilities = array(
            'edit_post' => 'edit_private_post',
            'read_post' => 'read_private_post',
            'delete_post' => 'delete_private_post',
            'read_private_posts' => 'read_private_posts',
        );

        $args = array(
            'label' => __('Transaction', 'acf-form-builder'),
            'labels' => $labels,
            'supports' => array('title'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-clipboard',
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => false,
            //'capabilities' => $capabilities,      
        );
        register_post_type('cats_transactions', $args);
    }

    /**
     * override framework settings
     */
    public function acf_override_framework_settings($settings)
    {
        $settings['menu_title'] = __('ACF FB', ACF_FORM_BUILDER_TEXTDOMAIN);
        $settings['framework_title'] = __('ACF FB', ACF_FORM_BUILDER_TEXTDOMAIN);
        return $settings;
    }

    public function acf_add_claim_listing_metabox($options)
    {
        $acf_groups = [];
        $args = array(
            'post_type' => 'acf-field-group',
            'orderby' => 'name',
            'order' => 'ASC',
            'posts_per_page' => -1
        );
        $groups = new WP_Query($args);

        foreach ($groups->posts as $key => $group) {
            $group_id = $group->ID;
            $form_settings = get_post_meta($group_id, '_acf_form_builder_metabox', true);
            if (isset($form_settings['is_form']) && $form_settings['is_form'] && isset($form_settings['form_type']) && $form_settings['form_type'] == 'frontend_post_submission') {
                $acf_groups[$group_id] = $group->post_title;
            }
        }

        $claim_listing_support_post_types = cs_get_option('claim_listing_support_post_types');
        if (empty($claim_listing_support_post_types) || !count($claim_listing_support_post_types)) {
            $claim_listing_support_post_types = array('post');
        }

        $options[] = array(
            'id' => '_acf_claim_listing_metabox',
            'title' => __('Claim Listing', ACF_FORM_BUILDER_TEXTDOMAIN),
            'post_type' => $claim_listing_support_post_types,
            'context' => 'side',
            'priority' => 'high',
            'sections' => array(
                array(
                    'name' => 'claim_listing',
                    'fields' => array(
                        // a field
                        array(
                            'id' => 'enable_claim_listing',
                            'type' => 'switcher',
                            'title' => __('Enable Claim Listing', ACF_FORM_BUILDER_TEXTDOMAIN),
                        ),
                        array(
                            'id' => 'claim_listing_acf_form_id',
                            'type' => 'select',
                            'title' => __('ACForm group', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => $acf_groups,
                            'class' => 'chosen',
                            'default_option' => __('Select a group', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('enable_claim_listing', '==', 'true')
                        ),
                    ),
                ),
            ),
        );
        return $options;
    }

    /**
     * add theme options
     */
    public function acf_add_theme_options_metabox($options)
    {

        $currency_options_non_group_id = array(
            'USD' => __('USD', ACF_FORM_BUILDER_TEXTDOMAIN),
            'EUR' => __('EUR', ACF_FORM_BUILDER_TEXTDOMAIN),
        );
        $currency_options_non_group_id = apply_filters('acf_currency_options', $currency_options_non_group_id);

        $options = array();

        $options[] = array(
            'name' => 'theme-options',
            'title' => 'Theme options',
            'icon' => 'fa fa-star',
            'fields' => array(
                array(
                    'id' => 'claim_listing_support_post_types',
                    'type' => 'select',
                    'title' => __('Claim Listing Feature', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'desc' => __('Select post types what supported to claim listing feature', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'options' => get_post_types(),
                    'class' => 'chosen',
                    'default_option' => __('Select post types', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'attributes' => array(
                        'multiple' => 'multiple',
                        'style' => 'width: 100%'
                    ),
                ),
                array(
                    'id' => 'checkout_page',
                    'type' => 'select',
                    'title' => __('Checkout Page', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'desc' => __('Select a checkout page', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'options' => 'page',
                    'query_args' => array(
                        'orderby' => 'name',
                        'order' => 'ASC',
                    ),
                    'class' => 'chosen',
                    'default_option' => __('Select a page', ACF_FORM_BUILDER_TEXTDOMAIN),
                ),
                array(
                    'id' => 'currency_options_non_group_id',
                    'type' => 'select',
                    'title' => __('Select currency', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'class' => 'chosen',
                    'options' => $currency_options_non_group_id,
                    'desc' => 'Select checkout currency'
                ),
                array(
                    'id' => 'failed_page',
                    'type' => 'select',
                    'title' => __('Checkout Failed Page', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'desc' => __('Select a page', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'options' => 'page',
                    'query_args' => array(
                        'orderby' => 'name',
                        'order' => 'ASC',
                    ),
                    'class' => 'chosen',
                    'default_option' => __('Select a page', ACF_FORM_BUILDER_TEXTDOMAIN)
                ),
                array(
                    'id' => 'redirect_after_checkout',
                    'type' => 'select',
                    'title' => __('Page', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'desc' => __('Select redirection page after checkout', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'options' => 'page',
                    'query_args' => array(
                        'orderby' => 'name',
                        'order' => 'ASC',
                    ),
                    'class' => 'chosen',
                    'default_option' => __('Select a page', ACF_FORM_BUILDER_TEXTDOMAIN),
                ),
                array(
                    'id' => 'enable_paypal_gateway',
                    'type' => 'switcher',
                    'title' => __('Enable Paypal', ACF_FORM_BUILDER_TEXTDOMAIN),
                ),
                array(
                    'id' => 'paypal_email',
                    'type' => 'text',
                    'title' => __('Paypal Email', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'dependency' => array('enable_paypal_gateway', '==', 'true')
                ),
                array(
                    'id' => 'paypal_sandbox_key',
                    'type' => 'text',
                    'title' => __('Paypal Sandbox Key', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'dependency' => array('enable_paypal_gateway', '==', 'true')
                ),
                array(
                    'id' => 'paypal_production_key',
                    'type' => 'text',
                    'title' => __('Paypal Production Key', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'dependency' => array('enable_paypal_gateway', '==', 'true')
                ),
                array(
                    'id' => 'enable_user_upload',
                    'type' => 'switcher',
                    'title' => __('Enable User Uploader', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'value' => true
                ),
                array(
                    'id' => 'paypal_mode',
                    'type' => 'select',
                    'title' => __('Paypal Mode', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'options' => array(
                        'sandbox' => __('Sandbox', ACF_FORM_BUILDER_TEXTDOMAIN),
                        'production' => __('Production', ACF_FORM_BUILDER_TEXTDOMAIN),
                    ),
                    'default' => 'sandbox',
                    'class' => 'chosen',
                    'dependency' => array('enable_paypal_gateway', '==', 'true')
                ),
                array(
                    'id' => 'enable_stripe_gateway',
                    'type' => 'switcher',
                    'title' => __('Enable Stripe', ACF_FORM_BUILDER_TEXTDOMAIN),
                ),
                array(
                    'id' => 'stripe_sandbox_key',
                    'type' => 'text',
                    'title' => __('Stripe Sandbox Key', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'dependency' => array('enable_stripe_gateway', '==', 'true')
                ),
                array(
                    'id' => 'stripe_production_key',
                    'type' => 'text',
                    'title' => __('Stripe Production Key', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'dependency' => array('enable_stripe_gateway', '==', 'true')
                ),
                array(
                    'id' => 'stripe_sandbox_secret_key',
                    'type' => 'text',
                    'title' => __('Stripe Sandbox Secret Key', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'dependency' => array('enable_stripe_gateway', '==', 'true')
                ),
                array(
                    'id' => 'stripe_production_secret_key',
                    'type' => 'text',
                    'title' => __('Stripe Production Secret Key', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'dependency' => array('enable_stripe_gateway', '==', 'true')
                ),
                array(
                    'id' => 'stripe_mode',
                    'type' => 'select',
                    'title' => __('Stripe Mode', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'options' => array(
                        'sandbox' => __('Sandbox', ACF_FORM_BUILDER_TEXTDOMAIN),
                        'production' => __('Production', ACF_FORM_BUILDER_TEXTDOMAIN),
                    ),
                    'default' => 'sandbox',
                    'class' => 'chosen',
                    'dependency' => array('enable_stripe_gateway', '==', 'true')
                ),
                array(
                    'id' => 'pay_per_post',
                    'type' => 'number',
                    'title' => __('Post Price', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'default' => 0,
                    'dependency' => array('payment_type', '==', 'pay_per_post'),
                ),
                array(
                    'id' => 'subscription',
                    'type' => 'select',
                    'title' => __('Package', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'desc' => __('Select available package for this form', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'options' => 'posts',
                    'query_args' => array(
                        'post_type' => 'cats_packages',
                        'posts_per_page' => -1,
                    ),
                    'attributes' => array(
                        'multiple' => 'multiple',
                        'style' => 'width: 100%'
                    ),
                    'class' => 'chosen',
                    'dependency' => array('payment_type', '==', 'subscription'),
                ),
                array(
                    'id' => 'enable_woo_gateway',
                    'type' => 'switcher',
                    'title' => __('Enable Woocommerce Gateway', ACF_FORM_BUILDER_TEXTDOMAIN),
                )
            ),
        );
        return $options;
    }

    /**
     * add shortcode metabox
     */
    public function acf_add_shortcode_metabox($options)
    {

        $user_roles = $this->get_role_names();

        $options = array();

        $options[] = array(
            'title' => __("ACF form builder shortcode", ACF_FORM_BUILDER_TEXTDOMAIN),
            'shortcodes' => array(
                /*array(
                  'name' => 'cats_claim_listing_post',
                  'title' => __("Claim Listing Post", ACF_FORM_BUILDER_TEXTDOMAIN),
                  'fields' => array(
                    array(
                      'id' => 'post_id',
                      'type' => 'number',
                      'title' => __("Post id", ACF_FORM_BUILDER_TEXTDOMAIN),
                    ),
                  ),
                ),*/
                array(
                    'name' => 'cats_user_regist',
                    'title' => __("User Registration", ACF_FORM_BUILDER_TEXTDOMAIN),
                    'fields' => array(
                        array(
                            'id' => 'group_id',
                            'type' => 'number',
                            'title' => __("ACF group id", ACF_FORM_BUILDER_TEXTDOMAIN),
                        ),
                    ),
                ),
                array(
                    'name' => 'cats_user_edit',
                    'title' => __("Edit User Profile", ACF_FORM_BUILDER_TEXTDOMAIN),
                ),
                array(
                    'name' => 'cats_profile',
                    'title' => __("User Profile", ACF_FORM_BUILDER_TEXTDOMAIN),
                ),
                array(
                    'name' => 'cats_user_packages',
                    'title' => __("Current User's Packages", ACF_FORM_BUILDER_TEXTDOMAIN),
                ),
                array(
                    'name' => 'cats_user_transaction',
                    'title' => __("User's Payment History", ACF_FORM_BUILDER_TEXTDOMAIN),
                ),
                array(
                    'name' => 'cats_form',
                    'title' => __("Frontend Submit Post", ACF_FORM_BUILDER_TEXTDOMAIN),
                    'fields' => array(
                        array(
                            'id' => 'group_id',
                            'type' => 'number',
                            'title' => __("ACF form id", ACF_FORM_BUILDER_TEXTDOMAIN),
                        ),
                    ),
                ),
                array(
                    'name' => 'cats_edit_form',
                    'title' => __("Edit Post Form", ACF_FORM_BUILDER_TEXTDOMAIN),
                    'fields' => array(
                        array(
                            'id' => 'group_id',
                            'type' => 'number',
                            'title' => __("ACF form id", ACF_FORM_BUILDER_TEXTDOMAIN),
                        ),
                        array(
                            'id' => 'post_id',
                            'type' => 'number',
                            'title' => __("Post id (optional)", ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __("Add post id for creating edit form", ACF_FORM_BUILDER_TEXTDOMAIN),
                        ),
                    ),
                ),
                array(
                    'name' => 'cats_posts',
                    'title' => __("Show All Posts", ACF_FORM_BUILDER_TEXTDOMAIN),
                    'fields' => array(
                        array(
                            'id' => 'post_type',
                            'type' => 'select',
                            'options' => get_post_types(),
                            'attributes' => array(
                                'multiple' => 'multiple',
                                'style' => 'width: 100%'
                            ),
                            'title' => __("Post type", ACF_FORM_BUILDER_TEXTDOMAIN),
                            'class' => 'chosen',
                        ),
                        array(
                            'id' => 'post_status',
                            'type' => 'select',
                            'options' => get_post_statuses(),
                            'attributes' => array(
                                'multiple' => 'multiple',
                                'style' => 'width: 100%'
                            ),
                            'title' => __("Post Status", ACF_FORM_BUILDER_TEXTDOMAIN),
                            'class' => 'chosen',
                        ),
                    ),
                ),
                array(
                    'name' => 'cats_checkout',
                    'title' => __("Checkout Page", ACF_FORM_BUILDER_TEXTDOMAIN),
                ),
                array(
                    'name' => 'cats_package',
                    'title' => __("Packages", ACF_FORM_BUILDER_TEXTDOMAIN),
                    'fields' => array(
                        array(
                            'id' => 'id',
                            'type' => 'select',
                            'title' => __('Package (optional)', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Select available packages', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => 'posts',
                            'query_args' => array(
                                'post_type' => 'cats_packages',
                            ),
                            'attributes' => array(
                                'multiple' => 'multiple',
                                'style' => 'width: 100%'
                            ),
                            'class' => 'chosen',
                        ),
                        array(
                            'id' => 'style',
                            'type' => 'select',
                            'title' => __('Package style', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Select package style', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                'style-1' => __('Style 1', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'style-2' => __('Style 2', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'style-3' => __('Style 3', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'style-4' => __('Style 4', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                            'class' => 'chosen',
                        ),
                        array(
                            'id' => 'column',
                            'type' => 'select',
                            'title' => __('Number of columns', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Select an option', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                '1' => __('1', ACF_FORM_BUILDER_TEXTDOMAIN),
                                '2' => __('2', ACF_FORM_BUILDER_TEXTDOMAIN),
                                '3' => __('3', ACF_FORM_BUILDER_TEXTDOMAIN),
                                '4' => __('4', ACF_FORM_BUILDER_TEXTDOMAIN),
                                '6' => __('6', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                            'class' => 'chosen',
                        ),
                    ),
                ),
            ),
        );

        return $options;
    }

    /**
     * add form metabox
     */
    public function acf_add_form_builder_metabox($options)
    {

        $post_id = isset($_GET['post']) ? $_GET['post'] : 'your-field-group-id-here';

        $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';

        if (is_numeric($post_id)) {
            $post_type = get_post_type($post_id);
        }

        if ((isset($post_type) && $post_type != 'acf-field-group')) {
            if (!isset($_POST['_acf_form_builder_metabox']))
                return;
        }

        $options = array();

        $args = array(
            'orderby' => 'name',
            'fields' => array('id', 'user_nicename')
        );

        $users = get_users($args);

        $authors = array();

        foreach ($users as $key => $author) {
            $authors[$author->id] = $author->user_nicename;
        }

        $wp_roles = wp_roles();

        $roles = $wp_roles->role_names;

        $form_types = array(
            'frontend_post_submission' => __('Frontend Post Submission', ACF_FORM_BUILDER_TEXTDOMAIN),
            'user_registeration' => __('User Registeration', ACF_FORM_BUILDER_TEXTDOMAIN),
            /*'contact_form' => __('Contact Form', ACF_FORM_BUILDER_TEXTDOMAIN),
            'subscribe_form' => __('Subscribe Form', ACF_FORM_BUILDER_TEXTDOMAIN),*/
        );
        $currency_options = array(
            'USD' => __('USD', ACF_FORM_BUILDER_TEXTDOMAIN),
            'EUR' => __('EUR', ACF_FORM_BUILDER_TEXTDOMAIN),
        );

        $currency_options = apply_filters('acf_currency_options', $currency_options);

        $form_types = apply_filters('acf_form_types', $form_types);

        $options[] = array(
            'id' => '_acf_form_builder_metabox',
            'title' => __('Form Builder Settings', ACF_FORM_BUILDER_TEXTDOMAIN),
            'post_type' => array('acf', 'acf-field-group'),
            'context' => 'normal',
            'priority' => 'high',
            'sections' => array(
                array(
                    'title' => 'General settings',
                    'id' => 'general_settings',
                    'name' => 'general_settings',
                    'fields' => array(
                        array(
                            'id' => 'is_form',
                            'type' => 'switcher',
                            'title' => __('Form Display', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Use this field group as a form', ACF_FORM_BUILDER_TEXTDOMAIN),
                        ),
                        array(
                            'id' => 'form_type',
                            'type' => 'select',
                            'title' => __('Form type', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Choose a form type you want to create', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => $form_types,
                            'default' => 'frontend_post_submission',
                            'class' => 'chosen',
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'shortcode',
                            'type' => 'label',
                            'title' => __('Shortcode', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Copy and paste somewhere after you updated the field group', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'content' => '<p>[cats_form group_id="' . $post_id . '"]</p>
                        <p>[cats_edit_form]: Edit post</p>
                        <p>[cats_posts post_type="your-post-type" edit_page_url="link-to-your-edit-page"]: Display table edit post</p>',
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'redirect_after_submit',
                            'type' => 'select',
                            'title' => __('Redirect after submission', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Select where to redirect user to after the submission', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                'url' => __('URL', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'page' => __('Page', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'newly_post_created' => __('Newly post created', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'edit_page' => __('Edit Page', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                            'default' => 'url',
                            'class' => 'chosen',
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'redirect_after_submit_url',
                            'type' => 'text',
                            'title' => __('URL', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Put redirection URL here', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => '/',
                            'dependency' => array('is_form|redirect_after_submit', '==', 'true|url'),
                        ),
                        array(
                            'id' => 'redirect_after_submit_page',
                            'type' => 'select',
                            'title' => __('Page', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Select redirection page', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => 'page',
                            'query_args' => array(
                                'orderby' => 'name',
                                'order' => 'ASC',
                            ),
                            'class' => 'chosen',
                            'default_option' => __('Select a page', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|redirect_after_submit', '==', 'true|page'),
                        ),
                        array(
                            'id' => 'enable_payment',
                            'type' => 'switcher',
                            'title' => __('Enable payment', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'checkout_currency',
                            'type' => 'select',
                            'title' => __('Select currency', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|enable_payment', '==', 'true|true'),
                            'class' => 'chosen',
                            'options' => $currency_options,
                        ),
                        array(
                            'id' => 'checkout_page',
                            'type' => 'select',
                            'title' => __('Checkout Page', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Select a checkout page', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => 'page',
                            'query_args' => array(
                                'orderby' => 'name',
                                'order' => 'ASC',
                            ),
                            'class' => 'chosen',
                            'default_option' => __('Select a page', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|enable_payment', '==', 'true|true'),
                        ),
                        array(
                            'id' => 'failed_page',
                            'type' => 'select',
                            'title' => __('Checkout Failed Page', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Select a page', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => 'page',
                            'query_args' => array(
                                'orderby' => 'name',
                                'order' => 'ASC',
                            ),
                            'class' => 'chosen',
                            'default_option' => __('Select a page', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|enable_payment', '==', 'true|true'),
                        ),
                        array(
                            'id' => 'enable_guest_posting',
                            'type' => 'switcher',
                            'title' => __('Enable guest posting', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|enable_payment', '==', 'true|false'),
                        ),
                        array(
                            'id' => 'allow_users_posting',
                            'type' => 'select',
                            'options' => $authors,
                            'class' => 'chosen',
                            'default_option' => __('Select users', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'title' => __('Allow users posting', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('No user selected is same all users', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'attributes' => array(
                                'multiple' => 'multiple',
                                'style' => 'width: 100%'
                            ),
                            'dependency' => array('is_form|enable_guest_posting', '==', 'true|false'),
                        ),
                        array(
                            'id' => 'allow_roles_posting',
                            'type' => 'select',
                            'options' => $roles,
                            'class' => 'chosen',
                            'default_option' => __('Select roles', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'title' => __('Allow roles posting', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('No role selected is same all roles', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'attributes' => array(
                                'multiple' => 'multiple',
                                'style' => 'width: 100%'
                            ),
                            'dependency' => array('is_form|enable_guest_posting', '==', 'true|false'),
                        ),
                    ),
                ),
                array(
                    'title' => 'Form settings',
                    'id' => 'form_settings',
                    'name' => 'form_settings',
                    'fields' => array(
                        array(
                            'id' => 'custom_post_type',
                            'type' => 'select',
                            'title' => __('Custom post type', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Select custom post type', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => get_post_types(),
                            'class' => 'chosen',
                            'default_option' => __('Select a custom post type', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'default_feature_image',
                            'type' => 'image',
                            'title' => __('Default feature image', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'add_title' => __('Add image', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'default_category',
                            'type' => 'select',
                            'title' => __('Default category', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => 'categories',
                            'query_args' => array(
                                'hide_empty' => false,
                                'orderby' => 'name',
                                'order' => 'ASC',
                            ),
                            'class' => 'chosen',
                            'default_option' => __('Select a category', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'default_author',
                            'type' => 'select',
                            'title' => __('Default author', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => $authors,
                            'class' => 'chosen',
                            'default_option' => __('Select an author', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'post_status_after_created',
                            'type' => 'select',
                            'title' => __('Post status after created', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => get_post_statuses(),
                            'default' => 'pending',
                            'class' => 'chosen',
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'comment_status',
                            'type' => 'select',
                            'title' => __('Comment status', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                0 => __('Disable', ACF_FORM_BUILDER_TEXTDOMAIN),
                                1 => __('Enable', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                            'default' => 0,
                            'class' => 'chosen',
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'show_post_title',
                            'type' => 'switcher',
                            'title' => __('Show post title', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'show_post_content_editor',
                            'type' => 'switcher',
                            'title' => __('Show post content editor', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'field_label_placement',
                            'type' => 'select',
                            'title' => __('Field label placement', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                'top' => __('Top', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'left' => __('Left', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                            'default' => 'top',
                            'class' => 'chosen',
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'field_instruction',
                            'type' => 'select',
                            'title' => __('Field instruction', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                'label' => __('Label', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'field' => __('Field', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                            'default' => 'label',
                            'class' => 'chosen',
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'field_html_wrapper',
                            'type' => 'select',
                            'title' => __('Field HTML wrapper', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                'div' => __('div', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'tr' => __('tr', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'td' => __('td', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'ul' => __('ul', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'ol' => __('ol', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'dl' => __('dl', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                            'default' => 'div',
                            'class' => 'chosen',
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'custom_form_attributes',
                            'type' => 'text',
                            'title' => __('Custom form attributes', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'upload_form_type',
                            'type' => 'select',
                            'title' => __('Upload form type', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                'basic' => __('Basic Upload', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'wp_uploader' => __('WP Uploader', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                            'default' => 'basic',
                            'class' => 'chosen',
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'edit_page_id',
                            'type' => 'select',
                            'title' => __('Edit Page', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => 'pages',
                            'query_args' => array(
                                'orderby' => 'name',
                                'order' => 'ASC',
                            ),
                            'class' => 'chosen',
                            'default_option' => __('Select Edit Page', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                    ),
                ),
                array(
                    'title' => 'Register settings',
                    'id' => 'register_settings',
                    'name' => 'register_settings',
                    'fields' => array(
                        array(
                            'id' => 'user_role',
                            'type' => 'select',
                            'title' => __('Set user role', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Select a role', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => $roles,
                            'class' => 'chosen',
                            'default_option' => __('Select a role', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'can_access_wp_admin',
                            'type' => 'switcher',
                            'title' => __('Can access WP-Admin', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'hide_wp_admin',
                            'type' => 'switcher',
                            'title' => __('Hide WP-Admin bar', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'send_confirm_message',
                            'type' => 'switcher',
                            'title' => __('Send confirmation message', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'term_n_conditional',
                            'type' => 'textarea',
                            'title' => __('Term and conditional', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'limit_attempt_number',
                            'type' => 'number',
                            'title' => __('Number of limit attempt', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'limit_attempt_time',
                            'type' => 'select',
                            'title' => __('Time limit', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                'hour' => 'Hour',
                                'day' => 'Day',
                                'week' => 'Week',
                                'month' => 'Month',
                                'year' => 'Year',
                            ),
                            'class' => 'chosen',
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'show_custom_fields_backend',
                            'type' => 'switcher',
                            'title' => __('Show custom fields in edit profile backend', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'hide_custom_fields_frontend',
                            'type' => 'switcher',
                            'title' => __('Hide custom fields in frontend', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'require_password_to_update_profile',
                            'type' => 'switcher',
                            'title' => __('Require password to update profile', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'custom_css_class',
                            'type' => 'text',
                            'title' => __('Custom CSS class', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                    )
                ),
                array(
                    'title' => 'Payment settings',
                    'id' => 'payment_settings',
                    'name' => 'payment_settings',
                    'fields' => array(
                        array(
                            'id' => 'enable_paypal_gateway',
                            'type' => 'switcher',
                            'title' => __('Enable Paypal', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'paypal_email',
                            'type' => 'text',
                            'title' => __('Paypal Email', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|enable_paypal_gateway', '==', 'true|true')
                        ),
                        array(
                            'id' => 'paypal_sandbox_key',
                            'type' => 'text',
                            'title' => __('Paypal Sandbox Key', ACF_FORM_BUILDER_TEXTDOMAIN),

                            'dependency' => array('is_form|enable_paypal_gateway', '==', 'true|true')
                        ),
                        array(
                            'id' => 'paypal_production_key',
                            'type' => 'text',
                            'title' => __('Paypal Production Key', ACF_FORM_BUILDER_TEXTDOMAIN),

                            'dependency' => array('is_form|enable_paypal_gateway', '==', 'true|true')
                        ),
                        array(
                            'id' => 'paypal_mode',
                            'type' => 'select',
                            'title' => __('Paypal Mode', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                'sandbox' => __('Sandbox', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'production' => __('Production', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                            'default' => 'sandbox',
                            'class' => 'chosen',
                            'dependency' => array('is_form|enable_paypal_gateway', '==', 'true|true')
                        ),
                        array(
                            'id' => 'enable_stripe_gateway',
                            'type' => 'switcher',
                            'title' => __('Enable Stripe', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'stripe_sandbox_key',
                            'type' => 'text',
                            'title' => __('Stripe Sandbox Key', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|enable_stripe_gateway', '==', 'true|true')
                        ),
                        array(
                            'id' => 'stripe_production_key',
                            'type' => 'text',
                            'title' => __('Stripe Production Key', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|enable_stripe_gateway', '==', 'true|true')
                        ),
                        array(
                            'id' => 'stripe_sandbox_secret_key',
                            'type' => 'text',
                            'title' => __('Stripe Sandbox Secret Key', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|enable_stripe_gateway', '==', 'true|true')
                        ),
                        array(
                            'id' => 'stripe_production_secret_key',
                            'type' => 'text',
                            'title' => __('Stripe Production Secret Key', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|enable_stripe_gateway', '==', 'true|true')
                        ),
                        array(
                            'id' => 'stripe_mode',
                            'type' => 'select',
                            'title' => __('Stripe Mode', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                'sandbox' => __('Sandbox', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'production' => __('Production', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                            'default' => 'sandbox',
                            'class' => 'chosen',
                            'dependency' => array('is_form|enable_stripe_gateway', '==', 'true|true')
                        ),
                        array(
                            'id' => 'enable_woo_gateway',
                            'type' => 'switcher',
                            'title' => __('Enable Woocommerce Gateway', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'woo_gateway_title',
                            'type' => 'text',
                            'title' => __('WooCommerce Paygate Title', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|enable_woo_gateway', '==', 'true|true'),
                        ),
                        array(
                            'id' => 'woo_gateway_description',
                            'type' => 'textarea',
                            'title' => __('WooCommerce Paygate Description', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|enable_woo_gateway', '==', 'true|true'),
                            'sanitize' => false,
                        ),
                        array(
                            'id' => 'payment_type',
                            'type' => 'select',
                            'title' => __('Payment Type', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                'pay_per_post' => __('Pay Per Post', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'subscription' => __('Subscription', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                            'class' => 'chosen',
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'pay_per_post',
                            'type' => 'number',
                            'title' => __('Post Price', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 0,
                            'dependency' => array('is_form|payment_type', '==', 'true|pay_per_post'),
                        ),
                        array(
                            'id' => 'expiry_time',
                            'type' => 'number',
                            'title' => __('Expiry time', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Enter number of expiry days', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 5,
                            'dependency' => array('is_form|payment_type', '==', 'true|pay_per_post'),
                        ),
                        array(
                            'id' => 'is_feature',
                            'type' => 'switcher',
                            'title' => __('Enable Feature Post', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|payment_type', '==', 'true|pay_per_post'),
                        ),
                        array(
                            'id' => 'pay_per_post_feature',
                            'type' => 'number',
                            'title' => __('Feature Post Price', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('This price is additional price', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 0,
                            'dependency' => array('is_form|payment_type|is_feature', '==', 'true|pay_per_post|true'),
                        ),
                        array(
                            'id' => 'expiry_time_feature',
                            'type' => 'number',
                            'title' => __('Expiry feature time', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Enter number of expiry days', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 5,
                            'dependency' => array('is_form|payment_type|is_feature', '==', 'true|pay_per_post|true'),
                        ),
                        array(
                            'id' => 'is_vip',
                            'type' => 'switcher',
                            'title' => __('Enable VIP Post', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|payment_type', '==', 'true|pay_per_post'),
                        ),
                        array(
                            'id' => 'pay_per_post_vip',
                            'type' => 'number',
                            'title' => __('VIP Post Price', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('This price is additional price', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 0,
                            'dependency' => array('is_form|payment_type|is_vip', '==', 'true|pay_per_post|true'),
                        ),
                        array(
                            'id' => 'expiry_time_vip',
                            'type' => 'number',
                            'title' => __('Expiry VIP time', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Enter number of expiry days', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 5,
                            'dependency' => array('is_form|payment_type|is_vip', '==', 'true|pay_per_post|true'),
                        ),
                        array(
                            'id' => 'subscription',
                            'type' => 'select',
                            'title' => __('Package', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Select available package for this form', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => 'posts',
                            'query_args' => array(
                                'post_type' => 'cats_packages',
                            ),
                            'attributes' => array(
                                'multiple' => 'multiple',
                                'style' => 'width: 100%'
                            ),
                            'class' => 'chosen',
                            'dependency' => array('is_form|payment_type', '==', 'true|subscription'),
                        ),
                        array(
                            'id' => 'subscription_style',
                            'type' => 'select',
                            'title' => __('Package style', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Select package style', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => array(
                                'style-1' => __('Style 1', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'style-2' => __('Style 2', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'style-3' => __('Style 3', ACF_FORM_BUILDER_TEXTDOMAIN),
                                'style-4' => __('Style 4', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                            'class' => 'chosen',
                            'dependency' => array('is_form|payment_type', '==', 'true|subscription'),
                        ),
                    ),
                ),
                array(
                    'title' => 'Transalation settings',
                    'id' => 'translation_settings',
                    'name' => 'translation_settings',
                    'fields' => array(
                        array(
                            'id' => 'submit_button_text',
                            'type' => 'text',
                            'title' => __('Submit button text', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'update_button_text',
                            'type' => 'text',
                            'title' => __('Update button text', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form', '==', 'true'),
                        ),
                        array(
                            'id' => 'unauthorization_error_text',
                            'type' => 'textarea',
                            'title' => __('Unauthorization error', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form', '==', 'true'),
                            'default' => __('You do not have permission to edit this post or access this page', ACF_FORM_BUILDER_TEXTDOMAIN)
                        ),
                        array(
                            'id' => 'not_match_package',
                            'type' => 'textarea',
                            'title' => __('Not match package error', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form', '==', 'true'),
                            'default' => __('Your current package is empty or do not meet form requirements, please update or select the corresponding to this form.', ACF_FORM_BUILDER_TEXTDOMAIN)
                        ),
                    ),
                ),

                array(
                    'title' => 'Notify settings',
                    'id' => 'notify_settings',
                    'name' => 'notify_settings',
                    'fields' => array(
                        array(
                            'id' => 'is_notify',
                            'type' => 'switcher',
                            'title' => __('Enable notify', ACF_FORM_BUILDER_TEXTDOMAIN),
                        ),
                        array(
                            'type' => 'subheading',
                            'content' => 'Create Post',
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'create_post_notify_user',
                            'type' => 'switcher',
                            'title' => __('Notify to user', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'create_post_notify_user_subject',
                            'type' => 'text',
                            'title' => __('Subject', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|create_post_notify_user|form_type', '==', 'true|true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'create_post_notify_user_body',
                            'type' => 'textarea',
                            'title' => __('Email body', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|create_post_notify_user|form_type', '==', 'true|true|frontend_post_submission'),
                            'default' => ''
                        ),
                        array(
                            'id' => 'create_post_notify_admin',
                            'type' => 'switcher',
                            'title' => __('Notify to admin', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'create_post_notify_admin_subject',
                            'type' => 'text',
                            'title' => __('Subject', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|create_post_notify_admin|form_type', '==', 'true|true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'create_post_notify_admin_body',
                            'type' => 'textarea',
                            'title' => __('Email body', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|create_post_notify_admin|form_type', '==', 'true|true|frontend_post_submission'),
                            'default' => ''
                        ),
                        array(
                            'type' => 'subheading',
                            'content' => 'Update Post',
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'update_post_notify_user',
                            'type' => 'switcher',
                            'title' => __('Notify to user', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'update_post_notify_user_subject',
                            'type' => 'text',
                            'title' => __('Subject', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|update_post_notify_user|form_type', '==', 'true|true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'update_post_notify_user_body',
                            'type' => 'textarea',
                            'title' => __('Email body', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|update_post_notify_user|form_type', '==', 'true|true|frontend_post_submission'),
                            'default' => ''
                        ),
                        array(
                            'id' => 'update_post_notify_admin',
                            'type' => 'switcher',
                            'title' => __('Notify to admin', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'update_post_notify_admin_subject',
                            'type' => 'text',
                            'title' => __('Subject', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|update_post_notify_admin|form_type', '==', 'true|true|frontend_post_submission'),
                        ),
                        array(
                            'id' => 'update_post_notify_admin_body',
                            'type' => 'textarea',
                            'title' => __('Email body', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|update_post_notify_admin|form_type', '==', 'true|true|frontend_post_submission'),
                            'default' => ''
                        ),
                        array(
                            'type' => 'subheading',
                            'content' => 'Create User',
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'create_user_notify_user',
                            'type' => 'switcher',
                            'title' => __('Notify to user', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'create_user_notify_user_subject',
                            'type' => 'text',
                            'title' => __('Subject', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|create_user_notify_user|form_type', '==', 'true|true|user_registeration'),
                        ),
                        array(
                            'id' => 'create_user_notify_user_body',
                            'type' => 'textarea',
                            'title' => __('Email body', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|create_user_notify_user|form_type', '==', 'true|true|user_registeration'),
                            'default' => ''
                        ),
                        array(
                            'id' => 'create_user_notify_admin',
                            'type' => 'switcher',
                            'title' => __('Notify to admin', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'create_user_notify_admin_subject',
                            'type' => 'text',
                            'title' => __('Subject', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|create_user_notify_admin|form_type', '==', 'true|true|user_registeration'),
                        ),
                        array(
                            'id' => 'create_user_notify_admin_body',
                            'type' => 'textarea',
                            'title' => __('Email body', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|create_user_notify_admin|form_type', '==', 'true|true|user_registeration'),
                            'default' => ''
                        ),
                        array(
                            'type' => 'subheading',
                            'content' => 'Update User',
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'update_user_notify_user',
                            'type' => 'switcher',
                            'title' => __('Notify to user', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'update_user_notify_user_subject',
                            'type' => 'text',
                            'title' => __('Subject', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|update_user_notify_user|form_type', '==', 'true|true|user_registeration'),
                        ),
                        array(
                            'id' => 'update_user_notify_user_body',
                            'type' => 'textarea',
                            'title' => __('Email body', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|update_user_notify_user|form_type', '==', 'true|true|user_registeration'),
                            'default' => ''
                        ),
                        array(
                            'id' => 'update_user_notify_admin',
                            'type' => 'switcher',
                            'title' => __('Notify to admin', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration'),
                        ),
                        array(
                            'id' => 'update_user_notify_admin_subject',
                            'type' => 'text',
                            'title' => __('Subject', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|update_user_notify_admin|form_type', '==', 'true|true|user_registeration'),
                        ),
                        array(
                            'id' => 'update_user_notify_admin_body',
                            'type' => 'textarea',
                            'title' => __('Email body', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|update_user_notify_admin|form_type', '==', 'true|true|user_registeration'),
                            'default' => ''
                        ),
                        array(
                            'id' => 'post_form',
                            'type' => 'label',
                            'content' => __('You can use mail-tags in a email body.<br/> This <code>%file_name%</code> is a mail-tag that will be replaced with an actual post info through the <code>field_name</code> field.<br/> (ex: <code>%post_title%</code>, <code>%post_date%</code>, ...)', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|frontend_post_submission')
                        ),
                        array(
                            'id' => 'user_form',
                            'type' => 'label',
                            'content' => __('You can use mail-tags in a email body.<br/> This <code>%file_name%</code> is a mail-tag that will be replaced with an actual user info or user meta info through the <code>field_name</code> field.<br/> (ex: <code>%user_email%</code>, <code>%display_name%</code>, <code>%ID%</code> ...)', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'dependency' => array('is_form|form_type', '==', 'true|user_registeration')
                        )

                    ),
                ),
            ),
        );

        return $options;
    }

    /**
     * add package post type metabox
     */
    public function acf_add_package_metabox($options)
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'cats_packages' and post_status='publish'");
        $parent_packages = array();
        if ($results) {
            foreach ($results as $item) {
                $parent_packages[$item->ID] = $item->post_title;
            }
        }
        $options[] = array(
            'id' => '_acf_package_metabox',
            'title' => __('Package Settings', ACF_FORM_BUILDER_TEXTDOMAIN),
            'post_type' => 'cats_packages',
            'context' => 'normal',
            'priority' => 'high',
            'sections' => array(
                array(
                    'name' => 'package_option',
                    'id' => 'package_option',
                    'title' => __('Package Options', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'fields' => array(
                        array(
                            'id' => 'is_addon',
                            'type' => 'switcher',
                            'title' => __('Enable Package Addon', ACF_FORM_BUILDER_TEXTDOMAIN),
                        ),
                        array(
                            'id' => 'parent_package',
                            'type' => 'select',
                            'title' => __('Parent Package', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => $parent_packages,
                            'default' => 0,
                            'class' => 'chosen',
                            'dependency' => array('is_addon', '==', 'true'),
                        ),
                        array(
                            'id' => 'package_price',
                            'type' => 'number',
                            'title' => __('Package Price', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 0,
                        ),
                        array(
                            'id' => 'number_of_posts',
                            'type' => 'number',
                            'title' => __('Number Of Posts', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 10,
                        ),
                        array(
                            'id' => 'package_time',
                            'type' => 'number',
                            'title' => __('Package days', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Enter number of days', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 30,
                            'dependency' => array('is_addon', '==', 'false'),
                        ),
                        array(
                            'id' => 'redirect_after_purchase_package',
                            'type' => 'select',
                            'title' => __('Redirect after purchase', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Redirect to specific page after purchase this package', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'options' => 'page',
                            'dependency' => array('is_addon', '==', 'false'),
                            'class' => 'chosen'
                        ),
                        array(
                            'id' => 'max_post',
                            'type' => 'fieldset',
                            'title' => __('Max post', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'button_title' => __('Add New', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'accordion_title' => __('Group', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'fields' => array(
                                array(
                                    'id' => 'max_post_number',
                                    'type' => 'number',
                                    'title' => __('Number', ACF_FORM_BUILDER_TEXTDOMAIN),
                                    'default' => 10,
                                ),
                                array(
                                    'id' => 'max_post_time',
                                    'type' => 'select',
                                    'options' => array(
                                        'day' => __('Day', ACF_FORM_BUILDER_TEXTDOMAIN),
                                        'week' => __('Week', ACF_FORM_BUILDER_TEXTDOMAIN),
                                        'month' => __('Month', ACF_FORM_BUILDER_TEXTDOMAIN),
                                        'year' => __('Year', ACF_FORM_BUILDER_TEXTDOMAIN),
                                    ),
                                    'title' => __('Time', ACF_FORM_BUILDER_TEXTDOMAIN),
                                ),
                            ),
                            'dependency' => array('is_addon', '==', 'false'),
                        ),
                        array(
                            'id' => 'is_feature',
                            'type' => 'switcher',
                            'title' => __('Enable Feature Post', ACF_FORM_BUILDER_TEXTDOMAIN),
                        ),
                        array(
                            'id' => 'number_of_posts_feature',
                            'type' => 'number',
                            'title' => __('Number Of Feature Posts', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 10,
                            'dependency' => array('is_feature', '==', 'true'),
                        ),
                        array(
                            'id' => 'expiry_time_feature',
                            'type' => 'number',
                            'title' => __('Expiry feature time', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Enter number of expiry days', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 5,
                            'dependency' => array('is_feature|is_addon', '==', 'true|false'),
                        ),
                        array(
                            'id' => 'is_vip',
                            'type' => 'switcher',
                            'title' => __('Enable VIP Post', ACF_FORM_BUILDER_TEXTDOMAIN),
                        ),
                        array(
                            'id' => 'number_of_posts_vip',
                            'type' => 'number',
                            'title' => __('Number Of VIP Posts', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 10,
                            'dependency' => array('is_vip', '==', 'true'),
                        ),
                        array(
                            'id' => 'expiry_time_vip',
                            'type' => 'number',
                            'title' => __('Expiry VIP time', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'desc' => __('Enter number of expiry days', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'default' => 5,
                            'dependency' => array('is_vip|is_addon', '==', 'true|false'),
                        ),
                    ),
                ),
                array(
                    'name' => 'package_feature',
                    'id' => 'package_feature',
                    'title' => __('Package Feature', ACF_FORM_BUILDER_TEXTDOMAIN),
                    'fields' => array(
                        array(
                            'id' => 'package_feature_group',
                            'type' => 'group',
                            'title' => __('Package Features', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'button_title' => __('Add New', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'accordion_title' => __('Group', ACF_FORM_BUILDER_TEXTDOMAIN),
                            'fields' => array(
                                array(
                                    'id' => 'package_feature_icon',
                                    'type' => 'icon',
                                    'title' => __('Icon', ACF_FORM_BUILDER_TEXTDOMAIN),
                                ),
                                array(
                                    'id' => 'package_feature_text',
                                    'type' => 'text',
                                    'title' => __('Text', ACF_FORM_BUILDER_TEXTDOMAIN),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        );

        return $options;
    }

    /**
     * add transaction post type metabox
     */
    public function acf_add_transaction_metabox($options)
    {
        $table = '';
        if (isset($_GET['post'])) {
            $post_type = get_post_type($_GET['post']);
            if ($post_type == 'cats_transactions') {
                $post_meta = get_post_meta($_GET['post']);
                $post = get_post($_GET['post']);
                $user = get_user_by('id', $post->post_author);

                $table .= '<table width="100%" class="wp-list-table widefat fixed striped posts">';
                if ($post_meta['_acf_fb_order_id']) {
                    $table .= '<tr>';
                    $table .= '<td width="25%"><strong>' . __('Order ID(Woocommerce)', ACF_FORM_BUILDER_TEXTDOMAIN) . '</strong></td>';
                    $table .= '<td width="75%"><a href="' . get_admin_url() . 'post.php?post=' . $post_meta['_acf_fb_order_id'][0] . '&action=edit">#' . $post_meta['_acf_fb_order_id'][0] . ' - ' . get_the_title($post_meta['_acf_fb_order_id'][0]) . '</td>';
                    $table .= '</tr>';
                }
                $table .= '<tr>';
                $table .= '<td width="25%"><strong>' . __('Transaction Time', ACF_FORM_BUILDER_TEXTDOMAIN) . '</strong></td>';
                $table .= '<td width="75%">' . $post->post_date . '</td>';
                $table .= '</tr>';
                $table .= '<tr>';
                $table .= '<td width="25%"><strong>' . __('Transaction Secret', ACF_FORM_BUILDER_TEXTDOMAIN) . '</strong></td>';
                $table .= '<td width="75%">' . $post_meta['_transaction_secret'][0] . '</td>';
                $table .= '</tr>';
                $table .= '<tr>';
                $table .= '<td width="25%"><strong>' . __('Transaction Status', ACF_FORM_BUILDER_TEXTDOMAIN) . '</strong></td>';
                $table .= '<td width="75%">' . $post_meta['_transaction_status'][0] . '</td>';
                $table .= '</tr>';
                $table .= '<tr>';
                $table .= '<td><strong>' . __('Account', ACF_FORM_BUILDER_TEXTDOMAIN) . '</strong></td>';
                $table .= '<td>' . $user->user_nicename . '</td>';
                $table .= '</tr>';
                $table .= '<tr>';
                $table .= '<td width="25%"><strong>' . __('Content', ACF_FORM_BUILDER_TEXTDOMAIN) . '</strong></td>';
                $table .= '<td width="75%">' . $post_meta['_transaction_type'][0] . '</td>';
                $table .= '</tr>';
                if (isset($post_meta['_post_id'])) {
                    $post_id = $post_meta['_post_id'][0];
                    $post = get_post($post_id);
                    $table .= '<tr>';
                    $table .= '<td width="25%"><strong>' . __('Post title', ACF_FORM_BUILDER_TEXTDOMAIN) . '</strong></td>';
                    $table .= '<td width="75%">' . $post->post_title . '</td>';
                    $table .= '</tr>';
                } elseif (isset($post_meta['_package_id'])) {
                    $package_id = $post_meta['_package_id'][0];
                    $package = get_post($package_id);
                    $table .= '<tr>';
                    $table .= '<td width="25%"><strong>' . __('Package', ACF_FORM_BUILDER_TEXTDOMAIN) . '</strong></td>';
                    $table .= '<td width="75%">' . $package->post_title . '</td>';
                    $table .= '</tr>';
                }

                $total = $post_meta['_price'][0];
                if (isset($post_meta['_post_types'])) {
                    $post_types = unserialize($post_meta['_post_types'][0]);
                    foreach ($post_types as $key => $post_type) {
                        $total += $post_meta['_price_' . $post_type][0];
                    }
                }

                $table .= '<tr>';
                $table .= '<td width="25%"><strong>' . __('Total', ACF_FORM_BUILDER_TEXTDOMAIN) . '</strong></td>';
                $table .= '<td width="75%">$' . $total . '</td>';
                $table .= '</tr>';
                $table .= '</table>';

                if (!empty($post_meta['_transaction_info'][0])) {
                    $transaction_detail = unserialize($post_meta['_transaction_info'][0]);
                    $table .= '<h3>' . __('Transaction Detail') . '</h3>';
                    $table .= '<table width="100%" class="wp-list-table widefat fixed striped posts">';
                    foreach ($transaction_detail as $key => $value) {
                        if (!is_array($value) && !empty($value)) {
                            $table .= '<tr>';
                            $table .= '<td width="25%"><strong>' . $key . '</strong></td>';
                            $table .= '<td width="75%">' . $value . '</td>';
                            //$table .= '<td width="75%">' . ($key == 'amount' ? ($value / 100) : $value) . '</td>';
                            //$table .= '<td width="75%">' . (is_array($value) ? '<pre>' . print_r($value, true) . '</pre>' : $value) . '</td>';
                            $table .= '</tr>';
                        }
                    }
                    $table .= '</table>';
                }
            }
        }
        $options[] = array(
            'id' => '_acf_transaction_metabox',
            'title' => __('Transaction Info', ACF_FORM_BUILDER_TEXTDOMAIN),
            'post_type' => 'cats_transactions',
            'context' => 'normal',
            'priority' => 'high',
            'sections' => array(
                array(
                    'name' => 'general',
                    'id' => 'general',
                    'fields' => array(
                        array(
                            'id' => 'shortcode',
                            'type' => 'label',
                            'content' => $table,
                        )
                    ),
                ),
            ),
        );

        return $options;
    }

    public function show_package_in_user_profile($user)
    {
        $user_meta = get_user_meta($user->ID);
        if (isset($user_meta['_current_package_id'])) {
            $package_meta = get_post_meta($user_meta['_current_package_id'][0]);
            $package_meta = unserialize($package_meta['_acf_package_metabox'][0]);
            $package = get_post($user_meta['_current_package_id'][0]);

            $data = [
                'user' => $user,
                'user_meta' => $user_meta,
                'package' => $package,
                'package_meta' => $package_meta,
            ];
            helper_get_template_part('admin/partials/acf-info', 'package', $data, true);
        }
    }

    public function check_user_meta()
    {
        $user_id = get_current_user_id();
        $user_meta = get_user_meta($user_id);

        if (isset($user_meta['acf_form_id'])) {
            $acf_form_meta = get_post_meta($user_meta['acf_form_id'][0]);

            $acf_form_settings = isset($acf_form_meta['_acf_form_builder_metabox']) ? unserialize($acf_form_meta['_acf_form_builder_metabox'][0]) : [];

            if (isset($acf_form_settings['can_access_wp_admin']) && !$acf_form_settings['can_access_wp_admin']) {
                wp_redirect(home_url());
            }

        }
    }

    public function acf_user_profile_fields($user)
    {
        $user_meta = get_user_meta($user->ID);

        if (isset($user_meta['acf_custom_user_fields']) && $user_meta['acf_custom_user_fields'] && isset($user_meta['acf_form_id'])) {

            $acf_form_meta = get_post_meta($user_meta['acf_form_id'][0]);

            $acf_form_settings = isset($acf_form_meta['_acf_form_builder_metabox']) ? unserialize($acf_form_meta['_acf_form_builder_metabox'][0]) : [];

            $user_profiles = [];

            if (isset($acf_form_settings['show_custom_fields_backend']) && $acf_form_settings['show_custom_fields_backend']) {

                $user_custom_fields = unserialize($user_meta['acf_custom_user_fields'][0]);

                foreach ($user_custom_fields as $key => $value) {
                    $field_objects = get_field_object($key);

                    switch ($field_objects['form_field_type']) {
                        case 'username':

                            break;
                        case 'usermail':

                            break;
                        case 'userpassword':

                            break;
                        case 'userfirstname':

                            break;

                        case 'userlastname':

                            break;
                        case 'usernicename':

                            break;
                        case 'usernickname':

                            break;
                        case 'userdescription':

                            break;
                        case 'userurl':

                            break;
                        default:

                            $user_profiles[$key] = array(
                                'id' => $field_objects['ID'],
                                'name' => $field_objects['label'],
                                'value' => $value
                            );
                            break;
                    }
                }

            }

            $data['user_profiles'] = $user_profiles;
            return helper_get_template_part('admin/partials/acf-form-builder', 'backend-user-profile', $data, true);

        }

    }

}