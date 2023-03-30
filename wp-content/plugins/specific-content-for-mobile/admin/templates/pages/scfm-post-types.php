<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

$options = eos_scfm_get_main_options_array();
// $post_types = array_merge( array( 'page' ),get_post_types( array( 'publicly_queryable' => true,'public' => true ),'names' ) );
$post_types = array_unique( array_merge( array( 'page' ),get_post_types() ) );
// if( isset( $post_types['attachment'] ) ){
// 	unset( $post_types['attachment'] );
// }
$active_post_types = isset( $options['active_post_types'] ) && is_array( $options['active_post_types'] ) && !empty( $options['active_post_types'] ) ? $options['active_post_types'] : array( 'post','page' );
?>
<h2 style="margin-top:32px">
	<i class="dashicons-smartphone dashicons"></i>
	<?php printf( esc_html__( 'Specific Content For Mobile v%s','eos-scfm' ),EOS_SCFM_PLUGIN_VERSION ); ?>
</h2>
<section>
	<h3><?php esc_html_e( 'Post Types','eos-scfm' ); ?></h3>
	<?php
	foreach( $post_types as $scfm_post_type ){
		$postTypeObj = get_post_type_object( $scfm_post_type );
		$labels = get_post_type_labels( $postTypeObj );
		$labels_name = $labels->name;
		$checked = in_array( $scfm_post_type,$active_post_types ) ? ' checked' : '';
		?>
		<div class="scfm-top-16">
			<input id="<?php echo esc_attr( $scfm_post_type ); ?>-activation" name="<?php echo esc_attr( $scfm_post_type ); ?>-activation" type="checkbox" class="eos-scfm-option" <?php echo $checked; ?>/>
			<a href="<?php echo add_query_arg( 'post_type',$scfm_post_type,admin_url( 'edit.php' ) ); ?>">
				<?php echo esc_html( $labels_name ); ?>
			</a>
		</div>
	<?php } ?>
</section>
<?php eos_scfm_save_button(); ?>
