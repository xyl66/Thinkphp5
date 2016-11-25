<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

include_once 'includes/header.php';
include_once 'includes/navbar.php';
echo '<p>' . "\n" . 'Source Listing:' . "\n" . '</p>' . "\n" . '<ul>' . "\n" . '  ';
chdir('../');
$files = glob('*.php');
$files = array_merge($files, glob('util/*.php'));

foreach ($files as $fileName) {
	echo '  	<li><a href="package.php?view=';
	echo sha1($fileName);
	echo '">';
	echo $fileName;
	echo '</a>&nbsp;-&nbsp;';
	echo date('F d Y - g:i a', filemtime($fileName));
	echo '</li>' . "\n" . '    ';
}

echo '</ul>' . "\n" . '';

if (isset($_REQUEST['view'])) {
	$hash = $_REQUEST['view'];
	$n = array_search($hash, array_map(sha1, $files));
	$fileName = $files[$n];
	echo '  <hr />  ' . "\n" . '	Viewing: ';
	echo $fileName;
	echo '	' . "\n" . '	<hr />' . "\n" . '	';
	highlight_file($fileName);
	echo '	<hr />' . "\n" . '';
}

include_once 'includes/footer.php';
echo "\n";

?>
