<?php

function helper_get_template_part($path, $name, $vars = array(), $echo = false, $require_once = false) {
	$view = ACF_FORM_BUILDER_PLUGIN_PATH . '/' . $path . '-' . $name . '.php';
	extract($vars);
	if (!$echo) {
		ob_start();
		if ($require_once) {
			include_once $view;
		} else {
			include $view;
		}

		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	} else {
		if ($require_once) {
			include_once $view;
		} else {
			include $view;
		}

	}
}

function helper_get_lastest_post_ID() {
	global $wpdb;

	$query = "SELECT MAX(id) as 'lastest_id' FROM " . $wpdb->posts;

	$result = $wpdb->get_row($query);

	return $result->lastest_id;
}

add_action('acf/save_post', 'get_post_id_after_submit_post');

function get_post_id_after_submit_post( $post_id ) {
	
	return $post_id;
	
}