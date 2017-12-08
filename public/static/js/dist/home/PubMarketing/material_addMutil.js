//添加编辑多条图文
$(function(){
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
		link:"",
		title:"",
		coverimg:"/Public/images/demo_news.gif",
		dataset:[
			{
				link:"",
				title:"",
				img:""
			}
		]
	};

	var tpl_material_con=$("#tpl_material_con").html(),//手机模板
		tpl_material_ctrl=$("#tpl_material_ctrl").html(),//控制模板
		initData=$("#j-initData").val(),//初始化数据
		dragStart=0,//拖拽开始前的位置
		dragEnd=0;//拖拽结束后的位置

	//如果初始化数据为空，则设置默认参数
	if(initData.length){
		initData=$.parseJSON(initData);
	}
	else{
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

		setVal();
	};

	//更换封面图
	$(document).on("click",".j-imgcover",function(){
		var $btn=$(this);

		HYD.popbox.ImgPicker(function(imgs){
			initData.coverimg=imgs[0];
			$btn.siblings("img").attr("src",imgs[0]);//设置控制内容的图片
			reRender_material_con();//重新渲染预览视图
		})
	});

	//更改封面的标题和链接
	$(document).on("change",".j-renderEle",function(){
		var name=$(this).data("name"),
			val=$(this).val();

		initData[name]=val;//更新值
		reRender_material_con();//重新渲染预览视图
	});

	//更换配图
	$(document).on("click",".j-imgcover-small",function(){
		var $btn=$(this),
			index=$(this).parents("li").index();

		HYD.popbox.ImgPicker(function(imgs){
			initData.dataset[index].img=imgs[0];
			$btn.siblings("img").attr("src",imgs[0]);//设置控制内容的图片
			reRender_material_con();//重新渲染预览视图
		})
	});

	//更改配图的标题和链接
	$(document).on("change",".j-renderSubEle",function(){
		var name=$(this).data("name"),
			val=$(this).val(),
			index=$(this).parents("li").index();

		initData.dataset[index][name]=val;//更新值
		reRender_material_con();//重新渲染预览视图
	});

	//删除
	$(document).on("click",".j-del-material",function(){
		if(initData.dataset.length<=1){
			HYD.hint("warning","请至少保留一项！");
			return;
		}

		var index=$(this).parents("li").index();

		initData.dataset.splice(index,1);//从缓存中删除
		$(this).parents("li").remove();//从控制内容中删除
		reRender_material_con();//重新渲染预览视图
	});

	//添加
	$(document).on("click","#j-addMaterial",function(){
        var o = $("#materialDragPanel li");

		var tmp={
				link:"",
				title:"",
				img:""
			};

		initData.dataset.push(tmp);
		reRender_material_con();
		reRender_material_ctrl();
	});

	//首次加载渲染所有视图
	reRender_material_con();
	reRender_material_ctrl();

});