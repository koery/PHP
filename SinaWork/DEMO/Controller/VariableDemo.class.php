<?php
class VariableDemo extends  ControllerBase
{
	function __construct()
	{
		parent::__construct();
	}
	public function render()
	{
		echo "Print from VariableTest-render!<br>";
		if(isset($_GET['x']))
			echo "x:".$_GET['x']."<br>";
		$this->setVar("varToView",date('Y-m-j h:i:s A'));
		$this->setJson("varToView",date('Y-m-j h:i:s A'));
	}
}