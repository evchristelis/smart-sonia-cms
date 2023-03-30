<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-script-single-row.php
*/

if (! isset($data)) {
	exit; // no direct access
}

if ($data['bulk_unloaded_type'] === 'post_type') {
	?>
	<div class="wpacu_asset_options_wrap" <?php if ($data['row']['global_unloaded']) { echo 'style="display: none;"'; } ?>>
		<?php
		// Unloaded On All Pages Belonging to the page's Post Type
		if ($data['row']['is_post_type_unloaded']) {
			switch ($data['post_type']) {
				case 'product':
					$alreadyUnloadedBulkText = __('This JavaScript file is unloaded on all WooCommerce "Product" pages', 'wp-asset-clean-up');
					break;
				case 'download':
					$alreadyUnloadedBulkText = __('This JavaScript file is unloaded on all Easy Digital Downloads "Download" pages', 'wp-asset-clean-up');
					break;
				default:
					$alreadyUnloadedBulkText = sprintf(__('This JavaScript file is unloaded on all <u>%s</u> post types', 'wp-asset-clean-up'), $data['post_type']);
			}
			?>
			<p><strong style="color: #d54e21;"><?php echo wp_kses($alreadyUnloadedBulkText, array('u' => array())); ?>.</strong></p>
			<?php
		}
		?>
		<ul class="wpacu_asset_options">
			<?php
			if ($data['row']['is_post_type_unloaded']) {
				?>
				<li>
					<label><input data-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
					              class="wpacu_post_type_option wpacu_post_type_script wpacu_keep_bulk_rule"
					              type="radio"
					              name="wpacu_options_post_type_scripts[<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>]"
					              checked="checked"
					              value="default"/>
						<?php _e('Keep bulk rule', 'wp-asset-clean-up'); ?></label>
				</li>

				<li>
					<label><input data-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
					              class="wpacu_post_type_option wpacu_post_type_script wpacu_remove_bulk_rule"
					              type="radio"
					              name="wpacu_options_post_type_scripts[<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>]"
					              value="remove"/>
						<?php esc_html_e('Remove bulk rule', 'wp-asset-clean-up'); ?></label>
				</li>
				<?php
			} else {
				switch ($data['post_type']) {
					case 'product':
						$unloadBulkText = esc_html__('Unload JS on all WooCommerce "Product" pages', 'wp-asset-clean-up');
						break;
					case 'download':
						$unloadBulkText = esc_html__('Unload JS on all Easy Digital Downloads "Download" pages', 'wp-asset-clean-up');
						break;
					default:
						$unloadBulkText = sprintf(__('Unload on All Pages of "<strong>%s</strong>" post type', 'wp-asset-clean-up'), $data['post_type']);
				}
				?>
				<li>
					<label><input data-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
					              data-handle-for="script"
					              class="wpacu_bulk_unload wpacu_post_type_unload wpacu_post_type_script wpacu_unload_rule_input wpacu_unload_rule_for_script"
					              id="wpacu_global_unload_post_type_script_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
					              type="checkbox"
					              name="wpacu_bulk_unload_scripts[post_type][<?php echo esc_attr($data['post_type']); ?>][]"
					              value="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"/>
						<?php echo wp_kses($unloadBulkText, array('strong' => array())); ?> <small>* <?php _e('bulk unload', 'wp-asset-clean-up'); ?></small></label>
				</li>
				<?php
			}
			?>
		</ul>
	</div>
	<?php
}
