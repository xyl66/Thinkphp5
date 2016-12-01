/**
 * Created by F3233253 on 2016/11/3.
 */
function fillZero(v){
    if(v<10){v='0'+v;}
    return v;
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
var vm=new Vue({
    el: '#app',
    data: {
        search:"",//搜索
        currentPage:1,//当前页
        pageSize:10,//每页显示个数
        pwdread:1,
        userlist:{},
        grouplist:{},//用户组
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
        user_edit:{
            admin_id:"",
            account:"",
            password:"",
            group_id:"",
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
            return filter(this.userlist,this.search,'account');
        },
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
                vm.$data['userlist'] = data.userlist;
                vm.$data['grouplist'] = data.grouplist;
            }
        });
    },
    filters:{
        unix_to_datetime:function(datetime) {
            if (datetime == null) {
                return;
            }
            if (typeof(datetime) == 'string') {
                if (datetime.indexOf("-") > 0 || datetime == "") {
                    return datetime;
                }
            }
            var d = new Date();
            d.setTime(datetime * 1000);
            var Y, M, D, W, H, I, S;
            var Week = ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六']
            Y = d.getFullYear();
            M = fillZero(d.getMonth() + 1);
            D = fillZero(d.getDate());
            W = Week[d.getDay()];
            H = fillZero(d.getHours());
            I = fillZero(d.getMinutes());
            S = fillZero(d.getSeconds());
            return Y + '-' + M + '-' + D + ' ' + H + ':' + I + ':' + S;
        },
    },
    methods:{
        setvalue:function(index_id){
            index_id=this.currentPage>1?(this.currentPage-1)*10+index_id:index_id;
            this.user_edit=this.filteredList[index_id];
            document.getElementById("uppassword").disabled=true;

        },
        check_null:function(event){
            this.warn.title=this.user_edit.title.length<=0?true:false;
            if(!this.warn.title){
                var user=this.user_edit;
                $.confirm({
                    title:'课程签到系统',
                    content: function () {
                        var self = this;
                        return $.ajax({
                            url: upUserURL,
                            type: 'post',
                            dataType:'json',
                            data: {
                                'user': user,
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
        editpwd:function(event){
            if(event.target.disabled){ //不能选中时弹出是否确认修改框
                $.alert({
                    title: '课程签到系统',
                    type: 'red',
                    content: '确定修改密码？',
                    buttons: {
                        确定: {
                            text: '确定',
                            btnClass: 'btn-warning',
                            action:function(){
                                event.target.disabled=false;
                                event.target.focus();
                            }
                        },
                        取消: {
                            text: '取消',
                        }
                    }
                });
            }
        },
    }
})


