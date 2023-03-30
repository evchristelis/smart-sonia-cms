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
<script src="https://www.paypalobjects.com/api/checkout.js"></script>

<!-- Section -->
<section class="grid-csssection">	
	<div class="row">
		<?php if(isset($user_packages) && count($user_packages)) : ?>
			<?php foreach ($user_packages as $key => $package) : ?>				
				<div class="col-md-6 col-lg-4 ">
					
					<!-- acf-subscription__element -->
					<div class="acf-subscription__element">
						<div class="acf-subscription__icon"><i class="pe-7s-cash"></i>
							<h3 class="acf-subscription__title"><?php echo get_the_title($package['package_id']); ?></h3>
							<div class="acf-subscription__text">
								<p><?php _e('Expire date:', ACF_FORM_BUILDER_TEXTDOMAIN); ?> <?php echo date('M j, Y', $package['expiry_time']); ?></p>
								<p><?php _e('Articles left:', ACF_FORM_BUILDER_TEXTDOMAIN); ?> <?php echo $package['number_of_posts']; ?></p>
								<p><?php _e('Articles today:', ACF_FORM_BUILDER_TEXTDOMAIN); ?> <?php echo $package['number_of_posts_by_time']; ?></p>
							</div>
							<div class="acf-subscription__btn">
								<!-- <a class="grid-cssbtn grid-cssbtn--primary" href="#"><?php _e('Buy features', ACF_FORM_BUILDER_TEXTDOMAIN); ?>
								</a> -->
								<a class="grid-cssbtn" href="<?php the_permalink( $acf_options['checkout_page'] ); ?>?package_id=<?php echo $package['package_id']; ?>"><?php _e('Renew', ACF_FORM_BUILDER_TEXTDOMAIN); ?>
								</a>
							</div>
						</div>
					</div><!-- End / acf-subscription__element -->
					
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</section>
<!-- End / Section -->
