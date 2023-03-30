<form action="<?php echo $pageurl;?>&noheader=1" method="post" id="smapi_jform">
<input type="hidden" name="id" value="<?php echo $service['id'];?>">
   <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content" class="edit-form-section">
         <div id="namediv" class="stuffbox">
            <h3><label><?php echo $service['name'];?></label></h3>
            <div class="inside">
               <table class="form-table">
                  <tbody>
                     <tr valign="top">
                        <td class="first">Service Status</td>
                        <td><input name="active" type="checkbox" value="<?php echo $service['active'];?>" <?php if($service['active'] == 1):?>checked="checked"<?php endif;?>></td>
                     </tr>
                     <tr valign="top">
                       <td class="first">Scope</td>
                       <td>
                         <select name="scope">
                           <?php foreach(self::$apisetting['oauth2scopes'] as $scope): ?>
                           <option value="<?php echo $scope ?>" <?php if($service['scope'] == $scope){?>selected="selected"<?php }?>><?php echo $scope ?></option>
                           <?php endforeach; ?>
                         </select>
                       </td>
                     </tr>
                     <tr valign="top">
                        <td class="first">Access Level</td>
                        <td>
                        <select name="access[]" class="smapi_roles" onchange="smapi_clearSelect()" multiple size="8" style="width:50%;">
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
                     <?php if(!empty($service['params'])){?>
                     <tr valign="top">
                        <td colspan="2"><strong>Callback Parameters</strong></td>
                     </tr>
                     <?php foreach($service['params'] AS $key=>$param){?>
                     <tr valign="top">
                        <td class="first"><?php echo empty($param['name'])?$service['name']:$param['name'];?></td>
                        <td>
                        <?php if(!empty($param['name'])){?>
                        <input name="params[<?php echo $key;?>]" type="checkbox" <?php if($param['active'] == 1):?>checked="checked"<?php endif;?>>&nbsp;
                        <?php }?>
                        <?php if(!empty($param['depend_on'])){
                        $param['depend_on'] = explode('@', $param['depend_on']);
                        ?>
                        Depends on <a onclick="smapi_open_service('<?php echo $param['depend_on'][1];?>', 1)" href="javascript:"><?php echo $param['depend_on'][1];?></a> service
                        <?php }?>
                        </td>
                     </tr>
                     <?php }}?>
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