<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://support.catsplugins.com
 * @since      1.0.0
 *
 * @package    Acf_Form_Builder
 * @subpackage Acf_Form_Builder/admin/partials
 */
?>
<?php
  if(isset($user_meta['_current_packages'])){
    $current_packages = unserialize($user_meta['_current_packages'][0]);
  }  
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<table class="form-table">
  <tr>
      <th>
          <label><strong><?php _e('Package ID', ACF_FORM_BUILDER_TEXTDOMAIN); ?></strong></label>
      </th>
      <th>
          <label><strong><?php _e('Package Name', ACF_FORM_BUILDER_TEXTDOMAIN); ?></strong></label>
      </th>
      <th>
          <label><strong><?php _e('Number of remain posts', ACF_FORM_BUILDER_TEXTDOMAIN); ?></strong></label>
      </th>
      <th>
          <label><strong><?php _e('Number of remain posts in a ' . (isset($package_meta['max_post']['max_post_time']) ? $package_meta['max_post']['max_post_time'] : 'day'), ACF_FORM_BUILDER_TEXTDOMAIN); ?></strong></label>
      </th>
      <th>
          <label><strong><?php _e('Number of remain vip posts', ACF_FORM_BUILDER_TEXTDOMAIN); ?></strong></label>
      </th>
      <th>
          <label><strong><?php _e('Number of remain feature posts', ACF_FORM_BUILDER_TEXTDOMAIN); ?></strong></label>
      </th>
      <th>
          <label><strong><?php _e('Expiry date', ACF_FORM_BUILDER_TEXTDOMAIN); ?></strong></label>
      </th>
  </tr>
  <?php if(isset($current_packages) && count($current_packages)) : ?>
    <?php foreach($current_packages as $user_package) : ?>
    <tr>
  			<td><?php echo $user_package['package_id']; ?></td>
  			<td><?php echo get_the_title($user_package['package_id']); ?></td>
  			<td><?php echo $user_package['number_of_posts']; ?></td>
        <td><?php echo $user_package['number_of_posts_by_time']; ?></td>
        <td><?php echo $user_package['number_of_posts_vip']; ?></td>
        <td><?php echo $user_package['number_of_posts_feature']; ?></td>
        <td><?php echo date('Y-m-d', $user_package['expiry_time']); ?></td>
    </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</table>