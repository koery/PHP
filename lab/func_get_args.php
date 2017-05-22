<?php
function args()
{
	$params = func_get_args();
	print_r($params);
	$a = array_shift($params);
	print_r($params);
}


args(1,2,3,4,5,6,'sss');