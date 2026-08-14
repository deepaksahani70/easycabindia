$(window).scroll(function () {
    if ($(this).scrollTop() > 40) {
        $('header').addClass("sticky");
    } else {
        $('header').removeClass("sticky");
    }
});


settings = {
    objModalPopupBtn: ".modalButton",
    objModalCloseBtn: ".overlay, .closeBtn",
    objModalDataAttr: "data-popup"
}
$(settings.objModalPopupBtn).bind("click", function () {
    if ($(this).attr(settings.objModalDataAttr)) {

        var strDataPopupName = $(this).attr(settings.objModalDataAttr);
        $(".overlay, #" + strDataPopupName).fadeIn();
    }
});
$(settings.objModalCloseBtn).bind("click", function () {
    $(".modal").fadeOut();
});



$(document).on('click', '.tab_block_main ul li a', function (e) {
    var curTabContentId = $(this).attr('href');
    $(this).parents('.tab_block_main').find('ul li a').removeClass('active');
    $(this).addClass('active');
    $(this).parents('.tab_block_main').find('.tab_content .tab_block').removeClass('active');
    $(curTabContentId).addClass("active");
    e.preventDefault();
});

$(document).ready(function () {

    $('.button-nav button').click(function () {
        $('header .set-position nav').slideToggle()
    });

    $('.button-nav button').click(function () {
        $('.button-nav button').toggleClass('active')
    });
    $(".scrll").click(function () {
        $(".show-more").slideToggle(1000)
    });
});


var day = new Date();
var hr = day.getHours();
if (hr >= 0 && hr < 12) {
    //    alert("Good Morning!");
} else if (hr == 12) {
    //    alert("Good Afternoon!");
} else if (hr >= 12 && hr <= 17) {
    //    alert("Good Evening!");
} else {
    //        alert("Good Night!");
    $(".top-header,.form-adding form p:nth-child(5) input,.form-set-in p:nth-child(4) input").css({
        "background": "#000",
    });

    $(".top-header aside a b,.top-nav ul li a,.holiday-section h1,.holiday-section p,.holiday-section p i,.holiday-section p a,.closeBtn,.tab_block_main .tab_options > li a,.top-nav ul li a i").css({
        "color": "#fff",
    });

    $(".services-area .swiper-wrapper .swiper-slide span,header").css({
        "background": "rgba(0, 0, 0, 0.7)",
    });
    $(".manth-flex a,.form-adding,.closeBtn").css({
        "background": "#333",
    });
    $(".tab_block_main .tab_options > li a,.socale-logo ul li a i").css({
        "background": "#444",
        "border-color": "#444"
    });
    $("section.modalWindow").css({
        "top": "18%",
    });
    $(".form-set-in p a").css({
        "color": "#000",
    });
    $("header nav ul li a.active, header nav ul li a:hover").css({
        "color": "#007aff",
    });
    $(".top-header aside a img").css({
        "display": "none",
    });

    $(".price-laer-one  bubbles-cr").css({
        "background": "rgba(0, 0, 0, 0.8)",
    });
}

$(document).ready(function () {
    $('a[href^="#"]').on('click', function (e) {
        e.preventDefault();

        var target = this.hash;
        var $target = $(target);

        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 900, 'swing', function () {
            window.location.hash = target;
        });
    });
});

var nav = $('#menu > ul > li');
nav.find('li').hide();
nav.click(function () {
    nav.not(this).find('li').hide();
    $(this).find('li').slideToggle();
});
$(function () {
    $('#menu input').click(function () {
        $('#menu ul').slideToggle()
    });
});


jQuery(document).ready(function ($) {
    $('.tab-menu a').on('click', function (e) {
        e.preventDefault();
        $('.tab-menu li').removeClass('active-tab');
        $(this).parent('li').addClass('active-tab');

        /*tab-data*/
        var hrefdata = $(this).attr('href');
        $('.tab-data .data-content').removeClass('active-data');
        $('.tab-data .data-content#' + hrefdata + '').addClass('active-data');
        $('.active-data').prevAll('.data-content').addClass('prev').removeClass('next');
        $('.active-data').nextAll('.data-content').addClass('next').removeClass('prev');

        /*Mover*/
        var leftval = jQuery(this).parent('li').offset().left - jQuery(this).parents('ul').offset().left;
        var ewidth = jQuery(this).width();
        jQuery('.mover').css('left', leftval);
        jQuery('.mover').css('width', ewidth);
    });
});


$(function () {

    $('.md-trigger').on('click', function () {
        $('.md-modal').addClass('md-show');
    });

    $('.md-close').on('click', function () {
        $('.md-modal').removeClass('md-show');
    });

});


$("#menuo").on("mouseenter", function () {
    $("#menuo").addClass('hovered');
})

$("#menuo").on("mouseleave", function () {

    $("#menuo").removeClass('hovered');


})

$('.front span').click(function () {
    $('.front').css('transform', 'rotateY(-180deg)');
    $('.back').css('transform', 'rotateY(0deg)');
});

$('#return').click(function () {
    $('.front').css('transform', 'rotateY(0deg)');
    $('.back').css('transform', 'rotateY(180deg)');
});



(function ($) {
    $.fn.menumaker = function (options) {
        var cssmenu = $(this), settings = $.extend({
            format: "dropdown",
            sticky: false
        }, options);
        return this.each(function () {
            $(this).find(".button").on('click', function () {
                $(this).toggleClass('menu-opened');
                var mainmenu = $(this).next('ul');
                if (mainmenu.hasClass('open')) {
                    mainmenu.slideToggle().removeClass('open');
                }
                else {
                    mainmenu.slideToggle().addClass('open');
                    if (settings.format === "dropdown") {
                        mainmenu.find('ul').show();
                    }
                }
            });
            cssmenu.find('li ul').parent().addClass('has-sub');
            multiTg = function () {
                cssmenu.find(".has-sub").prepend('<span class="submenu-button"></span>');
                cssmenu.find('.submenu-button').on('click', function () {
                    $(this).toggleClass('submenu-opened');
                    if ($(this).siblings('ul').hasClass('open')) {
                        $(this).siblings('ul').removeClass('open').slideToggle();
                    }
                    else {
                        $(this).siblings('ul').addClass('open').slideToggle();
                    }
                });
            };
            if (settings.format === 'multitoggle') multiTg();
            else cssmenu.addClass('dropdown');
            if (settings.sticky === true) cssmenu.css('position', 'fixed');
            resizeFix = function () {
                var mediasize = 1000;
                if ($(window).width() > mediasize) {
                    cssmenu.find('ul').show();
                }
                if ($(window).width() <= mediasize) {
                    cssmenu.find('ul').hide().removeClass('open');
                }
            };
            resizeFix();
            return $(window).on('resize', resizeFix);
        });
    };
})(jQuery);

(function ($) {
    $(document).ready(function () {
        $("#cssmenu").menumaker({
            format: "multitoggle"
        });
    });
})(jQuery);

$(document).ready(async function () {
    try {
        const response = await fetch(window.location.pathname, {
            method: 'HEAD'
        });

        if (response.status === 404) {
            window.location.replace('/404.html');
        }
    } catch (error) {
        console.error('Error checking URL:', error);
    }
});