<div class="wrap">
<div id="smapi-icon-setting" class="icon32"><br></div>
<h2>Wordpress API Settings</h2>

<form action="<?php echo $page_url;?>" id="smapi_jform" method="post">
  <table class="form-table">
    <tbody>
      <tr valign="top">
        <th scope="row"><label>Regsiteration Status</label></th>
        <td>
        <select name="users_can_register">
          <option value="2">Wordpress default option</option>
          <option value="1" <?php if(self::$apisetting['users_can_register'] == 1){?>selected="selected"<?php }?>>Anyone can register</option>
          <option value="0" <?php if(self::$apisetting['users_can_register'] == 0){?>selected="selected"<?php }?>>Closed</option>
        </select>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>New Post Status</label></th>
        <td>
        <select name="new_post_status">
          <option value="publish">Publish</option>
          <option value="pending" <?php if(self::$apisetting['new_post_status'] == 'pending'){?>selected="selected"<?php }?>>Pending Review</option>
          <option value="open" <?php if(self::$apisetting['new_post_status'] == 'open'){?>selected="selected"<?php }?>>As defined by the sender</option>
        </select>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Exclude Categories</label></th>
        <td>
          <input name="exclude_cats" type="text" value="<?php echo self::$apisetting['exclude_cats'];?>" class="regular-text">
          <p class="description">Exclude one or group of categories from show in categories list.</p>
          <p class="description">Write ID(s) of categories separated by (,)</p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Resize Image</label></th>
        <td>
        <input name="resize_image" type="number" min="0" step="1" value="<?php echo self::$apisetting['resize_image'];?>" class="small-text">
        <p class="description">Resize image size inside post and page content.</p>
        <p class="description">Set it to zero to disable this feature.</p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Who Can Comment</label></th>
        <td>
        <select name="who_can_comment">
          <option value="default">Wordpress default option</option>
          <option value="anyone" <?php if(self::$apisetting['who_can_comment'] == 'anyone'){?>selected="selected"<?php }?>>Anyone can comment</option>
          <option value="usersonly" <?php if(self::$apisetting['who_can_comment'] == 'usersonly'){?>selected="selected"<?php }?>>Registered users only</option>
        </select>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Comment Status</label></th>
        <td>
        <select name="comment_moderation">
          <option value="2">Wordpress default option</option>
          <option value="1" <?php if(self::$apisetting['comment_moderation'] == 1){?>selected="selected"<?php }?>>Published</option>
          <option value="0" <?php if(self::$apisetting['comment_moderation'] == 0){?>selected="selected"<?php }?>>Pending Review</option>
        </select>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Popular Posts Range</label></th>
        <td>
        <input name="popular_range" type="number" min="0" step="1" value="<?php echo self::$apisetting['popular_range'];?>" class="small-text">
        <p class="description">This feature is depend on <a href="http://wordpress.org/extend/plugins/jetpack/" target="_blank">Jetpack plugin</a> with Stats module enabled.</p>
        <p class="description">Set it to zero to make range for all days.</p>
        </td>
      </tr>
      <?php self::load_custom_options();?>
      <tr valign="top">
        <th scope="row"><label>Maintenance Mode</label></th>
        <td>
        <select name="maintenance_mode">
          <option value="1" <?php if(self::$apisetting['maintenance_mode'] == 1){?>selected="selected"<?php }?>>Enable</option>
          <option value="0" <?php if(self::$apisetting['maintenance_mode'] == 0){?>selected="selected"<?php }?>>Disable</option>
        </select>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Maintenance Message</label></th>
        <td>
          <textarea name="maintenance_msg" rows="5" cols="40" class="regular-text"><?php echo self::$apisetting['maintenance_msg'];?></textarea>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">Developer Mode</th>
        <td><label><input name="developer_mode" type="checkbox" value="1" <?php if(self::$apisetting['developer_mode']==1){?>checked="checked"<?php }?>> Enable developer mode</label></td>
      </tr>
    </tbody>
  </table>
  <p class="submit">
    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
    <img src="<?php echo smapi_imgpath;?>/wpspin_light.gif" class="smapi_process" alt="" />
  </p>
</form>
</div>