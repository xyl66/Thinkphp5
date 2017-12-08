$(function(){
    //删除
    $(".j-del").click(function(){
        var id =$(this).data("id");
        $.jBox.show({
            title: "提示",
            content:"删除该角色会一起删除角色下的管理员，确认删除吗？",
            btnOK: {
                onBtnClick: function(jbox) {
                    $.jBox.close(jbox);
                    //删除数据
                    $.ajax({
                        url: "/System/del_role",
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
                                HYD.hint("danger", "对不起，删除失败：" + data.msg);
                            }
                            $.jBox.hideloading();
                        }
                    });
                }
            }
        });

        return false;
    });

});