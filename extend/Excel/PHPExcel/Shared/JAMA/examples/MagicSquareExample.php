<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

class MagicSquareExample
{
	public function magic($n)
	{
		if (($n % 2) == 1) {
			$a = ($n + 1) / 2;
			$b = $n + 1;

			for ($j = 0; $j < $n; ++$j) {
				for ($i = 0; $i < $n; ++$i) {
					$M[$i][$j] = ($n * (($i + $j + $a) % $n)) + (($i + (2 * $j) + $b) % $n) + 1;
				}
			}
		}
		else if (($n % 4) == 0) {
			for ($j = 0; $j < $n; ++$j) {
				for ($i = 0; $i < $n; ++$i) {
					if (((($i + 1) / 2) % 2) == (($j + 1) / 2) % 2) {
						$M[$i][$j] = ($n * $n) - ($n * $i) - $j;
					}
					else {
						$M[$i][$j] = ($n * $i) + $j + 1;
					}
				}
			}
		}
		else {
			$p = $n / 2;
			$k = ($n - 2) / 4;
			$A = $this->magic($p);
			$M = array();

			for ($j = 0; $j < $p; ++$j) {
				for ($i = 0; $i < $p; ++$i) {
					$aij = $A->get($i, $j);
					$M[$i][$j] = $aij;
					$M[$i][$j + $p] = $aij + (2 * $p * $p);
					$M[$i + $p][$j] = $aij + (3 * $p * $p);
					$M[$i + $p][$j + $p] = $aij + ($p * $p);
				}
			}

			for ($i = 0; $i < $p; ++$i) {
				for ($j = 0; $j < $k; ++$j) {
					$t = $M[$i][$j];
					$M[$i][$j] = $M[$i + $p][$j];
					$M[$i + $p][$j] = $t;
				}

				for ($j = ($n - $k) + 1; $j < $n; ++$j) {
					$t = $M[$i][$j];
					$M[$i][$j] = $M[$i + $p][$j];
					$M[$i + $p][$j] = $t;
				}
			}

			$t = $M[$k][0];
			$M[$k][0] = $M[$k + $p][0];
			$M[$k + $p][0] = $t;
			$t = $M[$k][$k];
			$M[$k][$k] = $M[$k + $p][$k];
			$M[$k + $p][$k] = $t;
		}

		return new Matrix($M);
	}

	public function microtime_float()
	{
		list($usec, $sec) = explode(' ', microtime());
		return (double) $usec + (double) $sec;
	}

	public function main()
	{
		echo '    <p>Test of Matrix Class, using magic squares.</p>' . "\n" . '    <p>See MagicSquareExample.main() for an explanation.</p>' . "\n" . '    <table border=\'1\' cellspacing=\'0\' cellpadding=\'4\'>' . "\n" . '      <tr>' . "\n" . '        <th>n</th>' . "\n" . '        <th>trace</th>' . "\n" . '        <th>max_eig</th>' . "\n" . '        <th>rank</th>' . "\n" . '        <th>cond</th>' . "\n" . '        <th>lu_res</th>' . "\n" . '        <th>qr_res</th>' . "\n" . '      </tr>' . "\n" . '      ';
		$start_time = $this->microtime_float();
		$eps = pow(2, -52);

		for ($n = 3; $n <= 6; ++$n) {
			echo '<tr>';
			echo '<td align=\'right\'>' . $n . '</td>';
			$M = $this->magic($n);
			$t = (int) $M->trace();
			echo '<td align=\'right\'>' . $t . '</td>';
			$O = $M->plus($M->transpose());
			$E = new EigenvalueDecomposition($O->times(0.5));
			$d = $E->getRealEigenvalues();
			echo '<td align=\'right\'>' . $d[$n - 1] . '</td>';
			$r = $M->rank();
			echo '<td align=\'right\'>' . $r . '</td>';
			$c = $M->cond();

			if ($c < (1 / $eps)) {
				echo '<td align=\'right\'>' . sprintf('%.3f', $c) . '</td>';
			}
			else {
				echo '<td align=\'right\'>Inf</td>';
			}

			$LU = new LUDecomposition($M);
			$L = $LU->getL();
			$U = $LU->getU();
			$p = $LU->getPivot();
			$S = $L->times($U);
			$R = $S->minus($M->getMatrix($p, 0, $n - 1));
			$res = $R->norm1() / ($n * $eps);
			echo '<td align=\'right\'>' . sprintf('%.3f', $res) . '</td>';
			$QR = new QRDecomposition($M);
			$Q = $QR->getQ();
			$R = $QR->getR();
			$S = $Q->times($R);
			$R = $S->minus($M);
			$res = $R->norm1() / ($n * $eps);
			echo '<td align=\'right\'>' . sprintf('%.3f', $res) . '</td>';
			echo '</tr>';
		}

		echo '<table>';
		echo '<br />';
		$stop_time = $this->microtime_float();
		$etime = $stop_time - $start_time;
		echo '<p>Elapsed time is ' . sprintf('%.4f', $etime) . ' seconds.</p>';
	}
}

require_once '../Matrix.php';
$magic = new MagicSquareExample();
$magic->main();

?>
