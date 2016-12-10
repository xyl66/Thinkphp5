/**
 * Created by F3233253 on 2016/11/3.
 */
function fillZero(v){
    if(v<10){v='0'+v;}
    return v;
}
//定义全局缓存
var course_edit_cache={name:"",
    course_time_start:"",
    course_time_end:"",
    course_place:"",};
//缓存赋值
function value2cache(cache,value) {
    cache.name=value['name'];
    cache.course_time_start=value['course_time_start'];
    cache.course_time_end=value['course_time_end'];
    cache.course_place=value['course_place'];
}
function cache2value(value,cache) {
    value['name']=cache.name;
    value['course_time_start']=cache.course_time_start;
    value['course_time_end']=cache.course_time_end;
    value['course_place']=cache.course_place;
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
//小于今天24:00
Vue.filter('before_today',function(datetime){
    var dd = new Date(), y = dd.getFullYear(), m = dd.getMonth()+1,d = dd.getDate();
    var date= y+"-";
    date=date+(m>10?m:"0"+m);
    date=date+"-"+(d>10?d:"0"+d);
    var course_time= Date.parse(date)/1000+16*3600;//获取今天00:00unix时间戳8:00-8*3600
    if(typeof(datetime)=='string'){
    if(datetime.indexOf("-")>0){
        datetime=Date.parse(datetime)/1000;//时间转化为时间戳
    }
    }
    return datetime<=course_time?true:false;
})
//大于当天0点
Vue.filter('after_today',function(datetime){
    var dd = new Date(), y = dd.getFullYear(), m = dd.getMonth()+1,d = dd.getDate();
    var date= y+"-";
    date=date+(m>10?m:"0"+m);
    date=date+"-"+(d>10?d:"0"+d);
    var course_time= Date.parse(date)/1000-8*3600;//获取今天00:00unix时间戳
    if(typeof(datetime)=='string') {
        if (datetime.indexOf("-") > 0) {
            datetime = Date.parse(datetime) / 1000;//时间转化为时间戳
        }
    }
    return datetime>=course_time?true:false;
})
//编辑组件
var CourseList=Vue.extend({
    props: ['ctodos'],
    template: '<table class="table table-hover table-bordered table-striped">'+
    '<thead>'+
    '<tr class="warning">'+
    '<th>序号</th>'+
    '<th>课程信息</th>'+
    '<th>课程日期 <span class="label label-default"></span></th>'+
    '<th>地点</th>'+
    '<th>操作</th>'+
    '</tr>'+
    '</thead>'+
    '<tbody>'+
    '<tr  v-for="(index,todo) in ctodos|filterBy search " >'+
    '<th scope="row" class="col-md-1">{{index+1}}</th>'+
    '<td class="col-md-3">'+
    '<a v-on:click="geturl(index)">{{todo.name}}</a>'+
    '<span v-if="todo.sign_count" class="label label-success">已签{{todo.sign_count}}</span>'+
    '</td>'+
    '<td class="col-md-3">{{todo.course_time_start|unix_to_datetime}}至{{todo.course_time_end|unix_to_datetime}} </td>'+
    '<td class="col-md-3">{{todo.course_place}}</td>'+
    '<td class="col-md-2" v-bind:course_id="todo.course_id">'+
    '<div class="row">'+
    '<div class="col-md-4"><button v-on:click="setvalue(index)"  type="button" class="edit btn-sm btn-info"  data-toggle="modal" data-target="#myModal" v-show="todo.course_time_start|after_today">编辑</button></div> <!--v-show="todo.course_time_start|after_today"-->'+
    '<div class="col-md-4"><button v-on:click="qrcode(index)" class="exportqrcode btn-sm btn-primary" data-toggle="modal" data-target="#myModal2">导出二维码</button></div>'+
    '<div class="col-md-4"><button v-on:click="export_file(index)" class="exportexcel btn-sm btn-success warning_3" v-show="todo.course_time_start|before_today">导出签到表</button></div>'+
    '</div>'+
    '</td>'+
    '</tr>'+
    '</tbody>'+
    '</table>',
})
var vm=new Vue({
    el: '#app',
    data: {
        selectdate: parseInt(new Date().valueOf()/1000),
        search:"",//搜索
        SignList:{},
        currentPage:1,
        pageSize:5,//每页显示个数
        isget:0,
        course:{},
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
        course_edit:{
            name:"",
            course_time_start:"",
            course_time_end:"",
            course_place:"",
        },
        warn:{ //提示数据
            name:false,
            time_start:false,
            time_end:false,
            place:false,
        },
    },
    computed:{
        todos:function(){
            var cpage=this.currentPage,copyArr,pageSize=this.pageSize,count=0;
            var sq=this.selectdate;
            if(this.isget){
            $.ajax({
                url:indexURL,
                /*async: false,*/
                type: 'post',
                dataType:'json',
                data: {
                    'course_time': sq
                },
                success: function(coursedata){
                    vm.$data['course'] = coursedata;
                    var q=q=this.filteredList;
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
                    vm.isget=0;
                  return curTodo;
                }
            });
            }
            else {
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
                if(q!=undefined){
                    var totalPage=Math.ceil(todo.length/pageSize);//总页数
                    if(totalPage>0){
                        boot(cpage,totalPage);
                    }
                }
                return curTodo;
            }
        },
        filteredList: function () {
            var filter = Vue.filter('filterBy')
            return filter(this.course, this.search,'name');
        },
        filterCourse:function (courselist) {
            var filter = Vue.filter('filterBy')
            return filter(courselist, this.search,'name');
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
            data: {
                'course_time': course_time
            },
            success: function(coursedata){
                vm.$data['course'] = coursedata;
            }
        });
    },
    filters:{
        getWeek:function(datetime){
            var d = new Date();
            d.setTime(datetime * 1000);
            var t=d.getDay();
            var Week=['星期天','星期一','星期二','星期三','星期四','星期五','星期六']
            return Week[d.getDay()];
        },
        unix_to_datetime:function(datetime){
        if(datetime==null){
            return;
         }
        if(typeof(datetime)=='string'){
            if(datetime.indexOf("-")>0||datetime==""){
                return datetime;
            }
         }
        var d = new Date();
        d.setTime(datetime * 1000);
        var Y,M,D,W,H,I,S;
        var Week=['星期天','星期一','星期二','星期三','星期四','星期五','星期六']
        Y=d.getFullYear();
        M=fillZero(d.getMonth()+1);
        D=fillZero(d.getDate());
        W=Week[d.getDay()];
        H=fillZero(d.getHours());
        I=fillZero(d.getMinutes());
        S=fillZero(d.getSeconds());
        return Y+'-'+M+'-'+D+' '+H+':'+I+':'+S;
    },
        edit_unix_to_datetime:{
            read:function(datetime){
                if(typeof(datetime)=='string'){
                    if(datetime.indexOf("-")>0||datetime==""){
                        return datetime;
                    }
                }
                var d = new Date();
                d.setTime(datetime * 1000);
                var Y,M,D,H,I;
                Y=d.getFullYear();
                M=fillZero(d.getMonth()+1);
                D=fillZero(d.getDate());
                H=fillZero(d.getHours());
                I=fillZero(d.getMinutes());
                var q=d.toLocaleString().replace(/:\d{1,2}$/,' ');
                $('.form_datetime_start').datetimepicker('setStartDate', q);
                return Y.toString()+'-'+M.toString()+'-'+D.toString()+' '+H.toString()+':'+I.toString();
        },
            write:function(datetime, oldDatetime){
                return datetime;
            }
        }
    },
    methods:{
        setvalue:function(index_id){
            index_id=this.currentPage>1?(this.currentPage-1)*10+index_id:index_id;
            this.course_edit=this.filteredList[index_id];
            value2cache(course_edit_cache,this.course_edit);
        },
        check_null:function(event){
            this.warn.name=this.coursename.length<=0?true:false;
            this.warn.time_start=this.coursetime_start.length<=0?true:false;
            this.warn.time_end=this.coursetime_end.length<=0?true:false;
            this.warn.place=this.courseplace.length<=0?true:false;
            if(!this.warn.name&&!this.warn.time_start&&!this.warn.time_end&&!this.warn.place){
                $('form').submit();
            }
        },
        checkendtime:function(event){
            var t=event.val();
        },
        courseUpdate:function(){
            var course=this.course_edit;
            $.confirm({
                title:'课程签到系统',
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: courseUpURL,
                        type: 'post',
                        dataType:'json',
                        data: {
                            'course': course,
                        },
                    }).done(function (result) {
                        if(result.status=='success'){
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">保存成功！</p>');
                        }
                        else{
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">保存失败：'+result.msg+'</p>');
                            cache2value(vm.$data.course_edit,course_edit_cache);
                            /*vm.$data.course_edit['name']=course_edit_cache.name;
                            vm.$data.course_edit['course_time_start']=course_edit_cache.course_time_start;
                            vm.$data.course_edit['course_time_end']=course_edit_cache.course_time_end;
                            vm.$data.course_edit['course_place']=course_edit_cache.course_place;*/
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
        geturl:function(index_id){
            index_id=this.currentPage>1?(this.currentPage-1)*10+index_id:index_id;
            var course=this.filteredList[index_id];
            $.confirm({
                title:'课程签到系统',
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: geturlURL,
                        type: 'post',
                        dataType:'json',
                        data: {
                            'courseid': course.course_id,
                        },
                    }).done(function (result) {
                        if(result.status=='success'){
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">当前课程签到地址：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;word-break:break-word;"><span id="url_span">'+result.url+'</span></p>');
                        }
                        else{
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">获取签到地址失败：'+result.msg+'</p>');
                        }
                    }).fail(function () {
                        self.setContent('<p>温馨提示：</p>');
                        self.setContentAppend('<p style="padding-left: 56px;">服务器响应失败.</p>');
                    });
                },
                buttons: {
                    '确定':{
                        btnClass: 'btn-info',
                        action:function () {
                        }
                    }
                }
            });
        },
        qrcode:function(index_id){
            index_id=this.currentPage>1?(this.currentPage-1)*10+index_id:index_id;
            var course=this.filteredList[index_id];
            $.ajax({
                url:signQrcodeURL,
                type: 'post',
                dataType:'json',
                data: {
                    'courseid': course.course_id,
                },
                success: function(result){
                    if(result.status=='success'){
                        vm.$data['qrcode_path']=result.qrcode_path;
                    }
                    else {
                        $.alert({
                            title: '课程签到系统',
                            type: 'red',
                            content: result.msg,
                        });
                    }
                }
            });
        },
        export_file:function(index_id){
            index_id=this.currentPage>1?(this.currentPage-1)*10+index_id:index_id;
            var course=this.filteredList[index_id];
            $.ajax({
                url:export_file_excelURL,
                type: 'post',
                dataType:'json',
                data: {
                    'courseid': course.course_id,
                },
                success: function(result){
                    if(result.status=='success'){
                       /* window.open(result.url);*/
                        var form=$("<form>");//定义一个form表单
                        form.attr("style","display:none");
                        form.attr("target","");
                        form.attr("method","post");
                        form.attr("action",result.url);
                        var input1=$("<input>");
                        input1.attr("type","hidden");
                        input1.attr("name",'course_id');
                        input1.attr("value",result.data);
                        $("body").append(form);//将表单放置在web中
                        form.append(input1);
                        form.submit();//表单提交
                    }
                    else {
                        $.alert({
                            title: '课程签到系统',
                            type: 'red',
                            content: result.msg,
                        });
                    }
                }
            });
        },
        getSignList:function(index_id){
            index_id=this.currentPage>1?(this.currentPage-1)*10+index_id:index_id;
            var course=this.filteredList[index_id];
            $.confirm({
                title:'课程签到系统',
                content: function () {
                    var self = this;
                    return $.ajax({
                        url: getSignListURL,
                        type: 'post',
                        dataType:'json',
                        data: {
                            'courseid': course.course_id,
                        },
                    }).done(function (result) {
                        if(result.status=='success'){
                            vm.$data['SignList']=result.data;
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">查看签到信息</p>');
                        }
                        else{
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">获取签到地址失败：'+result.msg+'</p>');
                        }
                    }).fail(function () {
                        self.setContent('<p>温馨提示：</p>');
                        self.setContentAppend('<p style="padding-left: 56px;">服务器响应失败.</p>');
                    });
                },
                buttons: {
                    '确定':{
                        btnClass: 'btn-info',
                        action:function () {
                            $('#showSign').click();
                        }
                    }
                }
            });
        },
    },
})


