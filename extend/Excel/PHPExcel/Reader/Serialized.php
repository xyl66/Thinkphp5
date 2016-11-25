<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

if (!defined('PHPEXCEL_ROOT')) {
	define('PHPEXCEL_ROOT', dirname(__FILE__) . '/../../');
	require PHPEXCEL_ROOT . 'PHPExcel/Autoloader.php';
	PHPExcel_Autoloader::Register();
	PHPExcel_Shared_ZipStreamWrapper::register();

	if (ini_get('mbstring.func_overload') & 2) {
		throw new Exception('Multibyte function overloading in PHP must be disabled for string functions (2).');
	}
}

class PHPExcel_Reader_Serialized implements PHPExcel_Reader_IReader
{
	public function canRead($pFilename)
	{
		if (!file_exists($pFilename)) {
			throw new Exception('Could not open ' . $pFilename . ' for reading! File does not exist.');
		}

		return $this->fileSupportsUnserializePHPExcel($pFilename);
	}

	public function load($pFilename)
	{
		if (!file_exists($pFilename)) {
			throw new Exception('Could not open ' . $pFilename . ' for reading! File does not exist.');
		}

		if (!$this->fileSupportsUnserializePHPExcel($pFilename)) {
			throw new Exception('Invalid file format for PHPExcel_Reader_Serialized: ' . $pFilename . '.');
		}

		return $this->_loadSerialized($pFilename);
	}

	private function _loadSerialized($pFilename)
	{
		$xmlData = simplexml_load_string(file_get_contents('zip://' . $pFilename . '#phpexcel.xml'));
		$excel = unserialize(base64_decode((string) $xmlData->data));

		for ($i = 0; $i < $excel->getSheetCount(); ++$i) {
			for ($j = 0; $j < $excel->getSheet($i)->getDrawingCollection()->count(); ++$j) {
				if ($excel->getSheet($i)->getDrawingCollection()->offsetGet($j) instanceof PHPExcl_Worksheet_BaseDrawing) {
					$imgTemp = &$excel->getSheet($i)->getDrawingCollection()->offsetGet($j);
					$imgTemp->setPath('zip://' . $pFilename . '#media/' . $imgTemp->getFilename(), false);
				}
			}
		}

		return $excel;
	}

	public function fileSupportsUnserializePHPExcel($pFilename = '')
	{
		if (!file_exists($pFilename)) {
			throw new Exception('Could not open ' . $pFilename . ' for reading! File does not exist.');
		}

		return PHPExcel_Shared_File::file_exists('zip://' . $pFilename . '#phpexcel.xml');
	}
}

?>
