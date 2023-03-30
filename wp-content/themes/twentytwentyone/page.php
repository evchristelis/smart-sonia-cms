<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header(); 
?>

<?php
//ACCIDENTS

if (isset($_GET['accidents'])=="accidents") {	
	echo "accidents";

$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'accident',
        'post_status' => 'publish',
        'posts_per_page' => 99999999, 
       'orderby' => 'title', 
    //   'meta_key'          => 'alert_type',
 //   'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
			// 'author'        =>  '1',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 


$worker_name = get_post_meta( $post->ID, "worker_name", true); 
$timestamp = get_post_meta( $post->ID, "timestamp", true); 
$timestamp_offset = "7200";
$notes = get_post_meta( $post->ID, "notes", true); 
$dangerous_zone = get_post_meta( $post->ID, "dangerous_zone", true); 
$mydate= get_post_meta( $post->ID, "date_split_accident", true);
$title=get_the_title();

$add20= '20'.$mydate;

//$finaldate = $add20 . $timestamp;
$finaldate=  $add20 .' '. $timestamp;

echo $finaldate;

//$worker_name="1111";
//$timestamp="2023-03-20T15:00:00";
//$timestamp_offset = "3600";
//$notes = "notes";
//$dangerous_zone = "0";

	
//echo $worker_name;
//echo $timestamp;
//echo $timestamp_offset;
//echo $notes;
//echo $dangerous_zone;

$body = [

	     'timestamp' => $finaldate,
    'timestamp_offset' => $timestamp_offset,
	'notes' => $notes,
    'dangerous_zone' => $dangerous_zone,		
    'worker_id' => $worker_name,
	 'worker_id' => $worker_name,
	'title' => $title,
	
	
];



$response = wp_remote_post('http://45.140.185.141:30446/sql/api/accidents', [
	     'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json; charset=utf-8'
    ],
	'method'      => 'POST',
    'data_format' => 'body',
    'body' => json_encode($body),
	
]);
 


 endwhile;
 wp_reset_postdata(); 
 
 
 }

//ALERTS

if (isset($_GET['alerts'])=="alerts") {	
	echo "alerts";



$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '12 minutes ago',
        ),
    ),
       'post_type' => 'alerts',
        'post_status' => 'publish',
        'posts_per_page' => 99999999, 
       'orderby' => 'title', 
    //   'meta_key'          => 'alert_type',
 //   'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
		//	 'author'        =>  '1',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 







$risk_grading = get_post_meta( $post->ID, "risk_grading", true);
$read = get_post_meta( $post->ID, "read", true);
$worker_name = get_post_meta( $post->ID, "worker_name", true); 
$timestamp = get_post_meta( $post->ID, "timestamp", true); 
$timestamp_offset = "7200";
$notes = get_post_meta( $post->ID, "notes", true); 
$dangerous_zone = get_post_meta( $post->ID, "dangerous_zone", true); 
$mydate= get_post_meta( $post->ID, "date_split_alert", true);
	$title=get_the_title();



$add20= '20'.$mydate;

//$finaldate = $add20  .  $timestamp;

$finaldate=  $add20 .' '. $timestamp;


echo $finaldate;
//$worker_name="1111";
//$timestamp="2023-03-20T15:00:00";
//$timestamp_offset = "3600";
//$notes = "notes";
//$dangerous_zone = "0";

	
//echo $worker_name;
//echo $timestamp;
//echo $timestamp_offset;
//echo $notes;
//echo $dangerous_zone;

$body = [

	     'timestamp' => $finaldate,
    'timestamp_offset' => $timestamp_offset,
	//'notes' => $notes,
    //'dangerous_zone' => $dangerous_zone,		
    'worker_id' => $worker_name,
	'risk_grading' => $risk_grading,
	'read' => $read,
	'title' => $title,
	
	
];



$response = wp_remote_post('http://45.140.185.141:30446/sql/api/alerts', [
	     'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json; charset=utf-8'
    ],
	'method'      => 'POST',
    'data_format' => 'body',
    'body' => json_encode($body),
	
]);
 


 endwhile;
 wp_reset_postdata(); 
 
 
 }

//RECCOMENDATIONS


if (isset($_GET['reccomendations'])=="reccomendations") {	
	echo "reccomendations";

$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '3 minutes ago',
        ),
    ),
       'post_type' => 'recommendations',
        'post_status' => 'publish',
        'posts_per_page' => 99999999, 
       'orderby' => 'title', 
    //   'meta_key'          => 'alert_type',
 //   'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
		//	 'author'        =>  '1',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 







$risk_grading = get_post_meta( $post->ID, "risk_grading", true);
$read = get_post_meta( $post->ID, "read", true);
$worker_name = get_post_meta( $post->ID, "worker_name", true); 
$timestamp = get_post_meta( $post->ID, "timestamp", true); 
$timestamp_offset = "7200";
$notes = get_post_meta( $post->ID, "notes", true); 
$dangerous_zone = get_post_meta( $post->ID, "dangerous_zone", true); 
$mydate= get_post_meta( $post->ID, "date_split_recommendation", true);
$title=get_the_title();


$add20= '20'.$mydate;

//$finaldate = $add20  .  $timestamp;

$finaldate=  $add20 .' '. $timestamp;


echo $finaldate;
//$worker_name="1111";
//$timestamp="2023-03-20T15:00:00";
//$timestamp_offset = "3600";
//$notes = "notes";
//$dangerous_zone = "0";

	
//echo $worker_name;
//echo $timestamp;
//echo $timestamp_offset;
//echo $notes;
//echo $dangerous_zone;

$body = [

	     'timestamp' => $finaldate,
    'timestamp_offset' => $timestamp_offset,
	//'notes' => $notes,
    //'dangerous_zone' => $dangerous_zone,		
    'worker_id' => $worker_name,
	//'risk_grading' => $risk_grading,
	'read' => $read,
	'title' => $title,
];



$response = wp_remote_post('http://45.140.185.141:30446/sql/api/recommendations', [
	     'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json; charset=utf-8'
    ],
	'method'      => 'POST',
    'data_format' => 'body',
    'body' => json_encode($body),
	
]);
 


 endwhile;
 wp_reset_postdata(); 


}
//sos

if (isset($_GET['sos'])=="sos") {	
	echo "sos";


$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'sos',
        'post_status' => 'publish',
        'posts_per_page' => 99999999, 
       'orderby' => 'title', 
    //   'meta_key'          => 'alert_type',
 //   'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
			// 'author'        =>  '1',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 







$risk_grading = get_post_meta( $post->ID, "risk_grading", true);
$read = get_post_meta( $post->ID, "read", true);
$worker_name = get_post_meta( $post->ID, "worker_name", true); 
$timestamp = get_post_meta( $post->ID, "timestamp", true); 
$timestamp_offset = "7200";
$notes = get_post_meta( $post->ID, "notes", true); 
$dangerous_zone = get_post_meta( $post->ID, "dangerous_zone", true); 
$mydate= get_post_meta( $post->ID, "date_split_recommendation", true);
$title=get_the_title();
$location=get_post_meta( $post->ID, "location", true);
$authorid=get_the_author_ID();
$height=get_post_meta( $post->ID, "height_sos", true);
$pressure=get_post_meta( $post->ID, "pressure", true);
//echo $timestamp;


if ($authorid == 1){
	$nameauthor ="superadmin";
}

if ($authorid == 72){
	$nameauthor ="Smart-1";
}

if ($authorid == 73){
	$nameauthor ="Smart-2";
}

if ($authorid == 74){
	$nameauthor ="Smart-3";
}

if ($authorid == 75){
	$nameauthor ="Smart-4";
}

if ($authorid == 76){
	$nameauthor ="Smart-5";
}


$split = explode(",", $location);


$add20= '20'.$mydate;

//$finaldate = $add20  .  $timestamp;

$finaldate=  $add20 .''. $timestamp;

	echo $finaldate;

//echo $finaldate;
//echo $location;
//$worker_name="1111";
//$timestamp="2023-03-20T15:00:00";
//$timestamp_offset = "3600";
//$notes = "notes";
//$dangerous_zone = "0";

	
//echo $worker_name;
//echo $timestamp;
//echo $timestamp_offset;
//echo $notes;
//echo $dangerous_zone;

$body = [

	     'timestamp' => $finaldate ,
    'timestamp_offset' => $timestamp_offset,
	//'notes' => $notes,
    //'dangerous_zone' => $dangerous_zone,		
    'worker_id' => $nameauthor,
	//'risk_grading' => $risk_grading,
	'read' => $read,
	//'title' => $title,
	'height' => $height22,
	'pressure' => $pressure22,
	'latitude' => $split[0],
	'longitude' => $split[1],
];



$response = wp_remote_post('http://45.140.185.141:30446/sql/api/sos', [
	     'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json; charset=utf-8'
    ],
	'method'      => 'POST',
    'data_format' => 'body',
    'body' => json_encode($body),
	
]);
 


 endwhile;
 wp_reset_postdata(); 
 
 }
//SMARTWATCHES


if (isset($_GET['smartwatches'])=="smartwatches") {	
	echo "smartwatches";


$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'smartwatches',
        'post_status' => 'publish',
        'posts_per_page' => 99999999, 
       'orderby' => 'title', 
    //   'meta_key'          => 'alert_type',
 //   'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
			// 'author'        =>  '1',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 







$risk_grading = get_post_meta( $post->ID, "risk_grading", true);
$read = get_post_meta( $post->ID, "read", true);
$worker_name = get_post_meta( $post->ID, "worker_name", true); 
$timestamp = get_post_meta( $post->ID, "timestamp", true); 
$timestamp_offset = "7200";
$notes = get_post_meta( $post->ID, "notes", true); 
$dangerous_zone = get_post_meta( $post->ID, "dangerous_zone", true); 
$mydate= get_post_meta( $post->ID, "date_split_alert", true);
$myid= get_post_meta( $post->ID, "id", true);
	



$add20= '20'.$mydate;

//$finaldate = $add20  .  $timestamp;

$finaldate=  $add20 .' '. $timestamp;


echo $myid;
//$worker_name="1111";
//$timestamp="2023-03-20T15:00:00";
//$timestamp_offset = "3600";
//$notes = "notes";
//$dangerous_zone = "0";

	
//echo $worker_name;
//echo $timestamp;
//echo $timestamp_offset;
//echo $notes;
//echo $dangerous_zone;

$body = [

	//     'timestamp' => $finaldate,
 //   'timestamp_offset' => $timestamp_offset,
	//'notes' => $notes,
    //'dangerous_zone' => $dangerous_zone,		
  //  'worker_id' => $worker_name,
	//'risk_grading' => $risk_grading,
	'smartwatch_id' => $myid,
];



$response = wp_remote_post('http://45.140.185.141:30446/sql/api/smartwatches', [
	     'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json; charset=utf-8'
    ],
	'method'      => 'POST',
    'data_format' => 'body',
    'body' => json_encode($body),
	
]);
 


 endwhile;
 wp_reset_postdata(); 
 
 
 }

//WEATHER DATA


if (isset($_GET['weather_data'])=="weather_data") {	
	//echo "weather_data";



$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '999999999 minutes ago',
        ),
    ),
       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => 99999999, 
       'orderby' => 'title', 
    //   'meta_key'          => 'alert_type',
 //   'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
			// 'author'        =>  '1',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 







$risk_grading = get_post_meta( $post->ID, "risk_grading", true);
$read = get_post_meta( $post->ID, "read", true);
$worker_name = get_post_meta( $post->ID, "worker_name", true); 
$timestamp = get_post_meta( $post->ID, "timestamp", true); 
$timestamp_offset = "7200";
$notes = get_post_meta( $post->ID, "notes", true); 
$dangerous_zone = get_post_meta( $post->ID, "dangerous_zone", true); 
$mydate= get_post_meta( $post->ID, "date_split_alert", true);

$pm1="0";
$pm2_5="0";
$hum_in= get_post_meta( $post->ID, "hum_in", true);
$hum_out= get_post_meta( $post->ID, "hum_out", true);
$temp_in= get_post_meta( $post->ID, "temp_in", true);
$temp_out= get_post_meta( $post->ID, "temp_out", true);
$wind_direction= get_post_meta( $post->ID, "wind_direction", true);
$wind_speed= get_post_meta( $post->ID, "wind_speed", true);
$wind_speed= get_post_meta( $post->ID, "wind_speed", true);
$wind_speed_avg= get_post_meta( $post->ID, "wind_speed_10_min_avg", true);
$wind_speed_avg= get_post_meta( $post->ID, "wind_speed_10_min_avg", true);
$rain_height= get_post_meta( $post->ID, "rain_month", true);
$solar_radiation= get_post_meta( $post->ID, "solar_radiation", true);
$barometer= get_post_meta( $post->ID, "barometer", true);
$uv_index= get_post_meta( $post->ID, "uv", true);
$latitude= get_post_meta( $post->ID, "latitude", true);
$longitude= get_post_meta( $post->ID, "longitude", true);
	
$convert_temparature = ($temp_out - 32) * 0.5556;
	
	echo $convert_temparature;
	
	$precipitation= get_post_meta( $post->ID, "rain_day", true);
$convert_rain=($precipitation * 25.4);

	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo $convert_rain;
	
$add20= '20'.$mydate;

//$finaldate = $add20  .  $timestamp;

$finaldate=  $add20 .' '. $timestamp;


//echo $finaldate;
//$worker_name="1111";
//$timestamp="2023-03-20T15:00:00";
//$timestamp_offset = "3600";
//$notes = "notes";
//$dangerous_zone = "0";

	
//echo $worker_name;
//echo $timestamp;
//echo $timestamp_offset;
//echo $notes;
//echo $dangerous_zone;

$body = [

	    
	     'timestamp' => $timestamp,
    'timestamp_offset' => $timestamp_offset,
	//'notes' => $notes,
    //'dangerous_zone' => $dangerous_zone,		
  //  'worker_id' => $worker_name,
	//'risk_grading' => $risk_grading,
	//'read' => $read,
	 'pm1' => $pm1,
	 'pm2_5' => $pm2_5p,
	 'pm10' => $pm10,
	 'hum_in' => $hum_in,
	 'hum_out' => $hum_out,
	 'temp_in' => $temp_in,
	 'temp_out' => $temp_out,
	 'wind_direction' => $wind_direction,
	 'wind_speed' => $wind_speed,
	 'wind_speed_avg' => "21",
	 'rain_height' => "22",
	 'solar_radiation' => $solar_radiation,
	 'uv_index' => $uv_index,
	 'barometer' => $barometer,
	 'latitude' => $latitude,
	 'longitude' => $longitude,
	'temp_out_celsius' => $convert_temparature,
	'precipitation' => $convert_rain,
	
	
	
];



$response = wp_remote_post('http://45.140.185.141:30446/sql/api/climate_data', [
	     'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json; charset=utf-8'
    ],
	'method'      => 'POST',
    'data_format' => 'body',
    'body' => json_encode($body),
		
]);
 


 endwhile;
 wp_reset_postdata(); 




}
//messages

if (isset($_GET['messages'])=="messages") {	
	echo "messages";


$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'messages',
        'post_status' => 'publish',
        'posts_per_page' => 99999999, 
       'orderby' => 'title', 
    //   'meta_key'          => 'alert_type',
 //   'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
		//	 'author'        =>  '1',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 







$risk_grading = get_post_meta( $post->ID, "risk_grading", true);
$read = get_post_meta( $post->ID, "read", true);
$worker_name = get_post_meta( $post->ID, "worker_name", true); 
$timestamp = get_post_meta( $post->ID, "timestamp", true); 
$timestamp_offset = "7200";
$notes = get_post_meta( $post->ID, "notes", true); 
$dangerous_zone = get_post_meta( $post->ID, "dangerous_zone", true); 
$mydate= get_post_meta( $post->ID, "date_split_message", true);
$title=get_the_title();


$add20= '20'.$mydate;

//$finaldate = $add20  .  $timestamp;

$finaldate=  $add20 .' '. $timestamp;


echo $finaldate;
//$worker_name="1111";
//$timestamp="2023-03-20T15:00:00";
//$timestamp_offset = "3600";
//$notes = "notes";
//$dangerous_zone = "0";

	
//echo $worker_name;
//echo $timestamp;
//echo $timestamp_offset;
//echo $notes;
//echo $dangerous_zone;

$body = [

	     'timestamp' => $finaldate,
    'timestamp_offset' => $timestamp_offset,
	//'notes' => $notes,
    //'dangerous_zone' => $dangerous_zone,		
    'worker_id' => $worker_name,
	'notes' => $notes,
	'read' => $read,
	'title' => $title,
	
	
];



$response = wp_remote_post('http://45.140.185.141:30446/sql/api/messages', [
	     'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json; charset=utf-8'
    ],
	'method'      => 'POST',
    'data_format' => 'body',
    'body' => json_encode($body),
	
]);
 


 endwhile;
 wp_reset_postdata(); 
 
 }

//SMART WATCH SENSOR DATE

if (isset($_GET['worker_data'])=="worker_data") {	
	echo "worker_data";


$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '1 minutes ago',
        ),
    ),
       'post_type' => 'worker_data',
        'post_status' => 'publish',
        'posts_per_page' => 99999999, 
       'orderby' => 'title', 
    //   'meta_key'          => 'alert_type',
 //   'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
			// 'author'        =>  '1',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 







$risk_grading = get_post_meta( $post->ID, "risk_grading", true);
$read = get_post_meta( $post->ID, "read", true);
$worker_name = get_post_meta( $post->ID, "worker_name", true); 
$timestamp = get_post_meta( $post->ID, "timestamp", true); 
$timestamp_offset = "7200";
$notes = get_post_meta( $post->ID, "notes", true); 
$dangerous_zone = get_post_meta( $post->ID, "dangerous_zone", true); 
$mydate= get_post_meta( $post->ID, "date_split_recommendation", true);
$title=get_the_title();
$location=get_post_meta( $post->ID, "location", true);
$authorid=get_the_author_ID();
$floor=get_post_meta( $post->ID, "floor", true);
$height=get_post_meta( $post->ID, "height", true);
$heartrate=get_post_meta( $post->ID, "heartrate", true);

//echo $authorid;


if ($authorid == 1){
	$nameauthor ="superadmin";
			$infoo = get_field('user_info', 'user_1');
	
	$varrr=strtok($infoo, '|');
//echo $varr;

$str_arr = explode (",", $varrr);
	//print_r($str_arr);
	
}

if ($authorid == 72){
	$nameauthor ="Smart-1";
		$infoo = get_field('user_info', 'user_72');
	
	$varrr=strtok($infoo, '|');
//echo $varr;

$str_arr = explode (",", $varrr);
	//print_r($str_arr);
}

if ($authorid == 73){
	$nameauthor ="Smart-2";
		$infoo = get_field('user_info', 'user_73');
	
	$varrr=strtok($infoo, '|');
//echo $varr;

$str_arr = explode (",", $varrr);
	//print_r($str_arr);
}

if ($authorid == 74){
	$nameauthor ="Smart-3";
	$infoo = get_field('user_info', 'user_74');
	
	$varrr=strtok($infoo, '|');
//echo $varr;

$str_arr = explode (",", $varrr);
	//print_r($str_arr);
}

if ($authorid == 75){
	$nameauthor ="Smart-4";
		$infoo = get_field('user_info', 'user_75');
	
	$varrr=strtok($infoo, '|');
//echo $varr;

$str_arr = explode (",", $varrr);
	//print_r($str_arr);
}

if ($authorid == 76){
	$nameauthor ="Smart-5";
		$infoo = get_field('user_info', 'user_76');
	
	$varrr=strtok($infoo, '|');
//echo $varr;

$str_arr = explode (",", $varrr);
	//print_r($str_arr);
}

echo $str_arr[1];
	
$split = explode(",", $location);


$add20= '20'.$timestamp;

//$finaldate = $add20  .  $timestamp;

//$finaldate=  $add20 .' '. $timestamp;


//echo $add20;
//echo $location;
//$worker_name="1111";
//$timestamp="2023-03-20T15:00:00";
//$timestamp_offset = "3600";
//$notes = "notes";
//$dangerous_zone = "0";

	
//echo $worker_name;
//echo $timestamp;
//echo $timestamp_offset;
//echo $notes;
//echo $dangerous_zone;

$body = [

	 	     'timestamp' =>$add20,
    'timestamp_offset' => $timestamp_offset,
	//'notes' => $notes,
    //'dangerous_zone' => $dangerous_zone,		
    'worker_id' => $nameauthor,
	//'risk_grading' => $risk_grading,
	//'floor' => $floor222,
	//'title' => $title,
	'height' => $height,
	'heart_rate' => $heartrate,
	'dangerous_zone' => "0",
	'latitude' => $split[0],
	'longitude' => $split[1],
	'ecg' => $heartrate22,
	'warm' => $heartrate22,
	'skin_temperature' => $heartrate22,
	'blood_pressure' => $heartrate22,
	'oxygen' => $heartrate22,
	'smartwatch_id' => $str_arr[1],
	'check_in' => "1",
	
	
];



$response = wp_remote_post('http://45.140.185.141:30446/sql/api/smartwatch_sensor_data', [
	     'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json; charset=utf-8'
    ],
	'method'      => 'POST',
    'data_format' => 'body',
    'body' => json_encode($body),
	
]);
 


 endwhile;
 wp_reset_postdata(); 



}

//Beacons


if (isset($_GET['beacons'])=="beacons") {	
	echo "beacons";

$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'beacons',
        'post_status' => 'publish',
        'posts_per_page' => 99999999, 
       'orderby' => 'title', 
    //   'meta_key'          => 'alert_type',
 //   'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
		//	 'author'        =>  '1',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 







$risk_grading = get_post_meta( $post->ID, "risk_grading", true);
$read = get_post_meta( $post->ID, "read", true);
$worker_name = get_post_meta( $post->ID, "worker_name", true); 
$timestamp = get_post_meta( $post->ID, "timestamp", true); 
$timestamp_offset = "7200";
$notes = get_post_meta( $post->ID, "notes", true); 
$dangerous_zone = get_post_meta( $post->ID, "dangerous_zone", true); 
$mydate= get_post_meta( $post->ID, "date_split_alert", true);
$beacon_name = get_post_meta( $post->ID, "beacon_id", true);
$info = get_post_meta( $post->ID, "info", true);

$varr=strtok($info, '|');
echo $varr;

$str_arr = explode (",", $varr);
	print_r($str_arr);
	
$add20= '20'.$mydate;

$add20new= '20'.$str_arr[3];

echo $add20new;
//$finaldate = $add20  .  $timestamp;

$finaldate=  $add20 .' '. $timestamp;


//echo $finaldate;
//$worker_name="1111";
//$timestamp="2023-03-20T15:00:00";
//$timestamp_offset = "3600";
//$notes = "notes";
//$dangerous_zone = "0";

	
//echo $worker_name;
//echo $timestamp;
//echo $timestamp_offset;
//echo $notes;
//echo $dangerous_zone;

$body = [

	     'timestamp' => $add20new,
    'timestamp_offset' => $timestamp_offset,		
    'beacon_name' => $beacon_name,
	'beacon_major_id' => $beacon_name,
	'beacon_location' => $str_arr[1],
	'dangerous_zone' => $add20n2ew,
	'notes' => $str_arr[2],
];



$response = wp_remote_post('http://45.140.185.141:30446/sql/api/beacons', [
	     'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json; charset=utf-8'
    ],
	'method'      => 'POST',
    'data_format' => 'body',
    'body' => json_encode($body),
	
]);
 


 endwhile;
 wp_reset_postdata(); 

}


?>


				<?php
if (isset($_GET['user1'])=="1") {	
	echo "111";
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '30 minutes ago',
        ),
    ),
       'post_type' => 'alerts',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
       //'orderby' => 'title', 
       'meta_key'          => 'alert_type',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '72',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			//echo "aaaaacc";
				$alerts = get_post_meta( $post->ID, "alert_type", true); 
				

				
				if ( $alerts == "1"){
								
					$i++;
					
				}
				
				
				if ( $alerts == "2"){
								
					$j++;
					
				}
	
				
				if ( $alerts == "3"){
								
					$k++;
					
				}
			
			

    endwhile;
	
	
	if ($k == "1") {
						$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Consult (seek assistance from managers).',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'VERY HIGH',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	//update_post_meta( $post_ID, timestamp, "123" );	
						
						exit();
					
				}
	
	
		if ($j >= "3") {
					
										$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Stop (for a longer period)',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
	//update_post_meta( $post_ID, timestamp, "123" );	
				
					}
				//echo $i;
			//		echo $j;
			//	echo $k;
				
				if ($i >= "5") {
					
									$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Take a Break',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	//update_post_meta( $post_ID, timestamp, "123" );			
					
					exit();
				}
				
				
					
		
				
				
							
				
				
				
				
    wp_reset_postdata(); 
					
					
					
}

?>



				
				
				
					<?php
		if (isset($_GET['user2'])=="2") {	
	echo "222";		
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args2 = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '30 minutes ago',
        ),
    ),
       'post_type' => 'alerts',
        'post_status' => 'publish',
        'posts_per_page' => 9999999999, 
       //'orderby' => 'title', 
       'meta_key'          => 'alert_type',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '73',
    );

    $loop2 = new WP_Query( $args2 ); 
        
    while ( $loop2->have_posts() ) : $loop2->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$alerts2 = get_post_meta( $post->ID, "alert_type", true); 
				

				
				if ( $alerts2 == "1"){
								
					$i2++;
					
				}
				
				
				if ( $alerts2 == "2"){
								
					$j2++;
					
				}
	
				
				if ( $alerts2 == "3"){
								
					$k2++;
					
				}
			
			

    endwhile;
			//	echo $i;
				//	echo $j;
			//	echo $k;
	
				if ($k2 == "1") {
						$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Consult (seek assistance from managers).',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'VERY HIGH',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	//update_post_meta( $post_ID, timestamp, "123" );	
						exit();
						
					
				}
				
			
			
					if ($j2 >= "3") {
					
										$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Stop (for a longer period)',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
							
	//update_post_meta( $post_ID, timestamp, "123" );	
				exit();
					}
					
	
			
			
				if ($i2 >= "5") {
					
									$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Take a Break',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	//update_post_meta( $post_ID, timestamp, "123" );			
					exit();
					
				}
				
				
							
				
				
				
				
				
				
    wp_reset_postdata(); 
					
					
		}
	

?>


				
				
				
				
				
					<?php
				
	if (isset($_GET['user3'])=="3") {				
				echo "3333";
				
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args3 = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '30 minutes ago',
        ),
    ),
       'post_type' => 'alerts',
        'post_status' => 'publish',
        'posts_per_page' => 9999999999, 
       //'orderby' => 'title', 
       'meta_key'          => 'alert_type',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '74',
    );

    $loop3 = new WP_Query( $args3 ); 
        
    while ( $loop3->have_posts() ) : $loop3->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$alerts3 = get_post_meta( $post->ID, "alert_type", true); 
				

				
				if ( $alerts3 = "1"){
								
					$i3++;
					
				}
				
				
				if ( $alerts3 = "2"){
								
					$j3++;
					
				}
	
				
				if ( $alerts3 = "3"){
								
					$k3++;
					
				}
			
			

    endwhile;
			//	echo $i;
				//	echo $j;
			//	echo $k;
				
		
		
			if ($k3 == "1") {
						$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Consult (seek assistance from managers).',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'VERY HIGH',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
	//update_post_meta( $post_ID, timestamp, "123" );	
						
						
					
				}
		
		
		
		
		
			
	
		
		
		
		
		
					if ($j3 >= "3") {
					
										$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Stop (for a longer period)',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
	//update_post_meta( $post_ID, timestamp, "123" );	
				
					}
					
	
				
					if ($i3 >= "5") {
					
									$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Take a Break',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	//update_post_meta( $post_ID, timestamp, "123" );			
					
					exit();
				}
							
				
				
				
				
    wp_reset_postdata(); 
					
					
	}	
	

?>


				
					<?php
		if (isset($_GET['user4'])=="4") {	
			echo "4444";
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args4 = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '30 minutes ago',
        ),
    ),
       'post_type' => 'alerts',
        'post_status' => 'publish',
        'posts_per_page' => 9999999999, 
       //'orderby' => 'title', 
       'meta_key'          => 'alert_type',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '75',
    );

    $loop4 = new WP_Query( $args4 ); 
        
    while ( $loop4->have_posts() ) : $loop4->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$alerts4 = get_post_meta( $post->ID, "alert_type", true); 
				

				
				if ( $alerts4 == "1"){
								
					$i4++;
					
				}
				
				
				if ( $alerts4 == "2"){
								
					$j4++;
					
				}
	
				
				if ( $alerts4 == "3"){
								
					$k4++;
					
				}
			
			

    endwhile;
			//	echo $i;
				//	echo $j;
			//	echo $k;
		
			if ($k4 == "1") {
						$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Consult (seek assistance from managers).',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'VERY HIGH',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
	//update_post_meta( $post_ID, timestamp, "123" );	
						
						
					
				}
			
			
			
			if ($j4 >= "3") {
					
										$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Stop (for a longer period)',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
	//update_post_meta( $post_ID, timestamp, "123" );	
				
					}
			
				if ($i4 >= "5") {
					
									$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Take a Break',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	//update_post_meta( $post_ID, timestamp, "123" );			
					
					exit();
				}
				
					
					
		
				
				
							
				
				
				
				
    wp_reset_postdata(); 
					
					
					
		}

?>

	

				
				
				
					<?php
if (isset($_GET['user5'])=="5") {	
	echo "55555";
	
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args5 = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '30 minutes ago',
        ),
    ),
       'post_type' => 'alerts',
        'post_status' => 'publish',
        'posts_per_page' => 9999999999, 
       //'orderby' => 'title', 
       'meta_key'          => 'alert_type',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '76',
    );

    $loop5 = new WP_Query( $args5); 
        
    while ( $loop5->have_posts() ) : $loop5->the_post(); 
        the_title(); 
        the_excerpt(); 
			
				$alerts5 = get_post_meta( $post->ID, "alert_type", true); 
				

				
				if ( $alerts5 == "1"){
								
					$i5++;
					
				}
				
				
				if ( $alerts5 == "2"){
								
					$j5++;
					
				}
	
				
				if ( $alerts5 == "3"){
								
					$k5++;
					
				}
			
			

    endwhile;
				echo $i5;
				echo $j5;
				echo $k5;
				if ($k5 == "1") {
						$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Consult (seek assistance from managers).',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'VERY HIGH',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
	//update_post_meta( $post_ID, timestamp, "123" );	
						
						
					
				}
	
		if ($j5 >= "3") {
					
										$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Stop (for a longer period)',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
							
	//update_post_meta( $post_ID, timestamp, "123" );	
				exit();
					}
	
	
				if ($i5 >= "5") {
					
									$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'Take a Break',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'recommendations',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_recommendation' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	//update_post_meta( $post_ID, timestamp, "123" );			
					exit();
					
				}
				
				
					
		
				
				
							
				
				
				
				
    wp_reset_postdata(); 
					
					
		
}

?>




<?
get_footer();
?>

