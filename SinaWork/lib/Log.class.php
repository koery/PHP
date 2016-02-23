<?php
/***************************************************
 * 接口调用日志控制类
 * copyright:	sina
 * filepath	:	/lib/Log.class.php
 * author	:	孙一
 * email	:	sunyi3@staff.sina.com.cn
 * create	： 	2015/08/17
 * version	：	2.0
 * *************************************************
 * 处理本地的日志记录
 */
class Log
{
	private $pathname;
	private $filename;
	public function __construct($pn = "" , $fn = "")
	{
		$this->pathname = $pn;
		$this->filename = $fn;
		if (!file_exists($this->pathname)) mkdir ($this->pathname);
	}
	/***************************************************
	* 设定日志文件路径名
	* pn:日志文件路径名
	*/	
	public function setPn($pn)
	{
		$this->pathname = $pn;
		if (!file_exists($this->pathname)) mkdir ($this->pathname);
	}
	/***************************************************
	* 设定日志文件名
	* fn:日志文件名
	*/	
	public function setfn($fn)
	{
		$this->pathname = $fn;
	}
	/***************************************************
	* 记录日志
	* msg：日志内容
	*/	
	public function writeLog($msg)
	{
		return error_log($msg."\r\n", 3, $this->pathname.$this->filename);
	}
}