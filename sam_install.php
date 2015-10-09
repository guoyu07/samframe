<?php
$dirname= str_replace("\\","/",dirname(__FILE__));
$map = scanFile($dirname);

$arr = array();
foreach($map as $class_name=>$path)
{
	$info = "'{$class_name}' => '{$path}',";
	$arr[] = $info;
}
$path = implode($arr,"\r\n");

$content = getContent();
$content = str_replace("_DATA_",$path,$content);

if(file_put_contents($dirname."/SamLoader.php",$content))
{
	echo "map生成成功！\r\n";
}else{
	exit("生成失败，请确认在本库根目录执行或检查此目录的读写权限\r\n");
}

function getContent()
{
	return '<?php
class SamLoader
{
	static public function loadClass($class)
	{
	    $map = self::getClassMap();
	    if(file_exists($map[$class])) include $map[$class];
	}

	static private function getClassMap()
	{
	  return array(
		  _DATA_
		  );
	}
}';
}

function scanFile($directory) 
{
	$arr = array();
	$mydir = dir($directory); 
	while($file = $mydir->read())
	{
		if(in_array($file,array('phptemplate'))) continue;
		if((is_dir("$directory/$file")) && ($file!=".")  && ($file!="..") && ($file != "smarty") && ($file != "Public") && ($file != "project_tools") && ($file != ".svn") && ($file != 'Include')) 
		{
			$res = scanFile("$directory/$file"); 
			$arr = array_merge($arr, $res);
		}else if(($file!=".")  && ($file!="..")){ 
			$file_path = $directory."/".$file;
			$file_info  = pathinfo($file_path);
			if(isset($file_info['extension']) && $file_info['extension'] == 'php') 
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


