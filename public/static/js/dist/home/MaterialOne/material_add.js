//添加编辑单条图文
$(function(){

    var tpl_material_con=$("#tpl_material_con").html(),//手机模板
        tpl_material_ctrl=$("#tpl_material_ctrl").html();//控制模板
        

    // 图片上传
    // KindEditor.ready(function(K) {
    //     var editor;
    //     editor = K.editor({
    //         allowFileManager : true
    //     });

    //     K('.j-imgcover').click(function() {
    //         var self = $(this);
    //         editor.loadPlugin('image', function() {
    //             editor.plugin.imageDialog({
    //                 clickFn : function(url) {
    //                     K('#cover_img').val(url);
    //                     $("#img-cover-img").attr("src", url);
    //                     //$(".materialPrePanel").find("img").attr("src", url);
    //                     self.parent().find(".formError").hide();
    //                     initData["coverimg"] = url;
    //                     reRender_material_con();
    //                     editor.hideDialog();
    //                 }
    //             });
    //         });
    //     });
    // });

	//默认数据
	var defaults={
		link:"链接",
		title:"标题",
        datetime:"",
        link_type:"",
        link_id:"",
        link_name:"",
		redirect:"",
		coverimg:"/Public/images/demo_news.gif",
		summary:"摘要"
	};


	//如果初始化数据为空，则设置默认参数
	if(!initData){
        initData=defaults;
	}

	//输出数据到页面
	var setVal=function(){
		$("#j-initData").val(JSON.stringify(initData));
    }

	//渲染预览视图
	var reRender_material_con=function(){
		var html=_.template(tpl_material_con,initData);
		$("#j-render-con").empty().append(html);
		setVal();
	};

	//渲染控制视图
	var reRender_material_ctrl=function(){
		var html=_.template(tpl_material_ctrl,initData);
		$("#j-render-ctrl").empty().append(html);

        //选择文件
        $("#j-imgcover").uploadify({
            "debug": false,
            "auto": true,
            "formData": {"PHPSESSID": $.cookie("PHPSESSID")},
            "width": 75,
            "multi": false,
            'swf': '/Public/plugins/uploadify/uploadify.swf',
            "buttonImage": "/Public/plugins/uploadify/uploadify-image.png",
            'uploader': '/Design/uploadFileWx', //接口名称
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
                
                var data = eval('(' + data + ')')
                initData["coverimg"] = data.file_path;
                $("#cover_img").val(data.file_path);
                reRender_material_con();
            },
            onUploadError: function(file, errorCode, errorMsg, errorString) {
                HYD.hint("danger", "对不起：" + file.name + "上传失败：" + errorString);
            }
        });

        setVal();

	};



	//首次加载渲染所有视图
	reRender_material_con();
	reRender_material_ctrl();

    $(".j-renderEle").keyup(function(){
        var name=$(this).data("name"),
            val=$(this).val();

        initData[name]=val;//更新值
        reRender_material_con();//重新渲染预览视图
    }).keyup();

    var imgUrl = $("#img-cover-img").attr("src");
    if(imgUrl){
        initData["coverimg"] = imgUrl;
        reRender_material_con();
    }
    // 添加原文链接
    // $(document).on("click",".j-show-btn",function(){
    //     if(!$(this).hasClass('visited')){
    //         $(this).next('.form-controls').show();
    //         $(this).html("原文链接").addClass("visited");
    //     }
    // })
    // 
    // 
    // 
    // 设置链接
    var tpl={
        menu_tab:$("#tpl_menu_tab").html(), //自定义菜单列表切换
        menu:$("#tpl_menu_lst").html(), //自定义菜单列表
        menu_ump:$("#tpl_menu_ump").html(), //自定义菜单营销活动列表
        menu_detail:$("#tpl_menu_detail").html(), //自定义菜单产品列表
        menu_group:$("#tpl_menu_group").html(), //自定义菜单产品分组列表
        menu_magazine:$("#tpl_menu_magazine").html(), //自定义菜单专题列表
        menu_sort:$("#tpl_menu_sort").html() //自定义菜单专题分类列表
    };
     
    // 隐藏显示菜单链接选项
    $(".fx-reset").hover(function() {
        $(this).children('.setLinks').show();
    }, function() {
        $(this).children('.setLinks').hide();
    });
     // 点击设置链接
    $("#setLinks>li>a").click(function() {
        var type=$(this).data('type');
        var key=$(this).data('key');
        var link_type=$(this).data('keys');
        var link_id=$(this).data('link_id');
        var $this=$(this);
        var titleText=$(this).text()+" | ";
        $(".j-show-btn").find('b').html(titleText);
        $('.J-diyUrl').data('link_type', link_type);
        if(type==0){
                //赋值link_type
                initData['link_type'] = link_type;
                $('.J-link_type').val(link_type);
                var TextVal="链接到"+$this.html();
            if(key!="diy"){
                var url=$this.data('url');

                initData['link_id'] = link_id;
                initData['link_name'] = TextVal;
                initData['redirect'] = url;
                $('.J-link_id').val(link_id);
                $('.J-link_name').val(TextVal);


                $(".j-show-btn").find('em').html(TextVal);
                $(".J-diyUrl").hide();
                $(".j-link").val(url).attr('readonly', 'readonly');
                $this.parents(".setLinks").hide();
            }else{
                $(".J-diyUrl").show();
                $(".j-show-btn").find('em').html(TextVal);

                initData['link_id'] = link_id;
                initData['link_name'] = TextVal;
                $('.J-link_id').val(link_id);
                $('.J-link_name').val(TextVal);

                $(".j-link").removeAttr('readonly').val("").focus();
                $this.parents(".setLinks").hide();
            }

        }else{
            $.ajax({
                url: "/PubMarketing/Ajaxmenu",
                type: "post",
                dataType: "json",
                data: {
                    "key": key
                },
                beforeSend: function() {
                    $.jBox.showloading();
                },
                success: function(data) {
                    //console.log(data);
                   if(key=="ump"){
                        //var html_head=_.template(tpl.menu_tab,data);
                        var html_head=$this.html();
                        var html=_.template(tpl.menu_tab,data);//渲染营销活动模板
                    }else if(key=="detail"){
                        var html_head=$this.html();
                        var html=_.template(tpl.menu_detail,data);//渲染产品模板
                    }else if(key=="group"){
                        var html_head=$this.html();
                        var html=_.template(tpl.menu,data);//渲染产品分组模板
                    }else if(key=="magazine"){
                        var html_head=$this.html();
                        var html=_.template(tpl.menu,data);//渲染专题模板
                    }else if(key=="sort"){
                        var html_head=$this.html();
                        var html=_.template(tpl.menu,data);//渲染专题分类模板
                    };
                    $.jBox.show({
                        title: html_head,
                        content: html,
                        btnOK: {show:false},
                        btnCancel:{show:false},
                        onOpen:function(jbox){
                            $.jBox.hideloading();
                        }
                    });
                }
            });
            $this.parents(".setLinks").hide();
        };
    });
    // 营销活动选择
    $(document).on("click",".tabs>.tabs_a",function(){
        var keys=$(this).data('keys');
        $(this).parent("li").addClass('cur').siblings('li').removeClass('cur');
        $.ajax({
            url: "/PubMarketing/Ajaxmenu",
            type: "post",
            dataType: "json",
            data: {
                "key": "ump",
                "game_key":keys
            },
            beforeSend: function() {
                $.jBox.showloading();
            },
            success: function(data) {
                $.jBox.hideloading();
                //console.log(data);
                var html=_.template(tpl.menu_ump,data);//渲染模板
                //console.log(html);
                //$(".jbox-container").html(html).height("auto").parents('.jbox').height("auto");
                $("#GamePicker").children('.tabs-content').remove().end().height("auto").append(html);
            }
        })
    });

    $(document).on("click",".jbox-container>div>div>.paginate>a",function(){
        if ($(this).attr("href")) {
            //获得页数
            var href=$(this).attr("href"),
                attr=href.split("/");
                var key,p;
                $.each( attr, function(i, n){
                    if(n == 'key'){
                        key = attr[i+1];
                        key = key.replace(/.html/,"");
                    }
                    if(n == 'p'){
                        p = attr[i+1];
                        p = p.replace(/.html/,"");
                    }
                });



            $.ajax({
                url: '/PubMarketing/Ajaxmenu',
                type: "post",
                dataType: "json",
                data: {
                    "p":p,
                    "key":key,
                },
                beforeSend: function() {
                    $.jBox.showloading();
                },
                success: function(data) {
                    $.jBox.hideloading();

                    var menu_tpl;
                    if(key == 'ump'){
                        menu_tpl = tpl.menu_ump;
                    }else if(key == 'group'){
                        menu_tpl = tpl.menu_group;
                    }else if(key == 'detail'){
                        menu_tpl = tpl.menu_detail;
                    }else if(key == 'magazine'){
                        menu_tpl = tpl.menu_magazine;
                    }else if(key == 'sort'){
                        menu_tpl = tpl.menu_sort;
                    }else{
                        menu_tpl = tpl.menu_ump;
                    }

                    var html=_.template(menu_tpl,data);//渲染模板
                    //console.log(html);
                    $(".jbox-container").html(html).height("auto").parents('.jbox').height("auto");
                }
            });
        }
        return false;
    });


    // 选择链接
    $(document).on('click', '.j-select', function(event) {
        if ($("#GoodsAndGroupPicker>ul>li>a:first>div").length != 2) {
            //var _this=$(this).parents("tr").find('td:first>input[type=hidden]');
            var LinkVal=$(this).prev("a").attr("href");
            var link_id=$(this).data('link_id');
            var TextVal="链接到"+$(this).prev("a").text();
            var link_type = $('.J-diyUrl').data('link_type');
            
            $(".J-diyUrl").hide();

            //赋值link_type
            initData['link_type'] = link_type;
            initData['link_id'] = link_id;
            initData['link_name'] = TextVal;
            initData['redirect'] = LinkVal;
            $('.J-link_type').val(link_type);
            $('.J-link_id').val(link_id);
            $('.J-link_name').val(TextVal);

            $(".j-show-btn").find('em').html(TextVal);
            $(".j-link").val(LinkVal);
            $(".jbox").remove();
            $("#jbox-overlay").hide();
        } else{
            //var _this=$(this).prev("a").attr('href');
            var LinkVal=$(this).prev("a").attr('href');
            var link_id=$(this).data('link_id');
            var TextVal="链接到"+$(this).prev("a").find('p').text();
            var link_type = $('.J-diyUrl').data('link_type');
            
            $(".J-diyUrl").hide();

            //赋值link_type
            initData['link_type'] = link_type;
            initData['link_id'] = link_id;
            initData['link_name'] = TextVal;
            initData['redirect'] = LinkVal;
            $('.J-link_type').val(link_type);
            $('.J-link_id').val(link_id);
            $('.J-link_name').val(TextVal);

            $(".j-show-btn").find('em').html(TextVal);
            $(".j-link").val(LinkVal);
            $(".jbox").remove();
            $("#jbox-overlay").hide();
        }
    });
});