/*
 * 我的图库
 * @Author  chenjie
*/
;(function($,document,window){
	//默认数据
	var defaults={
		callback:null //回调函数
	},
	opts={}//合并后的数据

	//窗口参数
	Win={
		width:$(window).width(),
		height:$(window).height()
	},

	//模板
	Tpl={
		main:$("#tpl_albums_main").html(),//相册主体
		overlay:$("#tpl_albums_overlay").html(),//遮罩层
		tree:$("#tpl_albums_tree").html(),//文件夹树
		treeFn:$("#tpl_albums_tree_fn").html(),//文件夹递归模板
		imgs:$("#tpl_albums_imgs").html()//图片列表
	},

	//缓存
	Cache={
		folderID:"",//当前选中的文件夹
		moveFolderID:0,//要移动的文件夹
		imgs:{}//当前页面的图片缓存
	},

	//模拟数据接口
	// Ajaxurl={
	// 	getFolderTree:"/Application/Home/View/Demo/json/albums_getFolderTree.json",//获取文件夹树
	// 	getImgList:"/Application/Home/View/Demo/json/albums_getImgList.json",//根据文件夹ID获取图片列表
	// 	addImg:"",//根据某个文件夹上传图片
	// 	moveImg:"",//将选中的图片移动到指定文件夹
	// 	delImg:"",//删除选中的图片
	// 	addFolder:"",//添加文件夹
	// 	renameFolder:"",//重命名文件夹
	// 	delFolder:"",//删除文件夹
	// },

	//数据接口
	Ajaxurl={
		getFolderTree:"/Design/getFolderTree",//获取文件夹树
		getImgList:"/Design/getImgList",//根据文件夹ID获取图片列表
		addImg:"/Design/uploadFile",//根据某个文件夹上传图片
		moveImg:"/Design/moveImg",//将选中的图片移动到指定文件夹
		delImg:"/Design/delImg",//删除选中的图片
		addFolder:"/Design/addFolder",//添加文件夹
		renameFolder:"/Design/renameFolder",//重命名文件夹
		delFolder:"/Design/delFolder",//删除文件夹
        moveCateImg:"/Design/moveCateImg",//合并分类
        renameImg:"/Design/renameImg"//重命名图片
	},

	/*
	 * 获取图片列表
	 * @Author  chenjie
	 * @param $albums 当前相册的dom
	 * @param folderid 文件夹id
	 * @param page 页数
	*/
	GetImgList=function($albums,folderid,page,file_name){
		var func_callee=arguments.callee,
			$pn_panelImgs=$albums.find("#j-panelImgs"),//图片列表容器
			$pn_panelPaginate=$albums.find("#j-panelPaginate"),//分页容器
			data=folderid>=0 ? {id:folderid,p:page,file_name:file_name} : {p:page,file_name:file_name};

		$.ajax({
            url: Ajaxurl.getImgList,
            type: "post",
            dataType: "json",
            data:data,
            beforeSend:function(){
            	$pn_panelImgs.find(".j-loading").show();
            },
            success: function(data) {
                if (data.status == 1) {
                	// console.log(data);
                	Cache.imgs=!_.isArray(data.data) ? null : data.data;

					//渲染模板
					var $render_imgs=$(_.template(Tpl.imgs,{dataset:Cache.imgs})),
						$render_paginate=$(data.page);
					//更新数据
					$pn_panelImgs.find(".j-loading").hide().end().find("ul,.j-noPic").remove().end().append($render_imgs);
					$pn_panelPaginate.empty().append($render_paginate);

					//翻页
					$render_paginate.filter("a:not(.disabled,.cur)").click(function(){
	                    //获得页数
	                    var href=$(this).attr("href"),
	                        page=href.split("/");

	                    page=page[page.length-1];
	                    page=page.replace(/.html/,"");

	                    func_callee($albums,folderid,page,file_name);

	                    return false;
	                });
                } else {
                    HYD.hint("danger", "对不起，图片获取失败：" + data.msg);
                }
            }
        });
	},

	/*
	 * 更新文件夹树图片数量
	 * @Author  chenjie
	 * @param $albums 当前相册的dom
	*/
	UpdateTreeNums=function($albums){
		$.ajax({
            url: Ajaxurl.getFolderTree,
            type: "post",
            dataType: "json",
            success: function(data) {
                if (data.status == 1) {   
                	var dataset=data.data.tree,
                		$trees=$albums.find("#j-panelTree");

                	//手动插入所有图片的数量
                	dataset.push({
                		id:"-1",
                		picNum:data.data.total
                	});

                	//更新图片数量递归
                	var doUpDate=function(dataset){
                		var func_callee=arguments.callee;

	                	//更新每个文件夹的图片数量
	                	_.each(dataset,function(item){
	                		$trees.find("dt[data-id="+item.id+"]").find(".j-num").text(item.picNum);
	                		if(item.subFolder && item.subFolder.length){
	                			func_callee(item.subFolder)
	                		}
	                	});
                	}

                	//执行更新
                	doUpDate(dataset);

                } else {
                    console.log("更新文件夹树图片数量失败")
                }
            }
        });
	};

	//静态实现相册
	$.albums=function(options){
		opts=$.extend(true,{},defaults,options);//合并参数

		var $albums=$("#albums"),//相册
			$overlay=$("#albums-overlay");//遮罩

		if(!$albums.length){
			$albums=$(Tpl.main);
			$overlay=$(Tpl.overlay);

			$("body").append(
				$albums.hide(),
				$overlay.hide()
			);

			var $btn_close=$albums.find("#j-close"),//关闭相册
				$btn_addFolder=$albums.find("#j-addFolder"),//添加文件夹
				$btn_renameFolder=$albums.find("#j-renameFolder"),//重命名文件夹
				$btn_delFolder=$albums.find("#j-delFolder"),//删除文件夹
				$btn_addImg=$albums.find("#j-addImg"),//上传图片
				$btn_moveImg=$albums.find("#j-moveImg"),//移动图片
                $btn_moveCateImg=$albums.find("#j-cateImg"),//合并分类
				$btn_delImg=$albums.find("#j-delImg"),//删除图片
				$pn_panelTree=$albums.find("#j-panelTree");//文件夹树容器

			//Method 关闭相册
			var closeAlbums=function(){
				$albums.fadeOut("fast");
				$overlay.fadeOut("fast");
				$albums.find("#j-panelImgs li").removeClass("selected");
			};

			//异步加载文件夹树
			$.ajax({
                url: Ajaxurl.getFolderTree,
                type: "post",
                dataType: "json",
                success: function(data) {
                    if (data.status == 1) {
                    	//渲染模板
                        var templateFn= _.template(Tpl.treeFn),//递归模板
                        	nodes=templateFn({dataset:data.data.tree,templateFn:templateFn}),//获取递归树
                        	$render=$(_.template(Tpl.tree,{dataset:data.data,nodes:nodes}));//拼接文件夹树

                        $pn_panelTree.empty().append($render);

                        //第一次打开，获取所有分类的图片
						$albums.find(".j-albumsNodes > dt:first").click();
                    } else {
                        HYD.hint("danger", "对不起，文件夹获取失败：" + data.msg);
                    }
                }
            });

            //Event 获取不同文件夹中的图片
			$(document).on("click",".j-albumsNodes dt",function(event){
				var $self=$(this),
					folderID=$self.data("id");//当前选中的文件夹ID

				//选中样式
				$self.parents(".j-albumsNodes").find("dt").removeClass("selected")
				$self.addClass("selected");

				//判断触发目标，如果不是弹窗中的选择文件夹则按照默认的进行
				if(!$(event.currentTarget).parents(".j-propagation").length){
					if(Cache.folderID==folderID) return;//如果选择的是当前文件夹则不进行操作

					Cache.folderID=folderID;//缓存当前选中的文件夹ID

					var can_add=$self.data("add"),//可添加
						can_rename=$self.data("rename"),//可重命名
						can_del=$self.data("del");//可删除

					can_add==1 ? $btn_addFolder.show() : $btn_addFolder.hide();
					can_rename==1 ? $btn_renameFolder.show() : $btn_renameFolder.hide();
					can_del==1 ? $btn_delFolder.show() : $btn_delFolder.hide();

					//渲染当然图片列表
					GetImgList($albums,Cache.folderID,1);
				}
				else{//弹窗中的文件夹选择
					Cache.moveFolderID=folderID;
				}

				return false;
			});

			//Event 展开/隐藏文件夹树
			$(document).on("click",".j-albumsNodes dt i",function(){
				var $icon=$(this),//文件夹图标
					$subNode=$icon.parent("dt").siblings("dd").find(" > dl"),//子节点
					hasSubNode=$subNode.length;//是否有子节点

				//有子节点的话就展开或者隐藏
				if(!hasSubNode) return;

				if($icon.hasClass("open")){
					$icon.removeClass("open");
					$subNode.slideUp(200);
				}
				else{
					$icon.addClass("open");
					$subNode.slideDown(200);
				}

				return false;
			});

			//Event 选择图片
			$albums.on("click","#j-panelImgs li",function(){
				$(this).toggleClass("selected");
				$(this).find('.img-name-edit').hide();
				return false;
			});
			// 编辑图片名称
			$albums.on("click","#j-panelImgs li .albums-edit",function(){
				$(this).children('.img-name-edit').show();
				return false;
			});
			//Event 使用选中的图片
			$albums.on("click","#j-useImg",function(){
				if(!$albums.find("#j-panelImgs li.selected").length){
					HYD.hint("warning","请选择图片！");
					return;
				}

				var tmp_selected=[];//选中的图片
				
				//从缓存中读取选中的图片数据
				$albums.find("#j-panelImgs li.selected").each(function(){
					tmp_selected.push(Cache.imgs[$(this).index()].file);
				});
				if(opts.callback){
					opts.callback(tmp_selected);//执行回调
					closeAlbums();//关闭
				}

				return false;
			});

			//Event 添加文件夹
			$btn_addFolder.click(function(){
				var tmpData=[{
					id:0,
					name:"未命名文件夹",
					picNum:0
				}];

				//添加
				$.ajax({
	                url: Ajaxurl.addFolder,
	                type: "post",
	                dataType: "json",
	                data:{
	                	name:tmpData.name,
	                	parent_id:Cache.folderID
	                },
	                success: function(data) {
	                    if (data.status == 1) {
	                    	tmpData[0].id=data.data;//后端创建文件夹成功后修改临时数据里的ID

	                    	//渲染一个文件夹
	                    	var html=_.template(Tpl.treeFn,{dataset:tmpData});
	                    		$render=$(html);
							$pn_panelTree.find("dt[data-id='"+Cache.folderID+"']").siblings("dd").append($render);

							//选中这个文件夹然后进行重命名操作
							$render.find("dt:first").click();
							$btn_renameFolder.click();
	                    } else {
	                        HYD.hint("danger", "对不起，添加失败：" + data.msg);
	                    }
	                }
	            });
			});

			//Event 重命名文件夹
			$btn_renameFolder.click(function(){
				var $dt=$pn_panelTree.find("dt[data-id='"+Cache.folderID+"']"),//选中的文件夹
					$txt=$dt.find(".j-treeShowTxt"),//显示的名称
					$ipt=$dt.find(".j-ip"),//输入框
					$loading=$dt.find(".j-loading");//loading图标

				$txt.hide();
				$ipt.show().focus().select();	

				$ipt.blur(function(){
					var val=$(this).val();

					//重命名文件夹
					$.ajax({
		                url: Ajaxurl.renameFolder,
		                type: "post",
		                dataType: "json",
		                data:{
		                	name:val,
		                	category_img_id:Cache.folderID
		                },
		                beforeSend:function(){
		                	$loading.css("display","inline-block");
		                },
		                success: function(data) {
		                    if (data.status == 1) {
		                    	$txt.find(".j-name").text(val);
		                    } else {
		                        HYD.hint("danger", "对不起，重命名失败：" + data.msg);
		                    }
		                    $txt.show();
							$ipt.hide();
							$loading.hide();
		                }
		            });
				});
			});

			//Event 删除文件夹
			$btn_delFolder.click(function(){
				var html=$("#tpl_albums_delFolder").html(),
					$render=$(html),
					$isDelImgs=$render.find("input[name=isDelImgs]");

				$.jBox.show({
		            title: "提示",
		            content: $render,
		            btnOK: {
		                onBtnClick: function(jbox) {
		                	var isDelImgs=$isDelImgs.filter(":checked").val();

		                	//删除
							$.ajax({
				                url: Ajaxurl.delFolder,
				                type: "post",
				                dataType: "json",
				                data:{
				                	type:isDelImgs,
				                	id:Cache.folderID
				                },
				                success: function(data) {
				                    if (data.status == 1) {
				                    	UpdateTreeNums($albums);
				                    	var $target=$pn_panelTree.find("dt[data-id="+Cache.folderID+"]").parent("dl");
				                    	$target.parent("dd").siblings("dt").click();
				                    	$target.remove();
				                    } else {
				                        HYD.hint("danger", "对不起，删除失败失败：" + data.msg);
				                    }
				                }
				            });

		                    $.jBox.close(jbox);
		                }
		            }
		        });
			});
			
			//Event 删除图片
			$btn_delImg.click(function(){
				if(!$albums.find("#j-panelImgs li.selected").length){
					HYD.hint("warning","请选择要删除的图片！");
					return;
				}

				var tmp_selected=[];//选中的图片
				
				//从缓存中读取选中的图片数据
				$albums.find("#j-panelImgs li.selected").each(function(){
					tmp_selected.push($(this).data("id"));
				});

				//删除
				$.ajax({
	                url: Ajaxurl.delImg,
	                type: "post",
	                dataType: "json",
	                data:{
	                	file_id:tmp_selected
	                },
	                success: function(data) {
	                    if (data.status == 1) {
	                    	//删除dom
							$albums.find("#j-panelImgs li.selected").fadeOut(300,function(){
								$(this).remove();
							});

							//更新数量
		            		UpdateTreeNums($albums);
	                    } else {
	                        HYD.hint("danger", "对不起，删除失败：" + data.msg);
	                    }
	                }
	            });
			});

			//Event 添加图片
	        $btn_addImg.uploadify({
	            "debug": false,
	            "auto": true,
	            "width": 86,
	            "height": 28,
	            "multi": true,
	            'swf': '/Public/plugins/uploadify/uploadify.swf',
	            'uploader': Ajaxurl.addImg, //接口名称
	            "buttonText": "上传图片",
	            "fileSizeLimit": "5MB",
	            "fileTypeExts": "*.jpg; *.jpeg; *.png; *.gif; *.bmp",
	            onUploadStart:function(){
	            	//当上传开始的时候，获取当前选中的文件夹
					$btn_addImg.uploadify('settings','formData',{
		            	"cate_id":Cache.folderID==-1 ? 0 : Cache.folderID,
		            	"PHPSESSID": $.cookie("PHPSESSID")
		            });
				},
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
	            'onQueueComplete' : function(data) {
		            GetImgList($albums,Cache.folderID,1);//重新渲染当当前文件夹的第一页图片
		            UpdateTreeNums($albums);//更新图片数量
		        },
	            onUploadError: function(file, errorCode, errorMsg, errorString) {
	                HYD.hint("danger", "对不起：" + file.name + "上传失败：" + errorString);
	            }
	        });

			//Event 移动图片
			$btn_moveImg.click(function(){
				if(!$albums.find("#j-panelImgs li.selected").length){
					HYD.hint("warning","请选择要移动的图片！");
					return;
				}

				//从左侧文件夹树中克隆
				var $clone_tree=$("<div class='albums-cl-tree j-albumsNodes j-propagation'></div>");
					$clone_tree.append($pn_panelTree.find("dd:first").contents().clone());

				$.jBox.show({
				    title: "请选择移动文件夹",
				    content: $clone_tree,
				    onOpen:function(){
				    	$clone_tree.find("dt:first").click();//选中第一个文件夹
				    },
				    btnOK: {
				        onBtnClick: function(jbox) {
				        	var tmp_selected=[];//选中的图片

				        	//从缓存中读取选中的图片数据
							$albums.find("#j-panelImgs li.selected").each(function(){
								tmp_selected.push($(this).data("id"));
							});

							//删除
							$.ajax({
				                url: Ajaxurl.moveImg,
				                type: "post",
				                dataType: "json",
				                data:{
				                	file_id:tmp_selected,
				                	cate_id:Cache.moveFolderID
				                },
				                success: function(data) {
				                    if (data.status == 1) {
				                    	//删除dom
				                    	$albums.find("#j-panelImgs li.selected").fadeOut(300,function(){
				                    		$(this).remove();
				                    	});

				                    	//更新文件夹的图片数量
				                    	UpdateTreeNums($albums);

				                    	HYD.hint("success", "恭喜您，操作成功！");
				                    } else {
				                        HYD.hint("danger", "对不起，移动失败：" + data.msg);
				                    }
				                }
				            });

				            $.jBox.close(jbox);
				        }
				    }
				});
			});

            //Event 合并分类
            $btn_moveCateImg.click(function(){
                /*if(!$albums.find("#j-panelImgs li.selected").length){
                    HYD.hint("warning","请选择要移动的图片！");
                    return;
                }*/
                if(!Cache.folderID){
                    HYD.hint("warning","请选择要移动的分类！");
                    return;
                }
                //从左侧文件夹树中克隆
                var $clone_tree=$("<div class='albums-cl-tree j-albumsNodes j-propagation'></div>");
                $clone_tree.append($pn_panelTree.find("dd:first").contents().clone());

                $.jBox.show({
                    title: "请选择移动文件夹",
                    content: $clone_tree,
                    onOpen:function(){
                        $clone_tree.find("dt:first").click();//选中第一个文件夹
                    },
                    btnOK: {
                        onBtnClick: function(jbox) {
                           /* var tmp_selected=[];//选中的图片

                            //从缓存中读取选中的图片数据
                            $albums.find("#j-panelImgs li.selected").each(function(){
                                tmp_selected.push($(this).data("id"));
                            });*/

                            //删除
                            $.ajax({
                                url: Ajaxurl.moveCateImg,
                                type: "post",
                                dataType: "json",
                                data:{
                                    cid:Cache.folderID,
                                    cate_id:Cache.moveFolderID
                                },
                                success: function(data) {
                                    if (data.status == 1) {
                                        //删除dom
                                        $albums.find("#j-panelImgs li.selected").fadeOut(300,function(){
                                            $(this).remove();
                                        });

                                        //更新文件夹的图片数量
                                        UpdateTreeNums($albums);

                                        HYD.hint("success", "恭喜您，操作成功！");
                                    } else {
                                        HYD.hint("danger", "对不起，移动失败：" + data.msg);
                                    }
                                }
                            });

                            $.jBox.close(jbox);
                        }
                    }
                });
            });

			//Event 关闭相册
			$btn_close.click(closeAlbums);
		}

		//显示相册
		$albums.fadeIn("fast");
		$overlay.fadeIn("fast");

		//重置弹窗位置
		if($albums.outerHeight()>=Win.height){
			$albums.css({
				"position":"absolute",
				"marginTop":"0",
				"top":$(document).scrollTop()+10
			});
		}

		//重命名图片
		$albums.on("click", ".renameImg", function() {
			var me = $(this)
			var file_id = me.closest('li').data("id");
			var file_name = me.siblings('input.file_name').val();
			$.ajax({
				url: Ajaxurl.renameImg,
                type: "post",
                dataType: "json",
                data:{
                    file_id:file_id,
                    file_name:file_name
                },
                success: function(data) {
                    if (data.status == 1) {
                    	me.closest('.albums-edit').children('.img-name-edit').hide();
                    	me.closest('.albums-edit').children('p').html(file_name);
                    	me.closest('.albums-edit').children('input.file_name').val(file_name);
                        HYD.hint("success", "恭喜您，操作成功！");
                    } else {
                        HYD.hint("danger", "对不起，操作失败");
                    }
                }
			})
			return false;
			
		});

		$albums.on("click", ".searchImg", function() {
			var file_name = $(this).prev().val();
			GetImgList($albums,Cache.folderID,1,file_name);
		})
	};
}(jQuery,document,window));

/*
 * 兼容历史版本的图片选择器
 * @Author  chenjie
 * @param callback 回调函数
*/
HYD.popbox.ImgPicker=function(callback){
	$.albums({callback:callback});
}