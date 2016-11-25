<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

$pkgName = 'JAMA';
$buildDir = substr(dirname(__FILE__), 0, -5 - strlen($pkgName));
chdir($buildDir);
$tarName = $pkgName . '.tar.gz';
$tarPath = $buildDir . $pkgName . '/downloads/' . $tarName;

if ($_GET['op'] == 'download') {
	require_once 'Archive/Tar.php';
	$tar = new Archive_Tar($tarPath);
	$files = glob($pkgName . '/*.php');
	$files = array_merge($files, glob($pkgName . '/*.TXT'));
	$files = array_merge($files, glob($pkgName . '/docs/*.php'));
	$files = array_merge($files, glob($pkgName . '/docs/includes/*.php'));
	$files = array_merge($files, glob($pkgName . '/examples/*.php'));
	$files = array_merge($files, glob($pkgName . '/tests/*.php'));
	$files = array_merge($files, glob($pkgName . '/utils/*.php'));
	$tar->create($files);
	$webDir = substr($_SERVER['PHP_SELF'], 0, -18);
	$urlPath = 'http://' . $_SERVER['HTTP_HOST'] . $webDir . '/downloads';
	header('Location: ' . $urlPath . '/' . $tarName);
}

include_once 'includes/header.php';
include_once 'includes/navbar.php';
echo '<p>' . "\n" . 'Download current version: ' . "\n" . '</p>' . "\n" . '<ul>' . "\n" . ' <li><a href=\'';
echo $_SERVER['PHP_SELF'] . '?op=download';
echo '\'>';
echo $tarName;
echo '</a></li>' . "\n" . '</ul>' . "\n" . '';
include_once 'includes/footer.php';

?>
