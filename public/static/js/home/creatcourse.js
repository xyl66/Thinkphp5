/**
 * Created by F3233253 on 2016/11/3.
 */
Vue.filter('datetime_to_unix',function(datetime){
    var t=new Date(datetime).valueOf();
    return t/1000?t/1000:'';
})
Vue.filter('unix_to_datetime',function(datetime){
    var newDate = new Date();
    newDate.setTime(datetime * 1000);
    return newDate.toGMTString();
})

new Vue({
    el: '#creatCourse',
    data: {
        course:{
            name:"",
            course_time_start: "",
            course_time_end:"",
            course_place:"",
        },
        warn:{
            name:false,
            time_start:false,
            time_end:false,
            place:false,
        },
    },
    methods:{
        check_null:function(event){
            var course=this.course;
            this.warn.name=course.name.length<=0?true:false;
            this.warn.time_start=course.course_time_start.length<=0?true:false;
            this.warn.time_end=course.course_time_end.length<=0?true:false;
            this.warn.place=course.course_place.length<=0?true:false;
            if(!this.warn.name&&!this.warn.time_start&&!this.warn.time_end&&!this.warn.place){
                //$('form').submit();
                $.confirm({
                    title:'课程签到系统',
                    content: function () {
                        var self = this;
                        return $.ajax({
                            url: courseCreatURL,
                            type: 'post',
                            dataType:'json',
                            data: {
                                'course': course,
                            },
                        }).done(function (result) {
                            if(result.status=='success'){
                                self.setContent('<p>温馨提示：</p>');
                                self.setContentAppend('<p style="padding-left: 56px;">创建成功！</p>');
                            }
                            else{
                                self.setContent('<p>温馨提示：</p>');
                                self.setContentAppend('<p style="padding-left: 56px;">创建失败：'+result.msg+'</p>');
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
                            }
                        },
                        '返回首页': {
                            action: function () {
                                window.location.href=indexURL;
                            }
                        },
                    }
                });
            }
        },
        checkendtime:function(event){
            var t=event.val();
        }
    }
})
$(function(){

})