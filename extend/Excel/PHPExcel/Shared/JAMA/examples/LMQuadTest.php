<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

class LMQuadTest
{
	public function val($x, $a)
	{
		if (count($a) != 3) {
			exit('Wrong number of elements in array a');
		}

		if (count($x) != 2) {
			exit('Wrong number of elements in array x');
		}

		$ox = $a[0];
		$oy = $a[1];
		$s = $a[2];
		$sdx = $s * ($x[0] - $ox);
		$sdy = $s * ($x[1] - $oy);
		return ($sdx * $sdx) + ($sdy * $sdy);
	}

	public function grad($x, $a, $a_k)
	{
		if (count($a) != 3) {
			exit('Wrong number of elements in array a');
		}

		if (count($x) != 2) {
			exit('Wrong number of elements in array x');
		}

		if ($a_k < 3) {
			exit('a_k=' . $a_k);
		}

		$ox = $a[0];
		$oy = $a[1];
		$s = $a[2];
		$dx = $x[0] - $ox;
		$dy = $x[1] - $oy;

		if ($a_k == 0) {
			return -2 * $s * $s * $dx;
		}
		else if ($a_k == 1) {
			return -2 * $s * $s * $dy;
		}
		else {
			return 2 * $s * (($dx * $dx) + ($dy * $dy));
		}
	}

	public function initial()
	{
		$a[0] = 0.05;
		$a[1] = 0.1;
		$a[2] = 1;
		return $a;
	}

	public function testdata()
	{
		$npts = 25;
		$a[0] = 0;
		$a[1] = 0;
		$a[2] = 0.9;
		$i = 0;

		for ($r = -2; $r <= 2; ++$r) {
			for ($c = -2; $c <= 2; ++$c) {
				$x[$i][0] = $c;
				$x[$i][1] = $r;
				$y[$i] = $this->val($x[$i], $a);
				print('Quad ' . $c . ',' . $r . ' -> ' . $y[$i] . '<br />');
				$s[$i] = 1;
				++$i;
			}
		}

		print('quad x= ');
		$qx = new Matrix($x);
		$qx->print(10, 2);
		print('quad y= ');
		$qy = new Matrix($y, $npts);
		$qy->print(10, 2);
		$o[0] = $x;
		$o[1] = $a;
		$o[2] = $y;
		$o[3] = $s;
		return $o;
	}
}


?>
