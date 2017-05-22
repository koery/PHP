<?php
/**
 * @desc       
 * @file_name  spl.php
 * @author     coco
 * @data       2015年10月28日下午7:32:53
 *
 */

$stack = new SplStack();
$stack->push('1');
$stack->push('2');

echo $stack->pop();
echo $stack->pop();