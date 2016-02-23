<?php
/*
 * ============================
 *       ¶©µ¥ÉêÇë½Ó¿Ú
 *    @author  lichenxi
 *    @date    2015.08.24
 *    @
 * ============================
 */
class order1 extends CgiBase
{
	
	//¶©µ¥ÉêÇë
	public function order(){
		GLOBAL $_ROOT_;
		$raw_post_data = file_get_contents('php://input');
		//$this->answer=array('status'=>$raw_post_data,'answer'=>1);
		echo "1:".file_get_contents('php://input')."\r\n";	
		echo "2:".$raw_post_data."\r\n";
		$this->_SETLOGS_("it works!");
		WriteLog("it really works");
		new MyClass1();
	}
}