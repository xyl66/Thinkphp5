<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

class LevenbergMarquardt
{
	public function chiSquared($x, $a, $y, $s, $f)
	{
		$npts = count($y);
		$sum = 0;

		for ($i = 0; $i < $npts; ++$i) {
			$d = $y[$i] - $f->val($x[$i], $a);
			$d = $d / $s[$i];
			$sum = $sum + ($d * $d);
		}

		return $sum;
	}

	public function solve($x, $a, $y, $s, $vary, $f, $lambda, $termepsilon, $maxiter, $verbose)
	{
		$npts = count($y);
		$nparm = count($a);

		if (0 < $verbose) {
			print('solve x[' . count($x) . '][' . count($x[0]) . ']');
			print(' a[' . count($a) . ']');
			println(' y[' . count(length) . ']');
		}

		$e0 = $this->chiSquared($x, $a, $y, $s, $f);
		$done = false;
		$H = array();
		$g = array();
		$oos2 = array();

		for ($i = 0; $i < $npts; ++$i) {
			$oos2[$i] = 1 / ($s[$i] * $s[$i]);
		}

		$iter = 0;
		$term = 0;

		do {
			++$iter;

			for ($r = 0; $r < $nparm; ++$r) {
				for ($c = 0; $c < $nparm; ++$c) {
					for ($i = 0; $i < $npts; ++$i) {
						if ($i == 0) {
							$H[$r][$c] = 0;
						}

						$xi = $x[$i];
						$H[$r][$c] += $oos2[$i] * $f->grad($xi, $a, $r) * $f->grad($xi, $a, $c);
					}
				}
			}

			for ($r = 0; $r < $nparm; ++$r) {
				$H[$r][$r] *= 1 + $lambda;
			}

			for ($r = 0; $r < $nparm; ++$r) {
				for ($i = 0; $i < $npts; ++$i) {
					if ($i == 0) {
						$g[$r] = 0;
					}

					$xi = $x[$i];
					$g[$r] += $oos2[$i] * ($y[$i] - $f->val($xi, $a)) * $f->grad($xi, $a, $r);
				}
			}

			if ($false) {
				for ($r = 0; $r < $nparm; ++$r) {
					$g[$r] = -0.5 * $g[$r];

					for ($c = 0; $c < $nparm; ++$c) {
						$H[$r][$c] *= 0.5;
					}
				}
			}
		} while (!$done);

		return $lambda;
	}
}


?>
