<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

$validateValue = $_REQUEST['fieldValue'];
$validateId = $_REQUEST['fieldId'];
$validateError = 'This username is already taken';
$validateSuccess = 'This username is available';
$arrayToJs = array();
$arrayToJs[0] = $validateId;

if ($validateValue == 'duncan') {
	$arrayToJs[1] = true;
	echo json_encode($arrayToJs);
}
else {
	for ($x = 0; $x < 1000000; $x++) {
		if ($x == 990000) {
			$arrayToJs[1] = false;
			echo json_encode($arrayToJs);
		}
	}
}

?>
