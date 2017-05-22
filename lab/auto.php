<?php
echo 'this is auto.php';
$params = [
	'modelName' => $_POST['modelName'],
	'controllersName' => $_POST['controllersName'],
	'submitType' => $_POST['submitType'],
	'comment' => $_POST['comment'],
	'params' => $_POST['params'],
];

//参数检测

//生成控制器代码
ob_clean();
echo makeControllerCode(),"\n";
echo makeModelCode(),"\n";
echo makeConfigCode(),"\n";
function makeControllerCode()
{
	global $params;
	$controllersName = ucfirst($params['controllersName']);
	$modelName = ucfirst($params['modelName']);
	$html = <<<html
/**
 * action{$controllersName} {$params['comment']}
 */
public function action{$controllersName}()
{
	\$params = Yii::\$app->request->post();
	\$result = Yii::\$container->get('{$modelName}')->{$params['controllersName']}(\$params);
	return \$result;
}
html;
	return $html;
}


function makeModelCode(){

	global $params;

	$html = <<<html
/**
 * {$params['controllersName']} {$params['comment']}
 */
public function {$params['controllersName']}(\$params)
{
	\$appCurl = Yii::\$container->get('AppCurl');
	\$result = \$appCurl->get(['api' => '{$params['controllersName']}'], \$params);
	return \$result;  
}
html;

if($params['submitType'] == 'post')
{
	$html = <<<html
/**
 * {$params['controllersName']} {$params['comment']}
 */
public function {$params['controllersName']}(\$params)
{
	\$appCurl = Yii::\$container->get('AppCurl');
	\$result = \$appCurl->post(['api' => '{$params['controllersName']}'], \$params);
	return \$result;  
}
html;
}
	return $html;
}

function makeConfigCode(){
global $params;
$html = <<<html
//{$params['comment']}
'{$params['controllersName']}' => [
    'url' => '{#{$params["modelName"]}Api#}/{$params['modelName']}/{$params['controllersName']}'
],
html;
return $html;

}