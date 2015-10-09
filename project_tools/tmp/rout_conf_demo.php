<?php

/*自定义路由开关*/
$ROUT_ON=true;

/*自定义路由分割符*/
$ROUT_SEPARATOR = '_';

/*自定义路由规则表*/
$ROUT_RULE=array(
	 'demo_$_$_$_$'=>array('index/demo','id','tid','fid','cid'),//分割符需要与上面保持一致
 );