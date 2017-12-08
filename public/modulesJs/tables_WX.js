/*
 *=================================================
 * tables 全选/取消操作
 * pagination 跳转交互
 * @Author  chenjie
 *=================================================
*/
$(function(){
	//缓存DOM
	var $ckbs=$(".wxtables").find("input[type='checkbox'].table-ckbs");

	//全选
	$(".btn_table_selectAll").click(function(){
		$ckbs.attr("checked",true);
	});

	//取消全选
	$(".btn_table_Cancle").click(function(){
		$ckbs.attr("checked",false);
	});

	//分页
	$(".paginate").each(function(){
		var $self=$(this),
			$input=$self.find("input"),
			$btn_goto=$self.find(".goto");

		var url=window.location.href.toString();


		$input.focus(function(){
			$(this).addClass("focus").siblings(".goto").addClass("focus");
		});

		$input.blur(function(){
			if($(this).val()==""){
				$(this).removeClass("focus").siblings(".goto").removeClass("focus");
			}
		});

		//只允许输入数字
		$input.keypress(function(event){
			var key = window.event ? event.keyCode : event.which;

			if(key==13){
				window.location.href=$(this).siblings("a.goto").attr("href");
			}

		    if (key == 8 || key == 46 || key == 37 || key == 39) {
		        return true;
		    }
		    else if ( key < 48 || key > 57 ) {
		        return false;
		    }
		    else return true;
		});

		//拼接
		$input.keyup(function(event){
			var cur_num=$(this).val(),//输入的页数
				arr=url.split("/"),
				arr_len=arr.length,//数组长度
				noP=false,//是否存在P
				noNum=false;//是否有

				if(arr[arr_len-1]==""){
					arr.pop();
					arr_len=arr.length;
					noP=true;
				}

				if(noP || arr[arr_len-2]!="p"){
					arr.push("p");
					arr_len=arr.length;
					noNum=true;
				}

				if(noP || noNum){
					arr[arr_len]=cur_num+".html";
				}
				else{
					arr[arr_len-1]=cur_num+".html";
				}
				
				$btn_goto.attr("href",arr.join("/"));
		});

	});
});