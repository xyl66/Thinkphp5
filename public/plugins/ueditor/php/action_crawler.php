<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

set_time_limit(0);
include 'Uploader.class.php';
$config = array('pathFormat' => $CONFIG['catcherPathFormat'], 'maxSize' => $CONFIG['catcherMaxSize'], 'allowFiles' => $CONFIG['catcherAllowFiles'], 'oriName' => 'remote.png');
$fieldName = $CONFIG['catcherFieldName'];
$list = array();

if (isset($_POST[$fieldName])) {
	$source = $_POST[$fieldName];
}
else {
	$source = $_GET[$fieldName];
}

foreach ($source as $imgUrl) {
	$item = new Uploader($imgUrl, $config, 'remote');
	$info = $item->getFileInfo();
	array_push($list, array('state' => $info['state'], 'url' => $info['url'], 'size' => $info['size'], 'title' => htmlspecialchars($info['title']), 'original' => htmlspecialchars($info['original']), 'source' => htmlspecialchars($imgUrl)));
}

return json_encode(array('state' => count($list) ? 'SUCCESS' : 'ERROR', 'list' => $list));

?>
