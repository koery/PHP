<?php
// define('BASEDIR', __DIR__);
// include BASEDIR.'/IMooc/Loader.php';
// spl_autoload_register('IMooc\Loader::autoload');
// echo '<meta http-equiv="content-type" content="text/html;charset=utf-8">';

// IMooc\Application::getInstance(__DIR__)->dispatch();

// echo '1';
// $stack = new SplStack();
// $stack->push('1');
// $stack->push('2');

// echo $stack->pop();
// echo $stack->pop();
$p =0;
$s=1000;
for($i=0;$i<=10;$i++){
	
	echo $p++*$s.':'.$s.'<br>';
}
