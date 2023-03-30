<div class="wrap">
   <div id="smapi-icon-optmanage" class="icon32"><br></div>
   <h2>Create Custom Options<a href="javascript:" onclick="smapi_open_service(-1)" class="add-new-h2">New Custom Option</a><img src="<?php echo smapi_imgpath.'/wpspin_light.gif';?>" alt="" class="smapi_service_-1_loading" style="display:none" /></h2>
   <div id="col-container">
      <div id="col-left" style="padding-top: 10px;width: 55%">
         <div class="col-wrap">
             <table class="wp-list-table widefat fixed tags" cellspacing="0">
                <thead>
                   <tr>
                      <th scope="col" class="manage-column"><span>Title</span></th>
                      <th scope="col" class="manage-column" style="width:100px"><span>Name</span></th>
                      <th scope="col" class="manage-column"><span>Description</span></th>
                      <th scope="col" class="manage-column"><span>Type</span></th>
                      <th scope="col" class="manage-column column-categories" style="width:75px"><span></span></th>
                   </tr>
                </thead>
                <tfoot>
                   <tr>
                      <th scope="col" class="manage-column"><span>Title</span></th>
                      <th scope="col" class="manage-column"><span>Name</span></th>
                      <th scope="col" class="manage-column"><span>Description</span></th>
                      <th scope="col" class="manage-column"><span>Type</span></th>
                      <th scope="col" class="manage-column column-categories"><span></span></th>
                   </tr>
                </tfoot>
                <tbody id="the-list" data-wp-lists="list:tag">
                <?php if($coptions){$counter=0;foreach($coptions AS $coption){$counter++;?>
                   <tr id="smapi-service-tab-<?php echo $coption->id;?>" class="smapi-service-tab <?php if($counter%2 == 0){echo 'alternate';}?>">
                      <td class="name column-name"><strong><?php echo $coption->title;?></strong><br />
                      <div class="row-actions"><span class="delete"><a class="smapi-delete" href="<?php echo $pageurl;?>&delete=1&noheader=1&id=<?php echo $coption->id;?>">Delete</a></span></div>
                      </td>
                      <td class="name column-name"><strong><?php echo $coption->name;?></strong></td>
                      <td class="description column-description"><?php echo $coption->description;?></td>
                      <td class="description column-description"><?php echo self::custom_option_type($coption->type);?></td>
                      <td class="description column-categories">
                      <input type="button" class="button action smapi-open-btn" value="Edit" onclick="smapi_open_service(<?php echo $coption->id;?>)" />
                      <img src="<?php echo smapi_imgpath.'/wpspin_light.gif';?>" alt="" class="smapi_service_<?php echo $coption->id;?>_loading" style="display:none" />
                      </td>
                   </tr>
                <?php }}else{?>
                <tr class="no-items"><td class="colspanchange" colspan="5">No items found.</td></tr>
                <?php }?>
                </tbody>
             </table>
             <br class="clear">
            <div class="form-wrap">
            <p><strong>Note:</strong><br>Custome options appear in 'Wordpress API Setting' page for end-user.</p>
            </div>
         </div>
      </div>
      <div id="col-right" class="smapi_form_ajax" style="width: 45%"></div>
   </div>
</div>
<script type="text/javascript">
var smapi_pageurl = '<?php echo $pageurl;?>';
jQuery(document).ready(function() {
    smapi_open_service(-1);
});
</script>