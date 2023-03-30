<?php 

// preloader types
function mg_preloader_types($type = false) {
	$types = array(
		'default' 				=> __('Default loader', MG_ML),
		'rotating_square' 		=> __('Rotating square', MG_ML),
		'overlapping_circles' 	=> __('Overlapping circles', MG_ML),
		'stretch_rect' 			=> __('Stretching rectangles', MG_ML),
		'spin_n_fill_square'	=> __('Spinning & filling square', MG_ML),
		'pulsing_circle' 		=> __('Pulsing circle', MG_ML),
		'spinning_dots'			=> __('Spinning dots', MG_ML),
		'appearing_cubes'		=> __('Appearing cubes', MG_ML),
		'folding_cube'			=> __('Folding cube', MG_ML),
		'old_style_spinner'		=> __('Old-style spinner', MG_ML),
		'minimal_spinner'		=> __('Minimal spinner', MG_ML),
		'spotify_like'			=> __('Spotify-like spinner', MG_ML),
		'vortex'				=> __('Vortex', MG_ML),
		'bubbling_dots'			=> __('Bubbling Dots', MG_ML),
		'overlapping_dots'		=> __('Overlapping dots', MG_ML),
		'fading_circles'		=> __('Fading circles', MG_ML),
	);
	return (!$type) ? $types : $types[$type];
}



// inline slider effects
function mg_inl_slider_fx($type = false) {
	$types = array(
		'fadeslide' => __('Fade and slide', MG_ML),
		'fade' 		=> __('Fade', MG_ML),
		'slide'		=> __('Slide', MG_ML),
		'v_slide'	=> __('Vertical slide', MG_ML),
		'overlap'	=> __('Overlap', MG_ML),
		'v_overlap'	=> __('Vertical overlap', MG_ML),
		'zoom-in'	=> __('Zoom-in', MG_ML),
		'zoom-out'	=> __('Zoom-out', MG_ML),
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}



// WP pages ilst
function mg_pages_list() {
	$pages = array();
	
	foreach(get_pages() as $pag) {
		$pages[ $pag->ID ] = $pag->post_title;	
	}
	
	return $pages;	
}



// lightbox command layouts
function mg_lb_cmd_layouts($type = false) {
	$types = array(
		'inside' 	 	=> __('Inside lightbox', MG_ML),
		'above' 	 	=> __('Above lightbox', MG_ML),
		'top' 			=> __('Detached - top of the page', MG_ML),
		'side'			=> __('Detached - on sides', MG_ML),
		'side_basic'	=> __('Detached - on sides (basic)', MG_ML),
		'ins_hidden'	=> __('Inside - hidden navigation', MG_ML),
		'hidden'		=> __('Detached - hidden navigation', MG_ML),
		'round_hidden'	=> __('Rounded - hidden navigation', MG_ML)
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}


// lightbox bg effects
function mg_lb_bg_showing_fx() {
	$opts = array(
		'' => __("no effect", MG_ML),
		'zoom-in' 	=> __("zoom-in", MG_ML),
		'zoom-out' 	=> __("zoom-out", MG_ML),
		'zoom-flip' => __("zoom & flip", MG_ML),
		'skew' 		=> __("skew", MG_ML),
		
		'symm_vert' => __("symmetrical vertical", MG_ML),
		'symm_horiz' => __("symmetrical horizontal", MG_ML),
		
		'genie_t_side' => __("genie | top side", MG_ML),
		'genie_r_side' => __("genie | right side", MG_ML),
		'genie_b_side' => __("genie | bottom side", MG_ML),
		'genie_l_side' => __("genie | left side", MG_ML),
		
		'slide_corn_tr' => __("slide | top-right corner", MG_ML),
		'slide_corn_br' => __("slide | bottom-right corner", MG_ML),
		'slide_corn_bl' => __("slide | bottom-left corner", MG_ML),
		'slide_corn_tl' => __("slide | top-left corner", MG_ML),
		
		'slide_t_side' => __("slide | top side", MG_ML),
		'slide_r_side' => __("slide | right side", MG_ML),
		'slide_b_side' => __("slide | bottom side", MG_ML),
		'slide_l_side' => __("slide | left side", MG_ML),
	);	
	
	return $opts;
}


// lightbox slider thumbs nav opts
function mg_lb_thumb_nav_mode($type = false) {
	$types = array(
		'always'	=> __('Always shown', MG_ML),
        'yes' 		=> __('Show with toggle button', MG_ML),
        'no' 		=> __('Hide with toggle button', MG_ML),
	);
	
	if($type === false) {return $types;}
	else {return $types[$type];}
}


// easings
function mg_easings() {
	$opts = array(
		'ease' => __("ease", MG_ML),
		'linear' => __("linear", MG_ML),
		'ease-in' => __("ease-in", MG_ML),
		'ease-out' => __("ease-out", MG_ML),
		'ease-in-out' => __("ease-in-out", MG_ML),
		'ease-in-back' => __("ease-in-back", MG_ML),
		'ease-out-back' => __("ease-out-back", MG_ML),
		'ease-in-out-back' => __("ease-in-out-back", MG_ML)
	);	
	
	return $opts;
}

