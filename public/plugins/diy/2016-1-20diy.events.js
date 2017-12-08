$(function(){
    //模块类型1 富文本
    HYD.DIY.Unit.event_type1=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom;//控制内容

        //如果之前存在编辑器则销毁
        if (data.ue) {
            data.ue.destroy();
        }

        data.ue = UE.getEditor('editor' + data.id); //创建编辑器

        data.ue.ready(function() {
            data.ue.setContent(HYD.DIY.Unit.html_decode(data.content.fulltext)); //设置编辑器的默认值
            data.ue.focus(true); //编辑器获得焦点
            //当值改变时反应到手机视图中
            var reSetVal = function() {
                var val = data.ue.getContent();
                if(val=="") val="<p>『富文本编辑器』</p>";
                $conitem.find(".fulltext").html(val); //更新到手机视图
                data.content.fulltext = HYD.DIY.Unit.html_encode(val); //更新到缓存
            }
            data.ue.addListener("selectionchange", reSetVal);
            data.ue.addListener("contentChange", reSetVal);
        });
    };

    //模块类型2 标题
    HYD.DIY.Unit.event_type2=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom; //控制内容

        data.dom_ctrl=ctrldom;

        //主标题
        $ctrl.find("input[name='title']").change(function() {
            var val = $(this).val();
            $conitem.find(".j-title").text($(this).val());
            data.content.title = val;
        });
        //副标题
        $ctrl.find("input[name='subtitle']").change(function() {
            var val = $(this).val();
            $conitem.find(".j-subtitle").text($(this).val());
            data.content.subtitle = val;
        });
        //显示方式
        $ctrl.find("input[name='direction']").change(function() {
            var val = $(this).val();
            $conitem.find(".members_special").removeClass("left center right").addClass(val);
            data.content.direction = val;
        });
    };

    //模块类型3 自定义模块
    HYD.DIY.Unit.event_type3=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom; //控制内容

        data.dom_ctrl=ctrldom;

        //修改事件
        var modify=function(){
            HYD.popbox.ModulePicker(function(module){
                $conitem.find(".type3_custModule").text(module.title);//修改手机内容
                $ctrl.find(".type3_custModule_ctrl").text(module.title);//修改控制内容
                data.content=module;
            });
        };

        //添加事件
        var add=function(){
            HYD.popbox.ModulePicker(function(module){
                $conitem.find(".type3_custModule").text(module.title);//修改手机内容
                //去掉添加按钮，替换为修改按钮
                var html=_.template($("#tpl_diy_ctrl_type3_modify").html(),{content:module}),
                    $render=$(html);

                $render.filter(".j-btn-modify").click(modify);//绑定修改按钮的事件
                $ctrl.find(".form-controls").empty().append($render);//替换dom内容
                data.content=module;
            });
        };
        
        $ctrl.find(".j-btn-add").click(add);//初始化模块时的修改按钮时间
        $ctrl.find(".j-btn-modify").click(modify);//初始化模块时的修改按钮时间
    };

    //模块类型4 产品
    HYD.DIY.Unit.event_type4=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom, //控制内容
            tpl_con=$("#tpl_diy_con_type4").html(),//手机内容模板
            tpl_ctrl=$("#tpl_diy_ctrl_type4").html();//控制内容模板

        data.dom_ctrl=ctrldom;

        //重新渲染数据
        var reRender=function(callback){
            var $render=$(_.template(tpl_con,data));
            $conitem.find(".members_con").remove().end().append($render);

            var $render_ctrl=$(_.template(tpl_ctrl,data));
            $ctrl.empty().append($render_ctrl);

            HYD.DIY.Unit.event_type4($ctrl,data);
            if(callback) callback();
        }
        var setImgHeightcallback = function(){
            $('.mingoods,.biggoods').each(function(index, el) {
                var me = $(this),
                    imgHeight = me.find('img').width();
                me.find('img').height(imgHeight);
            });
        };
        //改变布局
        $ctrl.find("input[name='layout']").change(function(){
            var val=$(this).val();
            data.content.layout=val;//同步数据到缓存
            reRender(setImgHeightcallback);
        });

        //是否显示产品名称
        $ctrl.find("input[name='showName']").change(function(){
            var val=$(this).val();
            data.content.showName=val;//同步数据到缓存
            reRender();
        });

        //是否显示购物车图标
        $ctrl.find("input[name='showIco']").change(function(){
            var val=$(this).is(":checked");
            data.content.showIco=val;//同步数据到缓存
            reRender();
        });

        //是否显示产品价格
        $ctrl.find("input[name='showPrice']").change(function(){
            var val=$(this).is(":checked");
            data.content.showPrice=val;//同步数据到缓存
            reRender();
        });

        //删除产品
        $ctrl.find(".j-delgoods").click(function(){
            var index=$(this).parents("li").index();
            data.content.goodslist.splice(index,1);
            reRender(setImgHeightcallback);
            return false;
        });

        //添加产品
        $ctrl.find(".j-addgoods").click(function(){
            HYD.popbox.GoodsAndGroupPicker("goodsMulti",function(list){
                _.each(list,function(goods){
                    data.content.goodslist.push(goods);
                });
                reRender(setImgHeightcallback);
            });
            return false;
        });
    };

    //模块类型5 产品列表（分组标签）
    HYD.DIY.Unit.event_type5=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom, //控制内容
            tpl_con=$("#tpl_diy_con_type5").html(),//手机内容模板
            tpl_ctrl=$("#tpl_diy_ctrl_type5").html();//控制内容模板

        data.dom_ctrl=ctrldom;

        //重新渲染数据
        var reRender=function(){
            var $render=$(_.template(tpl_con,data));
            $conitem.find(".members_con").remove().end().append($render);

            var $render_ctrl=$(_.template(tpl_ctrl,data));
            $ctrl.empty().append($render_ctrl);

            HYD.DIY.Unit.event_type5($ctrl,data);
        }

        //改变布局
        $ctrl.find("input[name='layout']").change(function(){
            var val=$(this).val();
            data.content.layout=val;//同步数据到缓存
            reRender();
        });

        //是否显示产品名称
        $ctrl.find("input[name='showName']").change(function(){
            var val=$(this).is(":checked");
            data.content.showName=val;//同步数据到缓存
            reRender();
        });

        //是否显示购物车图标
        $ctrl.find("input[name='showIco']").change(function(){
            var val=$(this).is(":checked");
            data.content.showIco=val;//同步数据到缓存
            reRender();
        });

        //是否显示产品价格
        $ctrl.find("input[name='showPrice']").change(function(){
            var val=$(this).is(":checked");
            data.content.showPrice=val;//同步数据到缓存
            reRender();
        });

        //选择/修改分组
        $ctrl.find(".j-btn-add,.j-btn-modify").click(function(){
            HYD.popbox.GoodsAndGroupPicker("group",function(group){
                data.content.group=group;
                reRender();
            });
        });
        // 选择产品显示数量
        $ctrl.find('input[name="goodsize"]').change(function(event) {
            var me = $(this),
                num = me.val();
            data.content.goodsize = num;
            reRender();
        });
    };

    //模块类型7 文本导航
    HYD.DIY.Unit.event_type7=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom, //控制内容
            tpl_con=$("#tpl_diy_con_type7").html(),//手机内容模板
            tpl_ctrl=$("#tpl_diy_ctrl_type7").html();//控制内容模板

        data.dom_ctrl=ctrldom;

        //重新渲染数据
        var reRender=function(){
            var $render=$(_.template(tpl_con,data));
            $conitem.find(".members_con").remove().end().append($render);

            var $render_ctrl=$(_.template(tpl_ctrl,data));
            $ctrl.empty().append($render_ctrl);

            HYD.DIY.Unit.event_type7($ctrl,data);
        }

        //改变标题
        $ctrl.find("input[name='title']").change(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset[index].showtitle=$(this).val();
            reRender();
        });

        //改变链接
        $ctrl.find(".droplist li").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            HYD.popbox.dplPickerColletion({
                linkType:$(this).data("val"),
                callback:function(item,type){
                    data.content.dataset[index].title=item.title;
                    data.content.dataset[index].showtitle=item.title;
                    data.content.dataset[index].link=item.link;
                    data.content.dataset[index].linkType=type;
                    reRender();
                }
            });
        });

        //自定义链接
        $ctrl.find("input[name='customlink']").change(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset[index].link=$(this).val();
        });

        //上移
        $ctrl.find(".j-moveup").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            if(index==0) return;//第一个导航不可再向上移动

            //替换缓存数组中的位置
            var tmpdata=data.content.dataset.slice(index,index+1)[0];
            data.content.dataset.splice(index,1);
            data.content.dataset.splice(index-1,0,tmpdata);

            reRender();//更新视图
        });

        //下移
        $ctrl.find(".j-movedown").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index(),
                len=data.content.dataset.length;

            if(index==len-1) return;//最后一个导航不可再向下移动

            //替换缓存数组中的位置
            var tmpdata=data.content.dataset.slice(index,index+1)[0];
            data.content.dataset.splice(index,1);
            data.content.dataset.splice(index+1,0,tmpdata);

            reRender();//更新视图
        });

        //添加
        $ctrl.find(".ctrl-item-list-add").click(function(){
            var newdata={
                    linkType:0,
                    link:"",
                    title:"",
                    showtitle:""
                };
            data.content.dataset.push(newdata);
            reRender();
        });

        //删除
        $ctrl.find(".j-del").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset.splice(index,1);
            reRender();
        });
    };

    //模块类型8 图片导航
    HYD.DIY.Unit.event_type8=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom, //控制内容
            tpl_con=$("#tpl_diy_con_type8").html(),//手机内容模板
            tpl_ctrl=$("#tpl_diy_ctrl_type8").html();//控制内容模板

        data.dom_ctrl=ctrldom;

        //重新渲染数据
        var reRender=function(callback){
            var $render=$(_.template(tpl_con,data));
            $conitem.find(".members_con").remove().end().append($render);

            var $render_ctrl=$(_.template(tpl_ctrl,data));
            $ctrl.empty().append($render_ctrl);

            HYD.DIY.Unit.event_type8($ctrl,data);
            if(callback) callback();
        }

        // 控制图片高度
        var setImgHeightcallback = function(){
            $('.members_nav1 ul li').each(function(index, el) {
                var me = $(this),
                    imgHeight = me.find('img').width();
                me.find('img').height(imgHeight);
            });
        }
        //改变标题
        $ctrl.find("input[name='title']").change(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset[index].showtitle=$(this).val();
            reRender();
        });

        //改变链接
        $ctrl.find(".droplist li").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            HYD.popbox.dplPickerColletion({
                linkType:$(this).data("val"),
                callback:function(item,type){
                    data.content.dataset[index].title=item.title;
                    data.content.dataset[index].showtitle=item.title;
                    data.content.dataset[index].link=item.link;
                    data.content.dataset[index].linkType=type;
                    if(item.pic && item.pic!=""){
                        data.content.dataset[index].pic=item.pic;
                    }
                    reRender(setImgHeightcallback);
                }
            });
        });

        //选择图片
        $ctrl.find(".j-selectimg").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            HYD.popbox.ImgPicker(function(imgs){
                data.content.dataset[index].pic=imgs[0];//获取第一张图片
                reRender(setImgHeightcallback);
            });
        });

        //自定义链接
        $ctrl.find("input[name='customlink']").change(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset[index].link=$(this).val();
        });

        //上移
        $ctrl.find(".j-moveup").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            if(index==0) return;//第一个导航不可再向上移动

            //替换缓存数组中的位置
            var tmpdata=data.content.dataset.slice(index,index+1)[0];
            data.content.dataset.splice(index,1);
            data.content.dataset.splice(index-1,0,tmpdata);

            reRender();//更新视图
        });

        //下移
        $ctrl.find(".j-movedown").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index(),
                len=data.content.dataset.length;

            if(index==len-1) return;//最后一个导航不可再向下移动

            //替换缓存数组中的位置
            var tmpdata=data.content.dataset.slice(index,index+1)[0];
            data.content.dataset.splice(index,1);
            data.content.dataset.splice(index+1,0,tmpdata);

            reRender();//更新视图
        });

        //添加
        $ctrl.find(".ctrl-item-list-add").click(function(){
            var newdata={
                    linkType:0,
                    link:"",
                    title:"",
                    showtitle:"",
                    pic:""
                };
            data.content.dataset.push(newdata);
            reRender();
        });

        //删除
        $ctrl.find(".j-del").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset.splice(index,1);
            reRender();
        });
    };

    //模块类型9 广告图片
    HYD.DIY.Unit.event_type9=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom, //控制内容
            tpl_con=$("#tpl_diy_con_type9").html(),//手机内容模板
            tpl_ctrl=$("#tpl_diy_ctrl_type9").html();//控制内容模板

        data.dom_ctrl=ctrldom;

        //重新渲染数据
        var reRender=function(){
            var $render=$(_.template(tpl_con,data));
            $conitem.find(".members_con").remove().end().append($render);

            var $render_ctrl=$(_.template(tpl_ctrl,data));
            $ctrl.empty().append($render_ctrl);

            HYD.DIY.Unit.event_type9($ctrl,data);
        }

        //改变标题
        $ctrl.find("input[name='title']").change(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset[index].showtitle=$(this).val();
            reRender();
        });

        //改变显示方式
        $ctrl.find("input[name='showType']").change(function(){
            data.content.showType=$(this).val();
            reRender();
        });

        // 是否留白
        $ctrl.find("input[name='space']").change(function(){
            data.content.space=$(this).val();
            reRender();
        });
        // 为非滚动图片时的上下距离
        $ctrl.find("#slider").slider({
            min:0,
            max:20,
            step:1,
            animate: "fast",
            value:data.content.margin,
            slide:function(event,ui){
                $conitem.find(".members_imgad ul li").css("margin-bottom",ui.value);
                $ctrl.find(".j-ctrl-showheight").text(ui.value+"px");
            },
            stop:function(event,ui){
                data.content.margin=parseInt(ui.value);
            }
        });
        //改变链接
        $ctrl.find(".droplist li").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            HYD.popbox.dplPickerColletion({
                linkType:$(this).data("val"),
                callback:function(item,type){
                    data.content.dataset[index].title=item.title;
                    data.content.dataset[index].showtitle=item.title;
                    data.content.dataset[index].link=item.link;
                    data.content.dataset[index].linkType=type;
                    if(item.pic && item.pic!=""){
                        data.content.dataset[index].pic=item.pic;
                    }
                    reRender();
                }
            });
        });

        //选择图片
        $ctrl.find(".j-selectimg").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            HYD.popbox.ImgPicker(function(imgs){
                data.content.dataset[index].pic=imgs[0];//获取第一张图片
                reRender();
            });
        });

        //自定义链接
        $ctrl.find("input[name='customlink']").change(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset[index].link=$(this).val();
        });

        //上移
        $ctrl.find(".j-moveup").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            if(index==0) return;//第一个导航不可再向上移动

            //替换缓存数组中的位置
            var tmpdata=data.content.dataset.slice(index,index+1)[0];
            data.content.dataset.splice(index,1);
            data.content.dataset.splice(index-1,0,tmpdata);

            reRender();//更新视图
        });

        //下移
        $ctrl.find(".j-movedown").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index(),
                len=data.content.dataset.length;

            if(index==len-1) return;//最后一个导航不可再向下移动

            //替换缓存数组中的位置
            var tmpdata=data.content.dataset.slice(index,index+1)[0];
            data.content.dataset.splice(index,1);
            data.content.dataset.splice(index+1,0,tmpdata);

            reRender();//更新视图
        });

        //添加
        $ctrl.find(".ctrl-item-list-add").click(function(){
            var newdata={
                    linkType:0,
                    link:"",
                    title:"",
                    showtitle:"",
                    pic:""
                };
            data.content.dataset.push(newdata);
            reRender();
        });

        //删除
        $ctrl.find(".j-del").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset.splice(index,1);
            reRender();
        });
    };

    //模块类型11 辅助空白
    HYD.DIY.Unit.event_type11=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom, //控制内容
            tpl_con=$("#tpl_diy_con_type11").html(),//手机内容模板
            tpl_ctrl=$("#tpl_diy_ctrl_type11").html();//控制内容模板

        data.dom_ctrl=ctrldom;

        //重新渲染数据
        var reRender=function(){
            var $render=$(_.template(tpl_con,data));
            $conitem.find(".members_con").remove().end().append($render);

            var $render_ctrl=$(_.template(tpl_ctrl,data));
            $ctrl.empty().append($render_ctrl);

            HYD.DIY.Unit.event_type11($ctrl,data);
        }

        $ctrl.find("#slider").slider({
            min:10,
            max:100,
            step:1,
            animate: "fast",
            value:data.content.height,
            slide:function(event,ui){
                $conitem.find(".custom-space").css("height",ui.value);
                $ctrl.find(".j-ctrl-showheight").text(ui.value+"px");
            },
            stop:function(event,ui){
                data.content.height=parseInt(ui.value);
            }
        });
    };

    //模块类型12 顶部导航
    HYD.DIY.Unit.event_type12=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom, //控制内容
            tpl_con=$("#tpl_diy_con_type12").html(),//手机内容模板
            tpl_ctrl=$("#tpl_diy_ctrl_type12").html();//控制内容模板

        data.dom_ctrl=ctrldom;

        //重新渲染数据
        var reRender=function(){
            var $render=$(_.template(tpl_con,data));
            $conitem.find(".Header_style12_panel").remove().end().append($render);

            var $render_ctrl=$(_.template(tpl_ctrl,data));
            $ctrl.empty().append($render_ctrl);

            HYD.DIY.Unit.event_type12($ctrl,data);
        }

        //导航名称修改
        $ctrl.find("input[name='navtitle']").change(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index(),
                val=$(this).val();

             data.content.dataset[index].showtitle=val;
             reRender();
        });

        //改变链接
        $ctrl.find(".droplist li").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            HYD.popbox.dplPickerColletion({
                linkType:$(this).data("val"),
                callback:function(item,type){
                    data.content.dataset[index].title=item.title;
                    data.content.dataset[index].showtitle=item.title;
                    data.content.dataset[index].link=item.link;
                    data.content.dataset[index].linkType=type;
                    if(item.pic && item.pic!=""){
                        data.content.dataset[index].pic=item.pic;
                    }
                    reRender();
                }
            });
        });

        //选择图片
        $ctrl.find(".j-selectimg").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            HYD.popbox.ImgPicker(function(imgs){
                data.content.dataset[index].pic=imgs[0];//获取第一张图片
                reRender();
            });

        });

        //自定义链接
        $ctrl.find("input[name='customlink']").change(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset[index].link=$(this).val();
        });

        //修改导航背景颜色
        $ctrl.find("select[name='navbgColor']").change(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index(),
                val=$(this).val();

            data.content.dataset[index].bgColor=val;
            reRender();
        });


        // 显示导航类型
        $ctrl.find('input[name="showstyle"]').change(function(event) {
            var me = $(this),
                style = me.val();
            data.content.style = style;
            reRender();
        });

        // 显示导航是否有边距
        $ctrl.find('input[name="marginstyle"]').change(function(event) {
            var me = $(this),
                marginstyle = me.val();
            data.content.marginstyle = marginstyle;
            reRender();
        });
        //导航颜色选择器
        // $ctrl.find(".colorPicker").each(function(e){
        //     var name=$(this).data("name"),
        //         color=$(this).data("color"),
        //         selector="#j_clp_col"+name;
        //         // alert($(selector).html());
        //     $(this).ColorPicker({
        //         color: color,
        //         onShow: function (colpkr) {
        //             $(colpkr).fadeIn(500);
        //             return false;
        //         },
        //         onHide: function (colpkr) {
        //             $(colpkr).fadeOut(500);
        //             reRender();
        //             return false;
        //         },
        //         onChange: function (hsb, hex, rgb) {
        //             var hex='#' + hex;
        //             $(selector).css("background-color",hex);
        //             data.content.dataset[e].bgColor=hex;
        //         }
        //     });
        // });
        $ctrl.find('.ctrl-item-list-li').each(function(index,ell) {
            var me = $(this);
            me.find('.colorPicker').each(function(indexs,el) {
                var _this = $(this);
                if(indexs == 0){
                    var name=$(this).data("name"),
                        color=$(this).data("color"),
                        selector="#j_clp_col"+name;
                        // alert($(selector).html());
                    _this.ColorPicker({
                        color: color,
                        onShow: function (colpkr) {
                            $(colpkr).fadeIn(100);
                            return false;
                        },
                        onHide: function (colpkr) {
                            $(colpkr).fadeOut(100);
                            reRender();
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            var hex='#' + hex;
                            _this.css("background-color",hex);
                            data.content.dataset[index].bgColor=hex;
                        }
                    });
                }else{
                    var name=$(this).data("name"),
                        color=$(this).data("color"),
                        selector="#j_clp_col"+name;
                        // alert($(selector).html());
                    _this.ColorPicker({
                        color: color,
                        onShow: function (colpkr) {
                            $(colpkr).fadeIn(100);
                            return false;
                        },
                        onHide: function (colpkr) {
                            $(colpkr).fadeOut(100);
                            reRender();
                            return false;
                        },
                        onChange: function (hsb, hex, rgb) {
                            var hex='#' + hex;
                            $(selector).css("background-color",hex);
                            data.content.dataset[index].fotColor=hex;
                        }
                    });
                }
            });
        });
        // 上传导航小图标
        $ctrl.find('.j-uploadIcon').click(function(event) {
            var index=$(this).parents("li.ctrl-item-list-li").index();
            HYD.popbox.ImgPicker(function(imgs){
                data.content.dataset[index].pic=imgs[0];
                reRender();
            });
        });
        //修改导航小图标
        $ctrl.find(".j-navModifyIcon").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            // HYD.popbox.ImgPicker(function(imgs){
                
            //     data.content.dataset[index].pic=imgs[0];
            //     reRender();
            // });
            HYD.popbox.IconPicker(function(imgs){
                console.log(imgs)
                data.content.dataset[index].pic=imgs[0];
                reRender();
            });
        });
        //上移
        $ctrl.find(".j-moveup").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            if(index==0) return;//第一个导航不可再向上移动

            //替换缓存数组中的位置
            var tmpdata=data.content.dataset.slice(index,index+1)[0];
            data.content.dataset.splice(index,1);
            data.content.dataset.splice(index-1,0,tmpdata);

            reRender();//更新视图
        });

        //下移
        $ctrl.find(".j-movedown").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index(),
                len=data.content.dataset.length;

            if(index==len-1) return;//最后一个导航不可再向下移动

            //替换缓存数组中的位置
            var tmpdata=data.content.dataset.slice(index,index+1)[0];
            data.content.dataset.splice(index,1);
            data.content.dataset.splice(index+1,0,tmpdata);

            reRender();//更新视图
        });

        //添加
        $ctrl.find(".ctrl-item-list-add").click(function(){
            var newdata={
                    linkType:0,
                    link:"",
                    title:"",
                    showtitle:"",
                    pic:""
                };
            data.content.dataset.push(newdata);
            reRender();
        });

        //删除
        $ctrl.find(".j-del").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset.splice(index,1);
            reRender();
        });
    };

    //模块类型13 橱窗
    HYD.DIY.Unit.event_type13=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom, //控制内容
            tpl_con=$("#tpl_diy_con_type13").html(),//手机内容模板
            tpl_ctrl=$("#tpl_diy_ctrl_type13").html();//控制内容模板

        data.dom_ctrl=ctrldom;

        //重新渲染数据
        var reRender=function(callback){
            var $render=$(_.template(tpl_con,data));
            $conitem.find(".members_con").remove().end().append($render);

            var $render_ctrl=$(_.template(tpl_ctrl,data));
            $ctrl.empty().append($render_ctrl);
            HYD.DIY.Unit.event_type13($ctrl,data);
            if(callback) callback();
        }
        // 设置宽高
        var setImgHeight = function(){
            var inputval = $('input[name=layout]:checked').val();
            if(parseInt(inputval) == 1){
                $('.board3').each(function(index, el) {
                    var me = $(this);
                    var bwidth = me.width();
                    me.height(bwidth).css('overflow','hidden');
                });
            }else{
                $('.board3').each(function(index, el) {
                    var me = $(this);
                    var bwidth = me.width();
                    if(me.hasClass('small_board') || !me.hasClass('big_board')){
                        me.children('span').attr('style', 'height:'+bwidth+'px !important;overflow:hidden;');
                    }
                    if(me.hasClass('big_board')){
                        me.children('span').attr('style', 'height:'+(bwidth*2+10)+'px !important;overflow:hidden;');
                    }
                });
            };
        };
        // 选择布局方式
        $ctrl.find('input[name="layout"]').change(function(event) {
            var me = $(this),
                layout = me.val();
            data.content.layout = layout;
            reRender(setImgHeight);
        });
        //改变标题
        $ctrl.find("input[name='title']").change(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset[index].showtitle=$(this).val();
            reRender();
        });

        //改变链接
        $ctrl.find(".droplist li").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            HYD.popbox.dplPickerColletion({
                linkType:$(this).data("val"),
                callback:function(item,type){
                    data.content.dataset[index].title=item.title;
                    data.content.dataset[index].showtitle=item.title;
                    data.content.dataset[index].link=item.link;
                    data.content.dataset[index].linkType=type;
                    if(item.pic && item.pic!=""){
                        data.content.dataset[index].pic=item.pic;
                    }
                    reRender();
                }
            });
        });

        //选择图片
        $ctrl.find(".j-selectimg").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            HYD.popbox.ImgPicker(function(imgs){
                data.content.dataset[index].pic=imgs[0];//获取第一张图片
                reRender(setImgHeight);
            });
        });

        //自定义链接
        $ctrl.find("input[name='customlink']").change(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset[index].link=$(this).val();
        });

        //上移
        $ctrl.find(".j-moveup").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            if(index==0) return;//第一个导航不可再向上移动

            //替换缓存数组中的位置
            var tmpdata=data.content.dataset.slice(index,index+1)[0];
            data.content.dataset.splice(index,1);
            data.content.dataset.splice(index-1,0,tmpdata);

            reRender();//更新视图
        });

        //下移
        $ctrl.find(".j-movedown").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index(),
                len=data.content.dataset.length;

            if(index==len-1) return;//最后一个导航不可再向下移动

            //替换缓存数组中的位置
            var tmpdata=data.content.dataset.slice(index,index+1)[0];
            data.content.dataset.splice(index,1);
            data.content.dataset.splice(index+1,0,tmpdata);

            reRender();//更新视图
        });

        //添加
        $ctrl.find(".ctrl-item-list-add").click(function(){
            var newdata={
                    linkType:0,
                    link:"",
                    title:"",
                    showtitle:"",
                    pic:""
                };
            data.content.dataset.push(newdata);
            reRender();
        });

        //删除
        $ctrl.find(".j-del").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();
            data.content.dataset.splice(index,1);
            reRender();
        });

    };

    //模块类型14 视频
    HYD.DIY.Unit.event_type14=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom, //控制内容
            tpl_con=$("#tpl_diy_con_type14").html(),//手机内容模板
            tpl_ctrl=$("#tpl_diy_ctrl_type14").html();//控制内容模板

        data.dom_ctrl=ctrldom;
        //重新渲染数据
        var reRender=function(videoUrl){
            var $render=$(_.template(tpl_con,data));
            $conitem.find(".members_con").remove().end().append($render);

            var $render_ctrl=$(_.template(tpl_ctrl,data));
            $ctrl.empty().append($render_ctrl);
            HYD.DIY.Unit.event_type14($ctrl,data);
            $render.find('iframe').attr('src', videoUrl);
        };
        // 验证视频地址并进行匹配
        var verifyVideoSrc = function(website){
            var idstr_reg=/vid\=([^\&]*)($|\&)+/g,
                idstr_reg2=/sid\/\w*.*?/g;
            var videoSrc,qqvideoID,youkuvideoID;
            qqvideoID = website.match(idstr_reg);
            youkuvideoID = website.match(idstr_reg2);
            if(qqvideoID){
                qqvideoID = qqvideoID.toString()
                videoSrc = 'http://v.qq.com/iframe/player.html?'+qqvideoID+'&tiny=0&auto=0';
            };
            if(youkuvideoID){
                youkuvideoID=youkuvideoID.toString();
                youkuvideoID=youkuvideoID.split('/v.swf');
                youkuvideoID=youkuvideoID.toString();
                youkuvideoID=youkuvideoID.replace('sid/','').replace(',','');
                videoSrc = 'http://player.youku.com/embed/'+youkuvideoID;
            };
            if(qqvideoID === null && youkuvideoID === null){
                HYD.hint("danger", "请填写正确的视频网址");
                return false;
            };
            return videoSrc;
        };
        $ctrl.find('.j-getvideo').click(function(event) {
            var website = $(this).prev('input').val();
            data.content.website = website;
            var videoSrc = verifyVideoSrc(website);
            reRender(videoSrc);
        });
    };

    //模块类型15 音频
    HYD.DIY.Unit.event_type15=function(ctrldom, data){
        var $conitem = data.dom_conitem, //手机内容
            $ctrl = ctrldom, //控制内容
            tpl_con=$("#tpl_diy_con_type15").html(),//手机内容模板
            tpl_ctrl=$("#tpl_diy_ctrl_type15").html();//控制内容模板

        data.dom_ctrl=ctrldom;
        //重新渲染数据
        var reRender=function(videoUrl){
            var $render=$(_.template(tpl_con,data));
            $conitem.find(".members_con").remove().end().append($render);

            var $render_ctrl=$(_.template(tpl_ctrl,data));
            $ctrl.empty().append($render_ctrl);
            HYD.DIY.Unit.event_type15($ctrl,data);
            // $render.find('embed').attr('src', videoUrl);
        };
        var AudioPicker=function(callback){
        
            //选择事件
            /*
             * 图片选择器
             * @Author  chenjie
             * @param callback 回调函数
             * @return Array 图片列表数组
             */

            var html = $("#tpl_popbox_Audio").html(),
                $picker = $(html),//选择器
                imglist;//图片列表缓存

            /*
             * ajax 根据页数 获取图片列表数据
             * @param page 页数
             */
            var showImgListRender = function(page,callback_showImgListRender) {
                var dorender=function(data){
                    imglist=data.list;//音频列表
                    if(!imglist || !imglist.length){
                        $picker.find(".imgpicker-list").append("<p class='txtCenter'>对不起，暂无音频</p>");
                    }
                    else{
                        //渲染模板
                        var html = _.template($("#tpl_popbox_ImgPicker_audio").html(), {dataset: imglist}),
                            $render = $(html);

                        //绑定选择事件
                        $render.filter("li").click(function() {
                            $(this).addClass("selected").siblings("li").removeClass("selected");
                        });

                        // 编辑音频名称
                        $render.find('.audio-name').click(function(event) {
                           return false;
                        });
                         $render.find('.j-get-edit-name').click(function(event) {
                            $(this).hide().siblings('.j-edit-name').show();
                            $(this).siblings('.j-edit-name').find('input').focus();
                            return false;
                        });
                        $render.find('.j-getAudioName').click(function(event) {
                            var me = $(this);
                            var curName = me.siblings('input[name="audioName"]').val();
                            var fild_id = me.data('id');
                            $.ajax({
                                url: '/Design/renameImg',
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    file_id: fild_id,
                                    file_name:curName
                                },
                                success:function(data){
                                    // console.log(data)
                                    if(data.status == 1){
                                        HYD.hint("success", "恭喜您，修改音频名称成功！");
                                        me.closest('.j-edit-name').hide().siblings('.j-curname').html(curName);
                                    }
                                    
                                }
                            })
                            
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
                    url:"/Design/getMediaList",
                    type:"post",
                    dataType:"json",
                    data:{"p":parseInt(page),type:'voice'},
                    // beforeSend:function(){
                    //     $.jBox.showloading();
                    // },
                    success:function(data){
                        if (data.status == 1) {
                            // console.log(data)
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
                var arrSelectedId=[];
                //上传事件
                $picker.find("#imgpicker_upload_input").uploadify({
                    "debug": false,
                    "auto": true,
                    "formData": {"PHPSESSID": $.cookie("PHPSESSID"),type:'voice'},
                    "width": 60,
                    "height": 60,
                    "multi": true,
                    'swf': '/Public/plugins/uploadify/uploadify.swf',
                    'uploader': '/Design/uploadMedia', //接口名称
                    "buttonText": "+",
                    "fileSizeLimit": "5MB",
                    "fileTypeExts": "*.mp3; *.wma; *.wav; *.amr",
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
                            tpl = $("#tpl_popbox_ImgPicker_audio2").html(), //获取模板
                            $PrvPanel = $picker.find(".imgpicker-upload-preview"); //获取预览图容器

                        var url=data.file_path;//图片路径
                        var id=data.file_id; //图片id
                        arrSelected.push(url);//将数据插入数组
                        arrSelectedId.push(id);
                        var html = _.template(tpl, {url: url,id:id}), //渲染模板
                        $render = $(html);
                        // console.log($render)
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
                    if(arrSelected.length==0){
                        HYD.hint("danger", "对不起，您没有选择音频：" + data.msg);
                    }else{
                        // console.log(arrSelected[0])
                        data.content.audiosrc=arrSelected[0];
                        reRender();
                    }
                    $.jBox.close(jbox);
                });
            };

            //打开图片选择器窗口，并渲染第一页数据
            showImgListRender(1,function(){
                $.jBox.show({
                    title: "选择音频",
                    content: $picker,
                    btnOK: {show: false},
                    btnCancel: {show: false},
                    onOpen:function(jbox){
                        var $btnuse=$picker.find("#j-btn-listuse");//使用选中音频按钮
                        var $btndel = $picker.find('#j-btn-listdel');//删除选中的音频按钮
                        //使用选中图片事件
                        $btnuse.click(function(){
                            var arrSelected=[];//选中的数据
                            var arrSelectedId=[]
                            //将选中的图片数据推入数组
                            $picker.find(".imgpicker-list li.selected").each(function(){
                                arrSelected.push(imglist[$(this).index()].file_path);
                                arrSelectedId.push(imglist[$(this).index()].file_id);
                            });
                            if(arrSelected.length==0){
                                HYD.hint("danger", "对不起，您没有选择音频：" + data.msg);
                            }else{
                                if(callback) callback(arrSelected);
                            }

                            $.jBox.close(jbox);
                        });
                        // 删除选中音频事件
                        $btndel.click(function(){
                            var file_id = $picker.find(".imgpicker-list li.selected").children('.audio-flag').data('id');
                            $.ajax({
                                url: '/Design/delImg',
                                type: 'POST',
                                dataType: 'json',
                                data: {file_id: file_id},
                                success:function(data){
                                    // console.log(data)
                                    if(data.status == 1){
                                        HYD.hint("success", "删除成功");
                                        $picker.find(".imgpicker-list li.selected").remove();
                                    }
                                }
                            })
                            
                        })



                        //切换到上传图片选项卡，初始化上传组件
                        $picker.find(".j-initupload").one("click",function(){
                            initUpload(jbox);
                        });
                    }
                });
            });
            
        }

        //选择图片
        $ctrl.find(".j-selectimg").click(function(){
            var index=$(this).parents("li.ctrl-item-list-li").index();

            HYD.popbox.ImgPicker(function(imgs){
                data.content.imgsrc=imgs[0];//获取第一张图片
                reRender();
            });
        });

        // 添加修改音频
        $ctrl.find(".j-audioselect").click(function(){

            AudioPicker(function(audio){
                data.content.audiosrc=audio[0];
                reRender();
            });
        });

        

    }
});