<?php
class VersionDemo extends  ControllerBase
{
	function __construct()
	{
		parent::__construct();
	}
	public function version()
	{
		echo "Print from VersionlTest-testVersion!<br>";
	}
}