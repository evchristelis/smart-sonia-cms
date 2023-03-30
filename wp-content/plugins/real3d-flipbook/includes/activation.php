<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class='wrap'>

  <p id="msg"><?php _e('Updating flipbooks...', 'real3d-flipbook');?></p>
	
</div>
<?php 

wp_enqueue_script( "real3d-flipbook-activation"); 
$r3d_nonce = wp_create_nonce( "r3d_nonce");
wp_localize_script( 'real3d-flipbook-activation', 'r3d_nonce', array($r3d_nonce) );