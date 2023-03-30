<form method="post" action="<?php echo $pageurl;?>&noheader=1" id="smapi_jform" class="validate">
<input type="hidden" name="id" value="<?php echo $service['id'];?>">
<div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content" class="edit-form-section">
         <div id="namediv" class="stuffbox">
            <h3><label><?php echo (empty($service['name']))?'New Custom Service':$service['name'];?></label></h3>
            <div class="inside">
              <table class="form-table">
              <tbody>
                <tr valign="top" class="form-required">
                   <td class="first">Name</td>
                   <td>
                   <input name="name" type="text" size="40" value="<?php echo $service['name'];?>" aria-required="true">
                   <p class="smapi_desc description">Must be in lower-case and without spaces.</p>
                   <p class="smapi_desc description">You will use it later to call the service by it.</p>
                   </td>
                </tr>
                <tr valign="top">
                   <td class="first">Description</td>
                   <td>
                   <textarea name="description" rows="5" cols="40"><?php echo $service['description'];?></textarea>
                   <p class="smapi_desc description">Small description about service.</p>
                   </td>
                </tr>
                <tr valign="top" class="form-required">
                    <td class="first">Access Level:</td>
                    <td>
                    <select name="access[]" class="smapi_roles" onchange="smapi_clearSelect()" multiple size="8" style="width:50%;" aria-required="true">
                      <option value="anyone" <?php if(in_array('anyone', $service['access_level'])){?>selected="selected"<?php }?>>Anyone</option>
                      <option value="logged" <?php if(in_array('logged', $service['access_level'])){?>selected="selected"<?php }?>>Logged User</option>
                      <?php
                      global $wp_roles;
                      if(!isset($wp_roles)){
                        $wp_roles = new WP_Roles();
                      }
                      $roles = $wp_roles->get_names();
                      foreach($roles as $role_value=>$role_name){
                        if(in_array($role_value, $service['access_level'])) $selected = 'selected="selected"';
                        else $selected = '';
                        echo '<option value="'.$role_value.'" '.$selected.'>'.$role_name.'</option>';
                      }
                      ?>
                    </select>
                    </td>
                 </tr>
                 <tr valign="top" class="form-required">
                   <td class="first">Code Type</td>
                   <td>
                   <select name="codetype" aria-required="true" onchange="smapi_codeType(this.value)">
                        <option value="query">SQL Query</option>
                        <option value="php" <?php if($service['codetype'] == 'php'){?>selected="selected"<?php }?>>PHP Code</option>
                   </select>
                   </td>
                </tr>
                <tr valign="top" class="smapi_cservice_query" <?php if($service['codetype'] == 'php'){?>style="display:none"<?php }?>>
                   <td class="first">SQL Query</td>
                   <td>
                   <textarea name="query" rows="8" cols="50" class="smapi_code_write"><?php if($service['codetype'] == 'query'){echo $service['query'];}?></textarea>
                   <p class="smapi_desc description">Use {param_name} to call any parameter from URL in query syntax like `WHERE ID='{postid}'`</p>
                   <p class="smapi_desc description">Use {wp_prefix} for wordpress prefix tables.</p>
                   </td>
                </tr>
                <tr valign="top" class="smapi_cservice_code" <?php if($service['codetype'] == 'query'){?>style="display:none"<?php }?>>
                   <td class="first">PHP Code</td>
                   <td>
                   <textarea name="phpcode" rows="15" cols="50" class="smapi_code_write"><?php if($service['codetype'] == 'php'){echo $service['query'];}?></textarea>
                   </td>
                </tr>
                <tr valign="top" class="smapi_cservice_code" <?php if($service['codetype'] == 'query'){?>style="display:none"<?php }?>>
                   <td class="first">Code Usage</td>
                   <td style="padding: 0 0 0 10px;">
                   <pre class="smapi_pre">
//SMAPI paging system if you want paging your result
$query = $this->Paging('SELECT * FROM '.$wpdb->prefix.'posts');

//Wordpress database object to execute the query and return results
//Reference: http://codex.wordpress.org/Class_Reference/wpdb
$result = $wpdb->get_results($query, 'ARRAY_A');

if($result){
  //Output function to finish and display data in JSON or XML format
  $this->output(1, $result);
}
else{
  $this->output(0, 'No result found');
}

//SMAPI function to check if user send these parameters or not
$this->CheckParams(array('username','password'));

//SMAPI Geolocation service
$locationinfo = smapi_geoloc::get_location_info();
if($locationinfo !== false){
  foreach($locationinfo as $key=>$value){
  }
}
                   </pre>
                   </td>
                </tr>
                <tr valign="top" class="smapi_cservice_query" <?php if($service['codetype'] == 'php'){?>style="display:none"<?php }?>>
                   <td class="first">Paging System</td>
                   <td>
                   <select name="paging" class="postform">
                      <option value="enable">Enable</option>
                      <option value="disable" <?php if($service['paging'] == 'disable'){?>selected="selected"<?php }?>>Disable</option>
                   </select>
                   </td>
                </tr>
                <tr valign="top">
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