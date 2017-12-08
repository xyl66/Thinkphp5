$(function() {
    var e = {
        1: {
            page: {
                title: "会员主页"
            },
            PModules: [{
                id: 1,
                type: "UserCenter",
                draggable: !1,
                sort: 0,
                content: {
                    showLevel: !0,
                    showPoint: !0,
                    showID: !0,
                    showCoupon: !0,
                    showRecharge: !0,
                    showWithdraw: !0,
                    showCollection: !0,
                    showModify: !0,
                    showPrivilege: !0,
                    showInteraction: !0,
                    showMyFriend: !0,
                    show_fxSubject: !0,
                    show_fxProduct: !0,
                    show_myAgent: !0,
                    show_myUser: !0,
                    show_fxOrder: !0,
                    show_myCommission: !0,
                    show_monthRank: !0,
                    show_highLevel: !0,
                    show_myStore: !0,
                    show_qrcode: !0,
                    agent_qrcode: !0
                }
            }],
            LModules: []
        }
    };
    HYD.DIY.Unit.event_typeUserCenter = function(e, n) {
        var t = n.dom_conitem,
            o = e,
            i = $("#tpl_diy_con_typeUserCenter").html(),
            s = $("#tpl_diy_ctrl_typeUserCenter").html(),
            a = function() {
                var e = $(_.template(i, n));
                t.find(".membersbox").remove().end().append(e);
                var a = $(_.template(s, n));
                o.empty().append(a), HYD.DIY.Unit.event_typeUserCenter(o, n)
            };
        o.find("input[name='showLevel'],input[name='showPoint'],input[name='showID'],input[name='showCoupon'],input[name='showRecharge'],input[name='showWithdraw'],input[name='showMyOrder'],input[name='showCollection'],input[name='showModify'],input[name='showPrivilege'],input[name='showInteraction'],input[name='showMyFriend'],input[name='show_fxSubject'],input[name='show_fxProduct'],input[name='show_myAgent'],input[name='show_myUser'],input[name='show_fxOrder'],input[name='show_myCommission'],input[name='show_monthRank'],input[name='show_highLevel'],input[name='show_myStore'],input[name='show_qrcode'],input[name='agent_qrcode']").change(function() {
            var e = $(this).attr("name"),
                t = $(this).is(":checked");
            n.content[e] = t, a()
        }), o.find(".j-title-selectimg").click(function() {
            HYD.popbox.ImgPicker(function(e) {
                n.content.pic = e[0], a()
            })
        })
    };
    var n = $("#j-initdata").val(),
        t = $("#j-pageID").val();
    n = n.length ? $.parseJSON(n) : e[t], $(".j-pagetitle").text(n.page.title), $(".j-pagetitle-ipt").val(n.page.title), _.each(n.PModules, function(e, n) {
        var t = 0 == n ? !0 : !1;
        HYD.DIY.add(e, t)
    }), _.each(n.LModules, function(e) {
        HYD.DIY.add(e, !0)
    }), $("#j-savePage").click(function() {
        return HYD.DIY.Unit.verify() ? ($.ajax({
            url: window.location.href,
            type: "post",
            dataType: "json",
            data: {
                content: JSON.stringify(HYD.DIY.Unit.getData()),
                id: t,
                is_preview: 0
            },
            beforeSend: function() {
                $.jBox.showloading()
            },
            success: function(e) {
                1 == e.status ? HYD.hint("success", "恭喜您，保存成功！") : HYD.hint("danger", "对不起，保存失败：" + e.msg), $.jBox.hideloading()
            }
        }), !1) : void 0
    })
});