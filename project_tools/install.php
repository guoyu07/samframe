<?php
ini_set("max_execution_time", "1800");
header("Content-type: text/html; charset=utf-8"); 

$init = false;
include 'bulid_app.php';
include 'classmap.php';


if($init) echo "<font color='red'>请修改/src/www/index.php文件LIB_PATH！</font>";