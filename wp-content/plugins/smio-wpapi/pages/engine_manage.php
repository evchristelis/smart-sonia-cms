<div class="wrap">
   <div id="smapi-icon-engine" class="icon32"><br></div>
   <h2>Engine Services Control</h2>
   <div id="col-container">
      <div id="col-left" style="width: 100%" data-width="50%">
      <form action="<?php echo $pageurl;?>&noheader=1" method="post">
         <div class="col-wrap">
          <div class="tablenav top">
      		<div class="alignleft actions bulkactions">
                <select name="doaction">
                  <option value="-1">Bulk Actions</option>
                  <option value="activate">Activate</option>
                  <option value="deactivate">Deactivate</option>
                </select>
                <input type="submit" class="button action" value="Apply">
        	</div>
        	<br class="clear">
        	</div>
             <table class="wp-list-table widefat fixed tags" cellspacing="0">
                <thead>
                   <tr>
                      <th scope="col" id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></th>
                      <th scope="col" class="manage-column"><span>Name</span></th>
                      <th scope="col" class="manage-column"><span>Scope</span></th>
                      <th scope="col" class="manage-column smapiCanHide column-title"><span>Description</span></th>
                      <th scope="col" class="manage-column" style="width: 15px"><span></span></th>
                      <th scope="col" class="manage-column column-categories" style="width:100px"><span></span></th>
                   </tr>
                </thead>
                <tfoot>
                   <tr>
                      <th scope="col" id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></th>
                      <th scope="col" class="manage-column"><span>Name</span></th>
                      <th scope="col" class="manage-column"><span>Scope</span></th>
                      <th scope="col" class="manage-column smapiCanHide column-title"><span>Description</span></th>
                      <th scope="col" class="manage-column"><span></span></th>
                      <th scope="col" class="manage-column column-categories"><span></span></th>
                   </tr>
                </tfoot>
                <tbody id="the-list" data-wp-lists="list:tag">
                <?php $counter=0;foreach($services AS $service){$counter++;?>
                   <tr id="smapi-service-tab-<?php echo $service->id;?>" class="smapi-service-tab <?php if($counter%2 == 0){echo 'alternate';}?>">
                      <th scope="row" class="check-column">
                        <label class="screen-reader-text"></label>
                        <input type="checkbox" name="service[]" value="<?php echo $service->id;?>">
                        <div class="locked-indicator"></div>
                      </th>
                      <td class="name column-name"><strong><?php echo $service->name;?></strong></td>
                      <td class="description column-title"><?php echo $service->scope;?></td>
                      <td class="description column-title smapiCanHide"><?php echo $service->description;?></td>
                      <td class="description"><img src="<?php echo smapi_imgpath,($service->active == 1)?'/active':'/unactive';?>.png" alt="" /></td>
                      <td class="description column-categories">
                      <input type="button" class="button action" value="Setting" onclick="smapi_open_service(<?php echo $service->id;?>)" />
                      <img src="<?php echo smapi_imgpath.'/wpspin_light.gif';?>" alt="" class="smapi_service_<?php echo $service->id;?>_loading" style="display:none" />
                      </td>
                   </tr>
                <?php }?>
                </tbody>
             </table>
             <div class="tablenav bottom">
        		<div class="alignleft actions bulkactions">
                <select name="doaction2">
                  <option value="-1">Bulk Actions</option>
          	      <option value="activate">Activate</option>
                  <option value="deactivate">Deactivate</option>
                </select>
                <input type="submit" class="button action" value="Apply">
            	</div>
            	<br class="clear">
             </div>
         </div>
      </form>
      </div>
      <div id="col-right" class="smapi_form_ajax" style="display: none;width: 48%"></div>
   </div>
</div>
<script type="text/javascript">
var smapi_pageurl = '<?php echo $pageurl;?>';
</script>