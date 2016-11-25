<?php

//@深圳PHP开发工作室
//QQ：771943372
//时间：2013年5月

include_once 'includes/header.php';
include_once 'includes/navbar.php';
echo '<p>' . "\n" . 'The first script your should run when you install Jama is the TestMatrix.php script.' . "\n" . '</p>' . "\n" . '<p>' . "\n" . 'This will run the unit tests for methods in the <code>Matrix.php</code> class.  Because' . "\n" . 'the Matrix.php class can be used to invoke all the decomposition methods the <code>TestMatrix.php</code> ' . "\n" . 'script is a test suite for the whole Jama package.' . "\n" . '</p>' . "\n" . '<p>' . "\n" . 'The original <code>TestMatrix.java</code> code uses try/catch error handling.  We will ' . "\n" . 'eventually create a build of JAMA that will take advantage of PHP5\'s new try/catch error ' . "\n" . 'handling capabilities.  This will improve our ability to replicate all the unit tests that ' . "\n" . 'appeared in the original (except for some print methods that may not be worth porting).' . "\n" . '</p>' . "\n" . '<p>' . "\n" . 'You can <a href=\'../test/TestMatrix.php\'>run the TestMatrix.php script</a> to see what ' . "\n" . 'unit tests are currently implemented.  The source of the <code>TestMatrix.php</code> script ' . "\n" . 'is provided below.  It is worth studying carefully for an example of how to do matrix algebra' . "\n" . 'programming with Jama.' . "\n" . '</p>' . "\n" . '';
highlight_file('../test/TestMatrix.php');
include_once 'includes/footer.php';

?>
