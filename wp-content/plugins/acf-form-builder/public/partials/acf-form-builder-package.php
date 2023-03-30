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
<?php do_action('acf_fb_before_render_purchased_package') ; ?>
<?php
if ( $packages->have_posts() ) {	

	if(isset($form_settings['not_match_package'])){
		echo '<div class="not_match_package">' . $form_settings['not_match_package'] . '</div>';
	}

	if($form_settings['column'] == 2){
		$column = 'col-md-6 col-sm-6 col-xs-12';
	}else if($form_settings['column'] == 3){
		$column = 'col-md-4 col-sm-4 col-xs-12';
	}else if($form_settings['column'] == 4 || $form_settings['column'] == 5){
		$column = 'col-md-3 col-sm-3 col-xs-12';
	}else if($form_settings['column'] == 6){
		$column = 'col-md-2 col-sm-2 col-xs-12';
	}else{
		$column = 'col-md-12 col-sm-12 col-xs-12';
	}

	?>
	<div class="row">
	<?php
	while ( $packages->have_posts() ) {
		$packages->the_post();
		
		$post_meta = get_post_meta(get_the_ID());
		$query = array(			
			'package_id' => get_the_ID(),
		);

		if($group_id){
			$query['group_id'] = $group_id;
		}

		if (isset($post_meta['_acf_package_metabox'])) {
			$package_settings = unserialize($post_meta['_acf_package_metabox'][0]);
		}

		if(is_numeric($package_settings['package_price']) && $package_settings['package_price']){
			$checkout_link = get_permalink($form_settings['checkout_page']) . '?' . http_build_query($query);
		}
		else{
			$query['set_free_package'] = true;
			$checkout_link = get_permalink($form_settings['redirect_after_checkout']) . '?' . http_build_query($query);
		}		

		switch ($form_settings['subscription_style']) {
			case 'style-1':

		?>			
			<div class="<?php echo $column; ?>">
				<div class="pricing-04 pricing-04--02 pricing-item">
					<div class="pricing__icon"><i class="fi flaticon-layers"></i></div>
					<h3 class="pricing__title"><?php the_title(); ?></h3>
					<div class="price-01"><span class="price__currency">$</span><span class="price__number"><?php echo $package_settings['package_price'] ? $package_settings['package_price'] : __('Free', ACF_FORM_BUILDER_TEXTDOMAIN); ?></span></div>
					<?php if(count($package_settings['package_feature_group']) && !empty($package_settings['package_feature_group'])) : ?>
					<ul class="pricing__feature-list">
						<?php foreach ($package_settings['package_feature_group'] as $key => $package_feature_group) : ?>
							<li><i class="<?php echo $package_feature_group['package_feature_icon']; ?>"></i> <?php echo $package_feature_group['package_feature_text']; ?></li>
						<?php endforeach; ?>
					</ul>
						<?php endif; ?>
					<a href="<?php echo $checkout_link; ?>" class="btn btn-secondary btn-rounder-lg btn-w200"><?php _e('Get Started', ACF_FORM_BUILDER_TEXTDOMAIN); ?></a>
				</div>
			</div>
		<?php				
				break;
			case 'style-2':
		?>
			<div class="<?php echo $column; ?>">
				<div class="pricing-08 pricing-item">
					<h3 class="pricing__title"><?php the_title(); ?></h3>

					<div class="price-01 price-01--05"><?php echo $package_settings['package_price'] ? '<span class="price__currency">$</span>' . $package_settings['package_price'] : __('Free', ACF_FORM_BUILDER_TEXTDOMAIN); ?></div>

					<span class="acf_fb_tag tag-default">Discount 10%</span>
					<?php if(count($package_settings['package_feature_group']) && !empty($package_settings['package_feature_group'])): ?>
						<ul class="pricing__feature-list">
							<?php foreach ($package_settings['package_feature_group'] as $key => $package_feature_group) : ?>
								<li><?php echo $package_feature_group['package_feature_text']; ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<a href="<?php echo $checkout_link; ?>" class="btn btn-secondary btn-rounder-lg btn-w200"><?php _e('Get Started', ACF_FORM_BUILDER_TEXTDOMAIN); ?></a>
				</div>
			</div>
		<?php
				break;
			case 'style-3':
		?>
			<div class="<?php echo $column; ?>">
				<div class="pricing-wrapper pricing-item">
					<div class="pricing-05">
						<h3 class="pricing__title"><?php the_title(); ?></h3>

						<div class="price-01"><span class="price__currency"></span><span class="price__number"><?php echo $package_settings['package_price'] ? '$' . $package_settings['package_price'] : __('Free', ACF_FORM_BUILDER_TEXTDOMAIN); ?></span></div>
						<?php if(count($package_settings['package_feature_group']) && !empty($package_settings['package_feature_group'])): ?>
						<div class="pricing__description">
							<?php foreach ($package_settings['package_feature_group'] as $key => $package_feature_group) : ?>
								<p><?php echo $package_feature_group['package_feature_text']; ?></p>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
						<a href="<?php echo $checkout_link; ?>" class="btn btn-secondary btn-rounder-lg btn-w200"><?php _e('Get Started', ACF_FORM_BUILDER_TEXTDOMAIN); ?></a>
					</div> <!-- prcing-05-->
				</div>
			</div>
		<?php
				break;
			default:
		?>
			<div class="<?php echo $column; ?>">
				<div class="pricing-02 pricing-02--04 pricing-item">
					<h3 class="pricing__title"><?php the_title(); ?></h3>

					<div class="price-01 price-01--03"><?php echo $package_settings['package_price'] ? '<span class="price__currency">$</span><span class="price__number">' . $package_settings['package_price'] . '</span>' : __('Free', ACF_FORM_BUILDER_TEXTDOMAIN); ?></div>
					<?php if(count($package_settings['package_feature_group']) && !empty($package_settings['package_feature_group'])): ?>
					<ul class="pricing__feature-list">
						<?php foreach ($package_settings['package_feature_group'] as $key => $package_feature_group) : ?>
							<li><?php echo $package_feature_group['package_feature_text']; ?></li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
					<a href="<?php echo $checkout_link; ?>" class="btn btn-secondary btn-rounder-lg btn-w200"><?php _e('Get Started', ACF_FORM_BUILDER_TEXTDOMAIN); ?></a>
				</div>
			</div>
		<?php
				break;
		}
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	?>
	</div>
	<?php
} else {
	_e('No available packages. Please add new one.');
}

do_action('acf_fb_after_render_purchased_package') ; ?>
