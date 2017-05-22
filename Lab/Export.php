<?php
/**
 * @desc       导出工具类(造轮子)
 * @file_name  Export.php
 * @author     coco
 * @data       2015年12月24日下午2:49:58
 *
 */

class export{
	
	private $filename;	//导出文件名称
	private $items;		//标题
	private $data;		//数据
	private $type;      //文件类型
	
	/**
	* @decs  初始化 文件名称 标题 数据
	* @param string $filename
	* @param string $items
	* @param string/arr $data
	*/
	public function __construct($filename,$items,$data,$type='csv'){
		
		$this->filename = $filename?$filename:date('Y-m-d-H-i-s',time());
		$this->items = $items;
		$this->data = $data;
		$this->type = $type;
	}
	
	
	/**
	 * 对外统一接口 / 对内调度
	 */
	public function export(){
		is_array($this->data)?$this->array_to_string($this->data):null;
		return $this->output();
	}
	
	
	/**
	* @decs 将二维数组转化为字符串
	* @param array $data
	*/
	private function array_to_string($data){
		$str = '';
		array_walk($data, function($value,$key) use(&$str){
			$str .= join(',',$value);
			$str .= "\n";
		});
		$this->data = $str;
	}
	
	/**
	* @decs 导出数据输出接口
	*/
	private function output(){
		
		$out_data = $this->items;
		$out_data .= "\n";
		$out_data .= $this->data;
		header("Content-type:text/csv");   
	    header("Content-Disposition:attachment;filename=".$this->filename.'.'.$this->type);   
	    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
	    header('Expires:0');   
	    header('Pragma:public'); 
		$this->data=iconv("UTF-8","gbk//IGNORE",$this->data);
		##mark ↓ 此处分离成导出类会多出来一行空白 待研究
		ob_clean();  
		echo $out_data;
		
	}
	
	
	
}//END