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
//获取选择项
function ischeck(rule_id,index_id){
    var arr=this.group_edit.rules,str=0;
    this.sel_rule_index[index_id]=rule_id;
    for (var item in arr){
        if(arr[item]==rule_id){
            str=1;
            this.sel[index_id]=true;
        }
    }
    return str;
}
//编辑组件
Vue.component('my-component', {
    template: '<div>A custom component!</div>'
})
var vm=new Vue({
    el: '#app',
    data: {
        search:"",//搜索
        grouplist:{},
        group:{},
        rule:{},//权限
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
        group_edit:{
            title:"",
            status:1,
            rules:[],
        },
        group_add:{
            title:"",
            status:1,
            rules:[],
        },
        warn:{ //提示数据
            title:false,
            name:false,
            condition:false,
        },
    },
    computed:{
        filteredList: function () {
            var filter = Vue.filter('filterBy')
            return filter(this.group, this.search,'title');
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
            success: function(data){
                vm.$data['group'] = data.grouplist;
                vm.$data['rule'] = data.rulelist;
            }
        });
    },
    filters:{
        getStatus:function(status){
            return status?'启用':'禁用';
        },
        getStatusBtn:function(type){
            return type?'禁用':'启用';
        },
        getRule:function(rule_id){
            var arr=rule_id,str="";
            for(var item in arr) {
                if(arr[item]){
                str+=this.rule[item].title+',';
                }
            }
            return str;
        },
    },
    methods:{
        setvalue:function(index_id){
            this.group_edit=this.group[index_id];
            value2cache(course_edit_cache,this.group_edit);
        },
        setstatus:function(index,status){
            var t=this.group[index];
            t.status=!status;
            $.confirm({
                title:'课程签到系统',
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: upGroupStatusURL,
                        type: 'post',
                        dataType:'json',
                        data: {
                            'group': t,
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
            this.warn.title=this.group_edit.title.length<=0?true:false;
            if(!this.warn.title){
                var group=this.group_edit;
                $.confirm({
                    title:'课程签到系统',
                    content: function () {
                        var self = this;
                        return $.ajax({
                            url: upGroupURL,
                            type: 'post',
                            dataType:'json',
                            data: {
                                'group': group,
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
        addGroup:function(event){
            this.warn.title=this.group_add.title.length<=0?true:false;
            if(!this.warn.title){
                var group=this.group_add;
                $.confirm({
                    title:'课程签到系统',
                    content: function () {
                        var self = this;
                        return $.ajax({
                            url: addGroupURL,
                            type: 'post',
                            dataType:'json',
                            data: {
                                'group': group,
                            },
                        }).done(function (result) {
                            if(result.status=='success'){
                                vm.$data['group'] = result.data.grouplist; /*刷新数据*/
                                vm.$data['rule'] = result.data.rulelist;/*刷新数据*/
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
        initGroupadd:function(event){
            this.group_add.title="";
            this.group_add.rules=new Array(this.rule.length);
        },
        deleteGroup:function(index){
            var t=this.group[index];
            $.confirm({
                title:'课程签到系统',
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: deleteURL,
                        type: 'post',
                        dataType:'json',
                        data: {
                            'group': t,
                        },
                    }).done(function (result) {
                        if(result.status=='success'){
                            vm.$data['group'] = result.data.grouplist; /*刷新数据*/
                            vm.$data['rule'] = result.data.rulelist;/*刷新数据*/
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">删除成功！</p>');
                        }
                        else{
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">删除失败：'+result.msg+'</p>');
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
        delete_group_btn:function(index){
            $.alert({
                title: '课程签到系统',
                type: 'red',
                content: '您确认删除该权限？',
                buttons: {
                    '确定': {
                        btnClass: 'btn-danger',
                        action: function () {
                            vm.deleteGroup(index);
                        }
                    },
                    '取消': {
                        btnClass: 'btn-default',
                        action: function () {
                        }
                    },
                }
            });
        },
    }
})


