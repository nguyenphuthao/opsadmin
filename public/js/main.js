(function ($) {
    "use strict"; // Start of use strict

    /*------------ Script initialization -------------*/
    $(window).load(function () {

    });

    /*-------------- Script ready --------------------*/
    $(document).ready(function () {

      
        /* Menu Mobile */
        $(document).on('click', '.navbar-toggle', function () {
            $('.nav-top-menu .nav-menu').slideToggle();
        });

        /* Animated */
        $('.animated').appear(function () {
            var elem = $(this);
            var animation = elem.data('animation');
            if (!elem.hasClass('visible')) {
                var animationDelay = elem.data('animation-delay');
                if (animationDelay) {

                    setTimeout(function () {
                        elem.addClass(animation + " visible");
                    }, animationDelay);

                } else {
                    elem.addClass(animation + " visible");
                }
            }
        });

        /* Popup Form */
        // $(document).on('click', '.link-btn a', function () {
        //     $('#form_Modal').modal({
        //         show: 'false'
        //     });
        // });

        /* Select Connect */
        $('.click_connect_1 .img-connect.active').attr('src', root_public+ 'public/images/home/connect-01-active.png');
        $('.click_connect_2 .img-connect.active').attr('src', root_public+'public/images/home/connect-02-active.png');
        $(document).on('click', '.click_connect_1', function () {
           
            $('.click_connect_1 .img-connect').attr('src', root_public+'public/images/home/connect-01-active.png');
            $('.click_connect_2 .img-connect').attr('src', root_public+'public/images/home/connect-02.png');
            
			$('#connect_date').html('<option value="0">Chọn ngày</option><option value="2017-01-07">07-01-2017</option><option value="2017-01-08">08-01-2017</option>');
			
			ga('send', 'event', 'RegiserPage', 'click', 'change_event_date_1');
        });
        $(document).on('click', '.click_connect_2', function () {
            
            $('.click_connect_1 .img-connect').attr('src', root_public+'public/images/home/connect-01.png');
            $('.click_connect_2 .img-connect').attr('src', root_public+'public/images/home/connect-02-active.png');
            
			
			$('#connect_date').html('<option value="0">Chọn ngày</option><option value="2017-01-21">21-01-2017</option><option value="2017-01-22">22-01-2017</option>');
			
			ga('send', 'event', 'RegiserPage', 'click', 'change_event_date_2');
        });
        /* counters */
        $('.imge-count').viewportChecker({
            classToAdd: 'counted',
            offset: 100,
            callbackFunction: function (elem, action) {
                elem.find('.span-count').countTo();
            }
        });
    });
    /* Video Play */
    $(".link-video a").click(function () {

        var theModal = $(this).data("target"),
        videoSRC = $(this).attr("data-video"),
        videoSRCauto = videoSRC + "?modestbranding=1&rel=0&controls=1&showinfo=0&html5=1&autoplay=1";
        $(theModal + ' iframe').attr('src', videoSRCauto);
        $(theModal + ' button.close').click(function () {
            videoSRCauto = videoSRC + "?modestbranding=1&rel=0&controls=1&showinfo=0&html5=1&autoplay=0";
            $(theModal + ' iframe').attr('src', videoSRCauto);
        });
        $('#video_Modal').on('click', function () {

            videoSRCauto = videoSRC + "?modestbranding=1&rel=0&controls=1&showinfo=0&html5=1&autoplay=0";
            $(theModal + ' iframe').attr('src', videoSRCauto);
        });
    });

    /*-------------- Script Scroll --------------------*/
    $(window).scroll(function () {

        /* Fixed Header Meny Top */
        var h = $(window).scrollTop();
        var width = $(window).width();
        if ( width > 767 ) {
            if( h > 1 ){
                $('.nav-top-menu').addClass('fixed-header-top');
            }
            else {
                $('.nav-top-menu').removeClass('fixed-header-top');
            }
        }

      

    });


})(jQuery); // End of use strict