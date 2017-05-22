<?php

$headers = getallheaders();

//print_r($_POST);
$usename = $_POST['usename'];
$password = $_POST['password'];
if(isset($_COOKIE['usename']) && $_COOKIE['usename'] == $usename ){
	echo '已经登录,cookie判定';
}else{

	if(check($usename,$password))
	{
		echo '登录成功并设置cookie';
	}else{
		echo '用户名或密码错误';
	}

}

function check($usename,$password)
{
	if('nuxse' == $usename && '123456' == $password){
		setcookie('usename',$usename,time()+10);
		return true;
	}
	return false;
}

print_r($headers);
print_r($_SERVER);
print_r($_COOKIE);