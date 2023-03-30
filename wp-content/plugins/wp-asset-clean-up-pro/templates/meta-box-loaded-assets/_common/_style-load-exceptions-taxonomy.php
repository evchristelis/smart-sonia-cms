<?php
if (! isset($data)) {
	exit; // no direct access
}

// Only show it on edit post/page/custom post type depending on the taxonomies set
$handleLoadViaTax = ( isset( $data['handle_load_via_tax']['styles'][ $data['row']['obj']->handle ] ) && $data['handle_load_via_tax']['styles'][ $data['row']['obj']->handle ] )
	? $data['handle_load_via_tax']['styles'][ $data['row']['obj']->handle ]
	: false;

$handleLoadViaTax['enable'] = isset( $handleLoadViaTax['enable'] ) && $handleLoadViaTax['enable'];
$handleLoadViaTax['values'] = ( isset( $handleLoadViaTax['values'] ) && $handleLoadViaTax['values'] ) ? $handleLoadViaTax['values'] : '';

$isLoadViaTaxEnabledWithValues = ($handleLoadViaTax['enable'] && ! empty($handleLoadViaTax['values']));

switch ($data['post_type']) {
	case 'product':
		$loadBulkTextViaTax = __('On all WooCommerce "Product" pages if these taxonomies (e.g. Category, Tag) are set', 'wp-asset-clean-up');
		break;
	case 'download':
		$loadBulkTextViaTax = __('On all Easy Digital Downloads "Download" pages if these taxonomies (e.g. Category, Tag) are set', 'wp-asset-clean-up');
		break;
	default:
		$loadBulkTextViaTax = sprintf(__('On All Pages of "<strong>%s</strong>" post type if these taxonomies (e.g. Category, Tag) are set', 'wp-asset-clean-up'), $data['post_type']);
}
?>
<!-- [wpacu_pro] -->
<li>
    <label for="wpacu_load_it_via_tax_option_style_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>">
        <input data-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
               data-handle-for="style"
               id="wpacu_load_it_via_tax_option_style_<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
               class="wpacu_load_it_via_tax_checkbox wpacu_load_exception wpacu_load_rule_input wpacu_bulk_load"
               type="checkbox"
               name="<?php echo WPACU_FORM_ASSETS_POST_KEY; ?>[styles][load_it_post_type_via_tax][<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>][enable]"
			<?php if ( $isLoadViaTaxEnabledWithValues ) { ?> checked="checked" <?php } ?>
               value="1"/>&nbsp;<span><?php echo $loadBulkTextViaTax; ?>:</span></label>
    <a style="text-decoration: none; color: inherit; vertical-align: middle;" target="_blank"
       href="https://www.assetcleanup.com/docs/?p=1415#load_exception"><span
                class="dashicons dashicons-editor-help"></span></a>
    <div class="wpacu_handle_manage_via_tax_input_wrap wpacu_handle_load_via_tax_input_wrap <?php if ( ! $isLoadViaTaxEnabledWithValues ) { echo 'wpacu_hide'; } ?>">
        <div class="wpacu_manage_via_tax_rule_area" style="min-width: 300px;">
            <select name="<?php echo WPACU_FORM_ASSETS_POST_KEY; ?>[styles][load_it_post_type_via_tax][<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>][values][]"
                    class="wpacu_manage_via_tax_dd wpacu_load_via_tax_dd <?php echo ($isLoadViaTaxEnabledWithValues) ? 'wpacu_chosen_select' : ''; ?>"
                    data-placeholder="<?php esc_attr_e('Select taxonomies added to the post type'); ?>..."
                    multiple="multiple"
                    data-handle="<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>"
                    data-handle-for="style"><?php if ( $isLoadViaTaxEnabledWithValues ) { echo \WpAssetCleanUpPro\MainPro::loadDDOptionsForAllSetTermsForPostType($data['post_type'], 'styles', $data['row']['obj']->handle, $handleLoadViaTax['values'], 'load_exception'); } ?></select>
        </div>
    </div>
	<?php
	if ( ! $isLoadViaTaxEnabledWithValues ) {
		// The loader shows when the checkbox above is checked
		?>
        <div data-wpacu-tax-terms-options-loader="1" style="display: none; margin: 10px 0 10px;">
            <img src="<?php echo WPACU_PLUGIN_URL; ?>/assets/icons/loader-horizontal.svg?x=<?php echo time(); ?>"
                 align="top"
                 width="90"
                 alt="" />
        </div>
	<?php } ?>
</li>
<!-- [/wpacu_pro] -->
