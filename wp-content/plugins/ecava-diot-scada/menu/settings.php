<?php

/*
 *	settings.php
 */

 /*
 * This file only gets included if "is_admin()" check is true.
 * Admin menu rendering code goes in this file.
 */
 
add_action('admin_menu', 'ecava_diot_scada_add_admin_menu');
add_action( 'admin_init', 'ecava_diot_scada_register_settings' );

function ecava_diot_scada_register_settings() {
	register_setting( ECAVA_DIOT_SCADA_MQTT_SETTINGS_OPTIONS, ECAVA_DIOT_SCADA_MQTT_SETTINGS_OPTIONS );
	add_settings_section( 'ecava_diot_scada_settings_main', '', 'ecava_diot_scada_settings_main_text', ECAVA_DIOT_SCADA_MAIN_MENU_SLUG);
	add_settings_field( 'ecava_diot_scada_mqtt_settings_server', 'Server', 'ecava_diot_scada_mqtt_settings_server_field', ECAVA_DIOT_SCADA_MAIN_MENU_SLUG, 'ecava_diot_scada_settings_main');
	add_settings_field( 'ecava_diot_scada_mqtt_settings_client_id', 'Client ID', 'ecava_diot_scada_mqtt_settings_client_id_field', ECAVA_DIOT_SCADA_MAIN_MENU_SLUG, 'ecava_diot_scada_settings_main');
}

function ecava_diot_scada_add_admin_menu() {
    add_menu_page("DIOT SCADA", "DIOT SCADA", ECAVA_DIOT_SCADA_MANAGEMENT_PERMISSION, ECAVA_DIOT_SCADA_MAIN_MENU_SLUG, "ecava_diot_scada_settings_page", ECAVA_DIOT_SCADA_MENU_ICON);
	add_submenu_page(ECAVA_DIOT_SCADA_MAIN_MENU_SLUG, "Settings", "Settings", ECAVA_DIOT_SCADA_MANAGEMENT_PERMISSION, ECAVA_DIOT_SCADA_MAIN_MENU_SLUG, "ecava_diot_scada_settings_page");
}
 
function ecava_diot_scada_settings_page() {

	// add error/update messages
 	if ( isset( $_GET['settings-updated'] ) ) {
	// add settings saved message with the class of "updated"
	add_settings_error( 'ecava_diot_scada_messages', 'ecava_diot_scada_messages', 'Settings Saved', 'updated' );
	}	
	// show error/update messages
	settings_errors( 'ecava_diot_scada_messages' );
	
	?>
	<div class="wrap">
		<h1>DIOT SCADA with MQTT Settings</h1>
		
		<form method="post" action="options.php">
			<?php settings_fields( ECAVA_DIOT_SCADA_MQTT_SETTINGS_OPTIONS ); ?>
			<?php do_settings_sections( ECAVA_DIOT_SCADA_MAIN_MENU_SLUG ); ?>
			<?php submit_button(); ?>
		</form>
		
		<br><br>
		
		<h4>Shortcode notes:</h4>
		<ul style="list-style-type:square">
			<li>
				<p>
					Use <strong>[diot topic="&lt;mqtt topic&gt;"]</strong> in pages/posts to subsribe and the value will
					be replaced in the location of the shortcode.
				</p>
			</li>
			<li>
				<p>
					If the value is a JSON and a specific value from the JSON is required, then the
					jsonPath can be added to the end of the topic.
					You may test the path at <a href="http://jsonpath.com/">http://jsonpath.com/</a> to be sure of the value selected.
				</p>
				<p>
					<h5>Examples:</h5>
					<ul>
						<li><i>topic="test/json$.temperature"</i></li>
						<li><i>topic="test/json$.pressure.value"</i></li>
						<li><i>topic="test/json$.room.1.light"</i> - if there is an array in the JSON then select it with the index using ".&lt;index&gt;." instead of using "[&lt;index&gt;]".</li>
					</ul>
				</p>
				<p>
					<strong>note: using "]" in the topic can break the shortcode.</strong>
				</p>
			</li>
			<li>
				<p>
					If the value is sent as binary data and needs to be represented in a certain data type such as an integer then the <strong>"data-type"</strong> parameter can be used. 
					Data types currently supported are <strong><i>int, uint, real32, real64, and boolean</i></strong>.
				</p>
			</li>
			<li>
				<p>
					Formatting the data retrieved into a certain number format can be done by using the <strong><i>format</i></strong> parameter.
				</p>
				<p>
					<h5>Examples:</h5>
					<ul>
						<li><i>format="#.##"</i> - will round numbers to 2 decimal places.</li>
						<li><i>format="#"</i> - will produce a round number with no decimal places.
					</ul>
					NOTE: more information on what formatting can be done can be found here: <a href="https://code.google.com/archive/p/javascript-number-formatter/">https://code.google.com/archive/p/javascript-number-formatter/</a>
				</p>
			</li>
			<li>
				<p>
					To display the data from an MQTT topic in a trend chart, the parameter <strong><i>trend-height</i></strong> can be used to
					set the height of the canvas the chart would be drawn on in pixels (only the number is required to be inserted in the parameter). The value from the topic must be
					a number for it to be displayed in the chart.
				</p>
				<p>
					<h5>Example:</h5>
					<ul>
						<li><i>trend-height=300</i></li>
					</ul>
				</p>
				<p>
					<strong>hint: use jsonPath feature to get the specific data to be used in the chart if the topic publishes a JSON.</strong>
				</p>
			</li>
		</ul>
		<br><br>
		
		<h4>Greetings! We are actively developing more for you.</h4>
		<p>
			Tell us what you need or any thought you may have <a href="https://www.ecava.com/contact/?link=diot">here</a>. Thank you!
		</p>
		
		
	</div>
	<?php
}

function ecava_diot_scada_settings_main_text() {
	echo 'MQTT Client runs on Javascript and it can only connect to an MQTT Broker through websocket.';
}

function ecava_diot_scada_parse_server_port($server_string) {
	$list = explode(',', $server_string);
	
	$ret_list = array();
	foreach ($list as $i) {
		$server = "";
		$port = "";
		$ilength = strlen($i);
		$icolon = strpos($i, ':');
		if ($icolon) {
			$server = substr($i, 0, $icolon);
			$port = intval(substr($i, $icolon + 1, $ilength - strpos($i, '/', $icolon)));
		}
		
		if (empty($server)) {
			$server = $i;
		}
		
		if (empty($port) || $port < 0) {
			$port = 80;
		}
		
		$entry['server'] = $server;
		$entry['port'] = $port;
		array_push($ret_list, $entry);
	}
	return $ret_list;
}

function ecava_diot_scada_mqtt_settings_server_field() {
	$options = get_option(ECAVA_DIOT_SCADA_MQTT_SETTINGS_OPTIONS);
	$server = $options['server'];
	$port = $options['port'];
	$list = array();
	
	if (!empty($server)) {
		$list = ecava_diot_scada_parse_server_port($server);
	}
	
	if (sizeof($list) > 0) {
		$server = "";
		
		if (!empty($port)) {
			$list[0]['port'] = $port;	// use old setting if available
		}
		
		for ($i = 0; $i < sizeof($list); $i++) {
			if ($i > 0)
				$server .= ',';
			$server .= $list[$i]['server'] . ':' . $list[$i]['port'];
		}
	}
	
	echo "<input id='ecava_diot_scada_mqtt_settings_server' name='".ECAVA_DIOT_SCADA_MQTT_SETTINGS_OPTIONS."[server]' size='50' type='text' value='{$server}' />";
	echo "<input id='ecava_diot_scada_mqtt_settings_port' name='".ECAVA_DIOT_SCADA_MQTT_SETTINGS_OPTIONS."[port]' type='hidden' value='' />";
}

function ecava_diot_scada_mqtt_settings_client_id_field() {
	$options = get_option(ECAVA_DIOT_SCADA_MQTT_SETTINGS_OPTIONS);
	echo "<input id='ecava_diot_scada_mqtt_settings_client_id' name='".ECAVA_DIOT_SCADA_MQTT_SETTINGS_OPTIONS."[client_id]' size='50' type='text' value='{$options['client_id']}' />";
	echo "<br><i>id will be auto-generated if left blank.</i>";
}