//素材库
$(function(){
	//删除
	$(".j-del").click(function(){
		var id =$(this).data("id");
        var url = $(this).attr("href");

        $.jBox.show({
            title: "提示",
            content: _.template($("#tpl_jbox_simple").html(), {content: "删除后将不可恢复，是否继续？"}),
            btnOK: {
                onBtnClick: function(jbox) {
                    $.jBox.close(jbox);
                    if(url){
                        //删除数据
                        $.ajax({
                            url: url,
                            type: "post",
                            dataType: "json",
                            beforeSend: function() {
                                $.jBox.showloading();
                            },
                            success: function(data) {
                                //console.log(data.status)
                                if (data.status == 1) {
                                    HYD.hint("success", "恭喜您，删除成功！");
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    HYD.hint("danger", "对不起，删除失败：" + data.info);
                                }
                                $.jBox.hideloading();
                            }
                        });
                    }else{
                        //删除数据
                        $.ajax({
                            url: "",
                            type: "post",
                            dataType: "json",
                            data: {
                                "id": id
                            },
                            beforeSend: function() {
                                $.jBox.showloading();
                            },
                            success: function(data) {
                                if (data.status == 1) {
                                    HYD.hint("success", "恭喜您，删除成功！");
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    HYD.hint("danger", "对不起，删除失败：" + data.info);
                                }
                                $.jBox.hideloading();
                            }
                        });
                    }
                }
            }
        });

        return false;
	});
});