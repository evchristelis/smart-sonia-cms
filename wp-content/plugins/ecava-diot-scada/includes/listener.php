<?php

/* listener.php */

// Exit if accessed directly. info: ABSPATH should be defined in wp-config and would not be defined if file was directly accessed rather than going through wordpress
if ( ! defined( 'ABSPATH' ) ) exit;

function ecava_diot_scada_listener_handler() {
	if (isset($_GET['ecava_action']) && $_GET['ecava_action'] == 'mqtt_settings') {
		$options = get_option(ECAVA_DIOT_SCADA_MQTT_SETTINGS_OPTIONS);
		$json = array();
		$json['server'] = $options['server'];
		$json['port'] = $options['port'];
		$json['client_id'] = $options['client_id'];
		echo json_encode($json);
		exit(0);
	}
}
add_action('init', 'ecava_diot_scada_listener_handler');