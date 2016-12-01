/**
 * Created by F3233253 on 2016/11/14.
 */
new Vue({
    el: '#signApp',
    data: {
        sign:{
            course_id:"",
            user_id:"",
            user_name:"",
            user_department:"",
            sign_place:"",
        },
        course_time:{
            start:"",
            end:"",
        }
    },
    methods:{
        signsave:function(event){
            var sign=this.sign;
            $.confirm({
                title:'课程签到系统',
                icon: 'fa fa-rocket',
                content: function () {
                    var self = this;
                    return $.ajax({
                        url:signURL,
                        type: 'post',
                        dataType:'json',
                        data: {
                            'sign': sign,
                        },
                    }).done(function (result) {
                        if(result.status=='success'){
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">恭喜您签到成功！</p>');
                        }
                        else{
                            self.setContent('<p>温馨提示：</p>');
                            self.setContentAppend('<p style="padding-left: 56px;">签到失败：'+result.msg+'</p>');
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
                }
            });
        },
    },
    computed:{
        getSubmit:function(){
            var nowtime=Date.parse(new Date())/1000;
            var datetime_start=Date.parse(this.course_time.start)/1000,datetime_end=Date.parse(this.course_time.end)/1000;
            if(nowtime<=datetime_start+15*60&&nowtime>=datetime_start-15*60){
                return '签到';
            }
            if(nowtime<=datetime_end+15*60&&nowtime>=datetime_end-15*60){
                return '签退';
            }
            return 'submit';
        }
    }
})
