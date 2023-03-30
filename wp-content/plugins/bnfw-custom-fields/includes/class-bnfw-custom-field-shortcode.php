<?php

/**
 * BNFW Custom Field Shortcode handler.
 */
class BNFW_Custom_Field_Shortcode {

    /**
     * Constructor.
     *
     * @since 1.0
     */
    function __construct() {
        $this->hooks();
    }

    /**
     * Factory method to return the instance of the class.
     *
     * Makes sure that only one instance is created.
     *
     * @return object Instance of the class
     */
    public static function factory() {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Setup hooks.
     *
     * @since 1.0
     */
    public function hooks() {
        add_filter('bnfw_shortcodes_post', array($this, 'post_custom_fields_shortcode'), 10, 2);
        add_filter('bnfw_shortcodes_post_meta', array($this, 'post_custom_fields_shortcode'), 10, 2);

        add_filter('bnfw_shortcodes_user', array($this, 'user_custom_fields_shortcode'), 10, 3);

        add_filter('bnfw_from_field', array($this, 'handle_shortcodes_in_from_field'), 10, 4);
        add_filter('bnfw_reply_name_field', array($this, 'handle_shortcodes_in_reply_name_field'), 10, 4);
        add_filter('bnfw_reply_email_field', array($this, 'handle_shortcodes_in_reply_email_field'), 10, 4);

        //look a way to get the meta after publishing in gutenberge and resend the email for all custom_post_types
        if(class_exists('ACF')){
            //call only the fix if gutenberg is active
            add_filter('bnfw_notification_disabled',array($this,'halt_email_if_has_custom_fields'), 10, 3);
            add_action('acf/save_post', array($this,'resend_email_after_pending'));
        }

    }

    /**
     * 
     * 
     * Gutenberg fixes custom fields not getting the value right after the API request
     * Check email if for custom fields, if positive cancel the email and prepare to resend
     * the real value of custom fields
     * 
     */
    public function halt_email_if_has_custom_fields($status, $id, $settings){


        $bnfw = BNFW::factory();
        if(!$bnfw->is_gutenberg_active())
            return $status;

        $result = array();

        /**
         * 
         * get shortcode regex pattern wordpress function
         * look for shortcode inside []
         * 
         */

        $has_shortcode = 0;

        if(isset($settings['resend'])){
            return $status;
        }
            
        if(preg_match_all("/\[[^\]]*\]/",$settings['message'],$result)){
            if(!empty($result[0])){
                //check if shorcode is custom_field
                foreach($result[0] as $shortcode){
                    if(strpos($shortcode, 'custom_field') !== false){
                        $has_shortcode++;
                    }
                }
            }
            if($has_shortcode > 0){
                set_transient('bnfw_notification_type_delay',$settings['notification']);
                return true;
            }
        }
        return $status;
    }


    /**
     * 
     * 
     * After the notification is cancelled resend the pending email with actual meta data records
     * 
     * 
     */
    public function resend_email_after_pending($post_id){
        $bnfw = BNFW::factory();
        if($bnfw->is_gutenberg_active()){
            $post_type = get_transient('bnfw_notification_type_delay');
            $notifications = $bnfw->notifier->get_notifications( $post_type , true);
            foreach ( $notifications as $notification ) {
                $settings = $bnfw->notifier->read_settings( $notification->ID );
                $settings['resend'] = true;
                $bnfw->engine->send_notification( $settings, $post_id );
            }
            delete_transient('bnfw_notification_type_delay');
        }
    }

    

    /**
     * Handle post custom fields shortcode.
     *
     * @since 1.0
     */
    public function post_custom_fields_shortcode($message, $id) {
        add_shortcode('custom_field', array($this, 'custom_field_shortcode_handler'));
        $message = str_replace('[custom_field', '[custom_field id="' . $id . '"', $message);
        $message = do_shortcode($message);
        remove_shortcode('custom_field', array($this, 'custom_field_shortcode_handler'));

        $message = strip_shortcodes($message);

        return $message;
    }

    /**
     * Custom fields shortcode handler.
     *
     * @since 1.0
     */
    public function custom_field_shortcode_handler($atts) {
        $atts = shortcode_atts(
                array(
                    'field' => '',
                    'id' => 0,
                    'type' => '',
                    'format' => '',
                    'prefix' => '',
                ),
                $atts
        );

        $post_id = absint($atts['id']);

        if (0 === $post_id) {
            return '';
        }

        $value = get_post_meta($post_id, $atts['field'], true);

        if (empty($value)) {
            return '';
        }

        return $atts['prefix'] . $this->format_meta_value($value, $atts['type'], $atts['format']);
    }

    /**
     * Format meta value.
     *
     * @param array|string $value Meta value.
     * @param string $type Date type.
     * @param string $format Additional format.
     *
     * @return string Formatted meta value.
     */
    protected function format_meta_value($value, $type, $format) {
        if (empty($type) || 'array' === $type) {
            if (empty($format)) {
                $format = ', ';
            }

            return implode($format, (array) $value);
        }

        if (is_array($value)) {
            $value = $value[0];
        }

        if ('url' === $type) {
            if (empty($format)) {
                $format = $value;
            }

            return '<a href="' . $value . '">' . $format . '</a>';
        }

        if ('attachment-id' === $type) {
            if (empty($format)) {
                $format = 'url';
            }

            if ('image' === $format) {
                return wp_get_attachment_image($value);
            }

            return wp_get_attachment_url($value);
        }

        if ('timestamp' === $type) {
            if (empty($format)) {
                $format = get_option('date_format');
            }

            return date($format, $value);
        }

        if ('date' === $type) {
            if (empty($format)) {
                $format = get_option('date_format');
            }

            return date($format, strtotime($value));
        }

        return $value;
    }

    /**
     * Handle user custom fields shortcode.
     *
     * @since 1.1
     */
    public function user_custom_fields_shortcode($message, $id, $prefix) {
        add_shortcode($prefix . 'user_custom_field', array($this, 'user_custom_field_shortcode_handler'));
        $message = str_replace('[' . $prefix . 'user_custom_field', '[' . $prefix . 'user_custom_field id="' . $id . '"', $message);
        $message = do_shortcode($message);
        remove_shortcode($prefix . 'user_custom_field', array($this, 'user_custom_field_shortcode_handler'));

        $message = strip_shortcodes($message);

        return $message;
    }

    /**
     * User custom fields shortcode handler.
     *
     * @since 1.1
     */
    public function user_custom_field_shortcode_handler($atts) {
        $atts = shortcode_atts(array(
            'field' => '',
            'id' => 0,
                ), $atts);

        $user_id = absint($atts['id']);
        $value = '';

        if ($user_id > 0) {
            $value = get_user_meta($user_id, $atts['field'], true);
        }

        if (is_array($value)) {
            return implode(', ', $value);
        }

        return $value;
    }

    public function handle_shortcodes_in_from_field($default, $setting, $id, $to_email) {
        if (empty($setting['from-name']) || empty($setting['from-email'])) {
            return $default;
        }

        $bnfw = BNFW::factory();

        $from_email = $setting['from-email'];
        if (!is_email($from_email)) {
            $from_email = $bnfw->engine->process_shortcodes_in_email($from_email, $id, $setting, $to_email);
        }

        if (!is_email($from_email)) {
            return $default;
        }

        $from_name = $bnfw->engine->handle_shortcodes($setting['from-name'], $setting['notification'], $id);

        return $from_name . ' <' . $from_email . '>';
    }

    public function handle_shortcodes_in_reply_name_field($default, $setting, $id, $to_email) {
        if (empty($setting['reply-name'])) {
            return $default;
        }

        $bnfw = BNFW::factory();

        return $bnfw->engine->handle_shortcodes($setting['reply-name'], $setting['notification'], $id);
    }

    public function handle_shortcodes_in_reply_email_field($default, $setting, $id, $to_email) {
        if (empty($setting['reply-email'])) {
            return $default;
        }

        if (is_email($setting['reply-email'])) {
            return $default;
        }

        $bnfw = BNFW::factory();

        return $bnfw->engine->process_shortcodes_in_email($setting['reply-email'], $id, $setting, $to_email);
    }

}
