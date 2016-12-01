/**
 * Created by F3233253 on 2016/11/16.
 */
new Vue({
    el:"#creatAccount",
    data:{
        user:{
            opassword:"",
            npassword1:"",
            npassword2:"",
        },
        warn:{
            opwd:false,
            npwd1:false,
            npwd2:false,
        },
    },
    methods:{
        check_null:function(event){
            this.warn.opwd=this.user.opassword.length<=0?true:false;
            this.warn.npwd1=this.user.npassword1.length<=0?true:false;
            if(this.user.npassword1.length>0){
                this.warn.npwd2=this.user.npassword1!=this.user.npassword2?true:false;
            }
            var user=this.user,areok=0;
            if(!this.warn.opwd&&!this.warn.npwd1&&!this.warn.npwd2){
                $.confirm({
                    title:'课程签到系统',
                    content: function () {
                        var self = this;
                        return $.ajax({
                            url: updateAccountURL,
                            type: 'post',
                            dataType:'json',
                            data: {
                                'user': user,
                            },
                        }).done(function (result) {
                            if(result.status=='success'){
                                self.setContent('<p>温馨提示：</p>');
                                self.setContentAppend('<p style="padding-left: 56px;">修改成功！</p>');
                                areok=1;
                            }
                            else{
                                self.setContent('<p>温馨提示：</p>');
                                self.setContentAppend('<p style="padding-left: 56px;">修改失败：'+result.msg+'</p>');
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
                                if(areok){
                                    window.location.href=logoutURL;
                                }
                            }
                        },
                    }
                });
            }
        },
    }
})