/*
 *=================================================
 * alerts 带隐藏类型的警告框
 * @Author  chenjie
 *=================================================
*/
$(function(){
	//给带有.disable-del的.alert标签内部插入隐藏按钮和事件
    $(".alert.disable-del").each(function(){
        var $btndel=$('<a href="javascript:;" class="alert-delete" title="隐藏"><i class="gicon-remove"></i></a>');

        $btndel.click(function(){
            $(this).parent(".alert").fadeOut();
        });

        $(this).append($btndel);
    });
});