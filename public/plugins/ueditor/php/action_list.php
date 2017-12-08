<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

function getfiles($path, $allowFiles, &$files = array())
{
	if (!is_dir($path)) {
		return NULL;
	}

	if (substr($path, strlen($path) - 1) != '/') {
		$path .= '/';
	}

	$handle = opendir($path);

	while (false !== $file = readdir($handle)) {
		if (($file != '.') && ($file != '..')) {
			$path2 = $path . $file;

			if (is_dir($path2)) {
				getfiles($path2, $allowFiles, $files);
			}
			else if (preg_match('/\\.(' . $allowFiles . ')$/i', $file)) {
				$files[] = array('url' => substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])), 'mtime' => filemtime($path2));
			}
		}
	}

	return $files;
}

include 'Uploader.class.php';

switch ($_GET['action']) {
case 'listfile':
	$allowFiles = $CONFIG['fileManagerAllowFiles'];
	$listSize = $CONFIG['fileManagerListSize'];
	$path = $CONFIG['fileManagerListPath'];
	break;

case 'listimage':
default:
	$allowFiles = $CONFIG['imageManagerAllowFiles'];
	$listSize = $CONFIG['imageManagerListSize'];
	$path = $CONFIG['imageManagerListPath'];
}

$allowFiles = substr(str_replace('.', '|', join('', $allowFiles)), 1);
$size = (isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize);
$start = (isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0);
$end = $start + $size;
$path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == '/' ? '' : '/') . $path;
$files = getfiles($path, $allowFiles);

if (!count($files)) {
	return json_encode(array(
	'state' => 'no match file',
	'list'  => array(),
	'start' => $start,
	'total' => count($files)
	));
}

$len = count($files);
$i = min($end, $len) - 1;

for ($list = array(); $start <= $i; $i--) {
	$list[] = $files[$i];
}

$result = json_encode(array('state' => 'SUCCESS', 'list' => $list, 'start' => $start, 'total' => count($files)));
return $result;

?>
