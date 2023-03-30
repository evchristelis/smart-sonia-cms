<?php

require('../phpMQTT.php');

$server = 'radiophonie.ibspan.waw.pl';     // change if necessary
$port = 1883;                     // change if necessary
$username = 'dotsoft';                   // set your username
$password = '6QNG@V*q4r';                   // set your password
$client_id = 'phpMQTT-subscriber'; // make sure this is unique for connecting to sever - you could use uniqid()

$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);
if(!$mqtt->connect(true, NULL, $username, $password)) {
	exit(1);
}

echo $mqtt->subscribeAndWaitForMessage('assist-iot/weather-station/data', 0);

$data=$mqtt->subscribeAndWaitForMessage('assist-iot/weather-station/data', 0);

//echo json_encode($data);

$fp = fopen('/var/www/vhosts/smart-sonia.eu/httpdocs/wp-content/uploads/wpallimport/files/weather.json', 'w');
fwrite($fp, $data);
fclose($fp);

$mqtt->close();