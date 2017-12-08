<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

class Uploader
{
	private $fileField;
	private $file;
	private $base64;
	private $config;
	private $oriName;
	private $fileName;
	private $fullName;
	private $filePath;
	private $fileSize;
	private $fileType;
	private $stateInfo;
	private $stateMap = array(0 => 'SUCCESS', 1 => '文件大小超出 upload_max_filesize 限制', 2 => '文件大小超出 MAX_FILE_SIZE 限制', 3 => '文件未被完整上传', 4 => '没有文件被上传', 5 => '上传文件为空', 'ERROR_TMP_FILE' => '临时文件错误', 'ERROR_TMP_FILE_NOT_FOUND' => '找不到临时文件', 'ERROR_SIZE_EXCEED' => '文件大小超出网站限制', 'ERROR_TYPE_NOT_ALLOWED' => '文件类型不允许', 'ERROR_CREATE_DIR' => '目录创建失败', 'ERROR_DIR_NOT_WRITEABLE' => '目录没有写权限', 'ERROR_FILE_MOVE' => '文件保存时出错', 'ERROR_FILE_NOT_FOUND' => '找不到上传文件', 'ERROR_WRITE_CONTENT' => '写入文件内容错误', 'ERROR_UNKNOWN' => '未知错误', 'ERROR_DEAD_LINK' => '链接不可用', 'ERROR_HTTP_LINK' => '链接不是http链接', 'ERROR_HTTP_CONTENTTYPE' => '链接contentType不正确');

	public function __construct($fileField, $config, $type = 'upload')
	{
		$this->fileField = $fileField;
		$this->config = $config;
		$this->type = $type;

		if ($type == 'remote') {
			$this->saveRemote();
		}
		else if ($type == 'base64') {
			$this->upBase64();
		}
		else {
			$this->upFile();
		}

		$this->stateMap['ERROR_TYPE_NOT_ALLOWED'] = iconv('unicode', 'utf-8', $this->stateMap['ERROR_TYPE_NOT_ALLOWED']);
	}

	private function upFile()
	{
		$file = $this->file = $_FILES[$this->fileField];

		if (!$file) {
			$this->stateInfo = $this->getStateInfo('ERROR_FILE_NOT_FOUND');
			return NULL;
		}

		if ($this->file['error']) {
			$this->stateInfo = $this->getStateInfo($file['error']);
			return NULL;
		}
		else if (!file_exists($file['tmp_name'])) {
			$this->stateInfo = $this->getStateInfo('ERROR_TMP_FILE_NOT_FOUND');
			return NULL;
		}
		else if (!is_uploaded_file($file['tmp_name'])) {
			$this->stateInfo = $this->getStateInfo('ERROR_TMPFILE');
			return NULL;
		}

		$this->oriName = $file['name'];
		$this->fileSize = $file['size'];
		$this->fileType = $this->getFileExt();
		$this->fullName = $this->getFullName();
		$this->filePath = $this->getFilePath();
		$this->fileName = $this->getFileName();
		$dirname = dirname($this->filePath);

		if (!$this->checkSize()) {
			$this->stateInfo = $this->getStateInfo('ERROR_SIZE_EXCEED');
			return NULL;
		}

		if (!$this->checkType()) {
			$this->stateInfo = $this->getStateInfo('ERROR_TYPE_NOT_ALLOWED');
			return NULL;
		}

		if (!file_exists($dirname) && !mkdir($dirname, 511, true)) {
			$this->stateInfo = $this->getStateInfo('ERROR_CREATE_DIR');
			return NULL;
		}
		else if (!is_writeable($dirname)) {
			$this->stateInfo = $this->getStateInfo('ERROR_DIR_NOT_WRITEABLE');
			return NULL;
		}

		if (!(move_uploaded_file($file['tmp_name'], $this->filePath) && file_exists($this->filePath))) {
			$this->stateInfo = $this->getStateInfo('ERROR_FILE_MOVE');
		}
		else {
			$this->stateInfo = $this->stateMap[0];
		}
	}

	private function upBase64()
	{
		$base64Data = $_POST[$this->fileField];
		$img = base64_decode($base64Data);
		$this->oriName = $this->config['oriName'];
		$this->fileSize = strlen($img);
		$this->fileType = $this->getFileExt();
		$this->fullName = $this->getFullName();
		$this->filePath = $this->getFilePath();
		$this->fileName = $this->getFileName();
		$dirname = dirname($this->filePath);

		if (!$this->checkSize()) {
			$this->stateInfo = $this->getStateInfo('ERROR_SIZE_EXCEED');
			return NULL;
		}

		if (!file_exists($dirname) && !mkdir($dirname, 511, true)) {
			$this->stateInfo = $this->getStateInfo('ERROR_CREATE_DIR');
			return NULL;
		}
		else if (!is_writeable($dirname)) {
			$this->stateInfo = $this->getStateInfo('ERROR_DIR_NOT_WRITEABLE');
			return NULL;
		}

		if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) {
			$this->stateInfo = $this->getStateInfo('ERROR_WRITE_CONTENT');
		}
		else {
			$this->stateInfo = $this->stateMap[0];
		}
	}

	private function saveRemote()
	{
		$imgUrl = htmlspecialchars($this->fileField);
		$imgUrl = str_replace('&amp;', '&', $imgUrl);

		if (strpos($imgUrl, 'http') !== 0) {
			$this->stateInfo = $this->getStateInfo('ERROR_HTTP_LINK');
			return NULL;
		}

		$heads = get_headers($imgUrl);
		if (!(stristr($heads[0], '200') && stristr($heads[0], 'OK'))) {
			$this->stateInfo = $this->getStateInfo('ERROR_DEAD_LINK');
			return NULL;
		}

		$fileType = strtolower(strrchr($imgUrl, '.'));
		if (!in_array($fileType, $this->config['allowFiles']) || stristr($heads['Content-Type'], 'image')) {
			$this->stateInfo = $this->getStateInfo('ERROR_HTTP_CONTENTTYPE');
			return NULL;
		}

		ob_start();
		$context = stream_context_create(array(
	'http' => array('follow_location' => false)
	));
		readfile($imgUrl, false, $context);
		$img = ob_get_contents();
		ob_end_clean();
		preg_match('/[\\/]([^\\/]*)[\\.]?[^\\.\\/]*$/', $imgUrl, $m);
		$this->oriName = $m ? $m[1] : '';
		$this->fileSize = strlen($img);
		$this->fileType = $this->getFileExt();
		$this->fullName = $this->getFullName();
		$this->filePath = $this->getFilePath();
		$this->fileName = $this->getFileName();
		$dirname = dirname($this->filePath);

		if (!$this->checkSize()) {
			$this->stateInfo = $this->getStateInfo('ERROR_SIZE_EXCEED');
			return NULL;
		}

		if (!file_exists($dirname) && !mkdir($dirname, 511, true)) {
			$this->stateInfo = $this->getStateInfo('ERROR_CREATE_DIR');
			return NULL;
		}
		else if (!is_writeable($dirname)) {
			$this->stateInfo = $this->getStateInfo('ERROR_DIR_NOT_WRITEABLE');
			return NULL;
		}

		if (!(file_put_contents($this->filePath, $img) && file_exists($this->filePath))) {
			$this->stateInfo = $this->getStateInfo('ERROR_WRITE_CONTENT');
		}
		else {
			$this->stateInfo = $this->stateMap[0];
		}
	}

	private function getStateInfo($errCode)
	{
		return !$this->stateMap[$errCode] ? $this->stateMap['ERROR_UNKNOWN'] : $this->stateMap[$errCode];
	}

	private function getFileExt()
	{
		return strtolower(strrchr($this->oriName, '.'));
	}

	private function getFullName()
	{
		$t = time();
		$d = explode('-', date('Y-y-m-d-H-i-s'));
		$format = $this->config['pathFormat'];
		$format = str_replace('{yyyy}', $d[0], $format);
		$format = str_replace('{yy}', $d[1], $format);
		$format = str_replace('{mm}', $d[2], $format);
		$format = str_replace('{dd}', $d[3], $format);
		$format = str_replace('{hh}', $d[4], $format);
		$format = str_replace('{ii}', $d[5], $format);
		$format = str_replace('{ss}', $d[6], $format);
		$format = str_replace('{time}', $t, $format);
		$oriName = substr($this->oriName, 0, strrpos($this->oriName, '.'));
		$oriName = preg_replace('/[\\|\\?"\\<\\>\\/\\*\\\\]+/', '', $oriName);
		$format = str_replace('{filename}', $oriName, $format);
		$randNum = rand(1, 10000000000) . rand(1, 10000000000);

		if (preg_match('/\\{rand\\:([\\d]*)\\}/i', $format, $matches)) {
			$format = preg_replace('/\\{rand\\:[\\d]*\\}/i', substr($randNum, 0, $matches[1]), $format);
		}

		$ext = $this->getFileExt();
		return $format . $ext;
	}

	private function getFileName()
	{
		return substr($this->filePath, strrpos($this->filePath, '/') + 1);
	}

	private function getFilePath()
	{
		$fullname = $this->fullName;
		$rootPath = $_SERVER['DOCUMENT_ROOT'];

		if (substr($fullname, 0, 1) != '/') {
			$fullname = '/' . $fullname;
		}

		return $rootPath . $fullname;
	}

	private function checkType()
	{
		return in_array($this->getFileExt(), $this->config['allowFiles']);
	}

	private function checkSize()
	{
		return $this->fileSize <= $this->config['maxSize'];
	}

	public function getFileInfo()
	{
		return array('state' => $this->stateInfo, 'url' => $this->fullName, 'title' => $this->fileName, 'original' => $this->oriName, 'type' => $this->fileType, 'size' => $this->fileSize);
	}
}


?>
