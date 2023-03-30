/*!
 * Copyright (c) 2013 Smart IO Labs
 * Project repository: http://smartiolabs.com
 */

if(typeof $ === "undefined"){
  var $ = jQuery;
}

jQuery(document).ready(function() {
  jQuery("#smapi_model_select").change(function(){
    jQuery('.smapi_apidesc').hide();
    jQuery('.smapi_method_'+jQuery(this).val()).show();
  });
  jQuery('#smapi-submit').click(function(){
    var form = jQuery(this).parents('form');
    if (!validateForm(form)) return false;
  });
  jQuery('.smapi-delete').click(function(){
    if(!confirm('Are you sure you want to continue?')){
      event.preventDefault();
    }
  });
  jQuery(".smapi_jradio").labelauty();
  jQuery.switcher(".smapi_onoff");
  jQuery(".smapi_select2").selectize({tags: true, plugins: ['remove_button','drag_drop']});

  var smapi_form_options = {
    beforeSubmit:  function(){jQuery('.smapi_process').show()},
    success:       function(responseText, statusText){if(responseText!=1){console.log(responseText);}else{jQuery('.smapi_process').hide();}}
  };
  jQuery('#smapi_jform').ajaxForm(smapi_form_options);

  smapiAutoLoad("body");
  window.send_to_editor = function(html) {
    imgurl = jQuery('img', html).attr('src');
    jQuery('.'+smapi_upload_field).val(imgurl);
    jQuery('.'+smapi_upload_field).trigger("change");
    tb_remove();
    if(typeof imgurl == "undefined"){
      alert("Empty link! Please click on `File URL` button before saving.");
    }
  }
});

var smapi_upload_field;
function smapiAutoLoad(element){
  jQuery(element).find('.smapi_upload_file_btn').click(function() {
    smapi_upload_field = jQuery(this).attr('data-container');
    formfield = jQuery('.'+smapi_upload_field).attr('name');
    tb_show('', 'media-upload.php?type=image&TB_iframe=1');
    return false;
  });
}

function smapi_delete_service(id){
  if(!confirm('Are you sure you want to continue ?')){
    return;
  }
  jQuery('.smapi_service_'+id+'_loading').show();
  jQuery.get(smapi_pageurl, {'noheader':1, 'delete': 1, 'id': id}
    ,function(data){
      jQuery('.smapi_service_'+id+'_loading').hide();
      if(data != 1){
        alert(data);
      }
      else{
        jQuery('#smapi-service-tab-'+id).hide(600, function() {
          jQuery('#smapi-service-tab-'+id).remove();
        });
      }
    });
}

function smapi_codeType(type){
  if(type == "query"){
    jQuery(".smapi_cservice_code").hide();
    jQuery(".smapi_cservice_query").show();
  }
  else{
    jQuery(".smapi_cservice_query").hide();
    jQuery(".smapi_cservice_code").show();
  }
}

function smapi_clearSelect(){
  jQuery(".smapi_roles option:selected").each(function(){
    if(jQuery(this).val() == "anyone" || jQuery(this).val() == "logged"){
      jQuery(".smapi_roles option:selected").each(function(){
        jQuery(this).prop('selected', false);
      });
      jQuery(this).prop('selected', true);
      return;
    }
  });
}

function smapi_drawStatCharts(statdata, elementid){
  var data = new google.visualization.DataTable();
  data.addColumn('date', 'Date');
  data.addColumn('number', 'Requests: ');
  data.addRows(statdata);
  var options = { 'displayAnnotations': false, 'displayExactValues': true, 'thickness': 3, 'color': '#058dc7', 'fill': 40 };
  var annotatedtimeline = new google.visualization.AnnotatedTimeLine(document.getElementById(elementid));
  annotatedtimeline.draw(data, options);
}

function smapi_open_service(id, showconfirm, action){
  if(showconfirm == 1){
    if(confirm("Do you want to save current changes?")){
      jQuery('#smapi_jform').ajaxSubmit();
    }
  }
  jQuery(".smapiCanHide").hide();
  if(jQuery("#col-left")[0].hasAttribute("data-width")){
    jQuery("#col-left").css("width", jQuery("#col-left").attr("data-width"));
  }
  jQuery(".smapi_form_ajax").show();
  jQuery('.smapi-service-tab').removeClass("smapi-alternate");
  jQuery('#smapi-service-tab-'+id).addClass("smapi-alternate");
  jQuery('.smapi_service_'+id+'_loading').show();
  jQuery.get(smapi_pageurl, {'noheader':1, 'action': action, 'id': id}
  ,function(data){
    jQuery('.smapi_form_ajax').html(data);
    var smapi_form_options = {
        beforeSubmit:  function(){jQuery('.smapi_process').show()},
        success:       function(responseText, statusText){
          if(responseText != 1){
            jQuery(".smapi_process").hide();
            alert("Error occured. Please try again !!");
            console.log(responseText);
          }
          else{
            jQuery(".smapi_process").hide();
            jQuery(".smapi_form_ajax").fadeOut("fast", function(){
              jQuery('.smapi_form_ajax').html('');
              if(jQuery("#col-left")[0].hasAttribute("data-width")){
                jQuery(".smapi_form_ajax").hide();
                jQuery("#col-left").css("width", "auto");
              }
              jQuery(".smapiCanHide").show();
              if(id != -1){
                jQuery("html, body").animate({scrollTop: jQuery('#smapi-service-tab-'+id).offset().top-100}, "slow");
              }
            });
          }
        },
        error: function (request, status, error) {console.log(request.responseText);}
    };
    jQuery('#smapi_jform').ajaxForm(smapi_form_options);
    jQuery('#smapi-submit').click(function(){
      var form = jQuery(this).parents('form');
      if (!validateForm(form)) return false;
    });
    jQuery('.smapi_service_'+id+'_loading').hide();
    if(id != -1)jQuery("html, body").animate({scrollTop: 0}, "slow");
    jQuery(".smapi_form_ajax .smapi_select2").selectize({tags: true, plugins: ['remove_button']});
    jQuery.switcher(".smapi_form_ajax input[type=checkbox]");
    //jQuery(".smapi_form_ajax input[type=checkbox]").switchButton({width: 50, height: 20, button_width: 25, show_labels: false});
  });
}

function smapi_showHide(element){
  if(jQuery('.'+element).attr('style') == 'display:none;'){
    jQuery('.'+element).show();
  }
  else{
    jQuery('.'+element).hide();
  }
}
