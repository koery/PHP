<?php
/***************************************************
 * 数据库访问封装类
 * copyright:	sina
 * filepath	:	/lib/Model.class.php
 * author	:	孙一
 * email	:	sunyi3@staff.sina.com.cn
 * create	： 	2015/08/17
 * version	：	2.0
 * *************************************************
 * 数据库访问封装：
 * 1：连接数据库
 * 2：封装增、查、改的SQL调用方法
 */
class Model
{
	private $host;								//数据库服务器IP，可以指定端口
												//ex:"127.0.0.1:3306"
	private $username;
	private $password;
	private $dbname;
	////////////////////////////////////////////////////////////////////
	private $table;
	private $field 	= array();
	private $where	= array();
	private $myWhere = "";                    	//设定次这项之后，where将失效
	private $page	= 0;
	private $limit	= 20;
	private $order	= array();					//ORDER BY
	private $group	= array();					
	private $keyValue= array();
	////////////////////////////////////////////////////////////////////
	private $status		=0;						//SQL查询成功：0，失败：非0
	private $connection;
	private $result		= array();				//SQL查询结果
	private $lastSQL 	= "";
	////////////////////////////////////////////////////////////////////
	/***************************************************
	* 构造函数
	* 连接数据库
	*/
	function Model($host,$username,$password,$dbname)
	{
		$this->host		= $host;
		$this->username	= $username;
		$this->password	= $password;
		$this->dbname	= $dbname;
		$this->connect();
	}
	/***************************************************
	* 连接数据库方法
	*/
	public function connect()
	{
		$this->connection =new mysqli(
									$this->host,
									$this->username,
									$this->password,
									$this->dbname);
	}
	//////////////////////////////////////////////////////////////////////
	/***************************************************
	* 清除指定的SQL查询属性设定
	* opr：删除项目
	*/
	public function clear($opr = 'all')
	{
		switch ($opr)
		{
			case 'table' 	: $this->table 	  = null;		break;
			case 'field' 	: $this->field 	  = array();	break;
			case 'where' 	: $this->where 	  = array();	break;
			case 'page'  	: $this->page	  = 0;			break;
			case 'limit' 	: $this->limit 	  = 20;			break;
			case 'order'	: $this->order 	  = array();	break;
			case 'group'	: $this->group	  = array();	break;
			case 'keyValue' : $this->keyValue = array();	break;
			case 'all':
			default:
				$this->table 	  = null;	
				$this->field 	  = array();
				$this->where 	  = array();
				$this->page	  	  = 0;		
				$this->limit 	  = 20;		
				$this->order 	  = array();
				$this->group	  = array();
				$this->keyValue   = array();
		}
	}
	//////////////////////////////////////////////////////////////////////
	/***************************************************
	* 设定表名
	* table：表名
	*/
	public function setTable($table)
	{
		$this->table = $table;
	}
	/***************************************************
	* 设定字段
	* field：字段（数组）
	*/
	public function setField($field)
	{
		$this->field = $field;
	}
	/***************************************************
	* 设定where条件
	* 方法一:注意累计添加
	* 	field	：字段
	* 	op		：操作符（=,>,<,=,<=,>=,in.....）
	* 	value	: 值
	* 	@EXAMPLE
	* 	$this->setWhere('age','<',20)
	* 方法二：以数组形式统一添加
	*	@EXAMPLE
	* 	$this->setWhere(array(array('age','<',27),
							  array('age','>',20)));
	* 方法三：直接存入完整WHERE语句
	*/
	public function setWhere($field,$op=-1,$value="0")
	{
		switch (true)
		{
			case(is_array($field)):
			{
				$wheres = $field;
				foreach($wheres as $row)
				{
					if(!is_array($row) || !isset($row[0]) || !isset($row[1]) || !isset($row[2] ))
						return -1;
					$this->where[] = array($row[0],$row[1],addslashes($row[2]));
				}
				break;
			}
			case($op==-1):
			{
				$this->myWhere = $field;
				break;
			}
			default:
			{
				$value = addslashes($value);
				$this->where[] = array($field,$op,$value);
			}
		}
		
	}
	/***************************************************
	* 设定查询页码
	* page：页码（默认查询第0页）
	*/
	public function setPage($page)
	{
		$this->page = $page; 
	}
	/***************************************************
	* 设定查询页记录数量
	* limit：查询页记录数量（默认为20，设定为0为不限制）
	*/
	public function setLimit($limit)
	{
		$this->limit = $limit;
	}
	/***************************************************
	* 设定ORDER BY逻辑，可以累积添加
	* field	：字段
	* isDESC：排序规则（true/false）
	*/
	public function setOrder($field,$isDESC)
	{
		$this->order[] = array($field,$isDESC);
	}
	/***************************************************
	* 设定GROUP BY逻辑，可以累积添加
	* field	：字段
	*/
	public function setGroup($field)
	{
		$this->group[] = $field;
	}
	/***************************************************
	* 设定插入或修改时将用到的键值对，可以累积添加
	* key	：键
	* value	：值
	*/
	public function setKeyValue($key,$value)
	{
		$this->keyValue[$key] = addslashes($value);
	}
	/***************************************************
	* 设定插入或修改时将用到的键值对数组，可以累积添加
	* kv：array(k1=>v1, k2=>v2, .....)
	*/
	public function setKeyValues($kv)
	{
		foreach($kv as $k => $v)
			$this->keyValue[$k] = addslashes($v);
	}
	/***************************************************
	* 构造字段连接成的字符串
	*/
	private function makeField()
	{
		$field = "";
		if(count($this->field)==0)return " * ";
		for($i=0;$i<count($this->field);$i++)
		{
			$field.="`".$this->field[$i]."`".($i==count($this->field)-1?' ':',');
		}
		return $field;
	}
	/***************************************************
	* 构造条件连接成的字符串
	*/
	private function makeWhere()
	{
		$where = "";
		if($this->myWhere!="")
		{
			$where = " ".$this->myWhere." ";
			$this->myWhere="";
		}
		else
		{
			if(count($this->where)==0)return "";
			$where = " WHERE ";
			for($i=0;$i<count($this->where);$i++)
			{
				$where.=" `" . $this->where[$i][0] . "` " . $this->where[$i][1] . " '" . $this->where[$i][2] . "' "  . ($i==count($this->where)-1?' ':' AND ');
			}
		}
		$this->clear('where');
		return $where;
	}
	/***************************************************
	* 构造GROUP连接成的字符串
	*/
	private function makeGroup()
	{
		if(count($this->group)==0)return "";
		$group = " GROUP BY ";
		for($i=0;$i<count($this->group);$i++)
		{
			$group.=" `" . $this->group[$i] . "` " . ($i==count($this->group)-1?' ':' , ');
		}
		return $group;
	}
	/***************************************************
	* 构造ORDER连接成的字符串
	*/
	private function makeOrder()
	{
		if(count($this->order)==0)return "";
		$order = " ORDER BY ";
		for($i=0;$i<count($this->order);$i++)
		{
			$order.=" `" . $this->order[$i][0] . "`". ($this->order[$i][1]?' DESC':' ASC'). ($i==count($this->order)-1?' ':', ');
		}
		return $order;
	}
	/***************************************************
	* 构造LIMIT逻辑的字符串
	*/
	private function makeLimit()
	{
		if( $this->limit == 0 )return "";
		$limit = " LIMIT " . ($this->limit * $this->page) . "," . $this->limit;
		return $limit;
	}
	/***************************************************
	* 构造INSERT逻辑键值对字符串
	*/
	private function makeInsertKV()
	{
		if( count($this->keyValue) == 0 )return "";
		$InsertKV = "( ";
		$i=0;
		foreach($this->keyValue as $k => $v)
		{
			$InsertKV .= " `$k` ". ($i==count($this->keyValue)-1?')':',');
			$i++;
		}
		$InsertKV.=' values (';
		$i=0;
		foreach($this->keyValue as $k => $v)
		{
			$InsertKV .= " '$v' ". ($i==count($this->keyValue)-1?')':',');
			$i++;
		}
		return $InsertKV;
	}
	/***************************************************
	* 构造UPDATE逻辑键值对字符串
	*/
	private function makeUpdateKV()
	{
		if( count($this->keyValue) == 0 )return "";
		$UpdateKV = " SET ";
		$i=0;
		foreach($this->keyValue as $k => $v)
		{
			$UpdateKV .= " `$k` = '$v' ". ($i==count($this->keyValue)-1?'':',');
			$i++;
		}
		return $UpdateKV;
	}
	/***************************************************
	* 执行SELECT逻辑
	*/
	public function select()
	{
		$this->status = 0;
		$this->result = array();
		if(!$this->table)
		{
			$this->status=1;
		}
		else
		{
			$sql = "SELECT " . $this->makeField() . " FROM " .  $this->table . $this->makeWhere() .   $this->makeGroup() .  $this->makeOrder() . $this->makeLimit();
			$this->lastSQL = $sql;
			$result = $this->connection->query($sql);
			if ($result) 
			{
				 if($result->num_rows>0)					//判断结果集中行的数目是否大于0
				 {                                               
					  while($row =$result->fetch_array() ) 	//循环输出结果集中的记录
					  {                       
						   $oneRow = array();
						   foreach($row as $key =>$value)
						   {
							$oneRow[$key] = $value;
						   }
						   $this->result[] = $oneRow;
					  }
				 }
			}
			else 
			{
				$this->status=-1;
			}
		}
		return $this->ReturnResult();
	}
	/***************************************************
	* 执行COUNT逻辑
	*/
	public function count()
	{
		$this->status = 0;
		$this->result = array();
		if(!$this->table)
		{
			$this->status=1;
		}
		else
		{
			$sql = "SELECT COUNT(*) AS `count`,COUNT(*) AS `COUNT` FROM " .  $this->table . $this->makeWhere();
			$this->lastSQL = $sql;
			$result = $this->connection->query($sql);
			if ($result) 
			{
				 if($result->num_rows>0)					//判断结果集中行的数目是否大于0
				 {                                               
					  while($row =$result->fetch_array() ) 	//循环输出结果集中的记录
					  {                       
						   $oneRow = array();
						   foreach($row as $key =>$value)
						   {
							$oneRow[$key] = $value;
						   }
						   $this->result[] = $oneRow;
					  }
				 }
			}
			else 
			{
				$this->status=-1;
			}
		}
		return $this->ReturnResult();
	}
	/***************************************************
	* 执行INSERT逻辑
	*/
	public function insert()
	{
		$this->status = 0;
		$this->result = array();
		if(!$this->table)
		{
			$this->status=1;
		}
		else
		{
			$sql = "INSERT INTO " . $this->table . $this->makeInsertKV(); 
			$this->lastSQL = $sql;
			$result = $this->connection->query($sql);
			$this->result=array('insert_id'=>mysqli_insert_id($this->connection));
			if (!$result) 
				$this->status=-1;
		}
		return $this->ReturnResult();
	}
	/***************************************************
	* 执行UPDATE逻辑
	*/
	public function update()
	{
		$this->status = 0;
		$this->result = array();
		if(!$this->table)
		{
			$this->status=1;
		}
		else
		{
			$sql = "UPDATE " . $this->table . $this->makeUpdateKV() . $this->makeWhere(); 
			$this->lastSQL 	= $sql;
			$result = $this->connection->query($sql);
			$this->result=array('affected_rows'=>mysqli_affected_rows($this->connection));
			if (!$result) 
			{
				$this->status=-1;
				$this->result=array();
			}      			
		}
		return $this->ReturnResult();
	}
	//记录MYSQL日志
	private function mysqlLog($log)
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
		$LogType = "AUTO_MYSQL";
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
	/***************************************************
	* 返回执行结果
	* status：SQL语句是否成功执行
	* result：如果是查询类语句，存储结果数组
	*/
	private function ReturnResult()
	{
		$this->clear('all');
		$logMsg = json_encode(array(
									'sql' 	=> $this->getSQL(),
									'status'=> $this->status
								));
		$this->mysqlLog($logMsg);
		return array(
					'status'=>$this->status,	//-1：SQL查询失败；0:查询成功；1：表名未设置；
					'result'=>$this->result
				);
	}
	/***************************************************
	* 查看最近一次执行的SQL语句
	*/
	public function getSQL()
	{
		return $this->lastSQL;
	}
	/***************************************************
	* SQL查询方法
	* query：要执行的完整SQL语句
	*/
	public function HARDQUERY($query)
	{
		$this->lastSQL = $query;
		$this->result  = $this->connection->query($query);
		$this->status  = $this->result?0:-1;
		return $this->ReturnResult();
	}
}

