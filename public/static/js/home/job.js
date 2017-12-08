/**
 * Created by F3233253 on 2016/11/3.
 */
Vue.filter('datetime_to_unix', function(datetime) {
    var t = new Date(datetime).valueOf();
    return t / 1000 ? t / 1000 : '';
})
Vue.filter('unix_to_datetime', function(datetime) {
    var newDate = new Date();
    newDate.setTime(datetime * 1000);
    return newDate.toGMTString();
})

new Vue({
    el: '#app',
    data: {
        qrcode_path: "", //二维码地址
    },
    methods: {
        qrcode: function() {
            var _this = this;
            $.ajax({
                url: signQrcodeURL,
                type: 'post',
                dataType: 'json',
                success: function(result) {
                    if (result.status == 'success') {
                        _this.qrcode_path = result.qrcode_path;
                    } else {
                        $.alert({
                            title: '课程签到系统',
                            type: 'red',
                            content: result.msg,
                        });
                    }
                }
            });
        },
    }
})
$(function() {

})