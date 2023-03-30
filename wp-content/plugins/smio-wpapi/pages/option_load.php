<?php foreach($coptions AS $coption){
$coption->name = 'co_'.$coption->name;
?>
<tr valign="top">
  <th scope="row"><label><?php echo $coption->title;?></label></th>
  <td>
  <?php if($coption->type == 'text'){?>
  <input name="<?php echo $coption->name;?>" type="text" size="40" value="<?php if(isset(self::$apisetting[$coption->name]))echo self::$apisetting[$coption->name];?>" class="regular-text">
  <?php }elseif($coption->type == 'textarea'){?>
  <textarea name="<?php echo $coption->name;?>" rows="5" cols="40" class="regular-text"><?php if(isset(self::$apisetting[$coption->name]))echo self::$apisetting[$coption->name];?></textarea>
  <?php }elseif($coption->type == 'number'){?>
  <input name="<?php echo $coption->name;?>" type="number" min="0" step="1" value="<?php if(isset(self::$apisetting[$coption->name]))echo self::$apisetting[$coption->name];?>" class="small-text">
  <?php }elseif($coption->type == 'select'){?>
  <select name="<?php echo $coption->name;?>">
  <?php
  $elements = explode("\n", str_replace("\r", '', $coption->values));
  foreach($elements AS $element){
    if(!empty($element)){
  ?>
    <option value="<?php echo $element;?>" <?php if(isset(self::$apisetting[$coption->name])){if(self::$apisetting[$coption->name] == $element){?>selected="selected"<?php }}?>><?php echo $element;?></option>
  <?php }}?>
  </select>
  <?php }?>
  <p class="description"><?php echo $coption->hint;?></p>
  </td>
</tr>
<?php }?>