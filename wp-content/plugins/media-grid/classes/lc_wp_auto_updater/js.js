(function($) { 
    "use strict"; 

    $(document).on('click', '.lcwpau_wizard_btn', function() {
        setTimeout(function() {

            $('#TB_ajaxContent').removeAttr('style');
            $('#TB_closeWindowButton').css('text-decoration', 'none');
        }, 10);
    });


    //remote verifier
    let lcwpau_acting = false;

    $(document).on("click", "#TB_window .lcwpau_ajax", function() {
        let $subj = $(this),
            $wrap = $(this).parents("table");

        if(lcwpau_acting) {
            return false;
        }
        if(!$wrap.find("input[name=lcwpau_username]").val() || !$wrap.find("input[name=lcwpau_purch_code]").val()) {
            return false;
        }
        if(!$('#TB_window input[name=lcwpau_disclaimer]')[0].checked) {
            alert(lcwpau_discl_alert);
            return false;    
        }

        lcwpau_acting = true;
        var btn_txt_backup = $subj.text();
        
        $subj.html('<img src="'+ window.lcwpau_admin_url +'/images/spinner.gif" alt="loading.." />');
        $wrap.find('.lcwpau_mess_wrap p').empty();
        
        const data = {
            action		: $wrap.data("action"),
            username 	: $wrap.find("input[name=lcwpau_username]").val(),
            purch_code 	: $wrap.find("input[name=lcwpau_purch_code]").val(),
        };
        $.post(ajaxurl, data, function(response) {
            let resp = $.trim(response);

            if(resp == "success") {
                $wrap.find("tr").not(".lcwpau_validation_ok").hide();
                $wrap.find(".lcwpau_validation_ok").show();	
                
                setTimeout(function() {
                    window.location.reload();    
                }, 1300);
            }
            else {
                $wrap.find('.lcwpau_mess_wrap p').html('<strong>'+ resp +'</strong>');
            }

        })
        .fail(function(e) {
            if(e.status) {
                console.error(e);
                $wrap.find('.lcwpau_mess_wrap p').html("Unknown error ..");     
            }
        })
        .always(function() {
            lcwpau_acting = false;  
            $subj.html(btn_txt_backup)
        });
    }); 

    $(document).on("click", "#TB_window .lcwpau_revoke", function() {
        if(lcwpau_acting) {
            return false;
        }
        var $wrap = $(this).parents("table");

        $wrap.find("tr").not(".lcwpau_validation_ok").show();
        $wrap.find(".lcwpau_validation_ok").hide();
    }); 	

})(jQuery); 