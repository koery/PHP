<?php
/**
 * @desc       
 * @file_name  export_test.php
 * @author     coco
 * @data       2015年12月24日下午3:36:50
 *
 */

include 'Import.php';

$filename = $_FILES['file']['tmp_name'];

$Import = new Import($filename);

$ret  = $Import->import();
print_r($ret);