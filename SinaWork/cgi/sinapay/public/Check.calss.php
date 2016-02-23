<?php
//校验类
class CHECK {
	function __construct()
	{
		return 1;
	}
	function __destruct()
	{
		return null;
	}
############################################################################
	//校验交易类别码
	public function Check_process($keyName, $arr, $bitmap)
	{
		//校验交易类别域
		if(!array_key_exists($keyName, $arr))
		{
			//数据包中无交易类别关键字
			return -1;
		}
		if(!$arr[$keyName])
		{
			//数据包中无交易类别报文消息域
			return -2;
		}
		if(!array_key_exists($arr[$keyName], $bitmap))
		{
			//交易类别域值有误
			return -3;
		}
		return 1;
	}
	//校验数据项
	public function Check_fields(&$arr, $bitmap)
	{
		foreach ($bitmap as $field)
		{
			$rv = $this->Check_unit($arr, $field);
			if($rv != 1)
			{
				switch ($rv)
				{
					case -6:
						return -4;
					case -5:
					case -4:
					case -3:
						return -3;
					case -2:
					case -1:
					default:
						return $rv;									
				}
			}
		}
		return 1;
	}
	//校验各消息报文域
	public function Check_unit(&$arr, $condition)
	{
		$name	= $condition['name'];
		$flag	= $condition['flag'];
		$form	= explode(",", $condition['form'], 3);
		$length	= explode(",", $condition['length'], 2);
	
		//检验必有域
		switch ($flag)
		{
			case 'M':
				if (!array_key_exists($name, $arr))
				{
					//未找到必有域
					return -1;
				}
				elseif($arr[$name] == null)
				{
					//必有域未赋值
					return -2;
				}
				else
				{
					//继续校验
				}
			case 'C':
			case 'O':
				if (isset($arr[$name]))
				{
					//检验域数据类型
					$anti_form = array_diff(array('c','i','p'), $form);
					if(in_array('c', $anti_form))
					{
						if(preg_match('/[A-Za-z]/', $arr[$name]))
						{
							return -3;
						}
					}
					if(in_array('i', $anti_form))
					{
						if(preg_match('/[0-9]/', $arr[$name]))
						{
							return -4;
						}
					}
					if(in_array('p', $anti_form))
					{
						if(preg_match('/[^A-Za-z0-9.]/', $arr[$name]))
						{
							return -5;
						}
					}

					//检验数据长度
					$min = $length[0];
					$max = $length[1];
					$len = strlen($arr[$name]);
					if($len<$min || $len>$max)
					{
						return -6;
					}
				}
				break;
			default:
				return 0;;
		}
		return 1;
	}
}