<table style="width: 100%">
  <tbody>
  <tr>
    <td>
      <input id="smapi_latitude" name="smapi_latitude" type="hidden" value="<?php echo $latitude ?>" />
      <input id="smapi_longitude" name="smapi_longitude" type="hidden" value="<?php echo $longitude ?>" />
      <div id="smapi_gmap_search">
        <input id="smapi_gmap_address" class="smapi_gmap_input" type="text" placeholder="Put the search address then press Enter..." />
      </div>
      <div id="smapi-gmap"></div>
    </td>
  </tr>
  </tbody>
</table>
<script type="text/javascript">
  var SMAPIgeocoder;
  var SMAPImap;
  var SMAPImarker = 0;

  jQuery(document).ready(function() {
    SMAPIinitialize();
    google.maps.event.addListenerOnce(SMAPImap, 'idle', function(){
      SMAPIReadUserGPS();
    });
    jQuery('.smapi_gmap_input').on('keypress', function (event) {
      if(event.which === 13){
        event.preventDefault();
        SMAPIcodeAddress();
      }
    });
  });

  function SMAPIinitialize() {
    if(jQuery("#smapi-gmap").length < 1){
      return;
    }
    SMAPIgeocoder = new google.maps.Geocoder();
    var lat = (jQuery("#smapi_latitude").val() == "")? 26.820553 : jQuery("#smapi_latitude").val();
    var lng = (jQuery("#smapi_longitude").val() == "")? 30.802498000000014 : jQuery("#smapi_longitude").val();
    var latlng = new google.maps.LatLng(lat, lng);
    var mapOptions = {
      zoom: 3,
      center: latlng
    }
    SMAPImap = new google.maps.Map(document.getElementById('smapi-gmap'), mapOptions);
    if(jQuery("#smapi_latitude").val() != "" && jQuery("#smapi_longitude").val() != ""){
      SMAPImarker = new google.maps.Marker({
        map: SMAPImap,
        draggable:true,
        position: latlng
      });
      SMAPImap.setZoom(11);
      SMAPIdraggerMarker();
      SMAPIgeocodePosition(SMAPImarker.getPosition());
    }
  }

  function SMAPIcodeAddress() {
    var address = jQuery('#smapi_gmap_address').val();
    SMAPIgeocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        SMAPImap.setCenter(results[0].geometry.location);
        SMAPImap.setZoom(15);
        if(SMAPImarker != 0){
          SMAPImarker.setMap(null);
        }
        SMAPImarker = new google.maps.Marker({
          map: SMAPImap,
          draggable:true,
          position: results[0].geometry.location
        });
        jQuery("#smapi_latitude").val(results[0].geometry.location.lat());
        jQuery("#smapi_longitude").val(results[0].geometry.location.lng());
        SMAPIdraggerMarker();
      } else {
        alert('Geocode was not successful for the following reason: ' + status);
      }
    });
  }

  function SMAPIdraggerMarker() {
    google.maps.event.addListener(SMAPImarker,'dragend',function(event){
      jQuery('#smapi_latitude').val(event.latLng.lat());
      jQuery('#smapi_longitude').val(event.latLng.lng());
      SMAPIgeocodePosition(SMAPImarker.getPosition());
    });
  }

  function SMAPIgeocodePosition(pos) {
    SMAPIgeocoder.geocode({
      latLng: pos
    }, function(responses) {
      if (responses && responses.length > 0) {
        jQuery('#smapi_gmap_address').val(responses[0].formatted_address);
      } else {
        alert('Cannot determine address at this location.');
      }
    });
  }

  function SMAPIReadUserGPS(){
    if(jQuery("#smapi_latitude").val() == "" && jQuery("#smapi_longitude").val() == ""){
      var geoSuccess = function(startPos) {
        var latlng = new google.maps.LatLng(startPos.coords.latitude, startPos.coords.longitude);
        SMAPImarker = new google.maps.Marker({
          map: SMAPImap,
          draggable:true,
          position: latlng
        });
        SMAPImap.setZoom(13);
        SMAPIdraggerMarker();
        SMAPIgeocodePosition(SMAPImarker.getPosition());
        SMAPImap.setCenter(latlng);
      };
      navigator.geolocation.getCurrentPosition(geoSuccess);
    }
  }
</script>
<style>
  #smapi-gmap {
    width: 100%;
    height: 600px;
  }
  #smapi_gmap_search {
    z-index: 9999;
    position: absolute;
    margin-left:95px;
    margin-top: 16px;
  }
  .smapi_gmap_input {
    border: 1px solid transparent;
    border-radius: 2px 0 0 2px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    height: 32px;
    outline: none;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    background-color: #fff;
    padding: 0 11px 0 13px;
    text-overflow: ellipsis;
    width: 400px;
    margin-left:5px;
  }
  .smapi_gmap_input:focus {
    border-color: #4d90fe;
  }

</style>