<form method="post" action="<?php echo $pageurl;?>&noheader=1" id="smapi_jform" class="validate">
<div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content" class="edit-form-section">
         <div id="namediv" class="stuffbox">
            <h3><label>Add New Scope</label></h3>
            <div class="inside">
              <table class="form-table">
              <tbody>
                <tr valign="top" class="form-required">
                   <td class="first">Name</td>
                   <td>
                   <input name="name" type="text" size="40" aria-required="true">
                   <p class="smapi_desc description">Must be in lower-case and without spaces.</p>
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