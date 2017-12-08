/*
Modules Tabs
create by chenjie
date 2014-01-20
*/
$(function(){
	//tabs
    $(document).on("click",".tabs .tabs_a",function(){
        var $self=$(this),
            dataOrigin=$self.data("origin"),
            index=0;

        if(!$self.parent().hasClass("wizardstep") && !$self.parent().hasClass("nochange")){
            //当前选项卡获得焦点样式
            $self.addClass('active').siblings('.tabs_a').removeClass('active');
            //当选项卡有data-index属性时触发强制跳转
            if($self.data("index")){
                index=$self.data("index");
                $(".tabs-content[data-origin='"+dataOrigin+"']").find(".tc[data-index='"+index+"']").removeClass('hide').siblings('.tc').addClass('hide');
            }
            else{
                index=$self.index();
                $(".tabs-content[data-origin='"+dataOrigin+"']").find(".tc:eq("+index+")").removeClass('hide').siblings('.tc').addClass('hide');
            }
        }
    });
});