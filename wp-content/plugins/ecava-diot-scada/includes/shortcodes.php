<?php

// shortcodes.php

// Exit if accessed directly. info: ABSPATH should be defined in wp-config and would not be defined if file was directly accessed rather than going through wordpress
if ( ! defined( 'ABSPATH' ) ) exit;

function ecava_diot_scada_diot_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'topic' => '',
		'json-select' => '',
		'data-type' => '',
		'jsonpath' => '',
		'trend-height' => '',
		'format' => '',
	), $atts );
	
	$display = '';
	if (!empty($a['topic'])) {
		
		$diot_data = array();		
		$diot_data['topic'] = $a['topic'];		
			
		$element_tag = "span";
		$element_style = "";
			
		if (!empty($a['jsonpath'])) {
			$diot_data['jsonpath'] = $a['jsonpath'];
		} else {
			// search for jsonpath within topic
			$jsonpath_index = strpos($a['topic'], '$.');
			if ($jsonpath_index == FALSE) {
				$jsonpath_index = strpos($a['topic'], '$[');
			}
			
			if ($jsonpath_index != FALSE) {
				$diot_data['jsonpath'] = substr($a['topic'], $jsonpath_index);
				$diot_data['topic'] = substr($a['topic'], 0, $jsonpath_index);
			}
		}		
		
		if (!empty($a['json-select'])) {
			$diot_data['json-select'] = $a['json-select'];
		}
		
		if (!empty($a['data-type'])) {
			$diot_data['data-type'] = $a['data-type'];
		}
		
		if (!empty($a['format'])) {
			$diot_data['format'] = $a['format'];
		}
		
		if (!empty($a['trending']) || !empty($a['trend-height'])) {
			$diot_data['trending'] = true;
			$trend_height = !empty($a['trend-height']) ? $a['trend-height'] : 100;
			$element_tag = "div";
			$element_style = "style='height:". $trend_height ."px;'";
		}
		
		
		ob_start();
			echo "<$element_tag class='diot' data-diot='". json_encode($diot_data) . "' ". $element_style ." ></$element_tag>";
		$display = ob_get_clean();		
	}
	
	return $display;	
}
add_shortcode( 'diot', 'ecava_diot_scada_diot_shortcode' );
