//添加编辑多条图文
$(function(){
    ue = UE.getEditor('editor-moudle', {
        autoHeight: false
    });



    var tpl_material_con=$("#tpl_material_con").html(),//手机模板
        tpl_material_ctrl=$("#tpl_material_ctrl").html(),//控制模板
        //initData=$("#j-initData").val(),//初始化数据
        dragStart=0,    //拖拽开始前的位置
        dragEnd= 0,     //拖拽结束后的位置
        curIndex = -1;


    reRenderContent = function(){
        var content = "";
        if(curIndex == -1 || curIndex == -3){
            content = initData.content;
        }else{
            if(!initData.dataset[curIndex]){
                content = "";
            }else{
                content = (initData.dataset[curIndex].content);
            }
        }
         ue.ready(function() {
             //设置编辑器的内容
             ue.setContent(content);

         });
    };

    function init(){
        // 重新初始化上传
        // uploadImgInit();
        dataChange();
        //reRenderContent();
    }



    function dataChange(){
        $("input").on("keyup", function(){

            var cid=$(this).closest(".material-item").data("id");

            	var name=$(this).data("name"),
            		val=$(this).val();
                    if(curIndex == -3 || -1 ==curIndex){
                        initData[name]=val;
                    }else{
                        if(!initData.dataset[curIndex]){
                            initData.dataset[curIndex] = {};
                        }
                        initData.dataset[curIndex][name]=val;
                    }


            setVal();
            reRender_material_con();//重新渲染预览视图
            // 给添加图文增加data-id
            $("#materialPre").find('dd').each(function(index) {
            $(this).attr('date-id', index);
        });
        });

        $(document).click(function(){
            //alert("ok");
        });
    }

    function renderContent(){
        // 先保存数据
        //setTextAreaContent();
        //reRenderContent();
    }

    function setTextAreaContent(){

        ue.ready(function() {
            //设置编辑器的内容
            var content = ue.getContent();
            if(curIndex == -3 || -1 ==curIndex){
                initData.content=content;
            }else{
                if(!initData.dataset[curIndex]){
                    initData.dataset[curIndex] = {};
                }
                initData.dataset[curIndex].content=content;
            }

        });


            //设置编辑器的内容
    }

	//鼠标经过对应的内容，在预览图上以背景高亮标识出来
	$(document).on("mouseenter",".material-item",function(){
		var origin=$(this).data("origin");
		switch(origin){
			case "header":
				$("#materialPre dt").addClass("hover").siblings("dt,dd").removeClass("hover");
				break;
			case "footer":
				var index=$(this).index();
				$("#materialPre dd:eq("+index+")").addClass("hover").siblings("dt,dd").removeClass("hover");
		}
	});

	//默认数据
	var defaults={
		redirect:"",
		title:"标题",
        author:"",
        link_id:"",
        link_name:"",
        link_type:"",
		coverimg:"/Public/images/demo_news.gif",
        content:"",
		dataset:[
			 {
				redirect:"",
                title:"标题",
                 author:"",
                link_id:"",
                link_name:"",
				link_type:"",
				img:"/Public/images/small_pic.png",
                error_id : 1
			}
		]
	};
	//如果初始化数据为空，则设置默认参数
	if(!initData){
        //initData = initData.replace('@@@', '"');
        //alert(initData);
		//initData=$.parseJSON(initData);
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
        //reRenderContent();
        // 如果是默认的图片则清空该数据值
        if(defaults.coverimg == initData.coverimg){
            initData.coverimg = "/Public/images/demo_news.gif";
        }

		var html=_.template(tpl_material_ctrl,initData);
		$("#j-render-ctrl").empty().append(html);

		//初始化布局拖动事件
	    $("#materialDragPanel").sortable({
	        revert: true,
	        placeholder: "drag-highlight",
	        start:function(event, ui){
	        	dragStart=$(ui.item).index();
	        },
	        stop: function(event, ui) {
	        	dragEnd=$(ui.item).index();
	        	HYD.changeDataPos(dragStart,dragEnd,initData.dataset);
	        	setVal();
	        }
	    }).disableSelection();

        //选择文件
        // $('#j-imgcover').click(function(event) {
        //     /* Act on the event */
        //     HYD.popbox.ImgPicker(function(imgs){
        //         // data.content.dataset[index].pic=imgs[0];//获取第一张图片
        //         if(curIndex == -3 || -1 ==curIndex){
        //             initData['coverimg']=imgs[0];
        //         }else{
        //             if(!initData.dataset[curIndex]){
        //                 initData.dataset[curIndex] = {};
        //             }
        //             initData.dataset[curIndex]['img'] = imgs[0];
        //         }
        //         $("#cover_img").val(imgs[0]);
        //         $("#N-img").show().attr('src',imgs[0]);
        //         setVal();
        //         reRender_material_con();
        //     });
        // });
        $("#j-imgcover").uploadify({
            "auto": true,
            "width": 75,
            "multi": false,
            'swf': '/Public/plugins/uploadify/uploadify.swf',
            'uploader': '/Design/uploadFileWx',
            "buttonImage": "/Public/plugins/uploadify/uploadify-image.png",
            "formData": {
                "PHPSESSID": $.cookie("PHPSESSID")
            },
            "onUploadSuccess": function(file, data) {

                var data = eval('(' + data + ')')
                if(curIndex == -3 || -1 ==curIndex){
                    initData['coverimg']=data.file_path;
                }else{
                    if(!initData.dataset[curIndex]){
                        initData.dataset[curIndex] = {};
                    }
                    initData.dataset[curIndex]['img'] = data.file_path;
                }
                $("#cover_img").val(data.file_path);
                setVal();
                reRender_material_con();
            },
            "onUploadError": function(file, errorCode, errorMsg, errorString) {
                HYD.hint("danger", file.name + "上传失败：" + errorString);
            }
        });

		setVal();
	};

     

	//删除
	$(document).on("click",".j-del-material",function(){
		if(initData.dataset.length<=1){
			HYD.hint("warning","请至少保留一项！");
			return;
		}

		var index=$(this).parents("dd").index();
		initData.dataset.splice(index-2,1);//从缓存中删除
		$(this).parents("li").remove();//从控制内容中删除
		reRender_material_con();//重新渲染预览视图
        // 比较两边高度
        var inHeight=$("#material-item").outerHeight(true);
        var Height=$("#materialPre").height();
        var sh=Height+100;
        if(sh<inHeight){
            $(".materialPanel").attr('style','min-height:'+inHeight+'');
        }else{
            $(".materialPanel").attr('style','min-height:'+sh+'px;');
        };
	});

	//添加
	$(document).on("click","#j-addMaterial",function(){
        var oN=$(".materialPrePanel dd");
        if(oN.length>=8){
        	HYD.hint("warning","最多添加8项!");
            return false;
        }
		var tmp={
				redirect:"",
				title:"标题",
				img:"/Public/images/small_pic.png",
                error_id: oN.length+1
			};
		initData.dataset.push(tmp);
		reRender_material_con();
		//reRender_material_ctrl();
        // 重新
        init();
        // 获取添加栏高度
        var inHeight=$("#material-item").outerHeight(true);
        var Height=$("#materialPre").height();
        var sh=Height+100;
        if(sh<inHeight){
            $(".materialPanel").attr('style','min-height:'+inHeight+'');
        }else{
            $(".materialPanel").attr('style','min-height:'+sh+'px;')
        }
        // 给添加图文增加data-id
        $("#materialPre").find('dd').each(function(index) {
            $(this).attr('date-id', index);
        });

	});


	// 编辑多图文数据
	$(document).on("click",".j_edit_btn",function(){
        // 添加前先保存现在的数据
        setTextAreaContent();
		
        var N_title =$(this).parents(".diy-phone-contain,dd").find('h2,h3').text();
		var N_href  =$(this).parents(".diy-phone-contain,dd").children('a').attr('href');
		var N_img   =$(this).parents(".diy-phone-contain,dd").find('img').attr('src');
		var N_id=$(this).parents("dt,dd").attr('date-id');
		$("#material-item").attr('data-id', N_id);

		var oParent=$(this).parent().parent().hasClass('diy-phone-contain');
		if (oParent) {
			$("#material-item").attr('style', 'margin-top:0;');
		} else{
			var oHeight=$(this).parents("dd").height();
			var index=$(this).parents('dd').attr('date-id');

		};

        // 算出它在当前的位置
        var i = $(this).parents("dd").index();
        if(i -2 != curIndex){
            // 如果不是本身则清空editor的数据
			
            ue.ready(function() {
                //设置编辑器的内容
                ue.setContent("");
            });
        }
        curIndex = i -2;

        if(curIndex === -3){
            title = initData.title;
            author = initData.author;
            href = initData.redirect;
            link_id = initData.link_id;
            link_type = initData.link_type;
            link_name = initData.link_name;
            img = initData.coverimg;
        }else if(initData.dataset[curIndex]){
            title = initData.dataset[curIndex].title;
            author = initData.dataset[curIndex].author;
            href = initData.dataset[curIndex].redirect;
            link_id = initData.dataset[curIndex].link_id;
            link_type = initData.dataset[curIndex].link_type;
            link_name = initData.dataset[curIndex].link_name;
            img = initData.dataset[curIndex].img;
        }
        $("#N-title").val(title);
        $("#N-author").val(author);
        $("#N-link").val(href);
        $("#N-img").attr("src",img);
        if(link_type != 0 ){
            $(".J-diyUrl").hide();
            $(".j-link").val(href).prop('readonly',true);
        }else{
            $(".J-diyUrl").show();
            $(".j-link").val(href).prop('readonly',false);
        }

        if(link_name){
            link_name = link_name;
        }else{
            link_name = '阅读原文链接';
        }
        $('.j-show-btn b').html(link_name);




        var N_mt=(curIndex-0)*(oHeight+1)+191;
        $("#material-item").attr('style', 'margin-top:'+N_mt+'px');

        if(curIndex == -1 || curIndex == -3){
            $('.J-instruction').html('大图片建议尺寸：900像素 × 500像素');
        }else{
            $('.J-instruction').html('小图片建议尺寸：200像素 × 200像素');
        }
        var allHeight=$("#material-item").outerHeight(true);
        if(allHeight>=758){
        	$(".materialPanel").attr('style','min-height:'+allHeight+'');
        }else{
        	$(".materialPanel").attr('style','min-height:586px');
        };
        // 获取添加栏高度
        var inHeight=$("#material-item").outerHeight(true);
        var Height=$("#materialPre").height();
        var sh=Height+100;
        if(sh<inHeight){
            $(".materialPanel").attr('style','min-height:'+inHeight+'');
        }else{
            $(".materialPanel").attr('style','min-height:'+sh+'px;')
        }
        // 重新排序data-id
        $("#materialPre").find('dd').each(function(index) {
            $(this).attr('date-id', index);
        });
        // 再渲染数据
        reRenderContent();

	});
	// 删除多图文时将右边编辑器归零
	$(document).on("click",".j_del_btn",function(){
		var f_title=$(".diy-phone-contain").find('h2').text();
		var f_link=$(".diy-phone-contain").children('a').attr('href');
		var f_img=$(".diy-phone-contain").find('img').attr('src');
		$("#material-item").attr('style', 'margin-top:0;');
		$("#N-title").val(f_title);
		$("#N-link").val(f_link);
		$("#N-img").attr("src",f_img);
		curIndex=-1;
		var allHeight=$("#material-item").outerHeight(true);
        var Height=$("#materialPre").height();
        var sh=Height+100;
		if(sh<allHeight){
        	$(".materialPanel").attr('style','min-height:'+allHeight+'');
        }else{
        	$(".materialPanel").attr('style','min-height:'+sh+'px;');
        };
        // 给添加图文增加data-id
        $("#materialPre").find('dd').each(function(index) {
        	$(this).attr('date-id', index);
        });
	});
    // 添加原文链接
    // $(document).on("click",".j-show-btn",function(){
    //     $(this).next('.form-controls').show();
    //     $(this).html("原文链接").addClass("visited");
    // })
	//首次加载渲染所有视图
	reRender_material_con();
	reRender_material_ctrl();
    init();

    function check(title, img, content, link){
        if(!title){
            return '标题不能为空!';
        }
        if(!img || img == '/Public/images/demo_news.gif' || img == '/Public/images/small_pic.png'){
            return '请上传图片';
        }
        if(!content && !link){
            return '正文和原文链接必段任选其一!';
        }
        return true;
    }

    $("#form1").submit(function(){
        // 前先保存现在的数据
        setTextAreaContent();

        if( true !== (errorMsg = check(initData.title, initData.coverimg, initData.content, initData.redirect))){
            HYD.hint("warning", errorMsg);
            $(".j_edit_btn").eq(0).click();
            return false;
        }

        for(var index in initData.dataset){
            if(true !== (errorMsg = check(initData.dataset[index].title, initData.dataset[index].img, initData.dataset[index].content, initData.dataset[index].redirect))){
                var index = parseInt(index) + 1;

                var selector = ".j_edit_btn:eq(" + (index) + ")";
                $(selector).click();
                HYD.hint("warning", errorMsg);
                return false;
            }
        }
        $.jBox.showloading();
        var url = $(this).attr("action");
        initData.is_preview = $(":hidden[name='is_preview']").val();
        $.post(url, initData, function(data){
            console.log(data);
            $.jBox.hideloading();
            if(data.status == 1){
                window.location.href = data.link;
            }
        });


        return false;
    });
    reRenderContent();
    var length = $(".j_edit_btn").length;
     var h=length*113+191;
     $("#materialPre").parent().parent(".materialPanel").attr('style', 'min-height:'+h+'px;');



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
        var link_id=$(this).data('link_id');
        var link_type=$(this).data('keys');
        var $this=$(this);
        var titleText=$(this).text()+" | ";
        $(".j-show-btn").find('b').html(titleText);
        $('.J-diyUrl').hide();

        if(type==0){
            var TextVal=$this.html();
            if(key!="diy"){
                var url=$this.data('url');
                var TextVal="链接到"+$this.html();
                $(".J-diyUrl").hide();

                initData['link_type'] = link_type;

                $(".j-show-btn").find('em').html(TextVal);


                if(curIndex == -1 || curIndex == -3){
                    initData['redirect'] = url;
                    initData['link_name'] = TextVal;
                    initData['link_id'] = link_id;
                    initData['link_type'] = link_type;
                }else{
                    initData.dataset[curIndex]['redirect'] = url;
                    initData.dataset[curIndex]['link_name'] = TextVal;
                    initData.dataset[curIndex]['link_id'] = link_id;
                    initData.dataset[curIndex]['link_type'] = link_type;
                }
                setVal();
        // console.log(initData)

                $(".j-link").val(url).attr('readonly', 'readonly');;
                $this.parents(".setLinks").hide();
            }else{

                $(".J-diyUrl").show();
                $(".j-show-btn").find('em').html(TextVal);

                if(curIndex == -1 || curIndex == -3){
                    initData['link_name'] = TextVal;
                    initData['link_id'] = link_id;
                    initData['link_type'] = link_type;
                }else{
                    initData.dataset[curIndex]['link_name'] = TextVal;
                    initData.dataset[curIndex]['link_id'] = link_id;
                    initData.dataset[curIndex]['link_type'] = link_type;
                }
                setVal();
        // console.log(initData)



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
    })

    // 选择链接
    
        $(document).on('click', '.j-select', function(event) {
            if ($(".jbox-container>div>.wxtables>thead>tr>td").length != 2) {
                //var _this=$(this).parents("tr").find('td:first>input[type=hidden]');
                var LinkVal=$(this).prev("a").attr("href");
                var link_id=$(this).data('link_id');
                var TextVal="链接到"+$(this).prev("a").text();
                var link_type = $('.J-diyUrl').data('link_type');
                $(".j-show-btn").find('em').text(TextVal)
                $(".J-diyUrl").hide();
                if(curIndex == -1 || curIndex == -3){
                    initData['redirect'] = LinkVal;
                    initData['link_name'] = TextVal;
                    initData['link_id'] = link_id;
                    initData['link_type'] = link_type;
                }else{
                    initData.dataset[curIndex]['redirect'] = LinkVal;
                    initData.dataset[curIndex]['link_name'] = TextVal;
                    initData.dataset[curIndex]['link_id'] = link_id;
                    initData.dataset[curIndex]['link_type'] = link_type;
                }
                setVal();
                // console.log(initData)

                $(".j-show-btn").find('em').html(TextVal);
                $(".j-link").val(LinkVal).attr('readony', 'readony');
                $(".jbox").remove();
                $("#jbox-overlay").hide();
            }else{
                //var _this=$(this).parents("tr").find('td:first>input[type=hidden]');
                var LinkVal=LinkVal=$(this).prev("a").attr('href');
                var link_id=$(this).data('link_id');
                var TextVal="链接到"+$(this).prev("a").find('p').text();
                var link_type = $('.J-diyUrl').data('link_type');
                $(".j-show-btn").find('em').text(TextVal)
                $(".J-diyUrl").hide();
                if(curIndex == -1 || curIndex == -3){
                    initData['redirect'] = LinkVal;
                    initData['link_name'] = TextVal;
                    initData['link_id'] = link_id;
                    initData['link_type'] = link_type;
                }else{
                    initData.dataset[curIndex]['redirect'] = LinkVal;
                    initData.dataset[curIndex]['link_name'] = TextVal;
                    initData.dataset[curIndex]['link_id'] = link_id;
                    initData.dataset[curIndex]['link_type'] = link_type;
                }
                setVal();
                // console.log(initData)

                $(".j-show-btn").children('em').html(TextVal);
                $(".j-link").val(LinkVal).attr('readony', 'readony');
                $(".jbox").remove();
                $("#jbox-overlay").hide();
            };
            
        });
    
    
    // 自定义链接更改的时候
    $(document).on('keydown change focus blur', '.j-link', function(event) {
        if(curIndex == -1 || curIndex == -3){
            initData['redirect'] = $(this).val();
        }else{
            initData.dataset[curIndex]['redirect'] = $(this).val();
        }

        setVal();
        // console.log(initData)
    });




});