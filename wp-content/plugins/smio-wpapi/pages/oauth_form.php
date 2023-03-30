<form method="post" action="<?php echo $pageurl;?>&noheader=1" id="smapi_jform" class="validate">
<input type="hidden" name="id" value="<?php echo $client['id'];?>">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content" class="edit-form-section">
         <div id="namediv" class="stuffbox">
            <h3><label><?php echo (empty($client['name']))?'New oAuth 2.0 client':$client['name'];?></label></h3>
            <div class="inside">
              <table class="form-table">
              <tbody>
                <?php if(!empty($client['app_id'])): ?>
                <tr valign="top">
                  <th scope="row"><label>Consumer Key</label></th>
                  <td>
                    <input size="40" type="text" value="<?php echo $client['app_id'];?>" class="regular-text" readonly onclick="jQuery(this).select()">
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Secret Key</label></th>
                  <td>
                    <input size="60" type="text" value="<?php echo $client['auth_key'];?>" class="regular-text" readonly onclick="jQuery(this).select()">
                  </td>
                </tr>
                <?php endif; ?>
                <tr valign="top">
                  <th scope="row"><label>Client Name</label></th>
                  <td>
                    <input name="name" size="60" type="text" value="<?php echo $client['settings']['name'];?>" class="regular-text">
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Use Description</label></th>
                  <td>
                    <textarea name="about" rows="6" cols="50" class="regular-text"><?php echo $client['settings']['about'];?></textarea>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Access Token Expire</label></th>
                  <td>
                    <input name="token_expire" type="number" step="10" value="<?php echo $client['settings']['token_expire'];?>"> Days
                    <p class="description">For lifetime expire set it as 0.</p>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Allowed Scope</label></th>
                  <td>
                    <select name="allowed_scopes[]" class="smapi_select2" multiple>
                      <?php foreach(self::$apisetting['oauth2scopes'] as $scope): ?>
                        <option value="<?php echo $scope ?>" <?php if(in_array($scope, $client['settings']['allowed_scopes'])){?>selected="selected"<?php }?>><?php echo $scope ?></option>
                      <?php endforeach; ?>
                    </select>
                    <p class="description">Allowed scope that consumer can generated access token for.</p>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Public Scope</label></th>
                  <td>
                    <select name="public_scopes[]" class="smapi_select2" multiple>
                      <?php foreach(self::$apisetting['oauth2scopes'] as $scope): ?>
                        <option value="<?php echo $scope ?>" <?php if(in_array($scope, $client['settings']['public_scopes'])){?>selected="selected"<?php }?>><?php echo $scope ?></option>
                      <?php endforeach; ?>
                    </select>
                    <p class="description">Public allowed scope that consumer can access without providing access token.</p>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Quota</label></th>
                  <td>
                    <input name="quota" type="number" min="0" step="100" value="<?php echo $client['quota'];?>" class="small-text">
                    <p class="description">Number of allowed requests per month.</p>
                    <p class="description">Set it to zero to make it unlimited.</p>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Status</label></th>
                  <td><label><input name="status" type="checkbox" value="1" <?php if($client['status']==1){?>checked="checked"<?php }?>> Enable/Disable client access.</label></td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Output type</label></th>
                  <td>
                    <select name="output_type">
                      <option value="json" <?php if($client['settings']['output_type'] == 'json'){?>selected="selected"<?php }?>>JSON</option>
                      <option value="jsonp" <?php if($client['settings']['output_type'] == 'jsonp'){?>selected="selected"<?php }?>>JSONP</option>
                      <option value="xml" <?php if($client['settings']['output_type'] == 'xml'){?>selected="selected"<?php }?>>XML</option>
                    </select>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Visitor Post</label></th>
                  <td><label><input name="visitor_can_post" type="checkbox" value="1" <?php if($client['settings']['visitor_can_post']==1){?>checked="checked"<?php }?>> Enable publishing posts by non logged users.</label></td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Posts Content In List</label></th>
                  <td>
                    <select name="postlist_content">
                      <option value="html">HTML Content</option>
                      <option value="plain" <?php if($client['settings']['postlist_content'] == 'plain'){?>selected="selected"<?php }?>>Plain Text</option>
                    </select>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Post Content In View</label></th>
                  <td>
                    <select name="post_content">
                      <option value="html">HTML Content</option>
                      <option value="plain" <?php if($client['settings']['post_content'] == 'plain'){?>selected="selected"<?php }?>>Plain Text</option>
                    </select>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Max Result</label></th>
                  <td>
                    <input name="max_perpage" type="number" min="1" value="<?php echo $client['settings']['max_perpage'];?>">
                    <p class="description">Max number of results can be request per page.</p>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Regsiteration Status</label></th>
                  <td>
                    <select name="users_can_register">
                      <option value="2">WordPress default option</option>
                      <option value="1" <?php if($client['settings']['users_can_register'] == 1){?>selected="selected"<?php }?>>Anyone can register</option>
                      <option value="0" <?php if($client['settings']['users_can_register'] == 0){?>selected="selected"<?php }?>>Closed</option>
                    </select>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>New Post Status</label></th>
                  <td>
                    <select name="new_post_status">
                      <option value="publish">Publish</option>
                      <option value="pending" <?php if($client['settings']['new_post_status'] == 'pending'){?>selected="selected"<?php }?>>Pending Review</option>
                      <option value="open" <?php if($client['settings']['new_post_status'] == 'open'){?>selected="selected"<?php }?>>As defined by the sender</option>
                    </select>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Exclude Categories</label></th>
                  <td>
                    <input name="exclude_cats" type="text" value="<?php echo $client['settings']['exclude_cats'];?>" class="regular-text">
                    <p class="description">Exclude one or group of categories from show in categories list.</p>
                    <p class="description">Write ID(s) of categories separated by (,)</p>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Resize Image</label></th>
                  <td>
                    <input name="resize_image" type="number" min="0" step="1" value="<?php echo $client['settings']['resize_image'];?>" class="small-text">
                    <p class="description">Resize image size inside post and page content.</p>
                    <p class="description">Set it to zero to disable this feature.</p>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Who Can Comment</label></th>
                  <td>
                    <select name="who_can_comment">
                      <option value="default">WordPress default option</option>
                      <option value="anyone" <?php if($client['settings']['who_can_comment'] == 'anyone'){?>selected="selected"<?php }?>>Anyone can comment</option>
                      <option value="usersonly" <?php if($client['settings']['who_can_comment'] == 'usersonly'){?>selected="selected"<?php }?>>Registered users only</option>
                    </select>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Comment Status</label></th>
                  <td>
                    <select name="comment_moderation">
                      <option value="2">WordPress default option</option>
                      <option value="1" <?php if($client['settings']['comment_moderation'] == 1){?>selected="selected"<?php }?>>Published</option>
                      <option value="0" <?php if($client['settings']['comment_moderation'] == 0){?>selected="selected"<?php }?>>Pending Review</option>
                    </select>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row"><label>Popular Posts Range</label></th>
                  <td>
                    <input name="popular_range" type="number" min="0" step="1" value="<?php echo $client['settings']['popular_range'];?>" class="small-text">
                    <p class="description">This feature depends on <a href="http://WordPress.org/extend/plugins/jetpack/" target="_blank">Jetpack plugin</a> with Stats module enabled.</p>
                    <p class="description">Set it to zero to make range for all days.</p>
                  </td>
                </tr>
                <tr valign="top" valign="top">
                    <td><input type="submit" name="submit" id="smapi-submit" class="button button-primary" value="Save Changes"></td>
                    <td><img src="<?php echo smapi_imgpath;?>/wpspin_light.gif" class="smapi_process" alt="" /></td>
                 </tr>
              </tbody>
              </table>
            </div>
         </div>
      </div>
   </div>
</form>