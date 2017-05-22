<?php
/***************************************************
 * 基于Flight-PHP扩展框架的入口文件
 * copyright:	sina
 * filepath	:	/index.php
 * author	:	孙一
 * email	:	sunyi3@staff.sina.com.cn
 * create	： 	2015/08/17
 * version	：	2.0
 * *************************************************
 * 框架入口文件：
 * 任何使用本框架开发的网站和CGI都将重定向到本文件。
 * 根据路由配置函数加载对应类并执行相应函数。
 */
error_reporting(E_ALL);
ini_set('display_errors', '1'); 
date_default_timezone_set('Asia/Shanghai'); 
require_once './flight/Flight.php';							//加载flight-php框架					
require_once './lib/ControllerBase.class.php';				//控制器基类
require_once './lib/CgiBase.class.php';						//HTTP接口基类
require_once './lib/Model.class.php';						//数据模型
require_once './lib/Log.class.php';							//日志处理类
require_once './lib/publicFunction.php';					//扩展函数文件
require_once './lib/MagicTool.php';							//http接口封装类
function url_need_not_login($url, $method) {return true;}	//无需Flight内置身份验证
ob_start();													//打开输出控制缓冲，用于接口在返回结果之前清空缓存
$_ROOT_ 	= dirname(__FILE__);							//框架入口文件【即框架根目录】的绝对路径
$LogServer	= include('./lib/logServer.config.php');
$ProType	= -1;										//-1:未定义，0：CGI，1：MVC		
/***************************************************
* HTTP-CGI路由规则定义
* m1,m2,m3	:对应一级、二级、三级模块（对应目录结构）
* space		:CGI所属空间（对应CGI类）
* action	:CGI具体动作（对应类内动作函数）
*/

Flight::route('/cgi(/@m1(/@m2((/@m3))))/@space-@action',
function($m1, $m2, $m3, $space, $action)
{
	spl_autoload_register('classLoader');  
	global $CGI_PATH;
	global $LogServer;
	global $ProType;
	global $_M1;
	global $_M2;
	global $_M3;
	global $_SPACE;
	global $_ACTION;
	$_M1 = $m1;
	$_M2 = $m2;
	$_M3 = $m3;
	$_SPACE = $space;
	$_ACTION= $action;
	$ProType = 0;
	/*********************************************
	*将模块对应为目录结构
	*********************************************/
	function addpath($m){return $m==""?"":"$m/";}
	$CGI_PATH 	= "./cgi/".addpath($m1).addpath($m2).addpath($m3);
	/*********************************************
	*下面代码处理CGI入口的日志记录，通过接口调用记录
	*********************************************/
	function addlogmd($m){return $m==""?"":"$m-";}
	$_MagicTool = new MagicTool();	
	$url 		= $LogServer['url']."?LogType=AUTO_CGI&CgiName="
						.addlogmd($m1).addlogmd($m2).addlogmd($m3).$space."-".$action;
	$startlog	= json_encode(array('time'		=> date('y-m-d/h:i:s',time()),
									'request' 	=> Flight::request()));
	$opt = array(
	 			'data'		=> $startlog,
	 			"method"	=> "POST",
				"timeout"	=> 3,
				"decode" 	=> false,
	);
	if($LogServer['ip']!="")$opt['ip'] = $LogServer['ip'];
	$_MagicTool->http_request($url,$opt);
	/***********************************************/
	/*=============================================*/
	/***********************************************/
	if( file_exists($CGI_PATH."$space.class.php") )					//加载CGI类文件
	{
		require_once ( $CGI_PATH."$space.class.php" );
		$cgi = new $space;
		if( method_exists($cgi, $action) )							//访问CGI类内部的action方法
		{
			Global $_CGI_FLAG;										//记录这次CGI调用是否存在成功
			$_CGI_FLAG = true;
			if($space != $action)
			{
				$cgi->$action();									//执行CGI动作函数处理业务逻辑
			}							
		}
		else
		{
			Global $_CGI_FLAG;
			$_CGI_FLAG = false;										//CGI调用失败
			echo "Action error!Check weather the method '$action'  exist in CGI-SPACE '$space'";
		}
	}
	else echo "CGI-SPACE error!Check weather the file '".$CGI_PATH."$space.class.php'  exist!";
},  true);

/***************************************************
* MVC架构路由规则定义
* module	:项目所属模块（对应项目目录，默认为HOME）
* controller:控制器名
* action	:动作函数名
*/
Flight::route('/(@module/)@controller-@action',
function($module, $controller, $action)
{
	spl_autoload_register('classLoader'); 
	Global $_MODULE;
	Global $_CONTROLLER;
	Global $_ACTION;
	global $ProType;
	$ProType = 1;
	$_MODULE		= $module==""?"HOME":$module;
	$_CONTROLLER 	= $controller;
	$_ACTION		= $action;
	if(file_exists("./$_MODULE/Controller/$controller.class.php"))			//加载控制器
	{
		require_once ("./$_MODULE/Controller/$controller.class.php");
		$ctrl = new $controller;
		if(method_exists($ctrl,$action))									//调用动作函数
			$ctrl->$action();
		else echo "Action error!Check weather the method '$action'  exist in Controllor '$controller'";
	}
	else echo "Controller'$controller' not exist!,Please check if the file './$_MODULE/Controller/$controller.class.php' exists!";
});
//启动Flight框架
Flight::start();

