<?php
/**
 * The public-facing functionality of the plugin.
 *
 * false       http://support.catsplugins.com
 * @since      1.0.0
 *
 * @package    Acf_Form_Builder
 * @subpackage Acf_Form_Builder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Acf_Form_Builder
 * @subpackage Acf_Form_Builder/public
 * @author     Cat's Plugins <admin@catsplugins.com>
 */
class Acf_Form_Builder_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
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
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function setup_wp_uploader()
    {

        $userUseUploader = cs_get_option('enable_user_upload');
        if ($userUseUploader == 1) {
            global $wp_roles;
            $wp_roles->add_cap( 'subscriber', 'upload_files' );
            add_filter('ajax_query_attachments_args', [$this, 'wpb_show_current_user_attachments']);
        }
    }

    public function wpb_show_current_user_attachments($query)
    {
        $user_id = get_current_user_id();
        if ($user_id
            && !current_user_can('activate_plugins')
            && !current_user_can('edit_others_posts')
        ) {
            $query['author'] = $user_id;
        }
        return $query;
    }


    private static function re_order_terms($data, $int, $prefix = '')
    {
        $output = [];
        foreach ($data[$int] as $term) {
            $output[$term->term_id] = $prefix . $term->name;

            if (isset($data[$term->term_id])) {
                $output = array_merge($output, self::re_order_terms($data, $term->term_id, $prefix . "--"));
            }
        }

        return $output;
    }

    private static function get_term_by_taxonomy($custom_taxonomy)
    {
        $terms = get_terms([
            'taxonomy' => $custom_taxonomy,
            'hide_empty' => false
        ]);

        $output = [];

        if ($terms) {
            foreach ($terms as $term) {

                $output[$term->parent][] = $term;
            }
        }

        $final = self::re_order_terms($output, 0);

        return $final;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/acf-form-builder-public.css', array(), $this->version, 'all');

        // DataTables CSS
        wp_enqueue_style($this->plugin_name . 'data-tables', 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css', ['wp-jquery-ui-dialog'], $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        // Register the script
        wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/acf-form-builder-public.js', array('jquery'), $this->version, false);

        // Localize the script with new data
        $jsData = [
            'ajax_url' => admin_url('admin-ajax.php'),
            'translated' => [
                'delete_confirm_title' => __('Do you really want to delete this post?', ACF_FORM_BUILDER_TEXTDOMAIN),
                'delete_confirm_content' => __('These items will be permanently deleted and cannot be recovered. Are you sure?', ACF_FORM_BUILDER_TEXTDOMAIN),
                'deleting' => __('Deleting...', ACF_FORM_BUILDER_TEXTDOMAIN),
                'delete_fail' => __('Deleting this post failed!', ACF_FORM_BUILDER_TEXTDOMAIN),
            ]
        ];
        wp_localize_script($this->plugin_name, 'acfbpData', $jsData);

        // Enqueued script with localized data.
        wp_enqueue_script($this->plugin_name);

        // DataTables JS
       // wp_enqueue_script($this->plugin_name . 'data-tables', 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js', ['jquery', 'jquery-effects-core', 'jquery-ui-dialog'], $this->version, true);
    }

    public function process_transaction_status()
    {
        if (isset($_GET['transaction']) == 'failed') {
            if (isset($_SESSION['transaction_secret'])) {

                $args = array(
                    'post_type' => 'cats_transactions',
                    'posts_per_page' => 1,
                    'post_status' => 'publish',
                    'meta_key' => '_transaction_secret',
                    'meta_value' => $_SESSION['transaction_secret'],
                );
                $has_transaction = get_posts($args);

                if (count($has_transaction)) {
                    $post_data = array(
                        'ID' => $has_transaction[0]->ID,
                        'post_status' => 'draft',
                    );

                    wp_update_post($post_data);

                    $transaction_id = $has_transaction[0]->ID;
                    $transaction_meta = get_post_meta($transaction_id);

                    update_post_meta($transaction_id, '_transaction_status', $_POST['payment_status']);
                    update_post_meta($transaction_id, '_transaction_info', $_POST);

                    if (isset($transaction_meta['_post_id'])) {
                        $post_id = $transaction_meta['_post_id'][0];
                        $post_data = array(
                            'ID' => $post_id,
                            'post_status' => 'draft',
                        );

                        wp_update_post($post_data);

                    }
                }
            }

            unset($_SESSION['transaction_secret']);
        }
    }

    public function process_ipn_return()
    {
        if (isset($_GET['ipn']) && $_GET['ipn']) {
            if (!isset($_POST['custom'])) {
                return;
            }

            file_put_contents(ACF_FORM_BUILDER_PLUGIN_PATH . '/logs/ipn-' . $_POST['custom'], $_POST);
            $transaction_secret = $_POST['custom'];
            $args = array(
                'post_type' => 'cats_transactions',
                'posts_per_page' => 1,
                'meta_key' => '_transaction_secret',
                'meta_value' => $transaction_secret,
            );
            $has_transaction = get_posts($args);

            if (count($has_transaction)) {
                $transaction_id = $has_transaction[0]->ID;
                $transaction_meta = get_post_meta($transaction_id);

                update_post_meta($transaction_id, '_transaction_status', $_POST['payment_status']);
                update_post_meta($transaction_id, '_transaction_info', $_POST);

                if (isset($transaction_meta['_post_id'])) {
                    $post_id = $transaction_meta['_post_id'][0];
                    $post_data = array(
                        'ID' => $post_id,
                        'post_status' => 'publish',
                    );

                    wp_update_post($post_data);

                    $group_id = $transaction_meta['_group_id'][0];

                    $form_meta = get_post_meta($group_id);

                    if (isset($form_meta['_acf_form_builder_metabox'])) {
                        $form_settings = unserialize($form_meta['_acf_form_builder_metabox'][0]);
                        $expiry_days = $form_settings['expiry_time'] ? $form_settings['expiry_time'] : 0;
                        $vip_expiry_days = $form_settings['expiry_time_vip'] ? $form_settings['expiry_time_vip'] : 0;
                        $feature_expiry_days = $form_settings['expiry_time_feature'] ? $form_settings['expiry_time_feature'] : 0;

                        $expiry_time = time() + (24 * 60 * 60 * $expiry_days);
                        $vip_expiry_time = time() + (24 * 60 * 60 * $vip_expiry_days);
                        $feature_expiry_time = time() + (24 * 60 * 60 * $feature_expiry_days);

                        update_post_meta($post_id, '_expiry_time', $expiry_time);
                        update_post_meta($post_id, '_vip_expiry_time', $vip_expiry_time);
                        update_post_meta($post_id, '_feature_expiry_time', $feature_expiry_time);
                    }

                } elseif (isset($transaction_meta['_package_id'])) {
                    $package_id = $transaction_meta['_package_id'][0];
                    $package = get_post($package_id);
                    $package_meta = get_post_meta($package_id);
                    $package->meta = unserialize($package_meta['_acf_package_metabox'][0]);

                    $user_id = $transaction_meta['_user_id'][0];
                    $user_packages_data = get_user_meta($user_id, '_current_packages', true);

                    $user_packages_data = !empty($user_packages_data) && count($user_packages_data) ? $user_packages_data : [];

                    if (!$package->meta['is_addon']) {
                        $user_packages_data[$package_id] = array(
                            'package_id' => $package_id,
                            'number_of_posts' => $package->meta['number_of_posts'],
                            'number_of_posts_by_time' => $package->meta['max_post']['max_post_number'],
                            'unit_time' => $package->meta['max_post']['max_post_time'],
                            'expiry_time' => time() + ($package->meta['package_time'] * 60 * 60 * 24),
                            'current_time' => time(),
                        );

                        if ($package->meta['is_vip']) {
                            $user_packages_data[$package_id]['number_of_posts_vip'] = $package->meta['number_of_posts_vip'];
                            $user_packages_data[$package_id]['expiry_time_vip'] = $package->meta['expiry_time_vip'];
                        }

                        if ($package->meta['is_feature']) {
                            $user_packages_data[$package_id]['number_of_posts_feature'] = $package->meta['number_of_posts_feature'];
                            $user_packages_data[$package_id]['expiry_time_feature'] = $package->meta['expiry_time_feature'];
                        }
                    } else {
                        $parent_package = isset($user_packages_data[$package->meta['parent_package']]) ? $user_packages_data[$package->meta['parent_package']] : [];
                        if ($user_packages_data && $parent_package) {
                            $parent_package['number_of_posts'] += $package->meta['number_of_posts'];
                            if ($package->meta['is_vip']) {
                                $parent_package['number_of_posts_vip'] += $package->meta['number_of_posts_vip'];
                            }

                            if ($package->meta['is_feature']) {
                                $parent_package['number_of_posts_feature'] += $package->meta['number_of_posts_feature'];
                            }
                            $user_packages_data[$package->meta['parent_package']] = $parent_package;
                        }
                    }

                    update_user_meta($user_id, '_current_packages', $user_packages_data);
                }
            }
            exit;
        }
    }

    public function process_stripe_ipn_return()
    {
        if (isset($_GET['stripe_ipn']) && $_GET['stripe_ipn'] && isset($_POST['stripeToken']) && isset($_GET['transaction_secret'])) {
            try {
                $transaction_secret = $_GET['transaction_secret'];
                $args = array(
                    'post_type' => 'cats_transactions',
                    'posts_per_page' => 1,
                    'meta_key' => '_transaction_secret',
                    'meta_value' => $transaction_secret,
                );
                $has_transaction = get_posts($args);

                if (count($has_transaction)) {
                    $transaction_id = $has_transaction[0]->ID;
                    $transaction_meta = get_post_meta($transaction_id);

                    if (!isset($stripe_secret_key) || empty($stripe_secret_key)) {
                        $stripe_secret_key = cs_get_option('stripe_mode') == 'sandbox' ? cs_get_option('stripe_sandbox_secret_key') : cs_get_option('stripe_production_secret_key');
                    }

                    \Stripe\Stripe::setApiKey($stripe_secret_key);

                    $token = $_POST['stripeToken'];

                    // Charge the user's card:
                    $charge = \Stripe\Charge::create(array(
                        "amount" => $_POST['amount'] * 100,
                        "currency" => "usd",
                        "description" => $_POST['name'],
                        "source" => $token,
                    ));

                    $arrayCharge = $charge->__toArray(true);

                    update_post_meta($transaction_id, '_transaction_status', $arrayCharge['status']);
                    update_post_meta($transaction_id, '_transaction_info', $arrayCharge);

                    if (isset($transaction_meta['_post_id'])) {
                        $post_id = $transaction_meta['_post_id'][0];
                        $post_data = array(
                            'ID' => $post_id,
                            'post_status' => 'publish',
                        );

                        wp_update_post($post_data);

                        $group_id = $transaction_meta['_group_id'][0];

                        $form_meta = get_post_meta($group_id);

                        if (isset($form_meta['_acf_form_builder_metabox'])) {
                            $form_settings = unserialize($form_meta['_acf_form_builder_metabox'][0]);
                            $expiry_days = $form_settings['expiry_time'] ? $form_settings['expiry_time'] : 0;
                            $vip_expiry_days = $form_settings['expiry_time_vip'] ? $form_settings['expiry_time_vip'] : 0;
                            $feature_expiry_days = $form_settings['expiry_time_feature'] ? $form_settings['expiry_time_feature'] : 0;

                            $expiry_time = time() + (24 * 60 * 60 * $expiry_days);
                            $vip_expiry_time = time() + (24 * 60 * 60 * $vip_expiry_days);
                            $feature_expiry_time = time() + (24 * 60 * 60 * $feature_expiry_days);

                            update_post_meta($post_id, '_expiry_time', $expiry_time);
                            update_post_meta($post_id, '_vip_expiry_time', $vip_expiry_time);
                            update_post_meta($post_id, '_feature_expiry_time', $feature_expiry_time);

                            $stripe_secret_key = $form_settings['stripe_mode'] == 'sandbox' ? $form_settings['stripe_sandbox_secret_key'] : $form_settings['stripe_production_secret_key'];
                        }

                    } elseif (isset($transaction_meta['_package_id'])) {
                        $package_id = $transaction_meta['_package_id'][0];
                        $package = get_post($package_id);
                        $package_meta = get_post_meta($package_id);
                        $package->meta = unserialize($package_meta['_acf_package_metabox'][0]);

                        $user_id = $transaction_meta['_user_id'][0];
                        $user_packages_data = get_user_meta($user_id, '_current_packages', true);

                        $user_packages_data = !empty($user_packages_data) && count($user_packages_data) ? $user_packages_data : [];
                        if (!$package->meta['is_addon']) {
                            $user_packages_data[$package_id] = array(
                                'package_id' => $package_id,
                                'number_of_posts' => $package->meta['number_of_posts'],
                                'number_of_posts_by_time' => $package->meta['max_post']['max_post_number'],
                                'unit_time' => $package->meta['max_post']['max_post_time'],
                                'expiry_time' => time() + ($package->meta['package_time'] * 60 * 60 * 24),
                                'current_time' => time(),
                            );

                            if ($package->meta['is_vip']) {
                                $user_packages_data[$package_id]['number_of_posts_vip'] = $package->meta['number_of_posts_vip'];
                                $user_packages_data[$package_id]['expiry_time_vip'] = $package->meta['expiry_time_vip'];
                            }

                            if ($package->meta['is_feature']) {
                                $user_packages_data[$package_id]['number_of_posts_feature'] = $package->meta['number_of_posts_feature'];
                                $user_packages_data[$package_id]['expiry_time_feature'] = $package->meta['expiry_time_feature'];
                            }

                        } else {
                            $parent_package = isset($user_packages_data[$package->meta['parent_package']]) ? $user_packages_data[$package->meta['parent_package']] : [];
                            if ($user_packages_data && $parent_package) {
                                $parent_package['number_of_posts'] += $package->meta['number_of_posts'];
                                if ($package->meta['is_vip']) {
                                    $parent_package['number_of_posts_vip'] += $package->meta['number_of_posts_vip'];
                                }

                                if ($package->meta['is_feature']) {
                                    $parent_package['number_of_posts_feature'] += $package->meta['number_of_posts_feature'];
                                }
                                $user_packages_data[$package->meta['parent_package']] = $parent_package;
                            }
                        }

                        update_user_meta($user_id, '_current_packages', $user_packages_data);
                    }
                }
            } catch (Exception $e) {
                die("Can't process payment");
            }
        }
    }

    public function process_free_package()
    {
        if (isset($_GET['package_id']) && isset($_GET['set_free_package'])) {
            if (is_user_logged_in()) {
                $package_id = $_GET['package_id'];
                $package = get_post($package_id);

                if ($package && $package->post_type == 'cats_packages') {
                    $package_meta = get_post_meta($package_id);
                    $package->meta = unserialize($package_meta['_acf_package_metabox'][0]);
                    if ($package->meta['package_price'] == 0) {
                        $user = wp_get_current_user();
                        $user_id = $user->ID;

                        $user_packages_data = get_user_meta($user_id, '_current_packages', true);

                        $user_packages_data = !empty($user_packages_data) && count($user_packages_data) ? $user_packages_data : [];

                        $user_packages_data[$package_id] = array(
                            'package_id' => $package_id,
                            'number_of_posts' => $package->meta['number_of_posts'],
                            'number_of_posts_by_time' => $package->meta['max_post']['max_post_number'],
                            'unit_time' => $package->meta['max_post']['max_post_time'],
                            'expiry_time' => time() + ($package->meta['package_time'] * 60 * 60 * 24),
                            'current_time' => time(),
                        );

                        if ($package->meta['is_vip']) {
                            $user_packages_data[$package_id]['number_of_posts_vip'] = $package->meta['number_of_posts_vip'];
                            $user_packages_data[$package_id]['expiry_time_vip'] = $package->meta['expiry_time_vip'];
                        }

                        if ($package->meta['is_feature']) {
                            $user_packages_data[$package_id]['number_of_posts_feature'] = $package->meta['number_of_posts_feature'];
                            $user_packages_data[$package_id]['expiry_time_feature'] = $package->meta['expiry_time_feature'];
                        }

                        update_user_meta($user_id, '_current_packages', $user_packages_data);

                        $_current_packages = get_user_meta($user_id, '_current_packages', true);
                    }
                }

            }
        }
    }

    public function process_init_request()
    {
        if (isset($_GET['transaction_secret'])) {
            if (!empty($_GET['transaction_secret'])) {
                $args = array(
                    'post_type' => 'cats_transactions',
                    'posts_per_page' => 1,
                    'post_status' => 'pending',
                    'meta_key' => '_transaction_secret',
                    'meta_value' => $_GET['transaction_secret'],
                );

                $has_transaction = get_posts($args);

                if (count($has_transaction)) {
                    $post_id = $has_transaction[0]->ID;

                    $post_data = array(
                        'ID' => $post_id,
                        'post_status' => 'publish',
                    );

                    wp_update_post($post_data);

                    //update package add on

                    unset($_SESSION['transaction_secret']);
                }
            }
        }
    }

    /**
     * Register the shortcode for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function acf_fb_register_shortcode()
    {
        add_shortcode('cats_form', array($this, 'acf_fb_register_form_shortcode_atts'));
        add_shortcode('cats_form_login', array($this, 'acf_fb_login_form_shortcode_atts'));
        add_shortcode('cats_edit_form', array($this, 'acf_fb_register_form_shortcode_atts'));
        add_shortcode('cats_checkout', array($this, 'acf_fb_register_checkout_shortcode_atts'));
        add_shortcode('cats_posts', array($this, 'acf_cats_posts_edit_table_shortcode_atts'));
        add_shortcode('cats_edit_current_post_link', array($this, 'acf_fb_edit_post_link_shortcode'));

        add_shortcode('cats_claim_current_post_button', array($this, 'claim_listing_button_shortcode'));
        add_shortcode('cats_package', array($this, 'acf_fb_register_package_shortcode_atts'));
        add_shortcode('cats_user_regist', array($this, 'acf_fb_register_user_regist_shortcode_atts'));
        add_shortcode('cats_user_edit', array($this, 'acf_fb_register_user_edit_shortcode_atts'));
        add_shortcode('cats_profile', array($this, 'acf_fb_register_profile_shortcode_atts'));
        add_shortcode('cats_user_packages', array($this, 'acf_fb_register_user_packages_shortcode_atts'));
        add_shortcode('cats_user_transaction', array($this, 'acf_fb_register_user_transaction_shortcode_atts'));

    }

    public function add_claim_listing_button($content)
    {
        if (is_single()) {
            $post_id = get_the_ID();
            $_acf_claim_listing_metabox = get_post_meta($post_id, '_acf_claim_listing_metabox', true);

            if ($_acf_claim_listing_metabox && $_acf_claim_listing_metabox['enable_claim_listing'] && $_acf_claim_listing_metabox['claim_listing_acf_form_id']) {
                $form_id = $_acf_claim_listing_metabox['claim_listing_acf_form_id'];
                $form_settings = get_post_meta($form_id, '_acf_form_builder_metabox', true);

                $edit_page_id = $form_settings['edit_page_id'];
                $edit_page_url = get_permalink($edit_page_id);

                $args = array(
                    'group_id' => $form_id,
                    'post_id' => get_the_ID(),
                );
                $claim_listing_url = add_query_arg($args, $edit_page_url);
                $add_claim_listing_button = '<a class="claim-listing-button" href="' . $claim_listing_url . '">Claim Listing</a>';
                $content = $add_claim_listing_button . $content;
            }
        }
        return $content;
    }

    public function claim_listing_button_shortcode()
    {
        if (is_single()) {
            $post_id = get_the_ID();
            $_acf_claim_listing_metabox = get_post_meta($post_id, '_acf_claim_listing_metabox', true);

            if ($_acf_claim_listing_metabox && $_acf_claim_listing_metabox['enable_claim_listing'] && $_acf_claim_listing_metabox['claim_listing_acf_form_id']) {
                $form_id = $_acf_claim_listing_metabox['claim_listing_acf_form_id'];
                $form_settings = get_post_meta($form_id, '_acf_form_builder_metabox', true);

                $edit_page_id = $form_settings['edit_page_id'];
                $edit_page_url = get_permalink($edit_page_id);

                $args = array(
                    'group_id' => $form_id,
                    'post_id' => get_the_ID(),
                );
                $claim_listing_url = add_query_arg($args, $edit_page_url);
                echo '<a class="claim-listing-button" href="' . $claim_listing_url . '">Claim Listing</a>';
            }
        }
    }

    public function add_acf_head()
    {
        if (function_exists('acf_form_head')) {
            acf_form_head();
        }

    }

    public function add_acf_enqueue_uploader()
    {
        if (function_exists('acf_enqueue_uploader')) {
            acf_enqueue_uploader();
        }

    }

    public function get_role_by_id($id)
    {

        if (!is_user_logged_in()) {
            return false;
        }
        $oUser = get_user_by('id', $id);
        $aUser = get_object_vars($oUser);
        $sRole = $aUser['roles'][0];
        return $sRole;
    }

    public function acf_cats_posts_edit_table_shortcode_atts($atts, $content = null)
    {

        $atts = shortcode_atts(
            array(
                'post_type' => '',
                'post_status' => '',
            ), $atts);

        if (!function_exists('acf_form')) {
            return false;
        }

        if (isset($atts['post_type'])) {
            $post_types = explode(',', $atts['post_type']);
        } else {
            $post_types = array('post');
        };

        if (isset($atts['post_status'])) {
            $post_status = explode(',', $atts['post_status']);
        } else {
            $post_status = array('publish', 'pending');
        };

        if (is_user_logged_in()) {
            // Show all post by $allowCaps
            $user = wp_get_current_user();
            $user_ids = [$user->ID];


            //prepare the args
            $args = array(
                'post_type' => $post_types,
                'post_status' => $post_status,
                'posts_per_page' => -1,
                'order' => 'DESC',
                'orderby' => 'post_date',
                'meta_key' => 'acf-form-builder_group_id',
                'author__in' => $user_ids,
            );

            if (current_user_can('edit_others_posts')
                || current_user_can('edit_others_pages')
                || current_user_can('moderate_comments')
            ) {
                $args['author__in'] = [];
            }

            if (isset($_GET['author'])) {
                $users = new WP_User_Query(array(
                    'search' => '*' . esc_attr($_GET['author']) . '*',
                    'search_columns' => array(
                        'user_login',
                        'user_nicename',
                        'user_email',
                        'user_url',
                    ),
                    'fields' => ['id']
                ));
                $users_found = $users->get_results();


                foreach ($users_found as $value) {
                    if (!in_array((int)$value->id, $args['author__in'])) {
                        $args['author__in'][] = (int)$value->id;
                    }
                }
            }

            //bdump($args, 'prepare the args');
            // the query

            if (isset($_GET['search'])) {
                $args['s'] = sanitize_text_field($_GET['search']);
            }

            $the_query = new WP_Query($args);
            //bdump($the_query, 'the query');

            $data['the_query'] = $the_query;
            $data['user_ids'] = $user_ids;
            wp_enqueue_script('data-tables-responsive-js', 'https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js');
            wp_enqueue_style('data-tables-responsive-css', 'https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css');
            return helper_get_template_part('public/partials/acf-form-builder', 'user-posts', $data);

        } else {
            ob_start();
            echo '<div style="background: #fff;border-left: 4px solid #fff;border-left-color: #dc3232;-webkit-box-shadow: 2px 2px 5px 0px rgba(204,204,204,0.55); -moz-box-shadow: 2px 2px 5px 0px rgba(204,204,204,0.55); box-shadow: 2px 2px 5px 0px rgba(204,204,204,0.55);"><p style="margin: 2.5em; padding: 2px;">Warning: Either you did not login or your account has no right to post from frontend. Please use the other account which can edit post to do that.</p></div>';
            wp_login_form();
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
    }

    public function acf_fb_register_package_shortcode_atts($atts, $content = null)
    {

        $atts = shortcode_atts(
            array(
                'id' => 'all',
                'style' => 'style-1',
                'column' => 1,
                'form_settings' => array(),
                'group_id' => 0,
            ), $atts);

        if (!function_exists('acf_form')) {
            return false;
        }

        $args = array(
            'post_type' => 'cats_packages',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );

        if ($atts['id'] != 'all') {
            $args['post__in'] = explode(',', $atts['id']);
        }

        $packages = new WP_Query($args);

        $form_global_settings = array(
            'checkout_page' => cs_get_option('checkout_page'),
            'subscription_style' => $atts['style'],
        );

        $group_id = isset($atts['group_id']) ? $atts['group_id'] : 0;
        $form_settings = count($atts['form_settings']) ? $atts['form_settings'] : $form_global_settings;

        $form_settings['redirect_after_checkout'] = cs_get_option('redirect_after_checkout');

        $form_settings['column'] = $atts['column'];

        $data = array(
            'packages' => $packages,
            'group_id' => $group_id,
            'form_settings' => $form_settings,
        );

        return helper_get_template_part('public/partials/acf-form-builder', 'package', $data);
    }

    public function acf_fb_register_checkout_shortcode_atts()
    {
        if (!function_exists('acf_form')) {
            return false;
        }

        if (isset($_GET['group_id'])) {
            $post_meta = get_post_meta($_GET['group_id']);

            if (isset($post_meta['_acf_form_builder_metabox'])) {
                $form_settings = unserialize($post_meta['_acf_form_builder_metabox'][0]);
            }

            if (is_user_logged_in()) {

                switch ($form_settings['redirect_after_submit']) {
                    case 'page':
                        $return_page = get_permalink($form_settings['redirect_after_submit_page']);
                        break;

                    case 'url':
                        $return_page = $form_settings['redirect_after_submit_url'];
                        break;

                    case 'newly_post_created':
                        $return_page = '%post_url%?updated=true';
                        break;
                    default:
                        $return_page = '';
                        break;
                }

                if (!isset($_SESSION['acf_return_page'])) {
                    $_SESSION['acf_return_page'] = $return_page;
                }

                if ($form_settings['redirect_after_submit'] == 'page' && !$form_settings['redirect_after_submit_page']) {
                    if (cs_get_option('redirect_after_checkout')) {
                        //using global redirect page
                        $_SESSION['acf_return_page'] = get_permalink(cs_get_option('redirect_after_checkout'));
                    } else {
                        //using woo redirect page
                        unset($_SESSION['acf_return_page']);
                    }
                }

                $user = wp_get_current_user();
                $transaction = 'TS-' . helper_get_lastest_post_ID() . '-' . time();
                $transaction_secret = md5($transaction);

                $has_transaction = array();

                if (isset($_SESSION['transaction_secret'])) {
                    $args = array(
                        'post_type' => 'cats_transactions',
                        'posts_per_page' => 1,
                        'author' => $user->data->ID,
                        'post_status' => 'pending',
                        'meta_key' => '_transaction_secret',
                        'meta_value' => $_SESSION['transaction_secret'],
                    );
                    $has_transaction = get_posts($args);
                }

                if (!count($has_transaction)) {
                    $post_data = array(
                        'post_title' => $transaction,
                        'post_type' => 'cats_transactions',
                        'post_status' => 'pending',
                        'post_author' => $user->data->ID,
                        'meta_input' => array(
                            '_group_id' => $_GET['group_id'],
                            '_transaction_secret' => $transaction_secret,
                            '_transaction_status' => __('Pending', ACF_FORM_BUILDER_TEXTDOMAIN),
                        ),
                    );

                    if (isset($_GET['post_id'])) {
                        $post_data['meta_input']['_post_id'] = $_GET['post_id'];
                        $post_data['meta_input']['_transaction_type'] = __('Pay per post', ACF_FORM_BUILDER_TEXTDOMAIN);
                        $post_data['meta_input']['_price'] = $form_settings['pay_per_post'];
                        $post_data['meta_input']['_product_name'] = '[' . $_GET['group_id'] . ']Pay per post';
                        $post_data['meta_input']['_product_id'] = $_GET['group_id'];

                        $post_types = get_post_meta($_GET['post_id'], ACF_FORM_BUILDER_TEXTDOMAIN . '_post_type', true);

                        if (is_array($post_types) && count($post_types)) {
                            $post_data['meta_input']['_post_types'] = $post_types;
                            foreach ($post_types as $key => $post_type) {
                                $post_data['meta_input']['_price_' . $post_type] = $form_settings['pay_per_post_' . $post_type];
                            }
                        }
                    } elseif (isset($_GET['package_id'])) {
                        $package = get_post($_GET['package_id']);
                        $package_meta = get_post_meta($_GET['package_id']);
                        $package->meta = unserialize($package_meta['_acf_package_metabox'][0]);
                        $post_data['meta_input']['_package_id'] = $_GET['package_id'];
                        $post_data['meta_input']['_user_id'] = $user->data->ID;
                        $post_data['meta_input']['_transaction_type'] = __('Subscription', ACF_FORM_BUILDER_TEXTDOMAIN);
                        $post_data['meta_input']['_price'] = $package->meta['package_price'];
                        $post_data['meta_input']['_product_name'] = $package->post_title;
                    }

                    $transaction_id = wp_insert_post($post_data, true);
                } else {
                    $transaction_id = $has_transaction[0]->ID;
                    $transaction = $has_transaction[0]->post_title;
                }

                if (!$transaction_id) {
                    return '<div class="acf_error">' . __("Sorry, Can't purchase this action for now. Please try again.") . '</div>';
                }

                if (!isset($_SESSION['transaction_secret'])) {
                    $_SESSION['transaction_secret'] = $transaction_secret;
                }

                $query['transaction_secret'] = $_SESSION['transaction_secret'];

                if (isset($_SESSION['form_page']) && isset($_GET['package_id'])) {
                    $return_page = $_SESSION['form_page'];
                }

                do_action('acf_register_checkout_action', $form_settings['form_type'], $form_settings);

                $data = array(
                    'form_settings' => $form_settings,
                    'data' => $_GET,
                    'return_page' => $return_page . '?' . http_build_query($query),
                    'user' => $user,
                    'transaction' => $transaction,
                );

                if (isset($_GET['post_id'])) {
                    $post_data = get_post_meta($_GET['post_id'], null, null);
                    $data['post_data'] = $post_data;
                    return helper_get_template_part('public/partials/acf-checkout', 'pay-per-post', $data);
                } elseif (isset($_GET['package_id'])) {
                    $package = get_post($_GET['package_id']);
                    $package_meta = get_post_meta($_GET['package_id']);
                    $package->meta = unserialize($package_meta['_acf_package_metabox'][0]);
                    $post_data['meta_input']['_package_id'] = $_GET['package_id'];
                    $post_data['meta_input']['_user_id'] = $user->data->ID;
                    $post_data['meta_input']['_transaction_type'] = __('Subscription', ACF_FORM_BUILDER_TEXTDOMAIN);
                    $post_data['meta_input']['_price'] = $package->meta['package_price'];
                    $post_data['meta_input']['_product_name'] = $package->post_title;
                    $data['package'] = $package;
                    return helper_get_template_part('public/partials/acf-checkout', 'subscription', $data);
                }
            } else {
                ob_start();
                echo '<div class="acf_error">' . $form_settings['unauthorization_error_text'] . '</div>';
                wp_login_form();
                $output = ob_get_contents();
                ob_end_clean();
                return $output;
            }

        } else {

            if (isset($_GET['package_id'])) {

                if (is_user_logged_in()) {

                    if (isset($_GET['package_id'])) {
                        $package_options_raw = get_post_meta($_GET['package_id'], '_acf_package_metabox');
                        $package_options = $package_options_raw[0];
                        $return_page = get_permalink($package_options['redirect_after_purchase_package']);
                    } else {
                        $return_page = get_permalink(cs_get_option('redirect_after_checkout'));
                    }

                    $user = wp_get_current_user();
                    $transaction = 'TS-' . helper_get_lastest_post_ID() . '-' . time();
                    $transaction_secret = md5(time() . '-' . $transaction);

                    $has_transaction = array();

                    if (isset($_SESSION['transaction_secret'])) {
                        $args = array(
                            'post_type' => 'cats_transactions',
                            'posts_per_page' => 1,
                            'author' => $user->data->ID,
                            'post_status' => 'pending',
                            'meta_key' => '_transaction_secret',
                            'meta_value' => $_SESSION['transaction_secret'],
                        );
                        $has_transaction = get_posts($args);
                    }

                    if (!count($has_transaction)) {
                        $post_data = array(
                            'post_title' => $transaction,
                            'post_type' => 'cats_transactions',
                            'post_status' => 'pending',
                            'post_author' => $user->data->ID,
                            'meta_input' => array(
                                '_transaction_secret' => $transaction_secret,
                                '_transaction_status' => __('Pending', ACF_FORM_BUILDER_TEXTDOMAIN),
                            ),
                        );

                        if (isset($_GET['package_id'])) {
                            $package = get_post($_GET['package_id']);
                            $package_meta = get_post_meta($_GET['package_id']);
                            $package->meta = unserialize($package_meta['_acf_package_metabox'][0]);
                            $post_data['meta_input']['_package_id'] = $_GET['package_id'];
                            $post_data['meta_input']['_user_id'] = $user->data->ID;
                            $post_data['meta_input']['_transaction_type'] = __('Subscription', ACF_FORM_BUILDER_TEXTDOMAIN);
                            $post_data['meta_input']['_price'] = $package->meta['package_price'];
                            $post_data['meta_input']['_product_name'] = $package->post_title;
                        }

                        $transaction_id = wp_insert_post($post_data, true);
                    } else {
                        $transaction_id = $has_transaction[0]->ID;
                    }

                    if (!$transaction_id) {
                        return '<div class="acf_error">' . __("Sorry, Can't purchase this action for now. Please try again.") . '</div>';
                    }

                    $_SESSION['transaction_secret'] = $transaction_secret;

                    $query['transaction_secret'] = $transaction_secret;

                    $form_settings = get_option('_cs_options');

                    do_action('acf_register_checkout_action', $form_settings['form_type'], $form_settings);

                    $data = array(
                        'form_settings' => $form_settings,
                        'data' => $_GET,
                        'return_page' => $return_page . '?' . http_build_query($query),
                        'user' => $user,
                        'transaction' => $transaction,
                    );

                    $package = get_post($_GET['package_id']);
                    $package_meta = get_post_meta($_GET['package_id']);
                    $package->meta = unserialize($package_meta['_acf_package_metabox'][0]);
                    $post_data['meta_input']['_package_id'] = $_GET['package_id'];
                    $post_data['meta_input']['_user_id'] = $user->data->ID;
                    $post_data['meta_input']['_transaction_type'] = __('Subscription', ACF_FORM_BUILDER_TEXTDOMAIN);
                    $post_data['meta_input']['_price'] = $package->meta['package_price'];
                    $post_data['meta_input']['_product_name'] = $package->post_title;
                    $data['package'] = $package;
                    return helper_get_template_part('public/partials/acf-checkout', 'subscription', $data);
                } else {
                    ob_start();
                    //echo '<div class="acf_error">' . $form_settings['unauthorization_error_text'] . '</div>';
                    wp_login_form();
                    $output = ob_get_contents();
                    ob_end_clean();
                    return $output;
                }

            }
        }
    }

    public function acf_fb_login_form_shortcode_atts($atts, $content = null)
    {
        wp_login_form();
    }

    public function acf_fb_register_form_shortcode_atts($atts, $content = null)
    {
        if (!function_exists('acf_form')) {
            return false;
        }

        $atts = shortcode_atts(
            array(
                'group_id' => isset($_GET['group_id']) ? $_GET['group_id'] : isset($atts['group_id']) ? $atts['group_id'] : 0,
                'post_id' => isset($atts['post_id']) ? $atts['post_id'] : isset($_GET['post_id']) ? $_GET['post_id'] : 0
            ), $atts);

        global $wp;
        $_SESSION['form_page'] = home_url(add_query_arg(array(), $wp->request));

        if (isset($_SESSION['transaction_secret'])) {
            unset($_SESSION['transaction_secret']);
        }

        if (isset($_GET['package_id'])) {
            $package_id = $_GET['package_id'];
            $user = wp_get_current_user();
            $user_id = $user->ID;
            $user_data = get_user_meta($user_id);
            $package = get_post($package_id);
            $package_meta = get_post_meta($package_id);
            $package->meta = unserialize($package_meta['_acf_package_metabox'][0]);
            if (!$package->meta['package_price']) {
                $user_packages_data = get_user_meta($user_id, '_current_packages', true);

                $user_packages_data = !empty($user_packages_data) && count($user_packages_data) ? $user_packages_data : [];

                $user_packages_data[$package_id] = array(
                    'package_id' => $package_id,
                    'number_of_posts' => $package->meta['number_of_posts'],
                    'number_of_posts_by_time' => $package->meta['max_post']['max_post_number'],
                    'unit_time' => $package->meta['max_post']['max_post_time'],
                    'current_time' => time(),
                );
            }
        }
        if (isset($atts['group_id'])) {
            $post_meta = get_post_meta($atts['group_id']);
            if (isset($post_meta['_acf_form_builder_metabox'])) {
                $form_settings = unserialize($post_meta['_acf_form_builder_metabox'][0]);
            }

            if ($form_settings['is_form']) {
                if ($form_settings['form_type'] == 'frontend_post_submission') {
                    return $this->process_frontend_post_submission($atts, $form_settings);
                } else {
                    return $this->process_custom_form($atts, $form_settings);
                }

            } else {
                return;
            }
        } else {
            return;
        }
    }

    public function process_custom_form($atts, $form_settings, $form = true)
    {
        switch ($form_settings['redirect_after_submit']) {
            case 'page':
                $return_page = get_permalink($form_settings['redirect_after_submit_page']);
                break;

            case 'url':
                $return_page = $form_settings['redirect_after_submit_url'];
                break;

            case 'newly_post_created':
                $return_page = '';
            default:
                $return_page = '';
                break;
        }

        $html_before_fields = wp_nonce_field(ACF_FORM_BUILDER_TEXTDOMAIN . '_' . $form_settings['form_type'], '_wpnonce_' . $form_settings['form_type'], true, false);
        $html_before_fields .= '<input type="hidden" name="group_id" value="' . $atts['group_id'] . '">';
        $html_before_fields .= '<input type="hidden" name="form_type" value="' . $form_settings['form_type'] . '">';

        $html_after_fields = '';

        if (is_user_logged_in()) {
            $author = wp_get_current_user();
            $author = $author->ID;
        } else {
            $author = isset($form_settings['default_author']) ? $form_settings['default_author'] : 1;
        }

        $form = apply_filters('acf_form_tag', true, $form_settings['form_type']);

        $options = array(
            /* (string) Unique identifier for the form. Defaults to 'acf-form' */
            'id' => 'custom-form',
            /* (int|string) The post ID to load data from and save data to. Defaults to the current post ID.
            Can also be set to 'new_post' to create a new post on submit */
            'post_id' => $atts['post_id'] ? $atts['post_id'] : 'new_post',
            /* (array) An array of post data used to create a post. See wp_insert_post for available parameters.
            The above 'post_id' setting must contain a value of 'new_post' */
            'new_post' => array(
                'post_type' => $form_settings['custom_post_type'],
                'post_status' => $form_settings['post_status_after_created'],
                'comment_status' => $form_settings['comment_status'],
                'post_author' => $author,
            ),
            /* (array) An array of field group IDs/keys to override the fields displayed in this form */
            'field_groups' => array($atts['group_id']),
            /* (array) An array of field IDs/keys to override the fields displayed in this form */
            'fields' => false,
            /* (boolean) Whether or not to show the post title text field. Defaults to false */
            'post_title' => isset($form_settings['show_post_title']) && $form_settings['show_post_title'] ? $form_settings['show_post_title'] : false,
            /* (boolean) Whether or not to show the post content editor field. Defaults to false */
            'post_content' => isset($form_settings['show_post_content_editor']) && $form_settings['show_post_content_editor'] ? $form_settings['show_post_content_editor'] : false,
            /* (boolean) Whether or not to create a form element. Useful when a adding to an existing form. Defaults to true */
            'form' => $form,
            /* (array) An array or HTML attributes for the form element */
            'form_attributes' => $form_settings['custom_form_attributes'],
            /* (string) The URL to be redirected to after the form is submit. Defaults to the current URL with a GET parameter '?updated=true'.
            A special placeholder '%post_url%' will be converted to post's permalink (handy if creating a new post) */
            'return' => $return_page,
            /* (string) Extra HTML to add before the fields */
            'html_before_fields' => $html_before_fields,
            /* (string) Extra HTML to add after the fields */
            'html_after_fields' => $html_after_fields,
            /* (string) The text displayed on the submit button */
            'submit_value' => isset($form_settings['submit_button_text']) && !empty($form_settings['submit_button_text']) ? $form_settings['submit_button_text'] : __('Submit', ACF_FORM_BUILDER_TEXTDOMAIN),
            /* (string) A message displayed above the form after being redirected. Can also be set to false for no message */
            'updated_message' => __("Post updated", ACF_FORM_BUILDER_TEXTDOMAIN),
            /* (string) Determines where field labels are places in relation to fields. Defaults to 'top'.
            Choices of 'top' (Above fields) or 'left' (Beside fields) */
            'label_placement' => isset($form_settings['field_label_placement']) ? $form_settings['field_label_placement'] : 'top',
            /* (string) Determines where field instructions are places in relation to fields. Defaults to 'label'.
            Choices of 'label' (Below labels) or 'field' (Below fields) */
            'instruction_placement' => isset($form_settings['field_instruction']) ? $form_settings['field_instruction'] : 'label',
            /* (string) Determines element used to wrap a field. Defaults to 'div'
            Choices of 'div', 'tr', 'td', 'ul', 'ol', 'dl' */
            'field_el' => isset($form_settings['field_html_wrapper']) ? $form_settings['field_html_wrapper'] : 'div',
            /* (boolean) Whether or not to sanitize all $_POST data with the wp_kses_post() function. Defaults to true. Added in v5.6.5 */
            'kses' => true,
            /* (string) Whether to use the WP uploader or a basic input for image and file fields. Defaults to 'wp'
            Choices of 'wp' or 'basic'. Added in v5.2.4 */
            'uploader' => isset($form_settings['upload_form_type']) ? $form_settings['upload_form_type'] : 'basic',

        );

        $data = array(
            'group_id' => $atts['group_id'],
            'options' => $options,
            'form_settings' => $form_settings,
        );
        if (is_user_logged_in()) {
            return helper_get_template_part('public/partials/acf-form-builder', 'frontend-post-submission', $data);
        } else {
            ob_start();
            do_action('acf_custom_action', $form_settings['form_type'], $form_settings);
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
    }

    public function process_frontend_post_submission($atts, $form_settings)
    {

        switch ($form_settings['redirect_after_submit']) {
            case 'page':
                $return_page = get_permalink($form_settings['redirect_after_submit_page']);
                break;

            case 'url':
                $return_page = $form_settings['redirect_after_submit_url'];
                break;

            case 'newly_post_created':
                $return_page = '%post_url%?updated=true';
                break;

            default:

                $return_page = '';
                break;
        }

        $html_before_fields = wp_nonce_field(ACF_FORM_BUILDER_TEXTDOMAIN . '_' . $form_settings['form_type'], '_wpnonce_' . $form_settings['form_type'], true, false);
        $html_before_fields .= '<input type="hidden" name="group_id" value="' . $atts['group_id'] . '">';
        if (isset($atts['post_id']) && $atts['post_id'] > 0) {
            $html_before_fields .= '<input type="hidden" name="action" value="edit">';
        }

        $html_before_fields .= '<input type="hidden" name="form_type" value="' . $form_settings['form_type'] . '">';
        $html_after_fields = '';

        apply_filters('acf_frontend_post_html_before_fields_form', $html_before_fields);

        if (is_user_logged_in()) {
            $author = wp_get_current_user();
            $author = $author->ID;
        } else {
            $author = isset($form_settings['default_author']) ? $form_settings['default_author'] : 1;
        }

        $form = apply_filters('acf_form_tag', true, $form_settings['form_type']);

        $options = array(
            /* (string) Unique identifier for the form. Defaults to 'acf-form' */
            'id' => 'frontend-post-submission-form',
            /* (int|string) The post ID to load data from and save data to. Defaults to the current post ID.
            Can also be set to 'new_post' to create a new post on submit */
            'post_id' => $atts['post_id'] ? $atts['post_id'] : 'new_post',
            /* (array) An array of post data used to create a post. See wp_insert_post for available parameters.
            The above 'post_id' setting must contain a value of 'new_post' */
            'new_post' => array(
                'post_type' => $form_settings['custom_post_type'],
                'post_status' => $form_settings['post_status_after_created'],
                'comment_status' => $form_settings['comment_status'],
                'post_author' => $author,
            ),
            /* (array) An array of field group IDs/keys to override the fields displayed in this form */
            'field_groups' => array($atts['group_id']),
            /* (array) An array of field IDs/keys to override the fields displayed in this form */
            'fields' => false,
            /* (boolean) Whether or not to show the post title text field. Defaults to false */
            'post_title' => isset($form_settings['show_post_title']) && $form_settings['show_post_title'] ? $form_settings['show_post_title'] : false,
            /* (boolean) Whether or not to show the post content editor field. Defaults to false */
            'post_content' => isset($form_settings['show_post_content_editor']) && $form_settings['show_post_content_editor'] ? $form_settings['show_post_content_editor'] : false,
            /* (boolean) Whether or not to create a form element. Useful when a adding to an existing form. Defaults to true */
            'form' => $form,
            /* (array) An array or HTML attributes for the form element */
            'form_attributes' => $form_settings['custom_form_attributes'],
            /* (string) The URL to be redirected to after the form is submit. Defaults to the current URL with a GET parameter '?updated=true'.
            A special placeholder '%post_url%' will be converted to post's permalink (handy if creating a new post) */
            'return' => $return_page,
            /* (string) Extra HTML to add before the fields */
            'html_before_fields' => $html_before_fields,
            /* (string) Extra HTML to add after the fields */
            'html_after_fields' => $html_after_fields,
            /* (string) The text displayed on the submit button */
            'submit_value' => isset($form_settings['submit_button_text']) && !empty($form_settings['submit_button_text']) ? $form_settings['submit_button_text'] : __('Submit', ACF_FORM_BUILDER_TEXTDOMAIN),
            /* (string) A message displayed above the form after being redirected. Can also be set to false for no message */
            'updated_message' => __("Post updated", ACF_FORM_BUILDER_TEXTDOMAIN),
            /* (string) Determines where field labels are places in relation to fields. Defaults to 'top'.
            Choices of 'top' (Above fields) or 'left' (Beside fields) */
            'label_placement' => isset($form_settings['field_label_placement']) ? $form_settings['field_label_placement'] : 'top',
            /* (string) Determines where field instructions are places in relation to fields. Defaults to 'label'.
            Choices of 'label' (Below labels) or 'field' (Below fields) */
            'instruction_placement' => isset($form_settings['field_instruction']) ? $form_settings['field_instruction'] : 'label',
            /* (string) Determines element used to wrap a field. Defaults to 'div'
            Choices of 'div', 'tr', 'td', 'ul', 'ol', 'dl' */
            'field_el' => isset($form_settings['field_html_wrapper']) ? $form_settings['field_html_wrapper'] : 'div',
            /* (boolean) Whether or not to sanitize all $_POST data with the wp_kses_post() function. Defaults to true. Added in v5.6.5 */
            'kses' => true,
            /* (string) Whether to use the WP uploader or a basic input for image and file fields. Defaults to 'wp'
            Choices of 'wp' or 'basic'. Added in v5.2.4 */
            'uploader' => isset($form_settings['upload_form_type']) ? $form_settings['upload_form_type'] : 'basic',
        );

        $data = array(
            'group_id' => $atts['group_id'],
            'options' => $options,
            'form_settings' => $form_settings,
        );

        switch ($form_settings['payment_type']) {
            case 'subscription':
                if (is_user_logged_in()) {
                    $user = wp_get_current_user();
                    $user_id = $user->ID;

                    $user_data = get_user_meta($user_id);

                    $current_packages = isset($user_data['_current_packages']) ? unserialize($user_data['_current_packages'][0]) : [];

                    $package_available = false;
                    if (count($current_packages)) {
                        foreach ($current_packages as $package_id => $user_package) {
                            if (in_array($user_package['package_id'], $form_settings['subscription']) && $user_package['expiry_time'] > time()) {
                                $is_vip = isset($user_package['number_of_posts_vip']) && $user_package['number_of_posts_vip'] ? $user_package['number_of_posts_vip'] : 0;
                                $is_feature = isset($user_package['number_of_posts_feature']) && $user_package['number_of_posts_feature'] ? $user_package['number_of_posts_feature'] : 0;

                                $html_after_fields = '<div class="custom-fields">';
                                $html_after_fields .= $is_vip ? '<input type="checkbox" id="vip_post" name="post_type[]" value="vip"> <label for="vip_post">' . __('VIP', ACF_FORM_BUILDER_TEXTDOMAIN) . '</label>' : '';
                                $html_after_fields .= $is_feature ? '<input type="checkbox" id="feature_post" name="post_type[]" value="feature"> <label for="feature_post">' . __('Feature', ACF_FORM_BUILDER_TEXTDOMAIN) . '</label>' : '';
                                $html_after_fields .= '</div>';

                                $data['options']['html_after_fields'] = $html_after_fields;

                                $current_time = isset($user_package['current_time']) ? $user_package['current_time'] : time();

                                $package_meta = get_post_meta($package_id);
                                $package_meta = unserialize($package_meta['_acf_package_metabox'][0]);

                                switch ($package_meta['max_post']['max_post_time']) {
                                    case 'week':
                                        $next_time = $current_time + (60 * 60 * 24 * 7);
                                        break;
                                    case 'month':
                                        $next_time = $current_time + (60 * 60 * 24 * 30);
                                        break;
                                    case 'year':
                                        $next_time = $current_time + (60 * 60 * 24 * 365);
                                        break;
                                    default:
                                        $next_time = $current_time + (60 * 60 * 24);
                                        break;
                                }

                                if (time() > $next_time) {
                                    $current_packages[$package_id]['current_time'] = $next_time;
                                    $current_packages[$package_id]['number_of_posts_by_time'] = $package_meta['max_post']['max_post_number'];
                                }

                                $package_available = $user_package['package_id'];
                                $data['user_package'] = $user_package;
                                break;
                            }
                        }
                    }

                    if ($package_available) {
                        update_user_meta($user_id, '_current_packages', $current_packages);
                        if ($user_package['number_of_posts_by_time'] > 0 && $user_package['number_of_posts'] > 0) {
                            return helper_get_template_part('public/partials/acf-form-builder', 'frontend-post-submission', $data);
                        } else {
                            return '<div class="acf_error">' . __('You reached max posts', ACF_FORM_BUILDER_TEXTDOMAIN) . '</div>';
                        }
                    } else {
                        $atts['id'] = implode(',', $form_settings['subscription']);
                        $atts['form_settings'] = $form_settings;

                        if ($form_settings['enable_payment'] == 1) {
                            return $this->acf_fb_register_package_shortcode_atts($atts);
                        } else {
                            return helper_get_template_part('public/partials/acf-form-builder', 'frontend-post-submission', $data);
                        }
                    }

                } else {
                    ob_start();
                    echo '<div class="acf_error">' . $form_settings['unauthorization_error_text'] . '</div>';
                    wp_login_form();
                    $output = ob_get_contents();
                    ob_end_clean();
                    return $output;
                }
                break;

            default:
                if ($form_settings['enable_guest_posting']) {
                    return helper_get_template_part('public/partials/acf-form-builder', 'frontend-post-submission', $data);
                } else {
                    if (is_user_logged_in()) {
                        $is_vip = isset($form_settings['is_vip']) && $form_settings['is_vip'] ? true : false;
                        $is_feature = isset($form_settings['is_feature']) && $form_settings['is_feature'] ? true : false;

                        $html_after_fields = '<div class="custom-fields">';
                        $html_after_fields .= $is_vip ? '<input type="checkbox" id="vip_post" name="post_type[]" value="vip"> <label for="vip_post">' . __('VIP', ACF_FORM_BUILDER_TEXTDOMAIN) . '</label>' : '';
                        $html_after_fields .= $is_feature ? '<input type="checkbox" id="feature_post" name="post_type[]" value="feature"> <label for="feature_post">' . __('Feature', ACF_FORM_BUILDER_TEXTDOMAIN) . '</label>' : '';
                        $html_after_fields .= '</div>';

                        $data['options']['html_after_fields'] = $html_after_fields;
                        return helper_get_template_part('public/partials/acf-form-builder', 'frontend-post-submission', $data);
                    } else {
                        ob_start();
                        echo '<div class="acf_error">' . $form_settings['unauthorization_error_text'] . '</div>';
                        wp_login_form();
                        $output = ob_get_contents();
                        ob_end_clean();
                        return $output;
                    }
                }
                break;
        }
    }

    public function acf_fb_register_user_packages_shortcode_atts()
    {
        if (!function_exists('acf_form')) {
            return false;
        }

        if (is_user_logged_in()) {
            $user_id = get_current_user_id();

            $data = [];

            $user_meta = get_user_meta($user_id);

            $acf_options = get_option('_cs_options');

            $data['acf_options'] = $acf_options;

            if (isset($user_meta['_current_packages'])) {
                $data['user_packages'] = unserialize($user_meta['_current_packages'][0]);
            }

            return helper_get_template_part('public/partials/acf-form-builder', 'user-packages', $data);
        }
    }

    public function acf_fb_register_user_transaction_shortcode_atts()
    {
        if (!function_exists('acf_form')) {
            return false;
        }

        if (is_user_logged_in()) {
            $user_id = get_current_user_id();

            $data = [];

            $args = array(
                'post_type' => 'cats_transactions',
                'posts_per_page' => -1,
                'author' => $user_id,
                'meta_key' => '_transaction_type',
                'meta_value' => 'Subscription',
            );

            $data['user_transactions'] = new WP_Query($args);

            return helper_get_template_part('public/partials/acf-form-builder', 'user-transactions', $data);
        }
    }

    public function acf_fb_register_profile_shortcode_atts()
    {
        if (!function_exists('acf_form')) {
            return false;
        }

        if (is_user_logged_in()) {
            $user_id = get_current_user_id();

            $data = [];
            $user_meta = get_user_meta($user_id);

            $user_profiles = [];

            if ($user_meta['acf_custom_user_fields'] && isset($user_meta['acf_form_id'])) {

                $acf_form_meta = get_post_meta($user_meta['acf_form_id'][0]);

                $acf_form_settings = isset($acf_form_meta['_acf_form_builder_metabox']) ? unserialize($acf_form_meta['_acf_form_builder_metabox'][0]) : [];

                if (isset($acf_form_settings['hide_custom_fields_frontend']) && !$acf_form_settings['hide_custom_fields_frontend']) {

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
                                    'value' => $value,
                                );
                                break;
                        }
                    }

                }

            }

            $data['user_data'] = get_userdata($user_id);
            $data['user_meta'] = $user_meta;
            $data['user_profiles'] = $user_profiles;

            return helper_get_template_part('public/partials/acf-form-builder', 'user-profile', $data);
        }
    }

    public function acf_fb_register_user_regist_shortcode_atts($atts)
    {
        if (!function_exists('acf_form')) {
            return false;
        }

        $atts = shortcode_atts(
            array(
                'group_id' => '',
                'post_id' => isset($atts['post_id']) ? $atts['post_id'] : isset($_GET['post_id']) ? $_GET['post_id'] : 0,
            ), $atts);

        if (isset($atts['group_id'])) {
            $post_meta = get_post_meta($atts['group_id']);
            if (isset($post_meta['_acf_form_builder_metabox'])) {
                $form_settings = unserialize($post_meta['_acf_form_builder_metabox'][0]);
            }

            add_filter('acf/load_value',
                function () {
                    return '';
                }
            );

            if ($form_settings['is_form']) {
                if ($form_settings['form_type'] == 'user_registeration') {
                    return $this->process_user_register_form($atts, $form_settings);
                }
            }
        }

        return false;
    }

    public function acf_fb_register_user_edit_shortcode_atts()
    {
        if (!function_exists('acf_form')) {
            return false;
        }

        if (is_user_logged_in()) {

            $user_id = get_current_user_id();

            $user_meta = get_user_meta($user_id);

            $group_id = isset($user_meta['acf_form_id']) ? $user_meta['acf_form_id'][0] : 0;

            if ($group_id) {

                $post_meta = get_post_meta($group_id);
                if (isset($post_meta['_acf_form_builder_metabox'])) {
                    $form_settings = unserialize($post_meta['_acf_form_builder_metabox'][0]);
                }

                if ($user_meta['acf_custom_user_fields']) {

                    $user_custom_fields = unserialize($user_meta['acf_custom_user_fields'][0]);

                    foreach ($user_custom_fields as $key => $value) {
                        add_filter('acf/load_field/key=' . $key,
                            function ($field) {
                                switch ($field['form_field_type']) {
                                    case 'username':
                                        $field['readonly'] = 1;
                                        break;

                                    case 'usermail':
                                        $field['readonly'] = 1;
                                        break;

                                    default:
                                        # code...
                                        break;
                                }

                                return $field;
                            }
                        );

                        add_filter('acf/load_value/key=' . $key,
                            function ($current_value, $post_id, $field) use ($value) {
                                switch ($field['form_field_type']) {
                                    case 'userpassword':
                                        $value = '';
                                        break;

                                    default:

                                        break;
                                }

                                return $value;

                            }, 10, 3
                        );

                    }

                }

                $atts['group_id'] = $group_id;

                if (isset($form_settings) && $form_settings['is_form']) {
                    if ($form_settings['form_type'] == 'user_registeration') {
                        return $this->process_user_register_form($atts, $form_settings, 'edit');
                    }
                }
            }
        }
    }

    public function process_user_register_form($atts, $form_settings, $action = 'add')
    {

        if ($action != 'edit') {
            switch ($form_settings['redirect_after_submit']) {
                case 'page':
                    $return_page = get_permalink($form_settings['redirect_after_submit_page']);
                    break;

                case 'url':
                    $return_page = $form_settings['redirect_after_submit_url'];
                    break;

                case 'newly_post_created':
                    $return_page = '';
                default:
                    $return_page = '';
                    break;
            }
        } else {
            $return_page = '';
        }

        $html_before_fields = wp_nonce_field(ACF_FORM_BUILDER_TEXTDOMAIN . '_' . $form_settings['form_type'], '_wpnonce_' . $form_settings['form_type'], true, false);
        $html_before_fields .= '<input type="hidden" name="group_id" value="' . $atts['group_id'] . '">';
        $html_before_fields .= '<input type="hidden" name="form_type" value="' . $form_settings['form_type'] . '">';
        $html_before_fields .= '<input type="hidden" name="action" value="' . $action . '">';

        $html_after_fields = '';

        $form = apply_filters('acf_user_form_tag', false, $form_settings['form_type']);

        $options = array(
            /* (string) Unique identifier for the form. Defaults to 'acf-form' */
            'id' => 'user-registeration',
            /* (array) An array of field group IDs/keys to override the fields displayed in this form */
            'field_groups' => array($atts['group_id']),
            /* (array) An array of field IDs/keys to override the fields displayed in this form */
            'fields' => false,
            /* (boolean) Whether or not to show the post title text field. Defaults to false */
            /* (boolean) Whether or not to create a form element. Useful when a adding to an existing form. Defaults to true */
            'form' => $form,
            /* (array) An array or HTML attributes for the form element */
            'form_attributes' => $form_settings['custom_form_attributes'],
            /* (string) The URL to be redirected to after the form is submit. Defaults to the current URL with a GET parameter '?updated=true'.
            A special placeholder '%post_url%' will be converted to post's permalink (handy if creating a new post) */
            'return' => $return_page,
            /* (string) Extra HTML to add before the fields */
            'html_before_fields' => $html_before_fields,
            /* (string) Extra HTML to add after the fields */
            'html_after_fields' => $html_after_fields,
            /* (string) The text displayed on the submit button */
            'submit_value' => isset($form_settings['submit_button_text']) && !empty($form_settings['submit_button_text']) ? $form_settings['submit_button_text'] : __('Submit', ACF_FORM_BUILDER_TEXTDOMAIN),
            /* (string) A message displayed above the form after being redirected. Can also be set to false for no message */
            'updated_message' => __("Post updated", ACF_FORM_BUILDER_TEXTDOMAIN),
            /* (string) Determines where field labels are places in relation to fields. Defaults to 'top'.
            Choices of 'top' (Above fields) or 'left' (Beside fields) */
            'label_placement' => isset($form_settings['field_label_placement']) ? $form_settings['field_label_placement'] : 'top',
            /* (string) Determines where field instructions are places in relation to fields. Defaults to 'label'.
            Choices of 'label' (Below labels) or 'field' (Below fields) */
            'instruction_placement' => isset($form_settings['field_instruction']) ? $form_settings['field_instruction'] : 'label',
            /* (string) Determines element used to wrap a field. Defaults to 'div'
            Choices of 'div', 'tr', 'td', 'ul', 'ol', 'dl' */
            'field_el' => isset($form_settings['field_html_wrapper']) ? $form_settings['field_html_wrapper'] : 'div',
            /* (boolean) Whether or not to sanitize all $_POST data with the wp_kses_post() function. Defaults to true. Added in v5.6.5 */
            'kses' => true,
            /* (string) Whether to use the WP uploader or a basic input for image and file fields. Defaults to 'wp'
            Choices of 'wp' or 'basic'. Added in v5.2.4 */
            'uploader' => isset($form_settings['upload_form_type']) ? $form_settings['upload_form_type'] : 'basic',
        );

        do_action('acf_user_register_form_action', $form_settings['form_type'], $form_settings);

        $data = array(
            'group_id' => $atts['group_id'],
            'options' => $options,
            'form_settings' => $form_settings,
        );

        return helper_get_template_part('public/partials/acf-form-builder', 'user-registeration', $data);
    }

    public function do_shortcode_content($content)
    {
        return do_shortcode($content);
    }

    public function acf_render_field($field)
    {
        if (isset($field['form_field_type'])) {
            switch ($field['form_field_type']) {
                case 'feature-image':

                    break;
                case 'category':
                    $args = [
                        'hide_empty' => false,
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'fields' => 'id=>name',
                    ];
                    $field['choices'] = get_categories($args);
                    break;

                case 'custom_taxonomy':

                    $args = [
                        'hide_empty' => false,
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'fields' => 'id=>name',
                    ];

                    if (!empty($field['custom_taxonomy'])) {
                        $field['choices'] = self::get_term_by_taxonomy($field['custom_taxonomy']);
                    }


                    break;

                case 'tag':
                    $args = [
                        'hide_empty' => false,
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'fields' => 'id=>name',
                    ];
                    $field['choices'] = get_tags($args);
                    break;

                case 'the_excerpt':

                    break;

                default:

                    break;
            }
        }
        return $field;
    }

    public function acf_pre_save_post($post_id)
    {
        //bdump([$post_id, $_POST], 'acf_before_save_post');
        if ($_POST['form_type'] != 'frontend_post_submission' && $_POST['form_type'] != 'user_registeration') {
            do_action('acf_custom_form_action', $_POST['form_type'], $_POST, $post_id);
            return;
        }
        return $post_id;
    }

    public function acf_before_save_post($post_id)
    {
        //bdump([$post_id, $_POST], 'acf_before_save_post');
        if (is_admin() || (isset($_POST['form_type']) && !isset($_POST['_wpnonce_' . $_POST['form_type']]) || !wp_verify_nonce($_POST['_wpnonce_' . $_POST['form_type']], ACF_FORM_BUILDER_TEXTDOMAIN . '_' . $_POST['form_type']))) {
            return;
        } else {
            if ($_POST['form_type'] == 'user_registeration') {
                $group_id = $_POST['group_id'];
                $post_meta = get_post_meta($_POST['group_id']);
                if (isset($post_meta['_acf_form_builder_metabox'])) {
                    $form_settings = unserialize($post_meta['_acf_form_builder_metabox'][0]);
                }

                $errors = [];
                $userdata = array();
                $usermeta = array();

                unset($_POST['acf']['_validate_email']);

                if (is_user_logged_in()) {
                    //global $current_user;
                    $current_user = wp_get_current_user();
                    $user_id = $current_user->ID;
                    foreach ($_POST['acf'] as $key => $value) {
                        $field_objects = get_field_object($key);
                        $usermeta[$key] = $value;
                        switch ($field_objects['form_field_type']) {
                            case 'username':
                                $usermeta[$key] = $current_user->user_login;
                                break;

                            case 'usermail':
                                $usermeta[$key] = $current_user->user_email;
                                break;

                            case 'userpassword':
                                $user_pass = sanitize_text_field($value);
                                $usermeta[$key] = wp_hash($value);
                                break;

                            case 'userfirstname':
                                $userdata['first_name'] = sanitize_text_field($value);

                                break;

                            case 'userlastname':
                                $userdata['last_name'] = sanitize_text_field($value);

                                break;

                            case 'usernicename':
                                $userdata['user_nicename'] = sanitize_text_field($value);

                                break;

                            case 'usernickname':
                                $userdata['nickname'] = sanitize_text_field($value);

                                break;

                            case 'userdescription':
                                $userdata['description'] = sanitize_text_field($value);

                                break;

                            case 'userurl':
                                $userdata['user_url'] = sanitize_text_field($value);

                                break;

                            default:

                                $usermeta[$key] = $value;
                                break;
                        }

                    }

                    if (isset($form_settings['require_password_to_update_profile']) && $form_settings['require_password_to_update_profile']) {

                        $user = wp_get_current_user();
                        if (isset($user_pass) && $user && wp_check_password($user_pass, $user->data->user_pass, $user_id)) {

                        } else {
                            $errors[] = 'Wrong password.';
                        }
                    }

                    if (count($errors)) {
                        $_SESSION['errors'] = $errors;
                        wp_redirect($_POST['_wp_http_referer']);
                        exit;
                    }

                    update_user_meta($user_id, 'acf_custom_user_fields', $usermeta);

                    if ($form_settings['update_user_notify_user']) {
                        $this->acf_fb_send_mail($form_settings, array(
                            'notify_type' => 'update_user_notify_admin',
                            'notify_to' => '',
                        ));
                    }
                    if ($form_settings['update_user_notify_admin']) {
                        $this->acf_fb_send_mail($form_settings, array(
                            'notify_type' => 'update_user_notify_admin',
                            'notify_to' => '',
                        ));
                    }

                } else {

                    foreach ($_POST['acf'] as $key => $value) {
                        $field_objects = get_field_object($key);
                        $usermeta[$key] = $value;
                        switch ($field_objects['form_field_type']) {
                            case 'username':
                                $is_valid = validate_username($value);
                                if ($is_valid) {
                                    $username = sanitize_user($value);
                                    if (username_exists($username)) {
                                        $errors[] = "Username In Use!";
                                    } else {
                                        $userdata['user_login'] = $username;
                                    }

                                } else {
                                    $errors[] = 'Username is not valid.';
                                }

                                break;

                            case 'usermail':
                                $is_valid = is_email($value);
                                if ($is_valid) {
                                    $email = sanitize_user($value);
                                    if (email_exists($email)) {
                                        $errors[] = "Email In Use!";
                                    } else {
                                        $userdata['user_email'] = sanitize_email($value);
                                    }

                                } else {
                                    $errors[] = 'Email is not valid.';
                                }

                                break;

                            case 'userpassword':
                                $userdata['user_pass'] = sanitize_text_field($value);
                                $usermeta[$key] = wp_hash($value);
                                break;

                            case 'userfirstname':
                                $userdata['first_name'] = sanitize_text_field($value);

                                break;

                            case 'userlastname':
                                $userdata['last_name'] = sanitize_text_field($value);

                                break;

                            case 'usernicename':
                                $userdata['user_nicename'] = sanitize_text_field($value);

                                break;

                            case 'usernickname':
                                $userdata['nickname'] = sanitize_text_field($value);

                                break;

                            case 'userdescription':
                                $userdata['description'] = sanitize_text_field($value);

                                break;

                            case 'userurl':
                                $userdata['user_url'] = sanitize_text_field($value);

                                break;

                            default:

                                $usermeta[$key] = $value;
                                break;
                        }

                    }

                    if (count($errors)) {
                        $_SESSION['errors'] = $errors;
//            dump($errors);exit;
                        wp_redirect($_POST['_wp_http_referer']);
                        exit;
                    }

                    $userdata['role'] = !empty($form_settings['user_role']) ? $form_settings['user_role'] : 'administration';

                    $user_id = wp_insert_user($userdata);

                    //On success
                    if (!is_wp_error($user_id)) {
                        add_user_meta($user_id, 'acf_custom_user_fields', $usermeta, true);
                        add_user_meta($user_id, 'acf_form_id', $group_id, true);
                        add_user_meta($user_id, 'acf_form_settings', $form_settings, true);

                        if ($form_settings['update_user_notify_user']) {
                            $this->acf_fb_send_mail($form_settings, array(
                                'notify_type' => 'update_user_notify_user',
                                'notify_to' => '',
                            ));
                        }
                        if ($form_settings['update_user_notify_admin']) {
                            $this->acf_fb_send_mail($form_settings, array(
                                'notify_type' => 'update_user_notify_admin',
                                'notify_to' => '',
                            ));
                        }

                    } else {
                        $errors[] = 'Create user failed.';
                        $_SESSION['errors'] = $errors;
//            dump($errors);exit;
                        wp_redirect($_POST['_wp_http_referer']);
                        exit;
                    }

                }

            }
            //bdump([$_POST['form_type'], $_POST, $post_id], 'acf_before_save_post_metadata');
            do_action('acf_before_save_post_metadata', $_POST['form_type'], $_POST, $post_id);
        }
    }

    public function acf_after_save_post($post_id)
    {
        if (is_admin() || !isset($_POST['form_type']) || !isset($_POST['_wpnonce_' . $_POST['form_type']]) || !wp_verify_nonce($_POST['_wpnonce_' . $_POST['form_type']], ACF_FORM_BUILDER_TEXTDOMAIN . '_' . $_POST['form_type'])) {
            return;
        } else {

            $post_meta = get_post_meta($_POST['group_id']);

            if (isset($post_meta['_acf_form_builder_metabox'])) {
                $form_settings = unserialize($post_meta['_acf_form_builder_metabox'][0]);
            }

            update_post_meta($post_id, ACF_FORM_BUILDER_TEXTDOMAIN . '_group_id', $_POST['group_id']);
            update_post_meta($post_id, ACF_FORM_BUILDER_TEXTDOMAIN . '_form_type', $_POST['form_type']);

            if (isset($_POST['post_type']) && count($_POST['post_type'])) {
                update_post_meta($post_id, ACF_FORM_BUILDER_TEXTDOMAIN . '_post_type', $_POST['post_type']);
            }

            $post_fields = get_field_objects($post_id);

            if (is_array($post_fields) && count($post_fields)) {

                foreach ($post_fields as $field) {
                    if (isset($field['form_field_type'])) {
                        switch ($field['form_field_type']) {
                            case 'meta_key':
                                update_post_meta($post_id, $field['input_meta_key'], $field['value']);
                                delete_post_meta($post_id, $field['name']);
                                delete_post_meta($post_id, '_' . $field['name']);

                                break;
                            case 'title':
                                $post_data = array(
                                    'ID' => $post_id,
                                    'post_title' => $field['value'],
                                );
                                wp_update_post($post_data);

                                break;

                            case 'feature-image':

                                switch ($field['return_format']) {
                                    case 'array':
                                        $post_feature_image = $field['value']['ID'];
                                        set_post_thumbnail($post_id, $post_feature_image);
                                        break;
                                    case 'id':
                                        $post_feature_image = $field['value'];
                                        set_post_thumbnail($post_id, $post_feature_image);
                                        break;

                                    default:

                                        break;
                                }
                                break;

                            case 'category':
                                $post_categories = $field['value'];
                                wp_set_post_categories($post_id, $post_categories);
                                break;

                            case 'custom_taxonomy':
                                $post_taxonomy = $field['value'];

                                if (!empty($post_taxonomy)) {
                                    $_term = get_term_by('term_id', $field['custom_taxonomy']);
                                    if (!is_wp_error($_term)) {
                                        wp_set_post_terms($post_id, $post_taxonomy, $field['custom_taxonomy']);
                                    }
                                }

                                break;

                            case 'tag':
                                $post_tag = $field['value'];
                                wp_set_post_tags($post_id, $post_tag);
                                break;

                            case 'content':
                                $post_content = array(
                                    'ID' => $post_id,
                                    'post_content' => $field['value'],
                                );
                                wp_update_post($post_content);
                                break;

                            case 'parent_page':
                                $post_page_parent_id = array(
                                    'ID' => $post_id,
                                    'parent_id' => $field['value'],
                                );
                                wp_update_post($post_page_parent_id);
                                break;

                            case 'the_excerpt':
                                $post_data = array(
                                    'ID' => $post_id,
                                    'post_excerpt' => $field['value'],
                                );
                                wp_update_post($post_data);
                                break;
                            default:

                                break;
                        }
                    }
                }

            }

            if (!isset($post_feature_image) || $post_feature_image == '') {
                set_post_thumbnail($post_id, $form_settings['default_feature_image']);
            }
            if (!isset($post_categories) || $post_categories == '') {
                wp_set_post_categories($post_id, $form_settings['default_category']);
            }

            if (!isset($_POST['action'])) {
                if ($form_settings['create_post_notify_user']) {
                    $this->acf_fb_send_mail($form_settings, array(
                        'notify_type' => 'create_post_notify_user',
                        'post_id' => $post_id,
                        'post_fields' => $post_fields,
                        'notify_to' => '',
                    ));
                }
                if ($form_settings['create_post_notify_admin']) {
                    $this->acf_fb_send_mail($form_settings, array(
                        'notify_type' => 'create_post_notify_admin',
                        'post_id' => $post_id,
                        'post_fields' => $post_fields,
                        'notify_to' => '',
                    ));
                }
            }

            //if(isset($_POST['action']) && $_POST['action'] == 'edit'){
            if (isset($_POST['action']) && $_POST['action'] == 'edit') {
                if ($form_settings['update_post_notify_user']) {
                    $this->acf_fb_send_mail($form_settings, array(
                        'notify_type' => 'update_post_notify_user',
                        'post_id' => $post_id,
                        'post_fields' => $post_fields,
                        'notify_to' => '',
                    ));
                }
                if ($form_settings['update_post_notify_admin']) {
                    $this->acf_fb_send_mail($form_settings, array(
                        'notify_type' => 'update_post_notify_admin',
                        'post_id' => $post_id,
                        'post_fields' => $post_fields,
                        'notify_to' => '',
                    ));
                }
                wp_redirect($_POST['_wp_http_referer']);
                exit;
            }

            if ($form_settings['enable_payment']) {
                if ($form_settings['payment_type'] == 'pay_per_post') {
                    $query = array(
                        'group_id' => $_POST['group_id'],
                        'post_id' => $post_id,
                    );
                    wp_redirect(get_permalink($form_settings['checkout_page']) . '?' . http_build_query($query));
                    exit;
                } else {
                    $user = wp_get_current_user();
                    $user_data = get_user_meta($user->ID);

                    $current_packages = isset($user_data['_current_packages']) ? unserialize($user_data['_current_packages'][0]) : [];

                    if (count($current_packages)) {
                        foreach ($current_packages as $package_id => $user_package) {
                            if (in_array($user_package['package_id'], $form_settings['subscription'])) {
                                if ($user_package['number_of_posts_by_time'] > 0 && $user_package['number_of_posts'] > 0) {
                                    $current_packages[$package_id]['number_of_posts_by_time'] = (int)$user_package['number_of_posts_by_time'] - 1;
                                    $current_packages[$package_id]['number_of_posts'] = (int)$user_package['number_of_posts'] - 1;
                                    if (isset($_POST['post_type']) && count($_POST['post_type'])) {
                                        foreach ($_POST['post_type'] as $key => $post_type) {
                                            if ($post_type == 'vip' && $user_package['number_of_posts_vip'] > 0) {
                                                $vip_expiry_days = isset($user_package['expiry_time_vip']) ? $user_package['expiry_time_vip'] : 0;
                                                $vip_expiry_time = time() + (24 * 60 * 60 * $vip_expiry_days);
                                                update_post_meta($post_id, '_vip_expiry_time', $vip_expiry_time);

                                                $current_packages[$package_id]['number_of_posts_vip'] = (int)$user_package['number_of_posts_vip'] - 1;
                                            }
                                            if ($post_type == 'feature' && $user_package['number_of_posts_feature'] > 0) {
                                                $feature_expiry_days = isset($user_package['expiry_time_feature']) ? $user_package['expiry_time_feature'] : 0;
                                                $feature_expiry_time = time() + (24 * 60 * 60 * $feature_expiry_days);
                                                update_post_meta($post_id, '_feature_expiry_time', $feature_expiry_time);

                                                $current_packages[$package_id]['number_of_posts_feature'] = (int)$user_package['number_of_posts_feature'] - 1;
                                            }
                                        }
                                    }
                                }

                                break;
                            }
                        }
                    }

                    update_user_meta($user->ID, '_current_packages', $current_packages);

                    $post_data = array(
                        'ID' => $post_id,
                        'post_status' => 'publish',
                    );

                    wp_update_post($post_data);
                }

                do_action('acf_after_save_post_metadata', $_POST['form_type'], $_POST, $post_id);

                if ($form_settings['redirect_after_submit'] == 'edit_page') {
                    $post_group_id = get_post_meta($post_id, '_group_id', true);
                    $edit_page_id = $form_settings['edit_page_id'];
                    $edit_page_url = get_the_permalink($edit_page_id);
                    wp_redirect($edit_page_url . '?post_id=' . $post_id . '&group_id=' . $post_group_id);
                    exit;
                }

                if ($form_settings['redirect_after_submit'] == 'newly_post_created') {
                    wp_redirect(get_the_permalink($post_id));
                    exit;
                }

            } else {

                if ($form_settings['redirect_after_submit'] == 'edit_page') {
                    $group_id = get_post_meta($post_id, 'acf-form-builder_group_id', true);
                    $edit_page_id = $form_settings['edit_page_id'];
                    $edit_page_url = get_the_permalink($edit_page_id);
                    wp_redirect($edit_page_url . '?post_id=' . $post_id . '&group_id=' . $group_id);
                    exit;
                }

                if ($form_settings['redirect_after_submit'] == 'newly_post_created') {
                    wp_redirect(get_the_permalink($post_id));
                    exit;
                }

            }
            //}
        }
    }

    public function check_user_meta()
    {
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            $user_meta = get_user_meta($user_id);

            if (isset($user_meta['acf_form_id'])) {
                $acf_form_meta = get_post_meta($user_meta['acf_form_id'][0]);

                $acf_form_settings = isset($acf_form_meta['_acf_form_builder_metabox']) ? unserialize($acf_form_meta['_acf_form_builder_metabox'][0]) : [];

                if (isset($acf_form_settings['hide_wp_admin']) && $acf_form_settings['hide_wp_admin']) {
                    show_admin_bar(false);
                }
            }
        }
    }

    // add a link to the WP Toolbar
    public function acf_fb_edit_post_link($wp_admin_bar)
    {

        $post_id = get_the_ID();
        $form_id = get_post_meta($post_id, 'acf-form-builder_group_id', true);
        if (isset($form_id)) {
            $form_settings = get_post_meta($form_id, '_acf_form_builder_metabox');
            $edit_page_id = $form_settings[0]['edit_page_id'];
            if (isset($edit_page_id)) {
                $edit_page_url = get_permalink($edit_page_id);
            }
            if (isset($edit_page_url)) {
                //build frontend edit post link
                $edit_link_arg = array(
                    'group_id' => $form_id,
                    'post_id' => $post_id,
                );
                $link_query = add_query_arg($edit_link_arg, $edit_page_url);
                //add the link to wp-admin bar
                $menu_node_args = array(
                    'id' => 'acf_fb_frontend_edit_link',
                    'title' => 'Frontend Edit',
                    'href' => $link_query,
                    'meta' => array(
                        'class' => 'acf_fb_frontend_edit_link',
                        'title' => 'Edit Current Post in Frontend',
                    ),
                );
                $wp_admin_bar->add_node($menu_node_args);
            }
        }
    }

    public function acf_fb_ajax_delete_post()
    {
        $return['status'] = 'failed';
        if (isset($_POST['data'])) {
            $data = $_POST['data'];

            if (current_user_can('delete_post', $data['post_id'])) {
                $status = wp_delete_post($data['post_id'], true);

                if ($status) {
                    $return['status'] = 'success';
                }
            }
        }
        die(json_encode($return));
    }

    //edit post shortcode - useful for template
    public function acf_fb_edit_post_link_shortcode()
    {

        $post_id = get_the_ID();
        $form_id = get_post_meta($post_id, 'acf-form-builder_group_id', true);
        if (isset($form_id)) {
            $form_settings = get_post_meta($form_id, '_acf_form_builder_metabox');
            $edit_page_id = $form_settings[0]['edit_page_id'];
            if (isset($edit_page_id)) {
                $edit_page_url = get_permalink($edit_page_id);
            }
            if (isset($edit_page_url)) {
                //build frontend edit post link
                $edit_link_arg = array(
                    'group_id' => $form_id,
                    'post_id' => $post_id,
                );
                $link_query = add_query_arg($edit_link_arg, $edit_page_url);
            }

            echo '<a href=' . $link_query . ' class="catsplugins_edit-current-post-link">Edit Post</a>';
        }
    }

    // ver 2.5
    public function acf_fb_send_mail($form_settings, $params = array())
    {
        /*$params = array(
        'notify_type'   => '',
        'post_id'       => '',
        'post_fields'   => '',
        'notify_to'     => '',
        );*/
        if ($form_settings['is_notify']) {
            $post_fields = null;
            $to = null;

            switch ($params['notify_type']) {
                case 'create_post_notify_user':
                    $subject = $form_settings['create_post_notify_user_subject'];
                    $body = $form_settings['create_post_notify_user_body'];
                    break;
                case 'create_post_notify_admin':
                    $subject = $form_settings['create_post_notify_admin_subject'];
                    $body = $form_settings['create_post_notify_admin_body'];
                    break;
                case 'update_post_notify_user':
                    $subject = $form_settings['update_post_notify_user_subject'];
                    $body = $form_settings['update_post_notify_user_body'];
                    break;
                case 'update_post_notify_admin':
                    $subject = $form_settings['update_post_notify_admin_subject'];
                    $body = $form_settings['update_post_notify_admin_body'];
                    break;
                case 'create_user_notify_user':
                    $subject = $form_settings['create_user_notify_user_subject'];
                    $body = $form_settings['create_user_notify_user_body'];
                    break;
                case 'create_user_notify_admin':
                    $subject = $form_settings['create_user_notify_admin_subject'];
                    $body = $form_settings['create_user_notify_admin_body'];
                    break;
                case 'update_user_notify_user':
                    $subject = $form_settings['update_user_notify_user_subject'];
                    $body = $form_settings['update_user_notify_user_body'];
                    break;
                case 'update_user_notify_admin':
                    $subject = $form_settings['update_user_notify_admin_subject'];
                    $body = $form_settings['update_user_notify_admin_body'];
                    break;
                default:
                    $subject = '';
                    $body = '';
                    break;
            }

            if ($subject && $body) {

                if ($params) {

                    $to = $params['notify_to'];

                    if (isset($params['post_fields']) && is_array($params['post_fields']) && count($params['post_fields'])) {
                        $post_fields = $params['post_fields'];
                    }

                    if (isset($params['post_id'])) {

                        $post = get_post($params['post_id']);

                        if (!$post_fields) {
                            $post_fields = get_field_objects($params['post_id']);
                        }

                        foreach ($post as $f => $val) {
                            if (is_array($val)) {
                                $body = preg_replace("/%$f%/", implode(',', $val), $body);
                                $subject = preg_replace("/%$f%/", implode(',', $val), $subject);
                            } else {
                                $body = preg_replace("/%$f%/", $val, $body);
                                $subject = preg_replace("/%$f%/", $val, $subject);
                            }
                        }
                    }

                    if (is_array($post_fields) && count($post_fields)) {
                        foreach ($post_fields as $k => $field) {
                            if (is_array($field['value'])) {
                                foreach ($field['value'] as $_k => $_val) {
                                    if (is_array($_val)) {
                                        $body = preg_replace("/%$k%/", implode(',', $_val), $body);
                                        $subject = preg_replace("/%$k%/", implode(',', $_val), $subject);
                                    } else {
                                        $body = preg_replace("/%$k%/", $_val, $body);
                                        $subject = preg_replace("/%$k%/", $_val, $subject);
                                    }
                                }
                            } else {
                                $body = preg_replace("/%$k%/", $field['value'], $body);
                                $subject = preg_replace("/%$k%/", $field['value'], $subject);
                            }
                        }
                    }
                }

                if ($form_settings['form_type'] == 'user_registeration') {
                    $current_user = wp_get_current_user();
                    $meta_fields = get_user_meta($current_user->ID);
                    if ($meta_fields) {
                        foreach ($meta_fields as $m => $mval) {
                            $body = preg_replace("/%$m%/", $mval[0], $body);
                            $subject = preg_replace("/%$m%/", $mval[0], $subject);
                        }
                    }
                    if ($current_user) {
                        foreach ($current_user as $p => $pval) {
                            if (!is_string($pval)) {
                                continue;
                            }
                            $body = preg_replace("/%$p%/", $pval, $body);
                            $subject = preg_replace("/%$p%/", $pval, $subject);
                        }
                    }
                }

                if (!$to) {
                    if ($params['notify_type'] == 'user_notify_admin' || $params['notify_type'] == 'post_notify_admin') {
                        $to = get_option('admin_email');
                    } else {
                        $current_user = wp_get_current_user();
                        $to = $current_user->user_email;
                    }
                }

                $headers = array('Content-Type: text/html; charset=UTF-8');

                wp_mail($to, $subject, $body, $headers);
            }
        }

    }

    // Woocommerce gateway
    public function acf_fb_insert_or_update_woo_product_item($package)
    {
        $super_admins = get_users('role=administrator');
        $id = '';
        global $wpdb;
        $posts = $wpdb->get_col($wpdb->prepare("select post_id from $wpdb->postmeta where meta_key = %s and meta_value = %s", '_is_package_product', $package['id']));

        if ($posts) {
            $id = $posts[0];
        }
        $post = array(
            'ID' => $id,
            'post_author' => $super_admins[0]->ID,
            'post_content' => '',
            'post_status' => "publish",
            'post_title' => $package['name'],
            'post_parent' => '',
            'post_type' => "product",
        );

        //Create post
        $post_id = wp_insert_post($post);

        wp_set_object_terms($post_id, 'simple', 'product_type');

        update_post_meta($post_id, '_visibility', 'visible');
        update_post_meta($post_id, '_stock_status', 'instock');
        update_post_meta($post_id, 'total_sales', '0');
        update_post_meta($post_id, '_downloadable', 'no');
        update_post_meta($post_id, '_virtual', 'yes');
        update_post_meta($post_id, '_regular_price', "1");
        update_post_meta($post_id, '_sale_price', "1");
        update_post_meta($post_id, '_purchase_note', "");
        update_post_meta($post_id, '_featured', "no");
        update_post_meta($post_id, '_weight', "");
        update_post_meta($post_id, '_length', "");
        update_post_meta($post_id, '_width', "");
        update_post_meta($post_id, '_height', "");
        update_post_meta($post_id, '_sku', "");
        update_post_meta($post_id, '_product_attributes', array());
        update_post_meta($post_id, '_sale_price_dates_from', "");
        update_post_meta($post_id, '_sale_price_dates_to', "");
        update_post_meta($post_id, '_price', $package['price']);
        update_post_meta($post_id, '_sold_individually', "");
        update_post_meta($post_id, '_manage_stock', "no");
        update_post_meta($post_id, '_backorders', "no");
        update_post_meta($post_id, '_stock', "");
        update_post_meta($post_id, '_is_package_product', $package['id']);

        return $post_id;
    }

    public function acf_woo_gateway_submit()
    {

        if (isset($_POST['acf_woo_gateway']) && isset($_SESSION['transaction_secret']) && is_user_logged_in()) {
            global $wpdb;
            $value = htmlspecialchars($_SESSION['transaction_secret']);

            $posts = $wpdb->get_col($wpdb->prepare("select post_id from $wpdb->postmeta where meta_key = %s and meta_value = %s", '_transaction_secret', $value));

            if ($posts) {
                $product_price = get_post_meta($posts[0], '_price', true);
                $product_name = get_post_meta($posts[0], '_product_name', true);
                $transaction_type = get_post_meta($posts[0], '_transaction_type', true);
                if ($transaction_type == 'Subscription') {
                    $relative_post_id = get_post_meta($posts[0], '_package_id', true);
                } elseif ($transaction_type == 'Pay per post') {
                    $relative_post_id = get_post_meta($posts[0], '_product_id', true);
                    $product_price = $_SESSION['transaction_price'];
                } else {
                    $relative_post_id = '';
                }
                if ($relative_post_id) {
                    $product_id = $this->acf_fb_insert_or_update_woo_product_item(array(
                        'id' => $relative_post_id,
                        'name' => $product_name,
                        'price' => $product_price,
                    ));
                    if ($product_id) {
                        //check if product already in cart
                        if (sizeof(WC()->cart->get_cart()) > 0) {
                            foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
                                $_product = $values['data'];
                                if ($_product->id == $product_id) {
                                    WC()->cart->remove_cart_item($cart_item_key);
                                }

                            }

                        }

                        WC()->cart->add_to_cart($product_id);
                        wp_redirect(get_permalink(wc_get_page_id('checkout')));
                    } else {
                        //product_id not found
                        wp_redirect(get_permalink(wc_get_page_id('checkout')));
                    }
                } else {
                    //relative_id not found
                    wp_redirect(get_permalink(wc_get_page_id('checkout')));
                }
            }
            exit;
        }
    }

    public function acf_woo_gateway_update_payment_menthod_in_checkout_page($checkout)
    {
//        if(isset($_SESSION['acf_woo_gateway'])) {
        //            WC()->session->set( 'chosen_payment_method', htmlspecialchars($_SESSION['acf_woo_gateway']) );
        //        }
    }

    public function acf_woo_gateway_update_payment_menthod_in_validate_checkout($posted_data, $errors)
    {
//        if(isset($_SESSION['acf_woo_gateway'])) {
        //            $posted_data['payment_method'] = htmlspecialchars($_SESSION['acf_woo_gateway']);
        //        }
    }

    public function acf_woo_gateway_change_transaction_status($order_id, $status)
    {
        $transaction_secret = get_post_meta($order_id, '_order_transaction_secret', true);
        if ($transaction_secret) {
            if ('publish' == $status) {
                $this->acf_woo_gateway_transaction_publish($transaction_secret);
            } else {
                $this->acf_woo_gateway_transaction_draft($transaction_secret, $status);
            }
        }
    }

    public function acf_woo_gateway_payment_completed($status, $order_id)
    {
        $transaction_secret = get_post_meta($order_id, '_order_transaction_secret', true);
        if (!$transaction_secret && isset($_SESSION['transaction_secret']) && $_SESSION['transaction_secret']) {
            update_post_meta($order_id, '_order_transaction_secret', $_SESSION['transaction_secret']);
            global $wpdb;
            $posts = $wpdb->get_col($wpdb->prepare("select post_id from $wpdb->postmeta where meta_key = %s and meta_value = %s", '_transaction_secret', $_SESSION['transaction_secret']));
            if ($posts) {
                update_post_meta($posts[0], '_acf_fb_order_id', $order_id);
            }
        }
        unset($_SESSION['transaction_secret']);
    }

    public function acf_woo_gateway_order_completed($order_id)
    {
        $this->acf_woo_gateway_change_transaction_status($order_id, 'publish');
    }

    public function acf_woo_gateway_order_draft($order_id)
    {
        $this->acf_woo_gateway_change_transaction_status($order_id, 'draft');
    }

    public function acf_woo_gateway_order_pending($order_id)
    {
        $this->acf_woo_gateway_change_transaction_status($order_id, 'pending');
    }

    public function acf_woo_gateway_redirect_page($order_id)
    {

        if (cs_get_option('enable_woo_gateway') && isset($_SESSION['acf_return_page']) && $_SESSION['acf_return_page']) {
            $order = new WC_Order($order_id);
            $url = $_SESSION['acf_return_page'];
            if ($order->status != 'failed') {
                unset($_SESSION['acf_return_page']);
                wp_redirect($url);
                exit;
            }
        }
    }

    public function acf_woo_gateway_transaction_publish($transaction_secret)
    {
        global $wpdb;
        $post = $wpdb->get_col($wpdb->prepare("
              select pm.post_id
              from $wpdb->postmeta pm join $wpdb->posts p on pm.post_id = p.ID
              where meta_key = %s
                and meta_value = %s
                and p.post_type = %s
              ", '_transaction_secret', $transaction_secret, 'cats_transactions'));

        if ($post) {

            $post_data = array(
                'ID' => $post[0],
                'post_status' => 'publish',
            );

            wp_update_post($post_data);

            $transaction_id = $post[0];
            $transaction_meta = get_post_meta($transaction_id);

            update_post_meta($transaction_id, '_transaction_status', 'publish');

            if (isset($transaction_meta['_post_id'])) {
                $post_id = $transaction_meta['_post_id'][0];
                $post_data = array(
                    'ID' => $post_id,
                    'post_status' => 'publish',
                );

                wp_update_post($post_data);

                $group_id = $transaction_meta['_group_id'][0];

                $form_meta = get_post_meta($group_id);

                if (isset($form_meta['_acf_form_builder_metabox'])) {
                    $form_settings = unserialize($form_meta['_acf_form_builder_metabox'][0]);
                    $expiry_days = $form_settings['expiry_time'] ? $form_settings['expiry_time'] : 0;
                    $vip_expiry_days = $form_settings['expiry_time_vip'] ? $form_settings['expiry_time_vip'] : 0;
                    $feature_expiry_days = $form_settings['expiry_time_feature'] ? $form_settings['expiry_time_feature'] : 0;

                    $expiry_time = time() + (24 * 60 * 60 * $expiry_days);
                    $vip_expiry_time = time() + (24 * 60 * 60 * $vip_expiry_days);
                    $feature_expiry_time = time() + (24 * 60 * 60 * $feature_expiry_days);

                    update_post_meta($post_id, '_expiry_time', $expiry_time);
                    update_post_meta($post_id, '_vip_expiry_time', $vip_expiry_time);
                    update_post_meta($post_id, '_feature_expiry_time', $feature_expiry_time);
                }

            } elseif (isset($transaction_meta['_package_id'])) {
                $package_id = $transaction_meta['_package_id'][0];
                $package = get_post($package_id);
                $package_meta = get_post_meta($package_id);
                $package->meta = unserialize($package_meta['_acf_package_metabox'][0]);

                $user_id = $transaction_meta['_user_id'][0];
                $user_packages_data = get_user_meta($user_id, '_current_packages', true);

                $user_packages_data = !empty($user_packages_data) && count($user_packages_data) ? $user_packages_data : [];

                if (!$package->meta['is_addon']) {
                    $user_packages_data[$package_id] = array(
                        'package_id' => $package_id,
                        'number_of_posts' => $package->meta['number_of_posts'],
                        'number_of_posts_by_time' => $package->meta['max_post']['max_post_number'],
                        'unit_time' => $package->meta['max_post']['max_post_time'],
                        'expiry_time' => time() + ($package->meta['package_time'] * 60 * 60 * 24),
                        'current_time' => time(),
                    );

                    if ($package->meta['is_vip']) {
                        $user_packages_data[$package_id]['number_of_posts_vip'] = $package->meta['number_of_posts_vip'];
                        $user_packages_data[$package_id]['expiry_time_vip'] = $package->meta['expiry_time_vip'];
                    }

                    if ($package->meta['is_feature']) {
                        $user_packages_data[$package_id]['number_of_posts_feature'] = $package->meta['number_of_posts_feature'];
                        $user_packages_data[$package_id]['expiry_time_feature'] = $package->meta['expiry_time_feature'];
                    }
                } else {
                    $parent_package = isset($user_packages_data[$package->meta['parent_package']]) ? $user_packages_data[$package->meta['parent_package']] : [];
                    if ($user_packages_data && $parent_package) {
                        $parent_package['number_of_posts'] += $package->meta['number_of_posts'];
                        if ($package->meta['is_vip']) {
                            $parent_package['number_of_posts_vip'] += $package->meta['number_of_posts_vip'];
                        }

                        if ($package->meta['is_feature']) {
                            $parent_package['number_of_posts_feature'] += $package->meta['number_of_posts_feature'];
                        }
                        $user_packages_data[$package->meta['parent_package']] = $parent_package;
                    }
                }

                update_user_meta($user_id, '_current_packages', $user_packages_data);
            }
        }
    }

    public function acf_woo_gateway_transaction_draft($transaction_secret, $status)
    {
        global $wpdb;
        $post = $wpdb->get_col($wpdb->prepare("
              select pm.post_id
              from $wpdb->postmeta pm join $wpdb->posts p on pm.post_id = p.ID
              where meta_key = %s
                and meta_value = %s
                and p.post_type = %s
              ", '_transaction_secret', $transaction_secret, 'cats_transactions'));
        if ($post) {
            $post_data = array(
                'ID' => $post[0],
                'post_status' => $status,
            );

            wp_update_post($post_data);

            $transaction_id = $post[0];
            $transaction_meta = get_post_meta($transaction_id);

            update_post_meta($transaction_id, '_transaction_status', $status);

            if (isset($transaction_meta['_post_id'])) {
                $post_id = $transaction_meta['_post_id'][0];
                $post_data = array(
                    'ID' => $post_id,
                    'post_status' => 'draft',
                );

                wp_update_post($post_data);

            }
        }
    }
}