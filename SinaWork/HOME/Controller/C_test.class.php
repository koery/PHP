<?php
class C_test extends  ControllerBase
{
	function __construct()
	{
		parent::__construct();
	}
	public function A_test()
	{
		echo "come into A_test<br>";
		if(isset($_GET['x']))
			echo "x:".$_GET['x']."<br>";
		$this->setVar("varToView",date('Y-m-j h:i:s A'));
		$this->setJson("varToView",date('Y-m-j h:i:s A'));
	}
}