<script type="text/javascript">
  var menus = {"oneThemeLocationNoMenus":"","moveUp":"Move up one","moveDown":"Move down one","moveToTop":"Move to the top","moveUnder":"Move under %s","moveOutFrom":"Move out from under %s","under":"Under %s","outFrom":"Out from under %s","menuFocus":"%1$s. Menu item %2$d of %3$d.","subMenuFocus":"%1$s. Sub item number %2$d under %3$s."};
</script>

<div id="nav-menu-meta" class="wrap nav-menus-php">
  <div id="smapi-icon-devsetting" class="icon32"><br></div>
  <h2>Mobile Application Settings</h2>

  <form action="<?php  echo $pageurl;?>&noheader=1" onsubmit="smioapi_mobform_submitted()" id="smapi_jform" class="nav-menu-meta" method="post">
    <input type="hidden" name="mob_categories" value="<?php echo self::$apisetting['mob_categories']?>" />
    <input type="hidden" name="mob_pages" value="<?php echo self::$apisetting['mob_pages']?>" />
    <div id="nav-menus-frame" class="wp-clearfix">
    <div id="menu-settings-column" class="metabox-holder">
      <div class="clear"></div>
        <div id="side-sortables" class="accordion-container">
          <ul class="outer-border">
            <li class="control-section accordion-section add-post-type-cat">
              <h3 class="accordion-section-title">
                Categories <span class="screen-reader-text">Press return or enter to open this section</span>
              </h3>
              <div class="accordion-section-content">
                <div class="inside">
                  <div id="taxonomy-category" class="taxonomydiv">

                    <div id="taxonomy-category" class="categorydiv" style="margin-top:5px">
                      <div id="category-all" class="tabs-panel">
                        <ul id="categorychecklist" data-wp-lists="list:category" class="smioapiPostTaxDIV categorychecklist form-no-clear">
                          <?php wp_terms_checklist(0, array('taxonomy' => self::$apisetting['mob_cat_post_type_tax'], 'checked_ontop' => false));?>
                        </ul>
                      </div>
                    </div>
                    <p class="button-controls wp-clearfix">
                      <span class="list-controls">
                        <a href="javascript:" class="select-all-categories select-all aria-button-if-js" role="button">Select All</a>
                      </span>
                      <span class="add-to-menu">
                        <input type="button" class="button submit-add-to-menu right" value="Add to Menu" id="submit-posttype-category">
                      </span>
                    </p>
                  </div>
                </div>
              </div>
            </li>
            <li class="control-section accordion-section add-post-type-page">
              <h3 class="accordion-section-title">
                Pages <span class="screen-reader-text">Press return or enter to open this section</span>
              </h3>
              <div class="accordion-section-content">
                <div class="inside">
                  <div id="posttype-page" class="posttypediv">
                    <div id="taxonomy-category" class="categorydiv">
                      <div id="category-all" class="tabs-panel">
                        <ul id="pageschecklist" class="categorychecklist form-no-clear">
                          <?php $mobpages = get_pages(); if(!empty($mobpages)): foreach($mobpages as $mobpage):?>
                          <li id="sort-add-page-<?php echo $mobpage->ID?>"><label class="selectit"><input value="<?php echo $mobpage->ID?>" type="checkbox"> <?php echo $mobpage->post_title?></label></li>
                          <?php endforeach; endif;?>
                        </ul>
                      </div>
                    </div>
                    <p class="button-controls wp-clearfix">
                      <span class="list-controls">
                        <a href="javascript:" class="select-all-pages select-all aria-button-if-js" role="button">Select All</a>
                      </span>
                      <span class="add-to-menu">
                        <input type="button" class="button submit-add-to-menu right" value="Add to Menu" id="submit-menu-pages">
                      </span>
                    </p>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>

    </div><!-- /#menu-settings-column -->
    <div id="menu-management-liquid">
      <div id="menu-management">
        <div id="update-nav-menu">
          <div class="menu-edit ">
            <div id="nav-menu-header">
              <div class="major-publishing-actions wp-clearfix">
                <label class="menu-name-label" for="menu-name">Data source</label>

                <select name="mob_cat_post_type" onchange="smioapiPostType(this)">
                  <option value="">Select post type</option>
                  <option value="post" <?php if(self::$apisetting['mob_cat_post_type'] == 'post'){?>selected="selected"<?php }?>>Post</option>
                  <?php $post_types = get_post_types(array('_builtin' => false, 'public' => true));foreach ($post_types as $post_type):?>
                    <option value="<?php echo $post_type;?>" <?php if(self::$apisetting['mob_cat_post_type'] == $post_type){?>selected="selected"<?php }?>><?php echo $post_type;?></option>
                  <?php endforeach;?>
                </select>
                <select name="mob_cat_post_type_tax" id="smioapiPostTaxSelc" onchange="smioapiPostTax(this)">
                  <?php $taxonomy_objects = get_object_taxonomies(self::$apisetting['mob_cat_post_type'], 'objects');foreach ($taxonomy_objects as $type => $object):?>
                    <option value="<?php echo $type;?>" <?php if(self::$apisetting['mob_cat_post_type_tax'] == $type){?>selected="selected"<?php }?>><?php echo $type;?></option>
                  <?php endforeach;?>
                </select>
                <div class="publishing-action">
                  <a href="https://smartiolabs.com/product/smart-wp-job-manager-mobile-app" target="_blank">order mobile app</a>
                  <img src="<?php echo smapi_imgpath; ?>/wpspin_light.gif" class="smapi_process smioapi_taxs_load" alt="loading..." />
                  <input type="submit" class="button button-primary button-large" value="Save Settings">
                </div>
              </div>
            </div>
            <div id="post-body">
              <div id="post-body-content" class="wp-clearfix">
                <h3>Mobile Menu Structure</h3>
                <div class="drag-instructions post-body-plain">
                  <p>Drag each item into the order you prefer. Click the delete link on the right of the item to remove it from the menu.</p>
                </div>
                <div id="menu-instructions" class="post-body-plain menu-instructions-inactive"><p>Add menu items from the column on the left.</p></div>
                <ul class="menu ui-sortable" id="menu-cats-sort-area">
                  <?php if(!empty(self::$apisetting['mob_categories'])):
                    $mobcats = explode(',', self::$apisetting['mob_categories']);
                    foreach($mobcats as $mobcatid):
                      $mobcat = get_term($mobcatid, self::$apisetting['mob_cat_post_type_tax']);
                  ?>
                    <li id="cat-sort-<?php echo $mobcat->term_id?>">
                      <div class="menu-item-bar">
                        <div class="menu-item-handle ui-sortable-handle">
                          <span class="item-title"><span class="menu-item-title"><?php echo $mobcat->name?></span></span>
                          <span class="item-controls">
						                <span class="item-type">
                              <a class="smio_delete_sorted_item" href="javascript:">Delete</a>
                            </span>
                          </span>
                        </div>
                      </div>
                    </li>
                  <?php endforeach; endif;?>
                  <li id="cat-sort-0" style="display: none">
                    <div class="menu-item-bar">
                      <div class="menu-item-handle ui-sortable-handle">
                        <span class="item-title"><span class="menu-item-title"></span></span>
                        <span class="item-controls">
                          <span class="item-type">
                            <a class="smio_delete_sorted_item" href="javascript:">Delete</a>
                          </span>
                        </span>
                      </div>
                    </div>
                  </li>
                </ul>
                <ul class="menu ui-sortable" id="menu-pages-sort-area">
                  <?php if(!empty(self::$apisetting['mob_pages'])):
                    $mobpages = explode(',', self::$apisetting['mob_pages']);
                    foreach($mobpages as $mobpageid):
                      $mobpage = get_post($mobpageid);
                  ?>
                    <li id="page-sort-<?php echo $mobpage->ID?>">
                      <div class="menu-item-bar">
                        <div class="menu-item-handle ui-sortable-handle">
                          <span class="item-title"><span class="menu-item-title"><?php echo $mobpage->post_title?></span></span>
                          <span class="item-controls">
						                <span class="item-type">
                              <a class="smio_delete_sorted_item" href="javascript:">Delete</a>
                            </span>
                          </span>
                        </div>
                      </div>
                    </li>
                  <?php endforeach; endif;?>
                  <li id="page-sort-0" style="display: none">
                    <div class="menu-item-bar">
                      <div class="menu-item-handle ui-sortable-handle">
                        <span class="item-title"><span class="menu-item-title"></span></span>
                        <span class="item-controls">
                          <span class="item-type">
                            <a class="smio_delete_sorted_item" href="javascript:">Delete</a>
                          </span>
                        </span>
                      </div>
                    </div>
                  </li>
                </ul>
                <!--Menu Components-->
                <div class="menu-settings">
                  <h3>Menu Components</h3>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Notifications subscription</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_menu_subscription" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_menu_subscription'])): ?>checked="checked"<?php endif;?> />
                      <label>User can adjust his subscription to receive notifications about some interested fields.</label>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">History of notifications</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_menu_notfhistory" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_menu_notfhistory'])): ?>checked="checked"<?php endif;?> />
                      <label>History of all sent notifications for this user only.</label>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Show contact us</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_menu_contactus" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_menu_contactus'])): ?>checked="checked"<?php endif;?> />
                      <label>Show/Hide contact us page from the side menu.</label>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Show nearby posts</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_menu_nearby" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_menu_nearby'])): ?>checked="checked"<?php endif;?> />
                      <label>Automatically show all nearby posts based on user current GPS location.</label>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Show follow posts</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_menu_follow" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_menu_follow'])): ?>checked="checked"<?php endif;?> />
                      <label>Display posts posted by authors that user followed.</label>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">GPS post meta keys</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_metakey_lat" type="text" placeholder="Latitude post meta key" value="<?php echo self::$apisetting['mob_metakey_lat']?>" size="30" />
                      <input name="mob_metakey_lng" type="text" placeholder="Longitude post meta key" value="<?php echo self::$apisetting['mob_metakey_lng']?>" size="30" />
                      <p class="description">Set posts GPS location meta keys from postmeta table so plugin can display nearby posts of user current location.</p>
                    </div>
                  </fieldset>
                </div>
                <!--Home Screen Settings-->
                <div class="menu-settings">
                  <h3>Home Screen</h3>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Head Title</legend>
                    <div class="menu-settings-input">
                      <input name="mob_headtitle" type="text" value="<?php echo self::$apisetting['mob_headtitle']?>" />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Cover Image</legend>
                    <div class="menu-settings-input">
                      <input class="smapi_upload_field_cover" type="url" size="50" name="mob_home_cover" value="<?php echo self::$apisetting['mob_home_cover']; ?>" />
                      <input class="smapi_upload_file_btn button action" data-container="smapi_upload_field_cover" type="button" value="Select File" />
                      <p class="description">Choose image to appear in the top of screen in a standard size 1024x512 px</p>
                    </div>
                  </fieldset>

                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Categories Metro</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_home_catmetro" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_home_catmetro'])): ?>checked="checked"<?php endif;?> />
                      <label>Show some categories in a metro style.</label>
                    </div>
                  </fieldset>

                  <fieldset class="menu-settings-group" style="overflow:inherit;">
                  <legend class="menu-settings-group-name howto">Selected Categories</legend>
                  <div class="menu-settings-input checkbox-input">
                    <select name="mob_home_catids[]" class="smapi_select2" multiple id="smioapiPostTaxSelc2">
                      <?php $terms = get_terms(array('taxonomy' => self::$apisetting['mob_cat_post_type_tax'], 'hide_empty' => false, 'orderby' => 'none'));
                      if(!empty($terms)):
                      foreach (self::$apisetting['mob_home_catids'] as $mob_catid):
                      foreach ($terms as $term): if($term->term_id == $mob_catid): ?>
                        <option value="<?php echo $term->term_id;?>" selected="selected"><?php echo $term->name;?></option>
                      <?php break; endif; endforeach; endforeach;
                      foreach ($terms as $term): if(! in_array($term->term_id, self::$apisetting['mob_home_catids'])): ?>
                        <option value="<?php echo $term->term_id;?>"><?php echo $term->name;?></option>
                      <?php endif; endforeach; endif; ?>
                    </select>
                    <p class="description">Drag each item into the order you prefer. Click the remove icon on the right of the item to remove it from the menu.</p>
                  </div>
                  </fieldset>

                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Popular Posts Slider</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_home_popular" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_home_popular'])): ?>checked="checked"<?php endif;?> />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Last Posts List</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_home_recent" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_home_recent'])): ?>checked="checked"<?php endif;?> />
                      <label>List of recent posts from all categories.</label>
                    </div>
                  </fieldset>

                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Enable iOS ADs</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_home_iosads" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_home_iosads'])): ?>checked="checked"<?php endif;?> />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Enable Android ADs</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_home_andads" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_home_andads'])): ?>checked="checked"<?php endif;?> />
                    </div>
                  </fieldset>
                </div>
                <!--Post Feeds Screen-->
                <div class="menu-settings">
                  <h3>Post Feeds Screen</h3>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Display Style</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input class="smapi_jradio" name="mob_feeds_style" value="grid" type="radio" data-icon="<?php echo smapi_imgpath; ?>/grid.png" data-labelauty='Grid' <?php if(self::$apisetting['mob_feeds_style'] == 'grid'): ?>checked="checked"<?php endif;?> />
                      <input class="smapi_jradio" name="mob_feeds_style" value="list" type="radio" data-icon="<?php echo smapi_imgpath; ?>/list.png" data-labelauty='List'<?php if(self::$apisetting['mob_feeds_style'] == 'list'): ?>checked="checked"<?php endif;?> />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Show featured image</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_feeds_fimage" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_feeds_fimage'])): ?>checked="checked"<?php endif;?> />
                      <label>Show/hide the featured image for each post.</label>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Post content source</legend>
                    <div class="menu-settings-input checkbox-input">
                      <select name="mob_feeds_contsource">
                        <option value="contents">Post HTML contents</option>
                        <option value="excerpt" <?php if(self::$apisetting['mob_feeds_contsource'] == 'excerpt'): ?>selected="selected"<?php endif;?>>Excerpt field</option>
                      </select>
                    </div>
                  </fieldset>
                </div>
                <!--Post View Screen-->
                <div class="menu-settings">
                  <h3>Post View Screen</h3>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Show featured image</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_post_fimage" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_post_fimage'])): ?>checked="checked"<?php endif;?> />
                      <label>Show/hide the featured image for the post.</label>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Show comments</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_post_showcomms" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_post_showcomms'])): ?>checked="checked"<?php endif;?> />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Users add comments</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_post_addcomms" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_post_addcomms'])): ?>checked="checked"<?php endif;?> />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Show author info</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_post_author" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_post_author'])): ?>checked="checked"<?php endif;?> />
                      <label>Show/hide author name and a link to the author profile screen.</label>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Show list of categories</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_post_categories" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_post_categories'])): ?>checked="checked"<?php endif;?> />
                      <label>Show/hide list of categories that post is related with them.</label>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Enable iOS ADs</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_post_iosads" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_post_iosads'])): ?>checked="checked"<?php endif;?> />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Enable Android ADs</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_post_andads" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_post_andads'])): ?>checked="checked"<?php endif;?> />
                    </div>
                  </fieldset>
                </div>
                <!--General Settings-->
                <div class="menu-settings">
                  <h3>General Settings</h3>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">iOS App ID</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_common_iosappid" type="number" value="<?php echo self::$apisetting['mob_common_iosappid']?>" />
                      <p class="description">Important to displaying a rating request for app in App Store.</p>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Android Package ID</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_common_andappid" type="text" placeholder="e.g. com.smartiolabs.app" value="<?php echo self::$apisetting['mob_common_andappid']?>" size="30" />
                      <p class="description">Important to displaying a rating request for app in Google Play store.</p>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Windows Phone App ID</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_common_winappid" type="text" value="<?php echo self::$apisetting['mob_common_winappid']?>" size="20" />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">iOS ADs ID</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_common_iosadid" type="text" value="<?php echo self::$apisetting['mob_common_iosadid']?>" size="50" />
                      <p class="description">Register with <a href="https://www.google.com/admob/" target="_blank">Google AdMob</a></p>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Android ADs ID</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_common_andadid" type="text" value="<?php echo self::$apisetting['mob_common_andadid']?>" size="50" />
                      <p class="description">Register with <a href="https://www.google.com/admob/" target="_blank">Google AdMob</a></p>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Debug ADs</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_debug_ads" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_debug_ads'])): ?>checked="checked"<?php endif;?> />
                      <label>Show testing ADs if your AD account is just fresh or does not show any ADs.</label>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">GPS Tracking</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_common_gps" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_common_gps'])): ?>checked="checked"<?php endif;?> />
                      <label>Automatically tracking user location without user enable it from settings screen.</label>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Push Notification</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_common_push" class="smapi_onoff" type="checkbox" <?php if(!empty(self::$apisetting['mob_common_push'])): ?>checked="checked"<?php endif;?> />
                      <label>Automatically register user device for receving push notification messages.</label>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">ADs Type</legend>
                    <div class="menu-settings-input checkbox-input">
                      <select name="mob_common_adtype">
                        <option value="banner">Banner</option>
                        <option value="interstitial" <?php if(self::$apisetting['mob_common_adtype'] == 'interstitial'): ?>selected="selected"<?php endif;?>>Interstitial</option>
                      </select>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Cache Expire</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_cache_expire" type="number" value="<?php echo self::$apisetting['mob_cache_expire']?>" /> Minutes
                      <p class="description">Cache a lot of data and settings to accelerate app navigating and loading on your server. data like home posts, categories, popular posts slider, menu items and other app options.</p>
                    </div>
                  </fieldset>
                </div>
                <!--Contact Us Screen Settings-->
                <div class="menu-settings">
                  <h3>Contact Us Screen</h3>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">Photo Gallery</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input class="smapi_upload_contact_photo1" type="url" size="50" name="mob_contact_photo1" value="<?php echo self::$apisetting['mob_contact_photo1']; ?>" />
                      <input class="smapi_upload_file_btn button action" data-container="smapi_upload_contact_photo1" type="button" value="Select File" />
                    </div>
                    <div class="menu-settings-input checkbox-input">
                      <input class="smapi_upload_contact_photo2" type="url" size="50" name="mob_contact_photo2" value="<?php echo self::$apisetting['mob_contact_photo2']; ?>" />
                      <input class="smapi_upload_file_btn button action" data-container="smapi_upload_contact_photo2" type="button" value="Select File" />
                    </div>
                    <div class="menu-settings-input checkbox-input">
                      <input class="smapi_upload_contact_photo3" type="url" size="50" name="mob_contact_photo3" value="<?php echo self::$apisetting['mob_contact_photo3']; ?>" />
                      <input class="smapi_upload_file_btn button action" data-container="smapi_upload_contact_photo3" type="button" value="Select File" />
                    </div>
                    <div class="menu-settings-input checkbox-input">
                      <input class="smapi_upload_contact_photo4" type="url" size="50" name="mob_contact_photo4" value="<?php echo self::$apisetting['mob_contact_photo4']; ?>" />
                      <input class="smapi_upload_file_btn button action" data-container="smapi_upload_contact_photo4" type="button" value="Select File" />
                      <p class="description">Choose some photos for your company to appear in the top of conatct us screen. Recommended size 1024x512 px</p>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Company Name</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_contact_name" type="text" value="<?php echo self::$apisetting['mob_contact_name']?>" size="30" />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Description</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_contact_desc" type="text" placeholder="e.g. Today opens from 8:30 am to 5:00 pm" value="<?php echo self::$apisetting['mob_contact_desc']?>" size="60" />
                      <p class="description">Write about your company, slogan or openning times.</p>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Location</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_contact_lat" type="text" placeholder="Latitude point" value="<?php echo self::$apisetting['mob_contact_lat']?>" />
                      <input name="mob_contact_lng" type="text" placeholder="Longitude point" value="<?php echo self::$apisetting['mob_contact_lng']?>" />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Google Maps API Key</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_gmaps_apikey" type="text" value="<?php echo self::$apisetting['mob_gmaps_apikey']?>" size="60" />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Address</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_contact_address" type="text" value="<?php echo self::$apisetting['mob_contact_address']?>" size="60" />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Website</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_contact_website" type="url" value="<?php echo self::$apisetting['mob_contact_website']?>" size="50" />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Email</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_contact_email" type="email" value="<?php echo self::$apisetting['mob_contact_email']?>" size="40" />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Phone</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_contact_phone" type="text" value="<?php echo self::$apisetting['mob_contact_phone']?>" />
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group menu-theme-locations">
                    <legend class="menu-settings-group-name howto">Rating</legend>
                    <div class="menu-settings-input checkbox-input">
                      <input name="mob_contact_rating" type="text" value="<?php echo self::$apisetting['mob_contact_rating']?>" size="7" />
                      <p class="description">Set a rating from 1-5 for your company if it has an official rating.</p>
                      <p class="description">Leave it empty to hide this feature.</p>
                    </div>
                  </fieldset>
                </div>
                <!--Requirements-->
                <div class="menu-settings">
                  <h3>Requirements</h3>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">
                      <?php if(function_exists('smpush_install')): ?>
                        <code class="smapi-installed-hint">INSTALLED</code>
                      <?php else: ?>
                        <code class="smapi-not-installed-hint">NOT INSTALLED</code>
                      <?php endif; ?>
                    </legend>
                    <div class="menu-settings-input checkbox-input">
                      <label for="auto-add-pages"><code>Smart Notification Wordpress Plugin</code> <a href="https://smartiolabs.com/product/push-notification-system" target="_blank">Learn more</a></label>
                      <p class="description">Free license for Smart WordPress App license owners.</p>
                      <p class="description">Required to sending mobile push notification messages and to run subscription and notification history pages.</p>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">
                      <?php if(function_exists('GetWtiLikeCount')): ?>
                        <code class="smapi-installed-hint">INSTALLED</code>
                      <?php else: ?>
                        <code class="smapi-not-installed-hint">NOT INSTALLED</code>
                      <?php endif; ?>
                    </legend>
                    <div class="menu-settings-input checkbox-input">
                      <label for="auto-add-pages"><code>WTI Like Post</code> <a class="thickbox" href="<?php echo admin_url()?>plugin-install.php?tab=plugin-information&plugin=wti-like-post&TB_iframe=true&width=772&height=853">install now</a></label>
                      <p class="description">Required for like/dislike posts feature.</p>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">
                      <?php if(function_exists('stats_get_csv')): ?>
                        <code class="smapi-installed-hint">INSTALLED</code>
                      <?php else: ?>
                        <code class="smapi-not-installed-hint">NOT INSTALLED</code>
                      <?php endif; ?>
                    </legend>
                    <div class="menu-settings-input checkbox-input">
                      <label for="auto-add-pages"><code>Jetpack</code> <a class="thickbox" href="<?php echo admin_url()?>plugin-install.php?tab=plugin-information&plugin=jetpack&TB_iframe=true&width=772&height=853">install now</a></label>
                      <p class="description">Jetpack plugin with Stats module needs to be enabled to retrieve popular posts.</p>
                    </div>
                  </fieldset>
                  <fieldset class="menu-settings-group">
                    <legend class="menu-settings-group-name howto">
                      <?php if(function_exists('z_taxonomy_image_url')): ?>
                        <code class="smapi-installed-hint">INSTALLED</code>
                      <?php else: ?>
                        <code class="smapi-not-installed-hint">NOT INSTALLED</code>
                      <?php endif; ?>
                    </legend>
                    <div class="menu-settings-input checkbox-input">
                      <label for="auto-add-pages"><code>Categories Images</code> <a class="thickbox" href="<?php echo admin_url()?>plugin-install.php?tab=plugin-information&plugin=categories-images&TB_iframe=true&width=772&height=853">install now</a></label>
                      <p class="description">Required for displaying the images of Metro categories in the app home screen.</p>
                    </div>
                  </fieldset>
                </div>

              </div>
            </div>
            <div id="nav-menu-footer">
              <div class="major-publishing-actions wp-clearfix">
                <div class="publishing-action">
                  <img src="<?php echo smapi_imgpath;?>/wpspin_light.gif" class="smapi_process" alt="saving..." />
                  <input type="submit" class="button button-primary button-large" value="Save Settings"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  </form>
</div>