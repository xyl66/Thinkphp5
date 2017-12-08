/*
 * 会员道脚本命名空间
 * @Author  chenjie
 */
var HYD = HYD ? HYD : {}; //会员道命名空间
HYD.Task = HYD.Task ? HYD.Task : {}; //任务队列命名空间
HYD.Task.Queues = HYD.Task.Queues ? HYD.Task.Queues : []; //任务队列列表
HYD.Con=HYD.Con ? HYD.Con : {};//常量
HYD.popbox=HYD.popbox ? HYD.popbox : {};//弹窗选择器

/*
 * 添加任务
 * @Author  chenjie
 * @param data.id      任务队列id
 * @param data.status  任务状态[progress|sucess|danger]
 * @param data.title   任务显示的标题
 * @param data.percent 任务当前进度
 */
HYD.Task.add = function(data) {
    if (data == undefined || data.id == undefined || data.id == "") return;
    //默认值
    var defaultData = {
            "id": "",
            "status": "progress",
            "title": "正在同步订单数据",
            "percent": "0"
        },
        tmpData = $.extend({}, defaultData, data), //合并后的值
        tpl = $("#tpl_taskqueue_" + tmpData.status).html(); //获取模板

    if (parseInt(tmpData.percent) >= 100) {
        tmpData.percent = "100";
    }

    var render = _.template(tpl, tmpData); //渲染模板并返回结果

    $newTask = $(render);

    //生成当前任务信息
    var queue = {
        id: tmpData.id,
        status: tmpData.status,
        title: tmpData.title,
        dom: $newTask,
        timer: null
    }

    // 缓存这个任务
    HYD.Task.Queues.push(queue);
    //绑定这个任务的操作按钮
    HYD.Task.bindCtrlBtn(queue);
    //插入视图
    $newTask.insertBefore(".header-tasks li:last");
    //检查任务列表是否为空
    HYD.Task.checkIsEmpty();

    if (tmpData.status == "success" || tmpData.status == "danger") return;

    //2s心跳更新任务状态
    queue.timer = setInterval(function() {
        $.ajax({
            url: "/OrderPoint/ajax_task",
            type: "post",
            dataType: "json",
            data: {
                task_id: tmpData.id
            },
            success: function(data) {
                if (data.status == 1) {
                    var tmp = data.task;
                    //如果任务成功或失败结束定时循环
                    if (tmp.status == "success" || tmp.status == "danger") {
                        clearInterval(queue.timer);
                        queue.timer = null;
                        return;
                    }
                    HYD.Task.update({
                        id: tmpData.id,
                        status: tmp.status,
                        percent: tmp.percent
                    });
                } else {
                    console.log(data.msg);
                }
            }
        });
    }, 4000);
}

/*
 * 更新任务
 * @Author  chenjie
 * @param id  任务标识
 * @param status  任务状态[progress|sucess|danger]
 * @param title   任务显示的标题
 * @param percent 任务当前进度
 */
HYD.Task.update = function(data) {
    if (!data || !data.id || !data.status) return;
    //从缓存中获取这个任务信息
    var queue = HYD.Task.get(data.id).queue;

    if (queue == undefined) return;

    if (parseInt(data.percent) >= 100) {
        data.percent = "100";
    }

    //只有处于progress状态的任务才能进行进度条更新
    if ((queue.status == data.status) && data.status == "progress") {
        queue.dom
            .find(".header-tasks-queue-title").text(data.title).end()
            .find(".progress-bar").css("width", data.percent + "%").end()
            .find(".header-tasks-queue-percent").text(data.percent + "%");
    } else {
        clearInterval(queue.timer); //清除定时器
        queue.timer = null;

        var tpl = $("#tpl_taskqueue_" + data.status).html(), //获取模板
            render = _.template(tpl, {
                id: queue.id,
                title: data.title != undefined ? data.title : queue.title,
                percent: data.percent
            }), //渲染模板并返回结果
            $newTask = $(render);

        $newTask.insertBefore(queue.dom); //将新状态dom插入视图
        queue.dom.remove(); //从视图中移除旧的dom
        queue.dom = $newTask; //改变当前任务的dom
        queue.status = data.status; //改变当前任务的状态
        queue.title = data.title; //改变当前任务的标题
        HYD.Task.bindCtrlBtn(queue); //绑定对应的按钮事件
    }
}

/*
 * 从队列中获取一个任务
 * @Author  chenjie
 * @param id  任务标识id
 */
HYD.Task.get = function(id) {
    var queues = HYD.Task.Queues,
        queues_len = queues.length;

    for (var i = 0; i < queues_len; i++) {
        if (queues[i].id == id) {
            return {
                queue: queues[i],
                i: i
            };
        }
    }
}

/*
 * 从队列中删除一个任务
 * @Author  chenjie
 * @param id  任务标识id
 */
HYD.Task.del = function(id) {
    var tmp = HYD.Task.get(id); //获取任务

    //删除dom
    tmp.queue.dom.fadeOut(300, function() {
        $(this).remove()
    });
    //从队列缓存中删除
    HYD.Task.Queues.splice(tmp.i, 1);
    //检测队列是否为空
    HYD.Task.checkIsEmpty();
}

/*
 * 绑定一个任务中的‘清除任务’、‘重新执行’事件
 * @Author  chenjie
 * @param queue  要绑定的任务
 */
HYD.Task.bindCtrlBtn = function(queue) {
    queue.dom.find(".header-tasks-queue-percent > a").click(function() {
        var $btn = $(this),
            type = $btn.data("ctrl"); //按钮类型

        switch (type) {
            //清楚已完成
            case "remove":
                //从后台删除这个队列
                $.ajax({
                    url: "/OrderPoint/ajax_del",
                    type: "post",
                    dataType: "json",
                    data: {
                        "task_id": queue.id
                    },
                    success: function(data) {
                        if (data.status == 1) {
                            //从UI删除队列
                            HYD.Task.del(queue.id);
                        } else {
                            console.log(data.msg);
                        }
                    }
                });
                break;
                //重新执行
            case "repeat":
                $.ajax({
                    url: "/OrderPoint/ajax_revert",
                    type: "post",
                    dataType: "json",
                    data: {
                        "task_id": queue.id
                    },
                    success: function(data) {
                        if (data.status == 1) {
                            HYD.Task.del(queue.id);
                            HYD.Task.add({
                                id: queue.id,
                                status: "progress",
                                title: queue.title
                            });
                        } else {
                            console.log(data.msg)
                        }
                    }
                });

                break;
        }
    });
}

/*
 * 检测任务列表是否为空,为空时显示‘暂无任务’,否则隐藏
 * @Author  chenjie
 */
HYD.Task.checkIsEmpty = function() {
    if (!HYD.Task.Queues) return;
    if (HYD.Task.Queues.length) {
        $("#j-TasksEmpty").hide();
        $("#j-TasksClear").show();
    } else {
        $("#j-TasksEmpty").show();
        $("#j-TasksClear").hide();
    }
}

/*
 * 显示任务列表容器
 * @Author  chenjie
 */
HYD.Task.show = function() {
    $(".header-tasks").parent(".header-ctrl-item").addClass("show");
}

/*
 * 隐藏任务列表容器
 * @Author  chenjie
 */
HYD.Task.hide = function() {
    $(".header-tasks").parent(".header-ctrl-item").removeClass("show");
}

/*
 * 初始化通知栏
 * @Author  chenjie
 */
HYD.initNotice = function() {
    var $notice = $(".header-notice"), //通知栏容器
        $lists = $notice.find(".hn-list"), //通知列表
        singleHeight = $lists.find("li").height(), //单个通知的高度
        speed = 300, //滚动速度
        timing = 2500, //时间间隔
        timer = null; //计时器

    //向上滚动一条通知
    var scrollUp = function() {
        $lists.stop(true, true).animate({
                "top": "-=" + singleHeight
            },
            speed,
            function() {
                $lists.append($lists.find("li:first").clone()).find("li:first").remove();
                $lists.css({
                    "top": 0
                });
            });
    }

    //向下滚动一条通知
    var scrollDown = function() {
        $lists.css({
            "top": -singleHeight
        });
        $lists.find("li:last").clone().insertBefore($lists.find("li:first"));
        $lists.find("li:last").remove();
        $lists.stop(true, true).animate({
                "top": "+=" + singleHeight
            },
            speed,
            function() {
                $lists.css({
                    "top": 0
                });
            });
    }

    //鼠标移入通知栏停止自动滚动
    $notice.mouseenter(function(event) {
        clearInterval(timer);
    });

    //鼠标离开开启自动滚动
    $notice.mouseleave(function(event) {
        timer = setInterval(scrollUp, timing);
    }).trigger('mouseleave');

    //上一条
    $notice.find(".j-notice-next").click(scrollUp);

    //下一条
    $notice.find(".j-notice-prev").click(scrollDown);
}

/*
 * 前端接口hint
 * @Author  chenjie
 * @param type    弹出的类型
 * @param content 弹出的内容
 */
HYD.hint = function(type, content) {
    if (!type || !content) return;

    var tpl = $("#tpl_hint").html(), //获取模板
        //渲染模板
        render = _.template(tpl, {
            type: type,
            content: content
        }),
        $hint = $(render),
        speed = 200, //显示速度
        timing = 1500; //显示时间

    $("body").append($hint.css("opacity", "0"));

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
 * 正则表达式集合
 * @Author  chenjie
 */
HYD.regRules = {
    email: /^[a-z]([a-z0-9]*[-_]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i,
    mobphone: /^(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/,
    telphone: /^0\d{2,3}(\-)?\d{7,8}$/
};

/*
 * 初始化警告框式的提示
 * @Author  chenjie
 */
HYD.initAlertTips = function() {
    $(".alert").each(function() {
        var origin = $(this).data("origin");

        if ($.cookie("notips_" + origin) == 1) {
            return;
        } else {
            $(this).fadeIn(300);
        }
    })

    //点击标题右边的图标显示alerts
    $(".j-showAlertTips").click(function() {
        var origin = $(this).data("origin");
        $(".alert[data-origin='" + origin + "']").fadeIn(300);
    });

    //不再提示
    $(".alert-notips").click(function() {
        var indenti = $(this).data("indenti"); //标识符
        $.cookie("notips_" + indenti, 1,{expires:30,path:'/'});
        $(this).parent(".alert").fadeOut(300);
    });
};

/*
 * 兑换数组数据位置
 * @Author  chenjie
 * @param start 要对换的数据
 * @param end 要对换数据的最终位置
 * @param data 要对换的数据数组
*/
HYD.changeDataPos=function(start,end,data){
    var tmp=data[start];

    if(start<=end){
        data.splice(end+1,0,tmp);
        data.splice(start,1);
    }
    else{
        data.splice(end,0,tmp);
        data.splice(start+1,1);
    }
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
            url:"/files/imgpicker.json",
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

$(function() {
    //左侧菜单切换
    $(".cl-menu .hasChild > a").click(function() {
        var $self = $(this),
            $children = $self.siblings(".cl-menu-children");

        $self.toggleClass("hide-children");
        $children.slideToggle(150);
        return false;
    });

    //隐藏单个菜单的上下分割线
    $(".cl-menu-selected").prev(".cl-menu-seperator").hide().end().next(".cl-menu-seperator").hide();

    //顶部菜单下拉
    $(".header-ctrl-item").hover(function() {
        var $self = $(this),
            type = $self.data("type"), //下拉类型，用于区别task
            cache = $self.data("cache"); //task是否已经缓存数据

        if ($self.find(".header-ctrl-item-children").length) {
            $self.addClass("show"); //显示下拉菜单
            //当下拉菜单是任务队列时才执行
            if (type && type == "task" && cache == "0") {
                $self.data("cache", "1"); //缓存数据后修改状态

                //获取所有任务队列
                $.ajax({
                    url: "/OrderPoint/ajax_taskAll",
                    type: "post",
                    dataType: "json",
                    beforeSend: function() {
                        $("#j-TasksEmpty").addClass("tasks-ajaxloading").text("正在加载...");
                    },
                    success: function(data) {
                        // console.log(data);
                        if (data.status == 1) {
                            var queues = data.task,
                                queues_len = queues.length;

                            for (var i = 0; i < queues_len; i++) {
                                var tmp = queues[i];
                                //如果UI中存在这个队列，则不进行添加
                                if (!$(".header-tasks-queue[data-id=" + tmp.task_id + "]").length) {
                                    HYD.Task.add({
                                        id: tmp.task_id,
                                        status: tmp.status,
                                        title: tmp.title,
                                        percent: tmp.percent
                                    });
                                }
                            }
                        }
                        $("#j-TasksEmpty").removeClass("tasks-ajaxloading").text("暂无数据");
                    }
                });
            }
        }
    }, function() {
        $(this).removeClass("show");
    });

    //清除所有已完成的任务
    $(document).on("click", "#j-TasksClear > a", function() {
        $(".header-tasks-queue[data-status='success'] .header-tasks-queue-percent > a").trigger("click");
    });

    HYD.initNotice(); //初始化通知栏
    HYD.initAlertTips(); //初始化警告框式的提示
    $(".tips").tooltips(); //初始化所有带tips的元素为tooltips

    var $container = $(".container .inner");//获取中间内容dom

    //获取常量
    var getConstant=function(){
        HYD.Con.windowHeight=$(this).height();
        HYD.Con.windowWidth=$(this).width();
        HYD.Con.containerOffset=$container.offset();//中间部分的offset位置
        HYD.Con.containerWidth=$container.outerWidth();//中间部分的宽度
    };

    //设置回到顶部位置
    var setGotopPos=function(){
        $("#j-gotop").css("left",HYD.Con.containerWidth+HYD.Con.containerOffset.left+10);
    };

    //重置窗口位置重
    $(window).resize(function(){
        getConstant();
        setGotopPos();
    });

    getConstant();
    setGotopPos();

    //当页面滚动高度大于150px时，显示gotop
    $(window).scroll(function(){
        if($(this).scrollTop()>=150){
            $("#j-gotop").fadeIn(300);
        }
        else{
            $("#j-gotop").fadeOut(300);
        }
    })
});

/*
 * 获取某个商城要导入的新订单数量
 * @Author  zhangcheng
 * @url  /OrderPoint/ajax_count
 * @param shop_id 商城ID
 * @return data.total 订单数量
 */

/*
 * 导入某个商城的新订单
 * @Author  zhangcheng
 * @url  /OrderPoint/ajax_import
 * @param shop_id 商城ID
 * @return data.id 任务队列ID
 */

/*
 * 查询某个任务队列的信息
 * @Author  zhangcheng
 * @url  /OrderPoint/ajax_task
 * @param task_id 任务队列ID
 * @return data 队列相关信息
 */

/*
 * 查询所有任务队列的信息
 * @Author  zhangcheng
 * @url  /OrderPoint/ajax_taskAll
 * @return data 所有任务队列的信息
 */

/*
 * 删除指定的任务队列
 * @Author  zhangcheng
 * @url  /OrderPoint/ajax_del
 * @param task_id 任务队列ID
 * @return data.status [1|0]
 */
/*
 * 解绑商城
 * @Author  zhangcheng
 * @url  /OrderPoint/ajax_relieve
 * @param shop_id
 * @return data.status
 */