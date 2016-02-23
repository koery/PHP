<?php
/***************************************************
 * HTTP接口调用类
 * copyright:	sina
 * filepath	:	/lib/MagicTool.php
 * author	:	孙一
 * email	:	sunyi3@staff.sina.com.cn
 * create	： 	2015/08/17
 * version	：	2.0
 * *************************************************/
class MagicTool
{
    private $ip_inner;
    public function __construct()
    {
        $cur_path = dirname(__FILE__);
        //$this->ip_inner = trim( shell_exec( '/sbin/ifconfig eth0 | grep -o -e "inet addr:\S*" | awk -F ":" \'{print $2}\'' ) );
    }
	/***************************************************
	* 记录LOG【暂时关闭】
	*/
	public function Log($level="MagicTool", $log)
	{
		$cur_path = dirname(__FILE__);
		$datetime = date("Y-m-d H:i:s", time());
		$log_data = $datetime . " " . $level . " " . $log;
		//	$this->ReportData("exec_log", $log_data);
		//	file_put_contents($cur_path . "/tool.log", $log_data . "\n\n", FILE_APPEND);
	}
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
		// var_dump($opt['data']);
		//设置常用url的返回值判断参数
		//		echo "<br>url:<br>$url<br>";
		$com_url = array();
		if (!empty($opt) && !is_array($opt)) 
		{
			$err_msg = "error:opt is not array\n";
			echo $err_msg;
			return false;
		}
		if (empty($url)) 
		{
			$err_msg = "error:url is null\n";
			echo $err_msg;
			return false;
		}
		$org_url = $url;
		list($req_url, ) = explode('?', $org_url, 2);
		//设置超时时间
		$timeout = array_key_exists('timeout', $opt) ? $opt['timeout'] : 60;
		//获取请求方法
		$method = array_key_exists('method', $opt) ? strtoupper($opt['method']) : 'GET';
		//请求数据
		$data = array_key_exists('data', $opt) ? $opt['data'] : '';
		//构造请求数据
		if ($method == 'GET') 
		{
			$data = is_array($data) ? http_build_query($data) : $data;
			$con_flag = strpos($url, '?') ? "&" : "?";
			$url .= $con_flag . $data;
			//echo ($url."\r\n");
		}
		//如果指定了ip，转换url，host
		$curlHandle = curl_init();
		if (isset($opt['headers'])) 
		{
			$headers = $opt['headers'];
		} 
		else 
		{
			$headers = array();
		}
		
		if (array_key_exists('ip', $opt)) 
		{
			$ip = $opt['ip'];
			$match_count = preg_match('/^http(s)?:\/\/([-0-9a-z.]+)+(:\d+)?\//', trim($url), $matches);
			if (!$match_count) 
			{
				$err_msg = "error:url format error\n";
				echo $err_msg;
				return false;
			}
			$host = $matches[2];
			$url = preg_replace('/' . $host . '/', $ip, trim($url));
			$headers[] = "Host: " . $host;   //绑定域名到此ip地址上
		}
		// 传送头信息
		curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
		if ($method == 'POST') 
		{
			curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curlHandle, CURLOPT_URL, $url);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);
		$start_time = microtime(true);
		$ret_data = curl_exec($curlHandle);
		$http_code = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
		if (curl_errno($curlHandle)) 
		{
			$err_msg = "error:curl error, " . curl_error($curlHandle) . "\n";
			$succ_flag = false;
			return $err_msg;
		}
		curl_close($curlHandle);
		if ($http_code !== 200) 
		{
			$err_msg = "error:server response error ,ret code is  " . $http_code . "\n";
			$succ_flag = false;
			return $err_msg;
		}
		$decode_data = json_decode($ret_data, true);
		if (array_key_exists('callback', $opt)) 
		{
			$succ_flag = call_user_func_array($opt['callback'], array($ret_data));
			if (!$succ_flag) 
			{
				$err_msg = "error:return code check error,check result " . $succ_flag . "\n";
				$succ_flag = false;
			    return $err_msg;
			}
		} 
		else 
		{
			list($req_url, ) = explode('?', $org_url, 2);
			if (array_key_exists($req_url, $com_url)) 
			{
				$code_key = key($com_url[$req_url]);
				$succ_code = $com_url[$req_url][$code_key];
				if ($decode_data[$code_key] !== $succ_code) 
				{
					$err_msg = "error:return code check error,ret= " . $ret_data. "\n";
					$succ_flag = false;
			        return $err_msg;
				}
			}
		}
		if (array_key_exists('decode', $opt) && $opt['decode'] == true) 
		{
			$ret_data = $decode_data;
		}
		return $ret_data;
	}

}

?>
