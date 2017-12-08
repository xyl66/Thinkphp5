$(function() {
    $(".j-import").click(function() {
        var a = $(this).data("shopid");
        HYD.ajaxPopTable({
            title: "选择要导入的商品",
            width: 1e3,
            minHeight: 400,
            url: "/Item/import_rrdshop_list",
            data: {
                id: a
            },
            tpl: $("#tpl_item_importTable").html(),
            onPageChange: function(b, c) {
                b.find(".j-selectAll").click(function() {
                    b.find(".j-chkbox").attr("checked", !0)
                }),
                b.find(".j-lowestPrice").change(function() {
                    var a = $(this).val(),
                    b = $(this).data("index");
                    datatotal = data.totalRows,
                    c.data.dataset[b].lowestPrice = a
                }),
                b.find(".j-import").click(function() {
                    var d = [];
                    b.find(".j-chkbox:checked").each(function() {
                        var a = $(this).data("index");
                        d.push(c.data.dataset[a])
                    }),
                    $.ajax({
                        url: "/Item/import_rrdshop",
                        type: "post",
                        dataType: "json",
                        data: {
                            goods: d,
                            shop_id: a
                        },
                        beforeSend: function() {
                            $.jBox.showloading()
                        },
                        success: function(a) {
                            $.jBox.close(b),
                            $.jBox.hideloading(),
                            1 == a.status ? HYD.hint("success", "恭喜您，导入成功" + a.data.success + "条，导入失败" + a.data.fail + "条") : HYD.hint("danger", "对不起，导入失败：" + a.msg)
                        }
                    })
                })
            }
        })
    }),
    $(document).on("click", ".j-goodsList-import-all",
    function() {
        $.jBox.show({
            title: "提示",
            content: _.template($("#tpl_jbox_simple").html(), {
                content: "导入时间可能过长, 请耐心等待, 导入期间请勿刷新页面！是否执行此操作?"
            }),
            btnOK: {
                onBtnClick: function(a) {
                    $.jBox.close(a);
                    var b=$("#sid").val(), c = $("#totalRows").val(); 
                    $.ajax({
                        url: "/Item/import_rrdshop_all",
                        type: "post",
                        dataType: "json",
                        data: {
                            id: b,
                            total_num: c
                        },
                        beforeSend: function() {
                            $.jBox.showloading()
                        },
                        success: function(a) {
                            $(".jbox-close").click(),
                            1 == a.status ? HYD.hint("success", "恭喜您，导入成功" + a.data.success + "条，导入失败" + a.data.fail + "条") : HYD.hint("danger", "对不起，导入失败：" + a.msg),
                            $.jBox.hideloading()
                        }
                    })
                }
            }
        })
    })
});