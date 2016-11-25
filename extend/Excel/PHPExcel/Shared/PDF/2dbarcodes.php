<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

class TCPDF2DBarcode
{
	/**
	 * @var array representation of barcode.
	 * @access protected
	 */
	protected $barcode_array;

	public function __construct($code, $type)
	{
		$this->setBarcode($code, $type);
	}

	public function getBarcodeArray()
	{
		return $this->barcode_array;
	}

	public function setBarcode($code, $type)
	{
		$mode = explode(',', $type);

		switch (strtoupper($mode[0])) {
		case 'TEST':
			$this->barcode_array['num_rows'] = 5;
			$this->barcode_array['num_cols'] = 15;
			$this->barcode_array['bcode'] = array(
	array(1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1),
	array(0, 1, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0),
	array(0, 1, 0, 0, 1, 1, 0, 0, 1, 1, 1, 0, 0, 1, 0),
	array(0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0),
	array(0, 1, 0, 0, 1, 1, 1, 0, 1, 1, 1, 0, 0, 1, 0)
	);
			break;

		default:
			$this->barcode_array = false;
		}
	}
}


?>
