<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

$nameValue = $_REQUEST['firstname'];
$userValue = $_REQUEST['user'];
$validateError = 'This username is already taken';
$validateSuccess = 'This username is available';
$arrayToJs = array();
$arrayToJs[0] = array();
$arrayToJs[1] = array();

if ($userValue == 'karnius') {
	$arrayToJs[0][0] = 'user';
	$arrayToJs[0][1] = true;
	$arrayToJs[0][2] = 'This user is available';
}
else {
	$arrayToJs[0][0] = 'user';
	$arrayToJs[0][1] = false;
	$arrayToJs[0][2] = 'This user is already taken';
}

if ($nameValue == 'duncan') {
	$arrayToJs[1][0] = 'firstname';
	$arrayToJs[1][1] = true;
}
else {
	$arrayToJs[1][0] = 'firstname';
	$arrayToJs[1][1] = false;
	$arrayToJs[1][2] = 'This name is already taken';
}

echo json_encode($arrayToJs);

?>
