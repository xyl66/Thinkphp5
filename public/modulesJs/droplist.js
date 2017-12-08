/*
 * droplist
 * @Author  chenjie
*/
$(function(){
	//显示下拉菜单
	$(document).on("mouseover",".droplist .j-droplist-toggle",function(){
		$(this).siblings(".droplist-menu").show();
	});

	//隐藏下拉菜单
	$(document).on("mouseleave",".droplist .droplist-menu",function(){
		$(this).hide();
	});

	//隐藏下拉菜单
	$(document).on("mouseleave",".droplist",function(){
		$(this).find(".droplist-menu").hide();
	});

	//选中
	$(document).on("click",".droplist .droplist-menu a",function(){
		// var $menu=$(this).parents(".droplist-menu"),
		// 	$showtxt=$menu.siblings(".j-droplist-toggle").find("span");

		// $showtxt.text($(this).text());
		// $menu.hide();
		$(this).parents(".droplist-menu").hide();
	});
});