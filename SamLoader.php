<?php
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
		  'SamLoader' => '/home/qinpeng/SamPhp/SamLoader.php',
'DB' => '/home/qinpeng/SamPhp/Db/DB.php',
'DBPDO' => '/home/qinpeng/SamPhp/Db/DB.php',
'DBStatment' => '/home/qinpeng/SamPhp/Db/DB.php',
'DBException' => '/home/qinpeng/SamPhp/Db/DB.php',
'Http' => '/home/qinpeng/SamPhp/Extend/net/Http.class.php',
'Curl' => '/home/qinpeng/SamPhp/Extend/net/Curl.class.php',
'Redis' => '/home/qinpeng/SamPhp/Extend/cache/Redis.class.php',
'Memcached' => '/home/qinpeng/SamPhp/Extend/cache/Memcached.class.php',
'Apc' => '/home/qinpeng/SamPhp/Extend/cache/Apc.class.php',
'Pages' => '/home/qinpeng/SamPhp/Extend/util/Pages.class.php',
'Upload' => '/home/qinpeng/SamPhp/Extend/util/Upload.class.php',
'Verify' => '/home/qinpeng/SamPhp/Extend/util/Verify.class.php',
'Image' => '/home/qinpeng/SamPhp/Extend/util/Image.class.php',
'Xss' => '/home/qinpeng/SamPhp/Extend/util/Xss.class.php',
'Strings' => '/home/qinpeng/SamPhp/Extend/util/Strings.class.php',
'Config' => '/home/qinpeng/SamPhp/Core/Config.php',
'Web' => '/home/qinpeng/SamPhp/Core/Web.php',
'Object' => '/home/qinpeng/SamPhp/Core/Object.php',
'Cache' => '/home/qinpeng/SamPhp/Core/Cache.php',
'QException' => '/home/qinpeng/SamPhp/Core/QException.php',
'Controller' => '/home/qinpeng/SamPhp/Core/Controller.php',
'Template' => '/home/qinpeng/SamPhp/Templates/Template.php',
'DBModel' => '/home/qinpeng/SamPhp/Model/DBModel.php',
		  );
	}
}