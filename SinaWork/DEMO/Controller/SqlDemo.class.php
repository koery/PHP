<?php
class SqlDemo extends  ControllerBase
{
	function __construct()
	{
		parent::__construct();
	}
	/***************************************************
	* SqlDemo-insert
	* 数据库插入操作的例子
	* 相关函数:
	* $TestModel->setTable();
	* $TestModel->setKeyValues();
	* $TestModel->insert();
	* 返回结果
	*	array(2) { ["status"]=> int(0)  
	*			   ["result"]=> array(1) { ["insert_id"]=> 插入数据的insert_id }
	*	}
	*/
	public function insert()
	{
		//连接数据库
		$TestModel = new Model("124.238.233.103:3306","root","","user");
		/*******选择操作表***********/
		$TestModel->setTable('user');
		/*******设定键值对***********/
		$TestModel->setKeyValues(array(
									'name'		=>	'XuZheng',
									'age'		=>	25,
									'country'	=>	'China'));
		/********执行insert***********/
		$answer = $TestModel->insert();
		echo $TestModel->getSQL().'<br>';
		$this->writeLine();
		var_dump($answer);
	}
	/***************************************************
	* SqlDemo-select
	* 数据库查询操作的例子
	* 相关函数：
		$TestModel->setTable();
		$TestModel->setField();
		$TestModel->setWhere();  三种等价用法--->
			1：$TestModel->setWhere(array(
									array('age','>','10'),
									array('age','<','26')));
			2：$TestModel->setWhere("WHERE `age` > '10' AND `age` < '26'");
			3：$TestModel->setWhere('age','>','10');
			   $TestModel->setWhere('age','<','26');
		$TestModel->setOrder($field,$isDESC);
		$TestModel->setGroup($field)
		$TestModel->setPage();	
		$TestModel->setLimit();			
		$TestModel->select();
	  返回结果
	  array(2) {
		  ["status"]=>int(0)
		  ["result"]=>array(Row1,Row2....)
		}
		
	*/
	public function select()
	{
		//连接数据库
		$TestModel = new Model("124.238.233.103:3306","root","","user");
		/*******选择操作表***********/
		$TestModel->setTable('user');
		/*******选择字段*************/
		$TestModel->setField(array('id','name','age','country'));
		/*******设定查询条件*********/
		$TestModel->setWhere(array(
								array('age','>','10'),
								array('age','<','26')));
		/*等价Where设定方法：
		1：$TestModel->setWhere("WHERE `age` > '10' AND `age` < '26'");
		2：$TestModel->setWhere('age','>','10');
		   $TestModel->setWhere('age','<','26');
		*/
		/*******选择排序规则*********/		//setOrder($field,$isDESC)
		$TestModel->setOrder('age', true);
		$TestModel->setOrder('id' , false);
		/*******选择排GROUP规则******/		//setGroup($field)
		$TestModel->setGroup('age');
		/*******设定要查询第一页*****/		//默认第一页，对应setPage(0)
		$TestModel->setPage(0);	
		/*******设定要每页记录数*****/		//默认20，设定为0位不限记录数
		$TestModel->setLimit(5);			
		$answer = $TestModel->select();
		echo $TestModel->getSQL().'<br>';
		$this->writeLine();
		var_dump($answer);
	}
	/***************************************************
	* SqlDemo-count
	* 数据库查询操作的例子
	* 相关函数：
		$TestModel->setTable();
		$TestModel->setField();
		$TestModel->setWhere();  三种等价用法--->
			1：$TestModel->setWhere(array(
									array('age','>','10'),
									array('age','<','26')));
			2：$TestModel->setWhere("WHERE `age` > '10' AND `age` < '26'");
			3：$TestModel->setWhere('age','>','10');
			   $TestModel->setWhere('age','<','26');		
		$TestModel->count();
	  返回结果
	  array(2) {
		  ["status"]=>int(0)
		  ["result"]=>array('count'=>记录总数)
		}
		
	*/
	public function count()
	{
		//连接数据库
		$TestModel = new Model("124.238.233.103:3306","root","","user");
		/*******选择操作表***********/
		$TestModel->setTable('user');
		/*******选择字段*************/
		$TestModel->setField(array('id','name','age','country'));
		/*******设定查询条件*********/
		$TestModel->setWhere(array(
								array('age','>','10'),
								array('age','<','26')));
		/*等价Where设定方法：
		1：$TestModel->setWhere("WHERE `age` > '10' AND `age` < '26'");
		2：$TestModel->setWhere('age','>','10');
		   $TestModel->setWhere('age','<','26');
		*/	
		$answer = $TestModel->count();
		echo $TestModel->getSQL().'<br>';
		$this->writeLine();
		var_dump($answer);
	}
	/***************************************************
	* SqlDemo-update
	* 数据库插入操作的例子
	* 相关函数:
	* $TestModel->setTable();
	* $TestModel->setKeyValues();
	* $TestModel->setWhere();  三种等价用法--->
	*	1：$TestModel->setWhere(array(
	*								array('age','>','10'),
	*								array('age','<','26')));
	*	2：$TestModel->setWhere("WHERE `age` > '10' AND `age` < '26'");
	*	3：$TestModel->setWhere('age','>','10');
	*	   $TestModel->setWhere('age','<','26');
	* $TestModel->update();
	* 返回结果
	*	array(2) {
	*	  ["status"]=>int(0)
	*     ["result"]=> array(1) { ["affected_rows"]=> 影响行数 }
	*	}
	*/
	public function update()
	{
		//连接数据库
		$TestModel = new Model("124.238.233.103:3306","root","","user");
		/*******选择操作表***********/
		$TestModel->setTable('user');
		/*******设定键值对***********/
		$TestModel->setKeyValues(array(
									'name'		=>	'XuZheng',
									'age'		=>	23,
									'country'	=>	'China'));
		$TestModel->setWhere('id','<','6');
		$answer = $TestModel->update();
		echo $TestModel->getSQL().'<br>';
		$this->writeLine();
		var_dump($answer);
	}
	private function writeLine()
	{
		echo '<HR style="FILTER: alpha(opacity=100,finishopacity=0,style=1)" width="100%" color=#987cb9 SIZE=3>';
	}
	public function testSql()
	{
		
		
		
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