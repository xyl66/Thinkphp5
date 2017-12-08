<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

$htmlData = '';

if (!empty($_POST['content1'])) {
	if (get_magic_quotes_gpc()) {
		$htmlData = stripslashes($_POST['content1']);
	}
	else {
		$htmlData = $_POST['content1'];
	}
}

echo '<!doctype html>' . "\r\n" . '<html>' . "\r\n" . '<head>' . "\r\n" . '	<meta charset="utf-8" />' . "\r\n" . '	<title>KindEditor PHP</title>' . "\r\n" . '	<link rel="stylesheet" href="../themes/default/default.css" />' . "\r\n" . '	<link rel="stylesheet" href="../plugins/code/prettify.css" />' . "\r\n" . '	<script charset="utf-8" src="../kindeditor.js"></script>' . "\r\n" . '	<script charset="utf-8" src="../lang/zh_CN.js"></script>' . "\r\n" . '	<script charset="utf-8" src="../plugins/code/prettify.js"></script>' . "\r\n" . '	<script>' . "\r\n" . '		KindEditor.ready(function(K) {' . "\r\n" . '			var editor1 = K.create(\'textarea[name="content1"]\', {' . "\r\n" . '				cssPath : \'../plugins/code/prettify.css\',' . "\r\n" . '				uploadJson : \'../php/upload_json.php\',' . "\r\n" . '				fileManagerJson : \'../php/file_manager_json.php\',' . "\r\n" . '				allowFileManager : true,' . "\r\n" . '				afterCreate : function() {' . "\r\n" . '					var self = this;' . "\r\n" . '					K.ctrl(document, 13, function() {' . "\r\n" . '						self.sync();' . "\r\n" . '						K(\'form[name=example]\')[0].submit();' . "\r\n" . '					});' . "\r\n" . '					K.ctrl(self.edit.doc, 13, function() {' . "\r\n" . '						self.sync();' . "\r\n" . '						K(\'form[name=example]\')[0].submit();' . "\r\n" . '					});' . "\r\n" . '				}' . "\r\n" . '			});' . "\r\n" . '			prettyPrint();' . "\r\n" . '		});' . "\r\n" . '	</script>' . "\r\n" . '</head>' . "\r\n" . '<body>' . "\r\n" . '	';
echo $htmlData;
echo '	<form name="example" method="post" action="demo.php">' . "\r\n" . '		<textarea name="content1" style="width:700px;height:200px;visibility:hidden;">';
echo htmlspecialchars($htmlData);
echo '</textarea>' . "\r\n" . '		<br />' . "\r\n" . '		<input type="submit" name="button" value="提交内容" /> (提交快捷键: Ctrl + Enter)' . "\r\n" . '	</form>' . "\r\n" . '</body>' . "\r\n" . '</html>' . "\r\n" . '' . "\r\n" . '';

?>
