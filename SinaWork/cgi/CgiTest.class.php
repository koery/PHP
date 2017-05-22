<?php
class CgiTest extends CgiBase
{
	public function __construct()
	{
		//$this->_SETLOG_(true,true);//第一个参数：是否记录接口入口日志
								   //第二个参数：是否记录接口消息日志
		
	}
	function cgi_1()
	{
		$this->answer=array('status'=>0,'answer'=>1);
		WriteLog("it is log from CgiTest-cgi_1!");
	}
	function cgi_2()
	{
		$this->answer=array('status'=>0,'answer'=>2);
	}
	function cgiAction()
	{
		WriteLog("it is log from CgiTest-cgiAction!");
		echo "here";
		$this->answer=	array('status'=>0,'answer'=>'Hello!cgiAction working!');
		$this->_SETLOGS_(array('status'=>0,'answer'=>'Hello!cgiAction working!'));
		$this->_SETSTATUS_(0);
	}
}