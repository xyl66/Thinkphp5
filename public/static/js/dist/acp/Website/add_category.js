//设置支付宝账号
$(function(){

    //表单验证
    $("#form1").submit(function(){
        var oCatename=$("input[name='category_name']"),
            oCatesort=$("input[name='category_sort']"),           
            oGroup=$(".select"),
            vCatename=$.trim(oCatename.val()),
            vCatesort=$.trim(oCatesort.val()),            
            vGroup = oGroup.val(),
            passed=true;
        
        if(vCatename=="" && passed){
            HYD.FormShowError(oCatename,"请输入分类名称");
            passed=false;
        }

        if(vCatesort=="" && passed){
            HYD.FormShowError(oCatesort,"请输入序号");
            passed=false;
        }

        // if(vGroup<=0 && passed){
        //     HYD.FormShowError(oGroup,"请选择分组");
        //     passed=false;
        // }

        return passed;
    });

});