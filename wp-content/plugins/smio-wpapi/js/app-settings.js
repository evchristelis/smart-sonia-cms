jQuery(document).ready(function() {
  $("#side-sortables").accordion({ header: "h3" });
  jQuery("#menu-pages-sort-area").sortable();
  jQuery("#menu-cats-sort-area").sortable();

  jQuery(".select-all-categories").click(function() {
    jQuery("#categorychecklist input:checkbox").attr("checked","checked");
  });

  jQuery(".select-all-pages").click(function() {
    jQuery("#pageschecklist input:checkbox").attr("checked","checked");
  });

  jQuery(".smio_delete_sorted_item").click(function() {
    smio_delete_sorted_item(this);
  });

  jQuery("#submit-posttype-category").click(function() {
    jQuery( "#categorychecklist input:checked" ).each(function( index ) {
      if(jQuery("#cat-sort-"+jQuery(this).val()).length){return}
      var template = jQuery("#cat-sort-0").html();
      jQuery("#menu-cats-sort-area").append('<li id="cat-sort-'+jQuery(this).val()+'">'+template+'</li>');
      jQuery("#menu-cats-sort-area li:last span.menu-item-title").html(jQuery(this).closest("label.selectit").text());
      jQuery("#menu-cats-sort-area").sortable("refresh");
      jQuery("#menu-cats-sort-area li:last .smio_delete_sorted_item").click(function() {
        smio_delete_sorted_item(this);
      });
    });
  });

  jQuery("#submit-menu-pages").click(function() {
    jQuery( "#pageschecklist input:checked" ).each(function( index ) {
      if(jQuery("#page-sort-"+jQuery(this).val()).length){return}
      var template = jQuery("#page-sort-0").html();
      jQuery("#menu-pages-sort-area").append('<li id="page-sort-'+jQuery(this).val()+'">'+template+'</li>');
      jQuery("#menu-pages-sort-area li:last span.menu-item-title").html(jQuery(this).closest("label.selectit").text());
      jQuery("#menu-pages-sort-area").sortable("refresh");
      jQuery("#menu-pages-sort-area li:last .smio_delete_sorted_item").click(function() {
        smio_delete_sorted_item(this);
      });
    });
  });

});

function smioapiPostType(select){
  jQuery('.smioapi_taxs_load').show();
  jQuery.get(location.href+"&loadtaxs=1&noheader=1&smioapi_post_type="+jQuery(select).val(), function(data){
    jQuery('.smioapi_taxs_load').hide();
    jQuery("#smioapiPostTaxSelc").html(data);
  }).fail(function() {
    jQuery('.smioapi_taxs_load').hide();
    jQuery("#smioapiPostTaxSelc").html("");
  });
}

function smioapiPostTax(select){
  jQuery('.smioapi_taxs_load').show();
  jQuery.getJSON(location.href+"&loadcats=1&noheader=1&smioapi_object_name="+jQuery(select).val(), function(data){
    jQuery('.smioapi_taxs_load').hide();
    jQuery(".smioapiPostTaxDIV").html(data["checklist"]);
    $('#smioapiPostTaxSelc2').selectize()[0].selectize.destroy();
    jQuery("#smioapiPostTaxSelc2").html(data["options"]);
    jQuery("#smioapiPostTaxSelc2").selectize({tags: true, plugins: ['remove_button','drag_drop']});
  }).fail(function() {
    jQuery('.smioapi_taxs_load').hide();
    jQuery(".smioapiPostTaxDIV").html("");
    jQuery("#smioapiPostTaxSelc2").html("");
  });
}

function smio_delete_sorted_item(element) {
  jQuery(element).closest("li").fadeOut("slow", function(){ jQuery(this).remove() });
  jQuery("#menu-cats-sort-area").sortable("refresh");
  jQuery("#menu-pages-sort-area").sortable("refresh");
}

function smioapi_mobform_submitted(){
  var sortedCats = [];
  jQuery("#menu-cats-sort-area").sortable("toArray").forEach(function(catid) {
    catid = catid.replace("cat-sort-", "");
    if(catid != "0"){
      sortedCats.push(catid);
    }
  });
  var sortedPages = [];
  jQuery("#menu-pages-sort-area").sortable("toArray").forEach(function(pageid) {
    pageid = pageid.replace("page-sort-", "");
    if(pageid != "0"){
      sortedPages.push(pageid);
    }
  });
  jQuery("input[name='mob_categories']").val(sortedCats.join(","));
  jQuery("input[name='mob_pages']").val(sortedPages.join(","));
}