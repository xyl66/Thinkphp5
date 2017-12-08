<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

include 'Uploader.class.php';
$base64 = 'upload';

switch (htmlspecialchars($_GET['action'])) {
case 'uploadimage':
	$config = array('pathFormat' => $CONFIG['imagePathFormat'], 'maxSize' => $CONFIG['imageMaxSize'], 'allowFiles' => $CONFIG['imageAllowFiles']);
	$fieldName = $CONFIG['imageFieldName'];
	break;

case 'uploadscrawl':
	$config = array('pathFormat' => $CONFIG['scrawlPathFormat'], 'maxSize' => $CONFIG['scrawlMaxSize'], 'allowFiles' => $CONFIG['scrawlAllowFiles'], 'oriName' => 'scrawl.png');
	$fieldName = $CONFIG['scrawlFieldName'];
	$base64 = 'base64';
	break;

case 'uploadvideo':
	$config = array('pathFormat' => $CONFIG['videoPathFormat'], 'maxSize' => $CONFIG['videoMaxSize'], 'allowFiles' => $CONFIG['videoAllowFiles']);
	$fieldName = $CONFIG['videoFieldName'];
	break;

case 'uploadfile':
default:
	$config = array('pathFormat' => $CONFIG['filePathFormat'], 'maxSize' => $CONFIG['fileMaxSize'], 'allowFiles' => $CONFIG['fileAllowFiles']);
	$fieldName = $CONFIG['fileFieldName'];
	break;
}

$up = new Uploader($fieldName, $config, $base64);
return json_encode($up->getFileInfo());

?>
