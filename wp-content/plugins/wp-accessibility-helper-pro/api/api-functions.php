<?php
/****************************
  WAH PRO & Plugin API
  DO NOT touch this file!
*****************************/

// Get WAH PRO license key
function get_wah_pro_license_key() {
    $wah_pro_license_key = '';
    if( get_option('wah_pro_license_key') ) {
        $wah_pro_license_key = get_option('wah_pro_license_key');
    }
    return $wah_pro_license_key;
}

// GET WAH PRO license email
function get_wah_pro_license_email() {
    $wah_pro_license_email = '';
    if( get_option('wah_pro_license_email') ) {
        $wah_pro_license_email = get_option('wah_pro_license_email');
    }
    return $wah_pro_license_email;
}

// Add plugin row meta links
add_filter( 'plugin_row_meta', 'wahpro_plugin_row_meta', 10, 2 );
function wahpro_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'wp-accessibility-helper-pro.php' ) !== false ) {

        $license_activated = 0;
        if ( get_wah_pro_license_key() ) {
          $license_activated = 1;
        }
        $wah_settings_url = admin_url('admin.php?page=wp_accessibility');
		$new_links = array(
        'doc' => '<a href="https://accessibility-helper.co.il/docs/" target="_blank">Documentation</a>',
        'wahpro_settings' => '<a href="'.$wah_settings_url.'">Settings</a>',
        'license' => $license_activated ? '<span style="color:green;font-weight:bold;">License activated</span>' : '<span style="color:red;font-weight:bold;">License not activated</span>'
		);

		$links = array_merge( $links, $new_links );
	}

	return $links;
}

// WAH PRO validate user license key
function wahpro_send_license_data( $license_email, $license_key ) {

	// If empty params return false
	if( empty( $license_email ) || empty( $license_key ) ) {
		return false;
	}

	$url 	= 'https://accessibility-helper.co.il/?action=wah-check-license';
	$fields = array(
		'lemail' => urlencode( $license_email ),
		'lkey' 	 => urlencode( $license_key )
	);

	$fields_string = http_build_query( $fields );

	//open connection
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_POST , count( $fields ) );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS , $fields_string );

	//execute post
	$result = curl_exec($ch);

	//close connection
	curl_close($ch);

	$result = json_decode( $result, true );

	if( $result && isset( $result['search'][0]['LicenseKey'] ) ) {
		return $result;
	}

	return false;
}

// Update site status after license checked
function wahpro_send_update_license_status( $email, $license, $siteurl ){

    // If empty params return false
    if( empty( $email ) || empty( $license ) || empty( $siteurl ) ) {
        return false;
    }

    // Send data to server
    $url 	= 'https://accessibility-helper.co.il/?action=wah-update-license-status';
    $fields = array(
        'email'      => urlencode( $email ),
        'license' 	 => urlencode( $license ),
        'siteurl' 	 => urlencode( $siteurl )
    );

    $fields_string = '';

    foreach( $fields as $key=>$value ) {
        $fields_string .= $key.'='.$value.'&';
    }

    rtrim($fields_string, '&');

    //open connection
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_POST, count($fields) );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields_string );

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);

    if( $result ) {
        return true;
    }

    return false;
}
