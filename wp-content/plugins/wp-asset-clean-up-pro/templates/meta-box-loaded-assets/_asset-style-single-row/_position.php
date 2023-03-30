<?php
/*
 * The file is included from /templates/meta-box-loaded-assets/_asset-style-single-row.php
*/

if ( ! isset($data, $stylePosition, $stylePositionNew, $styleHandleHasSrc) ) {
	exit; // no direct access
}

ob_start();

if ($styleHandleHasSrc) {
	?>
	<div class="wpacu-wrap-choose-position">
		<?php _e('Location:', 'wp-asset-clean-up'); ?>
		<select data-wpacu-input="position-select" name="<?php echo WPACU_FORM_ASSETS_POST_KEY; ?>[styles][<?php echo htmlentities(esc_attr($data['row']['obj']->handle), ENT_QUOTES); ?>][position]">
			<option <?php if ($stylePositionNew === 'head') {
				echo 'selected="selected"';
			} ?>
				value="<?php if ($stylePosition === 'head') {
					echo 'initial';
				} else {
					echo 'head';
				} ?>">
				&lt;HEAD&gt; <?php if ($stylePosition === 'head') { ?>* initial<?php } ?>
			</option>
			<option <?php if ($stylePositionNew === 'body') {
				echo 'selected="selected"';
			} ?>
				value="<?php if ($stylePosition === 'body') {
					echo 'initial';
				} else {
					echo 'body';
				} ?>">
				&lt;BODY&gt; <?php if ($stylePosition === 'body') { ?>* initial<?php } ?>
			</option>
		</select>
		<small>* applies site-wide</small>
	</div>
	<?php
} else {
	if ($data['row']['obj']->handle === 'woocommerce-inline') {
		$noSrcLoadedIn = __('Inline CSS Loaded In:', 'wp-asset-clean-up');
	} else {
		$noSrcLoadedIn = __('This handle is not for external stylesheet (most likely inline CSS) and it is loaded in:', 'wp-asset-clean-up');
	}

	echo esc_html($noSrcLoadedIn) . ' '. (($stylePosition === 'head') ? 'HEAD' : 'BODY');
}

$htmlChoosePosition = ob_get_clean();

if (isset($data['row']['obj']->position) && $data['row']['obj']->position !== '') {
	$extraInfo[] = $htmlChoosePosition;
}
