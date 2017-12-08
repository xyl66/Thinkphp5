UE.registerUI('diyimg',function(editor,uiName){
    var btn = new UE.ui.Button({
        name:uiName, //按钮的名字
        title:"添加图片",//提示
        cssRules :'background-position: -380px 0;',
        onclick:function(){
            //调用会员道选择图片组件
    		HYD.popbox.ImgPicker(function(data){
    			for(var i=0;i<data.length;i++){
                    editor.execCommand('inserthtml', '<p><img src="'+data[i]+'">​</p>');//向光标插入内容
                }
            });
        }
    });
    return btn;
},2);