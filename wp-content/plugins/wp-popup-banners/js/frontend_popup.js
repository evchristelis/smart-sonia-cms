var popup_delay_enable = wpb_frontend_js.popup_delay_enable;
var popup_delay = wpb_frontend_js.popup_delay;
var popup_close_countdown = wpb_frontend_js.popup_close_countdown;
var show_popup_cookie = wpb_frontend_js.show_popup_cookie;
var show_countdown_message = wpb_frontend_js.show_countdown_message;
var transition = wpb_frontend_js.popup_type;
var autoclose_enable = wpb_frontend_js.autoclose_enable;

(function ($) {
    $(function () {
        $(".wpb-text-popup").niceScroll();
        // alert(popup_delay);
        // alert(popup_delay_enable)
        // alert(transition);
        // alert(popup_close_countdown);
        // alert(show_countdown_message);

        $('.wpb_close_btn, #wpb_overlay, .wpb_overlay').click(function () {
            hide(transition);
            $('body').css('overflow', 'inherit');
        });

        var popupdelay_sec = (popup_delay_enable == 'yes' && popup_delay != '') ? popup_delay * 1000 : 100;


        Timeout = setTimeout(function () {
            show(transition);

        }, popupdelay_sec);



        function show(transition) {
            $('.wpb-preview-title').show();
            $('#wpb_overlay').css('display', 'block');
            if (transition == 'fade') {
                $('.wpb-main-wrapper').fadeIn({queue: false, duration: 'slow'});//fadein
                $('.wpb-main-wrapper').animate({top: '0'}, 'slow');//from top

            } else if (transition == 'slide-from-top') {
                $('.wpb-main-wrapper').css('top', '-50%');//from top
                $('.wpb-main-wrapper').fadeIn({queue: false, duration: 'slow'});//from top
                $('.wpb-main-wrapper').animate({top: '0'}, 'slow');//from top

            } else if (transition == 'slide-from-top2') {
                $('.wpb-main-wrapper').css('top', '-50%');//from top
                $('.wpb-main-wrapper').fadeIn({queue: false, duration: 'fast'});//from top
                $('.wpb-main-wrapper').animate({top: '0'}, 'fast');//from top

            } else if (transition == 'zoom') {
                $('.wpb-main-wrapper').css('width', '0%');//from top
                $('.wpb-main-wrapper').css('height', '0%');//from top
                $('.wpb-main-wrapper').fadeIn({queue: false, duration: 50}).css({top: 0, left: 0});
                $('.wpb-main-wrapper').animate({
                    top: '0',
                    left: '0',
                    height: '100%',
                    width: '100%', 
                    opacity: 1,
                    display: 'block'
                },1000);


            } else if (transition == 'slide-fix') {
                $('.wpb-main-wrapper').css('top', '0');
                $('.wpb-main-wrapper').slideDown("slow");
                $('.wpb-main-wrapper').animate({ display: 'block' });

            } else
            {

            }

            if (autoclose_enable == 'yes' && popup_close_countdown != '') {
                if (show_countdown_message == 'yes' && popup_close_countdown != '') {
                    countdown(popup_close_countdown);
                }

                Timeout1 = setTimeout(function () {
                    hide(transition);
                }, popup_close_countdown * 1000);
            }

            if (show_popup_cookie != '' && show_popup_cookie == 1) {
                delete_cookie('wp_popup_once');//delete cookie
            }

        }// end of show

        function hide(transition) {
            $('.wpb-preview-title').hide();
            if (transition == 'normal') {

            } else if (transition == 'fade') {
                $('.wpb-main-wrapper').fadeOut({queue: false, duration: 'slow'});
            
            } else if (transition == 'slide-from-top') {
                $('.wpb-main-wrapper').fadeOut({queue: false, duration: 'slow'});
                $('.wpb-main-wrapper').animate({top: '90%'}, 'slow');
            
            } else if (transition == 'slide-from-top2') {
                $('.wpb-main-wrapper').fadeOut({queue: false, duration: 'slow'});
                $('.wpb-main-wrapper').animate({top: 90}, 'slow');

            } else if (transition == 'slide-from-button') {
                $('.wpb-main-wrapper').fadeOut({queue: false, duration: 'slow'});
                $('.wpb-main-wrapper').animate({top: '55%'}, 'slow');

            } else if (transition == 'zoom') {
                $('.wpb-main-wrapper').fadeOut({queue: false, duration: 'slow'});
                $('.wpb-main-wrapper').animate({
                    top: 0,
                    left: 0,
                    height: 0,
                    width: 0,
                    opacity: 0.5
                });

            } else if (transition == 'slide-fix') {
                $('.wpb-main-wrapper').slideUp("slow");

            } else {
                $('.wpb-main-wrapper, #wpb_overlay').css('display', 'none');//normal

            }

            $('#wpb_overlay').fadeOut({queue: false, duration: 'slow'});

            if (show_popup_cookie != '' && show_popup_cookie == 2) {
                checkCookie();//check and set the cookie
            }

        }//end of hide

        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires=" + d.toGMTString();
            document.cookie = cname + "=" + cvalue + "; " + expires;
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ')
                    c = c.substring(1);
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function checkCookie() {
            var popup_cookie = getCookie("wp_popup_once");
            if (popup_cookie != "") {
                //cookie is set
            } else {
                popup_cookie = 'popup_show_once';
                if (popup_cookie != "" && popup_cookie != null) {
                    setCookie("wp_popup_once", popup_cookie, 1);
                }
            }
        }

        function delete_cookie(name) {
            document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        }

        function countdown(counter) {
            interval = setInterval(function () {
                $('.wpb_countdown').text(counter);
                if (counter == 0) {
                    // Display a login box
                    clearInterval(interval);
                    hide();
                }
                counter--;
            }, 1000);
        }



    });
}(jQuery));//main function end

