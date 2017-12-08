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