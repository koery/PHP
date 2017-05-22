<?php
/**
 * @desc       
 * @file_name  nickandheadimg.php
 * @author     coco
 * @data       2015年12月26日下午1:28:52
 *
 */

function test() {
	$db = new PDO("mysql:host=localhost;dbname=name", "root", "root");
	$nums = range(1,42189);
	foreach ($nums as $key => $value) {
		$an = curl('http://www.qmsjmfb.com/en.php', 'post', ['sex' => 'all','num' => 100,'xing' => '']);
		preg_match_all("/<li>(.*?)<\>/", $an, $match_an);
		if(isset($match_an[1])) {
			foreach($match_an[1] as $v) {
				$db->exec("insert into robot_name(name) values('{$v}')");
				echo $db -> lastinsertid();
			}
		}
	}
}

function head() {
	$db = new PDO("mysql:host=localhost;dbname=name", "root", "root");
	//插入code表 分批插入 by sang 2015-12-08 10:26
	$nums = range(1,331);
	foreach ($nums as $key => $value) {
		$url = "http://www.qzone.cc/wangming/yingwen/";
		if($value > 1) {
			$url = "http://www.qzone.cc/wangming/yingwen/list_{$value}.html";
		}
		$an = curl($url, 'get', []);
		preg_match_all("/<img src=\"(.*?)\" onerror=\"this.src='.*?'\" class=\"header icard\"  user_id=\"[0-9]+\"\/>/", $an, $match_an);
		if(isset($match_an[1])) {
			$b = array_unique($match_an[1]);
			if($b) {
				foreach($b as $v) {
					$db->exec("insert into robot_head_portrait(pic) values('{$v}')");
					echo $db -> lastinsertid();
				}
			}
		}
	}
}