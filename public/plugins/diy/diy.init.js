$(function(){
    //添加一个模块
    $(".j-diy-addModule").click(function() {
        var type = $(this).data("type"); //获取模块类型

        //默认数据
        var moduleData = {
            id: HYD.DIY.getTimestamp(), //模块ID
            type: type, //模块类型
            draggable: true, //是否可拖动
            sort: 0, //排序
            content: null //模块内容
        };

        //根据模块类型设置默认值
        switch (type) {
            //富文本
            case 1:
                moduleData.ue = null;
                moduleData.content = {
                    fulltext: "&lt;p&gt;『富文本编辑器』&lt;/p&gt;",
                    modulePadding:5
                };
                break;
            //标题
            case 2:
                moduleData.content = {
                    title: "标题名称",
                    style:0,
                    direction: "left",
                    modulePadding:5
                };
                break;
            //自定义模块
            case 3:
                moduleData.content = {
                    modulePadding:0
                };
            break;
            //商品
            case 4:
                moduleData.content={
                    layout:1,
                    showPrice:true,
                    showIco:true,
                    showName:1,
                    goodslist:[],
                    sale_num:5,
                    goodstyle:1,
                    layoutstyles:1,
                    goodstxt:"立即购买",
                    titleLine:0,
                    version:1,
                    modulePadding:5
                }
                break;
            //商品列表（分组标签）
            case 5:
                moduleData.content={
                    layout:1,
                    showPrice:true,
                    showIco:true,
                    showName:true,
                    group:null,
                    goodsize:6,
                    sale_num:5,
                    goodstyle:1,
                    goodstxt:"立即购买",
                    version:1,
                    modulePadding:5,
                    goodslist:[
                        {
                            item_id: "1",
                            link: "#",
                            pic: "/Public/images/diy/goodsView1.jpg",
                            price: "100.00",
                            original_price:"200.00",
                            title: "第一个商品"
                        },
                        {
                            item_id: "2",
                            link: "#",
                            pic: "/Public/images/diy/goodsView2.jpg",
                            price: "200.00",
                            original_price:"300.00",
                            title: "第二个商品"
                        },
                        {
                            item_id: "3",
                            link: "#",
                            pic: "/Public/images/diy/goodsView3.jpg",
                            price: "300.00",
                            original_price:"400.00",
                            title: "第三个商品"
                        },
                        {
                            item_id: "4",
                            link: "#",
                            pic: "/Public/images/diy/goodsView4.jpg",
                            price: "400.00",
                            original_price:"500.00",
                            title: "第四个商品"
                        }
                    ]
                }
                break;
            //搜索
            case 6:
                moduleData.content = {
                    modulePadding:5
                };
            break;
            //文本导航
            case 7:
                moduleData.content={
                    modulePadding:5,
                    dataset:[
                        {
                            linkType:0,
                            link:"",
                            title:"",
                            showtitle:""
                        }
                    ]
                }
                break;
            //图片导航
            case 8:
                moduleData.content={
                    layout:1,
                    layout1_style:1,
                    modulePadding:5,
                    dataset:[
                        {
                            linkType:0,
                            link:"#",
                            showtitle:"导航名称",
                            titleBackgroundColor:"#FE9303",
                            pic:"/Public/images/diy/waitupload.png"
                        },
                        {
                            linkType:0,
                            link:"#",
                            showtitle:"导航名称",
                            titleBackgroundColor:"#FE9303",
                            pic:"/Public/images/diy/waitupload.png"
                        },
                        {
                            linkType:0,
                            link:"#",
                            showtitle:"导航名称",
                            titleBackgroundColor:"#FE9303",
                            pic:"/Public/images/diy/waitupload.png"
                        },
                        {
                            linkType:0,
                            link:"#",
                            showtitle:"导航名称",
                            titleBackgroundColor:"#FE9303",
                            pic:"/Public/images/diy/waitupload.png"
                        }
                    ]
                }
                break;
            //广告图片
            case 9:
                moduleData.content={
                    showType:1,
                    space:0,
                    margin:5,
                    modulePadding:5,
                    dataset:[]
                }
                break;
            //分割线
            case 10:

            break;
            //辅助空白
            case 11:
                moduleData.content={
                    height:10
                }
                break;
            // 顶部菜单
            case 12:
                moduleData.content={
                    style:0,
                    marginstyle:0,
                    modulePadding:5,
                    dataset:[
                        {
                            link: "/Shop/index",
                            linkType: 6,
                            showtitle: "首页",
                            title: "店铺主页",
                            pic:"/PublicMob/images/ind3_1.png",
                            bgColor:"#07a0e7",
                            cloPicker:'0',
                            fotColor:'#fff'
                        },
                        {
                            link: "/Shop/index",
                            linkType: 6,
                            showtitle: "新品",
                            title: "",
                            pic:"/PublicMob/images/ind3_2.png",
                            bgColor:"#72c201",
                            cloPicker:'1',
                            fotColor:'#fff'
                        },
                        {
                            link: "/Shop/index",
                            linkType: 6,
                            showtitle: "热卖",
                            title: "店铺主页",
                            pic:"/PublicMob/images/ind3_3.png",
                            bgColor:"#ffa800",
                            cloPicker:'2',
                            fotColor:'#fff'
                        },
                        {
                            link: "/Shop/index",
                            linkType: 6,
                            showtitle: "推荐",
                            title: "",
                            pic:"/PublicMob/images/ind3_4.png",
                            bgColor:"#d50303",
                            cloPicker:'3',
                            fotColor:'#fff'
                        }
                    ]
                }
                break;
            // 橱窗
            case 13:
                moduleData.content={
                    layout:0,
                    version:2,
                    modulePadding:5,
                    dataset:[
                        {
                            linkType:0,
                            link:"#",
                            showTitle:1,
                            title:"橱窗文字",
                            pic:"/Public/images/diy/waitupload2.png"
                        },
                        {
                            linkType:0,
                            link:"#",
                            showTitle:1,
                            title:"橱窗文字",
                            pic:"/Public/images/diy/waitupload2.png"
                        },
                        {
                            linkType:0,
                            link:"#",
                            showTitle:1,
                            title:"橱窗文字",
                            pic:"/Public/images/diy/waitupload2.png"
                        }
                    ]
                }
                break;
            // 视频
            case 14:
                moduleData.content={
                    website:'',
                    modulePadding:5
                }
                break;
            // 音频
            case 15:
                moduleData.content = {
                    direct:0,
                    imgsrc:'',
                    audiosrc:'',
                    modulePadding:5
                }
                break;
            // 公告
            case 16:
                moduleData.content = {
                    linkType:0,
                    title:"公告",
                    showtitle:"请填写内容，如果过长，将会滚动显示",
                    bgColor:"#ffffcc",
                    cloPicker:"2",
                    fontSize:"font12",
                    modulePadding:5
                }
                break;
            // 公告
            case 17:
                moduleData.content = {
                    layout:0,
                    modulePadding:5,
                    dataset:[
                        {
                            linkType:0,
                            link:"#",
                            showtitle: "内容1",
                            bgColor:"#28c192",
                            cloPicker:'2',
                            fotColor:'#fff'
                        },
                        {
                            linkType:0,
                            link:"#",
                            showtitle: "内容2",
                            bgColor:"#28c192",
                            cloPicker:'2',
                            fotColor:'#fff'
                        },
                        {
                            linkType:0,
                            link:"#",
                            showtitle: "内容3",
                            bgColor:"#28c192",
                            cloPicker:'2',
                            fotColor:'#fff'
                        },
                        {
                            linkType:0,
                            link:"#",
                            showtitle: "内容4",
                            bgColor:"#28c192",
                            cloPicker:'2',
                            fotColor:'#fff'
                        },
                        {
                            linkType:0,
                            link:"#",
                            showtitle: "内容5",
                            bgColor:"#28c192",
                            cloPicker:'2',
                            fotColor:'#fff'
                        },
                        {
                            linkType:0,
                            link:"#",
                            showtitle: "内容6",
                            bgColor:"#28c192",
                            cloPicker:'2',
                            fotColor:'#fff'
                        },
                    ]
                }
                break;
        }

        //添加模块
        HYD.DIY.add(moduleData, true);
    });

    //初始化布局拖动事件
    $("#diy-phone .drag").sortable({
        revert: true,
        placeholder: "drag-highlight",
        stop: function(event, ui) {
            HYD.DIY.repositionCtrl(ui.item, $(".diy-ctrl-item[data-origin='item']")); //重置ctrl的位置
        }
    }).disableSelection();

    //编辑页面标题
    $(".j-pagetitle").click(function() {
        $(".diy-ctrl-item[data-origin='pagetitle']").show().siblings(".diy-ctrl-item[data-origin='item']").hide();
    });

    //编辑页面标题同步到手机视图中
    $(".j-pagetitle-ipt").change(function() {
        $(".j-pagetitle").text($(this).val());
    });
});