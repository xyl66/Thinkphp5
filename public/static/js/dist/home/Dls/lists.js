$(function() {
	$(".j-dls").click(function() {
		var n = $(this),
			e = n.data("id"),
			t = {
				name: n.data("name"),
				mobile: n.data("mobile")
			},
			a = _.template($("#tpl_set_dls").html(), t),
			i = $(a),
			o = i.find("input[name='one']"),
			d = i.find("input[name='min_two']"),
			s = i.find("input[name='max_two']"),
			l = i.find("input[name='min_three']"),
			m = i.find("input[name='max_three']"),
			r = i.find("input[name='mobile']"),
			c = i.find("input[name='password']"),
			u = i.find("input[name='end_time']");
		return i.find('input[name="dls_type"]').change(function() {
			$(".J_region").toggle()
		}), $.jBox.show({
			width: 500,
			title: "设置一级代理商",
			content: i,
			btnOK: {
				onBtnClick: function(n) {
					var t = o.val(),
						a = d.val(),
						_ = s.val(),
						p = l.val(),
						h = m.val(),
						f = r.val(),
						v = c.val(),
						w = i.find("input[name='is_deliver']:checked").val(),
						g = i.find("input[name='dls_type']:checked").val(),
						x = u.val(),
						j = [];
					i.find("input[name='region[]']:checked").each(function(n) {
						j.push($(this).val())
					}), $.jBox.close(n), $.ajax({
						url: "/Dls/setDls",
						type: "post",
						dataType: "json",
						data: {
							user_id: e,
							one: t,
							min_two: a,
							max_two: _,
							min_three: p,
							max_three: h,
							mobile: f,
							password: v,
							end_time: x,
							is_deliver: w,
							dls_type: g,
							region: j
						},
						beforeSend: function() {
							$.jBox.showloading()
						},
						success: function(n) {
							1 == n.status ? (HYD.hint("success", "恭喜您，设置成功！"), setTimeout(function() {
								window.location.reload()
							}, 1e3)) : HYD.hint("danger", "对不起，设置失败：" + n.msg), $.jBox.hideloading()
						}
					})
				}
			}
		}), !1
	}), $(".j-cancel").click(function() {
		var n = $(this),
			e = n.data("id"),
			t = _.template($("#tpl_jbox_simple").html(), {
				content: "取消资格后将不可恢复，此代理商下面的所有代理商都将取消，是否继续？"
			}),
			a = $(t);
		return $.jBox.show({
			width: 300,
			title: "取消代理商资格",
			content: a,
			btnOK: {
				onBtnClick: function(n) {
					$.jBox.close(n), $.ajax({
						url: "/Dls/cancelDls",
						type: "post",
						dataType: "json",
						data: {
							user_id: e
						},
						beforeSend: function() {
							$.jBox.showloading()
						},
						success: function(n) {
							1 == n.status ? (HYD.hint("success", "恭喜，操作成功！"), setTimeout(function() {
								window.location.reload()
							}, 1e3)) : HYD.hint("danger", "抱歉，操作失败：" + n.msg), $.jBox.hideloading()
						}
					})
				}
			}
		}), !1
	}), $(".j-edit-dls").click(function() {
		var n = $(this),
			e = n.data("id"),
			t = n.data("end_time");
		"--" == t && (t = "");
		var a = {
			name: n.data("name"),
			mobile: n.data("mobile"),
			end_time: t,
			one: n.data("one"),
			min_two: n.data("min_two"),
			max_two: n.data("max_two"),
			min_three: n.data("min_three"),
			max_three: n.data("max_three"),
			is_deliver: n.data("is_deliver"),
			dls_type: n.data("dls_type"),
			region: n.data("region")
		},
			i = _.template($("#tpl_edit_dls").html(), a),
			o = $(i),
			d = o.find("input[name='one']"),
			s = o.find("input[name='min_two']"),
			l = o.find("input[name='max_two']"),
			m = o.find("input[name='min_three']"),
			r = o.find("input[name='max_three']"),
			c = o.find("input[name='mobile']"),
			u = o.find("input[name='password']"),
			p = o.find("input[name='end_time']");
		o.find('input[name="dls_type"]').change(function() {
			$(".J_region").toggle()
		});
		var h = String($(this).data("region"));
		return h = h.split(","), $.jBox.show({
			width: 500,
			title: "编辑代理商",
			content: o,
			onOpen: function() {
				if (0 != h.length) for (var n = 0; n < h.length; n++) {
					var e = h[n];
					o.find('.J_region_item[value="' + e + '"]').attr("checked", !0)
				}
			},
			btnOK: {
				onBtnClick: function(n) {
					var t = d.val(),
						a = s.val(),
						i = l.val(),
						_ = m.val(),
						h = r.val(),
						f = c.val(),
						v = u.val(),
						w = p.val(),
						g = o.find("input[name='dls_type']:checked").val(),
						x = o.find("input[name='is_deliver']:checked").val(),
						j = [];
					o.find("input[name='region[]']:checked").each(function(n) {
						j.push($(this).val())
					}), $.jBox.close(n), $.ajax({
						url: "/Dls/editDls",
						type: "post",
						dataType: "json",
						data: {
							user_id: e,
							one: t,
							min_two: a,
							max_two: i,
							min_three: _,
							max_three: h,
							mobile: f,
							password: v,
							end_time: w,
							is_deliver: x,
							dls_type: g,
							region: j
						},
						beforeSend: function() {
							$.jBox.showloading()
						},
						success: function(n) {
							1 == n.status ? (HYD.hint("success", "恭喜您，设置成功！"), setTimeout(function() {
								window.location.reload()
							}, 1e3)) : HYD.hint("danger", "对不起，设置失败：" + n.msg), $.jBox.hideloading()
						}
					})
				}
			}
		}), !1
	}), $(".j-commission").click(function() {
		var n = $(this),
			e = n.data("id"),
			t = n.data("balance");
		defaults = {
			name: n.data("name"),
			commission: t,
			remark: n.data("remark")
		};
		var a = _.template($("#tpl_user_lists_commission").html(), defaults),
			i = $(a),
			o = i.find("input[name='payment']");
		return $remark = i.find("input[name='remark']"), $.jBox.show({
			width: 500,
			title: "修改佣金",
			content: i,
			btnOK: {
				onBtnClick: function(n) {
					return val_rank = parseFloat(o.val()), val_remark = $remark.val(), isNaN(val_rank) ? void HYD.FormShowError(o, "请输入合法金额" + val_remark) : ($.jBox.close(n), void $.ajax({
						url: "/User/updateCommission",
						type: "post",
						dataType: "json",
						data: {
							user_id: e,
							payment: val_rank,
							remark: val_remark
						},
						beforeSend: function() {
							$.jBox.showloading()
						},
						success: function(n) {
							1 == n.status ? (HYD.hint("success", "恭喜您，设置成功！"), setTimeout(function() {
								window.location.reload()
							}, 1e3)) : HYD.hint("danger", "对不起，设置失败：" + n.msg), $.jBox.hideloading()
						}
					}))
				}
			}
		}), !1
	}), _QV_ = "%E6%9D%AD%E5%B7%9E%E5%90%AF%E5%8D%9A%E7%A7%91%E6%8A%80%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8%E7%89%88%E6%9D%83%E6%89%80%E6%9C%89"
});