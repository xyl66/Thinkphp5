//选择发送图文消息通用代码
$(function(){
	//编辑器实例化
	var ue = UE.getEditor('js_editorArea',{
		toolbars: [
			['RemoveFormat','Link', 'Unlink']
		],
		initialFrameHeight:200,
		maximumWords:600
	});
	$("#ss").click(function(){
		console.log($("input[name=message_type]:checked").val())
	});
	$('.Jfastbtn').click(function(event) {
        var me = $(this);
        var con = me.data('con');
        var ueCon = ue.getContent();
        ueCon = ueCon+con;
        ue.setContent(ueCon);
    });
	//模板
	var tpl={
		text_pre:$("#tpl_materialPicker_text_pre").html(),//文本预览
		single_table:$("#tpl_materialPicker_single_table").html(),//单图文列表
		single_pre:$("#tpl_materialPicker_single_pre").html(),//单图文预览
		mutil_table:$("#tpl_materialPicker_mutil_table").html(),//多图文列表
		mutil_pre:$("#tpl_materialPicker_mutil_pre").html()//多图文预览
	};

	//设置单条、多条图文消息的id到页面的input中
	var setVal=function(data){
        $("#j-initDataID").val(data.id);

	};

	//渲染文本预览视图
	var reRenderMaterialPre_Text=function(data){
		if(!data.length) return;
		var html=_.template(tpl.text_pre,{summary:data});//渲染模板
		$("#j-materialPrev").empty().append(html);//插入dom
	}

	//渲染单图文预览视图
	var reRenderMaterialPre_Single=function(data){
		var html=_.template(tpl.single_pre,data);//渲染模板
		$("#j-materialPrev").empty().append(html);//插入dom
		setVal(data);//设置数据内容到input中
	};

	//渲染多图文预览视图
	var reRenderMaterialPre_Mutil=function(data){
		var html=_.template(tpl.mutil_pre,data);//渲染模板
		$("#j-materialPrev").empty().append(html);//插入dom
		setVal(data);//设置数据内容到input中
	};

	//选择单图文事件
	var do_pickerMaterial_Single=function(){
		HYD.ajaxPopTable({
			title:"选择单条图文",
	        url:"/MaterialOne/jsonList",
	        tpl:tpl.single_table,
	        onPageChange:function(jbox,ajaxdata){
	        	//选择事件
	        	jbox.find(".j-select").click(function(){
	        		var index=$(this).parents("tr").index();//获取索引
	        		reRenderMaterialPre_Single(ajaxdata.list[index]);//渲染对应视图
	        		$.jBox.close(jbox);
                    $("#j-initDataID").val(ajaxdata.list[index].material_one_id);
	        	});

	        }
	    });
	};

	//选择多图文事件
	var do_pickerMaterial_Mutil=function(){
		HYD.ajaxPopTable({
			title:"选择多条图文",
	        url:"/MaterialMore/jsonList",
	        tpl:tpl.mutil_table,
	        onPageChange:function(jbox,ajaxdata){
	        	//选择事件
	        	jbox.find(".j-select").click(function(){
	        		var index=$(this).parents("tr").index();//获取索引
	        		reRenderMaterialPre_Mutil(ajaxdata.list[index]);//渲染对应视图
	        		$.jBox.close(jbox);
                    $("#j-initDataID").val(ajaxdata.list[index].material_more_id);
                });
	        }
	    });
	};


	//文本图文消息的实时预览
	/*$("#j-materialText").keyup(function(){
		reRenderMaterialPre_Text($(this).val());
	}).keyup();*/
	ue.addListener( "selectionchange", function () {
		var _html = UE.getEditor('js_editorArea').getContent();
		reRenderMaterialPre_Text(_html);
		$('#j-materialText').val(_html);
	});
	$("#j-selectMaterialSingle").click(do_pickerMaterial_Single);//重新选择单条图文
	$("#j-selectMaterialMutil").click(do_pickerMaterial_Mutil);//重新选择多条图文

	//如果有图文id存在则渲染 [编辑状态]
	var initDataID=$("#j-initDataID").val();
	if(initDataID){
		var url="",//接口
			doAction=null,//执行的动作
			type=$(".j-sendType:checked").data("type");//图文类型

		switch(type){
			case 2:
				doAction=reRenderMaterialPre_Single;
				url="/MaterialOne/jsonList";
				break;
			case 3:
				doAction=reRenderMaterialPre_Mutil;
				url="/MaterialMore/jsonList";
				break;
		}
		//异步获取图文数据，然后渲染
        if(url){
            $.ajax({
                url: url,
                type: "post",
                dataType: "json",
                data: {
                    "id": initDataID
                },
                success: function(data) {
                    if (data.status == 1) {
                        doAction(data.list[0]);
                    } else {
                        HYD.hint("danger", "对不起，获取数据：" + data.msg);
                    }
                }
            });
        }
	}

    //切换选择的类型
    var materialId = $("#material_id").val();
	//var _html = $("#j-materialText").val();
    $(".j-sendType").click(function(){
        if(!$(this).is(":checked")) return;

        //if(!initDataID){
        //    $("#j-materialPrev").empty();//清空之前的预览数据
        //    $("#j-initDataID").val("");//清空选择的图文消息id
        //}

        var type=$(this).data("type");//选中的类型
        $(".j-sendTypeCon[data-type='"+type+"']").show().siblings(".j-sendTypeCon").hide();
        if(!materialId){
            // 自动打开选择器
            switch(type){
                case 1:reRenderMaterialPre_Text($("#j-materialText").val());break;
                case 2:do_pickerMaterial_Single();break;
                case 3:do_pickerMaterial_Mutil();break;
            }
        }
        materialId = 0;

    }).change();


	//发送类型为文本则直接渲染预览视图 [编辑状态]
	if($(".j-sendType:checked").data("type")==1){
		reRenderMaterialPre_Text($("#j-materialText").val());
		ue.addListener("ready",function(){ue.setContent($("#j-materialText").val());});
	}

	$('.ptck_box label').click(function(){
		if($(this).children(':checkbox').is(":checked")){
			$(this).children('i').addClass('act');
		}else{
			$(this).children('i').removeClass('act');
		}
	});
	$('.ptrd_box label').click(function(){
		if($(this).children('input:radio').val() ==1){
			$('.ptrd_box label').children('span').children('i').removeClass('act');
			$(this).children('span').children('.wenb').addClass('act');
		}else if($(this).children('input:radio').val() ==2){
			$('.ptrd_box label').children('span').children('i').removeClass('act');
			$(this).children('span').children('.onewenb').addClass('act');
		}else if($(this).children('input:radio').val() ==3){
			$('.ptrd_box label').children('span').children('i').removeClass('act');
			$(this).children('span').children('.morewenb').addClass('act');
		}
	});



});