<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

class PHPExcel_Writer_Serialized implements PHPExcel_Writer_IWriter
{
	/**
	 * Private PHPExcel
	 *
	 * @var PHPExcel
	 */
	private $_spreadSheet;

	public function __construct(PHPExcel $pPHPExcel = NULL)
	{
		$this->setPHPExcel($pPHPExcel);
	}

	public function save($pFilename = NULL)
	{
		if (!is_null($this->_spreadSheet)) {
			$this->_spreadSheet->garbageCollect();

			foreach ($this->_spreadSheet->getAllSheets() as $sheet) {
				$sheet->garbageCollect();
			}

			$objZip = new ZipArchive();

			if ($objZip->open($pFilename, ZIPARCHIVE::OVERWRITE) !== true) {
				if ($objZip->open($pFilename, ZIPARCHIVE::CREATE) !== true) {
					throw new Exception('Could not open ' . $pFilename . ' for writing.');
				}
			}

			$sheetCount = $this->_spreadSheet->getSheetCount();

			for ($i = 0; $i < $sheetCount; ++$i) {
				for ($j = 0; $j < $this->_spreadSheet->getSheet($i)->getDrawingCollection()->count(); ++$j) {
					if ($this->_spreadSheet->getSheet($i)->getDrawingCollection()->offsetGet($j) instanceof PHPExcel_Worksheet_BaseDrawing) {
						$imgTemp = $this->_spreadSheet->getSheet($i)->getDrawingCollection()->offsetGet($j);
						$objZip->addFromString('media/' . $imgTemp->getFilename(), file_get_contents($imgTemp->getPath()));
					}
				}
			}

			$objZip->addFromString('phpexcel.xml', $this->_writeSerialized($this->_spreadSheet, $pFilename));

			if ($objZip->close() === false) {
				throw new Exception('Could not close zip file ' . $pFilename . '.');
			}
		}
		else {
			throw new Exception('PHPExcel object unassigned.');
		}
	}

	public function getPHPExcel()
	{
		if (!is_null($this->_spreadSheet)) {
			return $this->_spreadSheet;
		}
		else {
			throw new Exception('No PHPExcel assigned.');
		}
	}

	public function setPHPExcel(PHPExcel $pPHPExcel = NULL)
	{
		$this->_spreadSheet = $pPHPExcel;
		return $this;
	}

	private function _writeSerialized(PHPExcel $pPHPExcel = NULL, $pFilename = '')
	{
		$pPHPExcel = clone $pPHPExcel;
		$sheetCount = $pPHPExcel->getSheetCount();

		for ($i = 0; $i < $sheetCount; ++$i) {
			for ($j = 0; $j < $pPHPExcel->getSheet($i)->getDrawingCollection()->count(); ++$j) {
				if ($pPHPExcel->getSheet($i)->getDrawingCollection()->offsetGet($j) instanceof PHPExcel_Worksheet_BaseDrawing) {
					$imgTemp = &$pPHPExcel->getSheet($i)->getDrawingCollection()->offsetGet($j);
					$imgTemp->setPath('zip://' . $pFilename . '#media/' . $imgTemp->getFilename(), false);
				}
			}
		}

		$objWriter = new xmlWriter();
		$objWriter->openMemory();
		$objWriter->setIndent(true);
		$objWriter->startDocument('1.0', 'UTF-8', 'yes');
		$objWriter->startElement('PHPExcel');
		$objWriter->writeAttribute('version', '1.7.4');
		$objWriter->writeComment('This file has been generated using PHPExcel v1.7.4 (http://www.codeplex.com/PHPExcel). It contains a base64 encoded serialized version of the PHPExcel internal object.');
		$objWriter->startElement('data');
		$objWriter->writeCData(base64_encode(serialize($pPHPExcel)));
		$objWriter->endElement();
		$objWriter->endElement();
		return $objWriter->outputMemory(true);
	}
}

?>
