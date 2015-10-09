<?php
defined('APP_PATH') or exit("项目目录未定义，请在入口文件定义mvc所在目录");
defined('LIB_VERION') or define('LIB_VERION','2.0');

include LIB_PATH.'/Public/common.class.php';
include LIB_PATH.'/SamLoader.php';


if(isset($argv) && !empty($argv)){
	define('RUN_MODE','cli');

	$options = getopt("m:");
	if(!empty($options['m'])){
		$_GET['method'] = $options['m'];
	}else{
		$_GET['method'] = 'index.index';
	}
}else{
	define('RUN_MODE','http');
}

if(!isset($_GET['method'])){
	$_GET['method'] = 'index.index';
}
$webapp = Object::find("Web");
try{
	$webapp->parseRoute();
	$webapp->run();
}catch(QException $e){
	echo $e->getError();
}


