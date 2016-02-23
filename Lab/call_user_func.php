<?php
/**
 * @desc       
 * @file_name  call_user_func.php
 * @author     coco
 * @date       2016年1月18日下午7:17:18
 *
 */


function increment(){
	var_dump(func_get_args());
	// $var++;
	// echo $var;
	echo '11';
}
$a = 2;
$b = 1;
__call();
call_user_func('increment', $a,$b);
//echo $a; // 0
// call_user_func_array('increment', array(&$a)); // You can use this instead
// echo $a; // 1



// function a($b)   
// {   
// 	$b++;   
// }   
// $c = 0;   
// call_user_func('a', $c);   
// echo $c;//显示 1   
// call_user_func_array('a', array($c));   
// echo $c;//显示 2  
// ?>
