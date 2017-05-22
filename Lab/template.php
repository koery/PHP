<?php
/**
 * @desc       模板引擎测试
 * @file_name  template.php
 * @author     coco
 * @data       2015年12月2日下午2:07:24
 *
 */

require_once 'Template.class.php';

$baseDir = str_replace('\\', '/', dirname(__FILE__));
$temp = new Template($baseDir.'/', $baseDir.'/');

$temp->assign('test', 'cicifuss');
$temp->assign('pagetitle', '山寨版smarty');

$temp->display('template');