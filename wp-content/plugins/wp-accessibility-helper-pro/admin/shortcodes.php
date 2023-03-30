<?php
/***************************
    WAH PRO Shortcodes
***************************/
add_shortcode( 'wah_pro_widget', 'init_wah_pro_widget' );
function init_wah_pro_widget( $atts , $content = null ) {

	$wah_font_setup_type = wah_get_param( 'wah_font_setup_type' );

	// Attributes
	$atts = shortcode_atts(
		array(
			'class' => '',
            'title' => '',
            'type'  => '',
		),
		$atts,
		'wah_pro_widget'
	);

    if( isset( $atts['type'] ) && !empty( $atts['type'] ) ) {

        ob_start();

        if( $atts['type'] == 'wah_font_resize' ) { ?>

            <span class="wah_shortcode_element font_resizer <?php echo isset($atts['class']) ? $atts['class'] : ''; ?>">
				<button type="button" class="wah-action-button smaller wahout"
					title="<?php _e('smaller font size','wp-accessibility-helper'); ?>"
					aria-label="<?php _e('smaller font size','wp-accessibility-helper'); ?>">A-</button>
				<button type="button" class="wah-action-button larger wahout"
					title="<?php _e('larger font size','wp-accessibility-helper'); ?>"
					aria-label="<?php _e('larger font size','wp-accessibility-helper'); ?>">A+</button>
				<?php if( $wah_font_setup_type == 'script' ) : ?>
					<button type="button" class="wah-action-button wah-font-reset wahout"
						title="<?php _e('Reset font size','wp-accessibility-helper'); ?>"
						aria-label="<?php _e('Reset font size','wp-accessibility-helper'); ?>"><?php _e('Reset font size','wp-accessibility-helper'); ?></button>
				<?php endif; ?>
            </span>

        <?php } elseif ( $atts['type'] == 'wah_disable_animations' ) { ?>

			<?php $title = isset( $atts['title'] ) && !empty( $atts['title'] ) ? $atts['title'] : __("Remove Animations","wp-accessibility-helper"); ?>
            <span class="wah_shortcode_element <?php echo isset($atts['class']) ? $atts['class'] : ''; ?>">
				<button type="button" class="wah-action-button wahout wah-call-remove-animations"
					aria-label="<?php echo $title; ?>" title="<?php echo $title; ?>">
						<?php echo $title; ?>
				</button>
            </span>

        <?php } elseif ( $atts['type'] == 'wah_underline_links' ) { ?>

			<?php $title = isset( $atts['title'] ) && !empty( $atts['title'] ) ? $atts['title'] : __("Underline links","wp-accessibility-helper"); ?>
            <span class="wah_shortcode_element <?php echo isset($atts['class']) ? $atts['class'] : ''; ?>">
				<button type="button" class="wah-action-button wahout wahpro_underline_links wah-call-underline-links"
					aria-label="<?php echo $title; ?>" title="<?php echo $title; ?>">
					<?php echo $title; ?>
				</button>
            </span>

		<?php } elseif ( $atts['type'] == 'wah_letter_spacing' ) { ?>

			<?php $title = isset( $atts['title'] ) && !empty( $atts['title'] ) ? $atts['title'] : __("Letter spacing","wp-accessibility-helper"); ?>
            <span class="wah_shortcode_element <?php echo isset($atts['class']) ? $atts['class'] : ''; ?>">
				<button type="button" class="wah-action-button wahout set-wah-letter_spacing"
					aria-label="<?php echo $title; ?>" title="<?php echo $title; ?>">
					<?php echo $title; ?>
				</button>
            </span>

        <?php } elseif ( $atts['type'] == 'wah_images_greyscale' ) { ?>

			<?php $title = isset( $atts['title'] ) && !empty( $atts['title'] ) ? $atts['title'] : __("Images Greyscale","wp-accessibility-helper"); ?>
            <span class="wah_shortcode_element <?php echo isset($atts['class']) ? $atts['class'] : ''; ?>">
				<button type="button" class="greyscale wah-action-button wahout wah-call-greyscale"
				aria-label="<?php echo $title; ?>" title="<?php echo $title; ?>">
					<?php echo $title; ?>
				</button>
            </span>

        <?php } elseif ( $atts['type'] == 'wah_readable_fonts' ) { ?>

			<?php $title = isset( $atts['title'] ) && !empty( $atts['title'] ) ? $atts['title'] : __("Readable Fonts","wp-accessibility-helper"); ?>
            <span class="wah_shortcode_element readable_fonts <?php echo isset($atts['class']) ? $atts['class'] : ''; ?>">
				<button type="button" class="wah-action-button wahout wah-call-readable-fonts"
				aria-label="<?php echo $title; ?>" title="<?php echo $title; ?>">
					<?php echo $title; ?>
				</button>
            </span>

        <?php } elseif ( $atts['type'] == 'wah_invert_colors' ) { ?>

			<?php $title = isset( $atts['title'] ) && !empty( $atts['title'] ) ? $atts['title'] : __("Invert Colors","wp-accessibility-helper"); ?>
            <span class="wah_shortcode_element <?php echo isset($atts['class']) ? $atts['class'] : ''; ?>">
				<button type="button" aria-label="<?php echo $title; ?>" title="<?php echo $title; ?>"
				class="wah-action-button wahout wah-call-invert">
					<?php echo $title; ?>
				</button>
            </span>

        <?php } elseif ( $atts['type'] == 'wah_highlight_links' ) { ?>

			<?php $title = isset( $atts['title'] ) && !empty( $atts['title'] ) ? $atts['title'] : __("Highlight Links","wp-accessibility-helper"); ?>
            <span class="wah_shortcode_element <?php echo isset($atts['class']) ? $atts['class'] : ''; ?>">
				<button type="button" aria-label="<?php echo $title; ?>" title="<?php echo $title; ?>"
				class="wah-action-button wahout wah-call-highlight-links">
					<?php echo $title; ?>
				</button>
            </span>

        <?php } elseif ( $atts['type'] == 'wahpro_accessibility_bar' ) {
			$enabled_widgets = wah_calculate_enabled_widgets();
		?>

            <div class="wah-pro-accessibility-bar-widget">
				<!-- WP Accessibility Helper PRO [accessibility bar widget] -->
					<?php if($enabled_widgets): $widgets = 0; ?>
						<div class="wah-pro-accessibility-bar">
							<?php foreach($enabled_widgets as $widget): $widgets++; ?>
								<div class="wah-pro-accessibility-bar-item bar-item-<?php echo $widgets; ?>">
									<?php echo $widget['html']; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				<!-- WP Accessibility Helper PRO [Created by Alex Volkov] -->
            </div>

        <?php }

        $widget_html = ob_get_clean();

    	return $widget_html;

    }

}

add_shortcode( 'wah_pro_popup', 'init_wahpro_popup' );
function init_wahpro_popup( $atts, $content = null ) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'wahpopup_id'                => '',
			'wahpopup_trigger'           => '',
			'wahpopup_title'             => '',
			'wahpopup_content'           => '',
			'wahpopup_close_title'       => '',
			'wahpopup_close_label'       => '',
			'wahpopup_width'		     => ''
		),
		$atts,
		'wah_pro_popup'
	);

	if( isset( $atts['wahpopup_trigger'] ) || isset( $atts['wahpopup_title'] ) || isset( $content ) ) {

		add_action( 'wp_footer', function() use ( $atts ,$content ) {
			render_wahpro_popup( $atts , $content);
		}, 11 );

		ob_start();
	?>

	<button class="wah-popup-trigger" type="button" data-dialogid="<?php echo $atts['wahpopup_id']; ?>">
		<?php echo $atts['wahpopup_trigger']; ?>
	</button>

	<?php

		$html = ob_get_clean();

	}

	return $html;
}

function render_wahpro_popup( $atts ,$content ) { ?>

	<div class="wah-dialog-popup" role="dialog" aria-label="<?php echo $atts['wahpopup_title']; ?>" tabindex="0"
			aria-hidden="true" data-dialogid="<?php echo $atts['wahpopup_id']; ?>">

		<div class="modal-document" role="document">

			<div class="wah-dialog-popup-inner" <?php if( $atts['wahpopup_width'] ): ?>style="max-width:<?php echo $atts['wahpopup_width']; ?>"<?php endif; ?>>

				<?php do_action('wah_dialog_inner_start'); ?>

				<?php if( $atts['wahpopup_title'] ) : ?>
					<h1 class="wah-dialog-title" ><?php echo $atts['wahpopup_title']; ?></h1>
				<?php endif; ?>

				<?php if( $content ) : ?>
					<div class="wah-dialog-content">
						<?php echo do_shortcode( $content ); ?>
					</div>
				<?php endif; ?>

				<?php do_action('wah_dialog_inner_end'); ?>

				<button type="button" aria-label="<?php echo $atts['wahpopup_close_label']; ?>" class="wah-close-dialog">
					<?php echo !empty( $atts['wahpopup_close_title'] ) ? $atts['wahpopup_close_title'] : __("Close","wp-accessibility-helper"); ?>
				</button>

			</div>

		</div>

	</div>

<?php }

add_shortcode( 'wah_minibar', 'init_wah_minibar' );
function init_wah_minibar( $atts, $content = null ) {
	// Attributes
	$atts = shortcode_atts(
		array(),
		$atts,
		'wah_minibar'
	);

	ob_start();
?>

<div class="wah-access-bar-container">
    <div class="wah-access-bar-buttons">
		<button type="button" name="wah-fontsize-toggle">
			<i class="goi-font-size-plus"></i>
		</button>
		<button type="button" name="wah-contrast-toggle">
			<i class="goi-black-and-white"></i>
		</button>
		<button type="button" name="wah-invert-toggle">
			<i class="goi-crossed-out-drop"></i>
		</button>
    </div>
</div>

<?php

	$html = ob_get_clean();
	return $html;

}

/******************
	accordion
*******************/
// Create Shortcode wah-accordion
// Use the shortcode: [wah-accordion]Content[/wah-accordion]
function create_wahaccordion_shortcode( $atts, $content ) {
	ob_start();
	$animations = $atts['animation'];
	$animation_class = '';
	if( $animations ){
		$animation_class = 'is-animated';
	}
?>
	<div id="<?php echo $atts['wah-id']; ?>" class="wah-accordion-container <?php echo $animation_class; ?>">
		<style>
			#<?php echo $atts['wah-id']; ?> ul li a { background: <?php echo $atts['bg-default']; ?>; color: <?php echo $atts['text-default']; ?>;}
			#<?php echo $atts['wah-id']; ?> ul li.is-active a { background: <?php echo $atts['bg-active']; ?>; color: <?php echo $atts['text-active']; ?>;}
		</style>
		<ul class="wah-accordion">
			<?php do_shortcode( $content ); ?>
		</ul>
	</div>
<?php
	return ob_get_clean();
}
add_shortcode( 'wah-accordion', 'create_wahaccordion_shortcode' );

// Create Shortcode wah-accordion-item
// Use the shortcode: [wah-accordion-item title=""]Content[/wah-accordion-item]
function create_wahaccordionitem_shortcode( $atts, $content ) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'title'     => '',
			'is-active' => ''
		),
		$atts,
		'wah-accordion-item'
	);
	// Attributes in var
	$title     = $atts['title'];
	$is_active = $atts['is-active'];

	if( $title ) { ?>

		<?php ob_start(); ?>
			<li class="wah-accordion-item <?php echo $is_active; ?>">
				<a href="#" class="wah-accordion-title"><?php echo $title; ?></a>
				<div class="wah-accordion-content" id="wah-panel-1">
					<?php echo do_shortcode($content); ?>
				</div>
			</li>
		<?php echo ob_get_clean(); ?>
	<?php }

}
add_shortcode( 'wah-accordion-item', 'create_wahaccordionitem_shortcode' );
