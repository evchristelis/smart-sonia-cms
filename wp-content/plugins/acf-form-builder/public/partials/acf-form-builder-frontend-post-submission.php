<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://support.catsplugins.com
 * @since      1.0.0
 *
 * @package    Acf_Form_Builder
 * @subpackage Acf_Form_Builder/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
$user = wp_get_current_user();
//$user_role = $user->roles[0];

do_action('acf_fb_before_render_form', $group_id, $options, $form_settings);

if((isset($form_settings['enable_guest_posting']) && $form_settings['enable_guest_posting'] == 0) 
	&& ((isset($form_settings['allow_roles_posting']) && count($form_settings['allow_roles_posting'])) || (isset($form_settings['allow_users_posting']) && count($form_settings['allow_users_posting'])))
	){
		if(isset($form_settings['allow_users_posting']) && count($form_settings['allow_users_posting']) && in_array($user->ID, $form_settings['allow_users_posting'])){
			acf_form($options);	
		}elseif(isset($form_settings['allow_roles_posting']) && count($form_settings['allow_roles_posting']) && in_array($user_role, $form_settings['allow_roles_posting'])){
			acf_form($options);	
		}else{
			echo '<p>' . $form_settings['unauthorization_error_text'] . '</p>';
		}
}else{
	acf_form($options);	
}

do_action('acf_fb_after_render_form', $group_id, $options, $form_settings);