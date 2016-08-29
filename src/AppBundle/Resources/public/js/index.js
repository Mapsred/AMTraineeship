/**
 * Created by Maps_red on 05/08/2016.
 */
var Index = {
    init: function () {
        $.localScroll();
        $(".cache").delay(1000).fadeOut(500);
        $("#wrapper-header").delay(1500).animate({opacity: '1', width: '100%'}, 500);
        $("#wrapper-navbar").delay(2000).animate({opacity: '1', height: '60px'}, 500);
        $("#main-container-image").delay(2500).animate({opacity: '1'}, 500);
    },

    buttonMenu: function () {
        $(document).on('touchstart mouseover', '#stripes', function (event) {
            event.stopPropagation();
            event.preventDefault();
            if (event.handled !== true) {
                $("#stripes").stop().animate({scale: '1.2', opacity: '0.5'}, 300);
                event.handled = true;
            } else {
                return false;
            }
        });

        $(document).on('touchend mouseout', '#stripes', function (event) {
            event.stopPropagation();
            event.preventDefault();
            if (event.handled !== true) {
                $("#stripes").stop().animate({scale: '1', opacity: '1'}, 300);
                event.handled = true;
            } else {
                return false;
            }
        });
    },

    onScroll: function () {
        var body = document.body, timer;

        window.addEventListener('scroll', function () {
            clearTimeout(timer);
            if (!body.classList.contains('disable-hover'))
                body.classList.add('disable-hover');
            timer = setTimeout(function () {
                body.classList.remove('disable-hover')
            }, 200);
        }, false);
    },

};


$(document).ready(function () {
    Index.init();
    Index.buttonMenu();
    Index.onScroll();
});
