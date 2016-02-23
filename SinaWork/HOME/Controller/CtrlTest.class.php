<?php
class CtrlTest extends  ControllerBase
{//1666791015
	function __construct()
	{
		parent::__construct();
	}
	public function testVersion()
	{
		echo "Print from CtrlTest-testVersion!<br>";
	}
	public function testSql()
	{
		$TestModel = new Model("127.0.0.1","root","","user");
		/*******选择操作表***********/
		$TestModel->setTable('user');
		/*******设定键值对*/
		$TestModel->setKeyValues(array(
										'name'		=>	'XuZheng',
										'age'		=>	24,
										'country'	=>	'China'
									 ));
		/*******插入一条数据*********/
		$TestModel->insert();
		/*******清除Model配置********/
		$TestModel->clear('keyValue');
		/*******查询总记录数*********/
		$answer = $TestModel->count();
		//print_r($answer);
		/*******查询第0页数据(20条)**/
		$answer = $TestModel->select();
		//print_r($answer);
		/*******设定要查询第一页*****/
		$TestModel->setPage(1);
		/*******查询第1页数据(20条)**/
		$answer = $TestModel->select();
		//print_r($answer);
		/*******设定更新键值对*******/
		$TestModel->setKeyValues(array(
										'name'		=>	'XuZheng',
										'age'		=>	27,
										'country'	=>	'China'
									 ));
		/*******设定where语句*********/
		$TestModel->setWhere("id",">=","1");
		$TestModel->setWhere("id","<=","10");
		/*******执行update业务********/
		$updateAnswer = $TestModel->update();
		var_dump($updateAnswer);
		exit;
		/*******清除Model配置********/
		$TestModel->clear('where');
		/*******设定要查询第零页******/
		$TestModel->setPage(0);
		/*******查询第0页数据(20条)**/
		$answer = $TestModel->select();
		print_r($answer);
		
		
		
		
	}
}
//本地建立user库=>user表的语句：
//sql:
/*
-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2015 
-- 服务器版本: 5.6.11
-- PHP 版本: 5.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


//!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT ;
//!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS ;
//!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION ;
//!40101 SET NAMES utf8;

--
-- 数据库: `user`
--
CREATE DATABASE IF NOT EXISTS `user` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `user`;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `age` int(11) NOT NULL,
  `country` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

//!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT ;
//!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS ;
//!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION ;
*/