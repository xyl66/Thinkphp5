(function(){
    function fixedbarposition(){
        var containeroffsetleft = $('.content-right').offset().left;
        var containerouterWidth = $('.content-right').outerWidth(true);
        var containerouterTop = $('.content-right').offset().top;
        var left = parseInt(containeroffsetleft)+parseInt(containerouterWidth) + 10;
        var Ulheight = $('.fixedBar').children('ul').outerHeight(true);
        $('.fixedBar').css({'left':left,'height':Ulheight,'top':containerouterTop,'margin-top':'0'});
    }
    fixedbarposition();
    $(window).resize(function(event) {
        fixedbarposition();
    });
    $('.fixedBar>ul>li').hover(function() {
        $(this).addClass('hover');
    }, function() {
        $(this).removeClass('hover');
    });
    $('.fixedBar>ul>li').click(function(event) {
        $(this).addClass('cur').siblings('li').removeClass('cur');
    });
    //初始化高亮
    $('.fixedBar>ul>li').eq(0).addClass('cur');
    // 获取相关数据
    var len = $('.fixedBar>ul').children('li').length;
    if(len == 0){
        $('.fixedBar').hide();
    }else{
        var obj = $('.left-menu'),
            data = {};
        data.array_id = [];
        data.array_height = [];
        obj.each(function(index, el) {
            var me = $(this);
            var spanChild = $(this).children('dt').children('span');
            var id = spanChild.data('id'),
                offsetTop = me.offset().top,
                height = me.outerHeight(true);
            var Top = parseInt(offsetTop) + parseInt(height);

            data.array_id.push(id);
            data.array_height.push(Top);
        });
        // console.log(data.array_id,data.array_height);
        for (var i = 0; i < data.array_height.length; i++) {
            var item = data.array_height[i];
            $(window).scroll(function(){
                var scrollTop = $(window).scrollTop();
                if(i == 0){

                }
            })
        };
        // 滚动事件
        $(window).scroll(function(event) {
            var scrollTop = $(window).scrollTop();
            for (var i = 0; i < data.array_height.length; i++) {
                var item = data.array_height[i];
                if(i == 0){
                    if(scrollTop < item){
                        $('.shopManager'+data.array_id[i]).addClass('cur').siblings('li').removeClass('cur');
                    }
                }else{
                    var fitem =data.array_height[i-1];
                    if(scrollTop > fitem && scrollTop < item){
                        $('.shopManager'+data.array_id[i]).addClass('cur').siblings('li').removeClass('cur');
                    }
                }
            };
        });
    };

    $('.fixedBar>ul>li>a').click(function(event) {
        var me = $(this),
            target = me.data('target');
        var ScrollTop = $(target).offset().top;
        if($.browser.chrome){
            elem="body"    
        }else{
            elem=document.documentElement||document.body;
        };
        $(elem).animate({
             scrollTop:ScrollTop
        }, 600)
    });
    
    
    
})()