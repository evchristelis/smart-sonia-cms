<div class="wrap">
   <div id="smapi-icon-servmanage" class="icon32"><br></div>
   <h2>Manage oAuth2 Scopes<a href="javascript:" onclick="smapi_open_service(-1)" class="add-new-h2">Add New Scope</a><img src="<?php echo smapi_imgpath.'/wpspin_light.gif';?>" alt="" class="smapi_service_-1_loading" style="display:none" /></h2>
   <div id="col-container">
      <div id="col-left" style="padding-top: 10px;width: 45%">
         <div class="col-wrap">
             <table class="wp-list-table widefat fixed tags" cellspacing="0">
                <thead>
                   <tr>
                      <th scope="col" class="manage-column"><span>Scope</span></th>
                      <th scope="col" class="manage-column column-categories" style="width:100px"><span></span></th>
                   </tr>
                </thead>
                <tfoot>
                   <tr>
                      <th scope="col" class="manage-column"><span>Scope</span></th>
                      <th scope="col" class="manage-column column-categories"><span></span></th>
                   </tr>
                </tfoot>
                <tbody id="the-list" data-wp-lists="list:tag">
                <?php $counter=0;foreach(self::$apisetting['oauth2scopes'] AS $scope){$counter++;?>
                   <tr id="smapi-service-tab-<?php echo $scope;?>" class="smapi-service-tab <?php if($counter%2 == 0){echo 'alternate';}?>">
                      <td class="name column-name"><strong><?php echo $scope;?></strong><br />
                      </td>
                      <td class="description column-categories">
                        <input type="button" class="button action" value="Delete" onclick="smapi_delete_service('<?php echo $scope;?>')" />
                        <img src="<?php echo smapi_imgpath.'/wpspin_light.gif';?>" alt="" class="smapi_service_<?php echo $scope;?>_loading" style="display:none" />
                      </td>
                   </tr>
                <?php } ?>
                </tbody>
             </table>
             <br class="clear">
         </div>
      </div>
      <div id="col-right" class="smapi_form_ajax" style="width: 55%"></div>
   </div>
</div>
<script type="text/javascript">
var smapi_pageurl = '<?php echo $pageurl;?>';
jQuery(document).ready(function() {
    smapi_open_service(-1);
});
</script>