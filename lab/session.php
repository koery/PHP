<?php
session_start();
print_r($_SESSION);
if(empty($_SESSION)){

	$_SESSION['userinfo'] = json_encode([
			'username' => 'xiaoming',
			'email' => '566112@qq.com'
	]);
}else {
print_r($_SESSION);
}