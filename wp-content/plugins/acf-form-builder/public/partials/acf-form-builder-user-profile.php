<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://support.catsplugins.com
 * @since      1.0.0
 *
 * @package    Acf_Form_Builder
 * @subpackage Acf_Form_Builder/public/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<!-- Section -->
<section class="grid-csssection">	
	<div class="row">
		<div class="row">
			<div class="col-md-4 col-lg-4 "><?php echo get_avatar( $user_data->user_email, '300' ); ?>
			</div>
			<div class="col-md-8 col-lg-8 ">
				<h3><?php _e('Hello', ACF_FORM_BUILDER_TEXTDOMAIN); ?> <?php echo $user_meta['first_name'][0]; ?>!</h3>
				<div class="profile-item"><strong><?php _e('Firstname:', ACF_FORM_BUILDER_TEXTDOMAIN); ?></strong> <?php echo $user_meta['first_name'][0]; ?></div>
				<div class="profile-item"><strong><?php _e('Lastname:', ACF_FORM_BUILDER_TEXTDOMAIN); ?></strong> <?php echo $user_meta['last_name'][0]; ?></div>
				<div class="profile-item"><strong><?php _e('Email:', ACF_FORM_BUILDER_TEXTDOMAIN); ?></strong> <?php echo $user_data->user_email; ?></div>
				<div class="profile-item"><strong><?php _e('About me:', ACF_FORM_BUILDER_TEXTDOMAIN); ?></strong> <?php echo $user_meta['description'][0]; ?></div>
				<?php if(count($user_profiles)) : ?>
					<h2><?php _e('ACF custom fields', ACF_FORM_BUILDER_TEXTDOMAIN); ?></h2>				
					<?php foreach($user_profiles as $key => $field) : ?>
						<div class="profile-item"><strong><?php echo $field['name']; ?>:</strong> <?php echo $field['value']; ?></div>					
					<?php endforeach; ?>
				<?php endif; ?>				
			</div>
		</div>
	</div>
</section>
<!-- End / Section -->
