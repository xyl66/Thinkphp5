HYD.msgSend=HYD.msgSend?HYD.msgSend:{};

/*
 * 重新计算textarea的短信字数和条数
 * @Author  chenjie
*/
HYD.msgSend.textareaRecount=function() {
    var val=$("#j-textarea-content").val(),
        len=val.length;//字数

    $("#j-textareCount-size").text(len);//更新字数

    //更新条数统计
    if(len>68){
        $("#j-textareCount-strip").text("2");
    }
    else{
        $("#j-textareCount-strip").text("1");
    }

    //超过125个字后面的内容将被删掉
    if(len>500){
        val=val.substring(0,500)
        $("#j-textarea-content").val(val);
        arguments.callee();
    }

    $("#j-materialPrev").find('.single-summary').text(val);//更新手机视图
};

/*
 * 向textarea光标处插入文本
 * @Author  chenjie
 * @param obj textarea
 * @param str 要插入的文本
*/
HYD.msgSend.textareaInsertTxt=function(obj, str) {
    if (document.selection) {
        var sel = document.selection.createRange();
        sel.text = str;
    } else if (typeof obj.selectionStart === 'number' && typeof obj.selectionEnd === 'number') {
        var startPos = obj.selectionStart,
            endPos = obj.selectionEnd,
            cursorPos = startPos,
            tmpStr = obj.value;
        obj.value = tmpStr.substring(0, startPos) + str + tmpStr.substring(endPos, tmpStr.length);
        cursorPos += str.length;
        obj.selectionStart = obj.selectionEnd = cursorPos;
    } else {
        obj.value += str;
    }

    HYD.msgSend.textareaRecount();
};

$(function() {
    // =======================================step2========================================
    //提交页面
    $('.J-step3').click(function(i){
        var OsmsTitle = $('.J-smsTitle');
        var OsmsContent = $('.J-smsContent');
        var smsTitle = OsmsTitle.val();
        var smsContent = OsmsContent.val();

        if(!smsTitle){
            HYD.FormShowError(OsmsTitle, '请填写群发标题!');
            return false;
        }
        if(!smsContent){
            HYD.FormShowError(OsmsContent, '请填写短信内容!');
            return false;
        }

         //异步提交
        $.ajax({
            url: '',
            type: 'post',
            dataType: 'json',
            data: {
                act: 'save',
                title: smsTitle,
                content: smsContent,
            },
            success: function(data) {
                if (data.status == 1) {
                    //跳转
                    window.location.href = "/SmsMarketing/massSend_step3"
                } else {
                    HYD.hint("danger", "对不起，" + data.msg);
                }
            }
        });


    })
    //向textare中插入文本
    // $(".j-msgAddon-txt").click(function(){
    // 	var txt=$(this).data("con");
    // 	HYD.msgSend.textareaInsertTxt($("#j-textarea-content")[0],txt);
    // 	return false;
    // });

    //编辑器实例化
    var ue = UE.getEditor('Js_editorArea',{
        toolbars: [
            ['RemoveFormat','Link', 'Unlink']
        ],
        maximumWords:600 //限制编辑器输入的字符长度
    });
    var JtextareaContent = $('#j-textarea-content').val();
    ue.addListener( "selectionchange", function () {
        var _html = UE.getEditor('Js_editorArea').getContent();
        var txt=$(".j-msgAddon-txt").data("con");
        $('#j-textarea-content').val(_html);
        HYD.msgSend.textareaInsertTxt(_html,txt);
        return false;
    });
    if(JtextareaContent !=0){
        ue.addListener("ready",function(){ue.setContent(JtextareaContent)}); 
    }
    $('.j-msgAddon-txt').click(function(event) {
        var me = $(this);
        var con = me.data('con');
        var ueCon = ue.getContent();
        ueCon = ueCon+con;
        ue.setContent(ueCon);
        $('#j-textarea-content').val(ueCon);
    });

    //向textare中插入短网址
    $(".j-msgAddon-url").click(function(){
        var html=$("#tpl_massSend_step2_inserturl").html(),
            $render=$(html),
            shorturl="";//短网址

        $.jBox.show({
            title: "插入短网址",
            content:$render,
            onOpen:function(){
                //生成短网址
                $(".j-createurl").click(function(){
                    var urlOrigin=$render.find("input[name='urlorigion']").val();//原始网址

                    //异步生成短网址
                    $.ajax({
                        url: '',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            url: urlOrigin
                        },
                        success: function(data) {
                            if (data.status == 1) {
                                shorturl = data.shorturl
                                $(".j-shorturl-txt").text(shorturl);
                            } else {
                                HYD.hint("danger", "对不起，短网址生成失败：" + data.msg);
                            }
                        }
                    });

                    return false;
                });
            },
            btnOK: {
                text:"插入短网址",
                onBtnClick: function(jbox) {
                    HYD.msgSend.textareaInsertTxt($("#j-textarea-content")[0],shorturl);//插入短网址
                    $.jBox.close(jbox);
                }
            }
        });

        return false;
    });
    
    //手动输入文字重新计算textarea的短信字数和条数
    $("#j-textarea-content").keyup(HYD.msgSend.textareaRecount);

    // 向短信内容中添加短信模板
    $("#j_moudle_btn").click(function(){
        //异步弹窗表格
        HYD.ajaxPopTable({
            title:"选择短信模板",
            url:"/SmsMarketing/massSend_step2",
            // url:"/files/test_temp.json",
            data:{act:'getTemp'},
            tpl:$("#tpl_sms_templates").html(),
            onOpen:function(jbox){
                //点击使用
                $(".j_moudle_duanxin>li>a.btn").live({
                    click:function(){
                        var text=$(this).prev("span").text();
                        $("#j-textarea-content").val(text);
                        $(".single-summary").text(text);
                        $.jBox.close(jbox);
                      }
                })
            },
        });
        return false;
    });

    

    HYD.msgSend.textareaRecount();//页面初始化计算字数和条数

});