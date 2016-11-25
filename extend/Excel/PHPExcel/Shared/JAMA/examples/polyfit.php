<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

function polyfit($X, $Y, $n)
{
	for ($i = 0; $i < sizeof($X); ++$i) {
		for ($j = 0; $j <= $n; ++$j) {
			$A[$i][$j] = pow($X[$i], $j);
		}
	}

	for ($i = 0; $i < sizeof($Y); ++$i) {
		$B[$i] = array($Y[$i]);
	}

	$matrixA = new Matrix($A);
	$matrixB = new Matrix($B);
	$C = $matrixA->solve($matrixB);
	return $C->getMatrix(0, $n, 0, 1);
}

function printpoly($C = NULL)
{
	for ($i = $C->m - 1; 0 <= $i; --$i) {
		$r = $C->get($i, 0);

		if (abs($r) <= pow(10, -9)) {
			$r = 0;
		}

		if ($i == $C->m - 1) {
			echo $r . 'x<sup>' . $i . '</sup>';
		}
		else if ($i < ($C->m - 1)) {
			echo ' + ' . $r . 'x<sup>' . $i . '</sup>';
		}
		else if ($i == 0) {
			echo ' + ' . $r;
		}
	}
}

require_once '../Matrix.php';
$X = array(0, 1, 2, 3, 4, 5);
$Y = array(4, 3, 12, 67, 228, 579);
$points = new Matrix(array($X, $Y));
$points->toHTML();
printpoly(polyfit($X, $Y, 4));
echo '<hr />';
$X = array(0, 1, 2, 3, 4, 5);
$Y = array(1, 2, 5, 10, 17, 26);
$points = new Matrix(array($X, $Y));
$points->toHTML();
printpoly(polyfit($X, $Y, 2));
echo '<hr />';
$X = array(0, 1, 2, 3, 4, 5, 6);
$Y = array(-90, -104, -178, -252, -26, 1160, 4446);
$points = new Matrix(array($X, $Y));
$points->toHTML();
printpoly(polyfit($X, $Y, 5));
echo '<hr />';
$X = array(0, 1, 2, 3, 4);
$Y = array(mt_rand(0, 10), mt_rand(40, 80), mt_rand(240, 400), mt_rand(1800, 2215), mt_rand(8000, 9000));
$points = new Matrix(array($X, $Y));
$points->toHTML();
printpoly(polyfit($X, $Y, 3));

?>
