<?php

// scripts.php

// Exit if accessed directly. info: ABSPATH should be defined in wp-config and would not be defined if file was directly accessed rather than going through wordpress
if ( ! defined( 'ABSPATH' ) ) exit;

function ecava_diot_scada_register_scripts() {
	// Register MQTT Client (Paho)
	$mqtt_client_vers  = date("ymd-Gis", filemtime( ECAVA_DIOT_SCADA_DIR . "js/mqttws31.js" ));
	wp_register_script('ecava_diot_scada_mqtt_client_library', ECAVA_DIOT_SCADA_URL . "js/mqttws31.js", array(), $mqtt_client_vers);
	
	// Register jsonPath
	$json_path_vers = date("ymd-Gis", filemtime( ECAVA_DIOT_SCADA_DIR . "js/jsonpath-0.8.0.js" ));
	wp_register_script('ecava_diot_scada_jsonpath', ECAVA_DIOT_SCADA_URL . "js/jsonpath-0.8.0.js", array(), $json_path_vers); 
	
	// Register format.js
	$format_vers = date("ymd-Gis", filemtime( ECAVA_DIOT_SCADA_DIR . "js/format.js" ));
	wp_register_script('ecava_diot_scada_format', ECAVA_DIOT_SCADA_URL . "js/format.js", array(), $format_vers); 
	
	// Register FLOT
	$json_flot_vers = date("ymd-Gis", filemtime( ECAVA_DIOT_SCADA_DIR . "js/jquery.flot.js" ));
	wp_register_script('ecava_diot_scada_flot', ECAVA_DIOT_SCADA_URL . "js/jquery.flot.js", array(), $json_flot_vers); 
	
	// Register FLOT time
	$json_flot_time_vers = date("ymd-Gis", filemtime( ECAVA_DIOT_SCADA_DIR . "js/jquery.flot.time.js" ));
	wp_register_script('ecava_diot_scada_flot_time', ECAVA_DIOT_SCADA_URL . "js/jquery.flot.time.js", array( 'ecava_diot_scada_flot' ), $json_flot_time_vers); 
		
	// Register MQTT subscriber script
	$ecava_diot_scada_mqtt_subs_vers = date("ymd-Gis", filemtime( ECAVA_DIOT_SCADA_DIR. "js/eds_mqttsub.js" ));
	wp_register_script('ecava_diot_scada_mqtt_subscriber', ECAVA_DIOT_SCADA_URL. "js/eds_mqttsub.js" , array( 'jquery', 'ecava_diot_scada_mqtt_client_library', 'ecava_diot_scada_jsonpath', 'ecava_diot_scada_flot_time', 'ecava_diot_scada_format'), $ecava_diot_scada_mqtt_subs_vers);
	
	wp_enqueue_script( 'ecava_diot_scada_mqtt_subscriber' );
}
add_action( 'wp_enqueue_scripts', 'ecava_diot_scada_register_scripts' );