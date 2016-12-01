/**
 * Created by F3233253 on 2016/11/3.
 */
function fillZero(v){
    if(v<10){v='0'+v;}
    return v;
}
function getStatus(status){
    return status?'启用':'禁用';
}
//定义全局缓存
var course_edit_cache={title:"",
    name:"",
    condition:"",
    };
//缓存赋值
function value2cache(cache,value) {
    cache.title=value['title'];
    cache.name=value['name'];
    cache.condition=value['condition'];
}
function cache2value(value,cache) {
    value['title']=cache.title;
    value['name']=cache.name;
    value['condition']=cache.condition;
}
var boot=function(currentPage,totalPages){
    $( '#example' ).bootstrapPaginator({
        currentPage: currentPage,   //当前页
        totalPages: totalPages,     //总页数
        bootstrapMajorVersion: 3,               //兼容Bootstrap3.x版本
        tooltipTitles: function (type, page) {
            switch (type) {
                case "first" :
                    return "第一页" ;
                case "prev" :
                    return "上一页" ;
                case "next" :
                    return "下一页" ;
                case "last" :
                    return "最后一页" ;
                case "page" :
                    return page;
            }
            return "" ;
        },
        onPageClicked: function (event, originalEvent, type, page) {
            $("#currentPage").val(page).show().focus().blur().hide();
            /*$.get( '/Home/GetPaginationData' , { currentPage: page, pageSize:10 }, function (view) {
                $( '#tableTest' ).html(view);
            });*/
        }
    });
}

//编辑组件
Vue.component('my-component', {
    template: '<div>A custom component!</div>'
})
var vm=new Vue({
    el: '#app',
    data: {
        selectdate: parseInt(new Date().valueOf()/1000),
        search:"",//搜索
        currentPage:1,
        pageSize:10,//每页显示个数
        isget:0,
        rule:{},
        todos:{},
        qrcode_path:"",//二维码地址
        trclass:[
            'active',
            '',
            'success',
            '',
            'info',
            '',
            'warning',
            '',
            'danger',
        ],
        rule_edit:{
            name:"",
            title:"",
            type:1,
            status:1,
            condition:"",
        },
        rule_add:{
            name:"",
            title:"",
            type:1,
            status:1,
            condition:"",
        },
        warn:{ //提示数据
            title:false,
            name:false,
            condition:false,
        },
    },
    computed:{
        todos:function(){
            var cpage=this.currentPage,copyArr,pageSize=this.pageSize,count=0;
            var sq=this.selectdate;
            var q=this.filteredList;
            var curTodo=todo=q==undefined?{}:q;
            //分页 每页数据pageSize
            copyArr=todo.length>0?todo.slice():{};
            while(copyArr.length>pageSize)
            {
                curTodo=copyArr.splice(0,pageSize);
                if(++count==cpage){
                    break
                }
            }
            if(count!=cpage){
                curTodo=copyArr;
            };
            if(q!=undefined&&q.length>0){
                var totalPage=Math.ceil(todo.length/pageSize);//总页数
                if(totalPage>0){
                    boot(cpage,totalPage);
                }
            }
            return curTodo;
        },
        filteredList: function () {
            var filter = Vue.filter('filterBy')
            return filter(this.rule, this.search,'title');
        },
        filterCourse:function (courselist) {
            var filter = Vue.filter('filterBy')
            return filter(courselist, this.search,'title');
        }
    },
    ready:function() {
        var dd = new Date(), y = dd.getFullYear(), m = dd.getMonth()+1,d = dd.getDate();
        var date= y+"-";
        date=date+(m>10?m:"0"+m);
        date=date+"-"+(d>10?d:"0"+d);
        var course_time= Date.parse(date)/1000;
        $.ajax({
            url:indexURL,
            type: 'post',
            dataType:'json',
            success: function(ruledata){
                vm.$data['rule'] = ruledata;
            }
        });
    },
    filters:{
        getStatus:function(status){
            return status?'启用':'禁用';
        },
        getStatusBtn:function(type){
            return type?'禁用':'启用';
        }
    },
    methods:{
        setvalue:function(index_id){
            this.rule_edit=this.rule[index_id];
            value2cache(course_edit_cache,this.rule_edit);
        },
        setstatus:function(index,status){
            var t=this.rule[index];
            t.status=!status;
            $.confirm({
                title:'课程签到系统',
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: upRuleStatusURL,
                        type: 'post',
                        dataType:'json',
                        data: {
                            'rule': t,
                        },
                    }).done(function (result) {
                        if(result.status=='success'){
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">'+getStatus(!status)+'成功！</p>');
                        }
                        else{
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">状态修改失败：'+result.msg+'</p>');
                        }
                    }).fail(function () {
                        self.setContent('<p>温馨提示：</p>');
                        self.setContentAppend('<p style="padding-left: 56px;">服务器响应失败.</p>');
                    });
                },
                buttons: {
                    '确定': {
                        btnClass: 'btn-info',
                        action: function () {
                            $("button[class='close']").click();
                        }
                    },
                }
            });
        },
        check_null:function(event){
            this.warn.title=this.rule_edit.title.length<=0?true:false;
            this.warn.name=this.rule_edit.name.length<=0?true:false;
            if(!this.warn.name&&!this.warn.title){
                var rule=this.rule_edit;
                $.confirm({
                    title:'课程签到系统',
                    content: function () {
                        var self = this;
                        return $.ajax({
                            url: upRuleURL,
                            type: 'post',
                            dataType:'json',
                            data: {
                                'rule': rule,
                            },
                        }).done(function (result) {
                            if(result.status=='success'){
                                self.setContent('<p>温馨提示：</p>');
                                self.setContentAppend('<p style="padding-left: 56px;">保存成功！</p>');
                            }
                            else{
                                self.setContent('<p>温馨提示：</p>');
                                self.setContentAppend('<p style="padding-left: 56px;">保存失败：'+result.msg+'</p>');
                            }
                        }).fail(function () {
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">服务器响应失败.</p>');
                        });
                    },
                    buttons: {
                        '确定': {
                            btnClass: 'btn-info',
                            action: function () {
                                $("button[class='close']").click();
                            }
                        },
                    }
                });
            }
        },
        addRule:function(event){
            this.warn.title=this.rule_add.title.length<=0?true:false;
            this.warn.name=this.rule_add.name.length<=0?true:false;
            if(!this.warn.name&&!this.warn.title){
                var rule=this.rule_add;
                $.confirm({
                    title:'课程签到系统',
                    content: function () {
                        var self = this;
                        return $.ajax({
                            url: addRuleURL,
                            type: 'post',
                            dataType:'json',
                            data: {
                                'rule': rule,
                            },
                        }).done(function (result) {
                            if(result.status=='success'){
                                vm.$data['rule'] = result.data;
                                self.setContent('<p>温馨提示：</p>');
                                self.setContentAppend('<p style="padding-left: 56px;">添加成功！</p>');
                            }
                            else{
                                self.setContent('<p>温馨提示：</p>');
                                self.setContentAppend('<p style="padding-left: 56px;">添加失败：'+result.msg+'</p>');
                            }
                        }).fail(function () {
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">服务器响应失败.</p>');
                        });
                    },
                    buttons: {
                        '确定': {
                            btnClass: 'btn-info',
                            action: function () {
                                $("button[class='close']").click();
                            }
                        },
                    }
                });
            }
        },
        checkendtime:function(event){
            var t=event.val();
        },
    }
})


