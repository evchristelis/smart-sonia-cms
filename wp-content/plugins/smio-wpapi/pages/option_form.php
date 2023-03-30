<form method="post" action="<?php echo $pageurl;?>&noheader=1" id="smapi_jform" class="validate">
<input type="hidden" name="id" value="<?php echo $coption['id'];?>">
<input type="hidden" name="type" value="<?php echo $coption['type'];?>">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content" class="edit-form-section">
         <div id="namediv" class="stuffbox">
            <h3><label><?php echo (empty($coption['title']))?'New Custom Option':$coption['title'];?></label></h3>
            <div class="inside">
              <table class="form-table">
              <tbody>
                <tr valign="top" class="form-required">
                   <td class="first">Title</td>
                   <td>
                   <input name="title" type="text" size="40" value="<?php echo $coption['title'];?>" aria-required="true">
                   <p class="smapi_desc description">Title for option which appears to end-user.</p>
                   </td>
                </tr>
                <tr valign="top" class="form-required">
                   <td class="first">Name</td>
                   <td>
                   <input name="name" type="text" size="40" value="<?php echo $coption['name'];?>" aria-required="true">
                   <p class="smapi_desc description">Must be in lower-case and without spaces.</p>
                   </td>
                </tr>
                <tr valign="top">
                   <td class="first">Description</td>
                   <td>
                   <textarea name="description" rows="5" cols="40"><?php echo $coption['description'];?></textarea>
                   <p class="smapi_desc description">Small description about option work.</p>
                   </td>
                </tr>
                <tr valign="top">
                   <td class="first">Hint</td>
                   <td>
                   <textarea name="hint" rows="5" cols="40"><?php echo $coption['hint'];?></textarea>
                   <p class="smapi_desc description">Hint to appear to end-user.</p>
                   </td>
                </tr>
                <?php if($coption['id'] > 0){?>
                <tr valign="top">
                   <td class="first">Input Type</td>
                   <td><strong><?php echo self::custom_option_type($coption['type']);?></strong></td>
                </tr>
                <?php if($coption['type'] == 'select'){?>
                <tr valign="top">
                   <td class="first">Elements</td>
                   <td>
                   <textarea name="values" rows="5" cols="40"><?php echo $coption['values'];?></textarea>
                   <p class="smapi_desc description">Write every element in a new line.</p>
                   </td>
                </tr>
                <?php }}else{?>
                <tr valign="top">
                     <td class="first">Input Type</td>
                     <td>
                     <select name="type" class="postform" onchange="if(this.value=='select'){jQuery('.smapi_select_values').show()}else{jQuery('.smapi_select_values').hide()}">
                        <option value="text">Text Input</option>
                        <option value="textarea">Textarea</option>
                        <option value="number">Number Input</option>
                        <option value="select">Select Menu</option>
                     </select>
                     </td>
                  </tr>
                  <tr valign="top" class="smapi_select_values" style="display:none;">
                     <td class="first">Elements</td>
                     <td>
                     <textarea name="values" rows="5" cols="40"></textarea>
                     <p class="smapi_desc description">Write every element in a new line.</p>
                     </td>
                  </tr>
                <?php }?>
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