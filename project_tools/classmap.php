<?php

/**
*class map生成
*
*/

echo "\r\n扫描项目目录ing...\r\n";
$dirname = str_replace("\\","/",dirname(dirname(__FILE__)))."/src"; 
$map = scanFile($dirname."/application");
echo "扫描done!\r\n";

$arr = array();
foreach($map as $class_name=>$path)
{
	$info = "\"{$class_name}\" => \"{$path}\",";
	$arr[] = $info;
}
$path = implode($arr,"\r\n");

$content = getContent();
$content = str_replace("_DATA_",$path,$content);

$load_file = $dirname."/www/auto_load.php";
if(file_exists($load_file)) unlink($load_file);
if(file_put_contents($load_file,$content))
{
	echo "class map生成done---success!\r\n";
}else{
	exit("生成失败，请确认在src父级目录执行或检查此目录的读写权限---error");
}

function getContent()
{
	return '<?php
define("ROOT_PATH",str_replace("\\\","/",dirname(__FILE__)));
define("APP_PATH",str_replace("\\\","/",dirname(dirname(__FILE__)))."/application");

spl_autoload_register("loadClass");
include LIB_PATH . "Sam.php";

function loadClass($classname)
{
	$file=get($classname);
    if(empty($file))
    {
        SamLoader::loadClass($classname);
    }
	else
    {
		if(file_exists($file)) include $file;
    }
}

function get($c)
{
	$map = getMap();
	if(isset($map[$c])){
		return $map[$c];
	}else{
		return array();
	}
}

function getMap()
{
	return array(
		_DATA_
		);
}';
}

function scanFile($directory) 
{
	$arr = array();
	$mydir = dir($directory); 
	while($file = $mydir->read())
	{ 
		if((is_dir("$directory/$file")) && ($file!=".")  && ($file!="..") && ($file != "smarty") && ($file != "myinclude") && ($file != ".svn")) 
		{
			$res = scanFile("$directory/$file"); 
			$arr = array_merge($arr, $res);
		}else if(($file!=".")  && ($file!="..")){ 
			$file_path = $directory."/".$file;
			$file_info  = pathinfo($file_path);
			if($file_info['extension'] == 'php') 
			{
				$classes = getClassName($file_path);
				foreach($classes as $class)
				{
					$arr[$class] = $file_info['dirname'].'/'.$file_info['basename'];
				}
			}
		}
	} 
	$mydir->close(); 
	return $arr;
} 


function getClassName($file)
{
	$lines = file($file);
	$class = array();
	foreach($lines as $line)
	{
		if(preg_match("/^\s*[cC]lass\s+(\S+)\s*/",$line,$match)) $class[] = $match[1];
		if (preg_match("/^\s*abstract\s*class\s+(\S+)\s*/", $line, $match)) $class[] = $match[1];
		if (preg_match("/^\s*interface\s+(\S+)\s*/", $line, $match)) $class[] = $match[1];
	}
	return $class;
}
