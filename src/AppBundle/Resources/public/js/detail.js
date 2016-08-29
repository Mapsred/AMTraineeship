/**
 * Created by Maps_red on 20/08/2016.
 */
var Detail =  {
    init:function () {
        $(".min-img").click(function () {
            Detail.hideAll();
            var img = $(this).data("img");
            $("."+img).show();

        })
    },

    hideAll: function () {
        $(".embed-responsive.embed-responsive-16by9").hide();
        $(".image_0").hide();
        $(".image_1").hide();
        $(".image_2").hide();
        $(".image_3").hide();
    },

};

$(document).ready(function () {
    Detail.init();
});