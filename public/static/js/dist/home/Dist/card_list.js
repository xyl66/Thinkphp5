$(function() {
    $(document).on("click", ".j-qrcode",
    function(a) {
        var b = $(this).data("url");
        HYD.showQrcode("/Public/qrcode?link=" + b)
    }),
    $(".BtnAddClass").click(function() {
        $.jBox.show({
            title: "创建充值卡",
            content: _.template($("#tpl_add_class").html()),
            btnOK: {
                onBtnClick: function(a) {
                    var b = a.find("input[name='title']"),
                    c = $.trim(b.val()),
					
                    d = parseInt($('input[name=stat]').val()),
                    e = parseInt($('input[name=enddate]').val()),
					 
                    f = $('input[name=serial]').val(),
                    g = $('input[name=price]').val();
					
					if(!c) {
                     HYD.hint("danger", "对不起，充值卡名称不能为空！");
                     $('input[name=title]').focus();
                     return false;
                    }
					
					if(!d) {
                     HYD.hint("danger", "对不起，起始卡号不能为空！");
                     $('input[name=stat]').focus();
                     return false;
                    }
					
					if(!e) {
                     HYD.hint("danger", "对不起，结束卡号不能为空！");
                     $('input[name=enddate]').focus();
                     return false;
                    }
					
					if(d<0) {
                     HYD.hint("danger", "对不起，起始卡号不能小于零！");
                     $('input[name=stat]').focus();
                     return false;
                    }
					
					if(d>=e){
                     HYD.hint("danger", "对不起，起始卡号不能大于等于结束卡号！");
                     $('input[name=stat]').focus();
                     return false;
                    }
					
					if(!g) {
                     HYD.hint("danger", "对不起，充值卡面值不能为空！");
                     $('input[name=price]').focus();
                     return false;
                    }
					
					if(g<0) {
                     HYD.hint("danger", "对不起，充值卡面值不能小于零！");
                     $('input[name=price]').focus();
                     return false;
                    }
                   // return "" == c ? (HYD.FormShowError(b, "充值卡名称不能为空"), !1) : 
					($.jBox.close(a), void $.ajax({
                        url: "/Dist/ajaxAddCard",
                        type: "post",
                        dataType: "json",
                        beforeSend: function() {
                            $.jBox.showloading()
                        },
                        data: {
                            card_name: c,
                            stat: d,
                            enddate: e,
                            serial: f,
                            price: g
                        },
                        success: function(a) {
                            1 == a.status ? (HYD.hint("success", "恭喜，操作成功" + a.msg), setTimeout(function() {
                                window.location.reload()
                            },
                            1e3)) : HYD.hint("danger", "对不起，操作失败" + a.msg),
                            $.jBox.hideloading()
                        }
                    }))
                }
            }
        })
    }),
    $(document).on("click", ".j-editClass",
    function(a) {
        var b = $(this),
        c = b.data("id"),
        d = {
            title: b.data("title"),
            pid: b.data("pid"),
            img: b.data("img"),
            serial: b.data("serial"),
            link: b.data("link")
        },
        e = _.template($("#tpl_edit_class").html(), d),
        f = $(e);
        $.jBox.show({
            title: "编辑充值卡",
            content: f,
            btnOK: {
                onBtnClick: function(a) {
                    var b = a.find("input[name='title']"),
                    d = $.trim(b.val()),
                    e = a.find("select[name='pid']").val(),
                    f = a.find("input[name='file_path']").val(),
                    g = a.find("input[name='serial']").val(),
                    h = a.find("input[name='link']").val();
                    return "" == d ? (HYD.FormShowError(b, "充值卡名称不能为空"), !1) : ($.jBox.close(a), void $.ajax({
                        url: "/Dist/ajaxEditCard",
                        type: "post",
                        dataType: "json",
                        beforeSend: function() {
                            $.jBox.showloading()
                        },
                        data: {
                            class_name: d,
                            parent_id: e,
                            class_id: c,
                            class_img: f,
                            serial: g,
                            link: h
                        },
                        success: function(a) {
                            1 == a.status ? (HYD.hint("success", "恭喜，操作成功" + a.msg), setTimeout(function() {
                                window.location.reload()
                            },
                            1e3)) : HYD.hint("danger", "对不起，操作失败" + a.msg),
                            $.jBox.hideloading()
                        }
                    }))
                }
            }
        })
    }),
    $(document).on("click", ".j-delClass",
    function(a) {
        var b = $(this),
        c = b.data("id");
        return $.jBox.show({
            title: "删除充值卡",
            content: "确认删除吗？",
            btnOK: {
                onBtnClick: function(a) {
                    $.jBox.close(a),
                    $.ajax({
                        url: "/Dist/ajaxDelCard",
                        type: "post",
                        dataType: "json",
                        data: {
                            id: c
                        },
                        beforeSend: function() {
                            $.jBox.showloading()
                        },
                        success: function(a) {
                            1 == a.status ? (HYD.hint("success", "恭喜您，删除成功！"), setTimeout(function() {
                                window.location.reload()
                            },
                            1e3)) : HYD.hint("danger", "对不起，删除失败：" + a.msg),
                            $.jBox.hideloading()
                        }
                    })
                }
            }
        }),
        !1
    }),
    $(".j-able").click(function() {
        var a = $(this).data("able"),
        b = $(this).data("id");
        if ("disable" == a) var c = "启用";
        else var c = "未启用";
        $.jBox.show({
            title: c + "充值卡",
            content: "确认要" + c + "吗？",
            btnOK: {
                onBtnClick: function(c) {
                    $.jBox.close(c),
                    $.ajax({
                        url: "/Dist/ajaxAbleCard",
                        type: "post",
                        dataType: "json",
                        data: {
                            id: b,
                            able: a
                        },
                        beforeSend: function() {
                            $.jBox.showloading()
                        },
                        success: function(a) {
                            1 == a.status ? (HYD.hint("success", "恭喜您，操作成功！"), setTimeout(function() {
                                window.location.reload()
                            },
                            1e3)) : HYD.hint("danger", "对不起，操作失败：" + a.msg),
                            $.jBox.hideloading()
                        }
                    })
                }
            }
        })
    }),
    $(".btn_table_delAll").click(function() {
        var a = [],
        b = $(".table-ckbs:checked");
        return b.each(function() {
            a.push($(this).data("id"))
        }),
        a.length ? ($.jBox.show({
            title: "批量删除充值卡",
            content: "确认删除吗？",
            btnOK: {
                onBtnClick: function(b) {
                    $.jBox.close(b),
                    $.ajax({
                        url: "/Dist/DelAllCard",
                        type: "post",
                        dataType: "json",
                        data: {
                            ids: a
                        },
                        beforeSend: function() {
                            $.jBox.showloading()
                        },
                        success: function(a) {
                            1 == a.status ? (HYD.hint("success", "恭喜您，删除成功！"), setTimeout(function() {
                                window.location.reload()
                            },
                            1e3)) : HYD.hint("danger", "对不起，删除失败：" + a.msg),
                            $.jBox.hideloading()
                        }
                    })
                }
            }
        }), !1) : void HYD.hint("warning", "对不起，请选择需要删除的充值卡！")
    }),
    $(document).on("click", ".img-list-add",
    function() {
        HYD.popbox.ImgPicker(function(a) {
            var b = '<li><span class="img-list-btndel j-delimg"><i class="gicon-trash white"></i></span><span class="img-list-overlay"></span><img src="' + a[0] + '"></li>';
            $(".img-list").empty().append(b),
            $(".j-imglist-dataset").val(a[0])
        })
    }),
    $(document).on("click", ".j-delimg",
    function() {
        var a = '<li class="img-list-add">+</li>';
        $(".img-list").empty().append(a),
        $(".j-imglist-dataset").val("")
    })
});