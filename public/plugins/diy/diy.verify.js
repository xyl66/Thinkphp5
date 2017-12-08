//diy模式验证
$(function(){
	//装修模块验证白名单,以下模块不参与数据验证
	HYD.DIY.Unit.verifyWhiteList=[
		"1",
		"6",
		"10",
		"11",
		"12",
		"13",
		"14",
		"15",
		"Header_style1",
		"Header_style2",
		"Header_style3",
		"Header_style4",
		"Header_style5",
		"Header_style6",
		"Header_style7",
		"Header_style8",
		"Header_style9",
		"Header_style10",
		"Header_style11",
		"Header_style12",
		"Header_style13",
		"Header_style14",
		"Header_style15",
		"Header_style16",
		"Header_style17",
		"Header_style18",
		"Header_style19",
		"Header_style20",
		"Header_style7_news",
		"Header_style9_news",
		"Header_style12_ad",
		"Header_style12_nav",
		"Header_style15_news",
		"UserCenter",
		"Navigation",
		"MgzCate",
		"GoodsGroup"
	];

	//模块类型2 标题
	HYD.DIY.Unit.verify_type2=function(data){
		var ctrlVisible=false,//控制内容是否为显示状态
			modulePassed=true;

		//显示控制内容
		var showCtrl=function(){
			if(ctrlVisible) return;
			data.dom_conitem.find(".diy-conitem-action").click();
			ctrlVisible=true;
			modulePassed=false;
		}

		if(data.content.title==""){
			showCtrl();
			var ele=data.dom_ctrl.find("input[name='title']");
			HYD.FormShowError(ele,"请填写标题");
		}

		if(data.content.subtitle==""){
			showCtrl();
			var ele=data.dom_ctrl.find("input[name='subtitle']");
			HYD.FormShowError(ele,"请填写副标题");
		}

		// console.log("verify2")

		return modulePassed;
	};

	//模块类型3 自定义模块
	HYD.DIY.Unit.verify_type3=function(data){
		var ctrlVisible=false,//控制内容是否为显示状态
			modulePassed=true;

		//显示控制内容
		var showCtrl=function(){
			if(ctrlVisible) return;
			data.dom_conitem.find(".diy-conitem-action").click();
			ctrlVisible=true;
			modulePassed=false;
		}

		if(!data.content){
			showCtrl();
			var ele=data.dom_ctrl.find(".j-verify");
			HYD.FormShowError(ele,"请选择一个自定义模块");
		}

		// console.log("verify3")
		return modulePassed;
	};

	//模块类型4 产品
	HYD.DIY.Unit.verify_type4=function(data){
		var ctrlVisible=false,//控制内容是否为显示状态
			modulePassed=true;

		//显示控制内容
		var showCtrl=function(){
			if(ctrlVisible) return;
			data.dom_conitem.find(".diy-conitem-action").click();
			ctrlVisible=true;
			modulePassed=false;
		}

		if(!data.content.goodslist.length){
			showCtrl();
			var ele=data.dom_ctrl.find(".j-verify");
			HYD.FormShowError(ele,"请至少选择一件产品");
		}

		// console.log("verify4")
		return modulePassed;
	};

	//模块类型5 产品分组列表
	HYD.DIY.Unit.verify_type5=function(data){
		var ctrlVisible=false,//控制内容是否为显示状态
			modulePassed=true;

		//显示控制内容
		var showCtrl=function(){
			if(ctrlVisible) return;
			data.dom_conitem.find(".diy-conitem-action").click();
			ctrlVisible=true;
			modulePassed=false;
		}

		if(!data.content.group){
			showCtrl();
			var ele=data.dom_ctrl.find(".j-verify");
			HYD.FormShowError(ele,"请选择产品分组");
		}

		// console.log("verify5")
		return modulePassed;
	};

	//模块类型7 文本导航
	HYD.DIY.Unit.verify_type7=function(data){
		var ctrlVisible=false,//控制内容是否为显示状态
			modulePassed=true;

		//显示控制内容
		var showCtrl=function(){
			if(ctrlVisible) return;
			data.dom_conitem.find(".diy-conitem-action").click();
			ctrlVisible=true;
			modulePassed=false;
		}

		if(!data.content.dataset.length){
			showCtrl();
			data.dom_ctrl.find(".j-verify-least").addClass("error").text("请至少添加一个导航链接");
		}

		for(var i=0;i<data.content.dataset.length;i++){
			var tmp=data.content.dataset[i];
			if(tmp.showtitle==""){
				showCtrl();
				var ele=data.dom_ctrl.find(".ctrl-item-list-li:eq("+i+") input[name='title']");
				HYD.FormShowError(ele,"请输入导航名称");
			}
			if(tmp.linkType==0){
				showCtrl();
				data.dom_ctrl.find(".ctrl-item-list-li:eq("+i+") .j-verify-linkType").addClass("error").text("请选择要链接的地址");
			}
		}

		// console.log("verify7")
		return modulePassed;
	};

	//模块类型8 图片导航
	HYD.DIY.Unit.verify_type8=function(data){
		var ctrlVisible=false,//控制内容是否为显示状态
			modulePassed=true;

		//显示控制内容
		var showCtrl=function(){
			if(ctrlVisible) return;
			data.dom_conitem.find(".diy-conitem-action").click();
			ctrlVisible=true;
			modulePassed=false;
		}

		if(!data.content.dataset.length){
			showCtrl();
			data.dom_ctrl.find(".j-verify-least").addClass("error").text("请至少添加一个图片导航");
		}

		for(var i=0;i<data.content.dataset.length;i++){
			var tmp=data.content.dataset[i];
			if(tmp.showtitle==""){
				showCtrl();
				var ele=data.dom_ctrl.find(".ctrl-item-list-li:eq("+i+") input[name='title']");
				HYD.FormShowError(ele,"请输入导航名称");
			}
			if(tmp.linkType==0){
				showCtrl();
				data.dom_ctrl.find(".ctrl-item-list-li:eq("+i+") .j-verify-linkType").addClass("error").text("请选择要链接的地址");
			}
			if(tmp.pic==""){
				showCtrl();
				data.dom_ctrl.find(".ctrl-item-list-li:eq("+i+") .j-verify-pic").addClass("error").text("请选择一张图片");
			}
		}

		// console.log("verify8")
		return modulePassed;
	};

	//模块类型9 图片广告
	HYD.DIY.Unit.verify_type9=function(data){
		var ctrlVisible=false,//控制内容是否为显示状态
			modulePassed=true;

		//显示控制内容
		var showCtrl=function(){
			if(ctrlVisible) return;
			data.dom_conitem.find(".diy-conitem-action").click();
			ctrlVisible=true;
			modulePassed=false;
		}

		if(!data.content.dataset.length){
			showCtrl();
			data.dom_ctrl.find(".j-verify-least").addClass("error").text("请至少添加一个图片广告");
		}

		for(var i=0;i<data.content.dataset.length;i++){
			var tmp=data.content.dataset[i];
			if(tmp.linkType==0){
				showCtrl();
				data.dom_ctrl.find(".ctrl-item-list-li:eq("+i+") .j-verify-linkType").addClass("error").text("请选择要链接的地址");
			}
			if(tmp.pic==""){
				showCtrl();
				data.dom_ctrl.find(".ctrl-item-list-li:eq("+i+") .j-verify-pic").addClass("error").text("请选择一张图片");
			}
		}

		// console.log("verify9")
		return modulePassed;
	};

	//检测页面所有模块是否已经通过验证
	HYD.DIY.Unit.verify=function(){
		var WhiteList=HYD.DIY.Unit.verifyWhiteList,//无需验证的模块白名单
			allPassed=true,//所有模块通过验证
			Llen=HYD.DIY.LModules.length,
			Plen=HYD.DIY.PModules.length;

		//遍历装修模块的数据完整性
		if(Llen){
			for(var i=0;i<Llen;i++){
				var module=HYD.DIY.LModules[i];
				if(WhiteList.indexOf(module.type.toString())<0){
					if(!HYD.DIY.Unit["verify_type"+module.type](module)){
						allPassed=false;
						break;
					}
				}
			}
		}

		//遍历装修模块的数据完整性
		if(Plen){
			for(var i=0;i<Plen;i++){
				var module=HYD.DIY.PModules[i];
				if(WhiteList.indexOf(module.type.toString())<0){
					if(!HYD.DIY.Unit["verify_type"+module.type](module)){
						allPassed=false;
						break;
					}
				}
			}
		}

		return allPassed;
	};
});