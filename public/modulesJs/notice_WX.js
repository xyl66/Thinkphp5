/*vent前端框架 顶部公告栏*/
$(function(){

    var hNotice={
            list:$(".header-notice").find(".hn-list"),
            liHeight:$(".header-notice").find(".hn-list li").height(),
            speed:300,
            timing:2500,
            timer:null,
            scrollUp:function(){
                hNotice.list.stop(true,true).animate({
                    "top":"-="+hNotice.liHeight
                },
                hNotice.speed,
                function(){
                    hNotice.list=$(".header-notice").find(".hn-list");
                    hNotice.list.append(hNotice.list.find("li:first").clone()).find("li:first").remove();
                    hNotice.list.css({"top":0});
                });
            },
            scrollDown:function(){
                hNotice.list.css({"top":-hNotice.liHeight});
                hNotice.list=$(".header-notice").find(".hn-list");
                hNotice.list.find("li:last").clone().insertBefore(hNotice.list.find("li:first"))
                hNotice.list.find("li:last").remove();
                hNotice.list.stop(true,true).animate({
                    "top":"+="+hNotice.liHeight
                },
                hNotice.speed,
                function(){
                    hNotice.list.css({"top":0});
                });
            }
        }

    $(".header-notice").mouseenter(function(event) {
        clearInterval(hNotice.timer);
    });

    $(".header-notice").mouseleave(function(event) {
        hNotice.timer=setInterval(hNotice.scrollUp,hNotice.timing);
    }).trigger('mouseleave');

    $(".header-notice").find(".j_next").click(hNotice.scrollUp);
    $(".header-notice").find(".j_prev").click(hNotice.scrollDown);


});