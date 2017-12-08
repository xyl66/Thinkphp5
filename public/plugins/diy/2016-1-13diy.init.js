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
                    fulltext: "&lt;p&gt;『富文本编辑器』&lt;/p&gt;"
                };
                break;
            //标题
            case 2:
                moduleData.content = {
                    title: "标题名称",
                    subtitle: "『副标题』",
                    direction: "left",
                    space:0
                };
                break;
            //自定义模块
            case 3:break;
            //产品
            case 4:
                moduleData.content={
                    layout:1,
                    showPrice:true,
                    showIco:true,
                    showName:1,
                    goodslist:[]
                }
                break;
            //产品列表（分组标签）
            case 5:
                moduleData.content={
                    layout:1,
                    showPrice:true,
                    showIco:true,
                    showName:true,
                    group:null,
                    goodsize:6,
                    goodslist:[
                        {
                            item_id: "1",
                            link: "#",
                            pic: "/Public/images/diy/goodsView1.jpg",
                            price: "100.00",
                            title: "第一个产品"
                        },
                        {
                            item_id: "2",
                            link: "#",
                            pic: "/Public/images/diy/goodsView2.jpg",
                            price: "200.00",
                            title: "第二个产品"
                        },
                        {
                            item_id: "3",
                            link: "#",
                            pic: "/Public/images/diy/goodsView3.jpg",
                            price: "300.00",
                            title: "第三个产品"
                        },
                        {
                            item_id: "4",
                            link: "#",
                            pic: "/Public/images/diy/goodsView4.jpg",
                            price: "400.00",
                            title: "第四个产品"
                        }
                    ]
                }
                break;
            //搜索
            case 6:break;
            //文本导航
            case 7:
                moduleData.content={
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
                    dataset:[
                        {
                            linkType:0,
                            link:"#",
                            title:"导航名称",
                            showtitle:"导航名称",
                            pic:"/Public/images/diy/waitupload.png"
                        },
                        {
                            linkType:0,
                            link:"#",
                            title:"导航名称",
                            showtitle:"导航名称",
                            pic:"/Public/images/diy/waitupload.png"
                        },
                        {
                            linkType:0,
                            link:"#",
                            title:"导航名称",
                            showtitle:"导航名称",
                            pic:"/Public/images/diy/waitupload.png"
                        },
                        {
                            linkType:0,
                            link:"#",
                            title:"导航名称",
                            showtitle:"导航名称",
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
                    margin:10,
                    dataset:[]
                }
                break;
            //分割线
            case 10:break;
            //辅助空白
            case 11:
                moduleData.content={
                    height:10
                }
                break;
            // 顶部菜单
            case 12:
                moduleData.content={
                    style:'0',
                    marginstyle:'0',
                    dataset:[
                        {
                            link: "/Shop/index",
                            linkType: 6,
                            showtitle: "首页",
                            title: "商城主页",
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
                            title: "商城主页",
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
            // 顶部菜单
            case 13:
                moduleData.content={
                    layout:'1',
                    dataset:[
                        {
                            linkType:0,
                            link:"#",
                            title:"导航名称",
                            pic:"/Public/images/diy/waitupload.png"
                        },
                        {
                            linkType:0,
                            link:"#",
                            title:"导航名称",
                            pic:"/Public/images/diy/waitupload.png"
                        },
                        {
                            linkType:0,
                            link:"#",
                            title:"导航名称",
                            pic:"/Public/images/diy/waitupload.png"
                        }
                    ]
                }
                break;
            // 视频
            case 14:
                moduleData.content={
                    website:''
                }
                break;
            // 音频
            case 15:
                moduleData.content = {
                    direct:0,
                    imgsrc:'',
                    audiosrc:''
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