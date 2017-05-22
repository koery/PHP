<?php
class CgiTest extends CgiBase
{
	function cgi_1()
	{
		$this->answer=array('status'=>0,'answer'=>1);
	}
	function cgi_2()
	{
		$this->answer=array('status'=>0,'answer'=>2);
	}
	function cgiAction()
	{
		echo "M1/M2/CgiTest-cgiAction";
		echo "<br>GET数组结构：<br>";
		var_dump($_GET);
		echo '<br>********************************<br>';
		echo "<br>POST数据段结构：<br>";
		var_dump(file_get_contents("php://input"));
		echo '<br>********************************<br>';
		exit;
		//$this->_SETLOGS_(array('status'=>0,'answer'=>'Hello!cgiAction working!'));
		//$this->_SETSTATUS_(0);
	}
}