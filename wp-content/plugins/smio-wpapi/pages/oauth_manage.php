<div class="wrap">
   <div id="smapi-icon-optmanage" class="icon32"><br></div>
   <h2>oAuth 2.0 Clients<a href="javascript:" onclick="smapi_open_service(-1)" class="add-new-h2">New Client</a><img src="<?php echo smapi_imgpath.'/wpspin_light.gif';?>" alt="" class="smapi_service_-1_loading" style="display:none" /></h2>
   <div id="col-container">
      <div id="col-left" style="padding-top: 10px;width: auto" data-width="20%">
         <div class="col-wrap">
             <table class="wp-list-table widefat fixed tags" cellspacing="0">
                <thead>
                   <tr>
                      <th scope="col" class="manage-column" style="width:100px"><span>Name</span></th>
                      <th scope="col" class="manage-column smapiCanHide"><span>Description</span></th>
                      <th scope="col" class="manage-column smapiCanHide"><span>Consumer Key</span></th>
                      <th scope="col" class="manage-column smapiCanHide"><span>Quota</span></th>
                      <th scope="col" class="manage-column smapiCanHide"><span>Remain</span></th>
                      <th scope="col" class="manage-column column-categories" style="width:75px"><span></span></th>
                   </tr>
                </thead>
                <tfoot>
                   <tr>
                      <th scope="col" class="manage-column"><span>Name</span></th>
                      <th scope="col" class="manage-column smapiCanHide"><span>Description</span></th>
                      <th scope="col" class="manage-column smapiCanHide"><span>Consumer Key</span></th>
                      <th scope="col" class="manage-column smapiCanHide"><span>Quota</span></th>
                      <th scope="col" class="manage-column smapiCanHide"><span>Remain</span></th>
                      <th scope="col" class="manage-column column-categories"><span></span></th>
                   </tr>
                </tfoot>
                <tbody id="the-list" data-wp-lists="list:tag">
                <?php if($clients){$counter=0;foreach($clients AS $client){$counter++;?>
                   <tr id="smapi-service-tab-<?php echo $client->id;?>" class="smapi-service-tab <?php if($counter%2 == 0){echo 'alternate';}?>">
                      <td class="name column-name"><strong><?php echo $client->settings['name'];?></strong><br />
                      <div class="row-actions"><span class="delete"><a class="smapi-delete" href="<?php echo $pageurl;?>&delete=1&noheader=1&id=<?php echo $client->id;?>">Delete</a></span></div>
                      </td>
                      <td class="description column-description smapiCanHide"><?php echo $client->settings['about'];?></td>
                      <td class="description column-description smapiCanHide"><?php echo $client->app_id;?></td>
                      <td class="description column-description smapiCanHide"><?php echo ($client->quota != 0)? $client->quota : 'unlimited' ?></td>
                      <td class="description column-description smapiCanHide"><?php echo ($client->quota != 0)? ($client->quota-$client->req_usage) : 'unlimited' ?></td>
                      <td class="description column-categories">
                      <input type="button" class="button action smapi-open-btn" value="Edit" onclick="smapi_open_service(<?php echo $client->id;?>)" />
                      <img src="<?php echo smapi_imgpath.'/wpspin_light.gif';?>" alt="" class="smapi_service_<?php echo $client->id;?>_loading" style="display:none" />
                      </td>
                   </tr>
                <?php }}else{?>
                <tr class="no-items"><td class="colspanchange" colspan="5">No items found.</td></tr>
                <?php }?>
                </tbody>
             </table>
             <br class="clear">
         </div>
      </div>
      <div id="col-right" class="smapi_form_ajax" style="display: none;width: 79%"></div>
   </div>
</div>
<script type="text/javascript">
var smapi_pageurl = '<?php echo $pageurl;?>';
jQuery(document).ready(function() {
    //smapi_open_service(-1);
});
</script>