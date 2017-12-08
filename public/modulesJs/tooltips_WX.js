/*
 * tooltips 工具提示
 * @Author  chenjie
 * @comment 用法$(".tips").tooltips();
*/
;(function($,document,window){
	//默认参数
	var defaults={
		trigger:"hover" //默认为hover触发显示/隐藏tips
	};

	$.fn.tooltips=function(){
		return this.each(function(){
			//显示tips
			var _show=function(){
				var	$target=$(this),//需要弹窗的元素
					content=$target.data("content"),//弹出的内容从标题中获取
					targetPos=$target.offset(),//元素的位置
					targetSize={
						width:$target.outerWidth(true),
						height:$target.outerHeight(true)
					},//元素的大小
					placement=$target.data("placement");//箭头的方向

				this.tip=null;//用于缓存弹出的tip对象

				//当内容为空时获取html中的text
				content=(content==undefined || content=="") ? content=$target.text() : content;

				//如果不存在tip则在body中创建一个
				if(this.$tip==null){
					var tpl=$("#tpl_tooltips").html();//获取模板
					if(tpl==undefined || tpl==""){
						console.log("Please check template!");
						return;
					}
					var render=_.template(tpl,{
						content:content,
						placement:placement
					});//渲染模板

					this.$tip=$(render);
					
					$("body").append(this.$tip);

					var tipTop=0,
						tipLeft=0,
						tipWidth=this.$tip.outerWidth(true),
						tipHeight=this.$tip.outerHeight(true);

					//根据箭头的方向设置不同的位置
					switch(placement){
						case "top":
							tipTop=targetPos.top+targetSize.height+5;
							tipLeft=targetPos.left-5;
						break;
						case "bottom":
							tipTop=targetPos.top-tipHeight-5;
							tipLeft=targetPos.left-5;
						break;
						case "left":
							tipTop=targetPos.top+targetSize.height/2-tipHeight/2;
							tipLeft=targetPos.left+targetSize.width+5;
						break;
						case "right":
							tipTop=targetPos.top+targetSize.height/2-tipHeight/2;
							tipLeft=targetPos.left-tipWidth-5;
						break;
					}
					
					//设置tip的位置
					this.$tip.css({
						top:tipTop,
						left:tipLeft
					});

				}

				//显示
				this.$tip.stop(true,true).fadeIn(300);
			};
			//隐藏tips
			var _hide=function(){
				if(this.$tip) this.$tip.stop(true,true).fadeOut(300);
			};

			var trigger=$(this).data("trigger");
			trigger=(trigger!=undefined && trigger!="") ? trigger : defaults.trigger;

			//根据参数trigger选择不同的触发方式
			switch(trigger){
				case "hover":
					$(this).hover(_show,_hide);
				break;
				case "click":
					$(this).click(_show).mouseleave(_hide);
				break;
			};

		});
	}
})(jQuery,document,window);