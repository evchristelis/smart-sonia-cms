<div class="wrap">
<div id="smapi-icon-doc" class="icon32"><br></div>
<h2>Developer Documentation</h2>

<div id="available-widgets" class="widgets-holder-wrap ui-droppable">
  <div class="sidebar-name" style="clear:both;">
    <div class="sidebar-name-arrow"><br></div>
    <h3>Complete list of services</h3>
  </div>
  <div class="widget-holder">
    <select id="smapi_model_select" style="margin-top:8px;margin-left: 10px;">
        <option value="about">Basics</option>
    <?php foreach($document['group'] AS $group=>$title){?>
        <optgroup label="<?php echo $title;?>">
        <?php foreach($document['links'][$group] AS $method=>$servtitle){?>
        <option value="<?php echo $method;?>"><?php echo $servtitle;?></option>
        <?php }?>
        </optgroup>
    <?php }?>
    </select>
    <table class="smapi_document smapi_apidesc smapi_method_about" style="margin-top:15px;margin-left: 10px;width:95%;">
        <tr>
            <th>Base URL</th>
            <?php $smapidocurl = (!empty($smapiexurl['auth_key']))?$smapiexurl['api_base'].'/?auth_key='.$smapiexurl['auth_key']:$smapiexurl['api_base'].'/';?>
            <td class="smapi_tdhold"><a href="<?php echo $smapidocurl;?>" target="_blank"><?php echo $smapidocurl;?></a></td>
        </tr>
        <tr>
            <th>Direct Base URL</th>
            <?php $directsmapiurl = (!empty($smapiexurl['auth_key']))?site_url().'/index.php?smapicontrol=debug&auth_key='.$smapiexurl['auth_key']:site_url().'/index.php?smapicontrol=debug';?>
            <td class="smapi_tdhold"><a href="<?php echo $directsmapiurl;?>" target="_blank"><?php echo $directsmapiurl;?></a></td>
        </tr>
        <tr>
            <th>Send Type</th>
            <td class="smapi_tdhold">Data can be sent in two methods POST and GET</td>
        </tr>
        <tr>
          <th>Response Schema</th>
          <td class="smapi_tdcode smapitbwbord">
          <table class="smapi_document">
              <tr><td><span>respond</span><p>Success return 1, and 0 if fails</p></td></tr>
              <tr><td><span>paging</span><p>Return values of paging system and zeros if there's no pages</p></td></tr>
              <tr><td><span>message</span><p>Return string message when happens error or success insert, and return empty if there's result</p></td></tr>
              <tr><td><span>result</span><p>Return array(s) of data, and empty if there's no result or happened error</p></td></tr>
          </table>
          </td>
        </tr>
        <tr>
            <th>Output Type</th>
            <td class="smapi_tdhold">Determine from developer setting either XML, JSON or JSONP</td>
        </tr>
        <tr>
            <th>PHP Version</th>
            <td class="smapi_tdhold">Plugin requires PHP version 5.2.4 or later
			<br />Wordpress version 3.0 or later</td>
        </tr>
        <tr>
            <th>Knowledge Base</th>
            <td class="smapi_tdhold">You will find group of lessons, faqs and explanations about this product <a href="https://smartiolabs.com/blog/category/projects/smio-wp-api/" target="_blank">Knowledge Base</a></td>
        </tr>
        <tr>
            <th>Support</th>
            <td class="smapi_tdhold">We will be happy if you ask us for any help <a href="https://smartiolabs.com/support" target="_blank">Smart IO Labs</a></td>
        </tr>
    </table>

    <?php foreach($document['api'] AS $model=>$api){?>
    <table class="smapi_document smapi_apidesc smapi_method_<?php echo $model;?>" style="margin-top:15px;margin-left: 10px;width:95%;display:none;">
        <tr>
            <th>Request Example</th>
            <?php 
            $api['example'] = (empty($smapiexurl['auth_key']))?str_replace('{api_key}', '', $api['example']):str_replace('{api_key}', 'auth_key='.$smapiexurl['auth_key'].'&', $api['example']);
            if($api['multisite']){
              $smapilstpa = (in_array(substr($api['example'], -1), array('/','?')))?'':'&';
              $api['example'] .= $smapilstpa.'siteid=';
              $api['params']['siteid'] = array(
                'description' => 'Site ID in the Wordpress network if multisite feature enabled',
                'type' => 'int',
                'required' => false
              );
            }
            if($api['paging']){
              $smapilstpa = (in_array(substr($api['example'], -1), array('/','?')))?'':'&';
              $api['example'] .= $smapilstpa.'callpage=1&perpage=10';
            }
            $smapidocurl = $smapiexurl['api_base'].'/'.$api['example'];
            $smapidocurl = rtrim($smapidocurl, '&');
            $smapidocurl = rtrim($smapidocurl, '?');
            $urlparams = parse_url($smapidocurl);
            if(isset($urlparams['query'])){
              parse_str($urlparams['query'], $urlparams);
            }
            else{
              $urlparams = array();
            }
            ?>
            <td class="smapi_tdhold"><a href='<?php echo $smapidocurl;?>' target="_blank"><?php echo $smapidocurl;?></a></td>
        </tr>
        <tr>
            <th>Send Type</th>
            <td class="smapi_tdhold">Send parameters in POST or GET is available</td>
        </tr>
        <?php if(!empty($api['note'])){?>
        <tr>
            <th>Note</th>
            <td class="smapi_tdhold"><?php echo $api['note'];?></td>
        </tr>
        <?php }?>
      <tr>
        <th>End-point</th>
        <td class="smapi_tdhold"><code>/<?php echo $model;?></code></td>
      </tr>
      <?php if(count($api['params']) > 0){?>
        <tr>
            <th>Parameters</th>
            <td class="smapi_td">
            <table class="smapi_document">
            <?php foreach($api['params'] AS $title=>$desc){?>
            <tr>
              <td class="smapi_tdparam"><?php echo $title;?></td>
              <td class="smapi_tdcode">
              <table class="smapi_document">
                  <tr><td><span>Description</span><p><?php echo $desc['description'];?></p></td></tr>
                  <tr><td><span>Type</span><p><?php echo $desc['type'];?></p></td></tr>
                  <tr><td><span>Required</span><p><?php if(!empty($desc['requiredtxt'])){echo $desc['requiredtxt'];}elseif($desc['required']){echo 'Yes';}else{echo 'No';}?></p></td></tr>
              </table>
              </td>
            </tr>
            <?php }?>
            </table>
            </td>
        </tr>
        <?php }?>
        <?php if(count($api['order']) > 0){?>
        <tr>
            <th>Order</th>
            <td class="smapi_td" style="border-top: #e0e0e0 1px solid;">
            <table class="smapi_document">
            <?php foreach($api['order'] AS $title=>$desc){?>
            <tr>
              <td class="smapi_tdparam"><?php echo $title;?></td>
              <td class="smapi_tdcode">
              <table class="smapi_document">
                  <tr><td><span>Description</span><p><?php echo $desc['description'];?></p></td></tr>
                  <tr><td><span>Type</span><p>ASC or DESC</p></td></tr>
                  <?php if($desc['default']){?>
                  <tr><td><span>Default</span><p>Default order, orders in <?php echo $desc['type'];?> mode</p></td></tr>
                  <?php }?>
              </table>
              </td>
            </tr>
            <?php }?>
            </table>
            </td>
        </tr>
        <?php }?>
        <?php if($api['paging']){?>
        <tr>
            <th>Paging</th>
            <td class="smapi_td" style="border-top: #e0e0e0 1px solid;">
            <table class="smapi_document">
            <tr>
              <td class="smapi_tdparam">Send</td>
              <td class="smapi_tdcode">
              <table class="smapi_document">
                  <tr><td><span>callpage</span>Page's number you want to call it</td></tr>
                  <tr><td><span>perpage</span>The number of results in each page default=10</td></tr>
              </table>
              </td>
            </tr>
            <tr>
              <td class="smapi_tdparam">Return</td>
              <td class="smapi_tdcode">
              <table class="smapi_document">
                  <tr><td><span>stillmore</span>Return 1 if there is more pages to show and 0 if not</td></tr>
                  <tr><td><span>perpage</span>The number of results in each page</td></tr>
                  <tr><td><span>callpage</span>Current page number</td></tr>
                  <tr><td><span>next</span>Next page number</td></tr>
                  <tr><td><span>previous</span>Previous page number</td></tr>
                  <tr><td><span>pages</span>Total number of pages</td></tr>
                  <tr><td><span>result</span>Total number of query results</td></tr>
              </table>
              </td>
            </tr>
            </table>
            </td>
        </tr>
        <?php }else{?>
        <tr>
            <th>Paging</th>
            <td class="smapi_tdhold">No</td>
        </tr>
        <?php }?>
      <tr>
        <th>Login</th>
          <td class="smapi_tdhold">
            <?php if($api['login']): ?>
              <div class="smapi-errors"><p>Required</p></div>
            <?php else: ?>
              <p>Not Required</p>
            <?php endif; ?>
          </td>
      </tr>
      <tr>
        <th>Authentication</th>
          <td class="smapi_tdhold">
            <p>If your environment does not support cookies: After calling the end-point <code>login</code> there is a return parameter called <code>session_id</code> you can send it again in the header of each request with name <code>SESSION_ID</code> to make the session alive.</p>
            <p>In case access token authentication is enabled from the developer settings then you should send <code>ACCESS_TOKEN</code> parameter in the request BODY or HEADER and you can get the value of this parameter from the end-point <code>request_token</code>, <code>signup</code>, <code>social</code> or <code>login</code> after successful response with name <code>Access_Token</code></p>
            <p>In case oAuth2 authentication is enabled should send <code>ACCESS_TOKEN</code> parameter in the request BODY, HEADER or as bearer authorization like <code>Authorization: Bearer ACCESS_TOKEN_VALUE_HERE</code> and you can get the value of this parameter from the end-point <code>request_token</code>, <code>signup</code>, <code>social</code> or <code>login</code> after successful response with name <code>Access_Token</code></p>
          </td>
      </tr>
      <?php if(!empty($api['wp_filter'])){?>
        <tr>
            <th>WP Filter</th>
            <td class="smapi_tdhold">
            <pre style="height: 225px;">&lt?php

add_filter('<?php echo $api['wp_filter'];?>', 'smio_sample_filter', 10, 3);

function smio_sample_filter($data) {
  //make some customization in the given array data which carries each element of the output array for this service
  return $data;
}

?></pre>
            </td>
        </tr>
        <?php }?>
        <?php if(!empty($api['wp_query_filter'])){?>
        <tr>
            <th>SQL Query Filter</th>
            <td class="smapi_tdhold">
            <pre style="height: 225px;">&lt?php

add_filter('<?php echo $api['wp_query_filter'];?>', '<?php echo $api['wp_query_filter'];?>');

function <?php echo $api['wp_query_filter'];?>($sql){
  global $wpdb;
  $pre_select = $after_select = $pre_where = $after_where = $pre_order = $after_order = '';
  //echo $sql;

  $vars = array(
  '{pre_select}',
  '{after_select}',
  '{pre_where}',
  '{after_where}',
  '{pre_order}',
  '{after_order}',
  );
  $replace = array(
  $pre_select,
  $after_select,
  $pre_where,
  $after_select,
  $pre_order,
  $after_order,
  );
  return str_replace($vars, $replace, $sql);
}

?></pre>
            </td>
        </tr>
        <?php }?>
        <tr>
            <th>Function Use</th>
            <td class="smapi_tdhold">
            <pre>&lt?php

$args = array(
<?php
if(is_array($urlparams)) {
  foreach($urlparams as $key=>$value){
    echo "'$key' => '$value',\n";
  }
}
?>
);

$fetch = new smapi_core('<?php echo $model;?>', $args);
if($fetch->error !== false){
  echo $fetch->error;
}
else{
  var_dump($fetch->result);
}

?></pre>
            </td>
        </tr>
        <tr>
            <th>Javascript Use</th>
            <td class="smapi_tdhold">
            <pre>&ltscript src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"&gt&lt/script&gt
&ltscript type="text/javascript"&gt
jQuery.ajax({
  url: '<?php echo $smapidocurl;?>',
  type: 'GET',
  dataType: 'jsonp',
  success: function (data, response) {
    if (response == 'success') {
        console.log(data);
    }
  }
});
&lt/script&gt</pre>
            </td>
        </tr>
        <tr>
            <th>Errors</th>
            <td class="smapi_tdhold smapi-errors">
            <?php if($api['login']){?>
              <p>Must be login to proceed</p>
            <?php }?>
            <?php if($api['admin']){?>
            <p>You do not have permission to use `<?php echo $model;?>` service</p>
            <?php }?>
            <?php if(count($api['errors']) > 0){?>
            <?php foreach($api['errors'] AS $error){?>
            <p><?php echo $error;?></p>
            <?php }}?>
            </td>
        </tr>
    </table>
    <?php }?>
  </div>
  <br class="clear">
</div>
</div>