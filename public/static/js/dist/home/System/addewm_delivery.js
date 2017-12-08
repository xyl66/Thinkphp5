$(function() {
    function a() {
        $(".J_PrintItem>textarea").each(function() {
            var a = $(this).data("item_config");
            "" != a && (a = JSON.parse(decodeURIComponent(a)), $(this).css({
                "font-weight": a.bold,
                "font-style": a.italic,
                "font-size": a.fontSize,
                "letter-spacing": a.letterSpacing
            }).parent().css({
                display: "block",
                left: a.left,
                top: a.top,
                position: "absolute",
                width: a.width,
                height: a.height
            }), "" != a.value && $(this).val(a.value))
        })
    }
    $.browser.msie && parseInt($.browser.version) < 8 && $("#hack_fixie67").show(),
    $(".textarea-item").each(function() {
        var a = $(this),
        c = a.find("textarea");
        a.resizable({
            start: function() {
                c.focus()
            }
        }),
        a.draggable({
            handle: ".textarea-item-move",
            drag: function() {
                c.focus()
            }
        }),
        a.find(".textarea-item-del").click(function() {
            a.hide(),
            b.slt_opts.val("")
        }),
        c.focus(function() {
            a.addClass("focus")
        }).blur(function() {
            a.removeClass("focus")
        })
    }),
    $(".ckb-font").click(function() {
        $(this).toggleClass("checked")
    });
    var b = b ? b: {};
    b.slt_opts = $("#slt_opts"),
    b.slt_fts = $("#slt_fontsize"),
    b.slt_lts = $("#slt_letterSpacing"),
    b.pos_top = $("#ipt_posTop"),
    b.pos_left = $("#ipt_posLeft"),
    b.ckb_fontbold = $("#ckb_fontbold"),
    b.ckb_fontitalic = $("#ckb_fontitalic"),
    b.slt_opts.change(function() {
        var a = $("#" + $(this).val()),
        b = a.find("textarea");
        a.show(),
        b.focus()
    }),
    b.slt_fts.change(function() {
        var a = b.slt_opts.val();
        $(".textarea-item>textarea[name=" + a + "]").css("fontSize", parseInt($(this).val()))
    }),
    b.slt_lts.change(function() {
        var a = b.slt_opts.val();
        $(".textarea-item>textarea[name=" + a + "]").css("letterSpacing", parseInt($(this).val()))
    }),
    b.pos_top.keyup(function() {
        var a = b.slt_opts.val();
        $(".textarea-item>textarea[name=" + a + "]").parent(".textarea-item").css("top", parseInt($(this).val()))
    }),
    b.pos_left.keyup(function() {
        var a = b.slt_opts.val();
        $(".textarea-item>textarea[name=" + a + "]").parent(".textarea-item").css("left", parseInt($(this).val()))
    }),
    b.ckb_fontbold.click(function() {
        var a = b.slt_opts.val();
        $(this).hasClass("checked") ? $(".textarea-item>textarea[name=" + a + "]").css("fontWeight", "bold") : $(".textarea-item>textarea[name=" + a + "]").css("fontWeight", "normal")
    }),
    b.ckb_fontitalic.click(function() {
        var a = b.slt_opts.val();
        $(this).hasClass("checked") ? $(".textarea-item>textarea[name=" + a + "]").css("fontStyle", "italic") : $(".textarea-item>textarea[name=" + a + "]").css("fontStyle", "normal")
    }),
    $(".textarea-item>textarea").focus(function() {
        var a = $(this);
        b.updateSetting({
            name: a.attr("name"),
            fontSize: parseInt(a.css("fontSize")),
            letterSpacing: parseInt(a.css("letterSpacing")),
            left: parseInt(a.parent(".textarea-item").css("left")),
            top: parseInt(a.parent(".textarea-item").css("top")),
            bold: a.css("fontWeight"),
            italic: a.css("fontStyle")
        })
    }),
    $("#btn_confirm").click(function() {
	
	   
	
        return "" == $("#J_ImgUrl").val() ? 
		(HYD.hint("warning", "请先上传二维码底图!"), !1)
		: ($(".textarea-item").each(function() {
            var a = $(this);
            if (a.is(":visible")) {
                var b = a.attr("id"),
                c = a.find("textarea[name=" + b + "]"),
                d = {
                    id: b,
                    width: a.css("width"),
                    height: a.css("height"),
                    top: a.css("top"),
                    left: a.css("left"),
                    fontSize: c.css("fontSize"),
                    letterSpacing: c.css("letterSpacing"),
                    bold: c.css("fontWeight"),
                    italic: c.css("fontStyle"),
                    value: c.val() != c.data("tip_value") ? c.val() : ""
                };
                $("#J_FormExpress").append('<input type="hidden" name="print_items_params[]" value="' + encodeURI(JSON.stringify(d)) + '" />')
            }
        }), $("#J_FormExpress").submit(), !1)
    }),
    b.updateSetting = function(a) {
        if (a) for (var b in a) {
            var c = a[b];
            switch (b) {
            case "name":
                this.slt_opts.val(c);
                break;
            case "fontSize":
                this.slt_fts.val(c);
                break;
            case "letterSpacing":
                this.slt_lts.val(c);
                break;
            case "left":
                this.pos_left.val(c);
                break;
            case "top":
                this.pos_top.val(c);
                break;
            case "bold":
                400 == parseInt(c) || "normal" == c ? this.ckb_fontbold.removeClass("checked") : this.ckb_fontbold.addClass("checked");
                break;
            case "italic":
                "italic" == c ? this.ckb_fontitalic.addClass("checked") : this.ckb_fontitalic.removeClass("checked")
            }
        }
    };
    var c = $("#J_ExpressBG");
    $(function() {
        var b = $("#J_ImgUrl").val();
        "" != b && (c.attr("src", b), c.prop("complete") ? (c.removeClass("default-height"), a()) : c.on("load",
        function() {
            $(this).removeClass("default-height"),
            a()
        }).on("error",
        function() {})),
        $("#J_ShippingCompanyId").on("change",
        function() {
            "" == $("#J_PrintTempName").prop("defaultValue") && $("#J_PrintTempName").val(0 != $(this).children(":selected").index() ? $(this).children(":selected").text() : null)
        }),
        $("#J_FormExpress").submit(function() {
            var a = $("input[name=print_temp_name]").val();
            if (!a) return HYD.hint("danger", "模板名称不能为空！"),
            $("input[name=print_temp_name]").focus(),
            !1;
            
            var c = $("#printing_paper_width").val();
            if (!c) return HYD.hint("danger", "请输入二维码长度！"),
            $("#printing_paper_width").focus(),
            !1;
            var d = $("#printing_paper_height").val();
            return d ? void 0 : (HYD.hint("danger", "请输入二维码宽度！"), $("#printing_paper_height").focus(), !1)
        })
    });
    var d = function() {
        $.albums({
            test: 1,
            callback: function(a) {
                $("#J_ImgUrl").val(a),
                c.attr("src", a),
                c.on("load",
                function() {
                    $(this).fadeIn().parent().removeClass("default-height")
                }).on("error",
                function() {})
            }
        })
    };
    $("#j-selectImgs").click(d)
});