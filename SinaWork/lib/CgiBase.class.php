<?php 
/***************************************************
 * HTTP-CGI基类
 * copyright:	sina
 * filepath	:	/lib/CgiBase.class.php
 * author	:	孙一
 * email	:	sunyi3@staff.sina.com.cn
 * create	： 	2015/08/17
 * version	：	2.0
 * *************************************************
 * CGI基类文件：
 * 1：解析并输出CGI数据结果
 * 2：统一http请求响应码
 * 3：日志记录【功能暂时关闭】
 */
class CgiBase
{
	
	public  $answer	= null;									//CGI输出结果
	private $logs	= array();								//日志键值对（功能暂时关闭）	
	private $status	= 0;									//接口返回状态：0->成功，非0->失败
	private $http 	= array ( 								//http响应码
	100 => "HTTP/1.1 100 Continue", 
	101 => "HTTP/1.1 101 Switching Protocols", 
	200 => "HTTP/1.1 200 OK", 
	201 => "HTTP/1.1 201 Created", 
	202 => "HTTP/1.1 202 Accepted", 
	203 => "HTTP/1.1 203 Non-Authoritative Information", 
	204 => "HTTP/1.1 204 No Content", 
	205 => "HTTP/1.1 205 Reset Content", 
	206 => "HTTP/1.1 206 Partial Content", 
	300 => "HTTP/1.1 300 Multiple Choices", 
	301 => "HTTP/1.1 301 Moved Permanently", 
	302 => "HTTP/1.1 302 Found", 
	303 => "HTTP/1.1 303 See Other", 
	304 => "HTTP/1.1 304 Not Modified", 
	305 => "HTTP/1.1 305 Use Proxy", 
	307 => "HTTP/1.1 307 Temporary Redirect", 
	400 => "HTTP/1.1 400 Bad Request", 
	401 => "HTTP/1.1 401 Unauthorized", 
	402 => "HTTP/1.1 402 Payment Required", 
	403 => "HTTP/1.1 403 Forbidden", 
	404 => "HTTP/1.1 404 Not Found", 
	405 => "HTTP/1.1 405 Method Not Allowed", 
	406 => "HTTP/1.1 406 Not Acceptable", 
	407 => "HTTP/1.1 407 Proxy Authentication Required", 
	408 => "HTTP/1.1 408 Request Time-out", 
	409 => "HTTP/1.1 409 Conflict", 
	410 => "HTTP/1.1 410 Gone", 
	411 => "HTTP/1.1 411 Length Required", 
	412 => "HTTP/1.1 412 Precondition Failed", 
	413 => "HTTP/1.1 413 Request Entity Too Large", 
	414 => "HTTP/1.1 414 Request-URI Too Large", 
	415 => "HTTP/1.1 415 Unsupported Media Type", 
	416 => "HTTP/1.1 416 Requested range not satisfiable", 
	417 => "HTTP/1.1 417 Expectation Failed", 
	500 => "HTTP/1.1 500 Internal Server Error", 
	501 => "HTTP/1.1 501 Not Implemented", 
	502 => "HTTP/1.1 502 Bad Gateway", 
	503 => "HTTP/1.1 503 Service Unavailable", 
	504 => "HTTP/1.1 504 Gateway Time-out"  
	); 
	/***************************************************
	* HTTP接口调用请求方法
	* url		:带请求接口的url
	* opt		:请求参数,数组(可选)
	*			{
	*				timeout			:超时时间
	* 				method  		:GET/POST
	* 				data    		:类型为string或array，
	* 				ip				:域名对应的ip，指定ip后，url中的域名将不依赖host和域名解析
	* 				decode  		: true/false ，是否对结果进行json_decode
	* 				check_callback 	:检查结果回调函数,例:function check($ret_data);
	*			}
	* @example
	* $url = "http://c.isd.com/AppCmdb/interface/cmdbInterface1.php"
	* $opt = array(
	* 			'ip'		=> '10.137.155.40',
	* 			'data'		=> json_encode(array('xxx'=>'xx')),
	* 			"method"	=> "POST",
	*			"timeout"	=> 60,
	*			"decode" 	=> true,
	*		  //"headers"	=> ""
	* );
	* $ret = $this->http_request($url,$opt);
	*/		
	public function http_request($url, $opt = array())
	{
		$_MagicTool = new MagicTool();	//HTTP调用封装类
		return $_MagicTool->http_request($url, $opt);
	}
	/***************************************************
	* HTTP返回码指定方法方法
	* number	:返回码（参见：$this->http）
	*/	
	public function headerHttp($num)
	{
		if(isset($this->http[$num]))
		{
			header($this->http[$num]); 
		}
	}
	/***************************************************
	* 输出接口日志方法（自动调用）	【暂时关闭】
	*/	
	public function _LOG_()
	{
		return array(
				'status' 	=> $this->status,
				'attribute'	=> $this->logs);
	}	
	/***************************************************
	* 设定接口返回日志的键值对
	* logs	: 键值对数组			【暂时关闭】
	*/
	public function _SETLOGS_($logs) 			
	{
		$this->logs = $logs;
	}
	/***************************************************
	* 设定接口返回日志的状态
	* 0  ：	表示成功
	* 非0：	表示失败
	*/
	public function _SETSTATUS_($status = 0) 	
	{
		$this->status = $status;
		
	}
	/***************************************************
	* 析构函数
	* 1：输出接口日志			
	* 2：输出接口返回结果
	*		支持返回类型：
	*		{
	*			XML/xml;
	*			JSON/json
	*			TEXT/text
	*		}
	*/
	function __destruct()
	{
		Global $_CGI_FLAG;
		//下面处理日志输出	
		$cgiLog = json_encode($this->_LOG_());
		global $ProType;
		global $_M1;
		global $_M2;
		global $_M3;
		global $_SPACE;
		global $_ACTION;
		Global $LogServer;
		$LogType = "AUTO_CGI";
		$CgiName = "";
		$CgiName = addlogmd($_M1).addlogmd($_M2).addlogmd($_M3).$_SPACE."-".$_ACTION;
		$_MagicTool = new MagicTool();	
		$url 		= $LogServer['url']."?LogType=$LogType&CgiName=$CgiName";
		$logdata	= $cgiLog;
		$opt = array(
					'data'		=> $logdata,
					"method"	=> "POST",
					"timeout"	=> 30,
					"decode" 	=> false,
		);
		if($LogServer['ip']!="")$opt['ip'] = $LogServer['ip'];

		$_MagicTool->http_request($url,$opt);
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//================================================================================================================================//
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(!$_CGI_FLAG)exit;														//CGI调用错误
		$returnType = 'JSON';														//设定默认返回JSON
		if(isset($_GET['returnType']))												//获取如果用户制定了返回类型
		{
			if($_GET['returnType'] == 'XML' || $_GET['returnType'] == 'xml')
				$returnType = 'XML';
			else if($_GET['returnType'] == 'TEXT' || $_GET['returnType'] == 'text')
				$returnType = 'TEXT';
		}
		switch($returnType)															//如果非TEXT输出，则将清空页面缓存，然后输出结果
		{
			case 'JSON':
				ob_end_clean();
				echo json_encode($this->answer);
				exit(0);
			case 'XML':
				ob_end_clean();
				echo arrtoxml($this->answer);
				exit(0);
		}
	}
}
 ?>