<?php
/**
 * @desc       导入类
 * @file_name  Import.php
 * @author     coco
 * @date       2016年1月23日上午11:12:51
 *
 */
class Import{
	
	private $filename;
	private $charset;
	private $replace_char = ['"','\t'];
	private $replace_result = ['',''];
	
	/**
	* @todo	 构造函数 初始化filename
	* @param string $filename
	*/
	public function __construct($filename=null,$charset='UTF-8'){
		$this->filename = $filename;
		$this->charset = $charset;
	} 
	/**
	* @todo	导入接口
	*/
	public function import(){
		
		$handle = fopen($this->filename, 'r');
		$result = $this->input_csv($handle); //解析csv
		fclose($handle); //关闭指针
		$len_result = count($result);
		return $len_result?$result:null;		
	}
	
	/**
	 * @decs   读取文件数据
	 * @param  $handle 文件句柄
	 * @return arr     数组格式的文件数据
	 */
	protected function input_csv($handle) {
	
		$out = [];
		$n = 0;
		while ($data = fgetcsv($handle, 10000)) {
			$num = count($data);
			for ($i = 0; $i < $num; $i++) {
				$out[$n][$i] = $this->auto_encoding($data[$i]);
			}
			$n++;
		}
		return $out;
	}
	
	/**
	 * 自动解析编码读入文件
	 * @param string $str 字符串
	 * @param string $charset 读取编码
	 * @return string 返回读取内容
	 */
	protected function auto_encoding($str) {
		$list = array('GBK', 'UTF-8', 'UTF-16LE', 'UTF-16BE', 'ISO-8859-1');
		foreach ($list as $item) {
			$str = $this->replace($str);
			$tmp = mb_convert_encoding($str, $item, $item);
			if (md5($tmp) == md5($str)) {
				return mb_convert_encoding($str, $this->charset, $item);
			}
		}
		return "";
	}
	
	/**
	* @todo	 特殊字符替换
	* @param string $str
	*/
	private function replace($str){
		return str_replace($this->replace_char,$this->replace_result,$str);
	}
	
}