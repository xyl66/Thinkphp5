<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

function tile(&$X, $rowWise, $colWise)
{
	$xArray = $X->getArray();
	print_r($xArray);
	$countRow = 0;
	$countColumn = 0;
	$m = $X->getRowDimension();
	$n = $X->getColumnDimension();
	if (($rowWise < 1) || ($colWise < 1)) {
		exit('tile : Array index is out-of-bound.');
	}

	$newRowDim = $m * $rowWise;
	$newColDim = $n * $colWise;
	$result = array();

	for ($i = 0; $i < $newRowDim; ++$i) {
		$holder = array();

		for ($j = 0; $j < $newColDim; ++$j) {
			$holder[$j] = $xArray[$countRow][$countColumn++];

			if ($countColumn == $n) {
				$countColumn = 0;
			}
		}

		++$countRow;

		if ($countRow == $m) {
			$countRow = 0;
		}

		$result[$i] = $holder;
	}

	return new Matrix($result);
}

include '../Matrix.php';
$X = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
$nRow = 3;
$nCol = 3;
$tiled_matrix = tile(new Matrix($X), $nRow, $nCol);
echo '<pre>';
print_r($tiled_matrix);
echo '</pre>';

?>
