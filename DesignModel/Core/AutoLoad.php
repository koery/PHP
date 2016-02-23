<?php
/**
 * @desc       
 * @file_name  AutoLoad.php
 * @author     coco
 * @data       2015年10月27日下午1:46:54
 *
 */
namespace Core;
class AutoLoad {
	
	static function autoload($class){
		dump($class);
		//include();
	}
}