<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

class LagrangeInterpolation
{
	public function findPolynomialFactors($x, $y)
	{
		$n = count($x);
		$data = array();
		$rhs = array();

		for ($i = 0; $i < $n; ++$i) {
			$v = 1;

			for ($j = 0; $j < $n; ++$j) {
				$data[$i][$n - $j - 1] = $v;
				$v *= $x[$i];
			}

			$rhs[$i] = $y[$i];
		}

		$m = new Matrix($data);
		$b = new Matrix($rhs, $n);
		$s = $m->solve($b);
		return $s->getRowPackedCopy();
	}
}

require_once '../Matrix.php';
$x = array(2, 1, 3);
$y = array(3, 4, 7);
$li = new LagrangeInterpolation();
$f = $li->findPolynomialFactors($x, $y);

for ($i = 0; $i < 3; ++$i) {
	echo $f[$i] . '<br />';
}

?>
