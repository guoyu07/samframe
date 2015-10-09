<?php
$root_dir = str_replace("\\","/",dirname(dirname(__FILE__)))."/src";

if(!file_exists($root_dir))
{
	echo "项目目录不存在，开始建立\r\n";
	if(!mkdir($root_dir)) exit("无权限操作目录，请手动建立！");
	chmod($root_dir,0775);
	mkdir($root_dir."/application");
	chmod($root_dir."/application",0775);

	mkdir($root_dir."/config");
	chmod($root_dir."/config",0775);
	file_put_contents($root_dir."/config/common.inc.php",file_get_contents('./tmp/common_conf_demo.php'));
	file_put_contents($root_dir."/config/rout.inc.php",file_get_contents('./tmp/rout_conf_demo.php'));

	mkdir($root_dir."/www");
	chmod($root_dir."/www",0775);
	file_put_contents($root_dir."/www/index.php",file_get_contents('./tmp/index.php'));
	chmod($root_dir."/www/index.php",0777);

	mkdir($root_dir."/www/cache");
	chmod($root_dir."/www/cache",0775);

	mkdir($root_dir."/application/controllers");
	chmod($root_dir."/application/controllers",0775);
	file_put_contents($root_dir."/application/controllers/IndexController.php",file_get_contents('./tmp/controller_demo.php'));

	mkdir($root_dir."/application/models");
	chmod($root_dir."/application/models",0775);

	mkdir($root_dir."/application/views");
	chmod($root_dir."/application/views",0775);

	mkdir($root_dir."/application/views/Index");
	chmod($root_dir."/application/views/Index",0775);
	file_put_contents($root_dir."/application/views/Index/demo.html",file_get_contents('./tmp/view_demo.html'));
	echo "项目目录生成done！\r\n";
	$init = true;

}
