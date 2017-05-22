<?php
/**
 * @desc       
 * @file_name  php_function.php
 * @author     coco
 * @data       2015年12月26日上午11:04:19
 *
 */

##array_walk($array,function,$params)
echo "<pre>";
$a = [

	0=>'北京',
	1=>'上海',
	2=>'内蒙古',
];

$b = [
		
	'china'=>'北京',
	'usa'=>'华盛顿',
	'uk'=>'伦敦'	,
	'england'=>'伦敦'	
];

$c = [

		0=>4,
		1=>5,
		2=>6
];
// array_walk($a, function (&$value,$key) use (&$c){
// 	$c[] = $value.'prefix';
// });


// array_walk($a, function (&$value,$key,$p){
// 	$value = $value.$p;
// },'prefix');

// $result = array_chunk($a, 2,false);
// $result = array_chunk($b, 2,true);


// $result = array_filter($a,function ($value){
// 	if(strlen($value)>=9)return $value;
// });

// $result = array_filter($a);

//$result = array_key_exists('china', $b);

// $result = array_keys($b);

// print_r(join(',',$result));

// $result = array_merge($a,$b);



//$result = array_multisort($a,SORT_ASC);


//$result = array_pop($a);
//$result = array_shift($a);

//array_unshift($b, '新西兰');

//array_push($b, '新西兰');

// $result = array_product($c);

//$result = array_rand($a,2);

//$result = array_replace($a, [0=>'天津',1=>'四川']);
//$result = array_reverse($a);
	
//$result = array_search('北京', $b);

//$result = array_slice($b, -1);

//$result = array_values($b);

//$result = array_unique($b);

//$result = array_sum($c);

//$result = array_splice($b, 2);
//$result = range('a','z')
$temp = range('aa','zz');
$result = shuffle($temp);
print_r($temp);
exit();
	