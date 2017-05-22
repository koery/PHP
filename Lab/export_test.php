<?php
/**
 * @desc       
 * @file_name  export_test.php
 * @author     coco
 * @data       2015年12月24日下午3:36:50
 *
 */

include 'Export.php';
$filename = date('Y-m-d-H-i-s',time()).'导出测试';
$titles = '订单号,商品标题,商品ID,期数';
$data =  '订单号,商品标题,商品ID,期数';
$data = [
	[
			'order_sn'=>'133213',
			'title'=>'毛衣',
			'id'=>111,
			'item'=>23
	],
	[
			
			'order_sn'=>'133213',
			'title'=>'毛衣',
			'id'=>111,
			'item'=>23
			
	],
	[
			
		'order_sn'=>'133213',
		'title'=>'毛衣',
		'id'=>111,
		'item'=>23
			
		]
];

$export = new export($filename, $titles, $data);
$export->export();
