<?php
$path = '/home';
$files = scandir($path);
print_r($files);
$temp = getfiles($path);
print_r($temp);
function getfiles($path){
	$files_temp = scandir($path);
	print_r($files_temp);
	$filepool = [];
	foreach($files_temp as $item){
		if($item != '.' && $item != '..'){
			$item = $path.'/'.$item;
			if(is_dir($item)){
				//$temp = getfiles($item);
				$filepool = array_merge($filepool,getfiles($item));
			}elseif(is_file($item)){
				$filepool[] = $item;
			}
		}			
	
	}
	return $filepool;
}

