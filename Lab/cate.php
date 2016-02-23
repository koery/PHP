<?php
header("Content-type: text/html; charset=utf-8"); 
   $cate = [

   		0=>[
   			'name'=>'数码',
 			'sub_cate' => [
 				0=>'手机',
 				1=>'相机',
 				2=>'ipad'
 			]
   		],
   		1=>[

   			'name'=>'衣服',
   			'sub_cate'=>[

   				0=>'男装',
   				1=>'女装',
   			]

   		]
   ];

   $cateJson =  json_encode($cate);

   $sub_cate = [

   		'手机'=>[

   			0=>'颜色',
   			1=>'尺寸',
   			2=>'配置'
   		],
   		'男装'=>[

   			0=>'颜色',
   			1=>'尺寸',
   			2=>'配置'

   		]
   ];

   $sub_cateJson = json_encode($sub_cate);

   echo $sub_cateJson;