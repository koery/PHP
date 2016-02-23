<?php 
/***************************************************
 * 控制器基类
 * copyright:	sina
 * filepath	:	/lib/ControllerBase.class.php
 * author	:	孙一
 * email	:	sunyi3@staff.sina.com.cn
 * create	： 	2015/08/17
 * version	：	2.0
 * *************************************************
 * 控制器基类文件：
 * 1：网站访问权限验证（暂留桩）
 * 2：获取GET&POST$REQUEST参数
 * 3：显示版本控制（WML，手机宽屏，手机窄屏，PC等）
 * 4：记录并传递变量到VIEW（包括PHP&JS变量）
 */
class ControllerBase
{
	public $REQUEST;
	public $GET;
	public $POST;
	public $answer;
	public $varToView;
	public $jsonToView;
	/**************************************************/
	private $vt = 4;			//显示版本控制
	/***************************************************
	* 构造函数
	* 获取GET&POST$REQUEST参数
	* 初始化路径变量
	* 初始化将传递给VIEW的变量
	*/	
	public function __construct()
	{
		Global $_MODULE;
		Global $_CONTROLLER;
		Global $_ACTION;
		if(isset($_GET))
			$this->GET		= $_GET;
		if(isset($_POST))
			$this->POST		= $_POST;
		if(isset($_REQUEST))
			$this->REQUEST	= $_REQUEST;
		//初始化要传递的PHP变量
		$this->varToView			= array();
		$this->varToView['_FLAG']	= $_CONTROLLER.'_'.$_ACTION;
		//初始化要传递的JS变量
		$this->jsonToView			= array();
		$this->jsonToView['_FLAG']	= $_CONTROLLER.'_'.$_ACTION;
		//初始化路径变量
		$this->_SET_PAth_();
		//if(!isset($this->need_login) || $this->need_login==true)$this->checkPerm();
	}
	/***************************************************
	* 初始化路径方法
	*/	
	private function _SET_PAth_()
	{
		Global $_MODULE;
		Global $_CONTROLLER;
		Global $_ACTION;
		Global $_PUBLIC_;
		Global $_CSS_;
		Global $_JS_;
		Global $_IMAGE_;
		Global $_ROOT_;
		$_PUBLIC_ 	= $_ROOT_."\\".$_MODULE."\\public";
		$_CSS_ 		= $_ROOT_."\\".$_MODULE."\\public\\CSS";
		$_JS_ 		= $_ROOT_."\\".$_MODULE."\\public\\JS";
		$_IMAGE_ 	= $_ROOT_."\\".$_MODULE."\\public\\IMAGE";
		
	}
	/***************************************************
	* 设定要传递给VIEW的JS变量键值对
	*/
	public function setJson($name,$value)
	{
		$this->jsonToView[$name] = $value;
	}
	/***************************************************
	* 设定要传递给VIEW的PHP变量键值对
	*/
	public function setVar($name,$value)
	{
		$this->varToView[$name] = $value;
	}
	/***************************************************
	* 用户权限认证方法【暂时留桩】
	*/
	public function checkPerm()		
	{
		if(false)
		{
			ob_end_clean();
			echo "Permission denied !";
			exit;
		}
	}
	/***************************************************
	* 查找VIEW文件
	* 1：根据vt值查找对应目录
	* 2：如果未找到，在本目录查找
	* 3：如果仍未找到，返回-1
	*/
	private function findView($_MODULE, $_CONTROLLER, $_ACTION)
	{
		global $_ROOT_;
		$extension = include("$_ROOT_/lib/extension.config.php");
		if(isset($_REQUEST['vt']))//版本号
		{
			$this->vt = strtoupper($_REQUEST['vt']);
		}
		$viewRoot = "$_ROOT_/$_MODULE/View/$_CONTROLLER/";
		$tryViewRoot1 = "$_ROOT_/$_MODULE/View/$_CONTROLLER/vt_".$this->vt."/$_ACTION";
		$tryViewRoot2 = "$_ROOT_/$_MODULE/View/$_CONTROLLER/$_ACTION";
		foreach($extension as $ex)
		{
			if(file_exists($tryViewRoot1.".$ex"))
			return $tryViewRoot1.".$ex";
		}
		foreach($extension as $ex)
		{
			if(file_exists($tryViewRoot2.".$ex"))
			return $tryViewRoot2.".$ex";
		}
		return -1;
			
	}
	/***************************************************
	* 传递函数
	* 1：传递PHP变量给对应VIEW
	* 2：传递JS变量给对应VIEW
	* 3：加载对应VIEW
	*/
	public function _render_()
	{
		Global $_MODULE;
		Global $_CONTROLLER;
		Global $_ACTION;
		if(count($this->varToView)!=0)
			extract($this->varToView);
		if(count($this->jsonToView)!=0)
		{
			echo "\r\n<script>\r\n//下面是Controller：".$_CONTROLLER."传入的JSON变量\r\n";
			foreach($this->jsonToView as $js_name => $js_var)
			{
				echo "\tvar $js_name = eval( '".json_encode($js_var)."');\r\n";
			}
			echo "</script>\r\n";
		}
		$viewPath = $this->findView($_MODULE, $_CONTROLLER, $_ACTION);
		if($viewPath!=-1)
		{
			require($viewPath);
		}
		else
		{
			echo "<br>View error!<br>Please check if the file '/sinaWork/$_MODULE/View/".$_CONTROLLER."/$_ACTION.php($_ACTION.html)' exists!";
		}
		
	}
	/***************************************************
	* 析构函数
	* 调用传递方法
	*/
	function __destruct()
	{
		
		$this->_render_();
	}
}
 ?>