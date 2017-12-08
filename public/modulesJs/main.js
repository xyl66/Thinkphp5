/*
 * 会员道脚本命名空间
 * @Author  chenjie
 */
var HYD = HYD ? HYD : {}; //会员道命名空间
HYD.Constant = HYD.Constant ? HYD.Constant : {}; //常量
HYD.popbox = HYD.popbox ? HYD.popbox : {}; //图片、产品、专题页面弹窗组件

//下拉通用组件的配置项
HYD.linkType={
    "1":"选择产品",
    "2":"产品分组",
    "3":"专题页面",
    "4":"页面分类",
    "5":"营销活动",
    "6":"商城主页",
    "7":"会员主页",
    "8":"分销申请",
    "9":"购物车",
    "10":"全部产品",
    "12":"产品分类",
    "11":"自定义链接"
};

//获取当前时间戳
HYD.getTimestamp = function() {
    var date = new Date();
    return "" + date.getFullYear() + parseInt(date.getMonth() + 1) + date.getDate() + date.getHours() + date.getMinutes() + date.getSeconds() + date.getMilliseconds();
};

/*
 * 前端接口hint
 * @Author  chenjie
 * @param type    弹出的类型[success|warning|danger]
 * @param content 弹出的内容
 */
HYD.hint = function(type, content, timing) {
    if (!type || !content) return;

    var tpl = $("#tpl_hint").html(), //获取模板
        //渲染模板
        render = _.template(tpl, {
            type: type,
            content: content
        }),
        $hint = $(render),
        speed = 200, //显示速度
        timing = timing || 1500; //显示时间

    $("body").append($hint.css({"opacity":"0","zIndex":"999999"}));

    $hint.animate({
            "opacity": 1,
            "top": 200
        },
        speed,
        function() {
            setTimeout(function() {
                $hint.animate({
                        "opacity": 0,
                        "top": 600
                    },
                    speed,
                    function() {
                        $(this).remove();
                    })
            }, timing);
        });
}

/*
 * 表单验证错误提示前端接口
 * @Author  chenjie
 * @param element [jquery object]表单元素
 * @param msg [string]提示信息
 * @param autofocus [boolean]是否自动获得焦点(默认自动获得)
 */
HYD.FormShowError = function(element, msg, autofocus) {
    if (!element || !msg) {
        return;
    }
    //默认参数为自动获得焦点
    if (autofocus == undefined) {
        autofocus = true;
    }
    //提示错误信息
    element.addClass("error").siblings(".fi-help-text").addClass("error").text(msg).show();
    //是否自动获得焦点
    if (autofocus) {
        element.focus();
    }
    //如果启用了div模拟select则给模拟层也加上错误提示样式
    if (element[0].nodeName.toLowerCase() == "select") {
        element.siblings(".select-sim").addClass("error")
    }
    //内容值改变时时清除错误提示
    element.one("change", function() {
        HYD.FormClearError($(this))
    });
}

/*
 * 清除表单验证错误提示前端接口
 * @Author  chenjie
 * @param element [jquery object]表单元素
 */
HYD.FormClearError = function(element) {
    if (!element) {
        return;
    }
    element.removeClass("error").siblings(".fi-help-text").hide();
    //如果启用了div模拟select则给模拟层也移除错误提示样式
    if (element[0].nodeName.toLowerCase() == "select") {
        element.siblings(".select-sim").removeClass("error")
    }
}

/*
 * 显示二维码弹层
 * @Author  chenjie
 * @param src 二维码地址
 */
HYD.showQrcode = function(src) {
    var $qrcode = $("#qrcode");

    if (!$qrcode.length) {
        var html = _.template($("#tpl_qrcode").html(), {
            src: src
        });
        $qrcode = $(html);
        $qrcode.click(function() {
            $qrcode.fadeOut(300);
        });
        $("body").append($qrcode);
    }

    $qrcode.find("img").attr("src", src);
    $qrcode.fadeIn(300);
}

/*
 * 切换wizard状态
 * @Author  chenjie
 * @param selector 选择器
 * @param index 要切换的索引
 */
HYD.changeWizardStep = function(selector, index) {
    var $wizard = $(selector),
        $wizard_item = $wizard.find(".wizard-item");

    $wizard_item.removeClass("process complete");

    for (var i = 0; i <= index - 1; i++) {
        $wizard_item.filter(":eq(" + i + ")").addClass("complete");
    }

    $wizard_item.filter(":eq(" + index + ")").addClass("process");
}

/*
 * 自动跳转
 * @Author  chenjie
 * @param url 要跳转的url
 * @param timing 跳转等待时长 单位ms
*/
HYD.autoLocation = function(url,timing) {
    if(!url) return;
    var timing=timing?timing:2000;

    timer=setInterval(function(){
        if(timing<=1000){
            clearInterval(timer);
            window.location.href=url;
        }
        else{
            timing=timing-1000;
            $("#j-autoLocation-second").text(timing/1000);
        }
    },1000);
};

/*
 * 异步弹窗表格
 * @Author  chenjie
 * @param options.title 弹窗标题
 * @param options.url 数据接口
 * @param options.data 发送的数据
 * @param options.tpl 数据渲染模板
 * @param options.onOpen 打开窗口后的回调函数
 * @param options.onPageChange 当改变页面时候的回调函数
*/
HYD.ajaxPopTable=function(options){
    var defaults={
        title:"",
        url:"",
        data:{
            p:1
        },
        tpl:"",
        onOpen:null,
        onPageChange:null
    },
    opts=$.extend(true,{},defaults,options),
    $pop=$("<div></div>"),
    ajaxdata,//数据缓存
    cachejbox;


    //渲染数据
    var reRenderData = function(callback) {


        var tpl=opts.tpl,
            url=opts.url;

        var dorender=function(data){

            ajaxdata=data;
            //渲染模板

            var html = _.template(tpl,data),
                $render = $(html);
            $pop.empty().append($render);//插入dom

            //分页点击事件
            $pop.find(".paginate a:not(.disabled,.cur)").click(function(){
                //获得页数
                var href=$(this).attr("href"),
                    page=href.split("/");

                for(var i=0;i<page.length;i++){
                    if(page[i]=="p"){
                        opts.data.p=page[i+1];
                        reRenderData();
                        break;
                    }
                }

                return false;
            });

            if(callback) callback();//reRenderData的回调函数
            if(opts.onPageChange) opts.onPageChange(cachejbox,ajaxdata);//翻页的回调函数
        }

        //获取数据并渲染
        $.ajax({
            url:url,
            type:"post",
            dataType:"json",
            data:opts.data,
            success:function(data){

                if (data.status == 1) {

                    dorender(data);
                } else {
                    HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
                }
            }
        });
    };
    reRenderData(function(){
        $.jBox.show({
            title: opts.title,
            content: $pop,
            btnOK: {show:false},
            btnCancel:{show:false},
            onOpen:function(jbox){
                cachejbox=jbox;
                if(opts.onOpen) opts.onOpen(jbox,ajaxdata);
            }
        });
    });
}


/*
 * 图片选择器
 * @Author  chenjie
 * @param callback 回调函数
 * @return Array 图片列表数组
 */
HYD.popbox.ImgPicker = function(callback) {
    var html = $("#tpl_popbox_ImgPicker").html(),
        $picker = $(html),//选择器
        imglist;//图片列表缓存

    /*
     * ajax 根据页数 获取图片列表数据
     * @param page 页数
     */
    var showImgListRender = function(page,callback_showImgListRender) {
        var dorender=function(data){
            imglist=data.list;//图片列表

            if(!imglist || !imglist.length){
                $picker.find(".imgpicker-list").append("<p class='txtCenter'>对不起，暂无图片</p>");
            }
            else{
                //渲染模板
                var html = _.template($("#tpl_popbox_ImgPicker_listItem").html(), {dataset: imglist}),
                    $render = $(html);

                //绑定选择事件
                $render.filter("li").click(function() {
                    $(this).toggleClass("selected");
                });

                $picker.find(".imgpicker-list").empty().append($render);//插入dom

                //分页符
                var paginate=data.page,
                    $render_paginate=$(paginate);

                $render_paginate.filter("a:not(.disabled,.cur)").click(function(){
                    //获得页数
                    var href=$(this).attr("href"),
                        page=href.split("/");
                    page=page[page.length-1];
                    page=page.replace(/.html/,"");

                    showImgListRender(page);
                    return false;
                })

                $picker.find(".paginate").empty().append($render_paginate);//插入dom

            }
            if(callback_showImgListRender) callback_showImgListRender();//执行回调，传入所有数据

        }

        //获取数据并渲染
        $.ajax({
            url:"/Design/getImg",
            type:"post",
            dataType:"json",
            data:{"p":parseInt(page)},
            // beforeSend:function(){
            //     $.jBox.showloading();
            // },
            success:function(data){
                if (data.status == 1) {
                    dorender(data);
                } else {
                    HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
                }
                // $.jBox.hideloading();
            }
        });
    };

    /*
     * 初始化上传组件
     * @param jbox 弹窗对象
     */
    var initUpload=function(jbox){
        var arrSelected=[];//选中的数据

        //上传事件
        $picker.find("#imgpicker_upload_input").uploadify({
            "debug": false,
            "auto": true,
            "formData": {"PHPSESSID": $.cookie("PHPSESSID")},
            "width": 60,
            "height": 60,
            "multi": true,
            'swf': '/Public/plugins/uploadify/uploadify.swf',
            'uploader': '/Design/uploadFile', //接口名称
            "buttonText": "+",
            "fileSizeLimit": "5MB",
            "fileTypeExts": "*.jpg; *.jpeg; *.png; *.gif; *.bmp",
            'onSelectError': function(file, errorCode, errorMsg) {
                switch (errorCode) {
                    case -100:
                        HYD.hint("danger", "对不起，系统只允许您一次最多上传10个文件");
                        break;
                    case -110:
                        HYD.hint("danger", "对不起，文件 [" + file.name + "] 大小超出5MB！");
                        break;
                    case -120:
                        HYD.hint("danger", "文件 [" + file.name + "] 大小异常！");
                        break;
                    case -130:
                        HYD.hint("danger", "文件 [" + file.name + "] 类型不正确！");
                        break;
                }
            },
            'onFallback': function() {
                HYD.hint("danger", "您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
            },
            'onUploadSuccess': function(file, data, response) {
                var data = $.parseJSON(data),
                    tpl = $("#tpl_popbox_ImgPicker_uploadPrvItem").html(), //获取模板
                    $PrvPanel = $picker.find(".imgpicker-upload-preview"); //获取预览图容器

                var url=data.file_path;//图片路径

                arrSelected.push(url);//将数据插入数组

                var html = _.template(tpl, {url: url}), //渲染模板
                $render = $(html);

                //删除图片事件
                $render.find(".j-imgpicker-upload-btndel").click(function() {
                    var index=$picker.find(".imgpicker-upload-preview li").index($(this).parent("li"));
                    //移除dom并从数组中删除
                    $render.fadeOut(300, function() {
                        arrSelected.splice(index,1);
                        $(this).remove();
                    });
                });

                $PrvPanel.append($render); //插入文档
            },
            onUploadError: function(file, errorCode, errorMsg, errorString) {
                HYD.hint("danger", "对不起：" + file.name + "上传失败：" + errorString);
            }
        });

        //使用上传图片按钮事件
        $picker.find("#j-btn-uploaduse").click(function(){
            if(callback) callback(arrSelected);//执行ImgPicker的回调函数，输出选中的图片数据
            $.jBox.close(jbox);
        });
    };

    //打开图片选择器窗口，并渲染第一页数据
    showImgListRender(1,function(){
        $.jBox.show({
            title: "选择图片",
            content: $picker,
            btnOK: {show: false},
            btnCancel: {show: false},
            onOpen:function(jbox){
                var $btnuse=$picker.find("#j-btn-listuse");//使用选中图片按钮

                //使用选中图片事件
                $btnuse.click(function(){
                    var arrSelected=[];//选中的数据

                    //将选中的图片数据推入数组
                    $picker.find(".imgpicker-list li.selected").each(function(){
                        arrSelected.push(imglist[$(this).index()]);
                    });

                    if(callback) callback(arrSelected);//执行ImgPicker的回调函数，输出选中的图片数据

                    $.jBox.close(jbox);
                });

                //切换到上传图片选项卡，初始化上传组件
                $picker.find(".j-initupload").one("click",function(){
                    initUpload(jbox);
                });
            }
        });
    });
};


/**
 * 导航icon小图标选择器
 * @Author xiege
 * @param 
 * return Array 图片列表数组
 */

HYD.popbox.IconPicker = function(callback) {
    var html = $("#icon_imgPicker").html(),
        $picker = $(html),//选择器
        elem,
        imglist;//图片列表缓存
    if($.browser.chrome){
        elem="body"    
    }else{
        elem=document.documentElement||document.body;
    };
    // 插入选择器
    $(elem).append($picker);
    // 插入相关图标
    var tpl = $('#icon_imglist').html();
    // console.log(HYD.popbox.iconimgsrc.data);
    var data={style:'style1',color:'color0'}; //默认数据
    var rerenderIcon = function(datas){
        data = datas ? datas : data;
        var imglist = _.template(tpl,{data:HYD.popbox.iconimgsrc.data[data.style][data.color]});
        $picker.find('.albums-icon-tab').html(imglist);
        $picker.find('.albums-icon-tab').find('li').click(function(event) {
            var me = $(this);
            if(!me.hasClass('selected')){
                me.addClass('selected').siblings('li').removeClass('selected');
            }
        });
    }
    rerenderIcon(data); // 首次载入数据
    // 选择风格
    $picker.find('.albums-cr-actions').children('a').click(function(event) {
        var me = $(this),
            style = me.data('style');
        data.style = style;
        me.addClass('cur').siblings('a').removeClass('cur');
        rerenderIcon(data);
    });
    // 选择颜色
    $picker.find('.albums-color-tab').find('li').click(function(event) {
        var me = $(this),
            color = me.data('color');
            data.color = color;
        me.addClass('cur').siblings('li').removeClass('cur');
        rerenderIcon(data);
        if(color == 'color1'){
            $('.albums-icon-tab').find('li').css({background:'#333'})
        }
    });
    var selectAy = [];
    // 使用选中图片
    $picker.find('#j-useIcon').click(function(event) {
        var $ctrl = $picker.find('.albums-icon-tab').find('li.selected');
        if($ctrl.length!=0){
            var imgsrc = $ctrl.children('img').attr('src');
            imgsrc = imgsrc.replace('Public','PublicMob');
            selectAy.push(imgsrc);
            closeIconPicker();
            if(callback) callback(selectAy);
        }else{
            HYD.hint("danger", "对不起，请选择一张小图标");
            return false;
        };
    });
    // 关闭窗口
    var closeIconPicker = function(){
        $picker.remove();
    };
    $picker.find('#Jclose').click(function(event) {
        closeIconPicker();
    });
};

/*
 * 自定义模块选择器
 * @Author  chenjie
 * @param callback 回调函数
 * @return Object 模块值
 */
HYD.popbox.ModulePicker=function(callback){
    var html = $("#tpl_popbox_ModulePicker").html(),
        $picker = $(html),//选择器
        moduleList;//数据缓存列表

    /*
     * ajax 根据页数 获取模块列表数据
     * @param page 页数
     */
    var showListRender = function(page,callback_showListRender) {
        var dorender=function(data){
            moduleList=data.list;//图片列表

            if(!moduleList || !moduleList.length){
                $picker.find(".modulePicker-list").append("<p class='txtCenter'>对不起，暂无自定义模块</p>");
            }
            else{
                var tpl = $("#tpl_popbox_ModulePicker_item").html(),//获取item模板
                    html = _.template(tpl, {dataset:moduleList}),//渲染列表数据
                    $render = $(html);

                $picker.find(".modulePicker-list").empty().append($render);//插入dom

                //分页符
                var paginate=data.page,
                    $render_paginate=$(paginate);

                $render_paginate.filter("a:not(.disabled,.cur)").click(function(){
                    //获得页数
                    var href=$(this).attr("href"),
                        page=href.split("/");
                    page=page[page.length-1];
                    page=page.replace(/.html/,"");

                    showListRender(page);
                    return false;
                })

                $picker.find(".paginate").empty().append($render_paginate);//插入dom

            }
            if(callback_showListRender) callback_showListRender();//执行回调，传入所有数据
        }

        //获取数据并渲染
        $.ajax({
            url:"/Design/getModule",
            type:"post",
            dataType:"json",
            data:{"p":parseInt(page)},
            // beforeSend:function(){
            //     $.jBox.showloading();
            // },
            success:function(data){
                if (data.status == 1) {
                    dorender(data);
                } else {
                    HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
                }
                // $.jBox.hideloading();
            }
        });
    };

    //打开选择器窗口，并渲染第一页数据
    showListRender(1,function(){
        $.jBox.show({
            title: "选择自定义模块",
            content: $picker,
            btnOK: {show: false},
            btnCancel: {show: false},
            onOpen:function(jbox){
               //绑定选择事件
                $picker.on("click",".j-select",function() {
                    var index=$(".modulePicker-list li").index($(this).parent("li"));
                    if(callback) callback(moduleList[index]);
                    $.jBox.close(jbox);
                });
            }
        });
    });
}

/*
 * 产品及分组选择器
 * @Author  chenjie
 * @param showmode 显示模式[goods|goodsMulti|group|all]
 * @param callback 回调函数
 */
HYD.popbox.GoodsAndGroupPicker=function(showmode,callback){
    var html = $("#tpl_popbox_GoodsAndGroupPicker").html(),
        $picker = $(html),//选择器
        datalist_goods,//产品数据缓存列表
        datalist_group;//分组数据缓存列表
    var matchlist = [];
    var goods_id = [];
    /*
     * ajax 根据页数 获取产品数据
     * @param page 页数
     */
    var showListRender_goods = function(page,callback_showListRender) {
        var dorender=function(data){
            datalist_goods=data.list;//图片列表
            if(!datalist_goods || !datalist_goods.length){
                $picker.find(".gagp-goodslist").append("<p class='txtCenter'>对不起，暂无数据</p>");
            }
            else{
                var tpl = $("#tpl_popbox_GoodsAndGroupPicker_goodsitem").html(),//获取item模板
                    html = _.template(tpl, {dataset:datalist_goods}),//渲染列表数据
                    $render = $(html);
                //选取事件
                $render.find(".j-select").click(function(){
                    var $btn=$(this),
                        $parent=$btn.parent("li"),
                        index = $parent.index(),
                        goodID = $parent.data('item'),
                        newArray;
                    // 此处兼容优惠相关设置 
                    var oldData = $('.j-verify').val();
                    if(oldData!=''){
                        newArray = oldData.split(',');
                    }else{
                        newArray=[];
                    }
                    // console.log(newArray)
                    if($parent.hasClass("selected")){
                        $parent.removeClass("selected");
                        $btn.removeClass("btn-success").text("选取");
                        // 删除已选中列表
                        if(matchlist.length != 0){
                            for(var i = 0;i < matchlist.length;i++){
                                if(goodID == matchlist[i].item_id){
                                    matchlist.splice(i,1);
                                    break;
                                }
                            }
                        };
                        if(goods_id.length != 0){
                            for (var i = 0; i < goods_id.length; i++) {
                                var item = goods_id[i];
                                if(goodID == item){
                                    goods_id.splice(i,1);
                                    break;
                                }
                            };
                            
                        };
                        // 兼容优惠券选择宝贝功能
                        if(newArray.length != 0){
                            for (var i = 0; i < newArray.length; i++) {
                                var item = newArray[i];
                                if(goodID == item){
                                    newArray.splice(i,1);
                                    break;
                                }
                            };
                            oldData = newArray.join(',');
                            $('.j-verify').val(oldData);
                        }
                    }
                    else{
                        $parent.addClass("selected");
                        $btn.addClass("btn-success").text("已选");
                        // 添加已选中列表
                        matchlist.push(datalist_goods[index]);
                        goods_id.push(goodID);
                        // 兼容优惠券选择宝贝功能
                        newArray.push(goodID);
                        oldData = newArray.join(',');
                        $('.j-verify').val(oldData);
                    }
                    
                });

                $picker.find(".gagp-goodslist").empty().append($render);//插入dom
                //分页符
                var paginate=data.page,
                    $render_paginate=$(paginate);

                $render_paginate.filter("a:not(.disabled,.cur)").click(function(){
                    //获得页数
                    var href=$(this).attr("href"),
                        page=href.split("/");
                    page=page[page.length-1];
                    page=page.replace(/.html/,"");

                    showListRender_goods(page);
                    
                    return false;
                })

                $picker.find(".paginate:eq(0)").empty().append($render_paginate);//插入dom

            }
            var item_id = [];
            
            if($('.j-verify').val()!=''){
                item_id = $('.j-verify').val().split(',')
            }else{
                $('.img-list li').not('.img-list-add').each(function(index, el) {
                var id = $(this).data('item');
                item_id.push(id);
            });
            }
            $picker.find('li').each(function(index, el) {
                var me = $(this);
                var item = me.data('item');
                $.each(item_id,function(index, el) {
                    if(item == el){
                        me.addClass('selected');
                        me.children('.j-select').addClass("btn-success").text("已选");
                    };
                });
            });
            if(callback_showListRender) callback_showListRender();//执行回调，传入所有数据
        }

        //获取数据并渲染
        $.ajax({
            url:"/Design/getItem",
            type:"post",
            dataType:"json",
            data:{"p":parseInt(page)},
            // beforeSend:function(){
            //     $.jBox.showloading();
            // },
            success:function(data){
                if (data.status == 1) {
                    dorender(data);
                } else {
                    HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
                }
                // $.jBox.hideloading();
            }
        });
    };

    /*
     * ajax 根据页数 获取产品分组数据
     * @param page 页数
     */
    var showListRender_group = function(page,callback_showListRender) {
        var dorender=function(data){
            datalist_group=data.list;//图片列表
            // console.log(data.list)
            if(!datalist_group || !datalist_group.length){
                $picker.find(".gagp-grouplist").append("<p class='txtCenter'>对不起，暂无数据</p>");
            }
            else{
                var tpl = $("#tpl_popbox_GoodsAndGroupPicker_groupitem").html(),//获取item模板
                    html = _.template(tpl, {dataset:datalist_group}),//渲染列表数据
                    $render = $(html);

                $picker.find(".gagp-grouplist").empty().append($render);//插入dom

                //分页符
                var paginate=data.page,
                    $render_paginate=$(paginate);

                $render_paginate.filter("a:not(.disabled,.cur)").click(function(){
                    //获得页数
                    var href=$(this).attr("href"),
                        page=href.split("/");
                    page=page[page.length-1];
                    page=page.replace(/.html/,"");

                    showListRender_group(page);
                    return false;
                })

                $picker.find(".paginate").empty().append($render_paginate);//插入dom

            }
            var groupID = $('.badge-success').data('group');
            if(groupID != undefined){
                $picker.find(".gagp-grouplist li").each(function(index, el) {
                    var me = $(this),
                        group_id = me.data('group');
                    if(groupID == group_id){
                        me.find('.j-select').addClass("btn-success").text("已选");
                    }
                });
            }
            if(callback_showListRender) callback_showListRender();//执行回调，传入所有数据
        }

        //获取数据并渲染
        $.ajax({
            url:"/Design/getGroup",
            type:"post",
            dataType:"json",
            data:{"p":parseInt(page)},
            // beforeSend:function(){
            //     $.jBox.showloading();
            // },
            success:function(data){
                if (data.status == 1) {
                    dorender(data);
                } else {
                    HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
                }
                // $.jBox.hideloading();
            }
        });
    };

    //产品多选使用事件
    var selectEvent_goods_multi=function(jbox,data){
        $picker.on("click",".j-btn-goodsuse",function(){
            var arrSelected=[],//选中的数据
                returnType=1;//返回类型为产品
            // console.log(datalist_goods)

            // //将选中的数据插入数组
            // $picker.find(".gagp-goodslist li.selected").each(function(){
            //     arrSelected.push(datalist_goods[$(this).index()]);
            // });
            // if(callback) callback(arrSelected,returnType);//执行回调函数，输出选中数据
            if(callback) callback(matchlist,returnType,goods_id);//执行回调函数，输出选中数据
            $.jBox.close(jbox);
        });
    }

    //产品单选事件
    var selectEvent_goods=function(jbox){
        var returnType=1;//返回类型为产品

        $picker.find(".j-btn-goodsuse").remove();//移除多选按钮

        $picker.on("click",".gagp-goodslist .j-select",function() {
            var index=$(".gagp-goodslist li").index($(this).parent("li"));
            if(callback) callback(datalist_goods[index],returnType);
            $.jBox.close(jbox);
        });
    }

    //分组标签单选事件
    var selectEvent_group=function(jbox){
        var returnType=2;//返回类型为产品分组标签

        $picker.on("click",".gagp-grouplist .j-select",function() {
            var index=$(".gagp-grouplist li").index($(this).parent("li"));
            if(callback) callback(datalist_group[index],returnType);
            $.jBox.close(jbox);
        });
    }

    //产品或者分组标签单选事件
    var selectEvent_goodsAndGroup=function(jbox){
        selectEvent_goods(jbox);

        //切换标签产品分组执行初始化
        $picker.find(".j-tab-group").one("click",function(){
            showListRender_group(1,function(){
                selectEvent_group(jbox)//绑定选择事件
            });
        });
    };
    //显示不同的模式
    switch(showmode){
        case "goods":
        case "goodsMulti":
            $picker.find(".tabs").remove();
            $picker.find(".gagp-goodslist").unwrap().unwrap();
            $picker.find(".tc[data-index='2']").remove();
            //打开选择器窗口，并渲染产品第一页数据
            showListRender_goods(1,function(){
                var title = '<span class="fl">选择产品</span><div class="goodsearch"><input type="text" name="title" placeholder="请输入产品名称" />'
                +'<select class="select small newselect" style="width:90px;"><option value="-1">在售中</option><option value="3">仓库中</option></select>'
                +'<a href="javascript:;" class="btn btn-primary jGetgood"><i class="gicon-search white"></i>查询</a></div>';
                $.jBox.show({
                    title: title,
                    content: $picker,
                    btnOK: {show: false},
                    btnCancel: {show: false},
                    onOpen:function(jbox){
                        if(showmode=="goodsMulti"){
                            var item_id = [];
                            $('.img-list li').not('.img-list-add').each(function(index, el) {
                                var id = $(this).data('item');
                                item_id.push(id);
                            });
                            $picker.find('li').each(function(index, el) {
                                var me = $(this);
                                var item = me.data('item');
                                $.each(item_id,function(index, el) {
                                    if(item == el){
                                        me.addClass('selected');
                                        me.children('.j-select').addClass("btn-success").text("已选");
                                    };
                                });
                            });
                            selectEvent_goods_multi(jbox,item_id);
                        }
                        else{
                            selectEvent_goods(jbox);
                        };
                        // 查询
                        $(document).on('click', '.jGetgood', function(event) {
                            var titles = $(this).siblings('input').val();
                            var status = $(this).siblings('select').val();
                            var showListRender_goods = function(page,callback_showListRender){
                                page = page ? page : 1;
                                var dorender=function(data){
                                    datalist_goods=data.list;//图片列表
                                    if(!datalist_goods || !datalist_goods.length){
                                        $picker.find(".gagp-goodslist").empty().append("<p class='txtCenter'>对不起，暂无数据</p>");
                                        $picker.find(".paginate").empty();
                                    }
                                    else{
                                        var tpl = $("#tpl_popbox_GoodsAndGroupPicker_goodsitem").html(),//获取item模板
                                            html = _.template(tpl, {dataset:datalist_goods}),//渲染列表数据
                                            $render = $(html);

                                        //选取事件
                                        $render.find(".j-select").click(function(){
                                            var $btn=$(this),
                                                $parent=$btn.parent("li"),
                                                index = $parent.index(),
                                                goodID = $parent.data('item'),
                                                newArray;
                                            // 此处兼容优惠相关设置 
                                            var oldData = $('.j-verify').val();
                                            if(oldData!=''){
                                                newArray = oldData.split(',');
                                            }else{
                                                newArray = [];
                                            }
                                            // console.log(newArray)
                                            if($parent.hasClass("selected")){
                                                $parent.removeClass("selected");
                                                $btn.removeClass("btn-success").text("选取");
                                                // 删除已选中列表
                                                if(matchlist.length != 0){
                                                    for(var i = 0;i < matchlist.length;i++){
                                                        if(goodID == matchlist[i].item_id){
                                                            matchlist.splice(i,1);
                                                            break;
                                                        }
                                                    }
                                                };
                                                if(goods_id.length != 0){
                                                    for (var i = 0; i < goods_id.length; i++) {
                                                        var item = goods_id[i];
                                                        if(goodID == item){
                                                            goods_id.splice(i,1);
                                                            break;
                                                        }
                                                    };
                                                    
                                                };
                                                // 兼容优惠券选择宝贝功能
                                                if(newArray.length != 0){
                                                    for (var i = 0; i < newArray.length; i++) {
                                                        var item = newArray[i];
                                                        if(goodID == item){
                                                            newArray.splice(i,1);
                                                            break;
                                                        }
                                                    };
                                                    oldData = newArray.join(',');
                                                    $('.j-verify').val(oldData);
                                                }
                                            }
                                            else{
                                                $parent.addClass("selected");
                                                $btn.addClass("btn-success").text("已选");
                                                // 添加已选中列表
                                                matchlist.push(datalist_goods[index]);
                                                goods_id.push(goodID);
                                                // 兼容优惠券选择宝贝功能
                                                newArray.push(goodID);
                                                oldData = newArray.join(',');
                                                $('.j-verify').val(oldData);
                                            }
                                            
                                        });

                                        $picker.find(".gagp-goodslist").empty().append($render);//插入dom

                                        //分页符
                                        var paginate=data.page,
                                            $render_paginate=$(paginate);

                                        $render_paginate.filter("a:not(.disabled,.cur)").click(function(){
                                            //获得页数
                                            var href=$(this).attr("href"),
                                                page=href.split("/");
                                            page=page[page.length-1];
                                            page=page.replace(/.html/,"");

                                            showListRender_goods(page);
                                            return false;
                                        })

                                        $picker.find(".paginate:eq(0)").empty().append($render_paginate);//插入dom

                                    }
                                    if(callback_showListRender) callback_showListRender();//执行回调，传入所有数据
                                };
                                //获取数据并渲染
                                $.ajax({
                                    url:"/Design/getItem",
                                    type:"post",
                                    dataType:"json",
                                    data:{
                                        "p":parseInt(page),
                                        "title":titles,
                                        "status":status
                                    },
                                    // beforeSend:function(){
                                    //     $.jBox.showloading();
                                    // },
                                    success:function(data){
                                        if (data.status == 1) {
                                            dorender(data);
                                        } else {
                                            HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
                                        }
                                        // $.jBox.hideloading();
                                    }
                                });
                            }
                            showListRender_goods()
                        });
                    }
                });
            });
        break;
        case "group":
            $picker.find(".tabs").remove();
            $picker.find(".gagp-grouplist").unwrap().unwrap();
            $picker.find(".tc[data-index='1']").remove();
            // returnType=2;//产品分组标签类型
            //打开选择器窗口，并渲染产品分组第一页数据
            showListRender_group(1,function(){
                $.jBox.show({
                    title: "选择产品分组",
                    content: $picker,
                    btnOK: {show: false},
                    btnCancel: {show: false},
                    onOpen:function(jbox){
                        selectEvent_group(jbox);
                    }
                });
            });
        break;
        case "all":
            //打开选择器窗口，并渲染产品第一页数据
            showListRender_goods(1,function(){
                $.jBox.show({
                    title:"选择产品或产品分组",
                    content: $picker,
                    btnOK: {show: false},
                    btnCancel: {show: false},
                    onOpen:function(jbox){
                        selectEvent_goodsAndGroup(jbox)
                    }
                });
            });
        break;
    }
}

/*
 * 专题页面及分类选择器
 * @Author  chenjie
 * @param showmode 显示模式[mgzCateMulti|mgz|mgzCate|all]
 * @param callback 回调函数
 */
HYD.popbox.MgzAndMgzCate = function(showmode, callback) {
    var html = $("#tpl_popbox_MgzAndMgzCate").html(),
        $picker = $(html), //选择器
        datalist_mgz,//数据缓存列表
        datalist_mgzCate;//数据缓存列表

    /*
     * ajax 根据页数 获取专题页面
     * @param page 页数
     */
    var showListRender_mgz = function(page, callback_showListRender) {
        var dorender = function(data) {
            datalist_mgz = data.list; //图片列表

            if (!datalist_mgz || !datalist_mgz.length) {
                $picker.find(".mgz-list-panel1").empty().append("<p class='txtCenter'>对不起，暂无数据</p>");
            } else {
                var tpl = $("#tpl_popbox_MgzAndMgzCate_item").html(), //获取item模板
                    html = _.template(tpl, {
                        dataset: datalist_mgz
                    }), //渲染列表数据
                    $render = $(html);

                //选取事件
                $render.find(".j-select").click(function() {
                    var $btn = $(this),
                        $parent = $btn.parent("li");

                    if ($parent.hasClass("selected")) {
                        $parent.removeClass("selected");
                        $btn.removeClass("btn-success").text("选取");
                    } else {
                        $parent.addClass("selected");
                        $btn.addClass("btn-success").text("已选");
                    }
                });

                $picker.find(".mgz-list-panel1").empty().append($render); //插入dom

                //分页符
                var paginate = data.page,
                    $render_paginate = $(paginate);
                $render_paginate.filter("a:not(.disabled,.cur)").click(function() {
                    //获得页数
                    var href = $(this).attr("href"),
                        page = href.split("/");
                    page = page[page.length - 1];
                    page = page.replace(/.html/, "");

                    showListRender_mgz(page);
                    return false;
                })

                $picker.find(".paginate").empty().append($render_paginate); //插入dom

            }
            if (callback_showListRender) callback_showListRender(); //执行回调，传入所有数据
        }

        //获取数据并渲染
        $.ajax({
            url: "/Design/getMagazine",
            type: "post",
            dataType: "json",
            data: {
                "p": parseInt(page)
            },
            // beforeSend: function() {
            //     $.jBox.showloading();
            // },
            success: function(data) {
                if (data.status == 1) {
                    dorender(data);
                } else {
                    HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
                }
                // $.jBox.hideloading();
            }
        });
    };

    /*
     * ajax 根据页数 获取页面分类
     * @param page 页数
     */
    var showListRender_mgzCat = function(page, callback_showListRender) {
        var dorender = function(data) {
            datalist_mgzCate = data.list; //图片列表

            if (!datalist_mgzCate || !datalist_mgzCate.length) {
                $picker.find(".mgz-list-panel2").empty().append("<p class='txtCenter'>对不起，暂无数据</p>");
            } else {
                var tpl = $("#tpl_popbox_MgzAndMgzCate_item").html(), //获取item模板
                    html = _.template(tpl, {
                        dataset: datalist_mgzCate
                    }), //渲染列表数据
                    $render = $(html);

                //选取事件
                $render.find(".j-select").click(function() {
                    var $btn = $(this),
                        $parent = $btn.parent("li");

                    if ($parent.hasClass("selected")) {
                        $parent.removeClass("selected");
                        $btn.removeClass("btn-success").text("选取");
                    } else {
                        $parent.addClass("selected");
                        $btn.addClass("btn-success").text("已选");
                    }
                });

                $picker.find(".mgz-list-panel2").empty().append($render); //插入dom

                //分页符
                var paginate = data.page,
                    $render_paginate = $(paginate);

                $render_paginate.filter("a:not(.disabled,.cur)").click(function() {
                    //获得页数
                    var href = $(this).attr("href"),
                        page = href.split("/");
                    page = page[page.length - 1];
                    page = page.replace(/.html/, "");

                    showListRender_mgzCat(page);
                    return false;
                })

                $picker.find(".paginate").empty().append($render_paginate); //插入dom

            }
            if (callback_showListRender) callback_showListRender(); //执行回调，传入所有数据
        }

        //获取数据并渲染
        $.ajax({
            url: "/Design/getMagazineCategory",
            type: "post",
            dataType: "json",
            data: {
                "p": parseInt(page)
            },
            // beforeSend: function() {
            //     $.jBox.showloading();
            // },
            success: function(data) {
                if (data.status == 1) {
                    dorender(data);
                } else {
                    HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
                }
                // $.jBox.hideloading();
            }
        });
    };

    //自定页面单选事件
    var selectEvent_mgz = function(jbox) {
        $picker.on("click",".mgz-list-panel1 .j-select",function() {
            var index = $(".mgz-list-panel1 li").index($(this).parent("li"));
            if (callback) callback(datalist_mgz[index], 3);
            $.jBox.close(jbox);
        });
    }

    //页面分类单选事件
    var selectEvent_mgzCat = function(jbox) {
        $picker.on("click",".mgz-list-panel2 .j-select",function() {
            var index = $(".mgz-list-panel2 li").index($(this).parent("li"));
            if (callback) callback(datalist_mgzCate[index], 4);
            $.jBox.close(jbox);
        });
    }

    //页面分类多选使用事件
    var selectEvent_mgzCat_multi=function(jbox){
        $picker.on("click",".j-btn-use",function(){
            var arrSelected=[],//选中的数据
                returnType=4;//返回类型为专题分类

            //将选中的数据插入数组
            $picker.find(".mgz-list-panel2 li.selected").each(function(){
                arrSelected.push(datalist_mgzCate[$(this).index()]);
            });

            if(callback) callback(arrSelected,returnType);//执行回调函数，输出选中数据

            $.jBox.close(jbox);
        });
    }

    //all
    var selectEvent_mgzAndmgzCat=function(jbox){
        selectEvent_mgz(jbox);

        //切换标签产品分组执行初始化
        $picker.find(".j-tab-mgzcate").one("click",function(){
            showListRender_mgzCat(1,function(){
                selectEvent_mgzCat(jbox)//绑定选择事件
            });
        });
    }

    //显示不同的模式
    switch(showmode){
        case "mgzCate"://选择专题页面分类
            $picker.find(".tabs").remove();
            $picker.find(".mgz-list-panel2").unwrap().unwrap();
            $picker.find(".tc[data-index='1']").remove();
            $picker.find(".j-btn-use").remove();
            //打开选择器窗口，并渲染产品分组第一页数据
            showListRender_mgzCat(1,function(){
                $.jBox.show({
                    title: "选择专题分类",
                    content: $picker,
                    btnOK: {show: false},
                    btnCancel: {show: false},
                    onOpen:function(jbox){
                        selectEvent_mgzCat(jbox);
                    }
                });
            });
            break;
        case "mgzCateMulti"://专题分类多选模式
            $picker.find(".tabs").remove();
            $picker.find(".mgz-list-panel2").unwrap().unwrap();
            $picker.find(".tc[data-index='1']").remove();
            //打开选择器窗口，并渲染第一页数据
            showListRender_mgzCat(1,function(){
                $.jBox.show({
                    title: "选择专题分类",
                    content: $picker,
                    btnOK: {show: false},
                    btnCancel: {show: false},
                    onOpen:function(jbox){
                        selectEvent_mgzCat_multi(jbox);
                    }
                });
            });
            break;
        case "mgz"://选择专题页面
            $picker.find(".tabs").remove();
            $picker.find(".mgz-list-panel1").unwrap().unwrap();
            $picker.find(".tc[data-index='2']").remove();
            $picker.find(".j-btn-use").remove();
            //打开选择器窗口，并渲染产品第一页数据
            showListRender_mgz(1,function(){
                $.jBox.show({
                    title:"选择专题页面",
                    content: $picker,
                    btnOK: {show: false},
                    btnCancel: {show: false},
                    onOpen:function(jbox){
                        selectEvent_mgzAndmgzCat(jbox)
                    }
                });
            });
            break;
        case "all"://选择专题页面或者分类
            $picker.find(".j-btn-use").remove();
            //打开选择器窗口，并渲染产品第一页数据
            showListRender_mgz(1,function(){
                $.jBox.show({
                    title:"选择专题页面或者分类",
                    content: $picker,
                    btnOK: {show: false},
                    btnCancel: {show: false},
                    onOpen:function(jbox){
                        selectEvent_mgzAndmgzCat(jbox)
                    }
                });
            });
            break;
    }

    //显示不同的模式
    switch(showmode){
        case "goods":
        case "goodsMulti":
            $picker.find(".tabs").remove();
            $picker.find(".gagp-goodslist").unwrap().unwrap();
            $picker.find(".tc[data-index='2']").remove();
            //打开选择器窗口，并渲染产品第一页数据
            showListRender_goods(1,function(){
                $.jBox.show({
                    title: "选择产品",
                    content: $picker,
                    btnOK: {show: false},
                    btnCancel: {show: false},
                    onOpen:function(jbox){
                        if(showmode=="goodsMulti"){
                            selectEvent_goods_multi(jbox);
                        }
                        else{
                            selectEvent_goods(jbox);
                        }
                    }
                });
            });
        break;
        case "group":
            $picker.find(".tabs").remove();
            $picker.find(".gagp-grouplist").unwrap().unwrap();
            $picker.find(".tc[data-index='1']").remove();
            // returnType=2;//产品分组标签类型
            //打开选择器窗口，并渲染产品分组第一页数据
            showListRender_group(1,function(){
                $.jBox.show({
                    title: "选择产品分组",
                    content: $picker,
                    btnOK: {show: false},
                    btnCancel: {show: false},
                    onOpen:function(jbox){
                        selectEvent_group(jbox);
                    }
                });
            });
        break;
        case "all":
            //打开选择器窗口，并渲染产品第一页数据
            showListRender_goods(1,function(){
                $.jBox.show({
                    title:"选择产品或产品分组",
                    content: $picker,
                    btnOK: {show: false},
                    btnCancel: {show: false},
                    onOpen:function(jbox){
                        selectEvent_goodsAndGroup(jbox)
                    }
                });
            });
        break;
    }
}

/*
 * 游戏选择器
 * @Author  chenjie
 * @param showmode 显示模式[all]
 * @param callback 回调函数
 */
HYD.popbox.GamePicker = function(showmode, callback) {
    var html = $("#tpl_popbox_GamePicker").html(),
        $picker = $(html), //选择器 
        datalist={
            "1":[],//幸运大转盘 数据缓存列表
            "2":[],//疯狂砸金蛋 数据缓存列表
            "3":[],//好运翻翻看 数据缓存列表
            "4":[]//骰子大王 数据缓存列表
        };

    /*
     * ajax 根据页数、游戏类型 获取游戏
     * @param gametype 游戏类型[1(幸运大转盘)|2(疯狂砸金蛋)|3(好运翻翻看)|4(骰子大王)]
     * @param page 页数
     */
    var showListRender_Game = function(gametype, page, callback_showListRender) {
        var dorender = function(data) {
            datalist[gametype] = data.list; //图片列表

            if (!datalist[gametype] || !datalist[gametype].length) {
                $picker.find(".game-list-panel"+gametype).empty().append("<p class='txtCenter'>对不起，暂无数据</p>");
            } else {
                var tpl = $("#tpl_popbox_GamePicker_item").html(), //获取item模板
                    html = _.template(tpl, {
                        dataset: datalist[gametype]
                    }), //渲染列表数据
                    $render = $(html);

                //选取事件
                $render.find(".j-select").click(function() {
                    var $btn = $(this),
                        $parent = $btn.parent("li");

                    if ($parent.hasClass("selected")) {
                        $parent.removeClass("selected");
                        $btn.removeClass("btn-success").text("选取");
                    } else {
                        $parent.addClass("selected");
                        $btn.addClass("btn-success").text("已选");
                    }
                });

                $picker.find(".game-list-panel"+gametype).empty().append($render); //插入dom

                //分页符
                var paginate = data.page,
                    $render_paginate = $(paginate);

                $render_paginate.filter("a:not(.disabled,.cur)").click(function() {
                    //获得页数
                    var href = $(this).attr("href"),
                        page = href.split("/");
                    page = page[page.length - 1];
                    page = page.replace(/.html/, "");

                    showListRender_Game(gametype,page);
                    return false;
                });

                $picker.find(".paginate:eq("+(gametype-1)+")").empty().append($render_paginate); //插入dom

            }
            if (callback_showListRender) callback_showListRender(gametype); //执行回调，传入所有数据
        }

        //映射后端游戏的type值用于ajax
        var ajaxGameTypeMap={
            "1":1,
            "2":4,
            "3":3,
            "4":5
        };

        //获取数据并渲染
        $.ajax({
            url: "/Design/getGame",
            type: "post",
            dataType: "json",
            data: {
                "p": parseInt(page),
                "type":parseInt(ajaxGameTypeMap[gametype])
            },
            // beforeSend: function() {
            //     $.jBox.showloading();
            // },
            success: function(data) {
                if (data.status == 1) {
                    dorender(data);
                } else {
                    HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
                }
                // $.jBox.hideloading();
            }
        });
    };


    //单选事件
    var selectEvent = function(jbox ,gametype) {
        $picker.on("click",".game-list-panel"+gametype+" .j-select",function(){
            var index = $(".game-list-panel"+gametype+" li").index($(this).parent("li"));
            if (callback) callback(datalist[gametype][index], 5);
            $.jBox.close(jbox);
        });
    }

    //获取第一个选项卡第一页数据
    showListRender_Game(1,1,function(gametype) {
        $.jBox.show({
            title: "选择营销活动",
            content: $picker,
            btnOK: {
                show: false
            },
            btnCancel: {
                show: false
            },
            onOpen: function(jbox) {
                selectEvent(jbox, gametype);

                $picker.find(".j-tab-game").one("click", function() {
                    var index=$(this).data("index");

                    showListRender_Game(index,1,function(gametype) {
                        selectEvent(jbox, gametype);//绑定选择事件
                    });
                });
            }
        });
    });
}

/*
 * 下拉列表选择器集合
 * @Author  chenjie
 * @param showmode 显示模式[goods|goodsMulti|group|all]
 * @param callback 回调函数
 */
HYD.popbox.dplPickerColletion=function(options){
    var defaults={
        linkType:1,
        callback:null
    };

    var opt=$.extend(true,{},defaults,options);//合并参数

    switch(parseInt(opt.linkType)){
        case 1://选择产品
            HYD.popbox.GoodsAndGroupPicker("goods",opt.callback);
            break;
        case 2://产品分组
            HYD.popbox.GoodsAndGroupPicker("group",opt.callback);
            break;
        case 3://专题页面
            HYD.popbox.MgzAndMgzCate("mgz",opt.callback);
            break;
        case 4://专题分类
            HYD.popbox.MgzAndMgzCate("mgzCate",opt.callback);
            break;
        case 5://营销活动
            HYD.popbox.GamePicker("all",opt.callback);
            break;
        case 6://商城主页
            var tmpdata={
                title:"商城主页",
                link:"/Shop/index"
            };
            opt.callback(tmpdata,6);
            break;
        case 7://会员主页
            var tmpdata={
                title:"会员主页",
                link:"/User/index"
            };
            opt.callback(tmpdata,7);
            break;
        case 8://分销申请
            var tmpdata={
                title:"分销申请",
                link:"/User/dist_apply"
            };
            opt.callback(tmpdata,8);
            break;
        case 9: //购物车
            var tmpdata = {
                title:"购物车",
                link:" /Item/cart"
            };
            opt.callback(tmpdata,9);
            break;
        case 10: //全部产品
            var tmpdata = {
                title:"全部产品",
                link:" /Item/lists"
            }
            opt.callback(tmpdata,10);
            break;
        case 11://自定义链接
            var tmpdata={
                title:"",
                link:""
            };
            opt.callback(tmpdata,11);
            break;
        case 12: // 产品分类
            var tmpdata = {
                title:"产品分类",
                link:"/Item/item_class"
            };
            opt.callback(tmpdata,12);
            break;
    }
}

/*
 * 异步弹窗表格
 * @Author  chenjie
 * @param options.title 弹窗标题
 * @param options.url 数据接口
 * @param options.data 发送的数据
 * @param options.tpl 数据渲染模板
 * @param options.onOpen 打开窗口后的回调函数
 * @param options.onPageChange 当改变页面时候的回调函数
*/
HYD.ajaxPopTable=function(options){
    var defaults={
        title:"",
        url:"",
        width:"auto",
        minHeight:"auto",
        data:{
            p:1
        },
        tpl:"",
        onOpen:null,
        onPageChange:null
    },
    opts=$.extend(true,{},defaults,options),
    $pop=$("<div></div>"),
    ajaxdata,//数据缓存
    cachejbox;


    //渲染数据
    var reRenderData = function(callback) {


        var tpl=opts.tpl,
            url=opts.url;

        var dorender=function(data){

            ajaxdata=data;
            //渲染模板

            var html = _.template(tpl,data),
                $render = $(html);
            $pop.empty().append($render);//插入dom

            //分页点击事件
            $pop.find(".paginate a:not(.disabled,.cur)").click(function(){
                //获得页数
                var href=$(this).attr("href"),
                    page=href.split("/");

                for(var i=0;i<page.length;i++){
                    if(page[i]=="p"){
                        opts.data.p=page[i+1].replace(/.html/,"");
                        reRenderData();
                        break;
                    }
                }

                return false;
            });

            if(callback) callback();//reRenderData的回调函数
            if(opts.onPageChange) opts.onPageChange(cachejbox,ajaxdata);//翻页的回调函数
        }

        //获取数据并渲染
        $.ajax({
            url:url,
            type:"post",
            dataType:"json",
            data:opts.data,
            success:function(data){

                if (data.status == 1) {

                    dorender(data);
                } else {
                    HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
                }
            }
        });
    };
    reRenderData(function(){
        $.jBox.show({
            title: opts.title,
            width:opts.width,
            minHeight:opts.minHeight,
            content: $pop,
            btnOK: {show:false},
            btnCancel:{show:false},
            onOpen:function(jbox){
                cachejbox=jbox;
                if(opts.onOpen) opts.onOpen(jbox,ajaxdata);
            }
        });
    });
}


/**
 * //选择音频事件
 * [AudioPicker description]
 */



/*
 * 正则表达式集合
 * @Author  chenjie
 */
HYD.regRules = {
    email: /^[a-z]([a-z0-9]*[-_]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i,
    mobphone: /^(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/,
    telphone: /^0\d{2,3}(\-)?\d{7,8}$/,
    integer:/^\d+$/,
    positiv:/^\d+(?=\.{0,1}\d+$|$)/
};


/**
 * [iconimgsrc 小图标路径]
 * @type {Object}
 */
HYD.popbox.iconimgsrc = {
    data:{
        style1:{
            color0:[
                '/Public/images/icon/style1/color0/icon_home.png',
                '/Public/images/icon/style1/color0/icon_allgoods.png',
                '/Public/images/icon/style1/color0/icon_newgoods.png',
                '/Public/images/icon/style1/color0/icon_user.png',
                '/Public/images/icon/style1/color0/icon_fx.png',
                '/Public/images/icon/style1/color0/icon_active.png',
                '/Public/images/icon/style1/color0/icon_hotsale.png',
                '/Public/images/icon/style1/color0/icon_subject.png',
                '/Public/images/icon/style1/color0/style1_gz0.png',
                '/Public/images/icon/style1/color0/style1_shopcar0.png'
            ],
            color1:[
                '/Public/images/icon/style1/color1/icon_home.png',
                '/Public/images/icon/style1/color1/icon_allgoods.png',
                '/Public/images/icon/style1/color1/icon_newgoods.png',
                '/Public/images/icon/style1/color1/icon_user.png',
                '/Public/images/icon/style1/color1/icon_fx.png',
                '/Public/images/icon/style1/color1/icon_active.png',
                '/Public/images/icon/style1/color1/icon_hotsale.png',
                '/Public/images/icon/style1/color1/icon_subject.png',
                '/Public/images/icon/style1/color1/style1_gz1.png',
                '/Public/images/icon/style1/color1/style1_shopcar1.png'
            ],
            color2:[
                '/Public/images/icon/style1/color2/icon_home.png',
                '/Public/images/icon/style1/color2/icon_allgoods.png',
                '/Public/images/icon/style1/color2/icon_newgoods.png',
                '/Public/images/icon/style1/color2/icon_user.png',
                '/Public/images/icon/style1/color2/icon_fx.png',
                '/Public/images/icon/style1/color2/icon_active.png',
                '/Public/images/icon/style1/color2/icon_hotsale.png',
                '/Public/images/icon/style1/color2/icon_subject.png',
                '/Public/images/icon/style1/color2/style1_gz2.png',
                '/Public/images/icon/style1/color2/style1_shopcar2.png'
            ],
            color3:[
                '/Public/images/icon/style1/color3/icon_home.png',
                '/Public/images/icon/style1/color3/icon_allgoods.png',
                '/Public/images/icon/style1/color3/icon_newgoods.png',
                '/Public/images/icon/style1/color3/icon_user.png',
                '/Public/images/icon/style1/color3/icon_fx.png',
                '/Public/images/icon/style1/color3/icon_active.png',
                '/Public/images/icon/style1/color3/icon_hotsale.png',
                '/Public/images/icon/style1/color3/icon_subject.png',
                '/Public/images/icon/style1/color3/style1_gz3.png',
                '/Public/images/icon/style1/color3/style1_shopcar3.png'
            ],
            color4:[
                '/Public/images/icon/style1/color4/icon_home.png',
                '/Public/images/icon/style1/color4/icon_allgoods.png',
                '/Public/images/icon/style1/color4/icon_newgoods.png',
                '/Public/images/icon/style1/color4/icon_user.png',
                '/Public/images/icon/style1/color4/icon_fx.png',
                '/Public/images/icon/style1/color4/icon_active.png',
                '/Public/images/icon/style1/color4/icon_hotsale.png',
                '/Public/images/icon/style1/color4/icon_subject.png',
                '/Public/images/icon/style1/color4/style1_gz4.png',
                '/Public/images/icon/style1/color4/style1_shopcar4.png'
            ],
            color5:[
                '/Public/images/icon/style1/color5/icon_home.png',
                '/Public/images/icon/style1/color5/icon_allgoods.png',
                '/Public/images/icon/style1/color5/icon_newgoods.png',
                '/Public/images/icon/style1/color5/icon_user.png',
                '/Public/images/icon/style1/color5/icon_fx.png',
                '/Public/images/icon/style1/color5/icon_active.png',
                '/Public/images/icon/style1/color5/icon_hotsale.png',
                '/Public/images/icon/style1/color5/icon_subject.png',
                '/Public/images/icon/style1/color5/style1_gz5.png',
                '/Public/images/icon/style1/color5/style1_shopcar5.png'
            ],
            color6:[
                '/Public/images/icon/style1/color6/icon_home.png',
                '/Public/images/icon/style1/color6/icon_allgoods.png',
                '/Public/images/icon/style1/color6/icon_newgoods.png',
                '/Public/images/icon/style1/color6/icon_user.png',
                '/Public/images/icon/style1/color6/icon_fx.png',
                '/Public/images/icon/style1/color6/icon_active.png',
                '/Public/images/icon/style1/color6/icon_hotsale.png',
                '/Public/images/icon/style1/color6/icon_subject.png',
                '/Public/images/icon/style1/color6/style1_gz6.png',
                '/Public/images/icon/style1/color6/style1_shopcar6.png'
            ],
            color7:[
                '/Public/images/icon/style1/color7/icon_home.png',
                '/Public/images/icon/style1/color7/icon_allgoods.png',
                '/Public/images/icon/style1/color7/icon_newgoods.png',
                '/Public/images/icon/style1/color7/icon_user.png',
                '/Public/images/icon/style1/color7/icon_fx.png',
                '/Public/images/icon/style1/color7/icon_active.png',
                '/Public/images/icon/style1/color7/icon_hotsale.png',
                '/Public/images/icon/style1/color7/icon_subject.png',
                '/Public/images/icon/style1/color7/style1_gz7.png',
                '/Public/images/icon/style1/color7/style1_shopcar7.png'
            ],
            color8:[
                '/Public/images/icon/style1/color8/icon_home.png',
                '/Public/images/icon/style1/color8/icon_allgoods.png',
                '/Public/images/icon/style1/color8/icon_newgoods.png',
                '/Public/images/icon/style1/color8/icon_user.png',
                '/Public/images/icon/style1/color8/icon_fx.png',
                '/Public/images/icon/style1/color8/icon_active.png',
                '/Public/images/icon/style1/color8/icon_hotsale.png',
                '/Public/images/icon/style1/color8/icon_subject.png',
                '/Public/images/icon/style1/color8/style1_gz8.png',
                '/Public/images/icon/style1/color8/style1_shopcar8.png'
            ],
        },
        style2:{
            color0:[
                '/Public/images/icon/style2/color0/icon_home.png',
                '/Public/images/icon/style2/color0/icon_allgoods.png',
                '/Public/images/icon/style2/color0/icon_newgoods.png',
                '/Public/images/icon/style2/color0/icon_user.png',
                '/Public/images/icon/style2/color0/icon_fx.png',
                '/Public/images/icon/style2/color0/icon_active.png',
                '/Public/images/icon/style2/color0/icon_hotsale.png',
                '/Public/images/icon/style2/color0/icon_subject.png',
                '/Public/images/icon/style2/color0/style2_gz0.png',
                '/Public/images/icon/style2/color0/style2_shopcar0.png'
            ],
            color1:[
                '/Public/images/icon/style2/color1/icon_home.png',
                '/Public/images/icon/style2/color1/icon_allgoods.png',
                '/Public/images/icon/style2/color1/icon_newgoods.png',
                '/Public/images/icon/style2/color1/icon_user.png',
                '/Public/images/icon/style2/color1/icon_fx.png',
                '/Public/images/icon/style2/color1/icon_active.png',
                '/Public/images/icon/style2/color1/icon_hotsale.png',
                '/Public/images/icon/style2/color1/icon_subject.png',
                '/Public/images/icon/style2/color1/style2_gz1.png',
                '/Public/images/icon/style2/color1/style2_shopcar1.png'
            ],
            color2:[
                '/Public/images/icon/style2/color2/icon_home.png',
                '/Public/images/icon/style2/color2/icon_allgoods.png',
                '/Public/images/icon/style2/color2/icon_newgoods.png',
                '/Public/images/icon/style2/color2/icon_user.png',
                '/Public/images/icon/style2/color2/icon_fx.png',
                '/Public/images/icon/style2/color2/icon_active.png',
                '/Public/images/icon/style2/color2/icon_hotsale.png',
                '/Public/images/icon/style2/color2/icon_subject.png',
                '/Public/images/icon/style2/color2/style2_gz2.png',
                '/Public/images/icon/style2/color2/style2_shopcar2.png'
            ],
            color3:[
                '/Public/images/icon/style2/color3/icon_home.png',
                '/Public/images/icon/style2/color3/icon_allgoods.png',
                '/Public/images/icon/style2/color3/icon_newgoods.png',
                '/Public/images/icon/style2/color3/icon_user.png',
                '/Public/images/icon/style2/color3/icon_fx.png',
                '/Public/images/icon/style2/color3/icon_active.png',
                '/Public/images/icon/style2/color3/icon_hotsale.png',
                '/Public/images/icon/style2/color3/icon_subject.png',
                '/Public/images/icon/style2/color3/style2_gz3.png',
                '/Public/images/icon/style2/color3/style2_shopcar3.png'
            ],
            color4:[
                '/Public/images/icon/style2/color4/icon_home.png',
                '/Public/images/icon/style2/color4/icon_allgoods.png',
                '/Public/images/icon/style2/color4/icon_newgoods.png',
                '/Public/images/icon/style2/color4/icon_user.png',
                '/Public/images/icon/style2/color4/icon_fx.png',
                '/Public/images/icon/style2/color4/icon_active.png',
                '/Public/images/icon/style2/color4/icon_hotsale.png',
                '/Public/images/icon/style2/color4/icon_subject.png',
                '/Public/images/icon/style2/color4/style2_gz4.png',
                '/Public/images/icon/style2/color4/style2_shopcar4.png'
            ],
            color5:[
                '/Public/images/icon/style2/color5/icon_home.png',
                '/Public/images/icon/style2/color5/icon_allgoods.png',
                '/Public/images/icon/style2/color5/icon_newgoods.png',
                '/Public/images/icon/style2/color5/icon_user.png',
                '/Public/images/icon/style2/color5/icon_fx.png',
                '/Public/images/icon/style2/color5/icon_active.png',
                '/Public/images/icon/style2/color5/icon_hotsale.png',
                '/Public/images/icon/style2/color5/icon_subject.png',
                '/Public/images/icon/style2/color5/style2_gz5.png',
                '/Public/images/icon/style2/color5/style2_shopcar5.png'
            ],
            color6:[
                '/Public/images/icon/style2/color6/icon_home.png',
                '/Public/images/icon/style2/color6/icon_allgoods.png',
                '/Public/images/icon/style2/color6/icon_newgoods.png',
                '/Public/images/icon/style2/color6/icon_user.png',
                '/Public/images/icon/style2/color6/icon_fx.png',
                '/Public/images/icon/style2/color6/icon_active.png',
                '/Public/images/icon/style2/color6/icon_hotsale.png',
                '/Public/images/icon/style2/color6/icon_subject.png',
                '/Public/images/icon/style2/color6/style2_gz6.png',
                '/Public/images/icon/style2/color6/style2_shopcar6.png'
            ],
            color7:[
                '/Public/images/icon/style2/color7/icon_home.png',
                '/Public/images/icon/style2/color7/icon_allgoods.png',
                '/Public/images/icon/style2/color7/icon_newgoods.png',
                '/Public/images/icon/style2/color7/icon_user.png',
                '/Public/images/icon/style2/color7/icon_fx.png',
                '/Public/images/icon/style2/color7/icon_active.png',
                '/Public/images/icon/style2/color7/icon_hotsale.png',
                '/Public/images/icon/style2/color7/icon_subject.png',
                '/Public/images/icon/style2/color7/style2_gz7.png',
                '/Public/images/icon/style2/color7/style2_shopcar7.png'
            ],
            color8:[
                '/Public/images/icon/style2/color8/icon_home.png',
                '/Public/images/icon/style2/color8/icon_allgoods.png',
                '/Public/images/icon/style2/color8/icon_newgoods.png',
                '/Public/images/icon/style2/color8/icon_user.png',
                '/Public/images/icon/style2/color8/icon_fx.png',
                '/Public/images/icon/style2/color8/icon_active.png',
                '/Public/images/icon/style2/color8/icon_hotsale.png',
                '/Public/images/icon/style2/color8/icon_subject.png',
                '/Public/images/icon/style2/color8/style2_gz8.png',
                '/Public/images/icon/style2/color8/style2_shopcar8.png'
            ],
        },
        style3:{
            color0:[
                '/Public/images/icon/style3/color0/icon_home.png',
                '/Public/images/icon/style3/color0/icon_allgoods.png',
                '/Public/images/icon/style3/color0/icon_newgoods.png',
                '/Public/images/icon/style3/color0/icon_user.png',
                '/Public/images/icon/style3/color0/icon_fx.png',
                '/Public/images/icon/style3/color0/icon_active.png',
                '/Public/images/icon/style3/color0/icon_hotsale.png',
                '/Public/images/icon/style3/color0/icon_subject.png',
                '/Public/images/icon/style3/color0/style3_gz0.png',
                '/Public/images/icon/style3/color0/style3_shopcar0.png'
            ],
            color1:[
                '/Public/images/icon/style3/color1/icon_home.png',
                '/Public/images/icon/style3/color1/icon_allgoods.png',
                '/Public/images/icon/style3/color1/icon_newgoods.png',
                '/Public/images/icon/style3/color1/icon_user.png',
                '/Public/images/icon/style3/color1/icon_fx.png',
                '/Public/images/icon/style3/color1/icon_active.png',
                '/Public/images/icon/style3/color1/icon_hotsale.png',
                '/Public/images/icon/style3/color1/icon_subject.png',
                '/Public/images/icon/style3/color1/style3_gz1.png',
                '/Public/images/icon/style3/color1/style3_shopcar1.png'
            ],
            color2:[
                '/Public/images/icon/style3/color2/icon_home.png',
                '/Public/images/icon/style3/color2/icon_allgoods.png',
                '/Public/images/icon/style3/color2/icon_newgoods.png',
                '/Public/images/icon/style3/color2/icon_user.png',
                '/Public/images/icon/style3/color2/icon_fx.png',
                '/Public/images/icon/style3/color2/icon_active.png',
                '/Public/images/icon/style3/color2/icon_hotsale.png',
                '/Public/images/icon/style3/color2/icon_subject.png',
                '/Public/images/icon/style3/color2/style3_gz2.png',
                '/Public/images/icon/style3/color2/style3_shopcar2.png'
            ],
            color3:[
                '/Public/images/icon/style3/color3/icon_home.png',
                '/Public/images/icon/style3/color3/icon_allgoods.png',
                '/Public/images/icon/style3/color3/icon_newgoods.png',
                '/Public/images/icon/style3/color3/icon_user.png',
                '/Public/images/icon/style3/color3/icon_fx.png',
                '/Public/images/icon/style3/color3/icon_active.png',
                '/Public/images/icon/style3/color3/icon_hotsale.png',
                '/Public/images/icon/style3/color3/icon_subject.png',
                '/Public/images/icon/style3/color3/style3_gz3.png',
                '/Public/images/icon/style3/color3/style3_shopcar3.png'
            ],
            color4:[
                '/Public/images/icon/style3/color4/icon_home.png',
                '/Public/images/icon/style3/color4/icon_allgoods.png',
                '/Public/images/icon/style3/color4/icon_newgoods.png',
                '/Public/images/icon/style3/color4/icon_user.png',
                '/Public/images/icon/style3/color4/icon_fx.png',
                '/Public/images/icon/style3/color4/icon_active.png',
                '/Public/images/icon/style3/color4/icon_hotsale.png',
                '/Public/images/icon/style3/color4/icon_subject.png',
                '/Public/images/icon/style3/color4/style3_gz4.png',
                '/Public/images/icon/style3/color4/style3_shopcar4.png'
            ],
            color5:[
                '/Public/images/icon/style3/color5/icon_home.png',
                '/Public/images/icon/style3/color5/icon_allgoods.png',
                '/Public/images/icon/style3/color5/icon_newgoods.png',
                '/Public/images/icon/style3/color5/icon_user.png',
                '/Public/images/icon/style3/color5/icon_fx.png',
                '/Public/images/icon/style3/color5/icon_active.png',
                '/Public/images/icon/style3/color5/icon_hotsale.png',
                '/Public/images/icon/style3/color5/icon_subject.png',
                '/Public/images/icon/style3/color5/style3_gz5.png',
                '/Public/images/icon/style3/color5/style3_shopcar5.png'
            ],
            color6:[
                '/Public/images/icon/style3/color6/icon_home.png',
                '/Public/images/icon/style3/color6/icon_allgoods.png',
                '/Public/images/icon/style3/color6/icon_newgoods.png',
                '/Public/images/icon/style3/color6/icon_user.png',
                '/Public/images/icon/style3/color6/icon_fx.png',
                '/Public/images/icon/style3/color6/icon_active.png',
                '/Public/images/icon/style3/color6/icon_hotsale.png',
                '/Public/images/icon/style3/color6/icon_subject.png',
                '/Public/images/icon/style3/color6/style3_gz6.png',
                '/Public/images/icon/style3/color6/style3_shopcar6.png'
            ],
            color7:[
                '/Public/images/icon/style3/color7/icon_home.png',
                '/Public/images/icon/style3/color7/icon_allgoods.png',
                '/Public/images/icon/style3/color7/icon_newgoods.png',
                '/Public/images/icon/style3/color7/icon_user.png',
                '/Public/images/icon/style3/color7/icon_fx.png',
                '/Public/images/icon/style3/color7/icon_active.png',
                '/Public/images/icon/style3/color7/icon_hotsale.png',
                '/Public/images/icon/style3/color7/icon_subject.png',
                '/Public/images/icon/style3/color7/style3_gz7.png',
                '/Public/images/icon/style3/color7/style3_shopcar7.png'
            ],
            color8:[
                '/Public/images/icon/style3/color8/icon_home.png',
                '/Public/images/icon/style3/color8/icon_allgoods.png',
                '/Public/images/icon/style3/color8/icon_newgoods.png',
                '/Public/images/icon/style3/color8/icon_user.png',
                '/Public/images/icon/style3/color8/icon_fx.png',
                '/Public/images/icon/style3/color8/icon_active.png',
                '/Public/images/icon/style3/color8/icon_hotsale.png',
                '/Public/images/icon/style3/color8/icon_subject.png',
                '/Public/images/icon/style3/color8/style3_gz8.png',
                '/Public/images/icon/style3/color8/style3_shopcar8.png'
            ],
        }
    }
}



$(function() {
    //顶部菜单下拉
    $(".header-ctrl-item").hover(function() {
        var $self = $(this),
            type = $self.data("type"), //下拉类型，用于区别task
            cache = $self.data("cache"); //task是否已经缓存数据

        if ($self.find(".header-ctrl-item-children").length) $self.addClass("show"); //显示下拉菜单
    }, function() {
        $(this).removeClass("show");
    });

    $(".tips").tooltips(); //初始化所有带tips的元素为tooltips

    try{
        var $container = $(".container .inner"); //获取中间内容dom

        //获取常量
        var getConstant = function() {
            HYD.Constant.windowHeight = $(this).height();
            HYD.Constant.windowWidth = $(this).width();
            HYD.Constant.containerOffset = $container.offset(); //中间部分的offset位置
            HYD.Constant.containerWidth = $container.outerWidth(); //中间部分的宽度
        };

        //设置回到顶部位置
        var setGotopPos = function() {
            $("#j-gotop").css("left", HYD.Constant.containerWidth + HYD.Constant.containerOffset.left + 10);
        };

        //重置窗口位置重
        $(window).resize(function() {
            getConstant();
            setGotopPos();
        });

        getConstant();
        setGotopPos();
    }
    catch(e){}

    //当页面滚动高度大于150px时，显示gotop
    $(window).scroll(function() {
        if ($(this).scrollTop() >= 150) {
            $("#j-gotop").fadeIn(300);
        } else {
            $("#j-gotop").fadeOut(300);
        }
    })
});