/**
 * Created by F3233253 on 2016/11/16.
 */
function setCookie(c_name,value,expiredays)
{
    var exdate=new Date()
    exdate.setDate(exdate.getDate()+expiredays)
    document.cookie=c_name+ "=" +escape(value)+ ((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
}
function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
        return unescape(arr[2]);
    else
        return null;
}
function delCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null)
        document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}
new Vue({
    el:'#lgapp',
    data:{
        user:{
            account:getCookie("cache_account"),
            pwd:getCookie("cache_pwd"),
        },
        rmpwd:getCookie("cache_pwd_checked"),
    },
    methods:{
        login:function () {
            var user=this.user,rd_remember=this.rmpwd;
            $.ajax({
                url:loginURL,
                type: 'post',
                dataType:'json',
                data: {
                    'user': user,
                },
                success: function(result){
                    if(result.status==1){
                        setCookie("cache_account",user.account,30);//记住账号

                        //记住密码
                        if(rd_remember=='true'){
                            setCookie("cache_pwd",user.pwd,30);
                            setCookie("cache_pwd_checked",true,30);//勾选状态
                        }
                        else{
                            setCookie("cache_pwd","",30);
                            setCookie("cache_pwd_checked",false,30);//勾选状态
                        }
                        window.location.href=result.url;
                    }
                    else {
                        $.alert({
                            title: '课程签到系统',
                            type: 'red',
                            content: result.msg,
                        });
                    }
                }
            })
        },
        fprgotpwd:function(){
            $.alert({
                title: '课程签到系统',
                type: 'red',
                content: '请联系管理员重置密码！',
            });
        },
        regit:function(){
            $.alert({
                title: '课程签到系统',
                type: 'red',
                content: '请向管理员申请账号！',
            });
        }
    },
    filters:{
        getstatus:function (rmp) {
            if(rmp=='true'){
                return true;
            }
            else{
                return false;
            }
        },
    }
})
//记住密码脚本
$("[name='my-checkbox']").bootstrapSwitch();
$('input[name="my-checkbox"]').on('switchChange.bootstrapSwitch', function(event, state) {
    console.log(this); // DOM element
    console.log(event); // jQuery event
    console.log(state); // true | false
    $("#rmpwd").val(state).show().focus().blur().hide();
});