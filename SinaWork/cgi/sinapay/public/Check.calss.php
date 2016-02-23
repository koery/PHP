<?php
//У����
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
	//У�齻�������
	public function Check_process($keyName, $arr, $bitmap)
	{
		//У�齻�������
		if(!array_key_exists($keyName, $arr))
		{
			//���ݰ����޽������ؼ���
			return -1;
		}
		if(!$arr[$keyName])
		{
			//���ݰ����޽����������Ϣ��
			return -2;
		}
		if(!array_key_exists($arr[$keyName], $bitmap))
		{
			//���������ֵ����
			return -3;
		}
		return 1;
	}
	//У��������
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
	//У�����Ϣ������
	public function Check_unit(&$arr, $condition)
	{
		$name	= $condition['name'];
		$flag	= $condition['flag'];
		$form	= explode(",", $condition['form'], 3);
		$length	= explode(",", $condition['length'], 2);
	
		//���������
		switch ($flag)
		{
			case 'M':
				if (!array_key_exists($name, $arr))
				{
					//δ�ҵ�������
					return -1;
				}
				elseif($arr[$name] == null)
				{
					//������δ��ֵ
					return -2;
				}
				else
				{
					//����У��
				}
			case 'C':
			case 'O':
				if (isset($arr[$name]))
				{
					//��������������
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

					//�������ݳ���
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