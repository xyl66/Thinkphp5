$(function() {
    $(".j-copy").zclip({
        path: '/Public/plugins/zclip/ZeroClipboard.swf',
        copy: function() {
            return $(this).data("copy");
        },
        afterCopy:function(){
            HYD.hint("success","内容已成功复制到您的剪贴板中");  
        }
    });
});