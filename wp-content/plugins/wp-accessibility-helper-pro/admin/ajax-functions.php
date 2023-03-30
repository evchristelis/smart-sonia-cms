<?php
add_action( 'wp_ajax_update_attachment_title', 'update_attachment_title' );
function update_attachment_title() {

	$result = array();
	$pid    = isset($_POST['pid']) ? sanitize_text_field($_POST['pid']):       '';
	$ptitle = isset($_POST['ptitle']) ? sanitize_text_field($_POST['ptitle']): '';

	if($pid){
		$result['plink'] = get_permalink($pid);
		$result['pid']   = $pid;
		if($ptitle){
			$attachment_post = array(
				'ID'           => $pid,
				'post_title'   => $ptitle,
			);
			wp_update_post( $attachment_post );
			$result['ptitle']   = $ptitle;
		}
		echo json_encode( $result );
	}
	die();
}

add_action( 'wp_ajax_update_attachment_alt', 'update_attachment_alt' );
function update_attachment_alt() {

	$result = array();
	$pid    = isset($_POST['pid']) ? sanitize_text_field($_POST['pid']):       '';
	$palt   = isset($_POST['palt']) ? sanitize_text_field($_POST['palt']): '';

	if($pid){

		$result['plink'] = get_permalink($pid);
		$result['pid']   = $pid;

		if($palt){
			update_post_meta($pid, '_wp_attachment_image_alt', $palt);
			$alt            = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
			$result['palt'] = $alt;
		}

		echo json_encode( $result );

	}

	die();
}


//WAH SCANNER
add_action('wp_ajax_wah_scan_homepage','wah_scan_homepage');
function wah_scan_homepage(){

	$result   = array();
	$postID   = isset($_POST['postID']) ? sanitize_text_field($_POST['postID']) : '';
	$url      = get_permalink($postID);

	$response      = wp_remote_get( $url, array('timeout' => 20) );
	$response_code = wp_remote_retrieve_response_code( $response );
	$body          = isset($response) ? $response['body']:   '';

	if( $body && $postID && $response_code == 200 ){

		$scanner_array = array();

		//get all images
		preg_match_all('/<img[^>]+>/i',$body, $images);
		$scanner_array['images'] = $images[0];

		//get all links
		$regexp_links = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
		if(preg_match_all("/$regexp_links/siU", $body, $links)) {
			$scanner_array['links'] = $links[0];
		}

		if($scanner_array['images']){
			ob_start();
			?>

			<div class="form_row">
				<h3 class="wah_scanner_table_trigger">
					<span></span><?php _e("Images Report Table","wp-accessibility-helper"); ?>
				</h3>
				<table class="widefat fixed wah_scanner_table" cellspacing="5">
					<thead>
						<tr>
							<th class="manage-column column-cb check-column wah_th" scope="col" style="width:130px !important;">
								<?php _e("Thumbnail","wp-accessibility-helper"); ?>
							</th>
							<th class="manage-column image_src">
								<?php _e("Image Source","wp-accessibility-helper"); ?>
							</th>
							<th class="manage-column column-cb check-column wah_th" scope="col">
								<?php _e("Alt","wp-accessibility-helper"); ?>
							</th>
							<th class="manage-column column-cb check-column wah_th" scope="col">
								<?php _e("Width","wp-accessibility-helper"); ?>
							</th>
							<th class="manage-column column-cb check-column wah_th" scope="col">
								<?php _e("Height","wp-accessibility-helper"); ?>
							</th>
							<th class="manage-column column-cb check-column wah_th" scope="col">
								<?php _e("Action","wp-accessibility-helper"); ?>
							</th>
							<th class="manage-column column-cb check-column wah_th" scope="col">
								<?php _e("Preview","wp-accessibility-helper"); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($scanner_array['images'] as $key=>$image_html):

							preg_match( '@src="([^"]+)"@' , $image_html, $match_src );
							$src = array_pop($match_src);

							preg_match( '@alt="([^"]+)"@' , $image_html, $match_alt );
							$alt = array_pop($match_alt);

							preg_match( '@width="([^"]+)"@' , $image_html, $match_width );
							$width = array_pop($match_width);

							preg_match( '@width="([^"]+)"@' , $image_html, $match_height );
							$height = array_pop($match_height);

							$attachment_id = wah_get_attachment_id_by_src($src);

						?>
						<tr>
						<td class="wah_scanner_thumbnail"><?php echo $image_html; ?></td>
						<td>
							<?php echo $src ? '<xmp>'.$src.'</xmp>' : '<span class="not-valid">'.__("not valid","wp-accessibility-helper").'</span>'; ?>
						</td>
						<td>
							<?php echo $alt ? '<span class="valid">'.$alt.'</span>' : '<span class="not-valid">not valid</span>'; ?>
						</td>
						<td>
							<?php echo $width ? '<span class="valid">'.$width.'</span>' : '<span class="warning">X</span>'; ?>
						</td>
						<td>
							<?php echo $height ? '<span class="valid">'.$height.'</span>' : '<span class="warning">X</span>'; ?>
						</td>
						<td>
							<?php if($attachment_id){ ?>
								<a href="<?php echo get_edit_post_link( $attachment_id); ?>" target="_blank">
									<?php _e("Edit image","wp-accessibility-helper"); ?>
								</a>
							<?php } ?>
						</td>
						<td>
							<a target="_blank" href="<?php echo add_query_arg('wahi',base64_encode($src), get_permalink($postID));?>">
								<?php _e("Preview","wp-accessibility-helper"); ?>
							</a>
						</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<?php
			$result['images'] = ob_get_clean();
		}

		if( $scanner_array['links'] ) {
			ob_start();
		?>

		<div class="form_row">
			<h3 class="wah_scanner_table_trigger">
				<span></span><?php _e("Links Report Table","wp-accessibility-helper"); ?>
			</h3>
			<table class="widefat fixed wah_scanner_table" cellspacing="5">
				<thead>
					<tr>
						<th class="manage-column column-cb check-column wah_th" scope="col" style="width:26px;">
							<?php _e("ID","wp-accessibility-helper"); ?>
						</th>
						<th class="manage-column column-cb check-column wah_th" scope="col">
							<?php _e("Source","wp-accessibility-helper"); ?>
						</th>
						<th class="manage-column column-cb check-column wah_th" scope="col">
							<?php _e("URL","wp-accessibility-helper"); ?>
						</th>
						<th class="manage-column column-cb check-column wah_th" scope="col">
							<?php _e("Title","wp-accessibility-helper"); ?>
						</th>
						<th class="manage-column column-cb check-column wah_th" scope="col">
							<?php _e("Aria-label","wp-accessibility-helper"); ?>
						</th>
						<th class="manage-column column-cb check-column wah_th" scope="col">
							<?php _e("Preview","wp-accessibility-helper"); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$link_html_counter = 0;
					foreach($scanner_array['links'] as $key=>$link_html):
						$link_html_counter++;
						preg_match( '@href="([^"]+)"@' , $link_html, $match_href );
						$href = array_pop($match_href);

						preg_match( '@title="([^"]+)"@' , $link_html, $match_title );
						$title = array_pop($match_title);

						preg_match( '@aria-label="([^"]+)"@' , $link_html, $match_aria_label );
						$aria_label = array_pop($match_aria_label);
					?>
						<tr>
							<td><?php echo $link_html_counter; ?></td>
							<td class="wah_scanner_thumbnail">
								<xmp><?php echo $link_html; ?></xmp>
							</td>
							<td>
								<?php
								if($href && $href !="#") {
									echo $href;
								} elseif($href && $href =="#") {
									echo '<span class="warning">'.__("empty href","wp-accessibility-helper").'</span>';
								} else {
									echo '<span class="warning">X</span>';
								}
								?>
							</td>
							<td><?php echo $title ? $title : '<span class="warning">X</span>'; ?></td>
							<td><?php echo $aria_label ? $aria_label : '<span class="warning">X</span>'; ?></td>
							<td>
								<a href="<?php echo add_query_arg('wahl',base64_encode($href), get_permalink($postID));?>" target="_blank">
									<?php _e("Preview link","wp-accessibility-helper"); ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<?php
			$result['links'] = ob_get_clean();
		}

		$result['response_code'] = $response_code;

		echo json_encode( $result );

	} else {

		$result['response_code'] = $response_code;

		echo json_encode( $result );

	}

	die();

}

function wah_get_attachment_id_by_src($image_url){
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
	if($attachment){
		return $attachment[0];
	}
}

/**************************************************
*   Update image alt - front scanner
**************************************************/
add_action('wp_ajax_wah_update_image_alt','wah_update_image_alt');
add_action('wp_ajax_nopriv_wah_update_image_alt','wah_update_image_alt');
function wah_update_image_alt(){

    $response = array();
    $attachment_source = isset($_POST['target_src']) ? sanitize_text_field($_POST['target_src']):       '';
	$wah_alt_input     = isset($_POST['wah_alt_input']) ? sanitize_text_field($_POST['wah_alt_input']): '';

    if( $attachment_source ) {

        $attachment_id = get_attachment_id( $attachment_source );

        if($attachment_id && $wah_alt_input){
			update_post_meta($attachment_id, '_wp_attachment_image_alt', $wah_alt_input);
			$response['atid']    = $attachment_id;
            $response['status']  = 'ok';
            $response['message'] = __('Image has been updated.','wp-accessibility-helper');
        } else {
			$response['atid']    = -1;
            $response['status']  = 'error';
            $response['message'] = __('It look likes, this image is not on your server...','wp-accessibility-helper');
        }

        echo json_encode($response);
    }


    die();
}

/*****************************************************
*	Save wah widgets order
*****************************************************/
add_action( 'wp_ajax_wah_update_widgets_order', 'wah_update_widgets_order' );
function wah_update_widgets_order( $data ){
	$response      = '';
	$send_response = false;
	if( ! $data ){
		$send_response = true;
		$data = isset($_POST['alldata']) ? $_POST['alldata']: '';
	} else {
		$array = array();
		foreach( $data as $widget_id=>$widget_data ){
			$array[] = $widget_id;
		}
		$data = $array;
	}

	$widgets_status = wah_get_widgets_status();

    $widgetsObject = array();
    $widgetsObject["widget-1"] = array(
        "active" => 1,
        "html"   => __('Font resize', 'wp-accessibility-helper' ),
        "class"  => "active"
    );
    $widgetsObject["widget-2"] = array(
        "active" => $widgets_status['wah_keyboard_navigation_setup'],
        "html"   => __('Keyboard navigation', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_keyboard_navigation_setup'] ? "active" : "notactive"
    );
    $widgetsObject["widget-3"] = array(
        "active" => $widgets_status['wah_readable_fonts_setup'],
        "html"   => __('Readable Font', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_readable_fonts_setup'] ? "active" : "notactive"
    );
    $widgetsObject["widget-4"] = array(
        "active" => $widgets_status['contrast_setup'],
        "html"   => __('Contrast', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['contrast_setup'] ? "active" : "notactive"
    );
    $widgetsObject["widget-5"] = array(
        "active" => $widgets_status['underline_links_setup'],
        "html"   => __('Underline links', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['underline_links_setup'] ? "active" : "notactive"
    );
    $widgetsObject["widget-6"] = array(
        "active" => $widgets_status['wah_highlight_links_enable'],
        "html"   => __('Highlight links', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_highlight_links_enable'] ? "active" : "notactive"
    );
    $widgetsObject["widget-7"] = array(
        "active" => 1,
        "html"   => __('Clear cookies', 'wp-accessibility-helper' ),
        "class"  => "active"
    );
    $widgetsObject["widget-8"] = array(
        "active" => $widgets_status['wah_greyscale_enable'],
        "html"   => __('Image Greyscale', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_greyscale_enable'] ? "active" : "notactive"
    );
    $widgetsObject["widget-9"] = array(
        "active" => $widgets_status['wah_invert_enable'],
        "html"   => __('Invert colors', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_invert_enable'] ? "active" : "notactive"
    );
    $widgetsObject["widget-10"] = array(
        "active" => $widgets_status['wah_remove_animations_setup'],
        "html"   => __('Remove Animations', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_remove_animations_setup'] ? "active" : "notactive"
    );
    $widgetsObject["widget-11"] = array(
        "active" => $widgets_status['remove_styles_setup'],
        "html"   => __('Remove styles', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['remove_styles_setup'] ? "active" : "notactive"
    );
	$widgetsObject["widget-12"] = array(
        "active" => $widgets_status['wah_lights_off_setup'],
        "html"   => __('Lights Off', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_lights_off_setup'] ? "active" : "notactive"
    );
	$widgetsObject["widget-13"] = array(
        "active" => $widgets_status['wah_highlight_titles_setup'],
        "html"   => __('Highlight Titles', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_highlight_titles_setup'] ? "active" : "notactive"
    );
	$widgetsObject["widget-14"] = array(
        "active" => $widgets_status['wah_image_alt_setup'],
        "html"   => __('Image description', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_image_alt_setup'] ? "active" : "notactive"
    );
	$widgetsObject["widget-15"] = array(
        "active" => $widgets_status['wah_enable_terms_link'],
        "html"   => __('Custom link', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_enable_terms_link'] ? "active" : "notactive"
    );
	$widgetsObject["widget-16"] = array(
        "active" => $widgets_status['wah_enable_large_mouse_cursor'],
        "html"   => __('Large mouse cursor', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_enable_large_mouse_cursor'] ? "active" : "notactive"
    );
	$widgetsObject["widget-17"] = array(
        "active" => $widgets_status['wah_enable_monochrome_mode'],
        "html"   => __('Monochrome', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_enable_monochrome_mode'] ? "active" : "notactive"
    );
	$widgetsObject["widget-18"] = array(
        "active" => $widgets_status['wah_enable_sepia_mode'],
        "html"   => __('Sepia', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_enable_sepia_mode'] ? "active" : "notactive"
    );
	$widgetsObject["widget-19"] = array(
        "active" => $widgets_status['wah_enable_inspector_mode'],
        "html"   => __('Inspector mode', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_enable_inspector_mode'] ? "active" : "notactive"
    );
	$widgetsObject["widget-20"] = array(
        "active" => $widgets_status['wah_set_layout_setup'],
        "html"   => __('Select Theme', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_set_layout_setup'] ? "active" : "notactive"
    );
	$widgetsObject["widget-21"] = array(
        "active" => $widgets_status['wah_enable_letter_spacing_mode'],
        "html"   => __('Letter spacing', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_enable_letter_spacing_mode'] ? "active" : "notactive"
    );
	$widgetsObject["widget-22"] = array(
        "active" => $widgets_status['wah_enable_adhd'],
        "html"   => __('ADHD Profile', 'wp-accessibility-helper' ),
        "class"  => $widgets_status['wah_enable_adhd'] ? "active" : "notactive"
    );
	$widgetsObject["widget-23"] = array(
		"active" => $widgets_status['wah_text_alignment'],
		"html"   => __('Text alignment', 'wp-accessibility-helper' ),
		"class"  => $widgets_status['wah_text_alignment'] ? "active" : "notactive"
	);
	$widgetsObject["widget-24"] = array(
		"active" => $widgets_status['wah_enable_mute'],
		"html"   => __('MUTE volume', 'wp-accessibility-helper' ),
		"class"  => $widgets_status['wah_enable_mute'] ? "active" : "notactive"
	);

	$s_data = array();
	foreach( $data as $id ) {
		$s_data[$id] = $widgetsObject[$id];
	}

	$s_data = serialize($s_data);
	wah_set_param( 'wah_sidebar_widgets_order', $s_data );
	$response = 'ok';

	if( ! $send_response ){
		return $response;
	} else {
		echo json_encode($response);
		die();
	}

}

/*****************************************************
*	Add new contrast item from repeater
*****************************************************/
add_action( 'wp_ajax_add_new_contrast_item', 'add_new_contrast_item' );
function add_new_contrast_item(){
	$response = array();
	ob_start();
?>
	<li>
		<div class="contrast-mode-item bg-color">
			<label><?php _e('Background color','wp-accessibility-helper'); ?></label>
			<input type="text" class="jscolor" placeholder="<?php _e('Background color','wp-accessibility-helper'); ?>" />
		</div>
		<div class="contrast-mode-item text-color">
			<label><?php _e('Text color','wp-accessibility-helper'); ?></label>
			<input type="text" class="jscolor" placeholder="<?php _e('Text color','wp-accessibility-helper'); ?>" />
		</div>
		<div class="contrast-mode-item button-title-alt">
			<label><?php _e('Title','wp-accessibility-helper'); ?></label>
			<input type="text" placeholder="<?php _e('Button title','wp-accessibility-helper'); ?>" />
		</div>
		<div class="contrast-mode-item action">
			<button class="wah-button delete-contrast-params">
				<?php _e("Delete","wp-accessibility-helper"); ?>
			</button>
			<span class="action-loader"></span>
		</div>
	</li>
<?php
	$response['status'] = 'ok';
	$response['html'] = ob_get_clean();
	echo json_encode($response);
	die();
}

/*****************************************************
***	Remove contrast item from repeater				***
*****************************************************/
add_action( 'wp_ajax_remove_contrast_item', 'remove_contrast_item' );
function remove_contrast_item(){
	$response = array();
	$response['status'] = 'ok';
	echo json_encode($response);
	die();
}
/***************************************************
**		Save contrast variations				****
****************************************************/
add_action( 'wp_ajax_save_contrast_variations', 'save_contrast_variations' );
function save_contrast_variations(){
	$response = array();
	$alldata  = isset($_POST['alldata']) ? $_POST['alldata']: '';
	if( $alldata ){
		$data = serialize($alldata);
		update_option('wah_contrast_variations',$data);
		$response['status'] = 'ok';
		echo json_encode($response);
	}
	die();
}
/***************************************************
**		Save EMPTY contrast variations			****
****************************************************/
add_action( 'wp_ajax_save_empty_contrast_variations', 'save_empty_contrast_variations' );
function save_empty_contrast_variations(){
	$response = array();
	$alldata  = '';
	update_option('wah_contrast_variations',$alldata);
	$response['status'] = 'ok';
	$response['message'] = __("Removed!","wp-accessibility-helper");
	echo json_encode($response);
	die();
}

/****************************************************
*** 	Get all contrast variations				*****
****************************************************/
function wah_get_contrast_variations(){
	$contrast_variations = get_option('wah_contrast_variations');
	$contrast_variations = unserialize($contrast_variations);
	if($contrast_variations){
		return $contrast_variations;
	}
}
/****************************************************
*** 	WAH RESET WIDGETS ORDER					*****
****************************************************/
add_action( 'wp_ajax_wah_reset_widgets_order', 'wah_reset_widgets_order' );
function wah_reset_widgets_order(){
	$response = array();
	//update_option('wah_sidebar_widgets_order','');
	wah_set_param( 'wah_sidebar_widgets_order','' );
	$response['status'] = 'ok';
	$response['message'] = 'Done';
	echo json_encode($response);
	die();
}
/****************************************************
*** 	WAH PRO update license key				*****
****************************************************/
add_action( 'wp_ajax_wah_pro_validate_license_key', 'wah_pro_validate_license_key' );
function wah_pro_validate_license_key() {

	$form_data 	= isset( $_POST['form_data'] ) ? $_POST['form_data'] : '';
	$response  	= array();
	$params 	= array();
	parse_str( $form_data, $params );

	if( $params['wah_license_email'] && $params['wah_license_key'] ) {

		$license_validation 	= wahpro_send_license_data( $params['wah_license_email'], $params['wah_license_key']);
		$license_status 		= $license_validation ? $license_validation['search'][0]['Active'] : 0;
		$license_site_url 		= $license_validation ? $license_validation['search'][0]['ActivatedURL'] : '';
		$current_site_url		= get_site_url();

		if( $license_validation ) {
			// first time activation
			if( $license_status == 0 ) {

				$update_license_status  = wahpro_send_update_license_status( $params['wah_license_email'], $params['wah_license_key'], $current_site_url );
				update_option( 'wah_pro_license_key', $params['wah_license_key'] );
				update_option( 'wah_pro_license_email', $params['wah_license_email'] );
				$response['status']  = 'ok';
				$response['log']     = '1';
				$response['message'] = 'License key saved & activated successfuly. Thank you!';

			} elseif( $license_status == 1 && ( $license_site_url == $current_site_url ) ) {

				update_option( 'wah_pro_license_key', $params['wah_license_key'] );
				update_option( 'wah_pro_license_email', $params['wah_license_email'] );
				$response['status']  = 'ok';
				$response['log']     = '2';
				$response['message'] = 'This license is currently active for this website.';

			} elseif( $license_status == 1 && ( $license_site_url != $current_site_url ) ) {

				update_option( 'wah_pro_license_key', '');
				update_option( 'wah_pro_license_email', '');
				$response['status']  = 'error';
				$response['log']     = '3';
				$response['message'] = 'This license has already been activated. Please purchase another license key.';

			}
		} else {
			update_option( 'wah_pro_license_key', '');
			update_option( 'wah_pro_license_email', '');
			$response['status']  = 'error';
			$response['log']     = '4';
			$response['message'] = 'License key or email is invalid. Please try again.';
		}

	}

	delete_option("_site_transient_update_plugins");
	echo json_encode($response);
	die();
}

/********************************
	Render Accordion Shortcode
*******************************/
add_action( 'wp_ajax_generate_accordion_shortcode', 'generate_accordion_shortcode' );
function generate_accordion_shortcode(){

	$form   = isset( $_POST['form'] ) ? $_POST['form'] : '';
	$result = array();

	if( $form ) {

		$form_args = array();
		parse_str( $form, $form_args);

		$bg_active       = $form_args['wah-acc-bg-active'];
		$bg_default      = $form_args['wah-acc-bg-default'];
		$text_active     = $form_args['wah-acc-text-active'];
		$text_default    = $form_args['wah-acc-text-default'];
		$animations      = $form_args['wah-acc-animations'];
		$first_is_active = $form_args['wah-acc-first-is-active'];
		$wahid           = 'wacc-'.uniqid().date('dmYHis');

		ob_start();

		echo '[wah-accordion wah-id="'.$wahid.'" bg-active="'.$bg_active.'" first-is-active="'.$first_is_active.'" animation="'.$animations.'" bg-default="'.$bg_default.'" text-active="'.$text_active.'" text-default="'.$text_default.'" ]';

		// unset settings params from main shortcode
		unset( $form_args['wah-acc-bg-active'] );
		unset( $form_args['wah-acc-bg-default'] );
		unset( $form_args['wah-acc-text-active'] );
		unset( $form_args['wah-acc-text-default'] );

		unset( $form_args['wah-acc-first-is-active'] );
		unset( $form_args['wah-acc-animations'] );

		$items_counter = 0;
		foreach( $form_args as $item ) {
			$active = '';
			if( $items_counter == 0 && $first_is_active ) {
				$active = 'is-active';
			}
			echo '[wah-accordion-item title="'.$item['wah-acc-title'].'" is-active="'.$active.'"]'.$item['wah-acc-content'].'[/wah-accordion-item]';

			$items_counter++;
		}

		echo '[/wah-accordion]';

		$result['html'] = ob_get_clean();

		echo json_encode($result);

		die();

	}

}
