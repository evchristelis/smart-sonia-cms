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
<?php if(count($user_profiles)) : ?>
<h2><?php _e('ACF custom fields', ACF_FORM_BUILDER_TEXTDOMAIN); ?></h2>
<table class="form-table">	
	<?php foreach($user_profiles as $key => $field) : ?>
	<tr>
		<th>
			<label for="<?php echo $key; ?>"><?php echo $field['name']; ?></label>
		</th>
		<td>
			<input type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $field['value']; ?>" class="regular-text" />
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>