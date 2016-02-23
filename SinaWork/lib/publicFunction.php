<?php
/***************************************************
 * 公共函数
 * copyright:	sina
 * filepath	:	/lib/publicFunction.php
 * author	:	孙一
 * email	:	sunyi3@staff.sina.com.cn
 * create	: 	2015/08/17
 * version	:	2.0
 * *************************************************/
 /*================================================*/
 /***************************************************
	* ?????XML??
	* arr		:??????
	* dom/item	:????
	*/
function arrtoxml($arr,$dom=0,$item=0)
{
    if (!$dom)
	{
        $dom = new DOMDocument("1.0");
    }
    if(!$item)
	{
        $item = $dom->createElement("root"); 
        $dom->appendChild($item);
    }
    foreach ($arr as $key=>$val)
	{
        $itemx = $dom->createElement(is_string($key)?$key:"item");
        $item->appendChild($itemx);
        if (!is_array($val))
		{
            $text = $dom->createTextNode($val);
            $itemx->appendChild($text);
            
        }
		else 
		{
            arrtoxml($val,$dom,$itemx);
        }
    }
    return $dom->saveXML();
}
 /***************************************************
	* XML???????
	* xml		:????XML
	*/
function xmltoarr( $xml )
{
    $reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
    if(preg_match_all($reg, $xml, $matches))
    {
        $count = count($matches[0]);
        $arr = array();
        for($i = 0; $i < $count; $i++)
        {
            $key= $matches[1][$i];
            $val = xmltoarr( $matches[2][$i] );  // ตน้
            if(array_key_exists($key, $arr))
            {
                if(is_array($arr[$key]))
                {
                    if(!array_key_exists(0,$arr[$key]))
                    {
                        $arr[$key] = array($arr[$key]);
                    }
                }else{
                    $arr[$key] = array($arr[$key]);
                }
                $arr[$key][] = $val;
            }else{
                $arr[$key] = $val;
            }
        }
        return $arr;
    }else{
        return $xml;
    }
}
function classLoader($className)
{
	global $_ROOT_;
	global $_M1;
	global $_M2;
	global $_M3;
	global $_SPACE;
	global $_ACTION;
	Global $_MODULE;
	Global $_CONTROLLER;
	Global $_ACTION;
	Global $ProType;//-1:未定义，0：CGI，1：MVC	
	if($ProType==0)
	{
		$fileName  = "$_ROOT_/cgi/".addpath($_M1).addpath($_M2).addpath($_M3)."public/$className.class.php";
		if(file_exists($fileName))
		{
			include_once($fileName);
		}
		else exit("Fatal error:Autoload class:$className failed from path :'$fileName'");
	}
	else if($ProType==1)
	{
		$fileName  = "$_ROOT_/$_MODULE/public/CLASS/$className.class.php";
		if(file_exists($fileName))
		{
			include_once($fileName);
		}
		else exit("Fatal error:Autoload class:$className failed from path :'$fileName'");
	}
}
function WriteLog($log)
{
	global $ProType;
	global $_M1;
	global $_M2;
	global $_M3;
	global $_SPACE;
	global $_ACTION;
	Global $_MODULE;
	Global $_CONTROLLER;
	Global $_ACTION;
	Global $LogServer;
	$LogType = "DEV_CGI";
	$CgiName = "";
	switch($ProType)
	{
		case 0://Cgi
			$CgiName = "CGI-".addlogmd($_M1).addlogmd($_M2).addlogmd($_M3).$_SPACE."-".$_ACTION;
			break;
		case 1://MVC
			$CgiName = "MVC-".$_MODULE."-".$_CONTROLLER."-".$_ACTION;
			break;
		default:
			$CgiName = "error";
	}
	$_MagicTool = new MagicTool();	
	$url 		= $LogServer['url']."?LogType=$LogType&CgiName=$CgiName";
	$logdata	= is_array($log)?json_encode($log):$log;
	$opt = array(
	 			'data'		=> $logdata,
	 			"method"	=> "POST",
				"timeout"	=> 3,
				"decode" 	=> false,
	);
	if($LogServer['ip']!="")$opt['ip'] = $LogServer['ip'];
	$_MagicTool->http_request($url,$opt);
}