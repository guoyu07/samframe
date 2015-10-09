<?php
/**
*公共函数库
*@方便在方法中直接调用
*
*/

/**
*include自定义类
*@param $file file
*@return null
*/
function import($file)
{
	$_file = LIB_PATH.'/Extend/'.$file;
	if(file_exists($_file)){
		include $_file;
	}else{
		sys_error("Error : The file of \"".$_file."\" not exist!!!");
	}
}

/**
*cookie操作
*@param
*/
function cookie(){}

/**
*session操作
*@param
*/
function session(){}

/**
*获取DBModel类对象
*@param $table String
*@param $db_config Array
*@return object Model
*/
function DBModel($table,$db_config=array())
{
	$model = new DBModel($table,$db_config);
	return $model;
}

function DBDestroy($db_config=array())
{
	DBModel::destroy($db_config);
}
/**
*系统错误提示
*@param $msg string
*@param $is_exit boolean
*@result echo $msg or exit
*/
function sys_error($msg,$is_exit = false)
{
	if(true == $is_exit){
		exit($msg);
	}
	echo "<br><font color='red'>".$msg."</font><br>";

}


function tree($directory) 
{
	$arr = array();
	$mydir = dir($directory); 
	while($file = $mydir->read())
	{ 
		if((is_dir("$directory/$file")) && ($file!=".")  && ($file!="..") && ($file != "smarty")) 
		{
			$res = tree("$directory/$file"); 
			$arr = array_merge($arr, $res);
		}else if(($file!=".")  && ($file!="..")){ 
			$file_path = $directory."/".$file;
			$arr[] = $file_path;
		}
	} 
	$mydir->close(); 
	return $arr;
} 


