// ecava_diot_scada_mqtt_subscriber.js

// Dependant on Jquery and MQTT Client Library

jQuery( document ).ready( function( $ ) {
    var client;
	//var server, port;
	var connOpt;
	
	//trending
	var trending = [];
	
	// generate unique client id
	function generate_new_client_id(prefix, min, max) {
		prefix = prefix || "ClientID_";
		min = min || 100000000000;
		max = max || 999999999999;
		var random_number = Math.floor(Math.random() * (max - min + 1)) + min;
		return prefix + random_number;
	}
	
	// called to handle unsigned integers
	function convertBytesToUnsignedInt(bytes) {
		var total = false;
		if (bytes instanceof Uint8Array) {
			total = 0;
			var size = bytes.length;				
			for (var i = 0; i < size; ++i) {
				total += bytes[i]*(Math.pow(256, (size-i)-1));
			}
		}
		return total;
	}

	// called to handle integers
	function convertBytesToInt(bytes) {
		var total = false;
		if (bytes instanceof Uint8Array) {
			total = 0;
			var size = bytes.length;
			var negative = false;
			for (var i = 0; i < size; ++i) {
				if (i == 0) {	//First Byte
					if (bytes[i] > 127) {
						negative = true;
					}
				}
				if (negative) {
					total -= (255-bytes[i])*(Math.pow(256, (size-i)-1));
					if (i == (size-1)) {
						total -= 1;
					}
				} else {
					total += bytes[i]*(Math.pow(256, (size-i)-1));
				}					
			}
		}
		return total;
	}
	
	function convertBytesToBool(bytes) {
		var ret = false;
		if (bytes instanceof Uint8Array && bytes.length == 1) {
			ret = true
			if (bytes[0] == 0) {
				ret = false;
			}
		}			
		return ret;
	}
	
	function convertBytesToReal32(bytes) {
		var ret = false;
		if (bytes instanceof Uint8Array && bytes.length == 4) {
			var newBytes = new Uint8Array(bytes);
			var dv = new DataView(newBytes.buffer);
			ret = dv.getFloat32(0).toPrecision(6);	
		}
		return ret;
	}
	
	function convertBytesToReal64(bytes) {
		var ret = false;
		if (bytes instanceof Uint8Array && bytes.length == 8) {
			var newBytes = new Uint8Array(bytes);
			var dv = new DataView(newBytes.buffer);
			ret = dv.getFloat64(0);
		}
		return ret;
	}
	
	function ConvertDataTypes(bytes, type) {
		var conv = false;
		switch (type) {
			case "int":
			case "integer": 
				conv = convertBytesToInt(bytes); 
				break;
			case "uint": 
				conv = convertBytesToUnsignedInt(bytes);
				break;
			case "real32":
				conv = convertBytesToReal32(bytes);
				break;
			case "real64":
				conv = convertBytesToReal64(bytes);
				break;
			case "bool":
			case "boolean":
				conv = convertBytesToBool(bytes);
				break;
			default:
				conv = bytes;
				break;
		}
		return conv;
	}
	
	// called when the client connects
	function onConnect() {
		console.log("MQTT Connected: " + client.host + ":" + client.port);
		var mqtt_subs = [];
		
		// get all DOM with diot class
		var diot_doms = $(".diot").toArray();
		for (var i = 0; i < diot_doms.length; i++) {
			if (diot_doms[i].hasAttribute("data-diot")) {
				try {
					var diot_data = JSON.parse(diot_doms[i].getAttribute("data-diot"));
					var topic = diot_data['topic'];
					if (mqtt_subs.indexOf(topic) == -1) {
						mqtt_subs.push(topic);
						client.subscribe(topic);
					}
					if (diot_data['trending'] == true) {
						// create trending object
						var trend = new Trending(diot_doms[i], topic);
						trending.push(trend);
					}
				} catch(err) {}			
			}
		}
	}
	
	// called when the client loses its connection
	function onConnectionLost(responseObject) {
		if (responseObject.errorCode !== 0) {
			console.log("MQTT Connection Lost:"+responseObject.errorMessage);
			client.connect(connOpt);
		}
	}
	
	// called when a message arrives
	function onMessageArrived(message) {
		//console.log("onMessageArrived.bytes:"+message.payloadBytes);
		
		var diot_doms = $(".diot").toArray();
		for (var i = 0; i < diot_doms.length; i++) {
			try {
				
				var diot_data = JSON.parse(diot_doms[i].getAttribute("data-diot"));
				var is_trend = (diot_data['trending'] == true);
				if (message.destinationName == diot_data['topic']) {
					var msg = "";					
					if (diot_data['data-type']) {
						var conv = ConvertDataTypes(message.payloadBytes, diot_data['data-type']);
						msg = conv;
					} else {
						msg = message.payloadString;
						
						if (diot_data['json-select']) {
							var msg_json = JSON.parse(msg);
							msg = eval("msg_json." + diot_data['json-select']);
						} else if (diot_data['jsonpath']) {
							var msg_json = JSON.parse(msg);
							var jsonpath_msg = jsonPath(msg_json, diot_data['jsonpath']);
							if (jsonpath_msg) {
								msg = (jsonpath_msg.length === 1) ? jsonpath_msg[0] : jsonpath_msg;
							}							
						}
					}
					
					// format the msg using format.js
					if (diot_data['format']) {
						msg = format(diot_data['format'], msg);
					}
					
					//console.log("onMessageArrived:" + msg);
					
					if (is_trend) {
						for (var j = 0; j <= trending.length; j++) {
							var dom = trending[j].getTrendDiv();
							if (dom == diot_doms[i]) {
								trending[j].setCurrentValue(msg);
								break;
							}
						}
					} else {
						diot_doms[i].innerHTML = msg;
					}					
				}
				
			} catch(err){}
		}		  
	}
	
	function parseServerPort(str) {
		if (str.search(/((https?)|(wss?)):\/\//ig) === -1) {
			str = "http://" + str;
		}
		
		var parser = document.createElement('a');
		parser.href = str;
		
		return {server: parser.hostname, port: parseInt(parser.port)};			
	}

	function parseServerList(server_list) {
		var list = [];
		var ret_list = [];
		if (server_list) {
			list = server_list.split(',');
			for (var i = 0; i < list.length; i++) {
				ret_list.push(parseServerPort(list[i]));
			}
		}
		return ret_list;
	}
	
	$.get(window.location.href, {ecava_action:"mqtt_settings"}, function(data,status,xhr) {
		if (status == 'success') {
			var ssl = (window.location.protocol == 'https:')? true : false;
						
			var server_list = parseServerList(data['server']);
			
			var server = data['server'];
			var port = parseInt(data['port']);
			
			if (server_list.length > 0) {
				server = server_list[0].server;
				if (!port) {
					port = 80;
					if (server_list[0].port) {
						port = server_list[0].port;
					}
				}
			}
			
			var client_id = data['client_id'];
			if (server) {
				if (!port) {
					port = 80;	// use default port
				}
				
				if (!client_id) {
					client_id = generate_new_client_id();
				}
					
				// Create a client instance
				client = new Paho.MQTT.Client(server, port, client_id);

				// set callback handlers
				client.onConnectionLost = onConnectionLost;
				client.onMessageArrived = onMessageArrived;
				
				connOpt = {onSuccess:onConnect};
				if (ssl) {
					connOpt.useSSL = true;
				}				
				
				// connect the client
				client.connect(connOpt);
			} else {
				console.log("MQTT server was not configured");
				//alert("MQTT server was not configured");
			}			
		} else {
			console.log("There was an issue loading the MQTT settings");
			//alert("There was an issue loading the MQTT settings");
		}
	}, "json");	
	
	function Trending(div, mqtt_topic) {
		var trend_holder = (div && div.nodeType == 1) ? div : document.getElementById(div);
		var data = [];
		var dataset;
		this.totalPoints = 30;
		var current_value = 0;
		var plot = 0;
		
		this.getMqttTopic = function() {
			return mqtt_topic;
		}
		
		this.getTrendDiv = function() {
			return trend_holder;
		}
		
		this.setCurrentValue = function(value) {
			current_value = Number(value);
			if (plot !== 0) {
				if (data.length > this.totalPoints)
					data.shift();
				var now = new Date().getTime();
				var temp = [now, current_value];
				data.push(temp);
				this.update();
			}				
		}

		var options = {
			series: {
				lines: {
					show: true,
					lineWidth: 1,
				}
			},
			xaxis: {
				mode: "time",
				font:{
					size:11,
					style:"italic",
					weight:"bold",
					family:"sans-serif",
					variant:"small-caps"
				},
				tickSize: [2, "second"],
				tickFormatter: function (v, axis) {
					var date = new Date(v);

					if (date.getSeconds() % 10 == 0) {
						var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
						var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
						var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();

						return hours + ":" + minutes + ":" + seconds;
					} else {
						return "";
					}
				},
				axisLabel: "Time",
				axisLabelUseCanvas: true,
				axisLabelFontSizePixels: 2,
				axisLabelFontFamily: 'Verdana, Arial',
				axisLabelPadding: 10
			},
			legend: {        
				labelBoxBorderColor: "#fff"
			},
			points: {
				show: true
			},
			grid: {                
				backgroundColor: "#ffffff"
				/*tickColor: "#008040"*/
			}
		};			
		
		this.update = function() {
			plot.setData(dataset);
			plot.setupGrid();
			plot.draw();
		}
		
		this.init = function() {
			
			dataset = [
				{ data: data }
			];

			plot = $.plot(trend_holder, dataset, options);
			
		}
		
		this.init();
	}
});