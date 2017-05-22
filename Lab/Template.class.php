<?php
/**
 * @desc       模板引擎
 * @file_name  Template.class.php
 * @author     coco
 * @data       2015年12月2日上午10:52:56
 *
 */

class Template {

	private $compileDir;
	private $leftTag = '{#';
	private $rightTag = '#}';
	private $currentTemp = '';
	private $outputHtml;
	private $varPool = [];
	
	
	public function __construct($templateDir, $compileDir, $leftTag = null, $rightTag = null) {
		$this->templateDir = $templateDir;
		$this->compileDir = $compileDir;
		if(!empty($leftTag)) $this->leftTag = $leftTag;
		if(!empty($rightTag)) $this->rightTag = $rightTag;
	}
	
	private function setVar($key,$value){
		$this->varPool[$key] = $value;
	}
	
	private function getVar($key){
		return empty($this->varPool[$key])?'':$this->varPool[$key];
	}
	
	/**
	* @decs 外部显示接口
	* @param string $templateName 模板名称
	* @param array	$data 		  携带数据
	* @return NULL
	*/
	public function display($templateName = NULL, $data = [],$suffix = '.html'){		
        
		$templateName = empty($templateName) ? $this->currentTemp : $templateName;
		$this->getSourceTemplate($templateName);
		$this->compileTemplate($templateName);
		include_once $this->compileDir.md5($templateName).$suffix;		
	}
	
	/**
	* @decs 外部添加变量接口 关联数据组
	* @param string $key 变量名称
	* @param mixed  $value 变量值
	*/
	public function assign($key,$value){
		$this->varPool[$key] = $value;
	}
	
	/**
	* @decs 获取模板源文件路径
	* @param string $templateName
	* @param string $suffix
	*/
	private function getSourceTemplate($templateName = NULL,$suffix = '.html'){
		
		$this->currentTemp = $templateName;
		$sourceFilename = $this->templateDir.$this->currentTemp.$suffix;
		$this->outputHtml = file_get_contents($sourceFilename);
	}
	
	/**
	* @decs 获取模板目的文件路径
	* @param string $templatename
	* @param string $suffix
	*/
	private function getDestinationTemplate($templateName = NULL,$suffix = '.html'){
		
		$templateName = empty($templateName) ? $this->currentTemp : $templateName;
		
		return $this->compileDir.md5($templateName).$suffix;
	}
	

 	private function compileTemplate($templateName = NULL, $suffix = '.html') {
 		
		$pattern = '/'.preg_quote($this->leftTag);
		$pattern .= ' *\$([a-zA-Z_]\w*) *';
		$pattern .= preg_quote($this->rightTag).'/';
		$this->outputHtml = preg_replace($pattern, '<?php echo $this->getVar(\'$1\');?>', $this->outputHtml);

		$compiledFilename = $this->getDestinationTemplate($templateName);
		file_put_contents($compiledFilename, $this->outputHtml);
	}
	
	
}//end