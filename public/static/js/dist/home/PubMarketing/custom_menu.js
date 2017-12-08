$(function(){

	if(initData.button && initData.button.length > 0){
		var str01="";
		for (var i = 0; i < initData.button.length; i++) {
			str01+= '<dl class="inner_menu jsMemu disabled">'+
					'<dt class="inner_menu_item jslevel1" data-id="'+i+'">'+
					'<a href="javascript:;" class="inner_menu_link">'+
					'<strong>'+initData.button[i].name+'</strong></a>'+
					'<span class="menu_opr"><a href="javascript:;" class="icon14_common jsAddBt"></a>'+
					'<a href="javascript:;" class="icon14_common jsEditBt"></a>'+
					'<a href="javascript:;" class="icon14_common jsDelBt"></a>'+
					'<a href="javascript:;" class="icon14_common jsSortup"></a>'+
					'<a href="javascript:;" class="icon14_common jsSortdown"></a></span></dt>';
			if(initData.button[i].sub_button){
				for (var j = 0; j < initData.button[i].sub_button.length; j++) {
					var k=1+j.toString();
					str01+='<dd class="inner_menu_item jslevel2" data-id="'+k+'">'+
					'<i class="icon_dot">●</i><a href="javascript:void(0);" class="inner_menu_link">'+
					'<strong>'+initData.button[i].sub_button[j].name+'</strong></a>'+
					'<span class="menu_opr"><a href="javascript:;" class="icon14_common edit_gray jsSubEditBt"></a>'+
					'<a href ="javascript:;" class="icon14_common del_gray jsSubDelBt"></a>'+
					'<a href="javascript:;" class="icon14_common jsSortup"></a>'+
					'<a href="javascript:;" class="icon14_common jsSortdown"></a></span></dd>';
				};
			}
			str01+='</dl>';
		};
		$("#menuList").append(str01);
	};
	var indexCur, indexCh, curData;
	function findData(indexCur,subid){
		indexCh = subid;
		if(!initData.button[indexCur]){
			initData.button[indexCur]={};
		}

		if(!initData.button[indexCur].sub_button){
			initData.button[indexCur].sub_button =[] ;
		}
		if(!initData.button[indexCur].sub_button[subid]){
			initData.button[indexCur].sub_button[subid] = {};
		}
		return curData = initData.button[indexCur].sub_button[subid];
	}
	function findSingleData(id){
		indexCur = id;
		if(!initData.button[indexCur]){
			initData.button[indexCur] = {};
		}
		return curData = initData.button[indexCur];
	}
	// 传文本ID
	function createTextId(content,id,callback){

        if(id){
            textUrl = '/PubMarketing/editContent';
        }else{
            textUrl = '/PubMarketing/addContent';
        }
        id = id || "";
        content = content || "";

        $.post(textUrl, {
            "content":content,
            "id"     : id
        }, function(data){
            callback(data.info);
        })
    }
	// 菜单管理--添加一级菜单
	$(document).on("click","#addBt",function(){
		// 确认现有一级菜单的个数
		var len=$("#menuList").children().length;
        if(!initData.button){
            initData.button = [];
        }
		if(!initData.button[len]){
			initData.button[len]={
	            "name": ""
			}
		}
		if(len < 3){
			var input=$("#menu_notes").show();
			// 清空input现有的值
			$("#add_val").val("");
			 $.jBox.show({
	            title: "输入提示框",
	            content: input,
	            btnOK: {
	            	show:true,
	            	onBtnClick:function(jbox){
	            		var N_value=$("#add_val").val();
	            		var N_test=N_value.trim(); 
	            		var length=$("#menuList").children().length;
	            		initData.button[length]['name']=N_value;
	            		var len=N_value.length;
	            		if(N_test!=""&&len<=8){
	            			// 创建插入元素
							var str_01='<dl class="inner_menu jsMemu disabled">'+
									   '<dt class="inner_menu_item jslevel1" data-id="'+length+'">'+
									   '<a href="javascript:;" class="inner_menu_link">'+
									   '<strong>',
								str_02='</strong></a>'+
									   '<span class="menu_opr">'+
									   '<a href="javascript:;" class="icon14_common jsAddBt"></a>'+
									   '<a href="javascript:;" class="icon14_common jsEditBt"></a>'+
									   '<a href="javascript:;" class="icon14_common jsDelBt"></a>'+
									   '<a href="javascript:;" class="icon14_common jsSortup"></a>'+
									   '<a href="javascript:;" class="icon14_common jsSortdown"></a></span></dt></dl>';
							// 拼接输入值并创建一级菜单
		            		var menu_name=str_01+N_value+str_02;
		            		$("#menuList").append(menu_name);
		            		$.jBox.close(jbox);
		            		$("#menu_notes").hide();  //确定后还原
	            		}else{
	            			HYD.hint("danger", "对不起，菜单名不能为空！" );
	            		};
	            		setVal();
	            	}
	            },
	            btnCancel:{show:true},
	            onOpen:function(){

	            }
	        });
		} else{
			HYD.hint("danger", "可创建最多3个一级菜单" );
		}
	});
	// 添加二级菜单
	$(document).on("click",".jsAddBt",function(){
		// 缓存父级元素
		var $parent=$(this).parents(".inner_menu");
		// 确认现有二级菜单的个数
		var len=$(this).parents(".inner_menu").children('dd').length;
		var indexID=$parent.index();
	    var length=$parent.children('dd').length;
		if(!initData.button){
			initData.button=[];
		}
		if(!initData.button[indexID] ){
			initData.button[indexID]={
				name:''
			}
		}
		if(!initData.button[indexID].sub_button){
			initData.button[indexID].sub_button=[];
		};
		if(!initData.button[indexID].sub_button[length]){
			initData.button[indexID].sub_button[length]= {
				name:""
			};
		}
		if(!initData.button[indexID].sub_button[length]){
			initData.button[indexID].sub_button[length]={
                name: ""
			}
		};
		if(len<5){
			var input=$("#menu_notes").show();
			// 清空input现有的值
			$("#add_val").val("");
			$.jBox.show({
	            title: "输入提示框",
	            content: input,
	            btnOK: {
	            	show:true,
	            	onBtnClick:function(jbox){
	            		var N_value=$("#add_val").val();
	            		var N_test=N_value.trim();
	            		initData.button[indexID].sub_button[length].name=N_value;
	            		var len=N_value.length;
	            		if(N_test!=""&&len<=16){
	            			// 创建插入元素
							var str_01='<dd class="inner_menu_item jslevel2" data-id="'+1+length+'">'+
									   '<i class="icon_dot">●</i>'+
									   '<a href="javascript:void(0);" class="inner_menu_link">'+
									   '<strong>',
								str_02='</strong></a>'+
									   '<span class="menu_opr">'+
									   '<a href="javascript:;" class="icon14_common edit_gray jsSubEditBt"></a>'+
									   '<a href="javascript:;" class="icon14_common del_gray jsSubDelBt"></a>'+
									   '<a href="javascript:;" class="icon14_common jsSortup"></a>'+
									   '<a href="javascript:;" class="icon14_common jsSortdown"></a></span></dd>';
							// 拼接输入值并创建一级菜单
		            		var menu_name=str_01+N_value+str_02;
		            		$parent.append(menu_name);
		            		$.jBox.close(jbox);
		            		$("#menu_notes").hide();  //确定后还原
	            		}else{
	            			HYD.hint("danger", "对不起，菜单名不能为空！" );
	            		};
	            		setVal();
	            	}
	            },
	            btnCancel:{show:true},
	            onOpen:function(){

	            }
	        });
		}else{
			HYD.hint("danger", "可创建最多5个二级菜单" );
		};
		return false;
	})
	// 菜单编辑功能
	$(document).on("click",".jsEditBt,.jsSubEditBt",function(){
		// 缓存父级元素
		var $parent=$(this).parents(".inner_menu_item");
		// 获取编辑内容
		var text=$parent.find('strong').text();
		// 将内容追加到编辑框中
		$("#menu_notes").find('#add_val').val(text);
		var input=$("#menu_notes").show();

		// clearDate(curData);
		var indexID=$(this).parents("dd.inner_menu_item").index()-1;
		var parentID=$(this).parents("dl.jsMemu").index();
		if(indexID >= 0){
			findData(parentID,indexID);
		}else{
			findSingleData(parentID)
		}

		$.jBox.show({
            title: "输入提示框",
            content: input,
            btnOK: {
            	show:true,
            	onBtnClick:function(jbox){
            		var N_value=$("#add_val").val();
            		var N_test=N_value.trim();
            		var len=N_value.length;
            		if(N_test!=""&&len<=8){
            			$parent.find('strong').text(N_value);
	            		$.jBox.close(jbox);
	            		$("#menu_notes").hide();  //确定后还原
	            		curData.name=N_value;
            		}else{
            			HYD.hint("danger", "对不起，菜单名不能为空！" );
            		};
            	}
            },
            btnCancel:{
            	show:true,
            	onBtnClick:function(jbox){
            		$.jBox.close(jbox);
	            	$("#menu_notes").hide();  //确定后还原
            	}
            },
            onOpen:function(){

            }
        });
	});
	// 删除菜单
	$(document).on("click",".jsDelBt,.jsSubDelBt",function(){
		var $parent=$(this).parent().parent();
		if(!$parent.hasClass('jslevel2')){
			var sID=$parent.parent("dl").index();
		}else {
			var pID=$parent.parent("dl").index();
			var sID=$parent.index()-1;
		};
		if($parent.closest('.inner_menu_box').children('dl.inner_menu').length==1 && !$parent.hasClass('jslevel2')){
			HYD.hint("danger", "自定义菜单不可少于1");
			return false;
		}
		$.jBox.show({
            title: "温馨提示",
            content: "删除后该菜单下设置的消息将不会被保存",
            btnOK: {
            	show:true,
            	onBtnClick:function(jbox){
            		if($parent.hasClass('jslevel2')){
						$parent.remove();
						initData.button[pID].sub_button.splice(sID,1);
						//console.log(initData)
					}else{
						$parent.parent(".inner_menu").remove();
						initData.button.splice(sID,1);
					};
					$("#index").hide();
					$("#none").children('p.action_tips').text('你可以先添加一个菜单，然后开始为其设置响应动作');
					$.jBox.close(jbox);
					// console.log(initData)
            	}

            },
            btnCancel:{
            	show:true,
            	onBtnClick:function(jbox){
            		$.jBox.close(jbox);
            	}
            },
            onOpen:function(){

            }
        });
        return false;
	});
	// 点击一级菜单设置动作
	$(document).on("click",".jslevel1",function(){
		var $parent=$(this).parent("dl");
		// 检测是否有二级菜单
		var len=$parent.children('dd').length;
		var curID=$(this).parent("dl").index();
		findSingleData(curID);
		if(len>0){
			$("#index").hide();
			$("#none").children('p.action_tips').text('已有子菜单，无法设置动作').end().show();
			$("#url").hide();
			$("#edit").hide();
			$(this).addClass('selected').siblings('dd').removeClass('selected').end().parent("dl").siblings('dl').find('dt,dd').removeClass('selected');
		
		} else{
			$("#none").hide();
			$("#index").show();
			$("#url").hide();
			$("#edit").hide();
			$(this).addClass('selected').parent().siblings('dl').find('dt,dd').removeClass('selected');
			$(".tab_content:not(:first)>.inner").empty();
			setData(curData);
		}
	});
	// 清掉所有数据
	function clearDate(data){
		if(data.content){
			delete data.content;
		};
		if(data.url){
			delete data.url;
		};
		if(data.new){
			delete data.new;
		};
		if(data.news){
			delete data.news;
		};
		if(data.image){
			delete data.image;
		};
		if(data.voice){
			delete data.voice;
		};
		if(data.video){
			delete data.video;
		};
		if(data.card){
			delete data.card;
		}
	}
	// 点击二级菜单设置动作
	$(document).on("click",".jslevel2",function(){
		var parentID=$(this).parent("dl").index();
		var indexID=$(this).index()-1;
		findData(parentID,indexID);
		$("#none").hide();
		$("#index").show();
		$("#url").hide();
		$("#edit").hide();
		$(this).addClass('selected').siblings('.jslevel2,.jslevel1').removeClass('selected').end().parent().siblings('dl').find('dt,dd').removeClass('selected');
		$(".tab_content:not(:first)>.inner").empty();
		setData(curData);
		console.log(initData)
	});
	// 数据交换
	var changeData = function(str,curindex,origindex,parentindex){
		if(str == 1){
			var temp = initData.button[curindex];
			initData.button[curindex] = initData.button[origindex];
			initData.button[origindex] = temp;
		}else{
			var temp = initData.button[parentindex].sub_button[curindex];
			initData.button[parentindex].sub_button[curindex] = initData.button[parentindex].sub_button[origindex];
			initData.button[parentindex].sub_button[origindex] = temp;
		}
		temp = null
	}
	// 菜单排序 上移[ jsSortup ] 
	$(document).on('click', '.jsSortup', function(event) {
		// 检测是否为一级
		var me = $(this);
		var checkFloor = me.parent().parent().hasClass('jslevel1');
		if(checkFloor){
			// 一级菜单上移
			var dlindex = me.closest('dl').index();
			if(dlindex == 0){
				HYD.hint("danger", "已是第一位，不可上移" );
			}else{
				var curindex = dlindex - 1; // 获取前一位索引
				// var temp = initData.button[curindex]; // 创建临时数据存放前一位数据

				// initData.button[curindex] = initData.button[dlindex]; // 这里开始数据交换
				// initData.button[dlindex] = temp; // 这里数据交换结束
				// temp = null;
				changeData(1,curindex,dlindex);
				// dom 交换位置
				var selector = me.closest('dl');
				selector.prev().before(selector);
			}
		}else{
			// 二级菜单上移
			if(me.closest('dd').index() == 1 ){
				HYD.hint("danger", "已是第一位，不可上移" );
			}else{
				var ddindex = me.closest('dd').index() - 1,
					curindex = ddindex - 1,
					pindex = me.closest('dl').index();
				// var temp = initData.button[pindex].sub_button[curindex];
				// initData.button[pindex].sub_button[curindex] = initData.button[pindex].sub_button[ddindex];
				// initData.button[pindex].sub_button[ddindex] = temp;
				// temp = null;
				changeData(2,curindex,ddindex,pindex);
				var selector = me.closest('dd');
				selector.prev().before(selector);

			}
			
		}
		return false;
	}); 
	// 菜单排序 下移[ jsSortdowm ] 
	$(document).on('click', '.jsSortdown', function(event) {
		// 检测是否为一级
		var me = $(this);
		var checkFloor = me.parent().parent().hasClass('jslevel1');
		if(checkFloor){
			// 一级菜单下移
			var dlindex = me.closest('dl').index(),
				len = $('#menuList').children('dl').length - 1;
			if(dlindex == len){
				HYD.hint("danger", "已是最后位，不可下移" );
			}else{
				var curindex = dlindex + 1;
				// var temp = initData.button[curindex];
				// initData.button[curindex] = initData.button[dlindex];
				// initData.button[dlindex] = temp;
				// temp = null;
				changeData(1,curindex,dlindex);
				var selector = me.closest('dl');
				selector.next().after(selector);

			}
		}else{
			// 二级菜单下移
			var sublen = me.closest('dl').children().length - 2;
			var ddindex = me.closest('dd').index() - 1;
			if(ddindex == sublen){
				HYD.hint("danger", "已是最后位，不可下移" );
			}else{
				var curindex = ddindex + 1,
					pindex = me.closest('dl').index();
				// var temp = initData.button[pindex].sub_button[curindex];
				// initData.button[pindex].sub_button[curindex] = initData.button[pindex].sub_button[ddindex];
				// initData.button[pindex].sub_button[ddindex] = temp;
				// temp = null;
				changeData(2,curindex,ddindex,pindex);
				var selector = me.closest('dd');
				selector.next().after(selector);
			}
		};
		return false;
	}); 

	// 点击发送信息出现相应页面
	$(document).on("click","#sendMsg",function(){
		$("#none").hide();
		$("#index").hide();
		$("#url").hide();
		$("#edit").show().find('.tab_content:first').addClass('selected').siblings('.tab_content').removeClass('selected');
	});
	// 设置动作信息保存
	$("#editSave").click(function(){
		curData.type="click";

		var curID=$(".tab_panel>.selected").index();
		clearDate(curData)
		//console.log(curData)
		curData.key="";
		if(curID==0){
			$.jBox.showloading();
			//valText=$("#js_editorArea").html();
			//alert(curData.key)
			    /*valText = UE.getEditor('js_editorArea').getContent(),
				valText = $("#edui1_iframeholder").html(),*/
				var valText = UE.getEditor('js_editorArea').getContent();
				console.log(valText);
				
				createTextId(valText, curData.key.substring(9), function(id){
				$.jBox.hideloading();
				curData.key = "KEY_TEXT_" + id;
				curData.content = valText;
				// console.log(curData);
				$.jBox.show({
	            title: "提示",
	            content: "内容保存成功",
	            btnOK: {show:true,onBtnClick:function(jbox){
	            	$.jBox.close(jbox);
	            }},
	            btnCancel:{show:false},
	            onOpen:function(jbox){
	            }
	        });
			});

		} else if(curID == 1){
			var single_title=$(".tab_content.selected .single-title").html();
			var single_datetime=$(".tab_content.selected .single-datetime").html();
			var cover_img=$(".tab_content.selected .cover-wrap").children('img').attr('src');
			var single_summary=$(".tab_content.selected .single-summary").html();
			var single_link=$(".tab_content.selected .single-link").attr('href');
			var id=$("#j-initDataID").val();
			curData.new={};
			curData.new.title=single_title;
			curData.new.add_time=single_datetime;
			curData.new.cover_img=cover_img;
			curData.new.summary=single_summary;
			curData.new.redirect=single_link;
			curData.key="KEY_NEW_"+id;
			$.jBox.show({
	            title: "提示",
	            content: "内容保存成功",
	            btnOK: {show:true,onBtnClick:function(jbox){
	            	$.jBox.close(jbox);
	            }},
	            btnCancel:{show:false},
	            onOpen:function(jbox){
					$.jBox.hideloading();
	            }
	        });
		} else if(curID==2){
			var main_title=$("#j-mutil-img .materialPrePanel").children('dt').find('h2').html();
			var main_img=$("#j-mutil-img .materialPrePanel").children('dt').find('img').attr('src');
			var id=$("#j-initDataID").val();
			curData.key="KEY_NEWS_"+id;
			//console.log(curData);
			curData.news=[];
			curData.news[0]={};
			curData.news[0].title=main_title;
			curData.news[0].cover_img=main_img;

			var length=$("#j-mutil-img dd").length;
			for (var i = 1; i <= length; i++) {
				curData.news[i]={};
				var m_title=$("#j-mutil-img dd:eq("+i+")").find('h3').html();
				var m_img=$("#j-mutil-img dd:eq("+i+")").find('img').attr('src');
				curData.news[i].title=m_title;
				curData.news[i].cover_img=m_img;
			};
			$.jBox.show({
	            title: "提示",
	            content: "内容保存成功",
	            btnOK: {show:true,onBtnClick:function(jbox){
	            	$.jBox.close(jbox);
	            }},
	            btnCancel:{show:false},
	            onOpen:function(jbox){
					$.jBox.hideloading();
	            }
	        });
		} else if(curID==3) {
			var id=$("#js_imgOne_img").find('img').data('id'),
				url=$("#js_imgOne_img").find('img').data('src');
			curData.key="KEY_IMAGE_"+id;
			curData.image={file_path:url,file_id:id};
			// console.log(curData)
			$.jBox.show({
	            title: "提示",
	            content: "内容保存成功",
	            btnOK: {show:true,onBtnClick:function(jbox){
	            	$.jBox.close(jbox);
	            }},
	            btnCancel:{show:false},
	            onOpen:function(jbox){
					$.jBox.hideloading();
	            }
	        });
		} else if(curID==4){
			var id=$("#js_audio").find('.audio').data('id'),
				url=$("#js_audio").find('.audio').data('src');
			curData.key="KEY_VOICE_"+id;
			curData.voice={file_id:id,file_path:url};
			// console.log(curData)
			$.jBox.show({
	            title: "提示",
	            content: "内容保存成功",
	            btnOK: {show:true,onBtnClick:function(jbox){
	            	$.jBox.close(jbox);
	            }},
	            btnCancel:{show:false},
	            onOpen:function(jbox){
					$.jBox.hideloading();
	            }
	        });
		}else if(curID==5){
			var id=$("#js_video").find('.video').data('id'),
				url=$("#js_video").find('.video').data('src');
			curData.key="KEY_VIDEO_"+id;
			curData.video={file_id:id,file_path:url};
			$.jBox.show({
	            title: "提示",
	            content: "内容保存成功",
	            btnOK: {show:true,onBtnClick:function(jbox){
	            	$.jBox.close(jbox);
	            }},
	            btnCancel:{show:false},
	            onOpen:function(jbox){
					$.jBox.hideloading();
	            }
	        });
		}else{
			var src=$('#js_card').find('img').attr('src');
			curData.card=src;
			curData.key="KEY_CARD";
			// console.log(curData)
			$.jBox.show({
	            title: "提示",
	            content: "内容保存成功",
	            btnOK: {show:true,onBtnClick:function(jbox){
	            	$.jBox.close(jbox);
	            }},
	            btnCancel:{show:false},
	            onOpen:function(jbox){
					$.jBox.hideloading();
	            }
	        });
		}
		//console.log(initData)
	});
	// 点击切换到填写跳转链接URL
	$(document).on("click","#goPage",function(){
		$("#none").hide();
		$("#index").hide();
		$("#url").show();
		$("#edit").hide();
	})
	// 保存链接URL
	$("#urlSave").click(function() {
		$.jBox.showloading();
		var url=$("#urlText").val();
		clearDate(curData);
		curData.url=url;
		curData.type="view";
		//console.log(initData)
		$.jBox.show({
            title: "提示",
            content: "链接保存成功",
            btnOK: {show:true,onBtnClick:function(jbox){
            	$.jBox.close(jbox);
            }},
            btnCancel:{show:false},
            onOpen:function(jbox){
				$.jBox.hideloading();
            }
        });
	});
	// 切换选择的类型
	var materialId = $("#material_id").val();
	$(".tab_nav").click(function(){
		$(this).addClass('selected').siblings('li.tab_nav').removeClass('selected');
		$(this).find('.j-sendType').attr('checked', true);
		$(this).siblings('li.tab_nav').find('.j-sendType').attr('checked', false);
		if(!$(this).find('.j-sendType').is(":checked"))return;
		var type=$(this).data("type");//选中的类型
        $(".tab_panel>.tab_content[data-type="+type+"]").addClass('selected').siblings(".tab_content").removeClass('selected');
		if(!materialId){
            // 自动打开选择器
            switch(type){
                // case 1:reRenderMaterialPre_Text();break;
                case 2:do_pickerMaterial_Single();break;
                case 3:do_pickerMaterial_Mutil();break;
				case 4:reRenderMaterialPre_One();break;
				case 5:reRenderMaterialPre_Auto();break;
				case 6:reRenderMaterialPre_Video();break;
				case 7:reRenderMaterialPre_Card();break;
            };
        };

        materialId = 0;

	}).change();

	//模板
	var tpl={
		text_pre:$("#tpl_materialPicker_text_pre").html(),//文本预览
		single_table:$("#tpl_materialPicker_single_table").html(),//单图文列表
		single_pre:$("#tpl_materialPicker_single_pre").html(),//单图文预览
		mutil_table:$("#tpl_materialPicker_mutil_table").html(),//多图文列表
		mutil_pre:$("#tpl_materialPicker_mutil_pre").html(),//多图文预览
		menu_tab:$("#tpl_menu_tab").html(), //自定义菜单营销活动列表切换
		menu_ump:$("#tpl_menu_ump").html(), //自定义菜单营销活动列表
		menu_detail:$("#tpl_menu_detail").html(), //自定义菜单产品列表
		menu_group:$("#tpl_menu_group").html(), //自定义菜单产品分组列表
		menu_magazine:$("#tpl_menu_magazine").html(), //自定义菜单专题列表
		menu_sort:$("#tpl_menu_sort").html(), //自定义菜单专题分类列表
		menu_imageOne:$("#tpl_popbox_ImgPicker").html(),//添加单图
		audio:$("#tpl_popbox_Audio").html(), //添加音频
		video:$("#tpl_popbox_Video").html()  //添加视频
	};

	//设置单条、多条图文消息的id到页面的input中
	// var setVal=function(data){
 	//     $("#j-initDataID").val(data.id);
	// };
	//输出数据到页面
	var setVal=function(){
		$("#j-initData").val(JSON.stringify(initData));
	}
	//渲染文本预览视图
	// var reRenderMaterialPre_Text=function(data){
	// 	if(!data.length) return;
	// 	$("#js_editorArea").empty().append(html);//插入dom
	// };

	//渲染单图文预览视图
	var reRenderMaterialPre_Single=function(data){
		var html=_.template(tpl.single_pre,data);//渲染模板
		$("#j-single-img").empty().append(html);//插入dom
		
	};

	//渲染多图文预览视图
	var reRenderMaterialPre_Mutil=function(data){
		var html=_.template(tpl.mutil_pre,data);//渲染模板
		$("#j-mutil-img").empty().append(html);//插入dom
	};
	//渲染插入单张图片视图
	var reRenderMaterialPre_One=function(data){
		var html=_.template(tpl.menu_imageOne,data);//渲染模板
		$("#js_imgOne_img").empty().append(html);//插入dom
	};
	// 渲染插入音频
	var reRenderMaterialAudio=function(data){
		var html=_.template(tpl.audio,data);
		$("#js_audio").empty().append(html);//插入dom
	};
	// 渲染插入视频
	var reRenderMaterialVideo=function(data){
		var html=_.template(tpl.video,data);
		$("#js_video").empty().append(html);//插入dom
	};
    //渲染插入分销名片
   /* var reRenderMaterialCard=function(data){
        var html=_.template(tpl.menu_imageOne,data);//渲染模板
        $("#js_card").empty().append(html);//插入dom
    };*/
	//选择单图文事件
	var do_pickerMaterial_Single=function(){
		HYD.ajaxPopTable({
			title:"选择单条图文",
	        url:"/MaterialOne/jsonList",
	        tpl:tpl.single_table,
	        onPageChange:function(jbox,ajaxdata){
	        	//选择事件
	        	jbox.find(".j-select").click(function(){
	        		var index=$(this).parents("tr").index();//获取索引
	        		reRenderMaterialPre_Single(ajaxdata.list[index]);//渲染对应视图
	        		$.jBox.close(jbox);
                    $("#j-initDataID").val(ajaxdata.list[index].material_one_id);
	        	});

	        }
	    });
	};

	//选择多图文事件
	var do_pickerMaterial_Mutil=function(){
		HYD.ajaxPopTable({
			title:"选择多条图文",
	        url:"/MaterialMore/jsonList",
	        tpl:tpl.mutil_table,
	        onPageChange:function(jbox,ajaxdata){
	        	//选择事件
	        	jbox.find(".j-select").click(function(){
	        		var index=$(this).parents("tr").index();//获取索引
	        		reRenderMaterialPre_Mutil(ajaxdata.list[index]);//渲染对应视图
	        		$.jBox.close(jbox);
	        		//initData.button[indexCur].
                    $("#j-initDataID").val(ajaxdata.list[index].material_more_id);
                });
	        }
	    });
	};
	
	//选择单张图片事件
	var reRenderMaterialPre_One=function(ajaxdata){
		
		//选择事件
		/*
		 * 图片选择器
		 * @Author  chenjie
		 * @param callback 回调函数
		 * @return Array 图片列表数组
		 */
	
		var html = $("#tpl_popbox_ImgPicker").html(),
			$picker = $(html),//选择器
			imglist;//图片列表缓存
	
		/*
		 * ajax 根据页数 获取图片列表数据
		 * @param page 页数
		 */
		var showImgListRender = function(page,callback_showImgListRender) {
			var dorender=function(data){
				imglist=data.list;//图片列表
				// console.log(imglist)
				if(!imglist || !imglist.length){
					$picker.find(".imgpicker-list").append("<p class='txtCenter'>对不起，暂无图片</p>");
				}
				else{
					//渲染模板
					var html = _.template($("#tpl_popbox_ImgPicker_listItem2").html(), {dataset: imglist}),
						$render = $(html);
	
					//绑定选择事件
					$render.filter("li").click(function() {
						$(this).addClass("selected").siblings("li").removeClass("selected");
					});
	
					$picker.find(".imgpicker-list").empty().append($render);//插入dom
	
					//分页符
					var paginate=data.page,
						$render_paginate=$(paginate);
	
					$render_paginate.filter("a:not(.disabled,.cur)").click(function(){
						//获得页数
						var href=$(this).attr("href"),
							page=href.split("/");
						page=page[page.length-1];
						page=page.replace(/.html/,"");
	
						showImgListRender(page);
						return false;
					})
	
					$picker.find(".paginate").empty().append($render_paginate);//插入dom
	
				}
				if(callback_showImgListRender) callback_showImgListRender();//执行回调，传入所有数据
	
			}
	
			//获取数据并渲染
			$.ajax({
				url:"/Design/getMediaList",
				type:"post",
				dataType:"json",
				data:{"p":parseInt(page)},
				// beforeSend:function(){
				//     $.jBox.showloading();
				// },
				success:function(data){
					if (data.status == 1) {
						// console.log(data)
						dorender(data);
					} else {
						HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
					}
					// $.jBox.hideloading();
				}
			});
		};
	
		/*
		 * 初始化上传组件
		 * @param jbox 弹窗对象
		 */
		var initUpload=function(jbox){
			var arrSelected=[];//选中的数据
	
			//上传事件
			$picker.find("#imgpicker_upload_input").uploadify({
				"debug": false,
				"auto": true,
				"formData": {"PHPSESSID": $.cookie("PHPSESSID")},
				"width": 60,
				"height": 60,
				"multi": true,
				'swf': '/Public/plugins/uploadify/uploadify.swf',
				'uploader': '/Design/uploadMedia', //接口名称
				"buttonText": "+",
				"fileSizeLimit": "5MB",
				"fileTypeExts": "*.jpg; *.jpeg; *.png; *.gif; *.bmp",
				'onSelectError': function(file, errorCode, errorMsg) {
					switch (errorCode) {
						case -100:
							HYD.hint("danger", "对不起，系统只允许您一次最多上传10个文件");
							break;
						case -110:
							HYD.hint("danger", "对不起，文件 [" + file.name + "] 大小超出5MB！");
							break;
						case -120:
							HYD.hint("danger", "文件 [" + file.name + "] 大小异常！");
							break;
						case -130:
							HYD.hint("danger", "文件 [" + file.name + "] 类型不正确！");
							break;
					}
				},
				'onFallback': function() {
					HYD.hint("danger", "您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
				},
				'onUploadSuccess': function(file, data, response) {
					var data = $.parseJSON(data),
						tpl = $("#tpl_popbox_ImgPicker_uploadPrvItem2").html(), //获取模板
						$PrvPanel = $picker.find(".imgpicker-upload-preview"); //获取预览图容器
	
					var url=data.file_path;//图片路径
					var id=data.file_id; //图片id
					arrSelected.push(url);//将数据插入数组
					
					var html = _.template(tpl, {url: url,id:id}), //渲染模板
					$render = $(html);
	
					//删除图片事件
					$render.find(".j-imgpicker-upload-btndel").click(function() {
						var index=$picker.find(".imgpicker-upload-preview li").index($(this).parent("li"));
						//移除dom并从数组中删除
						$render.fadeOut(300, function() {
							arrSelected.splice(index,1);
							$(this).remove();
						});
					});
	
					$PrvPanel.append($render); //插入文档
				},
				onUploadError: function(file, errorCode, errorMsg, errorString) {
					HYD.hint("danger", "对不起：" + file.name + "上传失败：" + errorString);
				}
			});
	
			//使用上传图片按钮事件
			$picker.find("#j-btn-uploaduse").click(function(){
				if(arrSelected.length==0){
					HYD.hint("danger", "对不起，您没有选择图片：" + data.msg);
				}else{
					var img='<img src="'+arrSelected[0]+'" width="100" height="100" />',
						id=$(this).data("id");
					$("#js_imgOne_img").html(img);
					$("#j-initDataID").val(id);
				}
				$.jBox.close(jbox);
			});
		};
	
		//打开图片选择器窗口，并渲染第一页数据
		showImgListRender(1,function(){
			$.jBox.show({
				title: "选择图片",
				content: $picker,
				btnOK: {show: false},
				btnCancel: {show: false},
				onOpen:function(jbox){
					var $btnuse=$picker.find("#j-btn-listuse");//使用选中图片按钮
	
					//使用选中图片事件
					$btnuse.click(function(){
						var arrSelected=[];//选中的数据
						var arrSelectedId=[]
						//将选中的图片数据推入数组
						$picker.find(".imgpicker-list li.selected").each(function(){
							arrSelected.push(imglist[$(this).index()].file_path);
							arrSelectedId.push(imglist[$(this).index()].file_id);
						});
						if(arrSelected.length==0){
							HYD.hint("danger", "对不起，您没有选择图片：" + data.msg);
						}else{
							var img='<img src="'+arrSelected[0]+'" width="100" height="100" data-id="'+arrSelectedId[0]+'" />'
							$("#js_imgOne_img").html(img)
						}
	
						$.jBox.close(jbox);
					});
	
					//切换到上传图片选项卡，初始化上传组件
					$picker.find(".j-initupload").one("click",function(){
						initUpload(jbox);
					});
				}
			});
		});
	};
	//选择音频事件
	var reRenderMaterialPre_Auto=function(ajaxdata){
		
		//选择事件
		/*
		 * 图片选择器
		 * @Author  chenjie
		 * @param callback 回调函数
		 * @return Array 图片列表数组
		 */
	
		var html = $("#tpl_popbox_Audio").html(),
			$picker = $(html),//选择器
			imglist;//图片列表缓存
	
		/*
		 * ajax 根据页数 获取图片列表数据
		 * @param page 页数
		 */
		var showImgListRender = function(page,callback_showImgListRender) {
			var dorender=function(data){
				imglist=data.list;//音频列表
				// console.log(imglist)
				if(!imglist || !imglist.length){
					$picker.find(".imgpicker-list").append("<p class='txtCenter'>对不起，暂无音频</p>");
				}
				else{
					//渲染模板
					var html = _.template($("#tpl_popbox_ImgPicker_audio").html(), {dataset: imglist}),
						$render = $(html);
	
					//绑定选择事件
					$render.filter("li").click(function() {
						$(this).addClass("selected").siblings("li").removeClass("selected");
					});
	
					$picker.find(".imgpicker-list").empty().append($render);//插入dom
	
					//分页符
					var paginate=data.page,
						$render_paginate=$(paginate);
	
					$render_paginate.filter("a:not(.disabled,.cur)").click(function(){
						//获得页数
						var href=$(this).attr("href"),
							page=href.split("/");
						page=page[page.length-1];
						page=page.replace(/.html/,"");
	
						showImgListRender(page);
						return false;
					})
	
					$picker.find(".paginate").empty().append($render_paginate);//插入dom
	
				}
				if(callback_showImgListRender) callback_showImgListRender();//执行回调，传入所有数据
	
			}
	
			//获取数据并渲染
			$.ajax({
				url:"/Design/getMediaList",
				type:"post",
				dataType:"json",
				data:{"p":parseInt(page),type:'voice'},
				// beforeSend:function(){
				//     $.jBox.showloading();
				// },
				success:function(data){
					if (data.status == 1) {
						// console.log(data)
						dorender(data);
					} else {
						HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
					}
					// $.jBox.hideloading();
				}
			});
		};
	
		/*
		 * 初始化上传组件
		 * @param jbox 弹窗对象
		 */
		var initUpload=function(jbox){
			var arrSelected=[];//选中的数据
			var arrSelectedId=[];
			//上传事件
			$picker.find("#imgpicker_upload_input").uploadify({
				"debug": false,
				"auto": true,
				"formData": {"PHPSESSID": $.cookie("PHPSESSID"),type:'voice'},
				"width": 60,
				"height": 60,
				"multi": true,
				'swf': '/Public/plugins/uploadify/uploadify.swf',
				'uploader': '/Design/uploadMedia', //接口名称
				"buttonText": "+",
				"fileSizeLimit": "5MB",
				"fileTypeExts": "*.mp3; *.wma; *.wav; *.amr",
				'onSelectError': function(file, errorCode, errorMsg) {
					switch (errorCode) {
						case -100:
							HYD.hint("danger", "对不起，系统只允许您一次最多上传10个文件");
							break;
						case -110:
							HYD.hint("danger", "对不起，文件 [" + file.name + "] 大小超出5MB！");
							break;
						case -120:
							HYD.hint("danger", "文件 [" + file.name + "] 大小异常！");
							break;
						case -130:
							HYD.hint("danger", "文件 [" + file.name + "] 类型不正确！");
							break;
					}
				},
				'onFallback': function() {
					HYD.hint("danger", "您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
				},
				'onUploadSuccess': function(file, data, response) {
					var data = $.parseJSON(data),
						tpl = $("#tpl_popbox_ImgPicker_audio2").html(), //获取模板
						$PrvPanel = $picker.find(".imgpicker-upload-preview"); //获取预览图容器
	
					var url=data.file_path;//图片路径
					var id=data.file_id; //图片id
					arrSelected.push(url);//将数据插入数组
					arrSelectedId.push(id);
					var html = _.template(tpl, {url: url,id:id}), //渲染模板
					$render = $(html);
					// console.log($render)
					//删除图片事件
					$render.find(".j-imgpicker-upload-btndel").click(function() {
						var index=$picker.find(".imgpicker-upload-preview li").index($(this).parent("li"));
						//移除dom并从数组中删除
						$render.fadeOut(300, function() {
							arrSelected.splice(index,1);
							$(this).remove();
						});
					});
	
					$PrvPanel.append($render); //插入文档
				},
				onUploadError: function(file, errorCode, errorMsg, errorString) {
					HYD.hint("danger", "对不起：" + file.name + "上传失败：" + errorString);
				}
			});
	
			//使用上传图片按钮事件
			$picker.find("#j-btn-uploaduse").click(function(){
				if(arrSelected.length==0){
					HYD.hint("danger", "对不起，您没有选择音频：" + data.msg);
				}else{
					var img='<div class="audio" data-src="'+arrSelected[0]+'" data-id="'+arrSelectedId[0]+'"><i></i></div>',
						id=$(this).data("id");
					$("#js_audio").html(img);
					$("#j-initDataID").val(id);
					$('#js_audio').find('.audio').click(function(event) {
						var src=$(this).data('src');
						if($('#js_audio').find('embed').length==0){
							$('#js_audio').append('<embed src='+src+' hidden=true />')
						}else{
							$('#js_audio').find('embed').remove();
						}
						
					});
				}
				$.jBox.close(jbox);
			});
		};
	
		//打开图片选择器窗口，并渲染第一页数据
		showImgListRender(1,function(){
			$.jBox.show({
				title: "选择音频",
				content: $picker,
				btnOK: {show: false},
				btnCancel: {show: false},
				onOpen:function(jbox){
					var $btnuse=$picker.find("#j-btn-listuse");//使用选中图片按钮
					
					//使用选中图片事件
					$btnuse.click(function(){
						var arrSelected=[];//选中的数据
						var arrSelectedId=[]
						//将选中的图片数据推入数组
						$picker.find(".imgpicker-list li.selected").each(function(){
							arrSelected.push(imglist[$(this).index()].file_path);
							arrSelectedId.push(imglist[$(this).index()].file_id);
						});
						if(arrSelected.length==0){
							HYD.hint("danger", "对不起，您没有选择图片：" + data.msg);
						}else{
							var img='<div class="audio" data-src="'+arrSelected[0]+'" data-id="'+arrSelectedId[0]+'"><i></i></div>'
							$("#js_audio").html(img);
							$('#js_audio').find('.audio').click(function(event) {
								var src=$(this).data('src');
								if($('#js_audio').find('embed').length==0){
									$('#js_audio').append('<embed src='+src+' hidden=true />')
								}else{
									$('#js_audio').find('embed').remove();
								}
								
							});
						}
	
						$.jBox.close(jbox);
					});
	
					//切换到上传图片选项卡，初始化上传组件
					$picker.find(".j-initupload").one("click",function(){
						initUpload(jbox);
					});
				}
			});
		});
	};
	//选择视频事件
	var reRenderMaterialPre_Video=function(ajaxdata){
		
		//选择事件
		/*
		 * 图片选择器
		 * @Author  chenjie
		 * @param callback 回调函数
		 * @return Array 图片列表数组
		 */
	
		var html = $("#tpl_popbox_Video").html(),
			$picker = $(html),//选择器
			imglist;//图片列表缓存
	
		/*
		 * ajax 根据页数 获取图片列表数据
		 * @param page 页数
		 */
		var showImgListRender = function(page,callback_showImgListRender) {
			var dorender=function(data){
				imglist=data.list;//音频列表
				// console.log(imglist)
				if(!imglist || !imglist.length){
					$picker.find(".imgpicker-list").append("<p class='txtCenter'>对不起，暂无视频</p>");
				}
				else{
					//渲染模板
					var html = _.template($("#tpl_popbox_ImgPicker_video").html(), {dataset: imglist}),
						$render = $(html);
	
					//绑定选择事件
					$render.filter("li").click(function() {
						$(this).addClass("selected").siblings("li").removeClass("selected");
					});
	
					$picker.find(".imgpicker-list").empty().append($render);//插入dom
	
					//分页符
					var paginate=data.page,
						$render_paginate=$(paginate);
	
					$render_paginate.filter("a:not(.disabled,.cur)").click(function(){
						//获得页数
						var href=$(this).attr("href"),
							page=href.split("/");
						page=page[page.length-1];
						page=page.replace(/.html/,"");
	
						showImgListRender(page);
						return false;
					})
	
					$picker.find(".paginate").empty().append($render_paginate);//插入dom
	
				}
				if(callback_showImgListRender) callback_showImgListRender();//执行回调，传入所有数据
	
			}
	
			//获取数据并渲染
			$.ajax({
				url:"/Design/getMediaList",
				type:"post",
				dataType:"json",
				data:{"p":parseInt(page),type:'video'},
				// beforeSend:function(){
				//     $.jBox.showloading();
				// },
				success:function(data){
					if (data.status == 1) {
						// console.log(data)
						dorender(data);
					} else {
						HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
					}
					// $.jBox.hideloading();
				}
			});
		};
	
		/*
		 * 初始化上传组件
		 * @param jbox 弹窗对象
		 */
		var initUpload=function(jbox,title,description){
			var arrSelected=[];//选中的数据
			var arrSelectedId=[];
			//上传事件
			$picker.find("#imgpicker_upload_input").uploadify({
				"debug": false,
				"auto": true,
				"formData": {"PHPSESSID": $.cookie("PHPSESSID"),type:'video',title:title,description:description},
				"width": 60,
				"height": 60,
				"multi": true,
				'swf': '/Public/plugins/uploadify/uploadify.swf',
				'uploader': '/Design/uploadMedia', //接口名称
				"buttonText": "+",
				"fileSizeLimit": "20MB",
				"fileTypeExts": "*.rm; *.rmvb; *.avi; *.wmv; *.mpg; *.mpeg; *.mp4",
				'onSelectError': function(file, errorCode, errorMsg) {
					switch (errorCode) {
						case -100:
							HYD.hint("danger", "对不起，系统只允许您一次最多上传10个文件");
							break;
						case -110:
							HYD.hint("danger", "对不起，文件 [" + file.name + "] 大小超出5MB！");
							break;
						case -120:
							HYD.hint("danger", "文件 [" + file.name + "] 大小异常！");
							break;
						case -130:
							HYD.hint("danger", "文件 [" + file.name + "] 类型不正确！");
							break;
					}
				},
				'onFallback': function() {
					HYD.hint("danger", "您未安装FLASH控件，无法上传图片！请安装FLASH控件后再试。");
				},
				'onUploadSuccess': function(file, data, response) {
					var data = $.parseJSON(data),
						tpl = $("#tpl_popbox_ImgPicker_video2").html(), //获取模板
						$PrvPanel = $picker.find(".imgpicker-upload-preview"); //获取预览图容器
					// console.log(data);
					var url=data.file_path;//图片路径
					var id=data.file_id; //图片id
					arrSelected.push(url);//将数据插入数组
					arrSelectedId.push(id);
					var html = _.template(tpl, {url: url,id:id}), //渲染模板
					$render = $(html);
					// console.log($render)
					//删除图片事件
					$render.find(".j-imgpicker-upload-btndel").click(function() {
						var index=$picker.find(".imgpicker-upload-preview li").index($(this).parent("li"));
						//移除dom并从数组中删除
						$render.fadeOut(300, function() {
							arrSelected.splice(index,1);
							$(this).remove();
						});
					});
	
					$PrvPanel.append($render); //插入文档
				},
				onUploadError: function(file, errorCode, errorMsg, errorString) {
					HYD.hint("danger", "对不起：" + file.name + "上传失败：" + errorString);
				}
			});
	
			//使用上传图片按钮事件
			$picker.find("#j-btn-uploaduse").click(function(){
				if(arrSelected.length==0){
					HYD.hint("danger", "对不起，您没有选择音频：" + data.msg);
				}else{
					var img='<div class="video" data-src="'+arrSelected[0]+'" data-id="'+arrSelectedId[0]+'"><i></i></div>',
						id=$(this).data("id");
					$("#js_video").html(img);
					$("#j-initDataID").val(id);
				}
				$.jBox.close(jbox);
				$('#js_video').find('.video').click(function(event) {
					var src=$(this).data('src');
					if($('#js_video').find('embed').length==0){
						$('#js_video').append('<embed class="videoMoudle" src='+src+' autostart="false" />')
					}else{
						$('#js_video').find('embed').remove();
					}
					
				});
			});
		};
	
		//打开图片选择器窗口，并渲染第一页数据
		showImgListRender(1,function(){
			$.jBox.show({
				title: "选择视频",
				content: $picker,
				btnOK: {show: false},
				btnCancel: {show: false},
				onOpen:function(jbox){
					var $btnuse=$picker.find("#j-btn-listuse");//使用选中图片按钮
					
					//使用选中图片事件
					$btnuse.click(function(){
						var arrSelected=[];//选中的数据
						var arrSelectedId=[]
						//将选中的图片数据推入数组
						$picker.find(".imgpicker-list li.selected").each(function(){
							arrSelected.push(imglist[$(this).index()].file_path);
							arrSelectedId.push(imglist[$(this).index()].file_id);
						});
						if(arrSelected.length==0){
							HYD.hint("danger", "对不起，您没有选择视频：" + data.msg);
						}else{
							var img='<div class="video" data-src="'+arrSelected[0]+'" data-id="'+arrSelectedId[0]+'"><i></i></div>'
							$("#js_video").html(img);
							$('#js_video').find('.video').click(function(event) {
								var src=$(this).data('src');
								if($('#js_video').find('embed').length==0){
									$('#js_video').append('<embed class="videoMoudle" src='+src+' autostart="false" />')
								}else{
									$('#js_video').find('embed').remove();
								}
								
							});
						}
	
						$.jBox.close(jbox);
					});
	
					//切换到上传图片选项卡，初始化上传组件
					$picker.find(".j-initupload").one("click",function(){
						var html='<div class="videoInfo">'+
								 '<div class="videolabel">请输入视频标题</div>'+
								 '<div class="videoTit" contenteditable="true"></div>'+
								 '<div class="videolabel">请输入视频描述</div>'+
								 '<div class="videodesc" contenteditable="true">'+
								 '</div></div>'
						$.jBox.show({
							title: "填写相关信息",
							content: html,
							btnOK: {
								show: true,
								onBtnClick:function(jboxs){
									var title=$('.videoTit').text(),
										description=$('.videodesc').text();
									initUpload(jbox,title,description);
									$.jBox.close(jboxs);
								}
							},
							btnCancel: {show: false},
						})
						
					});
				}
			});
		});
	};
	// 选择分销名片
	var reRenderMaterialPre_Card=function(){
		$.ajax({
			url: '/Design/getFxCard',
			type: 'POST',
			dataType: 'json',
			data: '',
			success:function(data){
				// console.log(data);
				if(data.status==1){
					$('#js_card').html('<img src="'+data.data.file_path+'" width="200" alt="" />');
				}else{
					HYD.hint("danger", "对不起，获取数据失败：" + data.msg);
				}
				
			}
		});;
		
	};
	// 设置动作里的返回操作
	$(document).on("click","#editBack,#urlBack",function(){
		$(this).parents("#edit,#url").hide();
		$("#index").show();
	});
	// 预览 [插入自定义菜单进行预览]
	$("#viewBt").click(function(){
		var len=$("#menuList").children().length;
		var str=""
		if(len!=0){
			var name=[];
			var oName=[];
			for (var i = 1; i <= len; i++) {
				var j=i-1;
				name[i]=$("#menuList").children("dl:eq("+j+")").children('dt').find('strong').text();
				var length=$("#menuList").children("dl:eq("+j+")").children('dd').length;
				if(length!=0){
					var str2="<div class='sub_pre_menu_box jsSubViewDiv'><ul class='sub_pre_menu_list'>";
					for (var k = 1; k <= length; k++) {
						var h=k-1;
						oName[k]=$("#menuList").children("dl:eq("+j+")").children('dd:eq('+h+')').find('strong').text();
						str2+="<li><a href='javascript:void(0)'>"+oName[k]+"</a></li>";
					};
					str2+="</ul><i class='arrows arrows_out'></i><i class='arrows arrows_in'></i></div>"
				}else{
					var str2="";
				};
				str+="<li class='pre_menu_item size1of3' id='menu_id["+i+"]'><a href='javascript:void(0)' class='jsView'>"+name[i]+"</a>"+str2+"</li>";
			};
		};
		$("#viewList").empty().append(str);
		$(".mask-area,#mobileDiv").show();
	});
	// 预览二级菜单[点击显示二级菜单]
	$(document).on("click",".jsView",function(){
		if(!$(this).next('.sub_pre_menu_box').hasClass('cur')){
			$(this).next(".sub_pre_menu_box").addClass("cur").parent('li').siblings('li').children('.jsView').next('.sub_pre_menu_box').removeClass('cur');
		} else{
			$(this).next('.sub_pre_menu_box').removeClass('cur');
		}
	});
	// 关闭预览
	$("#viewClose").click(function(){
		$(".mask-area,#mobileDiv").hide();
	});
	// setData (点击设置信息插入相应数据)
	function setData(data){
		// console.log(data)
		if(data.new){
			// console.log(data)
			var str='<dl class="materialPrePanel mgt20" style="border: 1px solid #E7E7EB;"><dt>'+
					'<h1 class="single-title first-t">'+data.new.title+'</h1>'+
					'<p class="single-datetime first-d">'+data.new.add_time+'</p>'+
					'<div class="cover-wrap">'+
					'<img src="'+data.new.cover_img+'">'+
					'</div><p class="single-summary first-p">'+data.new.summary+'</p>'+
					'<a href="'+data.new.redirect+'" target="_blank" class="single-link clearfix first-a">'+
					'<span class="fl">阅读全文</span><span class="fr symbol">&gt;</span></a></dt></dl>';
			//$("#j-mutil-img,#js_editorArea").empty();
			$("#j-mutil-img").empty();
			ue.setContent('');
			$("#j-single-img").html(str);
			$("#j-single-img").parent('.tab_content').addClass('selected').siblings('.tab_content').removeClass('selected');
			$("#edit").show().siblings('.action_content').hide();
			$(".tab_navs>li:eq(1)").addClass('selected').siblings('li').removeClass('selected');
		} else if(data.url){
			//$("#j-mutil-img,#j-single-img,#js_editorArea").empty();
			$("#j-mutil-img,#j-single-img").empty();
			ue.setContent('');
			$("#urlText").val(data.url);
			$("#url").show().siblings(".action_content").hide();
		} else if(data.news){
			var str="";
			for (var i = 0; i < data.news.length; i++) {
				if(i==0){
					str+='<dl class="materialPrePanel mgt20 bgcfff border">'+
					'<dt class="mb10 mt10"><a href="" target="_blank"><div class="cover-wrap">'+
					'<img src="'+data.news[i].cover_img+'" class="img-cover"></div>'+
					'<h2 class="w262">'+data.news[i].title+'</h2></a></dt>';
				}else{
					str+='<dd class="newWidth"><a class="border-top_1 p" href="" target="_blank"><h3>'+data.news[i].title+'</h3>'+
					'<div class="pic"><img src="'+data.news[i].cover_img+'" alt=""></div></a></dd>'
				};
			};
			str+="</dl>";
			//$("#j-single-img,#js_editorArea").empty();
			$("#j-single-img").empty();
			ue.setContent('');
			$("#j-mutil-img").html(str);
			$("#j-mutil-img").parent('.tab_content').addClass('selected').siblings('.tab_content').removeClass('selected');
			$("#edit").show().siblings('.action_content').hide();
			$(".tab_navs>li:eq(2)").addClass('selected').siblings('li').removeClass('selected');
		} else if(data.content){
			$("#j-mutil-img,#j-single-img,#js_audio,#js_video").empty();
			//$(".view").html(data.content);
			//ue.insertHtml(data.content);
			ue.setContent('');
			//console.log(data.content);
			ue.execCommand( 'inserthtml',data.content);
			$('.tab_content:first').addClass('selected').siblings('.tab_content').removeClass('selected');
			$("#edit").show().siblings('.action_content').hide();
			$(".tab_navs>li:first").addClass('selected').siblings('li').removeClass('selected');
			// numCount();
		} else if(data.image){
			// console.log(data)
			$("#j-mutil-img,#j-single-img,#js_audio,#js_video").empty();
			ue.setContent('');
			$('.tab_navs>li').eq(3).addClass('selected').siblings('li').removeClass('selected');
			$('.tab_content:eq(3)').addClass('selected').siblings('.tab_content').removeClass('selected');
			$("#edit").show().siblings('.action_content').hide();
			$('#js_imgOne_img').html('<img src="'+data.image.file_path+'" data-id="'+data.image.file_id+'" width="260" alt="" />')
		}else if(data.voice){
			$("#j-mutil-img,#j-single-img,#js_imgOne_img,#js_video").empty();
			ue.setContent('');
			$('.tab_navs>li').eq(4).addClass('selected').siblings('li').removeClass('selected');
			$('.tab_content:eq(4)').addClass('selected').siblings('.tab_content').removeClass('selected');
			$("#edit").show().siblings('.action_content').hide();
			$('#js_audio').html('<div class="audio" data-id="'+data.voice.file_id+'" data-src="'+data.voice.file_path+'"><i></i></div>')
		} else if(data.video){
			$("#j-mutil-img,#j-single-img,#js_imgOne_img,#js_audio").empty();
			ue.setContent('');
			$('.tab_navs>li').eq(5).addClass('selected').siblings('li').removeClass('selected');
			$('.tab_content:eq(5)').addClass('selected').siblings('.tab_content').removeClass('selected');
			$("#edit").show().siblings('.action_content').hide();
			$('#js_video').html('<div class="video" data-id="'+data.video.file_id+'" data-src="'+data.video.file_path+'"><i></i></div>');
			$(document).on('click', '#js_video .video', function(event) {
				var src=$(this).data('src');
					if($('#js_video').find('embed').length==0){
						$('#js_video').append('<embed class="videoMoudle" src='+src+' autostart="false" />')
					}else{
						$('#js_video').find('embed').remove();
					}
			});
		} else if(data.card){
			$("#j-mutil-img,#j-single-img,#js_imgOne_img,#js_audio,#js_video").empty();
			ue.setContent('');
			$('.tab_navs>li').eq(6).addClass('selected').siblings('li').removeClass('selected');
			$('.tab_content:eq(6)').addClass('selected').siblings('.tab_content').removeClass('selected');
			$("#edit").show().siblings('.action_content').hide();
			$('#js_card').html('<img src="'+data.card.img_url+'" width="200" alt="" />');
		};
	};
	// 定义自定义菜单文本字数计算方法
    // function numCount(){
    //     var num=$("#js_editorArea").text().length;
    //     $(".js_editorTip").children('em').text(595-num);
    // }
    // // 计算字数
    // $("#js_editorArea").keyup(function() {
    //     numCount();
    // });
	// 发布
    $("#pubBt").click(function(){
		// console.log(initData)
        $.jBox.showloading();
        $.post("", initData, function(data){
            $.jBox.hideloading();
            if(data.status == 1){
                HYD.hint("success", data.info);
                //console.log(initData)
            }else{
                HYD.hint("danger", data.info);
            }
        });
    });

    
	// 设置链接
	$("#getLinks").hover(function() {
		$(this).children('.setLinks').show();
	}, function() {
		$(this).children('.setLinks').hide();
	});

	
	// 点击设置链接
	$("#setLinks>li>a").click(function() {
		var type=$(this).data('type');
		var key=$(this).data('key');
		var $this=$(this);
		if(type==0){
			if(key!="diy"){
				var url=$this.data('url');
				$("#urlText").val(url).attr('readonly', 'readonly');;
				$this.parents(".setLinks").hide();
			}else{
				$("#urlText").removeAttr('readonly').val("").focus();
				$this.parents(".setLinks").hide();
			}
		}else{
			$.ajax({
                url: "/PubMarketing/Ajaxmenu",
                type: "post",
                dataType: "json",
                data: {
                    "key": key
                },
                beforeSend: function() {
                    $.jBox.showloading();
                },
                success: function(data) {
                	//console.log(data);
                	if(key=="ump"){
						var html=_.template(tpl.menu_ump,data);//渲染营销活动模板
                		var html_heads=_.template(tpl.menu_tab,data);
                	}else if(key=="detail"){
                		var html_head=$this.html();
						var html=_.template(tpl.menu_detail,data);//渲染产品模板
                	}else if(key=="group"){
                		var html_head=$this.html();
						var html=_.template(tpl.menu_group,data);//渲染产品分组模板
                	}else if(key=="magazine"){
                		var html_head=$this.html();
						var html=_.template(tpl.menu_magazine,data);//渲染专题模板
                	}else if(key=="sort"){
                		var html_head=$this.html();
						var html=_.template(tpl.menu_sort,data);//渲染专题分类模板
                	}
					
					$.jBox.show({
			            title: html_head,
			            content: html,
			            btnOK: {show:false},
			            btnCancel:{show:false},
			            onOpen:function(jbox){
							$.jBox.hideloading();
			            }
			        });
					$('.game-list-panel1').html(html_heads)
                }
            });
			$this.parents(".setLinks").hide();
		};
	});
	// 营销活动选择
	$(document).on("click",".tabs>a",function(){
		var keys=$(this).data('keys');
		$(this).parent("li").addClass('cur').siblings('li').removeClass('cur');
		$.ajax({
			url: "/PubMarketing/Ajaxmenu",
            type: "post",
            dataType: "json",
            data: {
                "key": "ump",
                "game_key":keys
            },
            beforeSend: function() {
                $.jBox.showloading();
            },
            success: function(data) {
            	$.jBox.hideloading();
            	//console.log(data);
				var html=_.template(tpl.menu_tab,data);//渲染模板
				//console.log(html);
				$(".game-list-panel1").html(html).height("auto").parents('.jbox').height("auto");
            }
		});
		var _this=$(this)
		if(_this.data('origin')=='videolst'){
			_this.parent('.tabs').next('.tabs-content').find('.tc').eq(0).removeClass('hide').siblings('.tc').addClass('hide');
		}
	})
	// 分页
	$(document).on("click",".jbox-container .paginate a",function(){
		if ($(this).attr("href")) {
			//获得页数
            var href=$(this).attr("href"),
                attr=href.split("/");
                var key,p;
                $.each( attr, function(i, n){
                	if(n == 'key'){
                		key = attr[i+1];
                		key = key.replace(/.html/,"");
                	}
                	if(n == 'p'){
                		p = attr[i+1];
                		p = p.replace(/.html/,"");
                	}
				});


			$.ajax({
				url: '/PubMarketing/Ajaxmenu',
				type: "post",
				dataType: "json",
				data: {
					"p":p,
					"key":key,
				},
				beforeSend: function() {
					$.jBox.showloading();
				},
				success: function(data) {
					$.jBox.hideloading();

					var menu_tpl;
					if(key == 'ump'){
						menu_tpl = tpl.menu_ump;
					}else if(key == 'group'){
						menu_tpl = tpl.menu_group;
					}else if(key == 'detail'){
						menu_tpl = tpl.menu_detail;
					}else if(key == 'magazine'){
						menu_tpl = tpl.menu_magazine;
					}else if(key == 'sort'){
						menu_tpl = tpl.menu_sort;
					}else{
						menu_tpl = tpl.menu_ump;
					}

					var html=_.template(menu_tpl,data);//渲染模板
					//console.log(html);
					$(".jbox-container").html(html).height("auto").parents('.jbox').height("auto");
				}
			});
		}
		return false;
	})
	// 选择链接
	$(document).on('click', '.j-select', function(event) {
		var LinkVal=$(this).prev('a').attr('href');

		$("#urlText").val(LinkVal);
		$(".jbox").remove();
		$("#jbox-overlay").hide();
	});
	
    
    
});


