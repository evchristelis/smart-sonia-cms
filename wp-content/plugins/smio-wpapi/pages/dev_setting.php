<div class="wrap">
<div id="smapi-icon-devsetting" class="icon32"><br></div>
<h2>Developer Settings</h2>

<form action="<?php  echo $page_url;?>" id="smapi_jform" method="post">
  <table class="form-table">
    <tbody>
      <tr valign="top">
        <th scope="row"><label>Authentication Key</label></th>
        <td>
          <input name="auth_key" type="text" value="<?php echo self::$apisetting['auth_key'];?>" class="regular-text">
          <p class="description">Send this key with any request to prevent access to API from outside.</p>
          <p class="description">Leave it empty to disable this feature.</p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Authentication Type</label></th>
        <td>
          <select name="auth_type" onchange="if(this.value != 'acctoken') jQuery('.accTokenFields').hide(); else jQuery('.accTokenFields').show();">
            <option value="auth_key">Normal authentication key</option>
            <option value="acctoken" <?php if(self::$apisetting['auth_type'] == 'acctoken'){?>selected="selected"<?php }?>>Normal authentication key with access token capability</option>
            <option value="oauth2" <?php if(self::$apisetting['auth_type'] == 'oauth2'){?>selected="selected"<?php }?>>oAuth 2.0 clients</option>
          </select>
        </td>
      </tr>
      <tr valign="top" class="accTokenFields">
        <th scope="row"><label>Access Token Expire</label></th>
        <td>
          <input name="token_expire" type="number" step="10" value="<?php echo self::$apisetting['token_expire'];?>"> Days
          <p class="description">For lifetime expire set it as 0.</p>
        </td>
      </tr>
      <tr valign="top" class="accTokenFields">
        <th scope="row"><label>Allowed Scope</label></th>
        <td>
          <select name="allowed_scopes[]" class="smapi_select2" multiple>
            <?php foreach(self::$apisetting['oauth2scopes'] as $scope): ?>
              <option value="<?php echo $scope ?>" <?php if(in_array($scope, self::$apisetting['allowed_scopes'])){?>selected="selected"<?php }?>><?php echo $scope ?></option>
            <?php endforeach; ?>
          </select>
          <p class="description">Allowed scope that consumer can generated access token for.</p>
        </td>
      </tr>
      <tr valign="top" class="accTokenFields">
        <th scope="row"><label>Public Scope</label></th>
        <td>
          <select name="public_scopes[]" class="smapi_select2" multiple>
            <?php foreach(self::$apisetting['oauth2scopes'] as $scope): ?>
              <option value="<?php echo $scope ?>" <?php if(in_array($scope, self::$apisetting['public_scopes'])){?>selected="selected"<?php }?>><?php echo $scope ?></option>
            <?php endforeach; ?>
          </select>
          <p class="description">Public allowed scope that consumer can access without providing access token.</p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Cache Status</label></th>
        <td>
          <label><input name="cache_status" type="checkbox" value="1" <?php if(self::$apisetting['cache_status']==1){?>checked="checked"<?php }?>> Enable API requests cache.</label>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Cache Expire</label></th>
        <td>
          <input name="cache_expire" type="number" value="<?php echo self::$apisetting['cache_expire'];?>"> Hour
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Cache Listener</label></th>
        <td>
          <label><input name="cache_listener" type="checkbox" value="1" <?php if(self::$apisetting['cache_listener']==1){?>checked="checked"<?php }?>> Remove all cache when insert, update or delete any data for posts or taxonomies.</label>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"></th>
        <td>
          <a href="<?php  echo $page_url;?>&noheader=1&purge_cache=1" class="button button-default">Purge Cache</a>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Geolocation Provider</label></th>
        <td>
        <select name="geo_provider" onchange="if(this.value=='db-ip.com'){jQuery('.smio_dbip_apikey').show();}else{jQuery('.smio_dbip_apikey').hide();}">
          <option value="db-ip.com" <?php if(self::$apisetting['geo_provider'] == 'db-ip.com'){?>selected="selected"<?php }?>>db-ip.com</option>
          <option value="telize.com" <?php if(self::$apisetting['geo_provider'] == 'telize.com'){?>selected="selected"<?php }?>>telize.com</option>
          <option value="ip-api.com" <?php if(self::$apisetting['geo_provider'] == 'ip-api.com'){?>selected="selected"<?php }?>>ip-api.com</option>
        </select>
        </td>
      </tr>
      <tr valign="top" class="smio_dbip_apikey" <?php if(self::$apisetting['geo_provider'] != 'db-ip.com'){?>style="display:none;"<?php }?>>
        <th scope="row"><label>db-ip.com API Key</label></th>
        <td>
          <input name="db_ip_apikey" type="text" value="<?php echo self::$apisetting['db_ip_apikey'];?>" class="regular-text">
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>API Base Name</label></th>
        <td>
          <input name="api_basename" type="text" value="<?php echo self::$apisetting['api_basename'];?>" class="regular-text">
          <p class="description"><span><code><?php echo site_url();?>/</code><abbr>API_BASE_NAME<code>/</code></abbr></span></p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Output type</label></th>
        <td>
        <select name="output_type">
          <option value="json" <?php if(self::$apisetting['output_type'] == 'json'){?>selected="selected"<?php }?>>JSON</option>
          <option value="jsonp" <?php if(self::$apisetting['output_type'] == 'jsonp'){?>selected="selected"<?php }?>>JSONP</option>
          <option value="xml" <?php if(self::$apisetting['output_type'] == 'xml'){?>selected="selected"<?php }?>>XML</option>
        </select>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="smapi_output_type">JSONP Callback</label></th>
        <td>
          <input name="jsonp_param" type="text" value="<?php echo self::$apisetting['jsonp_param'];?>" class="regular-text">
          <p class="description">Callback parameter name for JSONP output format</p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>XML CDATA</label></th>
        <td>
          <input name="cdata_tags" type="text" value="<?php echo self::$apisetting['cdata_tags'];?>" class="regular-text">
          <p class="description">Write tag names that needs to not parse by XML seperated by (,)</p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Visitor Post</label></th>
        <td><label><input name="visitor_can_post" type="checkbox" value="1" <?php if(self::$apisetting['visitor_can_post']==1){?>checked="checked"<?php }?>> Enable publishing posts by non logged users.</label></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Posts Content In List</label></th>
        <td>
        <select name="postlist_content">
          <option value="html">HTML Content</option>
          <option value="plain" <?php if(self::$apisetting['postlist_content'] == 'plain'){?>selected="selected"<?php }?>>Plain Text</option>
        </select>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Post Content In View</label></th>
        <td>
        <select name="post_content">
          <option value="html">HTML Content</option>
          <option value="plain" <?php if(self::$apisetting['post_content'] == 'plain'){?>selected="selected"<?php }?>>Plain Text</option>
        </select>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label>Max Result</label></th>
        <td>
        <input name="max_perpage" type="number" step="10" value="<?php echo self::$apisetting['max_perpage'];?>">
        <p class="description">Max number of results can be request per page.</p>
        </td>
      </tr>
    </tbody>
  </table>
  <p class="submit">
    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
    <img src="<?php echo smapi_imgpath;?>/wpspin_light.gif" class="smapi_process" alt="" />
  </p>
</form>
</div>
<script type="text/javascript">
  jQuery(document).ready(function() {
    jQuery("select[name='auth_type']").trigger("onchange");
  });
</script>