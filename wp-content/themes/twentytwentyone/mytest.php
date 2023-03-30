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

				<?php date_default_timezone_set('Europe/Athens'); ?>
				

				
<?php
// alert type 4
				?>
<?php
	if (isset($_GET['user1'])=="1") {
				
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'worker_data',
        'post_status' => 'publish',
        'posts_per_page' => 1, 
       //'orderby' => 'title', 
       'meta_key'          => 'heartrate',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '72',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$heartrate1 = get_post_meta( $post->ID, "heartrate", true); 
				
						
	
				
				
			
				
				if ($heartrate1 > 153 ) {
			
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HEART',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
				
				}
				

				if (($heartrate1 > 100) && ($heartrate1 < 153)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HEART',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
				
				
				}

			



    endwhile;
				
				
    wp_reset_postdata(); 
					
					
					


?>




<?php 
				//	echo $heartrate;

					if (!empty($heartrate1)) {
  


										
						 $args3 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => 1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop3 = new WP_Query( $args3 ); 
        
    while ( $loop3->have_posts() ) : $loop3->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556;
						
								
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);


	if (($convert_temparature > 28) && ($convert_temparature < 34)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'TEMPERATURE',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}


if ($convert_temparature > 35) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'TEMPERATURE',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}



		
		
		if ($wind > 36) {
				
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'WIND',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}
			
		if ($wind > 72) {
				
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'WIND',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}

			
if (($convert_rain > 7.6) && ($convert_rain < 50)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'PRECIPITATION',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}

if ($convert_rain > 50) {
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'PRECIPIPATION',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				
				}
	
	
	if (($humidity > 20) && ($humidity < 40)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HUMIDITY',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
	
											 if ($humidity > 60)  {
				
				
	$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HUMIDITY',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
$post_id=wp_insert_post( $wordpress_post );	
add_post_meta($post_id, 'risk_grading', 'HIGH', true);												 
				
				}
if (($uv_index > 6) && ($uv_index < 7.9)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'UV INDEX',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
	if (($uv_index > 8) && ($uv_index < 10.9)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'UV INDEX',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
    endwhile;
				
				
    wp_reset_postdata(); 

}

	}
		
?>



		<?php
					if (isset($_GET['user2'])=="2") {
						
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'worker_data',
        'post_status' => 'publish',
        'posts_per_page' => 1, 
       //'orderby' => 'title', 
       'meta_key'          => 'heartrate',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '73',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$heartrate2 = get_post_meta( $post->ID, "heartrate", true); 
				
						
	
				
				
			
				
				if ($heartrate2 > 153 ) {
			
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HEART',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
				
				}
				

				if (($heartrate2 > 100) && ($heartrate2 < 153)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HEART',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
				
				
				}

			



    endwhile;
				
				
    wp_reset_postdata(); 
					
					
					


?>




<?php 
					
					if (!empty($heartrate2)) {
										
						 $args3 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => 1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop3 = new WP_Query( $args3 ); 
        
    while ( $loop3->have_posts() ) : $loop3->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);


	if (($convert_temparature > 28) && ($convert_temparature < 34)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'TEMPERATURE',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}


if ($convert_temparature > 35) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'TEMPERATURE',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}



		
		
		if ($wind > 36) {
				
				echo "aaaaa";
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'WIND',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}
			
		if ($wind > 72) {
				
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'WIND',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}

			
if (($convert_rain > 7.6) && ($convert_rain < 50)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'PRECIPITATION',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}

if ($convert_rain > 50) {
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'PRECIPIPATION',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				
				}
	
	
	if (($humidity > 20) && ($humidity < 40)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HUMIDITY',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
	
											 if ($humidity > 60)  {
				
				
	$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HUMIDITY',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}
if (($uv_index > 6) && ($uv_index < 7.9)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'UV INDEX',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
	if (($uv_index > 8) && ($uv_index < 10.9)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'UV INDEX',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
    endwhile;
				
				
    wp_reset_postdata(); 


					}
						
					}
?>

		
		
		
		


		
		
		
		
		
			<?php
					if (isset($_GET['user3'])=="3") {
						
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'worker_data',
        'post_status' => 'publish',
        'posts_per_page' => 1, 
       //'orderby' => 'title', 
       'meta_key'          => 'heartrate',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '74',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$heartrate3 = get_post_meta( $post->ID, "heartrate", true); 
				
						
	
				
				
			
				
				if ($heartrate3 > 153 ) {
			
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HEART',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
				
				}
				

				if (($heartrate3 > 100) && ($heartrate3 < 153)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HEART',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
				
				
				}

			



    endwhile;
				
				
    wp_reset_postdata(); 
					
					
					


?>




<?php 
					if (!empty($heartrate3)) {
					
										
						 $args3 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => 1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop3 = new WP_Query( $args3 ); 
        
    while ( $loop3->have_posts() ) : $loop3->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);


	if (($convert_temparature > 28) && ($convert_temparature < 34)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'TEMPERATURE',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}


if ($convert_temparature > 35) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'TEMPERATURE',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}



		
		
		if ($wind > 36) {
				
				echo "aaaaa";
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'WIND',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}
			
		if ($wind > 72) {
				
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'WIND',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}

			
if (($convert_rain > 7.6) && ($convert_rain < 50)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'PRECIPITATION',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}

if ($convert_rain > 50) {
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'PRECIPIPATION',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				
				}
	
	
	if (($humidity > 20) && ($humidity < 40)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HUMIDITY',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
	
											 if ($humidity > 60)  {
				
				
	$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HUMIDITY',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}
if (($uv_index > 6) && ($uv_index < 7.9)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'UV INDEX',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
	if (($uv_index > 8) && ($uv_index < 10.9)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'UV INDEX',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
    endwhile;
				
				
    wp_reset_postdata(); 

					}

?>

		
		
		
		
			<?php
				
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'worker_data',
        'post_status' => 'publish',
        'posts_per_page' => 1, 
       //'orderby' => 'title', 
       'meta_key'          => 'heartrate',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '75',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$heartrate4 = get_post_meta( $post->ID, "heartrate", true); 
				
						
	
				
				
			
				
				if ($heartrate4 > 153 ) {
			
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HEART',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
				
				}
				

				if (($heartrate4 > 100) && ($heartrate4 < 153)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HEART',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
				
				
				}

			



    endwhile;
				
				
    wp_reset_postdata(); 
					
					
					
					}

?>




<?php 	
if (isset($_GET['user4'])=="4") {
					
		if (!empty($heartrate4)) {			
										
						 $args3 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => 1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop3 = new WP_Query( $args3 ); 
        
    while ( $loop3->have_posts() ) : $loop3->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);


	if (($convert_temparature > 28) && ($convert_temparature < 34)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'TEMPERATURE',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}


if ($convert_temparature > 35) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'TEMPERATURE',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}



		
		
		if ($wind > 36) {
				
				echo "aaaaa";
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'WIND',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}
			
		if ($wind > 72) {
				
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'WIND',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}

			
if (($convert_rain > 7.6) && ($convert_rain < 50)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'PRECIPITATION',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}

if ($convert_rain > 50) {
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'PRECIPIPATION',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				
				}
	
	
	if (($humidity > 20) && ($humidity < 40)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HUMIDITY',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
	
											 if ($humidity > 60)  {
				
				
	$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HUMIDITY',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}
if (($uv_index > 6) && ($uv_index < 7.9)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'UV INDEX',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
	if (($uv_index > 8) && ($uv_index < 10.9)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'UV INDEX',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
    endwhile;
				
				
    wp_reset_postdata(); 

		}

?>

		
			<?php
				
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'worker_data',
        'post_status' => 'publish',
        'posts_per_page' => 1, 
       //'orderby' => 'title', 
       'meta_key'          => 'heartrate',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '76',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$heartrate5 = get_post_meta( $post->ID, "heartrate", true); 
				
						
	
				
				
			
				
				if ($heartrate5 > 153 ) {
			
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HEART',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
				
				}
				

				if (($heartrate5 > 100) && ($heartrate5 < 153)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HEART',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
				
				
				}

			



    endwhile;
				
				
    wp_reset_postdata(); 
					
					
}


?>




<?php 
						if (isset($_GET['user5'])=="5") {
					if (!empty($heartrate5)) {
										
						 $args3 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => 1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop3 = new WP_Query( $args3 ); 
        
    while ( $loop3->have_posts() ) : $loop3->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);


	if (($convert_temparature > 28) && ($convert_temparature < 34)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'TEMPERATURE',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}


if ($convert_temparature > 35) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'TEMPERATURE',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}



		
		
		if ($wind > 36) {
				
				echo "aaaaa";
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'WIND',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}
			
		if ($wind > 72) {
				
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'WIND',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}

			
if (($convert_rain > 7.6) && ($convert_rain < 50)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'PRECIPITATION',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}

if ($convert_rain > 50) {
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'PRECIPIPATION',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				
				}
	
	
	if (($humidity > 20) && ($humidity < 40)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HUMIDITY',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
	
											 if ($humidity > 60)  {
				
				
	$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'HUMIDITY',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				}
if (($uv_index > 6) && ($uv_index < 7.9)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'UV INDEX',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
	if (($uv_index > 8) && ($uv_index < 10.9)) {
				
					$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => 'UV INDEX',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '1',
	'alert_type' => '4',
  ]
);
 
wp_insert_post( $wordpress_post );	
				
				
				}
    endwhile;
				
				
    wp_reset_postdata(); 


					}
						}
?>















































				<?php
		if (isset($_GET['user1'])=="1") {
			echo "11111";
			
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'worker_data',
        'post_status' => 'publish',
        'posts_per_page' => 9999999999, 
       //'orderby' => 'title', 
       'meta_key'          => 'heartrate',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '72',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$heartrate = get_post_meta( $post->ID, "heartrate", true); 
				
				$dates[]=$heartrate;
			 
			
	
				
				
				//if (min($dates) > 153 ) {
				
				
				if ($heartrate> 153 ) {
				
		
					
				//	echo "Aaaaab";
					
				
						//echo $heartrate;

				
										
						 $args2 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop2 = new WP_Query( $args2 ); 
        
    while ( $loop2->have_posts() ) : $loop2->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);
			
					
							if 	(($convert_temparature > 28) && ($wind > 36)){
					
								
										
						$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
							}
						
						if 	(($convert_temparature > 28) && ($uv_index> 6) ){
					
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
							}
						
						if 	(($wind > 36) && ($uv_index> 6) ){
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
							
							
							}
						
						if 	(($convert_temparature > 28)  && ($humidity > 40) ){
						
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
							
						}			
						
						if 	(($wind > 36) && ($humidity > 40) ){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							
						}
						
						
						if 	(($uv_index> 6) && ($humidity > 40) ){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
					
													
						if 	(($uv_index> 6) && ($convert_rain > 50 )){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							
						}
												
						if 	(($humidity > 40) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
						}
																		  
						if 	(($wind > 36) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							
						}
						if 	(($convert_temparature > 28) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
						}
						
						if 	(($convert_temparature > 28) && ($convert_rain > 50 )){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
						
						
						// generate high
				
						
					
					if 	(($convert_temparature > 35) && ($wind > 72) ){
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				
			}
						
							if 	(($convert_temparature > 35) && ($uv_index> 10.9)  ){
				
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
				
			}
						
							if 	(($convert_temparature > 35) && ($humidity > 60) ){
				
				
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
				
			}
						
							if 	(($uv_index> 10.9) && ($humidity > 60) ){
				
				
		
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				
			}
							if 	(($wind > 72) && ($humidity > 60) ){
				
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
	
				
			}
							if 	(($wind > 72) && ($uv_index> 10.9)){
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
						
						
						
						if 	(($uv_index> 10.9) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
												
						if 	(($humidity > 60) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
																		  
						if 	(($wind > 72) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
						if 	(($convert_temparature > 35) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
						
						if 	(($convert_temparature > 35) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
				

				
			
				
				
				
				
					
		
				
				
    endwhile;
				
				
    wp_reset_postdata(); 
					
					
					
	}
				
					
				
				
				
				
			//	if  (($heartrate) < 100 ) {
				
			//			$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "0";
//($myfile, $txt);
//fclose($myfile);
					
		//			echo $heartrate;
					
			//	}
				
 
			
				
				
				
			
				
		//if (min($dates) > 100 ) {
					
				
					if (($heartrate > 100) && ($heartrate < 153)) {
	//	echo "aaaaaaaaaaaa";
				//echo $heartrate;
			
			
		//	$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="0"){
			
		//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "1";
//fwrite($myfile, $txt);
//fclose($myfile);
			
		//	continue;
			
				
		//	}
			
			
		
			
	//		$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="1"){
			
			//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "2";
//fwrite($myfile, $txt);
//fclose($myfile);
	//			continue;
				
		//	}
			
	//		$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="2"){
			
			//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "3";
//fwrite($myfile, $txt);
//fclose($myfile);
			///	continue;
				
		//	}
				
				
			//	echo "aaa";						
						 $args2 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop2 = new WP_Query( $args2 ); 
        
    while ( $loop2->have_posts() ) : $loop2->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);
			
					
						if 	(($convert_temparature > 28) && ($wind > 36) && ($uv_index> 6) ){
				echo "yes";
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
							
								
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				
				exit();
				
				
						
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );		
							
						exit();
				
			}
					
					if 	(($convert_temparature > 28) && ($wind > 36) && ($humidity > 40) ){
				
							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
		exit();
				
			}
						if 	(($convert_temparature > 28) && ($wind > 36) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		
				exit();
			}
					
					if 	(($wind > 36) && ($uv_index> 6) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
			
			}
					
						if 	(($convert_temparature > 28) && ($uv_index> 6) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
				
			}
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($wind > 36) ){
		
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
						
exit();
				
			}
					
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($wind > 36) ){

							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){

							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
exit();
					
			}
					
					
						if 	(($humidity > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
						if 	(($humidity > 40) && ($wind > 36) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
						if 	(($humidity > 40) && ($convert_temparature > 28) && ($convert_rain > 7.6) ){
	
				
			}
					if 	(($wind > 36) && ($uv_index> 6) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){
							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );

					exit();
			}
					
						if 	(($convert_temparature > 28) && ($wind > 36) && ($convert_rain > 7.6) ){
								
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
	
				exit();
			}
					
					
					
					//high one
					
					
				
					
					if 	(($convert_temparature > 35) || ($wind > 72) || ($uv_index> 10.9) || ($humidity > 60) || ($convert_rain>50 )){
						
	$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 72,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-1',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	exit();
				
			}
				

				
			
				
				
				
				
					
		
			
				
    endwhile;
				
				
    wp_reset_postdata(); 
					
					
					}
	
				
					
					
				
					
				
	endwhile;		
				
			
				
		
				
				
    
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					

//}
				
				
    wp_reset_postdata(); 
				
			
		}
				?>
				
				







<?php
			if (isset($_GET['user2'])=="2") {
				echo "22222";
				
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'worker_data',
        'post_status' => 'publish',
        'posts_per_page' => 9999999999, 
       //'orderby' => 'title', 
       'meta_key'          => 'heartrate',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '73',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$heartrate = get_post_meta( $post->ID, "heartrate", true); 
				
				$dates[]=$heartrate;
			 
			
	
				
				
				//if (min($dates) > 153 ) {
				
				
				if ($heartrate> 153 ) {
				
		
					
				//	echo "Aaaaab";
					
				
						//echo $heartrate;

				
										
						 $args2 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop2 = new WP_Query( $args2 ); 
        
    while ( $loop2->have_posts() ) : $loop2->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);
			
					
							if 	(($convert_temparature > 28) && ($wind > 36)){
					
								
										
						$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
							}
						
						if 	(($convert_temparature > 28) && ($uv_index> 6) ){
					
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
							}
						
						if 	(($wind > 36) && ($uv_index> 6) ){
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
							
							
							}
						
						if 	(($convert_temparature > 28)  && ($humidity > 40) ){
						
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
							
						}			
						
						if 	(($wind > 36) && ($humidity > 40) ){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
						}
						
						
						if 	(($uv_index> 6) && ($humidity > 40) ){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
					
													
						if 	(($uv_index> 6) && ($convert_rain > 50 )){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
												
						if 	(($humidity > 40) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
																		  
						if 	(($wind > 36) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
						if 	(($convert_temparature > 28) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
						}
						
						if 	(($convert_temparature > 28) && ($convert_rain > 50 )){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
						
						
						// generate high
				
						
					
					if 	(($convert_temparature > 35) && ($wind > 72) ){
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
						
							if 	(($convert_temparature > 35) && ($uv_index> 10.9)  ){
				
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
						
							if 	(($convert_temparature > 35) && ($humidity > 60) ){
				
				
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
				
			}
						
							if 	(($uv_index> 10.9) && ($humidity > 60) ){
				
				
		
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
							if 	(($wind > 72) && ($humidity > 60) ){
				
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
	
				
			}
							if 	(($wind > 72) && ($uv_index> 10.9)){
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
								
			}
						
						
						
						if 	(($uv_index> 10.9) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
												
						if 	(($humidity > 60) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
																		  
						if 	(($wind > 72) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
						if 	(($convert_temparature > 35) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
						
						if 	(($convert_temparature > 35) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
				

				
			
				
				
				
				
					
		
				
				
    endwhile;
				
				
    wp_reset_postdata(); 
					
					
					
	}
				
					
				
				
				
				
			//	if  (($heartrate) < 100 ) {
				
			//			$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "0";
//($myfile, $txt);
//fclose($myfile);
					
		//			echo $heartrate;
					
			//	}
				
 
			
				
				
				
			
				
		//if (min($dates) > 100 ) {
					
				
	if (($heartrate > 100) && ($heartrate < 153)){
		
				//echo $heartrate;
			
			
		//	$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="0"){
			
		//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "1";
//fwrite($myfile, $txt);
//fclose($myfile);
			
		//	continue;
			
				
		//	}
			
			
		
			
	//		$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="1"){
			
			//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "2";
//fwrite($myfile, $txt);
//fclose($myfile);
	//			continue;
				
		//	}
			
	//		$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="2"){
			
			//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "3";
//fwrite($myfile, $txt);
//fclose($myfile);
			///	continue;
				
		//	}
				
				
			//	echo "aaa";						
						 $args2 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop2 = new WP_Query( $args2 ); 
        
    while ( $loop2->have_posts() ) : $loop2->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);
			
					
						if 	(($convert_temparature > 28) && ($wind > 36) && ($uv_index> 6) ){
				echo "Yes";
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
							
								
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				
						exit();
							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );		
							
						
				exit();
			}
					
					if 	(($convert_temparature > 28) && ($wind > 36) && ($humidity > 40) ){
				
							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
		exit();
				
			}
						if 	(($convert_temparature > 28) && ($wind > 36) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
				
			}
					
					if 	(($wind > 36) && ($uv_index> 6) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		
			exit();
			}
					
						if 	(($convert_temparature > 28) && ($uv_index> 6) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		
				exit();
			}
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($wind > 36) ){
		
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
						
exit();
				
			}
					
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($wind > 36) ){

							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){

							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
exit();
					
			}
					
					
						if 	(($humidity > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
						if 	(($humidity > 40) && ($wind > 36) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
						if 	(($humidity > 40) && ($convert_temparature > 28) && ($convert_rain > 7.6) ){
	
				
			}
					if 	(($wind > 36) && ($uv_index> 6) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
						exit();
					
			}
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){
							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
exit();
					
			}
					
						if 	(($convert_temparature > 28) && ($wind > 36) && ($convert_rain > 7.6) ){
								
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
	
				exit();
			}
					
					
					
					//high one
					
					
				
					
					if 	(($convert_temparature > 35) || ($wind > 72) || ($uv_index> 10.9) || ($humidity > 60) || ($convert_rain>50 )){
						
	$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 73,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-2',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	exit();
				
			}
				

				
			
				
				
				
				
					
		
			
				
    endwhile;
				
				
    wp_reset_postdata(); 
					
					
					}
	
				
					
					
				
					
				
	endwhile;		
				
			
				
		
				
				
    
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					

//}
				
				
    wp_reset_postdata(); 
			}
				?>









	



				
				
				








	<?php
				
if (isset($_GET['user3'])=="3") {
echo "33333";
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'worker_data',
        'post_status' => 'publish',
        'posts_per_page' => 9999999999, 
       //'orderby' => 'title', 
       'meta_key'          => 'heartrate',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '74',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$heartrate = get_post_meta( $post->ID, "heartrate", true); 
				
				$dates[]=$heartrate;
			 
			
	
				
				
				//if (min($dates) > 153 ) {
				
				
				if ($heartrate> 153 ) {
				
		
					
				//	echo "Aaaaab";
					
				
						//echo $heartrate;

				
										
						 $args2 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop2 = new WP_Query( $args2 ); 
        
    while ( $loop2->have_posts() ) : $loop2->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);
			
					
							if 	(($convert_temparature > 28) && ($wind > 36)){
					
								
										
						$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
							}
						
						if 	(($convert_temparature > 28) && ($uv_index> 6) ){
					
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
							}
						
						if 	(($wind > 36) && ($uv_index> 6) ){
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							
							exit();
							
							}
						
						if 	(($convert_temparature > 28)  && ($humidity > 40) ){
						
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
							
						}			
						
						if 	(($wind > 36) && ($humidity > 40) ){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
						}
						
						
						if 	(($uv_index> 6) && ($humidity > 40) ){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							
						}
					
													
						if 	(($uv_index> 6) && ($convert_rain > 50 )){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
												
						if 	(($humidity > 40) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
																		  
						if 	(($wind > 36) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
						if 	(($convert_temparature > 28) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
						
						if 	(($convert_temparature > 28) && ($convert_rain > 50 )){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							
						}
						
						
						// generate high
				
						
					
					if 	(($convert_temparature > 35) && ($wind > 72) ){
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
						
							if 	(($convert_temparature > 35) && ($uv_index> 10.9)  ){
				
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
						
							if 	(($convert_temparature > 35) && ($humidity > 60) ){
				
				
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
				
			}
						
							if 	(($uv_index> 10.9) && ($humidity > 60) ){
				
				
		
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
							if 	(($wind > 72) && ($humidity > 60) ){
				
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	exit();
				
			}
							if 	(($wind > 72) && ($uv_index> 10.9)){
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
						
						
						
						if 	(($uv_index> 10.9) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
												
						if 	(($humidity > 60) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
																		  
						if 	(($wind > 72) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
						if 	(($convert_temparature > 35) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
						
						if 	(($convert_temparature > 35) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
				

				
			
				
				
				
				
					
		
				
				
    endwhile;
				
				
    wp_reset_postdata(); 
					
		}					
					
	
				
					
				
				
				
				
			//	if  (($heartrate) < 100 ) {
				
			//			$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "0";
//($myfile, $txt);
//fclose($myfile);
					
		//			echo $heartrate;
					
			//	}
				
 
			
				
				
				
			
				
		//if (min($dates) > 100 ) {
					
				
				if (($heartrate > 100) && ($heartrate < 153)) {
		
				//echo $heartrate;
			
			
		//	$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="0"){
			
		//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "1";
//fwrite($myfile, $txt);
//fclose($myfile);
			
		//	continue;
			
				
		//	}
			
			
		
			
	//		$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="1"){
			
			//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "2";
//fwrite($myfile, $txt);
//fclose($myfile);
	//			continue;
				
		//	}
			
	//		$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="2"){
			
			//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "3";
//fwrite($myfile, $txt);
//fclose($myfile);
			///	continue;
				
		//	}
				
				
			//	echo "aaa";						
						 $args2 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop2 = new WP_Query( $args2 ); 
        
    while ( $loop2->have_posts() ) : $loop2->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);
			
					
						if 	(($convert_temparature > 28) && ($wind > 36) && ($uv_index> 6) ){
				
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
								
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				
						
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );		
							exit();
						
				
			}
					
					if 	(($convert_temparature > 28) && ($wind > 36) && ($humidity > 40) ){
				
							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );		
				
		exit();
				
			}
						if 	(($convert_temparature > 28) && ($wind > 36) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
				
			}
					
					if 	(($wind > 36) && ($uv_index> 6) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		
			exit();
			}
					
						if 	(($convert_temparature > 28) && ($uv_index> 6) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
				
			}
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($wind > 36) ){
		
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
						
exit();
				
			}
					
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($wind > 36) ){

							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){

							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
exit();
					
			}
					
					
						if 	(($humidity > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
						if 	(($humidity > 40) && ($wind > 36) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
						if 	(($humidity > 40) && ($convert_temparature > 28) && ($convert_rain > 7.6) ){
	
				
			}
					if 	(($wind > 36) && ($uv_index> 6) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){
							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
exit();
					
			}
					
						if 	(($convert_temparature > 28) && ($wind > 36) && ($convert_rain > 7.6) ){
								
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
	exit();
				
			}
					
					
					
					//high one
					
					
				
					
					if 	(($convert_temparature > 35) || ($wind > 72) || ($uv_index> 10.9) || ($humidity > 60) || ($convert_rain>50 )){
						
	$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 74,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-3',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	exit();
				
			}
				

				
			
				
				
				
				
					
		
			
				
    endwhile;
				
				
    wp_reset_postdata(); 
					
					
					}
	
				
					
					
				
					
				
	endwhile;		
				
			
				
		
				
				
    
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					

//}
				
				
    wp_reset_postdata(); 
}
				?>

















				


	<?php
		if (isset($_GET['user4'])=="4") {	
			
			echo "44444";
			
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'worker_data',
        'post_status' => 'publish',
        'posts_per_page' => 9999999999, 
       //'orderby' => 'title', 
       'meta_key'          => 'heartrate',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '75',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$heartrate = get_post_meta( $post->ID, "heartrate", true); 
				
				$dates[]=$heartrate;
			 
			
	
				
				
				//if (min($dates) > 153 ) {
				
				
				if ($heartrate> 153 ) {
				
		
					
				//	echo "Aaaaab";
					
				
						//echo $heartrate;

				
										
						 $args2 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop2 = new WP_Query( $args2 ); 
        
    while ( $loop2->have_posts() ) : $loop2->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);
			
					
							if 	(($convert_temparature > 28) && ($wind > 36)){
					
								
										
						$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
							}
						
						if 	(($convert_temparature > 28) && ($uv_index> 6) ){
					
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
							}
						
						if 	(($wind > 36) && ($uv_index> 6) ){
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
							
							
							}
						
						if 	(($convert_temparature > 28)  && ($humidity > 40) ){
						
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
							
						}			
						
						if 	(($wind > 36) && ($humidity > 40) ){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();	
							
						}
						
						
						if 	(($uv_index> 6) && ($humidity > 40) ){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							
						}
					
													
						if 	(($uv_index> 6) && ($convert_rain > 50 )){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
						}
												
						if 	(($humidity > 40) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							
						}
																		  
						if 	(($wind > 36) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
						if 	(($convert_temparature > 28) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
						
						if 	(($convert_temparature > 28) && ($convert_rain > 50 )){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
						}
						
						
						// generate high
				
						
					
					if 	(($convert_temparature > 35) && ($wind > 72) ){
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
						
							if 	(($convert_temparature > 35) && ($uv_index> 10.9)  ){
				
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
						
							if 	(($convert_temparature > 35) && ($humidity > 60) ){
				
				
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
		
				exit();
			}
						
							if 	(($uv_index> 10.9) && ($humidity > 60) ){
				
				
		
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
							if 	(($wind > 72) && ($humidity > 60) ){
				
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	exit();
				
			}
							if 	(($wind > 72) && ($uv_index> 10.9)){
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
						
						
						
						if 	(($uv_index> 10.9) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
												
						if 	(($humidity > 60) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
																		  
						if 	(($wind > 72) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
						if 	(($convert_temparature > 35) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
						
						if 	(($convert_temparature > 35) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
				

				
			
				
				
				
				
					
		
				
				
    endwhile;
				
				
    wp_reset_postdata(); 
					
					
					
	}
				
					
				
				
				
				
			//	if  (($heartrate) < 100 ) {
				
			//			$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "0";
//($myfile, $txt);
//fclose($myfile);
					
		//			echo $heartrate;
					
			//	}
				
 
			
				
				
				
			
				
		//if (min($dates) > 100 ) {
					
				
					if (($heartrate > 100) && ($heartrate < 153)) {
		
				//echo $heartrate;
			
			
		//	$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="0"){
			
		//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "1";
//fwrite($myfile, $txt);
//fclose($myfile);
			
		//	continue;
			
				
		//	}
			
			
		
			
	//		$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="1"){
			
			//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "2";
//fwrite($myfile, $txt);
//fclose($myfile);
	//			continue;
				
		//	}
			
	//		$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="2"){
			
			//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "3";
//fwrite($myfile, $txt);
//fclose($myfile);
			///	continue;
				
		//	}
				
				
			//	echo "aaa";						
						 $args2 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop2 = new WP_Query( $args2 ); 
        
    while ( $loop2->have_posts() ) : $loop2->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);
			
					
						if 	(($convert_temparature > 28) && ($wind > 36) && ($uv_index> 6) ){
				
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
						exit();	
								
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				
						exit();
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );		
							
						
				
			}
					
					if 	(($convert_temparature > 28) && ($wind > 36) && ($humidity > 40) ){
				
							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );		
				exit();
		
				
			}
						if 	(($convert_temparature > 28) && ($wind > 36) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
				
			}
					
					if 	(($wind > 36) && ($uv_index> 6) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
			
			}
					
						if 	(($convert_temparature > 28) && ($uv_index> 6) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
				
			}
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($wind > 36) ){
		
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
						
exit();
				
			}
					
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($wind > 36) ){

							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){

							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
exit();
					
			}
					
					
						if 	(($humidity > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();	
			}
					
						if 	(($humidity > 40) && ($wind > 36) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
						if 	(($humidity > 40) && ($convert_temparature > 28) && ($convert_rain > 7.6) ){
	
				
			}
					if 	(($wind > 36) && ($uv_index> 6) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){
							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
exit();
					
			}
					
						if 	(($convert_temparature > 28) && ($wind > 36) && ($convert_rain > 7.6) ){
								
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
	
				exit();
			}
					
					
					
					//high one
					
					
				
					
					if 	(($convert_temparature > 35) || ($wind > 72) || ($uv_index> 10.9) || ($humidity > 60) || ($convert_rain>50 )){
						
	$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 75,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-4',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
	
				
			}
				

				
			
				
				
				
				
					
		
			
				
    endwhile;
				
				
    wp_reset_postdata(); 
					
					
					}
	
				
					
					
				
					
				
	endwhile;		
				
			
				
		
				
				
    
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					

//}
				
				
    wp_reset_postdata(); 
		}
				?>



				
				






<?php
				if (isset($_GET['user5'])=="5") {	
					echo "55555";
				
				$given_date_as_time = date('Y-m-d H:i:s');
				//echo $given_date_as_time;
		 $args = array(  
			 'date_query' => array(
        array(
            'column' => 'post_date',
            'after'  => '5 minutes ago',
        ),
    ),
       'post_type' => 'worker_data',
        'post_status' => 'publish',
        'posts_per_page' => 9999999999, 
       //'orderby' => 'title', 
       'meta_key'          => 'heartrate',
    'orderby'           => 'meta_value',
    'order'             => 'DESC',
			 'author'        =>  '76',
    );

    $loop = new WP_Query( $args ); 
        
    while ( $loop->have_posts() ) : $loop->the_post(); 
        //the_title(); 
        //the_excerpt(); 
			
				$heartrate = get_post_meta( $post->ID, "heartrate", true); 
				
				$dates[]=$heartrate;
			 
			
	
				
				
				//if (min($dates) > 153 ) {
				
				
				if ($heartrate> 153 ) {
				
		
					
				//	echo "Aaaaab";
					
				
						//echo $heartrate;

				
										
						 $args2 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop2 = new WP_Query( $args2 ); 
        
    while ( $loop2->have_posts() ) : $loop2->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);
			
					
							if 	(($convert_temparature > 28) && ($wind > 36)){
					
								
										
						$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							
							}
						
						if 	(($convert_temparature > 28) && ($uv_index> 6) ){
					
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
							}
						
						if 	(($wind > 36) && ($uv_index> 6) ){
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							
							exit();
							
							}
						
						if 	(($convert_temparature > 28)  && ($humidity > 40) ){
						
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							
							exit();
						}			
						
						if 	(($wind > 36) && ($humidity > 40) ){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
						exit();		
							
						}
						
						
						if 	(($uv_index> 6) && ($humidity > 40) ){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
					
													
						if 	(($uv_index> 6) && ($convert_rain > 50 )){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
												
						if 	(($humidity > 40) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							exit();
						}
																		  
						if 	(($wind > 36) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								
							
						}
						if 	(($convert_temparature > 28) && ($convert_rain > 50 )){
							
							$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
								exit();
							
						}
						
						if 	(($convert_temparature > 28) && ($convert_rain > 50 )){
							
								$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();	
							
						}
						
						
						// generate high
				
						
					
					if 	(($convert_temparature > 35) && ($wind > 72) ){
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
						
							if 	(($convert_temparature > 35) && ($uv_index> 10.9)  ){
				
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
						
							if 	(($convert_temparature > 35) && ($humidity > 60) ){
				
				
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
				
			}
						
							if 	(($uv_index> 10.9) && ($humidity > 60) ){
				
				
		
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
							if 	(($wind > 72) && ($humidity > 60) ){
				
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				
	exit();
				
			}
							if 	(($wind > 72) && ($uv_index> 10.9)){
				
	
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
			}
						
						
						
						if 	(($uv_index> 10.9) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();		
						}
												
						if 	(($humidity > 60) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							
						}
																		  
						if 	(($wind > 72) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
						if 	(($convert_temparature > 35) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
						
						if 	(($convert_temparature > 35) && ($convert_rain > 50 )){
							
											$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'VERY HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '3',
  ]
);
 
wp_insert_post( $wordpress_post );
							exit();
						}
				

				
			
				
				
				
				
					
		
				
				
    endwhile;
				
				
    wp_reset_postdata(); 
					
					
					
	}
				
					
				
				
				
				
			//	if  (($heartrate) < 100 ) {
				
			//			$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "0";
//($myfile, $txt);
//fclose($myfile);
					
		//			echo $heartrate;
					
			//	}
				
 
			
				
				
				
			
				
		//if (min($dates) > 100 ) {
					
				
					if (($heartrate > 100) && ($heartrate < 153)){
		
				//echo $heartrate;
			
			
		//	$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="0"){
			
		//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "1";
//fwrite($myfile, $txt);
//fclose($myfile);
			
		//	continue;
			
				
		//	}
			
			
		
			
	//		$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="1"){
			
			//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "2";
//fwrite($myfile, $txt);
//fclose($myfile);
	//			continue;
				
		//	}
			
	//		$data_content =  file_get_contents("http://smart-sonia.eu.144-76-38-75.comitech.gr/newfile.txt");
 
		//	if ($data_content=="2"){
			
			//	$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//$txt = "3";
//fwrite($myfile, $txt);
//fclose($myfile);
			///	continue;
				
		//	}
				
				
			//	echo "aaa";						
						 $args2 = array(  
		       'post_type' => 'weather_station_data',
        'post_status' => 'publish',
        'posts_per_page' => -1, 
       'orderby' => 'title', 
     //  'meta_key'          => 'timestamp',
   // 'orderby'           => 'meta_value',
  //  'order'             => 'DESC',
	//		 'author'        =>  '72',
    );

    $loop2 = new WP_Query( $args2 ); 
        
    while ( $loop2->have_posts() ) : $loop2->the_post(); 
       // the_title(); 
       // the_excerpt(); 
			
		$temperature = get_post_meta( $post->ID, "temp_out", true);  $convert_temparature = ($temperature - 32) * 0.5556; 
					$wind = get_post_meta( $post->ID, "wind_speed", true); 
			   $uv_index= get_post_meta( $post->ID, "uv", true); 
					$humidity= get_post_meta( $post->ID, "hum_out", true); 
				$precipitation= get_post_meta( $post->ID, "rain_day", true); 
				 $convert_rain=($precipitation * 25.4);
			
					
						if 	(($convert_temparature > 28) && ($wind > 36) && ($uv_index> 6) ){
				
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
							
								
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
				exit();
						
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );		
							
						exit();
				
			}
					
					if 	(($convert_temparature > 28) && ($wind > 36) && ($humidity > 40) ){
				
							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );		
				exit();
		
				
			}
						if 	(($convert_temparature > 28) && ($wind > 36) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		exit();
				
			}
					
					if 	(($wind > 36) && ($uv_index> 6) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		
			exit();
			}
					
						if 	(($convert_temparature > 28) && ($uv_index> 6) && ($humidity > 40) ){
					
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
		
				exit();
			}
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($wind > 36) ){
		
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
						
exit();
				
			}
					
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($wind > 36) ){

							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){

							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
exit();
					
			}
					
					
						if 	(($humidity > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					
			}
					
						if 	(($humidity > 40) && ($wind > 36) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					
						if 	(($humidity > 40) && ($convert_temparature > 28) && ($convert_rain > 7.6) ){
	
				
			}
					if 	(($wind > 36) && ($uv_index> 6) && ($convert_rain > 7.6) ){
	
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
					exit();
			}
					if 	(($convert_temparature > 28) && ($uv_index> 6) && ($convert_rain > 7.6) ){
							
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
exit();
					
			}
					
						if 	(($convert_temparature > 28) && ($wind > 36) && ($convert_rain > 7.6) ){
								
				$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'MEDIUM',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '1',
  ]
);
 
wp_insert_post( $wordpress_post );
	exit();
				
			}
					
					
					
					//high one
					
					
				
					
					if 	(($convert_temparature > 35) || ($wind > 72) || ($uv_index> 10.9) || ($humidity > 60) || ($convert_rain>50 )){
						
	$date1 = date('H:i:s');
				$date2 = date('Y-m-d');
				$str2 = substr($date2 , 2);
						
				$wordpress_post = array(
'post_title' => '5 MIN. CHECK',
'post_status' => 'publish',
'post_author' => 76,
'post_type' => 'alerts',
					 'meta_input'  => [
    'worker_name' => 'Smart-5',
    'risk_grading' => 'HIGH',
	'date_split_alert' => $str2,
	'timestamp' => $date1,
	'read' => '0',
	'alert_type' => '2',
  ]
);
 
wp_insert_post( $wordpress_post );
				
					
				exit();
			}
				

				
			
				
				
				
				
						
		
			
				
    endwhile;
				
				
    wp_reset_postdata(); 
					
						
					}
	
				
					
				
				
					
				
	endwhile;		
				
			
				
		
				
				
    
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					

//}
				
				
    wp_reset_postdata(); 
					
				}
				?>



				
				
				








































			


<?

get_footer();
?>