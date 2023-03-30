<?php
$wah_custom_font = wah_get_param('wah_custom_font');
if($wah_custom_font && !empty($wah_custom_font)): ?>
    <style media="screen">#access_container {font-family:<?php echo $wah_custom_font; ?>;}</style>
<?php endif; ?>
