<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}
?>
<div class="wpacu_asset_options_wrap">
	<?php
	if ($data['row']['global_unloaded']) {
    ?>
		<div style="display: inline-block; margin-right: 15px;"><strong style="color: #d54e21;"><?php _e('This stylesheet file is unloaded site-wide (everywhere)', 'wp-asset-clean-up'); ?>.</strong></div>
    <?php
	}
	?>
	<ul class="wpacu_asset_options" <?php if ($data['row']['global_unloaded']) { echo 'style="display: block; margin: 10px 0;"'; } ?>>
		<?php
		if ($data['row']['global_unloaded']) {
        ?>
			<li>
				<label><input data-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
				              class="wpacu_global_option wpacu_style wpacu_keep_site_wide_rule"
				              type="radio"
				              name="wpacu_options_styles[<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>]"
				              checked="checked"
				              value="default" />
					<?php _e('Keep site-wide rule', 'wp-asset-clean-up'); ?></label>
			</li>

			<li style="margin-right: 0;">
				<label><input data-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
				              class="wpacu_global_option wpacu_style wpacu_remove_site_wide_rule"
				              type="radio"
				              name="wpacu_options_styles[<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>]"
				              value="remove" />
					<?php _e('Remove site-wide rule', 'wp-asset-clean-up'); ?></label>
			</li>
        <?php
		} else {
        ?>
			<li>
				<label>
					<input data-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
					       data-handle-for="style"
					       class="wpacu_global_unload wpacu_bulk_unload wpacu_global_style wpacu_unload_rule_input wpacu_unload_rule_for_style"
					       id="wpacu_global_unload_style_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>" type="checkbox"
					       name="wpacu_global_unload_styles[]" value="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"/>
					<?php _e('Unload site-wide', 'wp-asset-clean-up'); ?> <small>* <?php _e('everywhere', 'wp-asset-clean-up'); ?></small></label>
			</li>
        <?php
		}
		?>
	</ul>
	<?php if ($data['row']['global_unloaded']) { ?>
		<div style="margin: 7px 0 -2px 0; font-weight: 500;">
			<small><span class="dashicons dashicons-warning"
			             style="color: inherit !important; opacity: 0.6; vertical-align: middle;"></span> All other unload rules (e.g. per page, RegEx) are overwritten by this site-wide rule.</small> <a style="text-decoration: none; color: inherit; vertical-align: middle;" target="_blank" href="https://www.assetcleanup.com/docs/?p=1421"><span class="dashicons dashicons-editor-help"></span></a>
		</div>
	<?php } ?>
</div>