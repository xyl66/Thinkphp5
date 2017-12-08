//设置支付宝账号
$(function(){
    var ue = UE.getEditor('js_editor',{
        initialFrameWidth :835,//设置编辑器宽度
        initialFrameHeight:500,//设置编辑器高度
        scaleEnabled:true
        });
    //表单验证
    $("#form1").submit(function(){
        var oTitle=$("input[name='article_title']"),
            oDescription=$("textarea[name='article_description']"),
            // oContent=$("input[name='article_content']"),            
            oGroup=$(".select"),            
            vTitle=$.trim(oTitle.val()),
            vDescription=$.trim(oDescription.val()),
            vContent= ue.getContent(),
            vGroup = oGroup.val(),
            passed=true;
        
        if(vTitle=="" && passed){
            HYD.FormShowError(oTitle,"请输入文章标题");
            passed=false;
        }
        if(vDescription=="" && passed){
            HYD.FormShowError(oDescription,"请输入文章简介");
            passed=false;
        }       
        if(vContent==""  && passed){
            alert("请输入文章内容");
            //HYD.FormShowError(oContent,"请输入文章内容");
            passed=false;
        }       
        if(vGroup<=0 && passed){
            HYD.FormShowError(oGroup,"请选择文章分类");
            passed=false;
        }

        return passed;
    });

});